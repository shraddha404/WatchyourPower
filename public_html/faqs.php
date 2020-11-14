<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";

$current="about_esmi";
?>
<!DOCTYPE html>
<html>
<head>
<style>
.active{display:block;}
.inactive{display:none;}
</style>
<script>
function Show_Div(Div_id) {
    if (false == $(Div_id).is(':visible')) {
        $(Div_id).show(250);
    }
    else {
        $(Div_id).hide(250);
    }
}
</script>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/html_head.php"; ?>
</head>
<body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
               <?php include $_SERVER['DOCUMENT_ROOT']."/includes/header_banner.php"; ?>
<div class="wrapper row-offcanvas row-offcanvas-left">
               <?php include $_SERVER['DOCUMENT_ROOT']."/includes/left_menu.php"; ?>
<aside class="right-side" >
<section class="content-header">
	<h1>FAQ's</h1>
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
<!--p ><strong>About ESMI</strong><p ><strong>FAQ’s--SK</strong></p></p-->
	
<!--	<p  align="JUSTIFY">
<ul><li>About Electricity Supply Monitors</li>
<li>About Methodology</li>
<li>About Interruption Classification</li>
<li>About Voltage Classification</li>
<li>About Downloads</li>
<li>About Data availability and Sharing</li>
</ul></p>-->
<p ><strong>Electricity Supply Monitors</strong><p>
	<p  align="JUSTIFY"><ul><li><a href="#" onclick="Show_Div(Div_1)">What is an Electricity Supply Monitor?</a><div id="Div_1" class="inactive"><p>The electricity supply monitor is an electronic device as small as a handheld radio. It is a rugged unit that is used to measure voltage across electricity line. </p><p>The device measures and records voltage across a live electricity supply line for every minute and transmits the same over GPRS network to a central server. The device is designed to work as a plug and play unit and starts working as soon as it is plugged into a plug point. The device can only measure voltage supply and cannot be used to measure your electricity consumption.</p><p>The device contains simple, inexpensive components which consume very little power by themselves. In field testing’s it is observed to have consumed less than one unit of electricity per month.</p></div></li></ul></p>



<p ><strong>Methodology</strong><p>
	<p  align="JUSTIFY"><ul><li><a href="#" onclick="Show_Div(Div_2)">What is the method of data collection / recording?</a><div id="Div_2" class="inactive"><p>The initiative intends to collect data from different locations to get a broad sense of voltage and quality supplied to areas. To achieve this objective a set of ESM devices are installed at various locations spread across the country.   Each of these devices records minute by minute voltage at an accuracy of +/- 4 volts across a live electricity supply line and sends the recorded voltage to a central server. The devices are designed to operate within the supply range of 130-300 Volts, within which the device records voltage. In conditions of power outage the device compute zero voltage recordings and send it to the server. Thus the devices are capable to capture all interruptions in the electricity supply at a location.Type of consumer connection (Domestic, Non-domestic and Agricultural) are also documented and displayed on the website.</p></div></li>

<li><a href="#" onclick="Show_Div(Div_3)">How is the collected data represented?</a>
<div id="Div_3" class="inactive"><p>The website provides for all users to visualise voltage supply quality at different locations through pre-defined charts. On selecting a particular location on the map or through a search process the Reports get loaded. For each location it is possible to view four unique charts for any period of 31 days displaying the aggregate<b> i) electricity supply quality, ii) the interruptions profile, iii) availability of supply during evening hours and iv) minute by minute voltage profile across the selected time period.</b>  Each location report displays the type of consumer, category of location (state capital, District Headquarters, Other Municipal areas and gram panchayat), the name of location, the district and state where the data is being received from and voltage quality and quantity information for a selected duration of time. </p></div></li></ul></p>


<p ><strong>Interruption Classification</strong><p>
	<p  align="JUSTIFY"><ul><li><a href="#" onclick="Show_Div(Div_4)">What are the types of interruptions that are captured and how are they classified?</a><div id="Div_4" class="inactive"><p>Continuity in electricity supply is desired at all times to ensure no loss to daily and production activities. An interruption is a condition in which power supply is stopped due to various reasons and the voltage value during that time interval is zero. Interruptions can also be caused as a result of voltage dips, voltage unbalance and fluctuations most of which lead to flicker in lights, equipment failures, effecting productivity etc. </p>
