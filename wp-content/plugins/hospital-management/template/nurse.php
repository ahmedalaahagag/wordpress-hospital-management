<?php 
$user_object=new Hmgtuser();
?>
<script type="text/javascript">
$(document).ready(function() {
	jQuery('#nurse_list').DataTable({
		 "order": [[ 1, "asc" ]],
		 "aoColumns":[
	                  {"bSortable": false},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true}]
		});
	
} );
</script>
<div class="panel-body panel-white">
 <ul class="nav nav-tabs panel_tabs" role="tablist">
      <li class="active">
          <a href="#examlist" role="tab" data-toggle="tab">
             <i class="fa fa-align-justify"></i> <?php _e('Nurse List', 'hospital_mgt'); ?></a>
          </a>
      </li>
</ul>
	<div class="tab-content">
    	
         <?php 
		 //	$retrieve_class = get_all_data($tablename);		
		?>
		<div class="panel-body">
        <div class="table-responsive">
       <table id="nurse_list" class="display dataTable " cellspacing="0" width="100%">
        	
        	  <thead>
            <tr>
			<th  style="width: 50px;height:50px;"><?php  _e( 'Photo', 'hospital_mgt' ) ;?></th>
              <th class="sorting_asc"><?php _e( 'Nurse Name', 'hospital_mgt' ) ;?></th>
			   <th><?php _e( 'Department', 'hospital_mgt' ) ;?></th>
			 <th> <?php _e( 'Mobile No', 'hospital_mgt' ) ;?></th>
				<th> <?php _e( 'Nurse Email', 'hospital_mgt' ) ;?></th>
            </tr>
        </thead>
		<tfoot>
            <tr>
			<th><?php  _e( 'Photo', 'hospital_mgt' ) ;?></th>
              <th><?php _e( 'Nurse Name', 'hospital_mgt' ) ;?></th>
			   <th><?php _e( 'Department', 'hospital_mgt' ) ;?></th>
			 <th> <?php _e( 'Mobile No', 'hospital_mgt' ) ;?></th>
				<th> <?php _e( 'Nurse Email', 'hospital_mgt' ) ;?></th>
            </tr>
        </tfoot>
 
        <tbody>
         <?php 
		//$nursedata=get_usersdata('nurse');
		 $get_nurse = array('role' => 'nurse');
			$nursedata=get_users($get_nurse);
		 if(!empty($nursedata))
		 {
		 	foreach ($nursedata as $retrieved_data){
		 ?>
            <tr>
				<td class="user_image"><?php $uid=$retrieved_data->ID;
							$userimage=get_user_meta($uid, 'hmgt_user_avatar', true);
						if(empty($userimage))
						{
										echo '<img src='.get_option( 'hmgt_nurse_thumb' ).' height="50px" width="50px" class="img-circle" />';
						}
						else
							echo '<img src='.$userimage.' height="50px" width="50px" class="img-circle"/>';
				?></td>
                <td class="name"><a href="?dashboard=user&page=nurse&tab=addnurse&action=edit&nurse_id=<?php echo $retrieved_data->ID;?>"><?php echo $retrieved_data->display_name;?></a></td>
                <td class="department"><?php 
				$postdata=get_post($retrieved_data->department);
				echo $postdata->post_title;?></td>
				<td class="phone">
				<?php 
					echo get_user_meta($uid, 'mobile', true);
				?></td>
				
                <td class="email"><?php echo $retrieved_data->user_email;?></td>
               
					
                </td>
               
            </tr>
            <?php } 
			
		}?>
     
        </tbody>
        </table>
 		</div>
		</div>
	</div>
</div>
<?php ?>