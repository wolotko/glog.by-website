<div class="section">
    <div class="r-container">
        <div class="d-flex flex-column gap-5 align-items-center">
            <div class="row row-cols-xl-4 row-cols-sm-2 row-cols-1 align-items-stretch">
                <?php foreach($args['why_list'] as $reason) : ?>
                    <div class="col pb-5">
                        <a href="<?php echo $reason['link']; ?>" class="card card-outline-hover gap-3 align-items-center text-center p-4 h-100">
                            <h4 class="text-color"><?php echo $reason['title']; ?></h4>
                            <p class="font-1 text-color-2">
                                <?php echo $reason['description']; ?>
                            </p>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            <div>
                <a href="<?php echo $args['btn_link']; ?>" class="btn btn-accent px-5 py-3 rounded-pill"><?php echo $args['btn_name']; ?></a>
            </div>
        </div>
    </div>
</div>