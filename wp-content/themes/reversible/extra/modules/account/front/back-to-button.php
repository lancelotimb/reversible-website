<div class="back-to-button-outer">
	<div class="extra-button-wrapper back-to-button-wrapper">
		<?php if (is_user_logged_in()) : ?>
			<a class="back-button back-to-account-button square-button extra-button" href="<?php echo get_permalink( wc_get_page_id( 'myaccount' ) ); ?>" title="<?php _e("Retour à mon compte", "extra"); ?>">
				<svg class="icon icon-previous"><use xlink:href="#icon-previous"></use></svg>
				<span class="inner">
					<?php _e("Retour à mon compte", "extra"); ?>
				</span>
			</a>
		<?php else : ?>
			<a class="back-button back-to-shop-button square-button extra-button" href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>" title="<?php _e("Retour à la boutique", "extra"); ?>">
				<svg class="icon icon-previous"><use xlink:href="#icon-previous"></use></svg>
				<span class="inner">
					<?php _e("Retour à la boutique", "extra"); ?>
				</span>
			</a>
		<?php endif; ?>
	</div>
</div>
