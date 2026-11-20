<!DOCTYPE html>
<html lang="ru">
<?php 
    global $post;
    $pid = $post->ID;
	$title = wp_get_document_title();
	if( get_field('sharing_title', $pid) ) :
		$title = get_field('sharing_title', $pid);
	endif;
	
	if ( is_post_type_archive('news') ) :
        $title = wp_get_document_title();
    endif;
    
    if ( is_post_type_archive('equipment') ) :
        $title = wp_get_document_title();
    endif;
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php bloginfo('template_directory') ?>/css/style-trucks.css?v=2.13">
    <link rel="stylesheet" href="<?php bloginfo('template_directory') ?>/css/style.css?v=2.14">
    
    <title><?php echo $title; ?></title>
    
    <!-- Primary Meta Tags -->
    <meta name="title" content="<?php echo $title; ?>" />
    <?php if ( !is_post_type_archive('news') ) : ?>
        <meta name="description" content="<?php echo get_field('sharing_description', $pid); ?>" />
        
        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website" />
        <meta property="og:url" content="<?php echo get_the_permalink($pid); ?>" />
        <meta property="og:title" content="<?php echo $title; ?>" />
        <meta property="og:description" content="<?php echo get_field('sharing_description', $pid); ?>" />
        <meta property="og:image" content="<?php echo get_field('sharing_image', $pid); ?>" />
        
        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image" />
        <meta property="twitter:url" content="<?php echo get_the_permalink($pid); ?>" />
        <meta property="twitter:title" content="<?php echo $title; ?>" />
        <meta property="twitter:description" content="<?php echo get_field('sharing_description', $pid); ?>" />
        <meta property="twitter:image" content="<?php echo get_field('sharing_image', $pid); ?>" />
        
        <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
        <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
        <link rel="shortcut icon" href="/favicon.ico" />
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
        <meta name="apple-mobile-web-app-title" content="Litio Supply" />
        <link rel="manifest" href="/site.webmanifest" />
    <?php endif; ?>
    <?php wp_head(); ?>
	
	<!-- lscache no-cache -->

	<!-- Yandex.Metrika counter -->
	<script type="text/javascript" >
	   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
	   m[i].l=1*new Date();
	   for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
	   k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
	   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

	   ym(104362999, "init", {
			clickmap:true,
			trackLinks:true,
			accurateTrackBounce:true,
			webvisor:true
	   });
		
		document.addEventListener('DOMContentLoaded', function() {
			document.addEventListener('wpcf7mailsent', function(event) {
			  ym(104362999, 'reachGoal', 'wpcf7mailsent');
			  console.log("Метрика вызвана с аргументами: [104362999, 'reachGoal', 'wpcf7mailsent']");
			}, false);
		 });
		
	</script>
	
	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=AW-17056347262"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'AW-17056347262');
	</script>
	
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-TXRRZGJQ');</script>
	<!-- End Google Tag Manager -->


	<!-- Meta Pixel Code -->
	<script>
	!function(f,b,e,v,n,t,s)
	{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
	n.callMethod.apply(n,arguments):n.queue.push(arguments)};
	if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
	n.queue=[];t=b.createElement(e);t.async=!0;
	t.src=v;s=b.getElementsByTagName(e)[0];
	s.parentNode.insertBefore(t,s)}(window, document,'script',
	'https://connect.facebook.net/en_US/fbevents.js');
	fbq('init', '2014696182614338');
	fbq('track', 'PageView');
	</script>
	<noscript><img height="1" width="1" style="display:none"
	src="https://www.facebook.com/tr?id=2014696182614338&ev=PageView&noscript=1"
	/></noscript>
	<!-- End Meta Pixel Code -->
    
    <!-- /lscache no-cache -->
</head>

