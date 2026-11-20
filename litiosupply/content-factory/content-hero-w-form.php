<div class="section" hero-w-form>
    <div class="r-container">
        <div class="d-flex justify-content-center">
            <div class="row row-cols-xl-2 row-cols-1 d-flex align-items-center1">
                <div class="col mb-3 col-23">
                    <div class="d-flex flex-column gap-3 h-100 justify-content-center pe-xl-5">
                        <div class="d-flex flex-column gap-3">

                            <h3 class="fw-semibold"><?php echo $args['title']; ?></h3>
                            <?php

                            $content = $args['content'];
                            
                            // Меняем <!--more--> на <hr>
                            $content = str_replace('<!--more-->', '<hr>', $content);
                            
                            // Удаляем любые <p> вокруг <hr>, оставляя только <hr>
                            $content = preg_replace(
                                '~<p[^>]*>\s*(<hr[^>]*>)\s*</p>~i',
                                '$1',
                                $content
                            );
                            
                            // Перезаписываем все <p> на наш класс
                            $content = preg_replace(
                                '~<p[^>]*>~i',
                                '<p class="text-color-2">',
                                $content
                            );
                            
                            // Удаляем пустые параграфы
                            $content = preg_replace(
                                '~<p class="text-color-2">\s*</p>~i',
                                '',
                                $content
                            );
                            
                            echo $content;
                            
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col mb-3" formwrapper>
                    <div class="d-flex flex-column gap-4 px-lg-4 px-0">
                        <div class="position-relative">
                            <div class="w-100">
                                <div>
                                    <?php echo do_shortcode('[contact-form-7 id="195" html_class="d-flex flex-column h-100 justify-content-center w-100 form"]'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>