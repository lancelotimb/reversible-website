<?php
/**
 * Edit address form
 *
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user;

$page_title = ( $load_address === 'billing' ) ? __( 'Billing Address', 'woocommerce' ) : __( 'Shipping Address', 'woocommerce' );

get_currentuserinfo();

//var_dump('edit-account');
//var_dump(is_wc_endpoint_url('edit-address'));

?>

<h1><?php echo apply_filters( 'woocommerce_my_account_edit_address_title', $page_title ); ?></h1>

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
		<?php if ( ! $load_address ) : ?>

			<?php wc_get_template( 'myaccount/my-address.php' ); ?>

		<?php else : ?>
			<form method="post" class="account-form">

				<?php do_action( "woocommerce_before_edit_address_form_{$load_address}" ); ?>

				<?php foreach ( $address as $key => $field ) : ?>

					<?php woocommerce_form_field( $key, $field, ! empty( $_POST[ $key ] ) ? wc_clean( $_POST[ $key ] ) : $field['value'] ); ?>

				<?php endforeach; ?>

				<?php do_action( "woocommerce_after_edit_address_form_{$load_address}" ); ?>

				<p>
					<input type="hidden" name="save_address" value="<?php _e( "Changer l'adresse", 'extra' ); ?>" />
					<button type="submit" class="button extra-button account-form-submit">
						<?php _e( "Changer l'adresse", 'extra' ); ?>
					</button>
					<?php wp_nonce_field( 'woocommerce-edit_address' ); ?>
					<input type="hidden" name="action" value="edit_address" />
				</p>

			</form>

		<?php endif; ?>
	</div>
</div>