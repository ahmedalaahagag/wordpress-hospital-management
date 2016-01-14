<?php
//This is Dashboard at admin side
$obj_user  = new Hmgtuser();
$obj_package  = new Hmgt_packages();
$obj_sesssion_duration  = new Hmgt_session_duration();

?>
		<div class="panel-body" style="display: block;">
		<form name="income_form" action="" method="post" class="form-horizontal" id="income_form">
			<div class="form-group">
				<label class="col-sm-2 control-label" for="patient"><?php _e('Patient','hospital_mgt');?><span class="require-field"></span></label>
				<div class="col-sm-8">
					<?php if($edit){ $patient_id1=$result->party_name; }elseif(isset($_POST['patient'])){$patient_id1=$_POST['patient'];}else{ $patient_id1="";}?>
					<select name="party_name" class="form-control validate[]" id="patient">
						<option value=""><?php _e('Select Patient','hospital_mgt');?></option>
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
					</select>*change patient to calculate his invoice
				</div>
			</div>
			<script type="text/javascript">
				jQuery("#patient").change(function() {
				$("#invoice").hide();
				$("#invoice").show();
				});

			</script>

			<div  id="invoice" style="display: none;">
			<div class="form-group">
				<label class="col-sm-2 control-label" for="payment_status"><?php _e('Status','school-mgt');?><span class="require-field"></span></label>
				<div class="col-sm-8">
					<select name="payment_status" id="payment_status" class="form-control validate[]">
						<option value="Paid"
								<?php if($edit)selected('Paid',$result->payment_status);?> ><?php _e('Paid','hospital_mgt');?></option>
						<option value="Part Paid" selected="selected"
								<?php if($edit)selected('Part Paid',$result->payment_status);?>><?php _e('Part Paid','hospital_mgt');?></option>
						<option value="Unpaid"
								<?php if($edit)selected('Unpaid',$result->payment_status);?>><?php _e('Unpaid','hospital_mgt');?></option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="invoice_date"><?php _e('Date','hospital_mgt');?><span class="require-field"></span></label>
				<div class="col-sm-8">
					<input id="invoice_date" class="form-control " type="text"  value="<?php if($edit){ echo $result->income_create_date;}elseif(isset($_POST['invoice_date'])){ echo $_POST['invoice_date'];}else{ echo date("Y-m-d");}?>" name="invoice_date">
				</div>
			</div>
			<hr>

			<?php

					$session_price = $obj_sesssion_duration->get_duration_price($session->duration_id);
					$session_mins = $obj_sesssion_duration->get_duration_mins($session->duration_id);
					$session_discount =  $obj_sesssion_duration->has_discount(3);
					$sessions_total += $session_price;
					?>
				<h2>Paid Sessions</h2>
					<div id="sesssion_entry">
						<div class="form-group">
							<label class="col-sm-2 control-label" for="income_entry"><?php _e('Session Entry','hospital_mgt');?><span class="require-field"></span></label>
							<div class="col-sm-4">
								<input id="package_name" class="form-control validate[] text-input" type="text" readonly value="New Treatment Packages" name="[]">
							</div>
							<div class="col-sm-2">
								<input id="session_duration" class="form-control validate[] text-input" type="text" readonly value="60 Mins" name="[]">
							</div>
							<div class="col-sm-2">
								<input id="session_price" class="form-control validate[] text-input" type="text" readonly value="100 LE" name="[]">
							</div>


						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="income_entry"><?php _e('Session Entry','hospital_mgt');?><span class="require-field"></span></label>
							<div class="col-sm-4">
								<input id="package_name" class="form-control validate[] text-input" type="text" readonly value="New Treatment Packages" name="[]">
							</div>
							<div class="col-sm-2">
								<input id="session_duration" class="form-control validate[] text-input" type="text" readonly value="45 Mins" name="[]">
							</div>
							<div class="col-sm-2">
								<input id="session_price" class="form-control validate[] text-input" type="text" readonly value="50 LE" name="[]">
							</div>


						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="income_entry"><?php _e('Session Entry','hospital_mgt');?><span class="require-field"></span></label>
							<div class="col-sm-4">
								<input id="package_name" class="form-control validate[] text-input" type="text" readonly value="New Treatment Packages" name="[]">
							</div>
							<div class="col-sm-2">
								<input id="session_duration" class="form-control validate[] text-input" type="text" readonly value="35 Mins" name="[]">
							</div>
							<div class="col-sm-2">
								<input id="session_price" class="form-control validate[] text-input" type="text" readonly value="150 LE" name="[]">
							</div>


						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="invoice_date"><?php _e('Discount','hospital_mgt');?><span class="require-field"></span></label>
							<div class="col-sm-8">
								<input id="invoice_date" class="form-control " type="text"  value="0%" name="invoice_date">
							</div>
						</div>
			<hr>


		</form>
			<h2>Unpaid Sessions</h2>
			<div id="sesssion_entry">
				<div class="form-group">
					<label class="col-sm-2 control-label" for="income_entry"><?php _e('Session Entry','hospital_mgt');?><span class="require-field"></span></label>
					<div class="col-sm-4">
						<input id="package_name" class="form-control validate[] text-input" type="text" readonly value="New Treatment Packages" name="[]">
					</div>
					<div class="col-sm-2">
						<input id="session_duration" class="form-control validate[] text-input" type="text" readonly value="60 Mins" name="[]">
					</div>
					<div class="col-sm-2">
						<input id="session_price" class="form-control validate[] text-input" type="text" readonly value="100 LE" name="[]">
					</div>


				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="income_entry"><?php _e('Session Entry','hospital_mgt');?><span class="require-field"></span></label>
					<div class="col-sm-4">
						<input id="package_name" class="form-control validate[] text-input" type="text" readonly value="New Treatment Packages" name="[]">
					</div>
					<div class="col-sm-2">
						<input id="session_duration" class="form-control validate[] text-input" type="text" readonly value="45 Mins" name="[]">
					</div>
					<div class="col-sm-2">
						<input id="session_price" class="form-control validate[] text-input" type="text" readonly value="50 LE" name="[]">
					</div>


				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="income_entry"><?php _e('Session Entry','hospital_mgt');?><span class="require-field"></span></label>
					<div class="col-sm-4">
						<input id="package_name" class="form-control validate[] text-input" type="text" readonly value="New Treatment Packages" name="[]">
					</div>
					<div class="col-sm-2">
						<input id="session_duration" class="form-control validate[] text-input" type="text" readonly value="35 Mins" name="[]">
					</div>
					<div class="col-sm-2">
						<input id="session_price" class="form-control validate[] text-input" type="text" readonly value="150 LE" name="[]">
					</div>


				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="invoice_date"><?php _e('Discount','hospital_mgt');?><span class="require-field"></span></label>
					<div class="col-sm-8">
						<input id="invoice_date" class="form-control " type="text"  value="0%" name="invoice_date">
					</div>
				</div>
				<hr>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="invoice_date"><?php _e('Total','hospital_mgt');?><span class="require-field"></span></label>
					<div class="col-sm-8">
						<input id="invoice_date" class="form-control " type="text"  value="300 LE" name="invoice_date">
					</div>
				</div>

				</form>
	</div>
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
