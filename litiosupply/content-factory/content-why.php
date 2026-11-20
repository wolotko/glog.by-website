<div class="section pb-0">
    <div class="r-container">
        <?php if($args['title'] != '' ) : ?>
            <h3 class="fw-semibold text-center mb-5"><?php echo $args['title']; ?></h3>
        <?php endif; ?>
        <div class="d-flex flex-column gap-5 align-items-center">
            <div class="row row-cols-xl-3 row-cols-1 align-items-stretch">
                <?php foreach($args['why_list'] as $reason) : ?>
                    <div class="col pb-5">
                        <div class="card card-outline-hover gap-3 align-items-center text-center p-5 h-100">
                            <?php if($reason['icon'] != 'NONE' ) : ?>
                                <div class="mb-3">
                                    <?php echo file_get_contents(get_template_directory() . '/image/icons/'. $reason['icon'] .'.svg'); ?>
                                </div>
                            <?php endif; ?>
                            <h4 class="text-color"><?php echo $reason['title']; ?></h4>
                            <?php if($reason['description'] != '') : ?>
                                <p class="font-1 text-color-2">
                                    <?php echo $reason['description']; ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>