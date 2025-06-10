<?php
/**
 * Checkout Payment Section
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<?php if ( ! is_ajax() ) : ?>
	<?php do_action( 'woocommerce_review_order_before_payment' ); ?>
<?php endif; ?>

<div id="payment" class="woocommerce-checkout-payment">
	<?php if ( WC()->cart->needs_payment() ) : ?>
	<h3><?php _e("Choisissez votre moyen de paiement", 'extra'); ?></h3>
	<ul class="payment_methods methods">
		<?php
			if ( ! empty( $available_gateways ) ) {
				foreach ( $available_gateways as $gateway ) {
					wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
				}
			} else {
				if ( ! WC()->customer->get_country() ) {
					$no_gateways_message = __( 'Please fill in your details above to see available payment methods.', 'woocommerce' );
				} else {
					$no_gateways_message = __( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' );
				}

				echo '<p>' . apply_filters( 'woocommerce_no_available_payment_methods_message', $no_gateways_message ) . '</p>';
			}
		?>
	</ul>
	<?php endif; ?>

	<div class="form-row place-order">

		<noscript><?php _e( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the <em>Update Totals</em> button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ); ?><br/><input type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php _e( 'Update totals', 'woocommerce' ); ?>" /></noscript>

		<?php wp_nonce_field( 'woocommerce-process_checkout' ); ?>

		<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

		<?php
			if ( apply_filters( 'woocommerce_checkout_show_terms', true ) && function_exists( 'wc_terms_and_conditions_checkbox_enabled' ) ) {
			do_action( 'woocommerce_checkout_before_terms_and_conditions' );
		?>
			<div class="woocommerce-terms-and-conditions-wrapper">
				<?php
					do_action( 'woocommerce_checkout_terms_and_conditions' );
				?>

				<?php if ( wc_terms_and_conditions_checkbox_enabled() ) : ?>
					<p class="form-row validate-required">
						<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
						<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms" <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); // WPCS: input var ok, csrf ok. ?> id="terms" />
							<span class="woocommerce-terms-and-conditions-checkbox-text"><?php printf( __( "J’ai lu et j’accepte les <a href=\"%s\" target=\"_blank\">conditions générales de vente</a>", 'extra' ), esc_url( wc_get_page_permalink( 'terms' ) ) ); ?></span>&nbsp;<abbr class="required" title="<?php esc_attr_e( 'required', 'woocommerce' ); ?>">*</abbr>
						</label>
						<input type="hidden" name="terms-field" value="1" />
					</p>
				<?php endif; ?>
			</div>
		

		<?php 

		do_action( 'woocommerce_checkout_after_terms_and_conditions' );
		} 
		?>

		<input type="hidden" name="woocommerce_checkout_place_order" value="<?php echo esc_attr( $order_button_text ); ?>">
		<button type="submit" class="button alt extra-button" id="place_order" data-value="<?php echo esc_attr( $order_button_text ); ?>" >
			<?php echo esc_attr( $order_button_text ); ?>
		</button>


		<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

	</div>

	<div class="clear"></div>
</div>

<?php
if ( ! is_ajax() )  {
	do_action( 'woocommerce_review_order_after_payment' );
}