<?php 
/*
Plugin Name: Order Delivery Date for WP e-Commerce
Plugin URI: http://www.tychesoftwares.com/store/free-plugin/order-delivery-date-on-checkout/
Description: This plugin allows customers to choose their preferred Order Delivery Date during checkout.
Author: Ashok Rane
Version: 1.1
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
		$display = '<link rel="stylesheet" type="text/css" href="' . plugins_url() . '/order-delivery-date/datepicker.css">
		<script type="text/javascript" src="' . plugins_url() . '/order-delivery-date/datepicker.js"></script>
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

//////////////////////////////////////////////////////////////////////////////


//adds the files in the head of the admin settings form page
add_action('admin_head', 'order_delivery_date_adminside_head');
function order_delivery_date_adminside_head() {
	
	$plugin_path = plugins_url();
	
	//print($plugin_path);
	print '<link rel="stylesheet" href="'.$plugin_path.'/order-delivery-date/css/order-delivery-date.css" type="text/css" media="screen" /> ';
	//print('<script type="text/javascript" src="'.$plugin_path.'/order-delivery-date/js/calender-dates-deselect.js"></script>');
	
}  


//frontside scripts
add_action ('wp_enqueue_scripts','order_delivery_date_front_scripts');
function order_delivery_date_front_scripts(){
	$alldays = array();
	$alldays['Monday'] = get_option('Monday');
	$alldays['Tuesday'] = get_option('Tuesday');
	$alldays['Wednesday'] = get_option('Wednesday');
	$alldays['Thursday'] = get_option('Thursday');
	$alldays['Friday'] = get_option('Friday');
	$alldays['Saturday'] = get_option('Saturday');
	$alldays['Sunday'] = get_option('Sunday');
	
	$alldayskeys = array_keys($alldays);
	
	foreach($alldayskeys as $key){
		print('<input type="hidden" id="'.$key.'" value="'.$alldays[$key].'">');
		//print("Key: $key Value: $alldays[$key] <br />");
	}	
	print('<input type="hidden" name="orderDays" id="order-days" value="'.get_option('orderDay').'">');
	print('<input type="hidden" name="availableDays" id="availableDays" value="'.get_option('availableDays').'">');
	
	print('<script type="text/javascript" src="'.plugins_url().'/order-delivery-date/available-dates.js"></script>');
	print('<script type="text/javascript">
				</script>');	
}

//Code to create the settings page for the plugin
add_action('admin_menu', 'order_delivery_date_menu');
function order_delivery_date_menu()
{
	add_menu_page( 'Order Delivery Date','Order Delivery Date','administrator', 'order_delivery_date','order_delivery_date_settings');
}
function order_delivery_date_settings(){

	$check_prev = array();
	
/*	if(empty(get_option('Monday'))){
			$check_prev['monday'] = ""; 
	}*/
	print('<br /><br />
		<div id="order-delivery-date-settings">
			<div class="ino_titlee"><h3><span class="home">Order Delivery Date Settings</span></h3></div>
				<form id="order-delivery-date-settings-form" name="order-delivery-date-settings" method="post">
					
					<div id="order-days">
						<label for="delivery-days-tf">Delivery Days: </label>
						<fieldset class="days-fieldset">
							<legend><b>Days:</b></legend>
								<input type="checkbox" name="Monday" id="Monday" class="day-checkbox" '.get_option('Monday_check').' />
								<label for="Monday">Monday</label>
								<br />
								<input type="checkbox" name="Tuesday" id="Tuesday" class="day-checkbox" '.get_option('Tuesday_check').' />
								<label for="Tuesday">Tuesday</label>
								<br />
								<input type="checkbox" name="Wednesday" id="Wednesday" class="day-checkbox" '.get_option('Wednesday_check').' />
								<label for="Wednesday">Wednesday</label>
								<br />
								<input type="checkbox" name="Thursday" id="Thursday" class="day-checkbox" '.get_option('Thursday_check').' />
								<label for="Thursday">Thursday</label>
								<br />
								<input type="checkbox" name="Friday" id="Friday" class="day-checkbox" '.get_option('Friday_check').' />
								<label for="Friday">Friday</label>
								<br />
								<input type="checkbox" name="Saturday" id="Saturday" class="day-checkbox" '.get_option('Saturday_check').' />
								<label for="Saturday">Saturday</label>
								<br />
								<input type="checkbox" name="Sunday" id="Sunday" class="day-checkbox" '.get_option('Sunday_check').' />
								<label for="Sunday">Sunday</label>
								<br />
						</fieldset>
						<div id="help">Select the week days when the delivery of items takes place. For example, if you deliver only on Tuesday, Wednesday, Thursday & Friday, then select only those days here. The remaining days will not be available for selection to the customer.</div>
					</div>

					<div id="order-delay-days">
						<label for="order-delay-days-tf">Minimum Delivery time (in days): </label>
						<input type="text" name="orderday" id="orderday" value="'.get_option('OrderDay').'"/>
						<div id="help" >Enter the minimum number of days it takes for you to deliver an order. For example, if it takes 2 days atleast to ship an order, enter 2 here. The customer can select a date that is available only after the minimum days that are entered here.<br></div>
					</div>

					<div id="available-days">
						<label for="available-days-tf">Number of dates to choose: </label>
						<input type="text" name="available-days-tf" id="available-days-tf" value="'.get_option('availableDays').'"/>
						<div id="help">Based on the above 2 settings, you can decide how many dates should be made available to the customer to choose from. For example, if you enter 10, then 10 different dates will be made available to the customer to choose.</div>
					</div>
					
					<div class="submit_button"><span class="submit"><input type="submit" value="Save changes" name="save"/></span></div>
				</form>
			</div>');
}
if($_POST['save']){
	update_option('Monday',$_POST['Monday']);	
	update_option('Tuesday',$_POST['Tuesday']);	
	update_option('Wednesday',$_POST['Wednesday']);	
	update_option('Thursday',$_POST['Thursday']);	
	update_option('Friday',$_POST['Friday']);	
	update_option('Saturday',$_POST['Saturday']);	
	update_option('Sunday',$_POST['Sunday']);	
	update_option('orderDay',$_POST['orderday']);
	update_option('availableDays',$_POST['available-days-tf']);
	//checked property
	if($_POST['Monday'] == "on")	
		update_option('Monday_check','checked="checked"');	
	else
		update_option('Monday_check','');	
		
	if($_POST['Tuesday'] == "on")	
		update_option('Tuesday_check','checked="checked"');	
	else
		update_option('Tuesday_check','');	

	if($_POST['Wednesday'] == "on")	
		update_option('Wednesday_check','checked="checked"');	
	else
		update_option('Wednesday_check','');	

	if($_POST['Thursday'] == "on")	
		update_option('Thursday_check','checked="checked"');	
	else
		update_option('Thursday_check','');	
	
	if($_POST['Friday'] == "on")	
		update_option('Friday_check','checked="checked"');	
	else
		update_option('Friday_check','');	

	if($_POST['Saturday'] == "on")	
		update_option('Saturday_check','checked="checked"');	
	else
		update_option('Saturday_check','');	

	if($_POST['Sunday'] == "on")	
		update_option('Sunday_check','checked="checked"');	
	else
		update_option('Sunday_check','');	
}
?>
