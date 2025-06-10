<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.5.1
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<h1><?php _e("Vos informations", 'extra'); ?></h1>
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
		<form action="" method="post" class="account-form">

			<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

			<p class="form-row form-row-first validate-required">
				<label for="account_first_name"><?php _e( 'First name', 'woocommerce' ); ?> <span class="required">*</span></label>
				<input type="text" class="input-text" name="account_first_name" id="account_first_name" value="<?php echo esc_attr( $user->first_name ); ?>" />
				<span class="extra-invalid-required"><?php _e("Ce champ est requis.", 'extra'); ?></span>
			</p>
			<p class="form-row form-row-last validate-required">
				<label for="account_last_name"><?php _e( 'Last name', 'woocommerce' ); ?> <span class="required">*</span></label>
				<input type="text" class="input-text" name="account_last_name" id="account_last_name" value="<?php echo esc_attr( $user->last_name ); ?>" />
				<span class="extra-invalid-required"><?php _e("Ce champ est requis.", 'extra'); ?></span>
			</p>
			<div class="clear"></div>

			<p class="form-row form-row-wide validate-required validate-email">
				<label for="account_email"><?php _e( 'Email address', 'woocommerce' ); ?> <span class="required">*</span></label>
				<input type="email" class="input-text" name="account_email" id="account_email" value="<?php echo esc_attr( $user->user_email ); ?>" />

				<span class="extra-invalid-required"><?php _e("Ce champ est requis.", 'extra'); ?></span>
				<span class="extra-invalid-email"><?php _e("L'email est invalide.", 'extra'); ?></span>
			</p>

			<fieldset class="change-password-bloc">
				<legend><strong><?php _e( 'Password Change', 'woocommerce' ); ?></strong></legend>

				<p class="form-row form-row-wide">
					<label for="password_current"><?php _e( "Mot de passe actuel", 'extra' ); ?> <em><?php _e("(laisser vide pour le conserver)", 'extra'); ?></em></label>
					<input type="password" class="input-password input-text" name="password_current" id="password_current" />
				</p>
				<p class="form-row form-row-wide validate-password">
					<label for="password_1"><?php _e( "Nouveau mot de passe", 'extra' ); ?> <em><?php _e("(laisser vide pour le conserver)", 'extra'); ?></em></label>
					<input type="password" class="input-password input-text" name="password_1" id="password_1" />

					<span class="extra-invalid-password"><?php _e("Les mots de passe ne correspondent pas.", 'extra'); ?></span>
				</p>
				<p class="form-row form-row-wide validate-password">
					<label for="password_2"><?php _e( 'Confirm New Password', 'woocommerce' ); ?></label>
					<input type="password" class="input-password input-text" name="password_2" id="password_2" />

					<span class="extra-invalid-password"><?php _e("Les mots de passe ne correspondent pas.", 'extra'); ?></span>
				</p>
			</fieldset>
			<div class="clear"></div>

			<?php do_action( 'woocommerce_edit_account_form' ); ?>

			<p>
				<?php wp_nonce_field( 'save_account_details' ); ?>
				<?php
				/* <input type="submit" class="button" name="save_account_details" value="<?php _e( 'Save changes', 'woocommerce' ); ?>" /> */
				?>

				<button id="" type="submit" class="button big-shop-button account-form-submit" name="save_account_details">
					<span class="inner">
						<?php _e( 'Save changes', 'woocommerce' ); ?>
					</span>
				</button>

				<input type="hidden" name="save_account_details" value="<?php _e( 'Save changes', 'woocommerce' ); ?>" />
				<input type="hidden" name="action" value="save_account_details" />
			</p>

			<?php do_action( 'woocommerce_edit_account_form_end' ); ?>

		</form>
	</div>
</div>