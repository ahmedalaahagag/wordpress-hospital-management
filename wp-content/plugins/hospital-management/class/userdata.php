<?php
//$user = new WP_User($user_id);
if ($_REQUEST['action']) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/GMHC/wp-config.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/GMHC/wp-includes/wp-db.php');
    $wordpressdb = new wpdb(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
    switch ($_REQUEST['action']) {
        case 'add_user': {
            $usernewData = $_REQUEST['data'][0];
            $startDate = date('Y-m-d H:i:s',strtotime($_REQUEST['data'][1]));
            $startDateTime = date('H:i:s',strtotime($_REQUEST['data'][1]));
            $startDateDate = date('Y-m-d',strtotime($_REQUEST['data'][1]));
            $endDate = date('Y-m-d H:i:s',strtotime($_REQUEST['data'][2]));
            $endDateTime = date('H:i:s',strtotime($_REQUEST['data'][2]));
            $endDateDate = date('Y-m-d',strtotime($_REQUEST['data'][2]));
            $origindata = explode('&',$usernewData);
            $doctor_id = $origindata[3];
            $doctor_id = explode('=',$origindata[3])[1];
            $data['name'] = explode('=',$origindata[0]);
            if(!empty($data['name'][1])){
            $data['fullname'] = explode('+',$data['name'][1]);
            $newuserdata['first_name'] = $data['fullname'][0];
            $newuserdata['middle_name'] = $data['fullname'][1];
            $data['phone'] = explode('=',$origindata[2]);
            $newuserdata['phone'] = $data['phone'][1];
            $newuserdata['mobile'] = $data['phone'][1];
            $newuserdata['username'] = $newuserdata['first_name'].$newuserdata['middle_name'].rand(10,100);
            $newuserdata['user_login'] = $newuserdata['username'];
            $newuserdata['user_nicename'] = NULL;
            $newuserdata['user_url'] = NULL;
            $newuserdata['display_name'] = $data['first_name']  . $data['middle_name'];
            $user_id = wp_insert_user($newuserdata);
            //-------usersmeta table data--------------
            if (isset($newuserdata['first_name']))
                $usermetadata['first_name'] = $newuserdata['first_name'];
            if (isset($newuserdata['middle_name']))
                $usermetadata['middle_name'] = $newuserdata['middle_name'];
            if (isset($newuserdata['mobile']))
                $usermetadata['mobile'] = $newuserdata['mobile'];
            if (isset($data['phone']))
                $usermetadata['phone'] = $newuserdata['phone'];
            if (isset($newuserdata['patient_id']))
                $usermetadata['patient_id'] = $user_id;
             //----------insert code------------------
            hmgt_append_audit_log('Add new user  ', get_current_user_id());
            $user = new WP_User($user_id);
            $user->set_role('patient');
            foreach ($usermetadata as $key => $val) {
                $returnans = add_user_meta($user_id, $key, $val, true);
            }
            if (isset($data['first_name']))
                $returnans = update_user_meta($user_id, 'first_name', $data['first_name']);
            if (isset($data['middle_name']))
                $returnans = update_user_meta($user_id, 'last_name', $data['middle_name']);
            $patient_id = $user_id;
            }
            else
            {
                $patient_id = explode('=',$origindata[1])[1];
            }
            $table_appoinments = "wp_hmgt_appointment";
            $appoinments_data['appointment_date']=$startDateDate;
            $appoinments_data['appointment_time']=$startDateTime;
            $appoinments_data['appointment_time_string']=$startDate;
            $appoinments_data['appointment_end_time_string']=$endDate;
            $appoinments_data['doctor_id']=$doctor_id;
            $appoinments_data['patient_id']=$patient_id;
            $appoinments_data['appoint_create_date']= date("Y-m-d");
            $appoinments_data['appoint_create_by']= get_current_user_id();
            $result=$wordpressdb->insert( $table_appoinments, $appoinments_data );
            echo 1;
            exit;
        }
    }
}

class Hmgtuser
{
    public $usermetadata = array();
    public $userdata = array();

