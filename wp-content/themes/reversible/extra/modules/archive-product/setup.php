<?php
// LOAD ALL PRODUCTS
define('PRODUCTS_PER_PAGE', -1);
//define('PRODUCTS_PER_PAGE', 30);
//define('PRODUCTS_PER_PAGE', 4);

/**********************
 *
 *
 * CUSTOM FUNCTIONS
 *
 *
 *********************/
function extra_get_product_pagination() {
	global $wp_query, $extra_product_pagination;

	if ($extra_product_pagination == null) {
		$paged    = max( 1, $wp_query->get( 'paged' ) );
		$per_page = $wp_query->get( 'posts_per_page' );
		$total    = (PRODUCTS_PER_PAGE == -1) ? $wp_query->found_posts : PRODUCTS_PER_PAGE;
		$first    = ( $per_page * $paged ) - $per_page + 1;
		$last     = min( $total, $wp_query->get( 'posts_per_page' ) * $paged );

		$extra_product_pagination = array(
			'currentPage' => $paged,
			'productPerPage' => $per_page,
			'totalProduct' => $total,
			'currentPageFirst' => $first,
			'currentPageLast' => $last,
			'nbPage' => ceil($total / $per_page),
			//'basePageUrl' => get_permalink( wc_get_page_id( 'shop' ) ) . 'page/'
		);
	}

	return $extra_product_pagination;
}

/**********************
 *
 *
 * ENQUEUE ASSETS
 *
 *
 *********************/
function extra_archive_product_enqueue_assets() {
	// Use by shopping-cart so everywhere
	wp_enqueue_script('extra-product-thumbnail-loader', THEME_MODULES_URI.'/archive-product/front/js/product-thumbnail-loader.js', array('extra', 'jquery'), false, true);
	wp_enqueue_style('extra-product-item', THEME_MODULES_URI.'/archive-product/front/css/product-item.less');

	if (is_archive('product') || is_singular('product')) {
		wp_enqueue_style('extra-archive-product', THEME_MODULES_URI.'/archive-product/front/css/archive-product.less');
		wp_enqueue_style('extra-product-search', THEME_MODULES_URI.'/archive-product/front/css/product-search.less');

		// SCRIPTS
		wp_enqueue_script('extra-archive-product', THEME_MODULES_URI.'/archive-product/front/js/archive-product.js', array('extra', 'extra-common', 'extra-product-thumbnail-loader', 'jquery'), false, true);
		wp_enqueue_script('extra-product-search', THEME_MODULES_URI.'/archive-product/front/js/product-search.js', array('extra-archive-product'), false, true);
	}

	if (is_archive('product')) {
		wp_enqueue_style('extra-product-filters', THEME_MODULES_URI.'/archive-product/front/css/product-filters.less');

		// SCRIPTS
		wp_enqueue_script('diacritics', THEME_URI.'/assets/js/diacritics.js', array(), null, true);
		wp_enqueue_script('extra-archive-product-filter', THEME_MODULES_URI.'/archive-product/front/js/archive-product-filter.js', array('extra-archive-product', 'extra-product-search', 'diacritics', 'extra-shopping-cart'), false, true);

		wp_enqueue_script('extra-archive-product-totop', THEME_MODULES_URI.'/archive-product/front/js/archive-product-totop.js', array('extra-archive-product'), false, true);

		wp_localize_script('extra-archive-product-filter', 'resultCount', extra_get_product_pagination());
		//Check archive page and redirect to shop url with hash
		$redirect_to_url = null;
		$shop_url = get_permalink(wc_get_page_id('shop'));
		if (isset($_REQUEST['orderby'])) {
			$orderBy = $_REQUEST['orderby'];
			if ($orderBy == 'price') {
				$redirect_to_url = $shop_url.'#/tri=prix-croissant';
			} else if ($orderBy == 'price-desc') {
				$redirect_to_url = $shop_url.'#/tri=prix-decroissant';
			}
		} else {
			global $extra_product_material_filter, $extra_product_type_filter, $extra_product_collection_filter;
			if ($extra_product_material_filter != '') {
				$redirect_to_url = $shop_url.'#/materiel='.$extra_product_material_filter;
			} else if ($extra_product_type_filter != '') {
				$redirect_to_url = $shop_url.'#/type='.$extra_product_type_filter;
			} else if ($extra_product_collection_filter != '') {
				$redirect_to_url = $shop_url.'#/collection='.$extra_product_collection_filter;
			}
		}
		wp_localize_script('extra-archive-product-filter', 'redirectToUrl', $redirect_to_url);
	}
}
add_action('wp_enqueue_scripts', 'extra_archive_product_enqueue_assets');

