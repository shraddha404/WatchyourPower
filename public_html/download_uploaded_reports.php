<?php

    //$filename = './pdf/jobs/pdffile.pdf';
$filename=$_SERVER['DOCUMENT_ROOT']."/reports/".$_GET['f'];
    $fileinfo = pathinfo($filename);
    $sendname = $fileinfo['filename'] . '.' . strtoupper($fileinfo['extension']);

    header('Content-Type: application/pdf');
    header("Content-Disposition: attachment; filename=\"$sendname\"");
    header('Content-Length: ' . filesize($filename));
    readfile($filename);

?>
