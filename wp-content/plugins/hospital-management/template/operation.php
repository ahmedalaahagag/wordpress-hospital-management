<?php 
$obj_ot = new Hmgt_operation();
if(isset($_REQUEST['save_operation']))
{

	if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
	{

		$result = $obj_ot->hmgt_add_operation_theater($_POST);
		if($result)
		{
			if($_REQUEST['action'] == 'edit')
			{
				wp_redirect ( home_url().'?dashboard=user&page=operation&tab=operationlist&message=2');
			}
			else 
			{
				wp_redirect ( home_url().'?dashboard=user&page=operation&tab=operationlist&message=1'); 
			}
			
			
		}
	}
}
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{			
	$result = $obj_ot->delete_oprationtheater($_REQUEST['ot_id']);
	if($result)
	{
		wp_redirect ( home_url().'?dashboard=user&page=operation&tab=operationlist&message=3');
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
$active_tab = isset($_GET['tab'])?$_GET['tab']:'operationlist';
?>

<script type="text/javascript">
$(document).ready(function() {
	$('#hmgt_operation').DataTable({
		 "order": [[ 3, "Desc" ]],
		 "aoColumns":[
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bVisible": true},	                 
	                  {"bSortable": false}
	               ]
		});
	$('#operation_form').validationEngine();
	$('.timepicker').timepicker();
	$('#operation_date').datepicker({
		  changeMonth: true,
	        changeYear: true,
	        dateFormat: 'yy-mm-dd',
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            $(this).val(month + "-" + year);
	        }
                    
                }); 
	// $('#doctor').multiselect();
} );
</script>
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
<div class="panel-body panel-white">
 <ul class="nav nav-tabs panel_tabs" role="tablist">
      <li class="<?php if($active_tab == 'operationlist'){?>active<?php }?>">
          <a href="?dashboard=user&page=operation&tab=operationlist">
             <i class="fa fa-align-justify"></i> <?php _e('Operation List', 'hospital_mgt'); ?></a>
          </a>
      </li>
	  <li class="<?php if($active_tab=='addoperation'){?>active<?php }?>">
		  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['invoice_id']))
			{?>
			<a href="?dashboard=user&page=operation&tab=addoperation&action=edit&ot_id=<?php if(isset($_REQUEST['ot_id'])) echo $_REQUEST['ot_id'];?>"" class="tab <?php echo $active_tab == 'addoperation' ? 'active' : ''; ?>">
             <i class="fa fa"></i> <?php _e('Edit Operation List', 'hospital_mgt'); ?></a>
			 <?php }
			else
			{?>
				<a href="?dashboard=user&page=operation&tab=addoperation" class="tab <?php echo $active_tab == 'addoperation' ? 'active' : ''; ?>">
				<i class="fa fa-plus-circle"></i> <?php _e('Add Operation', 'hospital_mgt'); ?></a>
	  <?php } ?>
	  
	</li>
     
