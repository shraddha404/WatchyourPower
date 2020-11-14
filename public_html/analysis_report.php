<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";

$current="about_esmi";
?>
<!DOCTYPE html>
<html>
<head>
<style>
.inactive{
display:none;}

.active{
display:block;}
</style>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/html_head.php"; ?>
</head>
<body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
               <?php include $_SERVER['DOCUMENT_ROOT']."/includes/header_banner.php"; ?>
<div class="wrapper row-offcanvas row-offcanvas-left">
               <?php include $_SERVER['DOCUMENT_ROOT']."/includes/left_menu.php"; ?>
<aside class="right-side" >
<section class="content-header">
	<h1>Analysis Report</h1>
	<!--
	<ol class="breadcrumb">
	<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
	<li class="active">FAQ's</li>
	</ol>
	-->
</section>

<!-- Main content -->
<section class="content">
<header><!--<h1 class="article-title"> About ESMI</h1></header>
	                 Image -->

	                 <div class="article-content-main">
	<h4>Analysis Report</h4>
	</div>

</section><!-- /.content -->
</aside><!-- /.right-side -->

</div><!-- ./wrapper -->


<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>

    </body>
</html>
