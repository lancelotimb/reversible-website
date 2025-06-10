<?php
/**
 * Output a single payment method
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<li class="payment_method_<?php echo $gateway->id; ?>">
	<input id="payment_method_<?php echo $gateway->id; ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />

	<label for="payment_method_<?php echo $gateway->id; ?>">
		<?php _e("Paiement", 'extra') ?> <strong><?php echo $gateway->get_title(); ?></strong> <?php echo $gateway->get_icon(); ?>
	</label>
	<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
		<div class="payment_box payment_method_<?php echo $gateway->id; ?>"<?php echo ($gateway->chosen !== true) ? ' style="display:none;': ''; ?>">

			<?php if($gateway->id == 'paypal') : ?>
				<img src="<?php echo THEME_URI.'/assets/img/paypal.jpg'; ?>" alt="<?php _e("Paypal", 'extra'); ?>" />
			<?php endif; ?>
			<?php if($gateway->id == 'etransactions_std') : ?>
				<img src="<?php echo THEME_URI.'/assets/img/bank.jpg'; ?>" alt="<?php _e("Cartes bancaires", 'extra'); ?>" />
			<?php endif; ?>
			<?php $gateway->payment_fields(); ?>
		</div>
	<?php endif; ?>
</li>