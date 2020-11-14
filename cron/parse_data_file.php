<?php
chdir(dirname(__FILE__));
ini_set('memory_limit', '-1');
include "../lib/Admin.class.php";
$obj=new Admin(2);
$config=$obj->app_config;
// why this line?
$no_erro_code=$obj->getErrorCodeIdFromErrStr('No error');
/*
Please use just $config['data_files_path'] and $config['processed_files_path']
This setting has no relation with the Document Root
*/
//$data_files_path=$config['data_files_path'].'*.*';
$data_files_path=$config['device_data_files_path'].'*.*';
$processed_files_path=$config['processed_files_path'];
$files = glob( $data_files_path );
$exclude_files = array('.', '..','config.txt');
//$data_count=0;
$data=array();
$file_name="";
$i=0;
$time=time();
$allowed_files= array("txt","TXT","csv","CSV");
	if (!in_array($files, $exclude_files)) {
		foreach($files as $file){
		  if($time-filemtime($file) > 3600 ){	
			$file_name=basename($file);
			$f_name=explode(".",$file_name);
			$record_date=rtrim(chunk_split((substr($f_name[0],7,6)), 2, '-'),'-');
			$event_date=$record_date." ".substr($f_name[0],13,2).":00";
			$file_type=explode(".",$file_name);
			if(in_array($file_type[1],$allowed_files)){	
				$pfile = fopen($file,"r");
				while(! feof($pfile))
				  {
					$line = fgets($pfile);
					//$data_count=count(explode(",",$line));
                                        break;

				  }
				$data[$i]['filename']=$file_name;
				$data[$i]['content']=$line;
				$data[$i]['is_processed']=0;
				$data[$i]['event_date']=$event_date;
				$data[$i]['errorcode']='NULL';
				fclose($pfile);
				$i++;
			
		          $moved_file=$processed_files_path.''.$file_name;	
			  rename($file, $moved_file);
			}
		  }
			if($i>= 10000){
				break;
			}
		}
	if(!empty($data)){
		$obj->addDataFiles($data);
	}
}
echo "\r\n script execution completed";
?>
