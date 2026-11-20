<?php
/**
 * Plugin Name: GLOG Equipment Requests
 * Description: Гостевая подборка техники и заявки на покупку.
 * Version: 1.0.10
 * Requires PHP: 7.4
 */

defined('ABSPATH') || exit;

require_once __DIR__ . '/storage.php';
require_once __DIR__ . '/admin.php';

function glog_requests_settings(): array {
    return wp_parse_args(get_option('glog_requests_settings', []), [
        'days' => 30,
        'message' => 'Заявка №{номер} принята. Менеджер свяжется с вами, чтобы подтвердить цену и условия доставки.',
        'price_notice' => 'ООО „ЛитиоТранс“ не осуществляет розничную и оптовую торговлю техникой с собственного склада. Компания оказывает услуги логистического агента по поиску, сопровождению сделки и доставке спецтехники под заказ для юридических лиц и ИП. Указанные цены носят справочный характер, не являются публичной офертой (ст. 407 ГК РБ) и используются для предварительного расчета стоимости услуг поставки.',
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

    wp_enqueue_style('glog-equipment-requests', plugins_url('requests.css', __FILE__), [], '1.0.10');
    wp_enqueue_script('glog-equipment-requests', plugins_url('requests.js', __FILE__), [], '1.0.10', true);
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

add_shortcode('glog_equipment_cart', function ($atts): string {
    glog_requests_frontend();
    $atts = shortcode_atts(['icon' => '0'], $atts);
    if ($atts['icon'] === '1') {
        return '<button type="button" class="glog-request-open glog-request-open-icon" data-glog-open
                    aria-haspopup="dialog" aria-controls="glog-requests-dialog" title="Корзина">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M2.5 3.5h2l2.6 11.1a1 1 0 0 0 1 .8h9.6a1 1 0 0 0 1-.8l1.6-7.1H5.4"/>
                        <circle cx="9" cy="19.5" r="1.1"/>
                        <circle cx="17" cy="19.5" r="1.1"/>
                    </svg>
                    <span class="visually-hidden">Корзина</span>
                    <span class="glog-request-count" data-glog-count hidden></span>
                </button>';
    }
    return '<button type="button" class="glog-request-open" data-glog-open aria-haspopup="dialog">Корзина <span data-glog-count hidden></span></button>';
});

function glog_requests_dialog(): void {
    $settings = glog_requests_settings();
    ?>
    <dialog class="glog-requests-dialog" id="glog-requests-dialog" aria-labelledby="glog-requests-title"
            data-endpoint="<?php echo esc_url(add_query_arg('action', 'glog_equipment_request', admin_url('admin-ajax.php', 'relative'))); ?>">
        <header class="glog-requests-heading">
            <h2 id="glog-requests-title">Корзина</h2>
            <form id="glog-requests-close" method="dialog"><button class="glog-requests-close" type="submit" aria-label="Закрыть" autofocus></button></form>
        </header>
        <p data-glog-dialog-status role="status" aria-live="polite"></p>
        <p data-glog-add-error hidden><a href="<?php echo esc_url(home_url('/contacts/')); ?>">Перейти в контакты</a></p>
        <div data-glog-cart aria-live="polite"></div>
        <form id="glog-requests-checkout" data-glog-checkout hidden>
            <h3>Заявка на поставку</h3>
            <input type="hidden" name="privacy" value="<?php echo esc_attr($settings['privacy']); ?>">
            <div class="glog-requests-fields">
                <div>
                    <label for="glog-requests-name">Имя</label>
                    <input id="glog-requests-name" name="name" autocomplete="name" placeholder="Ваше имя" maxlength="120"
                           aria-describedby="glog-requests-name-error" data-required-message="Укажите имя." required>
                    <span class="glog-requests-field-error" id="glog-requests-name-error" hidden></span>
                </div>
                <div>
                    <label for="glog-requests-phone">Телефон</label>
                    <input id="glog-requests-phone" name="phone" type="tel" autocomplete="tel" placeholder="+375" maxlength="40"
                           aria-describedby="glog-requests-phone-error" data-required-message="Укажите номер телефона." required>
                    <span class="glog-requests-field-error" id="glog-requests-phone-error" hidden></span>
                </div>
                <div class="glog-requests-address">
                    <label for="glog-requests-address">Адрес доставки</label>
                    <input id="glog-requests-address" name="address" autocomplete="street-address" placeholder="Город, улица, дом" maxlength="300"
                           aria-describedby="glog-requests-address-error" data-required-message="Укажите адрес доставки." required>
                    <span class="glog-requests-field-error" id="glog-requests-address-error" hidden></span>
                </div>
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
                       value="<?php echo esc_attr(GLOG_REQUESTS_CONSENT_VERSION); ?>"
                       aria-describedby="glog-requests-consent-error" data-required-message="Подтвердите согласие на обработку данных." required>
                <div>
                    <label for="glog-requests-consent"><?php echo esc_html(glog_requests_consent_text()); ?></label>
                    <?php if ($settings['privacy']) : ?>
                        <a href="<?php echo esc_url($settings['privacy']); ?>" target="_blank" rel="noopener">Условия обработки персональных данных</a>
                    <?php endif; ?>
                    <span class="glog-requests-field-error" id="glog-requests-consent-error" hidden></span>
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
