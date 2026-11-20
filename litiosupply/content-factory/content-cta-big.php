<div class="section position-relative bg-attach-fixed"
    style="background-image: url(<?php echo $args['image']; ?>);">
    <div class="image-overlay"></div>
    <div class="r-container position-relative py-5" style="z-index: 2;">
        <div class="d-flex flex-column mx-auto text-center align-items-center text-white gap-4"
            style="max-width: 1000px;">
            <h3 class="font-1 text-white"><?php echo $args['title']; ?></h3>
            <p class="col-9 col-lg-9"><?php echo $args['subtitle']; ?></p>
            <div>
                <a href="#contacts" class="btn btn-accent px-5 py-3"><?php echo $args['btn_name']; ?></a>
            </div>
        </div>
    </div>
</div>