<?php 
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";
if($_GET['location_id']!='' && $_GET['type']!=''){
$location_id = $_GET['location_id'];
$type= $_GET['type'];
$document_details= $u->getLocationDocument($location_id,$type);
if($document_details['file_type']=='image/jpeg' || $document_details['file_type']=='image/png' || $document_details['file_type']=='image/jpg'){
$image=true;
}
}
header("Content-type: image/jpeg");
if($image){
echo $document_details['file_data'];
}else{
header('Content-type:'.$document_details['file_type']);
header('Content-Disposition: attachment; filename="'.$document_details['file_name'].'"');
echo $document_details['file_data'];
exit();
}
?>
