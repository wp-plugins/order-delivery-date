jQuery(document).ready(function(){
	jQuery(".hasDatepick").live("click",function(){
		disable_days();
		available_dates();
		disable_unavailable_dates();
	});
	jQuery(".datepick-cmd-next").live("click",function(){
		disable_days();
		available_dates();
		disable_unavailable_dates();
	});
	jQuery(".datepick-cmd-prev").live("click",function(){
		disable_days();
		available_dates();
		disable_unavailable_dates();
	});
	jQuery(".datepick-month-year").live("change",function(){
		disable_days();
		available_dates();
		disable_unavailable_dates();
	});
});
					
/*************** disables the dates that are unchecked for delivery ************/					
function disable_days(){
	jQuery(".datepick-month table tbody tr td a").each(function(index) {

		var isFound = $(this).attr("title").search(/Wednesday/i)
		var date = jQuery(this).html();
		if((isFound != -1) && (jQuery("#Wednesday").val() != "on"))
			jQuery(this).parent().html("<span class=dp1341385200000 day=Wednesday>"+date+"</span>");							

		var isFound = $(this).attr("title").search(/Monday/i)
		var date = jQuery(this).html();
		if((isFound != -1) && (jQuery("#Monday").val() != "on"))
			jQuery(this).parent().html("<span class=dp1341385200000 day=Monday>"+date+"</span>");							

		var isFound = $(this).attr("title").search(/Tuesday/i)
		var date = jQuery(this).html();
		if((isFound != -1) && (jQuery("#Tuesday").val() != "on"))
			jQuery(this).parent().html("<span class=dp1341385200000 day=Tuesday>"+date+"</span>");							

		var isFound = $(this).attr("title").search(/Thursday/i)
		var date = jQuery(this).html();
		if((isFound != -1) && (jQuery("#Thursday").val() != "on"))
		jQuery(this).parent().html("<span class=dp1341385200000 day=Thursday>"+date+"</span>");							

		var isFound = $(this).attr("title").search(/Friday/i)
		var date = jQuery(this).html();
		if((isFound != -1) && (jQuery("#Friday").val() != "on"))
		jQuery(this).parent().html("<span class=dp1341385200000 day=Friday>"+date+"</span>");							

		var isFound = $(this).attr("title").search(/Saturday/i)
		var date = jQuery(this).html();
		if((isFound != -1) && (jQuery("#Saturday").val() != "on"))
			jQuery(this).parent().html("<span class=dp1341385200000 day=Saturday>"+date+"</span>");							

		var isFound = $(this).attr("title").search(/Sunday/i)
		var date = jQuery(this).html();
		if((isFound != -1) && (jQuery("#Sunday").val() != "on"))
			jQuery(this).parent().html("<span class=dp1341385200000 day=Sunday>"+date+"</span>");							

	});						
}
//alert(new Date());
// var date = new Date();
//alert(date.setDate(date.getDate() + 7));

function addDays(dateObj, numDays) {
  return dateObj.setDate(dateObj.getDate() + numDays);
}

function getDateDiff(date1, date2, interval) {
	var second = 1000,
	minute = second * 60,
	hour = minute * 60,
	day = hour * 24,
	week = day * 7;
	date1 = new Date(date1).getTime();
	date2 = (date2 == 'now') ? new Date().getTime() : new Date(date2).getTime();
	var timediff = date2 - date1;
	if (isNaN(timediff)) return NaN;
		switch (interval) {
		case "years":
			return date2.getFullYear() - date1.getFullYear();
		case "months":
			return ((date2.getFullYear() * 12 + date2.getMonth()) - (date1.getFullYear() * 12 + date1.getMonth()));
		case "weeks":
			return Math.floor(timediff / week);
		case "days":
			return Math.floor(timediff / day);
		case "hours":
			return Math.floor(timediff / hour);
		case "minutes":
			return Math.floor(timediff / minute);
		case "seconds":
			return Math.floor(timediff / second);
		default:
			return undefined;
	}
}

