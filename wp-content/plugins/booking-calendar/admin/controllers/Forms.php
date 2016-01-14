<?php
class wpdevart_bc_ControllerForms {
	private $model;	
	private $view;	
	  
	public function __construct() {
		require_once(WPDEVART_PLUGIN_DIR . "/admin/models/Forms.php");
		$this->model = new wpdevart_bc_ModelForms();
		require_once(WPDEVART_PLUGIN_DIR . "/admin/views/Forms.php");
		$this->view = new wpdevart_bc_ViewForms($this->model);
	}  	
		
	public function perform() {
		$task = wpdevart_bc_Library::get_value('task');
		$id = wpdevart_bc_Library::get_value('id', 0);
		if (method_exists($this, $task)) {
		  $this->$task($id);
		}
		else {
		  $this->display_forms();
		}
	}
	  
	private function display_forms($error_msg="",$delete=true){
		$this->view->display_forms($error_msg,$delete);
	}  
	  
	private function add(){
		$this->view->edit_form();
	}
	  
	private function edit( $id ){
		$this->view->edit_form( $id );
	}
	  
	private function save( $id ){
		global $wpdb;
		$data_json = json_encode(array_slice($_POST, 1, -2));
		$title = (isset($_POST['title']) ? esc_html(stripslashes( $_POST['title'])) : '');
		if ($id != 0) {
		  $save = $wpdb->update($wpdb->prefix . 'wpdevart_forms', array(
			'title' => $title,
			'data' => $data_json,
		  ), array('id' => $id));
		}
		else {
		  $save = $wpdb->insert($wpdb->prefix . 'wpdevart_forms', array(
			'title' => $title,                       
			'data' => $data_json,         
		  ), array(
			'%s',
			'%s',
		  ));
		  $id = $wpdb->get_var('SELECT MAX(id) FROM ' . $wpdb->prefix . 'wpdevart_forms');
		} 
		if(isset($_POST["save"])) {
			$this->display_forms();
		}
		else {
			$this->edit($id);
		}
	}
	  
	private function delete( $id ){
		global $wpdb; 
		$error_msg = "";
		$delete = true;
		$exists = $this->model->check_exists( $id );
		if($exists === false) {
			$del_query = $wpdb->query($wpdb->prepare( 'DELETE FROM ' . $wpdb->prefix . 'wpdevart_forms WHERE id="%d"',$id ));
			if($del_query) {
				$error_msg = "Item succesfully deleted.";
			}
		} else {
			$error_msg = "You can't delete form which in use";
			$delete = false;
		}
		$this->display_forms($error_msg,$delete);
	}
	  
	private function delete_selected(){
		global $wpdb; 
		$error_msg = "";
		$delete = true;
		$check_for_action = (isset($_POST['check_for_action']) ? ( $_POST['check_for_action']) : '');
		foreach($check_for_action as $check){
			$exists = $this->model->check_exists( $check );
			if($exists === false) {
				$del_query = $wpdb->query($wpdb->prepare( 'DELETE FROM ' . $wpdb->prefix . 'wpdevart_forms WHERE id="%d"',$check ));
				if($del_query) {
					$error_msg = "Items succesfully deleted.";
				}
			} else {
				$error_msg = "You can't delete form which in use";
				$delete = false;
			}
		}
		$this->display_forms($error_msg,$delete);
	}
	 
 
  
}

?>