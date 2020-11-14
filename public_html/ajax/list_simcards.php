<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
$details = $_POST;
if($details['oper']=='update'){
$sim_cards = $u->getAllSimCards($_GET);
}else{
$sim_cards = $u->getAvailableSimCards();
}
?>
<select id="<?php echo $details['simcard_div_id']; ?>" name="<?php echo $details['simcard_div_id']; ?>">
	<option value="">-- Select  --</option>
	<?php foreach($sim_cards as $sim){ 
	if($sim['company'] == $details['sim_card_company']){
	?>
	<option value="<?php echo $sim['id']; ?>" <?php if($sim['id'] == $device_installation['sim_card_id']){ echo 'selected'; }?>><?php echo $sim['sim_no']; ?></option>
	<?php } } ?>
	</select>

