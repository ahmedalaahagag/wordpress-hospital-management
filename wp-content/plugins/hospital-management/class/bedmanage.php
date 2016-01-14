<?php 
//$user = new WP_User($user_id);
	  
class Hmgtbedmanage
{	

	//Bedtype
	public function hmgt_add_bedtype($data)
	{
		
		global $wpdb;
		$result = wp_insert_post( array(
				'post_status' => 'publish',
				'post_type' => 'bedtype_category',
				'post_title' => $data['category_name']) );
		hmgt_append_audit_log('Add new bed type ',get_current_user_id());
		return $result;
	}
	
	public function get_all_bedtype()
	{
		$args= array('post_type'=> 'bedtype_category','posts_per_page'=>-1,'orderby'=>'post_title','order'=>'Asc');
					$result = get_posts( $args );
		return $result;
		
	}
	public function get_bedtype_name($bed_type_id)
	{		
		$result = get_post( $bed_type_id );		
		if(!empty($result))	
		return $result->post_title;
		else 
			return "";
	}
	public function get_single_bedtype($bed_type_id)
	{
		global $wpdb;
		$table_bedtype = $wpdb->prefix. 'hmgt_bed_type';
		
		$result = $wpdb->get_row("SELECT * FROM $table_bedtype where bed_type_id= ".$bed_type_id);
		return $result;
	}
	public function delete_bedtype($bed_type_id)
	{
		global $wpdb;
		$table_bedtype = $wpdb->prefix. 'hmgt_bed_type';
		$result = $wpdb->query("DELETE FROM $table_bedtype where bed_type_id= ".$bed_type_id);
		hmgt_append_audit_log('Delete bad type ',get_current_user_id());
		return $result;
	}
	
	//Bed
	
	public function hmgt_add_bed($data)
	{
		global $wpdb;
		$table_bed = $wpdb->prefix. 'hmgt_bed';
		//-------usersmeta table data--------------
		$beddata['bed_number']=$data['bed_number'];
		$beddata['bed_type_id']=$_POST['bed_type_id'];
		$beddata['bed_charges']=$_POST['bed_charges'];
		$beddata['bed_description']=$_POST['bed_description'];
		$beddata['bed_create_date']=date("Y-m-d");
		$beddata['bed_create_by']=get_current_user_id();
	
	
		if($data['action']=='edit')
		{
			$beddataid['bed_id']=$data['bed_id'];
			$result=$wpdb->update( $table_bed, $beddata ,$beddataid);
			hmgt_append_audit_log('Update bed  ',get_current_user_id());
			return $result;
		}
		else
		{
			$result=$wpdb->insert( $table_bed, $beddata );
			hmgt_append_audit_log('Add new bed ',get_current_user_id());
			return $result;
		}
	
	}
	
	public function get_all_bed()
	{
		global $wpdb;
		$table_bed = $wpdb->prefix. 'hmgt_bed';
	
		$result = $wpdb->get_results("SELECT * FROM $table_bed");
		return $result;
	
	}
	public function get_bed_number($bed_id)
	{
		global $wpdb;
		$table_bed = $wpdb->prefix. 'hmgt_bed';
	
		$result = $wpdb->get_var("SELECT bed_number FROM $table_bed where bed_id= ".$bed_id);
		return $result;
	}
	public function get_single_bed($bed_id)
	{
		global $wpdb;
		$table_bed = $wpdb->prefix. 'hmgt_bed';
		
		$result = $wpdb->get_row("SELECT * FROM $table_bed where bed_id= ".$bed_id);
		return $result;
	}
	public function delete_bed($bed_id)
	{
		global $wpdb;
		$table_bed = $wpdb->prefix. 'hmgt_bed';
		$result = $wpdb->query("DELETE FROM $table_bed where bed_id= ".$bed_id);
		hmgt_append_audit_log('Delete bed ',get_current_user_id());
		return $result;
	}
	public function delete_bed_type($bed_id)
	{
		$result=wp_delete_post($bed_id);
		return $result;
	}
	
