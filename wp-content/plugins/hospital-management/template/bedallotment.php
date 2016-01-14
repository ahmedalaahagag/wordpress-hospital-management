<?php 

$obj_bed = new Hmgtbedmanage();
?>

<?php 
if(isset($_REQUEST['bedallotment']))
{

	if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
	{

		$result = $obj_bed->add_bed_allotment($_POST);
		if($result)
		{
			if($_REQUEST['action'] == 'edit')
			{
				wp_redirect ( home_url() . '?dashboard=user&page=bedallotment&tab=bedallotlist&message=2');
			}
			else 
			{
				wp_redirect ( home_url() . '?dashboard=user&page=bedallotment&tab=bedallotlist&message=1');
			}
			
			
		}
	}
}
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
	$result = $obj_bed->delete_bedallocate_record($_REQUEST['allotment_id']);
	if($result)
	{
		wp_redirect ( home_url() . '?dashboard=user&page=bedallotment&tab=bedallotlist&message=3');
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

$active_tab = isset($_GET['tab'])?$_GET['tab']:'bedallotlist';
?>

<div class="panel-body panel-white">
 <ul class="nav nav-tabs panel_tabs" role="tablist">
      <li class="<?php if($active_tab=='bedallotlist'){?>active<?php }?>">
          <a href="?dashboard=user&page=bedallotment&tab=bedallotlist">
             <i class="fa fa-align-justify"></i> <?php _e('Bed Allotment List', 'hospital_mgt'); ?></a>
          </a>
      </li>
      <li class="<?php if($active_tab=='bedassign'){?>active<?php }?>">
          <a href="?dashboard=user&page=bedallotment&tab=bedassign">
             <i class="fa fa-plus-circle"></i> <?php _e('Assign Bed', 'hospital_mgt'); ?></a>
          </a>
      </li>
</ul>
	<div class="tab-content">
	 <?php 
		 //	$retrieve_class = get_all_data($tablename);	
         if($active_tab=='bedallotlist'){
		?>
		<script type="text/javascript">
$(document).ready(function() {
	jQuery('#bedallotmentlist').DataTable({
		 "order": [[ 4, "Desc" ]],
		 "aoColumns":[
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},	                 
	                  {"bSortable": false}
	               ]
		});
		
	
} );
</script>
    	<div class="tab-pane fade active in"  id="bedallotlist">
        
		<div class="panel-body">
        <div class="table-responsive">
       <table id="bedallotmentlist" class="display dataTable " cellspacing="0" width="100%">
        	 <thead>
            <tr>
            <th><?php _e( 'Bed Type', 'hospital_mgt' ) ;?></th>
			<th><?php _e( 'Bed Number', 'hospital_mgt' ) ;?></th>
			 <th><?php _e( 'Patient', 'hospital_mgt' ) ;?></th>
			   <th><?php _e( 'Nurse', 'hospital_mgt' ) ;?></th>
              <th><?php _e( 'Allotment Date', 'hospital_mgt' ) ;?></th>
			  <th><?php _e( 'Discharge Date', 'hospital_mgt' ) ;?></th>
			  <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </thead>
		<tfoot>
            <tr>
            <th><?php _e( 'Bed Type', 'hospital_mgt' ) ;?></th>
			<th><?php _e( 'Bed Number', 'hospital_mgt' ) ;?></th>
			 <th><?php _e( 'Patient', 'hospital_mgt' ) ;?></th>
			   <th><?php _e( 'Nurse', 'hospital_mgt' ) ;?></th>
              <th><?php _e( 'Allotment Date', 'hospital_mgt' ) ;?></th>
			  <th><?php _e( 'Discharge Date', 'hospital_mgt' ) ;?></th>
			  <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </tfoot>
 
        <tbody>
         <?php 
		 
		$bedallotment_data=$obj_bed->get_all_bedallotment();
		
		
		 if(!empty($bedallotment_data))
		 {
		 	foreach ($bedallotment_data as $retrieved_data){ 
		 		$patient_data =	get_user_detail_byid($retrieved_data->patient_id);
		?>
            <tr>
				<td class="bed_type"><?php echo $obj_bed->get_bedtype_name($retrieved_data->bed_type_id);	?></td>
                <td class="bed_number"><?php echo $obj_bed->get_bed_number($retrieved_data->bed_number);?></td>
                <td class="patient"><?php echo $patient_data['first_name']." ".$patient_data['last_name']."(".$patient_data['patient_id'].")";?></td>
                 <td class="nurse">
                <?php 
                	$nurselist =  $obj_bed->get_nurse_by_assignid($retrieved_data->bed_allotment_id) ;
                	foreach($nurselist as $assign_id)
                	{
						$nurse_data =	get_user_detail_byid($assign_id->child_id);
                		echo $nurse_data['first_name']." ".$nurse_data['last_name'].",";
						
                	}
                ?>
                </td>
				<td class="allotment_time"><?php echo $retrieved_data->allotment_date;?></td>
                <td class="discharge_time"><?php echo $retrieved_data->discharge_time;?></td>
               	<td class="action"> 
               	<a href="?dashboard=user&page=bedallotment&tab=bedassign&action=edit&allotment_id=<?php echo $retrieved_data->bed_allotment_id;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
                <a href="?dashboard=user&page=bedallotment&tab=bedallotlist&action=delete&allotment_id=<?php echo $retrieved_data->bed_allotment_id;?>" class="btn btn-danger" 
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
		<?php }?>
		 <?php 
		 //	$retrieve_class = get_all_data($tablename);	
         if($active_tab=='bedassign'){
		?>
		<div class="tab-pane fade active in"  id="bedallot">
		<?php 
	//This is Dashboard at admin side
	$obj_bed = new Hmgtbedmanage();
	?>
	<script type="text/javascript">
$(document).ready(function() {
	$('#patient_form').validationEngine();
	$('#allotment_date').datepicker({
		  changeMonth: true,
	        changeYear: true,
	        dateFormat: 'yy-mm-dd',
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            $(this).val(month + "/" + year);
	        }
                    
                }); 
	$('#discharge_time').datepicker({
		  changeMonth: true,
	        changeYear: true,
	        dateFormat: 'yy-mm-dd',
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            $(this).val(month + "/" + year);
	        }
                    
                }); 
	 $('#nurse').multiselect();
} );
</script>
     <?php 	
		$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
					$edit=1;
					$result = $obj_bed->get_single_bedallotment($_REQUEST['allotment_id']);
				}?>
		
       <div class="panel-body">
        <form name="patient_form" action="" method="post" class="form-horizontal" id="patient_form">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="allotment_id" value="<?php if(isset($_REQUEST['allotment_id'])) echo $_REQUEST['allotment_id'];?>"  />
		<div class="form-group">
			<label class="col-sm-2 control-label" for="patient_id"><?php _e('Patient','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select name="patient_id" id="patient_id" class="form-control validate[required] ">
					<option value=""><?php _e('Select Patient','hospital_mgt');?></option>
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
				if($edit){ $patient=get_inpatient_status($result->patient_id);
					if(!empty($patient)){
				 $patient_status=$patient->patient_status;}}elseif(isset($_POST['patient_status'])){$patient_status=$_POST['patient_status'];}else{$patient_status=' ';} ?>
				<select name="patient_status" class="form-control validate[required]" >
				<option value=""><?php _e('select Patient Status','hospital_mgt');?></option>
				<?php foreach(admit_reason() as $reason)
				{?>
					<option value="<?php echo $reason;?>" <?php selected($patient_status,$reason);?>><?php echo $reason;?></option>
				<?php }?>				
				</select>				
			</div>	
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="bed_type_id"><?php _e('Select Bed Type','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
			<?php if(isset($_REQUEST['bed_type_id']))
					$bed_type1 = $_REQUEST['bed_type_id'];
				elseif($edit)
					$bed_type1 = $result->bed_type_id;
				else 
					$bed_type1 = "";
				?>
				<select name="bed_type_id" class="form-control validate[required]" id="bed_type_id">
				<option value = ""><?php _e('Bed type','hospital_mgt');?></option>
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
				<select name="bed_number" class="form-control validate[required]" id="bednumber">
				<option value=""><?php _e('Select Bed Number','hospital_mgt');?></option>
				<?php 
				if($edit)
				{
					
					$bedtype_data = $obj_bed->get_bed_by_bedtype($result->bed_type_id);
					if(!empty($bedtype_data))
					{
						foreach ($bedtype_data as $retrieved_data)
						{
							echo '<option value="'.$retrieved_data->bed_id.'" '.selected($result->bed_number,$retrieved_data->bed_id).'>'.$retrieved_data->bed_number.'</option>';
						}
					}
				}
				?>
				</select>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="allotment_date"><?php _e('Allotment Date','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="allotment_date" class="form-control validate[required]" type="text"  value="<?php if($edit){ echo $result->allotment_date;}elseif(isset($_POST['allotment_date'])) echo $_POST['allotment_date'];?>" name="allotment_date">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="discharge_time"><?php _e('Discharge Date','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="discharge_time" class="form-control validate[required]" type="text"  value="<?php if($edit){ echo $result->discharge_time;}elseif(isset($_POST['discharge_time'])) echo $_POST['discharge_time'];?>" name="discharge_time">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="doctor"><?php _e('Select Nurse','hospital_mgt');?></label>
			<div class="col-sm-8">
			<?php $allnurse = hmgt_getuser_by_user_role('nurse');
					$nurse_data = array();
					if($edit)
					{
						
						$nurse_list = $obj_bed->get_nurse_by_bedallotment_id($_REQUEST['allotment_id']);
						
						foreach($nurse_list as $assign_id)
						{
							$nurse_data[]=$assign_id->child_id;
						
						}
					}
					elseif(isset($_REQUEST['doctor']))
					{
						$nurse_list = $_REQUEST['doctor'];
						foreach($nurse_list as $assign_id)
						{
							$nurse_data[]=$assign_id;
						
						}
					}
					
					?>
				<select name="nurse[]" class="form-control" multiple="multiple" id="nurse">
				
				<?php 
					
					
					if(!empty($allnurse))
					{
					foreach($allnurse as $nurse)
					{
						$selected = "";
						if(in_array($nurse['id'],$nurse_data))
							$selected = "selected";
						echo '<option value='.$nurse['id'].' '.$selected.'>'.$nurse['first_name'].'</option>';
					}
					}
					?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="middle_name"><?php _e('Description','hospital_mgt');?></label>
			<div class="col-sm-8">
				<textarea class="form-control" name="allotment_description" id="allotment_description"><?php if($edit){ echo $result->allotment_description;}elseif(isset($_POST['allotment_description'])) echo $_POST['allotment_description'];?></textarea>
				
			</div>
		</div>
		<div class="col-sm-offset-2 col-sm-8">
        	<input type="submit" value="<?php if($edit){ _e('Submit','hospital_mgt'); }else{ _e('Submit','hospital_mgt');}?>" name="bedallotment" class="btn btn-success"/>
        </div>
        </form>
        </div>
    
		</div>
		<?php }?>
	</div>
</div>
<?php ?>