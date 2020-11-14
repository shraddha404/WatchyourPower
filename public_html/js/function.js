function createPieChart(dataSet,titleField,valueField,colorField,div,flag){
var leg='';
if(flag==1){
leg= {"autoMargins": false,"borderAlpha": 0.5, "equalWidths": false, "horizontalGap": 15, "markerSize": 10, "valueAlign": "left","valueWidth": 0 };


}else{
leg='';
}

chart = AmCharts.makeChart(""+div+"", {
    "type": "pie",
    "theme": "none",
    "dataProvider": dataSet,
    "valueField": valueField,
    "startEffect":'',
    "startDuration":"0",
    /*"sequencedAnimation":false*/
    "titleField": titleField,
    "colorField":colorField,
    "outlineAlpha": 0.8,
    "depth3D": 15,
    //"balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
    "balloonText": "[[title]]<br><span style='font-size:14px'>[[percents]]%</span>",
    "labelsEnabled":false,
    "angle": 30,
    "legend":leg	
    /*"legend": {
        "autoMargins": false,
        "borderAlpha": 0.5,
        "equalWidths": false,
        "horizontalGap": 15,
        "markerSize": 10,
    //    "useGraphSettings": true,
        "valueAlign": "left",
        "valueWidth": 0
    }*/
});

}

function initialize(locations){
	//var ico; // this for map icon according to connection type
	var map = new google.maps.Map(document.getElementById('world-map'), {
		zoom: 5,
		center: new google.maps.LatLng(21.1498,79.0806 ),
		//center: new google.maps.LatLng(locations[0][1], locations[0][2]),
		mapTypeId: google.maps.MapTypeId.ROADMAP
		});

	var infowindow = new google.maps.InfoWindow({minWidth: 350});
	var marker, i;
	var markers = []; 
	for(i = 0; i < locations.length; i++) { 
		/*str = locations[i][4];
		res = str.replace(/[, ]+/g, "").trim();
		if(res == "Domestic"){
		 ico ="/img/home.png";
		}else if(res == "NonDomestic"){
		 ico = "/img/commercial.png";
		}else{
		 ico = "/img/agri.png";
		}*/ // For map icons according to connection type 
		
		marker = new google.maps.Marker({
		position: new google.maps.LatLng(locations[i][1], locations[i][2]),
		url:"/reports.php?location_id="+locations[i][3],
		//icon: ico, //For map icons according to connection type
		icon: "/img/marker2.png",
		map: map
	});
	markers.push(marker);
	google.maps.event.addListener(marker, 'mouseover', (function(marker, i) { return function() {
		infowindow.setContent('<div style="color:#000; width:auto; min-width:200px;"><a href="/reports.php?location_id='+locations[i][3]+'" target="_blank">'+locations[i][0]+'</a>,<br />'+locations[i][4]+'</div>');
		infowindow.open(map, marker);
		} 
		})(marker, i));
	google.maps.event.addListener(marker, 'click', (function(marker, i) { return function() {
		window.open(this.url, '_blank');
		}
                })(marker, i));

	}
	var markerCluster = new MarkerClusterer(map, markers);
	//findAddress('India');
}

function initializeBig(locations,div){
	var map = new google.maps.Map(document.getElementById(''+div+''), {
		zoom: 4,
		center: new google.maps.LatLng(locations[0][1], locations[0][2]),
		mapTypeId: google.maps.MapTypeId.ROADMAP
		});

	var infowindow = new google.maps.InfoWindow();
	var marker, i;
	var markers = []; 

	for(i = 0; i < locations.length; i++) {  
		marker = new google.maps.Marker({
		position: new google.maps.LatLng(locations[i][1], locations[i][2]),
		url:"/reports.php?location_id="+locations[i][3],
		map: map
	});
	markers.push(marker);
	google.maps.event.addListener(marker, 'mouseover', (function(marker, i) { return function() {
		infowindow.setContent('<div style="color:#000; width:auto;"><a href="/reports.php?location_id='+locations[i][3]+'" target="_blank">'+locations[i][0]+'</a></div>');
		infowindow.open(map, marker);
		} 
		})(marker, i));
	google.maps.event.addListener(marker, 'click', (function(marker, i) { return function() {
		window.open(this.url, '_blank');
		}
                })(marker, i));
	}

	var markerCluster = new MarkerClusterer(map, markers);
	var markerCluster = new MarkerClusterer(map, markers);
}

