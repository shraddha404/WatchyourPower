<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";
if($_POST['submit']=='Save'){
        $details = $_POST;
        if($u->addParamColor($details)){
                $msg = "<span class=\"message\">Color added successfully.</span>";
        }else{
                $msg = "<span class=\"error\"$u->error</span>";
        }
}

if($_POST['submit']=='Update'){
$details = $_POST;
        if($u->addParamColor($details)){
                $msg = "<span class=\"message\">Color setting updated successfully.</span>";
        }
        else{
                $msg = "<span class=\"error\">$u->error</span>";
        }
}



if($_GET['param_id']!='' && $_GET['oper']=='update'){
$is_update=true;
$param_id=$_GET['param_id'];
$param_details = $u->getSummaryParamDetails($param_id);
}
$params = $u->getAllSummaryParams(); 
$main_menu = 'Display Settings';
$current = 'color_settings';
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/html_head.php"; ?>
<script>
$(function() {
$("#valid_till").datetimepicker(
{
format:'d/m/Y',
formatDate:'d/m/Y',
timepicker:false
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


	<div class="table">
	<form method="post" action="">
	<table class="listing" cellpadding="0" cellspacing="0">
	<tr>
        <td align="center">
        <table class="inputlisting">
	<tr>
	<td>Param *</td>
	<td>
		<select name="param_id" required>
        	<option value="">-- Select  --</option>
       			<?php foreach($params as $param){ ?>
       		<option value="<?php echo $param['id']; ?>" <?php if($param_details['id']==$param['id']){ echo 'selected="selected"';} ?>><?php echo $param['param']; ?></option>
        		<?php } ?>
        	</select>
        </td>
	</tr>
	<tr>
        <td>Color</td>
        <td><input type="color"  name="graph_display_color"  value="<?php echo $param_details['graph_display_color']; ?>" ></td>
        </tr>

	<tr>
	<td></td>
	<td><input type="submit" name="submit" value="<?php if($is_update){ echo "Update";   }else{ echo "Save"; }?>"></td>
	</tr>

	</table>
	</td></tr></table>
	</form>

	</div>
	<?php echo $msg;?>
	<div class="table" align="center">
	<table class="listing" cellpadding="0" cellspacing="0" style="width:100%; align:center;">

	<tr>
	<th class="first">Param</th>
	<th>Description</th>
	<th>Color</th>
	<th class="last">Action</th>
	</tr>
	<?php foreach($params as $param){?>
	<tr>
	
	<td class="first style1" style="width:auto;"> <?php echo $param['param'];?></td>
	<td class="first style1" style="width:auto;"> <?php echo $param['desc'];?></td>
	<td class="first style1" style="width:auto; text-align:center !important;"><?php// echo $param['graph_display_color'];?><div style="border:1px solid #000; width:15px;height:15px; background-color:<?php echo $param['graph_display_color'];?>;"></div></td>
	<td style="text-align:center !important; width:auto;"><a href="graph_color_setting.php?param_id=<?php echo $param['id'];?>&oper=update" rel="#overlay"><img src="/img/edit-icon.gif" width="16" height="16" alt="edit" /></a></td>
	</tr>
	<?php }?>
	</table>
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
