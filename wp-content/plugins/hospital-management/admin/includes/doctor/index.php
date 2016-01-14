<?php 
$role='doctor';
$user_object=new Hmgtuser();
?>
	<?php
$active_tab = isset($_GET['tab'])?$_GET['tab']:'doctorlist';
	
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
	if(isset($_POST['save_doctor']))
	{
		if(isset($_FILES['doctor_cv']) && !empty($_FILES['doctor_cv']) && $_FILES['doctor_cv']['size'] !=0)
		{
			if($_FILES['doctor_cv']['size'] > 0)
				$cv=load_documets($_FILES['doctor_cv'],'doctor_cv','CV');
	
		}
		else
		{
			if(isset($_REQUEST['hidden_cv']))
				$cv=$_REQUEST['hidden_cv'];
		}
			
		if(isset($_FILES['education_certificate']) && !empty($_FILES['education_certificate']) && $_FILES['education_certificate']['size'] !=0)
		{
			if($_FILES['education_certificate']['size'] > 0)
				$education_cert=load_documets($_FILES['education_certificate'],'education_certificate','Edu');
		}
		else{
			if(isset($_REQUEST['hidden_education_certificate']))
				$education_cert=$_REQUEST['hidden_education_certificate'];
		}
			
		if(isset($_FILES['experience_cert']) && !empty($_FILES['experience_cert']) && $_FILES['experience_cert']['size'] !=0)
		{
			if($_FILES['experience_cert']['size'] > 0)
				$experience_cert=load_documets($_FILES['experience_cert'],'experience_cert','Exp');
		}
		else
		{
			if(isset($_REQUEST['hidden_exp_certificate']))
				$experience_cert=$_REQUEST['hidden_exp_certificate'];
		}
			
	
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='insert')
		{
	
			if( !email_exists( $_POST['email'] ) && !username_exists( $_POST['username'] )) {
					
					
				$result=$user_object->hmgt_add_user($_POST);
				$user_object->upload_documents($cv,$education_cert,$experience_cert,$result);
				if($result)
				{
					wp_redirect ( admin_url() . 'admin.php?page=hmgt_doctor&tab=doctorlist&message=1');
				}
			}
			else
			{
				?>
								<div id="message" class="updated below-h2">
								<p><p><?php _e('Username Or Emailid All Ready Exist','hospital_mgt');?></p></p>
										</div>
			 <?php  } 
				}
				else
				{
					
					$result=$user_object->hmgt_add_user($_POST);
					$user_object->update_upload_documents($cv,$education_cert,$experience_cert,$result);
						if($result)
						{
								wp_redirect ( admin_url().'admin.php?page=hmgt_doctor&tab=doctorlist&message=2');
							
					}
					
				}
				
			
			
		}
		
		
	
	
		
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
			{
				
				$result=$user_object->delete_usedata($_REQUEST['doctor_id']);
				if($result)
				{
						wp_redirect ( admin_url() . 'admin.php?page=hmgt_doctor&tab=doctorlist&message=3');
					
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
    	<a href="?page=hmgt_doctor&tab=doctorlist" class="nav-tab <?php echo $active_tab == 'doctorlist' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Doctor List', 'hospital_mgt'); ?></a>
    	
        <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{?>
        <a href="?page=hmgt_doctor&tab=adddoctor&&action=edit&doctor_id=<?php echo $_REQUEST['doctor_id'];?>" class="nav-tab <?php echo $active_tab == 'adddoctor' ? 'nav-tab-active' : ''; ?>">
		<?php _e('Edit Doctor', 'hospital_mgt'); ?></a>  
		<?php 
		}
		else
		{?>
			<a href="?page=hmgt_doctor&tab=adddoctor" class="nav-tab <?php echo $active_tab == 'adddoctor' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span>'.__('Add New Doctor', 'hospital_mgt'); ?></a>  
		<?php  }?>
       
    </h2>
     <?php 
	//Report 1 
	if($active_tab == 'doctorlist')
	{ 
	
	?>	
    <script type="text/javascript">
$(document).ready(function() {
	jQuery('#doctor_list').DataTable({
		 "order": [[ 1, "asc" ]],
		 "aoColumns":[
	                  {"bSortable": false},
	                  {"bSortable": true},
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
        <table id="doctor_list" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>
			<th><?php  _e( 'Photo', 'hospital_mgt' ) ;?></th>
              <th><?php _e( 'Doctor Name', 'hospital_mgt' ) ;?></th>
			    <th><?php _e( 'Department', 'hospital_mgt' ) ;?></th>
			  <th> <?php _e( 'Specialization', 'hospital_mgt' ) ;?></th>
			  <th> <?php _e( 'Degree', 'hospital_mgt' ) ;?></th>
                <th> <?php _e( 'Doctor Email', 'hospital_mgt' ) ;?></th>
                <th> <?php _e( 'Mobile No', 'hospital_mgt' ) ;?></th>
                <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </thead>
		<tfoot>
            <tr>
			 <th><?php  _e( 'Photo', 'hospital_mgt' ) ;?></th>
              <th><?php _e( 'Doctor Name', 'hospital_mgt' ) ;?></th>
			   <th><?php _e( 'Department', 'hospital_mgt' ) ;?></th>
			   <th><?php _e( 'Specialization', 'hospital_mgt' ) ;?></th>
			  <th><?php _e( 'Degree', 'hospital_mgt' ) ;?></th>
                <th><?php _e( 'Doctor Email', 'hospital_mgt' ) ;?></th>
                <th> <?php _e( 'Mobile No', 'hospital_mgt' ) ;?></th>
                <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </tfoot>
 
        <tbody>
         <?php 
		 $get_doctor = array('role' => 'doctor');
			$doctordata=get_users($get_doctor);
		 if(!empty($doctordata))
		 {
		 	foreach ($doctordata as $retrieved_data){
		?>
            <tr>
				<td class="user_image"><?php $uid=$retrieved_data->ID;
							 
					$userimage=get_user_meta($uid, 'hmgt_user_avatar', true);
							if(empty($userimage))
							{
								echo '<img src='.get_option( 'hmgt_doctor_thumb' ).' height="50px" width="50px" class="img-circle" />';
							}
							else
							echo '<img src='.$userimage.' height="50px" width="50px" class="img-circle"/>';
				?></td>
                <td class="name"><a href="?page=hmgt_doctor&tab=adddoctor&action=edit&doctor_id=<?php echo $retrieved_data->ID;?>"><?php echo $retrieved_data->display_name;?></a></td>
				<td class="department"><?php 
				$postdata=get_post($retrieved_data->department);
				echo $postdata->post_title;?></td>
                <td class="specialization">
				<?php 
						$specialize_id=get_user_meta($uid, 'specialization', true);
						$specialization_data=get_post( $specialize_id);
						echo $specialization_data->post_title;
						
				?></td>
				<td class="subject_name"><?php echo get_user_meta($uid, 'doctor_degree', true);?></td>
                <td class="email"><?php echo $retrieved_data->user_email;?></td>
                 <td class="email"><?php echo $retrieved_data->mobile;?></td>
               	<td class="action"> <a href="?page=hmgt_doctor&tab=adddoctor&action=edit&doctor_id=<?php echo $retrieved_data->ID;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
                <a href="?page=hmgt_doctor&tab=doctorlist&action=delete&doctor_id=<?php echo $retrieved_data->ID;?>" class="btn btn-danger" 
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
	
	if($active_tab == 'adddoctor')
	 {
	require_once HMS_PLUGIN_DIR. '/admin/includes/doctor/add_doctor.php';
	 }
	 ?>
</div>
			
		</div>
	</div>
</div>