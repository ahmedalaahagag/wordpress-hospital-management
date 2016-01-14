<?php 
//package
$obj_package = new Hmgt_packages();

$active_tab = isset($_GET['tab'])?$_GET['tab']:'packageslist';
	
?>

<div class="page-inner" style="min-height:1631px !important">
<div class="page-title">
		<h3><img src="<?php echo get_option( 'hmgt_hospital_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'hmgt_hospital_name' );?></h3>
	</div>
	<?php
	if(isset($_REQUEST['save_package']))
	{
		if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
		{
	
			$result = $obj_package->hmgt_add_package($_POST);
			if($result)
			{
				if($_REQUEST['action'] == 'edit')
				{
					wp_redirect ( admin_url().'admin.php?page=hmgt_packages&tab=packageslist&message=2');
				}
				else
				{
					wp_redirect ( admin_url().'admin.php?page=hmgt_packages&tab=packageslist&message=1');
				}
					
					
			}
		}
	}
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
		$result = $obj_package->delete_package($_REQUEST['package_id']);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=hmgt_packages&tab=packageslist&message=3');
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
					_e("Record updated successfully.",'hospital_mgt');
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
    	<a href="?page=hmgt_packages&tab=packageslist" class="nav-tab <?php echo $active_tab == 'packageslist' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Package List', 'hospital_mgt'); ?></a>
    	
        <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{?>
        <a href="?page=hmgt_packages&tab=addpackage&&action=edit&package_id=<?php echo $_REQUEST['package_id'];?>" class="nav-tab <?php echo $active_tab == 'addpackage' ? 'nav-tab-active' : ''; ?>">
		<?php _e('Edit package', 'hospital_mgt'); ?></a>
		<?php 
		}
		else
		{?>
		<a href="?page=hmgt_packages&tab=addpackage" class="nav-tab <?php echo $active_tab == 'addmedcategory' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add New Package', 'hospital_mgt'); ?></a>
		<?php  }?>
       
    </h2>
     <?php 
	//Report 1 
	if($active_tab == 'packageslist')
	{ 
	
	?>	
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#package_list').DataTable({
		"aoColumns":[
	                  {"bSortable": true},
	                  {"bSortable": true}
	                ]
		});

} );
</script>
    <form name="package" action="" method="post">
    
        <div class="panel-body">
        	<div class="table-responsive">
        <table id="package_list" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>
			<th><?php _e( 'Package Name', 'hospital_mgt' ) ;?></th>
                <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </thead>
 
        <tfoot>
            <tr>
			<th><?php _e( 'Package Name', 'hospital_mgt' ) ;?></th>
                <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </tfoot>
 
        <tbody>
         <?php

		$package_data=$obj_package->get_all_packages();
		 if(!empty($package_data))
		 {
		 	foreach ($package_data as $retrieved_data){
			
			
		 ?>
            <tr>
				<td class="package_name"><?php echo $retrieved_data->package_name;?></td>
               	<td class="action">
               	<a href="?page=hmgt_packages&tab=addpackage&action=edit&package_id=<?php echo $retrieved_data->package_id;?>" class="btn btn-info">
               	<?php _e('Edit', 'hospital_mgt' ) ;?></a>
                <a href="?page=hmgt_packages&tab=packageslist&action=delete&package_id=<?php echo $retrieved_data->package_id;?>" class="btn btn-danger"
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
	
	if($active_tab == 'addpackage')
	 {
	require_once HMS_PLUGIN_DIR. '/admin/includes/packages/add_package.php';
	 }
	 ?>
</div>
			
		</div>
	</div>
</div>
<?php //} ?>