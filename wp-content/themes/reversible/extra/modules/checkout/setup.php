<?php
/************************
 *
 *
 * VALIDATIONS
 *
 *
 ***********************/
include_once THEME_MODULES_PATH . '/checkout/setup-validation.php';
include_once THEME_MODULES_PATH . '/checkout/setup-order.php';

/**********************
 *
 *
 * IF LOGOUT VIA CHEKOUT PAGE DO NOT CLEAR THE CART !!!
 *
 *
 *********************/
function extra_logout_on_checkout() {
	if (isset($_GET['keepcart'])) {
		global $woocommerce;
		if ($woocommerce && $woocommerce->session) {
			remove_action('wp_logout', array($woocommerce->session, 'destroy_session'));
		}
	}
}
add_action('wp_logout', 'extra_logout_on_checkout', 1);

function extra_checkout_update_order_review($post_data) {
	$extra_remove_from_cart_id = null;

	$posts_array = explode('&', $post_data);
	foreach ($posts_array as $post_param) {

		if (substr($post_param, 0, strlen('extra_remove_from_cart_id')) == 'extra_remove_from_cart_id') {
			$extra_remove_from_cart_id = intval(substr($post_param, strlen('extra_remove_from_cart_id=')));
			break;
		}
	}

	if ($extra_remove_from_cart_id !== null && !empty($extra_remove_from_cart_id)) {
		global $woocommerce;

		$cart_id = $woocommerce->cart->generate_cart_id($extra_remove_from_cart_id);
		$cart_item_id = $woocommerce->cart->find_product_in_cart($cart_id);

		if($cart_item_id){
			$woocommerce->cart->set_quantity($cart_item_id, 0);
//			wc_add_notice(__("Le produit a été retiré du panier", 'extra'), 'success');
		}
	}
}
add_action('woocommerce_checkout_update_order_review', 'extra_checkout_update_order_review');

/**********************
 *
 *
 * ENQUEUE ASSETS
 *
 *
 *********************/
function extra_checkout_enqueue_assets() {
	if (is_checkout()) {
		wp_enqueue_style('extra-form', THEME_URI.'/assets/css/form.less', array('extra-content'));
		wp_enqueue_style('extra-checkout', THEME_MODULES_URI.'/checkout/front/css/checkout.less', array('extra-content', 'extra-form', 'select2'));

		if (is_wc_endpoint_url()) {
			wp_enqueue_style('extra-order', THEME_MODULES_URI.'/checkout/front/css/order.less', array('extra-checkout'));
		}

		// EXTRA-SLIDER
		wp_enqueue_script('extra-checkbox', EXTRA_URI . '/assets/js/lib/extra.checkbox.js', array('jquery', 'extra', 'tweenmax'), null, true);

		wp_deregister_script('wc-checkout');
		wp_register_script( 'wc-checkout', THEME_MODULES_URI.'/checkout/front/js/wc-checkout.js', array( 'jquery', 'woocommerce', 'wc-country-select', 'wc-address-i18n', 'extra-shopping-cart' ) );
		wp_enqueue_script( 'wc-checkout' );

//		wp_enqueue_script('stacktable', THEME_URI.'/assets/js/lib/stacktable.js', array('jquery'), false, true);
		wp_enqueue_script('extra-checkout', THEME_MODULES_URI.'/checkout/front/js/checkout.js', array('extra-common', 'wc-checkout', 'extra-checkbox', 'extra-shopping-cart'), false, true);


		$step = 0;
		if (isset($_GET['justlogged']) && $_GET['justlogged'] == '1') {
			$step = 1;
		}
		wp_localize_script('extra-checkout', 'extra_checkout_options', array(
			'step' => $step
		));
	}
}
add_action('wp_enqueue_scripts', 'extra_checkout_enqueue_assets');

/************************
 *
 *
 * REBUILD CHECKOUT PAGE
 *
 *
 ***********************/
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
add_action( 'extra_login_checkout_form', 'woocommerce_checkout_login_form', 10 );


/************************
 *
 *
 * COUPON FORM
 *
 *
 ***********************/
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
//add_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_coupon_form', 15 );
add_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_coupon_form', 15 );
//function extra_checkout_coupon_proxy () {
//	include THEME_MODULES_PATH . '/checkout/front/coupon-proxy.php';
//}
//add_action ('woocommerce_checkout_order_review', 'extra_checkout_coupon_proxy');

/************************
 *
 *
 * CHECKOUT TOTAL
 *
 *
 ***********************/
