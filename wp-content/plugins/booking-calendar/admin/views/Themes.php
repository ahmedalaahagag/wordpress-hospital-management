<?php
class wpdevart_bc_ViewThemes {
	public $model_obj;
    	
    public function __construct( $model ) {
		$this->model_obj = $model;
    }	
    public function display_themes($error_msg="",$delete=true) {
		$rows = $this->model_obj->get_themes_rows();
		$items_nav = $this->model_obj->items_nav();
		$asc_desc = ((isset($_POST['asc_desc']) && $_POST['asc_desc'] == 'asc') ? 'asc' : 'desc');
		$res_order_by = (isset($_POST['order_by']) ? esc_html($_POST['order_by']) :  'id');
		$res_order_class = 'sorted ' . $asc_desc; ?>
		<div id="wpdevart_themes_container" class="wpdevart-list-container">
			<div id="action-buttons" class="div-for-clear">
				<div class="div-for-clear">
					<span class="admin_logo"></span>
					<h1>Themes <a href="http://wpdevart.com/wordpress-booking-calendar-plugin/"><span class="pro_feature"> (Upgrade to Pro Version)</span></a></h1>
				</div>
				<a href="" onclick="wpdevart_set_value('task','add'); wpdevart_form_submit(event, 'themes_form')" class="action-link">Add Theme</a>
				<a href="" onclick="wpdevart_set_value('task','delete_selected'); wpdevart_form_submit(event, 'themes_form')" class="action-link delete-link">Delete</a>
			</div>
			<?php if(isset($error_msg) && $error_msg != "") {
				$class = "error";
				if($delete === true) {
					$class = "updated";
				} ?>
				<div id="message" class="<?php echo $class; ?> notice is-dismissible"><p><?php echo $error_msg; ?></p></div>
			<?php } ?>
			<form action="admin.php?page=wpdevart-themes" method="post" id="themes_form">
			<?php wpdevart_bc_Library::items_nav($items_nav['limit'],$items_nav['total'],'themes_form'); ?>
				<table class="wp-list-table widefat fixed pages wpdevart-table"> 
					<tr>
						<thead>
							<th class="check-column"><input type="checkbox" name="check_all" onclick="check_all_checkboxes(this,'check_for_action');"></th>
							<th class="small-column">ID</th>
							<th>Title</th>
							<th class="action-column">Edit</th>
							<th class="action-column">Delete</th>
						</thead>
					<tr>
					<?php
						foreach ( $rows as $row ) { ?>
							<tr>
								<td><input type="checkbox" name="check_for_action[]" class="check_for_action" value="<?php echo $row->id; ?>"></td>
								<td><?php echo $row->id; ?></td>
								<td><a href="" onclick="wpdevart_set_value('task','edit'); wpdevart_set_value('cur_id','<?php echo $row->id; ?>'); wpdevart_form_submit(event, 'themes_form')" ><?php echo $row->title; ?></a></td>
								<td><a href="" onclick="wpdevart_set_value('task','edit'); wpdevart_set_value('cur_id','<?php echo $row->id; ?>'); wpdevart_form_submit(event, 'themes_form')" >Edit</a></td>
								<td><a href="" onclick="wpdevart_set_value('task','delete'); wpdevart_set_value('cur_id','<?php echo $row->id; ?>'); wpdevart_form_submit(event, 'themes_form')" >Delete</a></td>
							<tr>
					<?php	}
					?>
				</table>
				<input type="hidden" name="task" id="task" value="">
				<input type="hidden" name="id" id="cur_id" value="">
				<?php wpdevart_bc_Library::items_nav($items_nav['limit'],$items_nav['total'],'themes_form'); ?>
			</form>
		</div>
    <?php }
	
