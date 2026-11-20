<?php get_header('', array( 'type' => 'text' )); ?>

<?php /*
    <section class="text-head">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1><?php the_title(); ?></h1>
                    <ul class="breadcrumbs title-top">
                        <li><a href="<?php echo home_url('/'); ?>">Главная</a></li>
                        <li><a href="<?php echo home_url('/trucks/'); ?>">Грузовики</a></li>
                        <li><span><?php the_title(); ?></span></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
 */ ?>   
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    
        
        <section trucks-photo-info>
            <div class="container">
                <h1><?php echo get_field('truck_brand'); ?> <?php echo get_field('truck_model'); ?></h1>
            </div>
            <div class="container">
                <?php $truck_photos = get_field('truck_photos'); ?>
                <?php if($truck_photos) : ?>
                        <div trucks-photos>
                            <div class="owl-carousel">
                                <?php $i = 1; foreach($truck_photos as $truck_photo) : ?>
                                    <?php if ( $i > 15 ) break; ?>
                                	<div class="item">
                                		<div trucks-photo>
                                		    <img src="<?php echo $truck_photo['photo_link']; ?>" alt="<?php echo get_field('truck_brand'); ?> <?php echo get_field('truck_model'); ?> – фотография <?php echo $i; ?>" title="<?php echo get_field('truck_brand'); ?> <?php echo get_field('truck_model'); ?> – фотография <?php echo $i; ?>" />
                                		</div>
                                	</div>
                            	<?php $i++; endforeach; ?>
                            </div>
                        </div>
                    
                <?php endif; ?>
                <div trucks-info>
                    
                    <?php 
                        $truck_type = get_field('truck_type'); 
                        $truck_price = (int) get_field('truck_price');
                        // tractors | vans | semitrailers
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
                        $exrates_usd_rate = (float) get_field('exrates_usd_rate', 'option');
                         
                        $price_byn = $price_euro * $exrates_eur_rate;
                        $price_usd = $price_byn / $exrates_usd_rate;
                    ?>
                    <?php /*
                    <div price>
                        <span byn><?php echo number_format($price_byn, 0, ',', ' '); ?> руб.</span>
                        <span euro><?php echo number_format($price_euro, 0, ',', ' '); ?> &euro; / <?php echo number_format($price_usd, 0, ',', ' '); ?> $</span>
                    </div>
                    */ ?>
                    <div class="basic-options-list">
                        
                        
                            
                        <?php $hide_price = false; ?>
                        
                        <?php if( ( in_array($truck_type, array('tractors', 'vans') ) && $price_euro > 99999 ) or ( in_array($truck_type, array('semitrailers') ) && $price_euro > 79999 ) ) :  ?>
                            <?php $hide_price = true; ?>
                            <div class="basic-options-row">
                                <div><span byn>
                                        Цена
                                    </span></div>
                                <i></i>
                                <div>
                                    по запросу
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="basic-options-row">
                                <div><span byn>
                                        <?php echo number_format($price_byn, 0, ',', ' '); ?> руб.
                                    </span></div>
                                <i></i>
                                <div>
                                    <?php echo number_format($price_euro, 0, ',', ' '); ?> &euro; /
                                    <?php echo number_format($price_usd, 0, ',', ' '); ?> $
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if( !$hide_price ) : ?>
                            <?php if( $truck_type != 'vans' ) : ?>
                                <div class="basic-options-row">
                                    <div>Цена</div>
                                    <i></i>
                                    <div>на <span title="Склад временного хранения">СВХ</span> в Беларуси</div>
                                </div>
                            <?php else : ?>
                                <div class="basic-options-row">
                                    <div>Цена</div>
                                    <i></i>
                                    <div>указана с НДС</div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <div truck-cta mobile>
                            <a href="#form" btn>Бесплатная консультация</a>
                        </div>
                        
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
                    
                    <div truck-cta>
                        <h2>Купить <?php echo get_field('truck_brand'); ?> <?php echo get_field('truck_model'); ?> с доставкой в Беларусь</h2>
                        
                        <p>Оставьте заявку для бесплатной консультации и расчёта предложения или позвоните нам: <a href="tel:+375333981414">+375 (33) 398-1414</a></p>
                        
                        <a href="#form" btn>Бесплатная консультация</a>
                        
                    </div>
                </div>
            </div>
        </section>
        
        <?php
            $tech = get_field('truck_tech_info');
            $func = get_field('truck_functions');
            
            $tech = array_values(array_filter($tech, function ($row) {
            	return ! empty($row['label']) && ! empty($row['value']);
            }));
            
            $func = array_values(array_filter($func, function ($row) {
            	return ! empty($row['value']);
            }));
            
            $tech_count = is_array($tech) ? count($tech) : 0;
            $func_count = is_array($func) ? count($func) : 0;
            
            /* truck_tech_info: 2 колонки, лишнее всегда в первой */
            $tech_chunks = [];
            
            if ( $tech_count ):
            	$half = (int) ceil($tech_count / 2);
            
            	$tech_chunks = [
            		array_slice($tech, 0, $half),
            		array_slice($tech, $half),
            	];
            endif;
            
            /* truck_functions: 3 колонки, лишнее уходит в первую, затем во вторую */
            $func_chunks = [];
            
            if ( $func_count ):
            	$base = (int) floor($func_count / 3);
            	$rem  = $func_count % 3;
            
            	$size1 = $base + ( $rem >= 1 ? 1 : 0 );
            	$size2 = $base + ( $rem >= 2 ? 1 : 0 );
            	$size3 = $base;
            
            	$func_chunks = [
            		array_slice($func, 0, $size1),
            		array_slice($func, $size1, $size2),
            		array_slice($func, $size1 + $size2, $size3),
            	];
            endif;
        ?>
        
        <section track-tech>
            <div class="container">
                <h3>Технические данные <?php echo get_field('truck_brand'); ?> <?php echo get_field('truck_model'); ?></h3>
            </div>
            <div class="container">
                <div>
                    <?php foreach( $tech_chunks[0] as $tech ) : ?>
                        <div class="basic-options-list">
                            <div class="basic-options-row">
                                <div><?php echo $tech['label']; ?></div>
                                <i></i>
                                <div><?php echo $tech['value']; ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div>
                    <?php foreach( $tech_chunks[1] as $tech ) : ?>
                        <div class="basic-options-list">
                            <div class="basic-options-row">
                                <div><?php echo $tech['label']; ?></div>
                                <i></i>
                                <div><?php echo $tech['value']; ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        
        <section track-func>
            <div class="container">
                <h3>Оборудование и функции <?php echo get_field('truck_brand'); ?> <?php echo get_field('truck_model'); ?></h3>
            </div>
            <div class="container">
                <div>
                    <?php foreach( $func_chunks[0] as $tech ) : ?>
                        <div class="basic-options-list">
                            <div class="basic-options-row">
                                <div>
                                    <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 7L9.00004 18L3.99994 13" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                                <div><?php echo $tech['value']; ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div>
                    <?php foreach( $func_chunks[1] as $tech ) : ?>
                        <div class="basic-options-list">
                            <div class="basic-options-row">
                                <div>
                                    <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 7L9.00004 18L3.99994 13" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                                <div><?php echo $tech['value']; ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div>
                    <?php foreach( $func_chunks[2] as $tech ) : ?>
                        <div class="basic-options-list">
                            <div class="basic-options-row">
                                <div>
                                    <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 7L9.00004 18L3.99994 13" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                                <div><?php echo $tech['value']; ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        
        <section post class="post">
            <div class="container">
                <?php echo get_field('truck_description'); ?>
            </div>
        </section>
        
        <section class="post section-padding" id="form">
            <div class="section container">
                <div class="row">
                    <!-- Comment -->
                    <div class="col-lg-6 col-md-12">
                        <div>
                            <div class="cont">
                                <h3>Хочу заказать <?php echo get_field('truck_brand'); ?> <?php echo get_field('truck_model'); ?>!</h3>
                                <p>Оставьте заявку для бесплатной консультации и расчёта предложения или позвоните нам: <a href="tel:+375333981414">+375 (33) 398-1414</a></p>
                            </div>
                        </div>
                    </div>
                    <!-- Contact Form -->
                    <div class="col-lg-5 offset-lg-1 col-md-12">
                        <div class="form-box simple-crm-form-box">
                            <?php /*
                            <form method="post" class="row">
                                <div class="col-md-12">
                                    <input type="text" name="name" id="name" placeholder="Ваше имя" required="">
                                </div>
                                <div class="col-md-12">
                                    <input type="tel"  placeholder="Ваш телефон" required="">
                                </div>
                                <div class="col-md-12">
                                    <textarea name="message" id="message" cols="40" rows="4" placeholder="Комментарий"></textarea>
                                </div>
                                <div class="col-md-12 text-end">
                                    <input name="submit" type="submit" value="Отправить">
                                </div>
                            </form>*/ ?>
                            <script data-b24-form="inline/45/1jni2h" data-skip-moving="true">
                                (function(w,d,u){
                                var s=d.createElement('script');s.async=true;s.src=u+'?'+(Date.now()/180000|0);
                                var h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
                                })(window,document,'https://cdn-ru.bitrix24.by/b28582662/crm/form/loader_45.js');
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        
        <?php
            $p_id = get_the_ID();
            
            $brand = (string) get_field('truck_brand', $p_id);
            $model = (string) get_field('truck_model', $p_id);
            $year  = get_field('truck_year', $p_id);
            $mile  = get_field('truck_mileage', $p_id);
            
            $fuel  = get_field('truck_fuel', $p_id);
            $trans = get_field('truck_transmission', $p_id);
            
            $hp = get_field('truck_engine_power_hp', $p_id);
            $kw = get_field('truck_engine_power_kw', $p_id);
            
            /* цены приходят из твоего расчёта выше */
            if ( isset($price_euro) ) :
            	$price_euro = (int) $price_euro;
            endif;
            
            if ( isset($price_byn) ) :
            	$price_byn = (int) $price_byn;
            endif;
            
            if ( isset($price_usd) ) :
            	$price_usd = (int) $price_usd;
            endif;
            
            /* image */
            $photos = get_field('truck_photos', $p_id);
            $image  = '';
            
            if ( is_array($photos) && ! empty($photos[0]['photo_link']) ) :
            	$image = esc_url_raw((string) $photos[0]['photo_link']);
            endif;
            
            /* additionalProperty: техданные + функции */
            $additional = [];
            
            /* tech */
            $tech = get_field('truck_tech_info', $p_id);
            
            if ( is_array($tech) ) :
            	$tech = array_values(array_filter($tech, function ($row) {
            		return ! empty($row['label']) && ! empty($row['value']);
            	}));
            
            	foreach ( $tech as $row ) :
            		$additional[] = [
            			'@type' => 'PropertyValue',
            			'name'  => (string) $row['label'],
            			'value' => (string) $row['value'],
            		];
            	endforeach;
            endif;
            
            /* functions/features -> additionalProperty */
            $func = get_field('truck_functions', $p_id);
            
            if ( is_array($func) ) :
            	$func = array_values(array_filter($func, function ($row) {
            		return ! empty($row['value']);
            	}));
            
            	foreach ( $func as $row ) :
            		$additional[] = [
            			'@type' => 'PropertyValue',
            			'name'  => (string) $row['value'],
            			'value' => 'Да',
            		];
            	endforeach;
            endif;
            
            /* offers + multi currency */
            $price_specs = [];
            
            if ( isset($price_byn) && $price_byn ) :
            	$price_specs[] = [
            		'@type' => 'UnitPriceSpecification',
            		'priceCurrency' => 'BYN',
            		'price' => $price_byn,
            	];
            endif;
            
            if ( isset($price_euro) && $price_euro ) :
            	$price_specs[] = [
            		'@type' => 'UnitPriceSpecification',
            		'priceCurrency' => 'EUR',
            		'price' => $price_euro,
            	];
            endif;
            
            if ( isset($price_usd) && $price_usd ) :
            	$price_specs[] = [
            		'@type' => 'UnitPriceSpecification',
            		'priceCurrency' => 'USD',
            		'price' => $price_usd,
            	];
            endif;
            
            $offer_currency = '';
            $offer_price    = '';
            
            if ( isset($price_byn) && $price_byn ) :
            	$offer_currency = 'BYN';
            	$offer_price    = $price_byn;
            elseif ( isset($price_euro) && $price_euro ) :
            	$offer_currency = 'EUR';
            	$offer_price    = $price_euro;
            elseif ( isset($price_usd) && $price_usd ) :
            	$offer_currency = 'USD';
            	$offer_price    = $price_usd;
            endif;
            
            /* schema */
            $permalink = get_permalink($p_id);
            
            $schema = [
            	'@context' => 'https://schema.org',
            	'@type'    => 'Vehicle',
            	'mainEntityOfPage' => [
            		'@type' => 'WebPage',
            		'@id'   => $permalink,
            	],
            	'url'  => $permalink,
            	'name' => trim($brand . ' ' . $model),
            	'brand' => [
            		'@type' => 'Brand',
            		'name'  => $brand,
            	],
            	'model' => $model,
            ];
            
            if ( $year ) :
            	$schema['vehicleModelDate'] = (string) $year;
            endif;
            
            if ( $mile ) :
            	$schema['mileageFromOdometer'] = [
            		'@type' => 'QuantitativeValue',
            		'value' => (float) $mile,
            		'unitCode' => 'KMT',
            	];
            endif;
            
            if ( is_array($fuel) && ! empty($fuel['label']) ) :
            	$schema['fuelType'] = (string) $fuel['label'];
            endif;
            
            if ( is_array($trans) && ! empty($trans['label']) ) :
            	$schema['vehicleTransmission'] = (string) $trans['label'];
            endif;
            
            if ( $hp ) :
            	$schema['vehicleEngine'] = [
            		'@type' => 'EngineSpecification',
            		'name'  => $kw ? ((string) $hp . ' hp (' . (string) $kw . ' kW)') : ((string) $hp . ' hp'),
            	];
            endif;
            
            if ( $image ) :
            	$schema['image'] = $image;
            endif;
            
            if ( ! empty($additional) ) :
            	$schema['additionalProperty'] = $additional;
            endif;
            
            if ( $offer_currency && $offer_price ) :
            	$schema['offers'] = [
            		'@type' => 'Offer',
            		'url'   => $permalink,
            		'priceCurrency' => $offer_currency,
            		'price' => $offer_price,
            		'availability' => 'https://schema.org/InStock',
            	];
            	if ( ! empty($price_specs) ) :
            		$schema['offers']['priceSpecification'] = $price_specs;
            	endif;
            endif;
            ?>
            <!-- litespeed_nooptimize -->
            <script type="application/ld+json"><?php echo wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>
            <!-- litespeed_nooptimize -->

        
        
    <?php endwhile; endif; ?>
    
    <!-- litespeed_nooptimize -->
    <script>
        var link_to_form = 0;
        window.addEventListener('b24:form:init', (event) => {            
            var form = event.detail.object;    
            if(form.identification.id == 45)
            {
                link_to_form = form;   
                change_bt24_form();
            }
        });
        
        function change_bt24_form()
        {

            console.log(link_to_form.identification.id);
            
            if (link_to_form.identification.id == 45) {
                setTimeout(function () {
                    link_to_form.setProperty('brand', '<?php echo $brand; ?>, ID авто в источнике: <?php echo get_field('truck_id', $p_id); ?>');
                    link_to_form.setProperty('model', '<?php echo $model; ?>');
                    link_to_form.setProperty('equipment', 'mobile.de');
                    console.log('bt24 fired!');
                }, 500);
            }


        }
        


    </script>    
    <!-- litespeed_nooptimize -->

<?php get_footer(); ?>