<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";

$current="esmi_beyond_india";
?>
<!DOCTYPE html>
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/html_head.php"; ?>
</head>
<body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
               <?php include $_SERVER['DOCUMENT_ROOT']."/includes/header_banner.php"; ?>
<div class="wrapper row-offcanvas row-offcanvas-left">
               <?php include $_SERVER['DOCUMENT_ROOT']."/includes/left_menu.php"; ?>
<aside class="right-side" >
<section class="content-header">
	<h1> Minute-wise data</h1>
</section>

<!-- Main content -->
<section class="content">
<header><!--<h1 class="article-title"> About ESMI</h1></header>
	                 Image -->
	<div class="article-content-main">
		<p  align="JUSTIFY">
ESMI is developing a reliable database on supply interruptions and voltage levels at consumer locations in India. The minute-wise data collected by ESMI is available in the public domain, and can be downloaded from the <a href="https://dataverse.harvard.edu/dataverse/esmi">Harvard dataverse</a> for research and advocacy purposes. The data uploaded in the dataverse has been collected from ESMI installations in India over the years, since 2014. All the interruptions reported have been verified to the best possible extent. A few pointers to consider before using the data</p>
<ul>
<li>The time period for the data is from November 2014 to December 2018 (would be updated from time to time)</li>
<li>The data for a location may not be continuous across the years</li>
<li>A '0' voltage reading means a power outage</li>
<li>Missing data should be interpreted as unavailable data and not as interruption</li>
<li>Certain locations have been marked as [Offline], which indicates that the monitoring at the locations has been discontinued</li>
</ul>
<h4> Terms of use</h4>
<p align="JUSTIFY">The information made available on the website and through ESMI is made available on ‘as is where is’ basis. Though reasonable care is taken to provide reliable data, neither Prayas, nor any of its agents, contractors, employees can be held responsible for use of any data or information made available herein. Anybody is welcome to make full use of the information available herein for any non-commercial, academic and research purpose, provided the source of the information is clearly acknowledged. We will highly appreciate it if we are intimated of the use of this data and a copy of the publication, paper or report using this data is shared with us at <a href="mailto:esmi@prayaspune.org">esmi@prayaspune.org</a>.</p>
	</div>

</section><!-- /.content -->
</aside><!-- /.right-side -->

</div><!-- ./wrapper -->


<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>

    </body>
</html>
