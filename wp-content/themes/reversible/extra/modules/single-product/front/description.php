<?php
global $post, $extra_product_template, $product, $extra_product_template_meta;

$shop_url = get_permalink( wc_get_page_id( 'shop' ) );

/**************************
 *
 *
 * DESCRIPTION
 *
 *
 *************************/
$content = $post->post_content;
//var_dump($post->post_content);
$is_empty = str_replace("&nbsp;", '', $content);
if (!trim($is_empty) && $extra_product_template !== null) {
	$content = $extra_product_template->post_content;
}
?>
<?php if ($content) : ?>
	<div itemprop="description" class="content">
		<?php echo apply_filters( 'the_content', $content ) ?>
	</div>
<?php endif; ?>

<?php if ($extra_product_template !== null) : ?>
	<dl class="product-properties">
		<?php
		/**************************
		 *
		 *
		 * MATERIALS
		 *
		 *
		 *************************/
		$links = '';
		$terms = wp_get_post_terms($extra_product_template->ID, 'extra_product_template_material');
		foreach ($terms as $term) {
			if ($links != '')  {
				$links .= ', ';
			}
			$links .= '<a class="property-link" href="'.get_term_link($term).'">'.$term->name.'</a>';
		}
		if ($links != '') :
		?>
		<dt class="property-label"><?php _e("Matériaux :", 'extra'); ?></dt>
		<dd class="property-content"><?php echo $links; ?></dd>
		<?php endif;?>

		<?php
		/**************************
		 *
		 *
		 * TYPES
		 *
		 *
		 *************************/
		$links = '';
		$terms = wp_get_post_terms($extra_product_template->ID, 'extra_product_template_type');
		foreach ($terms as $term) {
			if ($links != '')  {
				$links .= ', ';
			}
			$links .= '<a class="property-link" href="'.get_term_link($term).'">'.$term->name.'</a>';
		}
		if ($links != '') :
			?>
			<dt class="property-label"><?php _e("Type de produit :", 'extra'); ?></dt>
			<dd class="property-content"><?php echo $links; ?></dd>
		<?php endif;?>



		<?php
		$is_small_first = true;
		?>
		<?php
		/**************************
		 *
		 *
		 * DETAIL
		 *
		 *
		 *************************/
		?>
		<?php if (isset($extra_product_template_meta['detail']) && !empty($extra_product_template_meta['detail'])) : ?>
			<dt class="property-label property-small<?php echo ($is_small_first) ? ' property-small-first' : ''; ?>"><?php _e("Détail :", 'extra'); ?></dt>
			<dd class="property-content property-small<?php echo ($is_small_first) ? ' property-small-first' : ''; ?>"><?php echo (isset($extra_product_template_meta['detail'])) ? $extra_product_template_meta['detail'] : ''; ?></dd>
		<?php
			$is_small_first = false;
		endif; ?>

		<?php
		/**************************
		 *
		 *
		 * DIMENSIONS
		 *
		 *
		 *************************/
		?>
		<?php if (isset($extra_product_template_meta['width']) && !empty($extra_product_template_meta['width'])) : ?>
			<dt class="property-label property-small<?php echo ($is_small_first) ? ' property-small-first' : ''; ?>"><?php _e("Largeur :", 'extra'); ?></dt>
			<dd class="property-content property-small<?php echo ($is_small_first) ? ' property-small-first' : ''; ?>" itemprop="width"><?php echo sprintf(__("%s cm", 'extra'), (isset($extra_product_template_meta['width'])) ? $extra_product_template_meta['width'] : '-'); ?></dd>
			<?php
			$is_small_first = false;
		endif; ?>

		<?php if (isset($extra_product_template_meta['height']) && !empty($extra_product_template_meta['height'])) : ?>
			<dt class="property-label property-small<?php echo ($is_small_first) ? ' property-small-first' : ''; ?>"><?php _e("Hauteur :", 'extra'); ?></dt>
			<dd class="property-content property-small<?php echo ($is_small_first) ? ' property-small-first' : ''; ?>" itemprop="height"><?php echo sprintf(__("%s cm", 'extra'), (isset($extra_product_template_meta['height'])) ? $extra_product_template_meta['height'] : '-'); ?></dd>
			<?php
			$is_small_first = false;
		endif; ?>

		<?php if (isset($extra_product_template_meta['length']) && !empty($extra_product_template_meta['length'])) : ?>
			<dt class="property-label property-small<?php echo ($is_small_first) ? ' property-small-first' : ''; ?>"><?php _e("Epaisseur :", 'extra'); ?></dt>
			<dd class="property-content property-small<?php echo ($is_small_first) ? ' property-small-first' : ''; ?>" itemprop="depth"><?php echo sprintf(__("%s cm", 'extra'), (isset($extra_product_template_meta['length'])) ? $extra_product_template_meta['length'] : '-'); ?></dd>
			<?php
			$is_small_first = false;
		endif; ?>
	</dl>
<?php endif;?>

