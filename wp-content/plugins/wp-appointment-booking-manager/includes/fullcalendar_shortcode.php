<?php  
  function appointgen_ustscalendar_shortcode($atts){ 
		global $table_prefix,$wpdb;
    $schedule = "";
    if(isset($_SESSION['scheduleid_front'])){
      $schedule = $_SESSION['scheduleid_front'];
    }
		if($_POST){
			$schedule = $_REQUEST['optschedules'];
		}
		$sql = "";
		$sql_schedule = "";
		$tvs_result = array();
		$mintime = "";
		$maxtime = "";
		$interval = "";
		if($schedule==0){
			$sql = "select * from ".$table_prefix."appointgen_ustsappointments";
			$mintime = 0;
			$maxtime = 24;
			$interval = 30;
		}
		else{
			$sql = "select * from ".$table_prefix."appointgen_ustsappointments where schedule_id like '%".$schedule."%'";
			$sql_schedule = "select scd.id as scheduleid,srv.id as serviceid,tmsl.id as timeslotid,vn.id as venueid, scd.* ,srv.*,tmsl.*,vn.* from ".$table_prefix."appointgen_schedules scd inner join ".$table_prefix."appointgen_services srv on scd.service = srv.id 
	inner join ".$table_prefix."appointgen_timeslot tmsl on tmsl.id = scd.timeslot
	inner join ".$table_prefix."appointgen_venues vn on vn.id = scd.venue 
	where scd.id=".$schedule;
			$tvs_result = $wpdb->get_results($sql_schedule);
      if(isset($tvs_result[0])){
        $mintime = $tvs_result[0]->mintime;
        $maxtime = $tvs_result[0]->maxtime;
        $interval = $tvs_result[0]->time_interval;	
      }
		}
		$ustsappointments = $wpdb->get_results($sql);
		$output = "<style type='text/css'";
    include_once USTSAPPOINTMENT_DIR.'operations/get_cssfixfront.php';
		$output .= '</style><script type="text/javascript">
			function appointgen_submit_form(){
				var schedule = jQuery("#optschedules").val();
				var sel = jQuery("option[value=" + schedule + "]", jQuery("select[name=optschedules]") );
				if (sel.length > 0){
					sel.attr("selected", "selected");
				}
				var schedule = jQuery("select[name=optschedules] option:selected").text();
			}
		</script>
		<style type="text/css">
				#calendar {
            max-width: 800px;
					}
					.event {
					}
					.greenEvent {
							background-color:#00FF00;
					}
					.redEvent {
							background-color:#FF0000;
					}
					table{
						margin:0!important;
					}
				</style>
				
				<div style="">
						<div style="float:left;">Schedules: </div>
						<div style="float:left;">
						<form id="frmschedules" method="post">
							<select id="optschedules" name="optschedules">
								<option value="0">All</option>';	
								$sql_schedule = "select * from ".$table_prefix."appointgen_schedules";
								$schedules = $wpdb->get_results($sql_schedule);
								foreach($schedules as $schedule){
                  $output .= '<option value="'.$schedule->id.'">'.$schedule->schedule_name.'</option>';
								}
							$output .='</select> 
						</form>
						</div>
						<div style="clear:both"></div>
						<div id="calendar"></div>
						<div style="clear:both"></div>
      	</div>
				<div id="calendar"></div>
				';
				include_once('add_appointment_front_popup.php');	
				$output .= "<script type='text/javascript'>
				function appointgen_generate_calendar(){
					jQuery('#calendar').fullCalendar({
						header: {
							left: 'prev, next today, agenda',
							center: 'title',
							right: 'month,agendaWeek,agendaDay'
						},
						defaultView: 'agendaWeek',
						theme:true,
						selectable: true,
						selectHelper: true,
						editable: true,
						allDayDefault: false,
						dayClick: function(date, allDay, jsEvent, view) {
								 jQuery('#dtpdate').val(jQuery.datepicker.formatDate('yy-mm-dd',date)); 
                jQuery('#addappointment_dialog').dialog('open');
						},
						events: [";
						foreach($ustsappointments as $appointment){
						$output .="
							{
								id: '".$appointment->appointment_id."',
                title: ' ".$appointment->schedule."->".$appointment->start_time.$appointment->timeshift."-".$appointment->end_time.$appointment->timeshift."', 
                start: '".$appointment->date." ".$appointment->start_time."',
                end: '".$appointment->date." ".$appointment->end_time."',
                backgroundColor : '#ED5B45',
                editable: true
							},";
						}	
						$output .="],
						minTime:".intval($mintime).",
						maxTime:".intval($maxtime).",
						slotMinutes:".intval($interval).",
						eventColor: '#F05133'
					});
				}
				jQuery(document).ready(function() {
						appointgen_generate_calendar();";
            if(isset($_REQUEST["optschedules"])){
              $output .="jQuery('#optschedules').val('".$_REQUEST["optschedules"]."');";
            }
						$output .="jQuery('#addappointment_dialog').dialog({
								autoOpen: false,
								height: 603,
								width: 550,
								modal: true,
								buttons: {
										'Add Appointment': function () {
												if(appointgen_save_appointment()){
													jQuery(this).dialog('close');
												}
												else{
												}
										},
										Cancel: function () {
												jQuery(this).dialog('close');
												appointgen_cleardata();
										}
								},
				
								close: function () {
									appointgen_cleardata();
								}
				
						});
						
					});
				</script>";
			return $output;		
	}
	add_shortcode('appointgen_ustscalendar','appointgen_ustscalendar_shortcode');