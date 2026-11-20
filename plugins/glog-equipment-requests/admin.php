<?php

defined('ABSPATH') || exit;

function glog_requests_policy_labels(): array {
    return [
        'delivery' => 'Условия оплаты и доставки',
        'returns' => 'Условия возврата',
        'privacy' => 'Обработка персональных данных',
    ];
}

add_action('admin_init', function (): void {
    register_setting('glog_requests', 'glog_requests_settings', [
        'type' => 'array',
        'sanitize_callback' => function ($input): array {
            $current = glog_requests_settings();
            $days = filter_var($input['days'] ?? null, FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 1, 'max_range' => 400],
            ]);
            $message = sanitize_textarea_field($input['message'] ?? '');
            if ($days === false) {
                add_settings_error('glog_requests_settings', 'days', 'Укажите срок от 1 до 400 дней.');
            }
            if (strpos($message, '{номер}') === false) {
                add_settings_error('glog_requests_settings', 'message', 'Добавьте {номер} в сообщение для покупателя.');
                $message = $current['message'];
            }

            $settings = [
                'days' => $days === false ? $current['days'] : $days,
                'message' => $message,
                'price_notice' => sanitize_textarea_field($input['price_notice'] ?? $current['price_notice']),
            ];
            foreach (glog_requests_policy_labels() as $key => $label) {
                $url = esc_url_raw($input[$key] ?? '', ['http', 'https']);
                $parts = wp_parse_url($url);
                $settings[$key] = in_array($parts['scheme'] ?? '', ['http', 'https'], true)
                    && !empty($parts['host']) && !isset($parts['user']) ? $url : '';
                if (!empty($input[$key]) && !$settings[$key]) {
                    add_settings_error('glog_requests_settings', $key, 'Проверьте адрес страницы «' . $label . '».');
                    $settings[$key] = $current[$key];
                }
            }
            return $settings;
        },
    ]);
});

add_action('admin_menu', function (): void {
    add_menu_page('Заявки на технику', 'Заявки на технику', 'manage_options', 'glog-requests', 'glog_requests_admin', 'dashicons-clipboard');
    add_submenu_page('glog-requests', 'Настройки заявок', 'Настройки', 'manage_options', 'glog-requests-settings', 'glog_requests_admin_settings');
});

function glog_requests_admin_settings(): void {
    if (!current_user_can('manage_options')) {
        wp_die('Недостаточно прав.');
    }

    $settings = glog_requests_settings();
    ?>
    <div class="wrap">
        <h1>Настройки заявок на технику</h1>
        <?php settings_errors(); ?>
        <form action="options.php" method="post">
            <?php settings_fields('glog_requests'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="glog-days">Срок хранения подборки, дней</label></th>
                    <td><input id="glog-days" type="number" required min="1" max="400"
                               name="glog_requests_settings[days]" value="<?php echo esc_attr($settings['days']); ?>"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="glog-message">Сообщение после отправки</label></th>
                    <td>
                        <textarea id="glog-message" class="large-text" required name="glog_requests_settings[message]" rows="3"><?php echo esc_textarea($settings['message']); ?></textarea>
                        <p class="description">Используйте <code>{номер}</code> для подстановки номера заявки.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="glog-price-notice">Сноска к цене на странице техники</label></th>
                    <td>
                        <textarea id="glog-price-notice" class="large-text" name="glog_requests_settings[price_notice]" rows="5"><?php echo esc_textarea($settings['price_notice']); ?></textarea>
                        <p class="description">Показывается под кнопкой и ссылками. Оставьте поле пустым, чтобы скрыть сноску.</p>
                    </td>
                </tr>
                <?php foreach (glog_requests_policy_labels() as $key => $label) : ?>
                    <tr>
                        <th scope="row"><label for="glog-<?php echo esc_attr($key); ?>"><?php echo esc_html($label); ?></label></th>
                        <td><input id="glog-<?php echo esc_attr($key); ?>" class="regular-text" type="url"
                                   name="glog_requests_settings[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr($settings[$key]); ?>"></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function glog_requests_admin(): void {
    global $wpdb;
    if (!current_user_can('manage_options')) {
        wp_die('Недостаточно прав.');
    }

    $table = glog_requests_table();
    $id = absint($_GET['request'] ?? 0);
    echo '<div class="wrap"><h1>Заявки на технику</h1>';
    if ($id) {
        $row = $wpdb->get_row($wpdb->prepare("SELECT id, submitted_at, request FROM {$table} WHERE id = %d AND submitted_at > 0", $id));
        glog_requests_admin_detail($row);
    } else {
        $page = max(1, absint($_GET['paged'] ?? 1));
        $per_page = 25;
        $total = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$table} WHERE submitted_at > 0");
        $rows = $wpdb->get_results($wpdb->prepare(
            "SELECT id, submitted_at, request FROM {$table} WHERE submitted_at > 0 ORDER BY submitted_at DESC, id DESC LIMIT %d OFFSET %d",
            $per_page,
            ($page - 1) * $per_page
        ));
        ?>
        <table class="widefat striped">
            <thead><tr><th>Номер</th><th>Получена</th><th>Имя</th><th>Расчётная сумма</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $row) : $request = json_decode($row->request, true); ?>
                <tr>
                    <td><a href="<?php echo esc_url(add_query_arg('request', $row->id, menu_page_url('glog-requests', false))); ?>">№<?php echo esc_html($row->id); ?></a></td>
                    <td><?php echo esc_html(wp_date('d.m.Y H:i', (int) $row->submitted_at)); ?></td>
                    <td><?php echo esc_html($request['customer']['name']); ?></td>
                    <td><?php echo esc_html(glog_requests_admin_price($request['total'])); ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$rows) : ?><tr><td colspan="4">Заявок на этой странице нет.</td></tr><?php endif; ?>
            </tbody>
        </table>
        <?php
        echo wp_kses_post(paginate_links([
            'base' => add_query_arg('paged', '%#%', menu_page_url('glog-requests', false)),
            'format' => '',
            'current' => $page,
            'total' => (int) ceil($total / $per_page),
        ]));
    }
    echo '</div>';
}

