<?php
/**
 * Simple product add to cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product;

if ( ! $product->is_purchasable() ) return;
?>


<div class="add-to-cart-bloc">
	<?php
	/**
	 * woocommerce_before_add_to_cart_form hook
	 *
	 * @hooked woocommerce_template_single_price - 10
	 */
	do_action( 'woocommerce_before_add_to_cart_form' );
	?>

	<?php if ( $product->is_in_stock() ) : ?>
	<form class="cart" method="post" enctype='multipart/form-data' id="extra-add-to-cart-form">
	<?php endif; ?>
		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<?php
		if ( ! $product->is_sold_individually() ) {
			woocommerce_quantity_input(array(
				'min_value' => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
				'max_value' => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product )
			));
		}
		?>

		<input type="hidden" class="extra-add-to-cart-id" name="add-to-cart" value="<?php echo esc_attr( $product->id ); ?>" />

		<button type="submit" class="extra-add-to-cart-button single_add_to_cart_button button alt big-shop-button"<?php echo (!$product->is_in_stock()) ? ' disabled' : ''; ?>>
			<svg class="icon icon-cart"><use xlink:href="#icon-cart"></use></svg>
			<span class="inner available"><?php echo $product->single_add_to_cart_text(); ?></span>
			<span class="inner outofstock"><?php _e("Vendu", 'extra'); ?></span>
			<span class="inner already-in-cart sold-individually"><?php _e("Déjà dans votre panier", 'extra'); ?></span>
			<span class="inner already-in-cart not-sold-individually"><?php _e("Ajouter à nouveau", 'extra'); ?></span>
		</button>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	<?php if ( $product->is_in_stock() ) : ?>
	</form>
	<?php endif; ?>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
</div>
