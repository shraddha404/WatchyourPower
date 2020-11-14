<?php
//include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";
?>
<!DOCTYPE html>
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/html_head.php"; ?>
<script type="text/javascript" src=""></script>
<script src="/amcharts/amcharts/amcharts.js" type="text/javascript"></script>
<script src="/amcharts/amcharts/pie.js" type="text/javascript"></script>
<script src="/amcharts/amcharts/serial.js" type="text/javascript"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDkqSg1ca_mdI0v-dvWPhd_zplQlrHi6ms"></script>
<script type="text/javascript">
	function initialize() {
		var mapOptions = {
		center: { lat: 18.562622, lng: 73.808723 },
		zoom:6 
		};
		var map = new google.maps.Map(document.getElementById('world-map'),
		mapOptions);
	}
	google.maps.event.addDomListener(window, 'load', initialize);
</script>
</head>
<body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
<header class="header">
	<a href="index.php" class="logo"><img src="/img/logo_new.png" /></a>
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
	<h1>Home</h1>
	<ol class="breadcrumb">
	<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
	<li class="active">Home</li>
	</ol>
</section>

<!-- Main content -->
<section class="content">


</section><!-- /.content -->
</aside><!-- /.right-side -->

</div><!-- ./wrapper -->


<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>

    </body>
</html>
