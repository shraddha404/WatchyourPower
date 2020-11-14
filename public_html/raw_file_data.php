<?php
ob_start();
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
session_start();
if($_SESSION['user_type']!='Admin'){
	header('Location:/index.php');
}
$details= $_GET;
$raw_data = $u->getRawFileData($details);
if(empty($raw_data)){
echo "<script type=\"text/javascript\"> alert ('No data found for selected date range'); window.history.go(-1);</script>";
}else{
$filename=date('Y-m-d')."raw_file_data.csv";
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
$content = '';
$title = '';
foreach($raw_data as $data){
$content .=''. stripslashes($data['filename']). ',';
$content .=''. stripslashes(date('d/m/Y',strtotime($data['imported']))). ',';
$content .=''. stripslashes($data['device']). ',';
$content .=''. stripslashes(date('d/m/Y',strtotime($data['event_date']))). ',';
$content .=''. stripslashes($data['error']). ',';
if($data['is_processed']==0){
$content .= '"Not Processed"'. ',';
}else{
$content .= '"Processed"'. ',';
}
$content .=''. stripslashes($data['content']). ',';
$content .= "\r\n";
$count++;
}
$title .= "File Name,Imported On,Device ID,Event Date,Error Code,Process Status,Content"."\n";
echo $title;
echo $content;
}
?>


