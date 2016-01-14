<?php 
//Add Treatment
$edit=0;
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
{
	$edit = 1;
	$treatment_id = $_REQUEST['treatment_id'];
	$result = $obj_treatment->get_single_treatment($treatment_id);
	
}
?>
	<script type="text/javascript">
$(document).ready(function() {
	$('#treatment_form').validationEngine();
	
} );
</script>
		
       <div class="panel-body">
        <form name="treatment_form" action="" method="post" class="form-horizontal" id="treatment_form">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="treatment_id" value="<?php if(isset($_REQUEST['treatment_id'])) echo $_REQUEST['treatment_id'];?>"  />
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="med_category_name"><?php _e('Treatment Name','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="treatment_name" class="form-control validate[required,custom[onlyLetterSp]] text-input" type="text" 
				value="<?php if($edit){ echo $result->treatment_name;}elseif(isset($_POST['treatment_name'])) echo $_POST['treatment_name'];?>" name="treatment_name">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="treatment_price"><?php _e('Treatment Price','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="treatment_price" class="form-control " type="text"  value="<?php if($edit){ echo $result->treatment_price;}elseif(isset($_POST['treatment_price'])) echo $_POST['treatment_price'];?>" name="treatment_price">
			</div>
		</div>
		
		<div class="col-sm-offset-2 col-sm-8">
        	<input type="submit" value="<?php if($edit){ _e('Save Treatment','hospital_mgt'); }else{ _e('Add Treatment','hospital_mgt');}?>" name="save_treatment" class="btn btn-success"/>
        </div>
        </form>
        </div>
        
     <?php 
	 //}
	 ?>