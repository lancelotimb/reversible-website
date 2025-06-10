<?php
global $extra_options;
$default_tumbnail = $extra_options['default-thumbnail'];

/**
 * @var $_product WC_Product
 */
$_product;
$quantity = $item['qty'];
if ($_product) {
	$product_template = extra_get_product_template($_product->post);
}

$link_wrapper_start = '<span class="product-no-link">';
$link_wrapper_stop = '</span>';
if (!($_product && ! $_product->is_visible())) {
	$link_wrapper_start = '<a class="product-link" href="'.get_permalink( $item['product_id']).'">';
	$link_wrapper_stop = '</a>';
}
?>

<tr class="table-item order-item">
	<td class="product-image table-column-1">
		<div class="cell-wrapper">
			<?php
			$thumbnail_id = $default_tumbnail['id'];
			if ( $_product && has_post_thumbnail($_product->id) ) {
				$thumbnail_id = get_post_thumbnail_id($_product->id);
			}
			$image_src = wp_get_attachment_image_src($thumbnail_id, array(150, 150));
			?>
			<?php echo $link_wrapper_start; ?>
				<img src="<?php echo $image_src[0]; ?>" width="<?php echo $image_src[1]; ?>" height="<?php echo $image_src[0]; ?>" alt="<?php echo $item['name']; ?>">
			<?php echo $link_wrapper_stop; ?>
		</div>
	</td>
	<td class="product-name table-column-2">
		<div class="cell-wrapper">
			<?php echo $link_wrapper_start; ?>
				<span class="product-template-name">
					<?php echo ($product_template) ? $product_template->post_title : $item['name']; ?>
					<?php if (($_product && !$_product->is_sold_individually()) || !$_product) : ?>
						<?php echo apply_filters( 'woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf( '&times; %s', $item['qty'] ) . '</strong>', $item ); ?>
					<?php endif; ?>
				</span>
				<?php
				if ($_product) :
					$sku = $_product->get_sku();
					if (!empty($sku)) : ?>
						<span class="product-sku"><?php echo sprintf(__('Ref. %s', 'extra'), $sku); ?></span>
					<?php endif;
				endif; ?>
			<?php echo $link_wrapper_stop; ?>

			<?php

			if ($item_meta) {
				// Allow other plugins to add additional product information here
				do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order );

				$item_meta->display();

				if ( $_product && $_product->exists() && $_product->is_downloadable() && $order->is_download_permitted() ) {

					$download_files = $order->get_item_downloads( $item );
					$i              = 0;
					$links          = array();

					foreach ( $download_files as $download_id => $file ) {
						$i++;

						$links[] = '<small><a href="' . esc_url( $file['download_url'] ) . '">' . sprintf( __( 'Download file%s', 'woocommerce' ), ( count( $download_files ) > 1 ? ' ' . $i . ': ' : ': ' ) ) . esc_html( $file['name'] ) . '</a></small>';
					}

					echo '<br/>' . implode( '<br/>', $links );
				}

				// Allow other plugins to add additional product information here
				do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order );
			}
			?>
		</div>
	</td>
	<td class="product-price table-column-3">
		<div class="cell-wrapper">
			<?php echo $order->get_formatted_line_subtotal( $item ); ?>
		</div>
	</td>
</tr>
