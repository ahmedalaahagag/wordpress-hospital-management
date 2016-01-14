<?php 
//$user = new WP_User($user_id);
	  
class Hmgt_report
{	

	//Medicine Category
	public function hmgt_add_report($data)
	{
		global $wpdb;
		$table_hmgt_report = $wpdb->prefix. 'hmgt_report';
		//-------usersmeta table data--------------
		$reportdata['patient_id']=$data['patient_id'];
		$reportdata['report_type']=$data['report_type'];
		$reportdata['report_description']=$data['report_description'];
		$reportdata['report_date']=$data['report_date'];
		$reportdata['created_date']=date("Y-m-d");
		$reportdata['createdby']=get_current_user_id();
		
		
		if($data['action']=='edit')	
		{
			$reportdataid['rid']=$data['rid'];			
			$result=$wpdb->update( $table_hmgt_report, $reportdata ,$reportdataid);
			hmgt_append_audit_log('Add new report ',get_current_user_id());
			return $result;
		}
		else
		{
			$result=$wpdb->insert( $table_hmgt_report, $reportdata );			
			return $result;			
		}
		
	}
	
	public function get_all_report()
	{
		global $wpdb;
		$table_hmgt_report = $wpdb->prefix. 'hmgt_report';
		
		$result = $wpdb->get_results("SELECT * FROM $table_hmgt_report");
		return $result;
		
	}
	public function get_report_byreport_type($type)
	{
		global $wpdb;
		$table_hmgt_report = $wpdb->prefix. 'hmgt_report';
	
		$result = $wpdb->get_results("SELECT * FROM $table_hmgt_report WHERE report_type = '$type'");
		return $result;
	
	}
	
	public function get_single_report($rid)
	{
		global $wpdb;
		$table_hmgt_report = $wpdb->prefix. 'hmgt_report';
		$result = $wpdb->get_row("SELECT * FROM $table_hmgt_report where rid= ".$rid);
		return $result;
	}
	public function delete_report($rid)
	{
		global $wpdb;
		$table_hmgt_report = $wpdb->prefix. 'hmgt_report';
		$result = $wpdb->query("DELETE FROM $table_hmgt_report where rid= ".$rid);
		hmgt_append_audit_log('Delete report  ',get_current_user_id());
		return $result;
	}
	
	
	
}
?>