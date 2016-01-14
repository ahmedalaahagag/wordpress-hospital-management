<?php
	global $table_prefix,$wpdb;
	$sql = "select scd.*, tms.timeslot_name, srv.service_name, vnus.venue_name from ".$table_prefix."appointgen_schedules scd 
					inner join ".$table_prefix."appointgen_timeslot tms on scd.timeslot=tms.id 
					inner join ".$table_prefix."appointgen_services srv on srv.id = scd.service
					inner join ".$table_prefix."appointgen_venues vnus on vnus.id = scd.venue";
	$schedules = $wpdb->get_results($sql);
?>
<script type="text/javascript">
	jQuery(document).ready(function(){
    jQuery('#inner_content').delegate("#delete_schedule","click",function(e){
      e.preventDefault();
      if(!confirm('Are you sure want to delete')){
        return false;
      }
      var scheduleid = jQuery(this).parent().children('#hdnscheduleid').val();
      jQuery.ajax({
          type: "POST",
          url: '<?php echo admin_url( 'admin-ajax.php' );?>?calltype=delete_schedule',
          data: {
            action: 'appointgen_appointment_operations',
            schedule_id:scheduleid
          },
          success: function (data) {
              var count = data.length;
              if(count>0){
                alert('Schedule Deleted');
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
		#btnsearchschedule{
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
      	<?php _e("Schedule","appointgen_ustsappointment"); ?>
        <a class="add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=add-schedule-menu"><?php _e("Add New","appointgen_ustsappointment"); ?></a>
    	</h2>
    </div>
    <div style="width:29%;float:left;margin-top:15px;">
    	<form id="frmsearchb" method="post" action="">
      	<input type="text" name="txtsearchschedule" id="txtsearchschedule" value="" style="width:250px;height:40px;" />
      	<input type="button" id="btnsearchschedule" name="btnsearchschedule" value="" />
      </form>
    </div>
    
    <div class="main_div">
     	<div class="metabox-holder" style="width:80%; float:left;">
        <div id="namediv" class="stuffbox" style="width:99%;">
        <h3 class="top_bar"><?php _e("Manage Schedule","appointgen_ustsappointment"); ?></h3>
				<div id="inner_content">		
        	<div class="data"></div>
			  	<div class="pagination"></div>			
				 <table class="wp-list-table widefat fixed bookmarks" cellspacing="0">
          <thead>
            <tr>
              <th><?php _e("Schedule Name","appointgen_ustsappointment"); ?></th>
              <th><?php _e("TimeSlot","appointgen_ustsappointment"); ?></th>
              <th><?php _e("Service","appointgen_ustsappointment"); ?></th>
              <th><?php _e("Venue","appointgen_ustsappointment"); ?></th>
              <th></th>
            </tr>
          </thead>
					<?php
          foreach($schedules as $schedule){
          ?>
            <tr class="alternate">
                <td><?php printf(__("%s","appointgen_ustsappointment"), $schedule->schedule_name);?></td>
                <td><?php printf(__("%s","appointgen_ustsappointment"), $schedule->timeslot_name);?></td>
                <td><?php printf(__("%s","appointgen_ustsappointment"), $schedule->service_name);?></td>
                <td><?php printf(__("%s","appointgen_ustsappointment"), $schedule->venue_name);?></td>
                <td>
                  <a href="<?php echo get_option('siteurl');?>/wp-admin/admin.php?page=add-schedule-menu&calltype=editschedule&id=<?php echo $schedule->id;?>"><?php _e("edit","appointgen_ustsappointment"); ?></a>
                  &nbsp;&nbsp;&nbsp;<a id="delete_schedule" style="cursor:pointer;" ><?php _e("delete","appointgen_ustsappointment"); ?></a>
                  <input type="hidden" id="hdnscheduleid"  name="hdnscheduleid" value="<?php echo $schedule->id;?>" />
                </td>
            </tr>
            <?php
            }
            ?>
          <tfoot>
            <tr>
              <th><?php _e("Schedule Name","appointgen_ustsappointment"); ?></th>
              <th><?php _e("TimeSlot","appointgen_ustsappointment"); ?></th>
              <th><?php _e("Service","appointgen_ustsappointment"); ?></th>
              <th><?php _e("Venue","appointgen_ustsappointment"); ?></th>
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