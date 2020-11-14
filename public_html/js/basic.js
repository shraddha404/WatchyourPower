function validateDate(flag){
//alert(flag);
                var fromDate = document.getElementById("from_date").value
                var toDate= document.getElementById("to_date").value
                //var curr_Date= new SimpleDateFormat("dd/mm/yyyy");
if(fromDate == null && toDate == null)
{
        return true;
}
 else if ((fromDate.length != 0) && (toDate.length != 0)){
               
            var dateRegEx = null;
            //dateRegEx = new RegExp(/^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/g);
	if(flag ==1){
		//alert("Hi");
		var dateRegEx = /^(0[1-9]|1\d|2\d|3[01])\/(0[1-9]|1[0-2])\/(19|20)\d{2}\s[0-2][0-9]:[0-5][0-9]$/
	}else{
		var dateRegEx = /^(0[1-9]|1\d|2\d|3[01])\/(0[1-9]|1[0-2])\/(19|20)\d{2}$/
	
	}
            if (dateRegEx.test(fromDate)){
           }
            else{
                alert("Invalid from date");
                return false;
            }
            if(dateRegEx.test(toDate)) {
            }
            else{
                alert("Invalid to date");
                return false;
            }
      
if(flag==1){
var fdate=fromDate.split(" ");
var tdate=toDate.split(" ");
	var f_date=fdate[0].split("/");
	var f_time=fdate[1].split(":");
	var f_year=f_date[2];
	var f_month=f_date[1]-1;
	var f_day=f_date[0];
	var f_hr=f_time[0];
	var f_min=f_time[1];
	var t_date=tdate[0].split("/");
	var t_time=tdate[1].split(":");
	var t_year=t_date[2];
	var t_month=t_date[1]-1;
	var t_day=t_date[0];
	var t_hr=t_time[0];
	var t_min=t_time[1];
	var stDate = new Date(f_year,f_month,f_day,f_hr,f_min,00);
	var enDate = new Date(t_year,t_month,t_day,t_hr,t_min,00);
}else{
var fdate=fromDate.split("/");
var tdate=toDate.split("/");
	var f_year=fdate[2];
	var f_month=fdate[1]-1; // -1 added by amar 
	var f_day=fdate[0];
	var t_year=tdate[2];
	var t_month=tdate[1]-1;
	var t_day=tdate[0];
		    var stDate = new Date(f_year,f_month,f_day);
		    var enDate = new Date(t_year,t_month,t_day);
}
		    var compDate = enDate - stDate;
		    //var fdate=enDate-curr_Date;
		  //  if(enDate.valueOf() >= stDate.valueOf())
		    /*if ( Date.parse ( enDate ) > Date.parse ( stDate ) )
		        return true;
		    else 
		    {
		        alert("To Date cannot be smaller than From Date");
		        return false;
		    }*/

	}
            
        }
/*

Amar this is the old function=====commented by Rupali

function validateDate1(from_date,to_date){
	
	var from = Date.parse(from_date);
	var to = Date.parse(to_date);
	if(to<from){
	alert('To date is smaller than From date');
	return false;
	}

}*/

function ProceedToAction(id,location_id,from,to,action){
	var form = $('#publish_unpublish');
	action = action.toLowerCase();
	$('#location_id').val(location_id);
	$('#from_date').val(from);
	$('#to_date').val(to);
	$('#'+action+'').prop("checked",true);
	$('<input>').attr({ type: 'hidden',id: 'is_processed',name: 'is_processed',value: '1'}).appendTo(form);
	$('<input>').attr({ type: 'hidden',id: 'log_id',name: 'log_id',value: id }).appendTo(form);
	$('#submit').click();
}

function getLocationDetails(location_name){
	$.get( "/ajax/get_location_details.php?location="+location_name  , function(data){
        $("#location_data").html ( data );
    });
}

function getDeviceDetails(device_status){
	$.get( "/ajax/get_device_details.php?status="+device_status  , function(data){
        $("#device_data").html ( data );
    });
}

function getDeviceInstallationDetails(installation_status){
	$.get( "/ajax/get_device_installation_details.php?status="+installation_status  , function(data){
        $("#installation_data").html ( data );
    });
}

$(document).ready(function(){
$('#loc_name').on('keydown',function(ev) {
	var keypressed = ev.keyCode || ev.which;
	location_name =$(this).val(); 
    	if (keypressed === 13){
	$.get( "/ajax/get_locations_by_criteria.php?keyword="+location_name  , function(data){
        $("#location_data").html(data);
    	});
	}
});
});

$(document).ready(function(){
$('#criteria').on('keydown',function(ev) {
        var keypressed = ev.keyCode || ev.which;
        criteria =$(this).val();
        if (keypressed === 13){
        $.get( "/ajax/get_device_installation_details.php?criteria="+criteria  , function(data){
        $("#installation_data").html(data);
        });
        }
});
});


$(document).ready(function(){
$('#device_name').on('keydown',function(ev) {
	var keypressed = ev.keyCode || ev.which;
	device_code =$(this).val(); 
    	if (keypressed === 13){
	$.get( "/ajax/get_device_details.php?device_code="+device_code  , function(data){
        $("#device_data").html(data);
    	});
	}
});
});

