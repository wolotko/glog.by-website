<div class="section position-relative" text>
    <div class="r-container">
        <div class="row">
            <div class="col">
                <?php if( $args['title'] != '' ): ?>
                    <h3 class="fw-semibold pb-4"><?php echo $args['title']; ?></h3>
                <?php endif; ?>
                <?php echo $args['content']; ?>
            </div>
        </div>
    </div>
</div>