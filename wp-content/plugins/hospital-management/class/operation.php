<?php 

	  
class Hmgt_operation
{	

	//Operation type
	public function hmgt_add_operationtype($data)
	{
		
		global $wpdb;
		$result = wp_insert_post( array(
				'post_status' => 'publish',
				'post_type' => 'operation_category',
				'post_title' => $data['category_name']) );
		hmgt_append_audit_log('Add new operation type  ',get_current_user_id());
		return $result;
	}
	
	public function get_all_operationtype()
	{
		$args= array('post_type'=> 'operation_category','posts_per_page'=>-1,'orderby'=>'post_title','order'=>'Asc');
					$result = get_posts( $args );
		return $result;
		
	}
	public function get_bedtype_name($id)
	{		
		$result = get_post( $id );		
		if(!empty($result))	
		return $result->post_title;
		else 
			return "";
	}
	
	public function delete_treatment($treatment_id)
	{
		global $wpdb;
		$table_treatment = $wpdb->prefix. 'hmgt_treatment';
		$result = $wpdb->query("DELETE FROM $table_treatment where treatment_id= ".$treatment_id);
		hmgt_append_audit_log('Delete treatment  ',get_current_user_id());
		return $result;
	}
	public function delete_operation_type($operation_id)
	{
		$result=wp_delete_post($operation_id);
		hmgt_append_audit_log('Delete operation type  ',get_current_user_id());
		return $result;
	}
	public function get_single_bed($bed_id)
	{
		global $wpdb;
		$table_bed = $wpdb->prefix. 'hmgt_bed';
	
		$result = $wpdb->get_row("SELECT * FROM $table_bed where bed_id= ".$bed_id);
		return $result;
	}
	public function get_bed_number($bed_id)
	{
		global $wpdb;
		$table_bed = $wpdb->prefix. 'hmgt_bed';
	
		$result = $wpdb->get_var("SELECT bed_number FROM $table_bed where bed_id= ".$bed_id);
		return $result;
	}
	public function hmgt_add_operation_theater($data)
	{
	
		global $wpdb;
		$table_ot = $wpdb->prefix. 'hmgt_ot';
		$table_hmgt_assign_type = $wpdb->prefix. 'hmgt_assign_type';
		$table_hmgt_charges = $wpdb->prefix. 'hmgt_charges';
		$table_hmgt_history = $wpdb->prefix. 'hmgt_history';
		$table_hmgt_guardian = $wpdb->prefix. 'hmgt_inpatient_guardian';
		//-------usersmeta table data--------------
		$ot_data['operation_title']=$data['operation_title'];
		$ot_data['patient_id']=$data['patient_id'];
		$ot_data['patient_status']=$data['patient_status'];
		$ot_data['bed_type_id']=$data['bed_type_id'];
		$ot_data['bednumber']=$data['bednumber'];
		$ot_data['operation_date']=$data['operation_date'];
		
		$ot_data['operation_time']=$data['operation_time'];
		
		$ot_data['ot_description']=$data['ot_description'];
		$ot_data['operation_charge']=$data['operation_charge'];
		$ot_data['ot_create_date']=date("Y-m-d");
		$ot_data['ot_create_by']=get_current_user_id();
	//	$ot_data['doctor_id']=$data['doctor'];
	
		$guardian = get_guardian_name($_POST['patient_id']);
		
		$history['patient_id']=$_POST['patient_id'];
		$history['status']=$_POST['patient_status'];
		$history['bed_number']=$data['bednumber'];
		
		if(!empty($guardian))
			$history['guardian_name']=$guardian->first_name." ".$guardian->last_name;
		else
			$history['guardian_name']="";
		
		$history['history_type']="operation";
		
		$history['history_date']=date("Y-m-d H:i:s");
		$history['created_by']=get_current_user_id();
		
		
		
		
		$bed_type = $this->get_bedtype_name($ot_data['bed_type_id']);
		$bed_number = $this->get_bed_number($ot_data['bednumber']);
		
	
		$get_bedrow= $this->get_single_bed($ot_data['bednumber']);
		$single_bed_charge= $get_bedrow->bed_charges;
		$total_bed_charge = $single_bed_charge + $ot_data['operation_charge'];
		$charge_data['charge_label']= 'operation Charge';
		$charge_data['charge_type']= 'operation';
		$charge_data['room_number']=$bed_number;
		$charge_data['bed_type']=$bed_type;
		$charge_data['charges']=$total_bed_charge;
		$charge_data['patient_id']=$ot_data['patient_id'];
		$charge_data['status']=0;
		//$charge_data['refer_id']=1;
		$charge_data['created_date']=date("Y-m-d");
		$charge_data['created_by']=get_current_user_id();
		
		
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
		//$result=$wpdb->update( $table_hmgt_guardian, $patient_satus,$patient_id);
		
		
		if($data['action']=='edit')
		{
			$ot_id['operation_id']=$data['operation_id'];
			$charge_referid['refer_id']=$data['operation_id'];
			$charge_referid['charge_type']='operation';
			$result=$wpdb->update( $table_ot, $ot_data ,$ot_id);
			$this->delete_assign_byparant($ot_id['operation_id']);
			if(!empty($data['doctor']))
			{
			
				foreach($data['doctor'] as $id)
				{
					
					$assign_data['child_id']=$id;
					$assign_data['parent_id']=$data['operation_id'];
					$assign_data['assign_type']='operation_theater';
					$assign_data['assign_date']=date("Y-m-d");
					$assign_data['assign_by']=get_current_user_id();
					$wpdb->insert( $table_hmgt_assign_type, $assign_data );
				}
			}
			$wpdb->update( $table_hmgt_charges, $charge_data,$charge_referid );
			hmgt_append_audit_log('Update operation detail  ',get_current_user_id());
			return $result;
		}
		else
		{
			$result=$wpdb->insert( $table_ot, $ot_data );
			$ot_id = $wpdb->insert_id;
			$charge_data['refer_id'] = $ot_id;
			if(!empty($data['doctor']))
			{
				foreach($data['doctor'] as $id)
				{
					$assign_data['child_id']=$id;
					$assign_data['parent_id']=$ot_id ;
					$assign_data['assign_type']='operation_theater';
					$assign_data['assign_date']=date("Y-m-d");
					$assign_data['assign_by']=get_current_user_id();
					$wpdb->insert( $table_hmgt_assign_type, $assign_data );
				}
			}
			hmgt_append_audit_log('Add new operation detail ',get_current_user_id());
			$wpdb->insert( $table_hmgt_charges, $charge_data );
			$wpdb->insert( $table_hmgt_history, $history );
			return $result;
		}
		
		return $result;
	}
	
