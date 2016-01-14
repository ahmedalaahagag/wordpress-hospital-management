<?php
//$user = new WP_User($user_id);

class Hmgt_packages
{

	//Medicine Category
	public function hmgt_add_package($data)
	{
		global $wpdb;
		$table_packages = $wpdb->prefix. 'hmgt_packages';
		$doctor_id = $data['doctor_id'];
		$patient_id = $data['patient_id'];
		$packagename = $data['package_name'];
		$package_id = $data['package_id']+1;
		if($data['action']=='edit')
		{
			$wpdb->query("DELETE FROM $table_packages WHERE package_id=$package_id-1");
		}
		for($i=0;$i<count($data['session_id']);$i++)
		{


			$packagedata['package_name'] = $data['package_id'];
			$packagedata['package_name']=$packagename;
			$packagedata['patient_id']=$patient_id;
			$packagedata['doctor_id']=$doctor_id;
			$packagedata['session_id']=$data['session_id'][$i];
			$packagedata['session_duration_id']=($data['duration_id'][$i]);
			$packagedata['from_date']=strtotime($data['from_date'][$i]);
			$packagedata['to_date']=strtotime($data['to_date'][$i]);
			if(array_key_exists('session_spent',$data))
			$packagedata['session_spent']=$data['session_spent'][$i];
			if(array_key_exists('session_paid',$data))
			$packagedata['session_paid']=$data['session_paid'][$i];
			$packagedata['create_Date']=date("Y-m-d");
			$packagedata['create_by']=get_current_user_id();

			$result=$wpdb->insert( $table_packages, $packagedata );
			print_r($wpdb->last_query);

		}
		return $result;
	}

	public function get_all_packages()
	{
		global $wpdb;
		$table_packages = $wpdb->prefix. 'hmgt_packages';
		$result = $wpdb->get_results("SELECT DISTINCT package_id,package_name FROM $table_packages ");
		return $result;

	}
	public function get_session_name($session_id)
	{
		global $wpdb;
		$table_session = $wpdb->prefix. 'hmgt_sessions';

		$result = $wpdb->get_var("SELECT session_name FROM $table_session where session_id= ".$session_id);
		return $result;
	}
	public function get_user_packages($user_id)
	{
		global $wpdb;
		$table_packages = $wpdb->prefix. 'hmgt_packages';
		$result = $wpdb->get_results("SELECT * FROM $table_packages where patient_id= ".$user_id);
		return $result;
	}
	public function get_single_session($session_id)
	{
		global $wpdb;
		$table_session = $wpdb->prefix. 'hmgt_sessions';
		$result = $wpdb->get_row("SELECT * FROM $table_session where session_id= ".$session_id);
		return $result;
	}
	public function delete_session($session_id)
	{
		global $wpdb;
		$table_session = $wpdb->prefix. 'hmgt_sessions';
		$result = $wpdb->query("DELETE FROM $table_session where session_id= ".$session_id);
		hmgt_append_audit_log('Delete session  ',get_current_user_id());
		return $result;
	}
	public function get_sessions($pacakge_id){
		global $wpdb;
		$table_packages = $wpdb->prefix. 'hmgt_packages';
		$table_session = $wpdb->prefix. 'hmgt_sessions';
		$result = $wpdb->get_results("SELECT * FROM $table_packages INNER JOIN $table_session ON ($table_session.session_id = $table_packages.session_id)");

	}
	public function get_last_id()
	{
		global $wpdb;
		$table_packages = $wpdb->prefix. 'hmgt_packages';
		$result = $wpdb->get_var("SELECT MAX(id) AS `maxid` FROM $table_packages");
	}
	public function get_single_package($package_id)
	{
		global $wpdb;
		$table_packages = $wpdb->prefix. 'hmgt_packages';
		$result = $wpdb->get_results("SELECT * FROM $table_packages WHERE package_id=$package_id");
		return $result;
	}
}
?>
