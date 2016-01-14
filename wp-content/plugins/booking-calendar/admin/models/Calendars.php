<?php
class wpdevart_bc_ModelCalendars {
	
  public function get_calendars_rows() {
    global $wpdb;
    $limit = (isset($_POST['page']) && $_POST['page'])? (((int) $_POST['page'] - 1) * 20) : 0;
    $order_by = ((isset($_POST['order_by']) && $_POST['order_by'] != "") ? esc_html($_POST['order_by']) :  'id');
	$order = ((isset($_POST['asc_desc']) && $_POST['asc_desc'] == 'asc') ? 'asc' : 'desc');
    $order_by = ' ORDER BY `' . $order_by . '` ' . $order;
    $where = ((isset($_POST['search_value']) && (esc_html($_POST['search_value']) != '')) ? 'WHERE title LIKE "%' . esc_html($_POST['search_value']) . '%"' : '');
	
    $query = "SELECT * FROM " . $wpdb->prefix . "wpdevart_calendars " . $where . " ".$order_by." LIMIT " . $limit . ",20";
    $rows = $wpdb->get_results($query);
   
    return $rows;
  }	
  
  public function get_calendar_rows( $id ) {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wpdevart_calendars WHERE id="%d"', $id),ARRAY_A);
   
    return $row;
  }
  public function items_nav() {
    global $wpdb;
    $where = ((isset($_POST['search_value']) && (esc_html($_POST['search_value']) != '')) ? 'WHERE title LIKE "%' . esc_html($_POST['search_value']) . '%"'  : '');
    $total = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."wpdevart_calendars " .$where);
    $items_nav['total'] = $total;
    if (isset($_POST['page']) && $_POST['page']) {
      $limit = ((int)$_POST['page'] - 1) * 20;
    }
    else {
      $limit = 0;
    }
    $items_nav['limit'] = (int)($limit / 20 + 1);
    return $items_nav;
  }

  
  public function get_ids( $id ) {
    global $wpdb;
    $result = $wpdb->get_row($wpdb->prepare('SELECT theme_id,form_id,extra_id FROM ' . $wpdb->prefix . 'wpdevart_calendars WHERE id="%d"', $id),ARRAY_A);
   
    return $result;
  }
  
  
  public function get_db_days( $id ) {
    global $wpdb;
    $row = $wpdb->get_results($wpdb->prepare('SELECT unique_id FROM ' . $wpdb->prefix . 'wpdevart_dates WHERE calendar_id="%d"', $id),ARRAY_A);

    return $row;
  }
  
  public function get_db_days_data( $id ) {
    global $wpdb;
    $row = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wpdevart_dates WHERE calendar_id="%d"', $id),ARRAY_A);

    return $row;
  }
  
  public function get_setting_rows() {
    global $wpdb;
    $row = $wpdb->get_results('SELECT id, title FROM ' . $wpdb->prefix . 'wpdevart_themes',ARRAY_A);
   
    return $row;
  }
  
  public function get_setting_row($id) {
    global $wpdb;
    $row = $wpdb->get_var($wpdb->prepare('SELECT value FROM ' . $wpdb->prefix . 'wpdevart_themes WHERE id="%d"',$id));
    $theme_info = json_decode($row, true);
	
    return $theme_info;
  }
  
   public function get_form_rows() {
    global $wpdb;
    $row = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'wpdevart_forms',ARRAY_A);
   
    return $row;
  } 
  
  public function get_extra_rows() {
    global $wpdb;
    $row = $wpdb->get_results('SELECT id, title FROM ' . $wpdb->prefix . 'wpdevart_extras',ARRAY_A);
    return $row;
  }
 
  
}

?>