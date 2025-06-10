<?php


function extra_get_product_template($post = null) {
	if ($post === null) {
		$post = get_queried_object();
	}

	$product_template = null;
	$connected = get_posts(array(
		'connected_type' => 'products_to_product_template',
		'connected_items' => $post,
		'nopaging' => true,
		'suppress_filters' => false
	));
	if ($connected && !empty($connected)) {
		$product_template = $connected[0];
	}

	return $product_template;
}

/**********************
 *
 *
 * PRODUCT PAGE TITLE
 *
 *
 *********************/
//add_filter( 'wp_title_parts', 'extra_single_product_page_title', 99, 1 );
//function extra_single_product_page_title ($title) {
//	if (is_singular('product')) {
//		var_dump($title);
//	}
//
//	return $title;
//}

/**********************
 *
 *
 * REDEFINE PRODUCT POST TYPE
 *
 *
 *********************/
function extra_register_post_type_product ($params) {
	// 'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments', 'custom-fields', 'page-attributes' ),
	$params['supports'] = array( 'title', 'editor', 'thumbnail');

	return $params;
}

add_filter('woocommerce_register_post_type_product', 'extra_register_post_type_product', 20);

///**********************
// *
// *
// * SET TITLE AND NAME WHEN SAVE POST
// *
// *
// *********************/
//function extra_save_product ($post_id, $post, $update) {
//	if ($post->post_type == 'product') {
//
//		$post_title = __("Produit", 'extra');
//		$post_name_prefixe = __("produit", 'extra');
//		$post_name_suffix = 'id-'.$post_id;
//
//		$connected = get_posts(array(
//			'connected_type' => 'products_to_product_template',
//			'connected_items' => $post,
//			'nopaging' => true,
//			'suppress_filters' => false
//		));
//		if (!empty($connected)) {
//			$product_template = $connected[0];
//			$post_title = $product_template->post_title;
//			$post_name_prefixe = $product_template->post_name;
//		}
//
//		$sku = get_post_meta($post_id, '_sku');
//		if ($sku !== null && !empty($sku) && reset($sku) != '') {
//			$post_name_suffix = reset($sku);
//		}
//
//		// unhook this function so it doesn't loop infinitely
//		remove_action( 'save_post', 'extra_save_product', 10, 3 );
//
//		// update the post, which calls save_post again
//		wp_update_post(array(
//			'ID' => $post_id,
//			'post_title' => $post_title,
//			'post_name' => $post_name_prefixe.'-'.$post_name_suffix,
//		));
//
//		// re-hook this function
//		add_action( 'save_post', 'extra_save_product', 10, 3 );
//	}
//}
//add_action( 'save_post', 'extra_save_product', 10, 3 );

///**********************
// *
// *
// * METABOX - ADMIN TITLE PRODUCT
// *
// *
// *********************/
//global $product_metabox;
//$product_metabox = new ExtraMetaBox(array(
//	'id' => '_product',
//	'lock' => WPALCHEMY_LOCK_BEFORE_POST_TITLE,
//	'title' => __("Paramètre du produit", "extra"),
//	'types' => array('product'),
//	'hide_ui' => TRUE,
//	'fields' => array(
//		array(
//			'type' => 'custom',
//			'template' => THEME_MODULES_PATH . '/single-product/admin/edit-title.php'
//		)
//	)
//));

//$args = array( 'post_type' => 'product', 'posts_per_page' => $number_of_products, 'meta_query' => array( array('key' => '_featured', '_visibility','value' => array('catalog', 'yes', 'visible'),'compare' => 'IN')) );

/**********************
 *
 *
 * PRODUCT COLLECTION TAXONOMY
 *
 *
 *********************/
