<!DOCTYPE html>
<html>
<head>
<title>Prayas</title>
<?php 
include $_SERVER['DOCUMENT_ROOT']."/includes/html_head.php"; ?>
</head>
<body class="skin-black">
<style>
html {
background:#fff;
}
.skin-black{
width:85%;
margin-left:8%;
background:#fff;
}
#esmi_content{
width:65%;
height:65%;
padding:3%;
position:absolute;
top:15%;
left:20%;
z-index:1000000000;
background:#4786b4;
opacity:0.9;
color:#fff;
font-size:25px;
text-align:center;
font-weight:bold;
font-family:'Source Sans Pro', sans-serif;
}

#esmi-content a{
color:#fff;
}

#esmi_content:hover {
    /*background-color: #cae1f2;*/
background:#01090f;
}

.esmi-blog{
    background-image: url('img/ESMI_Block.jpg');
    background-position: center;
}

.esmi{
text-align:center;
font-weight:bold;
}
.esmi a{
color:#fff;
}

.esmi h1{
font-size:50px;
}
.esmi h4{
font-weight:bold;
font-size:17px;
}

#emarc_content{
width:65%;
height:65%;
position:absolute;
top:15%;
left:20%;
z-index:1000000000;
background:#01090f;
padding:2%;
margin-left: -15px;
opacity:0.8;
color:#f6c516;
font-size:25px;
text-align:center;
font-weight:bold;
font-family:'Source Sans Pro', sans-serif;
}
.emark-block{
    background-image: url('img/eMARC_block.jpg');
    background-position: center;
}

.explore-button{
	display:block;
	background-color:orange;
	padding:2px;
	color:#000;
}

#emarc_content:hover {
  /*  background-color: #e7f3e9;*/
background:#1c5986;
color:#fff;
}
.connectedSortable {
    /*min-height: 805px !important;*/
    min-height: 570px !important;
}

.emarc{
text-align:center;
font-weight:bold;
}
.emarc a{
color:#f6c516;
}

.emarc h1{
font-size:50px;
}
.emarc h4{
font-weight:bold;
font-size:17px;
}

@media only screen and (max-width: 600px) {

#esmi_content{
	height:65%;
    }


#emarc_content{
	height:70%;
}
}
</style>
<!----------------------------------------------------------------->
<div style=" text-align:center;">
<!--img src="img/logo.gif" style="position:absolute; left:45%; width:150px;float:left;"-->
<!--img src="img/logo.gif" style="width:150px;"-->
<a href="http://www.prayaspune.org/peg/index.php" target="_new"><img src="img/REVISED_Prayas_logo.png" style="width:75px;"></a>
<!--h1 style="margin-left: 39%; margin-top: 6%; font-size: 15px; font-weight:bold;"> Remote Monitoring Initiatives</h1-->
<h1 style="margin-top:0;font-size: 15px; font-weight:bold;"> Remote Monitoring Initiatives</h1>
</div>
<div class="row">
<section class="col-lg-6 connectedSortable esmi-blog">                         
<div id="esmi_content" onClick="location.href='/index.php/';">
<p>
<div class="esmi">
<a href="/index.php"><h1>ESMI</h1></a>
<h4>Electricity Supply Monitoring Initiative</h4>
</div>
<br />
<em>
ESMI provides evidence based feedback about electricity supply quality from consumer locations across India. 
</em>
<br />
<a href="/index.php" class="explore-button">Explore</a>
</p>
</div>
<!--a href="/index.php"><img src="/img/ESMI_Block.jpg" style="background-position:left top; margin-left: -140px; margin-top: -38px;"></a-->
</section>

<section class="col-lg-6 connectedSortable emark-block">                         
<div id="emarc_content" onClick="location.href='http://emarc.watchyourpower.org/';">
<p>
<div class="emarc" style="padding-bottom:6%;">
<a href="http://emarc.watchyourpower.org/"><h1>eMARC</h1></a>
<h4>Monitoring and Analysis of Residential Electricity Consumption</h4>
</div>
<em>
eMARC provides insights on electricity consumption in Indian homes from a selected sample of households and appliances. 
</em>
<br />
<a href="http://emarc.watchyourpower.org/" class="explore-button">Explore</a>
</p>
</div>
<!--a href="http://emarc.watchyourpower.org/"><img src="/img/eMARC_block.jpg"></a-->
</section>

</div>
<footer class="box box-footer" style="height:60px; margin-bottom:0px ; background:#fff !important; text-align:center !important;">
<div class="footer-right" style="float:right; margin-top:1%;">
		<a href="/index.php" style="text-decoration:underline; color:#000;">Prayas (Energy Group)</a>
</div>
</footer>

</body>
</html>
