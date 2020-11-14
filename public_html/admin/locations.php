<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";
 $location_details=$_POST;
$details['user_id']=$_SESSION['user_id'];
if($_POST['submit']=='Save'){
$i=0;

        $details = $_POST;
        if($location_id=$u->addLocations($details)){
			$u->set_location_default_voltage_param($location_id);	
			if(!empty($_FILES)){
				$u->addLocationDocuments($location_id,$_FILES);
				}

                $msg = "<span class=\"message\">Location added successfully.</span>";
        }else{
		$location_details = $_POST;
		$location_params = $_POST;
		$msg =  "<span class=\"error\">$u->error</span>";
        }
}

if($_POST['submit']=='Update'){
$details = $_POST;

$location_id= $_POST['id'];
        if($u->updateLocations($details)){
		if(!empty($_FILES)){
		$u->addLocationDocuments($location_id,$_FILES);
		}
                $msg = "<span class=\"message\">Location Updated successfully.</span>";
        }
        else{
                $msg = "<span class=\"error\">$u->error</span>";
        }
}

if($_GET['location_id']!='' && $_GET['oper']=='delete'){
	$location_id = $_GET['location_id'];
        if($u->deleteLocations($location_id)){
                $msg = "<span class=\"message\">Location deleted successfully.</span>";
        }
        else{
                $msg = "<span class=\"error\">$u->error</span>";
        }
}
if($_GET['location_id']!='' && $_GET['type']!='' && $_GET['oper']=='delete_file'){
        $location_id = $_GET['location_id'];
	$document_type = $_GET['type'];
        if($u->deleteLocationDocument($location_id, $document_type)){
                header( "refresh:1;url=locations.php?location_id=".$location_id."&oper=update" );
		$msg = "<span class=\"message\">File deleted successfully.</span>";
		
        }
        else{
                $msg = "<span class=\"error\">$u->error</span>";
        }
}



if($_GET['location_id']!='' && $_GET['oper']=='update'){
$is_update = true;
$location_id = $_GET['location_id'];
$location_details = $u->getLocationDetails($location_id);
$location_documents  = $u->getAllLocationDocuments($location_id);
$new_data = array();
foreach($location_documents as $k=> $v){
$new_data[$v['document_type']]['document_id'] = $v['document_id'];
$new_data[$v['document_type']]['file_name'] = $v['filename'];
}
array_push($location_details, $new_data);
if(array_key_exists('electricity_bill_1', $location_details[0])){
 $bill_1 = true;
}
if(array_key_exists('electricity_bill_2', $location_details[0])){
 $bill_2 = true;
}
if(array_key_exists('electricity_bill_3', $location_details[0])){
 $bill_3 = true;
}
if(array_key_exists('id_proof_1', $location_details[0])){
 $id_1 = true;
}
if(array_key_exists('id_proof_2', $location_details[0])){
 $id_2 = true;
}
if(array_key_exists('id_proof_3', $location_details[0])){
 $id_3 = true;
}
if(array_key_exists('agreement_copy_1', $location_details[0])){
 $agreement_1 = true;
}
if(array_key_exists('agreement_copy_2', $location_details[0])){
 $agreement_2 = true;
}
if(array_key_exists('agreement_copy_3', $location_details[0])){
 $agreement_3 = true;
}
if(array_key_exists('photo_1', $location_details[0])){
 $photo_1 = true;
}
if(array_key_exists('photo_2', $location_details[0])){
 $photo_2 = true;
}
if(array_key_exists('photo_3', $location_details[0])){
 $photo_3 = true;
}
$location_params = $u->getLocationParams($location_id);
$location_params['appliances'] = explode(',',$location_params['appliances']); 
$location_params['water_source'] = explode(',',$location_params['water_source']);
}



$revenue_classes = $u->getRevenueClassification();
$locations = $u->getAllLocations();
$file="states_list.txt";
$states = $u->getStatesList($file);
$loc_states = $u->getAllStatesFromLocations();

$main_menu = 'Data Settings';
$current = 'location';
 $remark = $u->getRemark($location_id,'locations');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/html_head.php"; ?>
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/autocomplete_head.php"; ?>
<script type="text/javascript">
$(document).on("change","#state, #category_id", function(){
        var obj ={};
        obj.state = $('#state').val();
        obj.location_div_id = $('#location_id').attr('id');
        getLocationsInAdminPanel(obj,'loc');
	getLocationsByState(obj.state,'location_data');
});

function loadConnectionType(){
var selected_connection = $('#connection_type').val();
if(selected_connection ==='Domestic'){
$('#domestic').show();
}
else if( selected_connection ==='Non Domestic'){
$('#nondomestic').show();
}
 else if(selected_connection ==='Agriculture') {
$('#agri').show();
}
}

function loadAppliancefailure(){
var appliance_failure = $('#appliance_failuare').val();
var inverter_backup = $('#inverter_backup').val();
if(appliance_failure ==='Yes'){
$('#failure_details').show();
}
if(inverter_backup ==='Yes'){
$('#yes').show();
}
}


