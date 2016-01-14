<style type="text/css">
	.timeslot_note{
		font-style:italic;
		font-size:12px;
	}
</style>
<script type="text/javascript">
	jQuery(document).ready(function(){
			var calltype = appointgen_getUrlVars()["calltype"];
			if(calltype){
				if(calltype == 'edittimeslot'){
					<?php
          if(isset($_REQUEST['id'])){
            $id = $_REQUEST['id'];
            global $table_prefix,$wpdb;
            $sql = "select * from ".$table_prefix."appointgen_timeslot where id=".$id;
            $result = $wpdb->get_results($sql);
            ?>
            var timeslot = <?php echo json_encode($result[0]);?>;
            console.log(timeslot);
            jQuery('#hdntimeslotid').val(timeslot['id']);
            jQuery('#timeslot_name').val(timeslot['timeslot_name']);
            jQuery('#starttime').val(timeslot['mintime']);
            jQuery('#endtime').val(timeslot['maxtime']);
            jQuery('#timeinterval').val(timeslot['time_interval']);
          <?php } ?>  
				}
			}	
	});
</script>
<div class="wrapper">
  <div class="wrap" style="float:left; width:100%;">
    <div id="icon-options-general" class="icon32"><br />
    </div>
    <h2><?php _e("Time Slot","appointgen_ustsappointment"); ?></h2>
    <div class="main_div">
     	<div class="metabox-holder" style="width:69%; float:left;">
        <div id="namediv" class="stuffbox" style="width:99%;">
        <h3 class="top_bar"><?php _e("Add TimeSlot","appointgen_ustsappointment"); ?></h3>
            <form id="frmaddtimeslot" method="post" action="" novalidate="novalidate">
            <table style="padding:10px;" >
              <tr>
                <td><?php _e("Time Slot Name","appointgen_ustsappointment"); ?></td>
                <td><input type="text" name="timeslot_name" id="timeslot_name" value="" /><span style="color:red;">*</span> </td>
              </tr>
              <tr>
                <td><?php _e("Start Time","appointgen_ustsappointment"); ?></td>
                <td>
                	<input type="text" name="starttime" id="starttime" value="" /><span style="color:red;">*</span> 
                  <span class="timeslot_note"><?php _e("example: 09:30:00 (time in 24 hr format)","appointgen_ustsappointment"); ?></span>	
                </td>
              </tr>
              <tr>
                <td><?php _e("End Time","appointgen_ustsappointment"); ?></td>
                <td>
                	<input type="text" name="endtime" id="endtime" value="" /><span style="color:red;">*</span> 
                  <span class="timeslot_note"><?php _e("example: 20:30:00 (time in 24 hr format)","appointgen_ustsappointment"); ?></span>
                </td>
              </tr>
              <tr>
                <td><?php _e("Time Interval","appointgen_ustsappointment"); ?></td>
                <td>
                	<input type="text" name="timeinterval" id="timeinterval" value="" /><span style="color:red;">*</span> <br>
                	<span class="timeslot_note"><?php _e("Time interval must be in Minute","appointgen_ustsappointment"); ?></span>
                </td>
              </tr>
              <tr>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td></td>
                <td>
                	<input type="submit" id="btnaddtimeslot" name="btnaddtimeslot" value="Add Timeslot" style="width:150px;background-color: #0074A2;"/>
                  <input type="hidden" id="hdntimeslotid" name="hdntimeslotid" value="" style="width:150px;"/>
                </td>
              </tr>
            </table>
            </form>
				</div>
      </div>
    </div>
   </div>
  </div>