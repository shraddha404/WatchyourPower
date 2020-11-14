<?php
ini_set('memory_limit', '-1');
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
$details= $_GET;
$location_id = $details['location_id'];
//print_r($details);
if($details['from_date']=='' || $details['to_date']==''){
$details['from_date'] = date('d/m/Y',strtotime('-7 day'));
$details['to_date'] = date('d/m/Y',strtotime('-1 day'));
}

$from_date = new DateTime(date('Y-m-d',get_strtotime($details['from_date'])));
$to_date = new DateTime(date('Y-m-d',get_strtotime($details['to_date'])));
$diff = date_diff($from_date,$to_date);
if($diff->format('%a') < 8){
$show_graphs_in_one_line = 1;
}


$report_data = $u->getLocationSummaryReport($details, $location_id);
getArrayToShowPieChart($report_data);
//print_r($report_data);
foreach($report_data as $k=> $v){
if($v['param']=='no_data'){
$no_data_minutes = $v['value'];
}

}
$total_minutes = $report_data[0]['total_minutes'];
$data_not_available = round(intval(($no_data_minutes)/60));
$total_days = round(intval($total_minutes)/60/24);
$total_hours = round(intval($total_minutes)/60);
$available_hours = round(intval($total_hours-$data_not_available)); 
$column_report_data =$u->getColumnChartDataForEvening($details,$location_id);
$average_supply_minutes = $u->getEveningAverageAvailibility($column_report_data,$diff);
//echo $average_supply_minutes;
if($column_report_data){
getArrayToShowColumnCharts($column_report_data);
getFullColumnChartArrayofDateRange($column_report_data,$details);
}

$column_report_data = array_values($column_report_data);
//print_r($column_report_data);
$line_chart_data = $u->getLineChartDetails($details, $location_id);
#print_r($line_chart_data);

$interrupts = $u->getInterrupts($details);
//print_r($interrupts);
$interruptions=$interrupts;
//exit;
//print_r($interrupts);
$final_interrups =$u->getInterruptsTable($interrupts);
//print_r($final_interrups);
//$interruptions = $u->getInterruptions($details);
//$interrupt_duration = getFormattedInterruptionsArray($interruptions); // commented by amar
$interrupt_duration = getFormattedInterruptsArray($final_interrups);
//print_r($interrupt_duration);

if($details['location_id']){
$location = $u->getLocationDetails($details['location_id']);
$details['name'] = $location['name'];
$details['state'] = $location['state'];
$details['district']=$location['district'];
$details['category_id'] = $location['revenue_classification'];
$details['type']  = $location['connection_type'];
}

if($details['state']!='' and $details['category_id']!=''){
$locations = $u->getLocationsFromStateAndCategory($details['state'],$details['category_id']);
}

$locations = $u->getLocationsFromCriteria($details);
// get name of the location
if(!empty($details['location_id'])){
	$location_name = '';
	foreach($locations as $loc){
		if($details['location_id'] == $loc['id']){
			$location_name = $loc['name'];
			$district = $loc['district'];
			$state = $loc['state'];
			$type  = $loc['connection_type'];
			break;
		}
	}
}
//print_r($locations);
$categories=$u->getRevenueClassification();
//$states = $u->getAllStatesFromLocations();
$states = $u->getAllStatesFromLocationsWeb();
$districts = $u->getAvailableDistrictsFromCriteria();
$current="reports";
if(empty($report_data)){
$flag =1;
$date_range = $u->getDateRangeForAvailableData($location_id);
if($date_range['min_date']=='' && $date_range['max_date']==''){

$empty_pie_chart_msg = "<span class=\"empty_chart\">No data available for graph</span>";
}else{
$pie_min_date = date('d/m/Y',strtotime($date_range['min_date']));
$pie_max_date = date('d/m/Y',strtotime($date_range['max_date']));
$empty_pie_chart_msg = "<span class=\"empty_chart\">Data available from ".$pie_min_date." to ". $pie_max_date."</span>";
}
}

