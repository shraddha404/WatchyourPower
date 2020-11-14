<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/location_owner_header.php";
$main_menu = 'Location Owner Panel';
$current = 'Export Summery';
$locations = $u->getLocationsOwnerFromCriteria($details);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/locationowner/include/html_head.php"; ?>
<?php include $_SERVER['DOCUMENT_ROOT']."/locationowner/include/autocomplete_head.php"; ?>
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
<?php include $_SERVER['DOCUMENT_ROOT']."/locationowner/include/menu.php"; ?>
</div>

<div id="middle">

<div id="left-column">
	<?php include $_SERVER['DOCUMENT_ROOT']."/locationowner/include/left_nav.php"; ?>
</div>

<div id="center-column">
	<?php include $_SERVER['DOCUMENT_ROOT']."/locationowner/include/bredcrumb.php"; ?>


	<div class="table">
	<!--<form method="post" action="#" onsubmit="return compare_date_time('1');" id="event_log"> -->
<form method="get" action="raw_data.php" onsubmit="return validateForm(this);">
	<table class="listing" cellpadding="0" cellspacing="0">
	<tr>
	<td align="center">
	<table class="inputlisting">
	<tr>
            <td style="width:0;">&nbsp;&nbsp;&nbsp;</td>

                <td style="width:12%;" id="loc">
                        <select class="form-control" id="location_id" name="location_id">
                        <option value="">-Select-</option>
                        <?php foreach($locations as $loc){ if($loc['status']==0){ continue;}  ?>
                        <option value="<?php echo $loc['id']; ?>" <?php if($details['location_id']== $loc['id']){ echo 'selected="selected"';}?>><?php echo $loc['name']; ?></option>
                        <?php } ?>
                        </select>
                </td>
		<?php if($u->user_type=='Owner'){?>
                <td style="width:0;">&nbsp;&nbsp;&nbsp;</td>
                <td style="width:12%;">
                        <select class="form-control" id="" name="data_type">
                        <option value="voltage">Voltage</option>
                        <option value="summary">Summary</option>
                        </select>
                </td>
		<?php }else{ echo "<input type=\"hidden\" name=\"data_type\" value=\"summary\" >"; }?></tr><tr>
		<td style="width:0;">&nbsp;&nbsp;&nbsp;</td>
                <td style="width:20%;">
                        <input type="text" placeholder="From Date" class="form-control datepick" id="from_date" name="from_date" required value="<?php echo $details['from_date']; ?>" size="10" readonly="readonly">
                </td>
		<td style="width:0;">&nbsp;&nbsp;&nbsp;</td>
                <td style="width:20%;">
                        <input type="text" placeholder="To Date" class="form-control datepick" id="to_date" name="to_date" required value="<?php echo $details['to_date']; ?>" size="10" readonly="readonly">
                </td>
		<td style="width:0;">&nbsp;&nbsp;&nbsp;</td>
                <td style="width:12%;"><input type="submit" value="Download" class="btn btn-primary" id="submit"></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>

	
	</table>
</td></tr></table>
	</form>

	</div>
	<?php echo $msg;?>
	
	

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
