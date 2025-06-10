<?php
/**
 * Login form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( is_user_logged_in() ) {
	return;
}

?>
<form method="post" class="login" <?php if ( $hidden ) echo 'style="display:none;"'; ?>>

	<?php do_action( 'woocommerce_login_form_start' ); ?>

	<?php if ( $message ) echo wpautop( wptexturize( $message ) ); ?>

	<p class="form-row form-row-first">
		<label for="username"><?php _e( 'Username or email', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input type="text" class="input-text" name="username" id="username" />
	</p>
	<p class="form-row form-row-last">
		<label for="password"><?php _e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input class="input-text" type="password" name="password" id="password" />
	</p>
	<div class="clear"></div>

	<?php do_action( 'woocommerce_login_form' ); ?>

	<p class="form-row rememberme-wrapper">
		<?php
		$name = esc_attr( $name );
		$nonce_field = '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce( 'woocommerce-login' ) . '" />';
		if ( $referer ) {
			$nonce_field .= wp_referer_field( false );
		}
		echo $nonce_field;
		?>

		<input type="hidden" name="login" value="login" />
		<button type="submit" class="button big-shop-button">
			<span class="inner">
				<?php _e( 'Login', 'woocommerce' ); ?>
			</span>
		</button>
		<input type="hidden" name="redirect" value="<?php echo esc_url( $redirect ) ?>" />
		<input name="rememberme" type="checkbox" id="rememberme" value="forever" />
		<label for="rememberme" class="inline"><?php _e( 'Remember me', 'woocommerce' ); ?></label>
	</p>
	<p class="lost_password">
		<a href="<?php echo esc_url( wc_lostpassword_url() ); ?>"><?php _e( "J'ai paumé mon mot de passe", 'extra' ); ?></a>
	</p>

	<div class="clear"></div>

	<?php do_action( 'woocommerce_login_form_end' ); ?>

</form>
