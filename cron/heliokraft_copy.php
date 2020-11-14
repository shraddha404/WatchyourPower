<?php
chdir(dirname(__FILE__));
ini_set('memory_limit', '-1');
include "../lib/Admin.class.php";
$obj=new Admin(2);
$config=$obj->app_config;
$data_files_path=$config['device_data_files_path'];
$processed_files_path =$config['device_data_files_path_heliohraft'].'*.*';
$files = glob( $processed_files_path );
$exclude_files = array('.', '..','config.txt');
$data=array();
$file_name="";
$time=time();
	if (!in_array($files, $exclude_files)) {
		foreach($files as $file){
		  if($time-filemtime($file) > 3600 ){	
			$file_name=basename($file);
			$file_type=explode(".",$file_name);
			$mv_name=substr($file_name,0,19);
		        $moved_file=$data_files_path.''.$mv_name;	
			rename($file, $moved_file);
			//copy($file, $moved_file);
		  }
		}
	}
echo "\r\n script execution completed";
?>
