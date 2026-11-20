<section textlist>
    <div class="r-container">
        <div list>
            <?php foreach( $args['list'] as $item ) : ?>
                <div item>
                    <span title><?php echo $item['title']; ?></span>
                    <div value><?php echo $item['value']; ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>