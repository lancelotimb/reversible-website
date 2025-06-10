<?php

/**********************
 *
 *
 * ENQUEUE ASSETS
 *
 *
 *********************/
function extra_shopping_cart_enqueue_assets() {
	// REMOVE WOOCOMMERCE UNUSED SCRIPTS
	wp_deregister_script('wc-add-to-cart');
	wp_dequeue_script('wc-add-to-cart');
	wp_deregister_script('wc-cart-fragments');
	wp_dequeue_script('wc-cart-fragments');
	wp_deregister_script('wc-single-product');
	wp_dequeue_script('wc-single-product');

	if (!is_checkout()) {
		wp_deregister_script('woocommerce');
		wp_dequeue_script('woocommerce');
		wp_deregister_script('jquery-blockui');
		wp_dequeue_script('jquery-blockui');
		wp_deregister_script('jquery-cookie');
		wp_dequeue_script('jquery-cookie');
	}

	wp_enqueue_style( 'extra-shopping-menu', THEME_MODULES_URI.'/shopping-menu/front/css/shopping-menu.less', array(), false, 'all' );
	wp_enqueue_style('extra-shopping-cart', THEME_MODULES_URI.'/shopping-menu/front/css/cart-detail.less', array(), false, 'all' );

	wp_enqueue_script('extra-shopping-cart', THEME_MODULES_URI.'/shopping-menu/front/js/cart.js', array('extra', 'extra-common', 'extra-flash-messages', 'extra-product-thumbnail-loader', 'jquery'), false, true);

	ob_start();
	include  THEME_MODULES_PATH.'/shopping-menu/front/cart-detail.php';
	$cartDetailTemplate = ob_get_contents();
	ob_end_clean();

	wp_localize_script('extra-shopping-cart', 'cartOptions', array(
		'templates' => array(
			'cartDetailContainer' => $cartDetailTemplate
		)
	));

	wp_localize_script(
		'extra-shopping-cart',
		'shoppingCart',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' )
		)
	);
}
add_action('wp_enqueue_scripts', 'extra_shopping_cart_enqueue_assets');


/**********************
 *
 *
 * AJAX LOAD CART
 *
 *
 *********************/
function extra_get_shopping_cart() {
	global $extra_fill_cart, $woocommerce, $extra_fill_cart, $product, $post;
	$extra_fill_cart = true;

	$cart = $woocommerce->cart->get_cart();

	$products = array();
	foreach ( $cart as $cart_key => $cart_item ) {
		$quantity = $cart_item['quantity'];
		$product = $cart_item['data'];
		$post = $product->post;

		ob_start();
		include get_stylesheet_directory() . '/woocommerce/content-product.php';
		$productTemplate = ob_get_contents();
		ob_end_clean();

//		for ($quantity_index = 0; $quantity_index < $quantity; $quantity_index++) {
//			$products[] = array (
//				'id' => $post->ID,
//				'quantity' => 1,
//				'price' => floatval($product->price),
//				'template' => $productTemplate
//			);
//		}

		$products[] = array (
			'id' => $post->ID,
			'quantity' => $quantity,
			'price' => floatval($product->price),
			'template' => $productTemplate
		);
	}

	ob_start();
	include  THEME_MODULES_PATH.'/shopping-menu/front/account.php';
	$account = ob_get_contents();
	ob_end_clean();

	$response = array (
		'account' => $account,
		'cart' => array (
			'count' => $woocommerce->cart->cart_contents_count,
			'price' => $woocommerce->cart->get_cart_subtotal(),
			'products' => $products
		)
	);

	return $response;
}


function extra_exit_shopping_cart() {
	$response = extra_get_shopping_cart();

	echo json_encode($response);
	session_write_close();
	die();
}

add_action( 'wp_ajax_nopriv_extra_get_shopping_cart', 'extra_exit_shopping_cart' );
add_action( 'wp_ajax_extra_get_shopping_cart', 'extra_exit_shopping_cart' );


/**********************
 *
 *
 * AJAX REMOVE FROM CART
 *
 *
 *********************/
function extra_remove_from_shopping_cart() {
	global $woocommerce;

	$id = intval($_REQUEST['product_id']);
	$cart_id = $woocommerce->cart->generate_cart_id($id);
	$cart_item_id = $woocommerce->cart->find_product_in_cart($cart_id);

	if($cart_item_id){
//		$item = $woocommerce->cart->get_cart_item($cart_item_id);
//		$quantity = isset($item['quantity']) ? $item['quantity'] - 1 : 0;
//
//		$woocommerce->cart->set_quantity($cart_item_id, $quantity);

		$woocommerce->cart->set_quantity($cart_item_id, 0);
	}
	extra_exit_shopping_cart();
}
add_action( 'wp_ajax_nopriv_extra_remove_from_shopping_cart', 'extra_remove_from_shopping_cart' );
add_action( 'wp_ajax_extra_remove_from_shopping_cart', 'extra_remove_from_shopping_cart' );


/**********************
 *
 *
 * AJAX ADD TO CART
 *
 *
 *********************/
function extra_add_to_shopping_cart() {
	global $woocommerce;

	$id = intval($_POST['extra-add-to-cart']);
	$quantity = intval($_POST['extra-quantity']);

	if ($quantity < 1) {
		$quantity = 1;
	}

	$woocommerce->cart->add_to_cart($id, $quantity);

	$notices = wc_get_notices();
	wc_clear_notices();

	$response = extra_get_shopping_cart();
	$response['notices'] = $notices;

	echo json_encode($response);
	session_write_close();
	die();
}
add_action( 'wp_ajax_nopriv_extra_add_to_shopping_cart', 'extra_add_to_shopping_cart' );
add_action( 'wp_ajax_extra_add_to_shopping_cart', 'extra_add_to_shopping_cart' );


/**********************
 *
 *
 * OVERRIDE WOOCOMMERCE TRANSLATION !!
 *
 *
 *********************/
// apply_filters( 'gettext', $translations, $text, $domain );
// __( 'You cannot add another &quot;%s&quot; to your cart.', 'woocommerce' )
function extra_cart_override_translation($translations, $text, $domain) {
	if ($domain == 'woocommerce') {
		switch ($text) {
			case 'You cannot add another &quot;%s&quot; to your cart.' :
				$translations = __( 'Ce produit est déjà dans votre panier.', 'extra' );
				break;

			case 'You cannot add that amount to the cart &mdash; we have %s in stock and you already have %s in your cart.' :
				$translations = __( 'Vous ne pouvez pas en ajouter autant...<br><br>Il en reste %s et vous en avez %s dans votre panier.', 'extra' );
				break;

			case 'You cannot add that amount of &quot;%s&quot; to the cart because there is not enough stock (%s remaining).' :
				$translations = __( 'Vous ne pouvez pas en ajouter autant...', 'extra' );
				break;

			case 'You cannot add &quot;%s&quot; to the cart because the product is out of stock.' :
				$translations = __( 'Oh non...<br> Ce produit n\'est plus disponible.', 'extra' );
				break;

			default :
				break;
		}
	}

	return $translations;

}
add_filter('gettext', 'extra_cart_override_translation', 10, 3);


function extra_is_in_cart() {
	global $woocommerce, $post;

	$cart_id = $woocommerce->cart->generate_cart_id($post->ID);
	$cart_item_id = $woocommerce->cart->find_product_in_cart($cart_id);

	return !empty($cart_item_id);
}