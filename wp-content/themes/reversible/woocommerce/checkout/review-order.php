<?php
/**
 * Review order table
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<table class="shop_table woocommerce-checkout-review-order-table extra-checkout-cart extra-product-table">
	<thead>
		<tr>
			<th class="table-column-1"><?php _e("Produit", 'extra'); ?></th>
			<th class="table-column-2"><?php _e("Modèle / Référence", 'extra'); ?></th>
			<th class="table-column-3"><?php _e("Prix", 'extra'); ?></th>
			<th class="table-column-4"><?php _e("Retirer", 'extra'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
			do_action( 'woocommerce_review_order_before_cart_contents' );

			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					include THEME_MODULES_PATH . '/checkout/front/checkout-cart-item.php';
				}
			}

			do_action( 'woocommerce_review_order_after_cart_contents' );
		?>
	</tbody>
</table>