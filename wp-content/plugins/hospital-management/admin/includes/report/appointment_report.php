<?php 
$active_tab=isset($_REQUEST['tab'])?$_REQUEST['tab']:'day';
$month =hmgt_month_list();
if(isset($_POST['report']) && $_POST['report'] == 'daily')
{
	if(date('m') >= $_POST['month_name']  )
	{
		$year=date('Y');
	}
	else
	{
		$year = date('Y')-1;
	}
	$year=date('Y');
	global $wpdb;
	
	$table_name = $wpdb->prefix."hmgt_appointment";
	//Month query return per day request
	$q="SELECT EXTRACT(DAY FROM appointment_date) as date,count(*) as count FROM ".$table_name." WHERE YEAR(appointment_date) =".$year." AND MONTH(appointment_date) =".$_POST['month_name']." group by date(appointment_date) ORDER BY appointment_date ASC";

	
	$result=$wpdb->get_results($q);

	$chart_array = array();
	$chart_array[] = array('date','Appointment Request');
	foreach($result as $r)
	{
		$chart_array[]=array( "$r->date",(int)$r->count);
	}


	$options = Array(
			'title' => __('Daily Appointment Request By Month','hospital_mgt'),
			'titleTextStyle' => Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
			'legend' =>Array('position' => 'right',
					'textStyle'=> Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans')),
				
			'hAxis' => Array(
					'title' =>  __('Day','hospital_mgt'),
					'titleTextStyle' => Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
					'textStyle' => Array('color' => '#222','fontSize' => 10),
					'maxAlternation' => 2


			),
			'vAxis' => Array(
					'title' =>  __('No of Appointment Request','hospital_mgt'),
				 'minValue' => 0,
					'maxValue' => 5,
				 'format' => '#',
					'titleTextStyle' => Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
					'textStyle' => Array('color' => '#222','fontSize' => 12)
			)
	);
}
if(isset($_POST['report']) && $_POST['report'] == 'monthly')
{
	$year =isset($_POST['year'])?$_POST['year']:date('Y');	
	global $wpdb;
	$table_name = $wpdb->prefix."hmgt_appointment";
	
	//return per month request
	$q="SELECT EXTRACT(MONTH FROM appointment_date) as date,count(*) as count FROM ".$table_name." WHERE YEAR(appointment_date) =".$year." group by month(appointment_date) ORDER BY appointment_date ASC";
	//$q="SELECT EXTRACT(DAY FROM date) as date,count(*) as count FROM ".$table_name." group by date(date)";
	//echo $q;
	$result=$wpdb->get_results($q);

	$chart_array = array();
	$chart_array[] = array('month','Appointment Request');
	foreach($result as $r)
	{
		$chart_array[]=array( "$r->date",(int)$r->count);
	}


	$options = Array(
			'title' => __('Appointment Request By Month','hospital_mgt'),
			'titleTextStyle' => Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
			'legend' =>Array('position' => 'right',
					'textStyle'=> Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans')),

			'hAxis' => Array(
					'title' =>  __('Month','warrantyreturn'),
					'titleTextStyle' => Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
					'textStyle' => Array('color' => '#222','fontSize' => 10),
					'maxAlternation' => 2


			),
			'vAxis' => Array(
					'title' =>  __('No of Appointment Request','hospital_mgt'),
				 'minValue' => 0,
					'maxValue' => 5,
				 'format' => '#',
					'titleTextStyle' => Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
					'textStyle' => Array('color' => '#222','fontSize' => 12)
			)
	);
}
require_once HMS_PLUGIN_DIR.'/lib/chart/GoogleCharts.class.php';
$GoogleCharts = new GoogleCharts;
if(isset($chart_array))
{
$chart = $GoogleCharts->load( 'column' , 'chart_div' )->get( $chart_array , $options );
}
?>
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
    	<a href="?page=appointment_report&tab=day" class="nav-tab <?php echo $active_tab == 'day' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Daily', 'hospital_mgt'); ?></a>    	
		<a href="?page=appointment_report&tab=monthly" class="nav-tab <?php echo $active_tab == 'monthly' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Monthly', 'hospital_mgt'); ?></a>    	
    </h2>
    <?php if($active_tab == 'day'){?>
    <form id="daily_report_form" method="post">  
    <input type="hidden" name="report" value="daily">
    <div class="form-group col-md-3">
    	<label for="month_name">Select Month<span class="require-field">*</span></label>
    
                    	<select class="form-control validate[required]" name="month_name" id="month_name">
                    	<?php $month_key = isset($_REQUEST['month_name'])?$_REQUEST['month_name']:0;?>
                	<option value=""><?php _e('Select Month','hospital_mgt');?></option>
                                       <?php 
                                       foreach($month as $key=>$name)
                                       		echo '<option value="'.$key.'" '.selected($month_key,$key).'>'.$name.'</option>';
                                       	?>
					                </select>
    </div>
   
     <div class="form-group col-md-3 button-possition">
    	<label for="subject_id">&nbsp;</label>
      	<input type="submit" class="btn btn-info" value="Go" name="report_1">
    </div>
    	
    	</form>
    	
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
<?php 
	}
	if($active_tab == 'monthly'){?>
	<form id="monthly_report_form" method="post">  
    <input type="hidden" name="report" value="monthly">
    <div class="form-group col-md-3">
    	<label for="year"><?php _e('Select Year','hospital_mgt')?><span class="require-field">*</span></label>
    	<?php 
    	$selected_year = isset($_REQUEST['year'])?$_REQUEST['year']:0;
		$year = isset($year)?$year:date('Y');
	?>
                    	<select class="form-control validate[required]" name="year" id="year">
                    	<option value=""><?php _e('Select Year','hospital_mgt')?></option>
                    	<?php for($i=1995;$i < 2025;$i++)
                    			{					
										echo '<option value="'.$i.'" '.selected($selected_year,$i).'> '.$i.' </option>';									
								}	
						?>
					                </select>
    </div>
   
     <div class="form-group col-md-3 button-possition">
    	<label for="subject_id">&nbsp;</label>
      	<input type="submit" class="btn btn-info" value="Go" name="report_1">
    </div>
    	
    	</form>
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
	<?php 
	}
?>
</div></div>
</div>