if(empty($column_report_data)){
$date_range = $u->getDateRangeForAvailableData($location_id);
if($date_range['min_date']=='' && $date_range['max_date']==''){
$empty_column_chart_msg = "<span class=\"empty_chart\">No data available for graph</span>";
}else{
$column_min_date = date('d/m/Y',strtotime($date_range['min_date']));
$column_max_date = date('d/m/Y',strtotime($date_range['max_date']));
$empty_column_chart_msg = "<span class=\"empty_chart\">Data available from ".$column_min_date." to ". $column_max_date."</span>";
}
}

if(empty($line_chart_data)){
#$date_range = $u->getDateRangeForAvailableData($location_id);
#if($date_range['min_date']=='' && $date_range['max_date']==''){
$empty_line_chart_msg = "<span class=\"empty_chart\">No data available for graph</span>";
#}else{
#$line_min_date = date('d/m/Y',strtotime($date_range['min_date']));
#$line_max_date = date('d/m/Y',strtotime($date_range['max_date']));
#$empty_line_chart_msg = "<span class=\"empty_chart\">Data available from ".$line_min_date." to ". $line_max_date."</span>";
#}
}

if(empty($interruptions)){
#$date_range = $u->getDateRangeForAvailableData($location_id);
#if($date_range['min_date']=='' && $date_range['max_date']==''){
$empty_interruption_msg = "<span class=\"empty_chart\">No interrution data available.</span>";
#}else{
#$interrupt_min_date = date('d/m/Y',strtotime($date_range['min_date']));
#$interrupt_max_date = date('d/m/Y',strtotime($date_range['max_date']));
#$empty_interruption_msg = "<span class=\"empty_chart\">Data available from ".$interrupt_min_date." to ". $interrupt_max_date."</span>";
#}
}

$params = getSummaryParameters();
$average_divisior = 0;
foreach($column_report_data as $c_data){
if($c_data['no_data']==6){
continue;
}
$average_divisior++;
}
#echo $average_divisior;
#$average_availability = floor(($average_supply_minutes * 60 ) / count($column_report_data));
$average_availability = floor(($average_supply_minutes * 60 ) / $average_divisior);
$voltage_params=$u->getVoltageParamValues(7);
$avg_high_volt = $voltage_params['normal']['high_value'];
$avg_low_volt = $voltage_params['normal']['low_value'];
$u_agent = $_SERVER['HTTP_USER_AGENT'];
        $ub = '';
        if(preg_match('/MSIE/i',$u_agent))
        {
            $ub = "Internet Explorer";
        }
        elseif(preg_match('/Firefox/i',$u_agent))
        {
            $ub = "Mozilla Firefox";
        }
        elseif(preg_match('/Safari/i',$u_agent))
        {
            $ub = "Apple Safari";
        }
        elseif(preg_match('/Chrome/i',$u_agent))
        {
            $ub = "Google Chrome";
        }
        elseif(preg_match('/Flock/i',$u_agent))
         {
            $ub = "Flock";
        }
        elseif(preg_match('/Opera/i',$u_agent))
        {
            $ub = "Opera";
        }
        elseif(preg_match('/Netscape/i',$u_agent))
        {
            $ub = "Netscape";
        }
//echo $ub;
?>
<!DOCTYPE html>
<html>
<head>
<script type="text/javascript">
function Bookmark(){
    if(window.sidebar && window.sidebar.addPanel){
        window.sidebar.addPanel(document.title,location.href,''); //obsolete from FF 23.
	}else if(window.opera && window.print) { 
        alert(navigator.userAgent);
        var e = document.createElement('a');
        e.setAttribute('href',location.href);
        e.setAttribute('title',document.title);
        e.setAttribute('rel','sidebar');
        e.click();
	}else if(window.external){
            window.external.AddFavorite(location.href,document.title);
	}else{
        alert("To add our website to your bookmarks use CTRL+D on Windows and Linux and Command+D on the Mac.");
	}
}
</script>

<script type="text/javascript">
function printpage()
  {
  window.print()
  }
</script>

