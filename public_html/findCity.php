<?php 
    $stateId=intval($_REQUEST['state']);
   $rows=$u->getCityLocation($stateId);
    foreach($rows as $row)
    {
   
        $Cities .="<option>" . $row['town'] . "</option>";

    }
    $citiesDrop="
        <p><label>Cities</label></p>
                      <select name='Cities' id='Cities' class='form-control'>
            " . $Cities . "
        </select>";
    echo $citiesDrop;
    
 ?>
