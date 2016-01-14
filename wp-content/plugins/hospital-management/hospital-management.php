<?php
/*
Plugin Name: Hospital Management
Plugin URI: http://www.mobilewebs.net/mojoomla/extend/wordpress/hospital/
Description: Hospital Management System for wordpress plugin is ideal way to manage complete hospital operation. It has different user roles like doctor, patient, nurse,phramcist, accountant, laboratory staff and Support staff.
The system has different access rights for Admin, doctor, Student and Parent.
Version: 6.0
Author: Mojoomla
Author URI: http://codecanyon.net/user/dasinfomedia
Text Domain: hospital_mgt
Domain Path: /languages/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Copyright 2015  Mojoomla  (email : sales@mojoomla.com)
*/
define( 'HMS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'HMS_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );
define( 'HMS_PLUGIN_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );
define( 'HMS_CONTENT_URL',  content_url( ));
define( 'HMS_LOG_DIR',  WP_CONTENT_DIR.'/uploads/hospital_logs/');
define( 'HMS_LOG_file', HMS_LOG_DIR.'/hmgt_log.txt' );
require_once HMS_PLUGIN_DIR . '/settings.php';
?>