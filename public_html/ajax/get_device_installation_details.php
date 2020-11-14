<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";
$criteria = $_GET['criteria'];
if($criteria!=''){
$installations_by_criteria = $u->getAllDeviceInstallationsByCriteria($criteria);
}
$device_installations = $u->getAllDeviceInstallations();
$status= $_GET['status'];
?>
<script type="text/javascript">
function show_remark_details(id,remark_id){
    	$.post('remarks.php', {id:id,remark_id:remark_id}, function(data) {
	jQuery('#remark_content').html(data);
        jQuery('#remark_popup').dialog( { resizable: false,
        modal: true,
        title: 'Remarks Details',
        width: 500,
        height: 550,
        overlay: { backgroundColor: "#FFF", opacity: 0.5 }
        });

    });
} 
</script><table class="listing" cellpadding="0" cellspacing="0" style="width:100%; align:center;">

        <tr>
        <th class="first" style="width:auto;">Device Code</th>
        <th class="first" style="width:auto;">Status</th>
        <th style="width:auto;" colspan="4" class="last">Action</th>
        </tr>

        <?php if($criteria==''){
	foreach($device_installations as $installation){
	if($installation['installation_status']==$status){
	?>
        <tr>
        <td class="first style1" style="width:auto;"> <?php echo $installation['device_id_string'];?></td>
        <td class="first style1" style="width:auto;"> <?php if($installation['installation_status'] == 0){ echo 'Testing'; }else{ echo 'Deployed';}?></td>
        <td style="text-align:center !important; width:auto;"><a href="device_installation.php?installation_id=<?php echo $installation['installation_id'];?>&oper=update" rel="#overlay"><img src="/img/edit-icon.gif" width="16" height="16" alt="edit" /></a></td>
	<!--
        <td class="last" style="text-align:center !important; width:auto;"><a href="device_installation.php?installation_id=<?php echo $installation['installation_id'];?>&oper=delete" rel="#overlay"><img src="/img/hr.gif" width="16" height="16" alt="" /></a></td>
	-->
        <td><a onclick="show_remark_details(<?php echo $installation['installation_id'];?>,'device_installations')" value="">View All Remarks</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="../reports.php?location_id=<?php echo $installation['location_id'];?>" >View Report</a></td></tr>
        <?php } } }else{ 
	foreach($installations_by_criteria as $installation){
	?>

	<tr>
        <td class="first style1" style="width:auto;"> <?php echo $installation['device_id_string'];?></td>
        <td class="first style1" style="width:auto;"> <?php if($installation['installation_status'] == 0){ echo 'Testing'; }else{ echo 'Deployed';}?></td>
        <td style="text-align:center !important; width:auto;"><a href="device_installation.php?installation_id=<?php echo $installation['installation_id'];?>&oper=update" rel="#overlay"><img src="/img/edit-icon.gif" width="16" height="16" alt="edit" /></a></td>
        <td class="last" style="text-align:center !important; width:auto;"><a href="device_installation.php?installation_id=<?php echo $installation['installation_id'];?>&oper=delete" rel="#overlay"><img src="/img/hr.gif" width="16" height="16" alt="" /></a></td>
       <td><a onclick="show_remark_details(<?php echo $installation['installation_id'];?>,'device_installations')" value="">View All Remarks</a>&nbsp;&nbsp;| &nbsp;&nbsp;<a href="../reports.php?location_id=<?php echo $installation['location_id'];?>" >View Report</a></td> </tr>

	<?php } }?>

        </table>
<div id="remark_popup"  align="center" style="display:none;">
    <div  id="remark_content" class="popup" style="height:350px;width:300px;"></div>
</div>
