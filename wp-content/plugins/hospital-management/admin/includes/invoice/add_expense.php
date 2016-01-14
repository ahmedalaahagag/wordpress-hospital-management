<?php 
	//This is Dashboard at admin side
	$obj_invoice= new Hmgtinvoice();
	?>
	<script type="text/javascript">

$(document).ready(function() {
	$('#expense_form').validationEngine();
	$('#invoice_date').datepicker({
		  changeMonth: true,
	        changeYear: true,
	        dateFormat: 'yy-mm-dd',
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            $(this).val(month + "/" + year);
	        }
                    
                }); 
		
} );
</script>
	
     <?php 	

	if($active_tab == 'addexpense')
	 {
        	$expense_id=0;
			if(isset($_REQUEST['expense_id']))
				$expense_id=$_REQUEST['expense_id'];
			$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
					
					$edit=1;
					$result = $obj_invoice->hmgt_get_income_data($expense_id);
					//var_dump($result);
				
				}?>
		
       <div class="panel-body">
        <form name="expense_form" action="" method="post" class="form-horizontal" id="expense_form">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="expense_id" value="<?php echo $expense_id;?>">
		<input type="hidden" name="invoice_type" value="expense">
		
		
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="patient"><?php _e('Supplier Name','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="party_name" class="form-control validate[required] text-input" type="text" value="<?php if($edit){ echo $result->party_name;}elseif(isset($_POST['party_name'])) echo $_POST['party_name'];?>" name="party_name">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="payment_status"><?php _e('Status','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select name="payment_status" id="payment_status" class="form-control validate[required]">
					<option value="Paid"
						<?php if($edit)selected('Paid',$result->payment_status);?> ><?php _e('Paid','hospital_mgt');?></option>
						<option value="Unpaid"
						<?php if($edit)selected('Unpaid',$result->payment_status);?>><?php _e('Unpaid','hospital_mgt');?></option>
			</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="invoice_date"><?php _e('Date','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="invoice_date" class="form-control validate[required]" type="text"  value="<?php if($edit){ echo $result->income_create_date;}elseif(isset($_POST['invoice_date'])){ echo $_POST['invoice_date'];}else{ echo date("Y-m-d");}?>" name="invoice_date">
			</div>
		</div>
		<hr>
		
		<?php 
			
			if($edit){
				$all_entry=json_decode($result->income_entry);
			}
			else
			{
				if(isset($_POST['income_entry'])){
					
					$all_data=$obj_invoice->get_entry_records($_POST);
					$all_entry=json_decode($all_data);
				}
				
					
			}
			if(!empty($all_entry))
			{
					foreach($all_entry as $entry){
					?>
					<div id="expense_entry">
						<div class="form-group">
						<label class="col-sm-2 control-label" for="income_entry"><?php _e('Expense Entry','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-2">
							<input id="income_amount" class="form-control validate[required] text-input" type="text" value="<?php echo $entry->amount;?>" name="income_amount[]" >
						</div>
						<div class="col-sm-4">
							<input id="income_entry" class="form-control validate[required] text-input" type="text" value="<?php echo $entry->entry;?>" name="income_entry[]">
						</div>
						
						<div class="col-sm-2">
						<button type="button" class="btn btn-default" onclick="deleteParentElement(this)">
						<i class="entypo-trash"><?php _e('Delete','hospital_mgt');?></i>
						</button>
						</div>
						</div>	
					</div>
					<?php }
				
			}
			else
			{?>
					<div id="expense_entry">
						<div class="form-group">
						<label class="col-sm-2 control-label" for="income_entry"><?php _e('Expense Entry','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-2">
							<input id="income_amount" class="form-control validate[required] text-input" type="text" value="" name="income_amount[]" placeholder="Expense Amount">
						</div>
						<div class="col-sm-4">
							<input id="income_entry" class="form-control validate[required] text-input" type="text" value="" name="income_entry[]" placeholder="Expense Entry Label">
						</div>
						
						<div class="col-sm-2">
						<button type="button" class="btn btn-default" onclick="deleteParentElement(this)">
						<i class="entypo-trash"><?php _e('Delete','hospital_mgt');?></i>
						</button>
						</div>
						</div>	
					</div>
					
		<?php }?>
		
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="expense_entry"></label>
			<div class="col-sm-3">
				
				<button id="add_new_entry" class="btn btn-default btn-sm btn-icon icon-left" type="button"   name="add_new_entry" onclick="add_entry()"><?php _e('Add Expense Entry','hospital_mgt'); ?>
				</button>
			</div>
		</div>
		<hr>
		<div class="col-sm-offset-2 col-sm-8">
        	<input type="submit" value="<?php if($edit){ _e('Save Expense','hospital_mgt'); }else{ _e('Create Expense Entry','hospital_mgt');}?>" name="save_expense" class="btn btn-success"/>
        </div>
        </form>
        </div>
       <script>

   
   
   	
  
   	// CREATING BLANK INVOICE ENTRY
   	var blank_income_entry ='';
   	$(document).ready(function() { 
   		blank_expense_entry = $('#expense_entry').html();
   		//alert("hello" + blank_invoice_entry);
   	}); 

   	function add_entry()
   	{
   		$("#expense_entry").append(blank_expense_entry);
   		//alert("hellooo");
   	}
   	
   	// REMOVING INVOICE ENTRY
   	function deleteParentElement(n){
   		n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
   	}
       </script> 
     <?php 
	 }
	 ?>