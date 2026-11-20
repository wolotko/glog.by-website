<div class="section" id="contacts">
    <div class="r-container">
        <div class="d-flex justify-content-center">
            <div class="row row-cols-xl-2 row-cols-1 d-flex align-items-center1">
                <div class="col mb-3">
                    <div class="d-flex flex-column gap-3 h-100 justify-content-center pe-xl-5">
                        <div class="d-flex flex-column gap-3">

                            <h3 class="fw-semibold"><?php echo $args['title']; ?></h3>
                            <p class="text-color-2"><?php echo $args['descriotion']; ?></p>
                        </div>
                        <?php if($args['email'] && $args['phone'] ) : ?>
                            <hr>
                            <div class="d-flex flex-column gap-3">
                                <div class="row row-cols-xl-2 row-cols-1">
                                    <div class="col mb-3">
                                        <span class="d-flex flex-row gap-3">
                                            <div class="contact-item">
                                                <i class="fa-solid fa-2xs fa-envelope"></i>
                                            </div>
                                            <a href="mailto:<?php echo $args['email']; ?>"><?php echo $args['email']; ?></a>
                                        </span>
                                    </div>
                                    <div class="col mb-3">
                                        <span class="d-flex flex-row gap-3">
                                            <div class="contact-item">
                                                <i class="fa-solid fa-2xs fa-phone"></i>
                                            </div>
                                            <a href="tel:<?php echo $args['phone']; ?>"><?php echo $args['phone']; ?></a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col mb-3">
                    <div class="d-flex flex-column gap-4 px-lg-4 px-0">
                        <div class="position-relative">
                            <div class="w-100">
                                <div>
                                    <?php if($args['post_id'] == 69):  ?>
                                        <?php echo do_shortcode('[contact-form-7 id="72" html_class="d-flex flex-column h-100 justify-content-center w-100 form"]'); ?>
                                    <?php else : ?>
                                        <?php echo do_shortcode('[contact-form-7 id="54" html_class="d-flex flex-column h-100 justify-content-center w-100 form"]'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>