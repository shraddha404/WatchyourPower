<?php 
$menus = array
        ( 'Location Owner Panel' => array('menu' => array("Export Summery" => array('url' => '/locationowner/export_summery_voltage_data.php', 'text' => 'Export Summery | Voltage Data'),
                             
                                ), 
                        'url' => '/locationowner/admin_landing.php')

      



        );
$page_url = $menus[$main_menu]['menu'][$current]['url'];
$page_title = $menus[$main_menu]['menu'][$current]['text'];
?>

<a href="index.php" class="logo"><img src="/img/logo2.gif" width="150" height="78" alt="" /></a>
<ul id="top-navigation">
<?php foreach ($menus as $m => $value){ ?>
<?php if($m == $main_menu){ ?>
<li class="active"><span><span><?php echo $m; ?></span></span></li>
<?php } else{ ?>
<li><span><span><a href="<?php echo $value['url']; ?>"><?php echo $m; ?></a></span></span></li>
<?php } } ?>
<!--
<?php if($u->user_id){ ?>
<li><span><span><a href="/logout.php">Logout</a></span></span></li>
<?php } ?>
-->
</ul>

<span style="float:right; margin-top:100px; margin-right:50px;"><?php echo 'Welcome '.$u->user_profile['name']; ?> / <a href="/logout.php">Logout</a></span>
