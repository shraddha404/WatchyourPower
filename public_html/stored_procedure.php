<?php
session_start();
if(empty($_SESSION['user_id'])){
	header('Location:/index.php');
}

include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
$details= $_GET;
if($details['location_id']){
$location = $u->getLocationDetails($details['location_id']);
$details['state'] = $location['state'];
$details['category_id'] = $location['revenue_classification'];
}

if($details['state']!='' and $details['category_id']!=''){
$locations = $u->getLocationsFromStateAndCategory($details['state'],$details['category_id']);
}

$locations = $u->getLocationsFromCriteria($details);
// get name of the location
if(!empty($details['location_id'])){
        $location_name = '';
        foreach($locations as $loc){
                if($details['location_id'] == $loc['id']){
                        $location_name = $loc['name'];
                        $district = $loc['district'];
                        $state = $loc['state'];
                        $type  = $loc['connection_type'];
                        break;
                }
        }
}
//print_r($locations);
$categories=$u->getRevenueClassification();
//$states = $u->getAllStatesFromLocations();
$states = $u->getAllStatesFromLocationsWeb();
//$districts = $u->getDistrics();
$districts = $u->getAvailableDistrictsFromCriteria();
$distribution_types = $u->getSupplyUtilities();
$current="stored_procedure";

//$locations=$u->getAllLocations();
?>
<!DOCTYPE html>
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/html_head.php"; ?>
<script type="text/javascript">
$(document).ready(function() 
{
    $('#location_id option').click(function() 
    {
        var items = $(this).parent().val();
        if (items.length > 10) {
                       alert("You can select up to 10 locations at a time");
           $(this).removeAttr("selected");
        }
    });
   });
$(document).ready(  
            function() {  
                $('#btnAdd').click(  
                    function(e) {
			var y = $('#location_id').val();
			var yl= y.length;
			var x = $('#locations option').size(); 
			if(x+yl<11){
                        $('#location_id > option:selected').appendTo('#locations'); 
                        e.preventDefault();  
			}else{ 
			alert("You can select up to 10 locations at a time");
			}
                    });  
  
                /*$('#btnAddAll').click(  
                function(e) {  
                    $('#location_id > option').appendTo('#locations');  
                    e.preventDefault();  
                });  */
  
                $('#btnRemove').click(  
                function(e) {  
                    $('#locations > option:selected').appendTo('#location_id');  
                    e.preventDefault();  
                });  
  
                /*$('#btnRemoveAll').click(  
                function(e) {  
                    $('#locations > option').appendTo('#location_id');  
                    e.preventDefault();  
                });*/  
  
            });   
function validateForm(theForm){
        var days = getDaysInDateRange(theForm.from_date.value, theForm.to_date.value);
        //alert(days);
        if(days > 31){
                alert('Please select a date-range of up to 31 days.');
                return false;
        }
        return true;
}

$(document).on("change","#category_id", function(){
        var obj ={};
        obj.state = '';
        //obj.state = $('#state').val();
        obj.category_id = $('#category_id').val();
        obj.district = '';
        //obj.district = $('#district').val();
        obj.state_div_id = $('#state').attr('id');
        obj.location_div_id = $('#location_id').attr('id');
        obj.district_div_id = $('#district').attr('id');
        getStates(obj,'stat');
        //if(obj.state!=''){
        getDistricts(obj,'dist');
        //}
        getLocationsForMultiselect(obj,'loc');
});

$(document).on("change","#state", function(){
        var obj ={};
        obj.state = $('#state').val();
        obj.category_id = $('#category_id').val();
        //obj.district = $('#district').val();
        obj.district = '';
        obj.state_div_id = $('#state').attr('id');
        obj.location_div_id = $('#location_id').attr('id');
        obj.district_div_id = $('#district').attr('id');
        //if(obj.state!=''){
        getDistricts(obj,'dist');
        //}
        getLocationsForMultiselect(obj,'loc');
});

