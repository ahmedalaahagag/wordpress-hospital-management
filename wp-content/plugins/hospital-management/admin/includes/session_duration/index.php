<?php 
//session
$obj_session_duration = new Hmgt_session_duration();
$obj_session = new Hmgt_session();
$active_tab = isset($_GET['tab'])?$_GET['tab']:'sessiondurationlist';
	
?>

<div class="page-inner" style="min-height:1631px !important">
<div class="page-title">
		<h3><img src="<?php echo get_option( 'hmgt_hospital_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'hmgt_hospital_name' );?></h3>
	</div>`
	<?php 
	if(isset($_REQUEST['save_durations']))
	{
	
		if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
		{
	
			$result = $obj_session_duration->hmgt_add_session_duration($_POST);
			if($result)
			{
				if($_REQUEST['action'] == 'edit')
				{
					wp_redirect ( admin_url().'admin.php?page=hmgt_sessions_durations&tab=sessiondurationlist&message=2');
				}
				else
				{
					wp_redirect ( admin_url().'admin.php?page=hmgt_sessions_durations&tab=sessiondurationlist&message=1');
				}
					
					
			}
		}
	}
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
		$result = $obj_session_duration->delete_duration($_REQUEST['duration_id']);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=hmgt_sessions_durations&tab=sessiondurationlist&message=3');
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
    	<a href="?page=hmgt_sessions_durations&tab=sessiondurationlist" class="nav-tab <?php echo $active_tab == 'sessiondurationlist' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Durations List', 'hospital_mgt'); ?></a>
    	
        <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{?>
        <a href="?page=hmgt_sessions_durations&tab=addduration&&action=edit&session_id=<?php echo $_REQUEST['session_id'];?>" class="nav-tab <?php echo $active_tab == 'addduration' ? 'nav-tab-active' : ''; ?>">
		<?php _e('Edit duration', 'hospital_mgt'); ?></a>
		<?php 
		}
		else
		{?>
			<a href="?page=hmgt_sessions_durations&tab=addduration" class="nav-tab <?php echo $active_tab == 'addmedcategory' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add New Durations', 'hospital_mgt'); ?></a>
		<?php  }?>
       
    </h2>
     <?php 
	//Report 1 
	if($active_tab == 'sessiondurationlist')
	{ 
	
	?>	
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#duration_list').DataTable({
		"aoColumns":[
	                  {"bSortable": true},
	                  {"bSortable": true}
	                ]
		});
} );
</script>
    <form name="session" action="" method="post">
    
        <div class="panel-body">
        	<div class="table-responsive">
        <table id="session_list" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>
			<th><?php _e( 'Duration Time', 'hospital_mgt' ) ;?></th>
			<th><?php _e( 'Duration Price', 'hospital_mgt' ) ;?></th>
			<th><?php _e( 'Session', 'hospital_mgt' ) ;?></th>
            <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </thead>
 
        <tfoot>
            <tr>
            <th><?php _e( 'Duration Time', 'hospital_mgt' ) ;?></th>
            <th><?php _e( 'Duration Price', 'hospital_mgt' ) ;?></th>
			<th><?php _e( 'Session', 'hospital_mgt' ) ;?></th>
            <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </tfoot>
 
        <tbody>
         <?php 
		$durations_data=$obj_session_duration->get_all_session_duration();
		 if(!empty($durations_data))
		 {
		 	foreach ($durations_data as $duration_data){

		 ?>
            <tr>
            	<td class="duration_time"><?php echo $duration_data->duration_time;?></td>
            	<td class="duration_time"><?php echo $duration_data->duration_price;?></td>
				<td class="session_name"><?php echo $duration_data->session_name;?></td>
               	<td class="action">
               	<a href="?page=hmgt_sessions_durations&tab=addduration&action=edit&session_id=<?php echo $duration_data->session_id;?>" class="btn btn-info">
               	<?php _e('Edit', 'hospital_mgt' ) ;?></a>
                <a href="?page=hmgt_sessions_durations&tab=sessiondurationlist&action=delete&duration_id=<?php echo $duration_data->duration_id;?>" class="btn btn-danger"
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
	
	if($active_tab == 'addduration')
	 {
	require_once HMS_PLUGIN_DIR. '/admin/includes/session_duration/add_session_duration.php';
	 }
	 ?>
</div>
			
		</div>
	</div>
</div>
<?php //} ?>