    public function hmgt_add_user($data)
    {

        //-------usersmeta table data--------------
        if (isset($data['middle_name']))
            $usermetadata['middle_name'] = $data['middle_name'];
        if (isset($data['gender']))
            $usermetadata['gender'] = $data['gender'];
        if (isset($data['birth_date']))
            $usermetadata['birth_date'] = $data['birth_date'];
        if (isset($data['address']))
            $usermetadata['address'] = $data['address'];

        if (isset($data['city_name']))
            $usermetadata['city_name'] = $data['city_name'];
        if (isset($data['state_name']))
            $usermetadata['state_name'] = $data['state_name'];
        if (isset($data['country_name']))
            $usermetadata['country_name'] = $data['country_name'];
        if (isset($data['zip_code']))
            $usermetadata['zip_code'] = $data['zip_code'];
        if (isset($data['mobile']))
            $usermetadata['mobile'] = $data['mobile'];
        if (isset($data['phone']))
            $usermetadata['phone'] = $data['phone'];
        if (isset($data['hmgt_user_avatar']))
            $usermetadata['hmgt_user_avatar'] = $data['hmgt_user_avatar'];
        if ($data['role'] == 'doctor') {
            if (isset($data['office_address']))
                $usermetadata['office_address'] = $data['office_address'];
            if (isset($data['department']))
                $usermetadata['department'] = $data['department'];
            if (isset($data['specialization']))
                $usermetadata['specialization'] = $data['specialization'];
            if (isset($data['doc_degree']))
                $usermetadata['doctor_degree'] = $data['doc_degree'];
            if (isset($data['visiting_fees']))
                $usermetadata['visiting_fees'] = $data['visiting_fees'];
            if (isset($data['home_city_name']))
                $usermetadata['home_city'] = $data['home_city_name'];
            if (isset($data['home_state_name']))
                $usermetadata['home_state'] = $data['home_state_name'];
            if (isset($data['home_country_name']))
                $usermetadata['home_country'] = $data['home_country_name'];

        }

        if ($data['role'] == 'patient') {
            if (isset($data['patient_id']))
                $usermetadata['patient_id'] = $data['patient_id'];
            if (isset($data['blood_group']))
                $usermetadata['blood_group'] = $data['blood_group'];
            if (isset($data['patient_type']))
                $usermetadata['patient_type'] = $data['patient_type'];

            //print_r($usermetadata);

        }
        if (isset($data['patient_type']) == 'outpatient') {
            //$usermetadata['medicine']=$data['medicine'];
            if (isset($data['symptoms']))
                $usermetadata['symptoms'] = $data['symptoms'];
            if (isset($data['patient_convert']))
                $usermetadata['patient_type'] = 'inpatient';

        }
        if ($data['role'] == 'nurse' || $data['role'] == 'receptionist') {
            if (isset($data['department']))
                $usermetadata['department'] = $data['department'];
        }
        //-------users table data-----------------
        if (isset($data['username']))
            $userdata['user_login'] = $data['username'];
        if (isset($data['email']))
            $userdata['user_email'] = $data['email'];

        $userdata['user_nicename'] = NULL;
        $userdata['user_url'] = NULL;
        if (isset($data['first_name']))
            $userdata['display_name'] = $data['first_name'] . " " . $data['last_name'];

        if ($data['password'] != "")
            $userdata['user_pass'] = $data['password'];

        if ($data['action'] == 'edit') {
            hmgt_append_audit_log('Update user detail  ', get_current_user_id());
            $userdata['ID'] = $data['user_id'];
            $user_id = wp_update_user($userdata);

            $returnans = update_user_meta($user_id, 'first_name', $data['first_name']);
            $returnans = update_user_meta($user_id, 'last_name', $data['last_name']);

            foreach ($usermetadata as $key => $val) {
                $returnans = update_user_meta($user_id, $key, $val);
            }
            return $user_id;
        } else {
            //----------isert code------------------
            $user_id = wp_insert_user($userdata);
            hmgt_append_audit_log('Add new user  ', get_current_user_id());
            $user = new WP_User($user_id);
            $user->set_role($data['role']);
            foreach ($usermetadata as $key => $val) {
                $returnans = add_user_meta($user_id, $key, $val, true);
            }
            if (isset($data['first_name']))
                $returnans = update_user_meta($user_id, 'first_name', $data['first_name']);
            if (isset($data['last_name']))
                $returnans = update_user_meta($user_id, 'last_name', $data['last_name']);
            return $user_id;

        }

    }