// Register Custom Taxonomy
function register_product_collection_taxonomy() {
	$labels = array(
		'name'                       => _x( 'Collections', 'Taxonomy General Name', 'extra' ),
		'singular_name'              => _x( 'Collection de produit', 'Taxonomy Singular Name', 'extra' ),
		'menu_name'                  => __( 'Collections', 'extra' ),
		'all_items'                  => __( 'Toutes les collections', 'extra' ),
		'parent_item'                => __( 'Collection parente', 'extra' ),
		'parent_item_colon'          => __( 'Collection parente :', 'extra' ),
		'new_item_name'              => __( 'Nouvelle collection', 'extra' ),
		'add_new_item'               => __( 'Ajouter une collection', 'extra' ),
		'edit_item'                  => __( 'Modifier la collection', 'extra' ),
		'update_item'                => __( 'Mettre à jour la collection', 'extra' ),
		'separate_items_with_commas' => __( 'Noms séparés par des virgules', 'extra' ),
		'search_items'               => __( 'Chercher une collection', 'extra' ),
		'add_or_remove_items'        => __( 'Ajouter ou retirer une collection', 'extra' ),
		'choose_from_most_used'      => __( 'Choisir parmi les plus utilisées', 'extra' ),
		'not_found'                  => __( 'Aucune trouvée', 'extra' ),
	);
	$rewrite = array(
		'slug'                       => 'boutique/collection',
		'with_front'                 => true,
		'hierarchical'               => false,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
	);
	register_taxonomy( 'extra_product_collection', array( 'product' ), $args );

}
// Hook into the 'init' action
add_action( 'init', 'register_product_collection_taxonomy', 0 );

/**********************
 *
 *
 * ADMIN LIST FILTER // BECAUSE WOOCOMMERCE BREAK INITIAL FILTER
 *
 *
 *********************/
add_filter( 'parse_query', function ($query) {
	global $typenow, $wp_query;
	if ( 'product' == $typenow ) {
		if ( isset( $_GET['extra_product_collection'] ) ) {
			$query->query_vars['tax_query'][] = array(
				'taxonomy' => 'extra_product_collection',
				'field'    => 'slug',
				'terms'    => $_GET['extra_product_collection'],
			);
		}
	}

	return $query;
});

add_filter( 'manage_edit-product_columns', 'extra_product_stock_column',15 );
function extra_product_stock_column($columns){
	//add column
	$columns['stock'] = __( 'Stock');

	return $columns;
}
add_action( 'manage_product_posts_custom_column', 'extra_product_stock_row', 10, 2 );

function extra_product_stock_row( $column, $postid ) {
	if ( $column == 'stock' ) {
		$status = get_post_meta( $postid, '_stock_status', true);
		switch($status) {
			case 'instock' :
				_e("Disponible", 'extra-admin');
				break;
			case 'outofstock' :
				_e("Épuisé", 'extra-admin');
				break;
			default :
				echo '-';
				break;
		}
	}
}

/**********************
 *
 *
 * ENQUEUE ASSETS
 *
 *
 *********************/
function extra_single_product_enqueue_assets() {
	if (is_singular('product')) {
		wp_enqueue_style('extra-single-product', THEME_MODULES_URI.'/single-product/front/css/single-product.less');

//		wp_enqueue_script('easyzoom', THEME_URI.'/assets/js/lib/easyzoom.js', array('jquery'), false, true);
		wp_enqueue_script('jquery.fracs.js', THEME_URI.'/assets/js/lib/jquery.fracs.js', array('jquery'), false, true);

		wp_enqueue_script('extra-product-zoom', THEME_MODULES_URI.'/single-product/front/js/single-product-zoom.js', false, true);
		wp_enqueue_script('extra-single-product', THEME_MODULES_URI.'/single-product/front/js/single-product.js', array('extra', 'extra-common', 'jquery.fracs.js', 'jquery', 'extra-archive-product', 'extra-product-zoom'), false, true);
	}
}
add_action('wp_enqueue_scripts', 'extra_single_product_enqueue_assets');

/**********************
 *
 *
 * ADMIN CSS
 *
 *
 *********************/
function single_product_admin_style() {
	wp_enqueue_style( 'extra-single-product-admin', THEME_MODULES_URI . '/single-product/admin/css/single-product.less' );
}
add_action('admin_print_styles', 'single_product_admin_style');


