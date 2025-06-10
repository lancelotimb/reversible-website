<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**********************************
 *
 *
 * EXTRA GET TEMPLATE FOR CURRENT PRODUCT
 *
 *
 *********************************/
global $post, $extra_product_template, $extra_product_template_meta, $product_template_metabox, $extra_same_products;

$extra_product_template = extra_get_product_template();
if ($extra_product_template) {
	$extra_product_template_meta = $product_template_metabox->the_meta($extra_product_template->ID);
}

$extra_same_products = get_posts(array(
	'connected_type' => 'products_to_product_template',
	'connected_items' => $extra_product_template,
	'nopaging' => true,
	'suppress_filters' => false
));

/**********************************
 *
 *
 * EXTRA REMOVE HEADER AND FOOTER
 *
 *
 *********************************/

/**
 * woocommerce_before_main_content hook
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 */
do_action( 'woocommerce_before_main_content' );
?>
	<?php while ( have_posts() ) : the_post(); ?>

		<?php wc_get_template_part( 'content', 'single-product' ); ?>

	<?php endwhile; // end of the loop. ?>

<?php
woocommerce_output_content_wrapper_end();
/**
 * woocommerce_after_main_content hook
 */
do_action( 'woocommerce_after_single_product' );
?>

<?php
/**
 * woocommerce_sidebar hook
 *
 * @hooked woocommerce_get_sidebar - 10
 * @hooked extra_same_product_template - 1 // ADDED
 */
do_action( 'woocommerce_sidebar' );
