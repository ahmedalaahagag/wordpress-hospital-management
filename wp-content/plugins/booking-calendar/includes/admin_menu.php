<?php 

/*############  Coming Soon Admin Menu Class ################*/

class wpdevart_bc_admin_menu{
	
	private $menu_name;

	function __construct($param){
		$this->menu_name=$param['menu_name'];
	}
	
	public function create_menu(){
		$main_page = add_menu_page( $this->menu_name, $this->menu_name, 'manage_options', 'wpdevart-calendars', array($this, 'calendars_function'),WPDEVART_URL.'css/images/menu_icon.png');
		$page_bookings =	add_submenu_page('wpdevart-calendars',  "Calendars",  "Calendars", 'manage_options', 'wpdevart-calendars', array($this, 'calendars_function'));
		$page_reservation = add_submenu_page( 'wpdevart-calendars', 'Reservations', 'Reservations', 'manage_options', 'wpdevart-reservations', array($this, 'resrvations'));
		$page_forms = add_submenu_page( 'wpdevart-calendars', 'Forms', 'Forms', 'manage_options', 'wpdevart-forms', array($this, 'forms_function'));
		$page_extra = add_submenu_page( 'wpdevart-calendars', 'Extras', 'Extras', 'manage_options', 'wpdevart-extras', array($this, 'extras_function'));
		$page_themes = add_submenu_page( 'wpdevart-calendars', 'Themes', 'Themes', 'manage_options', 'wpdevart-themes', array($this, 'themes_function'));
		$page_uninstall = add_submenu_page( 'wpdevart-calendars', 'Uninstall'  , 'Uninstall', 'manage_options', 'wpdevart-booking-uninstall', array($this, 'uninstall_booking'));
		$page_featured = add_submenu_page( 'wpdevart-calendars', 'Featured plugins', 'Featured plugins', 'manage_options', 'wpdevart-add-booking', array($this, 'featured_plugins'));
		add_action('admin_print_styles-' .$main_page, array($this,'menu_requeried_scripts'));
		add_action('admin_print_styles-' .$page_bookings, array($this,'menu_requeried_scripts'));	
		add_action('admin_print_styles-' .$page_reservation, array($this,'requeried_scripts_reservation'));	
		add_action('admin_print_styles-' .$page_themes, array($this,'menu_requeried_scripts'));
		add_action('admin_print_styles-' .$page_uninstall, array($this,'menu_requeried_scripts'));
		add_action('admin_print_styles-' .$page_forms, array($this,'menu_requeried_scripts'));
		add_action('admin_print_styles-' .$page_extra, array($this,'menu_requeried_scripts'));
		add_action('admin_print_styles-' .$page_featured, array($this,'menu_requeried_scripts'));
	}
	
	public function menu_requeried_scripts(){
		wp_enqueue_script('wp-color-picker');		
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'jquery-ui-datepicker' ); 
		wp_enqueue_style(WPDEVART_PLUGIN_PREFIX.'-admin-style', WPDEVART_URL.'css/admin_style.css');
		wp_enqueue_style(WPDEVART_PLUGIN_PREFIX.'calendar-style', WPDEVART_URL.'css/booking.css');
		wp_register_script( WPDEVART_PLUGIN_PREFIX.'-admin-script', WPDEVART_URL.'js/admin_script.js', array("jquery") );
		wp_localize_script( WPDEVART_PLUGIN_PREFIX.'-admin-script', WPDEVART_PLUGIN_PREFIX, array(
			'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
			'ajaxNonce'   => wp_create_nonce( WPDEVART_PLUGIN_PREFIX . '_ajax_nonce' )
		) );
		wp_enqueue_script( WPDEVART_PLUGIN_PREFIX.'-admin-script' );
		
