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
	jQuery('#accountant_list').DataTable({
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
             <i class="fa fa-align-justify"></i>  <?php _e('Accountant List', 'hospital_mgt'); ?></a>
          </a>
      </li>
</ul>
	<div class="tab-content">
    	
         <?php 
		 //	$retrieve_class = get_all_data($tablename);		
		
		?>
		<div class="panel-body">
        <div class="table-responsive">
       <table id="accountant_list" class="display dataTable " cellspacing="0" width="100%">
        	
        	 <thead>
            <tr>
			<th style="width: 50px;height:50px;"><?php  _e( 'Photo', 'hospital_mgt' ) ;?></th>
              <th><?php _e( 'Name', 'hospital_mgt' ) ;?></th>
			   <th><?php _e( 'Department', 'hospital_mgt' ) ;?></th>
			  <th> <?php _e( 'Mobile No', 'hospital_mgt' ) ;?></th>
				<th> <?php _e( 'Email', 'hospital_mgt' ) ;?></th>
              
            </tr>
        </thead>
 
        <tfoot>
            <tr>
			<th><?php  _e( 'Photo', 'hospital_mgt' ) ;?></th>
              <th><?php _e( 'Name', 'hospital_mgt' ) ;?></th>
			   <th><?php _e( 'Department', 'hospital_mgt' ) ;?></th>
			  <th> <?php _e( 'Mobile No', 'hospital_mgt' ) ;?></th>
				<th> <?php _e( 'Email', 'hospital_mgt' ) ;?></th>
               
            </tr>
        </tfoot>
 
       <tbody>
         <?php 
		//$nursedata=get_usersdata('nurse');
		 $get_receptionist = array('role' => 'accountant');
			$receptionistdata=get_users($get_receptionist);
		 if(!empty($receptionistdata))
		 {
		 	foreach ($receptionistdata as $retrieved_data){
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
                <td class="name"><a href="#"><?php echo $retrieved_data->display_name;?></a></td>
                <td class="department"><?php 
				$postdata=get_post($retrieved_data->department);
				echo $postdata->post_title;?></td>
				<td class="phone">
				<?php 
					echo get_user_meta($uid, 'mobile', true);
				?></td>
				
                <td class="email"><?php echo $retrieved_data->user_email;?></td>
               	
               
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