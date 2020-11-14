<aside class="left-side sidebar-offcanvas <?php if($current=='home'||$current=='download_data'||$current=='select_locations'||$current=='compare_locations'){ echo 'collapse-left';}?>" <?php if($current!='home'){ ?>style="top:100px !important;" <?php } ?>>
<section class="sidebar">
<!-- Sidebar user panel -->
<ul class="sidebar-menu">
<li class="active">
<a href="index.php" class="bg-light-blue">
<i class="fa fa-dashboard"></i> <span>Home</span>
</a>
<li class="treeview">
<a href="#" class="bg-light-blue">
<i class="fa fa-folder"></i> <span>About ESMI</span>
<i class="fa fa-angle-left pull-right"></i>
</a>
<ul class="treeview-menu">
<li style="margin-left:-11px !important;"><a href="/the_initiative.php" class="bg-light-blue"><i class="fa fa-angle-double-right"></i> The Initiative </a></li>
<li style="margin-left:-11px !important;"><a href="/prayas_energy_group.php" class="bg-light-blue"><i class="fa fa-angle-double-right"></i>Prayas Energy Group</a></li>
<li style="margin-left:-11px !important;"><a href="/partners.php" class="bg-light-blue"><i class="fa fa-angle-double-right"></i>Partners</a></li>
<li style="margin-left:-11px !important;"><a href="/faqs.php" class="bg-light-blue"><i class="fa fa-angle-double-right"></i>FAQ's</a></li>
<li style="margin-left:-11px !important;"><a href="/terms_of_data_use.php" class="bg-light-blue"><i class="fa fa-angle-double-right"></i>Terms of Use</a></li>
<li style="margin-left:-11px !important;"><a href="/esmi_expanse.php" class="bg-light-blue"><i class="fa fa-angle-double-right"></i>ESMI Expanse</a></li>
<li style="margin-left:-11px !important;"><a href="/contact_us.php" class="bg-light-blue"><i class="fa fa-angle-double-right"></i>Contact Us</a></li>
</ul>
<!--<li class="active">
<a href="esmi_expanse.php" class="bg-light-blue">
<i class="fa fa-laptop"></i>
<span>ESMI Expanse</span>
</a>
</li>-->
</li>
<!--<li class="active">
<a href="#" class="bg-light-blue">
<i class="fa fa-laptop"></i>
<span>Coverage</span>
</a>
</li>

<li class="active">
<a href="contact_us.php" class="bg-light-blue">
<i class="fa fa-envelope"></i> <span>Contact Us</span>
</a>
</li>-->
<li class="treeview">
<a href="#" class="bg-light-blue">
<i class="fa fa-folder"></i> <span>Select Locations</span>
<i class="fa fa-angle-left pull-right"></i>
<ul class="treeview-menu">
<li style="margin-left:-11px !important;"><a href="/location_map.php" class="bg-light-blue"><i class="fa fa-angle-double-right"></i>Select On Map</a></li>
<li style="margin-left:-11px !important;"><a href="/reports.php" class="bg-light-blue"><i class="fa fa-angle-double-right"></i>Select From List</a></li>
</ul>

</li>
<li class="active">
<a href="compare_locations.php" class="bg-green">
<i class="fa fa-edit"></i><span>Compare Locations</span>
</a>
</li>
</li>
<li class="active">
<a href="esmi_beyond_india.php" class="bg-light-blue">
<i class="fa fa-edit"></i><span>ESMI Beyond India</span>
</a>
</li>
<li class="treeview">
<a href="#" class="bg-yellow">
<i class="fa fa-folder"></i> <span>Downloads</span>
<i class="fa fa-angle-left pull-right"></i>
</a>
<ul class="treeview-menu">
<!--<li style="margin-left:-11px !important;"><a href="analysis_report.php" class="bg-yellow"><i class="fa fa-angle-double-right"></i>Analysis reports</a></li>-->
<li style="margin-left:-11px !important;"><a href="/download_raw_data.php" class="bg-yellow"><i class="fa fa-angle-double-right"></i>Data</a></li>
<li style="margin-left:-11px !important;"><a href="/uploaded_reports.php" class="bg-yellow"><i class="fa fa-angle-double-right"></i>Analysis Reports</a></li>

<li style="margin-left:-11px !important;"><a href="/analysis_dashboard.php" class="bg-yellow"><i class="fa fa-angle-double-right"></i>State dashboard</a></li>
<li style="margin-left:-11px !important;"><a href="/location_dashboard.php" class="bg-yellow"><i class="fa fa-angle-double-right"></i>Location dashboard</a></li>
<li style="margin-left:-11px !important;"><a href="/minute_wise_data.php" class="bg-yellow"><i class="fa fa-angle-double-right"></i>Minute-wise data</a></li>
<?php if($u->user_id!=''){ ?>
<?php if($_SESSION['user_type']=='Admin'){?>
<li style="margin-left:-11px !important;"><a href="/daily_summary.php" class="bg-yellow"><i class="fa fa-angle-double-right"></i>Daily Summary</a></li><?php }?>
<li style="margin-left:-11px !important;"><a href="/stored_procedure.php" class="bg-yellow"><i class="fa fa-angle-double-right"></i>Multiple Location Data</a></li>


<?php } ?>
</ul>
</li>

<li class="active">
<a href="how_cani_contribute.php" class="bg-red">
<i class="fa fa-laptop"></i>
<span>Get Involved</span>
</a>
</li>
</ul>
</section>
</aside>
