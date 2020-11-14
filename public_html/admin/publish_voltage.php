<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";

if($_POST['submit'] == 'Save'){
$details = $_POST ;
	if($details['is_publish'] == 'publish'){
		$u->unPublishVoltage($details);
		$voltages = $u->getVoltagesToPublish($details,0);
		$summary_data=array();
		$i=0;
		$j=0;
		$voltage_ids='';
	   if(count($voltages) > 0){
		foreach($voltages as $voltage){
			$voltagedate=$voltage['day']." ".$voltage['hour_of_day'].":00:00";
			$summary=$u->generateVoltageSummary($voltage['readings'],$voltage['day'],$voltage['hour_of_day'],$voltage['location_id']);
			$summary_data[$i][0]=$summary['data'];
			if(count($summary['interrupt']) >0){
				$interrupts[$j]=$summary['interrupt'];
				$j++;
			}
			$summary_data[$i][1]=array('location_id' =>$voltage['location_id'], 'hour_of_day' => $voltagedate);
			$voltage_ids .=$voltage['id'].",";
			$i++;
		}
		if(!empty($summary_data)){
			if($u->addSummaryData($summary_data)){
				if(count($interrupts)>0){
					$u->addIntrrupts($interrupts);
			}
			$u->markVoltagePublished(rtrim($voltage_ids,","));

			}
			$u->addPublishUnpublish($details); 
			if($details['log_id']!=''){
				$u->updatePublishUnpublish($details);
			}
			$msg = "<span class=\"message\">Data published successfully.</span>";
		}else{
			$msg = "<span class=\"message\">No Data Available to publish.</span>";
		}
	     }else{
			$msg = "<span class=\"message\">No Data Available to publish.</span>";
		}
	}else if($details['is_publish'] == 'unpublish'){
		 $voltages = $u->getVoltagesToPublish($details,1);
		if(count($voltages) >0){
		if($u->unPublishVoltage($details)){


                 $voltage_ids='';
                foreach($voltages as $voltage){
                        $voltage_ids .=$voltage['id'].",";
                }
		$u->markVoltageUnPublished(rtrim($voltage_ids,","));

			$u->addPublishUnpublish($details); 
			if($details['log_id']!=''){
				$u->updatePublishUnpublish($details);
			}
			$msg = "<span class=\"message\">Data Unpublished successfully.</span>";
		}
	     }else{
			$msg = "<span class=\"message\">No Data Available to un-publish.</span>";
		}

	}
}else if($_POST['show']=='Show Data'){
echo"<script type=\"text/javascript\">";
echo "window.open(\"$_SERVER[DOCUMENT_ROORT]/raw_data.php?location_id=$_POST[location_id]&from_date=$_POST[from_date]&to_date=$_POST[to_date]\", '_blank');";
echo "</script>";
$data=$_POST;
$_GET['location_id']=$data['location_id'];

}


$details = $_GET;
//$locations = $u->getAllLocations($details);
$locations = $u->getLocationsFromCriteria($details);
$loc_states = $u->getAllStatesFromLocations();
$districts = $u->getDistrics();
$publish_unpublish_log = $u->getPublishUnpublishLog($_GET); 
$statuses = array('publish'=>'Publish', 'unpublish'=>'Unpublish');
$main_menu = 'Management';
$current = 'publish_unpublish';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/html_head.php"; ?>
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

