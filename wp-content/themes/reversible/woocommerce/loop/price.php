<?php
/**
 * Loop Price
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product;

?>

<?php if ( $price_html = $product->get_price_html() ) : ?>
	<span class="price">
		<?php if ($product->is_in_stock()) : ?>
			<?php echo $price_html; ?>
		<?php else : ?>
			<span class="price-outofstock"><?php _e("Vendu", 'extra'); ?></span>
		<?php endif; ?>
	</span>
<?php endif; ?>