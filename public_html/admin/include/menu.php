<?php 
$menus = array
        (
        'Admin Panel' => array('menu' => array("home" => array ('url' => "/admin/admin_landing.php", 'text' => "Home"),
                                "daily_progress_log" => array('url' => '/admin/daily_progress_log.php', 'text' => "Daily Progress"),
                                "event_log" => array ('url' => '/admin/event_processing_log.php', 'text' => 'Events Log | Extract Data '),
                                "device_installation_log" => array ('url' => '/admin/device_installation_log.php', 'text' => 'Device Installation Log'),
                                "request_log" => array ('url' => '/admin/request_log.php', 'text' => 'Request Log'),
                                "publish_unpublish_log" => array ('url' => '/admin/publish_unpublish_log.php', 'text' => 'Publish Unpublish Log'),
                                "processing_log" => array ('url' => '/admin/view_event_processing_log.php', 'text' => 'Event Processing Log'),
                                //"event_processing_log" => array ('url' => '/admin/log_event_processing.php', 'text' => 'Event Processing Log'),
                                "action_log" => array ('url' => '/admin/action_log.php', 'text' => 'Action Log', 'is_last' => '1')

                                ),
                        'url' => '/admin/admin_landing.php'),

        'Management' => array('menu' => array(//"web_user" => array('url' => '#', 'text'=>'Web User'),
                                //"web_user_requests" => array('url' => '#', 'text' => 'Web User Requests'),
                                "installation_table" => array('url' => '/admin/device_installation.php', 'text'=> 'Device Installation'),
                                "location_management" => array('url' => '/admin/location_management.php', 'text' => 'Location Mangement'),
                                "publish_unpublish" => array('url' => '/admin/publish_voltage.php', 'text' => 'Publish / Unpublish'),
                                "process_events" => array('url' => '/admin/event_processing.php', 'text' => 'Process Events'),
                                "update_data_file" => array('url' => '/admin/update_raw_data.php', 'text' => 'Update Raw Data Files'),
                                "upload_reports" => array('url' => '/admin/upload_reports.php', 'text' => 'Analysis Reports'),

                                /*"front_page" => array('url' => '#', 'text' => 'Front Page'),
                                "pop_up" => array('url' => '#', 'text' => 'Pop Up'),
                                "scripts" => array('url' => '#', 'text' => 'Scripts'),
                                "summary" => array('url' => '#', 'text' => 'Summary'),
                                "file_management" => array('url' => '#', 'text' => 'File Management'),
                                "backup" => array('url' => '#', 'text' => 'Backup', 'is_last'=>'1')*/
                                ),
                        'url' => '/admin/device_installation.php'),

        'Data Settings' => array('menu' => array("default" => array('url' => '/admin/default.php', 'text' => 'Current'),
                                "location" => array('url' => '/admin/locations.php', 'text' => 'Location'),
                                "device" => array('url' => '/admin/devices.php', 'text' => 'Device'),
                                "vendors" => array('url' => '/admin/vendor_form.php', 'text' => 'Vendor'),
                                "sim_cards" => array('url' => '/admin/sim_card.php', 'text' => 'Sim Cards'),
                               "parameters" => array('url' => '/admin/voltage_parameters.php', 'text' => 'Voltage Ranges'),
                                ),
                        'url' => '/admin/default.php'),

        'Display Settings' => array('menu' => array("avg_vol_setting" => array('url' => '/admin/average_voltage.php', 'text' => 'Pin Color Settings'),
				"param_color_setting" => array('url' => '/admin/graph_color_setting.php', 'text' => 'Graph Color Setting'),
                                ),
                        'url' => '/admin/average_voltage.php'),

        'Error Settings' => array ('menu' => array( 
                                "error_code" => array('url' => '/admin/error_code.php', 'text' => 'Error / Event Code'),
                               
			),
                        'url' => '/admin/error_code.php'
                ),


 'User Settings' => array('menu' => array( "users" => array('url' => '/admin/manage_users.php', 'text' => 'Users'),
                                "installation_presonal" => array('url' => '/admin/installation_personals.php', 'text' => 'Installation Personnel', 'is_last'=>1),
                                "location_owner" => array('url' => '/admin/location_owner.php', 'text' => 'Assign Locations To Location Owner', 'is_last'=>1),
),
                        'url' => '/admin/manage_users.php')
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
