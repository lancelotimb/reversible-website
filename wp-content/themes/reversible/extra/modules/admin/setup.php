<?php

/************************
 *
 *
 * REMOVE  UNUSED SHIPPING METHODS
 *
 *
 ***********************/
function extra_woocommerce_shipping_methods($methods) {
//	var_dump($methods);
	$methods = array(
		'WC_Shipping_Free_Shipping',
		'MH_Table_Rate_Plus_Shipping_Method',
	);

	return $methods;
}
add_filter('woocommerce_shipping_methods', 'extra_woocommerce_shipping_methods', 10, 1);


/************************
 *
 *
 * HIDE OTHE SHIPPING METHOD WHEN FREE SHIPPING IS AVAILABLE
 *
 *
 ***********************/
/**
 * woocommerce_package_rates is a 2.1+ hook
 */
add_filter( 'woocommerce_package_rates', 'extra_hide_shipping_when_free_is_available', 10, 2 );
/**
 * Hide shipping rates when free shipping is available
 *
 * @param array $rates Array of rates found for the package
 * @param array $package The package array/object being shipped
 * @return array of modified rates
 */
function extra_hide_shipping_when_free_is_available( $rates, $package ) {

	// Only modify rates if free_shipping is present
	if ( isset( $rates['free_shipping'] ) ) {

		// To unset a single rate/method, do the following. This example unsets flat_rate shipping
		unset( $rates['flat_rate'] );

		// To unset all methods except for free_shipping, do the following
		$free_shipping          = $rates['free_shipping'];
		$rates                  = array();
		$rates['free_shipping'] = $free_shipping;
	}

	return $rates;
}



/************************
 *
 *
 *
 *
 *
 ***********************/
function extra_admin_load_theme_textdomain () {
	load_theme_textdomain('mhtr', THEME_PATH . '/languages/mhtr');
	load_theme_textdomain('woocommerce-simply-order-export', THEME_PATH . '/languages/woocommerce-simply-order-export');
}
add_action('after_setup_theme', 'extra_admin_load_theme_textdomain');
