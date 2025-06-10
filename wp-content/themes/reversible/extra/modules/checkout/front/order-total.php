<div class="extra-order-total extra-table-total">
	<div class="total-line order">
		<span class="total-line-label"><?php _e( 'Order Number:', 'woocommerce' ); ?></span>
		<span class="total-line-value" ><strong><?php echo $order->get_order_number(); ?></strong></span>
	</div>
	<div class="total-line date">
		<span class="total-line-label"><?php _e( 'Date:', 'woocommerce' ); ?></span>
		<span class="total-line-value" ><strong><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></strong></span>
	</div>
	<?php if ( $order->payment_method_title ) : ?>
		<div class="total-line payement_method">
			<span class="total-line-label"><?php _e( "Paiement :", 'extra' ); ?></span>
			<span class="total-line-value" ><strong><?php echo $order->payment_method_title; ?></strong></span>
		</div>
	<?php endif; ?>

	<?php if ( $order->billing_email ) : ?>
		<div class="total-line billing_email">
			<span class="total-line-label"><?php _e( 'Email:', 'woocommerce' ); ?></span>
			<span class="total-line-value" ><a href="mailto:<?php echo $order->billing_email; ?>"><?php echo $order->billing_email; ?></a></span>
		</div>
	<?php endif; ?>

	<?php if ( $order->billing_phone ) : ?>
		<div class="total-line billing_phone">
			<span class="total-line-label"><?php _e( 'Telephone:', 'woocommerce' ); ?></span>
			<span class="total-line-value" ><strong><?php echo $order->billing_phone; ?></strong></span>
		</div>
	<?php endif; ?>

	<hr class="extra-table-total-separator"/>

	<?php
	$has_refund = false;

	if ( $total_refunded = $order->get_total_refunded() ) {
		$has_refund = true;
	}

	if ( $totals = $order->get_order_item_totals() ) {

		foreach ( $totals as $key => $total ) {
			$value = $total['value'];

			// Check for refund
			if ( $has_refund && $key === 'order_total' ) {
				$refunded_tax_del = '';
				$refunded_tax_ins = '';

				// Tax for inclusive prices
				if ( wc_tax_enabled() && 'incl' == $order->tax_display_cart ) {

					$tax_del_array = array();
					$tax_ins_array = array();

					if ( 'itemized' == get_option( 'woocommerce_tax_total_display' ) ) {

						foreach ( $order->get_tax_totals() as $code => $tax ) {
							$tax_del_array[] = sprintf( '%s %s', $tax->formatted_amount, $tax->label );
							$tax_ins_array[] = sprintf( '%s %s', wc_price( $tax->amount - $order->get_total_tax_refunded_by_rate_id( $tax->rate_id ), array( 'currency' => $order->get_order_currency() ) ), $tax->label );
						}

					} else {
						$tax_del_array[] = sprintf( '%s %s', wc_price( $order->get_total_tax(), array( 'currency' => $order->get_order_currency() ) ), WC()->countries->tax_or_vat() );
						$tax_ins_array[] = sprintf( '%s %s', wc_price( $order->get_total_tax() - $order->get_total_tax_refunded(), array( 'currency' => $order->get_order_currency() ) ), WC()->countries->tax_or_vat() );
					}

					if ( ! empty( $tax_del_array ) ) {
						$refunded_tax_del .= ' ' . sprintf( __( '(Includes %s)', 'woocommerce' ), implode( ', ', $tax_del_array ) );
					}

					if ( ! empty( $tax_ins_array ) ) {
						$refunded_tax_ins .= ' ' . sprintf( __( '(Includes %s)', 'woocommerce' ), implode( ', ', $tax_ins_array ) );
					}
				}

				$value = '<del>' . strip_tags( $order->get_formatted_order_total() ) . $refunded_tax_del . '</del> <ins>' . wc_price( $order->get_total() - $total_refunded, array( 'currency' => $order->get_order_currency() ) ) . $refunded_tax_ins . '</ins>';
			}
			if ( $key === 'order_total' ) {
				$value = '<strong>'.$value.'</strong>';
			}
			if ( $key == 'payment_method' ) {
				$value = null;
			}
			if ( $key == 'shipping' ) {
				$total['label'] = sprintf(__("Livraison %s :", 'extra'), $order->get_shipping_method());
			}
			?>
			<?php if ($value && !empty($value)) : ?>
			<div class="total-line <?php echo $key; ?>">
				<span class="total-line-label"><?php echo $total['label']; ?></span>
				<span class="total-line-value" ><?php echo $value; ?></span>
			</div>
			<?php endif; ?>
		<?php
		}
	}

	// Check for refund
	if ( $has_refund ) { ?>
		<div>
			<span><?php _e( 'Refunded:', 'woocommerce' ); ?></span>
			<span>-<?php echo wc_price( $total_refunded, array( 'currency' => $order->get_order_currency() ) ); ?></span>
		</div>
	<?php
	}

	// Check for customer note
	if ( '' != $order->customer_note ) { ?>
		<div>
			<span scope="row"><?php _e( 'Note:', 'woocommerce' ); ?></span>
			<span><?php echo wptexturize( $order->customer_note ); ?></span>
		</div>
	<?php } ?>
</div>

