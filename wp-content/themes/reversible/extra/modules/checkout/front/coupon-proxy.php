<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! WC()->cart->coupons_enabled() ) {
	return;
}
?>
<h3><?php _e("Code promo", 'extra'); ?></h3>
<input type="text" id="extra_coupon_code_proxy" name="extra_coupon_code" class="input-text" placeholder="<?php _e( 'Coupon code', 'woocommerce' ); ?>" value="" />
<a class="link-button" id="extra_coupon_code_proxy_link" href="#"><?php _e( 'Apply Coupon', 'woocommerce' ); ?></a>