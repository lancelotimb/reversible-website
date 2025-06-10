<?php
/**
 * Checkout login form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) {
	return;
}

//$info_message  = apply_filters( 'woocommerce_checkout_login_message', __( 'Returning customer?', 'woocommerce' ) );
//$info_message .= ' <a href="#" class="showlogin">' . __( 'Je suis déjà client', 'woocommerce' ) . '</a>';
//wc_print_notice( $info_message, 'notice' );
?>
<div class="form-bloc login-form-bloc first-login-form-bloc">
	<?php
		woocommerce_login_form(
			array(
//				'message'  => __( 'If you have shopped with us before, please enter your details in the boxes below. If you are a new customer please proceed to the Billing &amp; Shipping section.', 'woocommerce' ),
//				'message'  => __( "Si vous avez déjà acheté chez nous, veuillez entrer vos informations dans les champs ci-dessous.", 'extra' ),
				'redirect' => add_query_arg(array('justlogged' => '1'), wc_get_page_permalink( 'checkout' )),
				'hidden'   => false
			)
		);
	?>
</div>
<div class="login-form-bloc-mobile-separator"><?php _e("ou", 'extra'); ?></div>
<div class="form-bloc login-form-bloc">
	<a class="form-link form-link-next" href="#checkout_form">
		<span class="inner-text">
			<?php _e("Je suis <strong>nouveau</strong> client", 'extra'); ?>
		</span>
		<span class="next-button">
			<svg class="icon icon-arrow-down">
				<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-arrow-down"></use>
			</svg>
		</span>
	</a>
</div>