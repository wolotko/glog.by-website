<?php get_header('', array( 'type' => 'text' )); ?>   
<?php
    global $wp_query;
    $paged = max(1, (int) get_query_var('paged'));
    get_header('', array('type' => 'text'));
?>
    <section class="text-head">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <?php
                        
                        // если фильтров нет — базовый заголовок
                        $h1 = 'Спецтехника и оборудование  от компании GLOG';
                        
                        // пагинация
                        if ($paged > 1) :
                        	$h1 .= ' — страница ' . (int) $paged;
                        endif;
                    ?>
                    <h1><?php echo esc_html($h1); ?></h1>
                </div>
            </div>
        </div>
    </section>
    
    
        <section class="cars2 section-padding" trucks>
            <div class="container">
           
            <?php if (have_posts()) : ?>                    
                <div class="row" id="trucks-results">
                    <?php while (have_posts()) : the_post(); ?>
                        <div class="col-lg-6 col-md-12 mb-30 cars-stock-item">
                            <div class="item">
                                <figure>
                                    <img src="<?php echo get_field('equipment_photos')[0]['photo']; ?>"
                                         alt="Купить <?php echo get_field('sharing_title'); ?> с доставкой в Беларусь"
                                         class="img-fluid">
                                </figure>
                    
                                <div class="content">
                                    <div class="cont">
                    
                                        <div class="stock-price">
                                            <h3>
                                                <span><?php echo get_field('sharing_title'); ?></span>
                                                <span>
                                                    
                                                </span>
                                            </h3>
                    
                                            <div class="price-wrap">
                                                <?php 
                                                    $equipment_price = (int) get_field('equipment_price');
                            
                                                    $exrates_eur_rate = (float) get_field('exrates_eur_rate', 'option');
                                                    $exrates_usd_rate = (float) get_field('exrates_usd_rate', 'option');
                                                     
                                                    $price_byn = $equipment_price * $exrates_eur_rate;
                                                    $price_usd = $price_byn / $exrates_usd_rate;
                                                ?>
                                                <i><?php echo number_format($price_byn, 0, ',', ' '); ?> руб.</i><i>Расчетная цена</i><?php /* <i><?php echo number_format($equipment_price, 0, ',', ' '); ?> &euro;</i> */ ?>
                                            </div>
                                        </div>
                    
                                        <div class="basic-options-list" list>
                                            <?php foreach(get_field('equipment_tech_info') as $info) : ?>
                                                <?php if($info['show']) : ?>
                                                    <div class="basic-options-row">
                                                        <div><?php echo $info['label']; ?></div>
                                                        <i></i>
                                                        <div><?php echo $info['value']; ?></div>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                    
                                        <div class="book mt-30">
                                            <a href="<?php echo home_url('/contacts/'); ?>"
                                                   class="button-3"
                                                   data-source-id="<?php echo get_field('truck_id'); ?>">
                                                Запросить цену
                                            </a>
                                                
                    
                                            <a href="<?php the_permalink();; ?>"class="button-3">Подробнее</a>
                                        </div>
                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center" id="trucks-pagination">
                        <?php 
                            echo paginate_links([
                                'base'      => str_replace(
                                    999999999,
                                    '%#%',
                                    get_pagenum_link(999999999, false)
                                ),
                                'current'   => $paged,
                                'total'     => (int) $wp_query->max_num_pages,
                                'prev_text' => "&larr;",
                                'next_text' => "&rarr;",
                                'type'      => 'list',
                            ]);
                        ?>
                    </div>
                </div>
            <?php else : ?>
                <div class="col-12"><div class="trucks-empty">Ничего не найдено.</div></div>
            <?php endif; ?>
            </div>
        </section>
    
    
    <script>
        var link_to_form = 0;
        window.addEventListener('b24:form:init', (event) => {            
            var form = event.detail.object;    
            if(form.identification.id == 45)
            {
                link_to_form = form;   
            }
        });
        
        
        
        function change_bt24_form(brand, model, equipment)
        {

            console.log(link_to_form.identification.id);
            
            if (link_to_form.identification.id == 45) {
                console.log(brand);
                console.log(model);
                console.log(equipment);
                link_to_form.setProperty('brand', brand);
                link_to_form.setProperty('model', model);
                link_to_form.setProperty('equipment', equipment);
            }


        }
        
        
        document.addEventListener("DOMContentLoaded",function(){
            for(let i=0; i < document.querySelectorAll(".open-stock-form-want-car").length; i++) {
                document.querySelectorAll(".open-stock-form-want-car")[i].addEventListener('click', (event) => 
                {
                    let equipment = event.currentTarget.getAttribute('data-equipment');
                    let model = event.currentTarget.getAttribute('data-model');
                    let brand = event.currentTarget.getAttribute('data-brand') + ', ID авто в источнике: ' + event.currentTarget.getAttribute('data-source-id');
                    console.log('fired!');
                    change_bt24_form(brand, model, equipment); 
                });
            }
        });




        
        

    </script>

    
<?php get_footer(); ?>