<?php include $_SERVER['DOCUMENT_ROOT']."/includes/html_head.php"; ?>
<script type="text/javascript">
	var params = <?php echo json_encode($params); ?>;
	<?php if($report_data){ ?>
	var chartData = <?php echo json_encode($report_data); ?>;
	<?php } if($column_report_data){ ?>
	var barchartData = <?php echo json_encode($column_report_data); ?>;
	<?php } if($line_chart_data){ ?>
	var linechartData = <?php echo json_encode($line_chart_data); ?>;
	<?php } ?>

$(function() {
	$('.progress-indicator').show();	
	AmCharts.ready(function () {
	$('.progress-indicator').hide();
	<?php if($report_data){ ?>
	$(".fa-minus").trigger('mouseenter');
	$('.fa-minus').trigger('click');
		createPieChart(chartData,'desc','value','graph_display_color','chartdiv',1);
	<?php } if($column_report_data){ ?>
		createStackedColumnChart(barchartData,'barchartdiv',params);
	<?php } if($line_chart_data){ ?>
		createLineChart(linechartData, 'date','voltage','linechartdiv','<?php echo $avg_high_volt ?>','<?php echo $avg_low_volt;?>');
	<?php } ?>
	});

var dateRange = getMinMaxDates(1);
var today = new Date();
maxdate = (today.getDate()-1 +'/'+(today.getMonth() + 1) +'/' +  today.getFullYear());
$( "#from_date" ).datetimepicker(
{formatTime:'H:i',
format:'d/m/Y',
formatDate:'d/m/Y',
//minDate:dateRange.minDate,
//maxDate:dateRange.maxDate,
timepicker:false,
closeOnDateSelect:true
}
);
$( "#to_date" ).datetimepicker(
{formatTime:'H:i',
format:'d/m/Y',
formatDate:'d/m/Y',
maxDate:maxdate,
//minDate:dateRange.minDate,
//maxDate:dateRange.maxDate,
timepicker:false,
closeOnDateSelect:true
}
);

$(document).on("change","#category_id", function(){
        var obj ={};
        obj.state = '';
        //obj.state = $('#state').val();
        obj.category_id = $('#category_id').val();
        obj.district = '';
        //obj.district = $('#district').val();
	obj.state_div_id = $('#state').attr('id');
        obj.location_div_id = $('#location_id').attr('id');
        obj.district_div_id = $('#district').attr('id');
	getStates(obj,'stat');
	//if(obj.state!=''){
	getDistricts(obj,'dist');
	//}
        getLocations(obj,'loc');
});

$(document).on("change","#state", function(){
        var obj ={};
        obj.state = $('#state').val();
        obj.category_id = $('#category_id').val();
        //obj.district = $('#district').val();
        obj.district = '';
        obj.state_div_id = $('#state').attr('id');
        obj.location_div_id = $('#location_id').attr('id');
        obj.district_div_id = $('#district').attr('id');
        //if(obj.state!=''){
        getDistricts(obj,'dist');
        //}
        getLocations(obj,'loc');
});


$(document).on("change","#district", function(){
        var obj ={};
        obj.state = $('#state').val();
        obj.category_id = $('#category_id').val();
        obj.district = $('#district').val();
        obj.location_div_id = $('#location_id').attr('id');
        getLocations(obj,'loc');
});

$(document).on("change","#location_id", function(){
	if($('#location_id').val()!=''){
		$('#submit').show();
	}else{
		$('#submit').hide();
	}
});

/*$(document).on("click","#long-interruption",function(){
	$('#long-interruption-first td').toggle();
	$('#long-interruption-second td').toggle();
})

$(document).on("click","#short-interruption",function(){
	$('#short-interruption-first td').toggle();
	$('#short-interruption-second td').toggle();
})*/

$(document).on("click","#plus, #minus",function(){
	$('#long-interruption-first td').toggle();
	$('#long-interruption-second td').toggle();
})

$(document).on("click","#first_minus, #first_plus",function(){
	$('#short-interruption-first td').toggle();
	$('#short-interruption-second td').toggle();
})

});

