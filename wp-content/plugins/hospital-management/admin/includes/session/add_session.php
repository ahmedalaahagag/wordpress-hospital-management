<?php 
//Add Session
$edit=0;
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
{
	$edit = 1;
	$session_id = $_REQUEST['session_id'];
	$result = $obj_session->get_single_session($session_id);
}
?>

	<script type="text/javascript">
$(document).ready(function() {
	$('#session_form').validationEngine();
	
});
</script>
       <div class="panel-body">
        <form name="session_form" action="" method="post" class="form-horizontal" id="session_form">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="session_id" value="<?php if(isset($_REQUEST['session_id'])) echo $_REQUEST['session_id'];?>"  />
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="med_category_name"><?php _e('Session Name','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="session_name" class="form-control validate[required,custom[onlyLetterSp]] text-input" type="text"
				value="<?php if($edit){ echo $result->session_name;}elseif(isset($_POST['session_name'])) echo $_POST['session_name'];?>" name="session_name">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="session_price"><?php _e('Session Type','hospital_mgt');?></label>
			<div class="col-sm-8">
				<select class="form-control" name="session_type">
					<?php
					$session_types_item ['id']= '1';
					$session_types_item ['value']= 'Assessment';
					$session_types [] =$session_types_item;
					$session_types_item ['id']= '2';
					$session_types_item ['value']= 'Treatment';
					$session_types [] =$session_types_item;
					foreach($session_types as $seesion_type)
					{
						if($result->session_type == $seesion_type['id']){
						echo"<option value='".$seesion_type['id']."'selected=selected>".$seesion_type['value']."</option>";
						}
						else{
							echo"<option value='".$seesion_type['id']."'>".$seesion_type['value']."</option>";
						}
					}
					?>
				</select>
			</div>
		</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="discount_sessions_number"><?php _e('Discount After ','hospital_mgt');?><span class="require-field"></span></label>
				<div class="col-sm-8">
					<input id="discount_sessions_number" class="form-control validate[custom[integer],min[1]] text-input" type="text" value="<?php if($edit){ echo $result->discount_sessions_number;}elseif(isset($_POST['discount_sessions_number'])) echo $_POST['discount_sessions_number'];?>" name="discount_sessions_number">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="discount_sessions_percentage"><?php _e('Discount Percentage','hospital_mgt');?><span class="require-field"></span></label>
				<div class="col-sm-8">
					<input id="discount_sessions_percentage" class="form-control validate[custom[integer],max[100],min[0]] text-input" type="text" value="<?php if($edit){ echo $result->discount_sessions_percentage;}elseif(isset($_POST['discount_sessions_percentage'])) echo $_POST['discount_sessions_percentage'];?>" name="discount_sessions_percentage">
				</div>
			</div>
		<div class="col-sm-offset-2 col-sm-8">
        	<input type="submit" value="<?php if($edit){ _e('Save Session','hospital_mgt'); }else{ _e('Add Session','hospital_mgt');}?>" name="save_session" class="btn btn-success"/>
        </div>
        </form>
        </div>
     <?php 
	 //}
	 ?>