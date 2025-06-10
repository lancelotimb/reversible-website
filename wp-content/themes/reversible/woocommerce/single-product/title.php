<?php
/**
 * Single Product title
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $extra_product_template, $post, $product, $extra_same_products;

$product->is_sold_individually();

$sku = $product->get_sku();
?>
<div class="title-wrapper">
	<div class="title-bloc">
		<h2 class="product_template_title entry-title"><?php echo $extra_product_template->post_title; ?></h2>
		<h1 itemprop="name" class="product_title"><?php echo $post->post_title; ?></h1>
		<?php if (!empty($sku)) : ?>
			<p itemprop="sku" class="sku"><?php echo sprintf(__("Réf. %s", 'extra'), $product->get_sku()); ?></p>
		<?php endif; ?>
	</div>
	<div class="related-link-bloc">
		<h3>
			<?php if ($product->is_sold_individually()) : ?>
				<?php _e("Modèle unique", 'extra'); ?>
			<?php else : ?>
				<?php
				$availability      = $product->get_availability();
				$availability_html = empty( $availability['availability'] ) ? '' : '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . esc_html( $availability['availability'] ) . '</p>';

				echo apply_filters( 'woocommerce_stock_html', $availability_html, $availability['availability'], $product );
				?>
			<?php endif; ?>
		</h3>
		<?php if ($extra_product_template && (($extra_same_products && !empty($extra_same_products) && count($extra_same_products) > 1))) : ?>
			<a class="scroll-to-anchor" href="<?php echo get_permalink($post->ID); ?>#tous-les-<?php echo $extra_product_template->post_name; ?>"><?php _e("Voir tous les modèles", 'extra'); ?></a>
		<?php endif; ?>
	</div>
</div>