/**********************
 *
 *
 * POSTS 2 POSTS
 *
 *
 *********************/
function many_products_one_product_template() {
	p2p_register_connection_type( array(
		'name' => 'products_to_product_template',
		'from' => 'product',
		'from_query_vars' => array('post_status' => 'any'),
		'to' => 'product_template',
		'cardinality' => 'many-to-one',
		'title' => __("Modèle", 'extra'),
		'to_labels' => array(
			'singular_name' => __( 'Modèle', 'extra' ),
			'search_items' => __( 'Chercher un modèle', 'my-textdomain' ),
			'not_found' => __( 'Aucun modèle trouvé', 'my-textdomain' ),
			'create' => __( 'Choisir le modèle', 'my-textdomain' ),
		),
		'admin_box' => array(
			'show' => 'from'
		),
		'admin_column' => 'from'
	) );
}
add_action( 'p2p_init', 'many_products_one_product_template' );

/**********************
 *
 *
 * BULK CREATOR FOR PRODUCT - WOOCOMMERCE
 *
 *
 *********************/
require_once THEME_MODULES_PATH . '/single-product/admin/add-multiple.php';


/**********************
 *
 *
 * PRODUCT SOLD INDIVIDUALLY BY DEFAULT
 *
 *
 *********************/
//function extra_product_default_no_quantities( $individually, $product ){
//	$individually = true;
//	return $individually;
//}
//add_filter( 'woocommerce_is_sold_individually', 'extra_product_default_no_quantities', 10, 2 );


/**********************
 *
 *
 * REDIRECT SINGLE PRODUCT OUT OF STOCK
 *
 *
 *********************/
function extra_redirect_single_product_outofstock () {
	if (is_singular('product')) {
		global $post;
		if ($post) {
			$current_product = new WC_Product($post);

			$redirect = false;

			if (!$current_product->is_in_stock()) {
				$redirect = true;
				// EDITOR CAN SEE THE SINGLE PRODUCT OUT OF STOCK
//				if (is_user_logged_in() && current_user_can('edit_post', $post->ID)) {
//					$redirect = false;
//				}
			}

			if ($redirect) {
				// IF NOT IN STOCK REDIRECT TO SHOP
//				wp_redirect(get_permalink(wc_get_page_id( 'shop' )));

				$message = '';
				ob_start();
				get_template_part("extra/modules/single-product/front/already-sold");
				$message = ob_get_contents();
				ob_end_clean();
				extra_add_flash_message($post->ID, $message);
				session_write_close();
//				exit;
			}
		}
	}
}
add_action( 'template_redirect', 'extra_redirect_single_product_outofstock' );

/**********************
 *
 *
 * SALE - FLASH
 *
 *
 *********************/
function extra_sale_flash ($html, $post, $product) {
	$salePercent = 100 - floor(floatval($product->get_sale_price()) * 100 / floatval($product->get_regular_price()));
//	$product->get_regular_price();
//	$product->get_sale_price();

	$html = '<span class="onsale">-' . $salePercent . '%</span>';

	return $html;
}
add_filter('woocommerce_sale_flash', 'extra_sale_flash', 10, 3);
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);


/**********************
 *
 *
 * SEARCH PRODUCT
 *
 *
 *********************/
//function extra_single_product_search () {
//	get_template_part('extra/modules/archive-product/front/product-search');
//}
//add_action('woocommerce_before_single_product_summary', 'extra_single_product_search', 5);

/**********************
 *
 *
 * BACK BUTTON
 *
 *
 *********************/
function extra_single_product_back_button () {
	get_template_part('extra/modules/single-product/front/back-button');
}
add_action('woocommerce_before_single_product_summary', 'extra_single_product_back_button', 5);

/**********************
 *
 *
 * DESCRIPTION - CONTENT, DIMENSIONS && CATEGORIES
 *
 *
 *********************/
function extra_single_product_description () {
	get_template_part('extra/modules/single-product/front/description');
}
add_action('woocommerce_single_product_summary', 'extra_single_product_description', 20);



