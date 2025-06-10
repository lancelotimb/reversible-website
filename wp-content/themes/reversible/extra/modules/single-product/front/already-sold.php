<?php
global $post, $extra_product_template;

$extra_product_template = extra_get_product_template();
?>
<ul class="woocommerce-error">
	<li>
		<h4><?php _e("Vendu !", 'extra'); ?></h4>
		<p class="chapo">
			<?php _e("Oh non... Ce produit vous a filé entre les doigts", 'extra'); ?>
		</p>
		<p>
			<?php _e("C'est pas grave, je vais "); ?>
			<a href="<?php echo get_permalink(wc_get_page_id( 'shop' )).'#/recherche='.$extra_product_template->post_title; ?>">
				<strong><?php echo sprintf(__("trouver un autre %s", 'extra'), $extra_product_template->post_title); ?></strong>
			</a>
		</p>
	</li>
</ul>