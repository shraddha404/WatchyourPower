<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";
$locations = $u->getAllLocations(array('restore_all'=> 1));
$location_name=$_GET['location'];
$state= $_GET['state'];
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
</script>
<table class="listing" cellpadding="0" cellspacing="0">
        <tr>
        <th class="first" >Location Name</th>
        <th class="last" colspan="3" >Action</th>
        </tr>
	
        <?php foreach($locations as $location){
		if($location['name']==$location_name){?>
        <tr>
        <td class="first style1" style="width:70% !important;"> <?php echo $location['name'];?></td>
        <td style="text-align:center !important; width:5%;"><a href="locations.php?location_id=<?php echo $location['id'];?>&oper=update" rel="#overlay"><img src="/img/edit-icon.gif" width="16" height="16" alt="edit" /></a></td>
        <td class="last" style="width:5% !important;text-align:center !important;"><a href="locations.php?location_id=<?php echo $location['id'];?>&oper=delete" rel="#overlay" onclick="return confirm('Are you sure you want to delete location?');"><img src="/img/hr.gif" width="16" height="16" alt="" /></a></td><td><a onclick="show_remark_details(<?php echo $location['id'];?>,'locations')" value="">View All Remarks</a></td>
        </tr>
        	<?php } elseif($location['state']==$state){ ?>
	<tr>
        <td class="first style1" style="width:70% !important;"> <?php echo $location['name'];?></td>
        <td style="text-align:center !important; width:5%;"><a href="locations.php?location_id=<?php echo $location['id'];?>&oper=update" rel="#overlay"><img src="/img/edit-icon.gif" width="16" height="16" alt="edit" /></a></td>
        <td class="last" style="width:5% !important;text-align:center !important;"><a href="locations.php?location_id=<?php echo $location['id'];?>&oper=delete" rel="#overlay" onclick="return confirm('Are you sure you want to delete location?');"><img src="/img/hr.gif" width="16" height="16" alt="" /></a></td><td><a onclick="show_remark_details(<?php echo $location['id'];?>,'locations')" value="">View All Remarks</a></td>
        </tr>
		<?php }  
            }  ?> 
        </table>
<div id="remark_popup"  align="center" style="display:none;">
    <div  id="remark_content" class="popup" style="height:350px;width:300px;"></div>
</div>

