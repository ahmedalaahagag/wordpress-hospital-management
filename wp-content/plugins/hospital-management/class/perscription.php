<?php 
class Hmgtprescription 
{
	
	public function hmgt_add_prescription($data)
	{
		$entry_value=$this->get_entry_records($data);
		global $wpdb;	
		$table_prescription=$wpdb->prefix. 'hmgt_priscription';
		$table_hmgt_charges = $wpdb->prefix. 'hmgt_charges';
		
		$medication_value=$this->get_medication_records($data);
		
		$prescriptiondata['patient_id']=$data['patient_id'];
		$prescriptiondata['teratment_id']=$data['treatment_id'];
		$prescriptiondata['case_history']=$data['case_history'];
		//$prescriptiondata['medication']=$data['medication'];
		$prescriptiondata['medication_list']=$medication_value;
		$prescriptiondata['treatment_note']=$data['note'];
		$prescriptiondata['pris_create_date']=date("Y-m-d");
		$prescriptiondata['prescription_by']=get_current_user_id();
		$prescriptiondata['custom_field']=$entry_value;
		
		
		$visiting_fees = get_user_meta($prescriptiondata['prescription_by'],'visiting_fees',true);
		
		$charge_data['charge_label']= 'Doctor Fees';
		$charge_data['charge_type']= 'doctorfees';
		$charge_data['room_number']="";
		$charge_data['bed_type']="";
		$charge_data['charges']=$visiting_fees;
		$charge_data['patient_id']=$prescriptiondata['patient_id'];
		$charge_data['status']=0;
		//$charge_data['refer_id']=1;
		$charge_data['created_date']=date("Y-m-d");
		$charge_data['created_by']=get_current_user_id();
		
		if(isset($data['patient_convert']))
		{
			update_user_meta($data['patient_id'],'patient_type',$data['patient_convert']);
		}
		
		if($data['action']=='edit')
		{
			$prescription_dataid['priscription_id']=$data['prescription_id'];
			$charge_referid['refer_id']=$data['prescription_id'];
			$charge_referid['charge_type']='doctorfees';
			
			$result=$wpdb->update( $table_prescription, $prescriptiondata ,$prescription_dataid);
			$wpdb->update( $table_hmgt_charges, $charge_data,$charge_referid );
			hmgt_append_audit_log('Update prescription  ',get_current_user_id());
			return $result;
		}
		else
		{
			$result=$wpdb->insert( $table_prescription,$prescriptiondata);
			$pre_id = $wpdb->insert_id;
			$charge_data['refer_id']=$pre_id;
			$wpdb->insert( $table_hmgt_charges, $charge_data );
			hmgt_append_audit_log('Add new presciption ',get_current_user_id());
			return $result;
		}
	}
	public function get_entry_records($data)
	{
		$all_value_entry=$data['custom_value'];
		$all_label=$data['custom_label'];
			
		$entry_data=array();
		$i=0;
		foreach($all_value_entry as $one_entry)
		{
			$entry_data[]= array('label'=>$all_label[$i],'value'=>$one_entry);
			$i++;
		}
		return json_encode($entry_data);
	}
	public function get_medication_records($data)
	{
		$all_medication=$data['medication'];
		$medicationm_time=$data['times1'];
		$medication_per_day=$data['days'];
			
		$all_data=array();
		$i=0;
		foreach($all_medication as $one_record)
		{
			$all_data[]= array('medication_name'=>$one_record,
					'time'=>$medicationm_time[$i],'per_days'=>$medication_per_day[$i]);
			$i++;
		}
		return json_encode($all_data);
	}
	public function delete_prescription($prescription_id)
	{
		global $wpdb;
		$table_prescription = $wpdb->prefix. 'hmgt_priscription';
		$result = $wpdb->query("DELETE FROM $table_prescription where priscription_id= ".$prescription_id);
		hmgt_append_audit_log('Delete presciption  ',get_current_user_id());
		return $result;
	}
	public function get_prescription_data($prescription_id)
	{
		global $wpdb;
		$table_prescription = $wpdb->prefix. 'hmgt_priscription';
	
		$result = $wpdb->get_row("SELECT * FROM $table_prescription where priscription_id= ".$prescription_id);
		return $result;
	}
	public function get_all_prescription()
	{
		global $wpdb;
		$table_prescription = $wpdb->prefix. 'hmgt_priscription';
		
		$result = $wpdb->get_results("SELECT * FROM $table_prescription");
		return $result;
		
	}
}
?>