function available_dates(){
	var delay_days = parseInt(jQuery("#order-days").val());
	var noOfDaysToFind = parseInt(jQuery("#availableDays").val())
	//alert(delay_days+" : "+noOfDaysToFind);
	if(isNaN(delay_days)){
		delay_days = 0;
	}
	if(isNaN(noOfDaysToFind)){
		noOfDaysToFind = 1000;
	}
	//alert(delay_days+" :: "+noOfDaysToFind);
	var date = new Date();
	var t_year = date.getFullYear();
	var t_month = date.getMonth()+1;
	var t_day = date.getDate();
	var t_month_days = daysInMonth(t_month, t_year);
	
	var monthNames = [ "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December" ];
	
	var s_day = new Date( addDays( date , delay_days ) );
	//console.log("start date: \n Date: " + s_day.getDate() + " Month: " + s_day.getMonth() + " Year: " + s_day.getFullYear() );
	//alert(s_day);
	// start_date = s_day.getDate();
	// start_month = s_day.getMonth();
	// start_year = s_day.getFullYear();
	start = (s_day.getMonth()+1) + "/" + s_day.getDate() + "/" + s_day.getFullYear();
	
	var end_date = new Date( addDays( s_day , noOfDaysToFind ) );
	//console.log("\nEnd date: \n Date: " + end_date.getDate() + " Month: " + end_date.getMonth() + " Year: " + end_date.getFullYear() );
	// end_date = end_date.getDate();
	// end_month = end_date.getMonth();
	// end_year = end_date.getFullYear();
	end = (end_date.getMonth()+1) + "/" + end_date.getDate() + "/" + end_date.getFullYear();
	
	//alert(start + "   " + end);
	var loopCounter = getDateDiff(start , end , 'days');
	var prev = s_day;
	for(var i=1; i<=loopCounter; i++){
		console.log(loopCounter);
		var l_start = new Date(start);
		var l_end = new Date(end);
		//console.log(new Date(start));
		//console.log(new Date(end));
		//console.log("Incriment: " + i);
		var new_date = new Date(addDays(l_start,i));
		day = "";
		if(new_date.getDay() == 0)
			day = "Sunday";
		if(new_date.getDay() == 1)
			day = "Monday";
		if(new_date.getDay() == 2)
			day = "Tuesday";
		if(new_date.getDay() == 3)
			day = "Wednesday";
		if(new_date.getDay() == 4)
			day = "Thursday";
		if(new_date.getDay() == 5)
			day = "Friday";
		if(new_date.getDay() == 6)
			day = "Saturday";
		day_check = jQuery("#"+day).val();
		//console.log(day_check);
		if(day_check != "on"){
			l_end = new Date(addDays(l_end,1));
			//console.log("New l_end: " + l_end);
			end = (l_end.getMonth()+1) + "/" + l_end.getDate() + "/" + l_end.getFullYear();
			loopCounter = getDateDiff(start , end , 'days');
			console.log(day + " : " + jQuery("#"+day).val() + " : " + loopCounter);
		}
		//console.log("Date: " + new_date.getDate() + " Month: " + (new_date.getMonth()+1) + " Year: " + new_date.getFullYear() + "Day: " + new_date.getDay());
		jQuery('.datepick-month table tbody tr td a[date="'+new_date.getDate()+'"][month="'+(new_date.getMonth()+1)+'"]').attr("available","available");
		//jQuery('.datepick-month table tbody tr td a[date="'+new_date.getDate()+'"][month="'+(new_date.getMonth()+1)+'"]').css({background:"red"});
		
		//alert(new Date(start_date + "/" + start_month + "/" + start_year));
		//alert(new Date(start));
		//var next_day = new Date( addDays( s_day , i ) );
		//alert(next_day);
		//s_day = prev;
	}	
/*	//var start_date = "";
	
	var noOfDays = parseInt(jQuery("#availableDays").val());
	
	var d = new Date();
	var current_year = d.getFullYear();
	var current_day = d.getDate()+parseInt(jQuery("#order-days").val())+1;
	var current_month = d.getMonth() + 1;
	var total_month_days = 	daysInMonth(current_month, current_year);	
	var next_day = current_day++;	
	
	var inc = false;
	
	for (var i=1; i<noOfDays; i++){
		//next_day;		
		var day = jQuery('.datepick-month table tbody tr td a[date="'+next_day+'"]').attr("day");
		var day_check = jQuery("#"+day).val();
		if(day_check != "on")
			noOfDays++;
		
		next_month = false;
		
		if(next_day>total_month_days && next_month==false){
			next_day = next_day - total_month_days;
			current_month++;
			total_month_days = 	daysInMonth(current_month, current_year);
		}else{
			 next_month = true;
			}

		//jQuery('.datepick-month table tbody tr td a[date="'+next_day+'"][month="'+current_month+'"]').css({background:'red'});
		jQuery('.datepick-month table tbody tr td a[date="'+next_day+'"][month="'+current_month+'"]').attr("available","available");
		next_day++;
  }*/
}
function disable_unavailable_dates(){
//alert("dsasdasadasdads");
	jQuery('.datepick-month table tbody tr td a').each(function(){
		var available = jQuery(this).attr("available");
		var day_a = jQuery(this).attr("day");
		var date_a = jQuery(this).attr("date");
		if(available == undefined){
			jQuery(this).parent().html("<span class=dp1341385200000 day="+day_a+">"+date_a+"</span>");
			//alert(jQuery(this).html());
		//if(available == undefined)
		//alert(available);
	}
	});
}
//Function to get the total days in a given month and year.
function daysInMonth(month, year) {
    return new Date(year, month, 0).getDate();
}