	public function get_all_operation()
	{
		global $wpdb;
		$table_ot = $wpdb->prefix. 'hmgt_ot';	
		$result = $wpdb->get_results("SELECT * FROM $table_ot");
		return $result;
	
	}
	public function get_operation_name($id)
	{
		$result = get_post( $id );
		return $result->post_title;
	}
	public function get_doctor_by_oprationid($operation_id)
	{
		global $wpdb;		
		$table_hmgt_assign_type = $wpdb->prefix. 'hmgt_assign_type';		
		$result = $wpdb->get_results("SELECT * FROM $table_hmgt_assign_type WHERE parent_id = $operation_id AND assign_type = 'operation_theater' ");
		return $result;	
	}
	public function get_single_operation($id)
	{
		global $wpdb;
		$table_ot = $wpdb->prefix. 'hmgt_ot';	
		$result = $wpdb->get_row("SELECT * FROM $table_ot where operation_id = $id");
		return $result;	
	}
	public function delete_assign_byparant($parent_id)
	{
		global $wpdb;
		$table_hmgt_assign_type = $wpdb->prefix. 'hmgt_assign_type';		
		$result = $wpdb->query("DELETE FROM $table_hmgt_assign_type where parent_id= ".$parent_id. " AND assign_type = 'operation_theater'"  );
		return $result;
	}
	public function delete_oprationtheater($ot_id)
	{
		global $wpdb;
		$table_ot = $wpdb->prefix. 'hmgt_ot';
		$result = $wpdb->query("DELETE FROM $table_ot  where operation_id = $ot_id");
		$table_hmgt_assign_type = $wpdb->prefix. 'hmgt_assign_type';
		$result = $wpdb->query("DELETE FROM $table_hmgt_assign_type where parent_id= ".$ot_id. " AND assign_type = 'operation_theater'"  );
		hmgt_append_audit_log('Delete operation  ',get_current_user_id());
		return $result;
	}
	
}
?>