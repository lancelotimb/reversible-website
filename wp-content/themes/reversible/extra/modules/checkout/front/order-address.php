<div class="address-blocs">
	<div class="address-bloc address-bloc-billing">
		<h3 class="address-bloc-title"><?php _e( 'Billing Address', 'woocommerce' ); ?></h3>
		<address>
			<?php
			if ( ! $order->get_formatted_billing_address() ) {
				_e( 'N/A', 'woocommerce' );
			} else {
				echo $order->get_formatted_billing_address();
			}
			?>
		</address>
	</div>

	<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && get_option( 'woocommerce_calc_shipping' ) !== 'no' ) : ?>

		<div class="address-bloc address-bloc-shipping">
			<h3 class="address-bloc-title"><?php _e( 'Shipping Address', 'woocommerce' ); ?></h3>
			<address>
				<?php
				if ( ! $order->get_formatted_shipping_address() ) {
					_e( 'N/A', 'woocommerce' );
				} else {
					echo $order->get_formatted_shipping_address();
				}
				?>
			</address>
		</div>

	<?php endif; ?>
</div>
