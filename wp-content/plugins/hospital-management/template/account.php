<?php 
	
	//$school_obj = new School_Management ( get_current_user_id () );
     $user_object=new Hmgtuser();
	$user = wp_get_current_user ();

	$user_data =get_userdata( $user->ID);
	require_once ABSPATH . 'wp-includes/class-phpass.php';
	$wp_hasher = new PasswordHash( 8, true );
	if(isset($_POST['save_change']))
	{
		$referrer = $_SERVER['HTTP_REFERER'];
		
		$success=0;
		if($wp_hasher->CheckPassword($_REQUEST['current_pass'],$user_data->user_pass))
		{
			
			if(isset($_REQUEST['new_pass'])==$_REQUEST['conform_pass'])
			{
				 wp_set_password( $_REQUEST['new_pass'], $user->ID);
					$success=1;
			}
			else
			{
				wp_redirect($referrer.'&sucess=2');
			}
			
		}
		else{
			
			wp_redirect($referrer.'&sucess=3');
		}
		if($success==1)
		{
			 wp_cache_delete($user->ID,'users');
			wp_cache_delete($user_data->user_login,'userlogins');
			wp_logout();
			if(wp_signon(array('user_login'=>$user_data->user_login,'user_password'=>$_REQUEST['new_pass']),false)):
				$referrer = $_SERVER['HTTP_REFERER'];
				
				wp_redirect($referrer.'&sucess=1');
			endif;
			ob_start();
		}else{
    wp_set_auth_cookie($user->ID, true);
		}
		
	
	}
?>
<?php 
	$edit=1;
	$coverimage=get_option( 'hmgt_hospital_background_image' );
	if($coverimage!="")
	{?>

<style>
.profile-cover{
	background: url("<?php echo get_option( 'hmgt_hospital_background_image' );?>") repeat scroll 0 0 / cover rgba(0, 0, 0, 0);
}
<?php }?>



