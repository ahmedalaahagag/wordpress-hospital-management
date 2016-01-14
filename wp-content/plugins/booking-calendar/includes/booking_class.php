<?php
class wpdevart_bc_BookingCalendar {
	
	private $theme_option;
	private $calendar_data;
	private $form_data;
	private $extra_field;
	private $id;
	private $selected;
	private $ajax;
	private $currency = "$";
	private $week_days = array(
		"Sunday",
		"Monday",
		"Tuesday",
		"Wednesday",
		"Thursday",
		"Friday",
		"Saturday"
	);
	private $abbr_week_days = array(
		"Sun",
		"Mon",
		"Tue",
		"Wed",
		"Thu",
		"Fri",
		"Sat"
	);
	private $short_week_days = array(
		"Su",
		"Mo",
		"Tu",
		"We",
		"Th",
		"Fr",
		"Sa"
	);
	public $jd, $year, $month, $day, $month_days_count, $month_start, $month_name, $prev_month, $next_month,$bookings = array();

	
	public function __construct($date = '', $id, $theme_option, $calendar_data, $form_option, $extra_field, $selected = array(),$ajax = false) {
        $this->theme_option = $theme_option;
        $this->calendar_data = $calendar_data;
        $this->form_data = $form_option;
        $this->extra_field = $extra_field;
        $this->id = $id;
        $this->ajax = $ajax;
        $this->selected = $selected;
        $currency_list = wpdevart_bc_get_currency();
		if(isset($currency_list[esc_html($this->theme_option['currency'])])) {
			$this->currency = $currency_list[esc_html($this->theme_option['currency'])]['simbol'];
		}
		if(isset($this->theme_option['time_format']))
			$this->theme_option['time_format'] .= ( isset( $theme_option->time_type )? ' '.$theme_option->time_type : '' );
		if(isset($theme_option->time_type) && $theme_option->time_type !='') {
			$this->theme_option['time_format'] = str_replace(array('H','h'), 'g', $this->theme_option["time_format"]);
		}
		if ($date == '' && !isset( $_REQUEST['date'] )) {
			$date = date( 'Y-m-d' );
		}
		if (isset( $_REQUEST['date'] ) && $_REQUEST['date'] != '') {
			$date = $_REQUEST['date'];
		}
		$date      = date('Y-m-d', strtotime( $date ));
		$date_array = explode( '-', $date );
		$year      = $date_array[0];
		$month     = $date_array[1];
		$day       = $date_array[2];
		if (isset( $_REQUEST['year'] ) && $_REQUEST['year'] != '') {
			$year = $_REQUEST['year'];
		}
		if (isset( $_REQUEST['month'] ) && $_REQUEST['month'] != '') {
			$month = $_REQUEST['month'];
		}
		if (isset( $_REQUEST['day'] ) && $_REQUEST['day'] != '') {
			$day = $_REQUEST['day'];
		}
		$this->month = (int) $month;
		$this->year  = (int) $year;
		$this->day   = (int) $day;
		$this->month_days_count = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
		$this->jd = cal_to_jd(CAL_GREGORIAN, $this->month, date( 1 ), $this->year);
		$this->month_start = jddayofweek( $this->jd);
		$this->month_name = __( jdmonthname($this->jd, 1), 'booking-calendar' );
	}

