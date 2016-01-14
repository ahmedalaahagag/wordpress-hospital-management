<?php 
$obj_bloodbank= new Hmgtbloodbank();


	?>
	<?php 

?>
	<?php
$active_tab = isset($_GET['tab'])?$_GET['tab']:'bloodbanklist';
	
	?>
	<script type="text/javascript">
$(document).ready(function() {
	jQuery('#bood_doner').DataTable({
		
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
	<div class="panel-body">
	<form name="wcwm_report" action="" method="post">
		<div class="panel-body">
        	<div class="table-responsive">
        <table id="bood_doner" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>
			<th><?php _e('Name', 'hospital_mgt' ) ;?></th>
			<th><?php _e( 'Blood Group', 'hospital_mgt' ) ;?></th>
			<th><?php _e( 'Age', 'hospital_mgt' ) ;?></th>
			<th><?php _e( 'Gender', 'hospital_mgt' ) ;?></th>
			<th><?php _e( 'Last Donation Date', 'hospital_mgt' ) ;?></th> 
			<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </thead>
		<tfoot>
            <tr>
			<th><?php _e( 'Name', 'hospital_mgt' ) ;?></th>
			<th><?php _e( 'Blood Group', 'hospital_mgt' ) ;?></th>
			<th><?php _e( 'Age', 'hospital_mgt' ) ;?></th>
			<th><?php _e( 'Gender', 'hospital_mgt' ) ;?></th>
			<th><?php _e( 'Last Donation Date', 'hospital_mgt' ) ;?></th> 
			<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </tfoot>
 
        <tbody>
         <?php 
		$blooddonordata=$obj_bloodbank->get_all_blooddonors();
		 if(!empty($blooddonordata))
		 {
		 	foreach($blooddonordata as $retrieved_data){ 
		?>
            <tr>
			
                <td class="name"><a href="?page=hmgt_bloodbank&tab=addbloodbank&action=edit&blooddonor_id=<?php echo $retrieved_data->bld_donor_id;?>"><?php echo $retrieved_data->donor_name;?></a></td>
                <td class="bloodgroup">
				<?php 
						//echo $retrieved_data->blood_group;
						//$blood=$obj_bloodbank->get_single_bloodgroup($retrieved_data->blood_group);
						echo $retrieved_data->blood_group;
				?></td>
				<td class="age"><?php echo $retrieved_data->donor_age;?></td>
				<td class="age"><?php echo $retrieved_data->donor_gender;?></td>
                <td class="lastdonate_date"><?php echo $retrieved_data->last_donet_date;?></td>
               	<td class="action"> <a href="?page=hmgt_bloodbank&tab=addblooddonor&action=edit&blooddonor_id=<?php echo $retrieved_data->bld_donor_id;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
                <a href="?page=hmgt_bloodbank&tab=bloodbanklist&action=delete&blooddonor_id=<?php echo $retrieved_data->bld_donor_id;?>" class="btn btn-danger" 
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
       
</form>
     
</div>
	


<?php ?>