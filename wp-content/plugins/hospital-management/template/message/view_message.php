<?php 
//Subject
if($_REQUEST['from']=='sendbox')
{
$message = get_post($_REQUEST['id']);
$box='sendbox';
if(isset($_REQUEST['delete']))
{
		echo $_REQUEST['delete'];
		wp_delete_post($_REQUEST['id']);
		wp_safe_redirect(home_url()."?dashboard=user&page=message&tab=sentbox" );
		exit();
}
}
if($_REQUEST['from']=='inbox')
{
	$message = $obj_message->hmgt_get_message_by_id($_REQUEST['id']);
	$box='inbox';

	if(isset($_REQUEST['delete']))
	{
		//echo $_REQUEST['delete'];
			
		$obj_message->delete_message($_REQUEST['id']);
		wp_safe_redirect(home_url()."?dashboard=user&page=message&tab=inbox" );
		exit();
	}

}
?>
<div class="mailbox-content">
 	<div class="message-header">
		<h3><span><?php _e('Subject','hospital_mgt')?> :</span>  <?php if($box=='sendbox'){ echo $message->post_title; } else{ echo $message->msg_subject; } ?></h3>
        <p class="message-date"><?php  if($box=='sendbox') echo  mysql2date('d/m/y', $message->date ); else echo  mysql2date('d/m/y', $message->msg_date ) ;?></p>
	</div>
	<div class="message-sender">                                
    	<p><?php if($box=='sendbox'){ echo hmgt_get_display_name($message->post_author); } else{ echo hmgt_get_display_name($message->sender); } ?> <span>&lt;<?php if($box=='sendbox'){ echo hmgt_get_emailid_byuser_id($message->post_author); } else{ echo hmgt_get_emailid_byuser_id($message->sender); } ?>&gt;</span></p>
    </div>
    <div class="message-content">
    	<p><?php if($box=='sendbox'){ echo $message->post_content; } else{ echo $message->message_body; }?></p>
    </div>
    <div class="message-options pull-right">
    	<a class="btn btn-default" href="?dashboard=user&page=message&tab=view_message&id=<?php echo $_REQUEST['id'];?>&from=<?php echo $box;?>&delete=1" onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');"><i class="fa fa-trash m-r-xs"></i><?php _e('Delete','hospital_mgt')?></a> 
   </div>
 </div>
<?php ?>