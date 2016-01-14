<?php
	global $table_prefix,$wpdb;
	$sql = "select * from ".$table_prefix."appointgen_timeslot";
	$timeslots = $wpdb->get_results($sql);
?>
<script type="text/javascript">
	jQuery(document).ready(function(){
    jQuery('#inner_content').delegate("#delete_timeslot","click",function(e){
      e.preventDefault();
      if(!confirm('Are you sure want to delete')){
        return false;
      }
      var timeslotid = jQuery(this).parent().children('#hdntimeslotid').val();//jQuery('#hdnappointmentid').val();
      jQuery.ajax({
          type: "POST",
          url: '<?php echo admin_url( 'admin-ajax.php' );?>?calltype=delete_timeslot',
          data: {
            action: 'appointgen_appointment_operations',
            timeslot_id:timeslotid
          },
          success: function (data) {
              var count = data.length;
              if(count>0){
                alert('Timeslot Deleted');
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
		#btnsearchappointment{
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
      	<?php _e("TimeSlot","appointgen_ustsappointment"); ?>
        <a class="add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=add-timeslot-menu"><?php _e("Add New","appointgen_ustsappointment"); ?></a>
    	</h2>
    </div>
    <div style="width:29%;float:left;margin-top:15px;">
    	<form id="frmsearchb" method="post" action="">
      	<input type="text" name="txtsearchappointment" id="txtsearchappointment" value="" style="width:250px;height:40px;" />
      	<input type="button" id="btnsearchappointment" name="btnsearchappointment" value="" />
      </form>
    </div>
    
    <div class="main_div">
     	<div class="metabox-holder" style="width:80%; float:left;">
        <div id="namediv" class="stuffbox" style="width:99%;">
        <h3 class="top_bar"><?php _e("Manage Timeslot","appointgen_ustsappointment"); ?></h3>
				<div id="inner_content">		
        	<div class="data"></div>
			  	<div class="pagination"></div>			
				 <table class="wp-list-table widefat fixed bookmarks" cellspacing="0">
          <thead>
            <tr>
              <th><?php _e("Timeslot Name","appointgen_ustsappointment"); ?></th>
              <th><?php _e("Start Time","appointgen_ustsappointment"); ?></th>
              <th><?php _e("End Time","appointgen_ustsappointment"); ?></th>
              <th><?php _e("Time Interval","appointgen_ustsappointment"); ?></th>
              <th></th>
            </tr>
          </thead>
					<?php
          foreach($timeslots as $timeslot){
          ?>
            <tr class="alternate">
                <td><?php printf(__("%s","appointgen_ustsappointment"), $timeslot->timeslot_name);?></td>
                <td><?php printf(__("%s","appointgen_ustsappointment"), $timeslot->mintime);?></td>
                <td><?php printf(__("%s","appointgen_ustsappointment"), $timeslot->maxtime);?></td>
                <td><?php printf(__("%s","appointgen_ustsappointment"), $timeslot->time_interval);?></td>
                
                <td>
                  <!-- <a href="<?php //echo get_option('siteurl');?>/wp-admin/edit.php?post_type=custom_appointment&page=add-timeslot-menu&calltype=edittimeslot&id=<?php //echo $timeslot->id;?>">edit</a> -->
                  <a href="<?php echo get_option('siteurl');?>/wp-admin/admin.php?page=add-timeslot-menu&calltype=edittimeslot&id=<?php echo $timeslot->id;?>"><?php _e("edit","appointgen_ustsappointment"); ?></a>
                  &nbsp;&nbsp;&nbsp;<a id="delete_timeslot" style="cursor:pointer;" ><?php _e("delete","appointgen_ustsappointment"); ?></a>
                  <input type="hidden" id="hdntimeslotid"  name="hdntimeslotid" value="<?php echo $timeslot->id;?>" />
                </td>
            </tr>
            <?php
            }
            ?>
          <tfoot>
            <tr>
              <th><?php _e("Timeslot Name","appointgen_ustsappointment"); ?></th>
              <th><?php _e("Start Time","appointgen_ustsappointment"); ?></th>
              <th><?php _e("End Time","appointgen_ustsappointment"); ?></th>
              <th><?php _e("Time Interval","appointgen_ustsappointment"); ?></th>
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