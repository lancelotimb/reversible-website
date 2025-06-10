<?php
function ef_front_page_enqueue_assets() {
	if ( is_front_page() ) {
		wp_enqueue_style( 'ef-front-page', THEME_MODULES_URI . '/front-page/css/front-page.less', array('extra-content') );
		wp_enqueue_script( 'ef-front-page', THEME_MODULES_URI . '/front-page/js/front-page.js', array('jquery', 'extra'), null, true );
	}
}
add_action( 'wp_enqueue_scripts', 'ef_front_page_enqueue_assets' );
/**********************
 *
 *
 *
 * NO SECOND TITLE HERE
 *
 *
 *
 *********************/
add_filter('extra_second_title_exclude_post_id', function($ids) {
	array_push($ids, get_option('page_on_front'));
	return $ids;
});
/**********************
 *
 *
 *
 * FRONT PAGE METABOX
 *
 *
 *
 *********************/
add_action('init', 'extra_front_page_metabox_init');
function extra_front_page_metabox_init () {
	global $front_page_metabox;
	$front_page_metabox = new ExtraMetaBox(array(
		'include_post_id' => get_option('page_on_front'),
		'id' => '_front_page',
		'hide_editor' => true,
		'hide_ui' => true,
		'title' => __("Paramètres pages d'accueil", "extra"),
		'types' => array('page'),
		'fields' => array(
			array(
				'type' => 'bloc',
				'title' => __("Carrousel", "extra"),
				'subfields' => array(
					array(
						'type' => 'tabs',
						'name' => 'slider',

						'fixed' => false,
						'bloc_label' => 'Image',
						'subfields' => array(
							array(
								'type' => 'image',
								'name' => 'image',
								'label' => 'Image'
							),
							array(
								'type' => 'text',
								'name' => 'title',
								'label' => 'Titre'
							)
						)
					),
				)
			),
			array(
				'type' => 'bloc',
				'title' => __("Mise en avant", "extra"),
				'subfields' => array(
					array(
						'type' => 'tabs',
						'name' => 'push',
						'fixed' => true,
						'num_tabs' => 3,
						'bloc_label' => 'Mise en avant',
						'subfields' => array(
							array(
								'type' => 'image',
								'name' => 'image',
								'label' => 'Image'
							),
							array(
								'type' => 'textarea',
								'name' => 'title',
								'label' => 'Titre de la mise en avant'
							),
							array(
								'type' => 'textarea',
								'name' => 'subtitle',
								'label' => 'Sous-titre de la mise en avant'
							),
							array(
								'type' => 'link',
								'name' => 'link',
								'include_taxonomies' => true
							)
						)
					),
				)
			),
			array(
				'type' => 'bloc',
				'title' => 'Bloc actualité',
				'subfields' => array(
					array(
						'type' => 'page_selector',
						'name' => 'post',
						'label' => 'Actualité à mettre en avant',
						'post_type' => 'post',
						'option_none_text' => 'Dernière actualité'
					)
				)
			),
			array(
				'type' => 'bloc',
				'title' => 'Matières',
				'subfields' => array(
					array(
						'type' => 'text',
						'name' => 'materials_title',
						'label' => 'Titre'
					),
					array(
						'type' => 'text',
						'name' => 'materials_link_text',
						'label' => 'Intitulé du lien'
					),
					array(
						'type' => 'link',
						'name' => 'materials_link',
						'label' => 'Lien'
					),
					array(
						'type' => 'separator'
					),
					array(
						'type' => 'gallery',
						'name' => 'materials_images',
						'label' => 'Matières'
					)
				)
			)
		)
	));
}

function front_page_extra_metabox_link_available_taxonomies($taxonomies) {
	$taxonomies = array(
		'extra_product_template_material',
		'extra_product_template_type',
		'extra_product_collection',
	);

	return $taxonomies;
}
add_filter('extra_metabox_link_available_taxonomies', 'front_page_extra_metabox_link_available_taxonomies');