<?php
//Compose mail

?>
<script type="text/javascript">

$(document).ready(function() {
	$('#message_form').validationEngine();
} );
</script>
		<div class="mailbox-content">
		<h2>
        	 	<?php  $edit=0;
			 if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
					{
						 echo esc_html( __( 'Edit Message', 'hospital_mgt') );
						 $edit=1;
						 $exam_data= get_exam_by_id($_REQUEST['exam_id']);
					}
					?>
        </h2>
        <?php
		if(isset($message))
			echo '<div id="message" class="updated below-h2"><p>'.$message.'</p></div>';
		?>
        <form name="message_form" action="" method="post" class="form-horizontal" id="message_form">
          <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="to"><?php _e('Message To','hospital_mgt');?><span class="require-field">*</span></label>
                                            <div class="col-sm-8">
                                                <select name="receiver" class="form-control validate[required] text-input" id="to">
							<option value="patient"><?php _e('All Patient','hospital_mgt');?></option>	
							<option value="doctor"><?php _e('All Doctor','hospital_mgt');?></option>	
							<option value="nurse"><?php _e('All Nurse','hospital_mgt');?></option>	
							<option value="receptionist"><?php _e('All Support Staff','hospital_mgt');?></option>	
							<option value="pharmacist"><?php _e('All Pharmacist','hospital_mgt');?></option>	
							<option value="laboratorist"><?php _e('All Laboratory Staff','hospital_mgt');?></option>	
							<option value="accountant"><?php _e('All Accountant','hospital_mgt');?></option>	
							<?php get_all_user_in_message();?>
						</select>
                                            </div>	
                                        </div>
         <div class="form-group">
                                            <label class="col-sm-2 control-label" for="subject"><?php _e('Subject','hospital_mgt');?><span class="require-field">*</span></label>
                                            <div class="col-sm-8">
                                               <input id="subject" class="form-control validate[required] text-input" type="text" name="subject" >
                                            </div>
                                        </div>
          <div class="form-group">
                                            <label class="col-sm-2 control-label" for="subject"><?php _e('Message Comment','hospital_mgt');?><span class="require-field">*</span></label>
                                            <div class="col-sm-8">
                                              <textarea name="message_body" id="message_body" class="form-control validate[required] text-input"></textarea>
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
           <div class="form-group">
                                            <div class="col-sm-10">
                                            <div class="pull-right">
                                            <input type="submit" value="<?php if($edit){ _e('Save Message','hospital_mgt'); }else{ _e('Send Message','hospital_mgt');}?>" name="save_message" class="btn btn-success"/>
                                            </div>
                                            </div>
                                        </div>
         	
        
        </form>
        
        </div>
<?php

?>