/**********************
 *
 *
 * WOOCOMMERCE OVERRIDES
 *
 *
 *********************/
// ALL PRODUCTS PER PAGE
add_filter( 'loop_shop_per_page', function ($cols) {return PRODUCTS_PER_PAGE;}, 20 );

// REMOVE ADD TO CART LINK IN PRODUCT LIST
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
// REMOVE PAGINATION
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );

/**********************
 *
 *
 * PRODUCT THUMBNAIL
 *
 *
 *********************/
function extra_woocommerce_placeholder_img_src($src) {
	global $extra_options;
	$default = $extra_options['default-thumbnail'];

	return get_permalink($default['id']);
}
add_filter('woocommerce_placeholder_img_src', 'extra_woocommerce_placeholder_img_src');

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
add_action('woocommerce_before_shop_loop_item_title', 'extra_woocommerce_template_loop_product_thumbnail');
function extra_woocommerce_template_loop_product_thumbnail() {
	include THEME_MODULES_PATH . '/archive-product/front/product-thumbnail.php';
}

/**********************
 *
 *
 * PRODUCTS HEADER - RESULT COUNT & SORTING
 *
 *
 *********************/
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
add_action( 'woocommerce_before_shop_loop', 'extra_woocommerce_before_loop', 20);

function extra_woocommerce_before_loop() {
	get_template_part('extra/modules/archive-product/front/products-header');
}
/**********************
 *
 *
 * REMOVE TITLE
 *
 *
 *********************/
add_filter('woocommerce_show_page_title', function () {
	return false;
});

/**********************
 *
 *
 * ORDER BY
 *
 *
 *********************/
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);


//function archive_product_header_menu_second($template_path) {
//	if (is_archive('product')) {
//		$template_path = 'extra/modules/archive-product/front/product-filter';
//	}
//
//	return $template_path;
//}
//add_filter('extra_template_header_menu_second', 'archive_product_header_menu_second', 10, 1);


function archive_product_header_menu_second() {
	if (is_archive('product')) {
		include THEME_MODULES_PATH . '/archive-product/front/product-filter.php';
	}
}
add_action('extra_after_main_menu', 'archive_product_header_menu_second');
add_action('extra_after_mobile_menu', 'archive_product_header_menu_second');


//remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
//remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

//add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
//add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
//add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
//add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );


function extra_product_pre_get_posts() {
	if (is_archive() && is_main_query() && (is_tax('extra_product_material') || is_tax('extra_product_type') || is_tax('extra_product_collection'))) {
		global $extra_product_material_filter, $extra_product_type_filter, $extra_product_collection_filter;
		if (!isset($extra_product_material_filter)) {
			$extra_product_material_filter = get_query_var('extra_product_material');
			$extra_product_type_filter = get_query_var('extra_product_type');
			$extra_product_collection_filter = get_query_var('extra_product_collection');

			set_query_var('extra_product_material', null);
			set_query_var('extra_product_type', null);
			set_query_var('extra_product_collection', null);
		}
	}
}

add_action( 'pre_get_posts', 'extra_product_pre_get_posts' );
