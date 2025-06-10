<?php
/**
 * Checkout coupon form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! WC()->cart->coupons_enabled() ) {
	return;
}
/* <a class="link-button" id="extra_coupon_code_link" href="#"><?php _e( 'Apply Coupon', 'woocommerce' ); ?></a> */
?>

<div class="checkout_coupon">
	<h3><?php _e("Qui c'est qui a un code promo ?", 'extra'); ?></h3>
	<div class="checkout_coupon_messages"></div>
	<div class="extra-inline-form">
		<input type="text" name="coupon_code" class="input-text" placeholder="<?php _e( 'Coupon code', 'woocommerce' ); ?>" id="coupon_code" value="" />
		<input type="hidden" name="apply_coupon" value="<?php _e( "C'est moi !", 'extra' ); ?>" />
		<button type="button" class="button extra-button coupon_code_button" id="coupon_code_button">
			<?php _e( "C'est moi !", 'extra' ); ?>
		</button>
	</div>
</div>