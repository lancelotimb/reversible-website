<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<table class="head container">
	<tr>
		<td class="header">
			<img src="<?php echo get_template_directory() . '/extra/assets/img/pdf/header.png'; ?>" width="640" height="203" alt="Reversible" />
		</td>
	</tr>
</table>

<h1 class="document-type-label">
	<?php _e('Facture N°', 'extra'); ?><?php $this->number( $this->get_type() ); ?>
</h1>

<?php do_action( 'wpo_wcpdf_after_document_label', $this->get_type(), $this->order ); ?>

<table class="order-data-addresses">
	<tr>
		<td class="address billing-address">
			<?php if ( $this->show_shipping_address() && $this->get_billing_address() != $this->get_shipping_address()) : ?>
				<h3><?php _e( 'Vendu à :', 'extra' ); ?></h3>
			<?php else : ?>
				<h3><?php _e( 'Adresse :', 'extra' ); ?></h3>
			<?php endif; ?>
			<?php $this->billing_address(); ?>
			<?php if ( isset($this->settings['display_email']) ) { ?>
			<div class="billing-email"><?php $this->billing_email(); ?></div>
			<?php } ?>
			<?php if ( isset($this->settings['display_phone']) ) { ?>
			<div class="billing-phone"><?php $this->billing_phone(); ?></div>
			<?php } ?>
		</td>
		<td class="address shipping-address">
			<?php if ( $this->show_shipping_address() && $this->get_billing_address() != $this->get_shipping_address()) { ?>
			<h3><?php _e( 'Livré à :', 'extra' ); ?></h3>
			<?php $this->shipping_address(); ?>
			<?php } ?>
		</td>
		<td class="order-data">
			<table>
				<?php do_action( 'wpo_wcpdf_before_order_data', $this->get_type(), $this->order ); ?>
				<?php if ( isset($this->settings['display_date']) && $this->settings['display_date'] == 'invoice_date') { ?>
				<tr class="invoice-date">
					<th><?php _e( 'Date de la facture :', 'extra' ); ?></th>
					<td><?php $this->date( $this->get_type() ); ?></td>
				</tr>
				<?php } ?>
				<tr class="order-number">
					<th><?php _e( 'Commande :', 'extra' ); ?></th>
					<td><?php _e('N°', 'extra'); ?><?php $this->order_number(); ?></td>
				</tr>
				<tr class="order-date">
					<th><?php _e( 'Date :', 'extra' ); ?></th>
					<td><?php $this->order_date(); ?></td>
				</tr>
				<tr class="payment-method">
					<th><?php _e( 'Paiement :', 'extra' ); ?></th>
					<td><?php $this->payment_method(); ?></td>
				</tr>

				<?php
				$order_tracking = extra_get_order_tracking($this->order->post->ID);
				?>
				<?php if (!empty($order_tracking['numbers']) && !empty($order_tracking['date']) && !empty($order_tracking['carrier'])) : ?>
					<tr class="shipping-method">
						<th><?php _e( 'Livraison :', 'extra' ); ?></th>
						<td><?php echo $order_tracking['carrier']; ?></td>
					</tr>

					<?php if (count($order_tracking['numbers']) == 1): $numbers = $order_tracking['numbers']; ?>
						<tr class="tracking-number">
							<th><?php _e( 'Suivi :', 'extra' ); ?></th>
							<td><?php echo $numbers[0] ?></td>
						</tr>
					<?php elseif (count($order_tracking['numbers']) > 1) : ?>
						<?php foreach($order_tracking['numbers'] as $key => $number) : ?>
							<tr class="tracking-number">
								<th><?php _e('Colis N°', 'extra'); ?><?php echo ($key+1) ?></th>
								<td><?php echo $number ?></td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				<?php endif; ?>

				<?php do_action( 'wpo_wcpdf_after_order_data', $this->get_type(), $this->order ); ?>
			</table>
		</td>
	</tr>
</table>

<?php do_action( 'wpo_wcpdf_before_order_details', $this->get_type(), $this->order ); ?>

<table class="order-details">
	<thead>
		<tr>
			<th class="product"><?php _e('Product', 'woocommerce-pdf-invoices-packing-slips'); ?></th>
			<th class="sku"><?php _e('Référence', 'woocommerce-pdf-invoices-packing-slips'); ?></th>
			<th class="quantity"><?php _e('Quantity', 'woocommerce-pdf-invoices-packing-slips'); ?></th>
			<th class="price"><?php _e('Price', 'woocommerce-pdf-invoices-packing-slips'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php $items = $this->get_order_items(); if( sizeof( $items ) > 0 ) : foreach( $items as $item_id => $item ) : ?>
		<tr>
			<td class="product">
				<?php $description_label = __( 'Description', 'woocommerce-pdf-invoices-packing-slips' ); // registering alternate label translation ?>
				<span class="item-name"><?php echo $item['name']; ?></span>
				<?php do_action( 'wpo_wcpdf_before_item_meta', $this->get_type(), $item, $this->order  ); ?>
				<span class="item-meta"><?php echo $item['meta']; ?></span>
				<?php do_action( 'wpo_wcpdf_after_item_meta', $this->get_type(), $item, $this->order  ); ?>
			</td>
			<td class="sku">
				<?php if( !empty( $item['sku'] ) ) : ?><?php echo $item['sku']; ?><?php endif; ?>
			</td>
			<td class="quantity"><?php echo $item['quantity']; ?></td>
			<td class="price"><?php echo $item['order_price']; ?></td>
		</tr>
		<?php endforeach; endif; ?>
	</tbody>
	<tfoot>
		<tr class="no-borders">
			<td class="no-borders" colspan="2">
			</td>
			<td class="no-borders" colspan="2">
				<table class="totals">
					<tfoot>
						<?php foreach( $this->get_woocommerce_totals() as $key => $total ) : ?>
						<tr class="<?php echo $key; ?>">
							<td class="no-borders">&nbsp;</td>
							<th class="description"><?php echo $total['label']; ?></th>
							<td class="price"><span class="totals-price"><?php echo $total['value']; ?></span></td>
						</tr>
						<?php endforeach; ?>
					</tfoot>
				</table>
			</td>
		</tr>
	</tfoot>
</table>

<?php do_action( 'wpo_wcpdf_after_order_details', $this->get_type(), $this->order ); ?>

<?php
 /*
<table class="foot container">
	<tr>
		<td class="footer">
			<img src="<?php echo THEME_URI . '/assets/img/pdf/footer.png'; ?>" width="813" height="203" alt="Reversible" />
		</td>
	</tr>
</table>
  */
?>

<?php if ( $this->get_footer() ): ?>
<div id="footer">
	<?php $this->footer(); ?>
</div><!-- #letter-footer -->
<?php endif; ?>