<?php
$current="static_page";
?>
<!DOCTYPE html>
<html>
	<head>
		<?php include $_SERVER['DOCUMENT_ROOT']."/includes/html_head.php"; ?>
    
	</head>
    <body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
        <header class="header">
            <a href="index.php" class="logo"><img src="/img/logo_new.png" /> </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
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
	<!--
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Blank page</li>
                    </ol>
	-->
                </section>
		<div class="row" style="margin-left:300px !important;">
                        <div class="col-lg-3 col-xs-6">
                            <div class="small-box bg-green">
                                <a class="small-box-footer" href="#">
                                    About ESMI <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-xs-6">
                            <div class="small-box bg-yellow">
                                <a class="small-box-footer" href="#">
                                    Contact Us <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        </div>
                    </div>
                <!-- Main content -->
                <section class="content">


                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->


        <?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>

    </body>
</html>
