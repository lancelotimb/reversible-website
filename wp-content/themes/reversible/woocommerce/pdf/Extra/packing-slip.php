<?php global $wpo_wcpdf; ?>
<table class="head container">
	<tr>
		<td class="header">
			<img src="<?php echo THEME_URI . '/assets/img/pdf/header.png'; ?>" width="640" height="203" alt="Reversible" />
		</td>
	</tr>
</table>

<h1 class="document-type-label">
	<?php _e("Bon de livraison", 'extra'); ?>
</h1>

<?php do_action( 'wpo_wcpdf_after_document_label', $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>

<table class="order-data-addresses">
	<tr>
		<td class="address shipping-address">
			<?php if ( isset($wpo_wcpdf->settings->template_settings['packing_slip_billing_address']) && $wpo_wcpdf->get_billing_address() != $wpo_wcpdf->get_shipping_address()) : ?>
			 	<h3><?php _e( 'Livré à :', 'extra' ); ?></h3>
			<?php else : ?>
				<h3><?php _e( 'Adresse :', 'extra' ); ?></h3>
			<?php endif; ?>
			<?php $wpo_wcpdf->shipping_address(); ?>
			<?php if ( isset($wpo_wcpdf->settings->template_settings['packing_slip_email']) ) { ?>
			<div class="billing-email"><?php $wpo_wcpdf->billing_email(); ?></div>
			<?php } ?>
			<?php if ( isset($wpo_wcpdf->settings->template_settings['packing_slip_phone']) ) { ?>
			<div class="billing-phone"><?php $wpo_wcpdf->billing_phone(); ?></div>
			<?php } ?>
		</td>
		<td class="address billing-address">
			<?php if ( isset($wpo_wcpdf->settings->template_settings['packing_slip_billing_address']) && $wpo_wcpdf->get_billing_address() != $wpo_wcpdf->get_shipping_address()) { ?>
			<h3><?php _e( 'Vendu à : ', 'extra' ); ?></h3>
			<?php $wpo_wcpdf->billing_address(); ?>
			<?php } ?>
		</td>
		<td class="order-data">
			<table>
				<?php do_action( 'wpo_wcpdf_before_order_data', $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>
				<tr class="order-number">
					<th><?php _e( 'Commande : ', 'extra' ); ?></th>
					<td><?php $wpo_wcpdf->order_number(); ?></td>
				</tr>
				<tr class="order-date">
					<th><?php _e( 'Date : ', 'extra' ); ?></th>
					<td><?php $wpo_wcpdf->order_date(); ?></td>
				</tr>

				<?php
				$order_tracking = extra_get_order_tracking($wpo_wcpdf->export->order->post->ID);
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
								<th><?php echo sprintf(__("Colis n°%s : ", 'extra'), ($key+1)); ?></th>
								<td><?php echo $number ?></td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				<?php endif; ?>

				<?php do_action( 'wpo_wcpdf_after_order_data', $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>
			</table>			
		</td>
	</tr>
</table>

<?php do_action( 'wpo_wcpdf_before_order_details', $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>

<table class="order-details">
	<thead>
		<tr>
			<th class="product"><?php _e('Product', 'wpo_wcpdf'); ?></th>
			<th class="sku"><?php _e('Référence', 'wpo_wcpdf'); ?></th>
			<th class="quantity"><?php _e('Quantity', 'wpo_wcpdf'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php $items = $wpo_wcpdf->get_order_items(); if( sizeof( $items ) > 0 ) : foreach( $items as $item_id => $item ) : ?>
		<tr>
			<td class="product">
				<?php $description_label = __( 'Description', 'wpo_wcpdf' ); // registering alternate label translation ?>
				<span class="item-name"><?php echo $item['name']; ?></span>
				<?php do_action( 'wpo_wcpdf_before_item_meta', $wpo_wcpdf->export->template_type, $item, $wpo_wcpdf->export->order  ); ?>
				<span class="item-meta"><?php echo $item['meta']; ?></span>
				<?php do_action( 'wpo_wcpdf_after_item_meta', $wpo_wcpdf->export->template_type, $item, $wpo_wcpdf->export->order  ); ?>
			</td>
			<td class="sku">
				<?php if( !empty( $item['sku'] ) ) : ?><?php echo $item['sku']; ?><?php endif; ?>
			</td>
			<td class="quantity"><?php echo $item['quantity']; ?></td>
		</tr>
		<?php endforeach; endif; ?>
	</tbody>
</table>

<?php do_action( 'wpo_wcpdf_after_order_details', $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>