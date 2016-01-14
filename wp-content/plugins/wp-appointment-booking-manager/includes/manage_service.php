<?php
	global $table_prefix,$wpdb;
	$sql = "select * from ".$table_prefix."appointgen_services";
	$services = $wpdb->get_results($sql);
?>
<script type="text/javascript">
  jQuery(document).ready(function(){
	jQuery('#inner_content').delegate("#delete_service","click",function(e){
		e.preventDefault();
    if(!confirm('Are you sure want to delete')){
      return false;
    }
		var serviceid = jQuery(this).parent().children('#hdnserviceid').val();
		jQuery.ajax({
				type: "POST",
        url: '<?php echo admin_url( 'admin-ajax.php' );?>?calltype=delete_service',
				data: {
          action: 'appointgen_appointment_operations',
          service_id:serviceid
        },
				success: function (data) {
						var count = data.length;
						if(count>0){
							alert('Service Deleted');
						}
				},
				error : function(s , i , error){
						console.log(error);
				}
		});
		console.log(jQuery(this).parent().parent().remove());
	});
});
</script>
<style type="text/css">
		#btnsearchservice{
			background:url('<?php echo USTSAPPOINTMENT_PLUGIN_URL ?>/images/search.png') no-repeat;
			width: 30px; 
			height: 30px; 
			cursor:pointer;
		}
	</style>
	<div class="wrapper">
  <div class="wrap" style="float:left; width:100%;">
    <div id="icon-options-general" class="icon32"><br />
    </div>
    
    <div style="width:50%;float:left;">
    	<h2>
      	<?php _e("Service","appointgen_ustsappointment"); ?>
        <a class="add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=add-services-menu"><?php _e("Add New","appointgen_ustsappointment"); ?></a>
    	</h2>
    </div>
    <div style="width:29%;float:left;margin-top:15px;">
    	<form id="frmsearchb" method="post" action="">
      	<input type="text" name="txtsearchservice" id="txtsearchservice" value="" style="width:250px;height:40px;" />
      	<input type="button" id="btnsearchservice" name="btnsearchservice" value="" />
      </form>
    </div>
    <div class="main_div">
     	<div class="metabox-holder" style="width:80%; float:left;">
        <div id="namediv" class="stuffbox" style="width:99%;">
        <h3 class="top_bar"><?php _e("Manage Service","appointgen_ustsappointment"); ?></h3>
				<div id="inner_content">		
        	<div class="data"></div>
			  	<div class="pagination"></div>			
				 <table class="wp-list-table widefat fixed bookmarks" cellspacing="0">
          <thead>
            <tr>
              <th><?php _e("Provider Name","appointgen_ustsappointment"); ?></th>
              <th><?php _e("Service Name","appointgen_ustsappointment"); ?></th>
              <th><?php _e("Service Details","appointgen_ustsappointment"); ?></th>
              <th><?php _e("Price","appointgen_ustsappointment"); ?></th>
              <th></th>
            </tr>
          </thead>
					<?php
          foreach($services as $service){
          ?>
            <tr class="alternate">
                <td><?php printf(__("%s","appointgen_ustsappointment"), $service->provider_name);?></td>
                <td><?php printf(__("%s","appointgen_ustsappointment"), $service->service_name);?></td>
                <td><?php printf(__("%s","appointgen_ustsappointment"), $service->service_details);?></td>
                <td><?php printf(__("%s","appointgen_ustsappointment"), $service->price);?></td>
                
                <td>
                  <a href="<?php echo get_option('siteurl');?>/wp-admin/admin.php?page=add-services-menu&calltype=editservice&id=<?php echo $service->id;?>"><?php _e("edit","appointgen_ustsappointment"); ?></a>
                  &nbsp;&nbsp;&nbsp;<a style="cursor:pointer;" id="delete_service" ><?php _e("delete","appointgen_ustsappointment"); ?></a>
                  <input type="hidden" id="hdnserviceid"  name="hdnserviceid" value="<?php echo $service->id;?>" />
                </td>
            </tr>
            <?php
            }
            ?>
          <tfoot>
            <tr>
              <th><?php _e("Provider Name","appointgen_ustsappointment"); ?></th>
              <th><?php _e("Service Name","appointgen_ustsappointment"); ?></th>
              <th><?php _e("Service Details","appointgen_ustsappointment"); ?></th>
              <th><?php _e("Price","appointgen_ustsappointment"); ?></th>
              <th></th>
            </tr>
          </tfoot>
        </table>
				</div>
				</div>
		  </div>
	  </div>
	 </div>
  </div>