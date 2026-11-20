<script>
    jQuery(function ($) {
        $('.hero-slider').owlCarousel({
            items: 1,
            margin: 0,
            loop: false,
            rewind: true,
            nav: false,
            dots: true,
            autoplay: false,
            autoHeight: true,
            smartSpeed: 600
        });
    });
</script>
<div class="hero-slider owl-carousel">
    <?php foreach ($args['list'] as $slide): ?>
        <div class="section position-relative ps-0" hero>
            <div class="floating-banner">
                <img
                    src="<?php echo esc_url($slide['image']); ?>"
                    alt="<?php echo esc_attr($slide['title']); ?>"
                    class="img-fluid"
                    style="border-top-right-radius: 30em; border-bottom-right-radius: 30em;"
                    width="1920"
                    height="1020"
                >
            </div>

            <div class="r-container ps-4 ps-xl-0" herotext>
                <div class="row row-cols-lg-2 row-cols-1 align-items-center">
                    <div class="col"></div>

                    <div class="col ps-4 pe-0">
                        <div class="d-flex flex-column gap-4">
                            <h1 class="fw-semibold">
                                <?php echo esc_html($slide['title']); ?>
                            </h1>

                            <p>
                                <?php echo esc_html($slide['description']); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>