</ul>
	<div class="tab-content">
	<?php if($active_tab == 'operationlist'){?>
    	 <div class="tab-pane fade active in"  id="eventlist">
         <?php 
		 //	$retrieve_class = get_all_data($tablename);		
		?>
		<div class="panel-body">
        <div class="table-responsive">
        <table id="hmgt_operation" class="display dataTable " cellspacing="0" width="100%">
        	<thead>
            <tr>
			<th><?php _e( 'Operation Name', 'hospital_mgt' ) ;?></th>
			 <th><?php _e( 'Patient', 'hospital_mgt' ) ;?></th>
			 <th><?php _e( 'Surgeon', 'hospital_mgt' ) ;?></th>
			 <th><?php _e( 'Date', 'hospital_mgt' ) ;?></th>
             <th><?php _e( 'operation Charge', 'hospital_mgt' ) ;?></th>
			  <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </thead>
		<tfoot>
            <tr>
			<th><?php _e( 'Operation Name', 'hospital_mgt' ) ;?></th>
			 <th><?php _e( 'Patient', 'hospital_mgt' ) ;?></th>
			 <th><?php _e( 'Surgeon', 'hospital_mgt' ) ;?></th>
			 <th><?php _e( 'Date', 'hospital_mgt' ) ;?></th>
              <th><?php _e( 'operation Charge', 'hospital_mgt' ) ;?></th>
			  <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </tfoot>
		<tbody>
         <?php 
		$ot_data=$obj_ot->get_all_operation();
		 if(!empty($ot_data))
		 {
		 	foreach ($ot_data as $retrieved_data){ 
		 		
		 	$patient_data =	get_user_detail_byid($retrieved_data->patient_id);
		 	
		?>
            <tr>
				<td class="operation_name"><?php echo $obj_ot->get_operation_name($retrieved_data->operation_title);?></td>
                <td class="patient"><?php echo $patient_data['first_name']." ".$patient_data['last_name']."(".$patient_data['patient_id'].")";?></td>
                <td class="surgen">
                <?php 
                	$surgenlist =  $obj_ot->get_doctor_by_oprationid($retrieved_data->operation_id) ;
                	foreach($surgenlist as $assign_id)
                	{
						$doctory_data =	get_user_detail_byid($assign_id->child_id);
						echo '<a href="#">';
                		echo $doctory_data['first_name']." ".$doctory_data['last_name']."</a>,";
                		
						
                	}
                ?></td>
                <td class="date"><?php echo $retrieved_data->operation_date;?></td>
                <td class="description"><?php echo $retrieved_data->operation_charge;?></td>
                <td class="action"> <a href="?dashboard=user&page=operation&tab=addoperation&action=edit&ot_id=<?php echo $retrieved_data->operation_id;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
                <a href="?dashboard=user&page=operation&tab=operationlist&action=delete&ot_id=<?php echo $retrieved_data->operation_id;?>" class="btn btn-danger" 
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
		</div>
	<?php }
	 if($active_tab == 'addoperation'){
		 
		$obj_bed = new Hmgtbedmanage();
		$edit = 0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit = 1;
			$ot_id = $_REQUEST['ot_id'];
			$result = $obj_ot->get_single_operation($ot_id);
		
		}
		?>
	
	 <div class="panel-body">
        <form name="operation_form" action="" method="post" class="form-horizontal" id="operation_form">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="operation_id" value="<?php if(isset($_REQUEST['ot_id']))echo $_REQUEST['ot_id'];?>"  />
		<div class="form-group">
			<label class="col-sm-2 control-label" for="patient"><?php _e('Patient','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
			
				<select name="patient_id" id="patient" class="form-control validate[required] ">
					<option value = ""><?php _e('Select Patient','hospital_mgt');?></option>
					<?php 
					if($edit)
						$patient_id1 = $result->patient_id;
					elseif(isset($_REQUEST['patient_id']))
						$patient_id1 = $_REQUEST['patient_id'];
					else 
						$patient_id1 = "";
					$patients = hmgt_inpatient_list();
					
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
			<label class="col-sm-2 control-label" for="patient_status"><?php _e('Patient Status','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8" >
				<?php
				$patient_status = "";
				 if($edit){ 
					$patient=get_inpatient_status($patient_id1);
					if(!empty($patient)){$patient_status=$patient->patient_status;}}elseif(isset($_POST['patient_status'])){ $patient_status=$_POST['patient_status'];}else{ $patient_status=' ';} ?>
				<select name="patient_status" class="form-control validate[required]" >
				<option value = ""><?php _e('select Patient Status','hospital_mgt');?></option>
				<?php foreach(admit_reason() as $reason)
				{?>
					<option value="<?php echo $reason;?>" <?php selected($patient_status,$reason);?>><?php echo $reason;?></option>
				<?php }?>				
				</select>				
			</div>	
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="patient"><?php _e('Operation','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
			<?php if($edit){ $operation=$result->operation_title; }elseif(isset($_POST['operation'])){$operation=$_POST['operation'];}else{$operation='';}?>
				<select name="operation_title" id="category_data" class="form-control validate[required] ">
					<option value = ""><?php _e('Select Operation','hospital_mgt');?></option>
					<?php 
					$operation_type=new Hmgt_operation();
					$operation_array =$operation_type->get_all_operationtype();
					if($edit)
						$operation1 = $result->operation_title;
					elseif(isset($_REQUEST['operation_title']))
					$operation1 = $_REQUEST['operation_title'];
					else
						$operation1 = "";
					 if(!empty($operation_array))
					 {
						foreach ($operation_array as $retrieved_data){?>
							<option value="<?php echo $retrieved_data->ID; ?>" <?php selected($operation1,$retrieved_data->ID);?>><?php echo $retrieved_data->post_title;?></option>
						<?php }
					 }
		?>
				</select>
			</div>
			<div class="col-sm-2"><button id="addremove" model="operation"><?php _e('Add Or Remove','hospital_mgt');?></button></div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="doctor"><?php _e('Select Doctor','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
			<?php $doctors = hmgt_getuser_by_user_role('doctor');
					$doctory_data = array();
					if($edit)
					{
						$doctor1 = $result->operation_title;
						$doctor_list = $obj_ot->get_doctor_by_oprationid($_REQUEST['ot_id']);
						
						foreach($doctor_list as $assign_id)
						{
							$doctory_data[]=$assign_id->child_id;
						
						}
					}
					elseif(isset($_REQUEST['doctor']))
					{
						$doctor_list = $_REQUEST['doctor'];
						foreach($doctor_list as $assign_id)
						{
							$doctory_data[]=$assign_id;
						
						}
					}
					
					?>
				<select name="doctor[]" class="form-control validate[required] " multiple="multiple" id="doctor">
				<option value=""><?php _e('Select Doctor','hospital_mgt');?></option>
				<?php 
					
					
					if(!empty($doctors))
					{
					foreach($doctors as $doctor)
					{
						$selected = "";
						if(in_array($doctor['id'],$doctory_data))
							$selected = "selected";
						echo '<option value='.$doctor['id'].' '.$selected.'>'.$doctor['first_name'].'</option>';
					}
					}
					?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="bedtype"><?php _e('Bed Category','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
			<?php if(isset($_REQUEST['bed_type_id']))
					$bed_type1 = $_REQUEST['bed_type_id'];
				elseif($edit)
					$bed_type1 = $result->bed_type_id;
				else 
					$bed_type1 = "";
				?>
				<select name="bed_type_id" class="form-control validate[required]" id="bed_type_id">
				<option value = ""><?php _e('Select Bed Category','hospital_mgt');?></option>
				<?php 
				
				$bedtype_data=$obj_bed->get_all_bedtype();
				if(!empty($bedtype_data))
				{
					foreach ($bedtype_data as $retrieved_data)
					{
						echo '<option value="'.$retrieved_data->ID.'" '.selected($bed_type1,$retrieved_data->ID).'>'.$retrieved_data->post_title.'</option>';
					}
				}
				?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="bednumber"><?php _e('Bed Number','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select name="bednumber" class="form-control validate[required]" id="bednumber">
				<option><?php _e('Select Bed Number','hospital_mgt');?></option>
				<?php 
				if($edit)
				{
					$obj_bed = new Hmgtbedmanage();
					$bedtype_data = $obj_bed->get_bed_by_bedtype($result->bed_type_id);
					if(!empty($bedtype_data))
					{
						foreach ($bedtype_data as $retrieved_data)
						{
							echo '<option value="'.$retrieved_data->bed_id.'" '.selected($result->bednumber,$retrieved_data->bed_id).'>'.$retrieved_data->bed_number.'</option>';
						}
					}
				}
				?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="request_date"><?php _e('Operation Date','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="operation_date" class="form-control validate[required]" type="text"  value="<?php if($edit){ echo $result->operation_date;}elseif(isset($_POST['operation_date'])) echo $_POST['operation_date'];?>" name="operation_date">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="operation_time"><?php _e('Operation Time','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="operation_time" class="form-control timepicker" data-show-meridian="false" data-minute-step="15" type="text" 
				value="<?php if($edit){ echo $result->operation_time;}elseif(isset($_POST['operation_time'])) echo $_POST['operation_time'];?>" name="operation_time">
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="ot_description"><?php _e('Description','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<textarea id="ot_description" class="form-control validate[required]" name="ot_description"><?php if($edit){ echo $result->ot_description;}elseif(isset($_POST['ot_description'])) echo $_POST['ot_description'];?></textarea>				
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="operation_charge"><?php _e('Opearation Charge','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">				
				<input id="operation_charge" class="form-control validate[required]" type="text"  
				value="<?php if($edit){ echo $result->operation_charge;}elseif(isset($_POST['operation_charge'])) echo $_POST['operation_charge'];?>" name="operation_charge">				
			</div>
		</div>
		<div class="col-sm-offset-2 col-sm-8">
        	<input type="submit" value="<?php if($edit){ _e('Save Operation','hospital_mgt'); }else{ _e('Add Operation','hospital_mgt');}?>" name="save_operation" class="btn btn-success"/>
        </div>
        </form>
        </div>
	 <?php }?>
	</div>
	
</div>
<?php ?>