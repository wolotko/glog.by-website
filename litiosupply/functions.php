<?php 

    add_filter('moove_gdpr_load_styles', '__return_false');
    add_filter('moove_gdpr_load_scripts', '__return_false');

	add_filter( 'wpcf7_validate_tel*', 'cf7_phone_e164_validation', 20, 2 );
	add_filter( 'wpcf7_validate_tel',  'cf7_phone_e164_validation', 20, 2 );

	function cf7_phone_e164_validation( $result, $tag ) {
		// Получаем имя поля (новые версии CF7 используют объект)
		if ( is_object( $tag ) && isset( $tag->name ) ) :
			$name = $tag->name;
		elseif ( is_array( $tag ) && isset( $tag['name'] ) ) :
			$name = $tag['name'];
		else :
			return $result;
		endif;

		// Если поля нет в POST — выходим
		if ( ! isset( $_POST[ $name ] ) ) :
			return $result;
		endif;

		$raw_value = trim( wp_unslash( $_POST[ $name ] ) );

		// Если поле не обязательно (tel, а не tel*) и пустое — не валидируем
		if ( $raw_value === '' ) :
			return $result;
		endif;

		// Разрешаем пользователю вводить пробелы, дефисы, скобки – убираем лишнее
		// Оставляем только цифры и плюс
		$normalized = preg_replace( '/[^\d\+]/', '', $raw_value );

		// Универсальный формат E.164: +[страна][номер], всего 7–15 цифр, первая цифра после + не ноль
		// Пример: +48555111222, +491711234567, +380991234567
		if ( ! preg_match( '/^\+[1-9]\d{6,14}$/', $normalized ) ) :
			$result->invalidate(
				$tag,
				'Пожалуйста, введите номер телефона в международном формате, например: +375441112233'
			);
		endif;

		return $result;
	}

// 	add_action( 'init', 'register_post_types_trucks' );

//     function register_post_types_trucks(){
    
//     	register_post_type( 'trucks', [
//     		'label'  => null,
//     		'labels' => [
//     			'name'               => 'Грузовики', // основное название для типа записи
//     			'singular_name'      => 'Грузовики', // название для одной записи этого типа
//     			'add_new'            => 'Добавить грузовик', // для добавления новой записи
//     			'add_new_item'       => 'Новый грузовик', // заголовка у вновь создаваемой записи в админ-панели.
//     			'edit_item'          => 'Редактировать грузовик', // для редактирования типа записи
//     			'new_item'           => 'Новый грузовик', // текст новой записи
//     			'view_item'          => 'Посмотреть грузовик', // для просмотра записи этого типа.
//     			'search_items'       => 'Найти грузовик', // для поиска по этим типам записи
//     			'not_found'          => 'Грузовик не найден', // если в результате поиска ничего не было найдено
//     			'not_found_in_trash' => 'В корзине – пусто', // если не было найдено в корзине
//     			'parent_item_colon'  => '', // для родителей (у древовидных типов)
//     			'menu_name'          => 'Грузовики', // название меню
//     		],
//     		'description'            => '',
//     		'public'                 => true,
//     		'show_in_menu'           => true, // показывать ли в меню админки
//     		'menu_position'       => 0,
//     		'menu_icon'           => 'dashicons-image-filter',
//     		'hierarchical'        => false,
//     		'supports'            => [ 'title', 'revisions', 'page-attributes' ], 
//     		'taxonomies'          => [],
//     		'has_archive'         => true,
//     		'rewrite'             => true,
//     		'query_var'           => false,
//     	] );
    
//     }
    
    add_action( 'init', 'register_post_types_equipment' );

    function register_post_types_equipment(){
    
    	register_post_type( 'equipment', [
    		'label'  => null,
    		'labels' => [
    			'name'               => 'Оборудование', // основное название для типа записи
    			'singular_name'      => 'Оборудование', // название для одной записи этого типа
    			'add_new'            => 'Добавить оборудование', // для добавления новой записи
    			'add_new_item'       => 'Новое оборудование', // заголовка у вновь создаваемой записи в админ-панели.
    			'edit_item'          => 'Редактировать оборудование', // для редактирования типа записи
    			'new_item'           => 'Новое оборудование', // текст новой записи
    			'view_item'          => 'Посмотреть оборудование', // для просмотра записи этого типа.
    			'search_items'       => 'Найти оборудование', // для поиска по этим типам записи
    			'not_found'          => 'Оборудование не найдено', // если в результате поиска ничего не было найдено
    			'not_found_in_trash' => 'В корзине – пусто', // если не было найдено в корзине
    			'parent_item_colon'  => '', // для родителей (у древовидных типов)
    			'menu_name'          => 'Оборудование', // название меню
    		],
    		'description'            => '',
    		'public'                 => true,
    		'show_in_rest'           => true,
    		'show_in_menu'           => true, // показывать ли в меню админки
    		'menu_position'       => 0,
    		'menu_icon'           => 'dashicons-image-filter',
    		'hierarchical'        => false,
    		'supports'            => [ 'title', 'revisions', 'page-attributes' ], 
    		'taxonomies'          => [],
    		'has_archive'         => true,
    		'rewrite'             => true,
    		'query_var'           => false,
    	] );
    
    }

?>