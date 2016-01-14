<?php 
//--------------------------------------------

function hmgt_get_remote_file($url, $timeout = 30){
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$file_contents = curl_exec($ch);
	curl_close($ch);
	return ($file_contents) ? $file_contents : FALSE;
}
function get_all_user_in_message()
{
	$doctor=get_users(array('role'=>'doctor'));
	$patient=get_users(array('role'=>'patient'));
	$nurse=get_users(array('role'=>'nurse'));
	$receptionist=get_users(array('role'=>'receptionist'));
	$pharmacist=get_users(array('role'=>'pharmacist'));
	$laboratorist=get_users(array('role'=>'laboratorist'));
	$accountant=get_users(array('role'=>'accountant'));
	
	$all_user = array('doctor'=>$doctor,
			'patient'=>$patient,
			'nurse'=>$nurse,
			'receptionist'=>$receptionist,
			'pharmacist'=>$pharmacist,
			'laboratorist'=>$laboratorist,
			'accountant'=>$accountant
			);
	$return_array = array();
	//echo count($all_user['doctor']);
	//exit;
	foreach($all_user as $key => $value)
	{ 
		if(!empty($value))
		{
		 echo '<optgroup label="'.$key.'" style = "text-transform: capitalize;">';
		foreach($value as $user)
		{
			
			echo '<option value="'.$user->ID.'">'.$user->display_name.'</option>';
		}
		}
	}	
}
//get_all_user_in_message();
/* echo '<select>';
get_all_user_in_message();
echo '</select>';
exit; */
function load_documets($file,$type,$nm) {


$imagepath =$file;
     
$parts = pathinfo($_FILES[$type]['name']);


 $inventoryimagename = mktime()."-".$nm."-"."in".".".$parts['extension'];
 $document_dir = WP_CONTENT_DIR ;
           $document_dir .= '/uploads/hospital_assets/';
	
		$document_path = $document_dir;

 
if($imagepath != "")
{	
	if(file_exists(WP_CONTENT_DIR.$imagepath))
	unlink(WP_CONTENT_DIR.$imagepath);
}
if (!file_exists($document_path)) {
	mkdir($document_path, 0777, true);
}	
       if (move_uploaded_file($_FILES[$type]['tmp_name'], $document_path.$inventoryimagename)) {
          $imagepath= $inventoryimagename;	
       }


return $imagepath;

				

}

function hmgt_get_countery_phonecode($country_name)
{

	//$xml=simplexml_load_file(plugins_url( 'countrylist.xml', __FILE__ )) or die("Error: Cannot create object");
	$url = plugins_url( 'countrylist.xml', __FILE__ );
	$xml =simplexml_load_string(hmgt_get_remote_file($url));
	foreach($xml as $country)
	{
		if($country_name == $country->name)
			return $country->phoneCode;

	}
}

add_action( 'wp_login_failed', 'hmgt_login_failed' ); // hook failed login 

function hmgt_login_failed( $user ) {
	// check what page the login attempt is coming from
	$referrer = $_SERVER['HTTP_REFERER'];
	
	 $curr_args = array(
				'page_id' => get_option('hmgt_login_page'),
				'login' => 'failed'
				);
				$referrer_faild = add_query_arg( $curr_args, get_permalink( get_option('hmgt_login_page') ) );


	// check that were not on the default login page
	if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') && $user!=null ) {
		// make sure we don't already have a failed login attempt
		if ( !strstr($referrer, 'login=failed' )) {
			// Redirect to the login page and append a querystring of login failed
			wp_redirect( $referrer_faild);
		} else {
			wp_redirect( $referrer );
		}

		exit;
	}
}


function hmgt_login_link()
{

	$args = array( 'redirect' => site_url() );
	
	if(isset($_GET['login']) && $_GET['login'] == 'failed')
	{
		?>

<div id="login-error" style="background-color: #FFEBE8;border:1px solid #C00;padding:5px;">
  <p><?php _e('Login failed: You have entered an incorrect Username or password, please try again.','hospital_mgt'); ?></p>
</div>
<?php
	}
		
	 $args = array(
			'echo' => true,
			'redirect' => site_url( $_SERVER['REQUEST_URI'] ),
			'form_id' => 'loginform',
			'label_username' => __( 'Username' , 'hospital_mgt'),
			'label_password' => __( 'Password', 'hospital_mgt' ),
			'label_remember' => __( 'Remember Me' , 'hospital_mgt'),
			'label_log_in' => __( 'Log In' , 'hospital_mgt'),
			'id_username' => 'user_login',
			'id_password' => 'user_pass',
			'id_remember' => 'rememberme',
			'id_submit' => 'wp-submit',
			'remember' => true,
			'value_username' => NULL,
	        'value_remember' => false ); 
	 $args = array('redirect' => site_url('/?dashboard=user') );
	 
	 if ( is_user_logged_in() )
	 {?>
<a href="<?php echo home_url('/')."?dashboard=user"; ?>"><i
								class="fa fa-sign-out m-r-xs"></i>
<?php _e('Dashboard','hospital_mgt');?>
</a>
<?php 
	 }
	 else 
	 {
	 wp_login_form( $args );
	 }
	 
}
//To show number of rows in table
function hmgt_tables_rows($table_name)
{
	global $wpdb;
	$table_name = $wpdb->prefix . $table_name;
	$count_query = "select count(*) from $table_name";
	$num = $wpdb->get_var($count_query);

	echo  $num;


}
function blood_group()
{
	return $blood_group=array('O+','O-','A+','A-','B+','B-','AB+','AB-');
	
}

function get_default_userprofile($role)
{
	$profile_pict=array('doctor'=>get_option('hmgt_doctor_thumb'),
			'nurse'=>get_option('hmgt_nurse_thumb'),
			'pharmacist'=>get_option('hmgt_pharmacist_thumb'),
			'laboratorist'=>get_option('hmgt_laboratorist_thumb'),
			'accountant'=>get_option('hmgt_accountant_thumb'),
			'patient'=>get_option('hmgt_patient_thumb'),
			'receptionist'=>get_option('hmgt_support_thumb')
	);
	//return "fdd";
	return $profile_pict[$role];

}

function get_role_name_in_message($role)
{
	$profile_pict=array('doctor'=>__( 'Doctor' ,'hospital_mgt'),
			'nurse'=>__( 'Nurse' ,'hospital_mgt'),
			'pharmacist'=> __( 'Pharmacist' ,'hospital_mgt'),
			'laboratorist'=> __( 'Laboratory Staff' ,'hospital_mgt'),
			'accountant'=>__( 'Accountant' ,'hospital_mgt'),
			'patient'=> __( 'Patient' ,'hospital_mgt'),
			'receptionist'=> __( 'Support Staff' ,'hospital_mgt')
	);
	//return "fdd";
	return $profile_pict[$role];

}
function hmgt_report_tables_rows($table_name,$type)
{
	global $wpdb;
	$table_name = $wpdb->prefix . $table_name;
	$count_query = "select count(*) from $table_name where report_type = '$type'";
	$num = $wpdb->get_var($count_query);
	echo  $num;
}

function hmgt_menu()
{
	$user_menu = array();
	$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/doctor.png' ),'menu_title'=>__( 'Therapist', 'hospital_mgt' ),'patient'=>1,'doctor' => 1,'nurse' => 1,'receptionist'=>1,'accountant' =>1,'pharmacist'=>0,'laboratorist'=>1,'page_link'=>'doctor');
	$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/outpatient.png' ),'menu_title'=>__( 'Patients', 'hospital_mgt' ),'patient'=>0,'doctor' => 1,'nurse' => 1,'receptionist'=>1,'accountant' =>1,'pharmacist'=>0,'laboratorist'=>0,'page_link'=>'outpatient');
	$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Accountant.png' ),'menu_title'=>__( 'Accountant', 'hospital_mgt' ),'patient'=>0,'doctor' => 1,'nurse' => 1,'receptionist'=>1,'accountant' =>1,'pharmacist'=>1,'laboratorist'=>1,'page_link'=>'accountant');
	$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/payment.png' ),'menu_title'=>__( 'Invoice', 'hospital_mgt' ),'doctor' => 1,'nurse' => 0,'receptionist'=>0,'accountant' =>1,'pharmacist'=>0,'laboratorist'=>0,'page_link'=>'invoice');
	//$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Occupation-Report.png' ),'menu_title'=>__( 'Occupation Report', 'hospital_mgt' ),'doctor' => 1,'page_link'=>'occupation_report');
	//$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/operation-report.png' ),'menu_title'=>__( 'Operation Report', 'hospital_mgt' ),'doctor' => 1,'page_link'=>'operation_report');
	//$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Fail-report.png' ),'menu_title'=>__( 'Fail Report', 'hospital_mgt' ),'doctor' => 1,'page_link'=>'fail_report');
	//$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Report.png' ),'menu_title'=>__( 'Report', 'hospital_mgt' ),'patient'=>0,'doctor' => 1,'nurse' => 0,'receptionist'=>0,'accountant' =>0,'pharmacist'=>0,'laboratorist'=>0,'page_link'=>'report');
	$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Diagnosis-Report.png' ),'menu_title'=>__( 'Sessions Settings', 'hospital_mgt' ),'patient'=>0 ,'doctor' => 1,'nurse' => 1,'receptionist'=>1,'accountant' =>1,'pharmacist'=>1,'laboratorist'=>1,'page_link'=>'session_settings');
	$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Assign--Bed-nurse1.png' ),'menu_title'=>__( 'Sessions Duration', 'hospital_mgt' ),'patient'=>1,'doctor' => 1,'nurse' => 1,'receptionist'=>1,'accountant' =>1,'pharmacist'=>1,'laboratorist'=>1,'page_link'=>'session_duration');
	$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Appointment.png' ),'menu_title'=>__( 'Packages', 'hospital_mgt' ),'patient'=>1,'doctor' => 1,'nurse' => 1,'receptionist'=>1,'accountant' =>1,'pharmacist'=>1,'laboratorist'=>1,'page_link'=>'packages');
	return  $user_menu;
}
/*function blood_group()
{
	return $blood_group=array('O+','O-','A+','A-','B+','B-','AB+','AB-');
	
}*/
//-----------Add guardian Record------------------
function add_guardian($records,$record_id)
{
	
	global $wpdb;
	 $table_name = $wpdb->prefix .'hmgt_inpatient_guardian';
	if($record_id)
	{
		return $result=$wpdb->update($table_name,$records,array('inpatient_id'=>$record_id));
	}
	else
	{
		$wpdb->insert( $table_name, $records);
		return $wpdb->insert_id;
	}
		
}
function get_patient_status($patient_id)
{
	global $wpdb;
	$table_name = $wpdb->prefix .'hmgt_inpatient_guardian';
	$pstatus =$wpdb->get_var("SELECT  patient_status FROM $table_name WHERE  	patient_id=".$patient_id);
	return $pstatus;
}
function delete_guardian($record_id)
{
	global $wpdb;
	$table_name = $wpdb->prefix .'hmgt_inpatient_guardian';
	return $result=$wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE patient_id= %d",$record_id));
	
}
function update_guardian($records,$record_id)
{
	global $wpdb;
	$table_name = $wpdb->prefix .'hmgt_inpatient_guardian';
	return $result=$wpdb->update($table_name,$records,array('patient_id'=>$record_id));
}

