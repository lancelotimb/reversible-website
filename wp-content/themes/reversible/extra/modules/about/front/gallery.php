<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 01/06/15
 * Time: 16:36
 */

global $about_metabox, $post;
$meta = $about_metabox->the_meta($post->ID);

$images = array();
if (isset ($meta['about'])) {
	$images = $meta['about'];
}
?>

<?php if (!empty($images)) : ?>
	<ul class="extra-about-gallery">
		<?php foreach ($images as $image ) : ?>
			<?php
			$image_left = (isset($image['image_left'])) ? $image['image_left'] : null;
			$image_right = (isset($image['image_right'])) ? $image['image_right'] : null;

			if ($image_left && $image_right) :
			?>
				<li>
					<?php
					extra_responsive_image(
						$image_left,
						array(
							'desktop' => array(780, 200),
							'smallDesktop' => array(780, 200),
							'tablet' => array(780, 200),
							'mobile' => array(780, 200),
						),
						'extra-about-image extra-about-image-left'
					);
					?>
					<?php
					extra_responsive_image(
						$image_right,
						array(
							'desktop' => array(780, 200),
							'smallDesktop' => array(780, 200),
							'tablet' => array(780, 200),
							'mobile' => array(780, 200),
						),
						'extra-about-image extra-about-image-right'
					);
					?>
					<span class="extra-about-label"><?php echo $image['label']; ?></span>
				</li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>
