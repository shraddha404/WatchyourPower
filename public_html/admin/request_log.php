<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";

if($_POST['submit'] == 'Search'){
$request_logs = $u->searchRequestLog($_POST);
}else{
$details = $_GET;
$request_logs = $u->getAllRequestLog($details);
}
$main_menu = 'Admin Panel';
$current = 'request_log';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/html_head.php"; ?>
<script>
$(function() {
$("#listing").tablesorter({debug: true});

$( "#from_date" ).datetimepicker(
{formatTime:'H:i',
format:'d/m/Y H:i',
formatDate:'d/m/Y',
closeOnDateSelect:true
});

$( "#to_date" ).datetimepicker(
{formatTime:'H:i',
format:'d/m/Y H:i',
formatDate:'d/m/Y',
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

	<div class="table">

	<form method="post" action="" autocomplete="off">
	<table class="listing" cellpadding="0" cellspacing="0">
        <tr>
        <td align="center">

        <table class="inputlisting">
<!--
        <tr>
        <td style="border:none !important;" width="20%">Users</td>
        <td style="border:none !important;" width="80%">
		<select name="user_id" required>
		<option value="">- Select Location -</option>
		<option value="1" selected>Pune</option>
		</select>
	</td>
        </tr>
-->

        <tr>
        <td>Month</td>
        <td><select name="month" id="month" onchange="" size="1" >
    <option value="">---Select---</option>
    <option value="01">January</option>
    <option value="02">February</option>
    <option value="03">March</option>
    <option value="04">April</option>
    <option value="05">May</option>
    <option value="06">June</option>
    <option value="07">July</option>
    <option value="08">August</option>
    <option value="09">September</option>
    <option value="10">October</option>
    <option value="11">November</option>
    <option value="12">December</option>
</select>
	</td>
        </tr>

        <tr>
        <td>Year</td>
        <td>
		<select id="year" name="year" required>
  <script>
  var myDate = new Date();
  var year = myDate.getFullYear();
  for(var i = 2000; i < year+1; i++){
	  document.write('<option value="'+i+'">'+i+'</option>');
  }
  </script>
</select>
	</td>
        </tr>

        <tr>
        <td>&nbsp;</td>
        <td>
		<input type="submit" name="submit" value="Search">
	</td>
        </tr>

	</table>

	</td>
	</tr>
	</table>
	</form>

	</div>

	<?php echo $msg;?>
	<div class="table">
	<img src="/img/bg-th-left.gif" width="8" height="7" alt="" class="left" /> 
	<img src="/img/bg-th-right.gif" width="7" height="7" alt="" class="right" />

	<table class="listing tablesorter" id="listing" cellpadding="0" cellspacing="0">

	<thead>
	<tr>
	<th class="last">Details</th>
	<th class="last">Action</th>
	</tr>
	</thead>

	<tbody>
	<?php foreach($request_logs as $request){
$param = explode(",", $request['report_params']);
$location=$u->getLocationDetails($param[4]);

?>
	<tr>
<!--	<td class="first style1"> <?php echo date('Y-m-d', strtotime($request['requested'])); ?> </td>
	<td ><?php echo $param[0];?></td>
<td ><?php echo $request['1'];?></td>-->

	<td class="first" width="90%"> <?php echo "The ".$param[5]." Report for ".$location['name']." from ".$param[6]." to ".$param[7]. " requested by ".$param[0]." (".$param[1].")"." From oraganization -".$param[2] ;?></td>
	<td class="last"><a href="/raw_data.php?location_id=<?php echo $param[4]."&from_date=".$param[6]."&to_date=".$param[7];?>" target="_blank">Process</a></td>

	</tr>
	<?php } ?>

	</tbody>
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
