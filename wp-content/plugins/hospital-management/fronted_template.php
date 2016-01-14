<?php
require_once(ABSPATH . 'wp-admin/includes/user.php');
$obj_hospital = new Hospital_Management(get_current_user_id());
if (!is_user_logged_in()) {
    $page_id = get_option('hmgt_login_page');

    wp_redirect(home_url() . "?page_id=" . $page_id);
}
if (is_super_admin()) {
    wp_redirect(admin_url() . 'admin.php?page=hospital');
}
//var_dump($obj_hospital->patient);
//echo "patient =>".count($obj_hospital->patient);
//echo "<BR>";
//echo "Appointment => ".count($obj_hospital->appointment);
//echo "<BR>";
//echo "Event => ".count($obj_hospital->events);
//echo "<BR>";
//echo "notice => ".count($obj_hospital->notice);
$sessions = new Hmgt_session();
$appointment_data = $obj_hospital->appointment;
$appointment_array = array();
if (!empty ($appointment_data)) {
    foreach ($appointment_data as $appointment) {
        $patient_data = get_user_detail_byid($appointment->patient_id);
        $patient_name = $patient_data['first_name'] . " " . $patient_data['last_name'] . "(" . $patient_data['patient_id'] . ")";
        $doctor_data = get_user_detail_byid($appointment->doctor_id);
        $doctor_name = $doctor_data['first_name'] . " " . $doctor_data['last_name'];
        $appointment_start_date = date('Y-m-d H:i:s', strtotime($appointment->appointment_time_string));
        //$appointment_date=$appointment->appointment_time_string;
        $appointment_enddate = date('Y-m-d H:i:s', strtotime($appointment->appointment_end_time_string));
        $i = 1;
        $appointment_array [] = array(
            'title' => 'Details :',
            'start' => $appointment_start_date,
            'end' => $appointment_enddate,
            'patient_name' => $patient_name,
            'doctor_name' => $doctor_name
        );
    }
}
//echo json_encode($appointment_array);
//var_dump($obj_hospital->patient);
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="<?php echo HMS_PLUGIN_URL . '/assets/css/dataTables.css'; ?>">
        <link rel="stylesheet" href="<?php echo HMS_PLUGIN_URL . '/assets/css/dataTables.editor.min.css'; ?>">
        <link rel="stylesheet" href="<?php echo HMS_PLUGIN_URL . '/assets/css/dataTables.tableTools.css'; ?>">
        <link rel="stylesheet" href="<?php echo HMS_PLUGIN_URL . '/assets/css/jquery-ui.css'; ?>">
        <link rel="stylesheet" href="<?php echo HMS_PLUGIN_URL . '/assets/css/font-awesome.min.css'; ?>">
        <link rel="stylesheet" href="<?php echo HMS_PLUGIN_URL . '/assets/css/popup.css'; ?>">
        <link rel="stylesheet" href="<?php echo HMS_PLUGIN_URL . '/assets/css/style.css'; ?>">
        <link rel="stylesheet" href="<?php echo HMS_PLUGIN_URL . '/assets/css/custom.css'; ?>">
        <link rel="stylesheet" href="<?php echo HMS_PLUGIN_URL . '/assets/css/fullcalendar.css'; ?>">
        <link rel="stylesheet" href="<?php echo HMS_PLUGIN_URL . '/assets/css/bootstrap-timepicker.min.css'; ?>">
        <link rel="stylesheet" href="<?php echo HMS_PLUGIN_URL . '/assets/css/bootstrap.min.css'; ?>">
        <link rel="stylesheet" href="<?php echo HMS_PLUGIN_URL . '/assets/css/bootstrap-multiselect.css'; ?>">
        <link rel="stylesheet" href="<?php echo HMS_PLUGIN_URL . '/assets/css/white.css'; ?>">
        <link rel="stylesheet" href="<?php echo HMS_PLUGIN_URL . '/assets/css/hospitalmgt.min.css'; ?>">
        <?php if (is_rtl()) {
            ?>
         <link rel="stylesheet" href="<?php echo HMS_PLUGIN_URL . '/assets/css/bootstrap-rtl.min.css'; ?>">
        <?php } ?>
        <link rel="stylesheet" href="<?php echo HMS_PLUGIN_URL . '/assets/css/simple-line-icons.css'; ?>">
        <link rel="stylesheet"
              href="<?php echo HMS_PLUGIN_URL . '/lib/validationEngine/css/validationEngine.jquery.css'; ?>">
        <link rel="stylesheet" href="<?php echo HMS_PLUGIN_URL . '/assets/css/hospital-responsive.css'; ?>">
        <script type="text/javascript" src="<?php echo HMS_PLUGIN_URL . '/assets/js/jquery-1.11.1.min.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo HMS_PLUGIN_URL . '/assets/js/jquery-ui.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo HMS_PLUGIN_URL . '/assets/js/moment.min.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo HMS_PLUGIN_URL . '/assets/js/fullcalendar.min.js'; ?>"></script>
        <script type="text/javascript"
                src="<?php echo HMS_PLUGIN_URL . '/assets/js/jquery.dataTables.min.js'; ?>"></script>
        <script type="text/javascript"
                src="<?php echo HMS_PLUGIN_URL . '/assets/js/dataTables.tableTools.min.js'; ?>"></script>
        <script type="text/javascript"
                src="<?php echo HMS_PLUGIN_URL . '/assets/js/dataTables.editor.min.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo HMS_PLUGIN_URL . '/assets/js/bootstrap.min.js'; ?>"></script>
        <script type="text/javascript"
                src="<?php echo HMS_PLUGIN_URL . '/assets/js/bootstrap-timepicker.min.js'; ?>"></script>
       <script type="text/javascript"
                src="<?php echo HMS_PLUGIN_URL . '/assets/js/bootstrap-multiselect.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo HMS_PLUGIN_URL . '/assets/js/responsive-tabs.js'; ?>"></script>
        <script type="text/javascript"
                src="<?php echo HMS_PLUGIN_URL . '/lib/validationEngine/js/languages/jquery.validationEngine-en.js'; ?>"></script>
        <script type="text/javascript"
                src="<?php echo HMS_PLUGIN_URL . '/lib/validationEngine/js/jquery.validationEngine.js'; ?>"></script>
    </head>
    <body class="hospital-management-content">
    <?php
    $user = wp_get_current_user();
    ?>
    <div class="container-fluid mainpage">
        <div class="navbar">
            <div class="col-md-8 col-sm-8 col-xs-6">
                <h3><img src="<?php echo get_option('hmgt_hospital_logo') ?>" class="img-circle head_logo" width="40"
                         height="40"/>
                    <span><?php echo get_option('hmgt_hospital_name'); ?></span>
                </h3></div>
            <ul class="nav navbar-right col-md-4 col-sm-4 col-xs-6">
                <!-- BEGIN USER LOGIN DROPDOWN -->
                <li class="dropdown"><a data-toggle="dropdown"
                                        class="dropdown-toggle" href="javascript:;">
                        <?php
                        $userimage = get_user_meta($user->ID, 'hmgt_user_avatar', true);
                        if (empty ($userimage)) {
                            echo '<img src=' . get_default_userprofile($obj_hospital->role) . ' height="40px" width="40px" class="img-circle" />';
                        } else
                            echo '<img src=' . $userimage . ' height="40px" width="40px" class="img-circle"/>';
                        ?>
                        <span>	<?php echo $user->display_name; ?> </span> <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu extended logout">
                        <li><a href="?dashboard=user&page=account"><i class="fa fa-user"></i>
                                <?php _e('My Profile', 'hospital_mgt'); ?></a></li>
                        <li><a href="<?php echo wp_logout_url(home_url()); ?>"><i
                                    class="fa fa-sign-out m-r-xs"></i><?php _e('Log Out', 'hospital_mgt'); ?> </a></li>
                    </ul>
                </li>
                <!-- END USER LOGIN DROPDOWN -->
            </ul>

        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2 nopadding hospital_left">
                <!--  Left Side -->
                <?php

                $menu = hmgt_menu();
                $class = "";
                if (!isset ($_REQUEST ['page']))
                    $class = 'class = "active"';
                //print_r($menu); 	?>
                <ul class="nav nav-pills nav-stacked">
                    <li><a href="<?php echo site_url(); ?>"><span class="icone"><img
                                    src="<?php echo plugins_url('hospital-management/assets/images/icon/home.png') ?>"/></span><span
                                class="title"><?php _e('Home', 'hospital_mgt'); ?></span></a></li>
                    <li <?php echo $class; ?>><a href="?dashboard=user"><span class="icone"><img
                                    src="<?php echo plugins_url('hospital-management/assets/images/icon/dashboard.png') ?>"/></span><span
                                class="title"><?php _e('Dashboard', 'hospital_mgt'); ?></span></a></li>
                    <?php
                    $role = $obj_hospital->role;
                    foreach ($menu as $value) {
                        if (isset($value[$role]) && $value[$role]) {
                            if (isset ($_REQUEST ['page']) && $_REQUEST ['page'] == $value ['page_link'])
                                $class = 'class = "active"';
                            else
                                $class = "";
                            echo '<li ' . $class . '><a href="?dashboard=user&page=' . $value ['page_link'] . '" class="left-tooltip" data-tooltip="' . $value ['menu_title'] . '" title="' . $value ['menu_title'] . '"><span class="icone"> <img src="' . $value ['menu_icone'] . '" /></span><span class="title">' . $value ['menu_title'] . '</span></a></li>';
                        }
                        ?>
                        <?php
                    }
                    ?>
                </ul>
            </div>
            <div class="page-inner" style="min-height:1050px;">
                <div class="right_side <?php if (isset($_REQUEST['page'])) echo $_REQUEST['page']; ?>">
                    <?php
                    if (isset ($_REQUEST ['page'])) {
                        require_once HMS_PLUGIN_DIR . '/template/' . $_REQUEST['page'] . '.php';
                        return false;
                    }
                    ?>
                    <!---start new dashboard------>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-white">
                                <div class="panel-body">
                                    <button type="button" onclick="addappointment();"
                                            class="btn btn-success pull-right" style="margin-left: 20px">Add Appointment
                                    </button>
                                    <script>
                                        function addappointment(){
                                            $("#datepickerfrom").show();
                                            $("#datepickerto").show();
                                            $("#datepickertolabel").show();
                                            $("#datepickerfromlabel").show();
                                            $("#createEventModal").modal('show');
                                        }
                                    </script>
                                    <div id="calendar"></div>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
                            <a href="<?php echo home_url() . '?dashboard=user&page=patient'; ?>">
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
                        </div>
                        <div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
                            <a href="<?php echo home_url() . '?dashboard=user&page=doctor'; ?>">
                                <div class="panel info-box panel-white">
                                    <div class="panel-body doctor">
                                        <div class="info-box-stats">
                                            <p class="counter"><?php echo count(get_users(array('role' => 'doctor'))); ?></p>
                                            <span
                                                class="info-box-title"><?php echo esc_html(__('Therapist', 'hospital_mgt')); ?></span>
                                        </div>
                                        <img src="<?php echo HMS_PLUGIN_URL . "/assets/images/dashboard/doctor.png" ?>"
                                             class="dashboard_background">

                                    </div>
                                </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
                            <a href="<?php echo home_url() . '?dashboard=user&page=supportstaff'; ?>">
                                <div class="panel info-box panel-white">
                                    <div class="panel-body receptionist">
                                        <div class="info-box-stats">
                                            <p class="counter"><?php echo count(get_users(array('role' => 'receptionist'))); ?></p>

                                            <span
                                                class="info-box-title"><?php echo esc_html(__('Support Staff', 'hospital_mgt')); ?></span>
                                        </div>
                                        <img
                                            src="<?php echo HMS_PLUGIN_URL . "/assets/images/dashboard/support-staft.png" ?>"
                                            class="dashboard_background">
                                    </div>
                                </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
                            <a href="<?php echo home_url() . '?dashboard=user&page=account'; ?>">
                                <div class="panel info-box panel-white">
                                    <div class="panel-body setting">
                                        <div class="info-box-stats">
                                            <p class="counter">
                                                &nbsp;<?php //echo count(get_users(array('role'=>'laboratorist')));?></p>
                                            <span
                                                class="info-box-title"><?php echo esc_html(__('Setting', 'hospital_mgt')); ?></span>
                                        </div>
                                        <img
                                            src="<?php echo HMS_PLUGIN_URL . "/assets/images/dashboard/setting-image.png" ?>"
                                            class="dashboard_background">
                                    </div>
                                </div>
                            </a>
                        </div>

                        <?php if ($obj_hospital->role == 'nurse' || $obj_hospital->role == 'doctor') { ?>
                            <div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
                                <a href="<?php echo home_url() . '?dashboard=user&page=appointment'; ?>">
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

                        <?php }
                        if ($obj_hospital->role == 'doctor') { ?>
                        <?php }
                        if ($obj_hospital->role == 'nurse' || $obj_hospital->role == 'doctor') { ?>
                        <?php }

                        if ($obj_hospital->role == 'doctor') { ?>

                        <?php }
                        if ($obj_hospital->role == 'pharmacist' || $obj_hospital->role == 'doctor') { ?>

                        <?php }


                        if ($obj_hospital->role == 'doctor') { ?>
                            <div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
                                <a href="<?php echo home_url() . '?dashboard=user&page=report'; ?>">
                                    <div class="panel info-box panel-white">
                                        <div class="panel-body operation_report">
                                            <div class="info-box-stats">
                                                <p class="counter">&nbsp;</p>

                                                <span
                                                    class="info-box-title"><?php echo esc_html(__('Report', 'hospital_mgt')); ?></span>
                                            </div>
                                            <img
                                                src="<?php echo HMS_PLUGIN_URL . "/assets/images/dashboard/report.png" ?>"
                                                class="dashboard_background">
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>
                        <?php
                        if ($obj_hospital->role == 'doctor' || $obj_hospital->role == 'laboratorist') { ?>
                        <?php } ?>
                        <div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
                            <a href="<?php echo home_url() . '?dashboard=user&page=accountant'; ?>">
                                <div class="panel info-box panel-white">
                                    <div class="panel-body accountant">
                                        <div class="info-box-stats">
                                            <p class="counter"><?php echo count(get_users(array('role' => 'accountant'))); ?></p>
                                            <span
                                                class="info-box-title"><?php echo esc_html(__('Accountant', 'hospital_mgt')); ?></span>
                                        </div>
                                        <img
                                            src="<?php echo HMS_PLUGIN_URL . "/assets/images/dashboard/accountant.png" ?>"
                                            class="dashboard_background">

                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php if ($obj_hospital->role == 'accountant') { ?>
                            <div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
                                <a href="<?php echo home_url() . '?dashboard=user&page=invoice'; ?>">
                                    <div class="panel info-box panel-white">
                                        <div class="panel-body invoice">
                                            <div class="info-box-stats">
                                                <p class="counter"><?php hmgt_tables_rows('hmgt_invoice'); ?></p>

                                                <span
                                                    class="info-box-title"><?php echo esc_html(__('Invoice', 'hospital_mgt')); ?></span>
                                            </div>
                                            <img
                                                src="<?php echo HMS_PLUGIN_URL . "/assets/images/dashboard/invoice.png" ?>"
                                                class="dashboard_background">
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php }

                        ?>


                    </div>


                    <!---End new dashboard------>


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
                                <input type="text" class="form-control pull-left" name="patientName" id="patientName"
                                       style="margin: 0 auto;" data-provide="typeahead" data-items="4"
                                       data-source="[&quot;Value 1&quot;,&quot;Value 2&quot;,&quot;Value 3&quot;]">
                                <?php
                                $obj_users = new Hmgtuser();
                                $patients = $obj_users->get_user_by_type('patient');
                                hmgt_render_doctors_options($patients, 'patient', $selected);
                                ?>
                            </div>
                            <br>
                            <button type="button" class="btn btn-success" id="newpatient">New Patient</button>
                            <br>
                            <label class="control-label" id="PhoneNumberLabel" for="inputPatient">Patient Phone
                                Number:</label>

                            <div class="controls">
                                <input type="text" class="form-control pull-left" name="patientNumber"
                                       id="patientNumber" tyle="margin: 0 auto;" data-provide="typeahead" data-items="4"
                                       data-source="[&quot;Value 1&quot;,&quot;Value 2&quot;,&quot;Value 3&quot;]">
                            </div>
                            <label class="control-label" for="inputPatient">Doctor:</label>

                            <div class="controls">
                                <?php
                                $obj_users = new Hmgtuser();
                                $doctors = $obj_users->get_user_by_type('doctor');
                                hmgt_render_doctors_options($doctors, 'doctor', $selected);
                                ?>
                            </div>
                            <label class="control-label" id="datepickerfromlabel" for="inputPatient" style="display:none;">
                                From:</label>
                            <input type="text" id="datepickerfrom" style="display:none;" class="form-control" value="2016-01-13 12:00:00">
                            <label class="control-label" id="datepickertolabel" for="inputPatient" style="display:none;">
                                To:</label>
                            <input type="text" id="datepickerto" style="display:none;" class="form-control" value="2016-01-13 12:00:00">
                           <label class="control-label" for="inputPatient">Session Type:</label>
                            <div class="controls">
                                <?php
                                $obj_sessions = new Hmgt_session();
                                $sessions = $obj_sessions->get_all_session();
                                hmgt_render_options($sessions, 'session', $selected);
                                ?>
                            </div>
                            <input type="text" style="display: none;" readonly id="apptStartTime"/>
                            <input type="text" style="display: none;" readonly id="apptEndTime"/>
                            <input type="text" style="display: none;" readonly id="apptAllDay"/>
                        </div>
                        <div class="control-group">
                            <div class="controls controls-row" id="when" style="margin-top:5px;">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true" onclick="location.reload();">Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitButton">Save</button>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script>
        $(document).ready(function () {
            $("#patientName").hide();
            $("#patientNumber").hide();
            $("#PhoneNumberLabel").hide();
            var calendar = $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'agendaWeek,agendaDay'
                },
                defaultView: 'agendaWeek',
                editable: true,
                selectable: true,
                events:<?php echo json_encode($appointment_array);?>,
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
                eventRender: function (event, element) {
                    element.find('.fc-title').append("<br>Therapist :" + event.doctor_name + "<br>Patient :" + event.patient_name + ", ");
                    element.find('.fc-content').attr('data-toggle', 'tooltip');
                    element.find('.fc-content').attr('title', 'Therapist :" ' + event.doctor_name + '" Patient :"' + event.patient_name + '","');
                },
            });
        });
        $("#datepickerfrom").on('change', function () {
            $('#apptStartTime').val($("#datepickerfrom").val());
            $('#apptEndTime').val($("#datepickerto").val());
        });
        $("#newpatient").on('click', function () {
            $("#patientName").show();
            $("#patientNumber").show();
            $("#PhoneNumberLabel").show();
            $(this).hide();
            $("#patient_id").toggle();
        });
        $('#submitButton').on('click', function (e) {
            // We don't want this to act as a link so cancel the link action
            e.preventDefault();
            var data = [];
            data.push($("#createAppointmentForm").serialize());
            data.push($('#apptStartTime').val());
            data.push($('#apptEndTime').val());
            data.push($('#apptAllDay').val());
            var relativeurl = '<?php echo HMS_PLUGIN_URL.'/class/userdata.php'?>';
            var data = {'action': 'add_user', 'data': data};
            $.get(relativeurl, data, function (callbackdata) {
                doSubmit();
                location.reload();
            })
        });

        function doSubmit() {
            $("#createEventModal").modal('hide');
            if($("#datepickerfrom").val()){
                $('#apptStartTime').val($("#datepickerfrom").val());
            }
            if($("#datepickerto").val()){
                $('#apptEndTime').val($("#datepickerto").val());
            }
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
    </body>
    </html>

<?php ?>