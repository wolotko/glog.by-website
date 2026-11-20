<?php get_header('', array( 'type' => 'text' )); ?>

  
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    
        
        <section trucks-photo-info>
            <div class="container">
                <h1><?php echo get_field('sharing_title'); ?></h1>
            </div>
            <div class="container">
                <?php $equipment_photos = get_field('equipment_photos'); ?>
                <?php if($equipment_photos) : ?>
                        <div trucks-photos>
                            <div class="owl-carousel">
                                <?php $i = 1; foreach($equipment_photos as $equipment_photo) : ?>
                                    <?php if ( $i > 15 ) break; ?>
                                	<div class="item">
                                		<div trucks-photo>
                                		    <img src="<?php echo $equipment_photo['photo']; ?>" alt="<?php echo get_field('sharing_title'); ?> – фотография <?php echo $i; ?>" title="<?php echo get_field('sharing_title'); ?> – фотография <?php echo $i; ?>" />
                                		</div>
                                	</div>
                            	<?php $i++; endforeach; ?>
                            </div>
                        </div>
                    
                <?php endif; ?>
                <div trucks-info>
                    
                    <?php 
                        $equipment_price = (int) get_field('equipment_price');

                        $exrates_eur_rate = (float) get_field('exrates_eur_rate', 'option');
                        $exrates_usd_rate = (float) get_field('exrates_usd_rate', 'option');
                         
                        $price_byn = $equipment_price * $exrates_eur_rate;
                        $price_usd = $price_byn / $exrates_usd_rate;
                    ?>
                    <div class="basic-options-list">
                        
                        
                            
                        <?php $hide_price = false; ?>
                        
                        <?php /*
                        <div class="basic-options-row">
                            <div><span byn>
                                    <?php echo number_format($price_byn, 0, ',', ' '); ?> руб.
                                </span></div>
                            <i></i>
                            <div>
                                <?php echo number_format($equipment_price, 0, ',', ' '); ?> &euro; /
                                <?php echo number_format($price_usd, 0, ',', ' '); ?> $
                            </div>
                        </div>
                        
                        <div class="basic-options-row">
                            <div>Цена</div>
                            <i></i>
                            <div>указана с НДС</div>
                        </div>
                        */ ?>
                        <div class="basic-options-row">
                            <div>Код позиции</div>
                            <i></i>
                            <div>
                                <span id="equipment-position-code"><?php echo esc_html(sprintf('GLG-1%05d', get_the_ID())); ?></span>
                                <button type="button" id="copy-equipment-position-code" hidden>Копировать</button>
                            </div>
                        </div>
                        <div class="basic-options-row">
                            <div>Год выпуска</div>
                            <i></i>
                            <div><?php echo get_field('equipment_year'); ?></div>
                        </div>
                        <?php foreach(get_field('equipment_tech_info') as $info) : ?>
                            <?php if($info['show']) : ?>
                                <div class="basic-options-row">
                                    <div><?php echo $info['label']; ?></div>
                                    <i></i>
                                    <div><?php echo $info['value']; ?></div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        
                        <div truck-cta mobile>
                            <a href="#form" btn>Бесплатная консультация</a>
                        </div>
                        
                        
                    </div>
                    
                    <div truck-cta>
                        <h2>Купить <?php echo get_field('truck_brand'); ?> <?php echo get_field('truck_model'); ?> с доставкой в Беларусь</h2>
                        
                        <p>Оставьте заявку для бесплатной консультации и расчёта предложения или позвоните нам: <a href="tel:+375333592626">+375 (33) 359-26-26</a></p>
                        
                        <a href="#form" btn>Бесплатная консультация</a>
                        
                    </div>
                </div>
            </div>
        </section>
        
        <section post class="post">
            <div class="container">
                <h3>Описание</h3>
            </div>
            <div class="container">
                <?php echo get_field('equipment_description'); ?>
            </div>
        </section>        
        
        <section track-tech>
            <div class="container">
                <h3>Технические данные &laquo;<?php echo get_field('sharing_title'); ?>&raquo;</h3>
            </div>
            <?php
                $tech_info = get_field('equipment_tech_info') ?: [];
                
                $tech_chunks = $tech_info
                    ? array_chunk($tech_info, (int) ceil(count($tech_info) / 2))
                    : [];
                
                $tech_chunks = array_pad($tech_chunks, 2, []);
            ?>
            
            <div class="container">
                <?php foreach ($tech_chunks as $column) : ?>
                    <div>
                        <?php foreach ($column as $tech) : ?>
                            <div class="basic-options-list">
                                <div class="basic-options-row">
                                    <div><?php echo esc_html($tech['label']); ?></div>
                                    <i></i>
                                    <div><?php echo esc_html($tech['value']); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
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
                                <p>Оставьте заявку для бесплатной консультации и расчёта предложения или позвоните нам: <a href="tel:+375333592626">+375 (33) 359-26-26</a></p>
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

    <script>
        {
            const code = document.getElementById('equipment-position-code');
            const button = document.getElementById('copy-equipment-position-code');

            if (code && button) {
                button.hidden = false;
                button.addEventListener('click', async () => {
                    try {
                        await navigator.clipboard.writeText(code.textContent.trim());
                        button.textContent = 'Скопировано';
                    } catch {
                        const range = document.createRange();
                        range.selectNodeContents(code);
                        const selection = window.getSelection();
                        selection.removeAllRanges();
                        selection.addRange(range);
                        button.textContent = 'Скопируйте код вручную';
                    }
                });
            }
        }
    </script>

<?php get_footer(); ?>