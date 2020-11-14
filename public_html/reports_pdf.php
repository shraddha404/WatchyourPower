<?php
ini_set('memory_limit', '-1');
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
if($column_report_data){
getArrayToShowColumnCharts($column_report_data);
getFullColumnChartArrayofDateRange($column_report_data,$details);
}

$column_report_data = array_values($column_report_data);
$line_chart_data = $u->getLineChartDetails($details, $location_id);
$interrupts = $u->getInterrupts($details);
$interruptions=$interrupts;
$final_interrups =$u->getInterruptsTable($interrupts);
$interrupt_duration = getFormattedInterruptsArray($final_interrups);

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
			$district = $loc['district'];
			$state = $loc['state'];
			$type  = $loc['connection_type'];
			break;
		}
	}
}

$categories=$u->getRevenueClassification();
$states = $u->getAllStatesFromLocationsWeb();
$districts = $u->getDistrics();
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
$empty_line_chart_msg = "<span class=\"empty_chart\">No data available for graph</span>";
}

if(empty($interruptions)){
$empty_interruption_msg = "<span class=\"empty_chart\">No interrution data available.</span>";
}

$params = getSummaryParameters();
$average_divisior = 0;
foreach($column_report_data as $c_data){
if($c_data['no_data']==6){
continue;
}
$average_divisior++;
}
$average_availability = floor(($average_supply_minutes * 60 ) / $average_divisior);
$voltage_params=$u->getVoltageParamValues(7);
$avg_high_volt = $voltage_params['normal']['high_value'];
$avg_low_volt = $voltage_params['normal']['low_value'];
//$url = $_SERVER['PATH_INFO'];

function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}
$url = curPageURL();
//$html = file_get_contents($url);
//echo $url;
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
		createPieChart(chartData,'desc','value','graph_display_color','chartdiv',1);
	<?php } if($column_report_data){ ?>
		createStackedColumnChart(barchartData,'barchartdiv',params);
	<?php } if($line_chart_data){ ?>
		createLineChart(linechartData, 'date','voltage','linechartdiv','<?php echo $avg_high_volt ?>','<?php echo $avg_low_volt;?>');
	<?php } ?>
	});
});

</script>
<script src="/js/canvg.js" type="text/javascript"></script>
<script src="/js/rgbcolor.js" type="text/javascript"></script>
<!--<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>-->
<script type="text/javascript" src="/js/html2canvas.js"></script>
<script type="text/javascript" src="/js/jquery.plugin.html2canvas.js"></script>
<script>

// Lookup for required libs
if ( typeof(AmCharts) === 'undefined' || typeof(canvg) === 'undefined' || typeof(RGBColor) === 'undefined' ) {
    throw('Woup smth is wrong you might review that http://www.amcharts.com/forum/viewtopic.php?id=11001');
}

