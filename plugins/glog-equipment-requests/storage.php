<?php

defined('ABSPATH') || exit;

const GLOG_REQUESTS_COOKIE = 'glog_equipment_cart';
const GLOG_REQUESTS_MAX_LINES = 100;
const GLOG_REQUESTS_MAX_QUANTITY = 99;

function glog_requests_table(): string {
    global $wpdb;
    return $wpdb->prefix . 'glog_equipment_requests';
}

function glog_requests_activate(): void {
    global $wpdb;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    $table = glog_requests_table();
    $charset = $wpdb->get_charset_collate();
    dbDelta("CREATE TABLE {$table} (
        id bigint unsigned NOT NULL AUTO_INCREMENT,
        token char(64) NOT NULL,
        revision bigint unsigned NOT NULL DEFAULT 1,
        expires_at bigint unsigned NOT NULL,
        submitted_at bigint unsigned NOT NULL DEFAULT 0,
        cart longtext NOT NULL,
        request longtext NULL,
        PRIMARY KEY  (id),
        UNIQUE KEY token (token),
        KEY expiry (submitted_at,expires_at)
    ) {$charset};");
    if (!wp_next_scheduled('glog_requests_cleanup')) {
        wp_schedule_event(time(), 'daily', 'glog_requests_cleanup');
    }
}

register_activation_hook(__DIR__ . '/glog-equipment-requests.php', 'glog_requests_activate');
register_deactivation_hook(__DIR__ . '/glog-equipment-requests.php', function (): void {
    wp_clear_scheduled_hook('glog_requests_cleanup');
});

add_action('glog_requests_cleanup', function (): void {
    global $wpdb;
    $table = glog_requests_table();
    $wpdb->query($wpdb->prepare("DELETE FROM {$table} WHERE submitted_at = 0 AND expires_at <= %d", time()));

});

function glog_requests_error(string $message, int $status): void {
    wp_send_json_error(['message' => $message], $status);
}

function glog_requests_product(int $id, int $quantity, float $rate): ?array {
    $post = get_post($id);
    if (!$post || $post->post_type !== 'equipment' || $post->post_status !== 'publish' || $post->post_password) {
        return null;
    }

    $title = function_exists('get_field') ? get_field('sharing_title', $id) : '';
    $price = function_exists('get_field') ? (int) get_field('equipment_price', $id) : 0;
    $unit_price = $price > 0 && $rate > 0 ? (int) round($price * $rate) : null;

    return [
        'product_id' => $id,
        'source_id' => function_exists('get_field') ? (string) get_field('truck_id', $id) : '',
        'code' => sprintf('GLG-1%05d', $id),
        'title' => wp_strip_all_tags($title ?: get_the_title($post)),
        'url' => get_permalink($post),
        'quantity' => $quantity,
        'unit_price' => $unit_price,
    ];
}

function glog_requests_snapshot(array $cart, int $revision): array {
    $rate = function_exists('get_field') ? (float) get_field('exrates_eur_rate', 'option') : 0;
    $items = [];
    $unavailable = [];
    $count = 0;
    $total = 0;
    $has_unknown_price = false;
    foreach ($cart as $id => $quantity) {
        $item = glog_requests_product((int) $id, (int) $quantity, $rate);
        if (!$item) {
            $unavailable[] = ['product_id' => (int) $id, 'code' => sprintf('GLG-1%05d', $id)];
            continue;
        }
        $items[] = $item;
        $count += $item['quantity'];
        if ($item['unit_price'] === null) {
            $has_unknown_price = true;
        } else {
            $total += $item['unit_price'] * $item['quantity'];
        }
    }

    $snapshot = [
        'items' => $items,
        'unavailable' => $unavailable,
        'count' => $count,
        'total' => $has_unknown_price ? null : $total,
        'currency' => 'BYN',
        'revision' => $revision,
    ];
    $snapshot['quote'] = hash('sha256', wp_json_encode($snapshot));
    return $snapshot;
}