function get_guardianby_patient($record_id)
{
	
	global $wpdb;
	$table_name = $wpdb->prefix .'hmgt_inpatient_guardian';
	
	$guardian =$wpdb->get_row("SELECT *FROM $table_name WHERE  	patient_id=".$record_id, ARRAY_A);
	//print_r($guardian);
	
	if(!empty($guardian))
		return $guardian;
	else
		return " ";
}
function admit_reason()
{
	return $reason=array(__('Admit','hospital_mgt'),
				  		__('Under Treatment','hospital_mgt'),
				 	 	__('Operation','hospital_mgt'),
				  		__('Recovery','hospital_mgt'),
				  		__('Cured','hospital_mgt'),
						__('Discharge','hospital_mgt'),
						__('Death','hospital_mgt')
					);				  
}

function hmgt_getuser_by_user_role($role)
{
	$user_list = get_users(array('role' => $role));
	$user_return = array();
	foreach($user_list as $users)
	{
		$first_name = get_user_meta($users->ID,'first_name',true);
		$last_name = get_user_meta($users->ID,'last_name',true);
		$user_return[] =array('id'=>$users->ID,'first_name'=>$first_name,'last_name' =>$last_name) ;
	}
	return $user_return;
}

function hmgt_get_display_name($user_id)
{
	if (!$user = get_userdata($user_id))
		return false;
	return $user->data->display_name;
}

function hmgt_append_audit_log($audit_action,$user_id)
{
	$current = file_get_contents(HMS_LOG_file);
	
	$current .= "\n".$audit_action." at ".date("d:M:Y H:i:s")." by ".hmgt_get_display_name($user_id);
	// Write the contents back to the file
	file_put_contents(HMS_LOG_file, $current);
}
function hmgt_get_emailid_byuser_id($id)
{
	if (!$user = get_userdata($id))
		return false;
	return $user->data->user_email;
}

//Patien detail
function get_user_detail_byid($patient_id)
{
	$user_return = array();
	$first_name = get_user_meta($patient_id,'first_name',true);
	$last_name = get_user_meta($patient_id,'last_name',true);
	$patient_id = get_user_meta($patient_id,'patient_id',true);
	$user_return =array('id'=>$patient_id,'first_name'=>$first_name,'last_name' =>$last_name,'patient_id'=>$patient_id) ;
	return $user_return;
}
function hmgt_patientid_list()
{
	
	$user_list = get_users(array('role' => 'patient'));
	$user_return = array();
	foreach($user_list as $users)
	{
		$first_name = get_user_meta($users->ID,'first_name',true);
		$last_name = get_user_meta($users->ID,'last_name',true);
		$patient_id = get_user_meta($users->ID,'patient_id',true);
		$user_return[] =array('id'=>$users->ID,'first_name'=>$first_name,'last_name' =>$last_name,'patient_id'=>$patient_id) ;
	}
	return $user_return;
}
function hmgt_inpatient_list()
{
	$user_list = get_users(array('role' => 'patient','meta_key'=>'patient_type','meta_value'=>'inpatient'));
	$user_return = array();
	foreach($user_list as $users)
	{
		$first_name = get_user_meta($users->ID,'first_name',true);
		$last_name = get_user_meta($users->ID,'last_name',true);
		$patient_id = get_user_meta($users->ID,'patient_id',true);
		$user_return[] =array('id'=>$users->ID,'first_name'=>$first_name,'last_name' =>$last_name,'patient_id'=>$patient_id) ;
	}
	return $user_return;
}
function get_inpatient_status($patient_id)
{
	global $wpdb;
	$table_name = $wpdb->prefix .'hmgt_inpatient_guardian';
	
	$patient_status =$wpdb->get_row("SELECT *FROM $table_name WHERE  patient_id=".$patient_id);
	
	
		return $patient_status;
	
}

function get_guardian_name($user_id)
{
	global $wpdb;
	$table_inpatient_guardian = $wpdb->prefix."hmgt_inpatient_guardian";
	$sql="SELECT * FROM $table_inpatient_guardian  WHERE patient_id = $user_id";
	$guardian = $wpdb->get_row($sql);
	return $guardian;
}


function get_lastpatient_id($role)
{
	global $wpdb;
	$this_role = "'[[:<:]]".$role."[[:>:]]'";
	$table_name = $wpdb->prefix .'usermeta';
	$metakey=$wpdb->prefix .'capabilities';
	$userid=$wpdb->get_row("SELECT MAX(user_id)as uid FROM $table_name where meta_key = '$metakey' AND meta_value RLIKE $this_role");
	return get_user_meta($userid->uid,'patient_id',true);
	
}
function display_patient_reports($patientid)
{
	global $wpdb;
	$table_name = $wpdb->prefix .'hmgt_priscription';
	
	$patientdata=$wpdb->get_results("SELECT * FROM $table_name where patient_id='$patientid'");
	
	return $patientdata;
}

//Report

function hmgt_month_list()
{
	$month =array('1'=>__("January",'hospital_mgt'),
			'2'=>__("February",'hospital_mgt'),
			'3'=>__("March",'hospital_mgt'),
			'4'=>__("April",'hospital_mgt'),
			'5'=>__("May",'hospital_mgt'),
			'6'=>__("June",'hospital_mgt'),
			'7'=>__("July",'hospital_mgt'),
			'8'=>__("August",'hospital_mgt'),
			'9'=>__("September",'hospital_mgt'),
			'10'=>__("Octomber",'hospital_mgt'),
			'11'=>__("November",'hospital_mgt'),
			'12'=>__("December",'hospital_mgt'));
	return $month;
}

//AJAX
add_action( 'wp_ajax_hmgt_add_remove_category',  'hmgt_add_remove_category');
add_action( 'wp_ajax_hmgt_remove_category', 'hmgt_remove_category');
add_action( 'wp_ajax_hmgt_remove_nurse_note', 'hmgt_remove_nurse_note');
add_action( 'wp_ajax_hmgt_add_category',  'hmgt_add_category');
add_action( 'wp_ajax_hmgt_get_bednumber',  'hmgt_get_bednumber');
add_action( 'wp_ajax_hmgt_patient_status_view',  'hmgt_patient_status_view');
add_action( 'wp_ajax_hmgt_add_nurse_notes',  'hmgt_add_nurse_notes');
add_action( 'wp_ajax_hmgt_add_doctor_notes',  'hmgt_add_doctor_notes');
add_action( 'wp_ajax_hmgt_user_profile',  'hmgt_user_profile');
add_action( 'wp_ajax_hmgt_patient_charges_view',  'hmgt_patient_charges_view');
add_action( 'wp_ajax_hmgt_patient_invoice_view',  'hmgt_patient_invoice_view');
add_action( 'wp_ajax_hmgt_view_event',  'hmgt_view_event');
add_action( 'wp_ajax_hmgt_view_report',  'hmgt_view_report');
add_action( 'wp_ajax_hmgt_view_priscription',  'hmgt_view_priscription');
add_action( 'wp_ajax_hmgt_load_convert_patient',  'hmgt_load_convert_patient');
add_action( 'wp_ajax_hmgt_sms_service_setting',  'hmgt_sms_service_setting');


function hmgt_add_remove_category()
{
	$model = $_REQUEST['model'];
	hmgt_add_category_type($model);
}


