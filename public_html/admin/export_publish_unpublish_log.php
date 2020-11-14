<?php
ob_start();
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";
$searched_events=$u->getPublish_Unpublish_Log($location_id);
if(empty($searched_events)){
echo "<script type=\"text/javascript\"> alert ('No data found for selected date'); window.history.go(-1);</script>";
}else{
$filename=date('Y-m-d')."_publish_unpublish_log.csv";
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
$content = '';
$title = '';
foreach($searched_events as $events){
$user=$u->getUserDetails($events['created_by']);
$content .= stripslashes($events['from_date']). ',';
$content .= stripslashes($events['to_date']). ',';
$content .= stripslashes($user['name']). ',';
$content .= stripslashes($events['location_name']). ',';
$content .= stripslashes($events['type']). ',';

$content .= "\n";
}
$title .= "From Date,To Date,Created By,Location Name,type"."\n";
echo $title;
echo $content;
}
?>
