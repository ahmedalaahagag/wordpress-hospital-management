<?php 
	//This is Dashboard at admin side
	$role='patient';
	?>
	<script type="text/javascript">
$(document).ready(function() {
	$('#patient_form').validationEngine();
	$('.request_time').timepicker();
	$('.dispatch_time').timepicker();
	$('#request_date').datepicker({
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
	if($active_tab == 'add_ambulance_req')	
	 {
        	$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
					
					$edit=1;
					$result= $obj_ambulance->get_single_ambulance_req($_REQUEST['amb_req_id']);
					
				}?>
		
       <div class="panel-body">
        <form name="patient_form" action="" method="post" class="form-horizontal" id="patient_form">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="amb_req_id" value="<?php if(isset($_REQUEST['amb_req_id']))echo $_REQUEST['amb_req_id'];?>"  />
		
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="ambulance_id"><?php _e('Ambulance','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select name="ambulance_id" class="form-control validate[required]" id="ambulance_id">
					<option value=""><?php _e('select Ambulance','hospital_mgt');?></option>
					<?php 
					if($edit)
						$amb_id = $result->ambulance_id;
					elseif(isset($_REQUEST['ambulance_id']))
						$amb_id = $_REQUEST['ambulance_id'];
					else 	
						$amb_id = "";
						$ambulance_data=$obj_ambulance->get_all_ambulance();
					 	if(!empty($ambulance_data))
					 	{
					 		foreach ($ambulance_data as $retrieved_data)
					 		{ 
					 			echo '<option value = '.$retrieved_data->amb_id.' '.selected($amb_id,$retrieved_data->amb_id).'>'.$retrieved_data->ambulance_id.'</option>';
					 		}
					 	}						
					 ?>
				</select>
				
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="patient_id"><?php _e('Patient','hospital_mgt');?></label>
			<div class="col-sm-8">
				<select name="patient_id" id="patient_id" class="form-control validate[required] ">
					<option><?php _e('Select Patient','hospital_mgt');?></option>
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
			<label class="col-sm-2 control-label" for="address"><?php _e('Address','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<textarea name = "address" id="address" class="form-control validate[required]"><?php if($edit){ echo $result->address;}elseif(isset($_POST['address'])) echo $_POST['address'];?></textarea>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="charges"><?php _e('Charges','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="charges" class="form-control validate[required]" type="text"  value="<?php if($edit){ echo $result->charge;}elseif(isset($_POST['charge'])) echo $_POST['charge'];?>" name="charge">
			</div>
		</div>		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="request_date"><?php _e('Request Date','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="request_date" class="form-control validate[required]" type="text"  value="<?php if($edit){ echo $result->request_date;}elseif(isset($_POST['request_date'])) echo $_POST['request_date'];?>" name="request_date">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="request_time"><?php _e('Request Time','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="request_time" class="form-control request_time" data-show-meridian="false"  data-default-time="00:15"
				type="text"  value="<?php if($edit){ echo $result->request_time;}elseif(isset($_POST['request_time'])) echo $_POST['request_time'];?>" name="request_time">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="dispatch_time"><?php _e('Dispatch Time','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="dispatch_time" class="form-control dispatch_time" data-show-meridian="false" data-minute-step="15" data-default-time="02:25" type="text"  value="<?php if($edit){ echo $result->dispatch_time;}elseif(isset($_POST['dispatch_time'])) echo $_POST['dispatch_time'];?>" name="dispatch_time">
			</div>
		</div>
		<div class="col-sm-offset-2 col-sm-8">
        	<input type="submit" value="<?php if($edit){ _e('Save Request','hospital_mgt'); }else{ _e('Add Ambulance Request','hospital_mgt');}?>" name="save_ambulance_request" class="btn btn-success"/>
        </div>
        </form>
        </div>
        
     <?php 
	 }
	 ?>