	public function booking_calendar($reservation = "") {
		$prev      = $this->calculate_date( $this->year . '-' . $this->month, '-1', 'month' );
		$prev_date_info = $prev['year'] . '-' . $prev['month'];
		$prev_date = '';
		$this->prev_month = $this->get_month_name($prev['year'] . '-' . $prev['month'],0);
		$prev_html = '<span><</span><span class="wpda-month-name"> ' . __($this->prev_month, 'booking-calendar') . ' ' . $prev_date . '</span>';
		
		$next      = $this->calculate_date( $this->year . '-' . $this->month . '-1', '+ 1', 'month' );
		$next_date = '';
		$next_date_info = $next['year'] . '-' . $next['month'] . '-' . $next['day'];
		$this->next_month = $this->get_month_name($next['year'] . '-' . $next['month'],0);
		$next_html = '<span class="wpda-month-name">' . $next_date . ' ' . __( $this->next_month, 'booking-calendar' ) . ' </span><span>></span>';
		
		$booking_calendar = '';
		$booking_calendar .= '<div class="wpda-booking-calendar-head">';
		// previous month link
		$booking_calendar .= '<div class="wpda-previous"><a href="?date=' . $prev_date_info . '" rel="nofollow, noindex">' . $prev_html . '</a></div>'; 
		//current date info
		$booking_calendar .= '<div class="current-date-info"><span class="wpda-current-year">' . $this->year . '</span>&nbsp;<span class="wpda-current-month">' . __( $this->month_name, 'booking-calendar' ) . '</span></div>';
        // next month link
		$booking_calendar .= '<div class="wpda-next"><a href="?date=' . $next_date_info . '" rel="nofollow, noindex">' . $next_html . '</a></div>';
		$booking_calendar .= '</div>';
        // booking calendar container
		if( $reservation == "") {
			$booking_calendar .= '<div class="wpdevart-calendar-container div-for-clear" data-id="' . $this->id . '">';
		} else {
			$booking_calendar .= '<table class="wpdevart-calendar-container" data-id="' . $this->id . '">';
		}
		if ($this->theme_option['week_days'] == 0) {
			$week_days = $this->week_days;
		} else if ($this->theme_option['week_days'] == 1) {
			$week_days = $this->abbr_week_days;
		} else {
			$week_days = $this->short_week_days;
		}
		for ($i = 0; $i < count( $week_days ); $i ++) {
			$di      = ( $i + $this->theme_option["day_start"] ) % 7;
			$week_day = $week_days[ $di ];
			if ($i == 0) {
				$cell_class = 'week-day-name week-start';
			} else {
				$cell_class = 'week-day-name';
			}
			$booking_calendar .= $this->booking_calendar_cell( __( $week_day, 'booking-calendar' ), $cell_class );
		}
		
        /* previous month cells */
		$empty_cells = 0;
		$count_in_row = 7;

        /* week start days */
		$week_start_days = $this->month_start - $this->theme_option['day_start'];
		if ($week_start_days < 0) {
			$week_start_days = $week_start_days + $count_in_row;
		}
		$r = 0;
		for ($i = $week_start_days; $i > 0; $i--) {
			if ( $i == 0 ) {
				$cell_class = 'past-month-day week-start';
			}
			else {
				$cell_class = 'past-month-day';
			}
			$day_count = ($i==1) ? "day" : "days";
			$day = date("j",strtotime("".($this->year . '-' . ($this->month) . '-1')." -".$i." ".$day_count.""));
			if($this->month == 1) {
				$month = 13;
			} else {
				$month = $this->month;
			}
			if($month == 13) {
				$date = ($this->year - 1) . '-' . ($month-1) . '-' . $day;
			} else {
				$date = $this->year . '-' . ($month-1) . '-' . $day;
			}
			if($r == 0  && $reservation == "reservation"){
				$booking_calendar .= "<tr>";
			}
			if( $reservation == "reservation") {
				$booking_calendar .= $this->reserv_calendar_cell(__( $this->prev_month . " " . $day, 'booking-calendar' ), $cell_class,$date);
			} else {
				$booking_calendar .= $this->booking_calendar_cell(__( $this->prev_month . " " . $day, 'booking-calendar' ), $cell_class,$date);
			}
			
			if(($r%7 == 0 && $r != 0) && $reservation == "reservation"){
				$booking_calendar .= "</tr><tr>";
			}
			$r++;
			$empty_cells ++;
		}

		/* days */
		$row_count    = $empty_cells;
		$weeknumadjust = $count_in_row - ($this->month_start - $this->theme_option['day_start']);

		for ($j = 1; $j <= $this->month_days_count; $j ++) {

			$date = $this->year . '-' . $this->month . '-' . $j;
			$row_count ++;
			if( $reservation == "reservation") {
				$booking_calendar .= $this->reserv_calendar_cell($j, 'current-month-day', $date);
			} else {
				$booking_calendar .= $this->booking_calendar_cell($j, 'current-month-day', $date);
			}
			if((($r + $j)%7 == 0 && $r != 0) && $reservation == "reservation"){
				$booking_calendar .= "</tr><tr>";
			}
			if ($row_count % $count_in_row == 0) {
				$row_count = 0;
			}
		}

		/* next month cells */
		$cells_left_count = $count_in_row - $row_count;
		if ($cells_left_count != $count_in_row) {
			for ($k = 1; $k <= $cells_left_count; $k ++) {
				$day_count = ($k==1) ? "day" : "days";
				$day = date("j",strtotime("".($this->year . '-' . ($this->month) . '-'.$this->month_days_count.'')." +".$k." ".$day_count.""));
				if($this->month == 12) {
					$month = 0;
				} else {
					$month = $this->month;
				}
				if($month == 0) {
					$date = ($this->year + 1) . '-' . ($month+1) . '-' . $day;
				} else {
					$date = $this->year . '-' . ($month+1) . '-' . $day;
				}
				
				if( $reservation == "reservation") {
					$booking_calendar .= $this->reserv_calendar_cell($this->next_month . " " . $k, 'next-month-day',$date);
				} else {
					$booking_calendar .= $this->booking_calendar_cell($this->next_month . " " . $k, 'next-month-day',$date);
				}
				if(($k%7 == 0) && $reservation == "reservation" && $k != $count_in_row){
					$booking_calendar .= "</tr><tr>";
				} elseif(($k%7 == 0) && $reservation == "reservation" && $k == $count_in_row) {
					$booking_calendar .= "</tr>";
				}
				$empty_cells ++;
			}
		}
		if( $reservation == "") {
			$booking_calendar .= '</div>';
		} else {
			$booking_calendar .= '</table>';
		}
		return $booking_calendar;
	}


	private function booking_calendar_cell( $day, $class, $date = '' ) {
		$class_list = '';
		$data_info = '';
		$day_info = '';
		if($date != "") {
			$date = date("Y-m-d",strtotime($date));
		}
		if (strpos( $class, 'week-day-name') === false ) {
			$class_list .= ' wpdevart-day';
		}
		$data_info = 'data-date="' . $date . '"';
		foreach($this->calendar_data as $day_data) {
			if($day_data['day'] == $date) {
				$day_info = json_decode($day_data['data'], true);
			}
		}
		$week_day = date('w', strtotime( $date ));

		if (isset($day_info["status"]) && $day_info["status"] == "available") {
			$data_info .= ' data-available="' . $day_info["available"] . '"';
		}else if(isset($this->theme_option['unavailable_week_days']) && in_array($week_day,$this->theme_option['unavailable_week_days'])){
			$data_info .= ' data-available="0"';
		}
		if(isset($day_info['status']) && $day_info['status'] != ''){
			if(isset($this->theme_option['unavailable_week_days']) && in_array($week_day,$this->theme_option['unavailable_week_days'])){
				$day_info['status']='unavailable';
			}
			$class_list .= ' wpdevart-' . $day_info['status'];
		}
		
		if ($day != '') {
			$date_diff = $this->get_date_diff($date,date( 'Y-m-d' ));
			if ($date_diff<0 && ($date != '' || strpos( $class, 'past-month-day') !== false )) {
				$class_list .= ' past-day';
			}
			if ($date == date( 'Y-m-d' )) {
				$class_list .= ' current-day';
			}
			if (in_array($this->get_day( $date ), array('Saturday', 'Sunday'))) {
				$class_list .= ' weekend';
			}
			if ($this->get_day( $date, 0 ) == $this->theme_option['day_start']) {
				$class_list .= ' week-start';
			}
			if (isset($this->theme_option['unavailable_week_days']) && in_array($week_day,$this->theme_option['unavailable_week_days'])) {
				$class_list .= ' wpdevart-unavailable'; // day with bookings
			} else if (strpos( $class, 'week-day-name' ) === false) {
				$class_list .= ' available-day'; // no bookings
			}
			if (isset($day_info["hours_enabled"]) && $day_info["hours_enabled"] == "on") {
				$class_list .= ' hour-enable'; // hour enable
			}
			if (isset($this->selected["date"]) && $this->selected["date"] == $date && $this->selected["date"] != "") {
				$class_list .= ' selected';
			}
			
			$bookings = '<div ' . $data_info . ' class="' . $class . $class_list . '">';
			$bookings.= '<div class="wpda-day-header div-for-clear"><div class="wpda-day-number">' . $day . '</div>';
			if (isset($day_info["info_admin"]) && $day_info["info_admin"] != "" && is_admin() && !$this->ajax) {
				$bookings .= '<div class="day-user-info-container">a<div class="day-user-info">' . esc_html($day_info["info_admin"]) . '</div></div>';
			}
			if (isset($day_info["info_users"]) && $day_info["info_users"] != "") {
				$bookings .= '<div class="day-user-info-container">i<div class="day-user-info">' . esc_html($day_info["info_users"]) . '</div></div>';
			}
			
			$bookings.= '</div>';
			if (isset($day_info["status"]) && $day_info["status"] == "available") {
				$bookings .= '<div class="day-availability">' . $day_info["available"] . ' <span class="day-av">available</span></div>';
			} elseif (isset($day_info["status"]) && $day_info["status"] == "booked") {
				$bookings .= '<div class="day-availability">' . __( 'Booked', 'booking-calendar' ) . '</div>';
			} elseif ((isset($day_info["status"]) && $day_info["status"] == "unavailable") || (isset($this->theme_option['unavailable_week_days']) && in_array($week_day,$this->theme_option['unavailable_week_days']))) {
				$bookings .= '<div class="day-availability">' . __( 'Unavailable', 'booking-calendar' ) . '</div>';
			}
			if (isset($day_info["price"]) && $day_info["price"] != "" && !(isset($this->theme_option['unavailable_week_days']) && in_array($week_day,$this->theme_option['unavailable_week_days']))) {
				$bookings .= '<div class="day-price"><span class="new-price" data-price="' . $day_info["price"] . '" data-currency="' . $this->currency . '">' .  esc_html($day_info["price"]) . $this->currency . '</span>';
				if (isset($day_info["marked_price"]) && $day_info["marked_price"] != "") {
					$bookings .= '<span class="old-price">' . esc_html($day_info["marked_price"]) . $this->currency . '</span>';
				}
				$bookings .= '</div>';
			}
			$bookings .= '</div>';

			return $bookings;
		}
    }


