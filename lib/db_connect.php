<?php
    /*
        Connect to the application DB*/
   
	$db_host = 'localhost';
	$db = 'voltage_analysis';
	$db_user = 'voltage_analysis';
	$db_pass = 'voltage_analysis123';


    try{
    $pdo = new PDO('mysql:host='.$db_host.';dbname='.$db, $db_user, $db_pass, array(
        PDO::ATTR_PERSISTENT => false
    ));
    }
    catch(PDOException $e){
	echo $e->getMessage();
    }

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
