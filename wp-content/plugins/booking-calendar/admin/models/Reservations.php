<?php
class wpdevart_bc_ModelReservations {
	
  public function get_reservations_rows($id) {
    global $wpdb;
	$where = array();
    $limit = (isset($_POST['wpdevart_page']) && $_POST['wpdevart_page'])? (((int) $_POST['wpdevart_page'] - 1) * 20) : 0;

    if(isset($_POST['reserv_status']) && count($_POST['reserv_status']) != 0){
		$reserv_status = implode("','",$_POST['reserv_status']);
		$where[] = ' status IN (' . stripslashes("'".$reserv_status."'") . ')';
	}
    if(isset($_POST['wpdevart_serch']) && (esc_html($_POST['wpdevart_serch']) != ''))
		$where[] = ' form LIKE "%' . esc_html($_POST['wpdevart_serch']) . '%"';
	if(isset($_SESSION["clendar_id"]) && (esc_html($_SESSION["clendar_id"]) != 0))
		$where[] = ' calendar_id=' . esc_html($_SESSION["clendar_id"]);
	if((isset($_POST["reserv_period_start"]) && (esc_html($_POST["reserv_period_start"]) != 0)) && (isset($_POST["reserv_period_end"]) && (esc_html($_POST["reserv_period_end"]) != 0))) {
		$where[] = ' (single_day BETWEEN "'.(esc_html($_POST["reserv_period_start"])).'" AND "'.(esc_html($_POST["reserv_period_end"])).'" OR check_in BETWEEN "'.(esc_html($_POST["reserv_period_start"])).'" AND "'.(esc_html($_POST["reserv_period_end"])).'")';
	}
	if($id != 0) {
		$where[] = ' id= '.esc_html($id).'';
	}
	$where = implode(" AND ",$where);
	if($where != '') {
		$where = "WHERE ". $where;
	}	
    $reserv_order_by = ((isset($_POST['order_by']) && $_POST['order_by'] != "") ? esc_html($_POST['order_by']) :  'id');
	$reserv_order = ((isset($_POST['asc_desc']) && $_POST['asc_desc'] == 'asc') ? 'asc' : 'desc');
	
    $reserv_order_by = ' ORDER BY `' . $reserv_order_by . '` ' . $reserv_order;

    $query = "SELECT * FROM " . $wpdb->prefix . "wpdevart_reservations " . $where . " ".$reserv_order_by." LIMIT " . $limit . ",20";
    $rows = $wpdb->get_results($query);

    return $rows;
  }	
  
  public function items_nav($id = 0) {
    global $wpdb;
    $where = array();
    $limit = (isset($_POST['wpdevart_page']) && $_POST['wpdevart_page'])? (((int) $_POST['wpdevart_page'] - 1) * 20) : 0;

    if(isset($_POST['reserv_status']) && count($_POST['reserv_status']) != 0){
		$reserv_status = implode("','",$_POST['reserv_status']);
		$where[] = ' status IN (' . stripslashes("'".$reserv_status."'") . ')';
	}
    if(isset($_POST['wpdevart_serch']) && (esc_html($_POST['wpdevart_serch']) != ''))
		$where[] = ' form LIKE "%' . esc_html($_POST['wpdevart_serch']) . '%"';
	if(isset($_SESSION["clendar_id"]) && (esc_html($_SESSION["clendar_id"]) != 0))
		$where[] = ' calendar_id=' . esc_html($_SESSION["clendar_id"]);
	if((isset($_POST["reserv_period_start"]) && (esc_html($_POST["reserv_period_start"]) != 0)) && (isset($_POST["reserv_period_end"]) && (esc_html($_POST["reserv_period_end"]) != 0))) {
		$where[] = ' (single_day BETWEEN "'.(esc_html($_POST["reserv_period_start"])).'" AND "'.(esc_html($_POST["reserv_period_end"])).'" OR check_in BETWEEN "'.(esc_html($_POST["reserv_period_start"])).'" AND "'.(esc_html($_POST["reserv_period_end"])).'")';
	}
	if($id != 0) {
		$where[] = ' id= '.esc_html($id).'';
	}
	$where = implode(" AND ",$where);
	if($where != '') {
		$where = "WHERE ". $where;
	}	

    $total = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."wpdevart_reservations " .$where);
    $items_nav['total'] = $total;

    if (isset($_POST['wpdevart_page']) && $_POST['wpdevart_page']) {
      $limit = ((int)$_POST['wpdevart_page'] - 1) * 20;
    }
    else {
      $limit = 0;
    }
    $items_nav['limit'] = (int)($limit / 20 + 1);
    return $items_nav;
  }
  
  public function get_form_data($form) {
    global $wpdb;
	if($form) {
		$form_value = json_decode($form, true);
		$cal_id = 0;
		if(isset($_SESSION["clendar_id"]) && (esc_html($_SESSION["clendar_id"]) != 0))
			$cal_id = $_SESSION["clendar_id"];
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
  
  public function get_extra_data($extras,$mail="",$price=0) {
    global $wpdb;
	if($mail == "mail") {
		$extra = $extras;
		$price = $price;
	} else {
		$extra = $extras->extras;
		$price = $extras->price;
	}
	if($extra) {
		$extras_value = json_decode($extra, true);
		$cal_id = 0;
		if(isset($_SESSION["clendar_id"]) && (esc_html($_SESSION["clendar_id"]) != 0))
			$cal_id = $_SESSION["clendar_id"];
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
  
  public function get_calendar_rows() {
    global $wpdb;
    $row = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'wpdevart_calendars',ARRAY_A);
   
    return $row;
  }
  
  public function get_reservation_row( $id ) {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wpdevart_reservations WHERE id="%d"', $id),ARRAY_A);
   
    return $row;
  }
  
  public function get_date_data( $unique_id ) {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare('SELECT data FROM ' . $wpdb->prefix . 'wpdevart_dates WHERE unique_id="%s"', $unique_id),ARRAY_A);
    $date_info = $row["data"];
    return $date_info;
  }
  
  public function get_theme_rows( $cal_id ) {
	global $wpdb;
    $theme_id = $wpdb->get_var($wpdb->prepare('SELECT theme_id FROM ' . $wpdb->prefix . 'wpdevart_calendars WHERE id="%d"', $cal_id));
	$theme_rows = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wpdevart_themes WHERE id="%d"', $theme_id),ARRAY_A);
	if(isset($theme_rows[0])) {
		$them_options = json_decode($theme_rows[0]["value"],true);
	} else {
		$them_options = array();
	}
		
	return $them_options;
  }
  
 
  
}

?>