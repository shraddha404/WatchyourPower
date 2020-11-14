<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
$locations = $u->getAllLocations(array('restore_all'=>1));

$details= $_GET;
#print_r($details);
if($_GET){
$report_data = $u->getLocationSummaryReport($details);
}
$column_report_data = getColumnChartDataForEvening($details);
$current="detail_report";
?>
<!DOCTYPE html>
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/html_head.php"; ?>
<script type="text/javascript">
         var chartData = <?php echo json_encode($report_data); ?>;
//	var columnData = <?php echo json_encode($column_report_data); ?>;
	var barChartData = [
			              {
                    "country": "USA",
                    "visits": 4025,
                    "color": "#FF0F00"
                },
                {
                    "country": "China",
                    "visits": 1882,
                    "color": "#FF6600"
                },
                {
                    "country": "Japan",
                    "visits": 1809,
                    "color": "#FF9E01"
                },
                {
                    "country": "Germany",
                    "visits": 1322,
                    "color": "#FCD202"
                },
                {
                    "country": "UK",
                    "visits": 1122,
                    "color": "#F8FF01"
                },
                {
                    "country": "France",
                    "visits": 1114,
                    "color": "#B0DE09"
                },
                {
                    "country": "India",
                    "visits": 984,
                    "color": "#04D215"
                },
                {
                    "country": "Spain",
                    "visits": 711,
                    "color": "#0D8ECF"
                },
                {
                    "country": "Netherlands",
                    "visits": 665,
                    "color": "#0D52D1"
                },
                {
                    "country": "Russia",
                    "visits": 580,
                    "color": "#2A0CD0"
                },
                {
                    "country": "South Korea",
                    "visits": 443,
                    "color": "#8A0CCF"
                },
                {
                    "country": "Canada",
                    "visits": 441,
                    "color": "#CD0D74"
                },
                {
                    "country": "Brazil",
                    "visits": 395,
                    "color": "#754DEB"
                },
                {
                    "country": "Italy",
                    "visits": 386,
                    "color": "#DDDDDD"
                },
                {
                    "country": "Australia",
                    "visits": 384,
                    "color": "#999999"
                },
                {
                    "country": "Taiwan",
                    "visits": 338,
                    "color": "#333333"
                },
                {
                    "country": "Poland",
                    "visits": 328,
                    "color": "#000000"
                }
			];
        AmCharts.ready(function () {
                 createPieChart(chartData,'param','value','chartdiv');
		//createColumnChart(barChartData, 'country', 'visits', 'barchartdiv');
        });

$(function() {
$( "#from_date" ).datetimepicker(
{formatTime:'H:i',
format:'d/m/Y H:i',
formatDate:'d/m/Y'
}
);
$( "#to_date" ).datetimepicker(
{formatTime:'H:i',
format:'d/m/Y H:i',
formatDate:'d/m/Y'
}
);
});
</script>
</head>
<body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
               <?php include $_SERVER['DOCUMENT_ROOT']."/includes/header_banner.php"; ?>
<div class="wrapper row-offcanvas row-offcanvas-left">
               <?php include $_SERVER['DOCUMENT_ROOT']."/includes/left_menu.php"; ?>
<aside class="right-side" >
<section class="content-header">
	<h1>Reports</h1>
	<!--
	<ol class="breadcrumb">
	<li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
	<li class="active">Reports</li>
	</ol>
	-->
</section>

<!-- Main content -->
<section class="content">

<form method="get" action="">
<table cellspacing="10" cellpadding="10" width="90%">
	<tbody>
	<tr>
		<td align="left" colspan="4"><h3>Detailed Report</h3></td>
		<td align="right" width="40%" colspan="5"><h4>Back To Map &nbsp;&nbsp;&nbsp;Detailed Report</h4></td>
	</tr>
	<tr>
	<!--
		<td width="15%">&nbsp;&nbsp;
			<select onchange="getCity(this.value);" class="form-control" name="state">
			</select>
		</td>
		<td>&nbsp;</td>
	-->
		<td>
			<select class="form-control" id="location_id" name="location_id" onchange="this.form.submit();">
			<option value="">-- Select  --</option>
			<?php foreach($locations as $loc){ ?>
			<option value="<?php echo $loc['id']; ?>" <?php if($details['location_id']== $loc['id']){ echo 'selected';}?>><?php echo $loc['name']; ?></option>
			<?php } ?>
			</select>
		</td>
		<td>&nbsp;</td>
			
		<td>
			<input type="text" class="datepick" placeholder="From Date" class="form-control" id="from_date" name="from_date" required value="<?php echo $details['from_date']; ?>"></td>
		<td>&nbsp;</td>
		<td>
			<input type="text"  class="datepick" placeholder="To Date" class="form-control" id="to_date" name="to_date" required value="<?php echo $details['to_date']; ?>"></td>
		<td>&nbsp;</td>
		<td><input type="submit" value="Submit"></td>
	</tr>
	<tr>
		<td colspan="9"></td></tr>      
</tbody></table>

<div id="chartdiv" style="width:100%; height:400px;"></div>
<div id="barchartdiv" style="width:100%; height:400px;"></div>

</section><!-- /.content -->
</aside><!-- /.right-side -->

</div><!-- ./wrapper -->


<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>

    </body>
</html>
