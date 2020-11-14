<?php include_once("../lib/Admin.class.php");?>
<?php error_reporting(E_ALL);
ini_set('display_errors', 1);
$u = new Admin(1);
 //$leave_data = '/var/www/html/voltage_analysis/public_html/unpublish.csv';
//$argv[1] = 'unpublish.csv';
$leave_data=$argv[1];

 if (($file = fopen($leave_data, "r")) !== FALSE)
 {  $i=0;
    while (($data = fgetcsv($file)) !== FALSE)
           {
		$data['from_date']='22/05/2018';
		$data['to_date']='31/05/2018';

		$data['is_publish']='unpublish';
		$data['location_id']=$data[1];
		$data['reason']='unpublish';
		$data['log_id']='';
 		$voltages = $u->getVoltagesToPublish($data,1);
		if(count($voltages) >0){
		if($u->unPublishVoltage($data)){




                 $voltage_ids='';
                foreach($voltages as $voltage){
                        $voltage_ids .=$voltage['id'].",";
                }
		$u->markVoltageUnPublished(rtrim($voltage_ids,","));

			$u->addPublishUnpublish($data); 
			if($data['log_id']!=''){
				$u->updatePublishUnpublish($data);
			}
			$msg = "<span class=\"message\">Data Unpublished successfully.</span>";
		}

//fwrite($file, $data);


        $i++;
        }


printf("Records Updated: %d\n", $i);
    }

}
fclose($file);
?>
