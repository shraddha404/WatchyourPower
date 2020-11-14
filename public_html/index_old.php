<?php 
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
$locations = $u->getDeviceInstalledLocations(array('restore_all'=>1));
$from_date = date('d/m/Y',strtotime('-7 day'));
$to_date = date('d/m/Y',strtotime('-1 day'));
$details = array('location_id'=>'30', 'from_date'=>$from_date, 'to_date'=>$to_date);
$report_data = $u->getLocationSummaryReport($details,30);
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
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDkqSg1ca_mdI0v-dvWPhd_zplQlrHi6ms"></script>
<script type="text/javascript" src="/js/markerclusterer.js"></script>
<script type="text/javascript">
var locations = 
	[
		<?php foreach($locations as $loc){ ?>
		['<?php echo sanitizeInput($loc['name']); ?>', <?php echo $loc['latitude']?>, <?php echo $loc['longitude']; ?>,<?php echo $loc['id']?>, '<?php echo sanitizeInput($loc['category']). ', '.sanitizeInput($loc['connection_type']); ?>'],
		<?php } ?>
	];

	google.maps.event.addDomListener(window, 'load', function(){initialize(locations);});
/*
	 var chartData = [
                {
                    "country": "Lithuania",
                    "value": 260
                },
                {
                    "country": "Ireland",
                    "value": 201
                },
                {
                    "country": "Germany",
                    "value": 65
                },
                {
                    "country": "Australia",
                    "value": 39
                }];
*/
	var chartData = <?php echo json_encode($report_data); ?>;
        AmCharts.ready(function () {
		 createPieChart(chartData,'param','value','chartdiv');
        });
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

<!-- Small boxes (Stat box)-->
<div class="row">
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/header.php"; ?>
</div> <!--/.row -->

                    <!-- Main row -->
<div class="row">

<!-- Left col -->
<section class="col-lg-7 connectedSortable">                            
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/map_section.php"; ?>
<p>We would love to hear from you! What can we do to improve your ESMI experience ? Please write to <a href="mailto:esmi@prayaspune.org">esmi@prayaspune.org</a> or fill in <a href="contact_us.php">this form.</a></p></section><!-- /.Left col -->

                        <!-- right col (We are only adding the ID to make the widgets sortable)-->
<section class="col-lg-5 connectedSortable ui-sortable"> 
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/report_section.php"; ?>
</section><!-- right col -->

</div><!-- /.row (main row) -->

</section><!-- /.content -->
</aside><!-- /.right-side -->
</div>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>
    </body>
</html>
