<?php
function extra_materials_enqueue_assets() {
	if ( is_page_template('template-materials.php') ) {
		wp_enqueue_style( 'extra-materials', THEME_MODULES_URI . '/materials/css/materials.less', array('extra-content') );
		wp_enqueue_script( 'extra-materials', THEME_MODULES_URI . '/materials/js/materials.js', array('jquery', 'extra-common'), null, true );
	}
}
add_action( 'wp_enqueue_scripts', 'extra_materials_enqueue_assets' );
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
	array_push($templateArray, 'template-materials.php');
	return $templateArray;
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
add_action('init', 'extra_materials_metabox_init');
function extra_materials_metabox_init () {
	global $materials_metabox;
	$materials_metabox = new ExtraMetaBox(array(
		'include_template' => 'template-materials.php',
		'id' => '_materials',
		'hide_editor' => true,
		'hide_ui' => true,
		'title' => __("Paramètres page d'accueil", "extra"),
		'types' => array('page'),
		'fields' => array(
			array(
				'type' => 'tabs',
				'name' => 'materials',
				'fixed' => false,
				'bloc_label' => 'Matériau',
				'subfields' => array(
					array(
						'type' => 'text',
						'name' => 'title',
						'label' => 'Titre'
					),
					array(
						'type' => 'image',
						'name' => 'image',
						'label' => 'Image'
					),
					array(
						'type' => 'custom_editor',
						'name' => 'content'
					)
				)
			)
		)
	));
}