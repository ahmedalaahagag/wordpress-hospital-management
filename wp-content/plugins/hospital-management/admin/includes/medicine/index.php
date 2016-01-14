<?php 
$obj_medicine = new Hmgtmedicine();

$active_tab = isset($_GET['tab'])?$_GET['tab']:'medicinelist';
	
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

<div class="page-inner" style="min-height:1631px !important">
<div class="page-title">
		<h3><img src="<?php echo get_option( 'hmgt_hospital_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'hmgt_hospital_name' );?></h3>
	</div>
	<?php 
	if(isset($_REQUEST['save_category']))
	{
	
		$result = $obj_medicine->hmgt_add_medicinecategory($_POST);
		if($result)
		{
			?>
				<div id="message" class="updated below-h2">
				<?php 
					_e('Medicine Category Insert Successfully.','hospital_mgt');
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
					wp_redirect ( admin_url().'admin.php?page=hmgt_medicine&tab=medicinelist&message=2'); }
				else 
				{
					wp_redirect ( admin_url().'admin.php?page=hmgt_medicine&tab=medicinelist&message=1');
				}
				
				
			}
		}
	}
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
		$result = $obj_medicine->delete_medicine($_REQUEST['medicine_id']);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=hmgt_medicine&tab=medicinelist&message=3');
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
	?>
	<div id="main-wrapper">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-white">
					<div class="panel-body">
	<h2 class="nav-tab-wrapper">
    	<a href="?page=hmgt_medicine&tab=medicinelist" class="nav-tab <?php echo $active_tab == 'medicinelist' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Medicine List', 'hospital_mgt'); ?></a>
    	
        <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{?>
        <a href="?page=hmgt_medicine&tab=addmedicine&&action=edit&medicine_id=<?php echo $_REQUEST['medicine_id'];?>" class="nav-tab <?php echo $active_tab == 'addmedicine' ? 'nav-tab-active' : ''; ?>">
		<?php _e('Edit Medicine', 'hospital_mgt'); ?></a>  
		<?php 
		}
		else
		{?>
			<a href="?page=hmgt_medicine&tab=addmedicine" class="nav-tab <?php echo $active_tab == 'addmedicine' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span>'.__('Add New Medicine', 'hospital_mgt'); ?></a>  
		<?php  }?>
       
    </h2>
     <?php 
	//Report 1 
	if($active_tab == 'medicinelist')
	{ 
	
	?>	
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
    <form name="medicine" action="" method="post">
    <div class="panel-body">
        	<div class="table-responsive">
        <table id="medicine_list" class="display" cellspacing="0" width="100%">
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
                
               	<td class="action"> <a href="?page=hmgt_medicine&tab=addmedicine&action=edit&medicine_id=<?php echo $retrieved_data->medicine_id;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
                <a href="?page=hmgt_medicine&tab=medicinelist&action=delete&medicine_id=<?php echo $retrieved_data->medicine_id;?>" class="btn btn-danger" 
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
       
</form>
     <?php 
	 }
	
	if($active_tab == 'addmedicine')
	 {
	require_once HMS_PLUGIN_DIR. '/admin/includes/medicine/add_medicine.php';
	 }
	 ?>
</div>
			
		</div>
	</div>
</div>


<?php //} ?>