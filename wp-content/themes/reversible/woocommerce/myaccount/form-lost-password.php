<?php
/**
 * Lost password form
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<h1><?php _e( "Réinitialisation", 'extra' ); ?></h1>
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

		<form method="post" class="lost_reset_password">

			<?php if( 'lost_password' == $args['form'] ) : ?>

				<p class="chapo"><?php _e( "Veuillez saisir votre identifiant ou votre adresse email.", 'extra' ); ?></p>
				<p><?php _e( "Vous recevrez un lien par email pour créer un nouveau mot de passe.", 'extra' ); ?></p>

				<p class="form-row form-row-first"><label for="user_login"><?php _e( 'Username or email', 'woocommerce' ); ?></label> <input class="input-text" type="text" name="user_login" id="user_login" /></p>

			<?php else : ?>

				<p><?php echo apply_filters( 'woocommerce_reset_password_message', __( 'Enter a new password below.', 'woocommerce') ); ?></p>

				<p class="form-row form-row-first">
					<label for="password_1"><?php _e( 'New password', 'woocommerce' ); ?> <span class="required">*</span></label>
					<input type="password" class="input-text" name="password_1" id="password_1" />
				</p>
				<p class="form-row form-row-last">
					<label for="password_2"><?php _e( 'Re-enter new password', 'woocommerce' ); ?> <span class="required">*</span></label>
					<input type="password" class="input-text" name="password_2" id="password_2" />
				</p>

				<input type="hidden" name="reset_key" value="<?php echo isset( $args['key'] ) ? $args['key'] : ''; ?>" />
				<input type="hidden" name="reset_login" value="<?php echo isset( $args['login'] ) ? $args['login'] : ''; ?>" />

			<?php endif; ?>

			<div class="clear"></div>

			<p class="form-row">
				<input type="hidden" name="wc_reset_password" value="true" />

				<button type="submit" class="button extra-button">
					<?php echo 'lost_password' == $args['form'] ? __( 'Reset Password', 'woocommerce' ) : __( 'Save', 'woocommerce' ); ?>
				</button>
			</p>

			<?php wp_nonce_field( $args['form'] ); ?>

		</form>
	</div>
</div>