<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
$main_menu = 'Admin Panel';
$current = 'Devices';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/html_head.php"; ?>
        <script src="/amcharts/amcharts/amcharts.js" type="text/javascript"></script>
        <script src="/amcharts/amcharts/pie.js" type="text/javascript"></script>
<script type="text/javascript">
            var chart;
            var legend;

            var chartData = [
                {
                    "country": "Lithuania",
                    "value": 260
                },
                {
                    "country": "Ireland",
                    "value": 201
                },
                {
                    "country": "Germany",
                    "value": 65
                },
                {
                    "country": "Australia",
                    "value": 39
                },
                {
                    "country": "UK",
                    "value": 19
                },
                {
                    "country": "Latvia",
                    "value": 10
                }
            ];

            AmCharts.ready(function () {
                // PIE CHART
                chart = new AmCharts.AmPieChart();
                chart.dataProvider = chartData;
                chart.titleField = "country";
                chart.valueField = "value";
                chart.outlineColor = "#FFFFFF";
                chart.outlineAlpha = 0.8;
                chart.outlineThickness = 2;
                chart.balloonText = "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>";
                // this makes the chart 3D
                chart.depth3D = 15;
                chart.angle = 30;

                // WRITE
                chart.write("pie-chart");
            });
        </script>
<!--
        <script type="text/javascript">
            var chart;

            var chartData = [
                {
                    "year": 2005,
                    "income": 23.5
                },
                {
                    "year": 2006,
                    "income": 26.2
                },
                {
                    "year": 2007,
                    "income": 30.1
                },
                {
                    "year": 2008,
                    "income": 29.5
                },
                {
                    "year": 2009,
                    "income": 24.6
                }
            ];


            AmCharts.ready(function () {
                // SERIAL CHART
                chart = new AmCharts.AmSerialChart();
                chart.dataProvider = chartData;
                chart.categoryField = "year";
                // this single line makes the chart a bar chart,
                // try to set it to false - your bars will turn to columns
                chart.rotate = true;
                // the following two lines makes chart 3D
                chart.depth3D = 20;
                chart.angle = 30;

                // AXES
                // Category
                var categoryAxis = chart.categoryAxis;
                categoryAxis.gridPosition = "start";
                categoryAxis.axisColor = "#DADADA";
                categoryAxis.fillAlpha = 1;
                categoryAxis.gridAlpha = 0;
                categoryAxis.fillColor = "#FAFAFA";

                // value
                var valueAxis = new AmCharts.ValueAxis();
                valueAxis.axisColor = "#DADADA";
                valueAxis.title = "Income in millions, USD";
                valueAxis.gridAlpha = 0.1;
                chart.addValueAxis(valueAxis);

                // GRAPH
                var graph = new AmCharts.AmGraph();
                graph.title = "Income";
                graph.valueField = "income";
                graph.type = "column";
                graph.balloonText = "Income in [[category]]:[[value]]";
                graph.lineAlpha = 0;
                graph.fillColors = "#bf1c25";
                graph.fillAlphas = 1;
                chart.addGraph(graph);

                chart.creditsPosition = "top-right";

                // WRITE
                chart.write("chartdiv");
            });
        </script>
-->
</head>
<body>
<div id="main">

<div id="header"> 
<?php #include $_SERVER['DOCUMENT_ROOT']."/admin/include/menu.php"; ?>
<a href="#" class="logo"><img src="/img/logo.gif" width="130" height="80" alt="" /></a>
</div>

<div id="middle">

<div id="left-column">
	<?php # include $_SERVER['DOCUMENT_ROOT']."/admin/include/left_nav.php"; ?>
</div>

<div id="center-column">

	<div class="top-bar"> 
	<h1>Graphs</h1>
	</div>

      <br />

<script type="text/javascript">
 
var w = 300, //width
h = 300, //height
r = 100, //radius
color = d3.scale.category20c(); //builtin range of colors
 
data = [{"label":"one", "value":20},
{"label":"two", "value":50},
{"label":"three", "value":30}];

var vis = d3.select("#charts")
.append("svg:svg") //create the SVG element inside the <id center-colum>
.data([data]) //associate our data with the document
.attr("width", w) //set the width and height of our visualization (these will be attributes of the <svg> tag
.attr("height", h)
.append("svg:g") //make a group to hold our pie chart
.attr("transform", "translate(" + r + "," + r + ")") //move the center of the pie chart from 0, 0 to radius, radius
 
var arc = d3.svg.arc() //this will create <path> elements for us using arc data
.outerRadius(r);
 
var pie = d3.layout.pie() //this will create arc data for us given a list of values
.value(function(d) { return d.value; }); //we must tell it out to access the value of each element in our data array
 
var arcs = vis.selectAll("g.slice") //this selects all <g> elements with class slice (there aren't any yet)
.data(pie) //associate the generated pie data (an array of arcs, each having startAngle, endAngle and value properties)G
.enter() //this will create <g> elements for every "extra" data element that should be associated with a selection. The result is creating a <g> for every object in the data array
.append("svg:g") //create a group to hold each slice (we will have a <path> and a <text> element associated with each slice)
.attr("class", "slice"); //allow us to style things in the slices (like text)
 
arcs.append("svg:path")
.attr("fill", function(d, i) { return color(i); } ) //set the color for each slice to be chosen from the color function defined above
.attr("d", arc); //this creates the actual SVG path using the associated data (pie) with the arc drawing function
 
arcs.append("svg:text") //add a label to each slice
.attr("transform", function(d) { //set the label's origin to the center of the arc
//we have to make sure to set these before calling arc.centroid
d.innerRadius = 0;
d.outerRadius = r;
return "translate(" + arc.centroid(d) + ")"; //this gives us a pair of coordinates like [50, 50]
})
.attr("text-anchor", "middle") //center the text on it's origin
.text(function(d, i) { return data[i].label; }); //get the label from our original data array
</script>

<div id="charts"></div>

<div id="pie-chart" style="width: 500px; height: 600px;"></div>
	

<div class="bar-chart"></div>

</div>

<div id="right-column">
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/right_nav.php"; ?>
</div>

</div>
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</div>


</body>
</html>
