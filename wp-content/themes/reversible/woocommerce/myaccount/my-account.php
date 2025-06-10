<?php
/**
 * My Account page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$user = get_user_by( 'id', get_current_user_id() );
?>
<h1><?php _e("Informations"); ?></h1>

<div class="account-blocs">
	<div class="account-bloc account-bloc-myaccount_user">
		<header class="account-bloc-header">
			<h3 class="account-bloc-title"><?php _e("Mon compte", 'extra'); ?></h3>
		</header>
		<div class="account-bloc-content">
			<div class="account-line">
				<?php echo esc_attr( $user->first_name ); ?> <?php echo esc_attr( $user->last_name ); ?>
			</div>
			<div class="account-line">
				<?php echo esc_attr( $user->data->user_email ); ?>
			</div>
		</div>
		<footer class="account-bloc-footer">
			<a href="<?php echo wc_customer_edit_account_url(); ?>"><?php _e("Modifier", 'extra'); ?></a>
			<a href="<?php echo wc_get_endpoint_url( 'customer-logout', '', wc_get_page_permalink( 'myaccount' )); ?>"><?php _e("Se deconnecter", 'extra'); ?></a>
		</footer>
	</div>

	<?php wc_get_template( 'myaccount/my-address.php' ); ?>
</div>

<?php do_action( 'woocommerce_before_my_account' ); ?>

<?php wc_get_template( 'myaccount/my-downloads.php' ); ?>

<?php wc_get_template( 'myaccount/my-orders.php', array( 'order_count' => $order_count ) ); ?>

<?php do_action( 'woocommerce_after_my_account' );