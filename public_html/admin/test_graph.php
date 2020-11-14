<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT'].'/include.php';?> 
<script type="text/javascript">
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
                }];
	AmCharts.ready(function () {
	});
</script>
</head>
<body>
	<input type="button" onclick="createPieChart(chartData,'country','value');" value="Create Pie Chart">
	<div id="chartdiv" style="width: 100%; height: 400px;"></div>
</body>
</html>

