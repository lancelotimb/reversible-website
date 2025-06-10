<?php if ( is_user_logged_in() ) { ?>
	<?php
	$current_user = wp_get_current_user();
	?>
	<a class="account-link" href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('Mon compte','extra'); ?>">
		<?php echo sprintf(__('Bonjour <strong>%s</strong>', 'extra'), $current_user->first_name); ?>
	</a>
<?php }
else { ?>
	<a class="account-link" href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('Identifiez-vous','extra'); ?>"><?php _e('Identifiez-vous','extra'); ?></a>
<?php } ?>
