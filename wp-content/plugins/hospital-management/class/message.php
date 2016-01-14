<?php 
//$user = new WP_User($user_id);
	  
class Hmgt_message
{	

	//Medicine Category
	public function hmgt_add_message($data)
	{
		global $wpdb;
		$table_message=$wpdb->prefix."hmgt_message";
		
		//-------usersmeta table data--------------
		$created_date = date("Y-m-d H:i:s");
		$subject = $data['subject'];
		$message_body = $data['message_body'];
		$role=$data['receiver'];
		$userdata=get_users(array('role'=>$role));
		if($role == 'doctor' || $role == 'patient' || $role == 'nurse' || $role == 'receptionist' || $role == 'pharmacist' || $role == 'laboratorist' || $role == 'accountant' )
		{ 
		if(!empty($userdata))
		{
			$mail_id = array();
		
			foreach($userdata as $user)
			{
				$mail_id[]=$user->ID;
			}
		
		
			foreach($mail_id as $user_id)
			{
		
				$reciever_id = $user_id;
				$message_data=array('sender'=>get_current_user_id(),
						'receiver'=>$user_id,
						'msg_subject'=>$subject,
						'message_body'=>$message_body,
						'msg_date'=>$created_date,
						'msg_status' =>0
				);
					
				$result=$wpdb->insert( $table_message, $message_data );
				$hmgt_sms_service_enable=0;
				if(isset($_POST['hmgt_sms_service_enable']))
					$hmgt_sms_service_enable = $_POST['hmgt_sms_service_enable'];
				if($hmgt_sms_service_enable)
				{
						
						
					$userdata=get_users(array('role'=>$role));
					if(!empty($userdata))
					{
						$mail_id = array();
						foreach($userdata as $user)
						{
							$mail_id[]=$user->ID;
						}
					}
					foreach($mail_id as $user_id)
					{
						$reciever_number = "+".hmgt_get_countery_phonecode(get_option( 'hmgt_contry' )).get_user_meta($user_id, 'mobile',true);
						$message_content = $_POST['sms_template'];
						$current_sms_service = get_option( 'hmgt_sms_service');
						if($current_sms_service == 'clickatell')
						{
				
							$clickatell=get_option('hmgt_clickatell_sms_service');
							$to = $reciever_number;
							$message = $message_content;
							$username = $clickatell['username']; //clickatell username
							$password = $clickatell['password']; // clickatell password
							$api_key = $clickatell['api_key'];//clickatell apikey
							$baseurl ="http://api.clickatell.com";
							$url = "$baseurl/http/auth?user=$username&password=$password&api_id=$api_key";
							$ret = file($url);
							$sess = explode(":",$ret[0]);
							if ($sess[0] == "OK")
							{
				
								$sess_id = trim($sess[1]); // remove any whitespace
								$url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$message";
								$ret = file($url);
								$send = explode(":",$ret[0]);
							}
						}
						if($current_sms_service == 'twillo')
						{
							//Twilio lib
							require_once HMS_PLUGIN_DIR. '/lib/twilio/Services/Twilio.php';
							$twilio=get_option( 'hmgt_twillo_sms_service');
				
							$account_sid = $twilio['account_sid']; //Twilio SID
							$auth_token = $twilio['auth_token']; // Twilio token
							$from_number = $twilio['from_number'];//My number
							$receiver = $reciever_number; //Receiver Number
							$message = $message_content; // Message Text
							//twilio object
							$client = new Services_Twilio($account_sid, $auth_token);
							$message_sent = $client->account->messages->sendMessage(
									$from_number, // From a valid Twilio number
									$receiver, // Text this number
									$message
							);
				
						}
							
					}
				}
		
		
		
			}
			$post_id = wp_insert_post( array(
					'post_status' => 'publish',
					'post_type' => 'hmgt_message',
					'post_title' => $subject,
					'post_content' =>$message_body
		
			) );
			hmgt_append_audit_log('Message sent ',get_current_user_id());
		
			$result=add_post_meta($post_id, 'message_for',$role);
			$result = 1;
		}
		}
		else 
		{
			$user_id = $data['receiver'];
			$message_data=array('sender'=>get_current_user_id(),
					'receiver'=>$user_id,
					'msg_subject'=>$subject,
					'message_body'=>$message_body,
					'msg_date'=>$created_date,
					'msg_status' =>0
			);
			$result=$wpdb->insert( $table_message, $message_data );
			$hmgt_sms_service_enable=0;
			if(isset($_POST['hmgt_sms_service_enable']))
				$hmgt_sms_service_enable = $_POST['hmgt_sms_service_enable'];
			if($hmgt_sms_service_enable)
			{
				$reciever_number = "+".hmgt_get_countery_phonecode(get_option( 'hmgt_contry' )).get_user_meta($user_id, 'mobile',true);
				$message_content = $_POST['sms_template'];
				$current_sms_service = get_option( 'hmgt_sms_service');
				if($current_sms_service == 'clickatell')
				{
				
					$clickatell=get_option('hmgt_clickatell_sms_service');
					$to = $reciever_number;
					$message = $message_content;
					$username = $clickatell['username']; //clickatell username
					$password = $clickatell['password']; // clickatell password
					$api_key = $clickatell['api_key'];//clickatell apikey
					$baseurl ="http://api.clickatell.com";
					$url = "$baseurl/http/auth?user=$username&password=$password&api_id=$api_key";
					$ret = file($url);
					$sess = explode(":",$ret[0]);
					if ($sess[0] == "OK")
					{
				
						$sess_id = trim($sess[1]); // remove any whitespace
						$url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$message";
						$ret = file($url);
						$send = explode(":",$ret[0]);
					}
				}
				if($current_sms_service == 'twillo')
				{
					//Twilio lib
					require_once HMS_PLUGIN_DIR. '/lib/twilio/Services/Twilio.php';
					$twilio=get_option( 'hmgt_twillo_sms_service');
				
					$account_sid = $twilio['account_sid']; //Twilio SID
					$auth_token = $twilio['auth_token']; // Twilio token
					$from_number = $twilio['from_number'];//My number
					$receiver = $reciever_number; //Receiver Number
					$message = $message_content; // Message Text
					//twilio object
					$client = new Services_Twilio($account_sid, $auth_token);
					$message_sent = $client->account->messages->sendMessage(
							$from_number, // From a valid Twilio number
							$receiver, // Text this number
							$message
					);
				
				}
			}
			
			$post_id = wp_insert_post( array(
					'post_status' => 'publish',
					'post_type' => 'hmgt_message',
					'post_title' => $subject,
					'post_content' =>$message_body			
			) );
			hmgt_append_audit_log('Message sent ',get_current_user_id());
			$result=add_post_meta($post_id, 'message_for','user');
			$result=add_post_meta($post_id, 'message_for_userid',$user_id);
		}
		return $result;
		
	}
	public function delete_message($mid)
	{
		global $wpdb;
		$table_hmgt_message = $wpdb->prefix. 'hmgt_message';
		$result = $wpdb->query("DELETE FROM $table_hmgt_message where message_id= ".$mid);
		hmgt_append_audit_log('Delete message ',get_current_user_id());
		return $result;
	}
	public function hmgt_count_send_item($user_id)
	{
		global $wpdb;
		$posts = $wpdb->prefix."posts";
		$total =$wpdb->get_var("SELECT Count(*) FROM ".$posts." Where post_type = 'hmgt_message' AND post_author = $user_id");
		return $total;
	}
	
