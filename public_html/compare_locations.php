<?php
ini_set('memory_limit', '-1');
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
$details= $_GET;
$first_location_id = $details['first_location_id'];
$second_location_id = $details['second_location_id'];
$third_location_id = $details['third_location_id'];
#print_r($details);
if($details['from_date']=='' || $details['to_date']==''){
$details['from_date'] = date('d/m/Y',strtotime('-7 day'));
$details['to_date'] = date('d/m/Y',strtotime('-1 day'));
}

$pie_chart_report_data_first = $u->getLocationSummaryReport($details, $first_location_id);
getArrayToShowPieChart($pie_chart_report_data_first);
#print_r($report_data);
$pie_chart_report_data_second = $u->getLocationSummaryReport($details, $second_location_id);
getArrayToShowPieChart($pie_chart_report_data_second);

$pie_chart_report_data_third = $u->getLocationSummaryReport($details, $third_location_id);
getArrayToShowPieChart($pie_chart_report_data_third);

$column_chart_report_data_first =$u->getColumnChartDataForEvening($details,$first_location_id);
if($column_chart_report_data_first){
getArrayToShowColumnCharts($column_chart_report_data_first);
getFullColumnChartArrayofDateRange($column_chart_report_data_first, $details);
$column_chart_report_data_first = array_values($column_chart_report_data_first);
}

$column_chart_report_data_second =$u->getColumnChartDataForEvening($details,$second_location_id);
if($column_chart_report_data_second){
getArrayToShowColumnCharts($column_chart_report_data_second);
getFullColumnChartArrayofDateRange($column_chart_report_data_second, $details);
$column_chart_report_data_second = array_values($column_chart_report_data_second);
}

$column_chart_report_data_third =$u->getColumnChartDataForEvening($details,$third_location_id);
if($column_chart_report_data_third){
getArrayToShowColumnCharts($column_chart_report_data_third);
getFullColumnChartArrayofDateRange($column_chart_report_data_third, $details);
$column_chart_report_data_third = array_values($column_chart_report_data_third);
}

$line_chart_data = $u->getLineChartDetailsToCompare($details, $first_location_id,$second_location_id,$third_location_id);
getLineChartArrayToCompareLocations($line_chart_data);

//print_r($line_chart_data);
if(empty($pie_chart_report_data_first) && empty($pie_chart_report_data_second)){
$legend_data = $pie_chart_report_data_third;
}else if(empty($pie_chart_report_data_third) && empty($pie_chart_report_data_second)){
$legend_data = $pie_chart_report_data_first;
}else{
$legend_data = $pie_chart_report_data_second;
}

#$interruptions = $u->getInterruptions($details);
#$interrupt_duration = getFormattedInterruptionsArray($interruptions);

if($details['first_location_id']){
$first_location = $u->getLocationDetails($details['first_location_id']);
$details['first_state'] = $first_location['state'];
$details['first_category_id'] = $first_location['revenue_classification'];
}


if($details['second_location_id']){
$second_location = $u->getLocationDetails($details['second_location_id']);
$details['second_state'] = $second_location['state'];
$details['second_category_id'] = $second_location['revenue_classification'];
}

if($details['third_location_id']){
$third_location = $u->getLocationDetails($details['third_location_id']);
$details['third_state'] = $third_location['state'];
$details['third_category_id'] = $third_location['revenue_classification'];
}

$locations = array(
		array('key' => 'voltage_'.$first_location['id'], 'name'=> $first_location['name'], 'color'=>'#FF6600'), 
		array('key' => 'voltage_'.$second_location['id'], 'name'=> $second_location['name'], 'color'=>'#003366'), 
		array('key' => 'voltage_'.$third_location['id'], 'name'=> $third_location['name'], 'color'=>'#8B0A50'), 
		);



#if($details['first_state']!='' and $details['first_category_id']!=''){
$first_loc_list = $u->getLocationsFromStateAndCategory($details['first_state'],$details['first_category_id']);
#}
#if($details['second_state']!='' and $details['second_category_id']!=''){
$second_loc_list = $u->getLocationsFromStateAndCategory($details['second_state'],$details['second_category_id']);
#}

$third_loc_list = $u->getLocationsFromStateAndCategory($details['third_state'],$details['third_category_id']);

$categories=$u->getRevenueClassification();
$states = $u->getAllStatesFromLocationsWeb();
$districts = $u->getAvailableDistrictsFromCriteria();
#print_r($line_chart_details);
$current="compare_locations";

