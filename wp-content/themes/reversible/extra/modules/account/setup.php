<?php

/**********************
 *
 *
 * ENQUEUE ASSETS
 *
 *
 *********************/
function extra_account_enqueue_assets() {
	if (is_account_page()) {
		wp_enqueue_style('extra-account', THEME_MODULES_URI.'/account/front/css/account.less', array('extra-content'));

		wp_enqueue_script('stacktable', THEME_URI.'/assets/js/lib/stacktable.js', array('jquery'), false, true);
		wp_enqueue_script('extra-account', THEME_MODULES_URI.'/account/front/js/account.js', array('extra-common', 'stacktable'), false, true);

		if (is_view_order_page()) {
			wp_enqueue_style('extra-form', THEME_URI.'/assets/css/form.less', array('extra-content'));
			wp_enqueue_style('extra-checkout', THEME_MODULES_URI.'/checkout/front/css/checkout.less', array('extra-content', 'extra-form', 'select2'));
			wp_enqueue_style('extra-order', THEME_MODULES_URI.'/checkout/front/css/order.less', array('extra-checkout'));

			wp_enqueue_style('extra-view-order', THEME_MODULES_URI.'/account/front/css/view-order.less', array('extra-order'));
		}

		if (is_wc_endpoint_url('edit-account') || is_wc_endpoint_url('edit-address') || !is_user_logged_in()) {
			wp_enqueue_style('extra-form', THEME_URI.'/assets/css/form.less', array('extra-content'));
			wp_enqueue_style('extra-account-form', THEME_MODULES_URI.'/account/front/css/account-form.less', array('extra-content', 'extra-form', 'select2'));


			wp_enqueue_script('extra-checkbox', EXTRA_URI . '/assets/js/lib/extra.checkbox.js', array('jquery', 'extra', 'tweenmax'), null, true);
			wp_enqueue_script('extra-account-form', THEME_MODULES_URI.'/account/front/js/account-form.js', array('extra-common', 'extra-checkbox'), false, true);
		}
	}
}
add_action('wp_enqueue_scripts', 'extra_account_enqueue_assets');


/************************
 *
 *
 * OVERRIDE WOOCOMMERCE TRANSLATIONS
 *
 *
 ***********************/
function extra_account_override_translation($translations, $text, $domain) {
	if ($domain == 'woocommerce') {
		switch ($text) {
			case 'Edit Address' :
				$translations = __( "Modifier l'adresse", 'extra' );
				break;
			case 'Edit Account Details' :
				$translations = __( 'Modifier le compte', 'extra' );
				break;
			case 'Lost Password' :
				$translations = __( 'Mot de passe perdu ?', 'extra' );
				break;
			case 'Order %s' :
				$translations = __( 'Commande<br>%s', 'extra' );
				break;
			default :
				break;
		}
	}

	// _x( '#', 'hash before order number', 'woocommerce' )

	return $translations;

}
add_filter('gettext', 'extra_account_override_translation', 10, 3);

function extra_account_override_translation_with_context($translated, $text, $context, $domain) {
	if ($domain == 'woocommerce') {
		switch ($text) {
			case '#' :
				$translated = __( 'n°', 'extra' );
				break;
			default :
				break;
		}
	}

	return $translated;
}
add_filter('gettext_with_context', 'extra_account_override_translation_with_context', 10, 4);