<?php
function extra_contact_enqueue_assets() {
	if ( is_page_template('template-contact.php') ) {
		wp_enqueue_style( 'extra-form', THEME_MODULES_URI . '/assets/css/form.less' );
		wp_enqueue_style( 'extra-contact', THEME_MODULES_URI . '/contact/css/contact.less' );
		wp_enqueue_script( 'extra-contact', THEME_MODULES_URI . '/contact/js/contact.js', array('jquery'), null, true );
	} else {
		add_filter( 'wpcf7_load_js', '__return_false' );
	}
}
add_action( 'wp_enqueue_scripts', 'extra_contact_enqueue_assets' );
/**********************
 *
 *
 *
 * FRONT PAGE METABOX
 *
 *
 *
 *********************/
add_action('init', 'extra_contact_metabox_init');
function extra_contact_metabox_init () {
	global $contact_metabox;
	$contact_metabox = new ExtraMetaBox(array(
		'include_template' => 'template-contact.php',
		'id' => '_contact',
		'hide_editor' => false,
		'hide_ui' => true,
		'title' => __("Paramètres page contact", "extra"),
		'types' => array('page'),
		'fields' => array(
			array(
				'type' => 'bloc',
				'subfields' => array(
					array(
						'type' => 'page_selector',
						'post_type' => 'wpcf7_contact_form',
						'name' => 'contact_form_id',
						'label' => __('Selectionnez votre formulaire', 'extra-admin')
					)
				)
			)
		)
	));
}