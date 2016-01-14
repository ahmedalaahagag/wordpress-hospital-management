<?php
class wpdevart_bc_ViewReservations {
	public $model_obj;
    	
    public function __construct( $model ) {
		$this->model_obj = $model;
    }	
    public function display_reservations($id=0) {
		
		if(!isset($_SESSION["clendar_id"])) {
			$_SESSION["clendar_id"] = 0;
		}
		if(isset($_POST["clendar_id"])) {
			$_SESSION["clendar_id"] = $_POST["clendar_id"];
		}
		$rows = $this->model_obj->get_reservations_rows($id);
		$calendar_rows = $this->model_obj->get_calendar_rows();
		$theme_options = $this->model_obj->get_theme_rows($_SESSION["clendar_id"]);
		$items_nav = $this->model_obj->items_nav($id);
		$asc_desc = ((isset($_POST['asc_desc']) && $_POST['asc_desc'] == 'asc') ? 'asc' : 'desc');
		$res_order_by = (isset($_POST['order_by']) ? esc_html($_POST['order_by']) :  'id');
		$res_order_class = 'sorted ' . $asc_desc; ?>
		<div id="wpdevart_reservations_container" class="wpdevart-list-container list-view">
		<form action="admin.php?page=wpdevart-reservations" method="post" id="reservations_form">
			<div id="action-buttons" class="div-for-clear">
				<div id="reservation_header" class="div-for-clear">
					<div class="div-for-clear">
						<span class="admin_logo"></span>
						<h1>Reservations List View <a href="http://wpdevart.com/wordpress-booking-calendar-plugin/"><span class="pro_feature"> (Upgrade to Pro Version)</span></a></h1>
					</div>
					<select name="clendar_id" onchange="this.form.submit()">
						<option value='0'>Select Calendar</option>
						<?php foreach($calendar_rows as $calendar_row) {
							echo "<option value='".$calendar_row["id"]."' ".selected($_SESSION["clendar_id"], $calendar_row["id"]).">".$calendar_row["title"]."</option>";
						} ?>
					</select>
					<span id="view_list"><span class="reservation-item-info">Reservation List View</span></span>
					<span id="view_calendar" class="pro-field"><span class="reservation-item-info">Reservation Month View<span class="pro_feature">(Pro Feature!)</span></span></span>
					<a id="add_reservation" href="" onclick="wpdevart_set_value('task','add'); wpdevart_form_submit(event, 'reservations_form')" class="add-reservation"><span class="plus">+</span><span class="reservation-item-info">Add Reservation</span></a>
				</div>
				<div id="resrv_action_filters">
					<div class="reserv_actions_filters_tabs div-for-clear">
						<div id="wpdevart_tab_1" class="wpdevart_tab show">
							<span>Actions</span>
						</div>
						<div id="wpdevart_tab_2" class="wpdevart_tab">
							<span>Filters</span>
						</div>
					</div>
					<div class="wpdevart_action_filters_container">
						<div id="wpdevart_tab_1_container" class="wpdevart_container show">
							<a href="" onclick="wpdevart_set_value('task','approve_selected'); wpdevart_form_submit(event, 'reservations_form')" class="action-button approve-button">Approve</a>
							<a href="" onclick="wpdevart_set_value('task','reject_selected');wpdevart_form_submit(event, 'reservations_form')" class="action-button reject-button">Reject</a>
							<a href="" onclick="wpdevart_set_value('task','canceled_selected');wpdevart_form_submit(event, 'reservations_form')" class="action-button cancel-button">Cancel</a>
							<a href="" onclick="wpdevart_set_value('task','delete_selected'); wpdevart_form_submit(event, 'reservations_form')" class="action-button delete-button">Delete</a>
						</div>
						<div id="wpdevart_tab_2_container" class="wpdevart_container div-for-clear">
						    <div class="filter_item status_filter_item">
								<label class="filter_item_label">Select Status</label>
								<div class="filter_fild_item">
									<input type="checkbox" name="reserv_status[]" id="res_approved" value="approved" <?php checked(isset($_POST["reserv_status"]) && in_array("approved",$_POST["reserv_status"])); ?>><label for="res_approved">Approved</label>
								</div>	
								<div class="filter_fild_item">
									<input type="checkbox" name="reserv_status[]" id="res_canceled" value="canceled" <?php checked(isset($_POST["reserv_status"]) && in_array("canceled",$_POST["reserv_status"])); ?>><label for="res_canceled">Canceled</label>
								</div>
								<div class="filter_fild_item">
									<input type="checkbox" name="reserv_status[]" id="res_rejected" value="rejected" <?php checked(isset($_POST["reserv_status"]) && in_array("rejected",$_POST["reserv_status"])); ?>><label for="res_rejected">Rejected</label>
								</div>	
								<div class="filter_fild_item">
									<input type="checkbox" name="reserv_status[]" id="res_pending" value="pending" <?php checked(isset($_POST["reserv_status"]) && in_array("pending",$_POST["reserv_status"])); ?>><label for="res_pending">Pending</label>
								</div>	
							</div>
							<div class="filter_item period_filter_item">
								<label class="filter_item_label">Period</label>
								<div class="filter_fild_item">
									<input type="text" name="reserv_period_start" value="<?php echo (isset($_POST["reserv_period_start"])? esc_js($_POST["reserv_period_start"]) : ""); ?>" class="admin_datepicker" placeholder="Check In">
								</div>
								<div class="filter_fild_item">
									<input type="text" name="reserv_period_end" value="<?php echo (isset($_POST["reserv_period_end"])? esc_js($_POST["reserv_period_end"]) : ""); ?>" class="admin_datepicker" placeholder="Check Out">
								</div>
							</div>
							<div class="filter_item searchs_filter_item">
								<label class="filter_item_label">Search</label>
								<div class="filter_fild_item">
									<input type="text" name="wpdevart_serch" value="<?php echo (isset($_POST["wpdevart_serch"])? esc_js($_POST["wpdevart_serch"]) : ""); ?>">
								</div>
							</div>
							
							<input type="submit" value="Applay" class="action-link">
						</div>
					</div>
				</div>
			</div>
			<?php
			if(isset($_SESSION["clendar_id"]) && $_SESSION["clendar_id"] != 0) {
				wpdevart_bc_Library::items_nav($items_nav['limit'],$items_nav['total'],'reservations_form');
			}	?>			
			<table class="wp-list-table widefat fixed pages wpdevart-table wpdevart-reservations-table"> 
				<tr>
					<thead>
						<th class="check-column"><input type="checkbox" name="check_all" onclick="check_all_checkboxes(this,'check_for_action');"></th>
						<th class="small-column <?php echo (($res_order_by == 'id')? $res_order_class : ""); ?>"><a onclick="wpdevart_set_value('order_by', 'id'); wpdevart_set_value('asc_desc', '<?php echo (($res_order_by == 'id' && $asc_desc == 'asc') ? 'desc' : 'asc'); ?>');wpdevart_form_submit(event, 'reservations_form')" href=""><span>ID</span><span class="sorting-indicator"></span></a></th>
						<th class="average-column <?php echo (($res_order_by == 'status')? $res_order_class : ""); ?>"><a onclick="wpdevart_set_value('order_by', 'status'); wpdevart_set_value('asc_desc', '<?php echo (($res_order_by == 'status' && $asc_desc == 'asc') ? 'desc' : 'asc'); ?>');wpdevart_form_submit(event, 'reservations_form')" href=""><span>Status</span><span class="sorting-indicator"></span></a></th>
						<th>Reservation information</th>
						<th class="medium-column">Reservation dates</th>
						<th class="medium-column">Actions</th>
					</thead>
				<tr>
				<?php
				if(isset($_SESSION["clendar_id"]) && $_SESSION["clendar_id"] != 0) {
					foreach ( $rows as $row ) {
                        $form_data = $this->model_obj->get_form_data($row->form);
                        $extras_data = $this->model_obj->get_extra_data($row);
						if(isset($theme_options["date_format"]) && $theme_options["date_format"] != "") {
							$date_format = $theme_options["date_format"];
						} else {
							$date_format = "F d, Y";
						}
						if($row->check_in) {
							$check_in = date($date_format, strtotime($row->check_in));
							$check_out = date($date_format, strtotime($row->check_out));
							$day_count = abs($this->get_date_diff($row->check_in,$row->check_out)) + 1;
						} else {
							$single_day = date($date_format, strtotime($row->single_day));
							$day_count = 1;
						} ?>
						
						<tr>
							<td><input type="checkbox" name="check_for_action[]" class="check_for_action" value="<?php echo $row->id; ?>"></td>
							<td><?php echo $row->id; ?></td>
							<td><span class="reserv_status reserv_status_<?php echo $row->status; ?>"><?php echo $row->status; ?><span></td>
							<td>
							<div class="reserv-info div-for-clear">
								<div class='reserv-info-container'>
									<h5>Details</h5>
									<span class='form_info'><span class='form_label'>Item Count</span> <span class='form_value'><?php echo $row->count_item; ?></span></span>
									<span class='form_info'><span class='form_label'>Price</span> <span class='form_value'><?php echo $row->price.$row->currency; ?></span></span>
									<span class='form_info'><span class='form_label'>Total Price</span> <span class='form_value'><?php echo $row->total_price.$row->currency; ?></span></span>
								</div>
								<span class="reserv-info-open"></span>
							</div>
							<div class="reserv-info-items div-for-clear">
								<?php
								$reserv_info = "";
								if(count($form_data)) {
									$reserv_info .= "<div class='reserv-info-container'>";
									$reserv_info .= "<h5>Contact Information</h5>";
									foreach($form_data as $form_fild_data) {
										$reserv_info .= "<span class='form_info'><span class='form_label'>". $form_fild_data["label"] ."</span> <span class='form_value'>". $form_fild_data["value"] ."</span></span>";
									}
									$reserv_info .= "</div>";
								}
								if(count($extras_data)) {
									$reserv_info .= "<div class='reserv-info-container'>";
									$reserv_info .= "<h5>Extra Information</h5>";
									foreach($extras_data as $extra_data) {
										$reserv_info .= "<h6>".$extra_data["group_label"]."</h6>";
										$reserv_info .= "<span class='form_info'><span class='form_label'>". $extra_data["label"] ."</span>"; 
										$reserv_info .= "<span class='form_value'>";
										if($extra_data["price_type"] == "percent") {
											$reserv_info .= "<span class='price-percent'>".$extra_data["operation"].$extra_data["price_percent"]."%</span>";
											$reserv_info .= "<span class='price'>".$extra_data["operation"] . $extra_data["price"] .$row->currency."</span></span></span>";
										} else {
											$reserv_info .= "<span class='price'>".$extra_data["operation"] . ($extra_data["price"] * $day_count) .$row->currency."</span></span></span>";
										}
										
									}
									$reserv_info .= "<h6>Price change</h6>";
									$reserv_info .= "<span class='form_info'><span class='form_label'></span><span class='form_value'>+".$row->extras_price.$row->currency."</span>"; 
									$reserv_info .= "</div>";
								}
								echo $reserv_info;	 		
								?>
							</div>
							</td>
							<td>
							<?php
								if(isset($check_in) && isset($check_out)) {
									echo $check_in. "-" .$check_out;
								} else {
									echo $single_day;
								} ?></td>
							<td>
							<?php if($row->status == "pending" || $row->status == "canceled" || $row->status == "rejected") { ?>
								<a href="" onclick="wpdevart_set_value('task','approve'); wpdevart_set_value('cur_id','<?php echo $row->id; ?>'); wpdevart_form_submit(event, 'reservations_form')" >Approve</a>
								<?php if($row->status == "pending") { ?>
									<a href="" onclick="wpdevart_set_value('task','reject'); wpdevart_set_value('cur_id','<?php echo $row->id; ?>'); wpdevart_form_submit(event, 'reservations_form')" >Reject</a>
								<?php  } ?>
							<?php } elseif($row->status == "approved") { ?>
								<a href="" onclick="wpdevart_set_value('task','canceled'); wpdevart_set_value('cur_id','<?php echo $row->id; ?>'); wpdevart_form_submit(event, 'reservations_form')" >Cancel</a>
							<?php  } ?>
							<a href="" onclick="wpdevart_set_value('task','delete'); wpdevart_set_value('cur_id','<?php echo $row->id; ?>'); wpdevart_form_submit(event, 'reservations_form')" >Delete</a></td>
						<tr>
				<?php	}
				 } ?>
			</table>
			
			<input type="hidden" name="task" id="task" value="">
			<input type="hidden" name="id" id="cur_id" value="">
			<input type="hidden" name="order_by" id="order_by" value="<?php echo (isset($_POST['order_by']))? esc_html($_POST['order_by']) : ""; ?>"/>
			<input type="hidden" name="asc_desc" id="asc_desc" value="<?php echo (isset($_POST['asc_desc']))? esc_html($_POST['asc_desc']) : ""; ?>"/>
			<?php if(isset($_SESSION["clendar_id"]) && $_SESSION["clendar_id"] != 0) {
				wpdevart_bc_Library::items_nav($items_nav['limit'],$items_nav['total'],'reservations_form');
			}	?>	
		</form>
	</div>
<?php
	}  
	
