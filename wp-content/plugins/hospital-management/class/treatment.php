<?php 
//$user = new WP_User($user_id);
	  
class Hmgt_treatment
{	

	//Medicine Category
	public function hmgt_add_treatment($data)
	{
		global $wpdb;
		$table_treatment = $wpdb->prefix. 'hmgt_treatment';
		//-------usersmeta table data--------------
		$treatmentdata['treatment_name']=$data['treatment_name'];
		$treatmentdata['treatment_price']=$_POST['treatment_price'];
		$treatmentdata['treat_create_Date']=date("Y-m-d");
		$treatmentdata['treat_create_by']=get_current_user_id();
		
		
		if($data['action']=='edit')	
		{
			$treatmentid['treatment_id']=$data['treatment_id'];			
			$result=$wpdb->update( $table_treatment, $treatmentdata ,$treatmentid);
			hmgt_append_audit_log('Update treatment ',get_current_user_id());
			return $result;
		}
		else
		{
			$result=$wpdb->insert( $table_treatment, $treatmentdata );	
			hmgt_append_audit_log('Add new treatment  ',get_current_user_id());
			return $result;			
		}
		
	}
	
	public function get_all_treatment()
	{
		global $wpdb;
		$table_treatment = $wpdb->prefix. 'hmgt_treatment';
		
		$result = $wpdb->get_results("SELECT * FROM $table_treatment");
		return $result;
		
	}
	public function get_treatment_name($treatment_id)
	{
		global $wpdb;
		$table_treatment = $wpdb->prefix. 'hmgt_treatment';
		
		$result = $wpdb->get_var("SELECT treatment_name FROM $table_treatment where treatment_id= ".$treatment_id);
		return $result;
	}
	public function get_single_session($session_id)
	{
		global $wpdb;
		$table_treatment = $wpdb->prefix. 'hmgt_sessions';
		$result = $wpdb->get_row("SELECT * FROM $table_treatment where treatment_id= ".$session_id);
		return $result;
	}
	public function delete_treatment($treatment_id)
	{
		global $wpdb;
		$table_treatment = $wpdb->prefix. 'hmgt_treatment';
		$result = $wpdb->query("DELETE FROM $table_treatment where treatment_id= ".$treatment_id);
		hmgt_append_audit_log('Delete treatment  ',get_current_user_id());
		return $result;
	}
	
	
	
}
?>