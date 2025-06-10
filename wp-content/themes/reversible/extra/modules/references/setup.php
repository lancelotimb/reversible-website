<?php
function extra_references_enqueue_assets() {
	if ( is_page_template('template-references.php') ) {
		wp_enqueue_style( 'extra-references', THEME_MODULES_URI . '/references/css/references.less' );
		wp_enqueue_script( 'extra-references', THEME_MODULES_URI . '/references/js/references.js', array('jquery', 'extra-common'), null, true );
	}
}
add_action( 'wp_enqueue_scripts', 'extra_references_enqueue_assets' );
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
	array_push($templateArray, 'template-references.php');
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
add_action('init', 'extra_references_metabox_init');
function extra_references_metabox_init () {
	global $references_metabox;
	$references_metabox = new ExtraMetaBox(array(
		'include_template' => 'template-references.php',
		'id' => '_references',
		'hide_editor' => false,
		'hide_ui' => true,
		'title' => __("Paramètres des références", "extra"),
		'types' => array('page'),
		'fields' => array(
			array(
				'type' => 'tabs',
				'name' => 'references',
				'fixed' => false,
				'bloc_label' => 'Référence',
				'subfields' => array(
					array(
						'type' => 'image',
						'name' => 'image',
						'label' => 'Image (format carré)'
					),
					array(
						'type' => 'image',
						'name' => 'logo',
						'label' => 'Logo (format carré)'
					),
					array(
						'type' => 'custom_editor',
						'name' => 'description'
					)
				)
			)
		)
	));
}