	private function reserv_calendar_cell( $day, $class, $date = '' ) {
		$date = date("Y-m-d",strtotime($date));	
		$class = "";		
		$link_content = "";		
		$reservations = $this->get_reservation_row_calid($this->id,$date);
		if ($day != '') {
			$bookings = '<td class="' . $class . '">';
			$bookings.= '<div class="wpda-day-header div-for-clear"><div class="wpda-day-number">' . $day . '</div></div>';
				if($reservations) {
					foreach($reservations as $reservation) {
						$form_data = $this->get_form_data($reservation["form"]);
						$extras_data = $this->get_extra_data($reservation);
						if($reservation["check_in"] == $date) {
							$class = "start";
						} elseif($reservation["check_out"] == $date) {
							$class = "end";
						}
						$bookings .= '<div class="reservation-month reservation-month-'.$reservation["id"].' '.$reservation["status"].' '.$class.'">';
						if($reservation["check_in"] == $date && $reservation["email"] == "") {
							$link_content = $reservation["id"];
						} elseif($reservation["check_in"] == $date && $reservation["email"] != "") {
							$link_content = $reservation["email"];
						}elseif($reservation["check_in"] != $date) {
							$link_content = "";
						}
						$content = '<div class="month-view-content"><div class="reserv-info-container">
									<h5>Details</h5>
									<span class="form_info"><span class="form_label">Item Count</span> <span class="form_value">'.$reservation["count_item"].'</span></span>
									<span class="form_info"><span class="form_label">Price</span> <span class="form_value">'.$reservation["price"].$reservation["currency"].'</span></span>
									<span class="form_info"><span class="form_label">Total Price</span> <span class="form_value">'.$reservation["total_price"].$reservation["currency"].'</span></span>
								</div><div class="reserv-info-items div-for-clear">';
						if(count($form_data)) {
							$content .= "<div class='reserv-info-container'>";
							$content .= "<h5>Contact Information</h5>";
							foreach($form_data as $form_fild_data) {
								$content .= "<span class='form_info'><span class='form_label'>". $form_fild_data["label"] ."</span> <span class='form_value'>". $form_fild_data["value"] ."</span></span>";
							}
							$content .= "</div>";
						}
						if(count($extras_data)) {
							$content .= "<div class='reserv-info-container'>";
							$content .= "<h5>Extra Information</h5>";
							foreach($extras_data as $extra_data) {
								$content .= "<h6>".$extra_data["group_label"]."</h6>";
								$content .= "<span class='form_info'><span class='form_label'>". $extra_data["label"] ."</span>"; 
								$content .= "<span class='form_value'>";
								if($extra_data["price_type"] == "percent") {
									$content .= "<span class='price-percent'>".$extra_data["operation"].$extra_data["price_percent"]."%</span>";
								}
								if(isset($extra_data["price"])) {
									$content .= "<span class='price'>".$extra_data["operation"] . $extra_data["price"] .$reservation["currency"]."</span>";
								}
								$content .= "</span></span>";
							}
							$content .= "<h6>Price change</h6>";
							$content .= "<span class='form_info'><span class='form_label'></span><span class='form_value'>+".$reservation["extras_price"].$reservation["currency"]."</span>"; 
							$content .= "</div>";
						}		
						$content .= '</div></div>';
						$bookings .= '<a href="" onclick="wpdevart_set_value(\'cur_id\',\''.$reservation["id"].'\');wpdevart_set_value(\'task\',\'display_reservations\'); wpdevart_form_submit(event, \'reservations_form\')" class="month-view-link">'.$link_content.'</a>';
						$bookings .= $content.'</div>';
					}
				}
			$bookings .= '</td>';
			return $bookings;
		}
    }

	
	public function booking_form() {
		
		$input_atribute = '';
		$form_html = '';		
		$form_html .= '<div class="wpdevart-booking-form-container" id="wpdevart_booking_form_'.$this->id.'">';
		if (!isset($this->theme_option["auto_fill"])) {
			$input_atribute = "autocomplete='off'";
		}
		if (isset($this->theme_option['legend_enable']) && $this->theme_option['legend_enable'] == "on") {
			$form_html .= '<div class="wpdevart-booking-legends div-for-clear">';
				if (isset($this->theme_option['legend_available_enable']) && $this->theme_option['legend_available_enable'] == "on") {
					$form_html .= '<div class="wpdevart-legends-available"><div class="legend-text"><span class="legend-div"></span>-'.esc_html($this->theme_option['legend_available']).'</div>';
					$form_html .= '</div>';
				}
				if (isset($this->theme_option['legend_booked_enable']) && $this->theme_option['legend_booked_enable'] == "on") {
					$form_html .= '<div class="wpdevart-legends-pending"><div class="legend-text"><span class="legend-div"></span>-'.esc_html($this->theme_option['legend_booked']).'</div>';
					$form_html .= '</div>';
				}
				if (isset($this->theme_option['legend_unavailable_enable']) && $this->theme_option['legend_unavailable_enable'] == "on") {
					$form_html .= '<div class="wpdevart-legends-unavailable"><div class="legend-text"><span class="legend-div"></span>-'.esc_html($this->theme_option['legend_unavailable']).'</div>';
					$form_html .= '</div>';
				}
			$form_html .= '</div>';
		}	
		$form_html .= '<div class="wpdevart-booking-form"><form method="post" class="div-for-clear"><div class="wpdevart-check-section">';
		if (isset($this->theme_option["enable_checkinout"]) && $this->theme_option["enable_checkinout"] == "on" && $this->theme_option["type_days_selection"] == "multiple_days") {
			$form_html .= '<div class="wpdevart-fild-item-container ">
				  '.$this->form_field_text(array('name'=>'form_checkin'.$this->id,'label'=>(isset($this->theme_option["for_check_in"])) ? sprintf(esc_html__("%s",'booking-calendar'),$this->theme_option["for_check_in"]) : __("Check in",'booking-calendar'), 'readonly' => 'true' )).'</div>
				  <div class="wpdevart-fild-item-container ">'.$this->form_field_text(array('name'=>'form_checkout'.$this->id,'label'=>(isset($this->theme_option["for_check_out"])) ? sprintf(esc_html__("%s",'booking-calendar'),$this->theme_option["for_check_out"]) : __("Check out",'booking-calendar'), 'readonly' => 'true' )).'</div>';
		} elseif (!isset($this->theme_option["enable_checkinout"]) && $this->theme_option["type_days_selection"] == "multiple_days") {
			$form_html .= '<input type="hidden" id="wpdevart_form_checkin'.$this->id.'" name="wpdevart_form_checkin'.$this->id.'"><input type="hidden" id="wpdevart_form_checkout'.$this->id.'" name="wpdevart_form_checkout'.$this->id.'" >';
		}  elseif ($this->theme_option["type_days_selection"] == "single_day") {
			$form_html .= '<input type="hidden" id="wpdevart_single_day'.$this->id.'" name="wpdevart_single_day'.$this->id.'">';
		}		  		  
		if (isset($this->theme_option["enable_number_items"]) && $this->theme_option["enable_number_items"] == "on") {
			$form_html .= $this->form_field_select(array('options'=>'','name'=>'count_item'.$this->id,'label'=>(isset($this->theme_option["for_item_count"])) ? sprintf(esc_html__("%s",'booking-calendar'),$this->theme_option["for_item_count"]) : __("Item count",'booking-calendar'),"onchange"=>"change_count(this)"));
		}
		if(isset($this->extra_field)) {
			$extra_fields = json_decode( $this->extra_field->data, true );
			$extra_title = $this->extra_field->title;
			$form_html .= '<div class="wpdevart-extras">';
			if (isset($this->theme_option["enable_extras_title"]) && $this->theme_option["enable_extras_title"] == "on") {
				$form_html .= '<h4 class="form_title">'.esc_html($extra_title).'</h4>';
			}
			foreach($extra_fields as $extra_field) {
				$form_html .= $this->extra_field($extra_field);
			}	
			$form_html .= '</div>';	
		}
		$form_html .= '</div>';
		/*FORM SECTION*/
		if(isset($this->form_data)) {
			$form_data = json_decode( $this->form_data->data, true );
			$form_title = $this->form_data->title;
			$form_html .= '<div class="wpdevart-form-section"><div class="wpdevart-reserv-info"><h4 class="form_title">'.((isset($this->theme_option["for_reservation"])) ? sprintf(esc_html__("%s",'booking-calendar'),$this->theme_option["for_reservation"]) : __("Reservation",'booking-calendar')).'</h4>';
			$form_html .= '<div id="check-info-'.$this->id.'" class="check-info ">'.((isset($this->theme_option["for_select_days"])) ? sprintf(esc_html__("%s",'booking-calendar'),$this->theme_option["for_select_days"]) : __("Please select the days from calendar.",'booking-calendar')).'</div>';
			$form_html .= '</div>';
			if (isset($this->theme_option["enable_form_title"]) && $this->theme_option["enable_form_title"] == "on") {
				$form_html .= '<h4 class="form_title">'.esc_html($form_title).'</h4>';
			}
			foreach($form_data as $form_field) {
				if(isset($form_field['type'])) {
					$func_name = "form_field_" . $form_field['type'];
					if(method_exists($this,$func_name)) {
						$form_html .= $this->$func_name($form_field,$input_atribute);
					}
				}
			}
			if (isset($this->theme_option["enable_terms_cond"]) && $this->theme_option["enable_terms_cond"] == "on") {		  
				$form_html .= $this->form_field_checkbox(array('required'=>'on','name'=>'terms_cond'.$this->id,'label'=>((isset($this->theme_option["for_termscond"])) ? sprintf(esc_html__("%s",'booking-calendar'),$this->theme_option["for_termscond"]) : __("I accept to agree to the Terms & Conditions.",'booking-calendar'))),"",$this->theme_option["terms_cond_link"]);
			}
			$form_html .= '<button type="submit" class="wpdevart-submit"  id="wpdevart-submit'.$this->id.'" name="wpdevart-submit'.$this->id.'">'.((isset($this->theme_option["for_submit_button"])) ? sprintf(esc_html__("%s",'booking-calendar'),$this->theme_option["for_submit_button"]) : __("Book Now",'booking-calendar')).'</button></div>';
		}
		$form_html .= '<input type="hidden" class="wpdevart_extra_price_value" id="wpdevart_extra_price_value'.$this->id.'" name="wpdevart_extra_price_value'.$this->id.'" value="">';
		$form_html .= '<input type="hidden" class="wpdevart_total_price_value" id="wpdevart_total_price_value'.$this->id.'" name="wpdevart_total_price_value'.$this->id.'" value="">';
		$form_html .= '<input type="hidden" class="wpdevart_price_value" id="wpdevart_price_value'.$this->id.'" name="wpdevart_price_value'.$this->id.'" value="">';
		$form_html .= '<input type="hidden" name="id" value="'.$this->id.'">';
		$form_html .= '<input type="hidden" name="task" value="save">';
		$form_html .= '</form></div></div>';
		return $form_html;
	}
	
