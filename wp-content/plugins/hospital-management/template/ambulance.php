<?php 
$obj_ambulance = new Hmgt_ambulance();
if(isset($_REQUEST['save_ambulance']))
{

	if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
	{

		$result = $obj_ambulance->hmgt_add_ambulance($_POST);
		if($result)
		{
			if($_REQUEST['action'] == 'edit')
			{
				?><div id="message" class="updated below-h2"><?php
				_e("Record updated successfully",'hospital_mgt');
				?>
				</div>
			<?php }
			else 
			{?>
			<div id="message" class="updated below-h2">
			<?php 
				_e('Record inserted successfully','hospital_mgt');
			?></div><?php }
			
			
		}
	}
}
if(isset($_REQUEST['save_ambulance_request']))
{

	if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
	{

		$result = $obj_ambulance->hmgt_add_ambulance_request($_POST);
		if($result)
		{
			if($_REQUEST['action'] == 'edit')
			{
				wp_redirect ( home_url() . '?dashboard=user&page=ambulance&tab=ambulance_req_list&message=2');
			}
			else 
			{
				wp_redirect ( home_url() . '?dashboard=user&page=ambulance&tab=ambulance_req_list&message=1');
			}
			
			
		}
	}
}
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
	if($_GET['page'] == 'ambulance')
	{
		$result = $obj_ambulance->delete_ambulance_req($_REQUEST['amb_req_id']);
	}
	
	if($result)
	{
			wp_redirect ( home_url() . '?dashboard=user&page=ambulance&tab=ambulance_req_list&message=3');
	}
}

$edit=0;
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
		
	$edit=1;
	$result= $obj_ambulance->get_single_ambulance_req($_REQUEST['amb_req_id']);
		
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

$active_tab = isset($_GET['tab'])?$_GET['tab']:'ambulance_req_list';
?>
<script type="text/javascript">
$(document).ready(function() {
	
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
<div class="panel-body panel-white">
 <ul class="nav nav-tabs panel_tabs" role="tablist">
      <li class="<?php if($active_tab == 'ambulance_req_list'){?>active<?php }?>">
          <a href="?dashboard=user&page=ambulance&tab=ambulance_req_list">
             <i class="fa fa-align-justify"></i> <?php _e('Request List', 'hospital_mgt'); ?></a>
          </a>
      </li>
      <li class="<?php if($active_tab == 'add_ambulance_req'){?>active<?php }?>">
      <a href="?dashboard=user&page=ambulance&tab=add_ambulance_req">
        <i class="fa fa-plus-circle"></i> 
        <?php 
        if(isset($_REQUEST['action']) && $_REQUEST['action'] =='edit')
        	 _e('Edit Request', 'hospital_mgt'); 
        else 
        _e('Add Request', 'hospital_mgt'); 
        ?></a> 
      </li>
</ul>
<script type="text/javascript">
$(document).ready(function() {
	jQuery('#ambulance_list').DataTable({
		 "order": [[ 2, "Desc" ]],
		 "aoColumns":[
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
	<div class="tab-content">
	<?php if($active_tab == 'ambulance_req_list'){?>
    	 <div class="tab-pane fade active in" id="prescription">
         <?php 
		 //	$retrieve_class = get_all_data($tablename);		
		?>
		<div class="panel-body">
         <div class="table-responsive">
        <table id="ambulance_list" class="display dataTable" cellspacing="0" width="100%">
        	 <thead>
            <tr>
			<th><?php _e( 'Ambulance', 'hospital_mgt' ) ;?></th>
			   <th><?php _e( 'Patient', 'hospital_mgt' ) ;?></th>
				<th><?php _e( 'Date', 'hospital_mgt' ) ;?></th>	
				<th><?php _e( 'Time', 'hospital_mgt' ) ;?></th>
				<th><?php _e( 'Dispatch Time', 'hospital_mgt' ) ;?></th>
				
				<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </thead>
		<tfoot>
            <tr>
				<th><?php _e( 'Ambulance', 'hospital_mgt' ) ;?></th>
			    <th><?php _e( 'Patient', 'hospital_mgt' ) ;?></th>
				<th><?php _e( 'Date', 'hospital_mgt' ) ;?></th>	
				<th><?php _e( 'Time', 'hospital_mgt' ) ;?></th>
				<th><?php _e( 'Dispatch Time', 'hospital_mgt' ) ;?></th>
				<th><?php _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </tfoot>
 
        <tbody>
         <?php 
		$ambulancereq_data=$obj_ambulance->get_all_ambulance_request();
		 if(!empty($ambulancereq_data))
		 {
		 	foreach ($ambulancereq_data as $retrieved_data){ 
		 		$patient_data =	get_user_detail_byid($retrieved_data->patient_id);?>
            <tr>
				<td class="ambulanceid"><?php echo $obj_ambulance->get_ambulance_id($retrieved_data->ambulance_id);?></td>
                <td class="patient"><?php echo $patient_data['first_name']." ".$patient_data['last_name']."(".$patient_data['patient_id'].")";?></td>
                <td class="date"><?php echo $retrieved_data->request_date;?></td>
				<td class="time"><?php echo $retrieved_data->request_time;?></td>
                <td class="dispatchtime"><?php echo $retrieved_data->dispatch_time;?></td>
               	<td class="action"> 
               	<a href="?dashboard=user&page=ambulance&tab=add_ambulance_req&action=edit&amb_req_id=<?php echo $retrieved_data->amb_req_id;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
                <a href="?dashboard=user&page=ambulance&tab=ambulance_req_list&action=delete&amb_req_id=<?php echo $retrieved_data->amb_req_id;?>" class="btn btn-danger" 
                onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
                <?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>              
               
            </tr>
            <?php } 
			
		}?>
        </tbody>
        
        </table>
        </div>
        </div>
        
		
	</div>
	<?php }
	if($active_tab == 'add_ambulance_req'){
	?>
	
	<div class="tab-pane fade active in" id="add_req">
       <script type="text/javascript">
$(document).ready(function() {
	$('#patient_form').validationEngine();
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
       <div class="panel-body">
         <form name="patient_form" action="" method="post" class="form-horizontal" id="patient_form">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="amb_req_id" value="<?php if(isset($_REQUEST['amb_req_id']))echo $_REQUEST['amb_req_id'];?>"  />
		
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="ambulance_id"><?php _e('Ambulance','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select name="ambulance_id" class="form-control validate[required] " id="ambulance_id">
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
				<input id="request_time" class="form-control request_time" type="text" data-show-meridian="false"  data-default-time="00:15" value="<?php if($edit){ echo $result->request_time;}elseif(isset($_POST['request_time'])) echo $_POST['request_time'];?>" name="request_time">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="dispatch_time"><?php _e('Dispatch Time','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="dispatch_time" class="form-control dispatch_time" data-show-meridian="false" data-minute-step="15"type="text"  value="<?php if($edit){ echo $result->dispatch_time;}elseif(isset($_POST['dispatch_time'])) echo $_POST['dispatch_time'];?>" name="dispatch_time">
			</div>
		</div>
		<div class="col-sm-offset-2 col-sm-8">
        	<input type="submit" value="<?php if($edit){ _e('Save Request','hospital_mgt'); }else{ _e('Add Ambulance Request','hospital_mgt');}?>" name="save_ambulance_request" class="btn btn-success"/>
        </div>
        </form>
        </div>
         
	</div>
	<?php }?>
	</div>
</div>
<?php ?>