    public function delete_usedata($record_id)
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'usermeta';
        $result = $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE user_id= %d", $record_id));
        $retuenval = wp_delete_user($record_id);
        return $retuenval;
    }

    public function update_user($data)
    {

        $user_id = wp_update_user();

        $returnval = update_user_meta($user_id, 'first_name', $data['first_name']);
        $returnval = update_user_meta($user_id, 'last_name', $data['last_name']);

        foreach ($usermetadata as $key => $val) {

            $returnans = update_user_meta($user_id, $key, $val);
            if ($returnans)
                $returnval = $returnans;
        }
    }

    public function upload_documents($cv, $education, $experience, $user_id)
    {
        $usermetadata['doctor_cv'] = $cv;
        $usermetadata['edu_certificate'] = $education;
        $usermetadata['exp_certificate'] = $experience;
        foreach ($usermetadata as $key => $val) {
            $returnans = add_user_meta($user_id, $key, $val, true);
        }
    }

    public function update_upload_documents($cv, $education, $experience, $user_id)
    {


        $usermetadata['doctor_cv'] = $cv;
        $usermetadata['edu_certificate'] = $education;
        $usermetadata['exp_certificate'] = $experience;
        foreach ($usermetadata as $key => $val) {
            $returnans = update_user_meta($user_id, $key, $val);

        }


    }

    public function update_diagnosis_report($user_id, $report, $diagno_id)
    {
        global $wpdb;
        $table_diagnosis = $wpdb->prefix . 'hmgt_diagnosis';
        //$returnans=add_user_meta( $user_id, 'diagnosis_report',$report );
        $diagnosisdata['patient_id'] = $user_id;
        $diagnosisdata['attach_report'] = $report;
        $diagnosisdata['diagnosis_date'] = date("Y-m-d");
        $dignosis_id['diagnosis_id'] = $diagno_id;
        $diagnosisdata['diagno_create_by'] = get_current_user_id();
        $result = $wpdb->update($table_diagnosis, $diagnosisdata, $dignosis_id);
        return $result;

    }

    public function upload_diagnosis_report($user_id, $report)
    {
        global $wpdb;
        $table_diagnosis = $wpdb->prefix . 'hmgt_diagnosis';
        //$returnans=add_user_meta( $user_id, 'diagnosis_report',$report );
        $diagnosisdata['patient_id'] = $user_id;
        $diagnosisdata['attach_report'] = $report;
        $diagnosisdata['diagnosis_date'] = date("Y-m-d");
        $diagnosisdata['diagno_create_by'] = get_current_user_id();
        $result = $wpdb->insert($table_diagnosis, $diagnosisdata);
    }

    public function get_staff_department()
    {
        $args = array('post_type' => 'department', 'posts_per_page' => -1, 'orderby' => 'post_title', 'order' => 'Asc');
        $result = get_posts($args);
        return $result;
    }

    public function add_staff_department($data)
    {
        $result = wp_insert_post(array(
            'post_status' => 'publish',
            'post_type' => 'department',
            'post_title' => $data['category_name']));
        return $result;
    }

    public function delete_staff_department($department_id)
    {
        $result = wp_delete_post($department_id);
        return $result;
    }

    public function get_doctor_specilize()
    {
        $args = array('post_type' => 'specialization', 'posts_per_page' => -1, 'orderby' => 'post_title', 'order' => 'Asc');
        $result = get_posts($args);
        return $result;
    }

    public function add_doctor_specilize($data)
    {
        $result = wp_insert_post(array(
            'post_status' => 'publish',
            'post_type' => 'specialization',
            'post_title' => $data['category_name']));
        return $result;

    }

    public function delete_doctor_specilize($specilize_id)
    {
        $result = wp_delete_post($specilize_id);
        return $result;
    }

    public function get_user_by_type($type = '')
    {
        $userinfo = array();
        if (!empty($type)) {
            $metavalue = "a:1:{s:" . strlen($type) . ":\"$type\";b:1;}";
            $users = get_users(array('wp_capabilities' => 'wp_capabilities', 'meta_value' => $metavalue));
            if (!empty($users)) {
                foreach ($users as $user) {
                    $userinfo = array();
                    $user = (array)$user;
                    $user['data'] = (array)$user['data'];
                    foreach ($user['data'] as $key => $data) {
                        switch ($key) {
                            case 'ID': {
                                $key = $type . '_id';
                                $user['data'][$key] = $data;
                            }
                            case'display_name': {
                                $key = $type . '_name';
                                $user['data'][$key] = $data;
                            }
                        }
                        $userinfo = $user['data'];
                    }
                    $usersinfo[] = $userinfo;

                }
            }
        }
        return $usersinfo;
    }


}

?>