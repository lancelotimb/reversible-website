<?php
/**
 * View Order
 *
 * Shows the details of a particular order on the account page
 *
 * @author    WooThemes
 * @package   WooCommerce/Templates
 * @version   2.2.0
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
	<div class="extra-order-status-wrapper">
		<dl class="inline extra-order-status">
			<dt><?php _e("Date :", 'extra'); ?></dt>
			<dd><strong><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></strong></dd>

			<dt><?php _e("Status :", 'extra'); ?></dt>
			<dd><strong><?php echo wc_get_order_status_name( $order->get_status() ); ?></strong></dd>
		</dl>
		<?php
		if ($order->has_status( 'completed' )) {
			extra_the_invoice_button($order);
		}
		?>
	</div>
	<?php
	if ($order->has_status( 'completed' )) {
		extra_the_order_tracking($order->post->ID);
	}
	?>

	<?php
	do_action( 'woocommerce_view_order', $order_id );
	?>
</div>

<?php if ( $notes = $order->get_customer_order_notes() ) :
	?>
	<div class="extra-order-comments">
		<h2><?php echo _n( "Message sur votre commande", "Messages sur votre commande", count($notes), 'extra' ); ?></h2>
		<ul class="commentlist notes">
			<?php foreach ( $notes as $note ) : ?>
				<li class="comment note">
					<div class="comment_container">
						<div class="comment-text">
							<p class="meta"><?php echo sprintf(__("Reversible vous à écrit le %s", 'extra'), date_i18n( __( 'l jS \o\f F Y, h:ia', 'woocommerce' ), strtotime( $note->comment_date ) )); ?></p>
							<div class="description">
								<?php echo wpautop( wptexturize( $note->comment_content ) ); ?>
							</div>
						</div>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php
endif;