	public function get_bed_by_bedtype($bed_type_id)
	{
		global $wpdb;
		$table_bed = $wpdb->prefix. 'hmgt_bed';
		
		$result = $wpdb->get_results("SELECT * FROM $table_bed where bed_type_id = ".$bed_type_id);
		return $result;
	}
	
	
	public function add_bed_allotment($data)
	{
		global $wpdb;
		$table_bedallotment = $wpdb->prefix. 'hmgt_bed_allotment';
		$table_hmgt_assign_type = $wpdb->prefix. 'hmgt_assign_type';
		$table_hmgt_guardian = $wpdb->prefix. 'hmgt_inpatient_guardian';
		$table_hmgt_bed = $wpdb->prefix. 'hmgt_bed';
		$table_hmgt_history = $wpdb->prefix. 'hmgt_history';
		$table_hmgt_charges = $wpdb->prefix. 'hmgt_charges';
		//-------usersmeta table data--------------
		$bedallotmnetdata['bed_number']=$data['bed_number'];
		$bedallotmnetdata['bed_type_id']=$_POST['bed_type_id'];
		$bedallotmnetdata['patient_id']=$_POST['patient_id'];
		$bedallotmnetdata['patient_status']=$_POST['patient_status'];		
		$bedallotmnetdata['allotment_date']=$_POST['allotment_date'];
		$bedallotmnetdata['discharge_time']=$_POST['discharge_time'];	
		$bedallotmnetdata['allotment_description']=$_POST['allotment_description'];
		$bedallotmnetdata['created_date']=date("Y-m-d");
		$bedallotmnetdata['allotment_by']=get_current_user_id();
		
		$guardian = get_guardian_name($_POST['patient_id']);
		$patient_satus['patient_status']=$_POST['patient_status'];
		$patient_id['patient_id']=$_POST['patient_id'];
		if(!empty($guardian))
		$result=$wpdb->update( $table_hmgt_guardian, $patient_satus,$patient_id);
		else 
		{
			$wpdb->insert($table_hmgt_guardian, array(
					"patient_status" => $_POST['patient_status'],
					"patient_id" => $_POST['patient_id']
					
			));
		}
		
		
		if($bedallotmnetdata['discharge_time'] >= date('Y-m-d'))
		{
			$bed['status']= 1;
			$bedid['bed_id']=$data['bed_number'];
			//$result=$wpdb->update( $table_hmgt_bed, $bed,$bedid);
		}
		
		$history['patient_id']=$_POST['patient_id'];
		$history['status']=$_POST['patient_status'];
		$history['bed_number']=$data['bed_number'];
			
		if(!empty($guardian))
			$history['guardian_name']=$guardian->first_name." ".$guardian->last_name;
		else
			$history['guardian_name']="";
		$history['history_type']="bed_assign";
		
		$history['history_date']=date("Y-m-d H:i:s");
		$history['created_by']=get_current_user_id();
		
		
		$bed_type = $this->get_bedtype_name($bedallotmnetdata['bed_type_id']);
		$bed_number = $this->get_bed_number($bedallotmnetdata['bed_number']);
		$charge_type = 'Bed charge';
		$startTimeStamp =strtotime( $bedallotmnetdata['allotment_date']);
		$endTimeStamp =strtotime( $bedallotmnetdata['discharge_time']);		
		$timeDiff = abs($endTimeStamp - $startTimeStamp) ;		
		$numberDays = $timeDiff/86400;  // 86400 seconds in one day
		
		// and you might want to convert to integer
		$numberDays = intval($numberDays) + 1;
		$get_bedrow= $this->get_single_bed($bedallotmnetdata['bed_number']);
		$single_bed_charge= $get_bedrow->bed_charges;
		$total_bed_charge = $single_bed_charge * $numberDays;
		$charge_data['charge_label']= 'Bed Charges';
		$charge_data['charge_type']= 'bed';
		$charge_data['room_number']=$bed_number;
		$charge_data['bed_type']=$bed_type;
		$charge_data['charges']=$total_bed_charge;
		$charge_data['patient_id']=$bedallotmnetdata['patient_id'];
		$charge_data['status']=0;
		
		$charge_data['created_date']=date("Y-m-d");
		$charge_data['created_by']=get_current_user_id();
		
		
		if($data['action']=='edit')
		{
			$allotmentid['bed_allotment_id']=$data['allotment_id'];
			$charge_referid['refer_id']=$data['allotment_id'];
			$charge_referid['charge_type']='bed';
			$result=$wpdb->update( $table_bedallotment, $bedallotmnetdata ,$allotmentid);
			$this->delete_assign_byparent($allotmentid['bed_allotment_id']);
			if(!empty($data['nurse']))
			{
					
				foreach($data['nurse'] as $id)
				{
						
					$assign_data['child_id']=$id;
					$assign_data['parent_id']=$allotmentid['bed_allotment_id'];
					$assign_data['assign_type']='nurse-bedallotment';
					$assign_data['assign_date']=date("Y-m-d");
					$assign_data['assign_by']=get_current_user_id();
					$wpdb->insert( $table_hmgt_assign_type, $assign_data );
				}
			}
			$wpdb->update( $table_hmgt_charges, $charge_data,$charge_referid );
			hmgt_append_audit_log('update bed allotment detail ',get_current_user_id());
			return $result;
		}
		else
		{
			
			$result=$wpdb->insert( $table_bedallotment, $bedallotmnetdata );
			$allotment_id = $wpdb->insert_id;
			hmgt_append_audit_log('Add new bed allotment ',get_current_user_id());
			if(!empty($data['nurse']))
			{
				foreach($data['nurse'] as $id)
				{
					$assign_data['child_id']=$id;
					$assign_data['parent_id']=$allotment_id ;
					$assign_data['assign_type']='nurse-bedallotment';
					$assign_data['assign_date']=date("Y-m-d");
					$assign_data['assign_by']=get_current_user_id();
					$wpdb->insert( $table_hmgt_assign_type, $assign_data );
				}
			}
			$charge_data['refer_id'] = $allotment_id;
			$history['parent_id']=$allotment_id;
			$wpdb->insert( $table_hmgt_charges, $charge_data );
			$wpdb->insert( $table_hmgt_history, $history );
			return $result;
		}
	}
	