if(empty($pie_chart_report_data_first)){
$flag_1 =1;
$date_range = $u->getDateRangeForAvailableData($first_location_id);
	if($flag_1==1){
		$empty_pie_chart_msg_second = "<span class=\"empty_chart\">No data available for graph</span>";
	}else{
		$first_min_date = date('d/m/Y',strtotime($date_range['min_date']));
		$first_max_date = date('d/m/Y',strtotime($date_range['max_date']));
		$empty_pie_chart_msg_first = "<span class=\"empty_chart\">Data available from ".$first_min_date." to ". $first_max_date."</span>";
	}
}

if(empty($pie_chart_report_data_second)){
$flag_2 =1;
$date_range = $u->getDateRangeForAvailableData($second_location_id);
	if($flag_2==1 ){
		$empty_pie_chart_msg_second = "<span class=\"empty_chart\">No data available for graph</span>";
	}else{
		$second_min_date = date('d/m/Y',strtotime($date_range['min_date']));
		$second_max_date = date('d/m/Y',strtotime($date_range['max_date']));
		$empty_pie_chart_msg_second = "<span class=\"empty_chart\">Data available from ".$second_min_date." to ". $second_max_date."</span>";
	}
}

if(empty($pie_chart_report_data_third)){
$flag_3 =1;
$date_range = $u->getDateRangeForAvailableData($third_location_id);
	if($flag_3==1 ){
		$empty_pie_chart_msg_third = "<span class=\"empty_chart\">No data available for graph</span>";
	}else{
		$third_min_date = date('d/m/Y',strtotime($date_range['min_date']));
		$third_max_date = date('d/m/Y',strtotime($date_range['max_date']));
		$empty_pie_chart_msg_third = "<span class=\"empty_chart\">Data available from ".$third_min_date." to ". $third_max_date."</span>";
	}
}

if(empty($column_chart_report_data_first)){
$date_range = $u->getDateRangeForAvailableData($first_location_id);
	if($flag_1==1 ){
		$empty_column_chart_msg_first = "<span class=\"empty_chart\">No data available for graph</span>";
	}else{
		$first_min_date = date('d/m/Y',strtotime($date_range['min_date']));
		$first_max_date = date('d/m/Y',strtotime($date_range['max_date']));
		$empty_column_chart_msg_first = "<span class=\"empty_chart\">Data available from ".$first_min_date." to ". $first_max_date."</span>";
	}
}

if(empty($column_chart_report_data_second)){
$date_range = $u->getDateRangeForAvailableData($second_location_id);
	if($flag_2==1){
		$empty_column_chart_msg_second = "<span class=\"empty_chart\">No data available for graph</span>";
	}else{
		$second_min_date = date('d/m/Y',strtotime($date_range['min_date']));
		$second_max_date = date('d/m/Y',strtotime($date_range['max_date']));
		$empty_column_chart_msg_second = "<span class=\"empty_chart\">Data available from ".$second_min_date." to ". $second_max_date."</span>";
}
}

if(empty($column_chart_report_data_third)){
$date_range = $u->getDateRangeForAvailableData($third_location_id);
	if($flag_3==1){
		$empty_column_chart_msg_third = "<span class=\"empty_chart\">No data available for graph</span>";
	}else{
		$third_min_date = date('d/m/Y',strtotime($date_range['min_date']));
		$third_max_date = date('d/m/Y',strtotime($date_range['max_date']));
		$empty_column_chart_msg_third = "<span class=\"empty_chart\">Data available from ".$third_min_date." to ". $third_max_date."</span>";
	}
}

if(empty($line_chart_data)){
$empty_line_chart_msg = "<span class=\"empty_chart\">No data available for graph</span>";
}
$params = getSummaryParameters();
$voltage_params=$u->getVoltageParamValues(7);
$avg_high_volt = $voltage_params['normal']['high_value'];
$avg_low_volt = $voltage_params['normal']['low_value'];
#print_r($pie_chart_report_data_first);
?>
<!DOCTYPE html>
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/html_head.php"; ?>
<script type="text/javascript">
	var locations = <?php echo json_encode($locations); ?>;
	var params = <?php echo json_encode($params); ?>;
