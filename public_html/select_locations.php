<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
$locations = $u->getAllLocations(array('restore_all'=>1));
#print_r($locations);

$details= $_GET;
#print_r($details);
if($_GET){
$report_data = $u->getLocationSummaryReport($details);
}
?>
<!DOCTYPE html>
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/html_head.php"; ?>
<script type="text/javascript">
         var chartData = <?php echo json_encode($report_data); ?>;
        AmCharts.ready(function () {
                 createPieChart(chartData,'param','value');
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
<header class="header">
	<a href="index.php" class="logo"><img src="/img/logo.png" /></a>
	<nav class="navbar navbar-static-top" role="navigation">
	<a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
	<span class="sr-only">Toggle navigation</span>
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
	</a>
	</nav>
</header>

<div class="wrapper row-offcanvas row-offcanvas-left">
<aside class="left-side sidebar-offcanvas">
               <?php include $_SERVER['DOCUMENT_ROOT']."/includes/left_menu.php"; ?>
</aside>
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
<table style="padding:20px;" width="100%">
	<tr>
	<th style="padding:3px !important; padding-right:20px !important;">Category</th>
	<th>State</th>
	<th>District</th>
	<th>City</th>
	</tr>
	<tr>
		<td style="padding-right:20px !important;">
			<select class="form-control" id="category_id" name="category" onchange="this.form.submit();">
			<option value="">-- Select  --</option>
			<?php foreach($locations as $loc){ ?>
			<option value="<?php echo $loc['id']; ?>" <?php if($details['location_id']== $loc['id']){ echo 'selected';}?>><?php echo $loc['name']; ?></option>
			<?php } ?>
			</select>
		</td>
		<td style="padding-right:20px !important;">
                        <select class="form-control" id="state_id" name="state" onchange="this.form.submit();">
                        <option value="">-- Select  --</option>
                        <?php foreach($locations as $loc){ ?>
                        <option value="<?php echo $loc['id']; ?>" <?php if($details['location_id']== $loc['id']){ echo 'selected';}?>><?php echo $loc['name']; ?></option>
                        <?php } ?>
                        </select>
                </td>
		<td style="padding-right:20px !important;">
                        <select class="form-control" id="district_id" name="district" onchange="this.form.submit();">
                        <option value="">-- Select  --</option>
                        <?php foreach($locations as $loc){ ?>
                        <option value="<?php echo $loc['id']; ?>" <?php if($details['location_id']== $loc['id']){ echo 'selected';}?>><?php echo $loc['name']; ?></option>
                        <?php } ?>
                        </select>
                </td>
		<td style="padding-right:20px !important;">
                        <select class="form-control" id="district_id" name="district" onchange="this.form.submit();">
                        <option value="">-- Select  --</option>
                        <?php foreach($locations as $loc){ ?>
                        <option value="<?php echo $loc['id']; ?>" <?php if($details['location_id']== $loc['id']){ echo 'selected';}?>><?php echo $loc['name']; ?></option>
                        <?php } ?>
                        </select>
                </td>
	</tr>
</table>

<div id="chartdiv" style="width:100%; height:400px;"></div>

</section><!-- /.content -->
</aside><!-- /.right-side -->

</div><!-- ./wrapper -->


<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>

    </body>
</html>
