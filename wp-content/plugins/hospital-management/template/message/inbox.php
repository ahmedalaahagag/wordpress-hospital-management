<?php 


?>
<div class="mailbox-content">
<div class="table-responsive">
 	<table class="table">
 		<thead>
 			<tr>
 				<th class="text-right" colspan="5">
                 <?php 
                $message = $obj_message->hmgt_count_inbox_item(get_current_user_id());
              
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
 		$totlal_message =count($message);
 		$totlal_message = ceil($totlal_message / $max);
 		$lpm1 = $totlal_message - 1;
 		$offest_value = ($p-1) * $max;
 	echo $obj_message->hmgt_pagination($totlal_message,$p,$prev,$next,'dashboard=user&page=message&tab=inbox');?>
                </th>
 			</tr>
 		</thead>
 		<tbody>
 		<tr>
 			
 			<th class="hidden-xs">
            	<span><?php _e('Message For','school-mgt');?></span>
            </th>
            <th><?php _e('Subject','hospital_mgt');?></th>
             <th>
                  <?php _e('Description','hospital_mgt');?>
            </th>
            </tr>
 		<?php 
 		$message = $obj_message->hmgt_get_inbox_message(get_current_user_id(),$limit,$max);
 		foreach($message as $msg)
 		{
 			?>
 			<tr>
 			
            <td><?php echo hmgt_get_display_name($msg->sender);?></td>
             <td>
                 <a href="?dashboard=user&page=message&tab=inbox&tab=view_message&from=inbox&id=<?php echo $msg->message_id;?>"> <?php echo $msg->msg_subject;?></a>
            </td>
            <td>&nbsp;
            </td>
            <td>
                <?php  echo  mysql2date('d M', $msg->msg_date );?>
            </td>
            </tr>
 			<?php 
 		}
 		?>
 		
 		</tbody>
 	</table>
 </div>
 </div>
 <?php ?>