<?php if($pie_chart_report_data_first){ ?>
	var piechartDataFirst = <?php echo json_encode($pie_chart_report_data_first); ?>;
<?php } if($pie_chart_report_data_second){ ?>
	var piechartDataSecond = <?php echo json_encode($pie_chart_report_data_second); ?>;
<?php } if($pie_chart_report_data_third){ ?>
	var piechartDataThird = <?php echo json_encode($pie_chart_report_data_third); ?>;
<?php } ?>

<?php if($column_chart_report_data_first){ ?>
	var barchartDataFirst = <?php echo json_encode($column_chart_report_data_first); ?>;
<?php } if($column_chart_report_data_second){ ?>
	var barchartDataSecond = <?php echo json_encode($column_chart_report_data_second); ?>;
<?php } if($column_chart_report_data_third){ ?>
	var barchartDataThird = <?php echo json_encode($column_chart_report_data_third); ?>;
<?php } if($line_chart_data){ ?>
	var linechartData = <?php  echo json_encode($line_chart_data); ?>;
<?php } ?>

$(function() {
	$('.progress-indicator').show();

	AmCharts.ready(function () {
	$('.progress-indicator').hide();
<?php if($pie_chart_report_data_first){ ?>
		createPieChart(piechartDataFirst,'desc','value','graph_display_color','piechartdiv');
<?php } if($pie_chart_report_data_second){ ?>
		createPieChart(piechartDataSecond,'desc','value','graph_display_color','piechartdiv2');
<?php } if($pie_chart_report_data_third){ ?>
		createPieChart(piechartDataThird,'desc','value','graph_display_color','piechartdiv3');
<?php } if($column_chart_report_data_first){ ?>
		createStackedColumnChart(barchartDataFirst,'columnchartdiv',params,1);
<?php } if($column_chart_report_data_second){ ?>
		createStackedColumnChart(barchartDataSecond,'columnchartdiv2',params,0);
<?php } if($column_chart_report_data_third){ ?>
		createStackedColumnChart(barchartDataThird,'columnchartdiv3',params,0);
<?php } if($line_chart_data){ ?>
		createLineChartToCompare(linechartData, 'date','voltage','linechartdiv',locations,'<?php echo $avg_high_volt; ?>','<?php echo $avg_low_volt;?>');
<?php } ?>
	});

var dateRange = getMinMaxDates(1);
var today = new Date();
maxdate = (today.getDate()-1 +'/'+(today.getMonth() + 1) +'/' +  today.getFullYear());
$( "#from_date" ).datetimepicker({
	formatTime:'H:i', 
	format:'d/m/Y', 
	formatDate:'d/m/Y', 
	//minDate:dateRange.minDate, 
	//maxDate:dateRange.maxDate, 
	timepicker:false,
	closeOnDateSelect:true 
	});

$( "#to_date" ).datetimepicker({
	formatTime:'H:i', 
	format:'d/m/Y', 
	formatDate:'d/m/Y', 
	//minDate:dateRange.minDate, 
	//maxDate:dateRange.maxDate, 
	timepicker:false,
	maxDate:maxdate,
	closeOnDateSelect:true 
	});

var page = '<?php echo $current;?>';
$(document).on("change","#first_category_id", function(){
	var obj ={};
	obj.page = page;
	obj.first_category_id = $('#first_category_id').val();
	if(obj.first_category_id.length == 0){
	    document.getElementById('first_state').value = "";
	    document.getElementById('first_district').value = "";
	    document.getElementById('first_location_id').value = "";
	}

		obj.state = $('#first_state').val();
		obj.district = $('#first_district').val();

		obj.state_div_id = $('#first_state').attr('id');
		obj.location_div_id = $('#first_location_id').attr('id');
		obj.district_div_id = $('#first_district').attr('id');


		getStates(obj,'first_stat');
		//if(obj.state!=''){
		getDistricts(obj,'first_dist');
		// }
		getLocations(obj,'first_loc');
});

$(document).on("change","#first_state", function(){
        var obj ={};
	obj.page = page;
        obj.state = $('#first_state').val();  			
obj.district = $('#first_district').val();      
		obj.category_id = $('#first_category_id').val();
		if(obj.state.length == 0 && obj.category_id.length == 0){
    			document.getElementById('first_district').value = "";
    document.getElementById('first_location_id').value = "";
		}

	
        obj.state_div_id = $('#first_state').attr('id');
        obj.location_div_id = $('#first_location_id').attr('id');
        obj.district_div_id = $('#first_district').attr('id');
        //if(obj.state!=''){
        getDistricts(obj,'first_dist');
        //}
        getLocations(obj,'first_loc');
});


$(document).on("change","#second_category_id", function(){
	var obj ={};
	obj.page = page;

	obj.second_category_id = $('#second_category_id').val();
if(obj.second_category_id.length == 0){ //alert(obj.category_id);
    document.getElementById('second_state').value = "";
    document.getElementById('second_district').value = "";
    document.getElementById('second_location_id').value = "";
}
	obj.district = $('#second_district').val();
	obj.state = $('#second_state').val();


	obj.state_div_id = $('#second_state').attr('id');
	obj.location_div_id= $('#second_location_id').attr('id');
	obj.district_div_id = $('#second_district').attr('id');
	getStates(obj,'second_stat');
        //if(obj.state!=''){
        getDistricts(obj,'second_dist');
        //}
	getLocations(obj,'second_loc');
});

$(document).on("change","#second_state", function(){
        var obj ={};
        obj.page = page;
 obj.state = $('#second_state').val();
        obj.category_id = $('#second_category_id').val();
        obj.district = $('#second_district').val();       
if(obj.state.length == 0 && obj.category_id.length == 0){
   document.getElementById('second_district').value = "";
    document.getElementById('second_location_id').value = "";
}
if(obj.state.length == 0 ){
   document.getElementById('second_district').value = "";
    document.getElementById('second_location_id').value = "";
}



        obj.state_div_id = $('#second_state').attr('id');
        obj.location_div_id = $('#second_location_id').attr('id');
        obj.district_div_id = $('#second_district').attr('id');
        //if(obj.state!=''){
        getDistricts(obj,'second_dist');
        //}
        getLocations(obj,'second_loc');
});

$(document).on("change","#third_category_id", function(){
        var obj ={};
	obj.page = page;
        
        obj.third_category_id = $('#third_category_id').val();
		if(obj.third_category_id.length == 0){
		    document.getElementById('third_state').value = "";
		   document.getElementById('third_district').value = "";
		    document.getElementById('third_location_id').value = "";
		}

	obj.state_div_id = $('#third_state').attr('id');
        obj.location_div_id= $('#third_location_id').attr('id');
        obj.district_div_id = $('#third_district').attr('id');
	getStates(obj,'third_stat');
        //if(obj.state!=''){
        getDistricts(obj,'third_dist');
        //}
        getLocations(obj,'third_loc');
});

$(document).on("change","#third_state", function(){
        var obj ={};
        obj.page = page;
        obj.state = $('#third_state').val();
        obj.category_id = $('#third_category_id').val();
		if(obj.state.length == 0 && obj.category_id.length == 0){
		   document.getElementById('third_district').value = "";
		   document.getElementById('third_location_id').value = "";
		}
		if(obj.state.length == 0 ){
		   document.getElementById('third_district').value = "";
		    document.getElementById('third_location_id').value = "";
		}
        obj.district = $('#third_district').val();
        obj.location_div_id= $('#third_location_id').attr('id');
        obj.district_div_id = $('#third_district').attr('id');
        //if(obj.state!=''){
        getDistricts(obj,'third_dist');
        //}
        getLocations(obj,'third_loc');
});


$(document).on("change","#first_district", function(){
	var obj ={};
	obj.page = page;
	obj.state = $('#first_state').val();
	obj.category_id = $('#first_category_id').val();
	obj.district = $('#first_district').val();
		if(obj.state.length == 0 && obj.category_id.length == 0 && obj.district.length == 0){

		   document.getElementById('first_location_id').value = "";
		}

	obj.location_div_id = $('#first_location_id').attr('id');
	getLocations(obj,'first_loc');
});

$(document).on("change","#second_district", function(){
	var obj ={};
	obj.page = page;
	obj.state = $('#second_state').val();
	obj.category_id = $('#second_category_id').val();
	obj.district = $('#second_district').val();

if(obj.state.length == 0 && obj.category_id.length == 0 && obj.district.length == 0){

   document.getElementById('second_location_id').value = "";
}

	obj.location_div_id= $('#second_location_id').attr('id');
	getLocations(obj,'second_loc');
});

$(document).on("change","#third_district", function(){
        var obj ={};
	obj.page = page;
        obj.state = $('#third_state').val();
        obj.category_id = $('#third_category_id').val();
        obj.district = $('#third_district').val();

if(obj.state.length == 0 && obj.category_id.length == 0 && obj.district.length == 0){ alert(obj.category_id);

   document.getElementById('third_location_id').value = "";
}

        obj.location_div_id= $('#third_location_id').attr('id');
        getLocations(obj,'third_loc');
});


$(document).on("change", "#first_location_id, #second_location_id, #third_location_id", function(){
	//if($('#first_location_id').val()!='' && $('#second_location_id').val()!='' && $('#third_location_id').val()!=''){
	if(($('#first_location_id').val()!='' && $('#second_location_id').val()!='') || ($('#first_location_id').val()!='' && $('#third_location_id').val()!='') || ($('#second_location_id').val()!='' && $('#third_location_id').val()!='')){
		$('#submit').show();
	}else{
		$('#submit').hide();
	}
});

});
 
