<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
//include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";
$current="esmi_expanse";
$districts = $u->getDistrics();
$loc_states = $u->getAllStatesFromLocations();
#$locations = $u->getLocations();
$locations = $u->getLocationsFromCriteria();
$location_count = $u->getDeployedLocationCount();
//$location_count = $u->getLocationCount();
$states_count = $u->getStatesCount();
$districs_count = $u->getDistricsCount();
$cities_count = $u->getCitiesCount();
$locationhours = $u->getLocationHours();
$location_hours =round($locationhours);
$path=$_SERVER['DOCUMENT_ROOT']."/img/down_arrow.png";?>
<!DOCTYPE html>
<html>
<head>

<script type="text/javascript">

var TSort_Data = new Array ('table_demo_us', 's', 's','s','s', 's');
tsRegister();

</script>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/html_head.php"; ?>
</head>
<body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
               <?php include $_SERVER['DOCUMENT_ROOT']."/includes/header_banner.php"; ?>

<div class="wrapper row-offcanvas row-offcanvas-left">
               <?php include $_SERVER['DOCUMENT_ROOT']."/includes/left_menu.php"; ?>
<aside class="right-side" >
<section class="content-header">
	<h1> ESMI Expanse</h1>
	<p  align="JUSTIFY">The electricity supply monitoring initiative aims at monitoring electricity supply quality at several locations across the country. We hope to cover diverse locations including metro cities, state capitals, district headquarters and rural areas. </p>

<p>
Click <a href="/esmi_expanse/ESMI_Expanse_Information.xlsx">here</a> to download CSV of ESMI Expanse Information. 
</p>

</section>

<!-- Main content -->
<section class="content">

<table width="100%"><tr><td valign="top">


<table class="table table-mailbox"align="left" style="border: 1px solid #333 !important; width:50%;">
                        <tbody>
                        <tr style="background-color:#3c8dbc !important;color: #f9f9f9 !important;">
                        <td class="name"colspan="2" style="border: 1px solid #333 !important;"><h4>Electricity Supply Monitoring as on <?php echo $today = date("j F Y");  ?></h4></td>
                        </tr>

                        <tr class="unread" >
                        <td class="name" style="border: 1px solid #333 !important;">Total Live Locations</td>
                        <td class="name" style="border: 1px solid #333 !important;"><?php echo $location_count;?></td>
                        </tr>
			<tr class="unread" >
                        <td class="name" style="border: 1px solid #333 !important;">States</td>
                        <td class="name" style="border: 1px solid #333 !important;"><?php echo $states_count;?></td>
                        </tr>
			<!--<tr class="unread" >
                        <td class="name" style="border: 1px solid #333 !important;">Cities</td>
                        <td class="name" style="border: 1px solid #333 !important;"><?php echo $cities_count;?></td>
                        </tr>-->
                        <tr class="unread" >
                        <td class="name" style="border: 1px solid #333 !important;">Districts</td>
                        <td class="name" style="border: 1px solid #333 !important;"><?php echo $districs_count;?></td>
                        </tr>
        		<!--<tr class="unread" >
                        <td class="name" style="border: 1px solid #333 !important;" width="30%">Available data</td>
                        <td class="name" style="border: 1px solid #333 !important;"><?php echo $location_hours;?> Thousand location hours.</td>
                        </tr>-->
        		<tr class="unread" >
                        <td class="name" style="border: 1px solid #333 !important;" width="50%">Available data (,000 location hours)</td>
                        <td class="name" style="border: 1px solid #333 !important;"><?php echo $location_hours;?></td>
                        </tr>
                        </tbody>
                </table>     </td><td valign="top">


    </td></tr></table> 
<table id="table_demo_us" width="100%"class="table table-mailbox"align="left" style="border: 1px solid #333 !important;width:100%;">
                        <thead>
                       
                        <tr class="unread"style="background-color:#3c8dbc !important;color: #f9f9f9 !important;" >
                        <th class="name" style="border: 1px solid #333 !important; cursor: pointer;color: #f9f9f9 !important;">
<a href="" onclick="tsDraw(0,'table_demo_us'); return false" style="color: #f9f9f9 !important;">Location Name<span id="">&nbsp;&nbsp;<img src="/img/sorter.png"/></span></a></th>
			<th class="name" style="border: 1px solid #333 !important; cursor: pointer;"><a href="" onclick="tsDraw(1,'table_demo_us'); return false" style="color: #f9f9f9 !important;">District<span id="">&nbsp;&nbsp;<img src="/img/sorter.png"/></span></a></th> 
			<th class="name" style="border: 1px solid #333 !important; cursor: pointer;"><a href="" onclick="tsDraw(2,'table_demo_us'); return false" style="color: #f9f9f9 !important;">State<span id="">&nbsp;&nbsp;<img src="/img/sorter.png"/></span></a></th>                        
                        <th class="name" style="border: 1px solid #333 !important; cursor: pointer;"><a href="" onclick="tsDraw(3,'table_demo_us'); return false" style="color: #f9f9f9 !important;">Connection Type<span id="">&nbsp;&nbsp;<img src="/img/sorter.png"/></span></a></th>
			<th class="name" style="border: 1px solid #333 !important; cursor: pointer;"><a href="" onclick="tsDraw(4,'table_demo_us'); return false" style="color: #f9f9f9 !important;">Category &nbsp;&nbsp;<img src="/img/sorter.png"/></a></th>                        
                        </tr>

</thead>
<tbody>
 <?php foreach($locations as $location){
if($location['status']==0){
continue;
}
?>
		
             <tr class="unread" >
			<td class="name" style="border: 1px solid #333 !important;"><?php echo $location['name'];?></td> 
                        <td class="name" style="border: 1px solid #333 !important;"><?php echo $location['district'];?></td>                      
                        <td class="name" style="border: 1px solid #333 !important;"><?php echo $location['state'];?></td>      
			<td class="name" style="border: 1px solid #333 !important;"><?php echo $location['connection_type'];?></td>
                        <td class="name" style="border: 1px solid #333 !important;"><?php $r = $u->getRevenueClassName($location['revenue_classification']);echo $r;?></td>                

                       </tr>
                <?php }?>

                        
			
                        </tbody>
                </table> 
</section><!-- /.content -->
</aside><!-- /.right-side -->

</div><!-- ./wrapper -->


<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>

    </body>
</html>
