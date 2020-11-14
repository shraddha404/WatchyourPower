<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";

if($_POST['id']){
$is_update=true;
$id=$_POST['id'];

$remarks = $u->getVersionHistory($id);
}
$main_menu = 'Settings';
$current = 'remark';
?>
<?php if(!empty($remarks)){?>



	<?php echo $msg;?>
	<div class="table" align="left">
	
<table style="width:50%;" class="sc_rc">
			<?php foreach($remarks as $remark){?>		
<tbody><tr><td valign="top" style="width:100px; padding:5px; border-top-left-radius: 5px;">
			<div><?php $UserDetails = $u->getUserDetails($remark['created_by']);echo $UserDetails['name']; ?></div>
						<div></div>
						<div></div>
						<div></div>
						
					</td>
<td valign="top" style="padding:5px; background:#fff; ">
						<div style="float:right;margin:0px 0px 15px 15px;font-size:12px;color:#aaa"><?php echo $remark['date']; ?></div>
				                <div><p><?php echo $remark['version']; ?> </p></div>
						<div ></div>
        	        			
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
<tr><td valign="top" style="width:100px; padding:5px;">
No version log for this device.</td>

			</tbody></table>
	</div>
	

	<div class="table">
      </div> <!-- end table class div -->

<?php }?>
<!--<div id="right-column">
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/right_nav.php"; ?>
</div>-->


<!-- <div align=center>This template  downloaded form <a href='#'>free website templates</a></div> -->


