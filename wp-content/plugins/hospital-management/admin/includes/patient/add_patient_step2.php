<?php 
	// This is Dashboard at admin side!!!!!!!!! 
	$role='patient';
	$patient_id=0;
	if(isset($_REQUEST['patient_id']))
		$patient_id=$_REQUEST['patient_id'];
	$patient_no=get_user_meta($patient_id, 'patient_id', true);
	if(isset($_POST['save_patient_step2']))
	{
		
		$guardian_data=array('guardian_id'=>$_POST['guardian_id'],
						'patient_id'=>$_REQUEST['patient_id'],
						'first_name'=>$_POST['first_name'],
						'middle_name'=>$_POST['middle_name'],
						'last_name'=>$_POST['last_name'],
						'gr_gender'=>$_POST['gender'],
						'gr_address'=>$_POST['address'],
						'gr_city'=>$_POST['city_name'],
						'gr_phone'=>$_POST['phone'],
						'gr_mobile'=>$_POST['mobile'],						
						'gr_relation'=>$_POST['guardian_realtion'],
						'image'=>$_POST['hmgt_user_avatar'],
						'inpatient_create_date'=>date("Y-m-d H:i:s"),'inpatient_create_by'=>get_current_user_id());
		
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
				$result=update_guardian($guardian_data,$_REQUEST['patient_id']);
					wp_redirect ( admin_url () . 'admin.php?page=hmgt_patient&tab=addpatient_step3&patient_id='.$_REQUEST['patient_id'].'&action=edit');
				
				
		}
		else
		{
					$result=add_guardian($guardian_data);
						 if($result)
						 {
							 wp_redirect ( admin_url () . 'admin.php?page=hmgt_patient&tab=addpatient_step3&patient_id='.$_REQUEST['patient_id']);		
						 }
		}
						
	}
	
	?>
	<script type="text/javascript">
