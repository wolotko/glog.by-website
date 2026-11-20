<?php
$current_id = get_the_ID();

$args_q = [
    'post_type'      => 'news',
    'posts_per_page' => 2,
    'post__not_in'   => is_singular('news') ? [$current_id] : [],
];

$query = new WP_Query($args_q);
?>

<?php if ($query->have_posts()) : ?>
    <div class="section" newssection>
        <div class="r-container">
            <?php if($args['title'] != '' ) : ?>
                <h3 class="fw-bold">
                    <?php echo $args['title']; ?>
                </h3>
            <?php endif; ?>
            <?php if($args['subtitle'] != '' ) : ?>
                <p>
                    <?php echo $args['subtitle']; ?>
                </p>
            <?php endif; ?>
            <div class="row row-cols-lg-2 row-cols-1">
                
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <div class="col col-lg-6 mb-5">
                        <a href="<?php the_permalink(); ?>">
                            <img src="<?php echo get_field('sharing_image'); ?>" alt="<?php echo get_field('sharing_description'); ?>" class="img-fluid rounded-4" width="1200" height="630">
                        </a>

                        <h3 class="fw-bold">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h3>

                        <p><?php echo get_field('sharing_lead'); ?></p>

                        <a href="<?php the_permalink(); ?>" class="btn btn-accent px-5 py-3">
                            Подробнее
                        </a>
                    </div>
                <?php endwhile; ?>

            </div>
        </div>
    </div>
<?php endif; ?>

<?php wp_reset_postdata(); ?>