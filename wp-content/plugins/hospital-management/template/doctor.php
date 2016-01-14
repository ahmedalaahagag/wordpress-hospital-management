<?php
$obj_users = new Hmgtuser();
if(isset($_REQUEST['save_doctor']))
{

	if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
	{

		$result = $obj_users->hmgt_add_user($_POST);
		if($result)
		{
			if($_REQUEST['action'] == 'edit')
			{
				wp_redirect ( home_url().'?dashboard=user&page=doctor&tab=doctorslist&message=2');
			 }
			else
			{
			wp_redirect ( home_url().'?dashboard=user&page=doctor&tab=doctorslist&message=1');
			}


		}
	}
}
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
	$result = $obj_session->delete_session($_REQUEST['duration_id']);
	if($result)
	{
			wp_redirect ( home_url().'?dashboard=user&page=doctor&tab=doctorslist&message=3');
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
$active_tab = isset($_GET['tab'])?$_GET['tab']:'doctorslist';
?>
<script type="text/javascript">
$(document).ready(function() {
	$('#session_form').validationEngine();

} );
</script>

<div class="panel-body panel-white">
 <ul class="nav nav-tabs panel_tabs" role="tablist">
      <li class="<?php if($active_tab == 'doctorslist'){?>active<?php }?>">
          <a href="?dashboard=user&page=doctor&tab=doctorslist">
             <i class="fa fa-align-justify"></i> <?php _e('Doctors List', 'hospital_mgt'); ?></a>
          </a>
      </li>
	  <li class="<?php if($active_tab=='adddoctor'){?>active<?php }?>">
		  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['doctor_id']))
			{?>
			<a href="?dashboard=user&page=doctor&tab=adddoctor=edit&doctor_id=<?php if(isset($_REQUEST['doctor_id'])) echo $_REQUEST['doctor_id'];?>"" class="tab <?php echo $active_tab == 'adddoctor' ? 'active' : ''; ?>">
             <i class="fa fa"></i> <?php _e('Edit doctor', 'hospital_mgt'); ?></a>
			 <?php }
			else
			{?>
				<a href="?dashboard=user&page=doctor&tab=adddoctor" class="tab <?php echo $active_tab == 'adddoctor' ? 'active' : ''; ?>">
				<i class="fa fa-plus-circle"></i> <?php _e('Add Doctor', 'hospital_mgt'); ?></a>
	  <?php } ?>

	</li>

