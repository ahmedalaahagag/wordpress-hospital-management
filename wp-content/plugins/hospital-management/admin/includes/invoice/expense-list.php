<?php 
	//This is Dashboard at admin side
	$obj_invoice= new Hmgtinvoice();
	if($active_tab == 'expenselist')
	 {
        	$invoice_id=0;
			if(isset($_REQUEST['income_id']))
				$invoice_id=$_REQUEST['income_id'];
			$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
					
					$edit=1;
					$result = $obj_invoice->hmgt_get_invoice_data($invoice_id);
	}
		 ?>
			     <script type="text/javascript">
$(document).ready(function() {
	jQuery('#tblexpence').DataTable({
		 "order": [[ 2, "Desc" ]],
		 "aoColumns":[
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  
	                                   
	                  {"bSortable": false}
	               ]
		});
		
	
} );
</script>
     <div class="panel-body">
        	<div class="table-responsive">
        <table id="tblexpence" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>
				<th> <?php _e( 'Supplier Name', 'hospital_mgt' ) ;?></th>
				<th> <?php _e( 'Amount', 'hospital_mgt' ) ;?></th>
				<th> <?php _e( 'Date', 'hospital_mgt' ) ;?></th>
                <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </thead>
		<tfoot>
            <tr>
				<th> <?php _e( 'Supplier Name', 'hospital_mgt' ) ;?></th>
				<th> <?php _e( 'Amount', 'hospital_mgt' ) ;?></th>
				<th> <?php _e( 'Date', 'hospital_mgt' ) ;?></th>
                <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </tfoot>
 
        <tbody>
         <?php 
		
		 	foreach ($obj_invoice->get_all_expense_data() as $retrieved_data){ 
				$all_entry=json_decode($retrieved_data->income_entry);
				$total_amount=0;
				foreach($all_entry as $entry){
					$total_amount+=$entry->amount;
				}
		 ?>
            <tr>
				
				<td class="patient_name"><?php echo $retrieved_data->party_name;?></td>
				<td class="income_amount"><?php echo $total_amount;?></td>
                <td class="status"><?php echo $retrieved_data->income_create_date;?></td>
                
               	<td class="action">
				<a  href="#" class="show-invoice-popup btn btn-default" idtest="<?php echo $retrieved_data->income_id; ?>" invoice_type="expense">
				<i class="fa fa-eye"></i> <?php _e('View Expense', 'hospital_mgt');?></a>
				<a href="?page=hmgt_invoice&tab=addexpense&action=edit&expense_id=<?php echo $retrieved_data->income_id;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
                <a href="?page=hmgt_invoice&tab=expenselist&action=delete&expense_id=<?php echo $retrieved_data->income_id;?>" class="btn btn-danger" 
                onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
                <?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>
                </td>
            </tr>
            <?php } 
			
		?>
     
        </tbody>
        
        </table>
        </div>
        </div>
	 <?php  }?>