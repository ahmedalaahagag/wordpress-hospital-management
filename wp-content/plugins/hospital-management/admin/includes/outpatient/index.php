<?php 
$role='outpatient';
$id=0;
$user_object=new Hmgtuser();
$blood_obj=new Hmgtbloodbank();
	
	
?>
	<?php
$active_tab = isset($_GET['tab'])?$_GET['tab']:'outpatientlist';
	
	?>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
    <div class="modal-content">
    <div class="patient_data">
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
	if(isset($_POST['save_outpatient']))
	{
		if(isset($_FILES['diagnosis']) && !empty($_FILES['diagnosis']) && $_FILES['diagnosis']['size'] !=0)
		{
			if($_FILES['diagnosis']['size'] > 0){
				$diagnosis_report =load_documets($_FILES['diagnosis'],'diagnosis','report');}
	
	
		}
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='insert')
		{
			if( !email_exists( $_POST['email'] ) && !username_exists( $_POST['username'] )) {
				$result=$user_object->hmgt_add_user($_POST);
					
				if($result)
				{
					$guardian_data=array('patient_id'=>$result,
							'doctor_id'=>$_POST['doctor'],
							'symptoms'=>$_POST['symptoms'],
							'inpatient_create_date'=>date("Y-m-d H:i:s"),'inpatient_create_by'=>get_current_user_id()
					);
					$inserted=add_guardian($guardian_data,$id);
					$user_object->upload_diagnosis_report($result,$diagnosis_report);
					if($inserted)
					{
						wp_redirect ( admin_url() . 'admin.php?page=hmgt_outpatient&tab=outpatientlist&message=1');
					}
				}
			}
			else
					{?>
						<div id="message" class="updated below-h2">
						<p><p><?php _e('Username Or Emailid All Ready Exist.','hospital_mgt');?></p></p>
						</div>
			  <?php }
			
			}
			if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
			{
			$result=$user_object->hmgt_add_user($_POST);
			$guardian_data=array('patient_id'=>$_REQUEST['outpatient_id'],
							'symptoms'=>$_POST['symptoms'],
							'doctor_id'=>$_POST['doctor'],
							'inpatient_create_date'=>date("Y-m-d H:i:s"),'inpatient_create_by'=>get_current_user_id()
							);	
				$result1=update_guardian($guardian_data,$_REQUEST['outpatient_id']);
				$returnans=$user_object->update_diagnosis_report($_REQUEST['outpatient_id'],$diagnosis_report,$_REQUEST['diagnosis_id']);
			if($result || $result1 ||$returnans)
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_outpatient&tab=outpatientlist&message=2');
			}
			
			
			}
			
		}
		
		
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
			{
				
				$result=$user_object->delete_usedata($_REQUEST['outpatient_id']);
				$result=delete_guardian($_REQUEST['outpatient_id']);
				if($result)
				{
					wp_redirect ( admin_url() . 'admin.php?page=hmgt_outpatient&tab=outpatientlist&message=3');
					
				}
			}
		?>
		<?php 
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
    	<a href="?page=hmgt_outpatient&tab=outpatientlist" class="nav-tab <?php echo $active_tab == 'outpatientlist' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Outpatient List', 'hospital_mgt'); ?></a>
    	
        <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{?>
        <a href="?page=hmgt_outpatient&tab=addoutpatient&&action=edit&outpatient_id=<?php echo $_REQUEST['outpatient_id'];?>" class="nav-tab <?php echo $active_tab == 'addoutpatient' ? 'nav-tab-active' : ''; ?>">
		<?php _e('Edit Outpatient', 'hospital_mgt'); ?></a>  
		<?php 
		}
		else
		{?>
			<a href="?page=hmgt_outpatient&tab=addoutpatient" class="nav-tab <?php echo $active_tab == 'addoutpatient' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add New Outpatient', 'hospital_mgt'); ?></a>  
		
		<?php  }?>
       
    </h2>
     <?php 
	//Report 1 
	if($active_tab == 'outpatientlist')
	{ ?>
	<script>
    $(document).ready(function() {
	jQuery('#outpatient_list').DataTable({ 
		"order": [[ 1, "asc" ]],
		"aoColumns":[
	                  {"bSortable": false},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bVisible": true},
	                  {"bVisible": true},
	                  {"bSortable": false}
	               ]
    });
	
} );
</script>	
    <form name="wcwm_report" action="" method="post">
     <div class="panel-body">
        	<div class="table-responsive">
        <table id="outpatient_list" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>
			<th><?php  _e( 'Photo', 'hospital_mgt' ) ;?></th>
			   <th><?php _e( 'Patient Name', 'hospital_mgt' ) ;?></th>
			 <th><?php _e( 'Patient Number', 'hospital_mgt' ) ;?></th>           
			  <th> <?php _e( 'Mobile No', 'hospital_mgt' ) ;?></th>
			  <th> <?php _e( 'Blood Group', 'hospital_mgt' ) ;?></th>
                <th> <?php _e( 'Email', 'hospital_mgt' ) ;?></th>
                <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </thead>
		<tfoot>
            <tr>
				<th><?php  _e( 'Photo', 'hospital_mgt' ) ;?></th>
				<th><?php _e( 'Patient Name', 'hospital_mgt' ) ;?></th>
				<th><?php _e( 'Patient Number', 'hospital_mgt' ) ;?></th>				
				<th> <?php _e( 'Mobile No', 'hospital_mgt' ) ;?></th>
				<th> <?php _e( 'Blood Group', 'hospital_mgt' ) ;?></th>
                <th> <?php _e( 'Email', 'hospital_mgt' ) ;?></th>
                <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </tfoot>
 
        <tbody>
         <?php 
		$get_patient = array('role' => 'patient','meta_key'=>'patient_type','meta_value'=>'outpatient');
		$patientdata=get_users($get_patient);
		 if(!empty($patientdata))
		 {
		 	foreach ($patientdata as $retrieved_data){
			
		 ?>
            <tr>
				<td class="user_image"><?php $uid=$retrieved_data->ID;
							$userimage=get_user_meta($uid, 'hmgt_user_avatar', true);
								if(empty($userimage))
									{
										echo '<img src='.get_option( 'hmgt_patient_thumb' ).' height="50px" width="50px" class="img-circle" />';
									}
							else
							echo '<img src='.$userimage.' height="50px" width="50px" class="img-circle"/>';
				?></td>
                <td class="name"><a href="?page=hmgt_outpatient&tab=addoutpatient&action=edit&outpatient_id=<?php echo $retrieved_data->ID;?>"><?php echo $retrieved_data->display_name;?></a></td>
                <td class="patient_id">
				<?php 
						echo get_user_meta($uid, 'patient_id', true);
				?></td>
				<td class="phone"><?php echo get_user_meta($uid, 'mobile', true);?></td>
                <td class="email"><?php echo get_user_meta($uid, 'blood_group', true);?></td>
				<td class="email"><?php echo $retrieved_data->user_email;?></td>

               	<td class="action"> 
				<a  href="?page=hmgt_outpatient&action=view_status&outpatient_id=<?php echo $retrieved_data->ID;?>" class="show-popup btn btn-default" idtest="<?php echo $retrieved_data->ID; ?>"><i class="fa fa-eye"></i> <?php _e('View Detail', 'hospital_mgt');?></a>
				<a  href="?page=hmgt_outpatient&action=view_status&patient_id=<?php echo $retrieved_data->ID;?>" class="show-charges-popup btn btn-default" idtest="<?php echo $retrieved_data->ID; ?>">
				<i class="fa fa-money"></i> <?php _e('Charges', 'hospital_mgt');?></a>
				<a href="?page=hmgt_outpatient&tab=addoutpatient&action=edit&outpatient_id=<?php echo $retrieved_data->ID;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
                <a href="?page=hmgt_outpatient&tab=outpatientlist&action=delete&outpatient_id=<?php echo $retrieved_data->ID;?>" class="btn btn-danger" 
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
	
	if($active_tab == 'addoutpatient')
	 {
	require_once HMS_PLUGIN_DIR. '/admin/includes/outpatient/add_out_patient.php';
	 }
	 
	 ?>
</div>
			
		</div>
	</div>
</div>


<?php //} ?>