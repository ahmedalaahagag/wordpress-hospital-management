<?php
$obj_appointment = new Hmgt_appointment();
$appointment_data = $obj_appointment->get_all_appointment();
$appointment_array = array();
if (!empty ($appointment_data)) {
    foreach ($appointment_data as $appointment) {
        $patient_data = get_user_detail_byid($appointment->patient_id);
        $patient_name = $patient_data['first_name'] . " " . $patient_data['last_name'] . "(" . $patient_data['patient_id'] . ")";
        $doctor_data = get_user_detail_byid($appointment->doctor_id);
        $doctor_name = $doctor_data['first_name'] . " " . $doctor_data['last_name'];
        $appointment_start_date = date('Y-m-d H:i:s', strtotime($appointment->appointment_time_string));
        //$appointment_date=$appointment->appointment_time_string;
        $appointment_enddate = date('Y-m-d H:i:s', strtotime($appointment_start_date) + 900);
        $i = 1;
        $appointment_array [] = array(
            'title' => 'Detail',
            'start' => $appointment_start_date,
            'end' => $appointment_enddate,
            'patient_name' => $patient_name,
            'doctor_name' => $doctor_name
        );

    }
}


$all_event = "";
$args['post_type'] = 'hmgt_event';
$args['posts_per_page'] = -1;
$args['post_status'] = 'public';
$q = new WP_Query();
$all_event = $q->query($args);
$event_array = array();
if (!empty ($all_event)) {
    foreach ($all_event as $event) {
        $event_start_date = get_post_meta($event->ID, 'start_date', true);
        $event_end_date = get_post_meta($event->ID, 'end_date', true);
        $i = 1;

        $event_array [] = array(
            'title' => $event->post_title,
            'start' => mysql2date('Y-m-d', $event_start_date),
            'end' => date('Y-m-d', strtotime($event_end_date . ' +' . $i . ' days'))
        );

    }
}
//echo json_encode($event_array);
?>
    <div class="page-inner" style="min-height:1088px !important">
        <div class="page-title">
            <h3><img src="<?php echo get_option('hmgt_hospital_logo') ?>" class="img-circle head_logo" width="40"
                     height="40"/><?php echo get_option('hmgt_hospital_name'); ?>
            </h3>
        </div>
        <div id="main-wrapper">

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-white">
                        <div class="panel-body">
                            <div id="calendar"></div>


                        </div>
                    </div>
                </div>

                <!--<div class="col-md-4">
				<div class="panel panel-white">
					<div class="panel-heading">
						<h3 class="panel-title"><?php _e('Events', 'hospital_mgt'); ?></h3>
						
					</div>
					<div class="panel-body">
					
					<div class="events">
					
					
					<?php
                $args['post_type'] = 'hmgt_event';
                $args['posts_per_page'] = -1;
                $args['post_status'] = 'public';
                $q = new WP_Query();
                $retrieve_class = $q->query($args);
                foreach ($retrieve_class as $retrieved_data) { ?>
					<div class="calendar-event"> 
					<p>
					<?php echo $retrieved_data->post_title; ?>
					</p>
					<p>
					<?php echo "<b>" . __('Start Date: ', '') . "</b>" . get_post_meta($retrieved_data->ID, 'start_date', true); ?></p>
					
					<p>
					<?php echo "<b>" . __('End Date: ', '') . "</b>" . get_post_meta($retrieved_data->ID, 'end_date', true); ?></p>
					
					</div>	
					<?php } ?>
					
					
					</div>
					
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="panel panel-white">
					<div class="panel-heading">
						<h3 class="panel-title"><?php _e('Notice', 'hospital_mgt'); ?></h3>
						
					</div>
					<div class="panel-body">
					
					<div class="events">
					
					
					<?php
                $args['post_type'] = 'hmgt_notice';
                $args['posts_per_page'] = -1;
                $args['post_status'] = 'public';
                $q = new WP_Query();
                $retrieve_class = $q->query($args);
                foreach ($retrieve_class as $retrieved_data) { ?>
					<div class="calendar-event"> 
					<p>
					<?php echo $retrieved_data->post_title; ?>
					</p>
					</div>
					<?php } ?>
					
					
					</div>
					
					</div>
				</div>
			</div>-->

            </div>
            <div class="row">
                <div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
                    <a href="<?php echo admin_url() . 'admin.php?page=hmgt_patient'; ?>">
                        <div class="panel info-box panel-white">
                            <div class="panel-body patient">
                                <div class="info-box-stats">
                                    <p class="counter"><?php echo count(get_users(array('role' => 'patient'))); ?></p>

                                    <span
                                        class="info-box-title"><?php echo esc_html(__('Patient', 'hospital_mgt')); ?></span>
                                </div>
                                <img src="<?php echo HMS_PLUGIN_URL . "/assets/images/dashboard/patient.png" ?>"
                                     class="dashboard_background">
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
                    <a href="<?php echo admin_url() . 'admin.php?page=hmgt_doctor'; ?>">
                        <div class="panel info-box panel-white">
                            <div class="panel-body doctor">
                                <div class="info-box-stats">
                                    <p class="counter"><?php echo count(get_users(array('role' => 'doctor'))); ?></p>
                                    <span
                                        class="info-box-title"><?php echo esc_html(__('Doctor', 'hospital_mgt')); ?></span>
                                </div>
                                <img src="<?php echo HMS_PLUGIN_URL . "/assets/images/dashboard/doctor.png" ?>"
                                     class="dashboard_background">

                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
                    <a href="<?php echo admin_url() . 'admin.php?page=hmgt_nurse'; ?>">
                        <div class="panel info-box panel-white">
                            <div class="panel-body nurse">
                                <div class="info-box-stats">
                                    <p class="counter"><?php echo count(get_users(array('role' => 'nurse'))); ?></p>
                                    <span
                                        class="info-box-title"><?php echo esc_html(__('Nurse', 'hospital_mgt')); ?></span>
                                </div>
                                <img src="<?php echo HMS_PLUGIN_URL . "/assets/images/dashboard/nurse.png" ?>"
                                     class="dashboard_background">
                                <!-- <div class="info-box-icon">
                                    <i class="fa fa-plus-square"></i>
                                </div> -->

                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
                    <a href="<?php echo admin_url() . 'admin.php?page=hmgt_receptionist'; ?>">
                        <div class="panel info-box panel-white">
                            <div class="panel-body receptionist">
                                <div class="info-box-stats">
                                    <p class="counter"><?php echo count(get_users(array('role' => 'receptionist'))); ?></p>

                                    <span
                                        class="info-box-title"><?php echo esc_html(__('Support Staff', 'hospital_mgt')); ?></span>
                                </div>
                                <img src="<?php echo HMS_PLUGIN_URL . "/assets/images/dashboard/support-staft.png" ?>"
                                     class="dashboard_background">

                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
                    <a href="<?php echo admin_url() . 'admin.php?page=hmgt_message'; ?>">
                        <div class="panel info-box panel-white">
                            <div class="panel-body message">
                                <div class="info-box-stats">
                                    <p class="counter"><?php
                                        $obj_message = new Hmgt_message();
                                        $message = $obj_message->hmgt_count_inbox_item(get_current_user_id());
                                        echo count($message);
                                        ?></p>

                                    <span
                                        class="info-box-title"><?php echo esc_html(__('Message', 'hospital_mgt')); ?></span>
                                </div>
                                <img src="<?php echo HMS_PLUGIN_URL . "/assets/images/dashboard/message.png" ?>"
                                     class="dashboard_background">

                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
                    <a href="<?php echo admin_url() . 'admin.php?page=hmgt_gnrl_settings'; ?>">
                        <div class="panel info-box panel-white">
                            <div class="panel-body setting">
                                <div class="info-box-stats">
                                    <p class="counter">
                                        &nbsp;<?php //echo count(get_users(array('role'=>'laboratorist')));?></p>
                                    <span
                                        class="info-box-title"><?php echo esc_html(__('Setting', 'hospital_mgt')); ?></span>
                                </div>
                                <img src="<?php echo HMS_PLUGIN_URL . "/assets/images/dashboard/setting-image.png" ?>"
                                     class="dashboard_background">
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
                    <a href="<?php echo admin_url() . 'admin.php?page=hmgt_appointment'; ?>">
                        <div class="panel info-box panel-white">
                            <div class="panel-body appointment">
                                <div class="info-box-stats">
                                    <p class="counter"><?php hmgt_tables_rows('hmgt_appointment'); ?></p>
                                    <span
                                        class="info-box-title"><?php echo esc_html(__('Appointment', 'hospital_mgt')); ?></span>
                                </div>
                                <img
                                    src="<?php echo HMS_PLUGIN_URL . "/assets/images/dashboard/appointment-image.png" ?>"
                                    class="dashboard_background">
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
                    <a href="<?php echo admin_url() . 'admin.php?page=hmgt_prescription'; ?>">
                        <div class="panel info-box panel-white">
                            <div class="panel-body prescription">
                                <div class="info-box-stats">
                                    <p class="counter"><?php hmgt_tables_rows('hmgt_priscription'); ?></p>

                                    <span
                                        class="info-box-title"><?php echo esc_html(__('Prescription', 'hospital_mgt')); ?></span>
                                </div>
                                <img
                                    src="<?php echo HMS_PLUGIN_URL . "/assets/images/dashboard/preseription-image.png" ?>"
                                    class="dashboard_background">

                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
                    <a href="<?php echo admin_url() . 'admin.php?page=hmgt_bedallotment'; ?>">
                        <div class="panel info-box panel-white">
                            <div class="panel-body assignbed">
                                <div class="info-box-stats">
                                    <p class="counter"><?php hmgt_tables_rows('hmgt_bed_allotment'); ?></p>

                                    <span
                                        class="info-box-title"><?php echo _e(__('Assign <BR> Bed/Nurse', 'hospital_mgt')); ?></span>
                                </div>
                                <img
                                    src="<?php echo HMS_PLUGIN_URL . "/assets/images/dashboard/assign-bed-image.png" ?>"
                                    class="dashboard_background">

                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
                    <a href="<?php echo admin_url() . 'admin.php?page=hmgt_treatment'; ?>">
                        <div class="panel info-box panel-white">
                            <div class="panel-body treatment">
                                <div class="info-box-stats">
                                    <p class="counter"><?php hmgt_tables_rows('hmgt_treatment'); ?></p>

                                    <span
                                        class="info-box-title"><?php echo esc_html(__('Treatment', 'hospital_mgt')); ?></span>
                                </div>
                                <img src="<?php echo HMS_PLUGIN_URL . "/assets/images/dashboard/tretment-image.png" ?>"
                                     class="dashboard_background">

                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
                    <a href="<?php echo admin_url() . 'admin.php?page=hmgt_event'; ?>">
                        <div class="panel info-box panel-white">
                            <div class="panel-body eventnotice">
                                <div class="info-box-stats">
                                    <p class="counter">
                                        <?php
                                        $args['post_type'] = array('hmgt_event', 'hmgt_notice');
                                        $args['posts_per_page'] = -1;
                                        $args['post_status'] = 'public';
                                        $q = new WP_Query();
                                        $retrieve_class = $q->query($args);
                                        echo count($retrieve_class);
                                        ?></p>

                                    <span
                                        class="info-box-title"><?php echo _e(__('Events/ <BR> Notice', 'hospital_mgt')); ?></span>
                                </div>
                                <img
                                    src="<?php echo HMS_PLUGIN_URL . "/assets/images/dashboard/notice-event-image.png" ?>"
                                    class="dashboard_background">

                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
                    <a href="<?php echo admin_url() . 'admin.php?page=hmgt_report'; ?>">
                        <div class="panel info-box panel-white">
                            <div class="panel-body operation_report">
                                <div class="info-box-stats">
                                    <p class="counter">
                                        &nbsp;<?php //hmgt_report_tables_rows('hmgt_report','Operation');?></p>

                                    <span
                                        class="info-box-title"><?php echo esc_html(__('Report', 'hospital_mgt')); ?></span>
                                </div>
                                <img src="<?php echo HMS_PLUGIN_URL . "/assets/images/dashboard/report.png" ?>"
                                     class="dashboard_background">
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
                    <a href="<?php echo admin_url() . 'admin.php?page=hmgt_pharmacist'; ?>">
                        <div class="panel info-box panel-white">
                            <div class="panel-body pharmacist">
                                <div class="info-box-stats">
                                    <p class="counter"><?php echo count(get_users(array('role' => 'pharmacist'))); ?></p>

                                    <span
                                        class="info-box-title"><?php echo esc_html(__('Pharmacist', 'hospital_mgt')); ?></span>
                                </div>
                                <img src="<?php echo HMS_PLUGIN_URL . "/assets/images/dashboard/pharmacist.png" ?>"
                                     class="dashboard_background">
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
                    <a href="<?php echo admin_url() . 'admin.php?page=hmgt_medicine'; ?>">
                        <div class="panel info-box panel-white">
                            <div class="panel-body medicine">
                                <div class="info-box-stats">
                                    <p class="counter"><?php hmgt_tables_rows('hmgt_medicine'); ?></p>

                                    <span
                                        class="info-box-title"><?php echo esc_html(__('Medicine', 'hospital_mgt')); ?></span>
                                </div>
                                <img src="<?php echo HMS_PLUGIN_URL . "/assets/images/dashboard/medicine.png" ?>"
                                     class="dashboard_background">

                            </div>
                        </div>
                    </a>
                </div>


                <div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
                    <a href="<?php echo admin_url() . 'admin.php?page=hmgt_laboratorist'; ?>">
                        <div class="panel info-box panel-white">
                            <div class="panel-body laboratorist">
                                <div class="info-box-stats">
                                    <p class="counter"><?php echo count(get_users(array('role' => 'laboratorist'))); ?></p>

                                    <span
                                        class="info-box-title"><?php echo _e(__('Laboratory <BR> Staff', 'hospital_mgt')); ?></span>
                                </div>
                                <img src="<?php echo HMS_PLUGIN_URL . "/assets/images/dashboard/laboratorist.png" ?>"
                                     class="dashboard_background">
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
                    <a href="<?php echo admin_url() . 'admin.php?page=hmgt_diagnosis'; ?>">
                        <div class="panel info-box panel-white">
                            <div class="panel-body diagnosis">
                                <div class="info-box-stats">
                                    <p class="counter"><?php hmgt_tables_rows('hmgt_diagnosis'); ?></p>

                                    <span
                                        class="info-box-title"><?php echo _e(__('Diagnosis <BR> Report', 'hospital_mgt')); ?></span>
                                </div>
                                <img src="<?php echo HMS_PLUGIN_URL . "/assets/images/dashboard/diagnosis-image.png" ?>"
                                     class="dashboard_background">

                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
                    <a href="<?php echo admin_url() . 'admin.php?page=hmgt_accountant'; ?>">
                        <div class="panel info-box panel-white">
                            <div class="panel-body accountant">
                                <div class="info-box-stats">
                                    <p class="counter"><?php echo count(get_users(array('role' => 'accountant'))); ?></p>
                                    <span
                                        class="info-box-title"><?php echo esc_html(__('Accountant', 'hospital_mgt')); ?></span>
                                </div>
                                <!-- <div class="info-box-icon">
                                    <i class="fa fa-money"></i>
                                </div> -->
                                <img src="<?php echo HMS_PLUGIN_URL . "/assets/images/dashboard/accountant.png" ?>"
                                     class="dashboard_background">
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
                    <a href="<?php echo admin_url() . 'admin.php?page=hmgt_invoice'; ?>">
                        <div class="panel info-box panel-white">
                            <div class="panel-body invoice">
                                <div class="info-box-stats">
                                    <p class="counter"><?php hmgt_tables_rows('hmgt_invoice'); ?></p>

                                    <span
                                        class="info-box-title"><?php echo esc_html(__('Invoice', 'hospital_mgt')); ?></span>
                                </div>
                                <img src="<?php echo HMS_PLUGIN_URL . "/assets/images/dashboard/invoice.png" ?>"
                                     class="dashboard_background">
                            </div>
                        </div>
                    </a>
                </div>
            </div>

        </div>

    </div>
    <div id="createEventModal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="createEventModal"
         aria-hidden="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Add Appointment</h4>
                </div>
                <div class="modal-body">
                    <form id="createAppointmentForm" class="form-horizontal">
                        <div class="control-group">
                            <label class="control-label" for="inputPatient">Patient:</label>
                            <div class="controls">
                                <input type="text" class="form-control pull-left" name="patientName" id="patientName" tyle="margin: 0 auto;" data-provide="typeahead" data-items="4" data-source="[&quot;Value 1&quot;,&quot;Value 2&quot;,&quot;Value 3&quot;]">
                                <label class="control-label pull-left" for="inputPatient">Patient Number:</label>
                                <input type="text" class="form-control pull-left" name="patientNumber" id="patientNumber" tyle="margin: 0 auto;" data-provide="typeahead" data-items="4" data-source="[&quot;Value 1&quot;,&quot;Value 2&quot;,&quot;Value 3&quot;]">

                                <input type="hidden" id="apptStartTime"/>
                                <input type="hidden" id="apptEndTime"/>
                                <input type="hidden" id="apptAllDay"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls controls-row" id="when" style="margin-top:5px;">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitButton">Save</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            var calendar = $('#calendar').fullCalendar({
                defaultView: 'agendaWeek',
                editable: true,
                selectable: true,
                //header and other values
                select: function (start, end, allDay) {
                    endtime = moment(end).format('MM-DD-YYYY , hh:mm');
                    starttime = moment(start).format('MM-DD-YYYY , hh:mm');
                    var mywhen = 'From :' + starttime + ' To ' + endtime;
                    $('#createEventModal #apptStartTime').val(start);
                    $('#createEventModal #apptEndTime').val(end);
                    $('#createEventModal #apptAllDay').val(allDay);
                    $('#createEventModal #when').text(mywhen);
                    $('#createEventModal').modal('show');

                },
                editable : true,
        });
        });
            $('#submitButton').on('click', function (e) {
                // We don't want this to act as a link so cancel the link action
                e.preventDefault();
                doSubmit();
            });

            function doSubmit() {
                $("#createEventModal").modal('hide');
                console.log($('#apptStartTime').val());
                console.log($('#apptEndTime').val());
                console.log($('#apptAllDay').val());
                $("#calendar").fullCalendar('renderEvent',
                    {
                        title: $('#patientName').val(),
                        start: moment($('#apptStartTime').val()).subtract('hours', 2).toDate(),
                        end: moment($('#apptEndTime').val()).subtract('hours', 2).toDate(),
                        allDay: ($('#apptAllDay').val() == "true"),
                    },
                    true);
            }

    </script>
<?php
?>