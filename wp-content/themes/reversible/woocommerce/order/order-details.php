<?php
/**
 * Order details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$order = wc_get_order( $order_id );

?>
<table class="shop_table order_details extra-product-table extra-order-table">
	<thead>
		<tr>
			<th class="table-column-1"><?php _e("Produit", 'extra'); ?></th>
			<th class="table-column-2"><?php _e("Modèle / Référence", 'extra'); ?></th>
			<th class="table-column-3"><?php _e("Prix", 'extra'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		if ( sizeof( $order->get_items() ) > 0 ) {

			foreach( $order->get_items() as $item_id => $item ) {
				$_product  = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
				$item_meta = new WC_Order_Item_Meta( $item['item_meta'], $_product );

				if ( apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
					include THEME_MODULES_PATH . '/checkout/front/order-item.php';
				}

				if ( $order->has_status( array( 'completed', 'processing' ) ) && ( $purchase_note = get_post_meta( $_product->id, '_purchase_note', true ) ) ) {
					?>
					<tr class="product-purchase-note">
						<td colspan="3"><?php echo wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ); ?></td>
					</tr>
					<?php
				}
			}
		}
		do_action( 'woocommerce_order_items_table', $order );
		?>
	</tbody>
</table>

<?php
/**********************
 *
 *
 * TOTAL
 *
 *
 *********************/
include_once THEME_MODULES_PATH . '/checkout/front/order-total.php';
?>


<?php
/**********************
 *
 *
 * INSTRUCTIONS
 *
 *
 *********************/
include_once THEME_MODULES_PATH . '/checkout/front/order-instructions.php';
?>




<?php
/**********************
 *
 *
 * ADDRESS
 *
 *
 *********************/
include_once THEME_MODULES_PATH . '/checkout/front/order-address.php';