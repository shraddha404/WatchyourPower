<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";

if($_POST['submit'] == 'Save'){
$details = $_POST ;
//print_r($_POST);
 if($u->adduploadedreports($details,$_FILES)){
                $msg = "<span class=\"message\">File Uploaded successfully.</span>";
        }else{
                $msg = "<span class=\"error\">$u->error</span>";
		$details=$_POST;
		$is_update=true;
        }	

}

if($_POST['submit']=='Update'){

$details = $_POST;
        if($u->updateAnalysisReport($details,$_FILES)){
                $msg = "<span class=\"message\">File Updated successfully.</span>";
        }
        else{
                $msg = "<span class=\"error\">$u->error</span>";
        }
}
if($_GET['id']!='' && $_GET['oper']=='delete'){
        if($u->deleteAnalysisReport($_GET['id'])){
                $msg = "<span class=\"message\">Analysis report deleted successfully.</span>";
		//header( "refresh:3;url=/admin/devices.php" );
		header( "location:/admin/upload_reports.php" );
        }
        else{
                $msg = "<span class=\"error\">$u->error</span>";
        }
}
if($_GET['id']!='' && $_GET['oper']=='update'){
$is_update = true;
$id = $_GET['id'];
$ReportDetails = $u->getReportDetails($id);
}
$details = $_GET;


$reports_list = $u->uploadedreportlist(); 

$main_menu = 'Management';
$current = 'upload_reports';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/html_head.php"; ?>
<script>
$(function() {
$( "#date_published" ).datetimepicker(
{formatTime:'H:i',
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

	<form method="post" action="" id="upload_reports" onsubmit="return validateDate('1');" enctype="multipart/form-data">

	<div class="table">
	<table class="listing" cellpadding="0" cellspacing="0">
        <tr>
        <td align="center">

        <table class="inputlisting">
	<input type="hidden" name="id" value="<?php echo $ReportDetails['id']; ?>">
	<tr>
		<td>Title</td>
		<td><input type="text"  name="title" id="title" value="<?php if($ReportDetails['title']!=''){echo $ReportDetails['title'];}?>"  required></td>
		
	</tr>
	<tr>
		<td>Upload File *</td>
		<td><input type="file" name="file_path" id="file_path" value="<?php if($ReportDetails['file_path']!=''){echo $ReportDetails['file_path'];}?>" required></td>
		
	</tr>
          <tr>
		<td>Description *</td>

		<td><textarea  name="description" id="description" class="form-control" rows="5" cols="30"  required><?php if($ReportDetails['description']!=''){echo $ReportDetails['description'];}?></textarea></td>
		
	</tr>
	<tr>
		<td>Pusblished on *</td>
		<td><input type="text" class="datepick" name="date_published" id="date_published" value="<?php if($ReportDetails['date_published']){ echo $ReportDetails['date_published']; }?>"  required></td>
		
	</tr>
	
	<tr>
		<td>&nbsp;</td>
	<td><input type="submit" name="submit" value="<?php if($is_update){ echo 'Update';   }else{ echo 'Save'; }?>"></td>
	</tr>
	</table>

	</td>
	</tr>
	</table>
	</form>

	</div>

	<?php echo $msg;?>

	<?php if(!empty ($reports_list)){ ?>
	<div class="table">
	<img src="/img/bg-th-left.gif" width="8" height="7" alt="" class="left" /> 
	<img src="/img/bg-th-right.gif" width="7" height="7" alt="" class="right" />

	<table class="listing tablesorter" id="listing" cellpadding="0" cellspacing="0">
	<thead>
	<tr>
	<th class="first">Sr. No</th>
	<th>Title</th>
	<th>Description</th>
	<th>File Path</th>
	<th>Date Published</th>
	<th>Action</th>
	</tr>
	</thead>
	<tbody>
	<?php 
	$i=1;
	foreach($reports_list as $list) {
	$date =  date('m/d/Y h:i', get_strtotime($list['date_published']));
	
	?>
	<tr>
	<td class="first style1"><?php echo $i; ?> </td>
	<td><?php echo $list['title']; ?></td>
	<td><?php echo $list['description']; ?></td>
        <td><?php echo $list['file_path']; ?></td>
	<td><?php echo $date; ?></td>
<td><a href="upload_reports.php?id=<?php echo $list['id'];?>&oper=delete" rel="#overlay" onclick="return confirm('Are you sure you want to delete report?');"><img src="/img/hr.gif" width="16" height="16" alt="" /></a>&nbsp;<a href="upload_reports.php?id=<?php echo $list['id'];?>&oper=update" rel="#overlay"><img src="/img/edit-icon.gif" width="16" height="16" alt="edit" /></a></td>
	</tr>
	<?php $i++; } ?>

	</tbody>
	</table>
	</div>
	<?php } ?>

	<div class="table">

      </div> <!-- end table class div -->

</div> <!-- end div center column  -->

</div>
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</div>



</body>
</html>
