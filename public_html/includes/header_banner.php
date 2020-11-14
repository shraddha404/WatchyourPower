<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
$username = $_POST['username'];
if($_POST['Submit']=='Submit'){
$email = $_POST['email'];
//echo $user_id = $u->isEmailRegistered($email);
        if($user_id = $u->isEmailRegistered($email)){
                $u->sendResetPasswordNotification($user_id);
                 $msg = "<span class=\"message\">Reset password email is sent at your email id</span>";

        }else{
                 $msg = "<span class=\"error\">This email address is not registered.</span>";
        }
}?>

<header class="header">
        <a href="http://www.prayaspune.org/peg" class="logo" target="blank"><img src="/img/REVISED_Prayaslogo_WHITE.png"/></a>
        <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        </a>
	<div class="navbar-right" id="basic">
                    <ul class="nav navbar-nav">
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
			   <?php if (empty($_SESSION['user_id'])){ ?>
				<!--
                            <a data-toggle="modal" data-target="#compose-modal" class="dropdown-toggle" href="#">
                                <span>Location Owner Login <i class="caret"></i></span>
                            </a-->
				
				<?php echo $error_msg;?>
				<?php } else { ?>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="padding: 4px 9px;">
                                <i class="glyphicon glyphicon-user"></i>
                                <span><?php echo "Welcome....".$u->user_profile['name'];?> <i class="caret"></i></span>
                            </a>
				<ul class="dropdown-menu" style="width: 100px;" >
                                <!-- User image -->
                                <!--li class="user-header bg-light-blue">
                                    <!--<img src="../../img/avatar3.png" class="img-circle" alt="User Image" />
                                    <p>
                                        <?php echo $u->user_profile['name'];?>
                                        <small><?php echo "Member Since..".$u->user_profile['created']; ?></small>
                                    </p>
                                </li-->

                                <li class="user-footer">
                                    <div class="pull-right">
                                        <a href="logout.php" class="btn btn-default btn-flat">Log out</a>
                                    </div>
                                </li>
                            </ul>
				<?php } ?>
                        </li>
				
                    </ul>
                </div>
        </nav>
</header>


<div class="modal fade" id="compose-modal" tabindex="-1"  role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="width:366px !important; left:170px !important;">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
		<div class="form-box" id="login-box" style="margin:0px auto 0px !important" >
            <!--div class="header bg-light-blue">Sign In</div-->
		<div id="message"></div>
            <form action="#" method="post" id="login_form" >
                <div class="body bg-gray">
                    <div class="form-group">
                        <input type="text" name="username" class="form-control" placeholder="Username" required/>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password" required/>
                    </div>
                    <div class="form-group">
                        <input type="text" name="code" placeholder="Please enter the code in image below" class="form-control" required/>
			
                    </div>
                    <div class="form-group" align="center">
			<img src="/captcha.php" />
                    </div>
                </div>
                <div class="footer">
                    <input type="submit" value="Submit" name="submit" class="btn bg-light-blue btn-block" />
                    <p>                            <!--a data-toggle="modal" data-dismiss="modal"data-target="#forgot_password" class="dropdown-toggle" href="#">I forgot my password</a--></p>
                    <a href="#" data-toggle="modal" data-target="#ragister_user" data-dismiss="modal" class="text-center">I forgot my password </a>
                </div>
            </form>

</div>
</div>
</div>
</div>


<div class="modal fade" id="ragister_user" tabindex="-1"  role="dialog" aria-hidden="true">
	<div class="modal-dialog">
                <div class="modal-content" style="width:366px !important; left:170px !important;">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
		<div class="form-box" id="login-box" style="margin:0px auto 0px !important">
  	<div class="header bg-light-blue">Forgot Password</div>
                <div class="body bg-gray">
			<h4>Enter your email address to reset your password</h4>
			<span> <?php echo $msg;?></span>
			<form method="post" action="">
                    <div class="form-group">
			<input type="email" name="email" id="email" required class="form-control" placeholder="Email ID"/><br/><br/>
                    </div>
			 <div class="footer">
                          <input type="submit" value="Submit" name="Submit"class="btn bg-light-blue btn-block" />
				
			<input type="button" name="cancel" class="btn bg-light-blue btn-block" value="Cancel" onClick="window.location = '../' " /></div>
			</form>
		</div>	

		</div>
           	

		</div>
	</div>
</div>

