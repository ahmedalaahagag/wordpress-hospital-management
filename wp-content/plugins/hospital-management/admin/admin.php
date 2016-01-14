<?php 
 // This is adminside main First page of school management plugin 
 //add_action( 'admin_head', 'hospital_admin_menu_icon' );
function hospital_admin_menu_icon() {
?>
<style type="text/css">
#adminmenu #toplevel_page_hospital div.wp-menu-image:before {
  content: "\f512";
}
</style>
 <?php 
}
 add_action( 'admin_menu', 'hospital_menu' );
function hospital_menu() {
	add_menu_page('Hospital Management', __('Hospital Management','hospital_mgt'),'manage_options','hospital','hospital_dashboard',plugins_url( 'hospital-management/assets/images/hospital-2.png' ));
	add_submenu_page('hospital', 'Dashboard', __( 'Dashboard', 'hospital_mgt' ), 'administrator', 'hospital', 'hospital_dashboard');
	add_submenu_page('hospital', 'Doctor', __( 'Therapist', 'hospital_mgt' ), 'administrator', 'hmgt_doctor', 'doctor_function');
	add_submenu_page('hospital', 'Inpatient', __( 'Inpatient', 'hospital_mgt' ), 'administrator', 'hmgt_patient', 'patient_function');
	add_submenu_page('hospital', 'Patient', __( 'Patient', 'hospital_mgt' ), 'administrator', 'hmgt_outpatient', 'outpatient_function');
	add_submenu_page('hospital', 'Nurse', __( 'Nurse', 'hospital_mgt' ), 'administrator', 'hmgt_nurse', 'nurse_function');
	add_submenu_page('hospital', 'Support Staff', __( 'Support Staff', 'hospital_mgt' ), 'administrator', 'hmgt_receptionist', 'receptionist_function');
	add_submenu_page('hospital', 'Pharmacist', __( 'Pharmacist', 'hospital_mgt' ), 'administrator', 'hmgt_pharmacist', 'pharmacist_function');
	add_submenu_page('hospital', 'Laboratorist', __( 'Laboratory Staff', 'hospital_mgt' ), 'administrator', 'hmgt_laboratorist', 'laboratorist_function');
	add_submenu_page('hospital', 'Accountant', __( 'Accountant', 'hospital_mgt' ), 'administrator', 'hmgt_accountant', 'accountant_function');
	//add_submenu_page('hospital', 'Medicine Category', __( 'Medicine Category', 'hospital_mgt' ), 'administrator', 'medicine_category', 'med_category_function');		
	add_submenu_page('hospital', 'Medicine', __( 'Medicine', 'hospital_mgt' ), 'administrator', 'hmgt_medicine', 'medicine_function');
	add_submenu_page('hospital', 'Treatment', __( 'Treatment', 'hospital_mgt' ), 'administrator', 'hmgt_treatment', 'treatment_function');
	add_submenu_page('hospital', 'Prescription', __( 'Prescription', 'hospital_mgt' ), 'administrator', 'hmgt_prescription', 'prescription_function');
	add_submenu_page('hospital', 'Add Bed', __( 'Add Bed', 'hospital_mgt' ), 'administrator', 'hmgt_bedmanage', 'bedmanage_function');
	add_submenu_page('hospital', 'Bed Assign', __( 'Assign Bed-Nurse', 'hospital_mgt' ), 'administrator', 'hmgt_bedallotment', 'bedallotment_function');
	add_submenu_page('hospital', 'Operation List', __( 'Operation List', 'hospital_mgt' ), 'administrator', 'hmgt_operation', 'operation_function');
	add_submenu_page('hospital', 'Diagnosis Report', __( 'Diagnosis Report', 'hospital_mgt' ), 'administrator', 'hmgt_diagnosis', 'diagnosis_function');
	add_submenu_page('hospital', 'Blood Bank', __( 'Blood Bank', 'hospital_mgt' ), 'administrator', 'hmgt_bloodbank', 'bloodbank_function');
	//add_submenu_page('hospital', 'Add Bed Type', __( 'Add Bed Type', 'hospital_mgt' ), 'administrator', 'bedtype', 'bedtype_function');
	
	add_submenu_page('hospital', 'Appointment', __( 'Appointment', 'hospital_mgt' ), 'administrator', 'hmgt_appointment', 'appointment_function');	
	add_submenu_page('hospital', 'Invoice', __( 'Invoice', 'hospital_mgt' ), 'administrator', 'hmgt_invoice', 'invoice_function');
	add_submenu_page('hospital', 'Event', __( 'Events', 'hospital_mgt' ), 'administrator', 'hmgt_event', 'event_function');
	add_submenu_page('hospital', 'Message', __( 'Message', 'hospital_mgt' ), 'administrator', 'hmgt_message', 'message_function');
	add_submenu_page('hospital', 'Ambulance', __( 'Ambulance', 'hospital_mgt' ), 'administrator', 'hmgt_ambulance', 'ambulance_function');
	//add_submenu_page('hospital', 'Report', __( 'Appointment Report', 'hospital_mgt' ), 'administrator', 'appointment_report', 'appointment_report_function');
	add_submenu_page('hospital', 'Report', __( 'Report', 'hospital_mgt' ), 'administrator', 'hmgt_report', 'hmgt_report');
	add_submenu_page('hospital', 'SMS', __( 'SMS Setting', 'school-mgt' ), 'administrator', 'hmgt_sms_setting', 'hmgt_sms_setting');
	add_submenu_page('hospital', 'Audit Log', __( 'Audit Log', 'school-mgt' ), 'administrator', 'hmgt_audit_log', 'hmgt_audit_log');
	add_submenu_page('hospital', 'Gnrl_setting', __( 'General Settings', 'hospital_mgt' ), 'administrator', 'hmgt_gnrl_settings', 'hmgt_gnrl_settings');
	add_submenu_page('hospital', 'Sessions Setting', __( 'Sessions Settings', 'hospital_mgt' ), 'administrator', 'hmgt_sessions_settings', 'hmgt_sessions_settings');
	add_submenu_page('hospital', 'Sessions Duration', __( 'Sessions Duration', 'hospital_mgt' ), 'administrator', 'hmgt_sessions_durations', 'hmgt_sessions_durations');
	add_submenu_page('hospital', 'Packages', __( 'Packages', 'hospital_mgt' ), 'administrator', 'hmgt_packages', 'hmgt_packages');
}

