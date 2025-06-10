<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.0
 */


/**********************************
 *
 *
 * EXTRA CONTENT PRODUCT IN LISTING
 *
 *
 *********************************/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product, $woocommerce_loop, $extra_fill_cart, $extra_add_filters_data, $extra_product_order;

if (!isset($extra_product_order)) {
	$extra_product_order = 0;
} else {
	$extra_product_order++;
}

$extra_fill_cart = isset($extra_fill_cart) && $extra_fill_cart == true;
$extra_add_filters_data = isset($extra_add_filters_data) && $extra_add_filters_data == true;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;

// Increase loop count
$woocommerce_loop['loop']++;

// Add product class !!! for ajax purpose
$classes[] = 'product';

$search_terms = null;
$is_visible = true;

if ($extra_add_filters_data) {
	$search_terms = $post->post_title;
	/**********************
	 *
	 *
	 * PRODUCT FILTERS
	 *
	 *
	 *********************/
	if ($post->connected && ! empty($post->connected)) {
		foreach($post->connected as $product_template) {
			// Materials
			$materials = array();
			$terms = wp_get_post_terms($product_template->ID, 'extra_product_template_material');
			foreach ($terms as $term) {
				$materials[] = $term->slug;
				$classes[] = 'extra-filter-materiel-'.$term->slug;
				$search_terms .= ' '.$term->slug;
			}

			// Types
			$types = array();
			$terms = wp_get_post_terms($product_template->ID, 'extra_product_template_type');
			foreach ($terms as $term) {
				$types[] = $term->slug;
				$classes[] = 'extra-filter-type-'.$term->slug;
				$search_terms .= ' '.$term->slug;
			}
		}

		$search_terms .= ' '.$product_template->post_title;
	}


	// Collections
	$collections = array();
	$terms = wp_get_post_terms($product->id, 'extra_product_collection');
	foreach ($terms as $term) {
		$collections[] = $term->slug;
		$classes[] = 'extra-filter-collection-'.$term->slug;
		$search_terms .= ' '.$term->slug;
	}



	/**********************
	 *
	 *
	 * IS VISIBLE ?
	 *
	 *
	 *********************/
	global $extra_product_material_filter, $extra_product_type_filter, $extra_product_collection_filter;

	$is_visible = false;
	$has_filter = (isset($extra_product_material_filter) && $extra_product_material_filter != '') ||
		(isset($extra_product_type_filter) && $extra_product_type_filter != '') ||
		(isset($extra_product_collection_filter) && $extra_product_collection_filter != '');
	if ($has_filter) {
		$is_visible_material = true;
		if (isset($extra_product_material_filter) && $extra_product_material_filter != '') {
			// Material
			$is_visible_material = false;
			foreach ($materials as $material) {
				if ($material == $extra_product_material_filter) {
					$is_visible_material = true;
					break;
				}
			}
		}
		$is_visible_type = true;
		if (isset($extra_product_type_filter) && $extra_product_type_filter != '') {
			// Type
			$is_visible_type = false;
			foreach ($types as $type) {
				if ($type == $extra_product_type_filter) {
					$is_visible_type = true;
					break;
				}
			}
		}
		$is_visible_collection = true;
		if (isset($extra_product_collection_filter) && $extra_product_collection_filter != '') {
			// Collection
			$is_visible_collection = false;
			foreach ($collections as $collection) {
				if ($collection == $extra_product_collection_filter) {
					$is_visible_collection = true;
					break;
				}
			}
		}
		$is_visible = $is_visible_material && $is_visible_type && $is_visible_collection;
	} else {
		$is_visible = true;
	}
}
?>

<li <?php post_class( $classes ); ?>
	data-product-id="<?php echo $product->id; ?>"
	data-product-order="<?php echo $extra_product_order; ?>"
	data-product-price="<?php echo $product->price; ?>"
	data-product-quantity="<?php echo $product->get_stock_quantity(); ?>"
	data-product-featured="<?php echo ($product->is_featured()) ? '1' : '0'; ?>"
	data-product-search-terms="<?php echo $search_terms ?>"
	<?php echo ($is_visible) ? '' : ' style="display:none;"'; ?>>

	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

	<?php if ($product->is_in_stock()) : ?>
		<a class="product-link<?php echo ($extra_fill_cart) ? ' border-fill' : '' ?>"<?php echo ($extra_fill_cart) ? 'data-border-fill-speed="0.6"' : '' ?> href="<?php the_permalink(); ?>">
	<?php else : ?>
		<div class="product-link product-link-disabled<?php echo ($extra_fill_cart) ? ' border-fill' : '' ?>"<?php echo ($extra_fill_cart) ? 'data-border-fill-speed="0.6"' : '' ?>>
	<?php endif; ?>
			<?php
				/**
				 * woocommerce_before_shop_loop_item_title hook
				 *
				 * @hooked woocommerce_show_product_loop_sale_flash - 10
				 * @hooked woocommerce_template_loop_product_thumbnail - 10
				 */
				do_action( 'woocommerce_before_shop_loop_item_title' );
			?>

			<span class="product-inner">
				<?php
				$post_id = $post->ID;
				$title = '--';
				if ($post->connected != null && !empty($post->connected)) {
					$connected = $post->connected[0];
					$post_id = $connected->ID;
					$title = get_the_title($connected->ID);
				} else {
					// FOR CART PURPOSE
					$product_template = extra_get_product_template($post_id);
					if ($product_template) {
						$title = get_the_title($product_template->ID);
					}
				}
				?>
				<h3 class="product-title"><?php echo $title; ?></h3>

				<?php
				/**
				 * woocommerce_after_shop_loop_item_title hook
				 *
				 * @hooked woocommerce_template_loop_rating - 5
				 * @hooked woocommerce_template_loop_price - 10
				 */
				do_action( 'woocommerce_after_shop_loop_item_title' );
				?>
			</span>
	<?php if ($product->is_in_stock()) : ?>
		</a>
	<?php else : ?>
		</div>
	<?php endif; ?>

	<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>

	<?php if ($extra_fill_cart) : ?>
		<a href="#remove-from-cart" class="extra-remove-from-cart" data-product-id="<?php echo $product->id; ?>" title="<?php _e("Retirer du panier", 'extra'); ?>">
			<svg class="icon icon-close"><use xlink:href="#icon-close"></use></svg>
			<?php _e("Retirer du panier", 'extra'); ?>
		</a>
	<?php endif; ?>
</li>