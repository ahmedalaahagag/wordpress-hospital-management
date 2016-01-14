<?php
//$user = new WP_User($user_id);

class Hmgt_session
{

	//Medicine Category
	public function hmgt_add_session($data)
	{
		global $wpdb;
		$table_sessions = $wpdb->prefix. 'hmgt_sessions';

		//-------usersmeta table data--------------//
		$sessiondata['session_name']=$data['session_name'];
		$sessiondata['session_type']=$data['session_type'];
		$sessiondata['discount_sessions_number']=$data['discount_sessions_number'];
		$sessiondata['discount_sessions_percentage']=$data['discount_sessions_percentage'];
		$sessiondata['session_create_Date']=date("Y-m-d");
		$sessiondata['session_create_by']=get_current_user_id();


		if($data['action']=='edit')
		{
			$sessionid['session_id']=$data['session_id'];
			$result=$wpdb->update( $table_sessions, $sessiondata ,$sessionid);
			hmgt_append_audit_log('Update session ',get_current_user_id());
			return $result;
		}
		else
		{
			$result=$wpdb->insert( $table_sessions, $sessiondata );
			hmgt_append_audit_log('Add new session  ',get_current_user_id());
			return $result;
		}

	}

	public function get_all_session()
	{
		global $wpdb;
		$table_session = $wpdb->prefix. 'hmgt_sessions';
		$result = $wpdb->get_results("SELECT * FROM $table_session");
		return $result;

	}
	public function get_session_name($session_id)
	{
		global $wpdb;
		$table_session = $wpdb->prefix. 'hmgt_sessions';

		$result = $wpdb->get_var("SELECT session_name FROM $table_session where session_id= ".$session_id);
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
}
?>