	private function form_field_text($form_field,$input_atribute=''){
		$input_class = array();
		$field_html = '';
		$readonly = "";
		$field_html .= '<div class="wpdevart-fild-item-container">
							<label for="wpdevart_'.$form_field['name'].'">'.esc_html($form_field['label']).'</label>';
		if(isset($form_field['required'])) {
			$field_html .= '<span class="wpdevart-required">*</span>';
			$input_class[] = 'wpdevart-required';
		}		
		if(isset($form_field['isemail']) && $form_field['isemail'] == "on" ) {
			$input_class[] = 'wpdevart-email';
		}			
		if(isset($form_field['class']) && $form_field['class'] != "" ) {
			$input_class[] = $form_field['class'];
		}		
		if(isset($form_field['readonly']) && $form_field['readonly'] == "true" ) {
			$readonly = "readonly";
		}	
		if(count($input_class)) {
			$input_class = implode(" ",$input_class);
			$class = "class='".$input_class."'";
		} else {
			$class = "";
		}
		$field_html .= '<div class="wpdevart-elem-container div-for-clear" id="wpdevart_wrap_'.$form_field['name'].'">
				  <input type="text" id="wpdevart_'.$form_field['name'].'" name="wpdevart_'.$form_field['name'].'" '.$input_atribute.' '.$class.' ' .$readonly. '>
			    </div>
		     </div>';
		return $field_html;
	}
	