function extra_checkout_total() {
	include THEME_MODULES_PATH . '/checkout/front/checkout-total.php';
}
add_action( 'woocommerce_checkout_order_review', 'extra_checkout_total', 16 );

function extra_woocommerce_update_order_review_fragments($fragments) {
	if (array_key_exists('.woocommerce-checkout-review-order-table', $fragments)) {
		ob_start();
		$html = '';
		extra_checkout_total();
		$html .= ob_get_clean();
		ob_end_clean();

		$fragments['.extra-checkout-total'] = $html;

		// For update shopping cart
		$fragments['extra-shopping-cart'] = extra_get_shopping_cart();
	}
	if (array_key_exists('form.woocommerce-checkout', $fragments)) {
		$html = $fragments['form.woocommerce-checkout'];
		unset($fragments['form.woocommerce-checkout']);
		$fragments['extra-empty-cart'] = '';
	}
	return $fragments;
}
add_filter('woocommerce_update_order_review_fragments', 'extra_woocommerce_update_order_review_fragments');

function extra_woocommerce_cart_shipping_method_full_label ($label, $method) {
	$label = $method->label;

	if ( $method->cost > 0 ) {
		if ( WC()->cart->tax_display_cart == 'excl' ) {
			$label .= ' : ' . wc_price( $method->cost );
			if ( $method->get_shipping_tax() > 0 && WC()->cart->prices_include_tax ) {
				$label .= ' <small>' . WC()->countries->ex_tax_or_vat() . '</small>';
			}
		} else {
			$label .= ' : ' . wc_price( $method->cost + $method->get_shipping_tax() );
			if ( $method->get_shipping_tax() > 0 && ! WC()->cart->prices_include_tax ) {
				$label .= ' <small>' . WC()->countries->inc_tax_or_vat() . '</small>';
			}
		}
	} elseif ( $method->id !== 'free_shipping' ) {
		$label .= ' (' . __( 'Free', 'woocommerce' ) . ')';
	}

	return $label;
}
add_filter('woocommerce_cart_shipping_method_full_label', 'extra_woocommerce_cart_shipping_method_full_label', 10, 2);


function extra_woocommerce_cart_totals_coupon_html($value, $coupon) {
	if ( is_string( $coupon ) ) {
		$coupon = new WC_Coupon( $coupon );
	}

	$value  = array();

	if ( $amount = WC()->cart->get_coupon_discount_amount( $coupon->code, WC()->cart->display_cart_ex_tax ) ) {
		$discount_html = '-' . wc_price( $amount );
	} else {
		$discount_html = '';
	}

	$value[] = apply_filters( 'woocommerce_coupon_discount_amount_html', $discount_html, $coupon );

	if ( $coupon->enable_free_shipping() ) {
		$value[] = __( 'Free shipping coupon', 'woocommerce' );
	}

	// get rid of empty array elements
	$value = array_filter( $value );
	$value = implode( ', ', $value ) . ' <a href="' . esc_url( add_query_arg( 'remove_coupon', urlencode( $coupon->code ), defined( 'WOOCOMMERCE_CHECKOUT' ) ? WC()->cart->get_checkout_url() : WC()->cart->get_cart_url() ) ) . '" class="woocommerce-remove-coupon" data-coupon="' . esc_attr( $coupon->code ) . '">' . __( 'Supprimer', 'extra' ) . '</a>';

	return $value;
}
add_filter('woocommerce_cart_totals_coupon_html', 'extra_woocommerce_cart_totals_coupon_html', 10, 2);


function extra_woocommerce_cart_totals_coupon_label( $html, $coupon ) {
	if ( is_string( $coupon ) )
		$coupon = new WC_Coupon( $coupon );

	return esc_html( __( 'Coupon:', 'woocommerce' ) );
}
add_filter('woocommerce_cart_totals_coupon_label', 'extra_woocommerce_cart_totals_coupon_label', 10, 2);

/************************
 *
 *
 * REMOVE ORDER NOTES
 *
 *
 ***********************/
add_filter( 'woocommerce_enable_order_notes_field', '__return_false', 1);


/************************
 *
 *
 * PAYMENT
 *
 *
 ***********************/
function extra_order_button_text($order_button_text) {
	$order_button_text = __("Valider ma commande", 'extra');

	return $order_button_text;
}
add_filter('woocommerce_order_button_text', 'extra_order_button_text', 1);

