<?php 

$obj_appointment = new Hmgt_appointment();
$hospital_obj=new Hospital_Management(get_current_user_id());
?>
<script type="text/javascript">
$(document).ready(function() {
	
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
if(isset($_REQUEST['save_appointment']))
{

	if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
	{
		
		$result = $obj_appointment->hmgt_add_appointment($_POST);
		
		if($result)
		{
			$hmgt_sms_service_enable=0;
			if(isset($_POST['hmgt_sms_service_enable']))
				$hmgt_sms_service_enable = $_POST['hmgt_sms_service_enable'];
			if($hmgt_sms_service_enable)
			{
				$doctor_number = "+".hmgt_get_countery_phonecode(get_option( 'hmgt_contry' )).get_user_meta($_REQUEST['doctor_id'], 'mobile',true);
				$patient_number = "+".hmgt_get_countery_phonecode(get_option( 'hmgt_contry' )).get_user_meta($_REQUEST['patient_id'], 'mobile',true);
				$doctor_name = hmgt_get_display_name($_REQUEST['doctor_id']);
				$patient_name = hmgt_get_display_name($_REQUEST['patient_id']);
				$message = "The Appointment has been booked for $patient_name with Dr. $doctor_name on DATE : ".$_REQUEST['appointment_date']." TIME : ".$_REQUEST['appointment_time'];
				
				if($current_sms_service == 'clickatell')
				{
						
					$clickatell=get_option('hmgt_clickatell_sms_service');
					$to1 = $doctor_number;
					$to2 = $patient_number;
					$message = $message_content;
					$username = $clickatell['username']; //clickatell username
					$password = $clickatell['password']; // clickatell password
					$api_key = $clickatell['api_key'];//clickatell apikey
					$baseurl ="http://api.clickatell.com";
					$url = "$baseurl/http/auth?user=$username&password=$password&api_id=$api_key";
					$ret = file($url);
					$sess = explode(":",$ret[0]);
					if ($sess[0] == "OK")
					{
							
						$sess_id = trim($sess[1]); // remove any whitespace
						$url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to1&text=$message";
						$ret = file($url);
						$send = explode(":",$ret[0]);
						$url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to2&text=$message";
						$ret = file($url);
						$send = explode(":",$ret[0]);
					}
				}
				if($current_sms_service == 'twillo')
				{
					//Twilio lib
					require_once HMS_PLUGIN_DIR. '/lib/twilio/Services/Twilio.php';
					$twilio=get_option( 'hmgt_twillo_sms_service');
						
					$account_sid = $twilio['account_sid']; //Twilio SID
					$auth_token = $twilio['auth_token']; // Twilio token
					$from_number = $twilio['from_number'];//My number
					$receiver = $reciever_number; //Receiver Number
			
					//twilio object
					$client = new Services_Twilio($account_sid, $auth_token);
					$message_sent = $client->account->messages->sendMessage(
							$from_number, // From a valid Twilio number
							$doctor_number, // Text this number
							$message
					);
					$message_sent = $client->account->messages->sendMessage(
							$from_number, // From a valid Twilio number
							$patient_number, // Text this number
							$message
					);
						
				}
			}
			if($_REQUEST['action'] == 'edit')
			{
				wp_redirect ( home_url() . '?dashboard=user&page=appointment&tab=appointmentlist&message=2');
			}
			else 
			{
				wp_redirect ( home_url() . '?dashboard=user&page=appointment&tab=appointmentlist&message=1');
			}	
			
			
		}
	}
}
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
	$result = $obj_appointment->delete_appointment($_REQUEST['appointment_id']);
	if($result)
	{
			wp_redirect ( home_url() . '?dashboard=user&page=appointment&tab=appointmentlist&message=3');
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

$active_tab = isset($_REQUEST['tab'])?$_REQUEST['tab']:'appointmentlist';
	?>
	 <?php if($obj_hospital->role == 'doctor'){?>
<script type="text/javascript">
$(document).ready(function() {
	jQuery('#appointment_list').DataTable({
		 "order": [[ 0, "Desc" ]],
		 "aoColumns":[
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},	                                 
	                  {"bSortable": false}
	               ]
		});
		
	
} );
</script>
<?php }
else{?>

<script type="text/javascript">
$(document).ready(function() {
	jQuery('#appointment_list').DataTable({ "order": [[ 0, "Desc" ]]});
	
} );
</script>
<?php }?>
<div class="panel-body panel-white">
 <ul class="nav nav-tabs panel_tabs" role="tablist">
      <li class="<?php if($active_tab == 'appointmentlist'){?>active<?php }?>">
          <a href="?dashboard=user&page=appointment&tab=appointmentlist">
             <i class="fa fa-align-justify"></i> <?php _e('Appointment List', 'hospital_mgt'); ?></a>
          </a>
      </li>
	   <?php if( $hospital_obj->role == 'doctor' ||  $obj_hospital->role == 'receptionist' || $obj_hospital->role == 'nurse'){?>
      <li class="<?php if($active_tab == 'addappoint'){?>active<?php }?>"><a href="?dashboard=user&page=appointment&tab=addappoint">
        <i class="fa fa-plus-circle"></i> <?php
		if(isset($_REQUEST['action']) && $_REQUEST['action'] =='edit')
			_e('Edit Appointment', 'hospital_mgt'); 
		else
			_e('Add Appointment', 'hospital_mgt'); 
	?></a> 
      </li>
     <?php }?>
</ul>
	  <div class="tab-content">
      <div class="tab-pane <?php if($active_tab == 'appointmentlist'){?>fade active in<?php }?>" id="appointmentlist">
         <div class="panel-body">
        <div class="table-responsive">
       <table id="appointment_list" class="display dataTable " cellspacing="0" width="100%">
        	 <thead>
            <tr>
			<th><?php _e( 'Date', 'hospital_mgt' ) ;?></th>
			 <th><?php _e( 'Patient ', 'hospital_mgt' ) ;?></th>
              <th><?php _e( 'Doctor', 'hospital_mgt' ) ;?></th>
              <?php if($obj_hospital->role == 'doctor'){?>
			  <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
			  <?php }?>
            </tr>
        </thead>
		<tfoot>
            <tr>
			<th><?php _e( 'Date', 'hospital_mgt' ) ;?></th>
			 <th><?php _e( 'Patient ', 'hospital_mgt' ) ;?></th>
              <th><?php _e( 'Doctor', 'hospital_mgt' ) ;?></th>
              <?php if($obj_hospital->role == 'doctor'){?>
			  <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
			  <?php }?>
            </tr>
        </tfoot>
	<tbody>
         <?php 
		//$appointment_data=$obj_appointment->get_all_appointment();
		 if(!empty($appointment_data))
		 {
		 	foreach ($appointment_data as $retrieved_data){ 
			
			
		 ?>
            <tr>
				<td class="appointment_time"><?php echo $retrieved_data->appointment_time_string;?></td>
                <td class="patient">
                <?php 
                $patient_data =	get_user_detail_byid($retrieved_data->patient_id);
                echo $patient_data['first_name']." ".$patient_data['last_name'];?></td>     
                <td class="doctor">
                 <?php $doctor_data =	get_user_detail_byid($retrieved_data->doctor_id);
                echo $doctor_data['first_name']." ".$doctor_data['last_name'];?></td>    
                <?php if($obj_hospital->role == 'doctor' ){?>            
               	<td class="action"> 
               	<a href="?dashboard=user&page=appointment&tab=addappoint&action=edit&appointment_id=<?php echo $retrieved_data->appointment_id;?>" class="btn btn-info"> 
               	<?php _e('Edit', 'hospital_mgt' ) ;?></a>
                <a href="?dashboard=user&page=appointment&tab=appointmentlist&action=delete&appointment_id=<?php echo $retrieved_data->appointment_id;?>" class="btn btn-danger" 
                onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
                <?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>
               
                </td>
                <?php }?>
               
            </tr>
            <?php } 
			
		}?>
        </tbody>
        
        </table>
 		</div>
		</div>
	</div>
	<?php 
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

  
		
} );
</script>
	<div class="tab-pane <?php if($active_tab == 'addappoint'){?>fade active in<?php }?>" id="add_appointment">
         <div class="panel-body">
        <form name="patient_form" action="" method="post" class="form-horizontal" id="patient_form">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="appointment_id" value="<?php if(isset($_REQUEST['appointment_id'])) echo $_REQUEST['appointment_id'];?>"  />
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="bed_number"><?php _e('Date','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-6">
				<input id="appointment_date" class="form-control validate[required] text-input" 
				type="text" value="<?php if($edit){ echo $result->appointment_date;}elseif(isset($_POST['appointment_date'])) echo $_POST['appointment_date'];?>" 
				name="appointment_date">
				</div> 
				<div class="col-sm-2">
				<input id="appointment_time" class="form-control validate[required] timepicker" type="text" 
				value="<?php if($edit){ echo $result->appointment_time;}elseif(isset($_POST['appointment_time'])) echo $_POST['appointment_time'];?>" 
				name="appointment_time" placeholder="Time"  data-minute-step="15" data-show-meridian="false" 
				data-default-time="00:15 AM" data-show-seconds="false" data-template="dropdown">
			</div>
		</div>	
		<?php
			if($obj_hospital->role == 'nurse' || $obj_hospital->role == 'doctor' || $obj_hospital->role == 'receptionist')
			{
		?>
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
		<?php 
			}
			elseif($obj_hospital->role == 'patient')
			{
				echo '<input type="hidden" name="patient_id" value="'.get_current_user_id().'">';
			}
			
		?>
		<?php
			if($obj_hospital->role == 'nurse' || $obj_hospital->role == 'patient' || $obj_hospital->role == 'receptionist' )
			{
		?>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="middle_name"><?php _e('Select Doctor','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<?php $doctors = hmgt_getuser_by_user_role('doctor');
					
					//var_dump($doctors );
					?>
					
				<select name="doctor_id" class="form-control validate[required] " id="doctor">
				<option value=""><?php  _e('Select Doctor','hospital_mgt');?></option>
				<?php 
				$doctory_data=$result->doctor_id;	
					if(!empty($doctors))
					{
					foreach($doctors as $doctor)
					{
						
						echo '<option value='.$doctor['id'].'" '.selected($doctory_data,$doctor['id']).'>'.$doctor['first_name'].'</option>';
					}
					}
					?>
				</select>
			</div>
		</div>
		
		<?php 
			}
			elseif($obj_hospital->role == 'doctor')
			{
				echo '<input type="hidden" name="doctor_id" value="'.get_current_user_id().'">';
			}
			
		?>
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
		</div>
</div>
</div>
<?php ?>