function createStackedColumnChart(chartData, div, params,flag){
var leg='';
if(flag==1){
leg={"autoMargins": false, "borderAlpha": 0.5, "equalWidths": false,"horizontalGap": 15,"markerSize": 10, "valueAlign": "left", "valueWidth": 0 };
}else{
leg='';
}

var balloon_text = "Evening Supply Hours: [[title]], [[category]]<br><span style='font-size:14px;'><b>[[value]]</b></span>";
var graphs = getGraphsForColumnChart(params);
var chart = AmCharts.makeChart(""+div+"", {
    "type": "serial",
    "theme": "none",
    "legend": {"autoMargins": false, "borderAlpha": 0.5, "equalWidths": false,"horizontalGap": 15,"markerSize": 10, "valueAlign": "left", "valueWidth": 0 },
    "dataProvider": chartData,
    "valueAxes": [{
        "stackType": "regular",
        "axisAlpha": 0,
        "gridAlpha": 0.1,
        "position": "left",
        "title": "Evening Supply Hours"
    }],
    "graphs": graphs,

/*	"graphs" : [{
        "balloonText": balloon_text,
        "fillAlphas": 0.9,
        "fontSize": 11,
        "lineAlpha": 0.5,
        "title": "Normal",
        "type": "column",
        "valueField": "normal"
    }, /*{
        "balloonText": balloon_text,
        "fillAlphas": 0.9,
        "fontSize": 11,
        "lineAlpha": 0.5,
        "title": "High",
        "type": "column",
        "valueField": "high"
    }, {
        "balloonText": balloon_text,
        "fillAlphas": 0.9,
        "fontSize": 11,
        "lineAlpha": 0.5,
        "title": "low",
        "type": "column",
        "valueField": "low"
    }, {
        "balloonText": balloon_text,
        "fillAlphas": 0.9,
        "fontSize": 11,
        "lineAlpha": 0.5,
        "title": "Very Low",
        "type": "column",
        "valueField": "very_low"
    }, {
        "balloonText": balloon_text,
        "fillAlphas": 0.9,
        "fontSize": 11,
        "lineAlpha": 0.5,
        "title": "No Data",
        "type": "column",
        "valueField": "no_data"
    }, {
        "balloonText": balloon_text,
        "fillAlphas": 0.9,
        "fontSize": 11,
        "lineAlpha": 0.5,
        "title": "No Supply",
        "type": "column",
        "valueField": "no_supply"
    }],*/

    //"depth3D": 20,
    //"angle": 30,
    "categoryField": "date",
    "categoryAxis": {
        "gridPosition": "start",
	"title":"Days",
	//"parseDates": true,
	"labelRotation":45,
	//"equalSpacing": true,
        "axisAlpha": 0,
        "gridAlpha": 0.1
    }
});

}

function createLineChart(chartData, category, value, div ,avg_high, avg_low){
var chart = AmCharts.makeChart(""+div+"", {
    "type": "serial",
//      "theme": "none",
    "pathToImages": "http://www.amcharts.com/lib/3/images/",
    "dataProvider": chartData,
/*
    "legend": {
        "autoMargins": false,
        "borderAlpha": 0.5,
        "equalWidths": false,
        "horizontalGap": 15,
        "markerSize": 10,
//        "useGraphSettings": true,
        "valueAlign": "left",
        "valueWidth": 0
    },
*/
    "valueAxes": [{
        "position": "left",
        "title": "Voltage",
	"baseValue":0,
	"minimum":0,
	"maximum":350
    }],
    "graphs": [{
	"valueAxis":"Voltage 1",
	"lineColor":"#FF6600",
	"title": "Voltage",
        "fillAlphas": 0.0,
        "valueField": ""+value+"",
	"connect":false
    }],
    "chartScrollbar": {},
    "chartCursor": {
        "categoryBalloonDateFormat": "JJ:NN, DD MMMM",
        "cursorPosition": "mouse"
    },
    "guides":[
	{
		"fillAlpha":0.30,
		"value": avg_low,
		"toValue":avg_high,
		"fillColor":'green',
		"inside":true,
		"label":"Normal voltage range"
	}
    ], 
    "categoryField": ""+category+"",
    "categoryAxis": {
        "minPeriod": "mm",
	"title": "Time",
        "parseDates": true
    }
});
chart.addListener("dataUpdated", zoomChart);
}

