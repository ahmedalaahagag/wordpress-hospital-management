<?php
$user_object=new Hmgtuser();
?>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
    <div class="modal-content">
    <div class="profile_data ">
     </div>
    </div>
    </div>
</div>
<!-- End POP-UP Code -->
<script type="text/javascript">
$(document).ready(function() {
	jQuery('#doctor_list').DataTable({
		 "order": [[ 1, "asc" ]],
		 "aoColumns":[
	                  {"bSortable": false},
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
<div class="panel-body panel-white">
 <ul class="nav nav-tabs panel_tabs" role="tablist">
      <li class="active">
          <a href="#examlist" role="tab" data-toggle="tab">
             <i class="fa fa-align-justify"></i> <?php _e('Doctor List', 'hospital_mgt'); ?></a>
          </a>

      </li>
</ul>
	<div class="tab-content">
    	
         <?php 
		 //	$retrieve_class = get_all_data($tablename);		
		
		?>
		<div class="panel-body">
        <div class="table-responsive">
       <table id="doctor_list" class="display dataTable " cellspacing="0" width="100%">
        	<thead>
            <tr>
			<th><?php  _e( 'Photo', 'hospital_mgt' ) ;?></th>
              <th><?php _e( 'doctor Name', 'hospital_mgt' ) ;?></th>
			    <th><?php _e( 'Department', 'hospital_mgt' ) ;?></th>
			  <th> <?php _e( 'Specialization', 'hospital_mgt' ) ;?></th>
			  <th> <?php _e( 'Degree', 'hospital_mgt' ) ;?></th>
                <th> <?php _e( 'doctor Email', 'hospital_mgt' ) ;?></th>
                <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </thead>
		<tfoot>
            <tr>
			 <th><?php  _e( 'Photo', 'hospital_mgt' ) ;?></th>
              <th><?php _e( 'doctor Name', 'hospital_mgt' ) ;?></th>
			   <th><?php _e( 'Department', 'hospital_mgt' ) ;?></th>
			   <th><?php _e( 'Specialization', 'hospital_mgt' ) ;?></th>
			  <th><?php _e( 'Degree', 'hospital_mgt' ) ;?></th>
                <th><?php _e( 'doctor Email', 'hospital_mgt' ) ;?></th>
                <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </tfoot>
		<tbody>
         <?php 
		 $get_doctor = array('role' => 'doctor');
			$doctordata=get_users($get_doctor);
		 if(!empty($doctordata))
		 {
		 	foreach ($doctordata as $retrieved_data){
		?>
            <tr>
				<td class="user_image"><?php $uid=$retrieved_data->ID;
							 
					$userimage=get_user_meta($uid, 'hmgt_user_avatar', true);
							if(empty($userimage))
							{
								echo '<img src='.get_option( 'hmgt_doctor_thumb' ).' height="50px" width="50px" class="img-circle" />';
							}
							else
							echo '<img src='.$userimage.' height="50px" width="50px" class="img-circle"/>';
				?></td>
                <td class="name"><a href="#"><?php echo $retrieved_data->display_name;?></a></td>
				<td class="department"><?php 
				$postdata=get_post($retrieved_data->department);
				echo $postdata->post_title;?></td>
                <td class="specialization">
				<?php 
						echo get_user_meta($uid, 'specialization', true);
						
				?></td>
				<td class="subject_name"><?php echo get_user_meta($uid, 'doctor_degree', true);?></td>
                <td class="email"><?php echo $retrieved_data->user_email;?></td>
               	<td class="action">
				<!--<a  href="#" class="view-profile btn btn-default" idtest="<?php echo $retrieved_data->ID; ?>"><i class="fa fa-eye"></i> <?php _e('View Profile', 'hospital_mgt');?></a>-->
               	<a href="?page=doctor&tab=adddoctor&action=edit&doctor_id=<?php echo $retrieved_data->ID;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
                <a href="?page=doctor&tab=doctorlist&action=delete&doctor_id=<?php echo $retrieved_data->ID;?>" class="btn btn-danger" 
                onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
                <?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>

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