	private function form_field_textarea($form_field,$input_atribute=''){
		$input_class = '';
		$field_html = '';
		$field_html .= '<div class="wpdevart-fild-item-container">
							<label for="wpdevart_'.$form_field['name'].'">'.esc_html($form_field['label']).'</label>';
		if(isset($form_field['required'])) {
			$field_html .= '<span class="wpdevart-required">*</span>';
			$input_class = 'class="wpdevart-required"';
		}		
		$field_html .= '<div class="wpdevart-elem-container div-for-clear" id="wpdevart_wrap_'.$form_field['name'].'">
				  <textarea id="wpdevart_'.$form_field['name'].'" name="wpdevart_'.$form_field['name'].'" '.$input_class.'></textarea>
			    </div>
		     </div>';
		return $field_html;
	}
	
	private function form_field_select($form_field,$input_atribute=''){
		$select_options = explode(PHP_EOL, $form_field['options']);
		$input_class = '';
		$field_html = '';
		if(count($select_options)){
			$field_html .= '<div class="wpdevart-fild-item-container">
								<label for="wpdevart_'.$form_field['name'].'">'.esc_html($form_field['label']).'</label>';
			if(isset($form_field['required'])) {
				$field_html .= '<span class="wpdevart-required">*</span>';
				$input_class = 'class="wpdevart-required"';
			}		
			$field_html .= '<div class="wpdevart-elem-container div-for-clear" id="wpdevart_wrap_'.$form_field['name'].'"><select id="wpdevart_'.$form_field['name'].'" name="wpdevart_'.$form_field['name'].'"';
			if(isset($form_field['multi'])) {
				$field_html .= 'multiple="multiple"';
			}
			if(isset($form_field['onchange'])) {
				$field_html .= 'onchange="'.$form_field['onchange'].'"';
			}
			$field_html .= ' '.$input_class.'>';
			foreach($select_options as $select_option) {
				if(trim($select_option) != '') {
					$field_html .= '<option value="'.esc_html($select_option).'">'.esc_html($select_option).'</option>';
				}
			}		  
			$field_html .= '</select>
					</div>
				 </div>';
		}
		else {
			$field_html .= 'No options';
		}		
		return $field_html;
	}
	
	private function extra_field($extra_field){
		$select_options = $extra_field['items'];
		$input_class = '';
		$field_html = '';
		if(count($select_options)){
			$field_html .= '<div class="wpdevart-fild-item-container">
								<label for="wpdevart_'.$extra_field['name'].'">'.esc_html($extra_field['label']).'</label>';
			if(isset($extra_field['required'])) {
				$field_html .= '<span class="wpdevart-required">*</span>';
				$input_class = "wpdevart-required";
			}		
			$field_html .= '<div class="wpdevart-elem-container div-for-clear" id="wpdevart_wrap_'.$extra_field['name'].'"><select onchange="change_extra(this)" class="wpdevart_extras '.$input_class.'" id="wpdevart_'.$extra_field['name'].'" name="wpdevart_'.$extra_field['name'].'">';
			foreach($select_options as $select_option) {
				$field_html .= '<option value="'.$select_option["name"].'" data-operation="'.$select_option["operation"].'" data-type="'.$select_option["price_type"].'" data-price="'.$select_option["price_percent"].'" data-label="'.$select_option["label"].'">'.$select_option["label"].' '.(($select_option["price_percent"])? '('.$select_option["operation"].$select_option["price_percent"].(($select_option["price_type"] == "price")? $this->currency : "%").')' : '').'</option>';
			}		  
			$field_html .= '</select>
					</div>
				 </div>';
		}
		else {
			$field_html .= __('No options','booking-calendar');
		}		
		return $field_html;
	}
	
	private function form_field_checkbox($form_field,$input_atribute='',$link=''){
		$input_class = '';
		$field_html = '';
		$field_html .= '<div class="wpdevart-fild-item-container">';
		if($link != "") {
			$field_html .= '<label for="wpdevart_'.$form_field['name'].'"><a href="'.esc_url($link).'" target="_blank">'.$form_field['label'].'</a></label>';
		} else {
			$field_html .= '<label for="wpdevart_'.$form_field['name'].'">'.esc_html($form_field['label']);
		}
		if(isset($form_field['required'])) {
			$field_html .= '<span class="wpdevart-required">*</span>';
			$input_class = 'class="wpdevart-required"';
		}		
		$field_html .= '</label>';
		$field_html .= '<div class="wpdevart-elem-container div-for-clear" id="wpdevart_wrap_'.$form_field['name'].'">
				  <input type="checkbox" id="wpdevart_'.$form_field['name'].'" name="wpdevart_'.$form_field['name'].'" '.$input_class.'>
			    </div>
		     </div>';
		return $field_html;
	}
	
			
	private function get_day($date, $type = 1) {
		$date      = date('Y n j', strtotime( $date ));
		$date_info = explode(' ', $date);
		$jd        = cal_to_jd( CAL_GREGORIAN, $date_info[1], $date_info[2], $date_info[0] );

		return jddayofweek( $jd, $type );
	}
	
