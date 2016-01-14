<?php
/*
Plugin Name: Wordpress Appointment Booking Manager
Plugin URI: http://upscalethought.com/?page_id=9
Description: Wordpress plugin for Appointment Schedule Booking. Easily manage your appointment scheduling.
Version: 1.0
Author: UpScaleThought
Author URI: http://upscalethought.com/
*/
define('USTSAPPOINTMENT_PLUGIN_URL', plugins_url('',__FILE__));
define("USTS_BASE_URL", WP_PLUGIN_URL.'/'.plugin_basename(dirname(__FILE__)));
define( 'USTSAPPOINTMENT_DIR', plugin_dir_path(__FILE__) );

$ustsappointment_calendar_page = get_page_by_path('usts-appointment-calendar');

$ustsappointment_calendar_page_id= 0;
if(isset($ustsappointment_calendar_page)){
  $ustsappointment_calendar_page_id = $ustsappointment_calendar_page->ID;
}

define('USTSAPPOINTMENTCALENDAR_PAGEID', $ustsappointment_calendar_page_id);
include_once('includes/fullcalendar_shortcode.php');
include_once('includes/create_page.php');
include_once('operations/ustsappointment_init.php');
add_action('admin_menu', 'appointgen_plugin_admin_menu');
function appointgen_plugin_admin_menu(){
	add_object_page('Appointment Management Pro', 'Appointment Booking', 'publish_posts', 'custom_appointment', 'appointgen_settings_menu');
}
function appointgen_settings_menu(){
  ?>
	<div> <h2><?php _e('Appointment Scheduling Manager','appointgen-ustsappointment');?></h2></div>
 <?php
} 
function appointgen_add_appointments_menu(){
	add_submenu_page( '-', 'Add Schedule', 'Add Schedule', 'manage_options', 'add-schedule-menu', 'appointgen_add_schedule_settings');
	add_submenu_page( 'custom_appointment', 'Manage Schedule', 'Manage Schedule', 'manage_options', 'manage-schedule-menu', 'appointgen_manage_schedule_settings');
	add_submenu_page( '-', 'Add Venue', 'Add Venue', 'manage_options', 'add-venues-menu', 'appointgen_add_venues_settings');
	add_submenu_page( 'custom_appointment', 'Manage Venue', 'Manage Venue', 'manage_options', 'manage-venue-menu', 'appointgen_manage_venue_settings');
	add_submenu_page( '-', 'Add Service', 'Add Service', 'manage_options', 'add-services-menu', 'appointgen_add_services_settings');
	add_submenu_page( 'custom_appointment', 'Manage Service', 'Manage Service', 'manage_options', 'manage-service-menu', 'appointgen_manage_service_settings');
	add_submenu_page( '-', 'Add Timeslot', 'Add Timeslot', 'manage_options', 'add-timeslot-menu', 'appointgen_add_timeslot_settings');
	add_submenu_page( 'custom_appointment', 'Manage Timeslot', 'Manage Timeslot', 'manage_options', 'manage-timeslot-menu', 'appointgen_manage_timeslot_settings');
	add_submenu_page( 'custom_appointment', 'Add Appointment', 'Add Appointment', 'manage_options', 'add-appointment-menu', 'appointgen_add_appointment_settings' );
	add_submenu_page( 'custom_appointment', 'Manage Appointment', 'Manage Appointment', 'manage_options', 'manage-appointment-menu', 'appointgen_manage_appointment_settings');
	add_submenu_page( 'custom_appointment', 'Appointment Calendar', 'Appointment Calendar', 'manage_options', 'appointment-calendar-menu', 'appointgen_appointment_calendar' );	
	//add_submenu_page( 'custom_appointment', 'appointment Settings', 'appointment Settings', 'manage_options', 'appointment-settings-menu', 'appointgen_appointment_settings_page' );
	add_submenu_page( 'custom_appointment', 'FrontEnd CSS Fix', 'FrontEnd CSS Fix', 'manage_options', 'css-fix-menu', 'appointgen_cssfix_front_setting' );
  
  add_submenu_page( 'custom_appointment', 'Appointment Pro Version', 'APPOINTMENT PRO VERSION', 'manage_options', 'appointment-pro-version', 'appointgen_appointment_pro_version_setting' );
}
//-------------appointment Settings-----------------------
function appointgen_ustsappointment_get_opt_val($opt_name,$default_val){
		if(get_option($opt_name)!=''){
			return $value = get_option($opt_name);
		}else{
			return $value =$default_val;
		}
}
//Schedule
function appointgen_add_schedule_settings(){
	include_once('includes/add_schedule.php');
}
function appointgen_manage_schedule_settings(){
	include_once('includes/manage_schedule.php');
}
//venue
function appointgen_add_venues_settings(){
	include_once('includes/add_venue.php');
}
function appointgen_manage_venue_settings(){
	include_once('includes/manage_venue.php');
}
// Service function
function appointgen_add_services_settings(){
	include_once('includes/add_service.php');
}
function appointgen_manage_service_settings(){
	include_once('includes/manage_service.php');
}
//time slot funcitons
function appointgen_add_timeslot_settings(){
	include_once('includes/add_timeslot.php');
}
function appointgen_manage_timeslot_settings(){
	include_once('includes/manage_timeslot.php');
}
//
/*function appointgen_appointment_settings_page(){
	include_once('operations/appointment_settings.php');
}*/
//
function appointgen_appointment_calendar(){
	include_once('calendar-fullcalendar.php');
}
function appointgen_manage_appointment_settings(){
	include_once('includes/manage_appointment.php');
}
function appointgen_add_appointment_settings(){
	include_once('includes/add_appointment_backend.php');
}
function appointgen_cssfix_front_setting(){
	include_once('includes/add_cssfix_front.php');	
}
function appointgen_appointment_pro_version_setting(){
  include_once('includes/appointment_pro_version.php');
}
add_action('admin_menu','appointgen_add_appointments_menu');
/*---------------------*/