	public function get_all_bedallotment()
	{
		global $wpdb;
		$table_bedallotment = $wpdb->prefix. 'hmgt_bed_allotment';
		$date = date('Y-m-d');
		//echo "SELECT * FROM $table_bedallotment where discharge_time > '$date'";
		$result = $wpdb->get_results("SELECT * FROM $table_bedallotment where discharge_time >= '$date'");
		return $result;
	
	}
	
	public function get_single_bedallotment($bed_allotment_id)
	{
		global $wpdb;
		$table_bedallotment = $wpdb->prefix. 'hmgt_bed_allotment';
	
		$result = $wpdb->get_row("SELECT * FROM $table_bedallotment where bed_allotment_id = ".$bed_allotment_id);
		return $result;
	}
	
	public function get_nurse_by_bedallotment_id($allocate_id)
	{
		global $wpdb;
		$table_hmgt_assign_type = $wpdb->prefix. 'hmgt_assign_type';
		$result = $wpdb->get_results("SELECT * FROM $table_hmgt_assign_type WHERE parent_id = $allocate_id AND assign_type = 'nurse-bedallotment' ");
		return $result;
	}
	public function get_nurse_by_assignid($bed_assing_id)
	{
		global $wpdb;
		$table_hmgt_assign_type = $wpdb->prefix. 'hmgt_assign_type';
		$result = $wpdb->get_results("SELECT * FROM $table_hmgt_assign_type WHERE parent_id = $bed_assing_id AND assign_type = 'nurse-bedallotment' ");
		return $result;
	}
	public function delete_assign_byparent($parent_id)
	{
		global $wpdb;
		$table_hmgt_assign_type = $wpdb->prefix. 'hmgt_assign_type';
		$result = $wpdb->query("DELETE FROM $table_hmgt_assign_type where parent_id= ".$parent_id. " AND assign_type = 'nurse-bedallotment'"  );
		return $result;
	}
	
	public function delete_bedallocate_record($allocate_id)
	{
		global $wpdb;
		$table_bedallotment = $wpdb->prefix. 'hmgt_bed_allotment';
		$result = $wpdb->query("DELETE FROM $table_bedallotment  where bed_allotment_id = $allocate_id");
		$table_hmgt_assign_type = $wpdb->prefix. 'hmgt_assign_type';
		$result = $wpdb->query("DELETE FROM $table_hmgt_assign_type where parent_id= ".$allocate_id. " AND assign_type = 'nurse-bedallotment'"  );
		hmgt_append_audit_log('Delete bed allotment detail ',get_current_user_id());
		return $result;
	}
	
}
?>