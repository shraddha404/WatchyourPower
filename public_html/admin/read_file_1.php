<?php
ob_start();
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";
if($_SESSION['user_id']==''){
header('Location:/../index.php');
}
$row_id = $_GET['row_id'];
$filename = $u->getFileNameFromRowId($row_id);
$content = $u->getFileDataFromFileName($filename);
#echo $content;
#exit;
/*$config=$u->app_config;
//$file='/var/www/html/voltage_analysis/processed_files/C02001614101209.txt';
$file=$config['processed_files_path'].''.$_GET['file'];
				$pfile = fopen($file,"r");
				while(! feof($pfile))
				  {
					$line = fgets($pfile);	
					echo $line."\r\n";
					//$data_count=count(explode(",",$line));
				  }
				pclose($pfile);*/
header("Cache-Control: public");
$file = $content;
header('Content-type: text/plain');
header('Content-Length: '.filesize($file));
header('Content-Disposition: attachment; filename='.$filename);
echo $file;
?>
