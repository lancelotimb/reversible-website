<?php
$small_images_size = array(
	'desktop' => array(270, 270),
	'smalldesktop' => array(270, 270),
	'tablet' => array(270, 270),
	'mobile' => array(270, 270),
);
?>

<?php if (isset ($extra_product_template_meta[$meta_image_name])) : ?>
<div class="small-image-wrapper <?php echo str_replace('_', '-', $meta_image_name); ?>">
	<?php
	extra_responsive_image(
		(isset ($extra_product_template_meta[$meta_image_name])) ? $extra_product_template_meta[$meta_image_name] : $default_tumbnail['id'],
		$small_images_size,
		'summary-anchor small-image image'
	);
	?>
</div>
<?php endif; ?>