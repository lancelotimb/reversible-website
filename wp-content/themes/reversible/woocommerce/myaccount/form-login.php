<?php
/**
 * Login Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<h1><?php _e( 'Login', 'woocommerce' ); ?></h1>
<div class="account-columns">
	<div class="account-column account-column-1">
		<?php
		/**********************
		 *
		 *
		 * INSTRUCTIONS
		 *
		 *
		 *********************/
		include_once THEME_MODULES_PATH . '/account/front/back-to-button.php';
		?>
	</div>

	<div class="account-column account-column-2">

		<form method="post" class="login">

			<?php do_action( 'woocommerce_login_form_start' ); ?>

			<p class="form-row form-row-wide">
				<label for="username"><?php _e( 'Username or email address', 'woocommerce' ); ?> <span class="required">*</span></label>
				<input type="text" class="input-text" name="username" id="username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
			</p>
			<p class="form-row form-row-wide">
				<label for="password"><?php _e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
				<input class="input-text" type="password" name="password" id="password" />
			</p>

			<?php do_action( 'woocommerce_login_form' ); ?>

			<p class="form-row">
				<input name="rememberme" type="checkbox" id="rememberme" value="forever" />
				<label for="rememberme" class="inline"><?php _e( 'Remember me', 'woocommerce' ); ?></label>

				<?php wp_nonce_field( 'woocommerce-login' ); ?>
				<input type="hidden" name="login" value="<?php _e( 'Login', 'woocommerce' ); ?>" />
				<button type="submit" class="button big-shop-button">
					<span class="inner">
						<?php _e( 'Login', 'woocommerce' ); ?>
					</span>
				</button>
			</p>
			<p class="lost_password">
				<a href="<?php echo esc_url( wc_lostpassword_url() ); ?>"><?php _e( 'Lost your password?', 'woocommerce' ); ?></a>
			</p>

			<?php do_action( 'woocommerce_login_form_end' ); ?>

		</form>
	</div>
</div>