    public function edit_setting( $id = 0 ) { 
	    
		$wpdevart_themes = array(
	
			/* General Themes */
			'general' => array(
				'title' => 'General',
				'sections' => array(
				    'general' => array(
						'date_format' => array(
							'id'   => 'date_format',
							'title' => __( 'Date format', 'booking-calendar' ),
							'description' => __( 'Type your date format for emails and the reservation table', 'booking-calendar' ),
							'valid_options' => array(
							  'F j, Y' => date('F j, Y'),
							  'Y M j' => date('Y M j'),
							  'd.m.Y' => date('d.m.Y'),
							  'd-m-Y' => date('d-m-Y'),
							  'd/m/Y' => date('d/m/Y'),
							  'm/d/Y' => date('m/d/Y')
							),
							'type' => 'select',
							'default' => ''
						),
						'week_days' => array(
							'id'   => 'week_days',
							'title' => __( 'Week days format', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'valid_options' => array(
							  '0' => 'Sunday',
							  '1' => 'Sun',
							  '2' => 'Su'
							),
							'type' => 'select',
							'default' => ''
						),
						/*'tyme_type' => array(
							'id'   => 'tyme_type',
							'title' => __( 'Tyme type', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'default' => ''
						),
						'month_number' => array(
							'id'   => 'month_number',
							'title' => __( 'Number of months', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'default' => 1
						),*/
						'day_start' => array(
							'id'   => 'day_start',
							'title' => __( 'Start Day of the week', 'booking-calendar' ),
							'description' => __( 'Type here the start day of the week for Calendar', 'booking-calendar' ),
							'valid_options' => array(
							  '0' => 'Sunday',
							  '1' => 'Monday',
							  '2' => 'Tuesday',
							  '3' => 'Wednesday',
							  '4' => 'Thursday',
							  '5' => 'Friday',
							  '6' => 'Saturday'
							),
							'type' => 'select',
							'default' => '1'
						),
						'unavailable_week_days' => array(
							'id'   => 'unavailable_week_days',
							'title' => __( 'Unavailable week days', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'valid_options' => array(
							  '0' => 'Sunday',
							  '1' => 'Monday',
							  '2' => 'Tuesday',
							  '3' => 'Wednesday',
							  '4' => 'Thursday',
							  '5' => 'Friday',
							  '6' => 'Saturday'
							),
							'type' => 'checkbox',
							'default' => array()
						),
						'enable_instant_approval' => array(
							'id'   => 'enable_instant_approval',
							'title' => __( 'Enable instant approval', 'booking-calendar' ),
							'description' => __( 'Select this option and the booking request will be approved instantly', 'booking-calendar' ),
							'type' => 'checkbox',
							'default' => ''
						),
						'type_days_selection' => array(
							'id'   => 'type_days_selection',
							'title' => __( 'Type of days selection in calendar', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'radio',
							'valid_options' => array("multiple_days"=>"Multiple days","single_day"=>"Single day"),
							'default' => 'multiple_days'
						),
						'enable_checkinout' => array(
							'id'   => 'enable_checkinout',
							'title' => __( 'Enable Check in/Check out', 'booking-calendar' ),
							'description' => __( 'Show Check in/Check out text in Form', 'booking-calendar' ),
							'type' => 'checkbox',
							'default' => 'on'
						),
						'enable_number_items' => array(
							'id'   => 'enable_number_items',
							'title' => __( 'Enable number of items', 'booking-calendar' ),
							'description' => __( 'Show number of items in Form', 'booking-calendar' ),
							'type' => 'checkbox',
							'default' => 'on'
						),
						'enable_terms_cond' => array(
							'id'   => 'enable_terms_cond',
							'title' => __( 'Enable Terms & Conditions', 'booking-calendar' ),
							'description' => __( 'Enable Terms & Conditions', 'booking-calendar' ),
							'enable' => array('terms_cond_link'),
							'type' => 'checkbox_enable',
							'default' => ''
						),
						'terms_cond_link' => array(
							'id'   => 'terms_cond_link',
							'title' => __( 'Terms & Conditions link', 'booking-calendar' ),
							'description' => __( 'Terms & Conditions link', 'booking-calendar' ),
							'type' => 'text',
							'extra_div'=> true,
							'extra_div_end'=> true,
							'default' => ''
						),
						'auto_fill' => array(
							'id'   => 'auto_fill',
							'title' => __( 'Auto-fill fields', 'booking-calendar' ),
							'description' => __( 'Select this option to use the Auto-fill fields function', 'booking-calendar' ),
							'type' => 'checkbox',
							'default' => ''
						),
						'enable_form_title' => array(
							'id'   => 'enable_form_title',
							'title' => __( 'Enable Form title', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'checkbox',
							'default' => 'on'
						),
						'enable_extras_title' => array(
							'id'   => 'enable_extras_title',
							'title' => __( 'Enable Extras title', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'checkbox',
							'default' => 'on'
						),
						'legend_enable' => array(
							'id'   => 'legend_enable',
							'title' => __( 'Show days status below date', 'booking-calendar' ),
							'description' => __( 'This is an important function, so we think you should use the days status option', 'booking-calendar' ),
							'enable' => array('legend_available_enable','legend_available','legend_booked_enable','legend_booked','legend_unavailable_enable','legend_unavailable'),
							'type' => 'checkbox_enable',
							'default' => 'on'
						),
						'legend_available_enable' => array(
							'id'   => 'legend_available_enable',
							'title' => __( 'Available', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'enable' => array('legend_available'),
							'type' => 'checkbox_enable',
							'extra_div'=> true,
							'default' => 'on'
						),
						'legend_available' => array(
							'id'   => 'legend_available',
							'title' => __( '', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'default' => 'Available'
						),
						'legend_booked_enable' => array(
							'id'   => 'legend_booked_enable',
							'title' => __( 'Booked', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'enable' => array('legend_booked'),
							'type' => 'checkbox_enable',
							'default' => 'on'
						),
						'legend_booked' => array(
							'id'   => 'legend_booked',
							'title' => __( '', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'default' => 'Booked'
						),
						'legend_unavailable_enable' => array(
							'id'   => 'legend_unavailable_enable',
							'title' => __( 'Unavailable', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'enable' => array('legend_unavailable'),
							'type' => 'checkbox_enable',
							'default' => 'on'
						),
						'legend_unavailable' => array(
							'id'   => 'legend_unavailable',
							'title' => __( '', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'extra_div_end'=> true,
							'default' => 'Unavailable'
						),
						'action_after_submit' => array(
							'id'   => 'action_after_submit',
							'title' => __( 'Action after submition', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'valid_options' => array(
								'stay_on_calendar' => 'Stay on Calendar',
								'redirect' => 'Redirect visitor to a new page'
							),
							'enable' => array('stay_on_calendar'=>array('message_text'),'redirect' =>array( 'redirect_url')),
							'type' => 'radio_enable',
							'pro' => true,
							'default' => 'stay_on_calendar'
						),
						'message_text' => array(
							'id'   => 'message_text',
							'title' => __( 'Message title', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'default' => 'Thanks :)'
						),
						/*'time_of_message' => array(
							'id'   => 'time_of_message',
							'title' => __( 'Time of message showing', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'default' => '30'
						),*/
						'redirect_url' => array(
							'id'   => 'redirect_url',
							'title' => __( 'URL', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'default' => ''
						)
					),
					'currency_settings' => array(
						'currency' => array(
							'id'   => 'currency',
							'title' => __( 'Currency', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'valid_options' => wpdevart_bc_get_currency(),
							'currency' => true,
							'type' => 'select',
							'default' => 'USD'
						)
						/*'currency_pos' => array(
							'id'   => 'currency_pos',
							'title' => __( 'Currency Position', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'valid_options' => array("after" => "After","before" => "Before"),
							'type' => 'select',
							'default' => ''
						)*/
					)
                )					
			),
			/*"hour_themes" => array(
				'title' => 'Hours Themes',
					'value' => array(
						'hours_enabled' => array(
							'id'   => 'hours_enabled',
							'title' => __( 'Hours Enabled', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'checkbox',
							'default' => ''
						),
						'hours_interval_enabled' => array(
							'id'   => 'hours_interval_enabled',
							'title' => __( 'Hours Interval Enabled', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'checkbox',
							'default' => ''
						),
						'enable_hours_info' => array(
							'id'   => 'enable_hours_info',
							'title' => __( 'Enable hours info', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'checkbox',
							'default' => ''
						),
						'hours_definitions' => array(
							'id'   => 'hours_definitions',
							'title' => __( 'Hours Definitions', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'textarea',
							'default' => ''
						)
					)
			),*/
			"styles_and_colors" => array(
				'title' => 'Styles and Colors',
				'pro' => true,
				'sections' => array(
					'styles' => array(
						/*Calendar styles*/
						'calendar_max_width' => array(
							'id'   => 'calendar_max_width',
							'title' => __( 'Calendar Maximum width', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'pro' => true,
							'default' => '680'
						),
						'calendar_header_font_weight' => array(
							'id'   => 'calendar_header_font_weight',
							'title' => __( 'Calendar Header font weight', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'select',
							'valid_options' => $this->font_weight(),
							'pro' => true,
							'default' => 'normal'
						),
						'calendar_header_font_style' => array(
							'id'   => 'calendar_header_font_style',
							'title' => __( 'Calendar Header font style', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'select',
							'valid_options' => $this->font_style(),
							'pro' => true,
							'default' => 'normal'
						),
						'calendar_header_padding' => array(
							'id'   => 'calendar_header_padding',
							'title' => __( 'Calendar Header padding', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'pro' => true,
							'default' => '10'
						),
						'next_prev_month_size' => array(
							'id'   => 'next_prev_month_size',
							'title' => __( 'Next Prev Month font size', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'pro' => true,
							'default' => '15'
						),
						'current_month_size' => array(
							'id'   => 'current_month_size',
							'title' => __( 'Current Month font size', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'pro' => true,
							'default' => '19'
						),
						'current_year_size' => array(
							'id'   => 'current_year_size',
							'title' => __( 'Current Year font size', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'pro' => true,
							'default' => '19'
						),
						'week_days_font_weight' => array(
							'id'   => 'week_days_font_weight',
							'title' => __( 'Week Days font weight', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'select',
							'valid_options' => $this->font_weight(),
							'pro' => true,
							'default' => 'normal'
						),
						'week_days_font_style' => array(
							'id'   => 'week_days_font_style',
							'title' => __( 'Week Days font style', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'select',
							'valid_options' => $this->font_style(),
							'pro' => true,
							'default' => 'normal'
						),
						'week_days_size' => array(
							'id'   => 'week_days_size',
							'title' => __( 'Week Days font size', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'pro' => true,
							'default' => '13'
						),
						'day_number_font_weight' => array(
							'id'   => 'day_number_font_weight',
							'title' => __( 'Day Number font weight', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'select',
							'valid_options' => $this->font_weight(),
							'pro' => true,
							'default' => 'normal'
						),
						'day_number_font_style' => array(
							'id'   => 'day_number_font_style',
							'title' => __( 'Day Number font style', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'select',
							'valid_options' => $this->font_style(),
							'pro' => true,
							'default' => 'normal'
						),
						'day_number_size' => array(
							'id'   => 'day_number_size',
							'title' => __( 'Day Number font size', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'pro' => true,
							'default' => '13'
						),
						'days_min_height' => array(
							'id'   => 'days_min_height',
							'title' => __( 'Days Minimum height', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'pro' => true,
							'default' => '65'
						),
						'info_font_weight' => array(
							'id'   => 'info_font_weight',
							'title' => __( 'Info font weight', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'select',
							'valid_options' => $this->font_weight(),
							'pro' => true,
							'default' => 'normal'
						),
						'info_font_style' => array(
							'id'   => 'info_font_style',
							'title' => __( 'Info font style', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'select',
							'valid_options' => $this->font_style(),
							'pro' => true,
							'default' => 'normal'
						),
						'info_size' => array(
							'id'   => 'info_size',
							'title' => __( 'Info font size', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'pro' => true,
							'default' => '13'
						),
						'info_border_radius' => array(
							'id'   => 'info_border_radius',
							'title' => __( 'Info Border radius', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'pro' => true,
							'default' => '0'
						),
						/*Form styles*/
						'form_title_weight' => array(
							'id'   => 'form_title_weight',
							'title' => __( 'Form/Extra Title font weight', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'select',
							'valid_options' => $this->font_weight(),
							'pro' => true,
							'default' => 'normal'
						),
						'form_title_style' => array(
							'id'   => 'form_title_style',
							'title' => __( 'Form/Extra Title font style', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'select',
							'valid_options' => $this->font_style(),
							'pro' => true,
							'default' => 'italic'
						),
						'form_title_size' => array(
							'id'   => 'form_title_size',
							'title' => __( 'Form/Extra Title font size', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'pro' => true,
							'default' => '21'
						),
						'form_labels_weight' => array(
							'id'   => 'form_labels_weight',
							'title' => __( 'Form/Extra Labels font weight', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'select',
							'valid_options' => $this->font_weight(),
							'pro' => true,
							'default' => 'normal'
						),
						'form_labels_style' => array(
							'id'   => 'form_labels_style',
							'title' => __( 'Form/Extra Labels font style', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'select',
							'valid_options' => $this->font_style(),
							'pro' => true,
							'default' => 'italic'
						),
						'form_labels_size' => array(
							'id'   => 'form_labels_size',
							'title' => __( 'Form/Extra Labels font size', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'pro' => true,
							'default' => '15'
						),
						'form_fields_weight' => array(
							'id'   => 'form_fields_weight',
							'title' => __( 'Form/Extra Fields font weight', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'select',
							'valid_options' => $this->font_weight(),
							'pro' => true,
							'default' => 'normal'
						),
						'form_fields_style' => array(
							'id'   => 'form_fields_style',
							'title' => __( 'Form/Extra Fields font style', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'select',
							'valid_options' => $this->font_style(),
							'pro' => true,
							'default' => 'normal'
						),
						'form_fields_size' => array(
							'id'   => 'form_fields_size',
							'title' => __( 'Form/Extra Fields font size', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'pro' => true,
							'default' => '15'
						),
						'form_submit_weight' => array(
							'id'   => 'form_submit_weight',
							'title' => __( 'Form Submit font weight', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'select',
							'valid_options' => $this->font_weight(),
							'pro' => true,
							'default' => 'normal'
						),
						'form_style_style' => array(
							'id'   => 'form_style_style',
							'title' => __( 'Form Submit font style', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'select',
							'valid_options' => $this->font_style(),
							'pro' => true,
							'default' => 'normal'
						),
						'reserv_info_weight' => array(
							'id'   => 'reserv_info_weight',
							'title' => __( 'Reservation Info font weight', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'select',
							'valid_options' => $this->font_weight(),
							'pro' => true,
							'default' => 'normal'
						),
						'reserv_info_style' => array(
							'id'   => 'reserv_info_style',
							'title' => __( 'Reservation Info font style', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'select',
							'valid_options' => $this->font_style(),
							'pro' => true,
							'default' => 'normal'
						),
						'reserv_info_size' => array(
							'id'   => 'reserv_info_size',
							'title' => __( 'Reservation Info font size', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'pro' => true,
							'default' => '14'
						)
					),
					"colors" => array(	
						/*Calendar colors*/
						'calendar_header_bg' => array(
							'id'   => 'calendar_header_bg',
							'title' => __( 'Calendar Header background', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#FFFFFF'
						),
						'next_prev_month' => array(
							'id'   => 'next_prev_month',
							'title' => __( 'Next Preview Month color', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#636363'
						),
						'current_month' => array(
							'id'   => 'current_month',
							'title' => __( 'Current Month color', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#636363'
						),
						'current_year' => array(
							'id'   => 'current_year',
							'title' => __( 'Current Year color', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#636363'
						),
						'week_days_bg' => array(
							'id'   => 'week_days_bg',
							'title' => __( 'Week Days background', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#ECECEC'
						),
						'week_days_color' => array(
							'id'   => 'week_days_color',
							'title' => __( 'Week Days color', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#656565'
						),
						'calendar_bg' => array(
							'id'   => 'calendar_bg',
							'title' => __( 'Calendar background', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#FFFFFF'
						),
						'calendar_border' => array(
							'id'   => 'calendar_border',
							'title' => __( 'Calendar Border color', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#ddd'
						),
						'day_bg' => array(
							'id'   => 'day_bg',
							'title' => __( 'Day background', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#FFFFFF'
						),
						'day_number_bg' => array(
							'id'   => 'day_number_bg',
							'title' => __( 'Day Number background', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#ECECEC'
						),
						'day_color' => array(
							'id'   => 'day_color',
							'title' => __( 'Day Number color', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#464646'
						),
						'available_day_bg' => array(
							'id'   => 'available_day_bg',
							'title' => __( 'Available Day background', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#FFFFFF'
						),
						'available_day_number_bg' => array(
							'id'   => 'available_day_number_bg',
							'title' => __( 'Available Day Number background', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#85B70B'
						),
						'available_day_color' => array(
							'id'   => 'available_day_color',
							'title' => __( 'Available Day Number color', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#FFFFFF'
						),
						'selected_day_bg' => array(
							'id'   => 'selected_day_bg',
							'title' => __( 'Selected Day background', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#FFFFFF'
						),
						'selected_day_number_bg' => array(
							'id'   => 'selected_day_number_bg',
							'title' => __( 'Selected Day Number background', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#373740'
						),
						'selected_day_color' => array(
							'id'   => 'selected_day_color',
							'title' => __( 'Selected Day Number color', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#FFFFFF'
						),
						'unavailable_day_bg' => array(
							'id'   => 'unavailable_day_bg',
							'title' => __( 'Unavailable Day background', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#FFFFFF'
						),
						'unavailable_day_number_bg' => array(
							'id'   => 'unavailable_day_number_bg',
							'title' => __( 'Unavailable Day Number background', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#464646'
						),
						'unavailable_day_color' => array(
							'id'   => 'unavailable_day_color',
							'title' => __( 'Unavailable Day Number color', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#ECECEC'
						),
						'booked_day_bg' => array(
							'id'   => 'booked_day_bg',
							'title' => __( 'Booked Day background', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#FFFFFF'
						),
						'booked_day_number_bg' => array(
							'id'   => 'booked_day_number_bg',
							'title' => __( 'Booked Day Number background', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#FD7C93'
						),
						'booked_day_color' => array(
							'id'   => 'booked_day_color',
							'title' => __( 'Booked Day Number color', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#FFFFFF'
						),
						'info_icon_color' => array(
							'id'   => 'info_icon_color',
							'title' => __( 'Info Icon color', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#FFFFFF'
						),
						'info_bg' => array(
							'id'   => 'info_bg',
							'title' => __( 'Info background', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#FFFFFF'
						),
						'info_color' => array(
							'id'   => 'info_color',
							'title' => __( 'Info Icon color', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#4E4E4E'
						),
						/*Form colors*/
						'form_bg' => array(
							'id'   => 'form_bg',
							'title' => __( 'Form background', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#FDFDFD'
						),
						'form_border' => array(
							'id'   => 'form_border',
							'title' => __( 'Form boreder color', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#ddd'
						),
						'form_title_color' => array(
							'id'   => 'form_title_color',
							'title' => __( 'Form/Extra Title color', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#636363'
						),
						'form_title_bg' => array(
							'id'   => 'form_title_bg',
							'title' => __( 'Form/Extra Title background', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#FDFDFD'
						),
						'form_labels_color' => array(
							'id'   => 'form_labels_color',
							'title' => __( 'Form/Extra Labels color', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#636363'
						),
						'form_fields_color' => array(
							'id'   => 'form_fields_color',
							'title' => __( 'Form/Extra Fields color', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#636363'
						),
						'reserv_info_color' => array(
							'id'   => 'reserv_info_color',
							'title' => __( 'Reservation Info color', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#545454'
						),
						'total_bg' => array(
							'id'   => 'total_bg',
							'title' => __( 'Total Price background', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#545454'
						),
						'total_color' => array(
							'id'   => 'total_color',
							'title' => __( 'Total Price color', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#F7F7F7'
						),
						'required_star_color' => array(
							'id'   => 'required_star_color',
							'title' => __( 'Required Star color', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#FD7C93'
						),
						'submit_button_bg' => array(
							'id'   => 'submit_button_bg',
							'title' => __( 'Submit Button background', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#FD7C93'
						),
						'submit_button_color' => array(
							'id'   => 'submit_button_color',
							'title' => __( 'Submit Button color', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#FFFFFF'
						),
						'error_info_bg' => array(
							'id'   => 'error_info_bg',
							'title' => __( 'Error Info background', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#FFFFFF'
						),
						'error_info_color' => array(
							'id'   => 'error_info_color',
							'title' => __( 'Error Info color', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#C11212'
						),
						'error_info_border' => array(
							'id'   => 'error_info_border',
							'title' => __( 'Error Info Border color', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#C11212'
						),
						'error_info_close_bg' => array(
							'id'   => 'error_info_close_bg',
							'title' => __( 'Error Info Close background', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#C11212'
						),
						'error_info_close_color' => array(
							'id'   => 'error_info_close_color',
							'title' => __( 'Error Info Close color', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#FFFFFF'
						),
						'successfully_info_bg' => array(
							'id'   => 'successfully_info_bg',
							'title' => __( 'Successfully Info background', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#FFFFFF'
						),
						'successfully_info_color' => array(
							'id'   => 'successfully_info_color',
							'title' => __( 'Successfully Info color', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#7FAD16'
						),
						'successfully_info_border' => array(
							'id'   => 'successfully_info_border',
							'title' => __( 'Successfully Info Border color', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#7FAD16'
						),
						'successfully_info_close_bg' => array(
							'id'   => 'successfully_info_close_bg',
							'title' => __( 'Successfully Info Close background', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#7FAD16'
						),
						'successfully_info_close_color' => array(
							'id'   => 'successfully_info_close_color',
							'title' => __( 'Successfully Info Close color', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'color',
							'pro' => true,
							'default' => '#FFFFFF'
						)
					)				
			    )			
			),
			"notifications" => array(
				'title' => 'Notifications',
				'sections' => array(
					'email_to_administrator' => array(
						'admin_mail_info' => array(
							'id'   => 'admin_mail_info',
							'title' => __( 'You can use these shortcodes in content of admin templates', 'booking-calendar' ),
							'description' => __( '<span>[detalis]</span> - inserting detalis about the reservation,<br><span>[siteurl]</span> - inserting your site URL ,<br><span>[moderatelink]</span> - inserting moderate link of new reservation,<br><span>[form]</span> - inserting form information,<br><span>[extras]</span> - inserting extras information, ', 'booking-calendar' ),
							'type' => 'info',
							'default' => ''
						),
						'notify_admin_on_book' => array(
							'id'   => 'notify_admin_on_book',
							'title' => __( 'Notify on book request', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'enable' => array('notify_admin_on_book_to','notify_admin_on_book_from','notify_admin_on_book_subject','notify_admin_on_book_content'),
							'type' => 'checkbox_enable',
							'default' => 'on'
						),
						'notify_admin_on_book_to' => array(
							'id'   => 'notify_admin_on_book_to',
							'title' => __( 'To:', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'width' => 340,
							'extra_div' => true,
							'default' => "\"Booking calendar\" <".(get_option("admin_email")).">"
						),
						'notify_admin_on_book_from' => array(
							'id'   => 'notify_admin_on_book_from',
							'title' => __( 'From:', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'width' => 340,
							'default' => '[useremail]'
						),
						'notify_admin_on_book_subject' => array(
							'id'   => 'notify_admin_on_book_subject',
							'title' => __( 'Subject:', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'width' => 340,
							'default' => 'You received a booking request.'
						),
						'notify_admin_on_book_content' => array(
							'id'   => 'notify_admin_on_book_content',
							'title' => __( 'Content:', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'textarea',
							'wp_editor' => true,
							'extra_div_end' => true,
							'default' => ''
						),
						'notify_admin_on_approved' => array(
							'id'   => 'notify_admin_on_approved',
							'title' => __( 'Notify on approved book request', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'enable' => array('notify_admin_on_approved_to','notify_admin_on_approved_from','notify_admin_on_approved_subject','notify_admin_on_approved_content'),
							'type' => 'checkbox_enable',
							'default' => 'on'
						),
						'notify_admin_on_approved_to' => array(
							'id'   => 'notify_admin_on_approved_to',
							'title' => __( 'To:', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'width' => 340,
							'extra_div' => true,
							'default' => "\"Booking calendar\" <".(get_option("admin_email")).">"
						),
						'notify_admin_on_approved_from' => array(
							'id'   => 'notify_admin_on_approved_from',
							'title' => __( 'From:', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'width' => 340,
							'default' => '[useremail]'
						),
						'notify_admin_on_approved_subject' => array(
							'id'   => 'notify_admin_on_approved_subject',
							'title' => __( 'Subject:', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'width' => 340,
							'default' => 'You received a booking request.'
						),
						'notify_admin_on_approved_content' => array(
							'id'   => 'notify_admin_on_approved_content',
							'title' => __( 'Content:', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'textarea',
							'wp_editor' => true,
							'extra_div_end' => true,
							'default' => ''
						),
						/*'notify_admin_paypal' => array(
							'id'   => 'notify_admin_paypal',
							'title' => __( 'PayPal - Notify', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'enable' => array('notify_admin_paypal_to','notify_admin_paypal_from','notify_admin_paypal_subject','notify_admin_paypal_content'),
							'type' => 'checkbox_enable',
							'default' => 'on'
						),
						'notify_admin_paypal_to' => array(
							'id'   => 'notify_admin_paypal_to',
							'title' => __( 'To:', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'width' => 340,
							'extra_div' => true,
							'default' => "\"Booking calendar\" <".(get_option("admin_email")).">"
						),
						'notify_admin_paypal_from' => array(
							'id'   => 'notify_admin_paypal_from',
							'title' => __( 'From:', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'width' => 340,
							'default' => '[useremail]'
						),
						'notify_admin_paypal_subject' => array(
							'id'   => 'admin_paypal_subject',
							'title' => __( 'Subject:', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'width' => 340,
							'default' => 'You received a booking request.'
						),
						'notify_admin_paypal_content' => array(
							'id'   => 'admin_paypal_content',
							'title' => __( 'Content:', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'textarea',
							'wp_editor' => true,
							'extra_div_end' => true,
							'default' => ''
						)*/
					),
					'email_to_user' => array(
						'user_mail_info' => array(
							'id'   => 'user_mail_info',
							'title' => __( 'You can use these shortcodes in content of user templates', 'booking-calendar' ),
							'description' => __( '<span>[detalis]</span> - inserting detalis about the reservation,<br><span>[siteurl]</span> - inserting your site URL ,<br><span>[form]</span> - inserting form information,<br><span>[extras]</span> - inserting extras information, ', 'booking-calendar' ),
							'type' => 'info',
							'default' => ''
						),
						'notify_user_on_book' => array(
							'id'   => 'notify_user_on_book',
							'title' => __( 'Notify on book request', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'enable' => array('notify_user_on_book_from','notify_user_on_book_subject','notify_user_on_book_content'),
							'type' => 'checkbox_enable',
							'default' => 'on'
						),
						'notify_user_on_book_from' => array(
							'id'   => 'notify_user_on_book_from',
							'title' => __( 'From:', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'width' => 340,
							'extra_div' => true,
							'default' => "\"Booking calendar\" <".(get_option("admin_email")).">"
						),
						'notify_user_on_book_subject' => array(
							'id'   => 'notify_user_on_book_subject',
							'title' => __( 'Subject:', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'width' => 340,
							'default' => 'Your booking request has been sent.'
						),
						'notify_user_on_book_content' => array(
							'id'   => 'user_on_book_content',
							'title' => __( 'Content:', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'textarea',
							'wp_editor' => true,
							'extra_div_end' => true,
							'default' => ''
						),
						'notify_user_on_approved' => array(
							'id'   => 'notify_user_on_approved',
							'title' => __( 'Notify when reservation is approved', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'enable' => array('notify_user_on_approved_from','notify_user_on_approved_subject','notify_user_on_approved_content'),
							'type' => 'checkbox_enable',
							'default' => 'on'
						),
						'notify_user_on_approved_from' => array(
							'id'   => 'notify_user_on_approved_from',
							'title' => __( 'From:', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'width' => 340,
							'extra_div' => true,
							'default' => "\"Booking calendar\" <".(get_option("admin_email")).">"
						),
						'notify_user_on_approved_subject' => array(
							'id'   => 'notify_user_on_approved_subject',
							'title' => __( 'Subject:', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'width' => 340,
							'default' => 'Your booking request has been approved'
						),
						'notify_user_on_approved_content' => array(
							'id'   => 'notify_user_on_approved_content',
							'title' => __( 'Content:', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'textarea',
							'wp_editor' => true,
							'extra_div_end' => true,
							'default' => ''
						),
						'notify_user_canceled' => array(
							'id'   => 'notify_user_canceled',
							'title' => __( 'Notify when reservation is canceled', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'enable' => array('notify_user_canceled_from','notify_user_canceled_subject','notify_user_canceled_content'),
							'type' => 'checkbox_enable',
							'default' => 'on'
						),
						'notify_user_canceled_from' => array(
							'id'   => 'notify_user_canceled_from',
							'title' => __( 'From:', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'width' => 340,
							'extra_div' => true,
							'default' => "\"Booking calendar\" <".(get_option("admin_email")).">"
						),
						'notify_user_canceled_subject' => array(
							'id'   => 'notify_user_canceled_subject',
							'title' => __( 'Subject:', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'width' => 340,
							'default' => 'Your booking request has been canceled'
						),
						'notify_user_canceled_content' => array(
							'id'   => 'notify_user_canceled_content',
							'title' => __( 'Content:', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'textarea',
							'wp_editor' => true,
							'extra_div_end' => true,
							'default' => ''
						),
						'notify_user_deleted' => array(
							'id'   => 'notify_user_deleted',
							'title' => __( 'Notify when reservation is deleted (rejected)', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'enable' => array('notify_user_deleted_from','notify_user_deleted_subject','notify_user_deleted_content'),
							'type' => 'checkbox_enable',
							'default' => 'on'
						),
						'notify_user_deleted_from' => array(
							'id'   => 'notify_user_deleted_from',
							'title' => __( 'From:', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'width' => 340,
							'extra_div' => true,
							'default' => "\"Booking calendar\" <".(get_option("admin_email")).">"
						),
						'notify_user_deleted_subject' => array(
							'id'   => 'notify_user_deleted_subject',
							'title' => __( 'Subject:', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'width' => 340,
							'default' => 'Your booking request has been rejected'
						),
						'notify_user_deleted_content' => array(
							'id'   => 'notify_user_deleted_content',
							'title' => __( 'Content:', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'textarea',
							'wp_editor' => true,
							'extra_div_end' => true,
							'default' => ''
						),
						/*'notify_user_paypal' => array(
							'id'   => 'notify_user_paypal',
							'title' => __( 'PayPal - Notify', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'enable' => array('notify_user_paypal_from','notify_user_paypal_subject','notify_user_paypal_content'),
							'type' => 'checkbox_enable',
							'default' => 'on'
						),
						'notify_user_paypal_from' => array(
							'id'   => 'notify_user_paypal_from',
							'title' => __( 'From:', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'width' => 340,
							'extra_div' => true,
							'default' => "\"Booking calendar\" <".(get_option("admin_email")).">"
						),
						'notify_user_paypal_subject' => array(
							'id'   => 'notify_user_paypal_subject',
							'title' => __( 'Subject:', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'width' => 340,
							'default' => 'Your booking request has been sent.'
						),
						'notify_user_paypal_content' => array(
							'id'   => 'notify_user_paypal_content',
							'title' => __( 'Content:', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'textarea',
							'wp_editor' => true,
							'extra_div_end' => true,
							'default' => ''
						)*/
					)
				)
			),
			"default_texts" => array(
				'title' => 'Default Texts',
				'sections' => array(
					'default_texts' => array(
						'for_check_in' => array(
							'id'   => 'for_check_in',
							'title' => __( 'Text for check in', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'default' => 'Check in'
						),
						'for_check_out' => array(
							'id'   => 'for_check_out',
							'title' => __( 'Text for check out', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'default' => 'Check out'
						),
						'for_item_count' => array(
							'id'   => 'for_item_count',
							'title' => __( 'Text for item count', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'default' => 'Item count'
						),
						'for_termscond' => array(
							'id'   => 'for_termscond',
							'title' => __( 'Text for terms & conditions', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'default' => 'I accept to agree to the Terms & Conditions.'
						),
						'for_reservation' => array(
							'id'   => 'for_reservation',
							'title' => __( 'Text for reservation', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'default' => 'Reservation'
						),
						'for_select_days' => array(
							'id'   => 'for_select_days',
							'title' => __( 'Text for select days', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'default' => 'Please select the days from calendar.'
						),
						'for_price' => array(
							'id'   => 'for_price',
							'title' => __( 'Text for price', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'default' => 'Price'
						),
						'for_total' => array(
							'id'   => 'for_total',
							'title' => __( 'Text for total', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'default' => 'Total'
						),
						'for_submit_button' => array(
							'id'   => 'for_submit_button',
							'title' => __( 'Text for submit button', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'default' => 'Book Now'
						),
						'for_request_successfully_sent' => array(
							'id'   => 'for_request_successfully_sent',
							'title' => __( 'Text for request successfully sent', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'default' => 'Your request has been successfully sent. Please wait for approval.'
						),
						'for_request_successfully_received' => array(
							'id'   => 'for_request_successfully_received',
							'title' => __( 'Text for request successfully received', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'default' => 'Your request has been successfully received. We are waiting you!'
						),
						'for_error_single' => array(
							'id'   => 'for_error_single',
							'title' => __( 'Text for no services available single', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'default' => 'There are no services available for this day.'
						),
						'for_error_multi' => array(
							'id'   => 'for_error_multi',
							'title' => __( 'Text for no services available multiple days', 'booking-calendar' ),
							'description' => __( '', 'booking-calendar' ),
							'type' => 'text',
							'default' => 'There are no services available for the period you selected.'
						)
					)
				)
			)	
		);
		if($id != 0){
			$setting_rows = $this->model_obj->get_setting_rows( $id );
			$value = json_decode( $setting_rows->value, true );
		}
		?>
		<div id="wpdevart_themes" class="wpdevart-item-container wpdevart-main-item-container">
			<?php
			    if($id != 0){ ?>
					<div class="div-for-clear">
						<span class="admin_logo"></span>
						<h1>Edit Theme <a href="http://wpdevart.com/wordpress-booking-calendar-plugin/"><span class="pro_feature"> (Upgrade to Pro Version)</span></a></h1>
					</div>
				<?php } else { ?>
					<div class="div-for-clear">
						<span class="admin_logo"></span>
						<h1>Edit Theme <a href="http://wpdevart.com/wordpress-booking-calendar-plugin/"><span class="pro_feature"> (Upgrade to Pro Version)</span></a></h1>
					</div>
				<?php } ?>
			<form action="?page=wpdevart-themes" method="post" class="div-for-clear">
				<div id="wpdevart_wpdevart-item_title">
					<span>Theme Name</span> <input type="text" name="title" value="<?php if(isset($setting_rows->title)) echo esc_attr($setting_rows->title); ?>">
					<input type="submit" value="Save" class="action-link wpda-input" name="save">
					<input type="submit" value="Apply" class="action-link wpda-input" name="apply">
				</div>
				<div id="wpdevart-tabs-container" class="div-for-clear">
					<div id="wpdevart_theme-tabs" class="div-for-clear">
						<?php foreach($wpdevart_themes as $key=>$wpdevart_theme) { ?>
							<div id="wpdevart_theme-tab-<?php echo $key; ?>" class="wpdevart_tab <?php echo ($key == "general")? "show" : ""; ?>"><?php echo $wpdevart_theme["title"];
							if(isset($wpdevart_theme["pro"]) && $wpdevart_theme["pro"] === true) {
								echo "<span class='pro_feature'> (Pro Feature!)</span>";
							}
							?></div>
						<?php } ?>
					</div>
					<div id="wpdevart-tabs-item-container" class="div-for-clear">
						<?php foreach( $wpdevart_themes as $key=>$wpdevart_setting ) { ?>
							<div id="wpdevart_theme-tab-<?php echo $key; ?>_container" class="wpdevart_container wpdevart-item-section <?php echo ($key == "general")? "show" : ""; ?>"> 
							<?php foreach( $wpdevart_setting['sections'] as $value_key=>$value_setting ) { ?>
								<div class="wpdevart-item-section-cont">
									<h3><?php echo str_replace("_"," ",$value_key); ?></h3>
									<div>
										<?php
										foreach( $value_setting as $key => $wpdevart_setting_value ) {
											if(isset($wpdevart_setting_value["extra_div"]) && $wpdevart_setting_value["extra_div"]){
												echo "<div class='items_open'>";
											}
											
											if( isset($value[$key]) ) {
												$sett_value = $value[$key];
											} else if(isset($value) && ($wpdevart_setting_value["type"] == "checkbox" || $wpdevart_setting_value["type"] == "checkbox_enable")){
												if(isset($wpdevart_setting_value["valid_options"])) {
													$sett_value = array();
												} else {
													$sett_value = "";
												}
											} else {
												$sett_value = $wpdevart_setting_value['default'];
											}

											$function_name = "wpdevart_callback_" . $wpdevart_setting_value['type'];
											wpdevart_bc_Library::$function_name($wpdevart_setting_value, $sett_value);
											if(isset($wpdevart_setting_value["extra_div_end"]) && $wpdevart_setting_value["extra_div_end"]){
												echo "</div>";
											}
										}
									?>
									</div>	
								</div>	
							<?php } ?>	
							</div>	
						<?php  } ?>
						<input type="hidden" name="task" value="save">
						<input type="hidden" name="id" value="<?php echo $id; ?>">
				    </div>
				</div>
			</form>
		</div>
	<?php	
	}
  
    private function font_weight() {
		$font_weight = array(
		     "normal" => "Normal",
		     "bold"   => "Bold",
		     "light"  => "Light"
		);
		return $font_weight;
	}
  
    private function font_style() {
		$font_style = array(
		     "normal" => "Normal",
		     "italic" => "Italic",
		);
		return $font_style;
	}

}

?>