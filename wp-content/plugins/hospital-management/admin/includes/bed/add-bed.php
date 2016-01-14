<?php 
	//Add bed
$edit = 0;
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
{
	$edit = 1;
	$bed_type_id = $_REQUEST['bed_id'];
	$result = $obj_bed->get_single_bed($bed_type_id);
	

}	
?>

		
       <div class="panel-body">
        <form name="bed_form" action="" method="post" class="form-horizontal" id="bed_form">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="bed_id" value="<?php if(isset($_REQUEST['bed_id'])) echo $_REQUEST['bed_id'];?>"  />
		<div class="form-group">
			<label class="col-sm-2 control-label" for="bed_type_id"><?php _e('Select Bed Category','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
			<?php if(isset($_REQUEST['bed_type_id']))
					$bed_type1 = $_REQUEST['bed_type_id'];
				elseif($edit)
					$bed_type1 = $result->bed_type_id;
				else 
					$bed_type1 = "";
				?>
				<select name="bed_type_id" class="form-control validate[required]" id="category_data">
				<option value = ""><?php _e('Select Bed Category','hospital_mgt');?></option>
				<?php 
				
				$bedtype_data=$obj_bed->get_all_bedtype();
				if(!empty($bedtype_data))
				{
					foreach ($bedtype_data as $retrieved_data)
					{
						echo '<option value="'.$retrieved_data->ID.'" '.selected($bed_type1,$retrieved_data->ID).'>'.$retrieved_data->post_title.'</option>';
					}
				}
				?>
				</select>
			</div>
			<div class="col-sm-2"><button id="addremove" model="bedtype"><?php _e('Add Or Remove','hospital_mgt');?></button></div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="bed_number"><?php _e('Bed Number','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="bed_number" class="form-control validate[required] text-input" type="text" 
				value="<?php if($edit){ echo $result->bed_number;}elseif(isset($_POST['bed_number'])) echo $_POST['bed_number'];?>" name="bed_number">
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="bed_charges"><?php _e('Charges','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="bed_charges" class="form-control " type="text"  
				value="<?php if($edit){ echo $result->bed_charges;}elseif(isset($_POST['bed_charges'])) echo $_POST['bed_charges'];?>" name="bed_charges">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="bed_description"><?php _e('Description','hospital_mgt');?></label>
			<div class="col-sm-8">
				<textarea id="bed_description" class="form-control"  name="bed_description"><?php if($edit){ echo $result->bed_description;}elseif(isset($_POST['bed_description'])) echo $_POST['bed_description'];?></textarea>
				
			</div>
		</div>
		<div class="col-sm-offset-2 col-sm-8">
        	<input type="submit" value="<?php if($edit){ _e('Save Bed','hospital_mgt'); }else{ _e('Add Bed','hospital_mgt');}?>" name="save_bed" class="btn btn-success"/>
        </div>
        </form>
        </div>
        
     <?php 
	 //}
	 ?>