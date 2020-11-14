<?php include '../../lib/db_connect.php';
//include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";

 $leave_data = 'unpublish.csv';
$argv[1] = 'unpublish.csv';


 if (($file = fopen($leave_data, "r")) !== FALSE)
 {  $i=0;
    while (($data = fgetcsv($file)) !== FALSE)
           {
$data['from_date']='22/05/2018';
$data['to_date']='31/05/2018';
$data['is_publish']='unpublish';
	    //$data = array($data[0], $data[1],$data['from_date'],$data['to_date']); 
//unPublishVoltage($data);
if(unPublishVoltage($data)){


                 $voltage_ids='';
                foreach($voltages as $voltage){
                        $voltage_ids .=$voltage['id'].",";
                }
		markVoltageUnPublished(rtrim($voltage_ids,","));

			addPublishUnpublish($data); 
			if($data['log_id']!=''){
				updatePublishUnpublish($data);
			}
			$msg = "<span class=\"message\">Data Unpublished successfully.</span>";
		}
//print_r($data);	
fwrite($file, $data);


        $i++;
        }


printf("Records Updated: %d\n", $i);
    }
fclose($file);
 function unPublishVoltage($details){	
	global $pdo;
	$from_date =  date('Y-m-d %H:%i:%s', get_strtotime($details['from_date'])); 
	$to_date =  date('Y-m-d %H:%i:%s', get_strtotime($details['to_date'])); 
	 $delete = "DELETE FROM location_summary WHERE hour_of_day BETWEEN ? AND ? AND location_id = ?";
	try{
	$stmt = $pdo->prepare($delete);
	if(unPublishInterrupts($details)){
		$stmt->execute(array($from_date, $to_date, $details[1]));
	}
	}catch(PDOException $e){
		//$this->setError($e->getMessage());
		return false;
	}
	return true;
}
function addPublishUnpublish($data){
	global $pdo;
	try{
		$from_date =  date('Y-m-d %H:%i:%s', get_strtotime($data['from_date'])); 
		$to_date =  date('Y-m-d %H:%i:%s', get_strtotime($data['to_date'])); 
	//	$from_date =  date('Y-m-d', get_strtotime($data['from_date'])); 
	//	$to_date =  date('Y-m-d', get_strtotime($data['to_date'])); 
		$insert = $pdo->prepare("INSERT INTO publish_unpublish_log(`location_id`,`from_date`,`to_date`,`type`,`created`,`reason`,`created_by`)
				VALUE (?, ?, ?, ?, NOW(),?,?)");
		$insert_args = array($data[1], $from_date, $to_date, $data['is_publish'],$data['reason'],$this->user_id); 
		$insert->execute($insert_args);
		$row_id = $pdo->lastInsertId('id');
		$action_details=array();
		$action_details['table_name']='publish_unpublish_log';
		$action_details['row_id']=$row_id;
		$action_details['operation']='Data published or unpublished';
		createActionLog($action_details);
	}
	catch(PDOException $e){
		//$this->setError($e->getMessage());
		return false;
	}
	return true;
}
function updatePublishUnpublish($data){
	global $pdo;
	try{
		$update = $pdo->prepare("UPDATE publish_unpublish_log SET is_processed = ? WHERE id = ?");
		$update->execute(array($data['is_processed'], $data['log_id']));
	}catch(PDOException $e){
		///$this->setError($e->getMessage());
		return false;
	}
	return true;
}
 function unPublishInterrupts($details){	
	global $pdo;
	$from_date =  date('Y-m-d %H:%i:%s', get_strtotime($details['from_date'])); 
	$to_date =  date('Y-m-d %H:%i:%s', get_strtotime($details['to_date'])); 
	//$from_date =  date('Y-m-d', get_strtotime($details['from_date'])); 
	//$to_date =  date('Y-m-d', get_strtotime($details['to_date'])); 
	 $delete = "DELETE FROM interrupts WHERE down_date BETWEEN ? AND ? AND location_id = ?";
	try{
	$stmt = $pdo->prepare($delete);
	$stmt->execute(array($from_date, $to_date, $details[1]));
	}catch(PDOException $e){
		//$this->setError($e->getMessage());
		return false;
	}
	return true;
}

function get_strtotime($date){
        $date = str_replace('/','-',$date);
        return strtotime($date);
}
function createActionLog($details){
            global $pdo;
		$insert = "INSERT INTO action_log 
				(`table_name`,`row_id`,`operation`,`created_on`,`created_by`)
				VALUES(?,?,?,NOW(),?)";
		$insert_args = array($details['table_name'],$details['row_id'],$details['operation'],$this->user_id);
        try{
		    $stmt=$pdo->prepare($insert);		
		    $stmt->execute($insert_args);
        }
        catch(PDOException $e){
				//$this->setError($e->getMessage());
				return false;
			}	
			return true;
	}
function markVoltageUnPublished($ids){
		global $pdo;
	try{
		$update = $pdo->prepare("UPDATE voltage_readings SET `published` = 0, is_unpublished=1 WHERE id IN ($ids)");
		$update->execute();
	}
	catch(PDOException $e){
		//$this->setError($e->getMessage());
		return false;
	}
	return true;
	}?>
