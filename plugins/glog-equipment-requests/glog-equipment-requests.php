<?php
/**
 * Plugin Name: GLOG Equipment Requests
 * Description: Гостевая подборка техники и заявки на покупку.
 * Version: 1.0.3
 * Requires PHP: 7.4
 */

defined('ABSPATH') || exit;

require_once __DIR__ . '/storage.php';
require_once __DIR__ . '/admin.php';

function glog_requests_settings(): array {
    return wp_parse_args(get_option('glog_requests_settings', []), [
        'days' => 30,
        'message' => 'Заявка №{номер} принята. Менеджер свяжется с вами, чтобы подтвердить цену и условия доставки.',
        'delivery' => '',
        'returns' => '',
        'privacy' => '',
    ]);
}

const GLOG_REQUESTS_CONSENT_VERSION = '1';

function glog_requests_consent_text(): string {
    return 'Даю согласие на обработку имени, номера телефона и адреса доставки для рассмотрения заявки и связи со мной.';
}

function glog_requests_frontend(): void {
    static $loaded = false;
    if ($loaded) {
        return;
    }
    $loaded = true;

    wp_enqueue_style('glog-equipment-requests', plugins_url('requests.css', __FILE__), [], '1.0.3');
    wp_enqueue_script('glog-equipment-requests', plugins_url('requests.js', __FILE__), [], '1.0.3', true);
    add_action('wp_footer', 'glog_requests_dialog');
}

add_shortcode('glog_equipment_add', function ($atts): string {
    $atts = shortcode_atts(['id' => get_the_ID()], $atts);
    $id = absint($atts['id']);
    $post = $id ? get_post($id) : null;
    if (!$post || $post->post_type !== 'equipment' || $post->post_status !== 'publish' || $post->post_password) {
        return '';
    }

    glog_requests_frontend();
    return sprintf(
        '<button type="button" class="glog-request-add" data-glog-add="%d">Добавить в корзину</button>',
        $id
    );
});

add_shortcode('glog_equipment_cart', function (): string {
    glog_requests_frontend();
    return '<button type="button" class="glog-request-open" data-glog-open aria-haspopup="dialog">Корзина <span data-glog-count hidden></span></button>';
});

function glog_requests_dialog(): void {
    $settings = glog_requests_settings();
    ?>
    <dialog class="glog-requests-dialog" id="glog-requests-dialog" aria-labelledby="glog-requests-title"
            data-endpoint="<?php echo esc_url(add_query_arg('action', 'glog_equipment_request', admin_url('admin-ajax.php', 'relative'))); ?>">
        <header class="glog-requests-heading">
            <h2 id="glog-requests-title">Корзина</h2>
            <form id="glog-requests-close" method="dialog"><button class="glog-requests-close" type="submit" aria-label="Закрыть" autofocus>×</button></form>
        </header>
        <p data-glog-dialog-status role="status" aria-live="polite"></p>
        <p data-glog-add-error hidden><a href="<?php echo esc_url(home_url('/contacts/')); ?>">Перейти в контакты</a></p>
        <div data-glog-cart aria-live="polite"></div>
        <form id="glog-requests-checkout" data-glog-checkout hidden>
            <h3>Заявка на поставку</h3>
            <input type="hidden" name="privacy" value="<?php echo esc_attr($settings['privacy']); ?>">
            <div class="glog-requests-fields">
                <label>Имя <input name="name" autocomplete="name" placeholder="Ваше имя" maxlength="120" required></label>
                <label>Телефон <input name="phone" type="tel" autocomplete="tel" placeholder="+375" maxlength="40" required></label>
                <label class="glog-requests-address">Адрес доставки <input name="address" autocomplete="street-address" placeholder="Город, улица, дом" maxlength="300" required></label>
            </div>
            <ul class="glog-requests-policies">
                <?php foreach (glog_requests_policy_labels() as $key => $label) : ?>
                    <?php if ($key !== 'privacy' && $settings[$key]) : ?>
                        <li><a href="<?php echo esc_url($settings[$key]); ?>" target="_blank" rel="noopener"><?php echo esc_html($key === 'delivery' ? 'Условия поставки и оплаты' : $label); ?></a></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
            <div class="glog-requests-consent">
                <input id="glog-requests-consent" type="checkbox" name="consent_version"
                       value="<?php echo esc_attr(GLOG_REQUESTS_CONSENT_VERSION); ?>" required>
                <div>
                    <label for="glog-requests-consent"><?php echo esc_html(glog_requests_consent_text()); ?></label>
                    <?php if ($settings['privacy']) : ?>
                        <a href="<?php echo esc_url($settings['privacy']); ?>" target="_blank" rel="noopener">Условия обработки персональных данных</a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
        <div class="glog-requests-actions">
            <button class="btn btn-accent glog-equipment-order-button" type="submit" form="glog-requests-checkout">Отправить заявку</button>
            <button class="glog-requests-text-button" type="submit" form="glog-requests-close">Продолжить выбор</button>
        </div>
    </dialog>
    <?php
}
