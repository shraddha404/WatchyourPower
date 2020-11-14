<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";

if($_POST['remark_id']=='locations'){
$is_update=true;
$remark_id=$_POST['remark_id'];
$param_id=$_POST['id'];
$remarks = $u->getRemarks($remark_id,$param_id);
}
else if($_POST['remark_id']=='devices'){
$is_update=true;
$remark_id=$_POST['remark_id'];
$param_id=$_POST['id'];
$remarks = $u->getRemarks($remark_id,$param_id);
}
else if($_POST['remark_id']=='device_installations'){
$is_update=true;
$remark_id=$_POST['remark_id'];
$param_id=$_POST['id'];
$remarks = $u->getRemarks($remark_id,$param_id);
}
//print_r($remarks);
$main_menu = 'Settings';
$current = 'remark';
?>
<?php if(!empty($remarks)){?>



	<?php echo $msg;?>
	<div class="table" align="left">
	
<table style="width:50%; " class="sc_rc">
			<?php foreach($remarks as $remark){?>		
<tbody><tr><td>				                <div><p><?php echo $remark['remark']; ?> </p></div></td></tr>


<tr>
<td valign="top" style="float:right;margin:0px 0px 15px 15px;font-size:12px;">
			<div><b><?php $UserDetails = $u->getUserDetails($remark['created_by']);echo $UserDetails['name']; ?></b></div>
						
					

						<div style="float:right;margin:0px 0px 15px 15px;font-size:12px;color:#aaa"><?php echo $remark['created_on']; ?></div>


        	        			
					</td>
				</tr>
	<?php }?>
			</tbody></table>
	</div>
	

	<div class="table">
      </div> <!-- end table class div -->

<?php } else{?>



	
	<div class="table" align="center"><?php echo $msg;?>
<table style="width:50%;" class="sc_rc"> <!-- end div center column  -->
<tr><td valign="top" style="width:100px; padding:5px; border-right:1px solid #aaa; border-bottom-left-radius: 5px; border-top-left-radius: 5px;">
No Remarks for this location.</td>

			</tbody></table>
	</div>
	

	<div class="table">
      </div> <!-- end table class div -->

<?php }?>
<!--<div id="right-column">
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/right_nav.php"; ?>
</div>-->


<!-- <div align=center>This template  downloaded form <a href='#'>free website templates</a></div> -->


