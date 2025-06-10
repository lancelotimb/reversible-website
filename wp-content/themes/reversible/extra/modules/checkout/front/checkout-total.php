<div class="extra-checkout-total extra-table-total">

	<div class="cart-subtotal">
		<span><?php _e( "Sous-total : ", 'extra' ); ?></span>
		<span><?php wc_cart_totals_subtotal_html(); ?></span>
	</div>

	<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
		<div class="cart-discount coupon-<?php echo esc_attr( $code ); ?>">
			<span><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
			<span class="color"><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
		</div>
	<?php endforeach; ?>

	<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

		<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

		<?php wc_cart_totals_shipping_html(); ?>

		<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

	<?php else : ?>
		<div class="shipping">
			<span><?php _e( "Livraison : ", 'extra' ); ?></span>
			<span>--</span>
		</div>
	<?php endif; ?>

	<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
		<div class="fee">
			<span><?php echo esc_html( $fee->name ); ?></span>
			<span><?php wc_cart_totals_fee_html( $fee ); ?></span>
		</div>
	<?php endforeach; ?>

	<?php

//	WC()->cart->tax_display_cart = 'excl';
//	var_dump(WC()->cart->tax_display_cart);
	?>

	<?php if ( WC()->cart->tax_display_cart === 'excl' ) : ?>
		<?php if ( get_option( 'woocommerce_tax_total_display' ) === 'itemized' ) : ?>
			<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
				<div class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
					<span><?php echo esc_html( $tax->label ); ?></span>
					<span><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
				</div>
			<?php endforeach; ?>
		<?php else : ?>
			<div class="tax-total">
				<span class="tax-countries"><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span>
				<span class="tax-total"><?php echo wc_price( WC()->cart->get_taxes_total() ); ?></span>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

	<div class="order-total">
		<span><?php _e( "Total :", 'extra' ); ?></span>
		<span><?php wc_cart_totals_order_total_html(); ?></span>
	</div>

	<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
</div>

