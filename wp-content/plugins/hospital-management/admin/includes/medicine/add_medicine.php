<?php 
	//Add Medicine
$edit = 0;
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
{
	$edit = 1;
	$medcategory_id = $_REQUEST['medicine_id'];
	$result = $obj_medicine->get_single_medicine($medcategory_id);
}	
?>
	
   	<script type="text/javascript">

$(document).ready(function() {
	$('#medicine_form').validationEngine();

} );
</script>
       <div class="panel-body">
        <form name="medicine_form" action="" method="post" class="form-horizontal" id="medicine_form">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="medicine_id" value="<?php if(isset($_REQUEST['medicine_id'])) echo $_REQUEST['medicine_id'];?>"  />
		
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="medicine_name"><?php _e('Medicine Name','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="medicine_name" class="form-control validate[required,custom[onlyLetterSp]] text-input" type="text" 
				value="<?php if($edit){ echo $result->medicine_name;}elseif(isset($_POST['medicine_name'])) echo $_POST['medicine_name'];?>" name="medicine_name">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="medicine_category"><?php _e('Category Name','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
			
				<select class="form-control validate[required]" name="medicine_category" id="category_data">
				<option value=""><?php _e('Select Category','hospital_mgt');?></option>
				<?php 
				$medicine_category = $obj_medicine->get_all_category();
				if(isset($_REQUEST['medicine_category']))
					$category =$_REQUEST['medicine_category'];  
				elseif($edit)
					$category =$result->med_cat_id;
				else 
					$category = "";
				
				if(!empty($medicine_category))
				{
					foreach ($medicine_category as $retrive_data)
					{
						echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
					}
				}
				?>
				
				</select>
			</div>
			<div class="col-sm-2"><button id="addremove" model="medicine"><?php _e('Add Or Remove','hospital_mgt');?></button></div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="med_price"><?php _e('Price','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="med_price" class="form-control validate[required,custom[onlyNumberSp]] text-input" type="text" 
				value="<?php if($edit){ echo $result->medicine_price;}elseif(isset($_POST['med_price'])) echo $_POST['med_price'];?>" name="med_price">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="mfg_cmp_name"><?php _e('Manufactured Company Name','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="mfg_cmp_name" class="form-control " type="text"  
				value="<?php if($edit){ echo $result->medicine_menufacture;}elseif(isset($_POST['mfg_cmp_name'])) echo $_POST['mfg_cmp_name'];?>" name="mfg_cmp_name">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="description"><?php _e('Description','hospital_mgt');?></label>
			<div class="col-sm-8">
				<textarea name="description" id="description" class="form-control"><?php if($edit){ echo $result->medicine_description;}elseif(isset($_POST['description'])) echo $_POST['description'];?>
				</textarea>
				
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="medicine_qty"><?php _e('Stock Status','hospital_mgt');?></label>
			<div class="col-sm-8">
				<?php if($edit){ $stock= $result->medicine_stock;}
				elseif(isset($_POST['medicine_stock'])) 
				$stock= $_POST['medicine_stock'];
				else 
					$stock= "";?>
				<select class="form-control validate[required]" name="medicine_stock" id="medicine_stock">
				<option value="In" <?php selected($stock,'In');?>><?php _e('IN','hospital_mgt');?></option>
				<option value="Out" <?php selected($stock,'Out');?>><?php _e('Out','hospital_mgt');?></option>
				</select>
			</div>
		</div>
		<div class="col-sm-offset-2 col-sm-8">
        	<input type="submit" value="<?php if($edit){ _e('Save Medicine','hospital_mgt'); }else{ _e('Add Medicine','hospital_mgt');}?>" name="save_medicine" class="btn btn-success"/>
        </div>
        </form>
        </div>
        
<?php 
	
?>