	public function hmgt_count_inbox_item($user_id)
	{
		global $wpdb;
		$tbl_name_message = $wpdb->prefix .'hmgt_message';
		
		$inbox =$wpdb->get_results("SELECT *  FROM $tbl_name_message where receiver = $user_id");
		return $inbox;
	}
	
	public function hmgt_get_inbox_message($user_id,$p=0,$lpm1=10)
	{
		global $wpdb;
		$tbl_name_message = $wpdb->prefix .'hmgt_message';
		
		$inbox =$wpdb->get_results("SELECT *  FROM $tbl_name_message where receiver = $user_id ORDER BY msg_date DESC limit $p , $lpm1");
		return $inbox;
	}
	public function hmgt_pagination($totalposts,$p,$prev,$next,$page)
	{
		
		
		$pagination = "";
		
		
		if($totalposts > 1)
		{
			$pagination .= '<div class="btn-group">';
		
			if ($p > 1)
				$pagination.= "<a href=\"?$page&pg=$prev\" class=\"btn btn-default\"><i class=\"fa fa-angle-left\"></i></a> ";
			else
				$pagination.= "<a class=\"btn btn-default disabled\"><i class=\"fa fa-angle-left\"></i></a> ";
		
			if ($p < $totalposts)
				$pagination.= " <a href=\"?$page&pg=$next\" class=\"btn btn-default next-page\"><i class=\"fa fa-angle-right\"></i></a>";
			else
				$pagination.= " <a class=\"btn btn-default disabled\"><i class=\"fa fa-angle-right\"></i></a>";
			$pagination.= "</div>\n";
		}
		return $pagination;
	}
	public function hmgt_get_send_message($user_id,$max=10,$offset=0)
	{	
		$args['post_type'] = 'hmgt_message';
		$args['posts_per_page'] =$max;
		$args['offset'] = $offset;
		$args['post_status'] = 'public';
		$args['author'] = $user_id;			
		$q = new WP_Query();
		$sent_message = $q->query( $args );
		return $sent_message;
	}
	
	public function hmgt_get_message_by_id($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "hmgt_message";
		return $retrieve_subject = $wpdb->get_row( "SELECT * FROM $table_name WHERE message_id=".$id);
	
	}
	
}
?>