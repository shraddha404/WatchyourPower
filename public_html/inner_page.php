<?php
$current="inner_page";
?>
<!DOCTYPE html>
<html>
	<head>
		<?php include $_SERVER['DOCUMENT_ROOT']."/includes/html_head.php"; ?>
    
	</head>
    <body class="skin-blue">
                       <?php include $_SERVER['DOCUMENT_ROOT']."/includes/header_banner.php"; ?>
	<div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">    
               <?php include $_SERVER['DOCUMENT_ROOT']."/includes/left_menu.php"; ?>
            </aside>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">                
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Blank page
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Blank page</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">


                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->


        <?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>

    </body>
</html>
