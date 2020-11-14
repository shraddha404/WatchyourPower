<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
<title>Zoom to State with Select List (Google Maps API)</title>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
</script>
    <script type="text/javascript">
    
// modified from http://www.weltmeer.ch/divelog/
    // globals
    var map=null;
    var geocoder = null;
var layer=null;
    function initialize() {
       geocoder = new google.maps.Geocoder();
       var myOptions = {
                   zoom: 5,
                   center: new google.maps.LatLng(21.0000, 78.0000),
                   mapTypeControl: true,
                   mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
                   navigationControl: true,
                   mapTypeId: google.maps.MapTypeId.ROADMAP
                };
       map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	
//var transitLayer = new google.maps.TransitLayer();
//transitLayer.setMap(map);
        findAddress("India");
    }

  	function findAddress(address) {
          var addressStr=document.getElementById("stateselect").value;
          if (!address && (addressStr != '')) 
             address = addressStr;
	  else 
             address = addressStr;
          if ((address != '') && geocoder) {
           geocoder.geocode( { 'address': address}, function(results, status) {
           if (status == google.maps.GeocoderStatus.OK) {
             if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
               if (results && results[0]
	           && results[0].geometry && results[0].geometry.viewport) 
                 map.fitBounds(results[0].geometry.viewport);
             } else {
               alert("No results found");
             }
           } else {
            alert("Geocode was not successful for the following reason: " + status);
           }
           });
          }
  	}

</script>
  </head>
  <body onload="initialize()" >
<h2>Zoom to State with Select List (Google Maps API)</h2>
<div id="TOPNAV">
<select id="stateselect" name="countryselect" class="textfeld"  onclick="findAddress();return false"  onchange="findAddress();return false" onfocus="">
              <option value=''>Select a State....</option>
<option value="Pune">Pune</option>
<option value="Maharashtra">Maharashtra</option>
<option value="Karnataka">Karnataka</option>
</select> 
</div>
<div id="map_canvas" style="width: 1000px; height: 800px"></div>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-162157-1";
urchinTracker();
</script>
	</body>
</html>

