<?php


function extra_about_gallery_function( $atts ) {
	ob_start();
	$html = '';
	include THEME_MODULES_PATH . '/about/front/gallery.php';
	$html .= ob_get_clean();

	return $html;
}
add_shortcode( 'extra_about_gallery', 'extra_about_gallery_function' );

add_action('init', 'extra_about_metabox_init');
function extra_about_metabox_init () {
	global $about_metabox;
	$about_metabox = new ExtraMetaBox(array(
		'include_template' => 'template-about.php',
		'id' => '_about',
		'hide_editor' => false,
		'hide_ui' => true,
		'title' => __("Paramètres page à propos", "extra"),
		'types' => array('page'),
		'fields' => array(
			array(
				'title' => __('Galerie', 'extra-admin'),
				'type' => 'bloc',
				'subfields' => array (
					array(
						'type' => 'label',
						'title' => __('Utilisation', 'extra-admin'),
						'label' => __('Pour utiliser la galerie placer le contenu suivant dans l\'éditeur de texte : [extra_about_gallery]', 'extra-admin')
					),
					array(
						'type' => 'tabs',
						'name' => 'about',
						'fixed' => false,
						'bloc_label' => 'Image',
						'subfields' => array(
							array(
								'type' => 'text',
								'name' => 'label',
								'label' => __('Légende', 'extra-admin')
							),
							array(
								'type' => 'image',
								'name' => 'image_left',
								'label' => 'Image gauche (780x200)'
							),
							array(
								'type' => 'image',
								'name' => 'image_right',
								'label' => 'Image droite (780x200)'
							),
						)
					)
				)
			)
		)
	));
}


function extra_about_enqueue_assets() {
	if ( is_page_template('template-about.php') ) {
		wp_enqueue_style( 'extra-about', THEME_MODULES_URI . '/about/front/css/about.less' );
		wp_enqueue_script('extra-about', THEME_MODULES_URI . '/about/front/js/about.js', array('extra-common'), null, true);
	}
}
add_action( 'wp_enqueue_scripts', 'extra_about_enqueue_assets' );