function showSubmitButton(){
if($('#first_location_id').val()!='' && $('#second_location_id').val()!=''){
                $('#submit').show();
        }else{
                $('#submit').hide();
        }
}

function validateForm(theForm){
	if(getDaysInDateRange(theForm.from_date.value, theForm.to_date.value) > 31){
		alert('Please select a date-range of up to 31 days.');
		return false;
	}
	return true;
}

</script>
</head>
<body class="skin-blue" onload="showSubmitButton();">
        <!-- header logo: style can be found in header.less -->
               <?php include $_SERVER['DOCUMENT_ROOT']."/includes/header_banner.php"; ?>
<div class="wrapper row-offcanvas row-offcanvas-left">
               <?php include $_SERVER['DOCUMENT_ROOT']."/includes/left_menu.php"; ?>
<aside class="right-side strech" >
<section class="content-header">
	<h1>Compare supply quality across ESMI locations</h1>
</section>

<section class="content">
<!-- Main content -->
<form method="get" action="" autocomplete="off" name="search" id="search" onsubmit="return validateForm(this); ">
<div class="row">
	<div class="col-md-4" style="width:25%;">
        <!-- Primary box -->
	<div class="box box-solid box-primary">
	<div class="box-header">
	<h3 class="box-title">First Location</h3>
		<!--<div class="box-tools pull-right">
		<button data-widget="collapse" class="btn btn-primary btn-sm"><i class="fa fa-minus"></i></button>
		<button data-widget="remove" class="btn btn-primary btn-sm"><i class="fa fa-times"></i></button>
		</div>-->
	</div>
	<div class="box-body" style="min-height:176px;">
	<div style="margin-bottom:5px;">
	<label style="float:left; width:20%; margin-right:10px; font-weight:normal;">Category: </label>
	<select class="form-control" style="width:75%;" id="first_category_id" name="first_category_id">
                <option value="">-- Select  Category--</option>
                <?php foreach($categories as $cat){
                                if($cat['name'] == 'Mega city') continue; // hiding Mega city option only in the website.
                ?>
                <option value="<?php echo $cat['id']; ?>" <?php if($details['first_category_id']== $cat['id']){ echo 'selected="selected"';}?> ><?php echo $cat['name']; ?></option>
                <?php } ?>
	</select>
	</div>
	<div style="margin-bottom:5px;">
	<label style="float:left; width:20%; margin-right:10px; font-weight:normal;">State: </label>
	<div id="first_stat">
	<select class="form-control" style="width:75%;" id="first_state" name="first_state">
        	<option value="">-- Select  State --</option>
                <?php foreach($states as $state){ ?>
                <option value="<?php echo $state; ?>" <?php if($details['first_state']== $state){ echo 'selected="selected"';}?>><?php echo $state; ?></option>
                        <?php } ?>
	</select>
	</div>
	</div>
	<div style="margin-bottom:5px;">
	<label style="float:left; width:20%; margin-right:10px; font-weight:normal;">District: </label>
	<div id="first_dist">
	<select class="form-control" style="width:75%;" id="first_district" name="first_district">
		<option value="">-- Select  District --</option>
		<?php foreach($districts as $dist){ ?>
		<option value="<?php echo $dist['district']; ?>" <?php if($details['first_district']== $dist['district']){ echo 'selected="selected"';}?>><?php echo $dist['district']; ?></option>
                        <?php } ?>
	</select>
	</div>
	</div>
	<div style="margin-bottom:5px;">
	<label style="float:left; width:20%; margin-right:10px; font-weight:normal;">Location: </label>
	<div id="first_loc">
	<select class="form-control" style="width:75%;" id="first_location_id" name="first_location_id">
                <option value="">-- Select  --</option>
                <?php foreach($first_loc_list as $loc){ if($loc['status']==0){ continue;}  ?>
                <option value="<?php echo $loc['id']; ?>" <?php if($details['first_location_id']== $loc['id']){ echo 'selected="selected"';}?>><?php echo $loc['name']; ?></option>
                <?php } ?>
	</select>
	</div>
	</div>
        </div><!-- /.box-body -->
        </div><!-- /.box -->
        </div><!-- /.col -->

        <div class="col-md-4" style="width:25%;">
        <!-- Primary box -->
        <div class="box box-solid box-primary">
        <div class="box-header">
        <h3 class="box-title">Second Location</h3>
                <!--<div class="box-tools pull-right">
                <button data-widget="collapse" class="btn btn-primary btn-sm"><i class="fa fa-minus"></i></button>
                <button data-widget="remove" class="btn btn-primary btn-sm"><i class="fa fa-times"></i></button>
                </div>-->
        </div>

        <div class="box-body" style="min-height:176px;">
	<div style="margin-bottom:5px;">
	<label style="float:left; width:20%; margin-right:10px; font-weight:normal;">Category: </label>
	<select class="form-control" style="width:75%;" id="second_category_id" name="second_category_id">
                <option value="">-- Select  Category--</option>
                <?php foreach($categories as $cat){
                                if($cat['name'] == 'Mega city') continue; // hiding Mega city option only in the website.
                ?>
                <option value="<?php echo $cat['id']; ?>" <?php if($details['second_category_id']== $cat['id']){ echo 'selected="selected"';}?> ><?php echo $cat['name']; ?></option>
                <?php } ?>
	</select>
	</div>

	<div style="margin-bottom:5px;">
	<label style="float:left; width:20%; margin-right:10px; font-weight:normal;">State: </label>
	<div id="second_stat">
	<select class="form-control" style="width:75%;" id="second_state" name="second_state">
                <option value="">-- Select  State --</option>
                <?php foreach($states as $state){ ?>
                <option value="<?php echo $state; ?>" <?php if($details['second_state']== $state){ echo 'selected="selected"';}?>><?php echo $state; ?></option>
                        <?php } ?>
	</select>
	</div>
	</div>

	<div style="margin-bottom:5px;">
	<label style="float:left; width:20%; margin-right:10px; font-weight:normal;">District: </label>
	<div id="second_dist">
	<select class="form-control" style="width:75%;" id="second_district" name="second_district">
                        <option value="">-- Select  District --</option>
                        <?php foreach($districts as $dist){ ?>
                        <option value="<?php echo $dist['district']; ?>" <?php if($details['second_district']== $dist['district']){ echo 'selected="selected"';}?>><?php echo $dist['district']; ?></option>
                        <?php } ?>
	</select>
	</div>
	</div>

	<div style="margin-bottom:5px;">
	<label style="float:left; width:20%; margin-right:10px; font-weight:normal;">Location: </label>
	<div id="second_loc">
	<select class="form-control" style="width:75%;" id="second_location_id" name="second_location_id">
                <option value="">-- Select  --</option>
                <?php foreach($second_loc_list as $loc){ if($loc['status']==0){ continue;} ?>
                <option value="<?php echo $loc['id']; ?>" <?php if($details['second_location_id']== $loc['id']){ echo 'selected="selected"';}?>><?php echo $loc['name']; ?></option>
                <?php } ?>
	</select>
	</div>
	</div>
        </div><!-- /.box-body -->
        </div><!-- /.box -->
 	</div><!-- /.col -->
	
	<div class="col-md-4" style="width:25%;">
        <!-- Primary box -->
        <div class="box box-solid box-primary">
        <div class="box-header" >
        <h3 class="box-title">Third Location</h3>
                <!--<div class="box-tools pull-right">
                <button data-widget="collapse" class="btn btn-primary btn-sm"><i class="fa fa-minus"></i></button>
                <button data-widget="remove" class="btn btn-primary btn-sm"><i class="fa fa-times"></i></button>
                </div>-->
        </div>
        <div class="box-body" style="min-height:176px;">
	<div style="margin-bottom:5px;">
	<label style="float:left; width:20%; margin-right:10px; font-weight:normal;">Category: </label>
	<select class="form-control" style="width:75%;" id="third_category_id" name="third_category_id">
                <option value="">-- Select  Category--</option>
                <?php foreach($categories as $cat){
                                if($cat['name'] == 'Mega city') continue; // hiding Mega city option only in the website.
                ?>
                <option value="<?php echo $cat['id']; ?>" <?php if($details['third_category_id']== $cat['id']){ echo 'selected="selected"';}?> ><?php echo $cat['name']; ?></option>
                <?php } ?>
        </select>
	</div>
	<div style="margin-bottom:5px;">
	<label style="float:left; width:20%; margin-right:10px; font-weight:normal;">State: </label>
	<div id="third_stat">
        <select class="form-control" style="width:75%;" id="third_state" name="third_state">
                <option value="">-- Select  State --</option>
                <?php foreach($states as $state){ ?>
                <option value="<?php echo $state; ?>" <?php if($details['third_state']== $state){ echo 'selected="selected"';}?>><?php echo $state; ?></option>
                        <?php } ?>
        </select>
	</div>
	</div>

	<div style="margin-bottom:5px;">
	<label style="float:left; width:20%; margin-right:10px; font-weight:normal;">District: </label>
	<div id="third_dist">
        <select class="form-control" style="width:75%;" id="third_district" name="third_district">
                        <option value="">-- Select  District --</option>
                        <?php foreach($districts as $dist){ ?>
                        <option value="<?php echo $dist['district']; ?>" <?php if($details['third_district']== $dist['district']){ echo 'selected="selected"';}?>><?php echo $dist['district']; ?></option>
                        <?php } ?>
        </select>
	</div>
	</div>

	<div style="margin-bottom:5px;">
	<label style="float:left; width:20%; margin-right:10px; font-weight:normal;">Location: </label>
	<div id="third_loc">	
	<select class="form-control" style="width:75%;" id="third_location_id" name="third_location_id">
                <option value="">-- Select  --</option>
                <?php foreach($third_loc_list as $loc){ if($loc['status']==0){ continue;}  ?>
                <option value="<?php echo $loc['id']; ?>" <?php if($details['third_location_id']== $loc['id']){ echo 'selected="selected"';}?>><?php echo $loc['name']; ?></option>
                <?php } ?>
        </select>
	</div>
	</div>

        </div><!-- /.box-body -->
        </div><!-- /.box -->
        </div><!-- /.col -->
	
	<div class="col-md-4" style="width:25%;">
        <!-- Primary box -->
        <div class="box box-solid box-primary">
        <div class="box-header">
        <h3 class="box-title">Date Range</h3>
                <!--<div class="box-tools pull-right">
                <button data-widget="collapse" class="btn btn-primary btn-sm"><i class="fa fa-minus"></i></button>
                <button data-widget="remove" class="btn btn-primary btn-sm"><i class="fa fa-times"></i></button>
                </div>-->
        </div>
        <div class="box-body" style="min-height:176px;">
	<div style="margin-bottom:5px;">
	<label style="float:left; width:20%; margin-right:10px; font-weight:normal;">From Date: </label>
	<input type="text" placeholder="From Date" style="width:75%;" class="form-control datepick" id="from_date" name="from_date" required value="<?php echo $details['from_date']; ?>" size="5" readonly="readonly">
	</div></br>
	<div style="margin-bottom:5px;">
	<label style="float:left; width:20%; margin-right:10px; font-weight:normal;">To Date: </label>
	<input type="text" placeholder="To Date" style="width:75%;" class="form-control datepick" id="to_date" name="to_date" required value="<?php echo $details['to_date']; ?>" size="5" readonly="readonly" >
	</div>
	<div style="margin-bottom:5px;">
         <input type="submit" value="Compare" class="btn btn-primary" id="submit" <?php if($first_location_id=='' || $second_location_id==''||$third_location_id==''){ ?>style="display:none;"<?php } ?> onclick="return validateDate('0');">
	</div>
        </div><!-- /.box-body -->
        </div><!-- /.box -->
        </div><!-- /.col -->


