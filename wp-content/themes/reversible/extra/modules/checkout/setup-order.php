<?php

/************************
 *
 *
 * OVERRIDE WOOCOMMERCE TRANSLATIONS
 *
 *
 ***********************/
function extra_order_override_translation($translations, $text, $domain) {
	if ($domain == 'woocommerce') {
		switch ($text) {
			case 'Subtotal:' :
				$translations = __( 'Sous-total :', 'extra' );
				break;
			case 'Discount:' :
				$translations = __( 'Code promo :', 'extra' );
				break;
			case 'Shipping:' :
				$translations = __( 'Livraison :', 'extra' );
				break;
			case 'Order Received' :
				$translations = __( 'Merci ♥', 'extra' );
				break;
			default :
				break;
		}
	}

	return $translations;

}
add_filter('gettext', 'extra_order_override_translation', 10, 3);


/************************
 *
 *
 * OVERRIDE SHIPPING DISPLAY
 *
 *
 ***********************/
function extra_woocommerce_order_shipping_to_display ($shipping, $order) {


	$tax_display = $order->tax_display_cart;

	if ( $order->get_shipping_method() ) {

		$tax_text = '';

		if ( $tax_display == 'excl' ) {

			// Show shipping excluding tax
			$shipping = wc_price( $order->order_shipping, array('currency' => $order->get_order_currency()) );

			if ( $order->order_shipping_tax > 0 && $order->prices_include_tax ) {
				$tax_text = WC()->countries->ex_tax_or_vat() . ' ';
			}

		} else {

			// Show shipping including tax
			$shipping = wc_price( $order->order_shipping + $order->order_shipping_tax, array('currency' => $order->get_order_currency()) );

			if ( $order->order_shipping_tax > 0 && ! $order->prices_include_tax ) {
				$tax_text = WC()->countries->inc_tax_or_vat() . ' ';
			}

		}

		$shipping .= sprintf( __( '&nbsp;<small>%s</small>', 'woocommerce' ), $tax_text );

	} else {
		$shipping = __( 'Free!', 'woocommerce' );
	}
	return $shipping;
}
add_filter( 'woocommerce_order_shipping_to_display', 'extra_woocommerce_order_shipping_to_display', 10, 2 );