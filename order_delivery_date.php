<?php
/*
Plugin Name: Order Delivery Date for WP e-Commerce
Plugin URI: http://www.tychesoftwares.com/store/free-plugin/order-delivery-date-on-checkout/
Description: This plugin allows customers to choose their preferred Order Delivery Date during checkout.
Author: Ashok Rane
Version: 1.2
Author URI: http://www.tychesoftwares.com/about
Contributor: Tyche Softwares, http://www.tychesoftwares.com/
*/
$wpefield_version = '1.2';
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
		
	    wp_enqueue_script( 'jquery' );
	    
	    //if(get_option("first_install") != "TRUE")
	    {
		    wp_enqueue_script( 'jquery-ui-datepicker' );
		    
		    wp_enqueue_style( 'jquery-ui', "http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/smoothness/jquery-ui.css" , '', '', false);
		    
		    wp_enqueue_script(
				'initialize-datepicker.js',
				plugins_url('/js/initialize-datepicker.js', __FILE__),
				'',
				'',
				false
			);
	    }
	    //http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/ui-lightness/jquery-ui.css
	    
	
		/*if(get_option("first_install") != "TRUE"){
			print('<script type="text/javascript" src="'.plugins_url().'/order-delivery-date/available-dates.js"></script>');
		}
		$display = '<link rel="stylesheet" type="text/css" href="' . plugins_url() . '/order-delivery-date/datepicker.css">
		<script type="text/javascript" src="' . plugins_url() . '/order-delivery-date/datepicker.js"></script>';*/
		
		$display = '
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
			var formats = ["MM d, yy","MM d, yy"];
			jQuery("#wpsc_checkout_form_'.$field_id.'").width("250px");

			jQuery("#wpsc_checkout_form_'.$field_id.'").val("").datepicker({dateFormat: formats[1], beforeShow: avd, beforeShowDay: chd});
			jQuery("#wpsc_checkout_form_'.$field_id.'").parent().append("<small style=\'font-size:10px;float:left;\'>We will try our best to deliver your order on the specified date</small>");
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
		//update_option("first_install","TRUE");
	}
	
	update_option('Monday_check','checked');
	update_option('Tuesday_check','checked');
	update_option('Wednesday_check','checked');
	update_option('Thursday_check','checked');
	update_option('Friday_check','checked');
	update_option('Saturday_check','');
	update_option('Sunday_check','');
	update_option('Monday','on');
	update_option('Tuesday','on');
	update_option('Wednesday','on');
	update_option('Thursday','on');
	update_option('Friday','on');
	update_option('Saturday','');
	update_option('Sunday','');
	update_option('orderDay','0');
	update_option('availableDays','30');
}
function wpefield_deactivate()
{
	global $wpdb;
	$wpefield__TABLE = $wpdb->prefix . 'wpsc_checkout_forms';
	$query = "delete from $wpefield__TABLE where unique_name = 'e_deliverydate'";
	$wpdb->query( $query );
	
	delete_option('Monday_check');
	delete_option('Tuesday_check');
	delete_option('Wednesday_check');
	delete_option('Thursday_check');
	delete_option('Friday_check');
	delete_option('Saturday_check');
	delete_option('Sunday_check');
	delete_option('Monday');
	delete_option('Tuesday');
	delete_option('Wednesday');
	delete_option('Thursday');
	delete_option('Friday');
	delete_option('Saturday');
	delete_option('Sunday');
	delete_option('orderDay');
	delete_option('availableDays');
	
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
	$checked = "No";
	foreach($alldayskeys as $key)
	{
		if($alldays[$key] == 'on')
		{
			$checked = "Yes";
		}
	}
	/*foreach($alldayskeys as $key){
		print('<input type="hidden" id="'.$key.'" value="'.$alldays[$key].'">');
		//print("Key: $key Value: $alldays[$key] <br />");
	}	*/
	if($checked == 'Yes')
	{
		foreach($alldayskeys as $key)
		{
			print('<input type="hidden" id="'.$key.'" value="'.$alldays[$key].'">');
		}
	}
	else if($checked == 'No')
	{
		foreach($alldayskeys as $key)
		{
			print('<input type="hidden" id="'.$key.'" value="on">');
		}
	}
	print('<input type="hidden" name="orderDays" id="order-days" value="'.get_option('orderDay').'">');
	print('<input type="hidden" name="availableDays" id="availableDays" value="'.get_option('availableDays').'">');
	
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

	print('<br>');
	if(isset($_POST['save']) && $_POST['save'] != "")
	{
		print('<div id="message" class="updated"><p>All changes have been saved.</p></div>');
	}
	print('<br>');

	print('<div id="order-delivery-date-settings">
			<div class="ino_titlee"><h3><span class="home">Order Delivery Date Settings</span></h3></div>
				<form id="order-delivery-date-settings-form" name="order-delivery-date-settings" method="post">
					
					<div id="order-days">
						<label  class="orddd_label" for="delivery-days-tf">Delivery Days: </label>
						<fieldset class="days-fieldset">
							<legend><b>Days:</b></legend>
								<input type="checkbox" name="Monday" id="Monday" class="day-checkbox" '.get_option('Monday_check').' />
								<label  class="orddd_label" for="Monday">Monday</label>
								<br />
								<input type="checkbox" name="Tuesday" id="Tuesday" class="day-checkbox" '.get_option('Tuesday_check').' />
								<label  class="orddd_label" for="Tuesday">Tuesday</label>
								<br />
								<input type="checkbox" name="Wednesday" id="Wednesday" class="day-checkbox" '.get_option('Wednesday_check').' />
								<label  class="orddd_label" for="Wednesday">Wednesday</label>
								<br />
								<input type="checkbox" name="Thursday" id="Thursday" class="day-checkbox" '.get_option('Thursday_check').' />
								<label  class="orddd_label" for="Thursday">Thursday</label>
								<br />
								<input type="checkbox" name="Friday" id="Friday" class="day-checkbox" '.get_option('Friday_check').' />
								<label  class="orddd_label" for="Friday">Friday</label>
								<br />
								<input type="checkbox" name="Saturday" id="Saturday" class="day-checkbox" '.get_option('Saturday_check').' />
								<label  class="orddd_label" for="Saturday">Saturday</label>
								<br />
								<input type="checkbox" name="Sunday" id="Sunday" class="day-checkbox" '.get_option('Sunday_check').' />
								<label  class="orddd_label" for="Sunday">Sunday</label>
								<br />
						</fieldset>
						<div id="help">Select the week days when the delivery of items takes place. For example, if you deliver only on Tuesday, Wednesday, Thursday & Friday, then select only those days here. The remaining days will not be available for selection to the customer.</div>
					</div>

					<div id="order-delay-days">
						<label  class="orddd_label" for="order-delay-days-tf">Minimum Delivery time (in days): </label>
						<input type="text" name="orderday" id="orderday" value="'.get_option('OrderDay').'"/>
						<div id="help" >Enter the minimum number of days it takes for you to deliver an order. For example, if it takes 2 days atleast to ship an order, enter 2 here. The customer can select a date that is available only after the minimum days that are entered here.<br></div>
					</div>

					<div id="available-days">
						<label  class="orddd_label" for="available-days-tf">Number of dates to choose: </label>
						<input type="text" name="available-days-tf" id="available-days-tf" value="'.get_option('availableDays').'"/>
						<div id="help">Based on the above 2 settings, you can decide how many dates should be made available to the customer to choose from. For example, if you enter 10, then 10 different dates will be made available to the customer to choose.</div>
					</div>
					
					<div class="submit_button"><span class="submit"><input type="submit" value="Save changes" name="save"/></span></div>
				</form>
			</div>');
}
if(isset($_POST['save']))
{
	//update_option("first_install","FALSE");
	if(isset($_POST['Monday']))
	{
		update_option('Monday',$_POST['Monday']);	
	}
	else
	{
		update_option('Monday','');
	}
	if(isset($_POST['Tuesday']))
	{
		update_option('Tuesday',$_POST['Tuesday']);
	}
	else
	{
		update_option('Tuesday','');	
	}
	if(isset($_POST['Wednesday']))
	{
		update_option('Wednesday',$_POST['Wednesday']);		
	}
	else
	{
		update_option('Wednesday','');	
	}
	if(isset($_POST['Thursday']))
	{
		update_option('Thursday',$_POST['Thursday']);	
	}
	else
	{
		update_option('Thursday','');	
	}
	if(isset($_POST['Friday']))
	{
		update_option('Friday',$_POST['Friday']);	
	}
	else
	{
		update_option('Friday','');
	}
	if(isset($_POST['Saturday']))
	{
		update_option('Saturday',$_POST['Saturday']);	
	}
	else
	{
		update_option('Saturday','');
	}
	if(isset($_POST['Sunday']))
	{
		update_option('Sunday',$_POST['Sunday']);
	}
	else
	{
		update_option('Sunday','');
	}

	if(isset($_POST['orderday']))
	{
		update_option('orderDay',$_POST['orderday']);
	}
	else
	{
		update_option('orderDay','');
	}
	
	if(isset($_POST['available-days-tf']))
	{
		update_option('availableDays',$_POST['available-days-tf']);
	}
	else
	{
		update_option('availableDays','');
	}
	//checked property
	if(isset($_POST['Monday']) && $_POST['Monday'] == "on")	
		update_option('Monday_check','checked="checked"');	
	else
		update_option('Monday_check','');	
		
	if(isset($_POST['Tuesday']) && $_POST['Tuesday'] == "on")	
		update_option('Tuesday_check','checked="checked"');	
	else
		update_option('Tuesday_check','');	

	if(isset($_POST['Wednesday']) && $_POST['Wednesday'] == "on")	
		update_option('Wednesday_check','checked="checked"');	
	else
		update_option('Wednesday_check','');	

	if(isset($_POST['Thursday']) && $_POST['Thursday'] == "on")	
		update_option('Thursday_check','checked="checked"');	
	else
		update_option('Thursday_check','');	
	
	if(isset($_POST['Friday']) && $_POST['Friday'] == "on")	
		update_option('Friday_check','checked="checked"');	
	else
		update_option('Friday_check','');	

	if(isset($_POST['Saturday']) && $_POST['Saturday'] == "on")	
		update_option('Saturday_check','checked="checked"');	
	else
		update_option('Saturday_check','');	

	if(isset($_POST['Sunday']) && $_POST['Sunday'] == "on")	
		update_option('Sunday_check','checked="checked"');	
	else
		update_option('Sunday_check','');	
}
?>