</form>
<hr />
</div>
<div class="progress-indicator" style="">
   <img src="/img/processing.gif" alt="progress bar" />
</div>
<?php if(empty($_GET)){ ?>
<?php } elseif($empty_pie_chart_msg_first && $empty_pie_chart_msg_second 
	&& $empty_column_chart_msg_first && $empty_column_chart_msg_second 
	&& $empty_column_chart_msg_third && $empty_pie_chart_msg_third 
	&& $empty_line_chart_msg){ ?>
<div class="parent-div"><span class="empty_chart">
<?php if($date_range['min_date']=='' && $date_range['max_date']==''){ ?>
No Data Available
<?php } else {?>
Data is available for below date range</br>
First Location: From <?php echo $first_min_date; ?> To <?php echo $first_max_date;?></br>
Second Location: From <?php echo $second_min_date; ?> To <?php echo $second_max_date;?></br>
Third Location: From <?php echo $third_min_date; ?> To <?php echo $third_max_date;?>
<?php }?>
</span></div>
<?php }else{ ?>


<!--<h4>Voltage Profile</h4>-->
<h4>Supply Profile</h4>
<div class="parent-div">
<div class="first_location" <?php if($third_location_id == ''){ echo 'style="width:50% !important;"';}?>><?php echo $first_location['name']?></div>
<div class="second_location" <?php if($third_location_id == ''){ echo 'style="width:50% !important; float:right;"';}else{ echo 'style="float:left"';}?>><?php echo $second_location['name']?></div>
<?php if($third_location_id!=''){?><div class="third_location" style="float:right;"><?php echo $third_location['name']?></div><?php }?>
</div>
<div class="parent-div">
<div id="piechartdiv" <?php if($third_location_id == ''){ echo 'style="width:50% !important;"';}?>><?php echo $empty_pie_chart_msg_first; ?></div>
<div id="piechartdiv2" <?php if($third_location_id == ''){ echo 'style="width:50% !important; float:right;"';}else{ echo 'style="float:left;"';}?>><?php echo $empty_pie_chart_msg_second; ?></div>
<?php if($third_location_id!=''){?><div id="piechartdiv3" style="float:right;"><?php echo $empty_pie_chart_msg_third; ?></div><?php }?>
</div></br>
<div class="parent-div">
<div style="width:60%; border:1px solid #333; padding:5px 20px 0px 20px; text-align:center; margin-left:240px; margin-top:230px;">
<?php foreach($legend_data as $l_data){?>
<div style="width:15px;height:15px; padding:5px 5px 5px 5px; display:inline-block; background-color:<?php echo $l_data['graph_display_color'];?>;"></div>
<p style="display:inline-block; margin-right:10px; margin-left:10px;"><?php echo $l_data['desc'];?></p>
<?php }?>
</div>
</div>
<div class="hr-div">&nbsp;<hr /></div>

<!--<h4>Evening Supply (Availability of Electricity during 5 pm - 11 pm)</h4>-->
<h4>Evening Supply hours (5pm-11pm)</h4>
<div class="parent-div">
<div class="first_location" <?php if($third_location_id == ''){ echo 'style="width:50% !important;"';}?>><?php echo $first_location['name']?></div>
<div class="second_location" <?php if($third_location_id == ''){ echo 'style="width:50% !important; float:right;"';}else{ echo 'style="float:left;"';}?>><?php echo $second_location['name']?></div>
<?php if($third_location_id!=''){?><div class="third_location" style="float:right;"><?php echo $third_location['name']?></div><?php }?>
</div>
<div class="parent-div">
<div id="columnchartdiv" <?php if($third_location_id == ''){ echo 'style="width:50% !important;"';}?>><?php echo $empty_column_chart_msg_first; ?></div>
<div id="columnchartdiv2" <?php if($third_location_id == ''){ echo 'style="width:50% !important; float:right;"';}else{ echo 'style="float:left;"';}?>><?php echo $empty_column_chart_msg_second; ?></div>
<?php if($third_location_id!=''){?><div id="columnchartdiv3" style="float:right;"><?php echo $empty_column_chart_msg_third; ?></div><?php }?>
</div>

<div class="hr-div">&nbsp;<hr /></div>

<!--<h4>Daily Voltage Supply</h4>-->
<h4>Voltage Profile</h4>
<div class="parent-div">
<div id="linechartdiv"><?php echo $empty_line_chart_msg; ?></div>
</div>
<?php } ?>

<div class="mailbox">
	<div class="table-responsive">
	</div>
</div>

</section><!-- /.content -->
</aside><!-- /.right-side -->

</div><!-- ./wrapper -->


<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>

    </body>
</html>
