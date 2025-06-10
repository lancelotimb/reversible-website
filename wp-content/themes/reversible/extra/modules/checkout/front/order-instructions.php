<?php if ( $order->has_status( 'failed' ) ) : // IF BANK REFUSED PAIEMENT !!!?>
	<div class="order-instructions-wrapper">
		<div class="order-instructions bank-decline">
			<p><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction.', 'woocommerce' ); ?></p>

			<p><?php
				if ( is_user_logged_in() )
					_e( 'Please attempt your purchase again or go to your account page.', 'woocommerce' );
				else
					_e( 'Please attempt your purchase again.', 'woocommerce' );
				?></p>

			<p>
				<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="pay button big-shop-button">
					<span class="inner">
						<?php _e( 'Pay', 'woocommerce' ) ?>
					</span>
				</a>
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="account button big-shop-button">
						<span class="inner">
							<?php _e( 'My Account', 'woocommerce' ); ?>
						</span>
					</a>
				<?php endif; ?>
			</p>
		</div>
		<hr class="order-instructions-separator" />
	</div>
<?php else :
	// Force loading payement gateways
	global $woocommerce;
	$woocommerce->payment_gateways;

	$instructions = '';
	ob_start();
	do_action( 'woocommerce_thankyou_' . $order->payment_method, $order->id );
	$instructions = ob_get_contents();
	ob_end_clean();

//	var_dump($order->payment_method);
//	var_dump($instructions);

	if (!empty($instructions)) : ?>
		<div class="order-instructions-wrapper">
			<div class="order-instructions order-instructions-<?php echo $order->payment_method; ?>">
				<?php echo $instructions; ?>
			</div>
			<hr class="order-instructions-separator" />
		</div>
	<?php endif;
endif; ?>