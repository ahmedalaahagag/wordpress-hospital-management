<?php 
	// This is Class at admin side!!!!!!!!! 
	
	?> 
	<div class="mailbox-content sendbox">
	<div class="table-responsive">
 	<table class="table">
 		<thead>
 			<tr>
 				<th class="text-right" colspan="5">
               	<?php 
               	$max = 10;
               	if(isset($_GET['pg'])){
               		$p = $_GET['pg'];
               	}else{
               		$p = 1;
               	}
               
               	$limit = ($p - 1) * $max;
               $prev = $p - 1;
               	$next = $p + 1;
               	$limits = (int)($p - 1) * $max;
              	$totlal_message = $obj_message->hmgt_count_send_item(get_current_user_id());
               $totlal_message = ceil($totlal_message / $max);
               $lpm1 = $totlal_message - 1;               	
               $offest_value = ($p-1) * $max;
               	echo $obj_message->hmgt_pagination($totlal_message,$p,$prev,$next,'dashboard=user&page=message&tab=sentbox');
               	
               	?>
                </th>
 			</tr>
 		</thead>
 		<tbody>
 		<tr>
 			
 			<th class="hidden-xs">
            	<span><?php _e('Message For','hospital_mgt');?></span>
            </th>
            <th><?php _e('Subject','hospital_mgt');?></th>
             <th>
                  <?php _e('Description','hospital_mgt');?>
            </th>
            </tr>
 		<?php 
 		$offset = 0;
 		if(isset($_REQUEST['pg']))
 			$offset = $_REQUEST['pg'];
 		
 		$message = $obj_message->hmgt_get_send_message(get_current_user_id(),$max,$offset);
 		
 		foreach($message as $msg_post)
 		{
 			if($msg_post->post_author==get_current_user_id())
 			{
 			?>
 			<tr>
 			<td class="hidden-xs message_for">
            	<span><?php 
            	if(get_post_meta( $msg_post->ID, 'message_for',true) == 'user')
            		echo hmgt_get_display_name(get_post_meta( $msg_post->ID, 'message_for_userid',true));
            	else
            	echo get_role_name_in_message(get_post_meta( $msg_post->ID, 'message_for',true));?></span>
            </td>
            <td class="message_title"><a href="?dashboard=user&page=message&tab=view_message&from=sendbox&id=<?php echo  $msg_post->ID;?>"><?php echo $msg_post->post_title;?></a></td>
             <td class="messsage_body">
                  <?php echo $msg_post->post_content;?>
            </td>
            </tr>
 			<?php 
 			}
 		}
 		?>
 		
 		</tbody>
 	</table>
 	</div>
 </div>
 <?php ?>