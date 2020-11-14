<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";

$current="contact_us";
?>
<!DOCTYPE html>
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/html_head.php"; ?>
<style>
.small-box:hover {
text-decoration: none;
color: #000;
}
.small-box p {
font-size: 14px !important;
}
</style>
</head>
<body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
               <?php include $_SERVER['DOCUMENT_ROOT']."/includes/header_banner.php"; ?>

<div class="wrapper row-offcanvas row-offcanvas-left">
               <?php include $_SERVER['DOCUMENT_ROOT']."/includes/left_menu.php"; ?>
<aside class="right-side" >
<section class="content-header">
	<h1>Contact Us</h1>
	<!--
	<ol class="breadcrumb">
	<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
	<li class="active">Contact Us</li>
	</ol>
	-->
</section>

<!-- Main content -->                           <div class="box box-solid" style="height:330px;float:left; width:40%;margin: 15px;">
<article>
		<div class="article-content-main"  style="margin: 15px;">
  		<section class="article-content clearfix">
  			<p><strong>Write to us at : <span id="cloak22816"><a href="mailto:esmi@prayaspune.org">esmi@prayaspune.org</a></span>
			</strong>
			</p>
			<p><strong>Or</strong></p>
			<p><strong>Prayas, Energy Group<br></strong>
			<span>Unit III A &amp; B, Devgiri,&nbsp;</span><br/>
			<span>Joshi Railway Museum lane,&nbsp;</span>
			<span>Kothrud Industrial Area,&nbsp;</span><br/>
			<span>Kothrud, Pune, MH 411038 ‎INDIA</span>
			</p>
			<p><span><strong>Telephone:&nbsp;</strong>91-20-25420720, 91-20-65205726</span></p>
			<p><span><strong>Fax:&nbsp;</strong>91-20-2543 9134</span></p>
			<p>&nbsp;</p>
   		</section>
		</div>		
		</article>
<!-- /.content --></div>                  




<div class="box box-solid" style="height:330px;float:right; width:50%;margin: 15px;">
  			<form action="sendmail.php" id="contactform" method="post" >
<table align="left" class="listing"cellspecing="10" cellspadding="10"style="margin: 15px;">
			<tbody>

			<tr >
			<td class="name" width="40%"><strong>Name:</strong></td><td class="name"width="1%">&nbsp;</td>
			<td class="name"> <input type="text" class="form-control" id="name" name="name" required></td>

			</tr>
	
			<tr >
			<td class="name"colspan="3" >&nbsp;</td>

			</tr>
			<tr >
			<td class="name" ><strong>Email Address:</td><td class="name">&nbsp;</td>
			<td class="name" ><input type="text" class="form-control" id="email" name="email" required></td>

			</tr>

			<tr >
			<td class="name"colspan="3" >&nbsp;</td>

			</tr>
			<!--<tr>
			<td class="name" ><strong>Purpose:</strong></td><td class="name">&nbsp;</td>
			<td class="name" ><select name="purpose" required class="form-control">
        	<option value="">-- Select  --</option>
       		<option value="Feedback" <?php if($_GET['purpose']=='feedback'){echo "selected";   }?>>Feedback</option>
       		<option value="Testimonial">Testimonial</option>
       		<option value="Other">Other</option>
        	</select></td>

			</tr>-->

			<tr>
			<td class="name" ><strong>Comment:</strong></td>			
			<td class="name">&nbsp;</td>
			<td class="name" ><textarea id="message" name="message" class="form-control" rows="6" cols="40"></textarea></td>

			</tr>
                            

			<tr >
			<td class="name"colspan="3" >&nbsp;</td>

			</tr>  <tr>
			<td class="name" colspan="2" align="center"> <input type="submit" value="Submit" id="submit" name="submit" class="sbt_btn"></td>

			</tr> 
<tr >
			<td class="name"colspan="3" >&nbsp;</td>

			</tr><tr >
			<td class="name"colspan="3" >&nbsp;</td>

			</tr><tr >
			<td class="name"colspan="3" >&nbsp;</td>

			</tr>                    
			</tbody>
		</table>            
                	</form>

               </div>	

</aside><!-- /.right-side -->

</div><!-- ./wrapper -->


<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>

    </body>
</html>