		if (function_exists('add_thickbox')) add_thickbox();
	}
	
	public function requeried_scripts_reservation(){
		wp_enqueue_script( 'jquery-ui-datepicker' ); 
		wp_enqueue_style(WPDEVART_PLUGIN_PREFIX.'-admin-style', WPDEVART_URL.'css/admin_style.css');
		wp_enqueue_style(WPDEVART_PLUGIN_PREFIX.'calendar-style', WPDEVART_URL.'css/booking.css');
		wp_register_script( WPDEVART_PLUGIN_PREFIX.'-reservation-script', WPDEVART_URL.'js/admin_reservation.js', array("jquery") );
		wp_localize_script( WPDEVART_PLUGIN_PREFIX.'-reservation-script', WPDEVART_PLUGIN_PREFIX, array(
			'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
			'ajaxNonce'   => wp_create_nonce( WPDEVART_PLUGIN_PREFIX . '_ajax_nonce' )
		) );
		wp_enqueue_script( WPDEVART_PLUGIN_PREFIX.'-reservation-script' );
		
		if (function_exists('add_thickbox')) add_thickbox();
	}
	
			
	public function calendars_function(){
        require_once(WPDEVART_PLUGIN_DIR . 'admin/controllers/Calendars.php');
		$controller = new wpdevart_bc_ControllerCalendars();
		$controller->perform();
		
	}		
	public function themes_function(){
        require_once(WPDEVART_PLUGIN_DIR . 'admin/controllers/Themes.php');
		$controller = new wpdevart_bc_ControllerThemes();
		$controller->perform();
	}	
	public function forms_function(){
        require_once(WPDEVART_PLUGIN_DIR . 'admin/controllers/Forms.php');
		$controller = new wpdevart_bc_ControllerForms();
		$controller->perform();
	}
	public function extras_function(){
        require_once(WPDEVART_PLUGIN_DIR . 'admin/controllers/Extras.php');
		$controller = new wpdevart_bc_ControllerExtras();
		$controller->perform();
	}
	public function resrvations(){
        require_once(WPDEVART_PLUGIN_DIR . 'admin/controllers/Reservations.php');
		$controller = new wpdevart_bc_ControllerReservations();
		$controller->perform();
	}
	
	
	
	/*################################## FEATURED PLUGINS #########################################*/
	public function featured_plugins(){
		$plugins_array=array(
			'coming_soon'=>array(
						'image_url'		=>	WPDEVART_URL.'css/images/featured_plugins/coming_soon.jpg',
						'site_url'		=>	'http://wpdevart.com/wordpress-coming-soon-plugin/',
						'title'			=>	'Coming soon and Maintenance mode',
						'description'	=>	'Coming soon and Maintenance mode plugin is an awesome tool to show your visitors that you are working on your website to make it better.'
						),
			'lightbox'=>array(
						'image_url'		=>	WPDEVART_URL.'css/images/featured_plugins/lightbox_icon.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-lightbox-plugin/',
						'title'			=>	'WordPress Lightbox',
						'description'	=>	'WordPress lightbox plugin is an high customizable and responsive product for displaying images and videos in popup.'
						),
			'youtube'=>array(
						'image_url'		=>	WPDEVART_URL.'css/images/featured_plugins/youtube.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-youtube-embed-plugin',
						'title'			=>	'WordPress YouTube Embed',
						'description'	=>	'YouTube Embed plugin is an convenient tool for adding video to your website. Use YouTube Embed plugin to add YouTube videos in posts/pages, widgets.'
						),
			'countdown'=>array(
						'image_url'		=>	WPDEVART_URL.'css/images/featured_plugins/countdown.jpg',
						'site_url'		=>	'http://wpdevart.com/wordpress-countdown-plugin/',
						'title'			=>	'WordPress Countdown plugin',
						'description'	=>	'WordPress Countdown plugin is an nice tool to create and insert countdown timers into your posts/pages and widgets.'
						),
            'facebook-comments'=>array(
						'image_url'		=>	WPDEVART_URL.'css/images/featured_plugins/facebook-comments-icon.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-facebook-comments-plugin/',
						'title'			=>	'WordPress Facebook comments',
						'description'	=>	'Our Facebook comments plugin will help you to display Facebook Comments on your website. You can use Facebook Comments on your pages/posts.'
						),						
			'facebook'=>array(
						'image_url'		=>	WPDEVART_URL.'css/images/featured_plugins/facebook.jpg',
						'site_url'		=>	'http://wpdevart.com/wordpress-facebook-like-box-plugin',
						'title'			=>	'Facebook Like Box',
						'description'	=>	'Our Facebook like box plugin will help you to display Facebook like box on your wesite, just add Facebook Like box widget to your sidebar and use it..'
						),
			'poll'=>array(
						'image_url'		=>	WPDEVART_URL.'css/images/featured_plugins/poll.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-polls-plugin',
						'title'			=>	'Poll',
						'description'	=>	'WordPress Polls plugin is an wonderful tool for creating polls and survey forms for your visitors. You can use our polls on widgets, posts and pages.'
						),
			'twitter'=>array(
						'image_url'		=>	WPDEVART_URL.'css/images/featured_plugins/twitter.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-twitter-plugin',
						'title'			=>	'Twitter button plus',
						'description'	=>	'Twitter button plus is nice and useful tool to show Twitter tweet button on your website.'
						),															
			
		);
		?>
        <script>
		
        jQuery(window).resize(wpdevart_countdown_feature_resize);
		jQuery(document).ready(function(e) {
            wpdevart_countdown_feature_resize();
        });
		
		function wpdevart_countdown_feature_resize(){
			var wpdevart_countdown_width=jQuery('.featured_plugin_main').eq(0).parent().width();
			var count_of_elements=Math.max(parseInt(wpdevart_countdown_width/450),1);
			var width_of_plugin=((wpdevart_countdown_width-count_of_elements*24-2)/count_of_elements);
			jQuery('.featured_plugin_main').width(width_of_plugin);
			jQuery('.featured_plugin_information').css('max-width',(width_of_plugin-160)+'px');
		}
       	</script>
		<h2>Featured Plugins</h2>
		<?php foreach($plugins_array as $key=>$plugin) { ?>
		<div class="featured_plugin_main">
			<span class="featured_plugin_image"><a target="_blank" href="<?php echo $plugin['site_url'] ?>"><img src="<?php echo $plugin['image_url'] ?>"></a></span>
			<span class="featured_plugin_information">
				<span class="featured_plugin_title"><h4><a target="_blank" href="<?php echo $plugin['site_url'] ?>"><?php echo $plugin['title'] ?></a></h4></span>
				<span class="featured_plugin_description"><?php echo $plugin['description'] ?></span>
			</span>
			<div style="clear:both"></div>                
		</div>
		<?php } 
	}
	
	public function uninstall_booking() {
		global $wpdb;
		if(isset( $_POST['uninstall_booking_data'] )   && wp_verify_nonce( $_POST['uninstall_booking_data'], 'uninstall_booking')){
			$wpdb->query("DROP TABLE " . $wpdb->prefix . "wpdevart_calendars");
			$wpdb->query("DROP TABLE " . $wpdb->prefix . "wpdevart_dates");
			$wpdb->query("DROP TABLE " . $wpdb->prefix . "wpdevart_forms");
			$wpdb->query("DROP TABLE " . $wpdb->prefix . "wpdevart_extras");
			$wpdb->query("DROP TABLE " . $wpdb->prefix . "wpdevart_themes");
			$wpdb->query("DROP TABLE " . $wpdb->prefix . "wpdevart_reservations");
			delete_option("wpdevart_booking_version");
			?>
			<div id="message" class="updated fade">
			  <p>The following Database Tables successfully deleted:</p>
			  <p><?php echo $wpdb->prefix; ?>wpdevart_calendars,</p>
			  <p><?php echo $wpdb->prefix; ?>wpdevart_dates,</p>
			  <p><?php echo $wpdb->prefix; ?>wpdevart_forms,</p>
			  <p><?php echo $wpdb->prefix; ?>wpdevart_extras,</p>
			  <p><?php echo $wpdb->prefix; ?>wpdevart_themes,</p>
			  <p><?php echo $wpdb->prefix; ?>wpdevart_reservations,</p>
			</div>
			<div class="wrap">
			  <h1>Uninstall Booking Calendar</h1>
			  <p><strong><a href="<?php echo wp_nonce_url('plugins.php?action=deactivate&amp;plugin=booking-calendar/booking_calendar.php', 'deactivate-plugin_booking-calendar/booking_calendar.php'); ?>">Click Here</a> To Finish the Uninstallation</strong></p>
			</div>
		  <?php
			return;
		}
		?>
		<div id="wpdevart_uninstal_container" class="wpdevart-list-container">
			<form method="post" action="admin.php?page=wpdevart-booking-uninstall" style="width:99%;">
			 <?php wp_nonce_field('uninstall_booking','uninstall_booking_data'); ?>
			    <div class="div-for-clear">
					<span class="admin_logo"></span>
					<h1>Uninstall Booking calendar</h1>
				</div>
				<p>Deactivating Booking calendar plugin does not remove any data that may have been created. To completely remove this plugin, you can uninstall it here.</p>
				<p style="color: red;"><strong>WARNING:</strong>Once uninstalled, this can't be undone. You should use a Database Backup plugin of WordPress to back up all the data first.</p>
				<p style="color: red"><strong>The following Database Tables will be deleted:</strong></p>
				<table class="widefat">
				  <thead>
					<tr>
					  <th>Database Tables</th>
					</tr>
				  </thead>
				  <tr>
					<td valign="top">
					  <ol>
						  <li><?php echo $wpdb->prefix; ?>wpdevart_calendars</li>
						  <li><?php echo $wpdb->prefix; ?>wpdevart_dates</li>
						  <li><?php echo $wpdb->prefix; ?>wpdevart_forms</li>
						  <li><?php echo $wpdb->prefix; ?>wpdevart_extras</li>
						  <li><?php echo $wpdb->prefix; ?>wpdevart_themes</li>
						  <li><?php echo $wpdb->prefix; ?>wpdevart_reservations</li>
					  </ol>
					</td>
				  </tr>
				</table>
				<p style="text-align: center;">
				  Do you really want to uninstall Booking Calendar :( ?
				</p>
				<p style="text-align: center;">
				  <input type="checkbox" id="check_yes" value="yes" />&nbsp;<label for="check_yes">Yes</label>
				</p>
				<p style="text-align: center;">
				  <input type="submit" value="UNINSTALL" class="button-primary" onclick="if (check_yes.checked) {	if (!confirm('You are About to Uninstall booking calendar.\nThis Action Is Not Reversible.')) {return false; } } else { return false; }" />
				</p>
			</form>
		</div>
  <?php    
	}
	
	
}