<p>The ESM’s have the capability of capturing these interruptions in supply which are then used to determine the supply quantity at a location for the given time. These interruptions are further classified into Short and Long interruptions. A short interruption is classified as one which lasts between 2-15 minutes and 16 -60 minutes. While these interruptions are relatively short the losses in production activities can be important and also cause damage to other equipments.  A long interruption on the other hand is an interruption which can last for duration of 1-3 hours and more than 3 hours at any location. These interruptions, if frequent, force consumers to make investments in equipments to ensure continuity in supply. Any voltage below 130 V is treated as no-supply condition for calculating these interruptions as such low voltage is practically not useful and would in fact lead to safety issues. </p></div>
</li></ul></p>

<p ><strong>Voltage Classification</strong><p>
	<p  align="JUSTIFY"><ul><li><a href="#" onclick="Show_Div(Div_5)">What do the Low, Normal and High voltages in the graphs indicate?</a><div id="Div_5" class="inactive"><p>Normal electrical appliances in houses and commercial establishments, in line with relevant technical standards, are expected to operate smoothly within a broad voltage range of 205 to 270 V. Hence, this voltage range is marked as ‘Normal’. Voltage from 204 V and upto 131 V is shown as ‘Low’ voltage and voltage above 271 V is marked as ‘High’ voltage. Voltage below 131 (i.e. 130 and less) is considered as no supply (or zero voltage), as at such low voltage many appliances, tube lights etc. may not work and it might adversely affect safety of equipment and people. Agricultural consumers are typically supplied 3 phase supply, and in this case ESMI monitors and reports, 1 phase, phase to neutral voltage at these locations, with same classification as above.</p>
<p><b>A further technical Explanation</b> - Electricity suppliers are expected to ensure supply voltage at consumer end within a band. This ‘declared or rated’ voltage band is specified by respective state electricity regulatory commissions (SERC) in their Standards of Performance or other similar regulations. Typically, SERC’s have specified voltage range of 230 V + /- 6 % for single phase supply and 400 V phase to phase, + /- 6 % for three phase supply (this implies 230 V between phase and neutral). This translates into specified voltage level of 216 V to 244 V. (e.g. DERC, APERC) Some SERC’s also specify declared voltage level of 230 V or 240 V, with variation band of + /- 6 %. (MERC), implying approved voltage range of 226 V to 254 V. Current, BIS specified ‘rated’ / ‘nominal’ voltage for electrical installations is  240 V /415 V (IS 12360). But there is a move to align this standard to international IEC standard, which is 230 V / 400 V with allowed variation of + /- 10%, implying allowed voltage range of 207 V to 253 V for single phase supply or phase to neutral for a three phase supply.</p>


<p>Considering these regulations, accuracy of electricity supply monitoring devices and ESMI objective of primarily tracking supply quality for ordinary household, commercial and industrial establishments, we consider ‘normal’ voltage range as 205 V to 270 V, ‘low’ range from 204  V to 131 V, and ‘high’ range of above 271 V. Any voltage below 131 (i.e. 130 and below) is considered as no supply, as such low voltage is practically not usable and it might adversely affect safety of equipment and people.</p></div>
</li></ul></p>

<p ><strong>Data Availability and Sharing</strong><p>
	<p  align="JUSTIFY"><ul><li><a href="#" onclick="Show_Div(Div_6)">Is the data displayed on the website available for download?</a><div id="Div_6" class="inactive"><p>Yes, all data displayed on the website is available for download subject to the terms of use mentioned on the site. Any user can download the displayed information in a PDF as well as spreadsheet compatible format. 
For each location a user can view data for any 31 days at one time in the form of charts, data download for the same location can be extracted to up to 100 days through the Download Data menu. </p></div></li>

<li><a href="#" onclick="Show_Div(Div_7)">What if I want data for my research/ analysis?</a><div id="Div_7" class="inactive"><p>We welcome users to use all available data for their research/ analysis. </p></div>
</li></ul></p>
	</div>

</section><!-- /.content -->
</aside><!-- /.right-side -->

</div><!-- ./wrapper -->


<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>

    </body>
</html>
