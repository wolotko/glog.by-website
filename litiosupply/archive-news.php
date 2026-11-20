<?php get_header(); ?>

    <?php
        $paged = max(1, get_query_var('paged'), get_query_var('page'));
        $title = $paged > 1 ? 'Новости — страница ' . $paged : 'Новости';
    ?>
    <div class="section position-relative pb-0" smallhero>
        <div class="r-container">
            <div class="row">
                <div class="col">
                    <h1 class="fw-semibold"><?php echo $title; ?></h1>
                </div>
            </div>
        </div>
    </div>
    
    <?php if ( have_posts() ) : ?>
        <div class="section" newssection>
            <div class="r-container">
                <div class="row row-cols-lg-2 row-cols-1">
                    <?php while ( have_posts() ) : the_post(); ?>
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
                            <a href="<?php the_permalink(); ?>" class="btn btn-accent px-5 py-3">Подробнее</a>
                        </div>
                    <?php endwhile; ?>
                    <?php the_posts_pagination(); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
<?php get_footer(); ?>