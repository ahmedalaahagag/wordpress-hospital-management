<?php
/**
 * Plugin Name: Booking Calendar WpDevArt
 * Plugin URI: http://wpdevart.com/wordpress-booking-calendar-plugin
 * Description: WordPress Booking Calendar plugin is an awesome tool for creating booking systems for your website. Create booking calendars in a few minutes.
 * Version: 1.0.4
 * Author: wpdevart
 * License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
 
 
class wpdevart_bc_calendar{
	
	protected $version = "1.0";
	protected $prefix = 'wpdevart';
	public $options;
	
	
	function __construct(){
		@session_start();
		$this->setup_constants();		//Setup constants
		$this->require_files();
		
		$this->call_base_filters();		//Function for the main filters (hooks)
		//$this->install_databese();		//Database function
		$this->create_admin_menu();		//Function for creating admin menu
		add_shortcode(WPDEVART_PLUGIN_PREFIX."_booking_calendar", array($this,'shortcodes'));
	}
	
	/**
	* Setup constants
	**/
	private function setup_constants() {
		if ( ! defined( 'WPDEVART_PLUGIN_DIR' ) ) {
			define( 'WPDEVART_PLUGIN_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
		}
		if ( ! defined( 'WPDEVART_PLUGIN_PREFIX' ) ) {
			define( 'WPDEVART_PLUGIN_PREFIX', $this->prefix );
		}
		if(! defined( 'WPDEVART_URL' ) ){
			define ('WPDEVART_URL', trailingslashit( plugins_url('', __FILE__ ) ) );
		}if(! defined( 'WPDEVART_VERSION' ) ){
			define ('WPDEVART_VERSION', $this->version);
		}
	}

	/**
	* Require files
	**/
	private function require_files() {
		require_once(WPDEVART_PLUGIN_DIR.'includes/currency_list.php');
		require_once(WPDEVART_PLUGIN_DIR.'includes/wpdevart_lib.php');
		require_once(WPDEVART_PLUGIN_DIR.'includes/booking_class.php');
	}
	
	private function create_admin_menu(){
		// Registration of file that is responsible for admin menu
		require_once(WPDEVART_PLUGIN_DIR.'includes/admin_menu.php');
		// Creation of admin menu object type 
		$wpdevart_admin_menu = new wpdevart_bc_admin_menu(array('menu_name' => 'Booking Calendar'));
		//Hook that will connect admin menu with class
		add_action('admin_menu', array($wpdevart_admin_menu,'create_menu'));
		
	}
	
	public function shortcodes($attr) {
		extract(shortcode_atts(array(
			'id' => null
		), $attr, WPDEVART_PLUGIN_PREFIX));
		if (empty($id)) {
			return;
		}
		$result = $this->wpdevart_booking_calendar($id);
		return  $result;
	}
	
	public function wpdevart_booking_calendar($id=0, $date='', $ajax = false, $selected = array(),$data = array()) {
		wp_enqueue_script( 'wpdevart-script' );
		wp_localize_script( 'wpdevart-script', WPDEVART_PLUGIN_PREFIX, array(
			'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
			'ajaxNonce'   => wp_create_nonce( 'wpdevart_ajax_nonce' )
		) );
		require_once(WPDEVART_PLUGIN_DIR . "/admin/models/Themes.php");
		$theme_model = new wpdevart_bc_ModelThemes();
		
		require_once(WPDEVART_PLUGIN_DIR . "/admin/models/Calendars.php");
		$calendar_model = new wpdevart_bc_ModelCalendars();
		
		require_once(WPDEVART_PLUGIN_DIR . "/admin/models/Forms.php");
		$form_model = new wpdevart_bc_ModelForms();
		require_once(WPDEVART_PLUGIN_DIR . "/admin/models/Extras.php");
		$extra_model = new wpdevart_bc_ModelExtras();
		$ids = $calendar_model->get_ids($id);
		$theme_option = $theme_model->get_setting_rows($ids["theme_id"]);
		$calendar_data = $calendar_model->get_db_days_data($id);
		$extra_field = $extra_model->get_extra_rows($ids["extra_id"]);
		$form_option = $form_model->get_form_rows($ids["form_id"]);
		if(isset($theme_option)) {
			$theme_option = json_decode($theme_option->value, true);	
		} else {
			$theme_option = array();
		}
			
		$calendar_start = new wpdevart_bc_BookingCalendar($date, $id, $theme_option, $calendar_data, $form_option, $extra_field);
		
		$request_message = "";
		if(isset($data["wpdevart-submit".$id])){
			$save = $calendar_start->save_reserv($data);
			if(!is_admin() || count($data)) {
				if($save && isset($theme_option["enable_instant_approval"]) && $theme_option["enable_instant_approval"] == "on") {
					$request_message = ((isset($theme_option["for_request_successfully_received"])) ? sprintf(esc_html__("%s",'booking-calendar'),$theme_option["for_request_successfully_received"]) : __("Your request has been successfully received. We are waiting you!",'booking-calendar'));
				} elseif($save) {
					$request_message = ((isset($theme_option["for_request_successfully_sent"])) ? sprintf(esc_html__("%s",'booking-calendar'),$theme_option["for_request_successfully_sent"]) : __("Your request has been successfully sent. Please wait for approval.",'booking-calendar'));
				}
				if(isset($theme_option["action_after_submit"]) && $theme_option["action_after_submit"] == "stay_on_calendar") {
					$submit_message = $theme_option["message_text"];
				}
				else { ?>
					<script>
						window.location.href = "<?php echo esc_url($theme_option["redirect_url"]); ?>";
					</script>
					<?php exit();
				}
			} else {
				return;
			}
		}
		
		$calendar_data_after_save = $calendar_model->get_db_days_data($id);
		$calendar = new wpdevart_bc_BookingCalendar($date, $id, $theme_option, $calendar_data_after_save, $form_option,$extra_field, $selected,$ajax);
		$booking_calendar = "";
				
		if (!$ajax) {
			$booking_calendar .= "<div class='booking_calendar_container' id='booking_calendar_container_" . $id . "' data-total='".((isset($theme_option["for_total"])) ? sprintf(esc_html__("%s",'booking-calendar'),$theme_option["for_total"]) : __("Total",'booking-calendar'))."' data-price='".((isset($theme_option["for_price"])) ? sprintf(esc_html__("%s",'booking-calendar'),$theme_option["for_price"]) : __("Price",'booking-calendar'))."'><div class='wpdevart-load-overlay'><div class='wpdevart-load-image'></div></div>";
		}	
		if (isset($submit_message) && $submit_message != "") {
			$booking_calendar .= "<div class='booking_calendar_message successfully_text_container'>".$submit_message."<span class='notice_text_close'>x</span></div>";
		}
		if($request_message != "") {
			$booking_calendar .= "<div class='successfully_text_container div-for-clear'>".$request_message."<span class='notice_text_close'>x</span></div>";
		}
		if (!$ajax) {
			if($theme_option["message_text"] == "multiple_days") {
				$booking_calendar .= "<div class='error_text_container div-for-clear'><span class='error_text'>".((isset($theme_option["for_error_multi"])) ? sprintf(esc_html__("%s",'booking-calendar'),$theme_option["for_error_multi"]) : __("There are no services available for the period you selected.",'booking-calendar'))."</span><span class='notice_text_close'>x</span></div>";
			} else {
				$booking_calendar .= "<div class='error_text_container div-for-clear'><span class='error_text'>".((isset($theme_option["for_error_single"])) ? sprintf(esc_html__("%s",'booking-calendar'),$theme_option["for_error_single"]) : __("There are no services available for this day.",'booking-calendar'))."</span><span class='notice_text_close'>x</span></div>";
			}
		}	
		$booking_calendar .= "<div class='booking_calendar_main'>";
		$booking_calendar .= $calendar->booking_calendar();
		if (!$ajax) {
			$booking_calendar .= "</div>";
		}
		$booking_calendar .= "</div>";
		if(!is_admin() || (isset($_GET["page"]) && $_GET["page"] == "wpdevart-reservations")) {
			$booking_calendar .= $calendar->booking_form();
		}
		
		return $booking_calendar;
	}

	
	
	public function wpdevart_booking_calendar_res($id=0, $date='', $ajax = false) {
		wp_enqueue_script( WPDEVART_PLUGIN_PREFIX.'-script' );
		wp_localize_script( WPDEVART_PLUGIN_PREFIX.'-script', WPDEVART_PLUGIN_PREFIX, array(
			'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
			'ajaxNonce'   => wp_create_nonce( 'wpdevart_ajax_nonce' )
		) );
		require_once(WPDEVART_PLUGIN_DIR . "/admin/models/Themes.php");
		$theme_model = new wpdevart_bc_ModelThemes();
		
		require_once(WPDEVART_PLUGIN_DIR . "/admin/models/Calendars.php");
		$calendar_model = new wpdevart_bc_ModelCalendars();
		
		require_once(WPDEVART_PLUGIN_DIR . "/admin/models/Forms.php");
		$form_model = new wpdevart_bc_ModelForms();
		require_once(WPDEVART_PLUGIN_DIR . "/admin/models/Extras.php");
		$extra_model = new wpdevart_bc_ModelExtras();
		$ids = $calendar_model->get_ids($id);
		$theme_option = $theme_model->get_setting_rows($ids["theme_id"]);
		$calendar_data = $calendar_model->get_db_days_data($id);
		$extra_field = $extra_model->get_extra_rows($ids["extra_id"]);
		$form_option = $form_model->get_form_rows($ids["form_id"]);
		$theme_option =json_decode($theme_option->value, true);		
		$calendar = new wpdevart_bc_BookingCalendar($date, $id, $theme_option, $calendar_data, $form_option, $extra_field,array());
		$booking_calendar = "";
				
		if (!$ajax) {
			$booking_calendar .= "<div class='booking_calendar_container' id='booking_calendar_container_" . $id . "'><div class='wpdevart-load-overlay'><div class='wpdevart-load-image'></div></div>";
		}	
			
		$booking_calendar .= "<div class='booking_calendar_main'>";
		$booking_calendar .= $calendar->booking_calendar("reservation");
		if (!$ajax) {
			$booking_calendar .= "</div>";
		}
		$booking_calendar .= "</div>";		
		return $booking_calendar;
	}
	
	public function wpdevart_ajax() {
		if(!check_ajax_referer('wpdevart_ajax_nonce', 'wpdevart_nonce')) {
			die('Request has failed.');
		}
		$id = 0;
		$selected = array();
		if(isset($_POST['wpdevart_link'])) {
			$link = esc_html( $_POST['wpdevart_link'] );
			parse_str( $link, $link_arr );
			$date = $link_arr['?date'];
		}
		if(isset($_POST['wpdevart_id'])) {
			$id = esc_html($_POST['wpdevart_id']);
		}
		if(isset($_POST['wpdevart_reserv'])) {
			$reserv = esc_html($_POST['wpdevart_reserv']);
		}
		if(isset($_POST['wpdevart_selected'])) {
			$selected["index"] = esc_html($_POST['wpdevart_selected']);
		}
		if(isset($_POST['wpdevart_selected_date'])) {
			$selected["date"] = esc_html($_POST['wpdevart_selected_date']);
		}
		if(isset($reserv) && $reserv == "true") {
			echo $this->wpdevart_booking_calendar_res($id,$date,true,$selected);
		} else {
			echo $this->wpdevart_booking_calendar($id,$date,true,$selected);
		}
		wp_die();
	}
	
	public function wpdevart_form_ajax() {
		if(!check_ajax_referer('wpdevart_ajax_nonce', 'wpdevart_nonce')) {
			die('Request has failed.');
		}
		$id = 0;
		$data = array();
		if(isset($_POST['wpdevart_id'])) {
			$id = esc_html($_POST['wpdevart_id']);
		}
		if(isset($_POST['wpdevart_data'])) {
			$data = json_decode(stripcslashes($_POST['wpdevart_data']),true);
		}
		echo $this->wpdevart_booking_calendar($id,"",true,array(),$data);
		wp_die();
	}
	
	public function wpdevart_add_field() {
		$max_id = 0;
		$count = 0;
		if ( isset( $_POST['wpdevart_field_type'] ) ) {
			$type = str_replace('_field', '', esc_html( $_POST['wpdevart_field_type']));
		}
		if ( isset( $_POST['wpdevart_field_max'] ) ) {
			$max_id = esc_html( $_POST['wpdevart_field_max']);
		}
		if ( isset( $_POST['wpdevart_field_count'] ) ) {
			$count = esc_html( $_POST['wpdevart_field_count']);
		}
		$args =  array(
			'name'   => 'form_field' . ($max_id + 1 + $count),
			'label' => __( 'New ' . $type . ' Field', 'booking-calendar' ),
			'type' => $type,
			'default' => ''
		);
		$function_name = "wpdevart_form_" . $type;
		wpdevart_bc_Library::$function_name($args, array('label' => __( 'New ' . $type . ' Field', 'booking-calendar' )));
		wp_die();
	}
	
	public function wpdevart_add_extra_field() {
		$max_id = 0;
		$count = 0;
		if ( isset( $_POST['wpdevart_extra_field_max'] ) ) {
			$max_id = esc_html( $_POST['wpdevart_extra_field_max']);
		}
		if ( isset( $_POST['wpdevart_extra_field_count'] ) ) {
			$count = esc_html( $_POST['wpdevart_extra_field_count']);
		}
		$args =  array(
			'name'   => 'extra_field' . ($max_id + 1 + $count),
			'label' => __( 'New Extra', 'booking-calendar' ),
			'type' => 'extras_field',
			'items' => array(
				'field_item1' => array('name'=>'field_item1',
									'label' => '1',
									'operation' => '+',
									'price_type' => 'price',
									'price_percent' => '0',
									'order' => '1'
								),
				'field_item2' => array('name'=>'field_item2',
									'label' => '2',
									'operation' => '+',
									'price_type' => 'price',
									'price_percent' => '0',
									'order' => '2'
								),
				'field_item3' => array('name'=>'field_item3',
									'label' => '3',
									'operation' => '+',
									'price_type' => 'price',
									'price_percent' => '0',
									'order' => '3'
								)
			),
			'default' => ''
		);
		wpdevart_bc_Library::wpdevart_extras_field($args,$args);
		wp_die();
	}	
	
	public function wpdevart_add_extra_field_item() {
		$count = 0;
		$max_id = 0;
		if ( isset( $_POST['wpdevart_extra_field_item_max'] ) ) {
			$max_id = esc_html( $_POST['wpdevart_extra_field_item_max']);
		}
		if ( isset( $_POST['wpdevart_extra_field'] ) ) {
			$extra_field = esc_html( $_POST['wpdevart_extra_field']);
		}
		if ( isset( $_POST['wpdevart_extra_field_item_count'] ) ) {
			$count = esc_html( $_POST['wpdevart_extra_field_item_count']);
		}
		$args =  array('name'=>'field_item'. ($max_id + 1 + $count),
			'label' => ($max_id + 1),
			'operation' => '+',
			'price_type' => 'price',
			'price_percent' => '0',
			'order' => ($max_id + 1)
		);
		wpdevart_bc_Library::wpdevart_extras_field_item($extra_field,$args);
		wp_die();
	}
	
	public function install_databese(){
		$version = get_option("wpdevart_booking_version");
        $new_version = $this->version;
		//registration of file that is responsible for database
		require_once(WPDEVART_PLUGIN_DIR.'includes/install_database.php');
		//Creation of database object type 
		$coming_install_database = new wpdevart_bc_install_database();
		if (!$version) {
			$coming_install_database->install_databese();
			add_option("wpdevart_booking_version", $new_version, '', 'no');
		}
		
	}
	
	public function registr_requeried_scripts(){
		load_plugin_textdomain( 'booking-calendar', FALSE, basename(dirname(__FILE__)).'/languages' );
		wp_enqueue_script( 'jquery-ui-datepicker', array('jquery') ); 
		if(!is_admin()){
			wp_register_script( 'wpdevart-script', WPDEVART_URL.'js/script.js', array("jquery"));
			wp_localize_script( 'wpdevart-script', WPDEVART_PLUGIN_PREFIX, array(
				'ajaxUrl'         => admin_url( 'admin-ajax.php' ),
				'ajaxNonce'       => wp_create_nonce( 'wpdevart_ajax_nonce' ),
				'required' => __("is required.",'booking-calendar'),
				'emailValid' => __("Enter the valid email address.",'booking-calendar'),
				'date' => __("Date",'booking-calendar')
			) );
			wp_enqueue_script( 'wpdevart-script' );
		}
		wp_enqueue_script( 'scrollto', WPDEVART_URL.'js/jquery.scrollTo-min.js', array("jquery") );
		wp_register_style( 'jquery-ui',  WPDEVART_URL.'css/jquery-ui.css');
		wp_enqueue_style( 'jquery-ui' );
		wp_enqueue_style( 'wpdevart-style', WPDEVART_URL.'css/style.css');
		wp_enqueue_style( 'wpdevartcalendar-style', WPDEVART_URL.'css/booking.css');
	}
	
	public function call_base_filters(){
		add_action( 'init',  array($this,'registr_requeried_scripts') );
		//add_action( 'init',  array($this,'install_databese') );
		register_activation_hook( __FILE__, array( $this, 'install_databese' ) );
		add_action( 'wp_ajax_nopriv_wpdevart_add_field', array($this,'wpdevart_add_field') );
		add_action( 'wp_ajax_wpdevart_add_field', array($this,'wpdevart_add_field') );
		add_action( 'wp_ajax_nopriv_wpdevart_add_extra_field', array($this,'wpdevart_add_extra_field') );
		add_action( 'wp_ajax_wpdevart_add_extra_field', array($this,'wpdevart_add_extra_field') );
		add_action( 'wp_ajax_nopriv_wpdevart_add_extra_field_item', array($this,'wpdevart_add_extra_field') );
		add_action( 'wp_ajax_wpdevart_add_extra_field_item', array($this,'wpdevart_add_extra_field_item') );
		add_action( 'wp_ajax_nopriv_wpdevart_ajax', array($this,'wpdevart_ajax') );
		add_action( 'wp_ajax_wpdevart_ajax', array($this,'wpdevart_ajax') );
		add_action( 'wp_ajax_nopriv_wpdevart_form_ajax', array($this,'wpdevart_form_ajax') );
		add_action( 'wp_ajax_wpdevart_form_ajax', array($this,'wpdevart_form_ajax') );
	}
}
$wpdevart_booking = new wpdevart_bc_calendar(); // Creation of the main object

?>