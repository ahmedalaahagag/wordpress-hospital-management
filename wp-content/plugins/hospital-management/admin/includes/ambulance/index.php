<?php 
// Ambulance
$obj_ambulance = new Hmgt_ambulance();

$active_tab = isset($_GET['tab'])?$_GET['tab']:'ambulance_req_list';
	
	?>


<div class="page-inner" style="min-height:1631px !important">
<div class="page-title">
		<h3><img src="<?php echo get_option( 'hmgt_hospital_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'hmgt_hospital_name' );?></h3>
	</div>
	<?php 
	if(isset($_REQUEST['save_ambulance']))
	{
	
		if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
		{
	
			$result = $obj_ambulance->hmgt_add_ambulance($_POST);
			if($result)
			{
				if($_REQUEST['action'] == 'edit')
				{
					wp_redirect ( admin_url() . 'admin.php?page=hmgt_ambulance&tab=ambulance_list&message=2');
				}
				else
				{
					wp_redirect ( admin_url() . 'admin.php?page=hmgt_ambulance&tab=ambulance_list&message=1');
				}
					
					
			}
		}
	}
	if(isset($_REQUEST['save_ambulance_request']))
	{
	
		if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
		{
	
			$result = $obj_ambulance->hmgt_add_ambulance_request($_POST);
			if($result)
			{
				if($_REQUEST['action'] == 'edit')
				{
					wp_redirect ( admin_url() . 'admin.php?page=hmgt_ambulance&tab=ambulance_req_list&message=2');
				}
				else
				{
					wp_redirect ( admin_url() . 'admin.php?page=hmgt_ambulance&tab=ambulance_req_list&message=1');
				}
					
					
			}
		}
	}
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
		if($_GET['tab'] == 'ambulance_req_list')
		{
			$result = $obj_ambulance->delete_ambulance_req($_REQUEST['amb_req_id']);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_ambulance&tab=ambulance_req_list&message=3');
			}
		}
		else
		{
			$result = $obj_ambulance->delete_ambulance($_REQUEST['amb_id']);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_ambulance&tab=ambulance_list&message=3');
			}
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
					_e("Record updated successfully.",'hospital_mgt');
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
    	<a href="?page=hmgt_ambulance&tab=ambulance_req_list" class="nav-tab <?php echo $active_tab == 'ambulance_req_list' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Ambulance Requested List', 'hospital_mgt'); ?>
		</a>
		 <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && $active_tab == 'add_ambulance_req')
		{?>
        <a href="?page=hmgt_ambulance&tab=add_ambulance_req&&action=edit&amb_req_id=<?php echo $_REQUEST['amb_req_id'];?>" class="nav-tab <?php echo $active_tab == 'add_ambulance_req' ? 'nav-tab-active' : ''; ?>">
		<?php _e('Edit Ambulance Request', 'hospital_mgt'); ?></a>  
		<?php 
		}
		else
		{?>
			<a href="?page=hmgt_ambulance&tab=add_ambulance_req" class="nav-tab <?php echo $active_tab == 'add_ambulance_req' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__(' Add Ambulance Request', 'hospital_mgt'); ?></a>  
		<?php  }?>
		 <a href="?page=hmgt_ambulance&tab=ambulance_list" class="nav-tab <?php echo $active_tab == 'ambulance_list' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span> '.__(' Ambulance List', 'hospital_mgt'); ?>
		</a>
		 <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && $active_tab == 'add_ambulance')
		{?>
        <a href="?page=hmgt_ambulance&tab=add_ambulance&&action=edit&amb_id=<?php echo $_REQUEST['amb_id'];?>" class="nav-tab <?php echo $active_tab == 'add_ambulance' ? 'nav-tab-active' : ''; ?>">
		<?php _e('Edit Ambulance', 'hospital_mgt'); ?></a>  
		<?php 
		}
		else
		{?>
		<a href="?page=hmgt_ambulance&tab=add_ambulance" class="nav-tab <?php echo $active_tab == 'add_ambulance' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__(' Add New Ambulance', 'hospital_mgt'); ?></a> 
       <?php }?>
	  
    </h2>
     <?php 
	//Report 1 
	if($active_tab == 'ambulance_req_list')
	{ 
	
	?>	
    	<script type="text/javascript">
$(document).ready(function() {
	jQuery('#ambulance_request').DataTable({
		 "order": [[ 2, "Desc" ]],
		 "aoColumns":[
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                         
	                  {"bSortable": false}
	               ]
		});
		
	
} );
</script>
    <form name="wcwm_report" action="" method="post">
    
        <div class="panel-body">
        	<div class="table-responsive">
        <table id="ambulance_request" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>
			<th><?php _e( 'Ambulance', 'hospital_mgt' ) ;?></th>
			   <th><?php _e( 'Patient', 'hospital_mgt' ) ;?></th>
				<th><?php _e( 'Date', 'hospital_mgt' ) ;?></th>	
				<th><?php _e( 'Time', 'hospital_mgt' ) ;?></th>
				<th><?php _e( 'Dispatch Time', 'hospital_mgt' ) ;?></th>
				
				<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </thead>
		<tfoot>
            <tr>
				<th><?php _e( 'Ambulance', 'hospital_mgt' ) ;?></th>
			    <th><?php _e( 'Patient', 'hospital_mgt' ) ;?></th>
				<th><?php _e( 'Date', 'hospital_mgt' ) ;?></th>	
				<th><?php _e( 'Time', 'hospital_mgt' ) ;?></th>
				<th><?php _e( 'Dispatch Time', 'hospital_mgt' ) ;?></th>
				
				<th><?php _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </tfoot>
 
        <tbody>
         <?php 
		$ambulancereq_data=$obj_ambulance->get_all_ambulance_request();
		 if(!empty($ambulancereq_data))
		 {
		 	foreach ($ambulancereq_data as $retrieved_data){ 
		 		$patient_data =	get_user_detail_byid($retrieved_data->patient_id);
			
		 ?>
            <tr>
				<td class="ambulanceid"><?php echo $obj_ambulance->get_ambulance_id($retrieved_data->ambulance_id);?></td>
                <td class="patient"><?php echo $patient_data['first_name']." ".$patient_data['last_name']."(".$patient_data['patient_id'].")";?></td>
                <td class="date"><?php echo $retrieved_data->request_date;?></td>
				<td class="time"><?php echo $retrieved_data->request_time;?></td>
                <td class="dispatchtime"><?php echo $retrieved_data->dispatch_time;?></td>
               	<td class="action"> 
               	<a href="?page=hmgt_ambulance&tab=add_ambulance_req&action=edit&amb_req_id=<?php echo $retrieved_data->amb_req_id;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
                <a href="?page=hmgt_ambulance&tab=ambulance_req_list&action=delete&amb_req_id=<?php echo $retrieved_data->amb_req_id;?>" class="btn btn-danger" 
                onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
                <?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>              
               
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
	 if($active_tab == 'ambulance_list')
	 {
	 ?>
	 <script type="text/javascript">
$(document).ready(function() {
	jQuery('#ambulance_list').DataTable({
		 "order": [[ 1, "asc" ]],
		 "aoColumns":[
	                  {"bSortable": false},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},                         
	                  {"bSortable": false}
	               ]
		});
		
	
} );
</script>
	 <div class="panel-body">
        	<div class="table-responsive">
        <table id="ambulance_list" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>
            	<th style="width: 50px;height:50px;"><?php _e( 'Image', 'hospital_mgt' ) ;?></th>
				<th><?php _e( 'Ambulance ID', 'hospital_mgt' ) ;?></th>
			   	<th><?php _e( 'Reg NO', 'hospital_mgt' ) ;?></th>
				<th><?php _e( 'Driver Name', 'hospital_mgt' ) ;?></th>	
							
				<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </thead>
		<tfoot>
            <tr>
				<th><?php _e( 'Image', 'hospital_mgt' ) ;?></th>
				<th><?php _e( 'Ambulance ID', 'hospital_mgt' ) ;?></th>
			   	<th><?php _e( 'Reg NO', 'hospital_mgt' ) ;?></th>
				<th><?php _e( 'Driver Name', 'hospital_mgt' ) ;?></th>	
				
				<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </tfoot>
 
        <tbody>
         <?php 
		$ambulance_data=$obj_ambulance->get_all_ambulance();
		 if(!empty($ambulance_data))
		 {
		 	foreach ($ambulance_data as $retrieved_data){ 
			
			
		 ?>
            <tr>
				<td class="driver_image">
					<?php //echo get_option( 'hmgt_driver_thumb' )."image";
						if(trim($retrieved_data->driver_image) == "")
							echo '<img src='.get_option( 'hmgt_driver_thumb' ).' height="50px" width="50px" class="img-circle"/>';
						else
							echo '<img src='.$retrieved_data->driver_image.' height="50px" width="50px" class="img-circle"/>';
					?>
				</td>
                <td class="amb_id"><?php echo $retrieved_data->ambulance_id;?></td>
                <td class="regno"><?php echo $retrieved_data->registerd_no;?></td>
				<td class="driver_name"><?php echo $retrieved_data->driver_name;?></td>
               
               	<td class="action"> 
               	<a href="?page=hmgt_ambulance&tab=add_ambulance&action=edit&amb_id=<?php echo $retrieved_data->amb_id;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
                <a href="?page=hmgt_ambulance&tab=ambulance_list&action=delete&amb_id=<?php echo $retrieved_data->amb_id;?>" class="btn btn-danger" 
                onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
                <?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>              
               
            </tr>
            <?php } 
			
		}?>
     
        </tbody>
        
        </table>
        </div>
        </div>
	 <?php 
	 }
	 
	if($active_tab == 'add_ambulance_req')
	 {
	require_once HMS_PLUGIN_DIR. '/admin/includes/ambulance/add-ambulance-req.php';
	 }
	 if($active_tab == 'add_ambulance')
	 {
	require_once HMS_PLUGIN_DIR. '/admin/includes/ambulance/add-new-ambulance.php';
	 }
	 ?>
</div>
			
		</div>
	</div>
</div>


<?php //} ?>