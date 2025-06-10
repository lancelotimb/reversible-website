<?php
the_post();
get_template_part("header-main-image");
get_template_part("header-sub-navigation");

$content = get_the_content();
?>

<div class="content main-content<?php echo (!empty($content)) ? ' has-content' : ''; ?>">
    <?php the_content(); ?>
	<?php
	if(is_checkout()) {
		echo do_shortcode('[woocommerce_checkout]');
	}
	?>
</div>