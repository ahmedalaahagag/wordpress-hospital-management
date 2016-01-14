<?php 
//Manage bed
$obj_bed = new Hmgtbedmanage();


$active_tab = isset($_GET['tab'])?$_GET['tab']:'managebedlist';
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
	if(isset($_REQUEST['save_bed']))
	{
	
		if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
		{
	
			$result = $obj_bed->hmgt_add_bed($_POST);
			if($result)
			{
				if($_REQUEST['action'] == 'edit')
				{
					wp_redirect ( admin_url() . 'admin.php?page=hmgt_bedmanage&tab=managebedlist&message=2');
				}
				else
				{
					wp_redirect ( admin_url() . 'admin.php?page=hmgt_bedmanage&tab=managebedlist&message=1');
				}
					
					
			}
		}
	}
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
		$result = $obj_bed->delete_bed($_REQUEST['bed_id']);
		if($result)
		{
			wp_redirect ( admin_url() . 'admin.php?page=hmgt_bedmanage&tab=managebedlist&message=3');
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
    	<a href="?page=hmgt_bedmanage&tab=managebedlist" class="nav-tab <?php echo $active_tab == 'managebedlist' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Bed List', 'hospital_mgt'); ?></a>
    	<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{?>
        <a href="?page=hmgt_bedmanage&tab=addbed&&action=edit&bed_id=<?php echo $_REQUEST['bed_id'];?>" class="nav-tab <?php echo $active_tab == 'addbed' ? 'nav-tab-active' : ''; ?>">
		<?php _e('Edit Bed', 'hospital_mgt'); ?></a>  
		
		<?php 
		}
		else
		{?>
			<a href="?page=hmgt_bedmanage&tab=addbed" class="nav-tab <?php echo $active_tab == 'addbed' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span>'.__('Add New Bed', 'hospital_mgt'); ?></a>  
		
		 
		<?php  }?>
       
    </h2>
     <?php 
	//Report 1 
	if($active_tab == 'managebedlist')
	{ 
	
	?>	
    <script type="text/javascript">
$(document).ready(function() {
	jQuery('#bed_list').DataTable({
		"aoColumns":[
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},              	                 
	                  {"bSortable": false}]});
} );
</script>
    <form name="wcwm_report" action="" method="post">
    
        <div class="panel-body">
        	<div class="table-responsive">
        <table id="bed_list" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>
			<th><?php _e( 'Bed Number', 'hospital_mgt' ) ;?></th>
			 <th><?php _e( 'Bed Type', 'hospital_mgt' ) ;?></th>
			 <th><?php _e( 'Charges', 'hospital_mgt' ) ;?></th>
              <th><?php _e( 'Description', 'hospital_mgt' ) ;?></th>
			  <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </thead>
		<tfoot>
            <tr>
			<th><?php _e( 'Bed Number', 'hospital_mgt' ) ;?></th>
			 <th><?php _e( 'Bed Type', 'hospital_mgt' ) ;?></th>
			 <th><?php _e( 'Charges', 'hospital_mgt' ) ;?></th>
              <th><?php _e( 'Description', 'hospital_mgt' ) ;?></th>
			  <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </tfoot>
 
        <tbody>
         <?php 
		$bed_data=$obj_bed->get_all_bed();
		 if(!empty($bed_data))
		 {
		 	foreach ($bed_data as $retrieved_data){ 
		?>
            <tr>
				<td class="bed_number"><?php echo $retrieved_data->bed_number;?></td>
                <td class="bed_type"><?php echo $obj_bed->get_bedtype_name($retrieved_data->bed_type_id);?></td>
                <td class="charge">
				<?php echo $retrieved_data->bed_charges;?></td>
				<td class="descrition"><?php echo $retrieved_data->bed_description;?></td>
				<td class="action"> <a href="?page=hmgt_bedmanage&tab=addbed&action=edit&bed_id=<?php echo $retrieved_data->bed_id;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
                <a href="?page=hmgt_bedmanage&tab=managebedlist&action=delete&bed_id=<?php echo $retrieved_data->bed_id;?>" class="btn btn-danger" 
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
	
	if($active_tab == 'addbed')
	 {
		require_once HMS_PLUGIN_DIR. '/admin/includes/bed/add-bed.php';
	 }
	
	 ?>
</div>
			
		</div>
	</div>
</div>
<?php ?>