<?php
//$user = new WP_User($user_id);

class Hmgt_dignosis
{

	//Report type
	public function hmgt_add_report_type($data)
	{

		global $wpdb;

		$result = wp_insert_post( array(
				'post_status' => 'publish',
				'post_type' => 'report_type_category',
				'post_title' => $data['category_name']) );
		hmgt_append_audit_log('Add new report type ',get_current_user_id());
		return $result;
	}

	public function get_all_report_type()
	{
		$args= array('post_type'=> 'report_type_category','posts_per_page'=>-1,'orderby'=>'post_title','order'=>'Asc');
		$result = get_posts( $args );
		return $result;

	}
	public function get_report_type_name($id)
	{
		$result = get_post( $id );
		return $result->post_title;
	}
	public function delete_report_type($id)
	{
		$result=wp_delete_post($id);
		hmgt_append_audit_log('Delete report type ',get_current_user_id());
		return $result;
	}

	public function hmgt_add_dignosis($data)
	{
		global $wpdb;
		$table_diagnosis = $wpdb->prefix. 'hmgt_diagnosis';
		//$diagnosisreportdata=array();
		//-------usersmeta table data--------------
		$diagnosisdata['patient_id']=$data['patient_id'];
		$diagnosisdata['report_type']=$data['report_type'];
		$diagnosisdata['diagno_description']=$data['diagno_description'];
		$diagnosisdata['report_cost']=$data['report_cost'];
		$diagnosisdata['diagno_create_by']=get_current_user_id();


		if($data['action']=='edit')
		{
			if(isset($_FILES['document']) && !empty($_FILES['document']) && $_FILES['document']['size'] !=0)
			{
				if($_FILES['document']['size'] > 0)
					$document_name=load_documets($_FILES['document'],'document','report');
			}
			else{
				if(isset($_REQUEST['edit_document']))
					$document_name=$_REQUEST['edit_document'];
			}
			$diagnosisdata['attach_report']=$document_name;
			$dignosis_id['diagnosis_id']=$data['diagnosis_id'];
			$result=$wpdb->update( $table_diagnosis, $diagnosisdata ,$dignosis_id);
			hmgt_append_audit_log('Update diagnosis report ',get_current_user_id());
			return $result;
		}
		else
		{
			$diagnosisdata['diagnosis_date']=date("Y-m-d");
			$result=$wpdb->insert( $table_diagnosis, $diagnosisdata );
			$dignosis_id['diagnosis_id']=$wpdb->insert_id;
			hmgt_append_audit_log('Add new diagnosis report ',get_current_user_id());
			if(isset($_FILES['document']) && !empty($_FILES['document']) && $_FILES['document']['size'] !=0)
			{
				if($_FILES['document']['size'] > 0)
					$diagnosisreportdata['attach_report']=load_documets($_FILES['document'],'document','report');
				$wpdb->update( $table_diagnosis, $diagnosisreportdata ,$dignosis_id);
			}



			return $result;
		}

	}

	public function get_all_dignosis_report()
	{
		global $wpdb;
		$table_diagnosis = $wpdb->prefix. 'hmgt_diagnosis';

		$result = $wpdb->get_results("SELECT * FROM $table_diagnosis");
		return $result;
	}
	public function get_single_dignosis_report($dignosis_reportid)
	{
		global $wpdb;
		$table_diagnosis = $wpdb->prefix. 'hmgt_diagnosis';
		$result = $wpdb->get_row("SELECT * FROM $table_diagnosis where diagnosis_id = ".$dignosis_reportid);
		return $result;
	}

	public function delete_dignosis($dignosis_reportid)
	{
		global $wpdb;
		$table_diagnosis = $wpdb->prefix. 'hmgt_diagnosis';
		$result = $wpdb->query("DELETE FROM $table_diagnosis where diagnosis_id = ".$dignosis_reportid);
		hmgt_append_audit_log('Delete diagnosis report ',get_current_user_id());
		return $result;
	}
	public function get_diagnosis_by_patient($patient_id)
	{
		global $wpdb;
		$table_diagnosis = $wpdb->prefix. 'hmgt_diagnosis';
		$result = $wpdb->get_results("SELECT *FROM $table_diagnosis where patient_id = ".$patient_id);
		return $result;
	}
	public function get_last_diagnosis_by_patient($patient_id)
	{
		global $wpdb;
		$table_diagnosis = $wpdb->prefix. 'hmgt_diagnosis';
		$result = $wpdb->get_row("SELECT *FROM $table_diagnosis where patient_id = ".$patient_id." ORDER BY 'diagnosis_id' DESC");
		return $result;
	}


}
?>
