<?php 	
if(isset($_POST['save_setting']))
{
	
	$optionval=hmgt_option();
	foreach($optionval as $key=>$val)
	{
		
		if(isset($_POST[$key]))
		{
			$result=update_option( $key, $_POST[$key] );
			
		}
	}
	
	if(isset($result))
	{?>
					<div id="message" class="updated below-h2">
						<p><?php _e('Record updated successfully','hospital_mgt');?></p>
					</div>
		<?php 
	}
}

?>
<script type="text/javascript">

$(document).ready(function() {
	//alert("hello");
	 $('#setting_form').validationEngine();
} );
</script>
<div class="page-inner" style="min-height:1631px !important">
<div class="page-title">


		<h3><img src="<?php echo get_option( 'hmgt_hospital_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'hmgt_hospital_name' );?></h3>
	</div>
	<div id="main-wrapper">
	<div class="panel panel-white">
					<div class="panel-body">
<h2>	
		
        	<?php  echo esc_html( __( 'General Settings', 'hospital_mgt')); ?>
        </h2>
		<div class="panel-body">
        <form name="student_form" action="" method="post" class="form-horizontal" id="setting_form">
        <div class="form-group">
			<label class="col-sm-2 control-label" for="hmgt_hospital_name"><?php _e('Hospital Name','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="hmgt_hospital_name" class="form-control validate[required]" type="text" value="<?php echo get_option( 'hmgt_hospital_name' );?>"  name="hmgt_hospital_name">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="hmgt_staring_year"><?php _e('Starting Year','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="hmgt_staring_year" class="form-control" type="text" value="<?php echo get_option( 'hmgt_staring_year' );?>"  name="hmgt_staring_year">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="hmgt_hospital_address"><?php _e('Hospital Address','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="hmgt_hospital_address" class="form-control validate[required]" type="text" value="<?php echo get_option( 'hmgt_hospital_address' );?>"  name="hmgt_hospital_address">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="hmgt_contact_number"><?php _e('Official Phone Number','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="hmgt_contact_number" class="form-control validate[required]" type="text" value="<?php echo get_option( 'hmgt_contact_number' );?>"  name="hmgt_contact_number">
			</div>
		</div>
		<div class="form-group" class="form-control" id="">
			<label class="col-sm-2 control-label" for="hmgt_contry"><?php _e('Country','hospital_mgt');?></label>
			<div class="col-sm-8">
			<!--  <input  class="form-control" type="text" value="<?php echo get_option( 'hmgt_contry' );?>" 
			name="hmgt_contry">-->
			
			<?php 
			
			$url = plugins_url( 'countrylist.xml', __FILE__ );
			//$xml=simplexml_load_file(plugins_url( 'countrylist.xml', __FILE__ )) or die("Error: Cannot create object");
			//var_dump($xml);
			//$xml->country
			
			if(hmgt_get_remote_file($url))
			{
				$xml =simplexml_load_string(hmgt_get_remote_file($url));
				//var_dump($xml);
			}
			else
				die("Error: Cannot create object");
			?>
			 <select name="hmgt_contry" class="form-control validate[required]" id="smgt_contry">
                        	<option value=""><?php _e('Select Country','school-mgt');?></option>
                            <?php
								foreach($xml as $country)
								{  
								?>
								 <option value="<?php echo $country->name;?>" <?php selected(get_option( 'hmgt_contry' ), $country->name);  ?>><?php echo $country->name;?></option>
							<?php }?>
                        </select> 
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="hmgt_email"><?php _e('Email','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="hmgt_email" class="form-control validate[required,custom[email]] text-input" type="text" value="<?php echo get_option( 'hmgt_email' );?>"  name="hmgt_email">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="hmgt_email"><?php _e('Doctor Can Only View His Patient Not Others','hospital_mgt');?></label>
			<div class="col-sm-8">
				<?php $viewallpatient = get_option('hmgt_viewall_patient'); ?>
				<label class="radio-inline">
			     <input type="radio" value="yes" class="tog validate[required]" name="hmgt_viewall_patient"  <?php  checked( 'yes', $viewallpatient);  ?>/><?php _e('Yes','hospital_mgt');?>
			    </label>
			    <label class="radio-inline">
			      <input type="radio" value="no" class="tog validate[required]" name="hmgt_viewall_patient"  <?php  checked( 'no', $viewallpatient);  ?>/><?php _e('No','hospital_mgt');?> 
			    </label>
			
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="hmgt_email"><?php _e('Hospital Logo','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
			<input type="text" id="hmgt_user_avatar_url" name="hmgt_hospital_logo" class="validate[required]" value="<?php  echo get_option( 'hmgt_hospital_logo' ); ?>" />
       				 <input id="upload_user_avatar_button" type="button" class="button" value="<?php _e( 'Upload image', 'hospital_mgt' ); ?>" />
       				 <span class="description"><?php _e('Upload image.', 'hospital_mgt' ); ?></span>
                     
                     <div id="upload_user_avatar_preview" style="min-height: 100px;">
			<img style="max-width:100%;" src="<?php  echo get_option( 'hmgt_hospital_logo' ); ?>" />
			
				
			</div>
		</div>
		</div>
			<div class="form-group">
			<label class="col-sm-2 control-label" for="hmgt_cover_image"><?php _e('Profile Cover Image','hospital_mgt');?></label>
			<div class="col-sm-8">
			
			<input type="text" id="hmgt_hospital_background_image" name="hmgt_hospital_background_image" value="<?php  echo get_option( 'hmgt_hospital_background_image' ); ?>" />	
       				  <input id="upload_image_button" type="button" class="button upload_user_cover_button" value="<?php _e( 'Upload Cover Image', 'hospital_mgt' ); ?>" />
       				 <span class="description"><?php _e('Upload Cover Image', 'hospital_mgt' ); ?></span>
                     
                     <div id="upload_hospital_cover_preview" style="min-height: 100px;">
			<img style="max-width:100%;" src="<?php  echo get_option( 'hmgt_hospital_background_image' ); ?>" />
			
				
			</div>
		</div>
		</div>

		<div class="col-sm-offset-2 col-sm-8">
        	
        	<input type="submit" value="<?php _e('Save', 'hospital_mgt' ); ?>" name="save_setting" class="btn btn-success"/>
        </div>
        
        
        </form>
		</div>
        </div>
        </div>
        </div>
        </div>
 <?php

?> 