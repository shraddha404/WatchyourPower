<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";
$_GET['flag']=1;
$sim_cards = $u->getAllSimCards($_GET);
$mobile=$_GET['mobile_no'];
$company=$_GET['company'];
?>
<table class="listing" cellpadding="0" cellspacing="0">
<tr>
        <th class="first">Sim Card No.</th>
        <th>Company</th>
        <th>Plan Data size</th>
        <th>Plan Cost</th>
        <th>Billing Cycle</th>
        <th>Status</th>
        <th colspan="3" class="last">Action</th>
        </tr>

        <?php foreach($sim_cards as $sim){
		 
		if($sim['mobile_no']==$mobile){
	?>
	
        <tr>
        <td class="first style1" style="width:auto;"> <?php echo $sim['sim_no'];?></td>
        <td class="style1" style="width:auto;"> <?php echo $sim['company'];?></td>
        <td class="style1" style="width:auto;"> <?php echo $sim['plan_data_size'];?></td>
        <td class="style1" style="width:auto;"> <?php echo $sim['plan_cost'];?></td>
        <td class="style1" style="width:auto;"> <?php echo $sim['billing_cycle'];?></td>
        <td class="style1" style="width:auto;"> <?php echo ucfirst($sim['status']);?></td>
        <td style="text-align:center !important; width:5%;"><a href="/admin/sim_card.php?sim_card_id=<?php echo $sim['id'];?>&oper=update" rel="#overlay"><img src="/img/edit-icon.gif" width="16" height="16" alt="edit" /></a></td>
        <td style="text-align:center !important; width:5%;" class="last"><a href="/admin/sim_card.php?sim_card_id=<?php echo $sim['id'];?>&oper=delete" rel="#overlay"><img src="/img/hr.gif" width="16" height="16" alt="" /></a></td>
        </tr>
        <?php }
		 if($sim['company']==$company || $company =='All'){ ?>
	<tr>
        <td class="first style1" style="width:auto;"> <?php echo $sim['sim_no'];?></td>
        <td class="style1" style="width:auto;"> <?php echo $sim['company'];?></td>
        <td class="style1" style="width:auto;"> <?php echo $sim['plan_data_size'];?></td>
        <td class="style1" style="width:auto;"> <?php echo $sim['plan_cost'];?></td>
        <td class="style1" style="width:auto;"> <?php echo $sim['billing_cycle'];?></td>
        <td class="style1" style="width:auto;"> <?php echo ucfirst($sim['status']);?></td>
        <td style="text-align:center !important; width:5%;"><a href="/admin/sim_card.php?sim_card_id=<?php echo $sim['id'];?>&oper=update" rel="#overlay"><img src="/img/edit-icon.gif" width="16" height="16" alt="edit" /></a></td>
        <td style="text-align:center !important; width:5%;" class="last"><a href="/admin/sim_card.php?sim_card_id=<?php echo $sim['id'];?>&oper=delete" rel="#overlay"><img src="/img/hr.gif" width="16" height="16" alt="" /></a></td>
        </tr>


	<?php } }?>
        
</table>

