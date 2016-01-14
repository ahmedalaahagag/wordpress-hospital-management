<?php 
//$user = new WP_User($user_id);	  
class Hmgt_appointment
{	

	//Medicine Category
	public function hmgt_add_appointment($data)
	{
		global $wpdb;
		$table_appointment = $wpdb->prefix. 'hmgt_appointment';
		//-------usersmeta table data--------------
		$appointmentdata['appointment_time_string']=$data['appointment_date']." ".$data['appointment_time'].":00";
		$appointmentdata['patient_id']=$data['patient_id'];
		$appointmentdata['doctor_id']=$data['doctor_id'];
		$appointmentdata['appointment_date']=$data['appointment_date'];
		$appointmentdata['appointment_time']=$data['appointment_time'];
		
		$appointmentdata['appoint_create_date']=date("Y-m-d");
		$appointmentdata['appoint_create_by']=get_current_user_id();
		
		
		if($data['action']=='edit')	
		{
			$appointment_id['appointment_id']=$data['appointment_id'];			
			$result=$wpdb->update( $table_appointment, $appointmentdata ,$appointment_id);
			hmgt_append_audit_log('Update appointment ',get_current_user_id());
			return $result;
		}
		else
		{
			$result=$wpdb->insert( $table_appointment, $appointmentdata );			
			hmgt_append_audit_log('Add new appointment ',get_current_user_id());
			return $result;			
		}
		
	}
	
	public function get_all_appointment()
	{
		global $wpdb;
		$table_appointment = $wpdb->prefix. 'hmgt_appointment';		
		$result = $wpdb->get_results("SELECT * FROM $table_appointment");
		return $result;		
	}
	public function get_treatment_name($treatment_id)
	{
		global $wpdb;
		$table_treatment = $wpdb->prefix. 'hmgt_treatment';
		
		$result = $wpdb->get_var("SELECT treatment_name FROM $table_treatment where appointment_id= ".$treatment_id);
		return $result;
	}
	public function get_single_appointment($appointment_id)
	{
		global $wpdb;
		$table_appointment = $wpdb->prefix. 'hmgt_appointment';
		$result = $wpdb->get_row("SELECT * FROM $table_appointment where appointment_id= ".$appointment_id);
		return $result;
	}
	public function delete_appointment($appointment_id)
	{
		global $wpdb;
		$table_appointment = $wpdb->prefix. 'hmgt_appointment';
		$result = $wpdb->query("DELETE FROM $table_appointment where appointment_id= ".$appointment_id);
		hmgt_append_audit_log('Delete appointment ',get_current_user_id());
		return $result;
	}
	
	
	
}
?>