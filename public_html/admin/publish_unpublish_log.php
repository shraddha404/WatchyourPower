<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";

$main_menu = 'Admin Panel';
$current = 'publish_unpublish_log';
//$locations = $u->getLocationsFromCriteria($details);
//$loc_states = $u->getAllStatesFromLocations();
$locations = $u->getAllLocations($_GET);
$devices = $u->getAllDevices(); 
//$errors=$u->getAllErrors(); 
//$errors=$u->getAllValidationParameterOptional(); 
if($_POST['submit']=='Submit'){

$location_id=$_POST['location_id'];
}
$searched_events=$u->getPublish_Unpublish_Log($location_id);
//print_r($searched_events);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/html_head.php"; ?>
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/autocomplete_head.php"; ?>
<script type="text/javascript">
$(function() {
$( "#from_date" ).datetimepicker(
{formatTime:'H:i',
format:'d/m/Y H:i',
formatDate:'d/m/Y',
closeOnDateSelect:true
}
);
$( "#to_date" ).datetimepicker(
{formatTime:'H:i',
format:'d/m/Y H:i',
formatDate:'d/m/Y',
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
	<!--<form method="post" action="#" onsubmit="return compare_date_time('1');" id="event_log"> -->
	<form method="post" action="publish_unpublish_log.php" id="event_log" onsubmit="return validateDate('1');"> 
	<table class="listing" cellpadding="0" cellspacing="0">
	<tr>
	<td align="center">
	<table class="inputlisting">
	<tr>

	<td>Location</td>
	<td id="location_id"><select name="location_id" >
        		<option value="">-- Select  --</option>
		<?php foreach($locations as $location){ ?>

			<option value="<?php echo $location['id']; ?>" ><?php echo $location['name']; ?></option>
		<?php } ?>
		</select>
	</td>

	</tr>

	<tr>
	<td>From</td>
	<td><input type="text" class="datepick" name="from_date" id="from_date" value="<?php echo $_POST['from_date']; ?>" ></td>
	<!--<td><input type="text" class="datepick" name="from_date" id="from_date" value="" onChange="validateDate($(this).val(), $('#to_date').val(),1);"></td>-->
	</tr>
	<tr>
	<td>To</td>
	<td><input type="text" class="datepick" name="to_date" id="to_date" value="<?php echo $_POST['to_date']; ?>" ></td>
	<!--<td><input type="text" class="datepick" name="to_date" id="to_date" value="" onChange="validateDate($('#from_date').val(), $(this).val(),1);"></td>-->
	</tr>
	
	
	<tr>
	<td></td>
	<td><input type="submit" name="submit" value="Submit"></td>
	</tr>
	
	</table>
</td></tr></table>
	</form>

	</div>
	<?php echo $msg;?>
	<?php
			if(count($searched_events) ==0){
				echo "No Event Found !!";
			}else {
	?>
		<span style="float:right; margin-bottom:10px; font-weight:bold; font-color:#43729f;"><a href="export_publish_unpublish_log.php">Export All</a></span><div class="table">
	<img src="/img/bg-th-left.gif" width="8" height="7" alt="" class="left" /> 
	<img src="/img/bg-th-right.gif" width="7" height="7" alt="" class="right" />

	<table class="listing" cellpadding="0" cellspacing="0">

	<tr>
	<th class="first" style="width:20% !important;">From Date</th>
	<th style="width:20% !important;">To Date</th>
	<th style="width:20% !important;">Created By</th>
	<th style="width:20% !important;">Location Name</th>
	<th style="width:20% !important;">Type</th>
	<!--<th class="last">Add</th>-->
	</tr>
	<?php foreach($searched_events as $event){ ?>
	<tr>
	
	<td><?php echo $event['from_date'];?></td>	
          <td><?php echo $event['to_date'];?></td>
	<td><?php $user=$u->getUserDetails($event['created_by']);echo $user['name'];?></td>
	<td><?php echo $event['location_name'];?></td>
	<td><?php echo $event['type'];?></td>

	<!--<td class="last"><img src="/img/add-icon.gif" width="16" height="16" alt="add" /></td>-->
	</tr>
	<?php }?>
	</table>
	</div>
	<?php  } ?>
	

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
