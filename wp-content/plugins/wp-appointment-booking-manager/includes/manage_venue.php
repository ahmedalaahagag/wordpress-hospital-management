<?php
	global $table_prefix,$wpdb;
	$sql = "select * from ".$table_prefix."appointgen_venues";
	$venues = $wpdb->get_results($sql);
?>
<script type="text/javascript">
  jQuery(document).ready(function(){
    jQuery('#inner_content').delegate("#delete_venue","click",function(e){
      e.preventDefault();
      if(!confirm('Are you sure want to delete')){
        return false;
      }
      var venueid = jQuery(this).parent().children('#hdnvenueid').val();
      jQuery.ajax({
          type: "POST",
          url: '<?php echo admin_url( 'admin-ajax.php' );?>?calltype=delete_venue',
          data: {
            action: 'appointgen_appointment_operations',
            venue_id:venueid
          },
          success: function (data) {
              var count = data.length;
              if(count>0){
                alert('Venue Deleted');
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
		#btnsearchvenue{
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
      	<?php _e("Venue","appointgen_ustsappointment"); ?>
        <a class="add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=add-venues-menu"><?php _e("Add New","appointgen_ustsappointment"); ?></a>
    	</h2>
    </div>
    <div style="width:29%;float:left;margin-top:15px;">
    	<form id="frmsearchb" method="post" action="">
      	<input type="text" name="txtsearchvenue" id="txtsearchvenue" value="" style="width:250px;height:40px;" />
      	<input type="button" id="btnsearchvenue" name="btnsearchvenue" value="" />
      </form>
    </div>
    
    <div class="main_div">
     	<div class="metabox-holder" style="width:80%; float:left;">
        <div id="namediv" class="stuffbox" style="width:99%;">
        <h3 class="top_bar"><?php _e("Manage Venue","appointgen_ustsappointment"); ?></h3>
				<div id="inner_content">		
        	<div class="data"></div>
			  	<div class="pagination"></div>			
				 <table class="wp-list-table widefat fixed bookmarks" cellspacing="0">
          <thead>
            <tr>
              <th><?php _e("Venue Name","appointgen_ustsappointment"); ?></th>
              <th><?php _e("Venue Address","appointgen_ustsappointment"); ?></th>
              <th></th>
            </tr>
          </thead>
					<?php
          foreach($venues as $venue){
          ?>
            <tr class="alternate">
                <td><?php printf(__("%s","appointgen_ustsappointment"), $venue->venue_name);?></td>
                <td><?php printf(__("%s","appointgen_ustsappointment"), $venue->venue_address);?></td>
                <td>
                  <a href="<?php echo get_option('siteurl');?>/wp-admin/admin.php?page=add-venues-menu&calltype=editvenue&id=<?php echo $venue->id;?>"><?php _e("edit","appointgen_ustsappointment"); ?></a>
                  &nbsp;&nbsp;&nbsp;<a style="cursor:pointer;" id="delete_venue"><?php _e("delete","appointgen_ustsappointment"); ?></a>
                  <input type="hidden" id="hdnvenueid"  name="hdnvenueid" value="<?php echo $venue->id;?>" />
                </td>
            </tr>
            <?php
            }
            ?>
          <tfoot>
            <tr>
							 <th><?php _e("Venue Name","appointgen_ustsappointment"); ?></th>
               <th><?php _e("Venue Address","appointgen_ustsappointment"); ?></th>              
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