function hmgt_add_category_type($model) 
{
	$title = "Title here";
	$table_header_title ="Table head";
	$button_text= "Button Text"; 
	$label_text = "Label Text";
	if($model == 'medicine')
	{
		$category_obj = new Hmgtmedicine();
		$cat_result = $category_obj->get_all_category();
		$title = __("Medicine Category",'hospital_mgt');
		$table_header_title =  __("Medicine Category",'hospital_mgt');
		$button_text=  __("Add Category",'hospital_mgt');
		$label_text =  __("Category Name",'hospital_mgt');
	}
	if($model == 'department')
	{
		$user_object=new Hmgtuser();
		$cat_result =$user_object->get_staff_department();
		$title = __("Department",'hospital_mgt');
		$table_header_title =  __("Department Name",'hospital_mgt');
		$button_text=  __("Add Department",'hospital_mgt');
		$label_text =  __("Department Name",'hospital_mgt');
	}
	if($model == 'bedtype')
	{
		$bed_type=new Hmgtbedmanage();
		$cat_result =$bed_type->get_all_bedtype();
		$title = __("Bed Category",'hospital_mgt');
		$table_header_title =  __("Bed Category Name",'hospital_mgt');
		$button_text=  __("Add Bed Category",'hospital_mgt');
		$label_text =  __("Bed Category Name",'hospital_mgt');
	}
	if($model == 'operation')
	{
		$operation_type=new Hmgt_operation();
		$cat_result =$operation_type->get_all_operationtype();
		$title = __("Operaion",'hospital_mgt');
		$table_header_title =  __("Opearion Name",'hospital_mgt');
		$button_text=  __("Add Opearion",'hospital_mgt');
		$label_text =  __("Operation Name",'hospital_mgt');
	}
	if($model == 'specialization')
	{
		$user_object=new Hmgtuser();
		$cat_result =$user_object->get_doctor_specilize();
		$title = __("Specialization",'hospital_mgt');
		$table_header_title =  __("Specialization Name",'hospital_mgt');
		$button_text=  __("Add Specialization",'hospital_mgt');
		$label_text =  __("Specialization Name",'hospital_mgt');
	}
	if($model == 'report_type')
	{
		$user_object=new Hmgt_dignosis();
		$cat_result =$user_object->get_all_report_type();
		$title = __("Dignosis Report",'hospital_mgt');
		$table_header_title =  __("Dignosis Report Name",'hospital_mgt');
		$button_text=  __("Add Dignosis Report",'hospital_mgt');
		$label_text =  __("Dignosis Report Name",'hospital_mgt');
	}
	?>
	<div class="modal-header"> <a href="#" class="close-btn-cat badge badge-success pull-right">X</a>
  		<h4 id="myLargeModalLabel" class="modal-title"><?php echo $title;?></h4>
	</div>
	<hr>
	<div class="panel panel-white">
  	<div class="category_listbox">
  	<div class="table-responsive">
  	<table class="table">
  		<thead>
  			<tr>
                <!--  <th>#</th> -->
                <th><?php echo $table_header_title;?></th>
                <th><?php _e('Action','hospital_mgt');?></th>
            </tr>
        </thead>
        <?php 
			
        	$i = 1;
        	if(!empty($cat_result))
        	{
        		
        		foreach ($cat_result as $retrieved_data)
        		{
        		echo '<tr id="cat-'.$retrieved_data->ID.'">';
        		//echo '<td>'.$i.'</td>';
        		echo '<td>'.$retrieved_data->post_title.'</td>';
  				echo '<td id='.$retrieved_data->ID.'><a class="btn-delete-cat badge badge-delete" model='.$model.' href="#" id='.$retrieved_data->ID.'>X</a></td>';
        		echo '</tr>';
        		$i++;		
        		}
        	}
        ?>
  	</table>
  	</div>
  	</div>
  	 <form name="medicinecat_form" action="" method="post" class="form-horizontal" id="medicinecat_form">
  	 	<div class="form-group">
			<label class="col-sm-4 control-label" for="medicine_name"><?php echo $label_text;?><span class="require-field">*</span></label>
			<div class="col-sm-4">
				<input id="medicine_name" class="form-control text-input" type="text" 
				value="" name="category_name">
			</div>
			<div class="col-sm-4">
				<input type="button" value="<?php echo $button_text;?>" name="save_category" class="btn btn-success" model="<?php echo $model;?>" id="btn-add-cat"/>
			</div>
		</div>
  	</form>
  	</div>
	<?php 
	die();
}

function hmgt_remove_category()
{
	//echo "Hello".$_POST['medicine_cat_id'];
	$model = $_REQUEST['model'];
	if($model == 'medicine')
	{
		$obj_medicine = new Hmgtmedicine();
		$obj_medicine->delete_medicine_category($_POST['cat_id']);
		die();
	}
	if($model == 'department')
	{
		$user_object=new Hmgtuser();
		$user_object->delete_staff_department($_POST['cat_id']);
		die();
	}
	if($model == 'bedtype')
	{
		$bed_type=new Hmgtbedmanage();
		$cat_result =$bed_type->delete_bed_type($_POST['cat_id']);
	}
	if($model == 'specialization')
	{
		$user_object=new Hmgtuser();
		$user_object->delete_doctor_specilize($_POST['cat_id']);
		die();
	}
	if($model == 'operation')
	{
		$operation_type=new Hmgt_operation();
		$operation_type->delete_operation_type($_POST['cat_id']);
		die();
	}
	
	if($model == 'report_type')
	{
		$report_type=new Hmgt_dignosis();
		$report_type->delete_report_type($_POST['cat_id']);
		die();
	}
	
}
function hmgt_add_category()
{
	global $wpdb;
	$model = $_REQUEST['model'];
	$array_var = array();
	$data['category_name'] = $_REQUEST['medicine_cat_name'];
	if($model == 'medicine')
	{
		$obj_medicine = new Hmgtmedicine();
		$obj_medicine->hmgt_add_medicinecategory($data);
		$id = $wpdb->insert_id;
	}
	if($model == 'department')
	{
		$user_object=new Hmgtuser();
		$user_object->add_staff_department($data);
		$id = $wpdb->insert_id;
	}
	if($model == 'bedtype')
	{
		$bed_type=new Hmgtbedmanage();
		$bed_type->hmgt_add_bedtype($data);
		$id = $wpdb->insert_id;
	}
	if($model == 'specialization')
	{
		$user_object=new Hmgtuser();
		$user_object->add_doctor_specilize($data);
		$id = $wpdb->insert_id;
	}
	if($model == 'operation')
	{
		$operation_type=new Hmgt_operation();
		$operation_type->hmgt_add_operationtype($data);
		$id = $wpdb->insert_id;
	}
	if($model == 'report_type')
	{
		$report_type=new Hmgt_dignosis();
		$report_type->hmgt_add_report_type($data);
		$id = $wpdb->insert_id;
	}
	
	$row1 = '<tr id="cat-'.$id.'"><td>'.$_REQUEST['medicine_cat_name'].'</td><td><a class="btn-delete-cat badge badge-delete" href="#" id='.$id.'>X</a></td></tr>';
	$option = "<option value='$id'>".$_REQUEST['medicine_cat_name']."</option>";
	$array_var[] = $row1;
	$array_var[] = $option;
	echo json_encode($array_var);
	die();
}


function hmgt_get_bednumber()
{
	$bed_type_id = $_POST['bed_type_id'];
	$obj_bed = new Hmgtbedmanage();
	$bedtype_data = $obj_bed->get_bed_by_bedtype($bed_type_id);	
	if(!empty($bedtype_data))
	{
		foreach ($bedtype_data as $retrieved_data)
		{
			echo '<option value="'.$retrieved_data->bed_id.'" '.selected($bed_type1,$retrieved_data->bed_id).'>'.$retrieved_data->bed_number.'</option>';
		}
	}
	
	
	die();
}

// Takes array of StdObjects
function hmgt_render_options($options,$name, $selected = 0 , $extraid ='',$array ='')
{
if($array==1)
$selectcontrol = '<select class="form-control" name="'.$name.'_id[]" id="'.$name.'_id'.$extraid.'">';
else
$selectcontrol = '<select class="form-control" name="'.$name.'_id" id="'.$name.'_id'.$extraid.'">';

foreach($options as $option){

if(is_array($option))
{$option = (object)$option;}
if($option->{$name.'_id'}==$selected)
$selectcontrol.='<option value="'.   $option->{$name.'_id'}.'" selected=selected>'.$option->{$name.'_name'}.'</option>';
else
$selectcontrol.='<option value="'.$option->{$name.'_id'}.'">'.$option->{$name.'_name'}.'</option>';
}
$selectcontrol .='</select>';
echo $selectcontrol;
}
// Takes array of StdObjects
function hmgt_render_doctors_options($options,$name, $selected = 0 , $extraid ='',$array ='')
{
if($array==1)
$selectcontrol = '<select class="form-control" name="'.$name.'_id[]" id="'.$name.'_id'.$extraid.'">';
else
$selectcontrol = '<select class="form-control" name="'.$name.'_id" id="'.$name.'_id'.$extraid.'">';
foreach($options as $option){
if(is_array($option))
{$option = (object)$option;}
if($option->{$name.'_id'}==$selected)
$selectcontrol.='<option value="'.$option->{$name.'_id'}.'" selected=selected>'.$option->{$name.'_name'}.'</option>';
else
$selectcontrol.='<option value="'.$option->{$name.'_id'}.'">'.$option->{$name.'_name'}.'</option>';
}
$selectcontrol .='</select>';
echo($selectcontrol);
}
// Takes array of StdObjects
function hmgt_render_options_duration($options,$name, $selected = 0 , $extraid ='',$array ='')
{
if($array==1)
$selectcontrol = '<select class="form-control" name="'.$name.'_id[]" id="'.$name.'_id'.$extraid.'">';
else
$selectcontrol = '<select class="form-control" name="'.$name.'_id" id="'.$name.'_id'.$extraid.'">';
foreach($options as $option){
if(is_array($option))
{$option = (object)$option;}
if($option->{$name.'_id'}==$selected)
$selectcontrol.='<option value="'.$option->{$name.'_id'}.'" selected=selected>'.$option->{$name.'_time'}.'</option>';
else
$selectcontrol.='<option value="'.$option->{$name.'_id'}.'">'.$option->{$name.'_time'}.'</option>';
}
$selectcontrol .='</select>';
echo $selectcontrol;
}

