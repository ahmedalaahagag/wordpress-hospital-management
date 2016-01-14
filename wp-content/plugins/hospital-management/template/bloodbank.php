<?php 
$obj_bloodbank=new Hmgtbloodbank();
$active_tab =isset($_REQUEST['tab'])?$_REQUEST['tab']:'bloodmanage';
if(isset($_POST['save_blooddonor']))
{
		
		if($_REQUEST['action']=='edit')
		{
			
			$result=$obj_bloodbank->hmgt_add_blood_donor($_POST);
			if($result)
			{
				wp_redirect ( home_url() . '?dashboard=user&page=bloodbank&tab=blooddonorlist&message=2');
			}
			
			
		}
		else
		{
			$result=$obj_bloodbank->hmgt_add_blood_donor($_POST);
				if($result)
				{
					wp_redirect ( home_url() . '?dashboard=user&page=bloodbank&tab=blooddonorlist&message=1');
				}
		}
	
}	?>

<script type="text/javascript">
$(document).ready(function() {
	
	$('#blooddonor_form').validationEngine();
	$('#bloodgroup_form').validationEngine();
	$('#last_donate_date').datepicker({
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
<?php if(isset($_POST['save_bloodgroup']))
{
	
	if($_REQUEST['action']=='edit')
		{
			
			$result=$obj_bloodbank->add_blood_group($_POST);
			if($result)
			{
				wp_redirect ( home_url() . '?dashboard=user&page=bloodbank&tab=bloodmanage&message=2');
			}
			
			
		}
		else
		{
			$result=$obj_bloodbank->add_blood_group($_POST);
				if($result)
				{
					wp_redirect ( home_url() . '?dashboard=user&page=bloodbank&tab=bloodmanage&message=1');
				}
		}
	
}
	
	

	
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
		{
			if(isset($_REQUEST['blooddonor_id']))
			{
				$result=$obj_bloodbank->delete_blooddonor($_REQUEST['blooddonor_id']);
				wp_redirect( site_url () . '/?dashboard=user&page=bloodbank&tab=blooddonorlist&message=success'); 	
			}
			if(isset($_REQUEST['bloodgroup_id']))
			{
				$result=$obj_bloodbank->delete_bloodgroup($_REQUEST['bloodgroup_id']);
				wp_redirect( site_url() . '/?dashboard=user&page=bloodbank&tab=bloodmanage&message=success');
			}	
			
		}
		if(isset($_REQUEST['message'])&& $_REQUEST['message']=='success' ){?>
			<div id="message" class="updated below-h2">
						<p><?php _e('Record deleted successfully','hospital_mgt');?></p>
					</div>
	 
	<?php 	}
	
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
	if($message == 2)
	{?><div id="message" class="updated below-h2 "><p><?php
				_e("Record updated successfully",'hospital_mgt');
				?></p>
				</div>
			<?php 
		
	}
	
			
	}
	
	
	?>
 <?php if($obj_hospital->role == 'nurse'){?>
 <script type="text/javascript">
$(document).ready(function() {
	jQuery('#bloodgroup_list').DataTable({ "aoColumns":[
	                              	                  {"bSortable": true},
	                            	                  {"bSortable": true},	                             
	                            	                  {"bSortable": false}
	                            	               ]});
	jQuery('#blooddonor_list').DataTable();
	
	 
} );
</script>
 <?php } elseif($obj_hospital->role == 'laboratorist'){?>
  <script type="text/javascript">
$(document).ready(function() {
	jQuery('#bloodgroup_list').DataTable({ "aoColumns":[
	                              	                  {"bSortable": true},
	                            	                  {"bSortable": true},	                             
	                            	                  {"bSortable": false}
	                            	               ]});
	jQuery('#blooddonor_list').DataTable({ "aoColumns":[
		                              	                  {"bSortable": true},
		                            	                  {"bSortable": true},
		                            	                  {"bSortable": true},
		                            	                  {"bSortable": true},	 
		                            	                  {"bSortable": true},	                             
		                            	                  {"bSortable": false}
		                            	               ]});
	
	 
} );
</script>
 <?php }else {?>
 <script type="text/javascript">
$(document).ready(function() {
	jQuery('#bloodgroup_list').DataTable();
	jQuery('#blooddonor_list').DataTable();
	
	 
} );
</script>
 <?php }?>

<div class="panel-body panel-white">
 <ul class="nav nav-tabs panel_tabs" role="tablist">
       <li class="<?php if($active_tab=='bloodmanage'){?>active<?php }?>">
			<a href="?dashboard=user&page=bloodbank&tab=bloodmanage" class="tab <?php echo $active_tab == 'bloodmanage' ? 'active' : ''; ?>">
             <i class="fa fa-align-justify"></i> <?php _e('Blood Manage', 'hospital_mgt'); ?></a>
	</li>
	<?php if($obj_hospital->role == 'laboratorist' || $obj_hospital->role == 'nurse'){?>
     <li class="<?php if($active_tab=='addbloodgoup'){?>active<?php }?>">
		  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['bloodgroup_id']))
			{?>
			<a href="?dashboard=user&page=bloodbank&tab=addbloodgoup&action=edit&bloodgroup_id=<?php if(isset($_REQUEST['bloodgroup_id'])) echo $_REQUEST['bloodgroup_id'];?>"" class="tab <?php echo $active_tab == 'addbloodgoup' ? 'active' : ''; ?>">
             <i class="fa fa"></i> <?php _e('Edit Blood Group', 'hospital_mgt'); ?></a>
			 <?php }
			else
			{?>
				<a href="?dashboard=user&page=bloodbank&tab=addbloodgoup" class="tab <?php echo $active_tab == 'addbloodgoup' ? 'active' : ''; ?>">
				<i class="fa fa-plus-circle"></i> <?php _e('Add Blood Group', 'hospital_mgt'); ?></a>
	  <?php } ?>
	  
	</li>
	<?php }?>
	<li class="<?php if($active_tab=='blooddonorlist'){?>active<?php }?>">
			<a href="?dashboard=user&page=bloodbank&tab=blooddonorlist" class="tab <?php echo $active_tab == 'blooddonorlist' ? 'active' : ''; ?>">
             <i class="fa fa-align-justify"></i> <?php _e('Blood Donor List', 'hospital_mgt'); ?></a>
	</li>
	<?php if($obj_hospital->role == 'laboratorist' || $obj_hospital->role == 'nurse'){?>
	<li class="<?php if($active_tab=='addblooddonor'){?>active<?php }?>">
		  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['blooddonor_id']))
			{?>
			<a href="?dashboard=user&page=bloodbank&tab=addblooddonor&action=edit&bloodgroup_id=<?php if(isset($_REQUEST['bloodgroup_id'])) echo $_REQUEST['bloodgroup_id'];?>"" class="tab <?php echo $active_tab == 'addblooddonor' ? 'active' : ''; ?>">
             <i class="fa fa"></i> <?php _e('Edit Blood Donor', 'hospital_mgt'); ?></a>
			 <?php }
			else
			{?>
				<a href="?dashboard=user&page=bloodbank&tab=addblooddonor" class="tab <?php echo $active_tab == 'addblooddonor' ? 'active' : ''; ?>">
				<i class="fa fa-plus-circle"></i> <?php _e('Add Blood Donor', 'hospital_mgt'); ?></a>
	  <?php } ?>
	  
	</li>
	<?php }?>
	
