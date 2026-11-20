<div class="section _bg-accent-6" testimonials>
    <div class="r-container">
        <div class="d-flex justify-content-center">
            <div class="row row-cols-xl-2 row-cols-1 w-100 overflow-hidden ">
                <div class="col col-xl-5 d-flex align-items-center text-center text-xl-start  p-5">
                    <div class="d-flex flex-column gap-3 mx-auto mb-0 align-items-center align-items-xl-start h-100">

                        <h3 class="fw-semibold"><?php echo $args['title']; ?></h3>

                    </div>
                </div>
                <div class="col col-xl-7 col-12 d-flex align-items-center bg-accent rounded-4">
                    <div class="overflow-hidden">
                        <div class="swiper swiperTestimonials">
                            <!-- Additional required wrapper -->
                            <div class="swiper-wrapper">
                                <!-- Slides -->
                                <?php foreach( $args['reviews'] as $r ) : ?>
                                    <div class="swiper-slide">
                                        <div class="d-flex flex-column gap-3 p-5">
                                            <div class="d-flex flex-row gap-3 align-items-center">
                                                <div class="customer-item">
                                                    <img src="<?php echo $r['logo']; ?>"
                                                        class="img-fluid border-light rounded-circle" alt="<?php echo $r['name']; ?>"
                                                        width="60">
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <h5><?php echo $r['name']; ?></h5>
                                                </div>
                                            </div>
                                            <span style="font-size: 18px;"><?php echo $r['review']; ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <!-- If we need pagination -->
                            <div class="swiper-pagination d-flex justify-content-xl-start justify-content-center ps-xl-5 ps-0"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>