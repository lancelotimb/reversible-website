<?php
/**
 * Single Product Image
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.14
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $extra_options, $post, $woocommerce, $product, $extra_product_template;
$default_tumbnail = $extra_options['default-thumbnail'];
?>
<div class="big-images images">
	<?php
	/***************************
	 *
	 *
	 * MAIN IMAGE
	 *
	 *
	 **************************/
	$thumbnail_id = $default_tumbnail['id'];
	if ( has_post_thumbnail() ) :
		$thumbnail_id = get_post_thumbnail_id();
		$image_src = wp_get_attachment_image_src($thumbnail_id, 'full');
		?>
		<div class="big-image-wrapper">
			<?php
			/***************************
			 *
			 *
			 * SALES
			 *
			 *
			 **************************/
			wc_get_template( 'single-product/sale-flash.php' );
			?>

			<a class="zoom-link no-fancybox<?php echo ($image_src[1] > 600 && $image_src[2] > 600) ? ' enabled' : ''; ?>" href="<?php echo $image_src[0]; ?>" data-zoom-width="<?php echo $image_src[1]; ?>" data-zoom-height="<?php echo $image_src[2]; ?>">
				<span class="zoom-icon-wrapper">
					<svg class="icon icon-search"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-search"></use></svg>
				</span>

				<?php extra_responsive_image(
					$thumbnail_id,
					array(
						'desktop' => array(600, 600),
						'smalldesktop' => array(600, 600),
						'tablet' => array(600, 600),
						'mobile' => array(600, 600),
					),
					'big-image image first-big-image summary-anchor summary-first-anchor',
					null,
					'image'
				);
				?>
			</a>
		</div>
	<?php endif; ?>

	<?php
	/***************************
	 *
	 *
	 * ADDITIONAL IMAGES
	 *
	 *
	 **************************/
	$attachment_ids = $product->get_gallery_attachment_ids();
	foreach ($attachment_ids as $attachment_id) :
		$image_src = wp_get_attachment_image_src($attachment_id, 'full');
		if ($image_src) : ?>
			<div class="big-image-wrapper">
				<a class="zoom-link no-fancybox<?php echo ($image_src[1] > 600 && $image_src[2] > 600) ? ' enabled' : ''; ?>" href="<?php echo $image_src[0]; ?>" data-zoom-width="<?php echo $image_src[1]; ?>" data-zoom-height="<?php echo $image_src[2]; ?>">
					<span class="zoom-icon-wrapper">
						<svg class="icon icon-search"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-search"></use></svg>
					</span>

					<?php extra_responsive_image(
						$attachment_id,
						array(
							'desktop' => array(600, 600),
							'smalldesktop' => array(600, 600),
							'tablet' => array(600, 600),
							'mobile' => array(600, 600),
						),
						'big-image image summary-anchor'
					); ?>
				</a>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
</div>