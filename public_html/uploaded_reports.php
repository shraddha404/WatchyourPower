<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
//include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";
$current="uploaded_reports";
$reports_list = $u->uploadedreportlist(); 
$path="../img/full-discography-download.png";?>
<!DOCTYPE html>
<html>
<head>

<script type="text/javascript">

var TSort_Data = new Array ('table_demo_us', 's', 's','s','s', 's');
tsRegister();

</script>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/html_head.php"; ?>
</head>
<body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
               <?php include $_SERVER['DOCUMENT_ROOT']."/includes/header_banner.php"; ?>

<div class="wrapper row-offcanvas row-offcanvas-left">
               <?php include $_SERVER['DOCUMENT_ROOT']."/includes/left_menu.php"; ?>
<aside class="right-side" >
<section class="content-header">
	<h1>Analysis Reports</h1>
	<p  align="JUSTIFY"></p>

</section>

<!-- Main content -->
<section class="content">

<table width="100%"><tr><td valign="top">
        
                      
	<?php foreach($reports_list as $list) {
                   $l=$list['file_path'];?>
                       

                        <p><strong><?php echo $list['title'];?></strong><a  style="color:#000;" href="download_uploaded_reports.php?f=<?php echo $l;?>" target="_blank"><img src="<?php echo $path;?>"></a></p>
                        <p style="color:#6D7B8D;"><?php echo $list['description'];?></p><br/>

		<?php }?>	
                            </td><td valign="top">


    </td></tr></table> 

</section><!-- /.content -->
</aside><!-- /.right-side -->

</div><!-- ./wrapper -->


<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>

    </body>
</html>
