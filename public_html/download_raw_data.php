<?php
session_start();

include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
/*if(empty($_SESSION['user_id'])){
	header('Location:/index.php');
}*/

$details= $_GET;
$location_id = $details['location_id'];

if($details['location_id']){
$location = $u->getLocationDetails($details['location_id']);
$details['state'] = $location['state'];
$details['category_id'] = $location['revenue_classification'];
}

if($details['state']!='' and $details['category_id']!=''){
$locations = $u->getLocationsFromStateAndCategory($details['state'],$details['category_id']);
}
$locations = $u->getLocationsFromCriteria($details);

$categories=$u->getRevenueClassification();
$states = $u->getAllStatesFromLocationsWeb();
$districts = $u->getDistrics();
$current="download_data";
?>
<!DOCTYPE html>
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/html_head.php"; ?>
<script type="text/javascript">
var dateRange = getMinMaxDates(3);
$(function() {
$( "#from_date" ).datetimepicker(
{
format:'d/m/Y',
formatDate:'d/m/Y',
//minDate:dateRange.minDate,
//maxDate:dateRange.maxDate,
timepicker:false,
closeOnDateSelect:true
}
);
$( "#to_date" ).datetimepicker(
{
format:'d/m/Y',
formatDate:'d/m/Y',
//minDate:dateRange.minDate,
//maxDate:dateRange.maxDate,
timepicker:false,
closeOnDateSelect:true
}
);

/*$(document).on("change","#state, #category_id", function(){
        var obj ={};
        obj.state = $('#state').val();
        obj.category_id = $('#category_id').val();
        obj.district = $('#district').val();
        obj.location_div_id = $('#location_id').attr('id');
        obj.district_div_id = $('#district').attr('id');
        if(obj.state!=''){
        getDistricts(obj,'dist');
        }
        getLocations(obj,'loc');
});*/

$(document).on("change","#category_id", function(){
        var obj ={};
        obj.state = $('#state').val();
        obj.category_id = $('#category_id').val();
        obj.district = $('#district').val();
        obj.state_div_id = $('#state').attr('id');
        obj.location_div_id = $('#location_id').attr('id');
        obj.district_div_id = $('#district').attr('id');
        getStates(obj,'stat');
        //if(obj.state!=''){
        getDistricts(obj,'dist');
        //}
        getLocations(obj,'loc');
});

$(document).on("change","#state", function(){
        var obj ={};
        obj.state = $('#state').val();
        obj.category_id = $('#category_id').val();
        obj.district = $('#district').val();
        obj.state_div_id = $('#state').attr('id');
        obj.location_div_id = $('#location_id').attr('id');
        obj.district_div_id = $('#district').attr('id');
        //if(obj.state!=''){
        getDistricts(obj,'dist');
        //}
        getLocations(obj,'loc');
});

$(document).on("change","#district", function(){
        var obj ={};
        obj.state = $('#state').val();
        obj.category_id = $('#category_id').val();
        obj.district = $('#district').val();
        obj.location_div_id = $('#location_id').attr('id');
        getLocations(obj,'loc');
});

$(document).on("change","#location_id", function(){
        if($('#location_id').val()!=''){
                $('#submit').show();
        }else{
                $('#submit').hide();
        }
});

});

function validateForm(theForm){
	if(getDaysInDateRange(theForm.from_date.value, theForm.to_date.value) > 90){
		alert('Please select a date-range of up to 90 days.');
		return false;
	}
	return true;
}

</script>
</head>
<body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
               <?php include $_SERVER['DOCUMENT_ROOT']."/includes/header_banner.php"; ?>
<div class="wrapper row-offcanvas row-offcanvas-left">
               <?php include $_SERVER['DOCUMENT_ROOT']."/includes/left_menu.php"; ?>
<aside class="right-side strech" >
<section class="content-header">
	<h1>Download Data</h1>
</section>