/**********************
 *
 *
 * SAME PRODUCT TEMPLATE
 *
 *
 *********************/
function extra_same_product_template () {
	get_template_part('extra/modules/single-product/front/same-product-template');
}
add_action('woocommerce_sidebar', 'extra_same_product_template', 1);


/**********************
 *
 *
 * SMALL IMAGES
 *
 *
 *********************/
function extra_small_images () {
	get_template_part('extra/modules/single-product/front/small-images');
}
add_action('woocommerce_after_single_product_summary', 'extra_small_images', 20);


/**********************
 *
 *
 * PRICE
 *
 *
 *********************/
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
add_action('woocommerce_before_add_to_cart_form', 'woocommerce_template_single_price', 10);


/**********************
 *
 *
 * REMOVE UNUSED COMPONENTS
 *
 *
 *********************/
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);

remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

remove_action('woocommerce_before_single_product', 'wc_print_notices', 10);



/**********************
 *
 *
 * OVERRIDE AVAILABILITY
 *
 *
 *********************/
/**
 * @param $availability
 * @param $product WC_Product
 *
 * @return string|void
 */
function extra_get_availability($array, $product) {
	$availability = $class = '';

	if ( $product->managing_stock() ) {

		if ( $product->is_in_stock() && $product->get_total_stock() > get_option( 'woocommerce_notify_no_stock_amount' ) ) {

			switch ( get_option( 'woocommerce_stock_format' ) ) {

				case 'no_amount' :
					$availability = __( 'In stock', 'woocommerce' );
					break;

				case 'low_amount' :
					if ( $product->get_total_stock() <= get_option( 'woocommerce_notify_low_stock_amount' ) ) {
						$availability = sprintf( __( 'Plus que %s restant', 'extra' ), $product->get_total_stock() );

						if ( $product->backorders_allowed() && $product->backorders_require_notification() ) {
							// POINTLESS
//							$availability .= ' ' . __( '(can be backordered)', 'woocommerce' );
						}
					} else {
						$availability = __( 'In stock', 'woocommerce' );
					}
					break;

				default :
					if ( $product->get_total_stock() <= get_option( 'woocommerce_notify_low_stock_amount' ) ) {
						$availability = sprintf( _n( "C'est le dernier !", 'Plus que %s restants', $product->get_total_stock(), 'extra' ), $product->get_total_stock() );
					} else {
						$availability = sprintf( __( '%s restants', 'extra' ), $product->get_total_stock() );
					}

					if ( $product->backorders_allowed() && $product->backorders_require_notification() ) {
						// POINTLESS
//						$availability .= ' ' . __( '(can be backordered)', 'woocommerce' );
					}
					break;
			}

			$class        = 'in-stock';

		} elseif ( $product->backorders_allowed() && $product->backorders_require_notification() ) {

			$availability = __( 'Available on backorder', 'woocommerce' );
			$class        = 'available-on-backorder';

		} elseif ( $product->backorders_allowed() ) {

			$availability = __( 'In stock', 'woocommerce' );
			$class        = 'in-stock';

		} else {

			$availability = __( 'Out of stock', 'woocommerce' );
			$class        = 'out-of-stock';
		}

	} elseif ( ! $product->is_in_stock() ) {

		$availability = __( 'Out of stock', 'woocommerce' );
		$class        = 'out-of-stock';
	}

	return array( 'availability' => $availability, 'class' => $class );
}
add_filter('woocommerce_get_availability', 'extra_get_availability', 10, 2);

/**********************
 *
 *
 * OVERRIDE MAX QUANTITY FOR BACKORDERS ALLOWED PRODUCT
 *
 *
 *********************/
/**
 * @param $availability
 * @param $product WC_Product
 *
 * @return string|void
 */
function extra_woocommerce_quantity_input_max ($max_value, $product) {
	if ($product->backorders_allowed()) {
		$max_value =  10;
	}

	return $max_value;
}
add_filter('woocommerce_quantity_input_max', 'extra_woocommerce_quantity_input_max', 10, 2);