function glog_requests_cookie(string $token, int $expiry): void {
    setcookie(GLOG_REQUESTS_COOKIE, $token, [
        'expires' => $expiry,
        'path' => '/',
        'secure' => is_ssl(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

function glog_requests_confirmation(int $id): array {
    return [
        'number' => $id,
        'message' => str_replace('{номер}', (string) $id, glog_requests_settings()['message']),
    ];
}

function glog_requests_same_origin(): bool {
    if (($_SERVER['HTTP_X_GLOG_REQUEST'] ?? '') !== '1' || ($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        return false;
    }

    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
    if (!$origin) {
        return true;
    }

    $expected = wp_parse_url(home_url());
    $actual = wp_parse_url($origin);
    return ($actual['scheme'] ?? '') === ($expected['scheme'] ?? '')
        && ($actual['host'] ?? '') === ($expected['host'] ?? '')
        && ($actual['port'] ?? null) === ($expected['port'] ?? null);
}

function glog_requests_ajax(): void {
    global $wpdb;
    nocache_headers();
    if (!glog_requests_same_origin()) {
        glog_requests_error('Запрос отклонён.', 403);
    }

    $body = file_get_contents('php://input', false, null, 0, 8193);
    if ($body === false || strlen($body) > 8192) {
        glog_requests_error('Слишком большой запрос.', 413);
    }
    $input = json_decode($body, true);
    if (!is_array($input)) {
        glog_requests_error('Неверный запрос.', 400);
    }
    $operation = $input['operation'] ?? '';
    if (!in_array($operation, ['read', 'add', 'quantity', 'submit'], true)) {
        glog_requests_error('Неизвестное действие.', 400);
    }

    $table = glog_requests_table();
    $token = $_COOKIE[GLOG_REQUESTS_COOKIE] ?? '';
    $row = null;
    $now = time();
    if (is_string($token) && preg_match('/^[a-f0-9]{64}$/D', $token)) {
        $row = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$table} WHERE token = %s AND expires_at > %d",
            hash('sha256', $token),
            $now
        ));
    }
    if ($wpdb->last_error) {
        glog_requests_error('Подборка временно недоступна.', 503);
    }

    if ($operation === 'submit' && $row && $row->submitted_at) {
        wp_send_json_success(glog_requests_confirmation((int) $row->id));
    }

    $active = $row && !$row->submitted_at ? $row : null;
    $cart = $active ? (array) json_decode($active->cart, true) : [];
    $revision = $active ? (int) $active->revision : 0;
    if ($operation === 'read') {
        wp_send_json_success(glog_requests_snapshot($cart, $revision));
    }

    if ($operation === 'submit') {
        glog_requests_submit($active, $cart, $revision, $input, $now);
    }

    $id = filter_var($input['product_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if (!$id) {
        glog_requests_error('Неверная позиция.', 400);
    }
    if ($operation === 'add') {
        if (!glog_requests_product($id, 1, 0)) {
            glog_requests_error('Эта позиция недоступна.', 404);
        }
        if (($cart[$id] ?? 0) >= GLOG_REQUESTS_MAX_QUANTITY) {
            glog_requests_error('Достигнуто максимальное количество для этой позиции.', 400);
        }
        if (!isset($cart[$id]) && count($cart) >= GLOG_REQUESTS_MAX_LINES) {
            glog_requests_error('Подборка заполнена.', 400);
        }
        $cart[$id] = ($cart[$id] ?? 0) + 1;
    } else {
        $quantity = filter_var($input['quantity'] ?? null, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 0, 'max_range' => GLOG_REQUESTS_MAX_QUANTITY],
        ]);
        if (!$active || !isset($cart[$id]) || $quantity === false || ($input['revision'] ?? null) !== $revision) {
            glog_requests_error('Обновите подборку и повторите действие.', 409);
        }
        if ($quantity === 0) {
            unset($cart[$id]);
        } else {
            $cart[$id] = $quantity;
        }
    }

    $expires = $now + glog_requests_settings()['days'] * DAY_IN_SECONDS;
    $values = ['cart' => wp_json_encode($cart), 'revision' => $revision + 1, 'expires_at' => $expires];
    if ($active) {
        $saved = $wpdb->update($table, $values, ['id' => $active->id, 'revision' => $revision, 'submitted_at' => 0]);
    } else {
        $token = bin2hex(random_bytes(32));
        $saved = $wpdb->insert($table, ['token' => hash('sha256', $token)] + $values);
    }

    if ($saved === false) {
        glog_requests_error('Не удалось сохранить подборку. Повторите попытку.', 503);
    }
    if (!$saved) {
        glog_requests_error('Подборка изменилась. Проверьте её и повторите действие.', 409);
    }

    glog_requests_cookie($token, $expires);
    wp_send_json_success(glog_requests_snapshot($cart, $revision + 1));
}

function glog_requests_submit($active, array $cart, int $revision, array $input, int $now): void {
    global $wpdb;
    $snapshot = glog_requests_snapshot($cart, $revision);
    if (!$active || !$snapshot['items'] || $snapshot['unavailable'] || ($input['quote'] ?? '') !== $snapshot['quote']) {
        glog_requests_error('Подборка или цены изменились. Проверьте позиции и отправьте заявку ещё раз.', 409);
    }

    $customer = [];
    foreach (['name' => 120, 'phone' => 40, 'address' => 300] as $field => $limit) {
        $value = $input[$field] ?? '';
        $value = is_string($value) ? sanitize_text_field($value) : '';
        if ($value === '' || strlen($value) > $limit * 4 || preg_match_all('/./us', $value) > $limit) {
            glog_requests_error('Проверьте имя, телефон и адрес доставки.', 400);
        }
        $customer[$field] = $value;
    }
    $digits = strlen(preg_replace('/[^0-9]/', '', $customer['phone']));
    if ($digits < 7 || $digits > 15) {
        glog_requests_error('Проверьте номер телефона.', 400);
    }

    $privacy = glog_requests_settings()['privacy'];
    if (!$privacy || ($input['privacy'] ?? '') !== $privacy) {
        glog_requests_error('Условия обработки данных изменились или ещё не опубликованы. Обновите страницу перед отправкой заявки.', 409);
    }

    $request = [
        'version' => 1,
        'customer' => $customer,
        'items' => $snapshot['items'],
        'total' => $snapshot['total'],
        'currency' => $snapshot['currency'],
        'privacy' => ['notice' => glog_requests_disclosure(), 'url' => $privacy],
    ];
    $table = glog_requests_table();
    $saved = $wpdb->update($table, [
        'request' => wp_json_encode($request),
        'submitted_at' => $now,
        'revision' => $revision + 1,
        'cart' => '[]',
    ], ['id' => $active->id, 'revision' => $revision, 'submitted_at' => 0]);

    if ($saved === false) {
        glog_requests_error('Не удалось сохранить заявку. Повторите попытку.', 503);
    }
    if (!$saved) {
        $submitted = $wpdb->get_var($wpdb->prepare("SELECT submitted_at FROM {$table} WHERE id = %d", $active->id));
        if (!$submitted) {
            glog_requests_error('Подборка изменилась. Проверьте её и повторите отправку.', 409);
        }
    }
    wp_send_json_success(glog_requests_confirmation((int) $active->id));
}

add_action('wp_ajax_glog_equipment_request', 'glog_requests_ajax');
add_action('wp_ajax_nopriv_glog_equipment_request', 'glog_requests_ajax');