function appointgen_appointment_uninstall(){
}

register_activation_hook( __FILE__, 'appointgen_ustsappointment_install' );
register_deactivation_hook( __FILE__, 'appointgen_appointment_uninstall');

function appointgen_prevent_admin_access()
{
    if (strpos( strtolower( $_SERVER['REQUEST_URI'] ), '/wp-admin' ) && !current_user_can( 'administrator' ) ){
		    wp_redirect( home_url() );
		}
}
add_action( 'init', 'appointgen_prevent_admin_access', 0 );
//====== session start =================================
add_action('init', 'appointgen_appointmentStartSession', 1);
function appointgen_appointmentStartSession() {
    if(!session_id()) {
        session_start();
    }
}
function sc_appointjs(){
	wp_register_script('appointjs',plugins_url('/includes/js/appoint.js',__FILE__));
	wp_localize_script( 'appointjs', 'scAppointAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
	
	wp_enqueue_script( 'appointjs');
}
function sc_appointjs_front(){
	wp_register_script('appointjs_front',plugins_url('/includes/js/appointgen_front.js',__FILE__),'jquery',"",true);
	wp_localize_script( 'appointjs_front', 'scAppointAjax_front', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
	wp_enqueue_script( 'appointjs_front');
}

add_action('admin_enqueue_scripts','sc_appointjs');
add_action('wp_enqueue_scripts','sc_appointjs_front');

function sc_add_timeslot_ajax_request(){
	global $table_prefix,$wpdb;	
	if ( isset($_REQUEST) ) {
		$hdntimeslotid = $_REQUEST['hdntimeslotid'];
		$timeslot_name = $_REQUEST['timeslot_name'];
		$starttime = $_REQUEST['start_time'];
		$endtime = $_REQUEST['end_time'];
		$time_interval = $_REQUEST['time_interval'];
		
		$values = array(
			'timeslot_name'=>$timeslot_name,
			'mintime'=>$starttime,
			'maxtime'=>$endtime,
			'time_interval'=>$time_interval, 
		);
		if($hdntimeslotid == "" || $hdntimeslotid == NULL){
			$wpdb->insert($table_prefix.'appointgen_timeslot',$values );	
			$inserted_id = $wpdb->insert_id;
			echo $inserted_id;
		}
		else{
			$wpdb->update(
				 $table_prefix.'appointgen_timeslot',
				 $values,
				 array('id' =>$hdntimeslotid)
			 );
			 echo $hdntimeslotid;
		}
	}
	exit;
}
add_action( 'wp_ajax_nopriv_sc_add_timeslot_ajax_request','sc_add_timeslot_ajax_request' );
add_action( 'wp_ajax_sc_add_timeslot_ajax_request', 'sc_add_timeslot_ajax_request' );

function sc_add_service_ajax_request(){
		global $table_prefix,$wpdb;	
		if ( isset($_REQUEST) ) {
			$hdnserviceid = $_REQUEST['hdnserviceid'];
			$provider_name = $_REQUEST['provider_name'];
			$service_name = $_REQUEST['service_name'];
			$service_details = $_REQUEST['service_details'];
			$price = $_REQUEST['price'];
			$days = $_REQUEST['days'];
			
			$values = array(
				'provider_name'=>$provider_name,
				'service_name'=>$service_name,
				'service_details'=>$service_details,
				'price'=>$price,
				'days'=>$days 
			);
			if($hdnserviceid == "" || $hdnserviceid == NULL){
				$wpdb->insert($table_prefix.'appointgen_services',$values );	
				$inserted_id = $wpdb->insert_id;
				echo $inserted_id;
			}
			else{
				$wpdb->update(
					 $table_prefix.'appointgen_services',
					 $values,
					 array('id' =>$hdnserviceid)
				 );
				 echo $hdnserviceid;
			}
		}
		exit;
}

add_action( 'wp_ajax_nopriv_sc_add_service_ajax_request','sc_add_service_ajax_request' );
add_action( 'wp_ajax_sc_add_service_ajax_request', 'sc_add_service_ajax_request' );

function sc_add_venue_ajax_request(){
		global $table_prefix,$wpdb;	
		if ( isset($_REQUEST) ) {
			$hdnvenueid = $_REQUEST['hdnvenueid'];
			$venue_name = $_REQUEST['venue_name'];
			$venue_address = $_REQUEST['venue_address'];
			$description = $_REQUEST['description'];
			
			$values = array(
				'venue_name'=>$venue_name,
				'venue_address'=>$venue_address,
				'description'=>$description
			);
			if($hdnvenueid == "" || $hdnvenueid == NULL){
				$wpdb->insert($table_prefix.'appointgen_venues',$values );	
				$inserted_id = $wpdb->insert_id;
				echo $inserted_id;
			}
			else{
				$wpdb->update(
					 $table_prefix.'appointgen_venues',
					 $values,
					 array('id' =>$hdnvenueid)
				 );
				 echo $hdnvenueid;
			}
		}
		exit;
}

add_action( 'wp_ajax_nopriv_sc_add_venue_ajax_request','sc_add_venue_ajax_request' );
add_action( 'wp_ajax_sc_add_venue_ajax_request', 'sc_add_venue_ajax_request' );

function appointgen_add_schedule_ajax_request(){
		global $table_prefix,$wpdb;	
		if ( isset($_REQUEST) ) {
			$hdnscheduleid = $_REQUEST['hdnscheduleid'];
			$schedule_name = $_REQUEST['schedule_name'];
			$timeslot = $_REQUEST['opttimeslot'];
			$service = $_REQUEST['optservice'];
			$venue = $_REQUEST['optvenue'];
			
			$values = array(
				'schedule_name'=>$schedule_name,
				'timeslot'=>$timeslot,
				'service'=>$service,
				'venue'=>$venue
			);
			if($hdnscheduleid == "" || $hdnscheduleid == NULL){
				$wpdb->insert($table_prefix.'appointgen_schedules',$values );	
				$inserted_id = $wpdb->insert_id;
				echo $inserted_id;
			}
			else{
				$wpdb->update(
					 $table_prefix.'appointgen_schedules',
					 $values,
					 array('id' =>$hdnscheduleid)
				 );
				 echo $hdnscheduleid;
			}
		}
		exit;
}

add_action( 'wp_ajax_nopriv_appointgen_add_schedule_ajax_request','appointgen_add_schedule_ajax_request' );
add_action( 'wp_ajax_appointgen_add_schedule_ajax_request', 'appointgen_add_schedule_ajax_request' );
//
function sc_set_ajax_schedule_session(){
		global $table_prefix,$wpdb;	
		if ( isset($_REQUEST) ) {
			$_SESSION['scheduleid'] = $_REQUEST['scheduleid']; 
			echo $_SESSION['scheduleid'];
		}
		exit;
}

add_action( 'wp_ajax_nopriv_sc_set_ajax_schedule_session','sc_set_ajax_schedule_session' );
add_action( 'wp_ajax_sc_set_ajax_schedule_session', 'sc_set_ajax_schedule_session' );

//
function sc_set_ajax_schedule_session_front(){
		global $table_prefix,$wpdb;	
		if ( isset($_REQUEST) ) {
			$_SESSION['scheduleid_front'] = $_REQUEST['scheduleid']; 
			echo $_SESSION['scheduleid_front'];
		}
		exit;
}

add_action( 'wp_ajax_nopriv_sc_set_ajax_schedule_session_front','sc_set_ajax_schedule_session_front' );
add_action( 'wp_ajax_sc_set_ajax_schedule_session_front', 'sc_set_ajax_schedule_session_front' );
//=========Payment System-----------------------------------------------------------------------------------
define('WP_CUSTOM_PRODUCT_URL', plugins_url('',__FILE__));
define('WP_CUSTOM_PRODUCT_PATH',plugin_dir_path( __FILE__ ));
function add_admin_additional_script(){
  wp_enqueue_script( 'thickbox');
  wp_enqueue_style ( 'thickbox');
  wp_enqueue_media();
  wp_enqueue_script( 'post' );
  wp_enqueue_style ( 'appointpg_admin_style',plugins_url( '/appointpg_resource/admin/css/admin.css', __FILE__ ));
}
function add_frontend_additional_script(){
	wp_enqueue_style( 'custom.css', plugins_url( '/appointpg_resource/css/custom.css', __FILE__ ) );
}
function load_custom_wp_admin_style() {
  //wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
}
//---------------------End Payemnt system code-----------------------------------------------------------------------
//============= WP Ajax Calls ====================================================
function appointgen_get_serviceprice_by_schedule(){
  if(isset($_REQUEST)){
    global $table_prefix,$wpdb;
    $schedule = $_REQUEST['schedule'];
    $price = 0;
    $sql_service_price = "select scd.id as scheduleid,srv.id as serviceid,tmsl.id as timeslotid,vn.id as venueid, scd.* ,srv.*,tmsl.*,vn.* from ".$table_prefix."appointgen_schedules scd inner join ".$table_prefix."appointgen_services srv on scd.service = srv.id 
    inner join ".$table_prefix."appointgen_timeslot tmsl on tmsl.id = scd.timeslot
    inner join ".$table_prefix."appointgen_venues vn on vn.id = scd.venue 
    where scd.id=".$schedule;	
    $result = $wpdb->get_results($sql_service_price);
    $price = $result[0]->price;
    echo $price;    
  }
  exit;
}
add_action( 'wp_ajax_nopriv_appointgen_get_serviceprice_by_schedule','appointgen_get_serviceprice_by_schedule' );
add_action( 'wp_ajax_appointgen_get_serviceprice_by_schedule', 'appointgen_get_serviceprice_by_schedule' );
function appointgen_get_appointments(){
  if(isset($_REQUEST)){
    global $table_prefix,$wpdb;
    $appointment_id = $_REQUEST['appointment_id'];
    $sql = "select * from ".$table_prefix."appointgen_ustsappointments where appointment_id=".$appointment_id;
    $result = $wpdb->get_results($sql);
    echo json_encode($result);
  }
  exit;
}
add_action( 'wp_ajax_nopriv_appointgen_get_appointments','appointgen_get_appointments' );
add_action( 'wp_ajax_appointgen_get_appointments', 'appointgen_get_appointments' );

function appointgen_check_appointment(){
  if(isset($_REQUEST)){
    global $table_prefix,$wpdb;
    $hdnappointmentid = $_REQUEST['hdnappointmentid'];
    $schedule = $_REQUEST['schedule'];
    $date = $_REQUEST['date'];
    $start_time = $_REQUEST['starttime'];
    $end_time = $_REQUEST['endtime'];
    $timeshift = $_REQUEST['timeshift'];

    $schedule_cond = "schedule like '%".$schedule."%'";  
    $date = $_REQUEST['date'];
    $starttime = $_REQUEST['starttime'];

    $sql = "";
    if($hdnappointmentid != '' || $hdnappointmentid != NULL ){
      $sql = "select * from ".$table_prefix."appointgen_ustsappointments where (".$schedule_cond.") and 
        date = '".$date."' and  
        ((start_time > '".$start_time."' and end_time < '".$end_time."') or 
        (end_time > '".$start_time."' and end_time < '".$end_time."') or 
        (start_time > '".$start_time."' and start_time < '".$end_time."') or 
        (start_time < '".$start_time."' and end_time > '".$end_time."') )
        and timeshift = '".$timeshift."'
        and appointment_id!=".$hdnappointmentid;
    }
    else{
      $sql = "select * from ".$table_prefix."appointgen_ustsappointments where (".$schedule_cond.") and 
        date = '".$date."' and  
        ((start_time > '".$start_time."' and end_time < '".$end_time."') or 
        (end_time > '".$start_time."' and end_time < '".$end_time."') or 
        (start_time > '".$start_time."' and start_time < '".$end_time."') or 
        (start_time < '".$start_time."' and end_time > '".$end_time."') )
        and timeshift = '".$timeshift."'";
    }
    $result = $wpdb->get_results($sql);
    $yesno = "";
    if(count($result)>0){
      $yesno .= "yes";	
    }
    else{
      $yesno .= "no";
    }
    echo $yesno;
  }
  exit;
}
add_action( 'wp_ajax_nopriv_appointgen_check_appointment','appointgen_check_appointment' );
add_action( 'wp_ajax_appointgen_check_appointment', 'appointgen_check_appointment' );
function appointgen_save_appointment(){
  if ( count($_POST) > 0 ){ 
    global $table_prefix,$wpdb;
    $hdnappointmentid = $_REQUEST['hdnappointmentid'];
    $scheduleid = $_REQUEST['scheduleid'];
    $schedule = $_REQUEST['schedule'];
    $date = $_REQUEST['date'];
    $start_time = $_REQUEST['start_time'];

    $end_time = $_REQUEST['end_time'];
    $time_shift = $_REQUEST['time_shift'];
    $first_name = $_REQUEST['first_name'];
    $last_name = $_REQUEST['last_name'];
    $email = $_REQUEST['email'];
    $phone = $_REQUEST['phone'];
    $details = $_REQUEST['details'];
    $appointmentby = $_REQUEST['appointmentby'];
    $price = $_REQUEST['price'];
    $payment_method = $_REQUEST['payment_method'];

    $values = array(
      'schedule_id'=>$scheduleid,
      'schedule'=>$schedule,
      'date'=>$date,
      'start_time'=>$start_time, 
      'end_time'=>$end_time, 
      'timeshift'=>$time_shift,
      'first_name'=>$first_name, 
      'last_name'=>$last_name, 
      'email'=>$email, 
      'phone'=>$phone, 
      'details'=>$details, 
      'appointment_by'=>$appointmentby, 
      'custom_price'=>$price, 
      'payment_method'=>$payment_method,
    );
    if($hdnappointmentid == "" || $hdnappointmentid == NULL){
      $wpdb->insert($table_prefix.'appointgen_ustsappointments',$values );	
      $inserted_id = $wpdb->insert_id;
      echo $inserted_id;
    }
    else{
      $wpdb->update(
         $table_prefix.'appointgen_ustsappointments',
         $values,
         array('appointment_id' =>$hdnappointmentid)
       );
       echo $hdnappointmentid;
    }
  }
  exit;
}
add_action( 'wp_ajax_nopriv_appointgen_save_appointment','appointgen_save_appointment' );
add_action( 'wp_ajax_appointgen_save_appointment', 'appointgen_save_appointment' );
function appointgen_get_room_bycat(){
  if(isset($_REQUEST)){
    global $table_prefix,$wpdb;
    $term_id = $_REQUEST['term_id'];
    $sql_room = "select * from ".$table_prefix."term_taxonomy tt inner join ".$table_prefix."term_relationships tr on tt.term_taxonomy_id = tr.term_taxonomy_id inner join ".$table_prefix."posts p on p.id=tr.object_id inner join ".$table_prefix."postmeta pm on pm.post_id= p.id where p.post_status = 'publish' and tt.term_id=".$term_id." and pm.meta_key='_room_price'";
    $result = $wpdb->get_results($sql_room);
    echo json_encode($result);
  }
  exit;
}
add_action( 'wp_ajax_nopriv_appointgen_get_room_bycat','appointgen_get_room_bycat' );
add_action( 'wp_ajax_appointgen_get_room_bycat', 'appointgen_get_room_bycat' );
function appointgen_save_cssfixfront(){
  if ( count($_POST) > 0 ){ 
    global $table_prefix,$wpdb;
    $cssfix = $_REQUEST['cssfix'];
    $css = $_REQUEST['css'];
    $isupdate ="";
    if($cssfix == "front"){
      $isupdate = update_option('cssfix_front',$css);
    }
    if($isupdate){
      echo "added";
    }
  }
  exit;
}
add_action( 'wp_ajax_nopriv_appointgen_save_cssfixfront','appointgen_save_cssfixfront' );
add_action( 'wp_ajax_appointgen_save_cssfixfront', 'appointgen_save_cssfixfront' );
function appointgen_search_appointment(){
  global $table_prefix,$wpdb;
  $search_text = $_REQUEST['searchtext'];
  $sql = "select * from ".$table_prefix."appointgen_ustsappointments where email='".$search_text."' or phone='".$search_text."' or schedule='".$search_text."' or date='".$search_text."'";
  $result = $wpdb->get_results($sql);
  $msg = "<div id='content_top'></div>";
  if(count($result)){
        $msg .= '<table class="wp-list-table widefat fixed bookmarks" cellspacing="0">
                    <thead>
                      <tr>
                        <th>'.__("Schedule","appointgen_ustsappointment").'</th>
                        <th>'.__("Date","appointgen_ustsappointment").'</th>
                        <th>'.__("Start Time","appointgen_ustsappointment").'</th>
                        <th>'.__("End Time","appointgen_ustsappointment").'</th>
                        <th>'.__("Email","appointgen_ustsappointment").'</th>
                        <th>'.__("Phone","appointgen_ustsappointment").'</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tr>';
                foreach($result as $appointment){
                  $msg .= '<tr class="alternate">
                              <td>'.sprintf(__("%s","appointgen_ustsappointment"),$appointment->schedule).'</td>
                              <td>'.sprintf(__("%s","appointgen_ustsappointment"),$appointment->date).'</td>
                              <td>'.sprintf(__("%s","appointgen_ustsappointment"),$appointment->start_time).' '.sprintf(__("%s","appointgen_ustsappointment"),$appointment->timeshift).'</td>  
                              <td>'.sprintf(__("%s","appointgen_ustsappointment"),$appointment->end_time).' '.sprintf(__("%s","appointgen_ustsappointment"),$appointment->timeshift).'</td>
                              <td>'.sprintf(__("%s","appointgen_ustsappointment"),$appointment->email).'</td>
                              <td>'.sprintf(__("%s","appointgen_ustsappointment"),$appointment->phone).'</td>

                              <td>
                                ';
                  $msg .= '<a href="'.site_url().'/wp-admin/admin.php?page=add-appointment-menu&calltype=editappointment&id='.$appointment->appointment_id.'">'.__("edit","appointgen_ustsappointment").'</a>
                                &nbsp;&nbsp;&nbsp;<a id="delete_appointment" href="#" onclick="return confirm("Are you sure want to delete");">'.__("delete","appointgen_ustsappointment").'</a>
                                <input type="hidden" id="hdnappointmentid"  name="hdnappointmentid" value="'.$appointment->appointment_id.'" />
                              </td>
                          </tr>';
                }
                $msg .= '</tr>
                          <tfoot>
                            <tr>
                              <th>'.__("Schedule","appointgen_ustsappointment").'</th>
                              <th>'.__("Date","appointgen_ustsappointment").'</th>
                              <th>'.__("Start Time","appointgen_ustsappointment").'</th>
                              <th>'.__("End Time","appointgen_ustsappointment").'</th>
                              <th>'.__("Email","appointgen_ustsappointment").'</th>
                              <th>'.__("Phone","appointgen_ustsappointment").'</th>
                              <th></th>
                            </tr>
                          </tfoot>
                        </table>';	
  }
  else{
    $msg .= '<div style="padding:80px;color:red;">'.__("Sorry! No Data Found!","appointgen_ustsappointment").'</div>';
  }
  $msg = "<div class='data'>" . _e($msg) . "</div>";
  echo $msg;
  exit;
}
add_action( 'wp_ajax_nopriv_appointgen_search_appointment','appointgen_search_appointment' );
add_action( 'wp_ajax_appointgen_search_appointment', 'appointgen_search_appointment' );
function appointgen_load_manageappointment_data(){
  if($_POST['page'])
  {
    $page = $_POST['page'];
    $cur_page = $page;
    $page -= 1;
    $per_page = 10;
    $previous_btn = true;
    $next_btn = true;
    $first_btn = true;
    $last_btn = true;
    $start = $page * $per_page;
    global $table_prefix,$wpdb;
    $sql = "select * from ".$table_prefix."appointgen_ustsappointments ";
    $result_count = $wpdb->get_results($sql);
    $count = count($result_count);
    $sql = $sql.' LIMIT '.$start.', '.$per_page.'';
    $result_page_data = $wpdb->get_results($sql); 
    $msg = "<style type='text/css'>
      #loading{
          width: 50px;
          position: absolute;
          height:50px;
      }
      #inner_content{
         padding: 0 20px 0 0!important;
      }
      #inner_content .pagination ul li.inactive,
      #inner_content .pagination ul li.inactive:hover{
          background-color:#ededed;
          color:#bababa;
          border:1px solid #bababa;
          cursor: default;
      }
      #inner_content .data ul li{
          list-style: none;
          font-family: verdana;
          margin: 5px 0 5px 0;
          color: #000;
          font-size: 13px;
      }

      #inner_content .pagination{
          width: 80%;
          height: 45px;
      }
      #inner_content .pagination ul li{
          list-style: none;
          float: left;
          border: 1px solid #006699;
          padding: 2px 6px 2px 6px;
          margin: 0 3px 0 3px;
          font-family: arial;
          font-size: 14px;
          color: #006699;
          font-weight: bold;
          background-color: #f2f2f2;
      }
      #inner_content .pagination ul li:hover{
          color: #fff;
          background-color: #006699;
          cursor: pointer;
      }
      .go_button
      {
        background-color:#f2f2f2;
        border:1px solid #006699;
        color:#cc0000;
        padding:2px 6px 2px 6px;
        cursor:pointer;
        position:absolute;
        width:50px;
      }
      .total
      {
        float:right;
        font-family:arial;
        color:#999;
        padding-right:150px;
      }
      #namediv input {
        width:5%!important;
      }
    </style>";  
    $msg .= "<div id='content_top'></div>";
    if(count($result_page_data)){
          $msg .= '<table class="wp-list-table widefat fixed bookmarks" cellspacing="0">
                      <thead>
                        <tr>
                          <th>Schedule</th>
                          <th>Date</th>
                          <th>Start Time</th>
                          <th>End Time</th>
                          <th>Email</th>
                          <th>Phone</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tr>';
                  foreach($result_page_data as $appointment){
                    $msg .= '<tr class="alternate">
                                <td>'.$appointment->schedule.'</td>
                                <td>'.$appointment->date.'</td>
                                <td>'.$appointment->start_time.' '.$appointment->timeshift.'</td>  
                                <td>'.$appointment->end_time.' '.$appointment->timeshift.'</td>
                                <td>'.$appointment->email.'</td>
                                <td>'.$appointment->phone.'</td>
                                <td>
                                  ';
                    $msg .= '<a href="'.site_url().'/wp-admin/admin.php?page=add-appointment-menu&calltype=editappointment&id='.$appointment->appointment_id.'">edit</a>
                                  &nbsp;&nbsp;&nbsp;<a style="cursor:pointer" id="delete_appointment">delete</a>
                                  <input type="hidden" id="hdnappointmentid"  name="hdnappointmentid" value="'.$appointment->appointment_id.'" />
                                </td>
                            </tr>';
                  }
                  $msg .= '</tr>
                            <tfoot>
                              <tr>
                                 <th>Schedule</th>
                                  <th>Date</th>
                                  <th>Start Time</th>
                                  <th>End Time</th>
                                  <th>Email</th>
                                  <th>Phone</th>
                                <th></th>
                              </tr>
                            </tfoot>
                          </table>';	
    }
    else{
      $msg .= '<div style="padding:80px;color:red;">Sorry! No Data Found!</div>';
    }	
    $msg = "<div class='data'>" . $msg . "</div>";
    $no_of_paginations = ceil($count / $per_page);
    /* ---------------Calculating the starting and endign values for the loop----------------------------------- */
    if ($cur_page >= 7) {
        $start_loop = $cur_page - 3;
        if ($no_of_paginations > $cur_page + 3)
            $end_loop = $cur_page + 3;
        else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
            $start_loop = $no_of_paginations - 6;
            $end_loop = $no_of_paginations;
        } else {
            $end_loop = $no_of_paginations;
        }
    } else {
        $start_loop = 1;
        if ($no_of_paginations > 7)
            $end_loop = 7;
        else
            $end_loop = $no_of_paginations;
    }
    /* ----------------------------------------------------------------------------------------------------------- */
    $msg .= "<div class='pagination'><ul>";
    // FOR ENABLING THE FIRST BUTTON
    if ($first_btn && $cur_page > 1) {
        $msg .= "<li p='1' class='active'>First</li>";
    } else if ($first_btn) {
        $msg .= "<li p='1' class='inactive'>First</li>";
    }
    // FOR ENABLING THE PREVIOUS BUTTON
    if ($previous_btn && $cur_page > 1) {
        $pre = $cur_page - 1;
        $msg .= "<li p='$pre' class='active'>Previous</li>";
    } else if ($previous_btn) {
        $msg .= "<li class='inactive'>Previous</li>";
    }
    for ($i = $start_loop; $i <= $end_loop; $i++) {

        if ($cur_page == $i)
            $msg .= "<li p='$i' style='color:#fff;background-color:#006699;' class='active'>{$i}</li>";
        else
            $msg .= "<li p='$i' class='active'>{$i}</li>";
    }
    // TO ENABLE THE NEXT BUTTON
    if ($next_btn && $cur_page < $no_of_paginations) {
        $nex = $cur_page + 1;
        $msg .= "<li p='$nex' class='active'>Next</li>";
    } else if ($next_btn) {
        $msg .= "<li class='inactive'>Next</li>";
    }
    // TO ENABLE THE END BUTTON
    if ($last_btn && $cur_page < $no_of_paginations) {
        $msg .= "<li p='$no_of_paginations' class='active'>Last</li>";
    } else if ($last_btn) {
        $msg .= "<li p='$no_of_paginations' class='inactive'>Last</li>";
    }
    $goto = "<input type='text' class='goto' size='1' style='margin-left:30px;height:24px;'/><input type='button' id='go_btn' class='go_button' value='Go'/>";
    $total_string = "<span class='total' a='$no_of_paginations'>Page <b>" . $cur_page . "</b> of <b>".$no_of_paginations."</b></span>";
    $img_loading = "<span ><div id='loading'></div></span>";
    $msg = $msg . "" . $goto . $total_string . $img_loading . "</ul></div>";  // Content for pagination
    echo $msg;
  }
  exit;
}
add_action( 'wp_ajax_nopriv_appointgen_load_manageappointment_data','appointgen_load_manageappointment_data' );
add_action( 'wp_ajax_appointgen_load_manageappointment_data', 'appointgen_load_manageappointment_data' );
function appointgen_activate_appointment(){
  if ( count($_POST) > 0 ){
    global $table_prefix,$wpdb;
    $appointmentid = $_REQUEST['appointment_id'];	
     $values = array('confirmed'=>1);
     $wpdb->update(
           $table_prefix.'appointgen_ustsappointment',
           $values,
           array('appointment_id' =>$appointmentid)
         );
     echo $appointmentid;		 
  }
  exit;
}
add_action( 'wp_ajax_nopriv_appointgen_activate_appointment','appointgen_activate_appointment' );
add_action( 'wp_ajax_appointgen_activate_appointment', 'appointgen_activate_appointment' );
function appointgen_delete_appointment(){
  if ( count($_POST) > 0 ){ 
    global $table_prefix,$wpdb;
    $appointmentid = $_REQUEST['appointment_id'];	
    $aff_rows = $wpdb->query("delete from ".$table_prefix."appointgen_ustsappointments where appointment_id='".$appointmentid."'");
    echo $aff_rows;		 
  }  
  exit;
}
add_action( 'wp_ajax_nopriv_appointgen_delete_appointment','appointgen_delete_appointment' );
add_action( 'wp_ajax_appointgen_delete_appointment', 'appointgen_delete_appointment' );
function appointgen_appointment_operations(){
  if ( count($_POST) > 0 ){ 
    global $table_prefix,$wpdb;
    $calltype = $_REQUEST['calltype'];
    if($calltype == 'delete_timeslot' ){
        $timeslotid = $_REQUEST['timeslot_id'];	
        $aff_rows = $wpdb->query("delete from ".$table_prefix."appointgen_timeslot where id='".$timeslotid."'");
    }
    else if($calltype == 'delete_service' ){
        $serviceid = $_REQUEST['service_id'];	
        $aff_rows = $wpdb->query("delete from ".$table_prefix."appointgen_services where id='".$serviceid."'");
    }
    else if($calltype == 'delete_venue' ){
        $venueid = $_REQUEST['venue_id'];	
        $aff_rows = $wpdb->query("delete from ".$table_prefix."appointgen_venues where id='".$venueid."'");
    }
    else if($calltype == 'delete_schedule' ){
        $scheduleid = $_REQUEST['schedule_id'];	
        $aff_rows = $wpdb->query("delete from ".$table_prefix."appointgen_schedules where id='".$scheduleid."'");
    }
  }
  exit;
}
add_action( 'wp_ajax_nopriv_appointgen_appointment_operations','appointgen_appointment_operations' );
add_action( 'wp_ajax_appointgen_appointment_operations', 'appointgen_appointment_operations' );
function appointgen_get_appointments_by_schedule(){
  global $table_prefix,$wpdb;
  $room = $_REQUEST['schedule'];
  $sql = "select * from ".$table_prefix."appointgen_ustsappointments where schedule like '%".$schedule."%'";
  $result = $wpdb->get_results($sql);
  echo json_encode($result);
  exit;
}
add_action( 'wp_ajax_nopriv_appointgen_get_appointments_by_schedule','appointgen_get_appointments_by_schedule' );
add_action( 'wp_ajax_appointgen_get_appointments_by_schedule', 'appointgen_get_appointments_by_schedule' );
//==================================End Ajax Call ===========================================================
	function appointgen_fullcalendarincludejs(){
		wp_register_script( 'fullcalendarjs',plugins_url('/fullcalendar/fullcalendar-1.6.4/fullcalendar/fullcalendar.js',__FILE__));
		wp_register_script( 'jscolor',plugins_url('/jscolor/jscolor.js',__FILE__));
		wp_enqueue_script( 'jquery');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-dialog');
    wp_enqueue_script('jquery-ui-datepicker');
    
		wp_enqueue_script( 'fullcalendarjs');
		wp_enqueue_script( 'jscolor');
	}
	function 	appointgen_fullcalendarincludecss(){
			wp_register_style( 'jquery-ui',plugins_url('/fullcalendar/fullcalendar-1.6.4/fullcalendar/cupertino/jquery-ui.min.css',__FILE__));
      wp_register_style( 'fullcalendarcss',plugins_url('/fullcalendar/fullcalendar-1.6.4/fullcalendar/fullcalendar.css',__FILE__));
			wp_register_style( 'fullcalendarprintcss',plugins_url('/fullcalendar/fullcalendar-1.6.4/fullcalendar/fullcalendar.print.css',__FILE__));
			
			wp_enqueue_style( 'jquery-ui');
      wp_enqueue_style( 'fullcalendarcss');
			wp_enqueue_style( 'fullcalendarprintcss');
	}
	add_action('admin_enqueue_scripts','appointgen_fullcalendarincludejs');
	add_action('admin_enqueue_scripts','appointgen_fullcalendarincludecss');
  function appointgen_fullcalendarincludejs_front(){
    wp_register_script( 'fullcalendar',plugins_url('/fullcalendar/fullcalendar-1.6.4/fullcalendar/fullcalendar.js',__FILE__), array( 'jquery' ));
    
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-dialog');
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_script( 'fullcalendar' );
    
  }
  function 	appointgen_fullcalendarincludecss_front(){
    wp_register_style( 'jquery-ui',plugins_url('/fullcalendar/fullcalendar-1.6.4/fullcalendar/cupertino/jquery-ui.min.css',__FILE__));
    wp_register_style( 'fullcalendar',plugins_url('/fullcalendar/fullcalendar-1.6.4/fullcalendar/fullcalendar.css',__FILE__));

    wp_enqueue_style( 'jquery-ui');
    wp_enqueue_style( 'fullcalendar');
  }
  add_action('wp_enqueue_scripts','appointgen_fullcalendarincludejs_front');
	add_action('wp_enqueue_scripts','appointgen_fullcalendarincludecss_front');