</style>
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
<div> 
	<div class="profile-cover">
			<div class="row">
				
						<div class="col-md-3 profile-image">
									<div class="profile-image-container">
									<?php $umetadata=get_user_meta($user->ID, 'hmgt_user_avatar', true);
										
													if(empty($umetadata)){
														echo '<img src='.get_default_userprofile($obj_hospital->role).' height="150px" width="150px" class="img-circle" />';
													}
													else
														echo '<img src='.$umetadata.' height="150px" width="150px" class="img-circle" />';
									?>
									</div>
						</div>
						
				</div>
			</div>				
	
	<div Id="main-wrapper"> 
		<div class="row">
			<div class="col-md-3 user-profile">
				<h3 class="text-center">
					<?php 
						echo $user_data->display_name;
					?>
				</h3>				
				<hr>
				<ul class="list-unstyled text-center">
				<li>
				<p><i class="fa fa-map-marker m-r-xs"></i>
					<a href="#"><?php echo $user_data->address.",".$user_data->city;?></a></p>
				</li>	
				<li><i class="fa fa-envelope m-r-xs"></i>
							<a href="#"><?php echo 	$user_data->user_email;?></a></p>
				</p></li>
				</ul>
			</div>			
				<?php if(isset($_REQUEST['message']))
				{
					$message =$_REQUEST['message'];
					if($message == 2)
					{?><div class="col-md-8 m-t-lg"><div id="message" class="updated below-h2 "><p><?php
								_e("Record updated successfully.",'hospital_mgt');
								?></p>
								</div></div>
							<?php 
						
					}
					
				}?>
				<div class="col-md-8 m-t-lg">
				<div class="panel panel-white">
				<div class="panel-heading">
										<div class="panel-title"><?php _e('Account Settings ','hospital_mgt');?>	</div>
									</div>
									<div class="panel-body">
						<form class="form-horizontal" action="#" method="post">
								<div class="form-group">
									<label  class="control-label col-xs-2"></label>
									<div class="col-xs-10">	
										<p>
										<h4 class="bg-danger"><?php 
										if(isset($_REQUEST['sucess']))
										{ 
											if($_REQUEST['sucess']==1)
											{
												wp_safe_redirect(home_url()."?dashboard=user&page=account&action=edit&message=2" );
											}
											
											
										}?></h4>
									</p>
									</div>
							</div>
							<div class="form-group">

								<label for="inputEmail" class="control-label col-sm-2"><?php _e('Name','hospital_mgt');?></label>

								<div class="col-sm-10">

									<input type="Name" class="form-control " id="name" placeholder="Full Name" value="<?php echo $user->display_name; ?>" readonly>
									
								</div>

							</div>
							<div class="form-group">

								<label for="inputEmail" class="control-label col-sm-2"><?php _e('Username','hospital_mgt');?></label>

								<div class="col-sm-10">

									<input type="username" class="form-control " id="name" placeholder="Full Name" value="<?php echo $user->user_login; ?>" readonly>
									
								</div>

							</div>


						</form>
						</div>		   
						</div>					
							<?php 			
				
							$user_info=get_userdata(get_current_user_id());
					
				 ?> 
				
				
						<div class="panel panel-white">
				<div class="panel-heading">
										<div class="panel-title"><?php _e('Other Information','hospital_mgt');?>	</div>
									</div>
									<div class="panel-body">
						<form class="form-horizontal" action="#" method="post" id="doctor_form">
							<input type="hidden" value="edit" name="action">
							<input type="hidden" value="<?php echo $obj_hospital->role;?>" name="role">
							<input type="hidden" value="<?php echo get_current_user_id();?>" name="user_id">
							<div class="form-group">
								<label class="col-sm-2 control-label" for="birth_date"><?php _e('Date of birth','hospital_mgt');?><span class="require-field">*</span></label>
								<div class="col-sm-10">
									<input id="birth_date" readonly class="form-control validate[required]" type="text"  name="birth_date"
									value="<?php if($edit){ echo $user_info->birth_date;}elseif(isset($_POST['birth_date'])) echo $_POST['birth_date'];?>">
								</div>
							</div>	
							<?php if($obj_hospital->role == 'doctor'){?>


							<div class="form-group">
								<label class="col-sm-2 control-label" for="birth_date"><?php _e('Degree','hospital_mgt');?><span class="require-field">*</span></label>
								<div class="col-sm-10">
									<input id="doc_degree" readonly class="form-control validate[required]" type="text"  name="doc_degree"
									value="<?php if($edit){ echo $user_info->doctor_degree;}elseif(isset($_POST['doc_degree'])) echo $_POST['doc_degree'];?>">
								</div>
							</div>	
							<div class="form-group">
								<label class="col-sm-2 control-label" for="visiting_fees"><?php _e('Home Visting Charge','hospital_mgt');?></label>
								<div class="col-sm-10">
									<input id="doc_degree" readonly class="form-control" type="text"  name="visiting_fees"
									value="<?php if($edit){ echo $user_info->visiting_fees;}elseif(isset($_POST['visiting_fees'])) echo $_POST['visiting_fees'];?>">
								</div>
							</div>
							<?php } //end Docotr field?>
							<div class="form-group">

								<label for="inputEmail" class="control-label col-sm-2"><?php _e('Home Town Address','hospital_mgt');?></label>

								<div class="col-sm-10">

									<input id="address"  readonly class="form-control validate[required]" type="text"  name="address" value="<?php if($edit){ echo $user_info->address;}?>">

								</div>

							</div>
							<?php if($obj_hospital->role == 'doctor'){?>
							<div class="form-group">

								<label for="inputEmail" class="control-label col-sm-2"><?php _e('City','hospital_mgt');?></label>

								<div class="col-sm-10">

									<input id="address" readonly class="form-control validate[required]" type="text"  name="home_city_name" value="<?php if($edit){ echo $user_info->home_city;}?>">

								</div>

							</div>
							<div class="form-group">

								<label for="inputEmail" class="control-label col-sm-2"><?php _e('Area','hospital_mgt');?></label>

								<div class="col-sm-10">

									<input id="address" readonly class="form-control validate[required]" type="text"  name="home_state_name" value="<?php if($edit){ echo $user_info->home_state;}?>">

								</div>

							</div>
							<div class="form-group">

								<label for="inputEmail" class="control-label col-sm-2"><?php _e('Country','hospital_mgt');?></label>

								<div class="col-sm-10">

									<input id="address" readonly class="form-control validate[required]" type="text"  name="home_country_name" value="<?php if($edit){ echo $user_info->home_country;}?>">

								</div>

							</div>

							<div class="form-group">

								<label for="inputEmail" class="control-label col-sm-2"><?php _e('Phone','hospital_mgt');?></label>

								<div class="col-sm-10">

									<input id="phone" readonly class="form-control validate[,custom[phone]] text-input" type="text"  name="phone" value="<?php if($edit){ echo $user_info->phone;}?>">

								</div>

							</div>
							<div class="form-group">

								<label for="inputEmail" class="control-label col-sm-2"><?php _e('Working Days','hospital_mgt');?></label>

								<div class="col-sm-10">

									<input id="phone" readonly class="form-control validate[,custom[number]] text-input" type="text"  name="workingdays" value="<?php if($edit){ echo $user_info->phone;}?>">

								</div>

							</div>
							<div class="form-group">

								<label for="inputEmail" class="control-label col-sm-2"><?php _e('Working Hours','hospital_mgt');?></label>

								<div class="col-sm-10">

									<input id="phone" readonly class="form-control validate[,custom[number]] text-input" type="text"  name="workinghours" value="<?php if($edit){ echo $user_info->phone;}?>">

								</div>

							</div>
							<div class="form-group">

								<label for="inputEmail" class="control-label col-sm-2"><?php _e('Email','hospital_mgt');?></label>

								<div class="col-sm-10">

									<input id="email" readonly class="form-control validate[required,custom[email]] text-input" type="text"  name="email" value="<?php if($edit){ echo $user_info->user_email;}?>">

								</div>

							</div>
							<div class="form-group">
							</div>
						</form>
						</div>
						</div>
					 </div>
					 </div>
 		</div>
		</div>
	</div>
</div>
<script>
document.ready(function(){
  $('input').attr('readonly', 'readonly');
});
</script>
<?php 
	if(isset($_POST['profile_save_change']))
	{
		
		$result=$user_object->hmgt_add_user($_POST);
		
		if($result)
		{ 
			
			wp_safe_redirect(home_url()."?dashboard=user&page=account&action=edit&message=2" );
		}
	}

}
?>