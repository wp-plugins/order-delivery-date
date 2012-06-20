<?php 
/*
Plugin Name: Order Delivery Date for WP e-Commerce
Plugin URI: http://www.tychesoftwares.com/store/free-plugin/order-delivery-date-on-checkout/
Description: This plugin allows customers to choose their preferred Order Delivery Date during checkout.
Author: Ashok Rane
Version: 1.0
Author URI: http://www.tychesoftwares.com/about
Contributor: Tyche Softwares, http://www.tychesoftwares.com/
*/
$wpefield_version = '1.0';
function wpefield_delivery_date()
{
	global $wpdb;
	$wpefield__TABLE = $wpdb->prefix . 'wpsc_checkout_forms';
	$field_id = '';
	$query = "select * from $wpefield__TABLE where unique_name = 'e_deliverydate'";
	$results = $wpdb->get_row( $query );
	if($results != null)
	{
		if($results->active=='1')
		{
			$field_id = $results->id;
		}
	}
	else
	{
		$max_count = $wpdb->get_var( $wpdb->prepare( "SELECT max(checkout_order)+1 FROM $wpefield__TABLE;" ) );
		$query = "INSERT INTO $wpefield__TABLE (`id`, `name`, `type`, `mandatory`, `display_log`, `default`, `active`, `checkout_order`, `unique_name`, `options`, `checkout_set`) VALUES
('', 'Delivery Date', 'text', '0', '0', '0', '1', $max_count, 'e_deliverydate', '', '0');";
		$wpdb->query($query);
		$field_id = $wpdb->insert_id;
	}
	
	if($field_id != '')
	{
		$display = '<link rel="stylesheet" type="text/css" href="' . plugins_url() . '/order_delivery_date/datepicker.css">
		<script type="text/javascript" src="' . plugins_url() . '/order_delivery_date/datepicker.js"></script>
		<style>
		.wpsc_checkout_form_'.$field_id.'
		{
			padding-top:20px!important;
		}
		#wpsc_checkout_form_'.$field_id.'
		{
			margin-top:20px!important;
		}
		</style>
		<script type="text/javascript">
        jQuery(function() {
			var formats = ["MM d, yyyy","MM d, yyyy"];
			jQuery("#wpsc_checkout_form_'.$field_id.'").width("150px");
			jQuery("#wpsc_checkout_form_'.$field_id.'").val("").datepick({dateFormat: formats[1], minDate:1});
			jQuery("#wpsc_checkout_form_'.$field_id.'").parent().append("<small style=\'font-size:10px;\'>We will try our best to deliver your order on the specified date</small>");
        });
        </script>';
		echo $display;
	}
}
function wpefield_activate()
{
	global $wpdb;
	$wpefield__TABLE = $wpdb->prefix . 'wpsc_checkout_forms';
	$field_id = '';
	$query = "select * from $wpefield__TABLE where unique_name = 'e_deliverydate'";
	$results = $wpdb->get_row( $query );
	if($results != null)
	{
		// do nothing
	}
	else
	{
		$max_count = $wpdb->get_var( $wpdb->prepare( "SELECT max(checkout_order)+1 FROM $wpefield__TABLE;" ) );
		$query = "INSERT INTO $wpefield__TABLE (`id`, `name`, `type`, `mandatory`, `display_log`, `default`, `active`, `checkout_order`, `unique_name`, `options`, `checkout_set`) VALUES
('', 'Delivery Date', 'text', '0', '0', '0', '1', $max_count, 'e_deliverydate', '', '0');";
		$wpdb->query($query);
	}
}
function wpefield_deactivate()
{
	global $wpdb;
	$wpefield__TABLE = $wpdb->prefix . 'wpsc_checkout_forms';
	$query = "delete from $wpefield__TABLE where unique_name = 'e_deliverydate'";
	$wpdb->query( $query );
}
add_action('wpsc_before_form_of_shopping_cart', 'wpefield_delivery_date');
register_activation_hook( __FILE__, 'wpefield_activate' );
register_deactivation_hook( __FILE__, 'wpefield_deactivate' );
?>