<?php 
if(isset($_POST['save_notice']))
{
	if($_REQUEST['action']=='edit')
	{
		$args = array(
		  'ID'           => $_REQUEST['notice_id'],
		  'post_type' => $_REQUEST['notice_type'],
		  'post_title'   => $_REQUEST['notice_title'],
		  'post_content' =>  $_REQUEST['notice_content'],
					
		);
		$result1=wp_update_post( $args );
		$result2=update_post_meta($_REQUEST['notice_id'], 'notice_for', $_REQUEST['notice_for']);
		$result3=update_post_meta($_REQUEST['notice_id'], 'start_date',$_REQUEST['start_date']);
		$result4=update_post_meta($_REQUEST['notice_id'], 'end_date',$_REQUEST['end_date']);
			
		if($result1 || $result2 || $result3 || $result4)
		{
				wp_redirect ( home_url() . '?dashboard=user&page=event&tab=event_list&message=2');
		}
		}
		else
		{
			$post_id = wp_insert_post( array(
						'post_status' => 'publish',
						'post_type' => $_REQUEST['notice_type'],
						'post_title' => $_REQUEST['notice_title'],
						'post_content' => $_REQUEST['notice_content']
					) );
			if(!empty($_POST['notice_for']))
			{
				 delete_post_meta($post_id, 'notice_for');
				 $result=add_post_meta($post_id, 'notice_for',$_POST['notice_for']);
				 $result=add_post_meta($post_id, 'start_date',$_POST['start_date']);
				 $result=add_post_meta($post_id, 'end_date',$_POST['end_date']);
				 if($result)
				{
					wp_redirect ( home_url() . '?dashboard=user&page=event&tab=event_list&message=1');
				}
				
			}
			
		}
		
		
	}
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
		$result=wp_delete_post($_REQUEST['notice_id']);
		if($result)
			{
				wp_redirect ( home_url() . '?dashboard=user&page=event&tab=event_list&message=3');
			}	
}

if(isset($_REQUEST['message']))
{
	$message =$_REQUEST['message'];
	if($message == 1)
	{?>
			<div id="message" class="updated below-h2 ">
			<p>
			<?php 
				_e('Record inserted successfully','hospital_mgt');
			?></p></div>
			<?php 
		
	}
	elseif($message == 2)
	{?><div id="message" class="updated below-h2 "><p><?php
				_e("Record updated successfully",'hospital_mgt');
				?></p>
				</div>
			<?php 
		
	}
	elseif($message == 3) 
	{?>
	<div id="message" class="updated below-h2"><p>
	<?php 
		_e('Record deleted successfully','hospital_mgt');
	?></div></p><?php
			
	}
}	
	
$active_tab = isset($_GET['tab'])?$_GET['tab']:'event_list';
?>
<script type="text/javascript">
$(document).ready(function() {
	jQuery('#hmgt_event').DataTable({
		 "aoColumns":[
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true}, 
	                  {"bSortable": true},      
	                  {"bSortable": true}, 
	                                              
	                  {"bSortable": false}
	               ]
		});
} );
</script>
<div class="popup-bg">
    <div class="overlay-content">
    
    	<div class="notice_content"></div>    
    </div>
     
    
</div>

<div class="panel-body panel-white">
 <ul class="nav nav-tabs panel_tabs" role="tablist">
      <li class="<?php if($active_tab == 'event_list'){?>active<?php }?>">
          <a href="?dashboard=user&page=event&tab=event_list">
             <i class="fa fa-align-justify"></i> <?php _e('Event List', 'hospital_mgt'); ?></a>
          </a>
      </li>
     
</ul>
	<div class="tab-content">
	<?php if($active_tab == 'event_list'){?>
    	 <div class="tab-pane fade active in"  id="eventlist">
         <?php 
		 //	$retrieve_class = get_all_data($tablename);		
		?>
		<div class="panel-body">
        <div class="table-responsive">
        <table id="hmgt_event" class="display dataTable " cellspacing="0" width="100%">
        	 <thead>
            <tr>                
                <th width="190px"><?php _e('Title','hospital_mgt');?></th>
                <th><?php _e('Comment','hospital_mgt');?></th>
                <th><?php _e(' Start Date','hospital_mgt');?></th>
				<th><?php _e(' End Date','hospital_mgt');?></th>
                <th><?php _e('For','hospital_mgt');?></th>
                <th width="185px"><?php _e('Action','hospital_mgt');?></th>               
            </tr>
        </thead>	
		<tfoot>
            <tr>
                 <th width="190px"><?php _e('Title','hospital_mgt');?></th>
                <th><?php _e('Comment','hospital_mgt');?></th>
                <th><?php _e(' Start Date','hospital_mgt');?></th>
				<th><?php _e(' End Date','hospital_mgt');?></th>
                <th><?php _e('For','hospital_mgt');?></th>
                <th width="185px"><?php _e('Action','hospital_mgt');?></th>   
            </tr>
        </tfoot>
 
        <tbody>
          <?php 
		  $args['post_type'] = array('hmgt_event','hmgt_notice');
		  $args['posts_per_page'] = -1;
		  $args['post_status'] = 'public';
		  $q = new WP_Query();
	$retrieve_class = $q->query( $args );
	$format =get_option('date_format') ;
		 	foreach ($obj_hospital->all_events_notice as $retrieved_data){ 
			
		 ?>
            <tr>
                <td><?php echo $retrieved_data->post_title;?></td>
                <td><?php 
					$strlength= strlen($retrieved_data->post_content);
					if($strlength > 60)
						echo substr($retrieved_data->post_content, 0,60).'...';
					else
						echo $retrieved_data->post_content;
				
				?></td>
                <td><?php echo get_post_meta($retrieved_data->ID,'start_date',true);?></td> 
				<td><?php echo get_post_meta($retrieved_data->ID,'end_date',true);?></td> 				
                <td>
                <?php 
                	  	if(get_post_meta( $retrieved_data->ID, 'notice_for',true) == 'all')
                	  		echo get_post_meta( $retrieved_data->ID, 'notice_for',true);
                	  	else
                	  	echo get_role_name_in_message(get_post_meta( $retrieved_data->ID, 'notice_for',true));?>
                </td>              
               <td>
                <a href="#" class="btn btn-primary view-notice" id="<?php echo $retrieved_data->ID;?>"> <?php _e('View','hospital_mgt');?></a>
               <!--  <a href="?page=event&tab=addnotice&action=edit&notice_id=<?php echo $retrieved_data->ID;?>"class="btn btn-info"><?php _e('Edit','hospital_mgt');?></a>
               <a href="?page=event&tab=noticelist&action=delete&notice_id=<?php echo $retrieved_data->ID;?>" class="btn btn-danger" 
               onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');"> <?php _e('Delete','hospital_mgt');?></a>
               -->
                </td>
            </tr>
            <?php } ?>
     
        </tbody>
        
        </table>
 		</div>
		</div>
		</div>
	<?php } ?>
	
	</div>
	
</div>
<?php ?>