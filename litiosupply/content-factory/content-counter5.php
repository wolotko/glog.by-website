<div class="section">
    <div class="r-container">
        <div class="row row-cols-xl-5 row-cols-sm-2 row-cols-1 text-center">
            <?php for ($i = 1; $i <= 5; $i++) : ?>
                <?php $c = $args['c_'. $i]; ?>
                <div class="col mb-3">
                    <h2 class="fw-bold accent-color-2 outline"><?php echo $c['value']; ?></h2>
                    <h4><?php echo $c['opisanie']; ?></h4>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</div>