$(document).on("change","#district", function(){
        var obj ={};
        obj.state = $('#state').val();
        obj.category_id = $('#category_id').val();
        obj.district = $('#district').val();
        obj.location_div_id = $('#location_id').attr('id');
        getLocationsForMultiselect(obj,'loc');
});

$(document).on("change","#consumer_type", function(){
        var obj ={};
        obj.consumer_type = $('#consumer_type').val();
        obj.distribution = '';
	obj.district = $('#district').val();
        obj.consumer_type_div_id = $('#consumer_type').attr('id');
        obj.location_div_id = $('#location_id').attr('id');
        obj.distribution_div_id = $('#dist_type').attr('id');
        getDistribution_company(obj,'distribution');
        getLocationsForMultiselect(obj,'loc');
});

$(document).on("change","#dist_type", function(){
        var obj ={};
        obj.dist_type = $('#dist_type').val();
        obj.consumer_type = $('#consumer_type').val();
        obj.distribution = '';
        obj.district = $('#district').val();
        obj.consumer_type_div_id = $('#consumer_type').attr('id');
        obj.location_div_id = $('#location_id').attr('id');
        obj.distribution_div_id = $('#dist_type').attr('id');
        getLocationsForMultiselect(obj,'loc');
});


$(function() {
var today = new Date();
maxdate = (today.getDate()-1 +'/'+(today.getMonth() + 1) +'/' +  today.getFullYear());
$( "#to_date" ).datetimepicker(
{
format:'d/m/Y',
formatDate:'d/m/Y',
timepicker:false,
closeOnDateSelect:true,
maxDate:maxdate
}
);

$( "#from_date" ).datetimepicker(
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
<body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
               <?php include $_SERVER['DOCUMENT_ROOT']."/includes/header_banner.php"; ?>
<div class="wrapper row-offcanvas row-offcanvas-left">
               <?php include $_SERVER['DOCUMENT_ROOT']."/includes/left_menu.php"; ?>
<aside class="right-side " >
<section class="content-header">
	<h1>Download Interruptions and Voltage Readings For Multiple Locations</h1>
</section>

<!-- Main content -->
<section class="content">
<!--<form method="get" action="" >-->
<table cellspacing="10" cellpadding="10" width="100%" border="0">

<tbody><!--tr><td colspan="7"><input class="btn btn-primary" type="button" value="Print" onclick="printpage()"></td></tr-->
        <tr>
        <th align="center">Category</th>
        <th>&nbsp;</th>
        <th align="center">State</th>
        <th>&nbsp;</th>
        <th align="center">District</th>
        <th>&nbsp;</th>
        <th align="center">Consumer Type</th>
        <th>&nbsp;</th>
        <th align="center">Distribution Company</th>
        <th>&nbsp;</th>
        </tr>
        <tr>
                <td style="width:20%;">
                        <select class="form-control" id="category_id" name="category_id">
                        <option value="">-- Select  Category--</option>
                        <?php foreach($categories as $cat){
                                if($cat['name'] == 'Mega city') continue; // hiding Mega city option only in the website.
                        ?>
                        <option value="<?php echo $cat['id']; ?>" <?php if($details['category_id']== $cat['id']){ echo 'selected="selected"';}?> ><?php echo $cat['name']; ?></option>
                        <?php } ?>
                        </select>

                </td>
                <td>&nbsp;&nbsp;&nbsp;</td>

                <td style="width:20%;" id="stat">
                        <select class="form-control" id="state" name="state">
                        <option value="">-- Select  State --</option>
                        <?php foreach($states as $state){ ?>
                        <option value="<?php echo $state; ?>" <?php if($details['state']== $state){ echo 'selected="selected"';}?>><?php echo $state; ?></option>
                        <?php } ?>
</select>
                </td>
                <td>&nbsp;&nbsp;&nbsp;</td>


                <td style="width:20%;" id="dist">
                        <select class="form-control" id="district" name="district">
                        <option value="">-- Select  District --</option>
                        <?php foreach($districts as $dist){ ?>
                        <option value="<?php echo $dist['district']; ?>" <?php if($details['district']== $dist['district']){ echo 'selected="selected"';}?>><?php echo $dist['district']; ?></option>
                        <?php } ?>
                        </select>
                </td>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td style="width:20%;" id="consumer">
                        <select class="form-control" id="consumer_type" name="consumer_type">
                        <option value="">-- Consumer Type --</option>
                        <option value="Domestic" <?php if($details['connection_type']== 'Domestic'){ echo 'selected="selected"';}?>>Domestic</option>
                        <option value="Non Domestic" <?php if($details['connection_type']== 'Non Domestic'){ echo 'selected="selected"';}?>>Non Domestic</option>
                        <option value="Agriculture" <?php if($details['connection_type']== 'Agriculture'){ echo 'selected="selected"';}?>>Agriculture</option>
                        </select>
                </td>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td style="width:20%;" id="distribution">
                        <select class="form-control" id="dist_type" name="dist_type">
                        <option value="">--Distribution Company--</option>
                        <?php foreach($distribution_types as $dist_type){ ?>
                        <option value="<?php echo $dist_type['supply_utility']; ?>" <?php if($details['supply_utility']== $dist_type['supply_utility']){ echo 'selected="selected"';}?>><?php echo $dist_type['supply_utility']; ?></option>
                        <?php } ?>
                        </select>
                </td>
                <td>&nbsp;&nbsp;&nbsp;</td>

        </tr>
        <tr><td colspan="8">&nbsp;</td></tr>
</table>

<form method="post" action="new_multi_location_data.php" onsubmit="return validateForm(this) && validateDate(0) " >
<table cellspacing="10" cellpadding="10" width="80%" border="0" align="left">
<tbody>
		<tr><td>&nbsp;</td></tr>
		<tr>
		<th>Select Locations</th>
		<th>&nbsp;</th>
		<th>Selected Locations</th>
		<th>Date Range</th>
		</tr>
        	<tr>
		<td style="width:20;" id="loc">
		<select name="location_to_select" multiple style="height:200px; width:200px;" size="10" id="location_id" >
		<?php foreach($locations as $location){?>
  		<option value="<?php echo $location['id'];?>"><?php echo $location['name'];?></option>
		<?php }?>
		</select>
		</td>
		<td align="center" style="width:10%;">  
                <input type="button" id="btnAdd" value=">" style="width: 50px;" /><br />  
                <!--<input type="button" id="btnAddAll" value=">>" style="width: 50px;" /><br />  -->
                <input type="button" id="btnRemove" value="<" style="width: 50px;" /><br />  
                <!--<input type="button" id="btnRemoveAll" value="<<" style="width: 50px;" />  -->
            	</td>   
		<td style="width:20%;" id="loc">
		<select name="locations[]" multiple style="height:200px; width:200px;" size="10" id="locations">
		<?php //foreach($locations as $location){?>
		<?php //}?>
		</select>
		</td>
                <td width="20%">
			<label>From date </label>
                       <input type="text"  class="form-control datepick" id="from_date" name="from_date" required value="" size="5" readonly="readonly"></br></br>
			<label>To date </label>
                       <input type="text"  class="form-control datepick" id="to_date" name="to_date" required value="" size="5" readonly="readonly"></td>
		</tr>
		<tr><td colspan="8">&nbsp;</td></tr>

		<tr>
                <td width="20%"><input type="radio" style="display:block; opacity:1 !important;" name="report_option" required value="voltage" >Voltage Data</td>
                <td width="20%"><input type="radio" style="opacity:1 !important;" name="report_option" required value="interrupts">Interrupts and Summary data</td>
		</tr>
		<tr><th>&nbsp;</th></tr>
		<tr>
                <td style="width:15%;"><input type="submit" value="Download" class="btn btn-primary" id="submit" ></td>
        	</tr>
</tbody>
</table>
</form>

</div>
</div>
</section><!-- /.content -->
</aside><!-- /.right-side -->

</div><!-- ./wrapper -->


<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>

    </body>
</html>
