<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";
$devices = $u->getDevicesByCriteria($_GET);
//$devices = $u->getAllDevices();
$device_code=$_GET['device_code'];
$status= $_GET['status'];
//$search_str=$_GET['search_str'];
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
function show_version_logs(id){
    	$.post('version_history.php', {id:id}, function(data) {
	jQuery('#version_content').html(data);
        jQuery('#version_popup').dialog( { resizable: false,
        modal: true,
        title: 'Version Log Details',
        width: 500,
        height: 550,
        overlay: { backgroundColor: "#FFF", opacity: 0.5 }
        });

    });
} 
</script>
<table class="listing" cellpadding="0" cellspacing="0">
        <tr>
	<th class="first">Device Id</th>
        <th >Date of Receipt</th>
        <th >Status</th>
        <th class="last"colspan="4">Action</th>
        </tr>
	
        <?php foreach($devices as $device){
	//if($status == $device['device_status']){
	?>
        <tr>
        <td class="first style1" style="width:auto !important;"> <?php echo $device['device_id_string'];?></td>
	<td class="style1"> <?php echo date('m/d/Y',strtotime($device['installed']));?></td>
        <td class="style1"> <?php echo $device['device_status']; ?></td>

        <td style="text-align:center !important; width:5%;"><a href="devices.php?device_id=<?php echo $device['device_id'];?>&oper=update" rel="#overlay"><img src="/img/edit-icon.gif" width="16" height="16" alt="edit" /></a></td>
        <td  style="text-align:center !important; width:5%;"><a href="devices.php?device_id=<?php echo $device['device_id'];?>&oper=delete" rel="#overlay" onclick="return confirm('Are you sure you want to delete device?');"><img src="/img/hr.gif" width="16" height="16" alt="" /></a></td><td class="last"><a onclick="show_version_logs(<?php echo $device['device_id'];?>)" value="">Version History</a>&nbsp;&nbsp; | &nbsp;&nbsp;<a onclick="show_remark_details(<?php echo $device['device_id'];?>,'devices')" value="">View All Remarks</a></td>
        </tr>
	<?php } //} ?>
        </table>
<div id="remark_popup"  align="center" style="display:none;">
                <div  id="remark_content" class="popup" style="height:350px;width:300px;">
		</div>
</div>

<div id="version_popup"  align="center" style="display:none;">
                <div  id="version_content" class="popup" style="height:350px;width:300px;">
		</div>
</div>
