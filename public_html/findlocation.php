<?php 
    $cityId=intval($_REQUEST['city']);
   $rows=$u->getLocationName($cityId);
    foreach($rows as $row)
    {
   
        $location .="<option>" . $row['name'] . "</option>";

    }
    $locationDrop="
        <p><label>Cities</label></p>
                         <select name='location' id='location' class='form-control'>
            " . $location . "
        </select>";
    echo $locationDrop;
    
 ?>
