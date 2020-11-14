<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";

$details= $_GET;
if($_POST['Submit']=='Submit'){
        $details = $_POST;
$details['user_id']=$_SESSION['user_id'];
        if($u->submit_request($details)){
                $msg = "<span class=\"message\">Your request submitted successfully.</span>";
        }else{
                $msg = "<span class=\"error\">$u->error</span>";
        }
}
$current="reports";
$locationnames=$u->getAllLocations();
?>
<!DOCTYPE html>
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/html_head.php"; ?>
<script type="text/javascript">
	

$(function() {
$( "#from_date" ).datetimepicker(
{formatTime:'H:i',
format:'d/m/Y H:i',
formatDate:'d/m/Y',
timepicker:false
}
);
$( "#to_date" ).datetimepicker(
{formatTime:'H:i',
format:'d/m/Y H:i',
formatDate:'d/m/Y',
timepicker:false
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
<aside class="right-side" >
<section class="content-header">
	<h1>Submit Your Request</h1>
	<!--
	<ol class="breadcrumb">
	<li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
	<li class="active">Submit Your Request</li>
	</ol>
	-->
</section>

<!-- Main content -->





                <div style="width:70%;margin-left: 150px;">

            	<form action="" method="post" onsubmit="return validateDate('0');">


                <div ><table style="width:70%;" align="center">
<tr><td class="form-group" colspan="2">&nbsp;</td></tr>
<tr><td class="form-group">*Name:</td><td><input type="text" name="name" class="form-control" placeholder="Full name" required/></td></tr>
<tr><td class="form-group" colspan="2">&nbsp;</td></tr>
<tr><td class="form-group">*Email:</td><td><input type="text" name="email" class="form-control" placeholder="Email ID" required/></td></tr>
<tr><td class="form-group" colspan="2">&nbsp;</td></tr>
<tr><td class="form-group">*Organization:</td><td><input type="text" name="organization" class="form-control" placeholder="Organization" required/></td></tr>
<tr><td class="form-group" colspan="2">&nbsp;</td></tr>
<tr><td class="form-group">*Contact Number:</td><td> <input type="text" name="contact_number" class="form-control" placeholder="Contact Number" required/></td></tr>
<tr><td class="form-group" colspan="2">&nbsp;</td></tr>
<tr><td class="form-group">*Report type:</td><td> <select class="form-control" id="report_type" name="report_type" required>
			<option value="">-- Select  --</option>
			<option value="detailed" >detailed</option>
                        <option value="raw" >raw</option>
			</select></td></tr>
<tr><td class="form-group" colspan="2">&nbsp;</td></tr>
<tr><td class="form-group">*Location:</td><td> <select name='location' id='location' class="form-control"><option value="" required>-- Select  --</option>
       			<?php foreach($locationnames as $locationname){ ?>
       		        <option value="<?php echo $locationname['id'];  ?>" ><?php echo $locationname['name'];?></option>
        	       <?php } ?>
        	         </select></td></tr>

<tr><td class="form-group" colspan="2">&nbsp;</td></tr>
<tr><td class="form-group">*From date:</td><td><input type="text" name="from_date" id="from_date"class="form-control datepick"  placeholder="From date" required/></td></tr>
<!--<tr><td class="form-group">From date:</td><td><input type="text" name="from_date" id="from_date"class="form-control datepick"  onChange="validateDate($(this).val(), $('#to_date').val());" placeholder="From date" required/></td></tr>-->
<tr><td class="form-group" colspan="2">&nbsp;</td></tr>
<tr><td class="form-group">*To date:</td><td> <input type="text" name="to_date" id="to_date" class="form-control datepick" placeholder="To date" required/></tr>
<!--<tr><td class="form-group">To date:</td><td> <input type="text" name="to_date" id="to_date" onChange="validateDate($('from_date').val(), $(this).val());"class="form-control datepick" placeholder="To date" required/></tr>-->
<tr><td class="form-group" colspan="2">&nbsp;</td></tr>
<tr><td class="form-group">*Terms & condition:</td><td>  <input type="checkbox"  name="terms_conditions" id="terms_conditions" required/>&nbsp;&nbsp;<a href="terms_of_data_use.php">Terms & condition</a></td></tr>
<tr><td class="form-group" colspan="2">&nbsp;</td></tr>
<tr><td colspan="2" align="center">  
                    <input type="submit" name="Submit" value="Submit" class="btn  " >
            </tr>
<tr><td class="form-group" colspan="2">&nbsp;</td></tr><tr><td class="form-group" colspan="2">&nbsp;</td></tr>
</table>


		</form>
		
</div>

</section><!-- /.content -->
</aside><!-- /.right-side -->
<div style="height:40px;"></div>
</div><!-- ./wrapper -->


<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>

    </body>
</html>
