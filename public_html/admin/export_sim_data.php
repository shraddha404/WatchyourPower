<?php
ob_start();
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";
$details['flag']=1;
$sim_cards = $u->getAllSimCards($details);
$filename=date('Y-m-d')."_sim_details.csv";
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
$content = '';
$title = '';
foreach($sim_cards as $sim){
$content .= stripslashes($sim['company']). ',';
$content .= stripslashes($sim['sim_no']). ',';
$content .= stripslashes($sim['mobile_no']). ',';
$content .= stripslashes($sim['plan']). ',';
$content .= stripslashes($sim['billing_cycle']). ',';
$content .= stripslashes($sim['status']). ',';
$content .= stripslashes($sim['activation_date']). ',';
$content .= stripslashes($sim['billing_due_date']). ',';
$content .= "\n";
}
$title .= "Company,Sim Number,Mobile Number,Plan,Billing Cycle,Status,Activation Date,Due Date"."\n";
echo $title;
echo $content;
?>