function getLocations(obj, id){
jQuery.ajax({
url: "/ajax/list_locations.php",
type: "POST",
data: obj,
success: function(msg){
$('#'+id).html(msg);
}
});
}

function getLocationsForMultiselect(obj, id){
jQuery.ajax({
url: "/ajax/list_locations_multiselect.php",
type: "POST",
data: obj,
success: function(msg){
$('#'+id).html(msg);
}
});
}

function getLocForDevInstall(obj, id){
jQuery.ajax({
url: "/ajax/list_locations_for_device_installations.php",
type: "POST",
data: obj,
success: function(msg){
$('#'+id).html(msg);
}
});
}


function getSimcards(obj, id){
jQuery.ajax({
url: "/ajax/list_simcards.php",
type: "POST",
data: obj,
success: function(msg){
$('#'+id).html(msg);
}
});
}


function getLocationsInAdminPanel(obj, id){
jQuery.ajax({
url: "/ajax/list_locations_for_admin.php",
type: "POST",
data: obj,
success: function(msg){
$('#'+id).html(msg);
}
});
}

function getLocationsByState(obj, id){
	$.get( "/ajax/get_location_details.php?state="+obj  , function(data){
	$('#'+id).html(data);
    	});
}

function getDistricts(obj, id){
jQuery.ajax({
url: "/ajax/list_districts.php",
type: "POST",
data: obj,
success: function(msg){
$('#'+id).html(msg);
}
});
}

function getDistribution_company(obj, id){
jQuery.ajax({
url: "/ajax/list_distribution_companies.php",
type: "POST",
data: obj,
success: function(msg){
$('#'+id).html(msg);
}
});
}


function getStates(obj, id){
jQuery.ajax({
url: "/ajax/list_states.php",
type: "POST",
data: obj,
success: function(msg){
$('#'+id).html(msg);
}
});
}


function getDistrictsOnLocMap(obj, id){
jQuery.ajax({
url: "/ajax/list_districts_for_location_map.php",
type: "POST",
data: obj,
success: function(msg){
$('#'+id).html(msg);
}
});
}


$(document).ready(function(){
$('#mobile_no').on('keydown',function(ev) {
	var keypressed = ev.keyCode || ev.which;
	mobile_no =$(this).val(); 
    	if (keypressed === 13){
	if(this.value.length!=10){
	alert("Please enter a valid 10 digit mobile number");
	return false;
	}
	$.get( "/ajax/get_sim_details.php?mobile_no="+mobile_no  , function(data){
        $("#sim_data").html(data);
    	});
	}
});
});

$(document).ready(function(){
$('#name').on('blur',function() {
        location_name = $(this).val();
        $.get( "/ajax/check_location.php?location_name="+location_name  , function(data){
	console.log('**'+data+'##');
	if($.trim(data)!=''){
        alert(data);
	$('#name').val('');
	$('#name').focus();
	}
        });
        });
});

function getSimcardsByCompany(company){
	$.get( "/ajax/get_sim_details.php?company="+company  , function(data){
        $("#sim_data").html ( data );
    });
}

/*$(document).ready(function(){
	$('#voltage_data').on('change', function(){
	var formdata = $('#event_log').serialize();
	page_location = '/raw_data.php?'+formdata;
	window.open(page_location,'_blank');
    	});
    	return false;
});

$(document).ready(function(){
	$('#raw_data').on('change', function(){
	var formdata = $('#event_log').serialize();
	page_location = '/raw_file_data.php?'+formdata;
	window.open(page_location,'_blank');
    	});
    	return false;
});*/

$(document).ready(function(){
        $('#login_form').on('submit', function(){
        var formdata = $('#login_form').serialize();
        $.post( "/login.php", formdata, function( data ){
	var response = jQuery.parseJSON(data);
                var error = response.msg;
                if(error){
                //alert( error );
		$('#message').html(error);
                }else{
                        window.location.href=response.header;
                }
        });
        return false;
});
});

function getMinMaxDates(difference){
var dates = {};
var today = new Date;
var before_month = new Date ( new Date(today).setMonth(today.getMonth() - difference));
var maxdate = today.getDate() +'/'+ (today.getMonth() + 1) +'/' + today.getFullYear(); 
var mindate = before_month.getDate() +'/'+ (before_month.getMonth() + 1) +'/' + before_month.getFullYear();
dates.minDate = mindate; 
dates.maxDate = maxdate; 
return dates;
}

function isDeviceDataPresent() {
var device_id=document.getElementById('device_id').value;
var flag="1";
for(i=0;i<1000;i++){
}
$.post('/ajax/is_device_voltage_present.php', {device_id:device_id}, function(data) {
data=data.replace(/(\r\n|\n|\r)/gm,"");
	if(data == "1"){
    		
		var r = confirm("Device Data Already present, old data will be moved to new location?");
		    if (r == true) {
			flag ="1";
		        return true;
		  } else {
			flag ="0";
			return false;
		 }
		
	}else{
			flag ="1";
		alert('returning true');
		return true;
	}
        });
var i=0;
for(i=0;i<1000;i++){
}
alert("flag="+flag);
return false;
}
