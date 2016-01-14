<?php 
$role='receptionist';
	$user_object=new Hmgtuser();

	
$active_tab = isset($_GET['tab'])?$_GET['tab']:'receptionistlist';
?>
<!-- POP up code -->
<div class="popup-bg" style="min-height:1631px !important">
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
	if(isset($_POST['save_receptionist']))
	{
	
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
	
			$result=$user_object->hmgt_add_user($_POST);
	
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_receptionist&tab=receptionistlist&message=2');
					
			}
	
	
		}
		else
		{
			if( !email_exists( $_POST['email'] ) && !username_exists( $_POST['username'] )) {
	
				$result=$user_object->hmgt_add_user($_POST);
					
				if($result)
				{
					wp_redirect ( admin_url() . 'admin.php?page=hmgt_receptionist&tab=receptionistlist&message=1');
				}
			}
			else
			{?>
						<div id="message" class="updated below-h2">
						<p><p><?php _e('Username Or Emailid All Ready Exist.','hospital_mgt');?></p></p>
						</div>
						
	  <?php }
		}
			
		
	}
	
		
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
			{
				
				$result=$user_object->delete_usedata($_REQUEST['receptionist_id']);
				if($result)
				{
					wp_redirect ( admin_url() . 'admin.php?page=hmgt_receptionist&tab=receptionistlist&message=3');
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
    	<a href="?page=hmgt_receptionist&tab=receptionistlist" class="nav-tab <?php echo $active_tab == 'receptionistlist' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Support Staff List', 'hospital_mgt'); ?></a>
    	
        <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{?>
        <a href="?page=hmgt_receptionist&tab=addreceptionist&action=edit&receptionist_id=<?php echo $_REQUEST['receptionist_id'];?>" class="nav-tab <?php echo $active_tab == 'addreceptionist' ? 'nav-tab-active' : ''; ?>">
		<?php _e('Edit Support Staff', 'hospital_mgt'); ?></a>  
		<?php 
		}
		else
		{?>
			<a href="?page=hmgt_receptionist&tab=addreceptionist" class="nav-tab <?php echo $active_tab == 'addreceptionist' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span>'.__('Add New Support Staff', 'hospital_mgt'); ?></a>  
		<?php  }?>
       
    </h2>
     <?php 
	//Report 1 
	if($active_tab == 'receptionistlist')
	{ 
	
	?>	
    <script type="text/javascript">
$(document).ready(function() {
	jQuery('#staff_list').DataTable({
		 "order": [[ 1, "asc" ]],
		 "aoColumns":[
	                  {"bSortable": false},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},	                 
	                  {"bSortable": false}
	               ]
		 
		});
	
} );
</script>
    <form name="reception" action="" method="post">
    
        <div class="panel-body">
        	<div class="table-responsive">
        <table id="staff_list" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>
			<th style="width: 50px;height:50px;"><?php  _e( 'Photo', 'hospital_mgt' ) ;?></th>
              <th><?php _e( 'Name', 'hospital_mgt' ) ;?></th>
			   <th><?php _e( 'Department', 'hospital_mgt' ) ;?></th>
			  <th> <?php _e( 'Mobile No.', 'hospital_mgt' ) ;?></th>
				<th> <?php _e( 'Email', 'hospital_mgt' ) ;?></th>
                <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </thead>
 
        <tfoot>
            <tr>
			<th><?php  _e( 'Photo', 'hospital_mgt' ) ;?></th>
              <th><?php _e( 'Name', 'hospital_mgt' ) ;?></th>
			   <th><?php _e( 'Department', 'hospital_mgt' ) ;?></th>
			  <th> <?php _e( 'Mobile No.', 'hospital_mgt' ) ;?></th>
				<th> <?php _e( 'Email', 'hospital_mgt' ) ;?></th>
                <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </tfoot>
 
        <tbody>
         <?php 
		//$nursedata=get_usersdata('nurse');
		 $get_receptionist = array('role' => 'receptionist');
			$receptionistdata=get_users($get_receptionist);
		 if(!empty($receptionistdata))
		 {
		 	foreach ($receptionistdata as $retrieved_data){
		 ?>
            <tr>
				<td class="user_image"><?php $uid=$retrieved_data->ID;
							$userimage=get_user_meta($uid, 'hmgt_user_avatar', true);
						if(empty($userimage))
						{
										echo '<img src='.get_option( 'hmgt_support_thumb' ).' height="50px" width="50px" class="img-circle" />';
						}
						else
							echo '<img src='.$userimage.' height="50px" width="50px" class="img-circle"/>';
				?></td>
                <td class="name"><a href="?page=hmgt_receptionist&tab=addreceptionist&action=edit&receptionist_id=<?php echo $retrieved_data->ID;?>"><?php echo $retrieved_data->display_name;?></a></td>
                <td class="department"><?php 
				$postdata=get_post($retrieved_data->department);
				echo $postdata->post_title;?></td>
				<td class="phone">
				<?php 
					echo get_user_meta($uid, 'mobile', true);
				?></td>
				
                <td class="email"><?php echo $retrieved_data->user_email;?></td>
               	<td class="action"> <a href="?page=hmgt_receptionist&tab=addreceptionist&action=edit&receptionist_id=<?php echo $retrieved_data->ID;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
                <a href="?page=hmgt_receptionist&tab=receptionistlist&action=delete&receptionist_id=<?php echo $retrieved_data->ID;?>" class="btn btn-danger" 
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
	
	if($active_tab == 'addreceptionist')
	 {
	require_once HMS_PLUGIN_DIR. '/admin/includes/receptionist/add_receptionist.php';
	 }
	 ?>
</div>
			
		</div>
	</div>
</div>


<?php //} ?>