function validateForm(theForm){
	var days = getDaysInDateRange(theForm.from_date.value, theForm.to_date.value);
	//alert(days);
	if(days > 31){
		alert('Please select a date-range of up to 31 days.');
		return false;;
	}
	return true;
}
$(document).ready(function(){
 var cat =  $('.btn').children("i").attr("class");
 var element = $('button');
 if(cat=='fa fa-minus'){
 element.attr("title","Collapse");
 }else{
 element.attr("title","Expand");
 }
});

$(document).on("click",".btn",function(){
var title =  $('.btn').children("i").attr("class");
var element = $('button');
 if(title=='fa fa-minus'){
 element.attr("title","Collapse");
 }else{
 element.attr("title","Expand");
 }
})


</script>
</head>
<body class="skin-blue">
               <?php include $_SERVER['DOCUMENT_ROOT']."/includes/header_banner.php"; ?>
<div class="wrapper row-offcanvas row-offcanvas-left">
               <?php include $_SERVER['DOCUMENT_ROOT']."/includes/left_menu.php";  ?>
<aside class="right-side" >
<section class="content-header">
<?php $query = http_build_query($_GET);?>
 <?php if(!empty($report_data)){?>
 <a href="reports_pdf.php?<?php echo $query;?>&flag=pdf" id="button" target="_blank" style="float:right;">&nbsp;|&nbsp;<img src="/img/download_icon.png"> Download Report</a>
<?php } ?>
<a href="#" <?php if($ub == 'Mozilla Firefox'){?>rel="sidebar" <?php } else {?> onclick="Bookmark();"<?php }?> style="float:right;"><img src="/img/bookmark.png"> Bookmark</a>
	<h4><?php if(!empty($details['location_id'])){ echo " Location - $details[name], District - $details[district], State - $details[state] . </br>Report From $details[from_date] To $details[to_date] for Connection type - $details[type]. "; }?></h4>
</section>

<!-- Main content -->
<div class="box box-solid bg-light-blue-gradient" style="color:black !important;">

        <div class="box-header">
        <!-- tools box -->
                <h3 class="box-title">Please select location and date range to see Report 
        <div class="pull-right box-tools">
                <button class="btn btn-primary btn-sm pull-right" data-widget='collapse' data-toggle="title" style="margin-left:15px;">
                        <i class="fa fa-minus"></i>
                </button>
        </div><!-- /. tools --></h3>
        </div>

        <div class="box-body">

<section class="content" >

<form method="get" action="" onsubmit="return validateForm(this);">
<table cellspacing="10" cellpadding="10" width="90%" border="0">

<tbody><!--tr><td colspan="7"><input class="btn btn-primary" type="button" value="Print" onclick="printpage()"></td></tr-->
	<tr>
	<th align="center">Category</th>
	<th>&nbsp;</th>
	<th align="center">State</th>
	<th>&nbsp;</th>
	<th align="center">District</th>
	<th>&nbsp;</th>
	<th align="center">Location</th>
	</tr>
        <tr>
                <td>
                        <select class="form-control" id="category_id" name="category_id">
                        <option value="">-- Select  Category--</option>
                        <?php foreach($categories as $cat){ 
				if($cat['name'] == 'Mega city') continue; // hiding Mega city option only in the website.
			?>
                        <option value="<?php echo $cat['id']; ?>" <?php if($details['category_id']== $cat['id']){ echo 'selected="selected"';}?> ><?php echo $cat['name']; ?></option>
                        <?php } ?>
                        </select>

                </td>
                <td>&nbsp;&nbsp;&nbsp;</td>

                <td style="width:22%;" id="stat">
                        <select class="form-control" id="state" name="state">
                        <option value="">-- Select  State --</option>
                        <?php foreach($states as $state){ ?>
                        <option value="<?php echo $state; ?>" <?php if($details['state']== $state){ echo 'selected="selected"';}?>><?php echo $state; ?></option>
                        <?php } ?>
                        </select>
                </td>
                <td>&nbsp;&nbsp;&nbsp;</td>


                <td style="width:22%;" id="dist">
                        <select class="form-control" id="district" name="district">
                        <option value="">-- Select  District --</option>
                        <?php foreach($districts as $dist){ ?>
                        <option value="<?php echo $dist['district']; ?>" <?php if($details['district']== $dist['district']){ echo 'selected="selected"';}?>><?php echo $dist['district']; ?></option>
                        <?php } ?>
                        </select>
                </td>
                <td>&nbsp;&nbsp;&nbsp;</td>

                <td style="width:22%;" id="loc">
			<select class="form-control" id="location_id" name="location_id">
                        <option value="">-- Select  --</option>
                        <?php 
				foreach($locations as $loc){ if($loc['status']==0){ continue;} 
				if(preg_match('/Offline/', $loc['name'])){
					$class = 'offline';
				}
				else{
					$class = '';
				}	
			?>
                        <option class="<?php echo $class; ?>" value="<?php echo $loc['id']; ?>" <?php if($details['location_id']== $loc['id']){ echo 'selected="selected"';}?>><?php echo $loc['name']; #echo ltrim(rtrim(preg_replace('/\[Offline\]/', '', $loc['name']))); ?></option>
                        <?php } ?>
                        </select>
                </td>
        </tr>
	<tr><td colspan="8">&nbsp;</td></tr>
