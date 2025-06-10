<?php
global $extra_options;

$url = '#';
if (isset ($extra_options['sarbacane_url'])) {
	$url = $extra_options['sarbacane_url'];
}
?>

<div class="column column-three newsletter">
	<form action="<?php echo $url ?>" method="get" class="newsletter-form">
		<h4><?php _e("Newsletter", 'extra'); ?></h4>
		<input type="hidden" name="action" value="INSERT">
		<input class="newsletter-email" type="text" placeholder="<?php _e("Votre Email", 'extra'); ?>" name="Email">
		<button class="newsletter-button" type="submit"><?php _e("Inscription", 'extra'); ?></button>
	</form>
</div>