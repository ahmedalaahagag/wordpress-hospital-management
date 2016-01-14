<?php

require_once HMS_PLUGIN_DIR. '/hmgt_function.php';
require_once HMS_PLUGIN_DIR. '/class/userdata.php';
require_once HMS_PLUGIN_DIR. '/class/medicine.php';
require_once HMS_PLUGIN_DIR. '/class/treatment.php';
require_once HMS_PLUGIN_DIR. '/class/bedmanage.php';
require_once HMS_PLUGIN_DIR. '/class/operation.php';
require_once HMS_PLUGIN_DIR. '/class/perscription.php';
require_once HMS_PLUGIN_DIR. '/class/ambulance.php';
require_once HMS_PLUGIN_DIR. '/class/bloodbank.php';
require_once HMS_PLUGIN_DIR. '/class/dignosis.php';
require_once HMS_PLUGIN_DIR. '/class/message.php';
require_once HMS_PLUGIN_DIR. '/class/invoice.php';
require_once HMS_PLUGIN_DIR. '/class/appointment.php';
require_once HMS_PLUGIN_DIR. '/class/report.php';
require_once HMS_PLUGIN_DIR. '/class/session.php';
require_once HMS_PLUGIN_DIR. '/class/session_duration.php';
require_once HMS_PLUGIN_DIR. '/class/packages.php';
require_once HMS_PLUGIN_DIR. '/class/hospital-management.php';

add_action( 'admin_bar_menu', 'hmgt_hospital_dashboard_link', 999 );

add_action( 'admin_head', 'hmgt_admin_css' );

function hmgt_admin_css(){
	$background = "dedede";?>
     <style>
      a.toplevel_page_hospital:hover,  a.toplevel_page_hospital:focus,.toplevel_page_hospital.opensub a.wp-has-submenu{
  background: url("<?php echo HMS_PLUGIN_URL;?>/assets/images/hospital-1.png") no-repeat scroll 8px 9px rgba(0, 0, 0, 0) !important;
  
}
.toplevel_page_hospital:hover .wp-menu-image.dashicons-before img {
  display: none;
}

.toplevel_page_hospital:hover .wp-menu-image.dashicons-before {
  min-width: 23px !important;
}
    
     </style>
<?php
}

function hmgt_hospital_dashboard_link( $wp_admin_bar ) {
	$args = array(
			'id'    => 'hospital-dashboard',
			'title' => __('Hospital Dashboard','hospital_mgt'),
			'href'  => home_url().'?dashboard=user',
			'meta'  => array( 'class' => 'hmgt-hospital-dashboard' )
	);
	$wp_admin_bar->add_node( $args );
}

