<?php
//$user = new WP_User($user_id);

if($_REQUEST['action'])
{
	require_once($_SERVER['DOCUMENT_ROOT'] . '/GMHC/wp-config.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/GMHC/wp-includes/wp-db.php');
    $wordpressdb = new wpdb(DB_USER,DB_PASSWORD,DB_NAME,DB_HOST);
    switch ($_REQUEST['action'])
    {
        case 'get_single_session_durations':{
            $session_id = $_REQUEST['session_id'];
            $table_sessions = $wordpressdb->prefix. 'wp_hmgt_sessions';
            $table_durations = $wordpressdb->prefix. 'wp_hmgt_sessions_durations';
            $durations = $wordpressdb->get_results("SELECT * FROM $table_durations INNER JOIN $table_sessions ON ($table_sessions.session_id = $table_durations.session_id ) where $table_durations.session_id= ".$session_id);
            foreach($durations as $duration){
                $duration=(array)$duration;
                $durationhtml .= "<option value='".$duration['duration_id']."'>".$duration['duration_time']."</option>";
            }
            print_r($durationhtml);
            exit;
        }
        case 'is_over_lapping':{
        $doctor_id=$_REQUEST['doctor_id'];
        $from_time =  $_REQUEST['from_time'];
        $to_time=$_REQUEST['to_time'];
        $table_packages = $wordpressdb->prefix. 'wp_hmgt_packages';
        $overlapping = $wordpressdb->get_row("SELECT * FROM $table_packages WHERE ((`to_date` BETWEEN $from_time AND $to_time) OR (`from_date` BETWEEN $from_time AND $to_time) AND doctor_id=$doctor_id)");
            if($overlapping)
                echo 1;
            else
                echo 0;
        }
		case 'delete_session':{
			$package_id=$_REQUEST['package_id'];
			$session_id =  $_REQUEST['session_id'];
			$duration_id =  $_REQUEST['duration_id'];
			$table_packages = $wordpressdb->prefix. 'wp_hmgt_packages';
			$overlapping = $wordpressdb->query("DELETE FROM $table_packages WHERE (`session_id` = $session_id AND `package_id`=$package_id AND `duration_id`=$duration_id)");
			return 1;
		}
    }
}
class Hmgt_session_duration
{


    //Medicine Category
	public function hmgt_add_session_duration($data)
	{
		global $wpdb;
		$table_durations = $wpdb->prefix. 'hmgt_sessions_durations';


		if($data['action']=='edit')
		{

			$result = $wpdb->query("DELETE FROM `$table_durations` WHERE session_id=".$data['session_id']);
			for($i=0;$i<count($data['duration_time']);$i++){
			$durationdata['session_id']=$data['session_id'];
			$durationdata['duration_time']=$data['duration_time'][$i];
			$durationdata['duration_price']=$data['duration_price'][$i];
			$durationdata['duration_create_Date']=date("Y-m-d");
			$durationdata['duration_create_by']=get_current_user_id();
			$durationid['duration_id']=$data['duration_id'];
			$result=$wpdb->insert( $table_durations, $durationdata );
			echo $result;
			hmgt_append_audit_log('Update duration ',get_current_user_id());
			}

			return $result;
		}
		else
		{
			for($i=0;$i<count($data['duration_time']);$i++){
			$durationdata['session_id']=$data['session_id'];
			$durationdata['duration_time']=$data['duration_time'][$i];
			$durationdata['duration_price']=$data['duration_price'][$i];
			$durationdata['duration_create_Date']=date("Y-m-d");
			$durationdata['duration_create_by']=get_current_user_id();
			$durationid['duration_id']=$data['duration_id'];
			$result=$wpdb->insert( $table_durations, $durationdata );
			echo $result;
			hmgt_append_audit_log('Add new duration  ',get_current_user_id());
			}
			return $result;
		}

	}

	public function get_all_session_duration()
	{
		global $wpdb;
		$table_sessions = $wpdb->prefix. 'hmgt_sessions';
		$table_durations = $wpdb->prefix. 'hmgt_sessions_durations';
		$result = $wpdb->get_results("SELECT * FROM $table_durations INNER JOIN $table_sessions ON ($table_sessions.session_id = $table_durations.session_id)");
		return $result;

	}
	public function get_session_name_duration($duration_id)
	{
		global $wpdb;
		$table_durations = $wpdb->prefix. 'hmgt_sessions_durations';
		$table_sessions = $wpdb->prefix. 'hmgt_sessions';
		$result = $wpdb->get_var("SELECT $table_sessions.session_name FROM $table_sessions INNER JOIN $table_durations ON ($table_sessions.session_id = $table_durations.session_id )");
		return $result;
	}
	public function get_single_session_durations($session_id)
	{
		global $wpdb;
		$table_sessions = $wpdb->prefix. 'hmgt_sessions';
		$table_durations = $wpdb->prefix. 'hmgt_sessions_durations';
		$result = $wpdb->get_results("SELECT * FROM $table_durations INNER JOIN $table_sessions ON ($table_sessions.session_id = $table_durations.session_id ) where $table_durations.session_id= ".$session_id );
		return $result;
	}
	public function delete_duration($duration_id)
	{
		global $wpdb;
		$table_durations = $wpdb->prefix. 'hmgt_sessions_durations';
		$result = $wpdb->query("DELETE FROM $table_durations where duration_id= ".$duration_id);
		hmgt_append_audit_log('Delete session  ',get_current_user_id());
		return $result;
	}
    public function get_duration_price($duration_id)
    {
    global $wpdb;
		$table_durations = $wpdb->prefix. 'hmgt_sessions_durations';
		$result = $wpdb->get_var("SELECT duration_price FROM $table_durations where duration_id= ".$duration_id);
        return $result;

    }
    public function get_duration_mins($duration_id)
    {
        global $wpdb;
        $table_durations = $wpdb->prefix. 'hmgt_sessions_durations';
        $result = $wpdb->get_var("SELECT duration_time FROM $table_durations where duration_id= ".$duration_id);
        return $result;

    }
    public function has_discount ($user_id)
    {
        global $wpdb;
        $totaldiscount =0 ;
        $table_packages = $wpdb->prefix. 'hmgt_packages';
        $table_sessions = $wpdb->prefix. 'hmgt_sessions';
        $results = $wpdb->get_results("SELECT session_id FROM $table_packages where patient_id= ".$user_id);
        foreach($results as $result)
        {
            $session_id =  $result->session_id;
            $sessionnumber = $wpdb->get_var("SELECT COUNT ($session_id) AS SessionNumber FROM $table_packages where patient_id= ".$user_id);
            $minfordiscount = $wpdb->get_var("SELECT (discount_sessions_number) FROM $table_sessions where session_id= ".$session_id);
            if($sessionnumber>=$minfordiscount){
                $discount = $wpdb->get_var("SELECT (discount_sessions_percentage) FROM $table_sessions where session_id= ".$session_id);
                $totaldiscount+=$discount;
            }
        }
        return $totaldiscount;
    }
}
?>
