<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 25/05/15
 * Time: 18:39
 */

/************************
 *
 *
 * ADD COLUMNS FOR EXPORT
 *
 *
 ***********************/
function extra_wpg_order_columns ($tabs) {
	$tabs['wc_settings_tab_amount'] = __( 'Montant TTC', 'extra' );
	$tabs['wc_settings_tab_shipping'] = __( 'Frais de livraison TTC', 'extra' );
	$tabs['wc_settings_tab_vat'] = __( 'Total Taxes', 'extra' );

	return $tabs;
}
add_filter ('wpg_order_columns', 'extra_wpg_order_columns');

/************************
 *
 *
 * ADD FIELDS FOR EXPORTS
 *
 *
 ***********************/
function extra_wc_settings_tab_order_export ($settings) {
	$settings ['shipping'] = array(
		'name' => __( 'Frais de livraison TTC', 'extra' ),
		'type' => 'checkbox',
		'desc' => __( 'Frais de livraison payés par le client', 'extra' ),
		'id'   => 'wc_settings_tab_shipping'
	);
	$settings ['vat'] = array(
		'name' => __( 'Total taxes', 'extra' ),
		'type' => 'checkbox',
		'desc' => __( 'Montant total des taxes payé par le client', 'extra' ),
		'id'   => 'wc_settings_tab_vat'
	);


	return $settings;
}
add_filter ('wc_settings_tab_order_export', 'extra_wc_settings_tab_order_export');


/************************
 *
 *
 * ADD COLUMNS VALUES TO CVS
 *
 *
 ***********************/
/**
 * @param $csv_values
 * @param $order_details WC_Order
 * @param $fields
 */
function extra_wpg_before_csv_write (&$csv_values, $order_details, $fields) {
	if( !empty( $fields['wc_settings_tab_shipping'] ) && $fields['wc_settings_tab_shipping'] === true )
		array_push( $csv_values, $order_details->get_total_shipping() + $order_details->get_shipping_tax() );

	if( !empty( $fields['wc_settings_tab_vat'] ) && $fields['wc_settings_tab_vat'] === true )
		array_push( $csv_values, $order_details->get_total_tax() );
}
add_action ('wpg_before_csv_write', 'extra_wpg_before_csv_write', 10, 3);

// do_action_ref_array( 'wpg_before_csv_write', array( &$csv_values, $order_details, $fields ) );


/************************
 *
 *
 * HIDE PRODUCT TEMPLATE FROM PRODUCT DETAIL
 *
 *
 ***********************/
function extra_woocommerce_hidden_order_itemmeta ($exclude_meta) {
	$exclude_meta[] = '_product_template_id';
	return $exclude_meta;
}
add_filter ('woocommerce_hidden_order_itemmeta', 'extra_woocommerce_hidden_order_itemmeta');