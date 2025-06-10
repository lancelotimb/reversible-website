<?php
/**
 * Thankyou page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( $order ) : ?>
	<h1><?php _e("Confirmation de votre commande", 'extra'); ?></h1>

	<?php
	/**********************
	 *
	 *
	 * INSTRUCTIONS
	 *
	 *
	 *********************/
	include_once THEME_MODULES_PATH . '/checkout/front/back-to-button.php';
	?>

	<?php do_action( 'woocommerce_thankyou', $order->id ); ?>
<?php else : ?>
	<h1><?php _e("Confirmation", 'extra'); ?></h1>

	<?php
	/**********************
	 *
	 *
	 * INSTRUCTIONS
	 *
	 *
	 *********************/
	include_once THEME_MODULES_PATH . '/checkout/front/back-to-button.php';
	?>

	<p><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?></p>
<?php endif;