if ( is_admin() ){

	require_once HMS_PLUGIN_DIR. '/admin/admin.php';

	function hospital_install()
	{
			
			add_role('doctor', __( 'Doctor' ,'hospital_mgt'),array( 'read' => true, 'level_0' => true ));
			add_role('nurse', __( 'Nurse' ,'hospital_mgt'),array( 'read' => true, 'level_0' => true ));
			add_role('pharmacist', __( 'Pharmacist' ,'hospital_mgt'),array( 'read' => true, 'level_0' => true ));
			add_role('laboratorist', __( 'Laboratory Staff' ,'hospital_mgt'),array( 'read' => true, 'level_0' => true ));
			add_role('accountant', __( 'Accountant' ,'hospital_mgt'),array( 'read' => true, 'level_0' => true ));
			add_role('patient', __( 'Patient' ,'hospital_mgt'),array( 'read' => true, 'level_0' => true ));
			add_role('receptionist', __( 'Support Staff' ,'hospital_mgt'),array( 'read' => true, 'level_0' => true ));
			hmgt_register_post();
			hmgt_install_tables();			
	}
	register_activation_hook(HMS_PLUGIN_BASENAME, 'hospital_install' );

	function hmgt_option(){
		$options=array(
				    "hmgt_hospital_name"=> __( 'Hospital Management System' ,'hospital-mgt'),
					"hmgt_staring_year"=>"2015",
					"hmgt_hospital_address"=>"",
					"hmgt_contact_number"=>"9999999999",
					"hmgt_contry"=>"India",
					"hmgt_email"=>get_option('admin_email'),
					"hmgt_hospital_logo"=>plugins_url( 'hospital-management/assets/images/Thumbnail-img.png' ),
					"hmgt_hospital_background_image"=>plugins_url('hospital-management/assets/images/hospital_background.png' ),
					"hmgt_doctor_thumb"=>plugins_url( 'hospital-management/assets/images/useriamge/doctor.png' ),
					"hmgt_patient_thumb"=>plugins_url( 'hospital-management/assets/images/useriamge/patient.png' ),
					"hmgt_guardian_thumb"=>plugins_url( 'hospital-management/assets/images/useriamge/patient.png' ),
					"hmgt_nurse_thumb"=>plugins_url( 'hospital-management/assets/images/useriamge/nurse.png' ),
					"hmgt_support_thumb"=>plugins_url( 'hospital-management/assets/images/useriamge/supportstaff.png' ),
					"hmgt_pharmacist_thumb"=>plugins_url( 'hospital-management/assets/images/useriamge/pharmacist.png' ),
					"hmgt_laboratorist_thumb"=>plugins_url( 'hospital-management/assets/images/useriamge/laboratorystaff.png' ),
					"hmgt_accountant_thumb"=>plugins_url( 'hospital-management/assets/images/useriamge/accountant.png' ),
					"hmgt_driver_thumb"=>plugins_url( 'hospital-management/assets/images/useriamge/driver.jpg' ),
				    "hmgt_viewall_patient"=>'yes',
					"hmgt_sms_service"=>"",
					"hmgt_sms_service_enable"=> 0,					
					"hmgt_clickatell_sms_service"=>array(),
					"hmgt_twillo_sms_service"=>array(),
					
		);
		return $options;
	}
	add_action('admin_init','hmgt_general_setting');

	function hmgt_general_setting()
	{
		$options=hmgt_option();
		foreach($options as $key=>$val)
		{
			add_option($key,$val); 
			
		}
	}

	function hmgt_call_script_page()
{
	$page_array = array('hospital','hmgt_doctor','hmgt_patient','hmgt_outpatient','hmgt_nurse','hmgt_receptionist','hmgt_pharmacist','hmgt_laboratorist','hmgt_accountant',
					'hmgt_medicine','hmgt_treatment','hmgt_prescription','hmgt_operation','hmgt_diagnosis','hmgt_bloodbank','hmgt_bedmanage','hmgt_bedallotment','hmgt_appointment',
					'hmgt_invoice','hmgt_event','hmgt_message','hmgt_ambulance','hmgt_gnrl_settings','hmgt_report','hmgt_sms_setting','hmgt_audit_log','hmgt_sessions_settings','hmgt_sessions_durations','hmgt_packages');
	return  $page_array;
}

	function hmgt_change_adminbar_css($hook) {
				$current_page = $_REQUEST['page'];
				$page_array = hmgt_call_script_page();
				if(in_array($current_page,$page_array))
		{
				wp_register_script( 'jquery-1.8.2', plugins_url( '/assets/js/jquery-1.11.1.min.js', __FILE__), array( 'jquery' ) );
			 	wp_enqueue_script( 'jquery-1.8.2' );		
				wp_enqueue_style( 'accordian-jquery-ui-css', plugins_url( '/assets/accordian/jquery-ui.css', __FILE__) );		
				wp_enqueue_script('accordian-jquery-ui', plugins_url( '/assets/accordian/jquery-ui.js',__FILE__ ));
			
				wp_enqueue_style( 'hmgt-calender-css', plugins_url( '/assets/css/fullcalendar.css', __FILE__) );
				wp_enqueue_style( 'hmgt-datatable-css', plugins_url( '/assets/css/dataTables.css', __FILE__) );
				wp_enqueue_style( 'hmgt-admin-style-css', plugins_url( '/admin/css/admin-style.css', __FILE__) );
				wp_enqueue_style( 'hmgt-style-css', plugins_url( '/assets/css/style.css', __FILE__) );
				wp_enqueue_style( 'hmgt-popup-css', plugins_url( '/assets/css/popup.css', __FILE__) );
				wp_enqueue_style( 'hmgt-datetimepicker', plugins_url( '/assets/css/bootstrap-datetimepicker.min.css', __FILE__) );
				wp_enqueue_style( 'hmgt-custom-css', plugins_url( '/assets/css/custom.css', __FILE__) );

				wp_enqueue_script('hmgt-calender_moment', plugins_url( '/assets/js/moment.min.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
				wp_enqueue_script('hmgt-calender', plugins_url( '/assets/js/fullcalendar.min.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
				wp_enqueue_script('hmgt-datatable', plugins_url( '/assets/js/jquery.dataTables.min.js',__FILE__ ), array( 'jquery' ), '4.1.1', true);
				wp_enqueue_script('hmgt-datatable-tools', plugins_url( '/assets/js/dataTables.tableTools.min.js',__FILE__ ), array( 'jquery' ), '4.1.1', true);
				wp_enqueue_script('hmgt-datatable-editor', plugins_url( '/assets/js/dataTables.editor.min.js',__FILE__ ), array( 'jquery' ), '4.1.1', true);
				wp_enqueue_script('hmgt-datetimepicker', plugins_url( '/assets/js/bootstrap-datetimepicker.min.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
				wp_enqueue_script('hmgt-customjs', plugins_url( '/assets/js/hmgt_custom.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );

			
			
				wp_enqueue_script('hmgt-popup', plugins_url( '/assets/js/popup.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
				wp_localize_script( 'hmgt-popup', 'hmgt', array( 'ajax' => admin_url( 'admin-ajax.php' ) ) );
			 	wp_enqueue_script('jquery');
			 	wp_enqueue_media();
		       	wp_enqueue_script('thickbox');
		       	wp_enqueue_style('thickbox');
		 
		      
			 	wp_enqueue_script('hmgt-image-upload', plugins_url( '/assets/js/image-upload.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
			 
			
				wp_enqueue_style( 'hmgt-bootstrap-css', plugins_url( '/assets/css/bootstrap.min.css', __FILE__) );
				wp_enqueue_style( 'hmgt-bootstrap-multiselect-css', plugins_url( '/assets/css/bootstrap-multiselect.css', __FILE__) );
				wp_enqueue_style( 'hmgt-bootstrap-timepicker-css', plugins_url( '/assets/css/bootstrap-timepicker.min.css', __FILE__) );
			 	wp_enqueue_style( 'hmgt-font-awesome-css', plugins_url( '/assets/css/font-awesome.min.css', __FILE__) );
			 	wp_enqueue_style( 'hmgt-white-css', plugins_url( '/assets/css/white.css', __FILE__) );
			 	wp_enqueue_style( 'hmgt-hospitalmgt-min-css', plugins_url( '/assets/css/hospitalmgt.min.css', __FILE__) );
				 if (is_rtl())
				 {
					wp_enqueue_style( 'hmgt-bootstrap-rtl-css', plugins_url( '/assets/css/bootstrap-rtl.min.css', __FILE__) );
					
				 }
				 wp_enqueue_style( 'hmgt-hospitalmgt-responsive-css', plugins_url( '/assets/css/hospital-responsive.css', __FILE__) );
			  
			 	wp_enqueue_script('hmgt-bootstrap-js', plugins_url( '/assets/js/bootstrap.min.js', __FILE__ ) );
			 	wp_enqueue_script('hmgt-bootstrap-multiselect-js', plugins_url( '/assets/js/bootstrap-multiselect.js', __FILE__ ) );
			 	wp_enqueue_script('hmgt-bootstrap-timepicker-js', plugins_url( '/assets/js/bootstrap-timepicker.min.js', __FILE__ ) );
			 	wp_enqueue_script('hmgt-hospital-js', plugins_url( '/assets/js/hospitaljs.js', __FILE__ ) );
			 	
			 	//Validation style And Script
			 	
			 	//validation lib
				
			 	wp_enqueue_style( 'wcwm-validate-css', plugins_url( '/lib/validationEngine/css/validationEngine.jquery.css', __FILE__) );	 	
			 	wp_register_script( 'jquery-validationEngine-en', plugins_url( '/lib/validationEngine/js/languages/jquery.validationEngine-en.js', __FILE__), array( 'jquery' ) );
			 	wp_enqueue_script( 'jquery-validationEngine-en' );
			 	wp_register_script( 'jquery-validationEngine', plugins_url( '/lib/validationEngine/js/jquery.validationEngine.js', __FILE__), array( 'jquery' ) );
			 	wp_enqueue_script( 'jquery-validationEngine' );

				
			 	
			 
			 if(isset($_REQUEST['page']) && ($_REQUEST['page'] == 'report' || $_REQUEST['page'] == 'hospital'))
			 {
			 wp_enqueue_script('hmgt-customjs', plugins_url( '/assets/js/Chart.min.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
			 }
		}
		
	}
	if(isset($_REQUEST['page']))
	add_action( 'admin_enqueue_scripts', 'hmgt_change_adminbar_css' );
}



function hmgt_install_login_page() {

	if ( !get_option('hmgt_login_page') ) {
		

		$curr_page = array(
				'post_title' => __('Hospital Management Login Page', 'hospital_mgt'),
				'post_content' => '[hmgt_login]',
				'post_status' => 'publish',
				'post_type' => 'page',
				'comment_status' => 'closed',
				'ping_status' => 'closed',
				'post_category' => array(1),
				'post_parent' => 0 );
		

		$curr_created = wp_insert_post( $curr_page );

		update_option( 'hmgt_login_page', $curr_created );
		
		
	}
}

function hmgt_user_dashboard()
{
	
	if(isset($_REQUEST['dashboard']))
	{
		
		require_once HMS_PLUGIN_DIR. '/fronted_template.php';
		exit;
	}
	
}

function hmgt_remove_all_theme_styles() {
	global $wp_styles;
	$wp_styles->queue = array();
}
if(isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'user')
{
add_action('wp_print_styles', 'hmgt_remove_all_theme_styles', 100);
}

function hmgt_load_script1()
{
	if(isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'user')
	{
	
	
	wp_register_script('hmgt-popup-front', plugins_url( 'assets/js/popup.js', __FILE__ ), array( 'jquery' ));
	wp_enqueue_script('hmgt-popup-front');
	
	wp_localize_script( 'hmgt-popup-front', 'hmgt', array( 'ajax' => admin_url( 'admin-ajax.php' ) ) );
	 wp_enqueue_script('jquery');
	
	}

}

function hmgt_domain_load(){
	load_plugin_textdomain( 'hospital_mgt', false, dirname( plugin_basename( __FILE__ ) ). '/languages/' );
}
add_action( 'plugins_loaded', 'hmgt_domain_load' );
add_action('wp_enqueue_scripts','hmgt_load_script1');
add_action('init','hmgt_install_login_page');
add_action('wp_head','hmgt_user_dashboard');
add_shortcode( 'hmgt_login','hmgt_login_link' );
add_action('init','hmgt_output_ob_start');

function hmgt_output_ob_start()
{
	if (!file_exists(HMS_LOG_DIR))
		mkdir(HMS_LOG_DIR, 0777, true);
	$file_name = 'hmgt_log.txt';
if (!file_exists(HMS_LOG_DIR.$file_name)) {
			$fh = fopen(HMS_LOG_DIR.$file_name, 'w');
			echo HMS_LOG_DIR;
			
		}
		
	ob_start();
}

//Register Post Type
function hmgt_register_post()
{
	register_post_type( 'hmgt_event', array(

			'labels' => array(

					'name' => __( 'Event', 'hospital_mgt' ),

					'singular_name' => 'hmgt_event'),

			'rewrite' => false,

			'query_var' => false ) );
	register_post_type( 'hmgt_notice', array(
	
			'labels' => array(
	
					'name' => __( 'Notice', 'hospital_mgt' ),
	
					'singular_name' => 'hmgt_notice'),
	
			'rewrite' => false,
	
			'query_var' => false ) );
	register_post_type( 'bedtype_category', array(
	
			'labels' => array(
	
					'name' => __( 'Bed Category', 'hospital_mgt' ),
	
					'singular_name' => 'bedtype_category'),
	
			'rewrite' => false,
	
			'query_var' => false ) );
	register_post_type( 'department', array(
	
			'labels' => array(
	
					'name' => __( 'Department', 'hospital_mgt' ),
	
					'singular_name' => 'department'),
	
			'rewrite' => false,
	
			'query_var' => false ) );
	
	register_post_type( 'hmgt_message', array(
	
			'labels' => array(
	
					'name' => __( 'Message', 'hospital_mgt' ),
	
					'singular_name' => 'hmgt_message'),
	
			'rewrite' => false,
	
			'query_var' => false ) );
	
	register_post_type( 'medicine_category', array(
	
			'labels' => array(
	
					'name' => __( 'Medicine Category', 'hospital_mgt' ),
	
					'singular_name' => 'medicine_category'),
	
			'rewrite' => false,
	
			'query_var' => false ) );
	
	register_post_type( 'nurse_notes', array(
	
			'labels' => array(
	
					'name' => __( 'Nurse Notes', 'hospital_mgt' ),
	
					'singular_name' => 'nurse_notes'),
	
			'rewrite' => false,
	
			'query_var' => false ) );
	
	register_post_type( 'operation_category', array(
	
			'labels' => array(
	
					'name' => __( 'Operation Category', 'hospital_mgt' ),
	
					'singular_name' => 'operation_category'),
	
			'rewrite' => false,
	
			'query_var' => false ) );
	
	register_post_type( 'report_type_category', array(
	
			'labels' => array(
	
					'name' => __( 'Report Type', 'hospital_mgt' ),
	
					'singular_name' => 'report_type_category'),
	
			'rewrite' => false,
	
			'query_var' => false ) );
	
	register_post_type( 'specialization', array(
	
			'labels' => array(
	
					'name' => __( 'Spacialization', 'hospital_mgt' ),
	
					'singular_name' => 'specialization'),
	
			'rewrite' => false,
	
			'query_var' => false ) );

}

//Inatall Table
function hmgt_install_tables()
{
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	global $wpdb;
	$table_hmgt_admit_reason = $wpdb->prefix . 'hmgt_admit_reason';

	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_admit_reason." (
			 `reason_is` int(11) NOT NULL AUTO_INCREMENT,
			  `admit_reason` varchar(100) NOT NULL,
			  `create_date` date NOT NULL,
			  `create_by` int(11) NOT NULL,
			  PRIMARY KEY (`reason_is`)
			) DEFAULT CHARSET=latin1";
	dbDelta($sql);
	
	$table_hmgt_ambulance = $wpdb->prefix . 'hmgt_ambulance';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_ambulance." (
			 `amb_id` int(11) NOT NULL AUTO_INCREMENT,
			  `ambulance_id` varchar(30) NOT NULL,
			  `registerd_no` varchar(25) NOT NULL,
			  `driver_name` varchar(50) NOT NULL,
			  `driver_address` varchar(300) NOT NULL,
			  `driver_phoneno` varchar(20) NOT NULL,
			  `charge` int(11) NOT NULL,
			  `description` text NOT NULL,
			  `driver_image` varchar(200) NOT NULL,
			  `amb_created_date` date NOT NULL,
			  `amb_createdby` int(11) NOT NULL,
			  PRIMARY KEY (`amb_id`)
			) DEFAULT CHARSET=latin1";
	dbDelta($sql);
	
	$table_hmgt_ambulance_req = $wpdb->prefix . 'hmgt_ambulance_req';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_ambulance_req." (
			`amb_req_id` int(11) NOT NULL AUTO_INCREMENT,
			  `ambulance_id` int(11) NOT NULL,
			  `patient_id` int(11) NOT NULL,
			  `address` varchar(1000) NOT NULL,
			  `charge` int(11) NOT NULL,
			  `request_date` date NOT NULL,
			  `request_time` time NOT NULL,
			  `dispatch_time` time NOT NULL,
			  `amb_req_create_date` date NOT NULL,
			  `amb_create_by` int(11) NOT NULL,
			  PRIMARY KEY (`amb_req_id`)
			) DEFAULT CHARSET=latin1";
	dbDelta($sql);
	
	$table_hmgt_appointment = $wpdb->prefix . 'hmgt_appointment';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_appointment." (
			`appointment_id` int(11) NOT NULL AUTO_INCREMENT,
			  `appointment_date` date NOT NULL,
			  `appointment_time` time NOT NULL,
			  `appointment_time_string` varchar(50) NOT NULL,
			  `patient_id` int(11) NOT NULL,
			  `doctor_id` int(11) NOT NULL,
			  `appointment_status` int(11) NOT NULL,
			  `appoint_create_date` date NOT NULL,
			  `appoint_create_by` int(11) NOT NULL,
			  PRIMARY KEY (`appointment_id`)
			) DEFAULT CHARSET=latin1";
	dbDelta($sql);
	
	$table_hmgt_assign_type = $wpdb->prefix . 'hmgt_assign_type';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_assign_type." (
			`assign_id` int(11) NOT NULL AUTO_INCREMENT,
			  `child_id` int(11) NOT NULL,
			  `parent_id` int(11) NOT NULL,
			  `assign_type` varchar(30) NOT NULL,
			  `assign_date` date NOT NULL,
			  `assign_by` int(11) NOT NULL,
			  PRIMARY KEY (`assign_id`)
			) DEFAULT CHARSET=latin1";
	dbDelta($sql);
	
	$table_hmgt_bed = $wpdb->prefix . 'hmgt_bed';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_bed." (
			`bed_id` int(11) NOT NULL AUTO_INCREMENT,
			  `bed_number` varchar(10) NOT NULL,
			  `bed_type_id` int(11) NOT NULL,
			  `bed_charges` double NOT NULL,
			  `bed_description` text NOT NULL,
			  `bed_create_date` date NOT NULL,
			  `bed_create_by` int(11) NOT NULL,
			  `status` tinyint(1) NOT NULL,
			  PRIMARY KEY (`bed_id`)
			) DEFAULT CHARSET=latin1";
	dbDelta($sql);
	
	$table_hmgt_bed_allotment = $wpdb->prefix . 'hmgt_bed_allotment';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_bed_allotment." (
			`bed_allotment_id` int(11) NOT NULL AUTO_INCREMENT,
			  `bed_type_id` int(11) NOT NULL,
			  `bed_number` int(11) NOT NULL,
			  `patient_id` int(11) NOT NULL,
			  `patient_status` varchar(20) NOT NULL,
			  `allotment_date` date NOT NULL,
			  `discharge_time` date NOT NULL,
			  `allotment_description` text NOT NULL,
			  `created_date` int(11) NOT NULL,
			  `allotment_by` int(11) NOT NULL,
			  PRIMARY KEY (`bed_allotment_id`)
			) DEFAULT CHARSET=latin1";
	dbDelta($sql);
	
	$table_hmgt_bld_donor = $wpdb->prefix . 'hmgt_bld_donor';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_bld_donor." (
			 `bld_donor_id` int(11) NOT NULL AUTO_INCREMENT,
			  `donor_name` varchar(100) NOT NULL,
			  `donor_gender` varchar(50) NOT NULL,
			  `donor_age` int(10) NOT NULL,
			  `donor_phone` varchar(25) NOT NULL,
			  `donor_email` varchar(50) NOT NULL,
			  `blood_group` varchar(20) NOT NULL,
			  `last_donet_date` date NOT NULL,
			  `donor_create_date` date NOT NULL,
			  `donor_create_by` int(11) NOT NULL,
			  PRIMARY KEY (`bld_donor_id`)
			) DEFAULT CHARSET=latin1";
	dbDelta($sql);
	
	$table_hmgt_blood_bank = $wpdb->prefix . 'hmgt_blood_bank';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_blood_bank." (
			 `blood_id` int(11) NOT NULL AUTO_INCREMENT,
			  `blood_group` varchar(10) NOT NULL,
			  `blood_status` int(10) NOT NULL,
			  `blood_create_date` date NOT NULL,
			  `blood_create_by` int(11) NOT NULL,
			  PRIMARY KEY (`blood_id`)
			) DEFAULT CHARSET=latin1";
	dbDelta($sql);
	
	$table_hmgt_charges = $wpdb->prefix . 'hmgt_charges';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_charges." (
			`charges_id` int(11) NOT NULL AUTO_INCREMENT,
			  `charge_label` varchar(100) NOT NULL,
			  `charge_type` varchar(100) NOT NULL,
			  `room_number` varchar(100) NOT NULL,
			  `bed_type` varchar(100) NOT NULL,
			  `charges` int(11) NOT NULL,
			  `patient_id` int(11) NOT NULL,
			  `status` tinyint(4) NOT NULL,
			  `refer_id` int(11) NOT NULL,
			  `created_date` date NOT NULL,
			  `created_by` int(11) NOT NULL,
			  PRIMARY KEY (`charges_id`)
			) DEFAULT CHARSET=latin1";
	dbDelta($sql);
	
	$table_hmgt_diagnosis = $wpdb->prefix . 'hmgt_diagnosis';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_diagnosis." (
			`diagnosis_id` int(11) NOT NULL AUTO_INCREMENT,
			  `diagnosis_date` date NOT NULL,
			  `patient_id` int(11) NOT NULL,
			  `report_type` varchar(100) NOT NULL,
			 `report_cost` int(11) NOT NULL,
			  `attach_report` varchar(500) NOT NULL,
			  `diagno_description` text NOT NULL,
			  `diagno_create_by` int(11) NOT NULL,
			  PRIMARY KEY (`diagnosis_id`)
			) DEFAULT CHARSET=latin1";
	dbDelta($sql);
	
	$table_hmgt_history = $wpdb->prefix . 'hmgt_history';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_history." (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			  `patient_id` int(11) NOT NULL,
			  `status` varchar(30) NOT NULL,
			  `bed_number` varchar(20) NOT NULL,
			  `guardian_name` varchar(200) NOT NULL,
			  `history_type` varchar(30) NOT NULL,
			  `parent_id` int(11) NOT NULL,
			  `history_date` datetime NOT NULL,
			  `created_by` int(11) NOT NULL,
			  PRIMARY KEY (`id`)
			) DEFAULT CHARSET=latin1";
	dbDelta($sql);
	
	$table_hmgt_inpatient_guardian = $wpdb->prefix . 'hmgt_inpatient_guardian';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_inpatient_guardian." (
			`inpatient_id` int(11) NOT NULL AUTO_INCREMENT,
			  `guardian_id` varchar(20) NOT NULL,
			  `patient_id` int(11) NOT NULL,
			  `first_name` varchar(100) NOT NULL,
			  `middle_name` varchar(100) NOT NULL,
			  `last_name` varchar(100) NOT NULL,
			  `gr_gender` varchar(50) NOT NULL,
			  `gr_address` varchar(200) NOT NULL,
			  `gr_city` varchar(100) NOT NULL,
			  `gr_mobile` varchar(25) NOT NULL,
			  `gr_phone` varchar(25) NOT NULL,
			  `gr_relation` varchar(50) NOT NULL,
			  `image` varchar(200) NOT NULL,
			  `admit_date` date NOT NULL,
			  `admit_time` time NOT NULL,
			  `patient_status` varchar(100) NOT NULL,
			  `doctor_id` int(11) NOT NULL,
			  `symptoms` text NOT NULL,
			  `inpatient_create_date` date NOT NULL,
			  `inpatient_create_by` int(11) NOT NULL,
			  PRIMARY KEY (`inpatient_id`)
			) DEFAULT CHARSET=latin1";
	dbDelta($sql);
	
	$table_hmgt_invoice = $wpdb->prefix . 'hmgt_invoice';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_invoice." (
			`invoice_id` int(11) NOT NULL AUTO_INCREMENT,
			  `invoice_title` varchar(100) NOT NULL,
			  `invoice_number` varchar(25) NOT NULL,
			  `patient_id` int(11) NOT NULL,
			  `invoice_create_date` date NOT NULL,
			  `vat_percentage` double NOT NULL,
			  `discount` double NOT NULL,
			  `status` varchar(50) NOT NULL,
			  `invoice_amount` int(11) NOT NULL,
			  `invoice_create_by` int(11) NOT NULL,
			  PRIMARY KEY (`invoice_id`)
			) DEFAULT CHARSET=latin1";
	dbDelta($sql);
	
	$table_hmgt_medicine = $wpdb->prefix . 'hmgt_medicine';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_medicine." (
			`medicine_id` int(11) NOT NULL AUTO_INCREMENT,
			  `medicine_name` varchar(100) NOT NULL,
			  `med_cat_id` int(11) NOT NULL,
			  `medicine_price` int(11) NOT NULL,
			  `medicine_menufacture` varchar(250) NOT NULL,
			  `medicine_description` text NOT NULL,
			  `medicine_stock` varchar(5) NOT NULL,
			  `med_create_date` date NOT NULL,
			  `med_create_by` int(11) NOT NULL,
			  PRIMARY KEY (`medicine_id`)
			) DEFAULT CHARSET=latin1";
	dbDelta($sql);
	
	$table_hmgt_message = $wpdb->prefix . 'hmgt_message';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_message." (
			`message_id` int(11) NOT NULL AUTO_INCREMENT,
			  `sender` varchar(100) NOT NULL,
			  `receiver` varchar(100) NOT NULL,
			  `msg_date` date NOT NULL,
			  `msg_subject` varchar(100) NOT NULL,
			  `message_body` text NOT NULL,
			  `msg_status` tinyint(4) NOT NULL,
			  PRIMARY KEY (`message_id`)
			) DEFAULT CHARSET=latin1";
	dbDelta($sql);
	
	$table_hmgt_ot = $wpdb->prefix . 'hmgt_ot';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_ot." (
			 `operation_id` int(11) NOT NULL AUTO_INCREMENT,
			  `operation_title` varchar(100) NOT NULL,
			  `patient_id` int(11) NOT NULL,
			  `patient_status` varchar(25) NOT NULL,
			  `bed_type_id` int(11) NOT NULL,
			  `bednumber` int(11) NOT NULL,
			  `doctor_id` int(11) NOT NULL,
			  `operation_date` date NOT NULL,
			  `operation_time` time NOT NULL,
			  `ot_description` text NOT NULL,
			  `operation_charge` int(11) NOT NULL,
			  `ot_create_date` date NOT NULL,
			  `ot_create_by` int(11) NOT NULL,
			  PRIMARY KEY (`operation_id`)
			) DEFAULT CHARSET=latin1";
	dbDelta($sql);
	
	$table_hmgt_priscription = $wpdb->prefix . 'hmgt_priscription';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_priscription." (
			`priscription_id` int(11) NOT NULL AUTO_INCREMENT,
			  `pris_create_date` date NOT NULL,
			  `patient_id` int(11) NOT NULL,
			  `teratment_id` int(11) NOT NULL,
			  `case_history` text NOT NULL,
			  `medication_list` text NOT NULL,
			  `treatment_note` text NOT NULL,
			  `prescription_by` int(11) NOT NULL,
			  `custom_field` varchar(6000) NOT NULL,
			  PRIMARY KEY (`priscription_id`)
			) DEFAULT CHARSET=latin1";
	dbDelta($sql);
	
	$table_hmgt_report = $wpdb->prefix . 'hmgt_report';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_report." (
			`rid` int(11) NOT NULL AUTO_INCREMENT,
			  `patient_id` int(11) NOT NULL,
			  `report_type` varchar(10) NOT NULL,
			  `report_description` text NOT NULL,
			  `report_date` date NOT NULL,
			  `created_date` date NOT NULL,
			  `createdby` int(11) NOT NULL,
			  PRIMARY KEY (`rid`)
			) DEFAULT CHARSET=latin1";
	dbDelta($sql);
	
	$table_hmgt_treatment = $wpdb->prefix . 'hmgt_treatment';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_treatment." (
			`treatment_id` int(11) NOT NULL AUTO_INCREMENT,
			  `treatment_name` varchar(100) NOT NULL,
			  `treatment_price` double NOT NULL,
			  `treat_create_Date` date NOT NULL,
			  `treat_create_by` int(11) NOT NULL,
			  PRIMARY KEY (`treatment_id`)
			) DEFAULT CHARSET=latin1";
	dbDelta($sql);
	
	$table_hmgt_income_expense = $wpdb->prefix . 'hmgt_income_expense';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_income_expense." (
		  `income_id` int(11) NOT NULL AUTO_INCREMENT,
		  `invoice_type` varchar(25) NOT NULL,
		  `party_name` text NOT NULL,
		  `income_entry` text NOT NULL,
		  `payment_status` varchar(25) NOT NULL,
		  `income_create_by` int(11) NOT NULL,
		  `income_create_date` date NOT NULL,
		  PRIMARY KEY (`income_id`)
		  )DEFAULT CHARSET=latin1" ;
	
	dbDelta($sql);
	$custom_field =  'custom_field';
	
	if (!in_array($custom_field, $wpdb->get_col( "DESC " . $table_hmgt_priscription, 0 ) )){  $result= $wpdb->query(
			"ALTER     TABLE $custom_field     ADD $table_hmgt_priscription     VARCHAR(6000)     NOT NULL"
	);}
}

?>