</table>
<table cellspacing="10" cellpadding="10" width="90%" border="0">
<tbody>
		<tr>
                <th>From Date</th>
                <th>&nbsp;</th>
                <th>To Date</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                </tr>

	        <tr>
                <td style="width:18%;">
                        <input type="text" placeholder="From Date" class="form-control datepick" id="from_date" name="from_date" required value="<?php echo $details['from_date']; ?>" size="5" readonly="readonly">
                </td>
                <td>&nbsp;</td>
                <td style="width:18%;">
                        <input type="text" placeholder="To Date" class="form-control datepick" id="to_date" name="to_date" required value="<?php echo $details['to_date']; ?>" size="5" readonly="readonly">
                </td>
                <td>&nbsp;</td>
                <td style="width:18%;"><input type="submit" value="Show Report" class="btn btn-primary" id="submit" <?php if($location_id==''){ ?>style="display:none;" <?php } ?>></td>
		<td>&nbsp;</td>
                <td>&nbsp;</td>
        	</tr>
        	<tr><td colspan="6">&nbsp;</td></tr>
</tbody>
</table>
<center>Please select location to view report. You can also select location on the map <a href="/location_map.php">here</a>.</center>
<center>You can see the summary reports for all locations <a href="/location_dashboard.php">here</a>.</center>
</form>
<hr />
</div>
</div>
<div class="progress-indicator" style="">
   <img src="/img/processing.gif" alt="progress bar" />