function hmgt_patient_status_view()
{
		 $uid=$_REQUEST['idtest'];
		 $obj_hospital = new Hospital_Management(get_current_user_id());
	?>
	<div class="modal-header"> <a href="#" class="close-btn-cat badge badge-success pull-right">X</a>
  		<h4 id="myLargeModalLabel" class="modal-title"><?php 
			
								
			$user=$user_info = get_userdata($uid);
			echo $user->display_name;
			?></h4>
	</div>
	<hr>
	
  	
  	<ul class="nav nav-tabs panel_tabs" role="tablist">
  	
  	 <?php if($obj_hospital->role == 'laboratorist' || $obj_hospital->role == 'doctor' || 
  	 		$obj_hospital->role == 'nurse' || $obj_hospital->role == 'patient' || $obj_hospital->role == 'administrator'){?>
      <li class="active">
          <a href="#diagnosis" role="tab" data-toggle="tab">
             <i class="fa fa-align-justify"></i> <?php _e(' Diagnosis Report', 'hospital_mgt'); ?></a>
          </a>
      </li>
      <?php }?>
      <li  class="<?php if($obj_hospital->role == 'pharmacist') {?>active<?php }?>"><a href="#doctor_note" role="tab" data-toggle="tab">
        <i class="fa fa-align-justify"></i> <?php _e('Doctor Notes', 'hospital_mgt'); ?></a> 
      </li>
      <?php if( $obj_hospital->role == 'doctor' || 
  	 		$obj_hospital->role == 'nurse' || $obj_hospital->role == 'patient' || $obj_hospital->role == 'administrator'){?>
	  <li><a href="#patient_history" role="tab" data-toggle="tab">
        <i class="fa fa-align-justify"></i> <?php _e('Patient History', 'hospital_mgt'); ?></a> 
      </li>
      <?php }?>
       <?php if($obj_hospital->role == 'laboratorist' || $obj_hospital->role == 'doctor' || 
  	 		$obj_hospital->role == 'nurse' || $obj_hospital->role == 'patient' || $obj_hospital->role == 'administrator'){?>
	   <li><a href="#nurse_notes" role="tab" data-toggle="tab" >
        <i class="fa fa-align-justify"></i> <?php _e('Nurse Notes', 'hospital_mgt'); ?></a> 
      </li>
      <?php }?>
    </ul>
		
		<div class="tab-content">
			<div class="tab-pane fade  <?php if($obj_hospital->role != 'pharmacist') {?> active in <?php }?>"  id="diagnosis">
				
				
				<div class="panel panel-white">
				
				
				<div class="panel-body patient_viewbox_full">
				 <?php if($obj_hospital->role == 'doctor' || $obj_hospital->role == 'laboratorist') {
				 if($obj_hospital->role == 'doctor' || $obj_hospital->role == 'laboratorist')
				 	$path = "?dashboard=user&page=diagnosis&tab=adddiagnosis&patient_id=".$uid;				
				 else 
				 	$path="?page=diagnosis&tab=adddiagnosis";
				 	?>
				<div class="print-button ">
					<a  href="<?php echo $path;?>" class="btn btn-success"><?php _e('Add Diagnosis Report','hospital_mgt');?></a>
				</div>
				<?php }
				 elseif($obj_hospital->role == 'administrator')
				 {	$path=admin_url()."admin.php?page=hmgt_diagnosis&tab=adddiagnosis&patient_id=".$uid;?>
				 <div class="print-button ">
					<a  href="<?php echo $path;?>" class="btn btn-success"><?php _e('Add Diagnosis Report ','hospital_mgt');?></a>
				</div>
				 <?php }?>
				<div class="clearfix"></div>
				
							<?php $diagnosis_obj=new Hmgt_dignosis(); 
							$diagnosisdata=$diagnosis_obj->get_diagnosis_by_patient($uid);
							foreach($diagnosisdata as $diagnosis){
							
							?>	
							<div class="form-group">
								<div class="col-xs-10">
								<blockquote class="diagnosis-report">
								 <b><?php $doctor_data= get_userdata($diagnosis->diagno_create_by);
								 echo __('Diagnosis report by','hospital_mgt')." ".$doctor_data->display_name." on ".$diagnosis->diagnosis_date;?> </b>
								 <?php if($diagnosis->attach_report!=""){?>
									<a href="<?php echo content_url().'/uploads/hospital_assets/'.$diagnosis->attach_report;?>" class="btn btn-default"><i class="fa fa-download"></i><?php _e('View Report','hospital_mgt');?></a>
									<?php }
										else{?>
											<a href="#" class="btn btn-default"><i class="fa fa-download"></i><?php _e('No Report','hospital_mgt');?></a>
										<?php }
										if($diagnosis->diagno_description!=""){?>
									<p>"<?php echo $diagnosis->diagno_description; ?>"</p>
										<?php }?>
								</blockquote>
								</div>
								</div>
							<?php } ?>
						</div>
						</div>
					
			
			</div>
			<div class="tab-pane fade  <?php if($obj_hospital->role == 'pharmacist') {?> active in <?php }?>" id="doctor_note">
			
				<div class="panel-body patient_viewbox_full ">
				 <?php if($obj_hospital->role == 'doctor') {
				  if($obj_hospital->role == 'doctor' || $obj_hospital->role == 'laboratorist')
				 	$path = "?dashboard=user&page=prescription&tab=addprescription&patient_id=".$uid;
				 else 
				 	$path="?page=diagnosis&tab=adddiagnosis";
				 	?>
				<div class="print-button">
						<a  href="<?php echo $path;?>" class="btn btn-success"><?php _e('Add Prescription','hospital_mgt');?></a>
				</div>
				<?php }
				elseif($obj_hospital->role == 'administrator')
								 {	$path=admin_url()."admin.php?page=hmgt_prescription&tab=addprescription&patient_id=".$uid;?>
								 <div class="print-button">
									<a  href="<?php echo $path;?>" class="btn btn-success"><?php _e('Add Prescription','hospital_mgt');?></a>
								</div>
								 <?php }?>
				
							<?php 
							$patient_id="";
							$patientreport=display_patient_reports($uid);
							foreach($patientreport as $report) {
							//var_dump($patientreport);
							$patient_id=$report->patient_id;
							?>
							<div class="col-md-10">
								<p>
								<label  class="control-label create-date" style="border: 1px solid;">
									<?php  echo $report->pris_create_date; ?></b>
								</label>
								</div>
							<div class="form-group doctor_note_part">
							<div class="col-md-10 date_wise">
								<b><?php _e("case History","hospital_mgt");?></b>
									<p><?php 
									echo $report->case_history;?></p>
								</div>
								
								<div class="col-md-10 date_wise">
								<hr><b><?php _e("Medicine List","hospital_mgt");?></b>
									
									<p>
									<div class="table-responsive">
									<table class="table">
									<thead>
									<tr>
									<th><?php _e('Name','hospital_mgt');?></th>
									<th><?php _e('Times ','hospital_mgt');?></th>
									<th><?php _e('Days','hospital_mgt');?></th>
									</tr>
									</thead>
									<tbody>
									<?php
										$obj_medicine = new Hmgtmedicine();
										$medicine_list=json_decode($report->medication_list);
										foreach($medicine_list as $retrieved_data)
										{?>
											<tr>
											
											<td><?php 
												$medicine=$obj_medicine->get_single_medicine($retrieved_data->medication_name);
											echo $medicine->medicine_name; ?></td>
											<td><?php echo $retrieved_data->time; ?></td>
											<td><?php echo $retrieved_data->per_days; ?></td>
											</tr>
										<?php }
									//echo $report->medication_list;?>
									</tbody>
									</table>
									</div>
									</p>
								</div>
								
								<div class="col-md-10 date_wise">
								<hr><b><?php _e("Extra Note","hospital_mgt");?></b>
									<p><?php 
									echo $report->treatment_note;?></p>
								</div>
								</div>
						
						<?php } 
								$user_object=new Hospital_Management();
								$patient_note_id=$user_object-> get_nurse_notes($patient_id);
						?>
									<div class="col-xs-10 doctor_notes">
							<?php foreach($patient_note_id as $notepost_id){
								$note_data= get_post($notepost_id->post_id);
								if($note_data->post_type=='doctor_notes' || $note_data->post_type=='administrator_notes'){
								echo '<div class="col-md-10 ">';
								echo '<blockquote id="note-'.$notepost_id->post_id.'">';
								echo  '<b><h4>';
								
								
								
								$nurse=get_userdata($note_data->post_author);
								 echo __('Notes by','hospital_mgt')." ".$nurse->display_name." on ".$note_data->post_date.'</h4></b>';
								echo '<p>'.$note_data->post_content.'</p>';
								echo '</blockquote>';
								echo '</div>';
								if( $obj_hospital->role == 'administrator' || (  $obj_hospital->role == 'doctor'&& $note_data->post_author ==  get_current_user_id()))
								{
								echo '<div class="col-md-1 ">';
								echo '<a class="btn-delete-note badge badge-delete" href="#" id="notex-'.$notepost_id->post_id.'" noteid='.$notepost_id->post_id.'>X</a>';
								echo '</div>';
								}
								}
								
							 }?>
							</div>
							
					
						
							
						
						</div>
						<?php if($obj_hospital->role == 'doctor' || $obj_hospital->role == 'administrator'){?>
							<div class="panel-body">
							 <form name="medicinecat_form" action="" method="post" class="form-horizontal" id="medicinecat_form">
			<div class="form-group">
			<label class="col-sm-4 control-label" for="medicine_name"><?php _e('Add Note','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-4">
				<input id="doctor_note_text" class="form-control text-input" type="text" name="doctor_note">
				<input type="hidden" value="<?php if(isset($patient_id))echo $patient_id;?>" name="patient_id" id="patient_id">
				<input type="hidden" value="<?php echo get_current_user_id();?>" name="docotr_id" id="docotr_id">
				
			</div>
			<div class="col-sm-4">
				<input type="button" value="Save Note" name="save_note" class="btn btn-success"  id="btn-add-doctor-note" note_by="<?php echo $obj_hospital->role;?>"/>
			</div>
		</div>
  	</form>
	</div>	
						<?php }?>
		</div>
			<div class="tab-pane fade" id="patient_history">
			<div class="panel panel-white">
				
						<div class="panel-body patient_viewbox_full">
							
						
							<div class="form-group">
								<div class="col-xs-10">
								<p>
								<div class="table-responsive">
									<table class="table">
									<thead>
									<tr>
									<th><?php _e('Date','hospital_mgt');?></th>
									<th><?php _e('Status ','hospital_mgt');?></th>
									<th><?php _e('Guardian','hospital_mgt');?></th>
									</tr>
									</thead>
									<tbody>
									<?php
										$patient_history_data=get_patient_history_data($uid);
										
										foreach($patient_history_data as $retrieved_data)
										{?>
											<tr>
											
											<td><?php echo $retrieved_data->history_date; ?></td>
											<td><?php echo $retrieved_data->status; ?></td>
											<td><?php echo $retrieved_data->guardian_name; ?></td>
											</tr>
										<?php } ?>
									</tbody>
									</table>
									</div>
									</p>
							</div>
						</div>
						</div>
						
			</div>
		</div>
		 <div class="tab-pane fade" id="nurse_notes">
			
				<?php hmgt_add_remove_nurse_notes($uid); ?>
					
			
			</div>
	</div>
  	
  
	<?php 
	die();
	
}
//------------add remove notes list-------
function hmgt_add_remove_nurse_notes($patient_id)
{
	$obj_hospital = new Hospital_Management(get_current_user_id());
	?>

	<div class="panel panel-white">
				<div class="panel-body patient_viewbox">
					
							<?php 
							$user_object=new Hospital_Management();
							 $patient_note_id=$user_object-> get_nurse_notes($patient_id);
							//print_r($patient_note_id);?>
							<div class="form-group">
								<div class="col-xs-10 nurse_notes">
							<?php foreach($patient_note_id as $notepost_id){
								$note_data= get_post($notepost_id->post_id);
								if($note_data->post_type=='nurse_notes' || $note_data->post_type=='administrator_notes'){
								echo '<div class="col-md-10 ">';
								echo '<blockquote id="note-'.$notepost_id->post_id.'">';
								echo  '<b><h4>';
								
								
								
								$nurse=get_userdata($note_data->post_author);
								 echo __('Notes by','hospital_mgt')." ".$nurse->display_name." on ".$note_data->post_date.'</h4></b>';
								echo '<p>'.$note_data->post_content.'</p>';
								echo '</blockquote>';
								echo '</div>';
								if(  $obj_hospital->role == 'administrator' || ( $obj_hospital->role == 'nurse' && $note_data->post_author ==  get_current_user_id()))
								{
								echo '<div class="col-md-1 ">';
								echo '<a class="btn-delete-note badge badge-delete" href="#" id="notex-'.$notepost_id->post_id.'" noteid='.$notepost_id->post_id.'>X</a>';
								echo '</div>';
								}
								}
								
							 }?>
							</div>
								</div>
						</div>	
						
						<?php if($obj_hospital->role == 'nurse' || $obj_hospital->role == 'administrator'){?>
						<div class="panel-body">
							 <form name="medicinecat_form" action="" method="post" class="form-horizontal" id="medicinecat_form">
			<div class="form-group">
			<label class="col-sm-4 control-label" for="medicine_name"><?php _e('Add Note','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-4">
				<input id="nurse_note_text" class="form-control text-input" type="text" name="nurse_note">
				<input type="hidden" value="<?php echo $patient_id;?>" name="patient_id" id="patient_id">
				<input type="hidden" value="<?php echo get_current_user_id();?>" name="nurse_id" id="nurse_id">
				
			</div>
			<div class="col-sm-4">
				<input type="button" value="Save Note" name="save_note" class="btn btn-success"  id="btn-add-note" note_by="<?php echo $obj_hospital->role;?>"/>
			</div>
		</div>
  	</form>
	</div>	
						<?php }?>
			</div>
<?php }
//-----------End list of notes----------
//---------Add nurse notes-------------------------
function hmgt_add_nurse_notes() 
{
	global $wpdb;
	
	$array_var = array();
	if($_REQUEST['note_by']=="doctor" || $_REQUEST['note_by']=="administrator")
		$data['note'] = $_REQUEST['doctor_note'];
	if($_REQUEST['note_by']=="nurse" || $_REQUEST['note_by']=="administrator")
		$data['note'] = $_REQUEST['nurse_note'];
	$data['note_by'] = 'nurse';
	$data['patient_id'] = $_REQUEST['patient_id'];
	
	if(!empty($data))
	{
		$obj_hospital = new Hospital_Management();
		$obj_hospital->hmgt_add_nurse_note($data);
		$id = $wpdb->insert_id;
	}
	$nurse=get_userdata(get_current_user_id());
	 $row='<div class="col-md-10 "><blockquote id="note-'.$id.'"><b>'.__('Notes by','hospital_mgt').' '.$nurse->display_name.' on '.date("Y-m-d").'</b><p>'.$data['note'].'</p></blockquote></div><div class="col-md-1 "><a class="btn-delete-note badge badge-delete" href="#" id="notex-'.$id.'" noteid='.$id.'>X</a></div>';
	
	$array_var[] = $row;
	echo json_encode($array_var);
	die();
}

function hmgt_add_doctor_notes()
{
	global $wpdb;

	$array_var = array();
	if($_REQUEST['note_by']=="doctor" || $_REQUEST['note_by']=="administrator")
		$data['note'] = $_REQUEST['doctor_note'];
	
	$data['note_by'] = 'doctor';
	$data['patient_id'] = $_REQUEST['patient_id'];

	if(!empty($data))
	{
		$obj_hospital = new Hospital_Management();
		$obj_hospital->hmgt_add_nurse_note($data);
		$id = $wpdb->insert_id;
	}
	$nurse=get_userdata(get_current_user_id());
	$row='<div class="col-md-10 "><blockquote id="note-'.$id.'"><b>'.__('Notes by','hospital_mgt').' '.$nurse->display_name.' on '.date("Y-m-d").'</b><p>'.$data['note'].'</p></blockquote></div><div class="col-md-1 "><a class="btn-delete-note badge badge-delete" href="#" id="notex-'.$id.'" noteid='.$id.'>X</a></div>';

	$array_var[] = $row;
	echo json_encode($array_var);
	die();
}

function hmgt_remove_nurse_note()
{
	$obj_hospital = new Hospital_Management();
		$obj_hospital->delete_nurse_note($_POST['note_id']);
		die();
}
//--------patient charges-------------------------
function get_patient_cherges($patient_id)
{
	global $wpdb;
	$table_name = $wpdb->prefix .'hmgt_charges';
	
	$charges_data=$wpdb->get_results("SELECT * FROM $table_name where patient_id='$patient_id'");
	
	return $charges_data;
}
function hmgt_patient_charges_view()
{
	 $uid=$_REQUEST['idtest'];
	
	?>
	<div class="modal-header"> <a href="#" class="close-btn-cat badge badge-success pull-right">X</a>
  		<h4 id="myLargeModalLabel" class="modal-title"><?php 
			
								
			$user=$user_info = get_userdata($uid);
			echo $user->display_name;
			?></h4>
	</div>
	<hr>
	<div class="panel panel-white">
				<div class="panel-body patient_viewbox_full">
				<div class="form-group">
								<div class="col-xs-10">
								<p>
								<div class="table-responsive">
									<table class="table">
									<thead>
									<tr>
									<th><?php _e('Date','hospital_mgt');?></th>
									<th><?php _e('Charge Name','hospital_mgt');?></th>
									<th><?php _e('Charges','hospital_mgt');?></th>
									</tr>
									</thead>
									<tbody>
									<?php
										
										$patient_charges_data=get_patient_cherges($uid);
										foreach($patient_charges_data as $retrieved_data)
										{?>
											<tr>
											
											<td><?php echo $retrieved_data->created_date; ?></td>
											<td><?php echo $retrieved_data->charge_label; ?></td>
											<td><?php echo $retrieved_data->charges; ?></td>
											</tr>
										<?php } ?>
									</tbody>
									</table>
									</div>
									</p>
							</div>
						</div>
						</div>
				</div>
	</div>
<?php
	
	
	die();
}
//-----------view paient invoice-----------------
function get_invoice_data($invoice_id)
{
	global $wpdb;
		$table_invoice= $wpdb->prefix. 'hmgt_invoice';
		$result = $wpdb->get_row("SELECT *FROM $table_invoice where invoice_id = ".$invoice_id);
		return $result;
}
function hmgt_patient_invoice_view()
{
	$obj_invoice= new Hmgtinvoice();
	if($_POST['invoice_type']=='invoice')
	{
	$invoice_data=get_invoice_data($_POST['idtest']);
	}
	if($_POST['invoice_type']=='income'){
	$income_data=$obj_invoice->hmgt_get_income_data($_POST['idtest']);
	}
	if($_POST['invoice_type']=='expense'){
	$expense_data=$obj_invoice->hmgt_get_income_data($_POST['idtest']);

	}
	//var_dump($income_data);
	//exit;
	?>
		
			<div class="modal-header">
			<a href="#" class="close-btn-cat badge badge-success pull-right">X</a>
			<h4 class="modal-title"><?php echo get_option('hmgt_hospital_name');?></h4>
			</div>
			<div class="modal-body" style="height:500px; overflow:auto;">
				<div id="invoice_print"> 
					<table width="100%" border="0">
						<tbody>
							<tr>
								<td width="70%">
									<img style="max-height:80px;" src="<?php echo get_option( 'hmgt_hospital_logo' ); ?>">
								</td>
								<td align="right" width="24%">
									<h4><?php
									if(!empty($invoice_data)){
										$invoice_no=$invoice_data->invoice_number;
										_e('Invoice number','hospital_mgt');
										echo " : ".$invoice_no;
										}
									?> </h4>
									<h5><?php $issue_date='DD-MM-YYYY';
												if(!empty($income_data)){
													$issue_date=$income_data->income_create_date;
													$payment_status=$income_data->payment_status;}
												if(!empty($invoice_data)){
													$issue_date=$invoice_data->invoice_create_date;
													$payment_status=$invoice_data->status;	}
												if(!empty($expense_data)){
													$issue_date=$expense_data->income_create_date;
													$payment_status=$expense_data->payment_status;}
									
									echo __('Issue Date','hospital_mgt')." : ".$issue_date;?></h5>
									<h5><?php echo __('Status','hospital_mgt')." : ".$payment_status;?></h5>
								</td>
							</tr>
						</tbody>
					</table>
					<hr>
					<table width="100%" border="0">
						<tbody>
							<tr>
								<td align="left">
									<h4><?php _e('Payment To','hospital_mgt');?> </h4>
								</td>
								<td align="right">
									<h4><?php _e('Bill To','hospital_mgt');?> </h4>
								</td>
							</tr>
							<tr>
								<td valign="top" align="left">
									<?php echo get_option( 'hmgt_hospital_name' )."<br>"; 
									 echo get_option( 'hmgt_hospital_address' ).","; 
									 echo get_option( 'hmgt_contry' )."<br>"; 
									 echo get_option( 'hmgt_contact_number' )."<br>"; 
									?>
									
								</td>
								<td valign="top" align="right">
									<?php 
									if(!empty($expense_data)){
									echo $party_name=$expense_data->party_name; 
									}
									else
									{
										if(!empty($income_data))
											$patiet_id=$income_data->party_name;
										 if(!empty($invoice_data))
											$patiet_id=$invoice_data->patient_id;
										
										
										
										$patient=get_userdata($patiet_id);
												
										echo $patient->display_name."<br>"; 
										 echo get_user_meta( $patiet_id,'address',true ).","; 
										 echo get_user_meta( $patiet_id,'city_name',true ).","; 
										 echo get_user_meta( $patiet_id,'zip_code',true ).","; 
										 echo get_user_meta( $patiet_id,'country_name',true )."<br>"; 
										 echo get_user_meta( $patiet_id,'mobile',true )."<br>"; 
									}
									?>
								</td>
							</tr>
						</tbody>
					</table>
					<hr>
					<h4><?php _e('Invoice Entries','hospital_mgt');?></h4>
					<table class="table table-bordered" width="100%" border="1" style="border-collapse:collapse;">
						<thead>
							<tr>
								<th class="text-center">#</th>
								<th class="text-center"> <?php _e('Date','hospital_mgt');?></th>
								<th width="60%"><?php _e('Entry','hospital_mgt');?> </th>
								<th><?php _e('Price','hospital_mgt');?></th>
								<th class="text-center"> <?php _e('Username','hospital_mgt');?> </th>
							</tr>
						</thead>
						<tbody>
						<?php 
							$id=1;
							$total_amount=0;
						if(!empty($income_data) || !empty($expense_data)){
							if(!empty($expense_data))
								$income_data=$expense_data;
							
							$patient_all_income=$obj_invoice->get_onepatient_income_data($income_data->party_name);
							foreach($patient_all_income as $result_income){
								$income_entries=json_decode($result_income->income_entry);
								foreach($income_entries as $each_entry){
								$total_amount+=$each_entry->amount;
								
						?>
							<tr>
								<td class="text-center"><?php echo $id;?></td>
								<td class="text-center"><?php echo $result_income->income_create_date;?></td>
								<td><?php echo $each_entry->entry; ?> </td>
								<td class="text-right"> <?php echo $each_entry->amount; ?></td>
								<td class="text-center"><?php echo hmgt_get_display_name($result_income->income_create_by);?></td>
							</tr>
								<?php $id+=1;}
								}
						 
						}
						 if(!empty($invoice_data)){
							 $total_amount=$invoice_data->invoice_amount
							 ?>
							<tr>
								<td class="text-center"><?php echo $id;?></td>
								<td class="text-center"><?php echo $invoice_data->invoice_create_date;?></td>
								<td><?php echo $invoice_data->invoice_title; ?> </td>
								<td class="text-right"> <?php echo $invoice_data->invoice_amount; ?></td>
								<td class="text-center"><?php echo hmgt_get_display_name($invoice_data->invoice_create_by);?></td>
							</tr>
							<?php }?>
						</tbody>
					</table>
					<table width="100%" border="0">
						<tbody>
							
							<?php if(!empty($invoice_data)){
								 $vat = $total_amount * ($invoice_data->vat_percentage / 100);
								$total_with_tax=$total_amount + $vat;
								$grand_total=$total_with_tax-$invoice_data->discount;
								?>
							<tr>
								<td width="80%" align="right"><?php _e('Sub Total :','hospital_mgt');?></td>
								<td align="right"><?php echo $total_amount;?></td>
							</tr>
							<tr>
								<td width="80%" align="right"><?php _e('Vat Percentage :','hospital_mgt');?></td>
								<td align="right"><?php echo $vat;?></td>
							</tr>
							<tr>
								<td width="80%" align="right"><?php _e('Discount :','hospital_mgt');?></td>
								<td align="right"><?php echo $invoice_data->discount;?></td>
							</tr>
							<tr>
								<td colspan="2">
									<hr style="margin:0px;">
								</td>
							</tr>
							<?php
							}
							if(!empty($income_data)){
								$grand_total=$total_amount;
							}
							?>								
							<tr>
								<td width="80%" align="right"><?php _e('Grand Total :','hospital_mgt');?></td>
								<td align="right"><h4><?php echo $grand_total; ?></h4></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="print-button pull-left">
						<a  href="?page=invoice&print=print&invoice_id=<?php echo $_POST['idtest'];?>&invoice_type=<?php echo $_POST['invoice_type'];?>" target="_blank"class="btn btn-success"><?php _e('Print','hospital_mgt');?></a>
				</div>
			</div>
			
				
			
		
	
	
	
	<?php die();
}
function hmgt_patient_invoice_print($invoice_id)
{
	$obj_invoice= new Hmgtinvoice();
	if($_REQUEST['invoice_type']=='invoice')
	{
		$invoice_data=get_invoice_data($invoice_id);
	}
	if($_REQUEST['invoice_type']=='income'){
		$income_data=$obj_invoice->hmgt_get_income_data($invoice_id);
	}
	if($_REQUEST['invoice_type']=='expense'){
		$expense_data=$obj_invoice->hmgt_get_income_data($invoice_id);

	}
	//$invoice_data=get_invoice_data($invoice_id);
	//var_dump($income_data);
	//exit;
	?>
		
			
			<div class="modal-body">
				<div id="invoice_print" style="width: 90%;margin:0 auto;"> 
				<div class="modal-header">
			
			<h4 class="modal-title"><?php echo get_option('hmgt_hospital_name');?></h4>
			</div>
					<table width="100%" border="0">
						<tbody>
							<tr>
								<td width="70%">
									<img style="max-height:80px;" src="<?php echo get_option( 'hmgt_hospital_logo' ); ?>">
								</td>
								<td width="24%" align="right">
								<h4><?php
									if(!empty($invoice_data)){
										$invoice_no=$invoice_data->invoice_number;
										_e('Invoice number','hospital_mgt');
										echo " : ".$invoice_no;
										}
									?> </h4>
									<h4><?php // _e('Invoice number','hospital_mgt');?> </h4>
									<h5><?php $issue_date='DD-MM-YYYY';
												if(!empty($income_data)){
													$issue_date=$income_data->income_create_date;
													$payment_status=$income_data->payment_status;}
												if(!empty($invoice_data)){
													$issue_date=$invoice_data->invoice_create_date;
													$payment_status=$invoice_data->status;	}
												if(!empty($expense_data)){
													$issue_date=$expense_data->income_create_date;
													$payment_status=$expense_data->payment_status;}
									
									echo __('Issue Date','hospital_mgt')." : ".$issue_date;?></h5>
									<h5><?php echo __('Status','hospital_mgt')." : ".$payment_status;?></h5>
								</td>
							</tr>
						</tbody>
					</table>
					<hr>
					<table width="100%" border="0">
						<tbody>
							<tr>
								<td align="left">
									<h4><?php _e('Payment To','hospital_mgt');?> </h4>
								</td>
								<td align="right">
									<h4><?php _e('Bill To','hospital_mgt');?> </h4>
								</td>
							</tr>
							<tr>
								<td valign="top" align="left">
									<?php echo get_option( 'hmgt_hospital_name' )."<br>"; 
									 echo get_option( 'hmgt_hospital_address' ).","; 
									 echo get_option( 'hmgt_contry' )."<br>"; 
									 echo get_option( 'hmgt_contact_number' )."<br>"; 
									?>
									
								</td>
								<td valign="top" align="right">
									<?php 
									if(!empty($expense_data)){
									echo $party_name=$expense_data->party_name; 
									}
									else
									{
										if(!empty($income_data))
											$patiet_id=$income_data->party_name;
										 if(!empty($invoice_data))
											$patiet_id=$invoice_data->patient_id;
										
										
										
										$patient=get_userdata($patiet_id);
												
										echo $patient->display_name."<br>"; 
										 echo get_user_meta( $patiet_id,'address',true ).","; 
										 echo get_user_meta( $patiet_id,'city_name',true ).","; 
										 echo get_user_meta( $patiet_id,'zip_code',true ).","; 
										 echo get_user_meta( $patiet_id,'country_name',true )."<br>"; 
										 echo get_user_meta( $patiet_id,'mobile',true )."<br>"; 
									}
									?>
								</td>
							</tr>
						</tbody>
					</table>
					<hr>
					<h4><?php _e('Invoice Entries','hospital_mgt');?></h4>
					<table class="table table-bordered" width="100%" border="1" style="border-collapse:collapse;">
						<thead>
							<tr>
								<th class="text-center">#</th>
								<th width="60%"><?php _e('Entry','hospital_mgt');?> </th>
								<th><?php _e('Price','hospital_mgt');?></th>
							</tr>
						</thead>
						<tbody>
						<?php 
							$id=1;
							$total_amount=0;
						if(!empty($income_data) || !empty($expense_data)){
							if(!empty($expense_data))
								$income_data=$expense_data;
							
							$patient_all_income=$obj_invoice->get_onepatient_income_data($income_data->party_name);
							foreach($patient_all_income as $result_income){
								$income_entries=json_decode($result_income->income_entry);
								foreach($income_entries as $each_entry){
								$total_amount+=$each_entry->amount;
								
						?>
							<tr>
								<td class="text-center"><?php echo $id;?></td>
								<td><?php echo $each_entry->entry; ?> </td>
								<td class="text-right"> <?php echo $each_entry->amount; ?></td>
							</tr>
								<?php $id+=1;}
								}
						 
						}
						 if(!empty($invoice_data)){
							 $total_amount=$invoice_data->invoice_amount
							 ?>
							<tr>
								<td class="text-center"><?php echo $id;?></td>
								<td><?php echo $invoice_data->invoice_title; ?> </td>
								<td class="text-right"> <?php echo $invoice_data->invoice_amount; ?></td>
							</tr>
							<?php }?>
						</tbody>
					</table>
					<table width="100%" border="0">
						<tbody>
							
							<?php if(!empty($invoice_data)){
								 $vat = $total_amount * ($invoice_data->vat_percentage / 100);
								$total_with_tax=$total_amount + $vat;
								$grand_total=$total_with_tax-$invoice_data->discount;
								?>
							<tr>
								<td width="80%" align="right"><?php _e('Sub Total :','hospital_mgt');?></td>
								<td align="right"><?php echo $total_amount;?></td>
							</tr>
							<tr>
								<td width="80%" align="right"><?php _e('Vat Percentage :','hospital_mgt');?></td>
								<td align="right"><?php echo $vat;?></td>
							</tr>
							<tr>
								<td width="80%" align="right"><?php _e('Discount :','hospital_mgt');?></td>
								<td align="right"><?php echo $invoice_data->discount;?></td>
							</tr>
							<tr>
								<td colspan="2">
									<hr style="margin:0px;">
								</td>
							</tr>
							<?php
							}
							if(!empty($income_data)){
								$grand_total=$total_amount;
							}
							?>								
							<tr>
								<td width="80%" align="right"><?php _e('Grand Total :','hospital_mgt');?></td>
								<td align="right"><h4><?php echo $grand_total; ?></h4></td>
							</tr>
						</tbody>
					</table>
				</div>
				
			</div>
	
	<?php die();
}
function hmgt_print_init()
{
	if(isset($_REQUEST['print']) && $_REQUEST['print'] == 'print' && $_REQUEST['page'] == 'invoice')
	{
		?>
<script>window.onload = function(){ window.print(); };</script>
<?php 
				
				hmgt_patient_invoice_print($_REQUEST['invoice_id']);
				exit;
			}
	
			if(isset($_REQUEST['print']) && $_REQUEST['print'] == 'print' && $_REQUEST['page'] == 'hmgt_prescription')
			{
				?>
			<script>window.onload = function(){ window.print(); };</script>
			<?php 
							
							hmgt_print_priscription($_REQUEST['prescription_id']);
							exit;
						}
			
}
add_action('init','hmgt_print_init');
//--------End nurse notes-------------------------
function get_patient_history_data($patient_id)
{
		global $wpdb;
		$table_history= $wpdb->prefix. 'hmgt_history';
		$result = $wpdb->get_results("SELECT *FROM $table_history where patient_id = ".$patient_id);
		return $result;
}
//----- View Report ------
function hmgt_view_report()
{
	 $notice = $_REQUEST['evnet_id'];
	//var_dump($notice);
	$obj_dignosis = new Hmgt_dignosis();
	$result = $obj_dignosis->get_single_dignosis_report($notice);
	
	?>
<div class="form-group"> 	<a href="#" class="close-btn-cat badge badge-success pull-right">X</a>
  <h4 class="modal-title" id="myLargeModalLabel">
    <?php _e('View Report','hospital_mgt'); ?>
  </h4>
</div>
<hr>
<div class="panel panel-white form-horizontal">
  <div class="form-group">
    <label class="col-sm-3" for="notice_title">
    <?php _e(' Report Type','hospital_mgt');?>
    : </label>
    <div class="col-sm-9"> <?php echo get_the_title($result->report_type);?> </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3" for="notice_title">
    <?php _e(' Report Cost','hospital_mgt');?>
    : </label>
    <div class="col-sm-9"> <?php echo $result->report_cost;?> </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3" for="notice_title">
    <?php _e('Description','hospital_mgt');?>
    : </label>
    <div class="col-sm-9"> <?php echo $result->diagno_description;?> 
    </div>
  </div>
 
</div>
<?php 
	die();
}
//---------view event----------------
function hmgt_view_event()
{
	 $notice = get_post($_REQUEST['evnet_id']);
	 //var_dump($notice);
	
	 ?>
<div class="form-group"> 	<a href="#" class="close-btn-cat badge badge-success pull-right">X</a>
  <h4 class="modal-title" id="myLargeModalLabel">
    <?php _e('Event Detail','hospital_mgt'); ?>
  </h4>
</div>
<hr>
<div class="panel panel-white form-horizontal">
  <div class="form-group">
    <label class="col-sm-3" for="notice_title">
    <?php _e(' Title','hospital_mgt');?>
    : </label>
    <div class="col-sm-9"> <?php echo $notice->post_title;?> </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3" for="notice_title">
    <?php _e(' Comment','hospital_mgt');?>
    : </label>
    <div class="col-sm-9"> <?php echo $notice->post_content;?> </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3" for="notice_title">
    <?php _e('Event/Notice For','hospital_mgt');?>
    : </label>
    <div class="col-sm-9"> <?php //echo get_post_meta( $notice->ID, 'notice_for',true);
    if(get_post_meta( $notice->ID, 'notice_for',true) == 'all')
    	echo get_post_meta( $notice->ID, 'notice_for',true);
    else
    	echo get_role_name_in_message(get_post_meta( $notice->ID, 'notice_for',true));
    ?> 
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3" for="notice_title">
    <?php _e('Start Date','hospital_mgt');?>
    : </label>
    <div class="col-sm-9"> <?php echo get_post_meta( $notice->ID, 'start_date',true);?> </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3" for="notice_title">
    <?php _e('End Date','hospital_mgt');?>
    : </label>
    <div class="col-sm-9"> <?php echo get_post_meta( $notice->ID, 'end_date',true);?> </div>
  </div>
</div>
<?php 
	die();
}
function hmgt_print_priscription($presciption_id)
{
	$obj_medicine = new Hmgtmedicine();
	$obj_treatment=new Hmgt_treatment();
	$obj_prescription=new Hmgtprescription();
	$result = $obj_prescription->get_prescription_data($presciption_id);
	?>
			
			
			<div class="panel-body prescription_popprint_content" style="width: 80%;margin:25px auto;">
			<div class="modal-header">
			
			<h4 class="modal-title"><?php echo get_option('hmgt_hospital_name');?></h4>
			</div>
			<hr>
			<table width="100%" border="0">
	            <tbody><tr>
	                <td valign="top" align="left">
	                        <?php _e('Patient Name','hospital_mgt');?> : <?php echo hmgt_get_display_name($result->patient_id);?><br>
	                    
	                        <?php _e('Sex','hospital_mgt');?> : <?php echo get_user_meta($result->patient_id,'gender',true);?> <br>
	                                    </td>
	                <td valign="top" align="right">
	                   <?php _e('Doctor Name','hospital_mgt');?> : <?php echo hmgt_get_display_name($result->prescription_by);?><br>
	                   <?php _e('Date','hospital_mgt');?> : <?php echo $result->pris_create_date;?><br>
	                  
	                </td>
	            </tr>
	        	</tbody>
        	 </table>
        	 <hr>
        	 <div class="row">
            <div class="col-md-12">

                <div data-collapsed="0" class="panel panel-primary">
                        
                    <div class="panel-body">
                            
                        <b><?php _e('Case History','hospital_mgt');?> :</b>
                        
                        <p><?php echo $result->case_history;?></p>                        
                        <hr>                            
                        <b><?php _e('Treatment','hospital_mgt');?> :</b>
                        
                        <p><?php echo $treatment=$obj_treatment->get_treatment_name($result->teratment_id);?></p>   
                        <hr>
                        <b> <?php _e('Medication','hospital_mgt');?>:</b>
                       
                        <div class="table-responsive">
                        <table class="table" >
                        <tr>
                        <th align="left"><?php _e('Name','hospital_mgt');?></th>
                        <th align="left"><?php _e('Times','hospital_mgt');?></th>
                        <th align="left"><?php _e('Days','hospital_mgt');?></th>
                        </tr>
                        <?php 
                        $all_medicine_list=json_decode($result->medication_list);
                        if(!empty($all_medicine_list))
                        {
                        	foreach($all_medicine_list as $retrieved_data){
                        	?>
                        	<tr>
											
											<td><?php 
												$medicine=$obj_medicine->get_single_medicine($retrieved_data->medication_name);
											echo $medicine->medicine_name; ?></td>
											<td><?php echo $retrieved_data->time; ?></td>
											<td><?php echo $retrieved_data->per_days; ?></td>
											</tr>
                        	<?php 	
                        	}
                        }
                        ?>
                        
                       
                        </table>
                        </div>
                        <hr>
                        
                        <b><?php _e('Extra Note','hospital_mgt');?> :</b>
                        
                        <p><?php echo $result->treatment_note;?></p>
                        
						 <?php 
                      $all_entry=json_decode($result->custom_field);
                      if(!empty($all_entry))
                      {
                      	
                      	foreach($all_entry as $entry){
                      		?>
                      		<hr>
                      		<b><?php  echo $entry->label;?></b>
                      		<P><?php echo $entry->value;?></P>
                      		
                      		<?php 
                      	}
                      }
                        ?>
                        <hr>
                    </div>

                </div>
				
            </div>
        	</div>
        	 </div>
	<?php 
	die();
}
function hmgt_view_priscription()
{
	$obj_medicine = new Hmgtmedicine();
	$obj_treatment=new Hmgt_treatment();
	$obj_prescription=new Hmgtprescription();
	$result = $obj_prescription->get_prescription_data($_REQUEST['prescription_id']);
	?>
			<div class="modal-header">
			<a href="#" class="close-btn-cat badge badge-success pull-right">X</a>
			<h4 class="modal-title"><?php echo get_option('hmgt_hospital_name');?></h4>
			</div>
			<hr>
			<div class="panel-body prescription_pop_content">
			<table width="100%" border="0">
	            <tbody><tr>
	                <td valign="top" align="left">
	                        <?php _e('Patient Name','hospital_mgt');?> : <?php echo hmgt_get_display_name($result->patient_id);?><br>
	                    
	                        <?php _e('Sex','hospital_mgt');?> : <?php echo get_user_meta($result->patient_id,'gender',true);?> <br>
	                                    </td>
	                <td valign="top" align="right">
	                   <?php _e('Doctor Name','hospital_mgt');?> : <?php echo hmgt_get_display_name($result->prescription_by);?><br>
	                   <?php _e('Date','hospital_mgt');?> : <?php echo $result->pris_create_date;?><br>
	                  
	                </td>
	            </tr>
	        	</tbody>
        	 </table>
        	 <hr>
        	 <div class="row">
            <div class="col-md-12">

                <div data-collapsed="0" class="panel panel-primary">
                        
                    <div class="panel-body">
                            
                        <b><?php _e('Case History','hospital_mgt');?> : </b>
                        
                        <p><?php echo $result->case_history;?></p>                        
                        <hr>     
                         <b><?php _e('Treatment','hospital_mgt');?> : </b>
                        
                        <p><?php echo $treatment=$obj_treatment->get_treatment_name($result->teratment_id);?></p>   
                        <hr>                       
                        <b> <?php _e('Medication','hospital_mgt');?> : </b>
                        <div class="table-responsive">
                        <table class="table">
                        <tr>
                        <th><?php _e('Name','hospital_mgt');?></th>
                        <th><?php _e('Times','hospital_mgt');?></th>
                        <th><?php _e('Days','hospital_mgt');?></th>
                        </tr>
                        <?php 
                        $all_medicine_list=json_decode($result->medication_list);
                        if(!empty($all_medicine_list))
                        {
                        	foreach($all_medicine_list as $retrieved_data){
                        	?>
                        	<tr>
											
											<td><?php 
												$medicine=$obj_medicine->get_single_medicine($retrieved_data->medication_name);
											echo $medicine->medicine_name; ?></td>
											<td><?php echo $retrieved_data->time; ?></td>
											<td><?php echo $retrieved_data->per_days; ?></td>
											</tr>
                        	<?php 	
                        	}
                        }
                        ?>
                        
                       
                        </table>
                        </div>
                        <hr>
                        
                        <b><?php _e('Extra Note','hospital_mgt');?> :</b>
                        
                        <p><?php echo $result->treatment_note;?></p>
                        <?php 
                      $all_entry=json_decode($result->custom_field);
                      if(!empty($all_entry))
                      {
                      	
                      	foreach($all_entry as $entry){
                      		?>
                      		<hr>
                      		<b><?php  echo $entry->label;?></b>
                      		<P><?php echo $entry->value;?></P>
                      		
                      		<?php 
                      	}
                      }
                        ?>
					<hr>
                    </div>

                </div>
				<div class="print-button pull-left">
						<a  href="?page=hmgt_prescription&print=print&prescription_id=<?php echo $_POST['prescription_id'];?>" target="_blank"class="btn btn-success"><?php _e('Print','hospital_mgt');?></a>
				</div>
            </div>
        	</div>
        	 </div>
	<?php 
	die();
}
///////--user profile pop-up
function hmgt_user_profile()
{
	$obj_hospital = new Hospital_Management(get_current_user_id());
	$user_info =get_userdata( $_REQUEST['user_id']);
	?>
<style>
.profile-cover{
	background: url("<?php echo get_option( 'hmgt_hospital_background_image' );?>") repeat scroll 0 0 / cover rgba(0, 0, 0, 0);
}

</style>
	<div class="modal-header"> <a href="#" class="close-btn-cat badge badge-success pull-right">X</a>
  		<h4 id="myLargeModalLabel" class="modal-title"><?php 
			
								
			$user=$user_info = get_userdata($_REQUEST['user_id']);
			echo $user->display_name;
			?></h4>
	</div>
	<hr>
	<div class="profile-cover">
			<div class="row">				
						<div class="col-md-3 profile-image">
									<div class="profile-image-container">
									<?php $umetadata=get_user_meta($_REQUEST['user_id'], 'hmgt_user_avatar', true);
										
													if(empty($umetadata)){
														echo '<img src='.get_default_userprofile($obj_hospital->role).' height="150px" width="150px" class="img-circle" />';
													}
													else
														echo '<img src='.$umetadata.' height="150px" width="150px" class="img-circle" />';
									?>
									</div>
						</div>						
			</div>
	</div>
	<div id="main-wrapper">
		<div class="panel-heading">
			<table class="table table-bordered">
				<tr>
					<td><?php _e('Email');?></td>
					<td><?php echo $user_info->user_email;?></td>
				</tr>
				<tr>
					<td><?php _e('Home Town Address');?></td>
					<td>
						<?php 
							echo $user_info->address;
							if( $user_info->home_city !="")
								echo 	", ".$user_info->home_city;
							if( $user_info->home_state !="")
								echo 	" ,".$user_info->home_state;
							if( $user_info->home_country !="")
								echo 	", ".$user_info->home_country.".";
						?>
						</td>
				</tr>
				<tr>
					<td><?php _e('Office Address');?></td>
					<td>
						<?php 
							if($user_info->office_address != "")
								echo $user_info->office_address.",";
							if( $user_info->city_name !="")
								echo 	", ".$user_info->city_name;
							if( $user_info->state_name !="")
								echo 	" ,".$user_info->state_name;
							if( $user_info->country_name !="")
								echo 	", ".$user_info->country_name.".";
						?>
					</td>
				</tr>
				<tr>
					<td><?php _e('Sex');?></td>
					<td><?php echo $user_info->gender;?></td>
				</tr>
				<tr>
					<td><?php _e('Birth Date');?></td>
					<td><?php echo $user_info->birth_date;?></td>
				</tr>
				<tr>
					<td><?php _e('Degree');?></td>
					<td><?php echo $user_info->doctor_degree;?></td>
				</tr>
				<tr>
					<td><?php _e('Visiting fees');?></td>
					<td><?php echo $user_info->visiting_fees;?></td>
				</tr>
				
			</table>
			
		</div>
		<div class="panel-body">
		</div>		   
	</div>	
	
	<?php 	
	die();
	
}
//------report period---------------
function hmgt_load_convert_patient()
{
		//$_REQUEST['patient_id'];
		 $patient_type=get_user_meta($_REQUEST['patient_id'],'patient_type',true);
			if( $patient_type=='outpatient'){
				
			echo '<label class="col-sm-2 control-label" for="patient_convert"></label>';
			echo '<div class="col-sm-8">';
			echo '<input type="checkbox"  name="patient_convert" value="inpatient">';
			echo __(' Convert into in patient','hospital_mgt');
			echo '</div>';
			}
			
		exit;
}

function hmgt_sms_service_setting()
{

	$select_serveice = $_POST['select_serveice'];
	
	if($select_serveice == 'clickatell')
	{
		$clickatell=get_option( 'hmgt_clickatell_sms_service');
		?>
			<div class="form-group">
				<label class="col-sm-2 control-label " for="username"><?php _e('Username','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="username" class="form-control validate[required]" type="text" value="<?php if(isset($clickatell['username'])) echo $clickatell['username'];?>" name="username">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label " for="password"><?php _e('Password','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="password" class="form-control validate[required]" type="text" value="<?php if(isset($clickatell['password'])) echo $clickatell['password'];?>" name="password">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label " for="api_key"><?php _e('API Key','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="api_key" class="form-control validate[required]" type="text" value="<?php if(isset($clickatell['api_key'])) echo $clickatell['api_key'];?>" name="api_key">
				</div>
			</div>
		<?php 
		}
		
		if($select_serveice == 'twillo')
		{
		$twillo=get_option( 'hmgt_twillo_sms_service');
				?>
				<div class="form-group">
				<label class="col-sm-2 control-label " for="account_sid"><?php _e('Account SID','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="account_sid" class="form-control validate[required]" type="text" value="<?php if(isset($twillo['account_sid'])) echo $twillo['account_sid'];?>" name="account_sid">
				</div>
			</div>
		<div class="form-group">
				<label class="col-sm-2 control-label" for="auth_token"><?php _e('Auth Token','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="auth_token" class="form-control validate[required] text-input" type="text" name="auth_token" value="<?php if(isset($twillo['auth_token'])) echo $twillo['auth_token'];?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="from_number"><?php _e('From Number','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="from_number" class="form-control validate[required] text-input" type="text" name="from_number" value="<?php if(isset($twillo['from_number'])) echo $twillo['from_number'];?>">
				</div>
			</div>
			
		<?php }
		
		die();
}
?>