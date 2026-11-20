<?php 

    $paged = max(1, (int) get_query_var('paged'));
    
    $tr_type  = rawurldecode((string) get_query_var('trucks_type'));
    $tr_brand = rawurldecode((string) get_query_var('trucks_brand'));
    $tr_year  = rawurldecode((string) get_query_var('trucks_year'));
    
    $tr_type  = sanitize_text_field(trim($tr_type));
    $tr_brand = sanitize_text_field(trim($tr_brand));
    $tr_year  = sanitize_text_field(trim($tr_year));
    
    // нормализация маркеров any-*
    if (strtolower($tr_type) === 'any-type') :
    	$tr_type = '';
    endif;
    
    if (strtolower($tr_brand) === 'any-brand') :
    	$tr_brand = '';
    endif;
    
    if (strtolower($tr_year) === 'any-year') :
    	$tr_year = '';
    endif;
    
    // единое правило: "-" и "0" считаем пустым
    foreach (['tr_type', 'tr_brand', 'tr_year'] as $k) :
    	if (!isset($$k)) :
    		continue;
    	endif;
    
    	if ($$k === '-' || $$k === '0') :
    		$$k = '';
    	endif;
    endforeach;
    
    // сид меняется раз в день по локальному времени WP
    $seed = (int) current_time('Ymd'); // например 20251224
    
    $orderby_cb = function ($orderby, $query) use ($seed) {
        // применяем ТОЛЬКО к нужному запросу
        if ((int) $query->get('daily_rand') !== 1) {
            return $orderby;
        }
    
        // RAND(seed) фиксирует "случайный" порядок внутри дня
        return "RAND({$seed})";
    };
    
    add_filter('posts_orderby', $orderby_cb, 10, 2);
    
    $meta_query = array('relation' => 'AND');

    if ($tr_type !== '') :
    	$meta_query[] = array('key' => 'truck_type', 'value' => $tr_type);
    endif;
    
    if ($tr_brand !== '') :
    	$meta_query[] = array('key' => 'truck_brand', 'value' => $tr_brand);
    endif;
    
    if ($tr_year !== '') :
    	$meta_query[] = array('key' => 'truck_year', 'value' => $tr_year);
    endif;
    
    $args = [
    	'post_type'      => 'trucks',
    	'post_status'    => 'publish',
    	'posts_per_page' => 12,
    	'paged'          => $paged,
    	'daily_rand'     => 1,
    ];
    
    if (count($meta_query) > 1) :
    	$args['meta_query'] = $meta_query;
    endif;
    
    $q = new WP_Query($args);

    
    remove_filter('posts_orderby', $orderby_cb, 10);
    
