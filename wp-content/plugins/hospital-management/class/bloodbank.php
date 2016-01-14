<?php 
class Hmgtbloodbank
{
	public function hmgt_add_blood_donor($data)
	{
		global $wpdb;
		$table_blooddonor=$wpdb->prefix. 'hmgt_bld_donor';
		$donordata['donor_name']=$data['bool_dodnor_name'];
		$donordata['donor_gender']=$data['gender'];
		$donordata['donor_age']=$data['dodnor_age'];
		$donordata['donor_phone']=$data['phone'];
		$donordata['donor_email']=$data['email'];
		$donordata['blood_group']=$data['blood_group'];
		$donordata['last_donet_date']=$data['last_donate_date'];
		$donordata['donor_create_date']=date("Y-m-d");
		$donordata['donor_create_by']=get_current_user_id();
		
		if($data['action']=='edit')
		{
			$donor_dataid['bld_donor_id']=$data['blooddonor_id'];
			$result=$wpdb->update( $table_blooddonor, $donordata ,$donor_dataid);
			hmgt_append_audit_log('Update boold doner ',get_current_user_id());
			return $result;
		}
		else
		{
			$result=$wpdb->insert( $table_blooddonor,$donordata);
			hmgt_append_audit_log('Add new blood doner ',get_current_user_id());
			return $result;
		}
	}
	public function get_all_blooddonors()
	{
		global $wpdb;
		$table_blooddonor=$wpdb->prefix. 'hmgt_bld_donor';
		
		$result = $wpdb->get_results("SELECT * FROM $table_blooddonor");
		return $result;
		
	}
	public function delete_blooddonor($blooddonor_id)
	{
		global $wpdb;
		$table_blooddonor=$wpdb->prefix. 'hmgt_bld_donor';
		$result = $wpdb->query("DELETE FROM $table_blooddonor where bld_donor_id= ".$blooddonor_id);
		return $result;
	}
	public function get_single_blooddonor($donor_id)
	{
		global $wpdb;
		$table_blooddonor=$wpdb->prefix. 'hmgt_bld_donor';
	
		$result = $wpdb->get_row("SELECT * FROM $table_blooddonor where bld_donor_id= ".$donor_id);
		return $result;
	}
	public function add_blood_group($data)
	{
		global $wpdb;
		$table_bloodbank=$wpdb->prefix. 'hmgt_blood_bank';
		$blooddata['blood_group']=$data['blood_group'];
		$blooddata['blood_status']=$data['blood_status'];
		
		if($data['action']=='edit')
		{
			$blood_dataid['blood_id']=$data['bloodgroup_id'];
			$result=$wpdb->update( $table_bloodbank, $blooddata ,$blood_dataid);
			hmgt_append_audit_log('Update boold group detail ',get_current_user_id());
			return $result;
		}
		else
		{
			$result=$wpdb->insert( $table_bloodbank,$blooddata);
			hmgt_append_audit_log('Add new blood group ',get_current_user_id());
			return $result;
		}
		
	}
	public function get_single_bloodgroup($blood_id)
	{
		global $wpdb;
		$table_bloodbank=$wpdb->prefix. 'hmgt_blood_bank';
	
		$result = $wpdb->get_row("SELECT * FROM $table_bloodbank where blood_id= ".$blood_id);
		return $result;
	}
	public function delete_bloodgroup($blood_id)
	{
		global $wpdb;
		$table_bloodbank=$wpdb->prefix. 'hmgt_blood_bank';
		$result = $wpdb->query("DELETE FROM $table_bloodbank where blood_id= ".$blood_id);
		hmgt_append_audit_log('Delete blood group ',get_current_user_id());
		return $result;
	}
	public function get_all_bloodgroups()
	{
		global $wpdb;
	$table_bloodbank=$wpdb->prefix. 'hmgt_blood_bank';
		
		$result = $wpdb->get_results("SELECT * FROM $table_bloodbank");
		return $result;
		
	}
}
?>