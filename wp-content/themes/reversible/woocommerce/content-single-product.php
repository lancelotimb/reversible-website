<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$classes = 'current-single-product';
//if (extra_is_in_cart()) {
//	$classes .= ' already-in-cart';
//}

?>

<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked wc_print_notices - 10 // DELETED
	 */
	do_action( 'woocommerce_before_single_product' );

	if ( post_password_required() ) {
		echo get_the_password_form();
		return;
	}
?>

<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class($classes); ?>>

	<?php
		/**
		 * woocommerce_before_single_product_summary hook
		 *
		 * @hooked extra_single_product_search - 4 // DELETED
		 * @hooked extra_single_product_back_button - 5 // ADDED
		 * @hooked woocommerce_show_product_sale_flash - 10 // DELETED
		 * @hooked woocommerce_show_product_images - 20
		 */
		do_action( 'woocommerce_before_single_product_summary' );
	?>

	<div class="summary entry-summary">

		<?php
			/**
			 * woocommerce_single_product_summary hook
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10 // DELETED
			 * @hooked woocommerce_template_single_excerpt - 20 // DELETED
			 * @hooked extra_single_product_description - 20 // ADDED
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40 // DELETED
			 * @hooked woocommerce_template_single_sharing - 50
			 */
			do_action( 'woocommerce_single_product_summary' );
		?>

	</div><!-- .summary -->

	<?php
		/**
		 * woocommerce_after_single_product_summary hook
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10 // DELETED
		 * @hooked woocommerce_upsell_display - 15 // DELETED
		 * @hooked woocommerce_output_related_products - 20 // DELETED
		 * @hooked woocommerce_show_product_images - 20 // ADDED
		 */
		do_action( 'woocommerce_after_single_product_summary' );
	?>

	<meta itemprop="url" content="<?php the_permalink(); ?>" />

</div><!-- #product-<?php the_ID(); ?> -->

<?php
	/**
	 * woocommerce_after_single_product hook
	 *
	 * @hooked extra_same_product_template - 10 // ADDED
	 */
	do_action( 'woocommerce_after_single_product' );
?>
