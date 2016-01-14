<?php 
	//This is Dashboard at admin side
$edit = 0;
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
{
	$edit = 1;
	$appointment_id = $_REQUEST['appointment_id'];
	$result = $obj_appointment->get_single_appointment($appointment_id);
	//var_dump($result);
}
	?>
	<script type="text/javascript">
$(document).ready(function() {
	$('#patient_form').validationEngine();
	$('#appointment_date').datepicker({
		  changeMonth: true,
	        changeYear: true,
	        dateFormat: 'yy-mm-dd',
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            $(this).val(month + "/" + year);
	        }
                    
                }); 
	$('.timepicker').timepicker();
} );
</script>
     <?php 	
	if($active_tab == 'addappointment')
	 {
				?>
		
       <div class="panel-body">
        <form name="patient_form" action="" method="post" class="form-horizontal" id="patient_form">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="appointment_id" value="<?php if(isset($_REQUEST['appointment_id'])) echo $_REQUEST['appointment_id'];?>"  />
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="bed_number"><?php _e('Date','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-6">
				<input id="appointment_date" class="form-control  validate[required] text-input" 
				type="date" value="<?php if($edit){ echo $result->appointment_date;}elseif(isset($_POST['appointment_date'])) echo $_POST['appointment_date'];?>" 
				name="appointment_date">
				</div>
				<div class="col-sm-2">
				<input id="appointment_time" class="form-control validate[required] timepicker" type="text" 
				value="<?php if($edit){ echo $result->appointment_time;}elseif(isset($_POST['appointment_time'])) echo $_POST['appointment_time'];?>" 
				name="appointment_time" placeholder="Time"  data-minute-step="15" data-show-meridian="false" 
				data-default-time="00:15 AM" data-show-seconds="false" data-template="dropdown">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="middle_name"><?php _e('Select Patient','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select name="patient_id" id="patient" class="form-control validate[required] ">
					<option value=""><?php _e('Select Patient','hospital_mgt');?></option>
					<?php 
					if($edit)
						$patient_id1 = $result->patient_id;
					elseif(isset($_REQUEST['patient_id']))
						$patient_id1 = $_REQUEST['patient_id'];
					else 
						$patient_id1 = "";
					$patients = hmgt_patientid_list();
					//print_r($patient);
					if(!empty($patients))
					{
					foreach($patients as $patient)
					{
						echo '<option value="'.$patient['id'].'" '.selected($patient_id1,$patient['id']).'>'.$patient['patient_id'].' - '.$patient['first_name'].' '.$patient['last_name'].'</option>';
					}
					}
					?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="middle_name"><?php _e('Select Doctor','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<?php $doctors = hmgt_getuser_by_user_role('doctor');
					
					
					?>
				<select name="doctor_id" class="form-control validate[required] " id="doctor">
				<option value=""><?php  _e('Select Doctor ','hospital_mgt');?></option>
				<?php 
					
				$doctory_data=$result->doctor_id;
					if(!empty($doctors))
					{
					foreach($doctors as $doctor)
					{
						
						echo '<option value='.$doctor['id'].' '.selected($doctory_data,$doctor['id']).'>'.$doctor['first_name'].'</option>';
					}
					}
					?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label " for="enable"><?php _e('Send SMS','hospital_mgt');?></label>
			<div class="col-sm-8">
				 <div class="checkbox">
				 	<label>
  						<input id="chk_sms_sent11" type="checkbox" <?php $smgt_sms_service_enable = 0;if($smgt_sms_service_enable) echo "checked";?> value="1" name="hmgt_sms_service_enable">
  					</label>
  				</div>
				 
			</div>
		</div>
		
		<div class="col-sm-offset-2 col-sm-8">
        	<input type="submit" value="<?php if($edit){ _e('Save Appointment','hospital_mgt'); }else{ _e('Add Appointment','hospital_mgt');}?>" name="save_appointment" class="btn btn-success"/>
        </div>
        </form>
        </div>
        
     <?php 
	 }
	 ?>