<?php

function extra_footer_global_options($sections) {

	$sections[] = array(
		'icon' => 'el-icon-website',
		'title' => __('Pied de page', 'extra-admin'),
		'desc' => null,
		'fields' => array(
			array(
				'id' => 'value_text_1',
				'type' => 'text',
				'title' => __('Première valeur', 'extra-admin'),
			),
			array(
				'id' => 'value_icon_1',
				'type' => 'select',
				'options' => array(
					'icon-credit-card' => 'Carte de crédit',
					'icon-france' => 'France',
					'icon-heart' => 'Coeur',
					'icon-box' => 'Boite',
				),
				'title' => __('Icône', 'extra-admin'),
			),
			array(
				'id'   =>'value_divider_1',
				'type' => 'divide'
			),

			array(
				'id' => 'value_text_2',
				'type' => 'text',
				'title' => __('Deuxième valeur', 'extra-admin'),
			),
			array(
				'id' => 'value_icon_2',
				'type' => 'select',
				'options' => array(
					'icon-credit-card' => 'Carte de crédit',
					'icon-france' => 'France',
					'icon-heart' => 'Coeur',
					'icon-box' => 'Boite',
				),
				'title' => __('Icône', 'extra-admin'),
			),
			array(
				'id'   =>'value_divider_2',
				'type' => 'divide'
			),

			array(
				'id' => 'value_text_3',
				'type' => 'text',
				'title' => __('Troisième valeur', 'extra-admin'),
			),
			array(
				'id' => 'value_icon_3',
				'type' => 'select',
				'options' => array(
					'icon-credit-card' => 'Carte de crédit',
					'icon-france' => 'France',
					'icon-heart' => 'Coeur',
					'icon-box' => 'Boite',
				),
				'title' => __('Icône', 'extra-admin'),
			),
			array(
				'id'   =>'value_divider_3',
				'type' => 'divide'
			),

			array(
				'id' => 'value_text_4',
				'type' => 'text',
				'title' => __('Quatrième valeur', 'extra-admin'),
			),
			array(
				'id' => 'value_icon_4',
				'type' => 'select',
				'options' => array(
					'icon-credit-card' => 'Carte de crédit',
					'icon-france' => 'France',
					'icon-heart' => 'Coeur',
					'icon-box' => 'Boite',
				),
				'title' => __('Icône', 'extra-admin'),
			),
			array(
				'id'   =>'value_divider_4',
				'type' => 'divide'
			),


			array(
				'id' => 'contact_title',
				'type' => 'text',
				'title' => __('Titre du bloc contact', 'extra-admin'),
			),
			array(
				'id' => 'contact_content',
				'type' => 'textarea',
				'title' => __('Contenu du bloc contact', 'extra-admin'),
			),
			array(
				'id' => 'contact_email',
				'type' => 'text',
				'title' => __('Email', 'extra-admin'),
			),
			array(
				'id' => 'contact_phone',
				'type' => 'text',
				'title' => __('Téléphone', 'extra-admin'),
			),
			array(
				'id'   =>'contact_divider',
				'type' => 'divide'
			),

			array(
				'id' => 'social_network_title',
				'type' => 'text',
				'title' => __('Titre du bloc réseaux sociaux', 'extra-admin'),
			),
			array(
				'id' => 'facebook_url',
				'type' => 'text',
				'title' => __('Lien facebook', 'extra-admin'),
			),
			array(
				'id' => 'twitter_url',
				'type' => 'text',
				'title' => __('Lien twitter', 'extra-admin'),
			),
			array(
				'id' => 'pinterest_url',
				'type' => 'text',
				'title' => __('Lien pinterest', 'extra-admin'),
			),
			array(
				'id' => 'youtube_url',
				'type' => 'text',
				'title' => __('Lien youtube', 'extra-admin'),
			),
			array(
				'id' => 'instagram_url',
				'type' => 'text',
				'title' => __('Lien instagram', 'extra-admin'),
			),
			array(
				'id' => 'sarbacane_url',
				'type' => 'text',
				'title' => __('Api sarbacane', 'extra-admin'),
			),
		)
	);

	return $sections;
}
add_filter('extra_default_global_options_section', 'extra_footer_global_options', 10, 1);

function get_svg_for_icon ($icon_name) {
	$svg = '';
	switch ($icon_name) {
		case 'icon-credit-card' :
			$svg = '<svg class="icon icon-credit-card"><use xlink:href="#icon-credit-card"></use></svg>';
			break;
		case 'icon-france' :
			$svg = '<svg class="icon icon-france"><use xlink:href="#icon-france"></use></svg>';
			break;
		case 'icon-heart' :
			$svg = '<svg class="icon icon-heart"><use xlink:href="#icon-heart"></use></svg>';
			break;
		case 'icon-box' :
			$svg = '<svg class="icon icon-box"><use xlink:href="#icon-box"></use></svg>';
			break;
	}

	return $svg;
}






/**********************
 *
 *
 * CREATE SARBACANE SUBSCRIPTION
 *
 *
 *********************/
function extra_sarbacane_subscription() {
	global $extra_options;

	$response = 'error';
	if (isset ($extra_options['sarbacane_url'], $_POST['email'])) {
		$url = $extra_options['sarbacane_url'].'?action=INSERT&Email='.$_POST['email'];
		if ('OK' === file_get_contents($url)) {
			$response = 'success';
		}
	}
	echo $response;
	die();
}
add_action( 'wp_ajax_nopriv_extra_sarbacane_subscription', 'extra_sarbacane_subscription' );
add_action( 'wp_ajax_extra_sarbacane_subscription', 'extra_sarbacane_subscription' );



function extra_footer_enqueue_scripts() {
	wp_enqueue_script( 'extra-sarbacane', THEME_MODULES_URI . '/footer/front/js/sarbacane.js', array('jquery', 'extra-common', 'extra-flash-messages'), null, true );

	wp_localize_script('extra-sarbacane', 'extraSarbacaneOptions', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'messages' => array(
			'error' => __('Oups...<br>Il y a eu un problème.<br>Merci de réessayer un peu plus tard.', 'extra'),
			'success' => __('Vous avez été ajouté à notre newsletter !', 'extra'),
			'notValid' => __("Attention !<br>Votre email n'est pas valide...", 'extra'),
		)
	));
}
add_action( 'wp_enqueue_scripts', 'extra_footer_enqueue_scripts' );