$(document).ready(function() {
	$('#guardian_form').validationEngine();
	$('#birth_date').datepicker({
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',	
	        onChangeMonthYear: function(year, month, inst) {
	            $(this).val(month + "/" + year);
	        }
                    
                }); 
} );
</script>
     <?php 	
	if($active_tab == 'addpatient_step2')
	 {
				
				$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
					
					
					$edit=1;
					$user_info = get_guardianby_patient($patient_id);
					//print_r($user_info);
					
					
				}
				//echo "hello".$patient_id;
				//print_r($user_info);
				?>
		
       <div class="panel-body">
        <form name="guardian_form" action="" method="post" class="form-horizontal" id="guardian_form">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="role" value="<?php echo $role;?>"  />
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="guardian_number"><?php _e('Guardian Id','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="guardian_id" class="form-control " type="text" 
				value="<?php if($edit){ echo $user_info['guardian_id'];}elseif(isset($_POST['guardian_id'])) echo $_POST['guardian_id'];?>"   name="guardian_id">
			</div>
		</div>
		<!-- 
		<div class="form-group">
			<label class="col-sm-2 control-label" for="patient_id"><?php _e('Patient Id','hospital_mgt');?></label>
			<div class="col-sm-8">
				
				<input id="patient_no" class="form-control validate[required]" type="text" 
				value="<?php if($edit){ echo get_user_meta($patient_id, 'patient_id', true);}elseif(isset($patient_no)) echo $patient_no;?>"
				name="patient_no" readonly>
			</div>
		</div>
		 -->
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="first_name"><?php _e('First Name','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="first_name" class="form-control validate[required,custom[onlyLetterSp]] text-input" type="text" value="<?php if($edit){ echo $user_info['first_name'];}elseif(isset($_POST['first_name'])) echo $_POST['first_name'];?>" name="first_name">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="middle_name"><?php _e('Middle Name','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="middle_name" class="form-control " type="text"  value="<?php if($edit){ echo $user_info['middle_name'];}elseif(isset($_POST['middle_name'])) echo $_POST['middle_name'];?>" name="middle_name">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="last_name"><?php _e('Last Name','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="last_name" class="form-control validate[required,custom[onlyLetterSp]] text-input" type="text"  value="<?php if($edit){ echo $user_info['last_name'];}elseif(isset($_POST['last_name'])) echo $_POST['last_name'];?>" name="last_name">
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="gender"><?php _e('Gender','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
			<?php $genderval = "male"; if($edit){ $genderval=$user_info['gr_gender']; }elseif(isset($_POST['gender'])) {$genderval=$_POST['gender'];}?>
				<label class="radio-inline">
			     <input type="radio" value="male" class="tog validate[required]" name="gender"  <?php  checked( 'male', $genderval); ?>/><?php _e('Male','hospital_mgt');?>	
			    </label>
			    <label class="radio-inline">
			      <input type="radio" value="female" class="tog validate[required]" name="gender"  <?php  checked( 'female', $genderval);?>/><?php _e('Female','hospital_mgt');?> 
			    </label>	
			</div>
		</div>
		
		
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="address"><?php _e('Home Town Address','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="address" class="form-control " type="text"  name="address" 
				value="<?php if($edit){ echo $user_info['gr_address'];}elseif(isset($_POST['address'])) echo $_POST['address'];?>">
			</div>
		</div>
		
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="city_name"><?php _e('City','school');?></label>
			<div class="col-sm-8">
				<input id="city_name" class="form-control " type="text"  name="city_name" 
				value="<?php if($edit){ echo $user_info['gr_city'];}elseif(isset($_POST['city_name'])) echo $_POST['city_name'];?>">
			</div>
		</div>
		
		
		
		<div class="form-group">
			<label class="col-sm-2 control-label " for="mobile"><?php _e('Mobile Number','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="mobile" class="form-control custom[phone] text-input" type="text"  name="mobile" 
				value="<?php if($edit){ echo $user_info['gr_mobile']	;}elseif(isset($_POST['mobile'])) echo $_POST['mobile'];?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label " for="phone"><?php _e('Phone','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="phone" class="form-control custom[phone] text-input" type="text"  name="phone" 
				value="<?php if($edit){ echo $user_info['gr_phone'];}elseif(isset($_POST['phone'])) echo $_POST['phone'];?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label " for="guardian_realtion"><?php _e('Relation With Patient','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="guardian_realtion" class="form-control validate[required] text-input" type="text"  name="guardian_realtion" 
				value="<?php if($edit){ echo $user_info['gr_relation'];}elseif(isset($_POST['guardian_realtion'])) echo $_POST['guardian_realtion'];?>">
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="photo"><?php _e('Image','hospital_mgt');?></label>
			<div class="col-sm-2">
				<input type="text" id="hmgt_user_avatar_url" class="form-control" name="hmgt_user_avatar"  
				value="<?php if($edit) if(trim($user_info['image']) != "")echo esc_url( $user_info['image'] );elseif(isset($_POST['hmgt_user_avatar'])) echo $_POST['hmgt_user_avatar']; ?>" />
			</div>	
				<div class="col-sm-3">
       				 <input id="upload_user_avatar_button" type="button" class="button" value="<?php _e( 'Upload image', 'hospital_mgt' ); ?>" />
       				 <span class="description"><?php _e('Upload image', 'hospital_mgt' ); ?></span>
       		
			</div>
			<div class="clearfix"></div>
			
			<div class="col-sm-offset-2 col-sm-8">
                     <div id="upload_user_avatar_preview" >
	                     <?php if($edit) 
	                     	{
	                     		//echo "<B><BR><BR>user image".$user_info['image']."<B><BR>";
	                     		
		                     	if(isset($user_info) && isset($user_info['image']) && trim($user_info['image']) != "")
		                     	{
		                     		
		                     		?>
									<img style="max-width:100%;" src="<?php  echo esc_url( $user_info['image'] );?>" />
		                     	
		                     	<?php }
		                     	
								else{?>
									<img alt="" src="<?php echo get_option( 'hmgt_guardian_thumb' ); ?>">
								<?php  }
	                     	}
					        else {
					        	?>
					        	<img alt="" src="<?php echo get_option( 'hmgt_guardian_thumb' ); ?>">
					        	<?php 
					        }?>
    				</div>
   		 </div>
		</div>
		<div class="col-sm-offset-2 col-sm-8">
        	<a href="?page=hmgt_patient&tab=addpatient&action=edit&patient_id=<?php echo $patient_id; ?>">
			<input type="button" value="<?php if($edit){ _e('Back To Last Step','hospital_mgt'); }else{ _e('Back To Last Step','hospital_mgt');}?>" name="back_step" class="btn btn-success"/>
			</a>
        	<input type="submit" value="<?php if($edit){ _e('Save And Next Step','hospital_mgt'); }else{ _e('Save And Next Step','hospital_mgt');}?>" name="save_patient_step2" class="btn btn-success"/>
			
		</div>
        
        </form>
        </div>
<?php 
	 }
?>