	private function get_date_diff($date1, $date2) {
		$start = strtotime($date1);
		$end = strtotime($date2);
		$datediff = $start - $end;
		return floor($datediff/(60*60*24));
	}

	private function search_in_array($needle, $haystack) {
		$array_iterator = new RecursiveArrayIterator( $haystack );
		$iterator       = new RecursiveIteratorIterator( $array_iterator );
		while ($iterator->valid()) {
			if (( $iterator->current() == $needle )) {
				return $array_iterator->key();
			}
			$iterator->next();
		}
		return false;
	}
	
	
	private function calculate_date( $start_date, $action, $type ) {
		$date    = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $start_date ) ) . " " . $action . " " . $type ));
		$date    = explode('-', $date);
		$new_date = array(
			'year'  => $date[0],
			'month' => $date[1],
			'day'   => $date[2]
		);
		return $new_date;
	}

	private function get_month_name( $date, $type = 1 ) {
		$date       = date('Y n j', strtotime( $date ));
		$date_info = explode(' ', $date);
		$jd         = cal_to_jd(CAL_GREGORIAN, $date_info[1], $date_info[2], $date_info[0]);
		return __(jdmonthname( $jd, $type ));
	}
	
	
	public function save_reserv($data){
		global $wpdb;
		$save = false;
		$emails = "";
		$email_array = array();
		if(isset($this->theme_option['enable_instant_approval']) && $this->theme_option['enable_instant_approval'] == "on") {
			$status = "approved";
		} else {
			$status = "pending";
		}
		$form = array();
		$extras = array();
		$extra_data = array();
		foreach($data as $key=>$item) {
			if(strrpos($key,"form_field") !== false) {
				$form[$key] = esc_html($item);		
			}
			if(strrpos($key,"extra_field") !== false) {
				$extras[$key] = esc_html($item);		
			}
		}
		
		$form_datas = json_decode($this->form_data->data,true);
		foreach($form_datas as $key => $form_data) {
			if(isset($form_data["isemail"]) && $form_data["isemail"]) {
				if(isset($form["wpdevart_".$key]) && $form["wpdevart_".$key] != "") {
					$email_array[] = $form["wpdevart_".$key];
				}
			}
		}
        if(count($email_array)) {
			$emails = implode(",",$email_array);
		}
		if(isset($this->extra_field)) {
			$extra_fields = json_decode( $this->extra_field->data, true );
			foreach($extras as $key => $extra) {
				$ex_key = str_replace("wpdevart_", "", $key);
				if(isset($extra_fields[$ex_key]['items'][$extra])) {
					$extra_data["".$ex_key.""] = $extra_fields[$ex_key]['items'][$extra];
				}
			}
		}
		$form = json_encode($form);
		$extra_data = json_encode($extra_data);
		$currency = (isset($this->currency) ? $this->currency : '');
		$check_in = (isset($data['wpdevart_form_checkin'.$this->id]) ? esc_html(stripslashes( $data['wpdevart_form_checkin'.$this->id])) : '');
		$check_out = (isset($data['wpdevart_form_checkout'.$this->id]) ? esc_html(stripslashes( $data['wpdevart_form_checkout'.$this->id])) : '');
		
		$date_count = abs($this->get_date_diff($check_in,$check_out));
		
		$single_day = (isset($data['wpdevart_single_day'.$this->id]) ? esc_html(stripslashes( $data['wpdevart_single_day'.$this->id])) : '');
		$start_hour = (isset($data['wpdevart_start_hour'.$this->id]) ? esc_html(stripslashes( $data['wpdevart_start_hour'.$this->id])) : '');
		$end_hour = (isset($data['wpdevart_end_hour'.$this->id]) ? esc_html(stripslashes( $data['end_hour'.$this->id])) : '');
		$count_item = (isset($data['wpdevart_count_item'.$this->id]) ? esc_html(stripslashes( $data['wpdevart_count_item'.$this->id])) : '');
		$total_price = (isset($data['wpdevart_total_price_value'.$this->id]) ? esc_html(stripslashes( $data['wpdevart_total_price_value'.$this->id])) : '');
		$price = (isset($data['wpdevart_price_value'.$this->id]) ? esc_html(stripslashes( $data['wpdevart_price_value'.$this->id])) : '');
		$extras_price = (isset($data['wpdevart_extra_price_value'.$this->id]) ? esc_html(stripslashes( $data['wpdevart_extra_price_value'.$this->id])) : '');
				
		$save_in_db = $wpdb->insert($wpdb->prefix . 'wpdevart_reservations', array(
			'calendar_id' => $this->id,                       
			'single_day' => $single_day,                       
			'check_in' => $check_in,         
			'check_out' => $check_out,         
			'start_hour' => $start_hour,         
			'end_hour' => $end_hour,         
			'currency' => $currency,         
			'count_item' => $count_item,         
			'price' => $price,         
			'total_price' => $total_price,         
			'extras' => $extra_data,         
			'extras_price' => $extras_price,         
			'form' => $form,         
			'address_billing' => '',         
			'address_shipping' => '',         
			'email' => $emails,         
			'status' => $status,         
			'payment_method' => '',         
			'payment_status' => '',         
			'date_created' => date('Y-m-d H:i',time())        
		  ), array(
			'%d',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%d',
			'%d',
			'%d',
			'%s',
			'%d',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s'
		  ));
		 if($save_in_db) {
			$save = true;
			$id = $wpdb->get_var('SELECT MAX(id) FROM ' . $wpdb->prefix . 'wpdevart_reservations');
			if($status == "approved") {
				$this->change_date_avail_count($id,true);
			}
			$this->send_mail($emails,$form,$extra_data,$count_item,$price,$currency,$total_price,$extras_price,$check_in,$check_out,$single_day);
		 } 
		return $save;	
	}
	private function send_mail($emails,$form_data,$extras_data,$count_item,$price,$currency,$total_price,$extras_price,$check_in,$check_out,$single_day){
		$admin_email_types = array();
		$user_email_types = array();
		$form_data = $this->get_form_data($form_data);
        $extras_data = $this->get_extra_data($extras_data,$price);
		if($check_in) {
			$check_in = date($this->theme_option["date_format"], strtotime($check_in));
			$check_out = date($this->theme_option["date_format"], strtotime($check_out));
			$day_count = abs($this->get_date_diff($check_in,$check_out)) + 1;
			$res_day = $check_in. "-" .$check_out;
		} else {
			$res_day = date($this->theme_option["date_format"], strtotime($single_day));
			$day_count = 1;
		}
		$site_url = site_url();
		$moderate_link = admin_url() . "admin.php?page=wpdevart-reservations";
		$res_info = "<table border='1' style='border-collapse:collapse;min-width: 360px;'>
						<caption style='text-align:left;'>Details</caption>
						<tr><td style='padding: 1px 7px;'>Reservation dates</td><td style='padding: 1px 7px;'>".$res_day."</td></tr>
						<tr><td style='padding: 1px 7px;'>Item Count</td><td style='padding: 1px 7px;'>".$count_item."</td></tr>
						<tr><td style='padding: 1px 7px;'>Price</td> <td style='padding: 1px 7px;'>".$price.$currency."</td></tr>
						<tr><td style='padding: 1px 7px;'>Total Price</td> <td style='padding: 1px 7px;'>".$total_price.$currency."</td></tr>
					</table>";
		$form = "";
		$extras = "";		
		if(count($form_data)) {
			$form .= "<table border='1' style='border-collapse:collapse;min-width: 360px;'>";
			$form .= "<caption style='text-align:left;'>Contact Information</caption>";
			foreach($form_data as $form_fild_data) {
				$form .= "<tr><td style='padding: 1px 7px;'>". $form_fild_data["label"] ."</td> <td style='padding: 1px 7px;'>". $form_fild_data["value"] ."</td></tr>";
			}
			$form .= "</table>";
		}	
		if(count($extras_data)) {
			$extras .= "<table border='1' style='border-collapse:collapse;min-width: 360px;'>";
			$extras .= "<caption style='text-align:left;'>Extra Information</caption>";
			foreach($extras_data as $extra_data) {
				$extras .= "<tr><td colspan='2' style='padding: 1px 7px;'>".$extra_data["group_label"]."</td></tr>";
				$extras .= "<tr><td style='padding: 1px 7px;'>". $extra_data["label"] ."</td>"; 
				$extras .= "<td style='padding: 1px 7px;'>";
				if($extra_data["price_type"] == "percent") {
					$extras .= "<span class='price-percent'>".$extra_data["operation"].$extra_data["price_percent"]."%</span>";
					$extras .= "<span class='price'>".$extra_data["operation"] . $extra_data["price"] .$currency."</span></td></tr>";
				} else {
					$extras .= "<span class='price'>".$extra_data["operation"] . ($extra_data["price"] * $day_count) .$currency."</span></td></tr>";
				}
				
			}
			$extras .= "<tr><td style='padding: 1px 7px;'>Price change</td><td style='padding: 1px 7px;'>+".$extras_price.$currency."</td></tr>";
			$extras .= "</table>";
		}
		if(isset($this->theme_option['notify_admin_on_book']) && $this->theme_option['notify_admin_on_book'] == "on") {
			$admin_email_types[] = 'notify_admin_on_book';
		}
		if(isset($this->theme_option['notify_user_on_book']) && $this->theme_option['notify_user_on_book'] == "on") {
			$user_email_types[] = 'notify_user_on_book';
		}
		if(isset($this->theme_option['enable_instant_approval']) && $this->theme_option['enable_instant_approval'] == "on") {
			if(isset($this->theme_option['notify_admin_on_approved']) && $this->theme_option['notify_admin_on_approved'] == "on") {
				$admin_email_types[] = 'notify_admin_on_approved';
			}
			if(isset($this->theme_option['notify_user_on_approved']) && $this->theme_option['notify_user_on_approved'] == "on") {
				$user_email_types[] = 'notify_user_on_approved';
			}
		}	
			/*Email to admin on approved*/
		if(count($admin_email_types)) {	
			foreach($admin_email_types as $admin_email_type) {
				$to = "";
				$from = "";
				$subject = "";
				$content = "";
				if(isset($this->theme_option[$admin_email_type.'_to']) && $this->theme_option[$admin_email_type.'_to'] != "") {
					$to = stripslashes($this->theme_option[$admin_email_type.'_to']);
				}
				if(isset($this->theme_option[$admin_email_type.'_subject']) && $this->theme_option[$admin_email_type.'_subject'] != "") {
					$subject = stripslashes($this->theme_option[$admin_email_type.'_subject']);
				}
				if(isset($this->theme_option[$admin_email_type.'_content']) && $this->theme_option[$admin_email_type.'_content'] != "") {
					$content = stripslashes($this->theme_option[$admin_email_type.'_content']);
					$content = str_replace("[detalis]", $res_info, $content);
					$content = str_replace("[siteurl]", $site_url, $content);
					$content = str_replace("[moderatelink]", $moderate_link, $content);
					$content = str_replace("[form]", $form, $content);
					$content = str_replace("[extras]", $extras, $content);
					$content = "<div class='wpdevart_email' style='color:#5A5A5A !important;line-height: 1.5;'>".$content."</div>";
				}
				if(isset($this->theme_option[$admin_email_type.'_from']) && $this->theme_option[$admin_email_type.'_from'] != "") {
					if(trim($this->theme_option[$admin_email_type.'_from']) == "[useremail]") {
						$from = "From: <" . $emails . ">" . "\r\n";
					} else {
						$from = "From: <" . stripslashes($this->theme_option[$admin_email_type.'_from']) . ">" . "\r\n";
					}
				}
				$headers = "MIME-Version: 1.0\n" . $from . " Content-Type: text/html; charset=\"" . get_option('blog_charset') . "\"\n";
				
				$send_admin = wp_mail($to, $subject, $content, $headers);
			}	
		}	
			/*Email to user on approved*/
		if(count($user_email_types)) {	
			foreach($user_email_types as $user_email_type) {	
				$from = "";
				$subject = "";
				$content = "";
				$to = $emails;
				if(isset($this->theme_option[$user_email_type.'_subject']) && $this->theme_option[$user_email_type.'_subject'] != "") {
					$subject = stripslashes($this->theme_option[$user_email_type.'_subject']);
				}
				if(isset($this->theme_option[$user_email_type.'_content']) && $this->theme_option[$user_email_type.'_content'] != "") {
					$content = stripslashes($this->theme_option[$user_email_type.'_content']);
					$content = str_replace("[detalis]", $res_info, $content);
					$content = str_replace("[siteurl]", $site_url, $content);
					$content = str_replace("[form]", $form, $content);
					$content = str_replace("[extras]", $extras, $content);
					$content = "<div class='wpdevart_email' style='color:#5A5A5A !important;line-height: 1.5;'>".$content."</div>";
				}
				if(isset($this->theme_option[$user_email_type.'_from']) && $this->theme_option[$user_email_type.'_from'] != "") {
					$from = "From: " . stripslashes($this->theme_option[$user_email_type.'_from']) . "" . "\r\n";
				}
				$headers = "MIME-Version: 1.0\n" . $from . " Content-Type: text/html; charset=\"" . get_option('blog_charset') . "\"\n";
				
				$send_user = wp_mail($to, $subject, $content, $headers);
			}
		}		
	}
	
	
	private function change_date_avail_count( $id,$approve ){
		global $wpdb; 	
		$reserv_info = $wpdb->get_row($wpdb->prepare('SELECT calendar_id, single_day, check_in, check_out, count_item, status FROM ' . $wpdb->prefix . 'wpdevart_reservations WHERE id="%d"', $id),ARRAY_A);
		if(isset($reserv_info["count_item"])) {
			$count_item = $reserv_info["count_item"];
		} else {
			$count_item = 1;
		}
		$cal_id = $reserv_info["calendar_id"]; 
		if($reserv_info["single_day"] == "") {
			$start_date = $reserv_info["check_in"];
			$date_diff = abs($this->get_date_diff($reserv_info["check_in"],$reserv_info["check_out"]));
			for($i=0; $i <= $date_diff; $i++) {
				$day = date( 'Y-m-d', strtotime($start_date. " +" . $i . " day" ));
				$unique_id = $cal_id."_".$day;
				$day_data = json_decode($this->get_date_data( $unique_id ),true);
				if($approve === true) {
					$day_data["available"] = $day_data["available"] - $count_item;
					if($day_data["available"] == 0) {
						$day_data["status"] = "booked";
					}
				} else {
					$day_data["available"] = $day_data["available"] + $count_item;
					$day_data["status"] = "available";
				}
				$day_info_jsone = json_encode($day_data);
				$update_in_db = $wpdb->update($wpdb->prefix . 'wpdevart_dates', array(
					'calendar_id' => $cal_id,
					'day' => $day,
					'data' => $day_info_jsone,
				  ), array('unique_id' => $unique_id));
			}
		} else {
			$unique_id = $cal_id."_".$reserv_info["single_day"];
			$day_data = json_decode($this->get_date_data( $unique_id ),true);
			if($approve === true) {
				$day_data["available"] = $day_data["available"] - $count_item;
				if($day_data["available"] == 0) {
					$day_data["status"] = "booked";
				}
			} else {
				$day_data["available"] = $day_data["available"] + $count_item;
				$day_data["status"] = "available";
			}
			$day_info_jsone = json_encode($day_data);
			$update_in_db = $wpdb->update($wpdb->prefix . 'wpdevart_dates', array(
				'calendar_id' => $cal_id,
				'day' => $reserv_info["single_day"],
				'data' => $day_info_jsone,
			  ), array('unique_id' => $unique_id));
		}
	}
	private function get_date_data( $unique_id ) {
		global $wpdb;
		$row = $wpdb->get_row($wpdb->prepare('SELECT data FROM ' . $wpdb->prefix . 'wpdevart_dates WHERE unique_id="%s"', $unique_id),ARRAY_A);
		$date_info = $row["data"];
		return $date_info;
	}
	
	private function get_reservation_row_calid( $id, $date ) {
		global $wpdb;
		$rows = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wpdevart_reservations WHERE calendar_id= '.$id.' and (check_in <= %s and check_out >= %s) or single_day = %s',$date,$date,$date),ARRAY_A);
		return $rows;
	}
	private function get_form_data($form) {
		global $wpdb;
		if($form) {
			$form_value = json_decode($form, true);
			$cal_id = $this->id;
			$form_id = $wpdb->get_var($wpdb->prepare('SELECT form_id FROM ' . $wpdb->prefix . 'wpdevart_calendars WHERE id="%d"', $cal_id));
			$form_info = $wpdb->get_var($wpdb->prepare('SELECT data FROM ' . $wpdb->prefix . 'wpdevart_forms WHERE id="%d"', $form_id));
			$form_info = json_decode($form_info, true);
			if(isset($form_info['apply']) || isset($form_info['save']))	{
				array_shift($form_info);
			}
			foreach($form_info as $key=>$form_fild_info) { 
				if(isset($form_value["wpdevart_".$key])) {
					$form_info[$key]["value"] = $form_value["wpdevart_".$key];
				}
				else {
					$form_info[$key]["value"] = "";
				}
			}
		} else {
			$form_info = array();
		}
		return $form_info;
	} 
	
	private function get_extra_data($extra,$price = false) {
		global $wpdb;
		if($price !== false) {
			$price = $price;
			$extra = $extra;
		} else  {
			$price = $extra["price"];
			$extra = $extra["extras"];
		}
		if($extra) {
			$extras_value = json_decode($extra, true);
			$cal_id = $this->id;
			$extra_id = $wpdb->get_var($wpdb->prepare('SELECT extra_id FROM ' . $wpdb->prefix . 'wpdevart_calendars WHERE id="%d"', $cal_id));
			$extra_info = $wpdb->get_var($wpdb->prepare('SELECT data FROM ' . $wpdb->prefix . 'wpdevart_extras WHERE id="%d"', $extra_id));
			$extra_info = json_decode($extra_info, true);
			
			if(isset($extra_info['apply']) || isset($extra_info['save']))	{
				array_shift($extra_info);
			}
			foreach($extras_value as $key=>$extra_value) { 
				if(isset($extra_info[$key])) {
					$extras_value[$key]["group_label"] = $extra_info[$key]["label"];
					if($extra_value['price_type'] == "percent") {
						$extras_value[$key]["price"] = ($price*$extra_value['price_percent'])/100;
					} else {
						$extras_value[$key]["price"] = $extra_value['price_percent'];
					}
				}
				else {
					$extras_value[$key]["group_label"] = "";
				}
			}
		} else {
			$extras_value = array();
		}
		return $extras_value;
	} 

}
