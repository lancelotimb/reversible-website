<?php
/**********************
 *
 *
 * WOOCOMMERCE REMOVE STYLES, SCRIPTS, BREADCRUMB, SIDEBAR AND CONTENT WRAPPER
 *
 *
 *********************/
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
remove_action('woocommerce_output_content_wrapper', 'woocommerce_output_content_wrapper', 10);


/**********************
 *
 *
 * WOOCOMMERCE REMOVE DEFAULT WOOCOMMERCE TAXONOMIES
 *
 *
 *********************/
function extra_remove_woocommerce_taxonomies ($post_types) {
	return array();
}
add_filter('woocommerce_taxonomy_objects_product_cat', 'extra_remove_woocommerce_taxonomies');
add_filter('woocommerce_taxonomy_objects_product_tag', 'extra_remove_woocommerce_taxonomies');

function extra_hide_woocommerce_taxonomies ($args) {
	$args['show_ui']= false;
	$args['show_admin_column']= false;
	$args['show_in_nav_menus']= false;
	$args['show_tagcloud']= false;
	$args['public']= false;
	return $args;
}
add_filter('woocommerce_taxonomy_args_product_cat', 'extra_hide_woocommerce_taxonomies');
add_filter('woocommerce_taxonomy_args_product_tag', 'extra_hide_woocommerce_taxonomies');


///**********************
// *
// *
// * SHOP MANAGER IS NOW AN EDITOR
// *
// *
// *********************/
//add_filter('extra_editor_roles', function(){
//	return array(get_role( 'editor' ), get_role( 'shop_manager' ));
//});



/**********************
 *
 *
 * CLEAN COUPON CODE SINGLE QUOTES
 *
 *
 *********************/
function extra_woocommerce_coupon_code($code) {
	$code = str_replace("\\'", '', $code);
	$code = str_replace("'", '', $code);
	$code = str_replace("’", '', $code);

	return $code;
}
add_filter('woocommerce_coupon_code', 'extra_woocommerce_coupon_code');