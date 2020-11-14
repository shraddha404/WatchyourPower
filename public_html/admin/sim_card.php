<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";
$sim_card= $_POST;
if($_POST['submit']=='Save'){
        $details = $_POST;
        if($u->addSimCard($details)){
                $msg = "<span class=\"message\">Simcard added successfully.</span>";
        }else{
                $msg = "<span class=\"error\">$u->error</span>";
        }
}


if($_POST['submit']=='Update'){
$details = $_POST;
        if($u->updateSimCard($details)){
                $msg = "<span class=\"message\">Simcard Updated successfully.</span>"; 
        }
        else{
                $msg = "<span class=\"error\">$u->error</span>"; } }

if($_GET['sim_card_id']!='' && $_GET['oper']=='delete'){
        if($u->removeSimCard($_GET['sim_card_id'])){
                $msg = "<span class=\"message\">Sim card deleted successfully.</span>";
        }
        else{
                $msg = "<span class=\"error\">$u->error</span>";
        }
}


if($_GET['sim_card_id']!='' && $_GET['oper']=='update'){
$is_update = true;
$sim_card_id = $_GET['sim_card_id'];
$sim_card = $u->getSimCardDetails($sim_card_id);
}

$sim_cards = $u->getAllSimCards($_GET);

$billing_cycle = array('monthly'=>'Monthly', 'quarterly'=>'Quarterly' ,'annual'=>'Annual');

