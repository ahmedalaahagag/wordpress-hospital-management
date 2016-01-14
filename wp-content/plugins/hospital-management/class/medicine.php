<?php 
//$user = new WP_User($user_id);
	  
class Hmgtmedicine
{	

	//Medicine Category
	public function hmgt_add_medicinecategory($data)
	{
		global $wpdb;
		$result = wp_insert_post( array(
						'post_status' => 'publish',
						'post_type' => 'medicine_category',
						'post_title' => $data['category_name']) );
		hmgt_append_audit_log('Add new medicine category ',get_current_user_id());
			return $result;			
	}
	
	public function get_all_category()
	{
		$args= array('post_type'=> 'medicine_category','posts_per_page'=>-1,'orderby'=>'post_title','order'=>'Asc');
					$result = get_posts( $args );
		
		return $result;
		
	}
	public function get_medicine_categoryname($cat_id)
	{
		$result = get_post( $cat_id );		
		if(!empty($result))	
		return $result->post_title;
		else 
			return "";
	}
	public function get_single_category($cat_id)
	{
		global $wpdb;
		$table_medicine_category = $wpdb->prefix. 'hmgt_medicine_category';
		$result = $wpdb->get_row("SELECT * FROM $table_medicine_category where med_cat_id= ".$cat_id);
		return $result;
	}
	public function delete_medicine_category($cat_id)
	{
		$result=wp_delete_post($cat_id);
		hmgt_append_audit_log('Delete medicine category',get_current_user_id());
		/*global $wpdb;
		$table_medicine_category = $wpdb->prefix. 'hmgt_medicine_category';
		$result = $wpdb->query("DELETE FROM $table_medicine_category where med_cat_id= ".$cat_id);*/
		return $result;
	}
	
	//Medicine 
	public function hmgt_add_medicine($data)
	{
		global $wpdb;
		$table_medicine_category = $wpdb->prefix. 'hmgt_medicine';
		//-------usersmeta table data--------------
		$medicinedata['medicine_name']=$data['medicine_name'];
		$medicinedata['med_cat_id']=$_POST['medicine_category'];
		$medicinedata['medicine_price']=$_POST['med_price'];
		$medicinedata['medicine_menufacture']=$_POST['mfg_cmp_name'];
		$medicinedata['medicine_description']=$_POST['description'];
		$medicinedata['medicine_stock']=$_POST['medicine_stock'];
		$medicinedata['med_create_date']=date("Y-m-d");
		$medicinedata['med_create_by']=get_current_user_id();
	
	
		if($data['action']=='edit')
		{
			$medicinid['medicine_id']=$data['medicine_id'];
			$result=$wpdb->update( $table_medicine_category, $medicinedata ,$medicinid);
			hmgt_append_audit_log('Update medicine ',get_current_user_id());
			return $result;
		}
		else
		{
			$result=$wpdb->insert( $table_medicine_category, $medicinedata );
			hmgt_append_audit_log('Add new medicine ',get_current_user_id());
			return $result;
		}
	
	}
	
	public function get_all_medicine()
	{
		global $wpdb;
		$table_medicine_category = $wpdb->prefix. 'hmgt_medicine';
	
		$result = $wpdb->get_results("SELECT * FROM $table_medicine_category");
		return $result;
	
	}
	public function get_single_medicine($cat_id)
	{
		global $wpdb;
		$table_medicine_category = $wpdb->prefix. 'hmgt_medicine';
		$result = $wpdb->get_row("SELECT * FROM $table_medicine_category where medicine_id= ".$cat_id);
		return $result;
	}
	public function delete_medicine($cat_id)
	{
		global $wpdb;
		$table_medicine_category = $wpdb->prefix. 'hmgt_medicine';
		$result = $wpdb->query("DELETE FROM $table_medicine_category where medicine_id= ".$cat_id);
		hmgt_append_audit_log('Delete medicine ',get_current_user_id());
		return $result;
	}
	
}
?>