</div>
<?php if(empty($_GET)){ ?>
<?php } elseif($empty_pie_chart_msg && $empty_column_chart_msg && $empty_line_chart_msg && $empty_interruption_msg){ ?>
<div class="parent-div"><span class="empty_chart">
<?php if($date_range['min_date']=='' && $date_range['max_date']==''){ ?>
<?php// if($flag == 1){ ?>
No Data Available
<?php } else {?>
Data is available for below date range</br>
From <?php echo $pie_min_date; ?> To <?php echo $pie_max_date;?>
<?php } ?>
</span></div>
<?php }else{ 
#$query = http_build_query($_GET);
?>
<page style="font-size: 14px" id="target">
<!--<a href="getpdf.php?query=<?php //echo $query;?>">GET PDF REPORT</a>-->
<div class="title-div">
<div class="title-left"><h4><!--Voltage Profile-->Supply Profile&nbsp;&nbsp;&nbsp;<img src="/img/help.png" alt="Help" title="This is an interactive pie graph shows electricity supply quality over selected date range into 5 voltage ranges. 0-130 V (No supply), 131-204 V (Low Voltage), 205-270 V (Normal Voltage), Above 271 V (High Voltage) and No data where data is not available."/></h4>  </div>
<div class="title-right"><h4>Interruptions Profile&nbsp;&nbsp;&nbsp;<img src="/img/help.png"  alt="Help" title="Discontinuity in power supply may cause inconvenience and force consumers to invest in backup solutions. The table shows the breakup of interruptions in the selected date range according to interruption duration, between 2 minutes to more than three hours .Third column also shows duration of no supply for each interruption category."/></h4>
</div>
</div>

<div class="parent-div">
	<div id="chartdiv"><?php echo $empty_pie_chart_msg; ?></div>
	<div id="interruption-table">
	<?php //if($interruptions){ include "includes/interruption_table.php"; }else{ echo $empty_interruption_msg; } ?>
	<?php include "includes/interruption_table.php";  ?>
	</div>
</div>
<div class="mailbox" style="position:relative; float:left;">
        <div class="table-responsive">
                <table class="table table-mailbox" style="width:65%; margin-left: 10px;">
                        <tbody>
                        <tr class="unread">
                        <td class="small-col" style="width:80%">
                        <span style="float:left;"><?php if(!empty($report_data)){ ?>Out of <?php echo $total_hours;?> hours of data in selected <?php echo $total_days;?> days, <?php echo $available_hours;?> hours of data is available<?php } else { ?>No data available<?php }?> </span>
                        </td>
                        </tr>
                        </tbody>
                </table>
        </div>
</div>

<div class="hr-div">&nbsp;<hr /></div>

<?php if($show_graphs_in_one_line){ ?>
<div class="title-div">
<div class="title-left">
<h4>Evening Supply hours (5pm-11pm)&nbsp;&nbsp;&nbsp;<img src="/img/help.png" alt="Help" class="help" title="Availability of electricity supply in evening hours is one of the important electricity supply quality parameter. This is an interactive chart which shows the availability of power during peak evening hours between 5 PM to 11 PM."/></h4>
<div class="mailbox">
        <div class="table-responsive">
                <table class="table table-mailbox" style="width:100%; margin-left: 10px;">
                        <tbody>
                        <tr class="unread">
                        <td class="small-col" style="width:70%">
                        <span style="float:left;">Average Availability during 6 hours of evening (5 pm - 11 pm)</span>
                        <span style="float:right;"><?php echo secondsToTime($average_availability); ?>(HH:MM)</span>
                        </td>
                        </tr>
                        </tbody>
                </table>
        </div>
</div>
</div>
<h4>Voltage Profile&nbsp;&nbsp;&nbsp;<img src="/img/help.png" alt="Help" title="This graph shows minute by minute voltage at the location for the selected date range. Voltage value less than 130V is an indicative value of very low voltage and is considered as no supply condition in other reports."/></h4>
</div>
<div class="title-div">
</div>
<div class="parent-div">
<div id="barchartdiv" style="width:50%;float:left;"><?php echo $empty_column_chart_msg; ?></div>
<div id="linechartdiv" style="width:50%;float:right;"><?php echo $empty_line_chart_msg; ?></div>
</div>
<?php }else{ ?>

<h4>Evening Supply hours (5pm-11pm)&nbsp;&nbsp;&nbsp;<img src="/img/help.png"  alt="Help" title="Availability of electricity supply in evening hours is one of the important electricity supply quality parameter. This is an interactive chart which shows the availability of power during peak evening hours between 5 PM to 11 PM."/></h4>
<div class="mailbox">
        <div class="table-responsive">
                <table class="table table-mailbox" style="width:65%;">
                        <tbody>
                        <tr class="unread">
                        <td class="small-col" style="width:80%">
			<span style="float:left;">Average Availability during 6 hours of evening (5 pm - 11 pm)</span> 
			<span style="float:right;"><?php echo secondsToTime($average_availability); ?></span>
			</td>
                        </tr>
			</tbody>
		</table>
	</div>
</div>
<div id="barchartdiv"><?php echo $empty_column_chart_msg; ?></div>
<hr />
<h4>Voltage Profile&nbsp;&nbsp;&nbsp;<img src="/img/help.png" alt="Help"  title="This graph shows minute by minute voltage at the location for the selected date range. Voltage value less than 130V is an indicative value of very low voltage and is considered as no supply condition in other reports."/></h4>


<div id="linechartdiv"><?php echo $empty_line_chart_msg; ?></div>
</page><?php 
	} //end else show graphs in one line
	} // end else of empty msg string
?>

</section><!-- /.content -->
</aside><!-- /.right-side -->

</div><!-- ./wrapper -->


<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>

    </body>
</html>