<body data-pid="<?php echo $pid; ?>">
	<noscript><div><img src="https://mc.yandex.ru/watch/104362999" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TXRRZGJQ"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->

    <!-- Scripts -->
    <script src="<?php bloginfo('template_directory') ?>/js/vendor/jquery.min.js"></script>


	
    <!-- Header -->
    <header class="sticky-top bg-white">
        <div class="r-container">
			<div contactline>
				<ul>
					<li>
						<a href="tel:+375333592626"><i class="fa-solid fa-phone"></i><em>+375(33)359-26-26</em></a>
					</li>
					<li>
						<a href="mailto:info.glog.by@gmail.com"><i class="fa-solid fa-envelope"></i><em>info.glog.by@gmail.com</em></a>
					</li>
					<li><a href="https://t.me/+375336904646" target="_blank"><i class="fab fa-telegram"></i></a></li>
					<li><a href="viber://chat?number=%2B375336904646" target="_blank"><i class="fab fa-viber"></i></a></li>
					<li><a href="https://wa.me/+375336904646" target="_blank"><i class="fab fa-whatsapp"></i></a></li>
				</ul>
			</div>
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid ps-3">
                    <div class="logo-container">
                        <a class="navbar-brand" href="<?php echo home_url(); ?>">
                            <?php /*<img src="/wp-content/uploads/2026/06/litio-logo3.png" alt="<?php bloginfo('name'); ?>" width="1000" height="1000" class="img-fluid"> */ ?>

                            <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 189 51" width="189" height="51">
                            	<style>
                            		.s0 { fill: #0b2342 } 
                            	</style>
                            	<path fill-rule="evenodd" class="s0" d="m44.99 7.02h-36.55c-1.36 0-2.53 0.38-3.2 1.06-0.68 0.59-0.97 1.55-0.97 2.82v10.56c0 1.17 0.38 2.04 0.97 2.62 0.77 0.68 1.93 1.07 3.29 1.07h33.16v-3.88h-22.88v-4.17h27.15v7.08c0 1.55-0.49 2.91-1.55 3.78-0.97 0.97-2.23 1.36-3.88 1.36h-32c-2.42 0-4.55-0.68-6.01-2.14-1.65-1.35-2.52-3.29-2.52-5.72v-10.56c0-2.43 0.87-4.46 2.42-5.92 1.46-1.36 3.49-2.13 6.02-2.13h36.55zm143.98 10.08v7.08c0 1.65-0.58 2.91-1.55 3.88-0.97 0.87-2.33 1.26-3.98 1.26h-29.47c-2.52 0-4.56-0.68-6.01-2.04-1.55-1.45-2.43-3.39-2.43-5.82v-10.56c0-2.43 0.78-4.46 2.23-5.92 1.46-1.36 3.4-2.13 6.01-2.13h34.13v4.17h-34.13c-1.45 0-2.42 0.39-3.1 0.97-0.58 0.68-0.97 1.65-0.97 2.91v10.56c0 1.17 0.39 2.04 1.07 2.62 0.68 0.68 1.84 1.07 3.2 1.07h30.73v-3.88h-21.13v-4.17zm-130.8-13.96v18.32c0 1.17 0.39 2.14 1.07 2.72 0.68 0.58 1.74 0.97 3.1 0.97h26.66v4.17h-26.66c-2.42 0-4.56-0.68-6.01-2.14-1.46-1.35-2.33-3.29-2.33-5.72v-18.32zm44.99-0.29h27.44c2.33 0 4.36 0.87 5.91 2.42 1.46 1.46 2.43 3.59 2.43 5.92v9.79c0 2.33-0.97 4.46-2.43 5.91-1.55 1.56-3.58 2.43-5.91 2.43h-27.44c-2.33 0-4.36-0.87-5.91-2.43-1.56-1.45-2.43-3.58-2.43-5.91v-9.79c0-2.33 0.87-4.46 2.43-5.92 1.55-1.55 3.58-2.42 5.91-2.42zm27.44 4.17h-27.44c-1.16 0-2.23 0.48-2.91 1.26-0.77 0.68-1.26 1.74-1.26 2.91v9.79c0 1.16 0.49 2.23 1.26 2.91 0.78 0.77 1.75 1.26 2.91 1.26h27.44c1.16 0 2.13-0.49 2.91-1.26 0.77-0.68 1.16-1.75 1.16-2.91v-9.79c0-1.17-0.39-2.23-1.16-2.91-0.78-0.78-1.75-1.26-2.91-1.26z"/>
                            	<path fill-rule="evenodd" class="s0" d="m2.14 48.48v-0.93c0.19 0 0.37-0.09 0.47-0.09 0.18-0.1 0.37-0.28 0.46-0.37 0.09-0.19 0.19-0.28 0.28-0.47 0.09-0.28 0.09-0.56 0.19-0.93 0-0.28 0.09-0.65 0.09-0.93 0-0.37 0-0.65 0-0.93l0.09-4.75h6.14v9.31h-0.93v-8.47h-4.28v3.35c-0.09 0.56-0.09 1.02-0.09 1.58 0 0.47-0.09 1.03-0.09 1.4-0.1 0.46-0.28 0.84-0.47 1.21-0.18 0.28-0.37 0.56-0.74 0.74q-0.42 0.28-1.12 0.28zm14.98 0c-0.93 0-1.77-0.18-2.42-0.56-0.65-0.46-1.12-1.02-1.49-1.76-0.28-0.66-0.46-1.49-0.46-2.42 0-0.93 0.18-1.68 0.46-2.42 0.37-0.75 0.84-1.3 1.49-1.68 0.65-0.46 1.49-0.65 2.42-0.65 0.93 0 1.77 0.19 2.42 0.65q0.98 0.56 1.39 1.68c0.38 0.74 0.56 1.49 0.56 2.42 0 0.93-0.18 1.76-0.56 2.42-0.27 0.74-0.74 1.3-1.39 1.76-0.65 0.38-1.49 0.56-2.42 0.56zm0-0.84c0.74 0 1.4-0.18 1.86-0.55q0.84-0.42 1.12-1.4c0.28-0.56 0.37-1.21 0.37-1.95 0-0.47 0-1.03-0.19-1.49-0.09-0.47-0.37-0.93-0.65-1.21-0.28-0.37-0.56-0.65-1.02-0.84-0.47-0.28-0.93-0.37-1.49-0.37q-1.12 0-1.95 0.56-0.7 0.56-1.12 1.39c-0.19 0.65-0.37 1.31-0.37 1.96 0 0.74 0.18 1.39 0.37 1.95 0.28 0.56 0.65 1.12 1.12 1.4q0.83 0.55 1.95 0.55zm7.26 0.75v-9.31h5.86v0.84h-4.93v8.47zm8.56 0v-9.31h0.93v8.01l5.21-8.01h1.11v9.31h-0.93v-7.91l-5.3 7.91zm14.42 0.09c-0.93 0-1.77-0.18-2.42-0.56q-0.84-0.69-1.39-1.67c-0.28-0.75-0.47-1.58-0.47-2.51 0-0.93 0.19-1.68 0.47-2.42 0.37-0.75 0.83-1.3 1.48-1.68 0.66-0.46 1.4-0.65 2.33-0.65q0.98 0 1.68 0.28c0.46 0.19 0.93 0.47 1.2 0.84 0.38 0.37 0.56 0.74 0.66 1.3h-0.93c-0.19-0.56-0.47-0.93-0.93-1.12-0.47-0.27-1.03-0.46-1.68-0.46-0.74 0-1.4 0.19-1.86 0.56q-0.7 0.56-1.12 1.39c-0.18 0.65-0.28 1.21-0.28 1.96q0 1.11 0.28 1.95c0.28 0.56 0.65 1.12 1.12 1.4 0.46 0.37 1.12 0.55 1.86 0.55 0.47 0 0.84 0 1.21-0.18 0.37-0.1 0.65-0.28 0.93-0.47q0.42-0.42 0.56-0.83h1.02c-0.09 0.46-0.37 0.93-0.74 1.3-0.37 0.28-0.75 0.56-1.3 0.74q-0.7 0.28-1.68 0.28zm8.56-0.09v-8.47h-3.07v-0.84h7.07v0.84h-3.07v8.47zm6.42 0v-9.31h1.02v8.01l5.21-8.01h1.12v9.31h-0.93v-7.91l-5.3 7.91zm10.79 0v-9.31h0.93v4.38h1.49l3.16-4.38h1.12l-3.35 4.66 3.35 4.65h-1.12l-2.98-4.1h-1.67v4.1zm8.09 0l3.54-9.31h1.3l3.54 9.31h-1.03l-0.74-2.05h-4.75l-0.83 2.05zm2.14-2.89h4.09l-2.05-5.48zm11.92 2.95v-8.47h-3.07v-0.84h7.08v0.84h-3.08v8.47zm6.43 0v-9.31h6.15v0.84h-5.22v3.44h4.75v0.84h-4.75v3.36h5.22v0.83zm8.1 0l2.99-4.65-2.89-4.66h1.21l2.33 3.82 2.33-3.82h1.21l-2.89 4.56 2.98 4.75h-1.21l-2.42-3.91-2.52 3.91zm9.69 0v-9.31h0.93v4.28h5.31v-4.28h1.03v9.31h-1.03v-4.19h-5.31v4.19zm14.54 0.1c-1.03 0-1.77-0.19-2.43-0.65-0.65-0.38-1.11-0.94-1.49-1.68-0.28-0.75-0.46-1.49-0.46-2.42 0-0.94 0.18-1.77 0.46-2.43 0.38-0.74 0.84-1.3 1.49-1.77 0.66-0.37 1.4-0.55 2.43-0.55 0.93 0 1.77 0.18 2.42 0.55 0.65 0.47 1.12 1.03 1.4 1.77 0.37 0.66 0.56 1.49 0.56 2.43 0 0.93-0.19 1.67-0.56 2.42q-0.42 1.12-1.4 1.68c-0.65 0.46-1.49 0.65-2.42 0.65zm0-0.84c0.74 0 1.39-0.19 1.86-0.56q0.84-0.56 1.12-1.4c0.28-0.65 0.37-1.3 0.37-1.95 0-0.56-0.09-1.03-0.19-1.49-0.18-0.56-0.37-0.94-0.65-1.31-0.28-0.37-0.65-0.65-1.02-0.84-0.47-0.18-0.93-0.28-1.49-0.28q-1.12 0-1.96 0.56-0.7 0.42-1.12 1.4c-0.28 0.56-0.37 1.21-0.37 1.96 0 0.65 0.09 1.3 0.37 1.95q0.42 0.84 1.12 1.4 0.84 0.56 1.96 0.56zm6.05 0.84v-0.93c0.19-0.1 0.37-0.1 0.56-0.19 0.09-0.09 0.28-0.19 0.37-0.37 0.1-0.1 0.19-0.28 0.28-0.47 0.1-0.28 0.19-0.56 0.19-0.84 0.09-0.37 0.09-0.65 0.09-1.02 0-0.28 0-0.56 0-0.93l0.1-4.66h6.15v9.31h-0.94v-8.47h-4.19l-0.09 3.35c0 0.47 0 1.03-0.1 1.58 0 0.47 0 0.94-0.09 1.4-0.09 0.47-0.19 0.84-0.46 1.12-0.1 0.37-0.38 0.56-0.66 0.75-0.37 0.18-0.74 0.27-1.21 0.37zm15 0c-0.93 0-1.77-0.19-2.42-0.65q-0.98-0.56-1.4-1.68c-0.37-0.75-0.56-1.49-0.56-2.42 0-0.94 0.19-1.77 0.56-2.43 0.28-0.74 0.75-1.3 1.4-1.77 0.65-0.37 1.49-0.55 2.42-0.55 1.03 0 1.77 0.18 2.42 0.55 0.66 0.47 1.12 1.03 1.49 1.77 0.28 0.66 0.47 1.49 0.47 2.43 0 0.93-0.19 1.67-0.47 2.42-0.37 0.74-0.83 1.3-1.49 1.68-0.65 0.46-1.39 0.65-2.42 0.65zm0-0.84q1.12 0 1.96-0.56 0.7-0.56 1.12-1.4c0.18-0.65 0.37-1.3 0.37-1.95 0-0.56-0.09-1.03-0.28-1.49-0.09-0.56-0.28-0.94-0.56-1.31q-0.42-0.56-1.12-0.84c-0.37-0.18-0.93-0.28-1.49-0.28-0.74 0-1.4 0.19-1.86 0.56q-0.84 0.42-1.12 1.4c-0.28 0.56-0.37 1.21-0.37 1.96 0 0.65 0.09 1.3 0.37 1.95q0.28 0.84 1.12 1.4c0.46 0.37 1.12 0.56 1.86 0.56zm7.27 0.74v-9.31h5.87v0.84h-4.94v8.47zm8.57 0v-9.31h0.93v7.92l5.31-7.92h1.03v9.31h-0.93v-7.91l-5.22 7.91zm10.71 0v-9.31h0.93v7.92l5.31-7.92h1.12v9.31h-1.02v-7.91l-5.22 7.91zm3.63-10.34c-0.46 0-0.84-0.09-1.21-0.18-0.28-0.19-0.56-0.38-0.74-0.65-0.19-0.28-0.38-0.56-0.38-1.03h0.94c0 0.37 0.18 0.65 0.46 0.84 0.28 0.19 0.56 0.28 0.93 0.28 0.47 0 0.75-0.09 1.03-0.28 0.28-0.19 0.46-0.47 0.46-0.84h0.94c-0.1 0.47-0.19 0.75-0.38 1.03-0.18 0.27-0.46 0.46-0.84 0.65-0.28 0.09-0.74 0.18-1.21 0.18z"/>
                            </svg>
                        </a>
                    </div>
                    <div class="d-flex d-lg-none align-items-center ms-auto">
                        <?php if (shortcode_exists('glog_equipment_cart')) : ?>
                            <?php echo do_shortcode('[glog_equipment_cart icon="1"]'); ?>
                        <?php endif; ?>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fa-solid fa-bars-staggered accent-color-new"></i>
                        </button>
                    </div>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-4 gap-1">
                            <li class="nav-item">
                                <a class="nav-link" href="/services/">Услуги</a>
                            </li>
							<!--<li class="nav-item">-->
       <!--                         <a class="nav-link" href="/cargo/">Грузоперевозки</a>-->
       <!--                     </li>-->
							<li class="nav-item">
                                <a class="nav-link" href="/equipment/">Техника и оборудование</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/news/">Новости</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/proekty/">Проекты</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/contacts/">Контакты</a>
                            </li>
                            <?php /*
                            <li class="nav-item">
                                <?php if($pid == 69):  ?>
                                    <a class="nav-link" href="/">Русский</a>
								<?php else:  ?>
                                    <a class="nav-link" href="/en/">English</a>
                                <?php endif; ?>
                            </li>
                            */ ?>
                            <?php 
                            
                                $sections = get_field( 'factory', $pid );
                        
                                if ( $sections ) :
                                    foreach ( $sections as $section ) :
                                        
                                        if($section['acf_fc_layout'] == 'menu') :
                            ?>
                            
                                            <li class="nav-item">
                                                <a class="nav-link" href="#<?php echo md5($section['name']); ?>"><?php echo $section['name']; ?></a>
                                            </li>
                            
                            <?php 
                                        endif;
                                        if($section['acf_fc_layout'] == 'menu-link') :
                            ?>
                            
                                            <li class="nav-item">
                                                <a class="nav-link" href="<?php echo $section['ancor']; ?>"><?php echo $section['name']; ?></a>
                                            </li>
                            
                            <?php 
                                        endif;							
                                        
                                    endforeach;
                                endif;
                            
                            ?>
                            
                            
                            
                        </ul>
                        <div class="mb-xl-0 mb-3 d-flex align-items-center gap-2">
                            <?php if (shortcode_exists('glog_equipment_cart')) : ?>
                                <div class="d-none d-lg-block">
                                    <?php echo do_shortcode('[glog_equipment_cart icon="1"]'); ?>
                                </div>
                            <?php endif; ?>
                            <?php if($pid == 69):  ?>
                                <a href="#contacts" form class="btn btn-accent px-5 py-3">Let’s talk</a>
                            <?php else : ?>
                                <a href="<?php echo esc_url(home_url('/contacts/')); ?>" class="btn btn-accent btn-header">&nbsp;Рассчитать&nbsp;<br>&nbsp;поставку&nbsp;</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    <!-- End Header -->
    
    <main>
        <?php if(!is_front_page() && !is_page(array('services', 'contacts')) && !is_archive('news') && !is_singular('news') && !is_archive('trucks') && !is_singular('trucks') ) : ?>
            <div slogan>
                <div class="r-container">
                    <h2>Поставки из Китая в Беларусь «под ключ» оборудования и спецтехники без посредников и переплат</h2>
                </div>
            </div>
        <?php endif; ?>