function extra_woocommerce_gateway_icon ($html, $gateway_id) {
	if ($gateway_id == 'paypal') {
		$html = '';
	}
	return $html;
}
add_filter('woocommerce_gateway_icon', 'extra_woocommerce_gateway_icon', 10, 2);


/************************
 *
 *
 * THANK YOU
 *
 *
 ***********************/
//remove_action( 'woocommerce_thankyou', 'woocommerce_order_details_table', 10 );



/************************
 *
 *
 * REDIRECT TO SHOP IF CART PAGE
 *
 *
 ***********************/
function extra_checkout_template_redirect () {
	if (is_cart()) {
		if (isset ($_REQUEST['cancel_order']) && $_REQUEST['cancel_order'] == 'true') {
			$checkout_page_id = wc_get_page_id( 'checkout' );
			$permalink = get_permalink($checkout_page_id);
		} else {
			$shop_page_id = wc_get_page_id('shop');
			$permalink = get_permalink($shop_page_id);

			if ( sizeof( WC()->cart->get_cart() ) == 0 ) {
				$message = '';
				ob_start();
				get_template_part("extra/modules/checkout/front/empty-cart");
				$message = ob_get_contents();
				ob_end_clean();


				// TODO DO AJAX REQUEST TO GET AJAX MESSAGE
				extra_add_flash_message($shop_page_id, $message);
			}
		}

		session_write_close();
		wp_redirect( $permalink );
		die;
	}
}
add_action( 'template_redirect', 'extra_checkout_template_redirect' );

//function extra_woocommerce_get_cancel_order_url($url) {
//	if (WC()->session->order_awaiting_payment != null) {
//		$order_id = WC()->session->order_awaiting_payment;
//		$order = wc_get_order($order_id);
//
//		$cancel_endpoint =
//
//		$url = wp_nonce_url( add_query_arg( array(
//			'cancel_order' => 'true',
//			'order'        => $this->order_key,
//			'order_id'     => $this->id,
//			'redirect'     => $redirect
//		), $cancel_endpoint ), 'woocommerce-cancel_order' )
//	}
//}
//add_filter('woocommerce_get_cancel_order_url', 'extra_woocommerce_get_cancel_order_url');


//function extra_woocommerce_cart_totals_order_total_html ($old_value) {
//
//	$value = '<strong>' . WC()->cart->get_total() . '</strong> ';
//
//	// If prices are tax inclusive, show taxes here
//	if ( wc_tax_enabled() && WC()->cart->tax_display_cart == 'incl' ) {
//		$tax_string_array = array();
//
//		if ( get_option( 'woocommerce_tax_total_display' ) == 'itemized' ) {
//			foreach ( WC()->cart->get_tax_totals() as $code => $tax )
//				$tax_string_array[] = sprintf( '%s %s', $tax->formatted_amount, $tax->label );
//		} else {
//			$tax_string_array[] = sprintf( '%s %s', wc_price( WC()->cart->get_taxes_total( true, true ) ), WC()->countries->tax_or_vat() );
//		}
//
//		if ( ! empty( $tax_string_array ) ) {
//			$value .= '<small class="includes_tax">' . sprintf( __( '(Includes %s)', 'woocommerce' ), implode( ', ', $tax_string_array ) ) . '</small>';
//		}
//	}
//
//	return $old_value;
//}
//add_filter ('woocommerce_cart_totals_order_total_html', 'extra_woocommerce_cart_totals_order_total_html');

//
//function extra_woocommerce_cart_subtotal ($cart_subtotal, $compound, $cart) {
//
//	if ( !$compound ) {
//		// Display varies depending on settings
//		if ( $cart->tax_display_cart === 'incl' ) {
//			$cart_subtotal = wc_price( $cart->subtotal );
//
//			if ( $cart->tax_total > 0 && $cart->prices_include_tax ) {
//				$cart_subtotal .= ' <small>' . WC()->countries->inc_tax_or_vat() . '</small>';
//			}
//
//		}
//	}
//
//
//	return $cart_subtotal;
//}
//add_filter ('woocommerce_cart_subtotal', 'extra_woocommerce_cart_subtotal', 10, 3);
//
//
//function extra_woocommerce_cart_total ($total) {
//	if ( WC()->cart->tax_display_cart === 'incl' ) {
//		if ( WC()->cart->tax_total > 0 && WC()->cart->prices_include_tax ) {
//			$total .= ' <small>' . WC()->countries->inc_tax_or_vat() . '</small>';
//		}
//	}
//
//	return $total;
//}
//add_filter ('woocommerce_cart_total', 'extra_woocommerce_cart_total');