/*$('#to_date').change(function () {
    alert($('#to_date').val());
});*/
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

	<form method="post" action="" id="publish_unpublish" onsubmit="return validateDate('1');">

	<div class="table">
	<table class="listing" cellpadding="0" cellspacing="0">
        <tr>
        <td align="center">

        <table class="inputlisting">
	<tr>
        <td>State</td>
        <td><select name="state" id="state">
                        <option value="">-- Select  --</option>
                <?php foreach($loc_states as $l_state){ ?>
                        <option value="<?php echo $l_state['state']; ?>" <?php if($l_state['state']==$data['state']){echo "selected";} ?>><?php echo $l_state['state']; ?></option>
                <?php } ?>
                </select>
        </td>
        </tr>
	<tr>
        <td>District</td>
        <td id="dist"> <select id="district" name="district" >
                        <option value="">-- Select  District --</option>
                        <?php foreach($districts as $dist){ ?>
                        <option value="<?php echo $dist['district']; ?>" <?php if($dist['district']==$data['district']){echo "selected";} ?>><?php echo $dist['district']; ?></option>
                        <?php } ?>
                        </select>
                </td>
        </tr>

        <tr>
        <td>Location *</td>
        <td id="location_id">
		<select name="location_id" id="location_id" required>
		<option value="">- Select Location -</option>
		<?php foreach($locations as $location) {?>
		<option value="<?php echo $location['id']; ?>" <?php if($_GET['location_id'] == $location['id']){ echo "selected"; }?>><?php echo $location['name']; ?></option>
		<?php } ?>
		</select>
	</td>
        </tr>
	<tr>
		<td>From *</td>
		<td><input type="text" class="datepick" name="from_date" id="from_date" value="<?php if($data['from_date']!=''){echo $data['from_date'];}?>"  required></td>
		<!--<td><input type="text" class="datepick" name="from_date" id="from_date" value="<?php if($data['from_date']!=''){echo $data['from_date'];}?>" onChange="validateDate($(this).val(), $('#to_date').val());" required></td>-->
	</tr>
	<tr>
		<td>To *</td>
		<td><input type="text" class="datepick" name="to_date" id="to_date" value="<?php if($data['to_date']!=''){echo $data['to_date'];}?>" required></td>
		<!--<td><input type="text" class="datepick" name="to_date" id="to_date" onChange="validateDate($('#from_date').val(), $(this).val());" value="<?php if($data['to_date']!=''){echo $data['to_date'];}?>" required></td>-->
	</tr>
	<tr>
		<td>Reason *</td>
		<td><textarea  name="reason" id="reason" class="form-control" rows="5" cols="30"  required><?php if($data['reason']!=''){echo $data['reason'];}?></textarea></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<input type="radio" name="is_publish" value="publish" id="publish">Publish
			<input type="radio" name="is_publish" value="unpublish" id="unpublish">Unpublish
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" id="submit" name="submit" value="Save">&nbsp; <input type="submit"  name="show" value="Show Data" ></td>
	</tr>
	</table>

	</td>
	</tr>
	</table>
	</form>

	</div>

	<?php echo $msg;?>

	<?php if(!empty ($publish_unpublish_log)){ ?>
	<div class="table">
	<img src="/img/bg-th-left.gif" width="8" height="7" alt="" class="left" /> 
	<img src="/img/bg-th-right.gif" width="7" height="7" alt="" class="right" />

	<table class="listing tablesorter" id="listing" cellpadding="0" cellspacing="0">
	<thead>
	<tr>
	<th class="first">Sr. No</th>
	<th>Location ID</th>
	<th>Created Date</th>
	<th>From</th>
	<th>To</th>
	<th>Status</th>
	<!--<th class="last">Action</th>-->
	</tr>
	</thead>
	<tbody>
	<?php 
	$i=1;
	foreach($publish_unpublish_log as $log) {
	$from_date =  date('m/d/Y h:i', get_strtotime($log['from_date']));
	$to_date = date('m/d/Y h:i', get_strtotime($log['to_date']));
	$action = ($statuses[$log['type']] == 'Publish') ? 'Unpublish' : 'Publish';
	?>
	<tr>
	<td class="first style1"><?php echo $i; ?> </td>
	<td><?php echo $log['name']; ?></td>
	<td><?php echo $log['created']; ?></td>
	<td><?php echo $from_date; ?></td>
	<td><?php echo $to_date; ?></td>
	<td><?php echo $statuses[$log['type']]; ?></td>
<!--	<td class="last"><?php echo "<a href=\"javascript: ProceedToAction('$log[id]','$log[location_id]','$from_date','$to_date','$action');\">$action</a>"; ?></td>-->
	</tr>
	<?php $i++; } ?>

	</tbody>
	</table>
	</div>
	<?php } ?>

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
