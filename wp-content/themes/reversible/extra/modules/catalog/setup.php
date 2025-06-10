<?php
function extra_catalog_enqueue_assets() {
	if ( is_page_template('template-catalog.php') ) {
		wp_enqueue_style( 'extra-catalog', THEME_MODULES_URI . '/catalog/css/catalog.less' );
	}
}
add_action( 'wp_enqueue_scripts', 'extra_catalog_enqueue_assets' );
/**********************
 *
 *
 *
 * NO SECOND TITLE
 *
 *
 *
 *********************/
add_filter('extra_second_title_exclude_template', function($templateArray) {
	array_push($templateArray, 'template-catalog.php');
	return $templateArray;
});
/**********************
 *
 *
 *
 * CATALOG METABOX
 *
 *
 *
 *********************/
add_action('init', 'extra_catalog_metabox_init');
function extra_catalog_metabox_init () {
	global $catalog_metabox;
	$catalog_metabox = new ExtraMetaBox(array(
		'include_template' => 'template-catalog.php',
		'id' => '_catalog',
		'hide_editor' => false,
		'hide_ui' => true,
		'title' => __("Paramètres page catalogue", "extra"),
		'types' => array('page'),
		'fields' => array(
			array(
				'type' => 'tabs',
				'name' => 'catalog',
				'fixed' => false,
				'bloc_label' => 'Produit',
				'subfields' => array(
					array(
						'type' => 'image',
						'name' => 'image',
						'label' => 'Image'
					),
//					array(
//						'type' => 'taxonomy',
//						'taxonomy' => 'extra_product_template_type',
//						'name' => 'type',
//						'label' => 'Produit'
//					),
//					array(
//						'type' => 'taxonomy',
//						'taxonomy' => 'extra_product_template_material',
//						'name' => 'material',
//						'label' => 'Matière'
//					),
					array(
						'type' => 'textarea',
						'name' => 'description',
						'label' => 'Description'
					),
//					array(
//						'type' => 'text',
//						'name' => 'type_text',
//						'label' => 'Produit'
//					),
//					array(
//						'type' => 'text',
//						'name' => 'material_text',
//						'label' => 'Matière'
//					),
					array(
						'type' => 'text',
						'name' => 'detail',
						'label' => 'Détail'
					),
					array(
						'type' => 'text',
						'name' => 'width',
						'label' => 'Largeur'
					),
					array(
						'type' => 'text',
						'name' => 'height',
						'label' => 'Hauteur'
					),
					array(
						'type' => 'text',
						'name' => 'thickness',
						'label' => 'Épaisseur'
					)
				)
			),
			array(
				'type' => 'custom_editor',
				'name' => 'contact'
			)
		)
	));
}