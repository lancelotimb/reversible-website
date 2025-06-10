<?php //$messages = extra_read_flash_messages(); ?>
<div class="extra-checkout-notices-wrapper" style="display: none;">
	<a href="#extra-checkout-popup" id="extra-checkout-notices-link"></a>
	<div class="extra-flash-message" id="extra-checkout-popup">
		<a class="square-button close-button extra-flash-messages-close-link extra-button" href="#" title="<?php _e("Fermer", 'extra'); ?>" >
			<svg class="icon icon-close"><use xlink:href="#icon-close"></use></svg>
			<span class="inner"><?php _e("Fermer", 'extra'); ?></span>
		</a>
		<div class="extra-checkout-notices" >
			<?php
			/*
			<?php wc_print_notices(); ?>

			<?php foreach ($messages as $message) : ?>
				<?php echo $message; ?>
			<?php endforeach; ?>
			 */
			?>
		</div>
	</div>
</div>