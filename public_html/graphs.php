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

var vis = d3.select("#center-column")
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


/*
var dataset = [ 5, 10, 13, 19, 21, 25, 22, 18, 15, 13,
                11, 12, 15, 20, 18, 17, 16, 18, 23, 25 ];

d3.select("#center-column").selectAll("div")
    .data(dataset)
    .enter()
    .append("div")
    .attr("class", "bar")
    .style("height", function(d) {
        var barHeight = d * 5;
        return barHeight + "px";
    });
*/
</script>

	<div class="table">
	</div>
	

	<div class="table">
	</div>
</div>

<div id="right-column">
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/right_nav.php"; ?>
</div>

</div>
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</div>


</body>
</html>
