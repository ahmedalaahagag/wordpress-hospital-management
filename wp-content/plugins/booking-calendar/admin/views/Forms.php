<?php
class wpdevart_bc_Viewforms {
	public $model_obj;
    	
    public function __construct( $model ) {
		$this->model_obj = $model;
    }	
    public function display_forms($error_msg="",$delete=true) {
		$rows = $this->model_obj->get_forms_rows();
		$items_nav = $this->model_obj->items_nav();
		$asc_desc = ((isset($_POST['asc_desc']) && $_POST['asc_desc'] == 'asc') ? 'asc' : 'desc');
		$res_order_by = (isset($_POST['order_by']) ? esc_html($_POST['order_by']) :  'id');
		$res_order_class = 'sorted ' . $asc_desc; ?>
		<div id="wpdevart_forms_container" class="wpdevart-list-container">
			<div id="action-buttons" class="div-for-clear">
				<div class="div-for-clear">
					<span class="admin_logo"></span>
					<h1>Forms <a href="http://wpdevart.com/wordpress-booking-calendar-plugin/"><span class="pro_feature"> (Upgrade to Pro Version)</span></a></h1>
				</div>
				<a href="" onclick="wpdevart_set_value('task','add'); wpdevart_form_submit(event, 'forms_form')" class="action-link">Add Form</a>
				<a href="" onclick="wpdevart_set_value('task','delete_selected'); wpdevart_form_submit(event, 'forms_form')" class="action-link delete-link">Delete</a>
			</div>
			<?php if(isset($error_msg) && $error_msg != "") {
				$class = "error";
				if($delete === true) {
					$class = "updated";
				} ?>
				<div id="message" class="<?php echo $class; ?> notice is-dismissible"><p><?php echo $error_msg; ?></p></div>
			<?php } ?>
			<form action="admin.php?page=wpdevart-forms" method="post" id="forms_form">
			<?php wpdevart_bc_Library::items_nav($items_nav['limit'],$items_nav['total'],'forms_form'); ?>
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
								<td><a href="" onclick="wpdevart_set_value('task','edit'); wpdevart_set_value('cur_id','<?php echo $row->id; ?>'); wpdevart_form_submit(event, 'forms_form')" ><?php echo $row->title; ?></a></td>
								<td><a href="" onclick="wpdevart_set_value('task','edit'); wpdevart_set_value('cur_id','<?php echo $row->id; ?>'); wpdevart_form_submit(event, 'forms_form')" >Edit</a></td>
								<td><a href="" onclick="wpdevart_set_value('task','delete'); wpdevart_set_value('cur_id','<?php echo $row->id; ?>'); wpdevart_form_submit(event, 'forms_form')" >Delete</a></td>
							<tr>
					<?php	}
					?>
				</table>
				<input type="hidden" name="task" id="task" value="">
				<input type="hidden" name="id" id="cur_id" value="">
				<?php wpdevart_bc_Library::items_nav($items_nav['limit'],$items_nav['total'],'forms_form'); ?>
			</form>
		</div>
    <?php }
	
    public function edit_form( $id = 0 ) { 
	    
		$wpdevart_forms = array(
			'form_field1' => array(
				'name'   => 'form_field1',
				'label' => __( 'First Name', 'booking-calendar' ),
				'type' => 'text',
				'default' => ''
			),
			'form_field2' => array(
				'name'   => 'form_field2',
				'label' => __( 'Last Name', 'booking-calendar' ),
				'type' => 'text',
				'default' => ''
			),
			'form_field3' => array(
				'name'   => 'form_field3',
				'label' => __( 'Email', 'booking-calendar' ),
				'type' => 'text',
				'isemail' => 'on',
				'default' => ''
			),
			'form_field4' => array(
				'name'   => 'form_field4',
				'label' => __( 'Phone', 'booking-calendar' ),
				'type' => 'text',
				'default' => ''
			),
			'form_field5' => array(
				'name'   => 'form_field5',
				'label' => __( 'Message', 'booking-calendar' ),
				'type' => 'textarea',
				'default' => ''
			)
		);
		if($id != 0){
			$form_rows = $this->model_obj->get_form_rows( $id );
			$value = json_decode( $form_rows->data, true );
			$wpdevart_forms = $value;
			$last_element = end($wpdevart_forms);
			$max_id = str_replace('form_field', '', $last_element['name']);
		} 
		else {
			$max_id = 5;
		}
		?>
		<div id="wpdevart_forms" class="wpdevart-item-container wpdevart-main-item-container">
		    <?php
			    if($id != 0){ ?>
					<div class="div-for-clear">
						<span class="admin_logo"></span>
						<h1>Edit Form <a href="http://wpdevart.com/wordpress-booking-calendar-plugin/"><span class="pro_feature"> (Upgrade to Pro Version)</span></a></h1>
					</div>
				<?php } else { ?>
					<div class="div-for-clear">
						<span class="admin_logo"></span>
						<h1>Edit Form <a href="http://wpdevart.com/wordpress-booking-calendar-plugin/"><span class="pro_feature"> (Upgrade to Pro Version)</span></a></h1>
					</div>
				<?php } ?>
			<form action="?page=wpdevart-forms" method="post">
				<div id="wpdevart_wpdevart-item_title">
					<span>Form Name</span> <input type="text" name="title" value="<?php if(isset($form_rows->title)) echo esc_attr($form_rows->title); ?>">
					<input type="submit" value="Save" class="action-link wpda-input" name="save">
					<input type="submit" value="Apply" class="action-link wpda-input" name="apply">
					<div id="add_field_container">
						<div id="add_field">
						</div>
						<div id="form_field_type" data-max="<?php echo $max_id; ?>">
							<span id="text_field">Text</span>
							<span id="textarea_field">Textarea</span>
							<span id="checkbox_field">Checkbox</span>
							<!--<span id="radio_field">Radio</span>-->
							<span id="select_field">Drop down</span>
						</div>
					</div>
				</div>
				<?php
				   ?>
					<div class="wpdevart-item-section"> 
						<h3>Form fields</h3>
						<div class="wpdevart-item-section-cont">
							<?php
							foreach( $wpdevart_forms as $key => $wpdevart_form ) {
								$sett_value = $wpdevart_forms[$key];
								if(isset($wpdevart_form['type'])) {
									$function_name = "wpdevart_form_" . $wpdevart_form['type'];
									if(method_exists("wpdevart_bc_Library",$function_name)) {
										wpdevart_bc_Library::$function_name($wpdevart_form, $sett_value);
									}
								}
							} ?>
							<div id="new_fieds">
							</div>	
						</div>	
					</div>	
				<input type="hidden" name="task" value="save">
				<input type="hidden" name="id" value="<?php echo $id; ?>">
				
			</form>
		</div>
	<?php	
	}
  

 
 
  
}

?>