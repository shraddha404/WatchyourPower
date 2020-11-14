<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";

$main_menu = 'Management';
$current = 'update_data_file';
//$locations = $u->getAllLocations($_GET);
$locations = $u->getLocationsFromCriteria($details);
$loc_states = $u->getAllStatesFromLocations();
$districts = $u->getDistrics();
$devices = $u->getAllDevices(); 
//$errors=$u->getAllValidationParameterOptional(); 
$errors=$u->getAllValidationParameter();
$voltage_fomr_err_id=$u->getErrorCodeIdFromErrStr('Voltage readings form not maching');
$invalid_file_err_id=$u->getErrorCodeIdFromErrStr('Invalid file format');
$file_name_err_id=$u->getErrorCodeIdFromErrStr('Invalid file name');
$restricted_events=array($voltage_fomr_err_id,$invalid_file_err_id,$file_name_err_id); 
//print_r($errors);
	if($_POST['submit'] !=''){
		
	  // if($_POST['event_id']!='' && !in_array($_POST['event_id'], $restricted_events)){
		if( $_POST['location_id']!=''){
		$search_data=array();
			$devices=$u->getDevicesByLocation($_POST['location_id']);
			foreach($devices as $device){
				$device_str="'".$device['device_id_string']."',";
			}
			$search_data['location_id']=$_POST['location_id'];
			$search_data['device_str']=rtrim($device_str,",");
		}
		if($_POST['device_id']!=''){
			$search_data['device_id']=$_POST['device_id'];
		}
		if($_POST['from_date']!=''){
			$search_data['from_date']=$_POST['from_date'];
		}
		if($_POST['to_date']!=''){
			$search_data['to_date']=$_POST['to_date'];
		}
		if($_POST['event_id']!=''){
			$search_data['event_id']=$_POST['event_id'];
		}

		$searched_events=$u->getEvents($search_data);
		$row_array = array();
		foreach($searched_events as $search_event){
			array_push($row_array,$search_event['datafile_id']);
		}

		if($_POST['submit']=='Process'){
		    foreach($searched_events as $event){	
			$new_content = array();
			$content='';
			$file_name=explode(".",$event['filename']);
			$validator=substr($file_name[0], 7);
			$data=explode(",", $event['content']);
			$is_data_found=0;
	                for ($i=4; $i<count($data); $i++){
				$reading_validator = substr($data[$i], 0, 8);
                	        if($validator == $reading_validator ){
					$is_data_found=$i;
        	                        break;
				}
               	 	}
			if($is_data_found > 4){
				$new_content[0] = $data[0];
				$new_content[1] = 'FXS1';
				$new_content[2] = 'Lat';
				$new_content[3] = 'Long';
				$k=$is_data_found;
				//for($j=4;$j<count($data);$j++){
				for($j=4;$j<72;$j++){
					$new_content[$j]=rtrim($data[$k]," ");
				$k++;
				}
				$content = implode(",",$new_content);	
			if($u->updateDatafilesContent($event['datafile_id'],$content)){
				$msg = "<span class=\"message\">Raw files updated successfully.</span>";
			}else{
				$msg = "<span class=\"error\">Some error occured while updating raw data files.</span>";
			}
			}else{
				//$msg = "<span class=\"error\">.</span>";

			}
			

		    }

		}
	   /*}else{
                $msg = "<span class=\"error\">This error can not be parsed.</span>";
	 }*/	
	}
	else if($_GET['oper']=='process'){
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/html_head.php"; ?>
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/autocomplete_head.php"; ?>
<script>
$(document).on("change","#state, #category_id", function(){
        var obj ={};
        obj.state = $('#state').val();
        obj.district = $('#district').val();
        obj.district_div_id = $('#district').attr('id');
        obj.location_div_id = $('#location_id').attr('id');
        if(obj.state!=''){
        getDistricts(obj,'dist');
        }
        getLocations(obj,'location_id');
});
$(document).on("change","#district", function(){
        var obj ={};
        obj.state = $('#state').val();
        obj.district = $('#district').val();
        obj.location_div_id = $('#location_id').attr('id');
        getLocations(obj,'location_id');
});

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
	<form method="post" action="#" onsubmit="return validateDate('1');"> 
	<table class="listing" cellpadding="0" cellspacing="0">
	<tr>	<input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id'];?>">
	<td align="center">
	<table class="inputlisting">
	<tr>
	<td>Event Code *</td>
	<td><select name="event_id" id="event_id" required>
        	<option value="">-- Select  --</option>
       			<?php foreach($errors as $error){ ?>
       		<option value="<?php echo $error['return_error']; ?>" <?php if($search_data['event_id'] ==$error['return_error']) {echo "selected";} ?> ><?php echo $error['desc']; ?></option>
        		<?php } ?>
        	</select>
	</td>
	</tr>
	<tr>
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
        <td>District</td>
        <td id="dist"> <select id="district" name="district" >
                        <option value="">-- Select  District --</option>
                        <?php foreach($districts as $dist){ ?>
                        <option value="<?php echo $dist['district']; ?>" <?php if($is_update && $device_installation['district'] == $dist['district']){ echo 'selected'; }?>><?php echo $dist['district']; ?></option>
                        <?php } ?>
                        </select>
                </td>
        </tr>

	<tr>
	<td>Location</td>
	<td id="location_id"><select name="location_id" >
        		<option value="">-- Select  --</option>
		<?php foreach($locations as $location){ ?>
			<option value="<?php echo $location['id']; ?>"  <?php if($search_data['location_id'] == $location['id']) {echo "selected";} ?> ><?php echo $location['name']; ?></option>
		<?php } ?>
		</select>
	</td>
	</tr>

	<tr>
	<td>Device ID *</td>
	<td class="ui-widget"><select id="combobox" name="device_id" required >
		<option value="">-- Select  --</option>
		<?php foreach($devices as $device){ ?>
		<option value="<?php echo $device['device_id_string']; ?>"  <?php if($search_data['device_id'] ==$device['device_id_string']) {echo "selected";} ?> ><?php echo $device['device_id_string']; ?></option>
		<?php } ?>
		</select>
        </td>
	</tr>

	<tr>
	<td>From</td>
	<td><input type="text" class="datepick" name="from_date" id="from_date" value="<?php if($search_data['from_date'] !='') {echo $search_data['from_date'];} ?>" ></td>
	<!--<td><input type="text" class="datepick" name="from_date" id="from_date" value="<?php if($search_data['from_date'] !='') {echo $search_data['from_date'];} ?>" onChange="validateDate($(this).val(), $('#to_date').val());"></td>-->
	</tr>
	<tr>
	<td>To</td>
	<td><input type="text" class="datepick" name="to_date" id="to_date" value="<?php if($search_data['to_date'] !='') {echo $search_data['to_date'];} ?>" ></td>
	<!--<td><input type="text" class="datepick" name="to_date" id="to_date" value="<?php if($search_data['to_date'] !='') {echo $search_data['to_date'];} ?>" onChange="validateDate($('#from_date').val(), $(this).val());"></td>-->
	</tr>


	<tr>
	<td></td>
	<td><input type="submit" name="submit" value="Submit">

<?php if($_POST['device_id']!='' && $_POST['event_id'] !='' && count($searched_events) >0 ) {
        echo "<input type=\"submit\" name=\"submit\" value=\"Process\">";
}
?>
</td>
	</tr>
	
	</table>
</td></tr></table>
	</form>

	</div>
	<?php echo $msg;
	if($_POST['submit']!=''){
	if(count($searched_events) >0){
	?>
	
	<div class="table" align="center">
	<table class="listing" cellpadding="0" cellspacing="0" style="width:100%; align:center;">
	<tr>
	<th class="first" >Date</th>
	<th >Device ID</th>
	<th >Event Code</th>
	<th >Details</th>
	<th >Check File</th>
	<th class="last"> Action</th>
	</tr>
	<?php foreach($searched_events as $event){?>
	<tr>
	
	<td class="first"> <?php echo $event['event_date'];?></td>
	<td><?php echo $event['device_id'];?></td>
	<td><?php echo $event['err_str'];?></td>
	<td>--</td>
	<td style="width:auto;"><a href="/admin/read_file.php?file=<?php echo $event['filename']; ?>" target="_blank">Check File</a></td>
	<td class="last"><?php if(!in_array($_POST['event_id'], $restricted_events)) { ?><a href="event_processing.php?oper=process&error=<?php echo $event['event_code']."&device_id=".$event['device_id']."&filename=".$event['filename']."&file_id=".$event['datafile_id']."&location_id=".$_POST['location_id']."&dev_id=".$_POST['device_id']."&user_id=".$_POST['user_id']."&fdate=".$_POST['from_date']."&tdate=".$_POST['to_date']."&eid=".$_POST['event_id'];?>">Process</a><?php } ?></td>
	</tr>
	<?php }?>
	</table>
	</div>
<?php }  else if($msg !=''){echo $msg; } else{ echo "No Data Found..!"; }}?>
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