// Define custom util
AmCharts.getExport = function(anything) {
    // Word around until somebody found out how to cover that
	function removeImages(svg) {
    		var startStr    = '<image';
    		var stopStr        = '</image>';
    		if(navigator.userAgent.toLowerCase().match('msie')) {
        		var stopStr        = 'gif" />';
    		}
    		var start = svg.indexOf(startStr);
    		var stop = svg.indexOf(stopStr);
    		var match = '';

    		// Recursion
        	if ( start != -1 && stop != -1 ) {
            	svg = removeImages(svg.slice(0,start) + svg.slice(stop + stopStr.length,svg.length));
    		}
    		//console.log(svg);
        	return svg;
	};

    // Senseless function to handle any input
    function gatherAnything(anything,inside) {
    switch(String(anything)) {
        case '[object String]':
        if ( document.getElementById(anything) ) {
            anything = inside?document.getElementById(anything):new Array(document.getElementById(anything));
        }
        break;
        case '[object Array]':
        for ( var i=0;i<anything.length;i++) {
            anything[i] = gatherAnything(anything[i],true);
        }
        break;

        case '[object XULElement]':
        anything = inside?anything:new Array(anything);
        break;

        case '[object HTMLDivElement]':
        anything = inside?anything:new Array(anything);
        break;

        default:
        anything = new Array();
        for ( var i=0;i<AmCharts.charts.length;i++) {
            anything.push(AmCharts.charts[i].div);
        }
        break;
    }
    return anything;
    }

    var chartContainer    = gatherAnything(anything);
    var chartImages        = [];
    var canvgOptions    = {
    ignoreAnimation    :    true,
    ignoreMouse        :    true,
    ignoreClear        :    true,
    ignoreDimensions:    true,
    offsetX            :    0,
    offsetY            :    0
    };

    /*
    ** Loop, generate, offer
    */

    // Loop through given container
    for(var i1=0;i1<chartContainer.length;i1++) {
    var canvas        = document.createElement('canvas');
    var context        = canvas.getContext('2d');
    var svgs        = chartContainer[i1].getElementsByTagName('svg');
    var image        = new Image();
    var heightCounter = 0;

    // Set dimensions, background color to the canvas
    canvas.width    = chartContainer[i1].offsetWidth;
    canvas.height    = chartContainer[i1].offsetHeight;
    context.fillStyle = '#fff';
    context.fillRect(0,0,canvas.width,canvas.height);

    // Loop through all svgs within the container
    for(var i2=0;i2<svgs.length;i2++) {

        var wrapper        = svgs[i2].parentNode;
        var clone        = svgs[i2].cloneNode(true);
        var cloneDiv    = document.createElement('div');
        var offsets        = {
        x:    wrapper.style.left.slice(0,-2) || 0,
        y:    wrapper.style.top.slice(0,-2) || 0,
        height:    wrapper.offsetHeight,
        width:    wrapper.offsetWidth
        };

        // Remove the style and append the clone to the div to receive the full SVG data
        clone.setAttribute('style','');
        cloneDiv.appendChild(clone);
        innerHTML = removeImages(cloneDiv.innerHTML); // without imagery

        // Apply parent offset
        if ( offsets.y == 0 ) {
        offsets.y = heightCounter;
        heightCounter += offsets.height;
        }

        canvgOptions.offsetX = offsets.x;
        canvgOptions.offsetY = offsets.y;

        // Some magic beyond the scenes...
        canvg(canvas,innerHTML,canvgOptions);
    }
        //console.log(canvas);return false;
    // Get the final data URL and throw that image to the array

    image.src = canvas.toDataURL();
    chartImages.push(image);
    }
    // Return DAT IMAGESS!!!!
    return chartImages
}

// Function to show the export in the document
function exportThis() {
    var items = AmCharts.getExport('chartdiv');
    for ( index in items ) {
    //document.getElementById('not_button').appendChild(items[0]); 
    $('#chartdiv').html(items[0]); 
    $('#barchartdiv').html(items[1]); 
    $('#linechartdiv').html(items[2]); 
    $('.left-side').hide();	
    }
}
function capture() {
    $('body').html2canvas({
	background:'#FFFFFF',
        onrendered: function (canvas) {
            //Set hidden field's value to image data (base-64 string)
            $('#img_val').val(canvas.toDataURL("image/jpeg"));
            //Submit the form manually
            document.getElementById("myForm").submit();
        },
	width: 1280,
	height: 1650
    });
}
</script>
</head>
<body class="skin-blue" style="color:#000; background:none repeat scroll 0 0 #fff;" <?php if($_GET['flag']=='pdf'){ echo 'onload="exportThis(); capture();"'; }?>>
<!--<header class="header" >
        <img class="reportlogo" src="/img/logo2.gif" width="130" height="70" />
