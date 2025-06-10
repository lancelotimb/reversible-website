<?php
/**
 * Checkout Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */



if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section_index = 1;
?>

<?php
do_action( 'woocommerce_before_checkout_form', $checkout );
?>
<div class="form-line form-line-identity" id="form-line-identity">
	<h2 class="form-line-title"><?php echo $section_index++; ?>. <?php _e("Identifiez-vous", 'extra'); ?></h2>
	<div class="form-line-content">
		<div class="form-line-content-inner">
			<?php
			if (is_user_logged_in()) :
				$current_user = wp_get_current_user();
				$redirect = get_permalink(wc_get_page_id('checkout'));

				$logoutUrl = add_query_arg(
					array(
						'keepcart' => '1'
					),
					wp_logout_url( $redirect )
				);
				/*
				<p><?php echo sprintf(__("Bonjour êtes-vous bien %s ?", 'extra'), '<strong>'.$current_user->data->display_name.'</strong>'); ?></p>
				 */
				?>

				<div class="form-bloc login-form-bloc first-login-form-bloc">
					<a class="form-link" href="<?php echo esc_url($logoutUrl); ?> ">
						<span class="inner-text">
							<?php echo sprintf(__("Je ne suis pas %s...", 'extra'), '<strong>'.$current_user->data->display_name.'</strong>'); ?>
						</span>
					</a>
				</div>
				<div class="login-form-bloc-mobile-separator"><?php _e("ou", 'extra'); ?></div>
				<div class="form-bloc login-form-bloc">
					<a class="form-link form-link-next" href="#checkout_form">
						<span class="inner-text">
							<?php echo sprintf(__("Je suis bien %s&nbsp;!", 'extra'), '<strong>'.$current_user->data->display_name.'</strong>'); ?>
						</span>
						<span class="next-button">
							<svg class="icon icon-arrow-down">
								<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-arrow-down"></use>
							</svg>
						</span>
					</a>
				</div>
			<?php else : ?>
			<?php endif; ?>

			<?php
			do_action( 'extra_login_checkout_form', $checkout );
			?>
		</div>
	</div>
</div>

<?php
// If checkout registration is disabled and not logged in, the user cannot checkout
//if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
//	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
//	return;
//}

// filter hook for include new pages inside the payment method
$get_checkout_url = apply_filters( 'woocommerce_get_checkout_url', WC()->cart->get_checkout_url() ); ?>

<form id="checkout_form" name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( $get_checkout_url ); ?>" enctype="multipart/form-data">

	<?php
	// For ajax remove in cart
	?>
	<input type="hidden" id="extra_remove_from_cart_id" name="extra_remove_from_cart_id" >

	<?php if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>

		<fieldset class="form-line form-line-address" id="form-line-address">
			<legend class="form-line-title"><?php echo $section_index++; ?>. <?php _e("Vos informations", 'extra'); ?></legend>

			<div class="form-line-content">
				<div class="form-line-content-inner">
					<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

					<div class="form-columns" id="customer_details">
						<div class="form-column form-column-1">
							<?php do_action( 'woocommerce_checkout_billing' ); ?>
						</div>

						<div class="form-column form-column-2">
							<?php do_action( 'woocommerce_checkout_shipping' ); ?>
						</div>
					</div>

					<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>


				</div>

				<button class="button extra-button form-link-next" type="button" id="extra-validate-billing-shipping">
					<?php _e("Continuer", 'extra'); ?>
				</button>
			</div>
		</fieldset>

	<?php endif; ?>

	<fieldset class="form-line form-line-order-review" id="form-line-order-review">
		<legend class="form-line-title" id="order_review_heading"><?php echo $section_index++; ?>. <?php _e("Paiement", 'extra'); ?></legend>

		<div class="form-line-content">
			<div class="form-line-content-inner">
				<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

				<div id="order_review" class="woocommerce-checkout-review-order">
					<?php do_action( 'woocommerce_checkout_order_review' ); ?>
				</div>
				<div id="order_empty" style="display: none;">
					<p class="chapo">
						<?php _e("Oh non... Vous n'avez plus de produit dans votre commande", 'extra'); ?>
					</p>
					<p>
						<?php _e("C'est pas grave, je retourne "); ?>
						<a class="extra-flash-messages-close-link" href="<?php echo get_permalink(wc_get_page_id( 'shop' )) ?>">
							<strong><?php _e("à la boutique ", 'extra'); ?></strong>
						</a>
					</p>
				</div>

				<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
			</div>
		</div>
	</fieldset>
</form>

<?php
do_action( 'woocommerce_after_checkout_form', $checkout );