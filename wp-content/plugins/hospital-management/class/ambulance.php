<?php 	  
class Hmgt_ambulance
{	

	//Medicine Category
	public function hmgt_add_ambulance($data)
	{
		global $wpdb;
		$table_ambulance = $wpdb->prefix. 'hmgt_ambulance';
		//-------usersmeta table data--------------
		$ambulancedata['ambulance_id']=$data['ambulance_id'];
		$ambulancedata['registerd_no']=$data['registerd_no'];
		$ambulancedata['driver_name']=$data['driver_name'];
		$ambulancedata['driver_address']=$data['driver_address'];
		$ambulancedata['driver_phoneno']=$data['driver_phoneno'];
		
		$ambulancedata['description']=$data['description'];
		$ambulancedata['driver_image']=$data['driver_image'];
		
		$ambulancedata['amb_createdby']=get_current_user_id();
		
		
		if($data['action']=='edit')	
		{
			$amb_id['amb_id']=$data['amb_id'];			
			$result=$wpdb->update( $table_ambulance, $ambulancedata ,$amb_id);
			
			//$current = file_get_contents(HMS_logfile_path);
			// Append a new person to the file
		//	$current .= "\nEdit Ambulance at ".date("d:M:Y H:i:s");
			// Write the contents back to the file
			//file_put_contents(HMS_logfile_path, $current);
			hmgt_append_audit_log('Update ambulance detail',get_current_user_id());
			return $result;
		}
		else
		{
			$ambulancedata['amb_created_date']=date("Y-m-d");
			$result=$wpdb->insert( $table_ambulance, $ambulancedata );		
			hmgt_append_audit_log('Add new ambulance',get_current_user_id());
			return $result;			
		}
		
	}
	public function generate_ambulance_id()
	{
		global $wpdb;
		$table_invoice=$wpdb->prefix.'hmgt_ambulance';
	
		$result = $wpdb->get_row("SELECT * FROM $table_invoice ORDER BY amb_id DESC");
		$year = date("y");
		$month = date("m");
		$date = date("d");
		$concat = "AMB".$month.$date;
		if(!empty($result))
		{	$res = $result->amb_id + 1;
		return $concat.$res;
		}
		else
		{
				
			$res = 1;
			return $concat.$res;
		}
	}
	public function get_all_ambulance()
	{
		global $wpdb;
		$table_ambulance = $wpdb->prefix. 'hmgt_ambulance';
		
		$result = $wpdb->get_results("SELECT * FROM $table_ambulance");
		return $result;
		
	}
	public function get_ambulance_id($amb_id)
	{
		global $wpdb;
		$table_ambulance = $wpdb->prefix. 'hmgt_ambulance';
		
		$result = $wpdb->get_var("SELECT ambulance_id FROM $table_ambulance where amb_id= ".$amb_id);
		return $result;
	}
	public function get_single_ambulance($amb_id)
	{
		global $wpdb;
		$table_ambulance = $wpdb->prefix. 'hmgt_ambulance';
		$result = $wpdb->get_row("SELECT * FROM $table_ambulance where amb_id= ".$amb_id);
		return $result;
	}
	public function delete_ambulance($amb_id)
	{
		global $wpdb;
		$table_ambulance = $wpdb->prefix. 'hmgt_ambulance';
		$result = $wpdb->query("DELETE FROM $table_ambulance where amb_id = ".$amb_id);
		hmgt_append_audit_log('Delete ambulance',get_current_user_id());
		return $result;
	}
	
	public function hmgt_add_ambulance_request($data)
	{
		global $wpdb;
		$table_ambulance_req = $wpdb->prefix. 'hmgt_ambulance_req';
		//-------usersmeta table data--------------
		$ambulancedata['ambulance_id']=$data['ambulance_id'];
		$ambulancedata['patient_id']=$data['patient_id'];
		$ambulancedata['address']=$data['address'];		
		$ambulancedata['charge']=$data['charge'];
		$ambulancedata['request_date']=$data['request_date'];
		$ambulancedata['request_time']=$data['request_time'];
		$ambulancedata['dispatch_time']=$data['dispatch_time'];		
		
		$ambulancedata['amb_create_by']=get_current_user_id();
	
	
		if($data['action']=='edit')
		{
			$amb_id['amb_req_id']=$data['amb_req_id'];
			$result=$wpdb->update( $table_ambulance_req, $ambulancedata ,$amb_id);
			hmgt_append_audit_log('Update ambulance request',get_current_user_id());
			return $result;
		}
		else
		{
			$ambulancedata['amb_req_create_date']=date("Y-m-d");
			$result=$wpdb->insert( $table_ambulance_req, $ambulancedata );
			hmgt_append_audit_log('Add new ambulance request',get_current_user_id());
			return $result;
		}
	
	}
	public function get_all_ambulance_request()
	{
		global $wpdb;
		$table_ambulance_req = $wpdb->prefix. 'hmgt_ambulance_req';
	
		$result = $wpdb->get_results("SELECT * FROM $table_ambulance_req");
		return $result;	
	}
	public function get_single_ambulance_req($amb_req_id)
	{
		global $wpdb;
		$table_ambulance_req = $wpdb->prefix. 'hmgt_ambulance_req';
		$result = $wpdb->get_row("SELECT * FROM $table_ambulance_req where amb_req_id= ".$amb_req_id);
		return $result;
	}
	
	public function delete_ambulance_req($amb_req_id)
	{
		global $wpdb;
		$table_ambulance_req = $wpdb->prefix. 'hmgt_ambulance_req';
		$result = $wpdb->query("DELETE FROM $table_ambulance_req where amb_req_id = ".$amb_req_id);
		hmgt_append_audit_log('Delete abmulance request ',get_current_user_id());
		return $result;
	}
	
	
	
}
?>