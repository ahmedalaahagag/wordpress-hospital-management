<?php 
$obj_dignosis = new Hmgt_dignosis();


$active_tab = isset($_GET['tab'])?$_GET['tab']:'diagnosislist';
	
	?>
	
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
    <div class="modal-content">
    <div class="category_list">
     </div>
     
    </div>
    </div> 
    
</div>
<!-- End POP-UP Code -->

<div class="page-inner" style="min-height:1631px !important">
<div class="page-title">
		<h3><img src="<?php echo get_option( 'hmgt_hospital_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'hmgt_hospital_name' );?></h3>
	</div>
	<?php 
	if(isset($_REQUEST['save_diagnosis']))
	{
	
		if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
		{
	
			$result = $obj_dignosis->hmgt_add_dignosis($_POST);
			if($result)
			{
				if($_REQUEST['action'] == 'edit')
				{
					wp_redirect ( admin_url() . 'admin.php?page=hmgt_diagnosis&tab=diagnosislist&message=2');
				}
				else
				{
					wp_redirect ( admin_url() . 'admin.php?page=hmgt_diagnosis&tab=diagnosislist&message=1');
				}
			}
		}
	}
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
		$result = $obj_dignosis->delete_dignosis($_REQUEST['diagnosis_id']);
		if($result)
		{
			wp_redirect ( admin_url() . 'admin.php?page=hmgt_diagnosis&tab=diagnosislist&message=3');
		}
	}
	if(isset($_REQUEST['message']))
	{
		$message =$_REQUEST['message'];
		if($message == 1)
		{?>
				<div id="message" class="updated below-h2 ">
				<p>
				<?php 
					_e('Record inserted successfully','hospital_mgt');
				?></p></div>
				<?php 
			
		}
		elseif($message == 2)
		{?><div id="message" class="updated below-h2 "><p><?php
					_e("Record updated successfully",'hospital_mgt');
					?></p>
					</div>
				<?php 
			
		}
		elseif($message == 3) 
		{?>
		<div id="message" class="updated below-h2"><p>
		<?php 
			_e('Record deleted successfully','hospital_mgt');
		?></div></p><?php
				
		}
	}
		
	?>
	<div id="main-wrapper">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-white">
					<div class="panel-body">
	<h2 class="nav-tab-wrapper">
    	<a href="?page=hmgt_diagnosis&tab=diagnosislist" class="nav-tab <?php echo $active_tab == 'diagnosislist' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Diagnosis Report List', 'hospital_mgt'); ?></a>
    	
        <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{?>
        <a href="?page=hmgt_diagnosis&tab=adddiagnosis&&action=edit&diagnosis_id=<?php echo $_REQUEST['diagnosis_id'];?>" class="nav-tab <?php echo $active_tab == 'adddiagnosis' ? 'nav-tab-active' : ''; ?>">
		<?php _e('Edit Diagnosis Report', 'hospital_mgt'); ?></a>  
		<?php 
		}
		else
		{?>
			<a href="?page=hmgt_diagnosis&tab=adddiagnosis" class="nav-tab <?php echo $active_tab == 'adddiagnosis' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span>'.__('Add Diagnosis Report', 'hospital_mgt'); ?></a>  
		<?php  }?>
       
    </h2>
     <?php 
	//Report 1 
	if($active_tab == 'diagnosislist')
	{ 
	
	?>	
    <script type="text/javascript">
$(document).ready(function() {
	jQuery('#diagnosis').DataTable({
		 "order": [[ 0, "Desc" ]],
		 "aoColumns":[
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bVisible": true},	                 
	                  {"bSortable": false}
	               ]
		});
} );
</script>
    <form name="wcwm_report" action="" method="post">
		<div class="panel-body">
        	<div class="table-responsive">
        <table id="diagnosis" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>
			<th><?php  _e( 'Date', 'hospital_mgt' ) ;?></th>
			 <th> <?php _e( 'Patient ID-Name', 'hospital_mgt' ) ;?></th>
              <th> <?php _e( 'Report Type', 'hospital_mgt' ) ;?></th>
				<th width="300px"> <?php _e( 'Description', 'hospital_mgt' ) ;?></th>
				<th> <?php _e( 'Report', 'hospital_mgt' ) ;?></th>
				<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </thead>
		<tfoot>
            <tr>
			<th><?php  _e( 'Date', 'hospital_mgt' ) ;?></th>
			<th> <?php _e( 'Patient ID-Name', 'hospital_mgt' ) ;?></th>
              <th> <?php _e( 'Report Type', 'hospital_mgt' ) ;?></th>
				<th width="300px">  <?php _e( 'Description', 'hospital_mgt' ) ;?></th>
				<th> <?php _e( 'Report', 'hospital_mgt' ) ;?></th>
				<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </tfoot>
 
        <tbody>
         <?php 
		$dignosis_data=$obj_dignosis->get_all_dignosis_report();
		 if(!empty($dignosis_data))
		 {
		 	foreach ($dignosis_data as $retrieved_data){ 
			
			
		 ?>
            <tr>
				<td class="date"><?php echo $retrieved_data->diagnosis_date;?></td>
				<td class="patient_id">
				<?php 
					$patient = get_user_detail_byid( $retrieved_data->patient_id);
					echo $patient['id']." - ".$patient['first_name']." ".$patient['last_name'];
				
				?></td>
                <td class="report_type"> <?php echo $obj_dignosis->get_report_type_name($retrieved_data->report_type);?></td>
                <td class="description"><?php echo $retrieved_data->diagno_description;?></td>		
                <td class="report">
                <?php 
					if(trim($retrieved_data->attach_report) != "")
                		echo '<a href="'.content_url().'/uploads/hospital_assets/'.$retrieved_data->attach_report.'" class="btn btn-default"><i class="fa fa-download"></i> View</a>';
					else 
						echo "No any Report";
                ?>
                </td>				
               	<td class="action"> 
               	<a href="?page=hmgt_diagnosis&tab=adddiagnosis&action=edit&diagnosis_id=<?php echo $retrieved_data->diagnosis_id;?>" 
               	class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
                <a href="?page=hmgt_diagnosis&tab=diagnosislist&action=delete&diagnosis_id=<?php echo $retrieved_data->diagnosis_id;?>" 
                class="btn btn-danger" 
                onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
                <?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>
               
                </td>
               
            </tr>
            <?php } 
			
		}?>
     
        </tbody>
        
        </table>
        </div>
        </div>
       
</form>
     <?php 
	 }
	
	if($active_tab == 'adddiagnosis')
	 {
	require_once HMS_PLUGIN_DIR. '/admin/includes/diagnosis/add_diagnosis.php';
	 }
	 ?>
</div>
			
	</div>
	</div>
</div>


<?php //} ?>