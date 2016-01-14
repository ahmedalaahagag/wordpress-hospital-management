<?php 
	//This is Dashboard at admin side
	$obj_invoice= new Hmgtinvoice();
	?>
	<script type="text/javascript">

$(document).ready(function() {
	$('#income_form').validationEngine();
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

	if($active_tab == 'addincome')
	 {
        	$income_id=0;
			if(isset($_REQUEST['income_id']))
				$income_id=$_REQUEST['income_id'];
			$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
					
					$edit=1;
					$result = $obj_invoice->hmgt_get_income_data($income_id);
					//var_dump($result);
				
				}?>
		
       <div class="panel-body">
        <form name="income_form" action="" method="post" class="form-horizontal" id="income_form">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="income_id" value="<?php echo $income_id;?>">
		<input type="hidden" name="invoice_type" value="income">
		
		
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="patient"><?php _e('Patient','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				
				<?php if($edit){ $patient_id1=$result->party_name; }elseif(isset($_POST['patient'])){$patient_id1=$_POST['patient'];}else{ $patient_id1="";}?>
				<select name="party_name" class="form-control validate[required]">
				<option value=""><?php _e('select Patient','hospital_mgt');?></option>
				<?php 
					
					$patients = hmgt_patientid_list();
					//print_r($patient);
					if(!empty($patients))
					{
					foreach($patients as $patient)
					{
						echo '<option value="'.$patient['id'].'" '.selected($patient_id1,$patient['id']).'>'.$patient['patient_id'].' - '.$patient['first_name'].' '.$patient['last_name'].'</option>';
					}
					}?>
				</select>
			</div>
		</div>
		
		
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="payment_status"><?php _e('Status','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select name="payment_status" id="payment_status" class="form-control validate[required]">
					<option value="Paid"
						<?php if($edit)selected('Paid',$result->payment_status);?> ><?php _e('Paid','hospital_mgt');?></option>
					<option value="Part Paid"
						<?php if($edit)selected('Part Paid',$result->payment_status);?>><?php _e('Part Paid','hospital_mgt');?></option>
						<option value="Unpaid"
						<?php if($edit)selected('Unpaid',$result->payment_status);?>><?php _e('Unpaid','hospital_mgt');?></option>
			</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="invoice_date"><?php _e('Date','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="invoice_date" class="form-control " type="text"  value="<?php if($edit){ echo $result->income_create_date;}elseif(isset($_POST['invoice_date'])){ echo $_POST['invoice_date'];}else{ echo date("Y-m-d");}?>" name="invoice_date">
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
					<div id="income_entry">
						<div class="form-group">
						<label class="col-sm-2 control-label" for="income_entry"><?php _e('Income Entry','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-2">
							<input id="income_amount" class="form-control validate[required] text-input" type="text" value="<?php echo $entry->amount;?>" name="income_amount[]">
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
					<div id="income_entry">
						<div class="form-group">
						<label class="col-sm-2 control-label" for="income_entry"><?php _e('Income Entry','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-2">
							<input id="income_amount" class="form-control validate[required] text-input" type="text" value="" name="income_amount[]" placeholder="Income Amount">
						</div>
						<div class="col-sm-4">
							<input id="income_entry" class="form-control validate[required] text-input" type="text" value="" name="income_entry[]" placeholder="Income Entry Label">
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
			<label class="col-sm-2 control-label" for="income_entry"></label>
			<div class="col-sm-3">
				
				<button id="add_new_entry" class="btn btn-default btn-sm btn-icon icon-left" type="button"   name="add_new_entry" onclick="add_entry()"><?php _e('Add Income Entry','hospital_mgt'); ?>
				</button>
			</div>
		</div>
		<hr>
		<div class="col-sm-offset-2 col-sm-8">
        	<input type="submit" value="<?php if($edit){ _e('Save Income','hospital_mgt'); }else{ _e('Create Income Entry','hospital_mgt');}?>" name="save_income" class="btn btn-success"/>
        </div>
        </form>
        </div>
       <script>
   
   
   	
  
   	// CREATING BLANK INVOICE ENTRY
   	var blank_income_entry ='';
   	$(document).ready(function() { 
   		blank_income_entry = $('#income_entry').html();
   		//alert("hello" + blank_invoice_entry);
   	}); 

   	function add_entry()
   	{
   		$("#income_entry").append(blank_income_entry);
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