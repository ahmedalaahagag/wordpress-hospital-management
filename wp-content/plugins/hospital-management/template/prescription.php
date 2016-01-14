<?php 
$obj_var=new Hmgtprescription();
$obj_treatment=new Hmgt_treatment();
if(isset($_POST['save_prescription']))
{


	if($_REQUEST['action']=='edit')
	{
			
		$result=$obj_var->hmgt_add_prescription($_POST);
		if($result)
			{
				wp_redirect ( home_url() . '?dashboard=user&page=prescription&tab=prescriptionlist&message=2');
			}
			
			
		}
		else
		{
			$result=$obj_var->hmgt_add_prescription($_POST);
				if($result)
				{
					wp_redirect ( home_url() . '?dashboard=user&page=prescription&tab=prescriptionlist&message=1');
				}
		}
		
	}
		
	


	
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
		{
			
			$result=$obj_var->delete_prescription($_REQUEST['prescription_id']);
			if($result)
			{
				wp_redirect ( home_url() . '?dashboard=user&page=prescription&tab=prescriptionlist&message=3');
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

$active_tab = isset($_GET['tab'])?$_GET['tab']:'prescriptionlist';	
?>
<div class="popup-bg">
    <div class="overlay-content">
   
    	<div class="prescription_content"></div>    
    
    </div> 
    
</div>	
<script type="text/javascript">
$(document).ready(function() {
	jQuery('#prescription_list').DataTable({
		"order": [[ 0, "Desc" ]],
		"aoColumns":[
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": false}
	                ]
		});
} );
</script>
<div class="panel-body panel-white">
 <ul class="nav nav-tabs panel_tabs" role="tablist">
      <li class="<?php if($active_tab=='prescriptionlist'){?>active<?php }?>">
          <a href="?dashboard=user&page=prescription&tab=prescriptionlist">
             <i class="fa fa-align-justify"></i> <?php _e('Prescription List', 'hospital_mgt'); ?></a>
          </a>
      </li>
	 
      <li class="<?php if($active_tab=='addprescription'){?>active<?php }?>">
      <a href="?dashboard=user&page=prescription&tab=addprescription">
        <i class="fa fa-plus-circle"></i> 
        <?php 
        if(isset($_REQUEST['action']) && $_REQUEST['action'] =='edit')
        	 _e('Edit Prescription', 'hospital_mgt'); 
        else 
        _e('Add Prescription', 'hospital_mgt'); 
        ?></a> 
      </li>
		
</ul>
	<div class="tab-content">
	 <?php 
		 //	$retrieve_class = get_all_data($tablename);	
         if($active_tab=='prescriptionlist'){
		?>
    	 <div class="tab-pane fade active in" id="prescription">
         <?php 
		 //	$retrieve_class = get_all_data($tablename);		
		?>
		<div class="panel-body">
        <div class="table-responsive">
       <table id="prescription_list" class="display dataTable " cellspacing="0" width="100%">
        	 <thead>
            <tr>
			<th><?php  _e( 'Date', 'hospital_mgt' ) ;?></th>
			 <th> <?php _e( 'Patient ID', 'hospital_mgt' ) ;?></th>
              <th> <?php _e( 'Patient Name', 'hospital_mgt' ) ;?></th>
             
              <th> <?php _e( 'Treatment', 'hospital_mgt' ) ;?></th>
				<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </thead>
		<tfoot>
            <tr>
			<th><?php  _e( 'Date', 'hospital_mgt' ) ;?></th>
			 <th> <?php _e( 'Patient ID', 'hospital_mgt' ) ;?></th>
              <th> <?php _e( 'Patient Name', 'hospital_mgt' ) ;?></th>
             
              <th> <?php _e( 'Treatment', 'hospital_mgt' ) ;?></th>
				<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </tfoot>
 
        <tbody>
         <?php 
		//$prescriptiondata=$obj_var->get_all_prescription();
         $prescriptiondata = $obj_hospital->prescription;
		//var_dump($prescriptiondata);
		 if(!empty($prescriptiondata))
		 {
		 	foreach ($prescriptiondata as $retrieved_data){ 
		?>
          <tr>
				
                <td class="name"><a href="?dashboard=user&page=prescription&action=edit&prescription_id=<?php echo $retrieved_data->priscription_id;?>"><?php echo $retrieved_data->pris_create_date;?></a></td>
                 <td class="patient">
				<?php 
						echo $patient_id=get_user_meta($retrieved_data->patient_id, 'patient_id', true);
						
				?></td>
                <td class="patient">
				<?php 
						
						$patient = get_user_detail_byid( $retrieved_data->patient_id);
						echo  $patient['first_name']." ".$patient['last_name'];
						
				?></td>
               
				<td class="treatment"><?php echo $treatment=$obj_treatment->get_treatment_name($retrieved_data->teratment_id);?></td>
                
               	<td class="action">
               	<a href="#" class="btn btn-primary view-prescription" id="<?php echo $retrieved_data->priscription_id;?>"> <?php _e('View','hospital_mgt');?></a> 
               	<a href="?dashboard=user&page=prescription&tab=addprescription&action=edit&prescription_id=<?php echo $retrieved_data->priscription_id;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
                <a href="?page=prescription&tab=prescriptionlist&action=delete&prescription_id=<?php echo $retrieved_data->priscription_id;?>" class="btn btn-danger" 
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
	if($active_tab=='addprescription'){
	?>
	<script type="text/javascript">
$(document).ready(function() {
	$('#prescription_form').validationEngine();
} );
</script>
	<div class="tab-pane fade active in" id="add_Prescription">
         <?php 
		 //	$retrieve_class = get_all_data($tablename);		
		?>
	
        
      <?php 
	//This is Dashboard at admin side
	$obj_medicine = new Hmgtmedicine();
	$medicinedata=$obj_medicine->get_all_medicine();
	if(!empty($medicinedata))
	{
		$medicine_array = array ();
		foreach ($medicinedata as $retrieved_data){
			$medicine_array [] = $retrieved_data->medicine_name;
		}
	}
	
	$obj_treatment=new Hmgt_treatment();
	$obj_var=new Hmgtprescription();

				$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
					$edit=1;
					$result = $obj_var->get_prescription_data($_REQUEST['prescription_id']);
				
				}?>
	
       <div class="panel-body">
        <form name="prescription_form" action="" method="post" class="form-horizontal" id="prescription_form">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="prescription_id" value="<?php if(isset($_REQUEST['prescription_id'])) echo $_REQUEST['prescription_id'];?>"  />
		
		
		
		
		<div class="form-group">
		
			<label class="col-sm-2 control-label" for="patient_id"><?php _e('Patient','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<?php if($edit){ $patient_id1=$result->patient_id; }elseif(isset($_REQUEST['patient_id'])){$patient_id1=$_REQUEST['patient_id'];}else{ $patient_id1="";}?>
				<select name="patient_id" class="form-control validate[required]" id="patient_id">
				<option value=""><?php _e('select Patient','hospital_mgt');?></option>
				<?php 
					
					$patients = hmgt_patientid_list();
					//print_r($patient);
					if(!empty($patients))
					{
					foreach($patients as $patient)
					{
						echo '<option value="'.$patient['id'].'" '.selected($patient_id1,$patient['id']).'>'.$patient['first_name'].' '.$patient['last_name'].' - '.$patient['patient_id'].'</option>';
					
					}
					}?>
				</select>
				
			</div>
		</div>
		<div class="form-group convert_patient">
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="treatment_id"><?php _e('Treatment','hospital_mgt');?><span class="require-field">*</span></label>
			<?php if($edit){ $treatmentval=$result->teratment_id; }elseif(isset($_POST['treatment_id'])){$treatmentval=$_POST['treatment_id'];}else{ $treatmentval="";}?>
			<div class="col-sm-8">
				<?php $treatment_data=$obj_treatment->get_all_treatment();?>
				
				<select name="treatment_id" class="form-control validate[required]" name="treatment_id">
				<option value=""><?php _e('select Treatment','hospital_mgt');?></option>
				<?php  if(!empty($treatment_data))
					   {
							foreach($treatment_data as $retrieved_data){ ?>
								<option value="<?php echo $retrieved_data->treatment_id;?>" <?php selected($treatmentval,$retrieved_data->treatment_id); ?> > <?php echo $retrieved_data->treatment_name;?></option>
							<?php }
					   }?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="case_history"><?php _e('Case History','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<textarea id="case_history" class="form-control validate[required]" name="case_history"><?php if($edit){echo $result->case_history; }elseif(isset($_POST['case_history'])) echo $_POST['case_history']; ?></textarea>
			</div>
		</div>
		<?php 
		if($edit){
				$all_medicine_list=json_decode($result->medication_list);
			}
			else
			{
				if(isset($_POST['medication'])){
					
					$all_data=$obj_var->get_medication_records($_POST);
					$all_medicine_list=json_decode($all_data);
				}
				
					
			}
			if(!empty($all_medicine_list))
			{
				foreach($all_medicine_list as $entry){
				?>
					<div class="form-group">		
			<label class="col-sm-2 control-label" for="medication"><?php _e('Medication','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-3">
			<select name="medication[]" id="medication" class="form-control valid">
			<?php 
			$medicinedata=$obj_medicine->get_all_medicine();
			if(!empty($medicinedata))
			{
				$medicine_array = array ();
				foreach ($medicinedata as $retrieved_data){
					$medicine_array [] = $retrieved_data->medicine_name;
					echo '<option value="'.$retrieved_data->medicine_id.'" '.selected($entry->medication_name,$retrieved_data->medicine_id).'>'.$retrieved_data->medicine_name.'</option>';
				}
			}
			?>
			</select>
			</div>
			<div class="col-sm-3">
				<select name="times1[]" id="bbb" class="form-control valid">
					<option value=""><?php _e('Time AS Day','hospital_mgt');?></option>
					<option value="1" <?php echo selected($entry->time,'1')?>>1</option>
					<option value="2" <?php echo selected($entry->time,'2')?>>2</option>
					<option value="3" <?php echo selected($entry->time,'3')?>>3</option>
					<option value="4" <?php echo selected($entry->time,'4')?>>4</option>
					<option value="5" <?php echo selected($entry->time,'5')?>>5</option>
					<option value="6" <?php echo selected($entry->time,'6')?>>6</option>
					<option value="7" <?php echo selected($entry->time,'7')?>>7</option>
					<option value="8" <?php echo selected($entry->time,'8')?>>8</option>					
				</select>
			</div>
			<div class="col-sm-2"><input id="days" class="form-control" type="text" value="<?php echo $entry->per_days;?>" name="days[]" placeholder="<?php _e('Total Days','hospital_mgt');?>"></div>
			<div class="col-sm-2">
				<button type="button" class="btn btn-default" onclick="deleteParentElement(this)">
				<i class="entypo-trash"><?php _e('Delete','hospital_mgt');?></i>
				</button>
			</div>
		</div>				
				<?php 
				}
			}
			?>
		<div id="invoice_entry">
		<div class="form-group">		
			<label class="col-sm-2 control-label" for="medication"><?php _e('Medication','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-3">
			<select name="medication[]" id="medication" class="form-control valid">
			<?php 
			$medicinedata=$obj_medicine->get_all_medicine();
			if(!empty($medicinedata))
			{
				$medicine_array = array ();
				foreach ($medicinedata as $retrieved_data){
					$medicine_array [] = $retrieved_data->medicine_name;
					echo '<option value="'.$retrieved_data->medicine_id.'">'.$retrieved_data->medicine_name.'</option>';
				}
			}
			?>
			</select>
			</div>
			<div class="col-sm-3">
				<select name="times1[]" id="bbb" class="form-control valid">
					<option value=""><?php _e('Time AS Day','hospital_mgt');?></option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>					
				</select>
			</div>
			<div class="col-sm-2"><input id="days" class="form-control" type="text" value="" name="days[]" placeholder="<?php _e('Total Days','hospital_mgt');?>"></div>
			<div class="col-sm-2">
				<button type="button" class="btn btn-default" onclick="deleteParentElement(this)">
				<i class="entypo-trash"><?php _e('Delete','hospital_mgt');?></i>
				</button>
			</div>
		</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="invoice_entry"></label>
			<div class="col-sm-3">
				
				<button id="add_new_entry" class="btn btn-default btn-sm btn-icon icon-left" type="button"   name="add_new_entry" onclick="add_entry()">
				<?php _e('Add Medicine','hospital_mgt'); ?>
				</button>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="note"><?php _e('Note','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<textarea id="note" class="form-control validate[required]" name="note"><?php if($edit){echo $result->treatment_note; }elseif(isset($_POST['note'])) echo $_POST['note']; ?> </textarea>
			</div>
		</div>
		<?php 
		if($edit){
			$all_entry=json_decode($result->custom_field);
		}
		else
		{
			if(isset($_POST['custom_label'])){
					
				$all_data=$obj_var->get_entry_records($_POST);
				$all_entry=json_decode($all_data);
			}
		
				
		}
		if(!empty($all_entry))
		{
			foreach($all_entry as $entry){
				?>
					<div id="custom_label">
						<div class="form-group">
						<label class="col-sm-2 control-label" for="income_entry"><?php _e('Custom Fiels','hospital_mgt');?></label>
						<div class="col-sm-2">
							<input id="income_amount" class="form-control text-input" type="text" value="<?php echo $entry->label;?>" name="custom_label[]" placeholder="Field lable">
						</div>
						<div class="col-sm-4">
							<input id="income_entry" class="form-control text-input" type="text" value="<?php echo $entry->value;?>" name="custom_value[]" placeholder="Field value">
						</div>						
						<div class="col-sm-2">
						<button type="button" class="btn btn-default" onclick="deleteParentElement(this)">
						<i class="entypo-trash"><?php _e('Delete','hospital_mgt');?></i>
						</button>
						</div>
						</div>	
					</div>
							<?php }
						
					}
					else
					{
		?>
		<div id="custom_label">
						<div class="form-group">
						<label class="col-sm-2 control-label" for="income_entry"><?php _e('Custom Fiels','hospital_mgt');?></label>
						<div class="col-sm-2">
							<input id="income_amount" class="form-control text-input" type="text" value="" name="custom_label[]" placeholder="Field lable">
						</div>
						<div class="col-sm-4">
							<input id="income_entry" class="form-control text-input" type="text" value="" name="custom_value[]" placeholder="Field value">
						</div>						
						<div class="col-sm-2">
						<button type="button" class="btn btn-default" onclick="deleteParentElement(this)">
						<i class="entypo-trash"><?php _e('Delete','hospital_mgt');?></i>
						</button>
						</div>
						</div>	
					</div>
		<?php }?>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="income_entry"></label>
			<div class="col-sm-3">
				
				<button id="add_new_entry" class="btn btn-default btn-sm btn-icon icon-left" type="button"   name="add_new_entry" onclick="add_custom_label()"><?php _e('Add More Field','hospital_mgt'); ?>
				</button>
			</div>
		</div>
		
		<div class="col-sm-offset-2 col-sm-8">
        	<input type="submit" value="<?php if($edit){ _e('Save Prescription','hospital_mgt'); }else{ _e('Create Prescription','hospital_mgt');}?>" name="save_prescription" class="btn btn-success"/>
        </div>
        </form>
        </div>
         <script>

   
   
   	
  
   	// CREATING BLANK INVOICE ENTRY
   	var blank_income_entry ='';
   	$(document).ready(function() { 
   		blank_income_entry = $('#invoice_entry').html();
   		//alert("hello" + blank_invoice_entry);
   	}); 

	var blank_custom_label ='';
   	$(document).ready(function() { 
   		blank_custom_label = $('#custom_label').html();
   		//alert("hello" + blank_invoice_entry);
   	}); 

   	function add_entry()
   	{
   		$("#invoice_entry").append(blank_income_entry);
   		//alert("hellooo" +blank_income_entry);
   	}

	function add_custom_label()
   	{
   		$("#custom_label").append(blank_custom_label);
   		//alert("hellooo");
   	}
   	
   	// REMOVING INVOICE ENTRY
   	function deleteParentElement(n){
   		n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
   	}
       </script> 
		
		
	</div>
	</div>
	<?php }?>
</div>
<?php ?>