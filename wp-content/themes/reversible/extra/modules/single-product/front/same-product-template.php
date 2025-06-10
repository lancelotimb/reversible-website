<?php
global $extra_product_template, $post, $product, $extra_same_products;
$current_post = $post;

if ($extra_product_template !== null) :
	if ($extra_same_products && !empty($extra_same_products) && count($extra_same_products) > 1) : ?>
		<div class="separator-wrapper products">
			<hr class="separator"/>
		</div>
		<ul class="products same-product-template" id="tous-les-<?php echo $extra_product_template->post_name; ?>" >
			<li class="same-product-template-title product">
				<div class="inner">
					<h2 itemprop="model" class="product_title entry-title"><?php echo $extra_product_template->post_title; ?></h2>
					<h4><?php _e("Découvrez d'autres modèles", 'extra'); ?></h4>
				</div>
			</li>
			<?php
				foreach ($extra_same_products as $post) {
					if ($post->ID != $current_post->ID) {
						$product = new WC_Product($post);
						wc_get_template_part( 'content', 'product' );
					}
				}
			?>
		</ul>
	<?php endif; ?>
<?php endif; ?>