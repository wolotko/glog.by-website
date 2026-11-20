<div class="section" <?php if($args['order']): ?>order<?php endif; ?> textimage>
    <div class="r-container">
        <div class="row row-cols-lg-2 row-cols-1 align-items-center">
            <div class="col col-lg-5 ps-0 pe-0 mt-5 mb-5">
                <div class="position-relative ps-xl-0 ps-0 pe-xl-0 pe-0 mb-3">
                    <div class="mt-0 px-lg-5 px-lg-5 px-3">
                        <img src="<?php echo $args['image']; ?>" alt="<?php echo $args['title']; ?>" class="img-fluid rounded-4" width="1000" height="1000">
                    </div>
                </div>
            </div>
            <div class="col col-lg-7 px-4">
                <div class="d-flex flex-column gap-3 pe-xl-5 text-center text-md-start align-items-center align-items-xl-start">
                    

                    
					<?php if($args['title'] != '' ) : ?>
						<h3 class="fw-semibold"><?php echo $args['title']; ?></h3>
                    <?php endif; ?>
                    <?php if($args['text'] != '' ) : ?>
                        <p class="text-color-2"><?php echo $args['text']; ?></p>
                    <?php endif; ?>
                    
                    <?php if($args['list']) : ?>
                        <?php foreach($args['list'] as $list): ?>
                            <div class="d-flex flex-md-row flex-column gap-3 px-4 align-items-center">
                                <div>
                                    <i class="fa-solid fa-circle-check fa-xl text-color mt-3 "></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-color-2"><?php echo $list['item']; ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                    <?php if($args['text_2'] != '' ) : ?>
                        <p class="text-color-2"><?php echo $args['text_2']; ?></p>
                    <?php endif; ?>
                    
                    
					<?php if( !empty($args['btn_name']) && !empty($args['btn_link']) ) : ?>
    					<div style="margin-top:30px;">
    						<a href="<?php echo $args['btn_link']; ?>" form class="btn btn-accent px-5 py-3"><?php echo $args['btn_name']; ?></a>
    					</div>	
					<?php endif; ?>
                    
                </div>
            </div>

        </div>
    </div>
</div>