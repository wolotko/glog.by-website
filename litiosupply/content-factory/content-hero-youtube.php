<div class="section position-relative ps-0" hero>
    <div class="floating-banner">
        <img src="<?php echo $args['image']; ?>" alt="<?php echo $args['title']; ?>" class="img-fluid" style="border-top-right-radius: 30em; border-bottom-right-radius: 30em;" width="1920" height="1020">
    </div>
    <div class="r-container ps-4 ps-xl-0" herotext>
        <div class="row row-cols-lg-2 row-cols-1 align-items-center">
            <div class="col">
            </div>
            <div class="col ps-4 pe-0">
                <div class="d-flex flex-column gap-4">
                    <h1 class="fw-semibold"><?php echo $args['title']; ?></h1>
                    <p>
                        <?php echo $args['description']; ?>
                    </p>
                    <div class="d-flex flex-row gap-4">
                        <?php if($args['btn'] != ''):  ?>
                            <a href="#contacts" form class="btn btn-accent px-5 py-3"><?php echo $args['btn']; ?></a>
                        <?php endif; ?>
                        <?php if($args['youtube'] != ''):  ?>
                            <button type="button" class="request-loader" data-bs-toggle="modal" data-bs-target="#e119">
                                <i class="fa-solid fa-play ms-1"></i>
                            </button>
                            <div class="modal fade" id="e119" tabindex="-1" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content bg-dark-color">
                                        <iframe class="ifr-video" src="https://www.youtube.com/embed/<?php echo $args['youtube']; ?>?autoplay=1"></iframe>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>