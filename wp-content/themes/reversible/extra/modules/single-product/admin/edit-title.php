<?php
global $post;
if ($post !== null) : ?>
	<h1><?php echo $post->post_title; ?></h1>
	<?php $sku = get_post_meta($post->ID, '_sku'); if ($sku !== null && !empty($sku) && reset($sku) != '') : ?>
		<h4><?php echo sprintf(__("Réf. %s", 'extra'), reset($sku)); ?></h4>
	<?php endif; ?>
	<a href="<?php echo get_permalink($post->ID); ?>" class="button button-small"><?php _e("Voir le produit", 'extra'); ?></a>
<?php endif; ?>