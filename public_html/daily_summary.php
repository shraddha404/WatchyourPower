<?php
session_start();
if($_SESSION['user_type']!='Admin'){
	header('Location:/admin/index.php');
}
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
$details= $_GET;
$current="daily_summary";
?>
<!DOCTYPE html>
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/html_head.php"; ?>
<script type="text/javascript">
var dateRange = getMinMaxDates(3);
$(function() {
$( "#to_date" ).datetimepicker(
{
format:'d/m/Y',
formatDate:'d/m/Y',
timepicker:false,
closeOnDateSelect:true
}
);

});
</script>
</head>
<body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
               <?php include $_SERVER['DOCUMENT_ROOT']."/includes/header_banner.php"; ?>
<div class="wrapper row-offcanvas row-offcanvas-left">
               <?php include $_SERVER['DOCUMENT_ROOT']."/includes/left_menu.php"; ?>
<aside class="right-side " >
<section class="content-header">
	<h1>Download Daily Summary</h1>
</section>

<!-- Main content -->
<section class="content">
<form method="get" action="daily_summary_data.php" >
<table cellspacing="10" cellpadding="10" width="" border="0">
<tbody>
		<tr>
		<th>Select Date</th>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
		</tr>

        	<tr>
                <td style="width:25%;">
                       <input type="text"  class="form-control datepick" id="to_date" name="to_date" required value="<?php echo $details['to_date']; ?>" size="5" readonly="readonly"></td>
                </td>
		<td style="width:0;">&nbsp;&nbsp;&nbsp;</td>
                <td style="width:15%;"><input type="submit" value="Download" class="btn btn-primary" id="submit" ></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
        	</tr>
        	<tr>
		<td colspan="8">&nbsp;</td>
		</tr>
</tbody>
</table>
</form>

</div>
</div>
</section><!-- /.content -->
</aside><!-- /.right-side -->

</div><!-- ./wrapper -->


<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>

    </body>
</html>
