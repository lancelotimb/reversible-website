<?php
global $woocommerce;
$cart_url = $woocommerce->cart->get_cart_url();
?>
<div class="cart-link-wrapper">
	<a class="cart-link extra-button" href="<?php echo $cart_url; ?>"  title="<?php _e("Voir mon panier", 'extra'); ?>">
		<svg class="icon icon-cart"><use xlink:href="#icon-cart"></use></svg>
		<svg class="icon icon-close"><use xlink:href="#icon-close"></use></svg>
		<span class="inner">
			<?php _e("Voir mon panier", 'extra'); ?>
		</span>
	</a>
	<span class="cart-count"></span>
</div>