    public function display_month_reservations() {
		
		if(!isset($_SESSION["clendar_id"])) {
			$_SESSION["clendar_id"] = 0;
		}
		if(isset($_POST["clendar_id"])) {
			$_SESSION["clendar_id"] = $_POST["clendar_id"];
		}
		$calendar_rows = $this->model_obj->get_calendar_rows();  ?>
		<div id="wpdevart_reservations_container" class="wpdevart-list-container month-view">
		<form action="admin.php?page=wpdevart-reservations" method="post" id="reservations_form">
			<div id="action-buttons" class="div-for-clear">
				<div id="reservation_header" class="div-for-clear">
					<div class="div-for-clear">
						<span class="admin_logo"></span>
						<h1>Reservations Month View</h1>
					</div>
					<select name="clendar_id" onchange="this.form.submit()">
						<option value='0'>Select Calendar</option>
						<?php foreach($calendar_rows as $calendar_row) {
							echo "<option value='".$calendar_row["id"]."' ".selected($_SESSION["clendar_id"], $calendar_row["id"]).">".$calendar_row["title"]."</option>";
						} ?>
					</select>
					<a id="view_list" href="" onclick="wpdevart_set_value('task','display_reservations'); wpdevart_form_submit(event, 'reservations_form')"><span class="reservation-item-info">Reservation List View</span></a>
					<span id="view_calendar"><span class="reservation-item-info">Reservation Month View</span></span>
					<a id="add_reservation" href="" onclick="wpdevart_set_value('task','add'); wpdevart_form_submit(event, 'reservations_form')" class="add-reservation"><span class="plus">+</span><span class="reservation-item-info">Add Reservation</span></a>
				</div>
				
			</div>
			<div class="wpdevart_res_month_view">
				<?php
				if(isset($_SESSION["clendar_id"]) && $_SESSION["clendar_id"] != 0) {
					$booking_obg = new wpdevart_bc_calendar();
					$result = $booking_obg->wpdevart_booking_calendar_res($_SESSION["clendar_id"]);			
					echo $result;
				} ?>
			</div>
			<input type="hidden" name="task" id="task" value="">
			<input type="hidden" name="id" id="cur_id" value="">
		</form>
	</div>
<?php
	}  
	