</ul>
	<div class="tab-content">
    	<?php if($active_tab=='bloodmanage'){?>
		<div class="panel-body">
        <div class="table-responsive">
       <table id="bloodgroup_list" class="display dataTable " cellspacing="0" width="100%">
        	<thead>
            <tr>
			<th><?php _e( 'Blood Group', 'hospital_mgt' ) ;?></th>
			   <th><?php _e( 'No of Bags', 'hospital_mgt' ) ;?></th> 
			   <?php if($obj_hospital->role == 'laboratorist' || $obj_hospital->role == 'nurse'){?>
				<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
				<?php }?>
            </tr>
        </thead>
		<tfoot>
            <tr>
			<th><?php _e( 'Blood Group', 'hospital_mgt' ) ;?></th>
			   <th><?php _e( 'No of Bags', 'hospital_mgt' ) ;?></th> 
			   <?php if($obj_hospital->role == 'laboratorist' || $obj_hospital->role == 'nurse'){?>
				<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
				<?php } ?>
            </tr>
        </tfoot>
		<tbody>
         <?php 
			foreach($obj_bloodbank->get_all_bloodgroups() as $retrieved_data){ ?>
            <tr>
				 <td class="blood_group">
				<?php 
						echo $retrieved_data->blood_group;
				?></td>
				<td class="subject_name"><?php  echo $retrieved_data->blood_status;;?></td>
              <?php if($obj_hospital->role == 'laboratorist' || $obj_hospital->role == 'nurse'){?>
               	<td class="action"> 
               	<a href="?dashboard=user&page=bloodbank&tab=addbloodgoup&action=edit&bloodgroup_id=<?php echo $retrieved_data->blood_id;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
                </td>
               <?php } ?>
            </tr>
            <?php } ?>
     
        </tbody>
        
        </table>
 		</div>
		</div>
		<?php } 
			if($active_tab=='addbloodgoup'){
				$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
					
					$edit=1;
					$result = $obj_bloodbank->get_single_bloodgroup($_REQUEST['bloodgroup_id']);	
					
				}?>
		
       <div class="panel-body">
        <form name="bloodgroup_form" action="" method="post" class="form-horizontal" id="bloodgroup_form">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="bloodgroup_id" value="<?php if(isset($_REQUEST['bloodgroup_id'])) echo $_REQUEST['bloodgroup_id'];?>"  />
		<div class="form-group">
			<label class="col-sm-2 control-label" for="bloodgruop"><?php _e('Blood Group','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<?php if($edit){ $userblood=$result->blood_group; }elseif(isset($_POST['blood_group'])){$userblood=$_POST['blood_group'];}else{$userblood='';}?>
				<select id="blood_group" class="form-control validate[required]" name="blood_group">
				<option value = ""><?php _e('Select Blood Group','hospital_mgt');?></option>
				<?php foreach(blood_group() as $blood){ ?>
						<option value="<?php echo $blood;?>" <?php selected($userblood,$blood);  ?>><?php echo $blood; ?> </option>
				<?php } ?>
			</select>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="blood_status"><?php _e('No of Bags','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="blood_status" class="form-control validate[required] text-input" type="text" value="<?php if($edit){ echo $result->blood_status;}elseif(isset($_POST['blood_status'])) echo $_POST['blood_status'];?>" name="blood_status">
			</div>
		</div>
		<div class="col-sm-offset-2 col-sm-8">
        	<input type="submit" value="<?php if($edit){ _e('Save','hospital_mgt'); }else{ _e('Add Blood Group','hospital_mgt');}?>" name="save_bloodgroup" class="btn btn-success"/>
        </div>
        </form>
        </div>
		<?php }
		if($active_tab=='addblooddonor'){
				$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
					
					$edit=1;
					$result = $obj_bloodbank->get_single_blooddonor($_REQUEST['blooddonor_id']);	
					
				}?>
		
       <div class="panel-body">
        <form name="blooddonor_form" action="" method="post" class="form-horizontal" id="blooddonor_form">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="blooddonor_id" value="<?php if(isset($_REQUEST['blooddonor_id'])) echo $_REQUEST['blooddonor_id'];?>"  />
		<div class="form-group">
			<label class="col-sm-2 control-label" for="first_name"><?php _e('Full Name','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="bool_dodnor_name" class="form-control validate[required,custom[onlyLetterSp]] text-input" type="text" value="<?php if($edit){ echo $result->donor_name;}elseif(isset($_POST['bool_dodnor_name'])) echo $_POST['bool_dodnor_name'];?>" name="bool_dodnor_name">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="gender"><?php _e('Gender','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
			<?php $genderval = "male"; if($edit){ $genderval=$result->donor_gender; }elseif(isset($_POST['gender'])) {$genderval=$_POST['gender'];}?>
				<label class="radio-inline">
			     <input type="radio" value="male" class="tog validate[required]" name="gender"  <?php  checked( 'male', $genderval);  ?>/><?php _e('Male','hospital_mgt');?>
			    </label>
			    <label class="radio-inline">
			      <input type="radio" value="female" class="tog validate[required]" name="gender"  <?php  checked( 'female', $genderval);  ?>/><?php _e('Female','hospital_mgt');?> 
			    </label>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="med_category_name"><?php _e('Age','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="dodnor_age" class="form-control validate[required] text-input" type="text" value="<?php if($edit){ echo $result->donor_age;}elseif(isset($_POST['dodnor_age'])) echo $_POST['dodnor_age'];?>" name="dodnor_age">
			</div>
		</div>
		<div class="form-group">	
			<label class="col-sm-2 control-label " for="phone"><?php _e('Phone','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="phone" class="form-control validate[,custom[phone]] text-input" type="text"  name="phone" 
				value="<?php if($edit){ echo $result->donor_phone;}elseif(isset($_POST['phone'])) echo $_POST['phone'];?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label " for="email"><?php _e('Email','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="email" class="form-control validate[required,custom[email]] text-input" type="text"  name="email" 
				value="<?php if($edit){ echo $result->donor_email;}elseif(isset($_POST['email'])) echo $_POST['email'];?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="bloodgruop"><?php _e('Blood Group','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<?php if($edit){ $userblood=$result->blood_group; }elseif(isset($_POST['blood_group'])){$userblood=$_POST['blood_group'];}else{$userblood='';}?>
				<select id="blood_group" class="form-control validate[required]" name="blood_group">
				<option value = ""><?php _e('Select Blood Group','hospital_mgt');?></option>
				<?php foreach(blood_group() as $blood){ ?>
						<option value="<?php echo $blood;?>" <?php selected($userblood,$blood);  ?>><?php echo $blood; ?> </option>
				<?php } ?>
			</select>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="last_donet_date"><?php _e('Last Donation Date','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="last_donate_date" class="form-control " type="text"  value="<?php if($edit){ echo $result->last_donet_date;}elseif(isset($_POST['last_donate_date'])) echo $_POST['last_donate_date'];?>" name="last_donate_date">
			</div>
		</div>
		
		
		<div class="col-sm-offset-2 col-sm-8">
        	<input type="submit" value="<?php if($edit){ _e('Save','hospital_mgt'); }else{ _e('Add Donor','hospital_mgt');}?>" name="save_blooddonor" class="btn btn-success"/>
        </div>
        </form>
        </div>
				
		<?php }
		if($active_tab=='blooddonorlist'){?>
		<div class="panel-body">
		<form name="wcwm_report" action="" method="post">
    
        <div class="panel-body">
        	<div class="table-responsive">
        <table id="blooddonor_list" class="display dataTable" cellspacing="0" width="100%">
        	 <thead>
            <tr>
			<th><?php _e('Name', 'hospital_mgt' ) ;?></th>
			<th><?php _e( 'Blood Group', 'hospital_mgt' ) ;?></th>
			<th><?php _e( 'Age', 'hospital_mgt' ) ;?></th>
			<th><?php _e( 'Gender', 'hospital_mgt' ) ;?></th>
			<th><?php _e( 'Last Donation Date', 'hospital_mgt' ) ;?></th> 
			<?php if($obj_hospital->role == 'laboratorist'){?>
			<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
			<?php }?>
            </tr>
        </thead>
		<tfoot>
            <tr>
			<th><?php _e( 'Name', 'hospital_mgt' ) ;?></th>
			<th><?php _e( 'Blood Group', 'hospital_mgt' ) ;?></th>
			<th><?php _e( 'Age', 'hospital_mgt' ) ;?></th>
			<th><?php _e( 'Gender', 'hospital_mgt' ) ;?></th>
			<th><?php _e( 'Last Donation Date', 'hospital_mgt' ) ;?></th> 
			<?php if($obj_hospital->role == 'laboratorist'){?>
			<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
			<?php }?>
            </tr>
        </tfoot>
 
        <tbody>
         <?php 
		$blooddonordata=$obj_bloodbank->get_all_blooddonors();
		
		 if(!empty($blooddonordata))
		 {
		 	foreach($blooddonordata as $retrieved_data){ 
			
		?>
            <tr>
			
                <td class="name"><a href="#"><?php echo $retrieved_data->donor_name;?></a></td>
                <td class="bloodgroup">
				<?php 
						//$blood=$obj_bloodbank->get_single_bloodgroup($retrieved_data->blood_group);
						echo $retrieved_data->blood_group;
				?></td>
				<td class="age"><?php echo $retrieved_data->donor_age;?></td>
				<td class="age"><?php echo $retrieved_data->donor_gender;?></td>
                <td class="lastdonate_date"><?php echo $retrieved_data->last_donet_date;?></td>
                <?php if($obj_hospital->role == 'laboratorist'){?>
               	<td class="action"> <a href="?dashboard=user&page=bloodbank&tab=addblooddonor&action=edit&blooddonor_id=<?php echo $retrieved_data->bld_donor_id;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
                <a href="?dashboard=user&page=bloodbank&tab=bloodbanklist&action=delete&blooddonor_id=<?php echo $retrieved_data->bld_donor_id;?>" class="btn btn-danger" 
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
       
</form>
     
</div>
		<?php } ?>
		</div>
		
	</div>

<?php ?>