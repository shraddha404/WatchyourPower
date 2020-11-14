<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";

//if($_SESSION['user_type']!='Admin'){
//header('Location:/index.php');
//}
$error_codes = $u->getAllErrors();
$date=date('Y-m-d',strtotime("-1 days"));
if($_POST['submit']=='Submit'){
$date = date('Y-m-d', get_strtotime($_POST['date']));
}
$daily_progress = $u->getDailyProgressLog($date);
$error_codes_count = $u->getErrorCodesCountByToday($date,$error_codes);


$main_menu = 'Admin Panel';
$current = 'daily_progress_log';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/html_head.php"; ?>
<script>
$(function() {
$( "#date" ).datetimepicker(
{
format:'d/m/Y',
formatDate:'d/m/Y',
timepicker:false,
closeOnDateSelect:true
}
);
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
	<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/bredcrumb.php"; ?>
	
	<div class="table">
        <form method="post" action="">
        <table class="listing" cellpadding="0" cellspacing="0">
        <tr>
        <td align="center">
        <table class="inputlisting">
	<tr>
        <td>Select Date</td>
        <td><input type="text" name="date" id="date" value="" class="datepick"></td>
        </tr>
	<tr>
        <td>&nbsp;</td>
        <td><input type="submit" name="submit" value="Submit"></td>
        </tr>

	</table></td></tr>
	</table>
	</form>	
	</div>
	<div class="table" style="margin:0 0 4px !important;">
	<h1> Daily Progress Log for <?php echo $date;?></h1>
	</div>
	<div class="table" align="center">
	<?php if(!empty($daily_progress)){?>
	<table class="listing" cellpadding="0" cellspacing="0" style="width:45%; align:center; float:left;">

	<tr>
	<th class="first">Title</th>
	<th>Todays</th>
	<th class="last">Previous</th>
	</tr>
	<tr>
	<td style="width:auto;">Files Received</td>
	<td style="width:auto;"><?php echo $daily_progress['today_files'];?></td>
	<td style="width:auto;"><?php echo $daily_progress['all_files'];?></td>
	</tr>
	<tr>
	<td>Files Validated</td>
	<td><?php echo $daily_progress['processed_today'];?></td>
	<td><?php echo $daily_progress['all_processed_files'];?></td>
	</tr>
	<tr>
	<td>Files Not Validated</td>
	<td><?php echo $daily_progress['todays_invalid_files'];?></td>
	<td><?php echo $daily_progress['all_invalid_files'];?></td>
	</tr>
	<tr>
	<td>Files Not Received</td>
	<td><?php echo $daily_progress['todays_files_not_received'];?></td>
	<td><?php echo $daily_progress['all_files_not_received'];?></td>
	</tr>
	<tr>
	<td>Device Not Reporting</td>
	<td><?php echo $daily_progress['todays_devices_not_reporting'];?></td>
	<td><?php echo $daily_progress['all_devices_not_reporting'];?></td>
	</tr>
	<tr>
	<!--<td>Web Requests Processed</td>
	<td><?php echo $daily_progress['todays_unprocessed_requests'];?></td>
	<td><?php echo $daily_progress['all_unprocessed_requests'];?></td>
	</tr>-->
	<tr>
	<td>Unprocessed Requests</td>
	<td><?php echo $daily_progress['todays_unprocessed_requests'];?></td>
	<td><?php echo $daily_progress['all_unprocessed_requests'];?></td>
	</tr>
	<tr>
	<td>New Users Added</td>
	<td><?php echo $daily_progress['todays_users'];?></td>
	<td><?php echo $daily_progress['all_users'];?></td>
	</tr>
	</table>
	<?php } else{ echo "No data found for daily progress log"; }
	//	if(!empty($error_codes_count)){
	?>
	
        <table class="listing" cellpadding="0" cellspacing="0" style="width:45%; align:center; float:right;">

        <tr>
        <th class="first">Error Code</th>
        <th class="last">Count</th>
        </tr>
	
	<?php foreach($error_codes_count as $key=>$value){  ?>
        <tr>
        <td style="width:auto;"><?php echo $key;?></td>
        <td style="width:auto;"><?php echo $value; ?></td>
        </tr>
	<?php }?>
	</table>
	<?php// }?>
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
