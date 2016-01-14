<?php 

$obj_bloodbank=new Hmgtbloodbank();

	

$active_tab = isset($_GET['tab'])?$_GET['tab']:'bloodbanklist';
	
	?>


<div class="page-inner" style="min-height:1631px !important">
<div class="page-title">
		<h3><img src="<?php echo get_option( 'hmgt_hospital_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'hmgt_hospital_name' );?></h3>
	</div>
	<?php 
	if(isset($_POST['save_blooddonor']))
	{
	
		if($_REQUEST['action']=='edit')
		{
				
			$result=$obj_bloodbank->hmgt_add_blood_donor($_POST);
			if($result)
			{	wp_redirect ( admin_url() . 'admin.php?page=hmgt_bloodbank&tab=blooddonorlist&message=2');
			}
				
				
		}
		else
		{
			$result=$obj_bloodbank->hmgt_add_blood_donor($_POST);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_bloodbank&tab=blooddonorlist&message=1');
			}
		}
	
	}
	if(isset($_POST['save_bloodgroup']))
	{
	
		if($_REQUEST['action']=='edit')
		{
				
			$result=$obj_bloodbank->add_blood_group($_POST);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_bloodbank&tab=bloodbanklist&message=2');
			}
				
				
		}
		else
		{
			$result=$obj_bloodbank->add_blood_group($_POST);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_bloodbank&tab=bloodbanklist&message=1');
			}
		}
	
	}
	
	
	
	
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
		if(isset($_REQUEST['blooddonor_id']))
		{
			$result=$obj_bloodbank->delete_blooddonor($_REQUEST['blooddonor_id']);
			wp_redirect( admin_url () . 'admin.php?page=hmgt_bloodbank&tab=blooddonorlist&message=success');
		}
		if(isset($_REQUEST['bloodgroup_id']))
		{
			$result=$obj_bloodbank->delete_bloodgroup($_REQUEST['bloodgroup_id']);
			wp_redirect( admin_url () . 'admin.php?page=hmgt_bloodbank&tab=bloodbanklist&message=success');
		}
			
	}
	if(isset($_REQUEST['message'])&& $_REQUEST['message']=='success' ){
		wp_redirect ( admin_url() . 'admin.php?page=hmgt_bloodbank&tab=bloodbanklist&message=3');
	
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
    	<a href="?page=hmgt_bloodbank&tab=bloodbanklist" class="nav-tab <?php echo $active_tab == 'bloodbanklist' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Blood Manage', 'hospital_mgt'); ?></a>
    	<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['bloodgroup_id']))
		{?>
			<a href="?page=hmgt_bloodbank&tab=addbloodgroop&action=edit&bloodgroup_id=<?php if(isset($_REQUEST['bloodgroup_id'])) echo $_REQUEST['bloodgroup_id'];?>" class="nav-tab <?php echo $active_tab == 'addbloodgroop' ? 'nav-tab-active' : ''; ?>">
		<?php echo __('Edit Blood Group', 'hospital_mgt'); ?></a>
		<?php }
		else
		{?>
		<a href="?page=hmgt_bloodbank&tab=addbloodgroop" class="nav-tab <?php echo $active_tab == 'addbloodgroop' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span>'.__('Add Blood Group', 'hospital_mgt'); ?></a>
		<?php }?>
        <a href="?page=hmgt_bloodbank&tab=blooddonorlist" class="nav-tab <?php echo $active_tab == 'blooddonorlist' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Blood Donor List', 'hospital_mgt'); ?></a> 
		<?php 
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['blooddonor_id']))
		{	?>
        <a href="?page=hmgt_bloodbank&tab=addblooddonor&action=edit&blooddonor_id=<?php if(isset($_REQUEST['blooddonor_id'])) echo $_REQUEST['blooddonor_id'];?>" class="nav-tab <?php echo $active_tab == 'addblooddonor' ? 'nav-tab-active' : ''; ?>">
		<?php _e('Edit Blood Donor', 'hospital_mgt'); ?></a>  
		<?php 
		
		}
		else
		{?>
			<a href="?page=hmgt_bloodbank&tab=addblooddonor" class="nav-tab <?php echo $active_tab == 'addblooddonor' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span>'.__('Add Blood Donor', 'hospital_mgt'); ?></a>  
		<?php  }?>
		
       
    </h2>
     <?php 
	//Report 1 
	if($active_tab == 'bloodbanklist')
	{ ?>	
	    	<script type="text/javascript">
$(document).ready(function() {
	jQuery('#bloodbag').DataTable({
		
		 "aoColumns":[
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
        <table id="bloodbag" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>
			<th><?php _e( 'Blood Group', 'hospital_mgt' ) ;?></th>
			   <th><?php _e( 'No of Bags', 'hospital_mgt' ) ;?></th> 
				<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </thead>
		<tfoot>
            <tr>
			<th><?php _e( 'Blood Group', 'hospital_mgt' ) ;?></th>
			   <th><?php _e( 'No of Bags', 'hospital_mgt' ) ;?></th> 
				<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </tfoot>
		<tbody>
         <?php foreach($obj_bloodbank->get_all_bloodgroups() as $retrieved_data){  ?>
            <tr>
				<td class="blood_group">
				<?php 
						echo $retrieved_data->blood_group;
				?></td>
				<td class="subject_name"><?php  echo $retrieved_data->blood_status;;?></td>
              
               	<td class="action"> <a href="?page=hmgt_bloodbank&tab=addbloodgroop&&action=edit&bloodgroup_id=<?php echo $retrieved_data->blood_id;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
                <a href="?page=hmgt_bloodbank&tab=bloodbanklist&action=delete&bloodgroup_id=<?php  echo $retrieved_data->blood_id;?>" class="btn btn-danger" 
                onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
                <?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>
                
                </td>
               
            </tr>
            <?php } ?>
     
        </tbody>
        
        </table>
        </div>
        </div>
       
</form>
     <?php 
	 }
	if($active_tab == 'addbloodgroop')
	 {
	require_once HMS_PLUGIN_DIR. '/admin/includes/blood-bank/add-blood-group.php';
	 }
	
	if($active_tab == 'addblooddonor')
	 {
	require_once HMS_PLUGIN_DIR. '/admin/includes/blood-bank/add-blood-donor.php';
	 }
	 if($active_tab == 'blooddonorlist')
	 {
	require_once HMS_PLUGIN_DIR. '/admin/includes/blood-bank/blood-donor-list.php';
	 }
	 ?>
</div>
			
		</div>
	</div>
</div>


<?php //} ?>