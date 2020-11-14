<?php 
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
$locations = $u->getDeviceInstalledLocations(array('restore_all'=>1));
$from_date = date('d/m/Y',strtotime('-7 day'));
$to_date = date('d/m/Y',strtotime('-1 day'));
$details = array('location_id'=>'30', 'from_date'=>$from_date, 'to_date'=>$to_date);
$report_data = $u->getLocationSummaryReport($details,30);
$states_count = $u->getStatesCount();
$locations = $u->getLocationsFromCriteria();
$loc_count=0;
foreach($locations as $loc){
if($loc['status']!=0){
$loc_count++;
}
}
//print_r($locations);
$current="home";
?>
<!DOCTYPE html>
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/html_head.php"; ?>
<script type="text/javascript" src=""></script>
<script src="/amcharts/amcharts/amcharts.js" type="text/javascript"></script>
<script src="/amcharts/amcharts/pie.js" type="text/javascript"></script>
<script src="/amcharts/amcharts/serial.js" type="text/javascript"></script>
<script src="/js/function.js" type="text/javascript"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBb2_EW71m4j7lA3N8KmY6Tf5bqMXEfb4A"></script>
<script type="text/javascript" src="/js/markerclusterer.js"></script>
<script type="text/javascript">
var locations = 
	[
		<?php foreach($locations as $loc){
		if($loc['status']==0){
		continue;
		}
		 ?>
		['<?php echo sanitizeInput($loc['name']); ?>', <?php echo $loc['latitude']?>, <?php echo $loc['longitude']; ?>,<?php echo $loc['id']?>, '<?php echo sanitizeInput($loc['category']). ', '.sanitizeInput($loc['connection_type']); ?>'],
		<?php } ?>
	];

	google.maps.event.addDomListener(window, 'load', function(){initialize(locations);});
	/*var chartData = <?php echo json_encode($report_data); ?>;
        AmCharts.ready(function () {
		 createPieChart(chartData,'param','value','chartdiv');
        });*/
</script>
</head>
<body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
               <?php include $_SERVER['DOCUMENT_ROOT']."/includes/header_banner.php"; ?>
<div class="wrapper row-offcanvas row-offcanvas-left">
               <?php include $_SERVER['DOCUMENT_ROOT']."/includes/left_menu.php"; ?>
<aside class="right-side strech">
<!--
<section class="content-header">
	<h1>Home</h1>
	<ol class="breadcrumb">
	<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
	<li class="active">Home</li>
	</ol>
</section>
-->

<!-- Main content -->
<section class="content">
<h4>We have revamped the <a href="http://watchyourpower.org">watchyourpower.org</a>, here is what is new.</h4>
<ul>
<li>
To view the supply quality reports for Live locations, select locations <a href="http://watchyourpower.org/reports.php">from list</a> or <a href="http://watchyourpower.org/location_map.php">from map</a>, available in the Select locations menu in the sidebar.
</li>
<li>
A new <a href="http://watchyourpower.org/location_dashboard.php">Location dashboard</a> shows summary analysis for all locations, available in the Downloads section .
</li>
<li>
The <a href="http://watchyourpower.org/analysis_dashboard.php">State dashboard</a> shows statewise summary analysis, available in the Downloads section .
</li>
<li>
<a href="http://watchyourpower.org/download_raw_data.php">Minute-wise data</a> collected through ESMI can now be downloaded, available in the Downloads section .
</li>
</ul>
                    <!-- Main row -->
<div class="row">

<!-- Left col -->
<section class="col-lg-7 connectedSortable">                         
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/map_section.php"; ?>
</section>

<!-- /.Left col -->

                        <!-- right col (We are only adding the ID to make the widgets sortable)-->
<section class="col-lg-5 connectedSortable ui-sortable"> 
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/report_section.php"; ?>
</section><!-- right col -->

</div><!-- /.row (main row) -->

<!-- Small boxes (Stat box)-->
<div class="row">
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/header.php"; ?>
<!--<p style="padding-left:20px;">We would love to hear from you! What can we do to improve your ESMI experience ? Please write to <a href="mailto:esmi@prayaspune.org">esmi@prayaspune.org</a> or fill in <a href="contact_us.php">this form.</a></p>-->
</div> <!--/.row -->
</section><!-- /.content -->
</aside><!-- /.right-side -->
</div>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>
    </body>
</html>