<!-- Main content -->
<section class="content">
<form method="get" action="raw_data.php" onsubmit="return validateForm(this);">
<table cellspacing="10" cellpadding="10" width="" border="0">
<tbody>
		<tr>
		<th>Category</th>
		<th>&nbsp;</th>
		<th>State</th>
		<th>&nbsp;</th>
		<th>District</th>
		<th>&nbsp;</th>
		<th>Location</th>
		<th>&nbsp;</th>
		<?php if($u->user_type=='Admin'||$u->user_type=='Special'){?>
		<th>Type of Data</th>
		<th>&nbsp;</th>
		<?php }?>
		<th>From Date</th>
		<th>&nbsp;</th>
		<th>To</th>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
		</tr>

        	<tr>
                <td style="width:12%;">
                        <select class="form-control" id="category_id" name="category_id">
                        <option value="">-Select-</option>
                        <?php foreach($categories as $cat){ 
				if($cat['name'] == 'Mega city') continue; // hiding Mega city option only in the website.
			?>
                        <option value="<?php echo $cat['id']; ?>" <?php if($details['category_id']== $cat['id']){ echo 'selected="selected"';}?> ><?php echo $cat['name']; ?></option>
                        <?php } ?>
                        </select>

                </td>
                <td style="width:0;">&nbsp;&nbsp;&nbsp;</td>

                <td style="width:12%;" id="stat">
                        <select class="form-control" id="state" name="state">
                        <option value="">-Select-</option>
                        <?php foreach($states as $state){ ?>
                        <option value="<?php echo $state; ?>" <?php if($details['state']== $state){ echo 'selected="selected"';}?>><?php echo $state; ?></option>
                        <?php } ?>
                        </select>
                </td>
                <td style="width:0;">&nbsp;&nbsp;&nbsp;</td>


                <td style="width:12%;" id="dist">
                        <select class="form-control" id="district" name="district">
                        <option value="">-Select-</option>
                        <?php foreach($districts as $dist){ ?>
                        <option value="<?php echo $dist['district']; ?>" <?php if($details['district']== $dist['district']){ echo 'selected="selected"';}?>><?php echo $dist['district']; ?></option>
                        <?php } ?>
                        </select>
                </td>
                <td style="width:0;">&nbsp;&nbsp;&nbsp;</td>

                <td style="width:12%;" id="loc">
                        <select class="form-control" id="location_id" name="location_id">
                        <option value="">-Select-</option>
                        <?php foreach($locations as $loc){ if($loc['status']==0){ continue;}  ?>
                        <option value="<?php echo $loc['id']; ?>" <?php if($details['location_id']== $loc['id']){ echo 'selected="selected"';}?>><?php echo $loc['name']; ?></option>
                        <?php } ?>
                        </select>
                </td>
		<?php if($u->user_type=='Admin'||$u->user_type=='Special'){?>
                <td style="width:0;">&nbsp;&nbsp;&nbsp;</td>
                <td style="width:12%;">
                        <select class="form-control" id="" name="data_type">
                        <option value="voltage">Voltage</option>
                        <option value="summary">Summary</option>
                        </select>
                </td>
		<?php }else{ echo "<input type=\"hidden\" name=\"data_type\" value=\"summary\" >"; }?>
		<td style="width:0;">&nbsp;&nbsp;&nbsp;</td>
                <td style="width:12%;">
                        <input type="text" placeholder="From Date" class="form-control datepick" id="from_date" name="from_date" required value="<?php echo $details['from_date']; ?>" size="5" readonly="readonly">
                </td>
		<td style="width:0;">&nbsp;&nbsp;&nbsp;</td>
                <td style="width:12%;">
                        <input type="text" placeholder="To Date" class="form-control datepick" id="to_date" name="to_date" required value="<?php echo $details['to_date']; ?>" size="5" readonly="readonly">
                </td>
		<td style="width:0;">&nbsp;&nbsp;&nbsp;</td>
                <td style="width:12%;"><input type="submit" value="Download" class="btn btn-primary" id="submit" <?php if($location_id==''){ ?>style="display:none;" <?php } ?> onclick="return validateDate('0');"></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
        	</tr>
        	<tr>
		<td colspan="8">&nbsp;</td>
		</tr>
</tbody>
</table>
</form>

			<hr style="border: 0; height: 1px; background: #333; background-image: linear-gradient(to right, #ccc, #333, #ccc);"/>
			<h4>Please click here to view&nbsp;&nbsp;<a href="uploaded_reports.php"><strong>Analysis Reports</strong></a>.</h4>

</div>
</div>
</section><!-- /.content -->
</aside><!-- /.right-side -->

</div><!-- ./wrapper -->


<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>

    </body>
</html>
