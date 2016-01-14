<?php 
	//This is Dashboard at admin side
	$role='patient';
	?>
	<script type="text/javascript">

$(document).ready(function() {
	
	$('#diagnosis_form').validationEngine();

   $("#dignosisreport").click(function(){			
			var files1 = $('#document')[0];			
			var n = files1.files[0].name;
            var  s = files1.files[0].size;
            var type = files1.files[0].type;
           // alert("Size " + s);			
		      if((type != 'image/png') && (type != 'image/jpg')  && (type != 'image/jpeg') && (type != 'image/gif') && (type != 'application/zip') && (type != 'application/pdf'))
		      {
					alert("File type not valid");			
					return false;
			}
		      if( s > 2000000)
				{
		    	  alert("Please Upload Report Size Maximum 2MB");				
					return false;
				}
			
	   });
		
} );
</script>
	
     <?php 	

	if($active_tab == 'adddiagnosis')
	 {
        	$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
					
					$edit=1;
					$result = $obj_dignosis->get_single_dignosis_report($_REQUEST['diagnosis_id']);
					
				}?>
		
       <div class="panel-body">
        <form name="diagnosis_form" action="" method="post" class="form-horizontal" id="diagnosis_form" enctype="multipart/form-data">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="diagnosis_id" value="<?php if(isset($_REQUEST['diagnosis_id'])) echo $_REQUEST['diagnosis_id'];?>"  />
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="patient_id"><?php _e('Patient','hospital_mgt');?><span class="require-field">*</span></label>
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
			<label class="col-sm-2 control-label" for="patient"><?php _e('Report Type','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
			<?php //if($edit){ $operation=$result->operation_title; }elseif(isset($_POST['operation'])){$operation=$_POST['operation'];}else{$operation='';}?>
				<select name="report_type" id="category_data" class="form-control validate[required] ">
					<option value=""><?php _e('Select Report','hospital_mgt');?></option>
					<?php 
					$report_type=new Hmgt_dignosis();
					$operation_array =$report_type->get_all_report_type();
					if($edit)
						$operation1 = $result->report_type;
					elseif(isset($_REQUEST['report_type']))
					$operation1 = $_REQUEST['report_type'];
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
			<div class="col-sm-2"><button id="addremove" model="report_type"><?php _e('Add Or Remove','hospital_mgt');?></button></div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="report_cost"><?php _e('Report Cost','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="report_cost" class="form-control  text-input" type="text" value="<?php if($edit){ echo $result->report_cost;}elseif(isset($_POST['report_cost'])) echo $_POST['report_cost'];?>" name="report_cost">
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="document"><?php _e('Document','hospital_mgt');?></label>
			<div class="col-sm-6">
				<input type="file" class="form-control file" name="document" id="document">
			</div>
			<div class="col-sm-2">
			<input type="hidden" name="edit_document" value="<?php if($edit) echo $result->attach_report; else echo "";?>">
				<?php 				
				if($edit)
					if(trim($result->attach_report) != "")
						echo '<a href="'.content_url().'/uploads/hospital_assets/'.$result->attach_report.'" class="btn btn-default"><i class="fa fa-download"></i> View</a>';
					else
						echo "No any Report";
				?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="description"><?php _e('Description','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<textarea id="diagno_description" class="form-control validate[required]" name="diagno_description"><?php if($edit)echo $result->diagno_description; elseif(isset($_POST['diagno_description'])) echo $_REQUEST['diagno_description']; else echo "";?> </textarea>
			</div>
		</div>
		<div class="col-sm-offset-2 col-sm-8">
        	<input type="submit" id="dignosisreport" value="<?php if($edit){ _e('Save Diagnosis Report','hospital_mgt'); }else{ _e('Create Diagnosis Report','hospital_mgt');}?>" name="save_diagnosis" class="btn btn-success"/>
        </div>
        </form>
        </div>
        
     <?php 
	 }
	 ?>