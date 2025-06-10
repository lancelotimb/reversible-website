<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $extra_options, $post, $woocommerce, $product, $extra_product_template;
$default_tumbnail = $extra_options['default-thumbnail'];
?>

<div class="small-images images">
	<?php
	/***************************
	 *
	 *
	 * PRODUCT TEMPLATE IMAGES
	 *
	 *
	 **************************/


	if ($extra_product_template !== null) {
		global $product_template_metabox, $extra_product_template_meta;

		$meta_image_name = 'wearing_image';
		include THEME_MODULES_PATH . '/single-product/front/small-image.php';

		$meta_image_name = 'detail_image_1';
		include THEME_MODULES_PATH . '/single-product/front/small-image.php';

		$meta_image_name = 'detail_image_2';
		include THEME_MODULES_PATH . '/single-product/front/small-image.php';

		$meta_image_name = 'detail_image_3';
		include THEME_MODULES_PATH . '/single-product/front/small-image.php';
	}
	wp_reset_postdata();
	?>
</div>