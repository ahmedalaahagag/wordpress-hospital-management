<?php

?>

<script type="text/javascript">
$(document).ready(function() {
	 $('#notice_form').validationEngine();
	  $('.datepicker').datepicker();
} );
</script>
<?php  $edit=0;
			 if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
					{
						$edit=1;
						 $postdata = get_post($_REQUEST['notice_id']);
					}
					?>
       <div class="panel-body"> 
		
	   <form name="class_form" action="" method="post" class="form-horizontal" id="notice_form">
          <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="notice_id"   value="<?php if($edit){ echo $postdata->ID;}?>"/> 
		<div class="form-group">
			<label class="col-sm-2 control-label" for="notice_type"><?php _e('Event/Notice','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select id="notice_type" class="form-control validate[required]" name="notice_type">
					<option><?php _e('Select Type','hospital_mgt');?></option>
					<option value="hmgt_notice" <?php if($edit) selected('hmgt_notice',$postdata->post_type);?>><?php echo _e('Notice','hospital_mgt'); ?></option>
					<option value="hmgt_event" <?php if($edit) selected('hmgt_event',$postdata->post_type);?>><?php echo _e('Event','hospital_mgt'); ?></option>
				</select>
				 
			</div>
		</div>
	   <div class="form-group">
			<label class="col-sm-2 control-label" for="notice_title"><?php _e('Event Title','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="notice_title" class="form-control validate[required] text-input" type="text" value="<?php if($edit){ echo $postdata->post_title;}?>" name="notice_title">
				
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="notice_content"><?php _e('Event Comment','hospital_mgt');?></label>
			<div class="col-sm-8">
			<textarea name="notice_content" class="form-control" id="notice_content"><?php if($edit){ echo $postdata->post_content;}?></textarea>
				
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="notice_content"><?php _e('Event Start Date','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
			<input id="notice_Start_date" class="datepicker form-control validate[required] text-input" type="text" value="<?php if($edit){ echo get_post_meta($postdata->ID,'start_date',true);}?>" name="start_date">
				
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="notice_content"><?php _e('Event End Date','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
			<input id="notice_end_date" class="datepicker form-control validate[required] text-input" type="text" value="<?php if($edit){ echo get_post_meta($postdata->ID,'end_date',true);}?>" name="end_date">
				
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="notice_for"><?php _e('Event For','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
			 <select name="notice_for" id="notice_for" class="form-control validate[required] text-input">
                       <option value = ""><?php _e('Select Role','hospital_mgt');?></option>
                       <?php 
					   if($edit)
					   {
					  	// wp_dropdown_roles( get_post_meta( $postdata->ID, 'notice_for',true));
					   	?>
					   		<option value="all" <?php echo selected(get_post_meta( $postdata->ID, 'notice_for',true),'all')?>><?php _e('All','hospital_mgt');?></option>
					   		<option value="patient" <?php echo selected(get_post_meta( $postdata->ID, 'notice_for',true),'patient')?>><?php _e('Patient','hospital_mgt');?></option>
					   		<option value="doctor" <?php echo selected(get_post_meta( $postdata->ID, 'notice_for',true),'doctor')?>><?php _e('Doctor','hospital_mgt');?></option>	
					   		<option value="receptionist" <?php echo selected(get_post_meta( $postdata->ID, 'notice_for',true),'receptionist')?>><?php _e('Support Staff','hospital_mgt');?></option>	
					   		<option value="pharmacist" <?php echo selected(get_post_meta( $postdata->ID, 'notice_for',true),'pharmacist')?>><?php _e('Pharmacist','hospital_mgt');?></option>	
					   		<option value="laboratorist" <?php echo selected(get_post_meta( $postdata->ID, 'notice_for',true),'laboratorist')?>><?php _e('Laboratory Staff','hospital_mgt');?></option>	
					   		<option value="accountant" <?php echo selected(get_post_meta( $postdata->ID, 'notice_for',true),'accountant')?>><?php _e('Accountant','hospital_mgt');?></option>	
					   <?php }
					   else
					   {
					   	?>
					   		<option value="all"><?php _e('All','hospital_mgt');?></option>
							<option value="patient"><?php _e('Patient','hospital_mgt');?></option>	
							<option value="doctor"><?php _e('Doctor','hospital_mgt');?></option>	
							<option value="receptionist"><?php _e('Support Staff','hospital_mgt');?></option>	
							<option value="pharmacist"><?php _e('Pharmacist','hospital_mgt');?></option>	
							<option value="laboratorist"><?php _e('Laboratory Staff','hospital_mgt');?></option>	
							<option value="accountant"><?php _e('Accountant','hospital_mgt');?></option>	
					   	<?php 
					   }
						  ?>
                       </select>
				
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label " for="enable"><?php _e('Send SMS','hospital_mgt');?></label>
			<div class="col-sm-8">
				 <div class="checkbox">
				 	<label>
  						<input id="chk_sms_sent" type="checkbox" <?php $smgt_sms_service_enable = 0;if($smgt_sms_service_enable) echo "checked";?> value="1" name="hmgt_sms_service_enable">
  					</label>
  				</div>
				 
			</div>
		</div>
		<div id="hmsg_message_sent" class="hmsg_message_none">
		<div class="form-group">
			<label class="col-sm-2 control-label" for="sms_template"><?php _e('SMS Text','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<textarea name="sms_template" class="form-control validate[required]" maxlength="160"></textarea>
				<label><?php _e('Max. 160 Character','hospital_mgt');?></label>
			</div>
		</div>
		</div>
		<div class="col-sm-offset-2 col-sm-8">        	
        	<input type="submit" value="<?php if($edit){ _e('Save Event','hospital_mgt'); }else{ _e('Add Event','hospital_mgt');}?>" name="save_notice" class="btn btn-success" />
        </div>
        
        
        </form>
       </div>
       
<?php

?>