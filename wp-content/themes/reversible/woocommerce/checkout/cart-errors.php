<?php
/**
 * Cart errors page
 *
 * @author 	WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php //wc_print_notices(); ?>

<p><?php _e( "Il y a quelques problèmes avec les articles dans votre panier.", 'extra' ) ?></p>

<?php do_action( 'woocommerce_cart_has_errors' ); ?>

<p><a class="button wc-backward" href="<?php echo esc_url( get_permalink(wc_get_page_id( 'shop' ) ) ); ?>"><?php _e( 'Retour à la boutique', 'extra' ) ?></a></p>
