<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";

$main_menu = 'Management';
$current = 'process_events';
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
			$file_absent_err_id=$u->getErrorCodeIdFromErrStr('Device does not exist');
			$file_not_installed_err_id=$u->getErrorCodeIdFromErrStr('Device not installed');
			if($_POST['event_id'] == $file_absent_err_id || $_POST['event_id'] ==$file_absent_err_id){
				$_POST['event_id']=0;
			}
			$devices=array();
			$device_codes=array();
			$device_details=$u->getDeviceDetialsByDevice($_POST['device_id']);
			$devices[$device_details['device_id_string']]=$device_details;

			$device_codes[0]=$device_details['device_id_string'];
			$location_id=$device['location_id'];
			$data_cnt=0;
			$j=0;
			$dataid='';
			$voltage_readings='';
                        $_POST['location_id']=$device_details['location_id'];
			foreach($searched_events as $file){
				$data_cnt=0;
				$voltage_readings='';
				$file_name=explode(".",$file['filename']);
				$device_code= substr($file_name[0], 0, 7);
				$volt_data=$u->validateContentByLocation($file['filename'],$file['content'],$devices,$device_codes,$device_codes,$_POST['event_id']);

//$volt_data=$obj->validateContentByLocation($file['filename'],$file['content'],$installed_devices,$device_codes_array,$installed_codes_array,'0');
				if($volt_data['error']  =='0'){
				   if($file['content']!='' && $file['content']!=' ')
				      {
					$data=explode(",",$file['content']);
					$record_date=rtrim(chunk_split((substr($file_name[0],7,6)), 2, '-'),'-');
					$hr_of_day=substr($file_name[0],13,2);
					for($i=4; $i<64; $i++){
						$voltage=substr($data[$i],10,3);
						if($voltage < 110){
			                                $voltage=0;
                        			}
			                        if($voltage >350){
                        			        $voltage='';
			                        }
						$voltage_readings.=$voltage.',';
						$data_cnt++;
					}
					$voltage_readings=rtrim($voltage_readings,',');
					$voltage_data[$j]['day']=$record_date;
					$voltage_data[$j]['hour_of_day']=$hr_of_day;
					$voltage_data[$j]['readings']=$voltage_readings;
					$voltage_data[$j]['location_id']=$device_details['location_id'];
					$voltage_data[$j]['device_id']=$device_details['device_id'];
					$voltage_data[$j]['sim_card_id']=$device_details['sim_card_id'];
					$j++;		
					$dataid.=$file['datafile_id'].',';
          			      }
				}else{
					$u->updateDataError($file['id'],$error_code);
				}
			}

			if(!empty($voltage_data)){
				if($u->addVoltageReadings($voltage_data)){
					$u->markProcessed(rtrim($dataid,','));
                        		$log=$u->addevent_processing_log($_POST,$row_array);
				}
			}
			
			$searched_events=$u->getEvents($search_data);
		}
	   /*}else{
                $msg = "<span class=\"error\">This error can not be parsed.</span>";
	 }*/	
	}
	else if($_GET['oper']=='process'){
	   //if($_POST['event_id']!='' && !in_array($_POST['event_id'], $restricted_events)){
	   if($_GET['eid']!='' && !in_array($_GET['eid'], $restricted_events)){

		if( $_GET['location_id']!=''){
		$search_data=array();
			$devices=$u->getDevicesByLocation($_GET['location_id']);
			foreach($devices as $device){
				$device_str="'".$device['device_id_string']."',";
			}
			$search_data['location_id']=$_GET['location_id'];
			$search_data['device_str']=rtrim($device_str,",");
		}
		if($_GET['dev_id']!=''){
			$search_data['device_id']=$_GET['dev_id'];
		}
		if($_GET['fdate']!=''){
			$search_data['from_date']=$_GET['fdate'];
		}
		if($_GET['tdate']!=''){
			$search_data['to_date']=$_GET['tdate'];
		}
		if($_GET['eid']!=''){
			$search_data['event_id']=$_GET['eid'];
		}
			
	$device=$u->getDeviceDetialsByDevice($_GET['device_id']);	
	$devices=array();
	$device_codes=array();
	$devices[$device['device_id_string']]=$device;
	$device_codes[0]=$device['device_id_string'];
	$location_id=$device['location_id'];
	$file=$u->getDatafilesDetails($_GET['file_id']);
$_GET['from_date']=$_GET['fdate'];
			$_GET['to_date']=$_GET['tdate'];
                        $_GET['location_id']=$location_id;
	$file_name=explode(".",$file['filename']);
	$file_absent_err_id=$u->getErrorCodeIdFromErrStr('Device does not exist');
	$file_not_installed_err_id=$u->getErrorCodeIdFromErrStr('Device not installed');
	//if($_GET['error'] == $file_absent_err_id || $_POST['error'] ==$file_absent_err_id){
	if($_GET['error'] == $file_absent_err_id || $_GET['error'] ==$file_absent_err_id){
		$_GET['error']=0;
	}
	//$error_code=$u->validateContentByLocation($file['filename'],$file['content'],$devices,$device_codes,$_GET['error']);
	$volt_data=$u->validateContentByLocation($file['filename'],$file['content'],$devices,$device_codes,$device_codes,$_GET['error']);
	//$error_code=0;	
	if($volt_data['error']  =='0'){
                $data=explode(",",$file['content']);
                $record_date=rtrim(chunk_split((substr($file_name[0],7,6)), 2, '-'),'-');
                $hr_of_day=substr($file_name[0],13,2);
                for($i=4; $i<64; $i++){
                        $voltage=substr($data[$i],10,3);
			if($voltage < 110){
                              $voltage=0;
                  	}
			if($voltage >350){
                              $voltage=350;
			}
                        $voltage_readings.=$voltage.',';
                        $data_cnt++;
                }
                $voltage_readings=rtrim($voltage_readings,',');
                /*$voltage_data[0]['date']=$record_date;
                $voltage_data[0]['hr_of_day']=$hr_of_day;
                $voltage_data[0]['readings']=$voltage_readings;
                $voltage_data[0]['location_id']=$location_id;
                $voltage_data[0]['device_id']=$device['id'];
                $voltage_data[0]['sim_card_id']=$device['sim_card_id'];*/
                $volt_data[0]['date']=$record_date;
                $volt_data[0]['hr_of_day']=$hr_of_day;
                $volt_data[0]['readings']=$voltage_readings;
                $volt_data[0]['location_id']=$location_id;
                $volt_data[0]['device_id']=$device['id'];
                $volt_data[0]['sim_card_id']=$device['sim_card_id'];

        }else{
                $u->updateDataError($_GET['file_id'],$error_code);
        }

	if(!empty($volt_data)){
                if($u->addVoltageReadings($volt_data)){
                        $u->markProcessed($_GET['file_id']);
			$log=$u->addevent_processing_log($_GET);
                }
        }
		$searched_events=$u->getEvents($search_data);

	   }else{
                $msg = "<span class=\"error\">This error can not be parsed.</span>";
	 }	
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

<?php if($_POST['device_id']!='' && $_POST['event_id'] !='' && count($searched_events) >0 && !in_array($_POST['event_id'], $restricted_events)) {
        echo "<input type=\"submit\" name=\"submit\" value=\"Process\">";
}
?>
</td>
	</tr>
	
	</table>
</td></tr></table>
	</form>

	</div>
	<?php //echo $msg;
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