$main_menu = 'Data Settings';
$current = 'sim_cards';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/html_head.php"; ?>
<script>
$(function() {
$( "#activation_date" ).datetimepicker({
format:'d/m/Y',
formatDate:'d/m/Y',
timepicker:false,
closeOnDateSelect:true
});

$( "#billing_date" ).datetimepicker({
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

	<?php echo $msg;?>
	<div class="table">
	<form method="post" action="">
	<?php if($is_update){  ?>
	<input type="hidden" name="sim_card_id" value="<?php echo $_GET['sim_card_id']; ?>">
	<?php } ?>
	<table class="listing" cellpadding="0" cellspacing="0">
	<tr>
	<td align="center">
	<table class="inputlisting">
	<tr>
	<td >Sim Number *</td>
	<td >
	<input type="text" name="sim_no" title="Please Enter Sim Card Number" value="<?php echo $sim_card['sim_no'];?>" pattern="^[1-9][0-9]{18}[a-zA-z0-9]{1}$" placeholder="20 digit sim number." required><!--<input type="text" name="sim_no_last_char" value="<?php echo $sim_card['sim_no_last_char'];?>" pattern="^[a-zA-Z]{1}$" title="Please enter valid 1 character." size="1">--></td>
	</tr>

	<tr>
	<td >Mobile Number *</td>
	<td >
	<input type="text" name="mobile_no" title="Please Enter Mobile Number"value="<?php echo $sim_card['mobile_no']; ?>" pattern="^[1-9][0-9]{9}$" title="Please enter valid 10 digit mobile number." required></td>
	</tr>

	<tr>
	<td >Company *</td>
	<td>
	<select name="company"  required title="Please Enter Company Name of Sim Card">
                <option value="">-- Select  --</option> 
                <option value="Airtel" <?php if($sim_card['company']=='Airtel'){ echo "selected"; }?>>Airtel</option>                
                <option value="Idea" <?php if($sim_card['company']=='Idea'){ echo "selected"; }?>>Idea</option>                
                <option value="Reliance" <?php if($sim_card['company']=='Reliance'){ echo "selected"; }?>>Reliance</option>
                <option value="Tata Docomo" <?php if($sim_card['company']=='Tata docomo'){ echo "selected"; }?>>Tata Docomo</option>
                <option value="Vodafone" <?php if($sim_card['company']=='Vodafone'){ echo "selected"; }?>>Vodafone</option>


        </select>
	</td>
	</tr>
<tr>
	<td >Currently With</td>
	<td >
	<input type="text" name="currently_with" title="Please Enter Which Sim Card you currently Use" value="<?php if($is_update){ echo $sim_card['currently_with']; }?>"></td>
	</tr>
	<tr>
	<td >plan data size *</td>
	<td >
	<input type="text" name="plan_data_size" title="Please Enter Plan Data size(3g,2g etc.)"value="<?php if($is_update){ echo $sim_card['plan_data_size']; }?>" required></td>
	</tr>
<tr>
	<td >Plan cost *</td>
	<td >
	<input type="text" name="plan_cost" title="Please Enter  Data Plan cost(100,200,300 etc.)"value="<?php if($is_update){ echo $sim_card['plan_cost']; }?>" required></td>
	</tr>

	<!--<tr>
	<td >Network</td>
	<td >
	<input type="text" name="network" value="<?php if($is_update){ echo $sim_card['network']; }?>"></td>
	</tr>-->

	<tr>
	<td >Billing Cycle *</td>
	<td >
	<select name="billing_cycle" required title="Please Enter Your Billing Cycle">
		<option value="">-- Select --</option>
		<?php foreach($billing_cycle as $key => $value){ ?>
		<option value="<?php echo $key; ?>" <?php if($sim_card['billing_cycle'] == $key){ echo 'selected'; } ?>><?php echo $value; ?></option>
		<?php } ?>
		</select>
	</td>
	</tr>

	<tr>
	<td >Status *</td>
	<td >
	<select name="status" required title="Please Enter Status">
		<option value="active" <?php if($sim_card['status'] == 'active'){ echo 'selected'; } ?>>Active</option>
		<option value="inactive" <?php if($sim_card['status'] == 'inactive'){ echo 'selected'; } ?>>Inactive</option>
		<option value="discard" <?php if($sim_card['status'] == 'discard'){ echo 'selected'; } ?>>Discard</option>
		<option value="misplaced" <?php if($sim_card['status'] == 'misplaced'){ echo 'selected'; } ?>>Misplaced</option>
		<option value="lost" <?php if($sim_card['status'] == 'lost'){ echo 'selected'; } ?>>Lost</option>
		</select>
	</td>
	</tr>

	<tr>
	<td >Activation Date *</td>
	<td >
	<input type="text" class="datepick" name="activation_date" title="Please Enter Activation Date for your Sim card" value="<?php if($is_update){ echo date('m/d/Y',strtotime($sim_card['activation_date'])); }?>" id="activation_date" required></td> 
	</tr>
<tr>
	<td >Billing due date</td>
	<td >
	<input type="text" name="billing_due_date" title="Please Enter Billing Due Date for your Sim card"value="<?php if($is_update){ echo $sim_card['billing_due_date']; }?>" id="billing_date" ></td> 
	</td>
	</tr>

	<tr>
	<td >&nbsp;</td>
	<td ><input type="submit" name="submit" value="<?php if($is_update){ echo "Update";   }else{ echo "Save"; }?>"></td>
	</tr>
	</table>

	</td></tr></table>
	</form>

	</div>
	<div class="table" align="center">
	<span style="float:right; margin-bottom:10px; font-weight:bold; font-color:#43729f;"><a href="export_sim_data.php">Export All</a></span></br>
	<table class="listing" style="width:70%;" align="center">
        <tr>
        <td style="border:none; width:30%;">
        <input type="number" id="mobile_no" name="mobile_no" title="Enter mobile number and press enter to view details" placeholder="Enter mobile number." />
        </td>
        <td style="border:none; width:10%;" align="center">OR</td>
        <td style="border:none; width:30%;">
                <select name="company" id="company" onChange="getSimcardsByCompany($(this).val()); if($(this).val()==''){ $('#sim_data').hide(); } else{ $('#sim_data').show();}" >
                <option value="">--Select Network--</option>
                <option value="All">All</option>
                <option value="Airtel">Airtel</option>
                <option value="Idea">Idea</option>                
                <option value="Tata Docomo">Tata Docomo</option>
                <option value="Reliance">Reliance</option>
                <option value="Vodafone">Vodafone</option>
                </select>
        </td>
        </table>
        </div>

	
        <div class="table" align="center" id="sim_data">
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
