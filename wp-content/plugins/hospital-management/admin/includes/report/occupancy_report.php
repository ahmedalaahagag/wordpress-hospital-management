<?php 

$month =hmgt_month_list();
if(isset($_POST['view_occupancy']))
{
	$start_date = $_POST['sdate'];
	$end_date = $_POST['edate'];
	global $wpdb;
	$hmgt_bed_allotment = $wpdb->prefix."hmgt_bed_allotment";
	$hmgt_bed = $wpdb->prefix."hmgt_bed";
	$posts = $wpdb->prefix."posts";
	$sql_query = "SELECT post.post_title as bedtype,count(allotment_date) as count FROM $hmgt_bed_allotment as bed_altmt 
			inner join $hmgt_bed as bed on bed.bed_id =  bed_altmt.bed_number inner join $posts as post on post.id = bed.bed_type_id WHERE
			bed_altmt.allotment_date BETWEEN '$start_date' AND '$end_date' GROUP BY bed.bed_type_id";
	$result=$wpdb->get_results($sql_query);
	
	
	 $chart_array = array();
	$chart_array[] = array('Bed Number','Bed Count');
	foreach($result as $r)
	{
		$chart_array[]=array( "$r->bedtype",(int)$r->count);
	}
	
	
	$options = Array(
			'title' => 'Occupancy Report',
          'pieHole' => 0.2,
			'pieSliceText' => 'value'
	); 
}

require_once HMS_PLUGIN_DIR.'/lib/chart/GoogleCharts.class.php';
$GoogleCharts = new GoogleCharts;
if(isset($chart_array))
{
$chart = $GoogleCharts->load( 'pie' , 'chart_div' )->get( $chart_array , $options );
}
?>
<script type="text/javascript">
$(document).ready(function() {
	
	$('.sdate').datepicker({dateFormat: "yy-mm-dd"}); 
	$('.edate').datepicker({dateFormat: "yy-mm-dd"}); 

 
} );
</script>
<div class="page-inner" style="min-height:1631px !important">
<div class="page-title">
		<h3><img src="<?php echo get_option( 'hmgt_hospital_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'hmgt_hospital_name' );?></h3>
	</div>
	<div id="main-wrapper">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-white">
					<div class="panel-body">
		<h2 class="nav-tab-wrapper">
    	<a href="?page=occupancy_report" class="nav-tab nav-tab-active">
		<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Occupancy Report', 'hospital_mgt'); ?></a>    	
		
    </h2>
    
  <form name="occupancy_report" action="" method="post">

     
	<div class="form-group col-md-3">
    	<label for="sdate"><?php _e('Strat Date','hospital_mgt');?></label>
       
					
            	<input type="text"  class="form-control sdate" name="sdate" value="<?php if(isset($_REQUEST['sdate'])) echo $_REQUEST['sdate'];else echo date('Y-m-d');?>">
            	
    </div>
    <div class="form-group col-md-3">
    	<label for="edate"><?php _e('End Date','hospital_mgt');?></label>
			<input type="text"  class="form-control edate" name="edate" value="<?php if(isset($_REQUEST['edate'])) echo $_REQUEST['edate'];else echo date('Y-m-d');?>">
            	
    </div>
    <div class="form-group col-md-3 button-possition">
    	<label for="subject_id">&nbsp;</label>
      	<input type="submit" name="view_occupancy" Value="<?php _e('Go','hospital_mgt');?>"  class="btn btn-info"/>
    </div>	
</form>
<div class="clearfix"></div>
    	
    	<?php if(isset($result) && count($result) >0){?>
  <div id="chart_div" style="width: 100%; height: 500px;"></div>
  
  <!-- Javascript --> 
  <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
  <script type="text/javascript">
			<?php echo $chart;?>
		</script>
  <?php }
 if(isset($result) && empty($result)) {?>
  <div class="clear col-md-12"><?php _e("There is not enough data to generate report.",'hospital_mgt');?></div>
  <?php }?>

</div></div>
</div>

