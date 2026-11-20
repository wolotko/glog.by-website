<div class="section" hero-w-form>
    <div class="r-container">
        <div class="d-flex justify-content-center">
            <div class="row row-cols-xl-2 row-cols-1 d-flex align-items-center">
                <div class="col mb-3 col-23">
                    <div class="d-flex flex-column gap-3 h-100 justify-content-center pe-xl-5" imagebox>
                        <img src="<?php echo $args['photo'] ?>" width="1000" height="1000" alt="Фотография менеджера" />
                    </div>
                </div>
                <div class="col mb-3" formwrapper>
                    <div class="d-flex flex-column gap-4 px-lg-4 px-0">
                        <div class="position-relative">
                            <div class="w-100">
                                <div>
                                    <?php echo do_shortcode('[contact-form-7 id="195" html_class="d-flex flex-column h-100 justify-content-center w-100 form"]'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>