function createLineChartToCompare(chartData, category, value, div,locations,avg_high,avg_low){
	graphs = getGraphsToCompareLineChart(locations); 
var chart = AmCharts.makeChart(""+div+"", {
    "type": "serial",
//      "theme": "none",
    "pathToImages": "http://www.amcharts.com/lib/3/images/",
    "dataProvider": chartData,
    "valueAxes": [{
        "position": "left",
        "title": "Voltage",
	"baseValue":0,
	"minimum":0,
	"maximum":350
    }],
    "legend": {
        "autoMargins": false,
        "borderAlpha": 0.5,
        "equalWidths": false,
        "horizontalGap": 15,
        "markerSize": 10,
//        "useGraphSettings": true,
        "valueAlign": "left",
        "valueWidth": 0
    },
    "graphs":graphs,
    "chartScrollbar": {},
    "chartCursor": {
        "categoryBalloonDateFormat": "JJ:NN, DD MMMM",
        "cursorPosition": "mouse"
    },
    "guides":[
	{
		"fillAlpha":0.30,
		"value":avg_low,
		"toValue":avg_high,
		"fillColor":'green',
		"inside":true,
                "label":"Normal voltage range"

	}
    ], 
    "categoryField": ""+category+"",
    "categoryAxis": {
	"title": "Time",
        "minPeriod": "mm",
        "parseDates": true
    }
});
chart.addListener("dataUpdated", zoomChart);
}



function zoomChart() {
    // different zoom methods can be used - zoomToIndexes, zoomToDates, zoomToCategoryValues
    //chart.zoomToIndexes(chartData.length - 250, chartData.length - 1);
    chart.zoomToIndexes(chartData.length - 10, chartData.length - 1);
}

function getGraphsToCompareLineChart(locations){
        graphs = [];
        for(var i=0; i<locations.length; i++){
                graphs.push({
                "valueAxis":locations[i].key,
                "lineColor":locations[i].color,
                "fillAlphas": 0.0,
                "valueField": locations[i].key,
                "title": locations[i].name,
                "connect":false
                });
        }
return graphs;
}

function getGraphsForColumnChart(params){
var graphs = [];
var balloon_text = "Evening Supply Hours: [[title]], [[category]]<br><span style='font-size:14px;'><b>[[value]]</b></span>";
        for(var i=0; i< params.length; i++){
                graphs.push({
		"balloonText": balloon_text,
		"fillAlphas": 0.9,
		"fontSize": 11,
		"lineAlpha": 0.5,
		"title": params[i].desc,
		"lineColor": params[i].graph_display_color,
		"type": "column",
		"valueField": params[i].param
                });
        }
return graphs;
}

function getDaysInDateRange(date1, date2){
	// format of date1 and date2 is dd/mm/yyyy
	var d1 = date1.split('/');
	var d2 = date2.split('/');

	var date_o1 = new Date(d1[2], d1[1] -1, d1[0]);
	var date_o2 = new Date(d2[2], d2[1] -1, d2[0]);


	//alert(d1[2]+' '+d1[1]+' '+d1[0]);
	var time1 = date_o1.getTime();
	var time2 = date_o2.getTime();

	return (time2 - time1)/(1000*60*60*24);
}
