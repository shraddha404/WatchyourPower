<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";
$main_menu = 'Admin Panel';
$current = 'event_log';
//$locations = $u->getLocationsFromCriteria($details);
//$loc_states = $u->getAllStatesFromLocations();
//$locations = $u->getAllLocations($_GET);
$devices = $u->getAllDevices(); 
//$errors=$u->getAllErrors(); 
//$errors=$u->getAllValidationParameterOptional(); 
$errors=$u->getAllValidationParameter(); 
	if($_POST['submit'] !='' && $_POST['export_data']=='show_event_log'){
		if( $_POST['location_id']!=''){
			$devices=$u->getDevicesByLocation($_POST['location_id']);
			foreach($devices as $device){
				$device_str="'".$device['device_id_string']."',";
			}
			$_GET['location_id']=$_POST['location_id'];
			$_GET['device_str']=rtrim($device_str,",");
		}
		if($_POST['event_id']!=''){
			$_GET['event_id']=$_POST['event_id'];
		}
		if($_POST['device_id']!=''){
			$_GET['device_id']=$_POST['device_id'];
		}
		if($_POST['from_date']!=''){
			$_GET['from_date']=$_POST['from_date'];
		}
		if($_POST['to_date']!=''){
			$_GET['to_date']=$_POST['to_date'];
		}

	}
	
if($_POST['submit']!='' && $_POST['export_data']=='voltage_data'  ){
if($_POST['from_date']!='' && $_POST['to_date'] !='' && $_POST['device_id']!=''){
echo "<script type=\"text/javascript\">";
echo "window.open(\"/raw_data.php?device_id=$_POST[device_id]&from_date=$_POST[from_date]&to_date=$_POST[to_date]\", '_blank');";
echo "</script>";
}else{
                $msg = "<span class=\"error\"> Please Select Device , From date and To date.</span>";
}
}
if($_POST['submit']!='' && $_POST['export_data']=='raw_file_data'  ){
if($_POST['from_date']!='' && $_POST['to_date'] !='' ){
echo "<script type=\"text/javascript\">";
echo "window.open(\"/raw_file_data.php?device_id=$_POST[device_id]&from_date=$_POST[from_date]&to_date=$_POST[to_date]\", '_blank');";
echo "</script>";
}else{
                $msg = "<span class=\"error\"> Please Select From date and To date.</span>";
}
}

if($_POST['submit']!='' && $_POST['export_data']=='summary'  ){
if($_POST['from_date']!='' && $_POST['to_date'] !='' ){
echo "<script type=\"text/javascript\">";
echo "window.open(\"/raw_data.php?device_id_string=$_POST[device_id]&data_type=$_POST[export_data]&from_date=$_POST[from_date]&to_date=$_POST[to_date]\", '_blank');";
echo "</script>";
}else{
                $msg = "<span class=\"error\"> Please Select From date and To date.</span>";
}
}

//$searched_events=$u->getEvents($_GET);
$searched_events=$u->getEventCountForDeviceAndError($_GET);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/html_head.php"; ?>
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/autocomplete_head.php"; ?>
<script type="text/javascript">
/*$(document).on("change","#state, #category_id", function(){
        var obj ={};
        obj.state = $('#state').val();
        obj.location_div_id = $('#location_id').attr('id');
        getLocations(obj,'location_id');
});*/

$(function() {
$( "#from_date" ).datetimepicker(
{formatTime:'H:i',
format:'d/m/Y H:i',
formatDate:'d/m/Y',
closeOnDateSelect:true
});

$( "#to_date" ).datetimepicker(
{formatTime:'H:i',
format:'d/m/Y H:i',
formatDate:'d/m/Y',
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
	<!--<form method="post" action="#" onsubmit="return compare_date_time('1');" id="event_log"> -->
	<form method="post" action="event_processing_log.php" id="event_log" onsubmit="return validateDate('1');"> 
	<table class="listing" cellpadding="0" cellspacing="0">
	<tr>
	<td align="center">
	<table class="inputlisting">
	<tr>
	<td>Event Code </td>
	<!--td><select name="event_id" id="event_id">
        	<option value="">-- Select  --</option>
       			<?php foreach($errors as $error){ ?>
       		<option value="<?php echo $error['id']; ?>" ><?php echo $error['err_str']; ?></option>
        		<?php } ?>
        	</select>
	</td-->
       <td><select name="event_id" id="event_id" >
        	<option value="">-- Select  --</option>
       			<?php foreach($errors as $error){ ?>
       		<option value="<?php echo $error['return_error']; ?>" <?php if($search_data['event_id'] ==$error['return_error']) {echo "selected";} ?> ><?php echo $error['desc']; ?></option>
        		<?php } ?>
        	</select>
	</td>
	</tr>

	<!--<tr>
	<td>State</td>
	<td><select name="state" id="state">
        		<option value="">-- Select  --</option>
		<?php foreach($loc_states as $l_state){ ?>
			<option value="<?php echo $l_state['state']; ?>" ><?php echo $l_state['state']; ?></option>
		<?php } ?>
		</select>
	</td>
	</tr>
	<tr>
	<td>Location</td>
	<td id="location_id"><select name="location_id" >
        		<option value="">-- Select  --</option>
		<?php foreach($locations as $location){ ?>
			<option value="<?php echo $location['id']; ?>" ><?php echo $location['name']; ?></option>
		<?php } ?>
		</select>
	</td>
	</tr>-->

	<tr>
	<td>Device ID</td>
	<td class="ui-widget"><select id="combobox" name="device_id" >
		<option value="">-- Select  --</option>
		<?php foreach($devices as $device){ ?>
		<option value="<?php echo $device['device_id_string']; ?>" ><?php echo $device['device_id_string']; ?></option>
		<?php } ?>
		</select>
        </td>
	</tr>

	<tr>
	<td>From</td>
	<td><input type="text" class="datepick" name="from_date" id="from_date" value="" ></td>
	<!--<td><input type="text" class="datepick" name="from_date" id="from_date" value="" onChange="validateDate($(this).val(), $('#to_date').val(),1);"></td>-->
	</tr>
	<tr>
	<td>To</td>
	<td><input type="text" class="datepick" name="to_date" id="to_date" value="" ></td>
	<!--<td><input type="text" class="datepick" name="to_date" id="to_date" value="" onChange="validateDate($('#from_date').val(), $(this).val(),1);"></td>-->
	</tr>
	
	<tr>
	<td><input type="radio" name="export_data" value="voltage_data" id="voltage_data"> Export voltage data</td>
	<td><input type="radio" name="export_data" value="raw_file_data" id="raw_file_data"> Export raw file data
	<input type="radio" name="export_data" value="summary" id="summary_data" checked> Export summary data 
	<input type="radio" name="export_data" value="show_event_log" id="event_log" checked> Show event log</td>
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
			if(count($searched_events) ==0 && $msg==''){
				echo "No Event Found !!";
			}else {
	?>
	<div class="table">
	<img src="/img/bg-th-left.gif" width="8" height="7" alt="" class="left" /> 
	<img src="/img/bg-th-right.gif" width="7" height="7" alt="" class="right" />

	<table class="listing" cellpadding="0" cellspacing="0">

	<tr>
	<th class="first" style="width:20%">Device Id</th>
	<th style="width:40% !important;">Event </th>
	<th style="width:10% !important;">Total Occurance</th>
	<!--<th class="last">Add</th>-->
	</tr>
	<?php foreach($searched_events as $event){?>
	<tr>
	
	<td><?php echo $event['device_id'];?></td>
	<td><?php echo $event['error'];?></td>
	<td><?php echo $event['cnt'];?></td>
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
