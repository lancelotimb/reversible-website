<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! woocommerce_products_will_display() ) {
	return;
}

$pagination = extra_get_product_pagination();

wp_localize_script('extra-archive-product', 'resultCountMessages', array(
	'noResult' => __('Aucun modèle', 'extra'),
	'oneResult' => __('<span class="count">1</span> modèle unique', 'extra'),
	'manyResults' => __(sprintf('<span class="count">%s</span> modèles uniques', $pagination['totalProduct']), 'extra'),
));

$shop_url = get_permalink( wc_get_page_id( 'shop' ) );
$news_sort_url = $shop_url;
$lowest_first_url = add_query_arg(
	array(
		'orderby' => 'price'
	),
	$shop_url
);
$highest_first_url = add_query_arg(
	array(
		'orderby' => 'price-desc'
	),
	$shop_url
);
?>
<div class="product-sorts-wrapper">
	<nav class="product-sorts">
		<span>
			<?php _e("Trier par :", 'extra'); ?>
		</span>
		<span class="product-sort default-sort-link">
			<a class="product-sort-link default-sort-link active" href="<?php echo $news_sort_url; ?>" data-sort-type="nouveautes">
				<?php _e("Nouveautés", 'extra'); ?>
			</a>
		</span>
		<span class="product-sort lowest-first-sort">
			<a class="product-sort-link lowest-first-sort-link" href="<?php echo $lowest_first_url; ?>" data-sort-type="prix-croissant">
				<?php _e("Prix croissant", 'extra'); ?>
			</a>
		</span>
		<span class="product-sort highest-first-sort">
			<a class="product-sort-link highest-first-sort-link" href="<?php echo $highest_first_url; ?>" data-sort-type="prix-decroissant">
				<?php _e("Prix decroissant", 'extra'); ?>
			</a>
		</span>

		<span class="extra-result-count">
		</span>
	</nav>
</div>
