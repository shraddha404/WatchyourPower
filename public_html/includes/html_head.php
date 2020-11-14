	<meta charset="UTF-8">
	<?php if($current=='reports'){?>
        <title>Prayas-ESMI Reports<?php if(!empty($details['location_id'])){ echo " - $location_name, $district, $state for $details[from_date] to $details[to_date]. "; }?></title>
	<?php } else { ?>
        <title>Prayas-ESMI</title>
	<?php }?>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<link rel="icon" type="image/ico" href="/img/favicon.ico" />
        <link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="/css/ionicons.min.css" rel="stylesheet" type="text/css" />
	<!-- Morris chart -->
        <link href="/css/morris/morris.css" rel="stylesheet" type="text/css" />
        <!-- jvectormap -->
        <link href="/css/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
        <link href="/css/datepicker3.css" rel="stylesheet" type="text/css" />
        <link href="/css/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
        <!-- bootstrap wysihtml5 - text editor -->
        <link href="/css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
<link type='text/css' href='/css/basic.css' rel='stylesheet' media='screen' />
        <!-- Theme style -->
        <link href="/css/AdminLTE.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="/js/jquery-1.11.1/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="/js/datetimepicker/jquery.datetimepicker.css"/>
	<script type='text/javascript' src='/js/gs_sortable.js'></script>
	<script src="/js/jquery-1.11.1/jquery-1.11.1.min.js" type='text/javascript'></script>
	<script src="/js/jquery-1.11.1/jquery-ui.min.js" type='text/javascript'></script>
	<script src="/js/jquery.tablesorter.min.js"></script>
	<!--<script src="/js/d3/d3.js"></script>-->
	<script src="/js/datetimepicker/jquery.datetimepicker.js"></script>
	<script src="/js/basic.js"></script>

<script type='text/javascript' src='/js/jquery.simplemodal.js'></script>
<script type='text/javascript' src='/js/jquery.js'></script>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
<script src="/amcharts/amcharts/amcharts.js" type="text/javascript"></script>
<script src="/amcharts/amcharts/pie.js" type="text/javascript"></script>
<script src="/amcharts/amcharts/serial.js" type="text/javascript"></script>
<script src="/amcharts/amcharts/themes/patterns.js" type="text/javascript"></script>
<script src="/js/function.js" type="text/javascript"></script>
