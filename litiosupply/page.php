<?php get_header(); ?>

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <?php 
                                
            $sections = get_field( 'factory' );
    
            if ( $sections ) :
                foreach ( $sections as $section ) :
                    $section['post_id'] = get_the_ID();
                    get_template_part( 'content-factory/content-' . $section['acf_fc_layout'], '', $section );
                endforeach;
            endif;
        
        ?>
    <?php endwhile; endif; ?>




<?php get_footer(); ?>