<nav class="reportnavbar navbar-static-top" role="navigation">
</nav>
</header>
</br></br></br>-->
<div class="wrapper row-offcanvas row-offcanvas-left"  style="padding-left:0px;width:98%;">
<aside class="right-side strech" style="background-color:#fff;">
<section class="content-header" style="background:none repeat scroll 0 0 #fff; box-shadow:none;">
	<h1 align="center">www.watchyourpower.org</h1>
	<h4><?php if(!empty($details['location_id'])){ echo " Location - $location_name, District - $district, State - $state . </br>Report From $details[from_date] To $details[to_date] for Connection type - $type. "; }?></h4></br>
	<!--	<h4>Report for<?php if(!empty($details['location_id'])){ echo " Connection Type : $type From $details[from_date] To $details[to_date] <br> Location - $location_name, District - $district, State - $state . "; }?></h4><br>
<h1 style="font-size:20px; width:auto;">Location:<?php if(!empty($details['location_id'])){ echo " $location_name, District: $district, <br> State: $state ( Connection Type: $type ) </br></br>Date Range: $details[from_date] to $details[to_date].</br> "; }?></h1></br>-->
<form method="POST" enctype="multipart/form-data" action="screenshot.php" id="myForm">
    <input type="hidden" name="img_val" id="img_val" value="" />
</form>
</section>
</br>
<div class="progress-indicator" style="">
   <img src="/img/processing.gif" alt="progress bar" />
</div>
<?php if(empty($_GET)){ ?>
<?php } elseif($empty_pie_chart_msg && $empty_column_chart_msg && $empty_line_chart_msg && $empty_interruption_msg){ ?>
<div class="parent-div"><span class="empty_chart">
<?php// if($date_range['min_date']=='' && $date_range['max_date']==''){ ?>
<?php if($flag == 1){ ?>
No Data Available
<?php } else {?>
Data is available for below date range</br>
From <?php echo $pie_min_date; ?> To <?php echo $pie_max_date;?>
<?php } ?>
</span></div>
<?php }else{ 
$query = http_build_query($_GET);?>
<page style="font-size: 14px;" id="target">
<!--<a href="getpdf.php?query=<?php echo $query;?>">GET PDF REPORT</a>-->
<div class="title-div">
<div class="title-left"><h4><!--Voltage Profile-->Supply Profile</h4></div>
<div class="title-right"><h4>Interruptions Profile</h4>
</div>
</div>

<div class="parent-div">
	<div id="chartdiv"><?php echo $empty_pie_chart_msg; ?></div>
	<div id="interruption-table">
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

</br>
<div class="hr-div">&nbsp;<hr /></div>

<?php if($show_graphs_in_one_line){ ?>
<br><br><br>
<div class="title-div">
<div class="title-left">
<h4>Evening Supply hours (5pm-11pm)</h4>
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
<div class="title-right"><h4>Voltage Profile</h4></div>
</div>
<div class="title-div">
</div>
<div class="parent-div">
<div id="barchartdiv" style="width:50%;float:left;"><?php echo $empty_column_chart_msg; ?></div>
<div id="linechartdiv" style="width:50%;float:right;"><?php echo $empty_line_chart_msg; ?></div>
</div>
</br></br></br>
<?php }else{ ?>

<h4>Evening Supply hours (5pm-11pm)</h4>
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
<div id="barchartdiv" style="width:100%;float:left;"><?php echo $empty_column_chart_msg; ?></div>
<hr />
</br>
<h4>Voltage Profile</h4>
<div id="linechartdiv" style="width:100%;float:left;"><?php echo $empty_line_chart_msg; ?></div>
</page><?php 
	} //end else show graphs in one line
	} // end else of empty msg string
?>

</section><!-- /.content -->
</aside><!-- /.right-side -->
</div><!-- ./wrapper -->
</br>
<footer class="box box-footer" style="height:53px; margin-bottom:0px ; text-align:left !important; color:#333!important; font-weight:bold; margin-top:10px !important; ">
<!--Disclaimer: Information is available on ‘as is where is basis’ and is subject to ‘terms of data use’ available on watchyourpower.org-->
Note : This information is subject to 'terms of use' mentioned on www.watchyourpower.org 
</footer>

    </body>
</html>
