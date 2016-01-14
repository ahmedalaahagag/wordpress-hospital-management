<?php 

/// class for install databese

class wpdevart_bc_install_database{

	function __construct(){
		
	}
	public function install_databese(){
		global $wpdb;
		/* Create Part */
		$wpdevartbooking = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "wpdevart_calendars` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`user_id` int(11) NOT NULL,
			`title` varchar(128) NOT NULL,
			`hours_enabled` varchar(3) NOT NULL,
			`hours_interval_enabled` varchar(3) NOT NULL,
			`theme_id` int(11) NOT NULL,
			`form_id` int(11) NOT NULL,
			`extra_id` int(11) NOT NULL,
			PRIMARY KEY (`id`)
		  ) DEFAULT CHARSET=utf8;";
		$wpdb->query($wpdevartbooking);
		
		$wpdevartbooking_dates = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "wpdevart_dates` (
			`unique_id` varchar(32) NOT NULL,
			`calendar_id` int(11) NOT NULL,
			`day` varchar(16) NOT NULL,
			`data` text NOT NULL,
			PRIMARY KEY (`unique_id`)
		  ) DEFAULT CHARSET=utf8;";
		$wpdb->query($wpdevartbooking_dates);
		
		$wpdevartbooking_forms = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "wpdevart_forms` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`title` varchar(32) NOT NULL,
			`data` text NOT NULL,
			PRIMARY KEY (`id`)
		  ) DEFAULT CHARSET=utf8;";
		$wpdb->query($wpdevartbooking_forms);
		
		$wpdevartbooking_extras = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "wpdevart_extras` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`title` varchar(32) NOT NULL,
			`data` text NOT NULL,
			PRIMARY KEY (`id`)
		  ) DEFAULT CHARSET=utf8;";
		$wpdb->query($wpdevartbooking_extras);
		
		$wpdevartbooking_themes = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "wpdevart_themes` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`title` varchar(32) NOT NULL,
			`value` longtext NOT NULL,
			PRIMARY KEY (`id`)
		  ) DEFAULT CHARSET=utf8;";
		$wpdb->query($wpdevartbooking_themes);
		
		$wpdevartbooking_reservations = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "wpdevart_reservations` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`calendar_id` int(11) NOT NULL,
			`single_day` varchar(16) NOT NULL,
			`check_in` varchar(16) NOT NULL,
			`check_out` varchar(16) NOT NULL,
			`start_hour` varchar(16) NOT NULL,
			`end_hour` varchar(16) NOT NULL,
			`currency` varchar(32) NOT NULL,
			`count_item` int(10) NOT NULL,
			`price` float NOT NULL,
			`total_price` float NOT NULL,
			`extras` text NOT NULL,
			`extras_price` float NOT NULL,
			`form` text NOT NULL,
			`address_billing` text NOT NULL,
			`address_shipping` text NOT NULL,
			`email` varchar(128) NOT NULL,
			`status` varchar(16) NOT NULL,
			`payment_method` varchar(32) NOT NULL,
			`payment_status` varchar(32) NOT NULL,
			`date_created` varchar(64) NOT NULL,
			PRIMARY KEY (`id`)
		  ) DEFAULT CHARSET=utf8;";
		$wpdb->query($wpdevartbooking_reservations);
		
		/* Insert Part */
		$wpdevart_calendar = $wpdb->get_var("SELECT id FROM ".$wpdb->prefix."wpdevart_calendars");
		if(!$wpdevart_calendar) {
			$wpdb->query( "INSERT INTO ".$wpdb->prefix."wpdevart_calendars (`id`, `user_id`, `title`, `hours_enabled`, `hours_interval_enabled`, `theme_id`, `form_id`, `extra_id`) VALUES(1, 1, 'Calendar', '', '', 1, 1, 0)" );
		}
		$wpdevart_extra = $wpdb->get_var("SELECT id FROM ".$wpdb->prefix."wpdevart_extras");
		if(!$wpdevart_extra) {
			$wpdb->query( 'INSERT INTO '.$wpdb->prefix.'wpdevart_extras (`id`, `title`, `data`) VALUES(1, \'Extra\', \'{"extra_field1":{"name":"extra_field1","label":"Adults","required":"on","items":{"field_item1":{"name":"field_item1","label":"1","operation":"+","price_type":"price","price_percent":"0"},"field_item2":{"name":"field_item2","label":"2","operation":"+","price_type":"price","price_percent":"0"},"field_item3":{"name":"field_item3","label":"3","operation":"+","price_type":"price","price_percent":"0"},"field_item4":{"name":"field_item4","label":"4","operation":"+","price_type":"price","price_percent":"0"}}},"extra_field2":{"name":"extra_field2","label":"Children ","items":{"field_item1":{"name":"field_item1","label":"1","operation":"+","price_type":"price","price_percent":"0"},"field_item2":{"name":"field_item2","label":"2","operation":"+","price_type":"price","price_percent":"0"},"field_item3":{"name":"field_item3","label":"3","operation":"+","price_type":"price","price_percent":"0"},"field_item4":{"name":"field_item4","label":"4","operation":"+","price_type":"price","price_percent":"0"}}}}\')' );
		}
		$wpdevart_form = $wpdb->get_var("SELECT id FROM ".$wpdb->prefix."wpdevart_forms");
		if(!$wpdevart_form) {
			$wpdb->query( 'INSERT INTO '.$wpdb->prefix.'wpdevart_forms (`id`, `title`, `data`) VALUES(1, \'Form\', \'{"apply":"Apply","form_field1":{"type":"text","name":"form_field1","label":"First Name","required":"on"},"form_field2":{"type":"text","name":"form_field2","label":"Last Name"},"form_field3":{"type":"text","name":"form_field3","label":"Email","required":"on","isemail":"on"},"form_field4":{"type":"text","name":"form_field4","label":"Phone","required":"on"},"form_field5":{"type":"textarea","name":"form_field5","label":"Message","required":"on"}}\')' );
		}
		$wpdevart_theme = $wpdb->get_var("SELECT id FROM ".$wpdb->prefix."wpdevart_themes");
		if(!$wpdevart_theme) {
			$wpdb->query( 'INSERT INTO '.$wpdb->prefix.'wpdevart_themes (`id`, `title`, `value`) VALUES(1, \'Theme\', \'{"title":"Theme","apply":"Apply","date_format":"F j, Y","week_days":"0","day_start":"1","type_days_selection":"multiple_days","enable_checkinout":"on","enable_number_items":"on","terms_cond_link":"","enable_form_title":"on","enable_extras_title":"on","legend_enable":"on","legend_available_enable":"on","legend_available":"Available","legend_booked_enable":"on","legend_booked":"Booked","legend_unavailable_enable":"on","legend_unavailable":"Unavailable","action_after_submit":"stay_on_calendar","message_text":"Thanks for reservation","redirect_url":"","currency":"USD","calendar_max_width":"680","calendar_header_font_weight":"normal","calendar_header_font_style":"normal","calendar_header_padding":"10","next_prev_month_size":"15","current_month_size":"19","current_year_size":"19","week_days_font_weight":"normal","week_days_font_style":"normal","week_days_size":"13","day_number_font_weight":"normal","day_number_font_style":"normal","day_number_size":"13","days_min_height":"65","info_font_weight":"normal","info_font_style":"normal","info_size":"13","info_border_radius":"","form_title_weight":"normal","form_title_style":"italic","form_title_size":"15","form_labels_weight":"normal","form_labels_style":"italic","form_labels_size":"15","form_fields_weight":"normal","form_fields_style":"normal","form_fields_size":"15","form_submit_weight":"normal","form_style_style":"normal","calendar_header_bg":"#FFFFFF","next_prev_month":"#636363","current_month":"#636363","current_year":"#636363","week_days_bg":"#ECECEC","week_days_color":"#656565","calendar_bg":"#FFFFFF","calendar_border":"#ddd","day_number_bg":"#ECECEC","day_color":"#464646","available_day_number_bg":"#85B70B","available_day_color":"#FFFFFF","selected_day_number_bg":"#373740","selected_day_color":"#FFFFFF","unavailable_day_number_bg":"#464646","unavailable_day_color":"#ECECEC","booked_day_number_bg":"#FD7C93","booked_day_color":"#FFFFFF","info_icon_color":"#FFFFFF","info_bg":"#FFFFFF","info_color":"#4E4E4E","form_bg":"#FDFDFD","form_border":"#ddd","form_labels_color":"#636363","form_fields_color":"#636363","required_star_color":"#FD7C93","submit_button_bg":"#FD7C93","submit_button_color":"#FFFFFF","error_info_bg":"#FFFFFF","error_info_color":"#C11212","error_info_border":"#C11212","error_info_close_bg":"#C11212","error_info_close_color":"#FFFFFF","successfully_info_bg":"#FFFFFF","successfully_info_color":"#7FAD16","successfully_info_border":"#7FAD16","successfully_info_close_bg":"#7FAD16","successfully_info_close_color":"#FFFFFF","notify_admin_on_book":"on","notify_admin_on_book_to":"\\\"Booking calendar\\\" <info@info.info>","notify_admin_on_book_from":"[useremail]","notify_admin_on_book_subject":"You received a booking request.","notify_admin_on_book_content":"","notify_admin_on_approved":"on","notify_admin_on_approved_to":"\\\"Booking calendar\\\" <info@info.info>","notify_admin_on_approved_from":"[useremail]","notify_admin_on_approved_subject":"You received a booking request.","notify_admin_on_approved_content":"","notify_user_on_book":"on","notify_user_on_book_from":"\\\"Booking calendar\\\" <info@info.info>","notify_user_on_book_subject":"Your booking request has been sent.","user_on_book_content":"","notify_user_on_approved":"on","notify_user_on_approved_from":"\\\"Booking calendar\\\" <info@info.info>","notify_user_on_approved_subject":"Your booking request has been approved","notify_user_on_approved_content":"","notify_user_canceled":"on","notify_user_canceled_from":"\\\"Booking calendar\\\" <info@info.info>","notify_user_canceled_subject":"Your booking request has been canceled","notify_user_canceled_content":"","notify_user_deleted":"on","notify_user_deleted_from":"\\\"Booking calendar\\\" <info@info.info>","notify_user_deleted_subject":"Your booking request has been rejected","notify_user_deleted_content":"","for_check_in":"Check in","for_check_out":"Check out","for_item_count":"Item count","for_termscond":"I accept to agree to the Terms & Conditions.","for_reservation":"Reservation","for_select_days":"Please select the days from calendar.","for_price":"Price","for_total":"Total","for_submit_button":"Book Now","for_request_successfully_sent":"Your request has been successfully sent. Please wait for approval.","for_request_successfully_received":"Your request has been successfully received. We are waiting you!","for_error_single":"There are no services available for this day.","for_error_multi":"There are no services available for the period you selected.","task":"save","id":"0"}\')' );
		}
	}
}
