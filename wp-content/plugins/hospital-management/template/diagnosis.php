<?php 
$obj_dignosis = new Hmgt_dignosis();
$active_tab=isset($_REQUEST['tab'])?$_REQUEST['tab']:'diagnosislist';
if(isset($_REQUEST['save_diagnosis']))
{
	$result = $obj_dignosis->hmgt_add_dignosis($_POST);
	
			if(isset($_REQUEST['action']) &&  $_REQUEST['action'] == 'edit')
			{
					wp_redirect ( home_url() . '?dashboard=user&page=diagnosis&tab=diagnosislist&message=2');
			}
			else 
			{
				wp_redirect ( home_url() . '?dashboard=user&page=diagnosis&tab=diagnosislist&message=1');
			}
			
			
		
	
}
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
	$result = $obj_dignosis->delete_dignosis($_REQUEST['diagnosis_id']);
	if($result)
	{
		wp_redirect ( home_url() . '?dashboard=user&page=diagnosis&tab=diagnosislist&message=3');
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
$active_tab = isset($_GET['tab'])?$_GET['tab']:'diagnosislist';
	$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
					
					$edit=1;
					$result = $obj_dignosis->get_single_dignosis_report($_REQUEST['diagnosis_id']);
					
				}?>


<script type="text/javascript">
$(document).ready(function() {
	jQuery('#diagnosis').DataTable({
		 "order": [[ 0, "Desc" ]],
		 "aoColumns":[
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bVisible": true},	                 
	                  {"bSortable": false}
	               ]
		});
} );
</script>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
    <div class="modal-content">
    <div class="notice_content"></div>    
    <div class="category_list">
     </div>
     
    </div>
    </div> 
    
</div>
<!-- End POP-UP Code -->
<div class="panel-body panel-white">
 <ul class="nav nav-tabs panel_tabs" role="tablist">
      <li class="<?php if($active_tab=='diagnosislist'){?>active<?php }?>">
			<a href="?dashboard=user&page=diagnosis&tab=diagnosislist" class="tab <?php echo $active_tab == 'diagnosislist' ? 'active' : ''; ?>">
             <i class="fa fa-align-justify"></i> <?php _e('Diagnosis Report List', 'hospital_mgt'); ?></a>
          </a>
      </li>
       <li class="<?php if($active_tab=='adddiagnosis'){?>active<?php }?>">
		  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['diagnosis_id']))
			{?>
			<a href="?dashboard=user&page=diagnosis&tab=adddiagnosis&action=edit&diagnosis_id=<?php if(isset($_REQUEST['diagnosis_id'])) echo $_REQUEST['diagnosis_id'];?>"" class="tab <?php echo $active_tab == 'adddiagnosis' ? 'active' : ''; ?>">
             <i class="fa fa"></i> <?php _e('Edit Diagnosis Report', 'hospital_mgt'); ?></a>
			 <?php }
			else
			{
				 if($obj_hospital->role != 'patient'){
				?>
				<a href="?dashboard=user&page=diagnosis&tab=adddiagnosis" class="tab <?php echo $active_tab == 'adddiagnosis' ? 'active' : ''; ?>">
				<i class="fa fa-plus-circle"></i> <?php _e('Add Diagnosis Report', 'hospital_mgt'); ?></a>
	  <?php } }?>
	  
	</li>
</ul>
<?php if($active_tab=='diagnosislist'){?>
	<div class="tab-content">
    	 
		<div class="panel-body">
        <div class="table-responsive">	
        <table id="diagnosis" class="display dataTable" cellspacing="0" width="100%">
        		 <thead>
            <tr>
			<th><?php  _e( 'Date', 'hospital_mgt' ) ;?></th>
			<th> <?php _e( 'Patient ID-Name', 'hospital_mgt' ) ;?></th>
              <th> <?php _e( 'Report Type', 'hospital_mgt' ) ;?></th>
				<th> <?php _e( 'Description', 'hospital_mgt' ) ;?></th>
				<th> <?php _e( 'Report', 'hospital_mgt' ) ;?></th>
				<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </thead>
	<tfoot>
            <tr>
			<th><?php  _e( 'Date', 'hospital_mgt' ) ;?></th>
			<th> <?php _e( 'Patient ID-Name', 'hospital_mgt' ) ;?></th>
              <th> <?php _e( 'Report Type', 'hospital_mgt' ) ;?></th>
				<th> <?php _e( 'Description', 'hospital_mgt' ) ;?></th>
				<th> <?php _e( 'Report', 'hospital_mgt' ) ;?></th>
				<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </tfoot>
 
        <tbody>
         <?php 
         
         if($role == 'patient')
         	$dignosis_data = $obj_hospital->get_current_patint_diagnosis_report(get_current_user_id());
         else
		$dignosis_data=$obj_dignosis->get_all_dignosis_report();
	
		 if(!empty($dignosis_data))
		 {
		 	foreach ($dignosis_data as $retrieved_data){ 
			
			
		 ?>
            <tr>
				<td class="date"><?php echo $retrieved_data->diagnosis_date;?></td>
				<td class="patient_id">
				<?php 
					$patient = get_user_detail_byid( $retrieved_data->patient_id);
					echo $patient['id']." - ".$patient['first_name']." ".$patient['last_name'];
				
				?></td>
                <td class="report_type"> <?php echo $obj_dignosis->get_report_type_name($retrieved_data->report_type);?></td>
                <td class="description"><?php echo $retrieved_data->diagno_description;?></td>		
                <td class="report">
                <?php 
					if(trim($retrieved_data->attach_report) != "")
                		echo '<a href="'.HMS_PLUGIN_URL.'/download_document.php?mime='.$retrieved_data->attach_report.'&title='.$retrieved_data->attach_report.'&token='.WP_CONTENT_DIR.'/uploads/hospital_assets/'.$retrieved_data->attach_report.'" class="btn btn-default"><i class="fa fa-download"></i> Download</a>';
					else 
						echo __('No any Report','hospital_mgt');
                ?>
                </td>				
               	<td class="action"> 
               	<?php if(($obj_hospital->role != 'patient')){?>
               	<a href="?dashboard=user&page=diagnosis&tab=adddiagnosis&action=edit&diagnosis_id=<?php echo $retrieved_data->diagnosis_id;?>" 
               	class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
               	<?php }?>
               	
               	 	<?php if(($obj_hospital->role == 'patient')){?>
               	<a id="<?php echo $retrieved_data->diagnosis_id;?>" 
               	class="btn btn-primary view-report"> <?php _e('view', 'hospital_mgt' ) ;?></a>
               	<?php }?>
               	
                <?php if(($obj_hospital->role != 'laboratorist') ){
                		if(($obj_hospital->role != 'patient')){
                	?>
                <a href="?dashboard=user&page=diagnosis&tab=diagnosislist&action=delete&diagnosis_id=<?php echo $retrieved_data->diagnosis_id;?>" 
                class="btn btn-danger" 
                onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
                <?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>
               <?php }}?>
                </td>
               
            </tr>
            <?php } 
			
		}?>
     
        </tbody>
        
        </table>
        </div>
        </div>
<?php }
	if($active_tab=='adddiagnosis'){?>
		
<script type="text/javascript">
$(document).ready(function() {
	
	$('#diagnosis_form').validationEngine();
	
} );
</script>
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
         
	<?php }?>
	</div>
</div>

<?php ?>