</ul>
	<div class="tab-content">
	<?php if($active_tab == 'doctorslist'){?>

    	 <div class="tab-pane fade active in"  id="eventlist">
         <?php
		 //	$retrieve_class = get_all_data($tablename);
		?>
		<div class="panel-body">
        <div class="table-responsive">
        <table id="hmgt_session" class="display dataTable " cellspacing="0" width="100%">
        	<thead>
            <tr>
			<th><?php _e( 'Doctor Name', 'hospital_mgt' ) ;?></th>
			<th><?php _e( 'Doctor Email', 'hospital_mgt' ) ;?></th>
               <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
             </thead>
		<tfoot>
            <tr>
			<th><?php _e( 'Doctor Name', 'hospital_mgt' ) ;?></th>
			<th><?php _e( 'Doctor Email', 'hospital_mgt' ) ;?></th>
            <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </tfoot>

        <tbody>
         <?php
		$doctor_data=$obj_users->get_user_by_type('doctor');
		 if(!empty($doctor_data))
		 {
		 	foreach ($doctor_data as $retrieved_data){
		 	$retrieved_data = (object) $retrieved_data;
		 ?>
            <tr>
				<td class="docotor_name"><?php echo $retrieved_data->doctor_name;?></td>
                <td class="docotor_mobile"><?php echo $retrieved_data->user_email;?></td>
               	<td class="action">
               	<a href="?dashboard=user&page=doctor&tab=adddoctor&action=edit&docotor_id=<?php echo $retrieved_data->ID;?>" class="btn btn-info">
               	<?php _e('Edit', 'hospital_mgt' ) ;?></a>
                <a href="?dashboard=user&page=doctor&tab=doctorslist&action=delete&docotor_id=<?php echo $retrieved_data->ID;?>" class="btn btn-danger"
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
	 if($active_tab == 'adddoctor'){

		 $edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit = 1;
			$doctor_id = $_REQUEST['docotor_id'];
		    $user_info = get_userdata($doctor_id);
		}
?>

	     <div class="panel-body">
      <?php
	//This is Dashboard at admin side
	$user_object=new Hmgtuser();
	$doctor_id=0;
	if(isset($_REQUEST['docotor_id']))
		$doctor_id=$_REQUEST['docotor_id'];
	$role='doctor';
	?>
	<script type="text/javascript">
$(document).ready(function() {
	$('#doctor_form').validationEngine();
	$('#birth_date').datepicker({
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            $(this).val(month + "/" + year);
	        }

                });
} );
</script>
     <?php
	if($active_tab == 'adddoctor')
	 {
        	$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
					$edit=1;
					$user_info = get_userdata($doctor_id);
				}?>
       <div class="panel-body">
        <form name="doctor_form" action="" method="post" class="form-horizontal" id="doctor_form" enctype="multipart/form-data">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="role" value="<?php echo $role;?>"  />
		<input type="hidden" name="user_id" value="<?php echo $doctor_id;?>"  />
		<div class="form-group">
			<label class="col-sm-2 control-label" for="first_name"><?php _e('First Name','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="first_name" class="form-control validate[required,custom[onlyLetterSp]] text-input" type="text" value="<?php if($edit){ echo $user_info->first_name;}elseif(isset($_POST['first_name'])) echo $_POST['first_name'];?>" name="first_name">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="middle_name"><?php _e('Middle Name','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="middle_name" class="form-control validate[custom[onlyLetterSp]] text-input" type="text"  value="<?php if($edit){ echo $user_info->middle_name;}elseif(isset($_POST['middle_name'])) echo $_POST['middle_name'];?>" name="middle_name">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="last_name"><?php _e('Last Name','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="last_name" class="form-control validate[required,custom[onlyLetterSp]] text-input" type="text"  value="<?php if($edit){ echo $user_info->last_name;}elseif(isset($_POST['last_name'])) echo $_POST['last_name'];?>" name="last_name">
			</div>
		</div>
		<div class="form-group" style="display: none;">
			<label class="col-sm-2 control-label" for="gender"><?php _e('Gender','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
			<?php $genderval = "male"; if($edit){ $genderval=$user_info->gender; }elseif(isset($_POST['gender'])) {$genderval=$_POST['gender'];}?>
				<label class="radio-inline">
			     <input type="radio" value="male" class="tog validate[required]" name="gender"  <?php  checked( 'male', $genderval);  ?>/><?php _e('Male','hospital_mgt');?>
			    </label>
			    <label class="radio-inline">
			      <input type="radio" value="female" class="tog validate[required]" name="gender"  <?php  checked( 'female', $genderval);  ?>/><?php _e('Female','hospital_mgt');?>
			    </label>
			</div>
		</div>
		<div class="form-group" style="display: none;">
			<label class="col-sm-2 control-label" for="birth_date"><?php _e('Date of birth','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="birth_date" class="form-control validate[required]" type="text"  name="birth_date"
				value="<?php if($edit){ echo $user_info->birth_date;}elseif(isset($_POST['birth_date'])) echo $_POST['birth_date'];?>">
			</div>
		</div>
		<div class="form-group" style="display: none;">
			<label class="col-sm-2 control-label" for="department"><?php _e('Department','hospital_mgt');?></label>
			<div class="col-sm-8">
			<?php if($edit){ $departmentid=$user_info->department; }elseif(isset($_POST['department'])){$departmentid=$_POST['department'];}else{$departmentid='';}?>
				<select name="department" class="form-control" id="category_data">
				<option><?php _e('select Department','hospital_mgt');?></option>
				<?php

					$department_array = $user_object->get_staff_department();
					 if(!empty($department_array))
					 {
						foreach ($department_array as $retrieved_data){?>
							<option value="<?php echo $retrieved_data->ID; ?>" <?php selected($departmentid,$retrieved_data->ID);?>><?php echo $retrieved_data->post_title;?></option>
						<?php }
					 }
		?>
				</select>
			</div>
			<div class="col-sm-2"><button id="addremove" model="department"><?php _e('Add Or Remove','hospital_mgt');?></button></div>
		</div>
		<div class="form-group" style="display: none;">
			<label class="col-sm-2 control-label" for="birth_date"><?php _e('Specialization','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<?php if($edit){ $specializeid= $user_info->specialization; }elseif(isset($_POST['specialization'])){$specializeid=$_POST['specialization'];}else{$specializeid='';}?>

				<select class="form-control validate[required]"
				id="specialization" name="specialization" >
					<option value="0" selected="selected"></option>
					<option><?php _e('Select Specialization','hospital_mgt');?></option>
					<?php

					$specialize_array = $user_object->get_doctor_specilize();
					 if(!empty($specialize_array))
					 {
						foreach ($specialize_array as $retrieved_data){?>
							<option value="<?php echo $retrieved_data->ID; ?>" <?php selected($specializeid,$retrieved_data->ID);?>><?php echo $retrieved_data->post_title;?></option>
						<?php }
					 }?>
					</select>
			</div>
			<div class="col-sm-2"><button id="addremove" model="specialization"><?php _e('Add Or Remove','hospital_mgt');?></button></div>
		</div>
		<div class="form-group" >
			<label class="col-sm-2 control-label" for="birth_date"><?php _e('Degree','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="doc_degree" class="form-control validate[required]" type="text"  name="doc_degree"
				value="<?php if($edit){ echo $user_info->doctor_degree;}elseif(isset($_POST['doc_degree'])) echo $_POST['doc_degree'];?>">
			</div>
		</div>
		<div class="form-group" style="display: none;">
			<label class="col-sm-2 control-label" for="visiting_fees"><?php _e('Home Visiting Charge','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="doc_degree" class="form-control" type="text"  name="visiting_fees"
				value="<?php if($edit){ echo $user_info->visiting_fees;}elseif(isset($_POST['visiting_fees'])) echo $_POST['visiting_fees'];?>">
			</div>
		</div>
		<div class="form-group" >
			<label class="col-sm-2 control-label" for="address"><?php _e('Home Town Address','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="address" class="form-control validate[required]" type="text"  name="address"
				value="<?php if($edit){ echo $user_info->address;}elseif(isset($_POST['address'])) echo $_POST['address'];?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="home_city_name"><?php _e('City','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="city_name" class="form-control validate[required]" type="text"  name="home_city_name"
				value="<?php if($edit){ echo $user_info->home_city;}elseif(isset($_POST['home_city_name'])) echo $_POST['home_city_name'];?>">
			</div>
		</div>
		<div class="form-group" style="display: none;">
			<label class="col-sm-2 control-label" for="home_state_name"><?php _e('State','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="home_state_name" class="form-control" type="text"  name="home_state_name"
				value="<?php if($edit){ echo $user_info->home_state;}elseif(isset($_POST['home_state_name'])) echo $_POST['home_state_name'];?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="home_country_name"><?php _e('Country','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="home_country_name" class="form-control" type="text"  name="home_country_name"
				value="<?php if($edit){ echo $user_info->home_country;}elseif(isset($_POST['home_country_name'])) echo $_POST['home_country_name'];?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="address"><?php _e('Office Address','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="address" class="form-control validate[required]" type="text"  name="office_address"
				value="<?php if($edit){ echo $user_info->office_address;}elseif(isset($_POST['office_address'])) echo $_POST['office_address'];?>">
			</div>
		</div>
		<div class="form-group" style="display: none;">
			<label class="col-sm-2 control-label" for="city_name"><?php _e('City','school');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="city_name" class="form-control validate[required]" type="text"  name="city_name"
				value="<?php if($edit){ echo $user_info->city_name;}elseif(isset($_POST['city_name'])) echo $_POST['city_name'];?>">
			</div>
		</div>
		<div class="form-group" style="display: none;">
			<label class="col-sm-2 control-label" for="state_name"><?php _e('State','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="state_name" class="form-control" type="text"  name="state_name"
				value="<?php if($edit){ echo $user_info->state_name;}elseif(isset($_POST['state_name'])) echo $_POST['state_name'];?>">
			</div>
		</div>
		<div class="form-group" style="display: none;">
			<label class="col-sm-2 control-label" for="country_name"><?php _e('Country','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="country_name" class="form-control" type="text"  name="country_name"
				value="<?php if($edit){ echo $user_info->country_name;}elseif(isset($_POST['country_name'])) echo $_POST['country_name'];?>">
			</div>
		</div>
		<div class="form-group" style="display: none;">
			<label class="col-sm-2 control-label" for="zip_code"><?php _e('Zip Code','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="zip_code" class="form-control  validate[required]" type="text"  name="zip_code"
				value="<?php if($edit){ echo '111111';}elseif(isset($_POST['zip_code'])) echo '111111';?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label " for="mobile"><?php _e('Mobile Number','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-1">
			<input type="text" readonly value="+<?php echo hmgt_get_countery_phonecode(get_option( 'hmgt_contry' ));?>"  class="form-control" name="phonecode">
			</div>
			<div class="col-sm-7">
				<input id="mobile" class="form-control validate[required,custom[phone]] text-input" type="text"  name="mobile" maxlength="10"
				value="<?php if($edit){ echo $user_info->mobile;}elseif(isset($_POST['mobile'])) echo $_POST['mobile'];?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label " for="phone"><?php _e('Phone','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="phone" class="form-control validate[,custom[phone]] text-input" type="text"  name="phone"
				value="<?php if($edit){ echo $user_info->phone;}elseif(isset($_POST['phone'])) echo $_POST['phone'];?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label " for="email"><?php _e('Email','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="email" class="form-control validate[required,custom[email]] text-input" type="text"  name="email"
				value="<?php if($edit){ echo $user_info->user_email;}elseif(isset($_POST['email'])) echo $_POST['email'];?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="username"><?php _e('User Name','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="username" class="form-control validate[required]" type="text"  name="username"
				value="<?php if($edit){ echo $user_info->user_login;}elseif(isset($_POST['username'])) echo $_POST['username'];?>" <?php if($edit) echo "readonly";?>>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="password"><?php _e('Password','hospital_mgt');?><?php if(!$edit) {?><span class="require-field">*</span><?php }?></label>
			<div class="col-sm-8">
				<input id="password" class="form-control <?php if(!$edit) echo 'validate[required]';?>" type="password"  name="password" value="">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="photo"><?php _e('Image','hospital_mgt');?></label>
			<div class="col-sm-2">
				<input type="text" id="hmgt_user_avatar_url" class="form-control" name="hmgt_user_avatar"
				value="<?php if($edit)echo esc_url( $user_info->hmgt_user_avatar );elseif(isset($_POST['hmgt_user_avatar'])) echo $_POST['hmgt_user_avatar']; ?>" />
			</div>
			<div class="col-sm-3">
       				 <input id="upload_user_avatar_button" type="button" class="button" value="<?php _e( 'Upload image', 'hospital_mgt' ); ?>" />
       				 <span class="description"><?php _e('Upload image', 'hospital_mgt' ); ?></span>
       		</div>
			<div class="clearfix"></div>

			<div class="col-sm-offset-2 col-sm-8">
                     <div id="upload_user_avatar_preview" >
	                     <?php if($edit)
	                     	{
	                     	if($user_info->hmgt_user_avatar == "")
	                     	{?>
	                     	<img alt="" src="<?php echo get_option( 'hmgt_doctor_thumb' ); ?>">
	                     	<?php }
	                     	else {
	                     		?>
					        <img style="max-width:100%;" src="<?php if($edit)echo esc_url( $user_info->hmgt_user_avatar ); ?>" />
					        <?php
	                     	}
	                     	}
					        else {
					        	?>
					        	<img alt="" src="<?php echo get_option( 'hmgt_doctor_thumb' ); ?>">
					        	<?php
					        }?>
    				</div>
   		 </div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="document"><?php _e('Curriculum Vitae','hospital_mgt');?></label>
			<div class="col-sm-6">
				<input type="file" class="form-control file" name="doctor_cv" >
				<input type="hidden" name="hidden_cv" value="<?php if($edit){ echo $user_info->doctor_cv;}elseif(isset($_POST['doctor_cv'])) echo $_POST['doctor_cv'];?>">
				<p class="help-block"><?php _e('Upload document in PDF','school-mgt');?></p>
			</div>
			<div class="col-sm-2">
				<?php if(isset($user_info->doctor_cv) && $user_info->doctor_cv!=""){?>
				<a href="<?php echo content_url().'/uploads/hospital_assets/'.$user_info->doctor_cv;?>" class="btn btn-default"><i class="fa fa-download"></i> <?php _e('Curriculum Vitae','hospital_mgt');?></a>
				<?php } ?>

			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="document"><?php _e('Education Certificate','hospital_mgt');?></label>
			<div class="col-sm-6">
				<input type="file" class="form-control file" name="education_certificate">
				<input type="hidden" name="hidden_education_certificate" value="<?php if($edit){ echo $user_info->edu_certificate;}elseif(isset($_POST['education_certificate'])) echo $_POST['education_certificate'];?>">
				<p class="help-block"><?php _e('Upload document in PDF','hospital_mgt');?></p>
			</div>
			<div class="col-sm-2">
				<?php if(isset($user_info->edu_certificate) && $user_info->edu_certificate!=""){?>
				<a href="<?php echo content_url().'/uploads/hospital_assets/'.$user_info->edu_certificate;?>" class="btn btn-default"><i class="fa fa-download"></i> <?php _e('Education Certificate','hospital_mgt');?></a>
				<?php } ?>

			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="document"><?php _e('Experience Certificate','hospital_mgt');?></label>
			<div class="col-sm-6">
				<input type="file" class="form-control file" name="experience_cert" >
				<input type="hidden" name="hidden_exp_certificate" value="<?php if($edit){ echo $user_info->exp_certificate;}elseif(isset($_POST['exp_certificate'])) echo $_POST['exp_certificate'];?>">
				<p class="help-block"><?php _e('Upload document in PDF','hospital_mgt');?></p>
			</div>
			<div class="col-sm-2">
				<?php if(isset($user_info->exp_certificate) && $user_info->exp_certificate!=""){?>
				<a href="<?php echo content_url().'/uploads/hospital_assets/'.$user_info->exp_certificate;?>" class="btn btn-default"><i class="fa fa-download"></i> <?php _e('Experience Certificate','hospital_mgt');?></a>
				<?php } ?>

			</div>
		</div>
		<div class="col-sm-offset-2 col-sm-8">
        	<input type="submit" value="<?php if($edit){ _e('Save Doctor','hospital_mgt'); }else{ _e('Add Doctor','hospital_mgt');}?>" name="save_doctor" class="btn btn-success"/>
        </div>
        </form>
</div>
<?php
	 }
?>
        </div>
	<script>

		function add_entry()
		{
            var blank_duration_entry = $('#duration_entry').last().clone();
            blank_duration_entry.find('input').val('').html();
            $("#duration_entry").last().after(blank_duration_entry);
			//alert("hellooo");
		}

		function deleteParentElement(n){
			n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
		}
	</script>

</div>
<?php } ?>