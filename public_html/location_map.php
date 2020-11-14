<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
$details= $_GET;
$locations = $u->getDeviceInstalledLocations(array('restore_all'=>1));
$states = $u->getAllStatesFromLocationsWeb();
$districts = $u->getDistrics();
$current="select_locations";
?>
<!DOCTYPE html>
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/html_head.php"; ?>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBb2_EW71m4j7lA3N8KmY6Tf5bqMXEfb4A"></script>
<script type="text/javascript" src="/js/markerclusterer.js"></script>
<script type="text/javascript">
    var map=null;
    var geocoder = null;
  	function findAddress(address,area) {
          /*var addressStr=document.getElementById("stateselect").value;
          if (!address && (addressStr != '')) 
             address = addressStr;
	  else 
             address = addressStr;*/
		
          if ((address != '') && geocoder) {
		if(area !=''){
			address=address + ' '+ area;
		}
           geocoder.geocode( { 'address': address}, function(results, status) {
           if (status == google.maps.GeocoderStatus.OK) {
             if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
               if (results && results[0]
	           && results[0].geometry && results[0].geometry.viewport) 
                 map.fitBounds(results[0].geometry.viewport);
		map.setZoom(9);
             } else {
               alert("No results found");
             }
           } else {
            alert("Geocode was not successful for the following reason: " + status);
           }
           });
          }
  	}
function initializeBigNew(locations,div){
       geocoder = new google.maps.Geocoder();
         map = new google.maps.Map(document.getElementById(''+div+''), {
                zoom: 5,
		center: new google.maps.LatLng(21.1498,79.0806 ),
                //center: new google.maps.LatLng(locations[0][1], locations[0][2]),
                mapTypeId: google.maps.MapTypeId.ROADMAP
                });

        var infowindow = new google.maps.InfoWindow({minWidth:350});
        var marker, i;
        var markers = [];
	//var ico; // This is for map icons according to connection type
        for(i = 0; i < locations.length; i++) {
		/*str = locations[i][4];
                res = str.replace(/[, ]+/g, "").trim();
                if(res == "Domestic"){
                 ico ="/img/home.png";
                }else if(res == "NonDomestic"){
                 ico = "/img/commercial.png";
                }else{
                 ico = "/img/agri.png";
                }*/  // This is for map icons according to connection type

                marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i][1], locations[i][2]),
		icon:"/img/marker2.png",
		//icon: ico, // This is for map icons according to connectuion type
		url:"/reports.php?location_id="+locations[i][3],
                map: map
        });
        markers.push(marker);
        google.maps.event.addListener(marker, 'mouseover', (function(marker, i) { return function() {
                infowindow.setContent('<div style="color:#000; width:auto; min-width:200px;"><a href="/reports.php?location_id='+locations[i][3]+'" target="_blank">'+locations[i][0]+'</a><br/>'+locations[i][4]+'</div>');
                infowindow.open(map, marker);
                }
                })(marker, i));
        google.maps.event.addListener(marker, 'click', (function(marker, i) { return function() {
		window.open(this.url, '_blank');	
                }
                })(marker, i));
        }

        var markerCluster = new MarkerClusterer(map, markers);
        //findAddress('India','');
}

var locations = 
        [
                <?php foreach($locations as $loc){ 
		if($loc['status']==0){
		continue;
		}
		?>
		['<?php echo sanitizeInput($loc['name']); ?>', <?php echo $loc['latitude']?>, <?php echo $loc['longitude']; ?>,<?php echo $loc['id']?>, '<?php echo sanitizeInput($loc['category']). ', '.sanitizeInput($loc['connection_type']); ?>'],
                <?php } ?>
        ];

        google.maps.event.addDomListener(window, 'load', function(){initializeBigNew(locations,'world-map');});

</script>
<script>

$(document).on("change","#state", function(){
        var obj ={};
        obj.state = $('#state').val();
        obj.district = $('#district').val();
        obj.district_div_id = $('#district').attr('id');
        if(obj.state!=''){
        getDistrictsOnLocMap(obj,'district');
        }
});

</script>
</head>
<body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
               <?php include $_SERVER['DOCUMENT_ROOT']."/includes/header_banner.php"; ?>
<div class="wrapper row-offcanvas row-offcanvas-left">
               <?php include $_SERVER['DOCUMENT_ROOT']."/includes/left_menu.php"; ?>
<aside class="right-side strech" >
<section class="content-header">
	<h1>Select Location</h1>
	<!--
	<ol class="breadcrumb">
	<li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
	<li class="active">Reports</li>
	</ol>
	-->
</section>

<!-- Main content -->
<section class="content">
<div id="TOPNAV">

                        <select  id="state" name="state" onchange="findAddress(this.value,'');">
                        <option value="">-- Select  State --</option>
                        <?php foreach($states as $state){ ?>
                        <option value="<?php echo $state; ?>" <?php if($details['state']== $state){ echo 'selected="selected"';}?>><?php echo $state; ?></option>
                        <?php } ?>
                        </select>
                        <select  id="district" name="district" onchange="findAddress(this.value,'district');">
                        <option value="">-- Select  District --</option>
                        <?php foreach($districts as $dist){ ?>
                        <option value="<?php echo $dist['district']; ?>" <?php if($details['district']== $dist['district']){ echo 'selected="selected"';}?>><?php echo $dist['district']; ?></option>
                        <?php } ?>
                        </select>
</div>

<div id="world-map" style="height: 800px;width:100%;"></div>

</section><!-- /.content -->
</aside><!-- /.right-side -->

</div><!-- ./wrapper -->


<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>

    </body>
</html>
