<?php 
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
?>
<!DOCTYPE html>
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/html_head.php"; ?>
</head>
<body class="skin-blue">
<style>
#esmi_content{
position:absolute;
top:30%;
z-index:1000000000;
background:#e7f3e9;
padding:5%;
height:24%;
}

#esmi_content:hover {
    background-color: #cae1f2;
}

.esmi-blog{
    background-image: url('img/ESMI_Block.jpg');
    background-position: center;
}

.emark-block{
    background-image: url('img/eMARC_block.jpg');
    background-position: center;
}

#emarc_content{
position:absolute;
top:30%;
z-index:1000000000;
background:#cae1f2;
padding:5%;
margin-left: -15px;
}

#emarc_content:hover {
    background-color: #e7f3e9;
}
.connectedSortable {
    min-height: 805px !important;
}

</style>
<div class="row">
<section class="col-lg-6 connectedSortable esmi-blog">                         
<div id="esmi_content" onClick="location.href='/index.php/';">
<p>
<a href="/index.php"><h3>ESMI</h3></a>
Electricity Supply Monitoring Initiative
ESMI provided evidence based feedback about electricity supply quality from consumer locations across India. 
<a href="/index.php">More Info</a>
</p>
</div>
<!--a href="/index.php"><img src="/img/ESMI_Block.jpg" style="background-position:left top; margin-left: -140px; margin-top: -38px;"></a-->
</section>

<section class="col-lg-6 connectedSortable emark-block">                         
<div id="emarc_content" onClick="location.href='http://emarc.watchyourpower.org/';">
<p>
<a href="http://emarc.watchyourpower.org/"><h3>eMARC</h3></a>
Monitoring and Analysis of Residential Electricity Consumption
eMARC provides insights on electricity consumption in Indian homes from a selected sample of households and appliances.This data is recorded by advanced IoT metering systems. 
<a href="http://emarc.watchyourpower.org/">More Info</a>
</p>
</div>
<!--a href="http://emarc.watchyourpower.org/"><img src="/img/eMARC_block.jpg"></a-->
</section>

</div>

</body>
</html>
