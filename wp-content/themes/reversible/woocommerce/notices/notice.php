<?php
/**
 * Show messages
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! $messages ){
	return;
}

?>

<div class="extra-woocommerce-notices">
	<?php foreach ( $messages as $message ) : ?>

		<?php if ( $message == "Please read and accept the terms and conditions to proceed with your order." ) {
			$message = "Veuillez accepter les conditions generales de vente";
		} ?>
		<div class="woocommerce-info"><?php echo wp_kses_post( $message ); ?></div>
	<?php endforeach; ?>
</div>
