<?php 
$obj_medicine = new Hmgtmedicine();
if(isset($_REQUEST['save_category']))
{

	$result = $obj_medicine->hmgt_add_medicinecategory($_POST);
	if($result)
	{?>
			<div id="message" class="updated below-h2">
			<?php 
				_e('Record inserted successfully','hospital_mgt');
			?></div><?php 	
	}
	
}

if(isset($_REQUEST['save_medicine']))
{
	
	if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
	{
		
		$result = $obj_medicine->hmgt_add_medicine($_POST);
		if($result)
		{
			if($_REQUEST['action'] == 'edit')
			{
				
				wp_redirect ( home_url() . '?dashboard=user&page=medicine&tab=medicinelist&message=2');
			}
			else 
			{
				wp_redirect ( home_url() . '?dashboard=user&page=medicine&tab=medicinelist&message=1');
			}
			
			
		}
	}
}
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
	$result = $obj_medicine->delete_medicine($_REQUEST['medicine_id']);
	if($result)
	{
		wp_redirect ( home_url() . '?dashboard=user&page=medicine&tab=medicinelist&message=3');
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
$active_tab = isset($_REQUEST['tab'])?$_REQUEST['tab']:'medicinelist';
	?>
	<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
    <div class="modal-content">
    <div class="category_list">
     </div>
     
    </div>
    </div> 
    
</div>
<!-- End POP-UP Code -->
<script type="text/javascript">
$(document).ready(function() {
	jQuery('#medicine_list').DataTable({
		"aoColumns":[
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},              	                 
	                  {"bSortable": false}]});
} );
</script>
<div class="panel-body panel-white">
 <ul class="nav nav-tabs panel_tabs" role="tablist">
      <li class="<?php if($active_tab == 'medicinelist') echo "active";?>">
          <a href="?dashboard=user&page=medicine&tab=medicinelist">
             <i class="fa fa-align-justify"></i> <?php _e('Medicine List', 'hospital_mgt'); ?></a>
          </a>
      </li>	  
      <li class="<?php if($active_tab == 'addmedicine') echo "active";?>">
      	<a href="?dashboard=user&page=medicine&tab=addmedicine">
        <i class="fa fa-plus-circle"></i> <?php
		if(isset($_REQUEST['action']) && $_REQUEST['action'] =='edit')
			_e('Edit Medicine', 'hospital_mgt'); 
		else
			_e('Add Medicine', 'hospital_mgt'); 
		?></a> 
      </li>
</ul>
	  <div class="tab-content">
      <div class="tab-pane <?php if($active_tab == 'medicinelist') echo "fade active in";?>" id="appointmentlist">
         <div class="panel-body">
        <div class="table-responsive">
       <table id="medicine_list" class="display dataTable" cellspacing="0" width="100%">
        	 <thead>
            <tr>
			<th><?php _e( 'Medicine Name', 'hospital_mgt' ) ;?></th>
			 <th><?php _e( 'Category', 'hospital_mgt' ) ;?></th>
               <th><?php _e( 'Price', 'hospital_mgt' ) ;?></th> 
			    <th><?php _e( 'Stock', 'hospital_mgt' ) ;?></th> 
				<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </thead>
		<tfoot>
            <tr>
			<th><?php _e( 'Medicine Name', 'hospital_mgt' ) ;?></th>
			 <th><?php _e( 'Category', 'hospital_mgt' ) ;?></th>
               <th><?php _e( 'Price', 'hospital_mgt' ) ;?></th> 
			    <th><?php _e( 'Stock', 'hospital_mgt' ) ;?></th> 
				<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </tfoot>
 
        <tbody>
         <?php 
		$medicinedata=$obj_medicine->get_all_medicine();
		 if(!empty($medicinedata))
		 {
		 	foreach ($medicinedata as $retrieved_data){ 
		?>
            <tr>
				<td class="medicine_name"><?php	echo $retrieved_data->medicine_name;	?></td>
                <td class="category"><?php echo $obj_medicine->get_medicine_categoryname($retrieved_data->med_cat_id);?></td>
                <td class="price"><?php  echo $retrieved_data->medicine_price;	?></td>
				<td class="medicine_qty"><?php echo $retrieved_data->medicine_stock;?></td>
                
               	<td class="action"> <a href="?dashboard=user&page=medicine&tab=addmedicine&action=edit&medicine_id=<?php echo $retrieved_data->medicine_id;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
                <a href="?dashboard=user&page=medicine&tab=medicinelist&action=delete&medicine_id=<?php echo $retrieved_data->medicine_id;?>" class="btn btn-danger" 
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
	<div class="tab-pane <?php if($active_tab == 'addmedicine') echo "fade active in";?>">
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
		</div>
</div>
</div>
<?php ?>