$(document).ready(function (){
    validate();
    $('#name, #state, #district ,#latitude, #longitude, #town, #revenue_classification, #connection_type, #report_contact_person,  #report_phone').change(validate);
});

function validate(){
    if ($('#name').val().length   >   0   &&
        $('#state').val().length  >   0   &&
        $('#district').val().length >  0  &&
        $('#latitude').val().length >  0  &&
        $('#longitude').val().length >  0  &&
        $('#revenue_classification').val().length >  0  &&
        $('#connection_type').val().length >  0  &&
        $('#report_contact_person').val().length >  0  &&
        $('#report_phone').val().length  >   0 ) {
        $("input[type=submit]").prop("disabled", false);
    }
    else {
        $("input[type=submit]").prop("disabled", true);
    }
}


/*$(document).ready(function () {
    $('#r_con').change(function () {
        if (!this.checked) 
           $('#report_contact').fadeOut('slow');
        else 
            $('#report_contact').fadeIn('slow');
    });
    $('#m_con').change(function () {
        if (!this.checked) 
           $('#maintainance_contact').fadeOut('slow');
        else 
            $('#maintainance_contact').fadeIn('slow');
    });
    $('#o_con').change(function () {
        if (!this.checked) 
           $('#other_contact').fadeOut('slow');
        else 
            $('#other_contact').fadeIn('slow');
    });
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
	<?php echo $msg;?>
		<div class="tabs-table">
		<form method="post" enctype="multipart/form-data">
	<div id="tabs">
	<ul>
		<li><a href="#tabs-1">Basic Details</a></li>
		<li><a href="#tabs-2">Contact Details</a></li>
		<li><a href="#tabs-3" onclick="loadConnectionType();">Electricity Connection /</br>Bill Related Information</a></li>
		<li><a href="#tabs-4">Attach Documents</a></li>
		<li><a href="#tabs-5" onclick="loadAppliancefailure();">Appliance Details</a></li>
	</ul>

		<div id="tabs-1">
                <table class="tabs-listing" cellpadding="0" cellspacing="0">
                <tr>
                <td align="center">

                <table class="tabs-inputlisting">

                <tr>
                <td>Display Name *</td>
                <td>
                <input type="text" name="name" id="name" value="<?php echo $location_details['name'];?>" required >
                <input type="hidden" name="id" value="<?php echo $location_details['id'];?>">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id'];?>">
		</td>
                <td>Country</td>
                <td>
                <input type="text" name="country" value="<?php if($is_update){ echo $location_details['country']; } else{ echo "India"; }?>  " ></td>
                </tr>
		
		<tr>
                <td>Alias</td>
                <td>
                <input type="text" name="alias" value="<?php echo $location_details['alias'];?>" ></td>
                <td>State *</td>
                <td>
                <select name="state" id="state" required tabindex="6">
                <option value="">-- Select  --</option>
                <?php foreach($states as $k=>$v){?>
                <option value="<?php echo $v;?>" <?php if($location_details['state']==$v){ echo 'selected="selected"'; }?>><?php echo $v;?></option>
                <?php } ?>
                </select>
		</td>
                </tr>

                <tr>
                <td>Address</td>
                <td>
                <input type="text" name="address" value="<?php echo $location_details['address'];?>" ></td>
                <td>Latitude *</td>
                <td>
                <input type="text" name="latitude"id="latitude" pattern="-?\d{1,3}\.\d+" value="<?php echo $location_details['latitude'];?>" ></td>
                </tr>
		
		<tr>
                <td>District *</td>
                <td>
                <input type="text" name="district" id="district"value="<?php echo $location_details['district'];?>" required ></td>
                <td>Longitude *</td>
                <td>
                <input type="text" name="longitude"id="longitude" pattern="-?\d{1,3}\.\d+" value="<?php echo $location_details['longitude'];?>" required ></td>
                </tr>

                <tr>
                <td>Village/Town/City *</td>
                <td>
                <input type="text" name="town" id="town"value="<?php echo $location_details['town'];?>" required ></td>
                <td>Revenue Classification *</td>
                <td>
                <select name="revenue_classification" id="revenue_classification"required>
                <option value="" >-- Select  --</option>
                        <?php foreach($revenue_classes as $r_class){ ?>
                <option value="<?php echo $r_class['revenue_class_id'];  ?>" <?php if($location_details['revenue_classification']==$r_class['revenue_class_id']){ echo 'selected="selected"';} ?>><?php echo $r_class['name'];?></option>
                <?php } ?>
                </select>
                </td>
		</tr>
		<tr>
                <td>Pincode</td>
                <td>
                <input type="text" name="pincode" value="<?php echo $location_details['pincode'];?>" pattern="^[1-9][0-9]{5}$" title="A six digit number that doesn't begin with zero."></td>
                <td>Type of Supply</td>
                <td>
		<select name="type_of_supplier">
                <option value="">-- Select  --</option>
                <option value="Grid" <?php if($location_details['type_of_supplier']=='Grid'){ echo 'selected="selected"'; }?>>Grid</option>
                <option value="Mini Grid" <?php if($location_details['type_of_supplier']=='Mini Grid'){ echo 'selected="selected"'; }?>>Mini Grid</option>
                <option value="Diesel" <?php if($location_details['type_of_supplier']=='Diesel'){ echo 'selected="selected"'; }?>>Diesel</option>
                </select>
	
		</td>
                </tr>
		
		<tr>
                <!--<td>Connection Type *</td>
                <td>
		<select name="connection_type" id="connection_type">
                <option value="">-- Select  --</option>
                <option value="Domestic" <?php if($location_details['connection_type']=='Domestic'){ echo 'selected="selected"'; }?>>Domestic</option>
                <option value="Non Domestic" <?php if($location_details['connection_type']=='Non Domestic'){ echo 'selected="selected"'; }?>>Non Domestic</option>
                <option value="Agriculture" <?php if($location_details['connection_type']=='Agriculture'){ echo 'selected="selected"'; }?>>Agriculture</option>
                </select>
                </td>-->
                <td>Remark</td>
                <td><textarea id="other_info" name="other_info" class="form-control" rows="5" cols="35" ><?php echo $remark['remark'];?></textarea>
                <!--input type="text" name="other_info" value="<?php echo $location_details['other_info'];?>" tabindex="26" --></td>
                </tr>

        </table>
        </td>
	</tr>
	</table>
	</div>
		<div id="tabs-3">
		<div>
		<div class="namemain">
                <label>Connection Type</label>
                <select name="connection_type" id="connection_type" onchange="if($(this).val()=='Domestic'){ $('#domestic').show(); $('#nondomestc').hide(); $('#agri').hide();}else if($(this).val()=='Non Domestic'){$('#nondomestic').show(); $('#domestic').hide(); $('#agri').hide();} else{ $('#agri').show(); $('#nondomestic').hide(); $('#domestic').hide();} ">
                <option value="">-- Select  --</option>
                <option value="Domestic" <?php if($location_details['connection_type']=='Domestic'){ echo 'selected="selected"'; }?>>Domestic</option>
                <option value="Non Domestic" <?php if($location_details['connection_type']=='Non Domestic'){ echo 'selected="selected"'; }?>>Non Domestic</option>
                <option value="Agriculture" <?php if($location_details['connection_type']=='Agriculture'){ echo 'selected="selected"'; }?>>Agriculture</option>
                </select>
		</div></br>
                <div class="namemain" id="domestic" style="display:none;">
		<label>A1). What is the number of family members?</label>
                <input type="text" name="family_members_no" value="<?php echo nl2br($location_params['family_members_no']);  ?>"></br></br>
                <label>A2). How many rooms does the house have (including kitchen)?</label>
                <input type="text" name="house_rooms" value="<?php echo nl2br($location_params['house_rooms']);  ?>"></br>
		</br>
                <label>A3). What is the main occupation of family?</label>
                <input type="text" name="main_occupation" value="<?php echo nl2br($location_params['main_occupation']);  ?>"></br></br>
		<label>A4). Please select the suitable category of house</label></br></br>
                <input type="radio" name="house_category" value="type1" <?php if($location_params['house_category']=='type1'){ echo 'checked';}  ?>> Residential Type 1: RCC Bunglow / Multistory RCC</br>
                <input type="radio" name="house_category" value="type2" <?php if($location_params['house_category']=='type2'){ echo 'checked';}  ?>> Residential Type 2: Apartment/ Township/ Large Bunglow</br>
                <input type="radio" name="house_category" value="type3" <?php if($location_params['house_categor']=='type3'){ echo 'checked';}  ?>> Residential Type 3: Other</br></br>
		<label>A5). Does the family have a BPL card?(OR EQUIVALENT / RATION CARD ETC.)</label>
		<input type="radio" name="has_bpl_card" value="Yes" <?php if($location_params['has_bpl_card']=='Yes'){ echo 'checked';}?>>Yes
                <input type="radio" name="has_bpl_card" value="No" <?php if($location_params['has_bpl_card']=='No'){ echo 'checked';}?> >No</br></br>
		</div>
	
		<div class="namemain" id="nondomestic" style="display:none;">
                <label>B1). Please Specify</label></br></br>
                <input type="radio" name="non_domestic_type" value="c_small" <?php if($location_params['non_domestic_type']=='c_small'){ echo 'checked';}  ?>> Commercial -small (below 250 sq.ft)
                <input type="radio" name="non_domestic_type" value="c_large" <?php if($location_params['non_domestic_type']=='c_large'){ echo 'checked';}  ?>> Commercial -Large (above 250 sq.ft)
                <input type="radio" name="non_domestic_type" value="workshop" <?php if($location_params['non_domestic_type']=='workshop'){ echo 'checked';}  ?>> Workshop
                <input type="radio" name="non_domestic_type" value="industry" <?php if($location_params['non_domestic_type']=='industry'){ echo 'checked';}  ?>> Industry
                <input type="radio" name="non_domestic_type" value="other" <?php if($location_params['non_domestic_type']=='other'){ echo 'checked';}  ?>> Other</br></br>
                </div>

		<div class="namemain" id="agri" style="display:none;">
		<label>C1). What are the typical crops grown?</label>
                <input type="text" name="crops_grown" value="<?php echo nl2br($location_params['crops_grown']); ?>"></br></br>
                <label>C2). What is the average size of irrigated area? </label>
                <input type="text" name="irrigated_area" value="<?php echo nl2br($location_params['irrigated_area']); ?>"></br></br></br>
                <label>C3). Source of water</label></br></br>
                <input type="checkbox" name="water_source[]" value="openwell" <?php if(in_array('openwell',$location_params['water_source'])){ echo 'checked';}  ?>> Open well
                <input type="checkbox" name="water_source[]" value="tubewell" <?php if(in_array('tubewell',$location_params['water_source'])){ echo 'checked';}  ?>> Tube well
                <input type="checkbox" name="water_source[]" value="tank" <?php if(in_array('tank',$location_params['water_source'])){ echo 'checked';}  ?>> Tank/ Backwater
                <input type="checkbox" name="water_source[]" value="river" <?php if(in_array('river',$location_params['water_source'])){ echo 'checked';}  ?>> River</br></br>
		<label>C4). What are the scheduled load shedding hours?</label>
                <input type="text" name="loadshading_hr" value="<?php $location_params['loadshading_hr'];?>">
                </div>



                <!--<div class="namemain">
                <label>Type of Supply</label>
                <select name="type_of_supplier">
                <option value="">-- Select  --</option>
                <option value="Grid" <?php if($location_details['type_of_supplier']=='Grid'){ echo 'selected="selected"'; }?>>Grid</option>
                <option value="Mini Grid" <?php if($location_details['type_of_supplier']=='Mini Grid'){ echo 'selected="selected"'; }?>>Mini Grid</option>
                <option value="Diesel" <?php if($location_details['type_of_supplier']=='Diesel'){ echo 'selected="selected"'; }?>>Diesel</option>
                </select>
                </div>-->

		<div class="namemain">
                <label>Is RGGV?</label>
                <input type="checkbox" value="1" name="is_RGGV" <?php if($location_details['is_RGGV']=='1'){ echo 'checked="checked"';  }?> tabindex="18">
                </div>

                <div class="namemain">
                <label>Supply Utility</label>
                <input type="text" name="supply_utility" value="<?php echo $location_details['supply_utility'];?>" />
		</div>

		<div class="namemain">
                <label>RGGV year</label>
                <input type="text" name="RGGV_year" value="<?php echo $location_details['RGGV_year'];?>" />
                </div>

                <div class="namemain">
                <label>Tower Id</label>
                <input type="text" name="tower_id" value="<?php echo $location_details['tower_id'];?>" />
                </div>

		<div class="namemain">
                <label>Feeder Name</label>
                <input type="text" name="feeder" value="<?php echo $location_details['feeder'];?>" />
                </div>

                <div class="namemain">
                <label>Feeder Category</label>
                <input type="text" name="category" value="<?php echo $location_details['category'];?>" />
                </div>
		
		<div class="namemain">
                <label>A). What is the average bill amount?</label>
                <input type="text" name="bill_amount" value="<?php echo $location_params['bill_amount'][0];?>"/>
		</div>

		<div class="namemain">
                <label>B). In which month do you see the highest bill amount? </label>
                <input type="text" name="highest_bill_month" value="<?php echo $location_params['highest_bill_month'];?>"/>
		</div>

		<div class="namemain">
                <label>c). Name of supplier?</label>
                <input type="text" name="supplier_name" value="<?php echo $location_params['supplier_name'];?>"/>
		</div>

		<div class="namemain">
                <label>D). Promissed hour of supply?</label>
                <input type="text" name="promised_supply_hr" value="<?php echo $location_params['promised_supply_hr'];?>"/>
		</div>

		<div class="namemain">
                <label>E). In which year was your house electrified?</label>
                <input type="text" name="electrified_year" value="<?php echo $location_params['electrified_year'];?>"/>
		</div>

		<div class="namemain">
                <label>F). Was the house electrified under RGGGVY?</label></br></br>
		<input type="radio" name="is_RGGGVY" value="Yes" <?php if($location_params['is_RGGGVY']=='Yes'){echo 'checked';}?>/>Yes
                <input type="radio" name="is_RGGGVY" value="No"  <?php if($location_params['is_RGGGVY']=='No'){echo 'checked';}?>/>No</br>
		</div>

		<div class="namemain">
                <label>G). How often does the house see sustained( above 30 min) Power outage?</label></br></br></br>
                <input type="radio" name="sustained_power_outage" value="Daily" <?php if($location_params['sustained_power_outage']=='daily'){echo 'checked';}?>/> Daily
                <input type="radio" name="sustained_power_outage" value="Once in a week" <?php if($location_params['sustained_power_outage']=='onceinweek'){echo 'checked';}?>/> Once a week
                <input type="radio" name="sustained_power_outage" value="More than once in a week" <?php if($location_params['sustained_power_outage']=='morethanonce'){echo 'checked';}?>/> More than once a week
                <input type="radio" name="sustained_power_outage" value="No" <?php if($location_params['sustained_power_outage']=='no'){echo 'checked';}?>/> No</br></br>
                </div>

		<div class="namemain">
                <label>H). Do you see regular fluctuations</label></br></br>
		<input type="radio" name="regular_fluctuated" value="Yes" <?php if($location_params['regular_fluctuated']=='Yes'){echo 'checked';}?>/>Yes
                <input type="radio" name="regular_fluctuated" value="No" <?php if($location_params['regular_fluctuated']=='No'){echo 'checked';}?>/>No</br></br>
                </div>
		</div>
		</div>
		
		<div id="tabs-4">
		<label style="color:red;">Note: Only jpg,jpeg,png,gif and pdf files are allowed.</label>
                <div class="namemain" style="width:100%;">
                <label>Electricity Bill 1</label>
                <input type="file" name="electricity_bill_1" style="max-width:265px !important;" value=""/>
		<img src="/img/plus.png" onclick="$('#e_bill_2').show();" /><?php if($bill_1){?>&nbsp;&nbsp;<a target="_blank" style="padding:5px;" href="view_proof.php?location_id=<?php echo $location_details['location_id'];?>&type=electricity_bill_1">View</a>&nbsp;&nbsp;<a style="padding:5px;" href="locations.php?location_id=<?php echo $location_details['location_id'];?>&type=electricity_bill_1&oper=delete_file">Delete</a><?php }?>
                </div>
                <div class="namemain" style="display:none; width:100%;" id="e_bill_2">
                <label>Electricity Bill 2</label>
                <input type="file" name="electricity_bill_2" style="max-width:265px !important;" value="" />
		<img src="/img/plus.png" onclick="$('#e_bill_3').show();" /><?php if($bill_2){?>&nbsp;&nbsp;<a target="_blank" style="padding:5px;" href="view_proof.php?location_id=<?php echo $location_details['location_id'];?>&type=electricity_bill_2">View</a>&nbsp;&nbsp;<a style="padding:5px;" href="locations.php?location_id=<?php echo $location_details['location_id'];?>&type=electricity_bill_2&oper=delete_file">Delete</a><?php }?>
                </div>
                <div class="namemain" style="display:none; width:100%;" id="e_bill_3">
                <label>Electricity Bill 3</label>
                <input type="file" name="electricity_bill_3" style="max-width:265px !important;" value="" /><?php if($bill_3){?>&nbsp;&nbsp;<a target="_blank" style="padding:5px;" href="view_proof.php?location_id=<?php echo $location_details['location_id'];?>&type=electricity_bill_3">View</a>&nbsp;&nbsp;<a style="padding:5px;" href="locations.php?location_id=<?php echo $location_details['location_id'];?>&type=electricity_bill_3&oper=delete_file">Delete</a><?php }?>
                </div>

                <div class="namemain" style="width:100%;">
                <label>Id Proof 1</label>
                <input type="file" name="id_proof_1" style="max-width:265px !important;" value="" />
		<img src="/img/plus.png" onclick="$('#id_proof_2').show();" /><?php if($id_1){?>&nbsp;&nbsp;<a target="_blank" style="padding:5px;" href="view_proof.php?location_id=<?php echo $location_details['location_id'];?>&type=id_proof_1">View</a>&nbsp;&nbsp;<a style="padding:5px;" href="locations.php?location_id=<?php echo $location_details['location_id'];?>&type=id_proof_1&oper=delete_file">Delete</a><?php }?>
                </div>

                <div class="namemain" style="display:none; width:100%;" id="id_proof_2">
                <label>Id Proof 2</label>
                <input type="file" name="id_proof_2" style="max-width:265px !important;" value="" />
		<img src="/img/plus.png" onclick="$('#id_proof_3').show();" /><?php if($id_2){?>&nbsp;&nbsp;<a target="_blank" style="padding:5px;" href="view_proof.php?location_id=<?php echo $location_details['location_id'];?>&type=id_proof_2">View</a>&nbsp;&nbsp;<a style="padding:5px;" href="locations.php?location_id=<?php echo $location_details['location_id'];?>&type=id_proof_2&oper=delete_file">Delete</a><?php }?>
		</div>
                <div class="namemain" style="display:none; width:100%;" id="id_proof_3">
                <label>Id Proof 3</label>
                <input type="file" name="id_proof_3" style="max-width:265px !important;" value="" /><?php if($id_3){?>&nbsp;&nbsp;<a target="_blank" style="padding:5px;" href="view_proof.php?location_id=<?php echo $location_details['location_id'];?>&type=id_proof_3">View</a>&nbsp;&nbsp;<a style="padding:5px;" href="locations.php?location_id=<?php echo $location_details['location_id'];?>&type=id_proof_3&oper=delete_file">Delete</a><?php }?>
                </div>

                <div class="namemain" style="width:100%;">
                <label>Agreement Copy 1</label>
                <input type="file" name="agreement_copy_1" style="max-width:265px !important;" value="" />
		<img src="/img/plus.png" onclick="$('#agreement_copy_2').show();" /><?php if($agreement_1){?>&nbsp;&nbsp;<a target="_blank" style="padding:5px;" href="view_proof.php?location_id=<?php echo $location_details['location_id'];?>&type=agreement_copy_1">View</a>&nbsp;&nbsp;<a style="padding:5px;" href="locations.php?location_id=<?php echo $location_details['location_id'];?>&type=agreement_copy_1&oper=delete_file">Delete</a><?php }?>
                </div>
		
                <div class="namemain" style="display:none; width:100%;" id="agreement_copy_2">
                <label>Agreement Copy 2</label>
                <input type="file" name="agreement_copy_2" style="max-width:265px !important;" value="" />
		<img src="/img/plus.png" onclick="$('#agreement_copy_3').show();" /><?php if($agreement_2){?>&nbsp;&nbsp;<a target="_blank" style="padding:5px;" href="view_proof.php?location_id=<?php echo $location_details['location_id'];?>&type=agreement_copy_2">View</a>&nbsp;&nbsp;<a style="padding:5px;" href="locations.php?location_id=<?php echo $location_details['location_id'];?>&type=agreement_copy_2&oper=delete_file">Delete</a><?php }?>
                </div>
                <div class="namemain" style="display:none; width:100%;" id="agreement_copy_3">
                <label>Agreement Copy 3</label>
                <input type="file" name="agreement_copy_3" style="max-width:265px !important;" value="" /><?php if($agreement_3){?>&nbsp;&nbsp;<a target="_blank" style="padding:5px;" href="view_proof.php?location_id=<?php echo $location_details['location_id'];?>&type=agreement_copy_3">View</a>&nbsp;&nbsp;<a style="padding:5px;" href="locations.php?location_id=<?php echo $location_details['location_id'];?>&type=agreement_copy_3&oper=delete_file">Delete</a><?php }?>
                </div>

		<div class="namemain" style="width:100%;">
                <label>Photo 1</label>
                <input type="file" name="photo_1" style="max-width:265px !important;" value="" />
		<img src="/img/plus.png" onclick="$('#photo_2').show();" /><?php if($photo_1){?>&nbsp;&nbsp;<a target="_blank" style="padding:5px;" href="view_proof.php?location_id=<?php echo $location_details['location_id'];?>&type=photo_1">View</a>&nbsp;&nbsp;<a style="padding:5px;" href="locations.php?location_id=<?php echo $location_details['location_id'];?>&type=photo_1&oper=delete_file">Delete</a><?php }?>
                </div>
		<div class="namemain" style="display:none; width:100%;" id="photo_2">
                <label>Photo 2</label>
                <input type="file" name="photo_2" style="max-width:265px !important;" value="" />
		<img src="/img/plus.png" onclick="$('#photo_3').show();" /><?php if($photo_2){?>&nbsp;&nbsp;<a target="_blank" style="padding:5px;" href="view_proof.php?location_id=<?php echo $location_details['location_id'];?>&type=photo_2">View</a>&nbsp;&nbsp;<a style="padding:5px;" href="locations.php?location_id=<?php echo $location_details['location_id'];?>&type=photo_2&oper=delete_file">Delete</a><?php }?>
                </div>
		<div class="namemain" style="display:none; width:100%;" id="photo_3">
                <label>Photo 3</label>
                <input type="file" name="photo_3" style="max-width:265px !important;" value="" /><?php if($photo_3){?>&nbsp;&nbsp;<a target="_blank" href="view_proof.php?location_id=<?php echo $location_details['location_id'];?>&type=photo_3">View</a>&nbsp;&nbsp;<a href="locations.php?location_id=<?php echo $location_details['location_id'];?>&type=photo_3&oper=delete_file">Delete</a><?php }?>
                </div>
		</div>

		<div id="tabs-2">
		
		<div id="report_contact" style="">
		<strong>Contact 1</strong></br></br>
                <div class="namemain">
		<label>Name *</label>
                <input type="text" name="report_contact_person" id="report_contact_person"value="<?php echo $location_params['report_contact_person'];?>" required >
                </div>

                <div class="namemain">
                <label>Tele Phone *</label>
                <input type="tel" name="report_phone" id="report_phone" value="<?php echo $location_params['report_phone'];?>" >
                <!--<input type="tel" name="report_phone" id="report_phone" value="<?php echo $location_params['report_phone'];?>"  pattern="^\d{4}-\d{3}-\d{4}$" placeholder="Please enter Tele Phone Number (format: xxxx-xxx-xxxx).">-->
                </div>
                <div class="namemain">
                <label>Mobile</label>
                <input type="text" name="report_mobile" value="<?php echo $location_params['report_mobile'];?>" pattern="^[1-9][0-9]{9}$" title="Please enter valid 10 digit mobile number." required>
                </div>
                <div class="namemain">
                <label>Email</label>
                <input type="email" name="report_email" value="<?php echo $location_params['report_email'];?>" >
                </div>

                <div class="namemain">
                <label>Remark</label>
                <input type="text" name="report_remark" value="<?php echo $location_params['report_remark'];?>" >
                </div>
		</div>
		
		<div class="namemain" id="contact_type" style="">
	<?php $cont1=explode(",",$location_params['contact']);?>
                <input id="r_con" type="checkbox" name="contact[]" value="report" <?php if(in_array('report',$cont1)){ echo 'checked';}  ?>> Reports 
                <input id="m_con" type="checkbox" name="contact[]" value="maintainance" <?php if(in_array('maintainance',$cont1)){ echo 'checked'; }?>> Device Maintainance
                <input id="o_con" type="checkbox" name="contact[]" value="other" <?php if(in_array('other',$cont1)){ echo 'checked';}  ?>> other
                </div>
		</br>

		<div id="maintainance_contact" >
		<strong>Contact 2</strong></br></br>
                <div class="namemain">
                <label>Name </label>
                <input type="text" name="maintainance_contact_person" value="<?php echo $location_params['maintainance_contact_person'];?>" >
                </div>

                <div class="namemain">
                <label>Phone </label>
                <input type="text" name="maintainance_phone" value="<?php echo $location_params['maintainance_phone'];?>" pattern="^[1-9][0-9]{9}$" title="Please enter valid 10 digit mobile number.">
                </div>
                <div class="namemain">
                <label>Mobile</label>
                <input type="text" name="maintainance_mobile" value="<?php echo $location_params['maintainance_mobile'];?>" pattern="^[1-9][0-9]{9}$" title="Please enter valid 10 digit mobile number.">
                </div>
                <div class="namemain">
                <label>Email</label>
                <input type="email" name="maintainance_email" value="<?php echo $location_params['maintainance_email'];?>" >
                </div>

                <div class="namemain">
                <label>Remark</label>
                <input type="text" name="maintainance_remark" value="<?php echo $location_params['maintainance_remark'];?>" >
                </div>
                </div>
		
		<div class="namemain" id="contact_type" style="">
	<?php $cont2=explode(",",$location_params['contact_2']);?>
                <input type="checkbox" name="contact_2[]" value="report"<?php if(in_array('report',$cont2)){ echo 'checked';}?>> Reports 
                <input type="checkbox" name="contact_2[]" value="maintainance" <?php if(in_array('maintainance',$cont2)){ echo 'checked';}?>> Device Maintainance
                <input type="checkbox" name="contact_2[]" value="other" <?php if(in_array('other',$cont2)){ echo 'checked';}?>> other
                </div>
		</br>

		<div id="other_contact" >
		<strong>Contact 3</strong></br></br>
                <div class="namemain">
                <label>Name </label>
                <input type="text" name="other_contact_person" value="<?php echo $location_params['other_contact_person'];?>">
                </div>

                <div class="namemain">
                <label>Phone </label>
                <input type="text" name="other_phone" value="<?php echo $location_params['other_phone'];?>" pattern="^[1-9][0-9]{9}$" title="Please enter valid 10 digit mobile number.">
                </div>
                <div class="namemain">
                <label>Mobile</label>
                <input type="text" name="other_mobile" value="<?php echo $location_params['other_mobile'];?>" pattern="^[1-9][0-9]{9}$" title="Please enter valid 10 digit mobile number.">
                </div>
                <div class="namemain">
                <label>Email</label>
                <input type="email" name="other_email" value="<?php echo $location_params['other_email'];?>" >
                </div>

                <div class="namemain">
                <label>Remark</label>
                <input type="text" name="other_remark" value="<?php echo $location_params['other_remark'];?>" >
                </div>
                </div>  
		<div class="namemain" id="contact_type" style="">
	<?php $cont3=explode(",",$location_params['contact_3']);?>
                <input type="checkbox" name="contact_3[]" value="report"<?php if(in_array('report',$cont3)){ echo 'checked';}?>> Reports
                <input type="checkbox" name="contact_3[]" value="maintainance" <?php if(in_array('maintainance',$cont3)){ echo 'checked';}?>> Device Maintainance
                <input type="checkbox" name="contact_3[]" value="other" <?php if(in_array('other',$cont3)){ echo 'checked';}?>> other
                </div>

		
		</div>

		<div id="tabs-5">
		<table class="tabs-listing" cellpadding="0" cellspacing="0">
                <td align="center">
                <table class="tabs-questions">
		<tr>
		<td>A). Please select the appliance/vehicle if present at the domestic/ non-domestic/ agricultural site</br></br>
		<input type="checkbox" name="appliances[]" value="television" <?php if(in_array('television',$location_params['appliances'])){echo 'checked';}?>> Telivision
		<input type="checkbox" name="appliances[]" value="refrigerator" <?php if(in_array('refrigerator',$location_params['appliances'])){echo 'checked';}?>> Refrigerator
		<input type="checkbox" name="appliances[]" value="2 wheeler" <?php if(in_array('2 wheeler',$location_params['appliances'])){echo 'checked';}?>> 2 Wheeler
		<input type="checkbox" name="appliances[]" value="4 wheeler" <?php if(in_array('4 wheeler',$location_params['appliances'])){echo 'checked';}?>> 4 Wheeler
		</td>
		</tr>
		<tr>
		<td>B). Has the location ever experienced appliance failure?</br></br>
                <input type="radio"  id="appliance_failuare" name="appliance_failuare" value="Yes" <?php if($location_params['appliance_failuare']=='Yes'){echo 'checked';}?> onchange="$('#failure_details').show();">Yes
                <input type="radio" name="appliance_failuare" value="No" <?php if($location_params['appliance_failuare']=='No'){echo 'checked';}?> onchange="$('#failure_details').hide();">No
		</td>
		</tr>
		<tr>
		<td style="display:none;" id="failure_details">B1). If yes, then which one and how often?</br>
                <input type="text" name="appliance_failuare_details" value="<?php echo nl2br($location_params['appliance_failuare_details']);?>">
		</tr>
		</td>
		</tr>
		<tr>
		<td>C). Does the location have an inverter/diesel backup?</br></br>
                <input type="radio" id="inverter_backup" name="inverter_backup" value="Yes" <?php if($location_params['inverter_backup']=='Yes'){echo 'checked';}?> onchange="$('#yes').show();">Yes
                <input type="radio" name="inverter_backup" value="No" <?php if($location_params['inverter_backup']=='No'){echo 'checked';}?> onchange="$('#yes').hide();">No
		<div id="yes" style="display:none;">
		<label>When was it puchased?</label><input type="text" name="inverter_backup_details" value="<?php echo nl2br($location_params['inverter_backup_details']);?>"></br>
		<label>Is the inverter/UPS used regularly?</label>
                <input type="radio" name="regular_ups_use" value="Yes" <?php if($location_params['regular_ups_use']=='Yes'){echo 'checked';}?>>Yes
                <input type="radio" name="regular_ups_use" value="No"  <?php if($location_params['regular_ups_use']=='No'){echo 'checked';}?>>No
		</div>
		</td>
		</tr>
		<tr>
		<td>D). How enthusiastic was the family?</br>
                <input type="radio" name="enthusiastic_family" value="Happy" <?php if($location_params['enthusiastic_family']=='Happy'){echo 'checked';}?>> Happy
                <input type="radio" name="enthusiastic_family" value="Indifferenrt" <?php if($location_params['enthusiastic_family']=='Indifferenrt'){echo 'checked';}?>> Indifferenrt
                <input type="radio" name="enthusiastic_family" value="Unhappy" <?php if($location_params['enthusiastic_family']=='Unhappy'){echo 'checked';}?>> Unhappy
                </td>
		</tr>
		</br>	
		</table>
                </td>
                </tr>
                </table>

		</div>
                <table><tr>
                <td colspan="3"><input type="submit" name="submit"  id="submit"  style="margin-left:300px !important; width:60px !important;" value="<?php if($is_update){ echo 'Update';   }else{ echo 'Save'; }?>">

</td>
                </tr></table>
	</div>
        </form>
	</div><!-- end of tabs div -->

	<div class="table" align="center">
	<span style="float:right; margin-bottom:10px; font-weight:bold; font-color:#43729f;"><a href="export_locations.php">Export All</a></span>
	</br>
	<table class="inputlising">
	<tr>
	<td>
	<input type="text" id="loc_name" name="loc_name" title="Enter location name and press enter to view details" placeholder="Enter location name."/>
	</td>
	<td>OR</td>
	<td>
                <select name="state" id="state" onChange="$('#location').show(); if($(this).val()==''){ $('#loc').hide(); } else{ $('#loc').show();}" >
                <option value="">Select to view details</option>
                <?php foreach($loc_states as $l_state){?>
                <option value="<?php echo $l_state['state'];?>" ><?php echo $l_state['state'];?></option>
                <?php }?>
                </select>
        </td>

        <td id="loc">
                <!--<select name="location_name" id="location" style="display:none;" onChange="getLocationDetails($(this).val());" >
                <option value="">Select to view details</option>
		<?php foreach($locations as $loc){?>
                <option value="<?php echo $loc['name'];?>" ><?php echo $loc['name'];?></option>
		<?php }?>
                </select>-->
        </td>
        </tr>
	</table>
	</div>

	<div class="table" align="center" id="location_data">
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
