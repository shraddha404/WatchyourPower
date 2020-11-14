<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";
$devices = $u->getAllDevices();
$active_devices = $u->getActiveDeviceCount();
$inactive_devices = $u->getInactiveDeviceCount();
$deployed_devices = $u->getDeployedDeviceCount();
$testing_devices = $u->getTestingDeviceCount();
$active_users = $u->getActiveUsersCount();
$inactive_users = $u->getInactiveUsersCount();
$unprocessed_events=$u->unprocessed_events();
$main_menu = 'Admin Panel';
$current = 'home';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/html_head.php"; ?>
<script>
$(function() {
$( "#installed" ).datepicker();
$("#deployed").datepicker();
});
</script>
</head>
<body>
<div id="main">

<div id="header"> 
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/menu.php"; ?>

</div>

<div id="middle">

<div id="left-column">
	<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/left_nav.php"; ?>
</div>

<div id="center-column">
	<div class="table" >
        <table class="listing" cellpadding="0" cellspacing="0" style="width:45% !important; float:left; padding-right:10px;">
	<tr><td style="border:none;"><strong>Device Status </strong></td></tr>
        <tr><td style="border:none;"><img src="/img/active_device.png" />&nbsp;&nbsp;<strong>Active Devices:&nbsp;&nbsp;<?php echo $active_devices;?></strong></td></tr>
        <tr><td style="border:none;"><img src="/img/inactive_device.png"/>&nbsp;&nbsp;<strong>Inactive Devices:&nbsp;&nbsp;<?php echo $inactive_devices;?></strong></td></tr>
        <tr><td style="border:none;"><img src="/img/deployed_device.png"/>&nbsp;&nbsp;<strong>Deployed Devices:&nbsp;&nbsp;<?php echo $deployed_devices;?></strong></td></tr>
        <tr><td style="border:none;"><img src="/img/testing_device.png" />&nbsp;&nbsp;<strong>Testing Devices:&nbsp;&nbsp;<?php echo $testing_devices;?></strong></td></tr>
        </table>

        <table class="listing" cellpadding="0" cellspacing="0" style="width:45% !important; float:right;">
	<tr><td style="border:none;"><strong>Web Status </strong></td></tr>
        <tr><td style="border:none;">&nbsp;&nbsp;<strong>Registered Web Users:&nbsp;&nbsp;<?php echo $active_users;?></strong></td></tr>
        <tr><td style="border:none;">&nbsp;&nbsp;<strong>Authentication Pending:&nbsp;&nbsp;<?php echo $inactive_users;?></strong></td></tr>
        <tr><td style="border:none;">&nbsp;&nbsp;<strong>Unprocessed Requests:&nbsp;&nbsp;<?php echo "0";?></strong></td></tr>
        <tr><td style="border:none;">&nbsp;&nbsp;<strong>Unique Visitors:&nbsp;&nbsp;<?php echo "0";?></strong></td></tr>
        </table>
	</div>
	
	<div class="table" >
        <table class="listing" cellpadding="0" cellspacing="0" style="width:45% !important; float:left; padding-right:10px;">
	<tr><td style="border:none;"><strong>Events </strong></td></tr>
        <tr><td style="border:none;">&nbsp;&nbsp;<strong>Unprocessed Error Events:&nbsp;&nbsp;<?php echo $unprocessed_events;?></strong></td></tr>
        <!--<tr><td style="border:none;">&nbsp;&nbsp;<strong>Successful Script Runs:&nbsp;&nbsp;<?php echo $inactive_devices;?></strong></td></tr>-->
        <tr><td style="border:none;">&nbsp;&nbsp;<strong>Summary Generated:&nbsp;&nbsp;<?php echo $testing_devices;?></strong></td></tr>
        </table>

        <table class="listing" cellpadding="0" cellspacing="0" style="width:45% !important; float:right;">
	<tr><td style="border:none;"><strong>Alerts </strong></td></tr>
        <tr><td style="border:none;">&nbsp;&nbsp;<strong>Active Devices:&nbsp;&nbsp;<?php echo $active_devices;?></strong></td></tr>
        <!--<tr><td style="border:none;">&nbsp;&nbsp;<strong>Inactive Devices:&nbsp;&nbsp;<?php ?></strong></td></tr>
        <tr><td style="border:none;">&nbsp;&nbsp;<strong>Testing Devices:&nbsp;&nbsp;<?php ?></strong></td></tr>-->
        </table>
	</div>
	<div class="table">

      </div> <!-- end table class div -->

</div> <!-- end div center column  -->

<!--<div id="right-column">
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/right_nav.php"; ?>
</div>-->

</div>
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</div>

<!-- <div align=center>This template  downloaded form <a href='#'>free website templates</a></div> -->

</body>
</html>
