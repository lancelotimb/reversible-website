<?php
global $post;

$src = wc_placeholder_img_src();
$alt = __("Image produit par défaut", 'extra');
if ( has_post_thumbnail() ) {
	$thumb_id = get_post_thumbnail_id($post->ID);
	$thumb = wp_get_attachment_image_src($thumb_id , 'shop_catalog' );
	$src = $thumb[0];

	$alt = get_post_meta($thumb_id, '_wp_attachment_image_alt', true);
	if(empty($alt)) {
		$alt = get_the_title($thumb_id);
	}
}
$blank_src = THEME_MODULES_URI . '/archive-product/front/img/product-blank.png';

?>
<div class="product-thumbnail-wrapper">
	<noscript>
		<img class="extra-product-thumbnail no-js" src="<?php echo $src; ?>" alt="<?php echo $alt; ?>"/>
	</noscript>
	<img class="extra-product-thumbnail js" src="<?php echo $blank_src; ?>" data-thumbnail-src="<?php echo $src; ?>" alt="<?php echo $alt; ?>"/>
</div>
