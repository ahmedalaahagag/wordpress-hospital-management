<?php 
  global $table_prefix,$wpdb;
  $schedule = "";
  if(isset($_SESSION['scheduleid'])){
    $schedule = $_SESSION['scheduleid'];
  }
	
	if($_POST){
		$schedule = $_REQUEST['optschedules'];
	}
	$sql = "";
	$sql_schedule = "";
	$tvs_result = "";
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
		$mintime = $tvs_result[0]->mintime;
		$maxtime = $tvs_result[0]->maxtime;
		$interval = $tvs_result[0]->time_interval;	
	}
	
	$ustsappointments = $wpdb->get_results($sql);
	//==========day calculation=============
	?>
  <script type="text/javascript">
		function appointgen_submit_form(){
			var schedule = jQuery('#optschedules').val();
			var sel = jQuery("option[value=" + schedule + "]", jQuery("select[name=optschedules]") );
			if (sel.length > 0){
				sel.attr('selected', 'selected');
			}
			var schedule = jQuery("select[name=optschedules] option:selected").text();
		}
	</script>
  <style type='text/css'>
	 #calendar {
			max-width: 800px;
			margin-top: 10px;
		}
		.event {
		}
		.greenEvent {
				background-color:#00FF00;
		}
		.redEvent {
				background-color:#FF0000;
		}
		#wpfooter {
			position:relative;
		}	
  </style>
  <div style="height:auto;">
      <div id="icon-options-general" class="icon32">
      </div>
      <h2 style="padding-top:10px;"><?php _e("Appointment Calendar","appointgen_ustsappointment"); ?></h2>
      <div style="height:15px;"></div>
      <div style="padding-left:30px;">
        <div style="float:left;"><?php _e("Schedules:","appointgen_ustsappointment"); ?> </div>
        <div style="float:left;">
        <form id="frmschedules" method="post">
          <select id="optschedules" name="optschedules" > 
           	<option value="0"><?php _e("All","appointgen_ustsappointment"); ?></option>
					 <?php 
						$sql_schedule = "select * from ".$table_prefix."appointgen_schedules";
						
            $schedules = $wpdb->get_results($sql_schedule);
            foreach($schedules as $schedule){
            ?>
            <option value="<?php echo $schedule->id;?>"><?php printf(__("%s","appointgen_ustsappointment"), $schedule->schedule_name);?></option>
            <?php } ?>
          </select>
          
        </form>
        </div>
        <div style="clear:both"></div>
        <div id='calendar' style="clear:both;"></div>
        <div style="clear:both"></div>
      </div>
      <?php include_once('includes/add_appointment.php');?>
  </div>
  <div style="clear:both;"></div>
  
  <script type='text/javascript'>
	function appointgen_get_appointments(){
		var schedule = jQuery('#optschedules').val();
		jQuery.ajax({
				type: "POST",
        url: '<?php echo admin_url( 'admin-ajax.php' );?>',
				data: {
          action: 'appointgen_get_appointments_by_schedule',  
          schedule: schedule
        },
				success: function (data) {
					console.log(data);
				},
				error : function(s , i , error){
					console.log(error);
				}
				
		});
	}	
		
	function appointgen_generate_calendar(){
		 jQuery('#calendar').fullCalendar({
			header: {
				left: 'prev, next today, agenda',
				center: 'title',
				right: 'month, agendaWeek, agendaDay'
			},
			defaultView: 'agendaWeek',
			theme:true,
			selectable: true,
			selectHelper: true,
			editable: true,
			allDayDefault: false,
			dayClick: function(date, allDay, jsEvent, view) {
					 jQuery('#dtpdate').val(jQuery.datepicker.formatDate('yy-mm-dd',date));
					 jQuery("#addappointment_dialog").dialog("open");
			},
			events: [
			<?php
      foreach($ustsappointments as $appointment){ ?>
				{
					id: <?php printf(__("%d","appointgen_ustsappointment"), $appointment->appointment_id);?>,
					title: '<?php printf(__('%s','appointgen_ustsappointment'), $appointment->schedule)?>-><?php printf(__("%s","appointgen_ustsappointment"), $appointment->start_time)?><?php printf(__("%s","appointgen_ustsappointment"), $appointment->timeshift)?>-<?php printf(__("%s","appointgen_ustsappointment"), $appointment->end_time); ?><?php printf(__("%s","appointgen_ustsappointment"), $appointment->timeshift)?>',
					start: '<?php printf(__("%s","appointgen_ustsappointment"), $appointment->date);?> <?php printf(__("%s","appointgen_ustsappointment"), $appointment->start_time);?>',
					end: '<?php printf(__("%s","appointgen_ustsappointment"), $appointment->date);?> <?php printf(__("%s","appointgen_ustsappointment"), $appointment->end_time);?>',
					backgroundColor : '#ED5B45',
					editable: true
				},
			  <?php } ?>	
			],
			minTime: <?php echo intval($mintime);?>,
			maxTime: <?php echo intval($maxtime);?>,
			slotMinutes: <?php echo intval($interval);?>,
			eventColor: '#F05133'
		});
	}
	function generate_calendar_on_ajaxcall(appointments){
		jQuery('#calendar').fullCalendar({
				header: {
					left: 'prev, next today, agenda',
					center: 'title',
					right: 'month, agendaWeek, agendaDay'
				},
				defaultView: 'agendaWeek',
				theme:true,
				selectable: true,
				selectHelper: true,
				editable: true,
				dayClick: function(date, allDay, jsEvent, view) {
						 jQuery('#dtpfromdate').val(jQuery.datepicker.formatDate('yy-mm-dd',date));
						 jQuery('#dtptodate').val(jQuery.datepicker.formatDate('yy-mm-dd',date));
						 jQuery("#addappointment_dialog").dialog("open");
				},
				events: [
				],
				minTime: mintime,
				maxTime: maxtime,
				slotMinutes: interval,
				eventColor: '#F05133'
			});
	}
	jQuery(document).ready(function() {
		
		appointgen_generate_calendar();
		<?php if(isset( $_REQUEST['optschedules'])){?>
        jQuery('#optschedules').val(<?php echo $_REQUEST['optschedules']?>);
    <?php } ?>

    jQuery("#addappointment_dialog").dialog({
					autoOpen: false,
					height: 550,
					width: 500,
					modal: true,
					buttons: {
							'Add Appointment': function () {
									if(appointgen_save_appointment()){
										jQuery(this).dialog("close");
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
	
 </script>