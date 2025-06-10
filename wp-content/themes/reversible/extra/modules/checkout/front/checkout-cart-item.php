<?php
global $extra_options;
$default_tumbnail = $extra_options['default-thumbnail'];

/**
 * @var $_product WC_Product
 */
$_product;
$quantity = $cart_item['quantity'];
$product_template = extra_get_product_template($_product->post);
?>

<tr class="table-item checkout-cart-item checkout-cart-item-<?php echo $_product->id; ?>">
	<td class="product-image table-column-1">
		<div class="cell-wrapper">
			<?php
			$thumbnail_id = $default_tumbnail['id'];
			if ( has_post_thumbnail($_product->id) ) {
				$thumbnail_id = get_post_thumbnail_id($_product->id);
			}
			$image_src = wp_get_attachment_image_src($thumbnail_id, array(150, 150));
			?>
			<a class="product-link" href="<?php echo get_permalink($_product->id); ?>">
				<img src="<?php echo $image_src[0]; ?>" width="<?php echo $image_src[1]; ?>" height="<?php echo $image_src[2]; ?>" alt="<?php echo $product_template->post_title; ?>">
			</a>
		</div>
	</td>
	<td class="product-name table-column-2">
		<div class="cell-wrapper">
			<a class="product-link" href="<?php echo get_permalink($_product->id); ?>">
				<span class="product-template-name">
					<?php echo ($product_template) ? $product_template->post_title : '--'; ?>
					<?php if (!$_product->is_sold_individually()) : ?>
						<?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '&times; %s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); ?>
					<?php endif; ?>
				</span>
				<?php
				$sku = $_product->get_sku();
				if (!empty($sku)) : ?>
					<span class="product-sku"><?php echo sprintf(__('Ref. %s', 'extra'), $sku); ?></span>
				<?php endif; ?>
				<span class="product-other">
					<?php echo WC()->cart->get_item_data( $cart_item ); ?>
				</span>
			</a>
		</div>
	</td>
	<td class="product-price table-column-3">
		<div class="cell-wrapper">
			<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
		</div>
	</td>
	<td class="product-remove table-column-4">
		<div class="cell-wrapper">
			<a class="square-button extra-button extra-checkout-remove-from-cart"
			   data-product-id="<?php echo $_product->id; ?>"
			   href="#"
			   title="<?php _e("Retirer du panier", 'extra'); ?>">
				<svg class="icon icon-close"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-close"></use></svg>
			</a>
		</div>
	</td>
</tr>
