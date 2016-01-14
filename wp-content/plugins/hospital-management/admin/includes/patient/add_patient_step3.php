<?php 
	// This is Dashboard at admin side!!!!!!!!! 
	$role='patient';
	$patient_id=0;
	if(isset($_REQUEST['patient_id']))
		$patient_id=$_REQUEST['patient_id'];
	 $patient_no=get_user_meta($patient_id,'patient_id', true);
	
	
	
	?>
	<script type="text/javascript">
$(document).ready(function() {
	$('#admit_form').validationEngine();
	$('.timepicker').timepicker();
	$('#admit_date').datepicker({
		  changeMonth: true,
	        changeYear: true,
	        dateFormat: 'yy-mm-dd',
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            $(this).val(month + "/" + year);
	        }
                    
                }); 
} );
</script>
     <?php 	
	if($active_tab == 'addpatient_step3')
	 {
        	$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
					
					$edit=1;
					$user_info = get_guardianby_patient($_REQUEST['patient_id']);
					$doctordetail=get_guardianby_patient($_REQUEST['patient_id']);
					
					$doctor = get_userdata($doctordetail['doctor_id']);
					
				}?>
		
       <div class="panel-body">
        <form name="admit_form" action="" method="post" class="form-horizontal" id="admit_form">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="role" value="<?php echo $role;?>"  />
		<!--  
		<div class="form-group">
			<label class="col-sm-2 control-label" for="patient_id"><?php _e('Patient Id','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="patient_no" class="form-control validate[required]" type="text" 
				value="<?php if($edit){ echo get_user_meta($patient_id, 'patient_id', true);}elseif(isset($patient_no)){ echo $patient_no; }?>"
				name="patient_no" readonly>
				
			</div>
		</div>
		-->
		<div class="form-group">
			<label class="col-sm-2 control-label" for="admit_date"><?php _e('Admit Date','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="admit_date" class="form-control validate[required]" type="text" value="<?php if($edit){ echo $user_info['admit_date'];}elseif(isset($_POST['admit_date'])) echo $_POST['admit_date'];?>" name="admit_date">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="admit_time"><?php _e('Admit Time','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="admit_time" class="form-control validate[required] timepicker" type="text" value="<?php if($edit){ echo $user_info['admit_time'];}elseif(isset($_POST['admit_time'])) echo $_POST['admit_time'];?>" 
				name="admit_time"  data-minute-step="15" data-show-meridian="false" 
				data-default-time="00:15 AM" data-show-seconds="false" data-template="dropdown">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="patient_status"><?php _e('Patient Status','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8" >
				<?php if($edit){ $patient_status=$user_info['patient_status']; }elseif(isset($_POST['patient_status'])){$patient_status=$_POST['patient_status'];}else{$patient_status='';}?>
				<select name="patient_status" class="form-control validate[required]" >
				<option><?php _e('select Patient Status','hospital_mgt');?></option>
				<?php foreach(admit_reason() as $reason)
				{?>
					<option value="<?php echo $reason;?>" <?php selected($patient_status,$reason);?>><?php echo $reason;?></option>
				<?php }?>				
				</select>				
			</div>	
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="doctor"><?php _e('Assign Doctor','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
			<?php if($edit){ if(!empty($doctor)) $doctorid=$doctor->ID; else $doctorid=""; }elseif(isset($_POST['doctor'])){$doctorid=$_POST['doctor'];}else{$doctorid='';}?>
				<select name="doctor" class="form-control validate[required]">
				<option><?php _e('select Doctor','hospital_mgt');?></option>
				<?php 
				$get_doctor = array('role' => 'doctor');
					$doctordata=get_users($get_doctor);
					 if(!empty($doctordata))
					 {
						foreach ($doctordata as $retrieved_data){?>
							<option value="<?php echo $retrieved_data->ID; ?>" <?php selected($doctorid,$retrieved_data->ID);?>><?php echo $retrieved_data->display_name;?></option>
						<?php }
					 }
		?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="symptoms"><?php _e('Symptoms','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<textarea id="symptoms" class="form-control validate[required]" name="symptoms"> <?php if($edit){ echo $user_info['symptoms'];}elseif(isset($_POST['symptoms'])) echo $_POST['symptoms'];?></textarea>
			</div>
		</div>
		
		<div class="col-sm-offset-2 col-sm-8">
        	
        	
			<a href="?page=hmgt_patient&tab=addpatient_step2&action=edit&patient_id=<?php echo $patient_id;?>""><input type="button" value="<?php  _e('Back To Last Step','hospital_mgt');?>" name="back_step" class="btn btn-success" /></a>
        <input type="submit" value="<?php  _e('Save Patient','hospital_mgt'); ?>" name="save_patient_step3" class="btn btn-success"/>
		</div>
          	
        
        </form>
        </div>
        
     <?php 
	 }
	 ?>