function glog_requests_admin_price(?int $value): string {
    return $value === null ? 'Уточняется' : number_format($value, 0, ',', ' ') . ' руб.';
}

function glog_requests_admin_detail($row): void {
    ?>
    <p><a href="<?php echo esc_url(menu_page_url('glog-requests', false)); ?>">К списку заявок</a></p>
    <?php if (!$row) : ?>
        <p>Заявка не найдена.</p>
        <?php return; ?>
    <?php endif; ?>
    <?php $request = json_decode($row->request, true); ?>
    <h2>Заявка №<?php echo esc_html($row->id); ?></h2>
    <p><?php echo esc_html(wp_date('d.m.Y H:i', (int) $row->submitted_at)); ?></p>
    <dl>
        <dt>Имя</dt><dd><?php echo esc_html($request['customer']['name']); ?></dd>
        <dt>Телефон</dt><dd><?php echo esc_html($request['customer']['phone']); ?></dd>
        <dt>Адрес доставки</dt><dd><?php echo esc_html($request['customer']['address']); ?></dd>
    </dl>
    <table class="widefat striped">
        <thead><tr><th>Позиция</th><th>Код</th><th>Количество</th><th>Расчётная цена за ед.</th></tr></thead>
        <tbody>
        <?php foreach ($request['items'] as $item) : ?>
            <tr>
                <td><a href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['title']); ?></a></td>
                <td><?php echo esc_html($item['code']); ?></td>
                <td><?php echo esc_html($item['quantity']); ?></td>
                <td><?php echo esc_html(glog_requests_admin_price($item['unit_price'])); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <p><strong>Расчётная сумма:</strong> <?php echo esc_html(glog_requests_admin_price($request['total'])); ?></p>
    <p>
        <?php if (!empty($request['privacy']['url'])) : ?>
            <a href="<?php echo esc_url($request['privacy']['url']); ?>">Условия обработки данных</a>.
        <?php endif; ?>
        <?php echo esc_html($request['privacy']['notice'] ?? ''); ?>
    </p>
    <?php if (!empty($request['privacy']['consented_at'])) : ?>
        <p>Согласие получено <?php echo esc_html(wp_date('d.m.Y H:i', (int) $request['privacy']['consented_at'])); ?>.
            Версия текста <?php echo esc_html($request['privacy']['consent_version']); ?>.</p>
    <?php endif; ?>
    <?php
}
