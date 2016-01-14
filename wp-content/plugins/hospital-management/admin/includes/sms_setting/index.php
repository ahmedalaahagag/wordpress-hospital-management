<?php 
$current_sms_service_active =get_option( 'hmgt_sms_service');
?>

<script type="text/javascript">

$(document).ready(function() {
	 $('#sms_setting_form').validationEngine();
} );

</script>

<div class="page-inner" style="min-height:1631px !important">
<div class="page-title">
		<h3><img src="<?php echo get_option( 'hmgt_hospital_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'hmgt_hospital_name' );?></h3>
	</div>
	<?php 


	if(isset($_REQUEST['save_sms_setting']))
	{
	
	
		if(isset($_REQUEST['select_serveice']) && $_REQUEST['select_serveice'] == 'clickatell')
		{
			$custm_sms_service = array();
			$result=get_option( 'hmgt_clickatell_sms_service');
	
			$custm_sms_service['username'] = trim($_REQUEST['username']);
			$custm_sms_service['password'] = $_REQUEST['password'];
			$custm_sms_service['api_key'] = $_REQUEST['api_key'];
	
	
			//print_r($custm_crm_option);
			$result=update_option( 'hmgt_clickatell_sms_service',$custm_sms_service );
	
		}
		if(isset($_REQUEST['select_serveice']) && $_REQUEST['select_serveice'] == 'twillo')
		{
			$custm_sms_service = array();
			$result=get_option( 'hmgt_twillo_sms_service');
			$custm_sms_service['account_sid'] = trim($_REQUEST['account_sid']);
			$custm_sms_service['auth_token'] = trim($_REQUEST['auth_token']);
			$custm_sms_service['from_number'] = $_REQUEST['from_number'];
	
	
	
			//print_r($custm_crm_option);
			$result=update_option( 'hmgt_twillo_sms_service',$custm_sms_service );
	
		}
	
		update_option( 'hmgt_sms_service',$_REQUEST['select_serveice'] );
	
		wp_redirect ( admin_url() . 'admin.php?page=hmgt_sms_setting&message=1');
	}
	if(isset($_REQUEST['message']))
	{
		$message =$_REQUEST['message'];
		if($message == 1)
		{?>
				<div id="message" class="updated below-h2 ">
				<p>
				<?php 
					_e('Record Updated Successfully','hospital_mgt');
				?></p></div>
				<?php 
			
		}
	}
	?>
	<div  id="main-wrapper" class="marks_list">
	<div class="panel panel-white">
	<div class="panel-body">  
 
	<h2 class="nav-tab-wrapper">
    	<a href="?page=hmgt_sms_setting" class="nav-tab  nav-tab-active">
		<?php echo '<span class="dashicons dashicons-awards"></span>'.__('SMS Setting', 'hospital_mgt'); ?></a>
        
    </h2>
    
	<div class="panel-body"> 
    <form action="" method="post" class="form-horizontal" id="sms_setting_form">  
    			<div class="form-group">
			<label class="col-sm-2 control-label " for="enable"><?php _e('Select Message Service','hospital_mgt');?></label>
			<div class="col-sm-8">
				 <div class="radio">
				 	<label>
  						<input id="checkbox" type="radio" <?php echo checked($current_sms_service_active,'clickatell');?>  name="select_serveice" value="clickatell"> <?php _e('Clickatell','hospital_mgt');?> 
  					</label> 
  					&nbsp;&nbsp;&nbsp;&nbsp;
  					<label>
  						<input id="checkbox" type="radio"  <?php echo checked($current_sms_service_active,'twillo');?> name="select_serveice" value="twillo">  <?php _e('Twilio','hospital_mgt');?>
  					</label>
  				</div>
				 
			</div>
		</div>
    	
		<div id="sms_setting_block">
		<?php if($current_sms_service_active == 'clickatell')
		{
			$clickatell=get_option( 'hmgt_clickatell_sms_service');
			
			?>
			
		<div class="form-group">
			<label class="col-sm-2 control-label " for="username"><?php _e('Username','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="username" class="form-control validate[required]" type="text" value="<?php echo $clickatell['username'];?>" name="username">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label " for="password"><?php _e('Password','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="password" class="form-control validate[required]" type="text" value="<?php echo $clickatell['password'];?>" name="password">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label " for="api_key"><?php _e('API Key','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="api_key" class="form-control validate[required]" type="text" value="<?php echo $clickatell['api_key'];?>" name="api_key">
			</div>
		</div>
		<?php 
		}
		if($current_sms_service_active == 'twillo')
		{
			$twillo=get_option( 'hmgt_twillo_sms_service');
			?>
			<div class="form-group">
			<label class="col-sm-2 control-label " for="account_sid"><?php _e('Account SID','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="account_sid" class="form-control validate[required]" type="text" value="<?php echo $twillo['account_sid'];?>" name="account_sid">
			</div>
		</div>
	<div class="form-group">
			<label class="col-sm-2 control-label" for="auth_token"><?php _e('Auth Token','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="auth_token" class="form-control validate[required] text-input" type="text" name="auth_token" value="<?php echo $twillo['auth_token'];?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="from_number"><?php _e('From Number','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="from_number" class="form-control validate[required] text-input" type="text" name="from_number" value="<?php echo $twillo['from_number'];?>">
			</div>
		</div>
		
				<?php 
				}
			?>
		</div>
    	
		
		
		
    	<div class="col-sm-offset-2 col-sm-8">        	
        	<input type="submit" value="<?php  _e('Save','hospital_mgt');?>" name="save_sms_setting" class="btn btn-success" />
        </div>
        
   
      </form>
	  </div>
      <div class="clearfix"> </div>
    
	 
   
	 </div>
	 </div>
	 </div>    
</div>
<?php ?>