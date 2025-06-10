<?php

/***********************
 *
 *
 * ADD ADMIN VIEWS
 *
 *
 **********************/
function extra_woocommerce_admin_reports ($reports) {
	$orders = $reports['orders'];
	$orders_reports = $orders['reports'];

	if (isset($orders_reports['sales_by_category'])) {
		unset($orders_reports['sales_by_category']);
	}

	$orders_reports['sales_by_product_template'] = array(
		'title'       => __( 'Ventes par modèle', 'extra' ),
		'description' => '',
		'hide_title'  => true,
		'callback'    => array( 'WC_Admin_Reports', 'get_report' )
	);
	$orders_reports['sales_by_collection'] = array(
		'title'       => __( 'Ventes par collection', 'extra' ),
		'description' => '',
		'hide_title'  => true,
		'callback'    => array( 'WC_Admin_Reports', 'get_report' )
	);
	$orders_reports['sales_by_material'] = array(
		'title'       => __( 'Ventes par matériaux', 'extra' ),
		'description' => '',
		'hide_title'  => true,
		'callback'    => array( 'WC_Admin_Reports', 'get_report' )
	);
	$orders_reports['sales_by_template_type'] = array(
		'title'       => __( 'Ventes par type de produit', 'extra' ),
		'description' => '',
		'hide_title'  => true,
		'callback'    => array( 'WC_Admin_Reports', 'get_report' )
	);

	$orders['reports'] = $orders_reports;
	$reports['orders'] = $orders;

	return $reports;
}
add_filter('woocommerce_admin_reports', 'extra_woocommerce_admin_reports');

function extra_wc_admin_reports_path ($path, $name, $class) {
	switch ($name) {
		case 'sales-by-product-template' :
			$path = THEME_MODULES_PATH . '/reports/admin/class-wc-report-sales-by-product-template.php';
			break;
		case 'sales-by-collection' :
			$path = THEME_MODULES_PATH . '/reports/admin/class-wc-report-sales-by-collection.php';
			break;
		case 'sales-by-material' :
			$path = THEME_MODULES_PATH . '/reports/admin/class-wc-report-sales-by-material.php';
			break;
		case 'sales-by-template-type' :
			$path = THEME_MODULES_PATH . '/reports/admin/class-wc-report-sales-by-template-type.php';
			break;
		default:
			break;
	}
	return $path;
}
add_filter('wc_admin_reports_path', 'extra_wc_admin_reports_path', 10, 3);


/***********************
 *
 *
 * AJAX SEARCH PRODUCT TEMPLATE
 *
 *
 **********************/
function extra_json_search_products_template() {
	WC_AJAX::json_search_products( '', array( 'product_template' ) );
}
add_action ('wp_ajax_extra_json_search_products_template', 'extra_json_search_products_template');



/***********************
 *
 *
 * ADD PRODUCT TEMPLATE ID WHEN SAVE NEW ORDER
 *
 *
 **********************/
function extra_woocommerce_add_order_item_meta ($item_id, $values, $cart_item_key) {
	if (isset ($values['product_id'])) {
		$product = wc_get_product($values['product_id']);
		$product_template = extra_get_product_template($product->post);
		if ($product_template) {
			wc_add_order_item_meta( $item_id, '_product_template_id', $product_template->ID );
		}
	}
}
add_action ('woocommerce_add_order_item_meta', 'extra_woocommerce_add_order_item_meta', 1, 3);



/***********************
 *
 *
 * RESTRICT ORDER REPORT ON COMPLETED STATS
 * // TODO Add status selector on ui and Check GET property
 *
 *
 **********************/
function extra_woocommerce_reports_get_order_report_data_args ($args) {
	if (isset($_GET['report']) && $_GET['report'] == 'sales_by_product_template') {
		// TODO OVERRIDE WITH TRANSIENT VALUE
	}
//	'order_status'        => array( 'completed', 'processing', 'on-hold' ),
//	$args['order_status'] = array('completed');

	return $args;
}
add_filter('woocommerce_reports_get_order_report_data_args', 'extra_woocommerce_reports_get_order_report_data_args');