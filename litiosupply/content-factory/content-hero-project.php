<div class="section">
    <div class="r-container">
        <div class="d-flex flex-column gap-4">
            <div class="mb-5 position-relative">
                <img src="<?php echo $args['image']; ?>" class="img-fluid rounded-4">
                <div class="floating-bottom linear-gradient rounded-4 text-white p-5">
                    <div class="d-flex flex-xl-row flex-md-row flex-column gap-5">
                        <?php foreach($args['list'] as $list) : ?>
                            <div class="col">
                                <h4><?php echo $list['title']; ?></h4>
                                <h5><?php echo $list['value']; ?></h5>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>