?>
<?php get_header('', array( 'type' => 'text' )); ?>   

    <section class="text-head">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <?php
                        $type_labels = [
                        	'tractors'     => 'Тягачи',
                        	'semitrailers' => 'Полуприцепы',
                        	'vans'         => 'Фургоны',
                        ];
                        
                        $h1_parts = [];
                        
                        if ($tr_type !== '' && isset($type_labels[$tr_type])) :
                        	$h1_parts[] = $type_labels[$tr_type];
                        endif;
                        
                        // нормализуем бренд: slug → реальный label
                        if ($tr_brand !== '') :
                        	$facets = $facets ?? trucks_get_filters_facets();
                        	foreach ($facets['brands'] as $b) :
                        		if (sanitize_title($b) === sanitize_title($tr_brand)) :
                        			$tr_brand = $b;
                        			break;
                        		endif;
                        	endforeach;
                        endif;
                        
                        if ($tr_brand !== '') :
                    	$h1_parts[] = $tr_brand;
                    endif;
                        
                        if ($tr_year !== '') :
                        	$h1_parts[] = $tr_year . ' г.в.';
                        endif;
                        
                        // если фильтров нет — базовый заголовок
                        $h1 = !empty($h1_parts)
                        	? implode(' ', $h1_parts)
                        	: 'Спецтехника и оборудование  от компании GLOG';
                        
                        // пагинация
                        if ($paged > 1) :
                        	$h1 .= ' — страница ' . (int) $paged;
                        endif;
                    ?>
                    <h1><?php echo esc_html($h1); ?></h1>
                    <?php /*
                    <ul class="breadcrumbs title-top">
                        <li><a href="<?php echo home_url('/'); ?>">Главная</a></li>
                        <?php if($paged > 1) : ?>
                            <li><a href="<?php echo home_url('/trucks/'); ?>">LITIO TRUCK</a></li>
                            <li><span>Страница <?php echo $paged; ?></span></li>
                        <?php else : ?>
                            <li><span>LITIO TRUCK</span></li>
                        <?php endif; ?>
                    </ul>
                    */ ?>
                </div>
            </div>
        </div>
    </section>
    
    
        <section class="cars2 section-padding" trucks>
            <div class="container">
                <?php /*
                <div class="row">
                    <div class="col-lg-12 col-md-12 mb-30">
                        <div class="sidebar-list mt-0">
                            <div class="item">
                                <div id="bt-24-option-form" class="form-place-bt-script-form">
                                    <h4>Получить предложение по покупке</h4>
                                    <script data-b24-form="inline/45/1jni2h" data-skip-moving="true">
                                        (function(w,d,u){
                                        var s=d.createElement('script');s.async=true;s.src=u+'?'+(Date.now()/180000|0);
                                        var h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
                                        })(window,document,'https://cdn-ru.bitrix24.by/b28582662/crm/form/loader_45.js');
                                    </script>
                                </div>
                                <div id="bt-24-option-form-leasing" class="form-place-bt-script-form">
                                    <h3></h3>
                                    <script data-b24-form="inline/11/uippoh" data-skip-moving="true">
                                        (function(w,d,u){
                                        var s=d.createElement('script');s.async=true;s.src=u+'?'+(Date.now()/180000|0);
                                        var h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
                                        })(window,document,'https://cdn-ru.bitrix24.by/b28582662/crm/form/loader_11.js');
                                    </script>
                                </div>
                                <div class="stock-control-container" trucks-control>
                                    <?php 
                                        $facets = trucks_get_filters_facets(); 
                                        $sel_type  = $tr_type;
                                        $sel_brand = $tr_brand;
                                        $sel_year  = $tr_year;

                                    ?>
                                    <div>
                                        <h3 class="mb-0">Выберите технику</h3>
                                        <div class="filter-radio-group">
                                            <select id="truck_type">
                                                <option value="0">Тип техники</option>
                                                <?php foreach ($facets['types'] as $type) : ?>
                                                    <option value="<?php echo $type['value']; ?>" <?php selected($sel_type, $type['value']); ?>><?php echo $type['label']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <select id="truck_brand">
                                                <option value="0">Бренд</option>
                                                <?php foreach ($facets['brands'] as $brand) : ?>
                                                    <option value="<?php echo $brand; ?>" <?php selected($sel_brand, $brand); ?>><?php echo $brand; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <select id="truck_year">
                                                <option value="0">Год выпуска</option>
                                                <?php foreach ($facets['years'] as $year) : ?>
                                                    <option value="<?php echo $year; ?>" <?php selected($sel_year, $year); ?>><?php echo $year; ?></option>
                                                <?php endforeach; ?>											
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mt-30 stock-control-container-form">
                                        <h3 class="mb-30">Нужна консультация?</h3>
                                        <a href="<?php echo home_url('/contacts/') ?>" class="button-3">Нужна консультация?</a>
                                        <div class="options-form options-form-stock">
                                            <h3 class="mb-30">Нужна консультация?</h3>
                                            <p>Будем рады предоставить вам дополнительную информацию. Пожалуйста, пришлите ваш вопрос, заполнив форму обратной связи.</p>
                                            <script data-b24-form="inline/75/3nuqdi" data-skip-moving="true">
                                            (function(w,d,u){
                                            var s=d.createElement('script');s.async=true;s.src=u+'?'+(Date.now()/180000|0);
                                            var h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
                                            })(window,document,'https://cdn-ru.bitrix24.by/b36850972/crm/form/loader_75.js');
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                */ ?>
            <?php if ($q->have_posts()) : ?>                    
                <div class="row" id="trucks-results">
                    <?php while ($q->have_posts()) : $q->the_post(); ?>
                        <div class="col-lg-6 col-md-12 mb-30 cars-stock-item">
                            <div class="item" data-source-id="<?php echo get_field('truck_id'); ?>" data-car-id="<?php echo get_the_ID(); ?>" data-brand="<?php echo get_field('truck_brand'); ?>" data-model="<?php echo get_field('truck_model'); ?>">
                                <figure>
                                    <img src="<?php echo get_field('truck_photos')[0]['photo_link']; ?>"
                                         alt="Купить <?php echo get_field('truck_brand'); ?> <?php echo get_field('truck_model'); ?> с доставкой в Беларусь"
                                         class="img-fluid">
                                </figure>
                    
                                <div class="content">
                                    <div class="cont">
                    
                                        <div class="stock-price">
                                            <h3>
                                                <span><?php echo get_field('truck_brand'); ?> <?php echo get_field('truck_model'); ?></span>
                                                <span>
                                                    <?php 
                                                        $truck_type = get_field('truck_type'); 
                                                        $truck_price = get_field('truck_price'); 
                                                    ?>
                                                    <?php if( $truck_type != 'vans' ) : ?>
                                                        Цена на СВХ в Беларуси
                                                    <?php else : ?>
                                                        Цена указана с НДС
                                                    <?php endif; ?>
                                                </span>
                                            </h3>
                    
                                            <div class="price-wrap">
                                                <?php 
                                                    switch ($truck_type) :
                                                        case "tractors":
                                                            $price_euro = (($truck_price * 1.05) + 9000) + 5500;
                                                            break;
                                                        case "semitrailers":
                                                            $price_euro = ($truck_price * 1.05) + 10000;
                                                            break;
                                                        case "vans":
                                                            $price_euro = (((($truck_price * 1.05) + 6500) * 1.1) + 14300) *1.2;
                                                            break;
                                                        default: 
                                                            $price_euro = $truck_price * 1.2;
                                                    endswitch;
                                                    
                                                    
                                                    $exrates_eur_rate = (float) get_field('exrates_eur_rate', 'option');
                                                     
                                                    $price_byn = $price_euro * $exrates_eur_rate;
                                                ?>
                                                <?php if( in_array($truck_type, array('tractors', 'vans') ) && $price_euro > 99999 ) :  ?>
                                                    <i closed>цена по запросу</i><i>&nbsp;</i>
                                                <?php elseif( in_array($truck_type, array('semitrailers') ) && $price_euro > 79999 ) : ?>
                                                    <i closed>цена по запросу</i><i>&nbsp;</i>
                                                <?php else : ?>
                                                    <i><?php echo number_format($price_byn, 0, ',', ' '); ?> руб.</i><i><?php echo number_format($price_euro, 0, ',', ' '); ?> &euro;</i>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                    
                                        <div class="basic-options-list">
                                            <?php if( get_field('truck_year') ) : ?>
                                                <div class="basic-options-row">
                                                    <div>Год выпуска</div>
                                                    <i></i>
                                                    <div><?php echo get_field('truck_year'); ?></div>
                                                </div>
                                            <?php endif; ?>
                                            <?php if( get_field('truck_mileage') ) : ?>
                                                <div class="basic-options-row">
                                                    <div>Пробег</div>
                                                    <i></i>
                                                    <div><?php echo get_field('truck_mileage'); ?></div>
                                                </div>
                                            <?php endif; ?>
                                            <?php if( get_field('truck_fuel') ) : ?>
                                                <div class="basic-options-row">
                                                    <div>Топливо</div>
                                                    <i></i>
                                                    <div><?php echo get_field('truck_fuel')['label']; ?></div>
                                                </div>
                                            <?php endif; ?>
                                            <?php if( get_field('truck_engine_power_hp') ) : ?>
                                                <div class="basic-options-row">
                                                    <div>Мощность</div>
                                                    <i></i>
                                                    <div><?php echo get_field('truck_engine_power_hp'); ?> л.с. <?php if( get_field('truck_engine_power_kw') ) : ?>(<?php echo get_field('truck_engine_power_kw'); ?> кВт)<?php endif; ?></div>
                                                </div>
                                            <?php endif; ?>
                                            <?php if( get_field('truck_transmission') ) : ?>
                                                <div class="basic-options-row">
                                                    <div>Трансмиссия</div>
                                                    <i></i>
                                                    <div><?php echo get_field('truck_transmission')['label']; ?></div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                    
                                        <div class="book mt-30">
                                            <a href="<?php echo home_url('/contacts/'); ?>"
                                                   class="button-3 open-stock-form open-stock-form-want-car"
                                                   data-source-id="<?php echo get_field('truck_id'); ?>" 
                                                   data-car-id="<?php echo get_the_ID(); ?>" 
                                                   data-brand="<?php echo get_field('truck_brand'); ?>" 
                                                   data-model="<?php echo get_field('truck_model'); ?>"
                                                   data-equipment="<?php echo get_field('truck_id'); ?>">
                                                <?php if( in_array($truck_type, array('tractors', 'vans') ) && $price_euro > 99999 ) :  ?>
                                                    Запросить цену
                                                <?php elseif( in_array($truck_type, array('semitrailers') ) && $price_euro > 79999 ) : ?>
                                                    Запросить цену
                                                <?php else : ?>
                                                    Забронировать
                                                <?php endif; ?>
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
                                "base"      => str_replace(999999999, "%#%", get_pagenum_link(999999999)),
                                "format"    => "",
                                "current"   => max(1, $paged),
                                "total"     => $q->max_num_pages,
                                "prev_text"    => "<i class='ti-angle-left'></i>",
            	                "next_text"    => "<i class='ti-angle-right'></i>",
            	                "type"      => 'list',
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
    <?php
    $facets = trucks_get_filters_facets();
    
    $brand_map = array();
    foreach ($facets['brands'] as $b) :
    	// ключ: slug (iveco / man / schmitz-cargobull)
    	$brand_map[sanitize_title($b)] = $b; // значение: реальный бренд (Iveco / MAN / Schmitz Cargobull)
    endforeach;
    ?>
    
    <script>
    window.TRUCKS_FILTERS = {
    	ajaxUrl: '<?php echo esc_js(admin_url('admin-ajax.php')); ?>',
    	nonce: '<?php echo esc_js(wp_create_nonce('trucks_filter_nonce')); ?>',
    	brandMap: <?php echo wp_json_encode($brand_map); ?>
    };
    </script>
    
    <script src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/js/trucks-filters-ajax.js?v=10012"></script>
    
<?php get_footer(); ?>