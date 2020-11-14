<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
$details= $_GET;

$location_id = $details['location_id'];

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
#print_r($report_data);
$column_report_data =$u->getColumnChartDataForEvening($details,$location_id);
$average_supply_minutes = $u->getEveningAverageAvailibility($column_report_data,$diff);
if($column_report_data){
getArrayToShowColumnCharts($column_report_data);
getFullColumnChartArrayofDateRange($column_report_data,$details);
}

$column_report_data = array_values($column_report_data);

$line_chart_data = $u->getLineChartDetails($details, $location_id);
#print_r($line_chart_data);

$interrupts = $u->getInterrupts($details);
$interruptions=$interrupts;
$date1 = "2009-06-26 11:10:10";
$date2 = "2009-06-26 10:10:20";
$diff = abs(strtotime($date2) - strtotime($date1));
//exit;
//print_r($interrupts);
$prev_date='0000-00-00';
$min_diff=0;
$down_sec=0;
$up_sec=0;
$final_interrups=array();
$i=0;
$j=0;
$pre=0;
for($i=0;$i<count($interrupts);$i++){

if($pre == '0' ){
	$down_sec=strtotime($interrupts[$i]['down_date']);
	$pre=1;
}
if($interrupts[$i]['up_date']=='0000-00-00 00:00:00'){

	if(($i+1) == count($interrupts)){
		$up=$interrupts[$i]['dn_date']." 23:59:00";
		$up_sec=strtotime($up);	
		$duration=$up_sec-$down_sec;
		$final_interrups[$j]=$duration;
		$j++;
		$duration=0;
		$pre=0;	
		
	}
	else if(abs(strtotime($interrupts[$i+1]['dn_date']) - strtotime($interrupts[$i]['dn_date'])) > 0){
		$up=$interrupts[$i]['dn_date']." 23:59:00";
		$up_sec=strtotime($up);	
		$duration=$up_sec-$down_sec;
		$final_interrups[$j]=$duration;
		$j++;
		$duration=0;
		$pre=0;	
	}

}else{
	  if($interrupts[$i]['dn_min'] >0 && $pre == 1 && $i >0){
		$up=$interrupts[$i]['dn_date']." ".$interrupts[$i]['dn_hr'].":".($interrupts[$i]['dn_min']-1).":00";
		$up_sec=strtotime($up);	
		$duration=$up_sec-$down_sec;
		$final_interrups[$j]=$duration;
		$j++;
		$duration=0;
		$down_sec=strtotime($interrupts[$i]['down_date']);
		$up_sec=strtotime($interrupts[$i]['up_date']);
		$duration=$up_sec-$down_sec;
		$final_interrups[$j]=$duration;
		$j++;
		$duration=0;
		$pre=0;
		
	  }else{
		$up_sec=strtotime($interrupts[$i]['up_date']);
		$duration=$up_sec-$down_sec;
		$final_interrups[$j]=$duration;
		$j++;
		$duration=0;
		$pre=0;
	  }	
		
	}	
/*echo "\n down=".$down_sec;	
echo "\r\n up=".$up_sec;	
echo "\r\n\n".$interrupts[$i]['down_date'];*/
}
//print_r($final_interrups);	


//exit;
//$interruptions = $u->getInterruptions($details);
//$interrupt_duration = getFormattedInterruptionsArray($interruptions); // commented by amar
$interrupt_duration = getFormattedInterruptsArray($final_interrups);
//print_r($interrupt_duration);

if($details['location_id']){
$location = $u->getLocationDetails($details['location_id']);
$details['state'] = $location['state'];
$details['category_id'] = $location['revenue_classification'];
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
			break;
		}
	}
}

$categories=$u->getRevenueClassification();
//$states = $u->getAllStatesFromLocations();
$states = $u->getAllStatesFromLocationsWeb();
$districts = $u->getDistrics();
$current="reports";
if(empty($report_data)){
$empty_pie_chart_msg = "<span class=\"empty_chart\">No data available for graph</span>";
}

if(empty($column_report_data)){
$empty_column_chart_msg = "<span class=\"empty_chart\">No data available for graph</span>";
}

if(empty($line_chart_data)){
$empty_line_chart_msg = "<span class=\"empty_chart\">No data available for graph</span>";
}
if(empty($interruptions)){
$empty_interruption_msg = "<span class=\"empty_chart\">No interrution data available.</span>";
}

$params = getSummaryParameters();
$average_availability = floor(($average_supply_minutes * 60 ) / count($column_report_data));
?>
<!DOCTYPE html>
<html>
<head>
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
		createPieChart(chartData,'param','value','graph_display_color','chartdiv');
	<?php } if($column_report_data){ ?>
		createStackedColumnChart(barchartData,'barchartdiv',params);
	<?php } if($line_chart_data){ ?>
		createLineChart(linechartData, 'date','voltage','linechartdiv');
	<?php } ?>
	});

var dateRange = getMinMaxDates(1);