	public function add() {
		if(!isset($_SESSION["clendar_id"])) {
			$_SESSION["clendar_id"] = 0;
		}
		if(isset($_POST["clendar_id"])) {
			$_SESSION["clendar_id"] = $_POST["clendar_id"];
		}
		
		$calendar_rows = $this->model_obj->get_calendar_rows(); ?>
		<div id="wpdevart_add_reservations_container"  class="wpdevart-list-container">
			<form action="admin.php?page=wpdevart-reservations" method="post" id="reservations_form">
				<div id="action-buttons" class="div-for-clear">
					<div id="reservation_header" class="div-for-clear">
						<div class="div-for-clear">
							<span class="admin_logo"></span>
							<h1>Add Reservation <a href="http://wpdevart.com/wordpress-booking-calendar-plugin/"><span class="pro_feature"> (Upgrade to Pro Version)</span></a></h1>
						</div>
						<select name="clendar_id" onchange="this.form.submit()">
							<option value='0'>Select Calendar</option>
							<?php foreach($calendar_rows as $calendar_row) {
								echo "<option value='".$calendar_row["id"]."' ".selected($_SESSION["clendar_id"], $calendar_row["id"]).">".$calendar_row["title"]."</option>";
							} ?>
						</select>
						<a id="view_list" href="" onclick="wpdevart_set_value('task','display_reservations'); wpdevart_form_submit(event, 'reservations_form')"><span class="reservation-item-info">Reservation List View</span></a>
						<span id="view_calendar" class="pro-field"><span class="reservation-item-info">Reservation Month View<span class="pro_feature">(Pro Feature!)</span></span></span>
						<span id="add_reservation" class="add-reservation"><span class="plus">+</span><span class="reservation-item-info">Add Reservation</span></span>
					</div>
					<input type="hidden" name="task" id="task" value="add">
					
				</div>
			</form>
			<div class="wpdevart_add_res">
				<?php
				if(isset($_SESSION["clendar_id"]) && $_SESSION["clendar_id"] != 0) {
					$booking_obg = new wpdevart_bc_calendar();
					$result = $booking_obg->wpdevart_booking_calendar($_SESSION["clendar_id"]);
					echo $result;
				} ?>
			</div>
				
		</div>
	<?php	
	}

	private function get_date_diff($date1, $date2) {
		$start = strtotime($date1);
		$end = strtotime($date2);
		$datediff = $start - $end;
		return floor($datediff/(60*60*24));
	}
 
  
}

?>