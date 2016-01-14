<style type="text/css">
	.timeslot_note{
		font-style:italic;
		font-size:12px;
	}
	.cs_popup_overlay{
		width: 100%;
		height: 100%;
		background: #000;
		position: absolute;
		z-index: 15001;
		top: 0px;
		left: 0px;
		opacity: 0.5;
	}
	
	.cs_popup_content{
		width: 37%;
		height: 400px;
		background: #fff;
		position: fixed;
		z-index: 15002;
		top: 50px;
		left: 30%;
	}
	
	.cs_popup_header{
		width: 100%;
		height:50px;
		background: #f3f3f3;
		border-bottom: solid 1px #ccc;
	}
	.cs_popup_close{
		width: 24px;
		height: 22px;
		color:#fff;
		background: #000;
		float: right;
		border-radius:14px;
		text-align: center;
		font-weight: bold;
		cursor: pointer;
		padding-top: 2px;
		margin: -20px -30px 0px 10px;
	}
	.cs_popup_close:hover{
		background: #ff0000;
	}
	.cs_popup_body{
		width: 100%;
		height:495px;
		overflow: auto;
	}
	.cs_popup_title{
		float: left;
		padding: 5px 10px 0px 15px;
	}
	
	.cs_prod_title{
		font-size: 20px;
		font-weight: bold;
		padding-top: 10px;
	}
	.buttons{
		padding: 300px 10px 10px 280px;
	}
	#srv_popup_content{
		width: 50%;
		height: 530px;
		background: #fff;
		position: fixed;
		z-index: 15002;
		top: 50px;
		left: 30%;
	}
	.srv_buttons{
		padding: 300px 10px 10px 430px;
	}
  .vnu_buttons{
		padding: 300px 10px 10px 280px;
	}
</style>
<?php 
global $table_prefix,$wpdb;
$scheduleid = 0;
if($_POST){
  $scheduleid = $_REQUEST['id'];
}
$sql_schedule = "select * from ".$table_prefix."appointgen_schedules where id=".$scheduleid;
$result_schedule = $wpdb->get_results($sql_schedule);
$sql_timeslot = "select * from ".$table_prefix."appointgen_timeslot";
$result_timeslot = $wpdb->get_results($sql_timeslot);

$sql_service = "select * from ".$table_prefix."appointgen_services";
$result_service = $wpdb->get_results($sql_service);

$sql_venue = "select * from ".$table_prefix."appointgen_venues";
$result_venue = $wpdb->get_results($sql_venue);

?>
<div class="wrapper">
  <div class="wrap" style="float:left; width:100%;">
    <div id="icon-options-general" class="icon32"><br />
    </div>
    <h2><?php _e("Schedule","appointgen_ustsappointment"); ?></h2>
    <div class="main_div">
     	<div class="metabox-holder" style="width:69%; float:left;">
        <div id="namediv" class="stuffbox" style="width:99%;">
        <h3 class="top_bar"><?php _e("Add Schedule","appointgen_ustsappointment"); ?></h3>
            <form id="frmaddschedule" method="post" action="" novalidate="novalidate">
            <table style="padding:10px;">
              <tr>
                <td><?php _e("Schedule Name","appointgen_ustsappointment"); ?></td>
                <td><input type="text" name="schedule_name" id="schedule_name" value="<?php if(isset($result_schedule[0])) printf(__("%s","appointgen_ustsappointment"),$result_schedule[0]->schedule_name)  ; ?>" /><span style="color:red;">*</span> </td>
              </tr>
              <tr>
                <td><?php _e("TimeSlot","appointgen_ustsappointment"); ?></td>
                <td>
                	<select name="timeslot" id="timeslot">
                  	<?php foreach($result_timeslot as $timeslot){
                      if(isset($result_schedule[0])){  
                        if($timeslot->id == $result_schedule[0]->timeslot){?>
                          <option value="<?php echo $timeslot->id;?>" selected="selected" ><?php printf(__("%s","appointgen_ustsappointment"), $timeslot->timeslot_name);?></option>
                   <?php } }else{?>
                          <option value="<?php echo $timeslot->id;?>"><?php printf(__("%s","appointgen_ustsappointment"), $timeslot->timeslot_name);?></option>
                    <?php 
                        } 
                       
                    }
                    ?>
                  </select> 
                </td>
              </tr>
              <tr>
                <td><?php _e("Service","appointgen_ustsappointment"); ?></td>
                <td>
                	<select name="optservice" id="optservice">
                  	<?php foreach($result_service as $service){
                      if(isset($result_schedule[0])){
                      if($service->id == $result_schedule[0]->service){ ?>
                        <option value="<?php echo $service->id?>" selected="selected" ><?php printf(__("%s","appointgen_ustsappointment"), $service->service_name);?></option>
                    <?php } }else{ ?>
                       <option value="<?php echo $service->id?>"><?php printf(__("%s","appointgen_ustsappointment"), $service->service_name);?></option>
                    <?php 
                        } 
                      
                    }
                    ?>
                  </select> 
                </td>
              </tr>
              <tr>
                <td><?php _e("Venue","appointgen_ustsappointment"); ?></td>
                <td>
                	<select name="optvenue" id="optvenue">
                  	<?php foreach($result_venue as $venue){ 
                      if(isset($result_schedule[0])){
                      if($venue->id == $result_schedule[0]->venue){ ?>
                        <option value="<?php echo $venue->id?>" selected="selected" ><?php printf(__("%s","appointgen_ustsappointment"), $venue->venue_name);?></option>
                    <?php } } else{ ?>
                        <option value="<?php echo $venue->id?>"><?php printf(__("%s","appointgen_ustsappointment"), $venue->venue_name);?></option>
                    <?php 
                        }
                      
                     }
                    ?>
                  </select> 
                </td>
              </tr>
              <tr><td colspan='2'></td></tr>
              <tr>
                <td></td>
                <td>
                	<input type="submit" id="btnaddschedule" name="btnaddschedule" value="<?php _e("Add Schedule","appointgen_ustsappointment"); ?>" style="width:150px;background-color: #0074A2;"/>
                  <input type="hidden" id="hdnscheduleid" name="hdnscheduleid" value="" style="width:150px;"/>
                </td>
              </tr>
            </table>
            </form>
				</div>
      </div>
    </div>
   </div>
  </div>
<script type="text/javascript">
	jQuery(document).ready(function(){
			var calltype = appointgen_getUrlVars()["calltype"];
			if(calltype){
				if(calltype == 'editschedule'){
					<?php
          if(isset($_REQUEST['id'])){
            $id = $_REQUEST['id'];
            global $table_prefix,$wpdb;
            $sql = "select * from ".$table_prefix."appointgen_schedules where id=".$id;
            $result = $wpdb->get_results($sql);
            ?>
            var schedule = <?php echo json_encode($result[0]);?>;
            jQuery('#hdnscheduleid').val(schedule['id']);
            jQuery('#schedule_name').val(schedule['schedule_name']);
            jQuery('#timeslot').val(schedule['timeslot']);
            jQuery('#optservice').val(schedule['service']);
            jQuery('#optvenue').val(schedule['venue']);
          <?php } ?>  
				}
			}	
			});
</script>