$( "#from_date" ).datetimepicker(
{formatTime:'H:i',
format:'d/m/Y',
formatDate:'d/m/Y',
//minDate:dateRange.minDate,
//maxDate:dateRange.maxDate,
timepicker:false
}
);
$( "#to_date" ).datetimepicker(
{formatTime:'H:i',
format:'d/m/Y',
formatDate:'d/m/Y',
//minDate:dateRange.minDate,
//maxDate:dateRange.maxDate,
timepicker:false
}
);

$(document).on("change","#state, #category_id", function(){
        var obj ={};
        obj.state = $('#state').val();
        obj.category_id = $('#category_id').val();
        obj.district = $('#district').val();
        obj.location_div_id = $('#location_id').attr('id');
        obj.district_div_id = $('#district').attr('id');
	if(obj.state!=''){
	getDistricts(obj,'dist');
	}
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

$(document).on("click","#long-interruption",function(){
	$('#long-interruption-first td').toggle();
	$('#long-interruption-second td').toggle();
})

$(document).on("click","#short-interruption",function(){
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
</script>
</head>
<body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
               <?php //include $_SERVER['DOCUMENT_ROOT']."/includes/header_banner.php"; ?>
<div class="wrapper row-offcanvas row-offcanvas-left">
               <?php //include $_SERVER['DOCUMENT_ROOT']."/includes/left_menu.php"; ?>
<aside class="right-side" >
<section class="content-header">
	<h1>Reports<?php if(!empty($details['location_id'])){ echo " - $location_name"; }?></h1>
	<!--
	<ol class="breadcrumb">
	<li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
	<li class="active">Reports</li>
	</ol>
	-->
</section>

<!-- Main content -->
<div class="box box-solid bg-light-blue-gradient" style="color:black !important;">

        <div class="box-header">
        <!-- tools box -->
        <div class="pull-right box-tools">
                <button class="btn btn-primary btn-sm pull-right" data-widget='collapse' data-widget='collapse' data-toggle="tooltip" title="Collapse" style="margin-right: 5px;">
                        <i class="fa fa-minus"></i>
                </button>
        </div><!-- /. tools -->
                <h3 class="box-title">Please select location and date range to see Report </h3>
        </div>

        <div class="box-body">

<section class="content">

<form method="get" action="" onsubmit="return validateForm(this);">
<table cellspacing="10" cellpadding="10" width="90%" border="0">

<tbody><tr><td colspan="7"><input class="btn btn-primary" type="button" value="Print" onclick="printpage()"></td></tr>
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

                <td style="width:22%;">
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
                        <?php foreach($locations as $loc){ ?>
                        <option value="<?php echo $loc['id']; ?>" <?php if($details['location_id']== $loc['id']){ echo 'selected="selected"';}?>><?php echo $loc['name']; ?></option>
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
<center>Please select locatoin to view report. You can also select location on the map <a href="/location_map.php">here</a>.</center>
</form>
<hr />
</div>
</div>
<div class="progress-indicator" style="">
   <img src="/img/processing.gif" alt="progress bar" />
</div>
<?php if(empty($_GET)){ ?>
<?php } elseif($empty_pie_chart_msg && $empty_column_chart_msg && $empty_line_chart_msg && $empty_interruption_msg){ ?>
<div class="parent-div"><span class="empty_chart">NO DATA FOUND</span></div>
<?php }else{ ?>
<page style="font-size: 14px">
<a href="testreport.php">PDF REPORT</a>
<div class="title-div">
<div class="title-left"><h3>Voltage Profile</h3></div>
<div class="title-right"><h3>Electricity Interruption Profile</h3></div>
</div>

<div class="parent-div">
	<div id="chartdiv"><?php echo $empty_pie_chart_msg; ?></div>
	<div id="interruption-table">
	<?php if($interruptions){ include "includes/interruption_table.php"; }else{ echo $empty_interruption_msg; } ?>
	</div>
</div>

<div class="hr-div">&nbsp;<hr /></div>

<?php if($show_graphs_in_one_line){ ?>
<div class="title-div">
<div class="title-left">
<h4>Evening Supply (Availability of Electricity during 5 pm - 11 pm)</h4>
<div class="mailbox">
        <div class="table-responsive">
                <table class="table table-mailbox" style="width:100%;">
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
</div>
<div class="title-right"><h4>Daily Voltage Profile</h4></div>
</div>
<div class="title-div">
</div>
<div class="parent-div">
<div id="barchartdiv" style="width:50%;float:left;"><?php echo $empty_column_chart_msg; ?></div>
<div id="linechartdiv" style="width:50%;float:right;"><?php echo $empty_line_chart_msg; ?></div>
</div>
<?php }else{ ?>

<h3>Evening Supply (Availability of Electricity during 5 pm - 11 pm)</h3>
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
<h3>Daily Voltage Profile</h3>
<div id="linechartdiv"><?php echo $empty_line_chart_msg; ?></div>
</page><?php 
	} //end else show graphs in one line
	} // end else of empty msg string
?>

</section><!-- /.content -->
</aside><!-- /.right-side -->

</div><!-- ./wrapper -->




    </body>
</html>
