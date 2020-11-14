<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";
$error_codes = $u->getAllErrors();
#$date=date('Y-m-d',strtotime("-1 days"));
$date=date('Y-m-d');
if($_POST['submit']=='Submit'){
$date = date('Y-m-d', get_strtotime($_POST['date']));
}
$action_logs = $u->getActionLog($date);
#print_r($action_logs);

$main_menu = 'Admin Panel';
$current = 'action_log';
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
});
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
	<h1> Action Log for <?php echo $date;?></h1>
	</div>
	<div class="table" align="center">
	<table class="listing" cellpadding="0" cellspacing="0" style="width:100%; align:center; float:left;">

	<tr>
	<!--<th class="first">Table name</th>-->
	<th>Date</th>
	<th>Operation Performed</th>
	<th class="last">Operation performed by</th>
	</tr>
	<?php foreach($action_logs as $action){?>
	<tr>
<!--	<td class="first style1"><?php echo $action['table_name'];?></td>-->
	<!--<td><?php echo date('d/m/Y',strtotime($action['created_on']));?></td>-->
	<td><?php echo $action['created_on'];?></td>
	<td><?php echo $action['operation'];?></td>
	<td><?php echo $action['username'];?></td>
	</tr>
	<?php }?>
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
