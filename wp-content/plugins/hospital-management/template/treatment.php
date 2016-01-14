<?php 
$obj_treatment = new Hmgt_treatment();
if(isset($_REQUEST['save_treatment']))
{

	if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
	{

		$result = $obj_treatment->hmgt_add_treatment($_POST);
		if($result)
		{
			if($_REQUEST['action'] == 'edit')
			{
				wp_redirect ( home_url().'?dashboard=user&page=treatment&tab=treatmentlist&message=2');
			 }
			else 
			{	
			wp_redirect ( home_url().'?dashboard=user&page=treatment&tab=treatmentlist&message=1');
			}
			
			
		}
	}
}
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
	$result = $obj_treatment->delete_treatment($_REQUEST['treatment_id']);
	if($result)
	{
			wp_redirect ( home_url().'?dashboard=user&page=treatment&tab=treatmentlist&message=3');
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
$active_tab = isset($_GET['tab'])?$_GET['tab']:'treatmentlist';
?>
<script type="text/javascript">
$(document).ready(function() {
	$('#treatment_form').validationEngine();
	jQuery('#hmgt_treatment').DataTable({
		"aoColumns":[
	                  {"bSortable": true},
	                  {"bSortable": true},	                
	                  {"bSortable": false}]
		});
} );
</script>

<div class="panel-body panel-white">
 <ul class="nav nav-tabs panel_tabs" role="tablist">
      <li class="<?php if($active_tab == 'treatmentlist'){?>active<?php }?>">
          <a href="?dashboard=user&page=treatment&tab=treatmentlist">
             <i class="fa fa-align-justify"></i> <?php _e('Treatment List', 'hospital_mgt'); ?></a>
          </a>
      </li>
	  <li class="<?php if($active_tab=='addtreatment'){?>active<?php }?>">
		  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['invoice_id']))
			{?>
			<a href="?dashboard=user&page=treatment&tab=addtreatment&action=edit&treatment_id=<?php if(isset($_REQUEST['treatment_id'])) echo $_REQUEST['treatment_id'];?>"" class="tab <?php echo $active_tab == 'addtreatment' ? 'active' : ''; ?>">
             <i class="fa fa"></i> <?php _e('Edit Treatment', 'hospital_mgt'); ?></a>
			 <?php }
			else
			{?>
				<a href="?dashboard=user&page=treatment&tab=addtreatment" class="tab <?php echo $active_tab == 'addtreatment' ? 'active' : ''; ?>">
				<i class="fa fa-plus-circle"></i> <?php _e('Add Treatment', 'hospital_mgt'); ?></a>
	  <?php } ?>
	  
	</li>
     
</ul>
	<div class="tab-content">
	<?php if($active_tab == 'treatmentlist'){?>
	
    	 <div class="tab-pane fade active in"  id="eventlist">
         <?php 
		 //	$retrieve_class = get_all_data($tablename);		
		?>
		<div class="panel-body">
        <div class="table-responsive">
        <table id="hmgt_treatment" class="display dataTable " cellspacing="0" width="100%">
        	<thead>
            <tr>
			<th><?php _e( 'Treatment Name', 'hospital_mgt' ) ;?></th>
			 <th><?php _e( 'Price', 'hospital_mgt' ) ;?></th>
               <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </thead>
		<tfoot>
            <tr>
			<th><?php _e( 'Treatment Name', 'hospital_mgt' ) ;?></th>
			 <th><?php _e( 'Price', 'hospital_mgt' ) ;?></th>
                <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </tfoot>
 
        <tbody>
         <?php 
		$treatment_data=$obj_treatment->get_all_treatment();
		 if(!empty($treatment_data))
		 {
		 	foreach ($treatment_data as $retrieved_data){ 
			
			
		 ?>
            <tr>
				<td class="treatment_name"><?php echo $retrieved_data->treatment_name;?></td>
                <td class="treatment_price"><?php echo $retrieved_data->treatment_price;?></td>                
               	<td class="action"> 
               	<a href="?dashboard=user&page=treatment&tab=addtreatment&action=edit&treatment_id=<?php echo $retrieved_data->treatment_id;?>" class="btn btn-info"> 
               	<?php _e('Edit', 'hospital_mgt' ) ;?></a>
                <a href="?dashboard=user&page=treatment&tab=treatmentlist&action=delete&treatment_id=<?php echo $retrieved_data->treatment_id;?>" class="btn btn-danger" 
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
	<?php }
	 if($active_tab == 'addtreatment'){
		 
		 $edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit = 1;
			$treatment_id = $_REQUEST['treatment_id'];
			$result = $obj_treatment->get_single_treatment($treatment_id);
			
		}
?>
	
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
	 <?php }?>
	</div>
	
</div>
<?php ?>