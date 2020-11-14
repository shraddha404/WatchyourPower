<div class="top-bar">
	
	<?php
	$hide_button=array('action_log','error_code','daily_progress_log','location_management','publish_unpublish','request_log','process_events','event_log','upload_reports'); 
	if(!in_array($current,$hide_button)){ ?><a href="<?php echo $page_url; ?>" style="text-decoration: none !important;" class="button">ADD NEW </a><?php }?>
	<a href="javascript: window.history.go(-1)" style="text-decoration: none !important;" class="button">Back</a>
        <h1><?php echo $page_title;?></h1>
        <div class="breadcrumbs">
        <!--<a href=""><?php echo $main_menu;?></a> / <a href="#"><?php echo $menus[$main_menu]['menu'][$current]['text'];?></a>-->
        </div>
        </div>

      <br />