function hospital_dashboard()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/dasboard.php';
	
}	
 function doctor_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/doctor/index.php';
}	
function patient_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/patient/index.php';
}	
function outpatient_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/outpatient/index.php';
}			

function nurse_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/nurse/index.php';
}
function receptionist_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/receptionist/index.php';
}	
function pharmacist_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/pharmacist/index.php';
}	
function laboratorist_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/laboratorist/index.php';
}	
function accountant_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/accountant/index.php';
}	
/*function med_category_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/medicine-category/index.php';
}	*/
function medicine_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/medicine/index.php';
}	
function prescription_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/prescription/index.php';
}	
function diagnosis_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/diagnosis/index.php';
}	
function bloodbank_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/blood-bank/index.php';
}
/*function bedtype_function()
{	require_once HMS_PLUGIN_DIR. '/admin/includes/bedtype/index.php';}*/
function bedmanage_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/bed/index.php';
}	
function appointment_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/appointment/index.php';
}
function treatment_function	()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/treatment/index.php';
}	
function invoice_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/invoice/index.php';
}	
function event_function()	
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/event/index.php';
}	
function hmgt_gnrl_settings()
 {
	require_once HMS_PLUGIN_DIR. '/admin/includes/general-settings.php';
}	
function message_function()
 {
	require_once HMS_PLUGIN_DIR. '/admin/includes/message/index.php';
}	
function ambulance_function()
 {
	require_once HMS_PLUGIN_DIR. '/admin/includes/ambulance/index.php';
}	
function operation_function()
 {
	require_once HMS_PLUGIN_DIR. '/admin/includes/OT/index.php';
}	
function bedallotment_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/bed-allotment/index.php';
}

function appointment_report_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/report/appointment_report.php';
}

function occupancy_report_function()
{	require_once HMS_PLUGIN_DIR. '/admin/includes/report/occupancy_report.php';}

function opearion_report_function()
{	require_once HMS_PLUGIN_DIR. '/admin/includes/report/operation_report.php';}

function fail_report_function()
{	require_once HMS_PLUGIN_DIR. '/admin/includes/report/fail_report.php';}

function birth_report_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/report/birth_report.php';
}
function hmgt_report()
{ require_once HMS_PLUGIN_DIR. '/admin/includes/report/index.php';}
function hmgt_sms_setting()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/sms_setting/index.php';
}
function hmgt_audit_log()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/auditlog/index.php';
}
function hmgt_sessions_settings()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/session/index.php';
}
function hmgt_sessions_durations()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/session_duration/index.php';
}
function hmgt_packages()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/packages/index.php';
}
?>