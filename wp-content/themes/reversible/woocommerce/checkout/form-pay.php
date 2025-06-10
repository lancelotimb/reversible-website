<?php
/**
 * Pay for order form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-pay.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author   WooThemes
 * @package  WooCommerce/Templates
 * @version  2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<h1><?php _e("Détail de la commande", 'extra'); ?></h1>

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

<div class="extra-order-details">
	<dl class="inline extra-order-status">
		<dt><?php _e("Date :", 'extra'); ?></dt>
		<dd><strong><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></strong></dd>

		<dt><?php _e("Status :", 'extra'); ?></dt>
		<dd><strong><?php echo wc_get_order_status_name( $order->get_status() ); ?></strong></dd>
	</dl>
</div>

<form id="order_review" method="post">
	<table class="shop_table extra-product-table">
		<thead>
			<tr>
				<th class="table-column-1"><?php _e("Produit", 'extra'); ?></th>
				<th class="table-column-2"><?php _e("Modèle / Référence", 'extra'); ?></th>
				<th class="table-column-3"><?php _e("Prix", 'extra'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			if ( sizeof( $order->get_items() ) > 0 ) :
				foreach ( $order->get_items() as $item ) :
					$_product  = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
					include THEME_MODULES_PATH . '/checkout/front/order-item.php';
				endforeach;
			endif;
			?>
		</tbody>
	</table>

	<?php
	/**********************
	 *
	 *
	 * TOTAL
	 *
	 *
	 *********************/
	include THEME_MODULES_PATH . '/checkout/front/order-total.php';
	?>

	<div class="order-instructions-wrapper">
		<div class="order-instructions">
			<div id="payment">
				<?php if ( $order->needs_payment() ) : ?>
					<h3><?php _e( 'Payment', 'woocommerce' ); ?></h3>
					<ul class="payment_methods methods">
						<?php
						if ( $available_gateways = WC()->payment_gateways->get_available_payment_gateways() ) {
							// Chosen Method
							if ( sizeof( $available_gateways ) )
								current( $available_gateways )->set_current();

							foreach ( $available_gateways as $gateway ) {
								?>
								<li class="payment_method_<?php echo $gateway->id; ?>">
									<input id="payment_method_<?php echo $gateway->id; ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />
									<label for="payment_method_<?php echo $gateway->id; ?>"><?php echo $gateway->get_title(); ?> <?php echo $gateway->get_icon(); ?></label>
									<?php
									if ( $gateway->has_fields() || $gateway->get_description() ) {
										echo '<div class="payment_box payment_method_' . $gateway->id . '" style="display:none;">';
										$gateway->payment_fields();
										echo '</div>';
									}
									?>
								</li>
							<?php
							}
						} else {

							echo '<p>' . __( 'Sorry, it seems that there are no available payment methods for your location. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) . '</p>';

						}
						?>
					</ul>
				<?php endif; ?>

				<div class="form-row">
					<?php wp_nonce_field( 'woocommerce-pay' ); ?>
					<?php
					$pay_order_button_text = apply_filters( 'woocommerce_pay_order_button_text', __( 'Pay for order', 'woocommerce' ) );

//					echo apply_filters( 'woocommerce_pay_order_button_html', '<input type="submit" class="button alt" id="place_order" value="' . esc_attr( $pay_order_button_text ) . '" data-value="' . esc_attr( $pay_order_button_text ) . '" />' );
					?>

					<button type="submit" class="button alt big-shop-button" id="place_order" data-value="<?php echo esc_attr( $pay_order_button_text ); ?>" >
						<span class="inner">
							<?php echo esc_attr( $pay_order_button_text ); ?>
						</span>
					</button>

					<input type="hidden" name="woocommerce_pay" value="1" />
				</div>

			</div>
		</div>
		<hr class="order-instructions-separator" />
	</div>


	<?php
	/**********************
	 *
	 *
	 * ADDRESS
	 *
	 *
	 *********************/
	include_once THEME_MODULES_PATH . '/checkout/front/order-address.php';
	?>
</form>