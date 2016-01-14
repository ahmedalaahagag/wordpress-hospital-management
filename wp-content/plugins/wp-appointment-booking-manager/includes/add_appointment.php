<?php
	global $table_prefix,$wpdb;
	$sql_paymentmethod = "select * from ".$table_prefix."appointgen_ustsappointments_paymentmethods";
	$payment_methods = $wpdb->get_results( $sql_paymentmethod );
	$current_user = wp_get_current_user();
	?>
	<style type="text/css">
		li .active{
			color: red;
		}
		li {
			margin-bottom: 0px;
		}
		#namediv input{
			width: 70%;
		}
	</style>
   
	<script type="text/javascript">
	var venue_service_schedule = Array();
  <?php if(isset($tvs_result[0])){ ?>
    venue_service_schedule = <?php echo json_encode($tvs_result[0]);?>;
  <?php } ?>  
	var dayscond = "";
	if(venue_service_schedule == "" || venue_service_schedule == null ){
		dayscond = "1";
	}
	else{
		var days = venue_service_schedule['days'];
		var daysarr = days.split(',');
		
		for(var i=0;i<daysarr.length;i++){
			if(i==(daysarr.length-1)){
				dayscond = dayscond + 'date.getDay() == '+daysarr[i]+'';
			}
			else{
				dayscond = dayscond + 'date.getDay() == '+daysarr[i]+' || ';
			}
		}
	}
	
  jQuery(function() {
		jQuery("#dtpdate").datepicker({
      dateFormat: "yy-mm-dd", 
			beforeShowDay: function(date){ 
				if(eval(dayscond)){
					return [1];
				}
				else{
					return [0];
				}
			
			}
		
		});
	});
	// Read a page's GET URL variables and return them as an associative array.
	function appointgen_getUrlVars()
	{
			var vars = [], hash;
			var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
			for(var i = 0; i < hashes.length; i++)
			{
					hash = hashes[i].split('=');
					vars.push(hash[0]);
					vars[hash[0]] = hash[1];
			}
			return vars;
	}
	//
	function appointgen_get_serviceprice(){
			var arr_schedules = new Array();
			var schedule = jQuery('#schedule_select').val();
			jQuery.ajax({
					type: "POST",
          url: '<?php echo admin_url( 'admin-ajax.php' );?>',
					data: {
              action: 'appointgen_get_serviceprice_by_schedule',
              schedule: schedule
          },
					success: function (data) {
							var count = data.length;
							jQuery('#txtCustomPrice').val(data);
					},
					complete: function (data){
					},
					error : function(s , i , error){
							console.log(error);
					}
			});
	}
	function appointgen_setappointment_info(appointment_id){
			jQuery.ajax({
					type: "POST",
          url: '<?php echo admin_url( 'admin-ajax.php' );?>',
					dataType:'json', 
					data: {
            action: 'appointgen_get_appointments',  
            appointment_id:appointment_id
          },
					success: function (data) {
							var count = data.length;
							if(data.length > 0 ){
								var appointment = data[0];
								jQuery('.hdnappointmentidcls').val(appointment['appointment_id']);
								var roomids = appointment['room_id'].split(',');
					
								jQuery('#dtpfromdate').val(appointment['from_date']);
								jQuery('#dtptodate').val(appointment['to_date']);
								
								jQuery('#txtFirstName').val(appointment['first_name']);
								jQuery('#txtLastName').val(appointment['last_name']);
								jQuery('#txtEmail').val(appointment['email']);
								jQuery('#txtPhone').val(appointment['phone']);
								jQuery('#details').val(appointment['details']);
								jQuery('#txtappointmentby').val(appointment['appointment_by']);
								jQuery('#optguest_type').val(appointment['guest_type']);
								jQuery('#txtCustomPrice').val(appointment['custom_price']);
								jQuery('#txtPaid').val(appointment['paid']);
								jQuery('#txtDue').val(appointment['due']);
								jQuery('#optpaymentmethod').val(appointment['payment_method']);
								jQuery('#txtTrackingNo').val(appointment['tracking_no']);
							}
					},
					error : function(s , i , error){
							console.log(error);
					}
			});
	}
	function appointgen_cleardata(){
			jQuery('#hdnappointmentid').val('');
			jQuery('#dtpdate').val('');
			jQuery('#starttime').val('');
			jQuery('#endtime').val('');
			jQuery('#starttime').val('');
			jQuery('#txtFirstName').val('');
			jQuery('#txtLastName').val('');
			jQuery('#txtEmail').val('');
			jQuery('#txtPhone').val('');
			jQuery('#details').val('');
			jQuery('#txtappointmentby').val('<?php echo $current_user->display_name?>');
			jQuery('#txtCustomPrice').val('');
			jQuery('#optpaymentmethod').val('');
	}
	function appointgen_load_moredeals_data_pagerefresh(page){
			jQuery.ajax
			({
					type: "POST",
          url: '<?php echo admin_url( 'admin-ajax.php' );?>',
					data: {
            action: 'appointgen_load_manageappointment_data_front',  
            page: page
          },
					success: function(msg)
					{
							jQuery("#inner_content").ajaxComplete(function(event, request, settings)
							{
									jQuery("#inner_content").html(msg);
							});
					}
					
			});
	
	}
	
	jQuery(document).ready(function(){
			//----------------multiselect combo---------------
			appointgen_get_serviceprice();
			var calltype = appointgen_getUrlVars()["calltype"];
			if(calltype){
				if(calltype = 'editappointment'){
					<?php
          if(isset($_REQUEST['id'])){
            $id = $_REQUEST['id'];
            global $table_prefix,$wpdb;
            $sql = "select * from ".$table_prefix."appointgen_ustsappointment where appointment_id=".$id;
            $result = $wpdb->get_results($sql);
            ?>
            var appointment = <?php echo json_encode($result[0]);?>;
            jQuery('#hdnappointmentid').val(appointment['appointment_id']);
            jQuery('#dtpfromdate').val(appointment['from_date']);
            jQuery('#txtFirstName').val(appointment['first_name']);
            jQuery('#txtLastName').val(appointment['last_name']);
            jQuery('#txtEmail').val(appointment['email']);
            jQuery('#txtPhone').val(appointment['phone']);
            jQuery('#details').val(appointment['details']);
            jQuery('#txtappointmentby').val(appointment['appointment_by']);
            jQuery('#optguest_type').val(appointment['guest_type']);
            jQuery('#txtCustomPrice').val(appointment['custom_price']);
            jQuery('#txtPaid').val(appointment['paid']);
            jQuery('#txtDue').val(appointment['due']);
            jQuery('#optpaymentmethod').val(appointment['payment_method']);
            jQuery('#txtTrackingNo').val(appointment['tracking_no']);
          <?php } ?>  
				}	
			}
			jQuery('#schedule_select').on("change",function(){
				appointgen_get_serviceprice();
			});
			jQuery('#frmappointment').on('submit',function(e){
	  		 e.preventDefault();
				 appointgen_save_appointment();
			});
			<?php if(isset($_REQUEST['calendarcell'])){
			$calendarcell = $_REQUEST['calendarcell'];
			$calendarcell_data = explode("|",$calendarcell);
			$cell_month_cat = $calendarcell_data[0];
			$cell_month = $calendarcell_data[1];
			$cell_date =  $calendarcell_data[2];
			?>
					jQuery('#dtpfromdate').val('<?php// echo $cell_date;?>');	
					jQuery('#dtptodate').val('<?php echo $cell_date;?>');	
					
			<?php }?>
	});
  function validateTime(strTime) {
    var regex = new RegExp("([0-1][0-9]|2[0-3]):([0-5][0-9])");
    if (regex.test(strTime)) {
      return true;
    } else {
      return false;
    }
  }
	function appointgen_save_appointment(){
			var hdnappointmentid = jQuery('.hdnappointmentidcls').val();
			var schedule = jQuery('#schedule_select :selected').text();
      var schedule_id = jQuery('#schedule_select :selected').val();
      //alert(schedule_id);return;
			var date = jQuery('#dtpdate').val();
			var starttime = jQuery('#starttime').val();
			var endtime = jQuery('#endtime').val();
			var timeshift = jQuery('#timeshift').val();
			
			var first_name = jQuery('#txtFirstName').val();
			var last_name = jQuery('#txtLastName').val();
			var email = jQuery('#txtEmail').val();
			var phone = jQuery('#txtPhone').val();
			var details = jQuery('#details').val();
			var appointmentby = jQuery('#txtappointmentby').val();
			var guest_type = jQuery('#optguest_type').val();
			var price = jQuery('#txtCustomPrice').val();
			var payment_method = jQuery('#optpaymentmethod').find('option:selected').val();
			if(schedule == ""){
				alert('Please choose at Least a Schedule.');
				return false;
			}
			else if(date==""){
				alert('Please choose a date.');
				return;
			}
			else if(starttime==""){
				alert('Please choose a StartTime.');
				return;
			}
      else if(!validateTime(starttime)){
        alert('Please Correct Start Time Format');
        return;
      }
			else if(endtime==""){
				alert('Please choose a EndTime.');
				return;
			}
      else if(!validateTime(endtime)){
        alert('Please Correct End Time Format');
        return;
      }
			else if(email!=''){
				if(!appointgen_validateEmail(email)){
					alert('Please input a valid email Address.');
					return false;
				}
			}
			else if(phone==''){
				alert('please input your phone number.');
				return false;
			}
			jQuery.ajax({
					type: "POST",
          url: '<?php echo admin_url( 'admin-ajax.php' );?>',
					data: {
            action:'appointgen_check_appointment',
            hdnappointmentid: hdnappointmentid,schedule: schedule,date:date,starttime:starttime,endtime: endtime, timeshift: timeshift},
					  success: function (data) {
              data = data.trim();
							if(data=='yes'){
								alert('Sorry! Already Booked!');
								return;
							}
							else if(data=='no'){
 								jQuery.ajax({
											type: "POST",
										  url: '<?php echo admin_url( 'admin-ajax.php' );?>',
											data: {
                        action: 'appointgen_save_appointment',
                        hdnappointmentid: hdnappointmentid,scheduleid:schedule_id,schedule: schedule,date: date, start_time:starttime,end_time:endtime,time_shift:timeshift, first_name:first_name,last_name:last_name,email:email,phone:phone,details: details,appointmentby: appointmentby, price: price,payment_method: payment_method 
                      },
											success: function (data) {
													if(data.length>0){
                            alert('added successfully');
                            window.location.href = "<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=appointment-calendar-menu";
													}
											},
											error : function(s , i , error){
													console.log(error);
											}
									});
							}
					},
					error : function(s , i , error){
							console.log(error);
					}
			});
			
	}
	function appointgen_validateEmail(email) {
			var atpos=email.indexOf("@");
			var dotpos=email.lastIndexOf(".");
			if (atpos < 1 || dotpos < atpos+2 || dotpos+2 >= email.length) {
					return false;
			}
			return true;
	}
	//===-----------------------add appointment dialog-------------------------------===
  </script>
  <style type="text/css">
	#frmappointment select, button, input, textarea {
		border:1px solid #E2E2E2;
		margin:5px;
	}
	#frmappointment label {
		margin:3px;
		width:135px;
	}
	span{font-size:12px;}
	#frmappointment table{
		width: 50%;
	}
	input.rounded {
			border: 1px solid #ccc;
	    -moz-border-radius: 5px;
	    -webkit-border-radius: 5px;
	    border-radius: 5px;
	    font-size: 20px;
	    padding: 4px 7px;
	    outline: 0;
	    -webkit-appearance: none;
	}
	select.rounded {
			border: 1px solid #ccc;
	    -moz-border-radius: 5px;
	    -webkit-border-radius: 5px;
	    border-radius: 5px;
	    font-size: 20px;
	    padding: 4px 7px;
	    outline: 0;
	    -webkit-appearance: none;
	}
	input.rounded:focus {
	    border-color: #4CB7FF;
	}
	.ui-dialog {
		z-index:3000!important;
	}
	/*-------theme css override------*/
	select, input[type="text"]{
		height: 35px;;
	}	
	
  </style>
  <?php $current_user = wp_get_current_user();
	?>
 <div id="addappointment_dialog" title="Add/Edit Appointment" class="wrapper" style="display:none;z-index:5000">
  <div class="wrap" style="float:left; width:100%;">
    <div class="main_div">
     	<div class="metabox-holder" style="width:49%; float:left;">
        <form id="frmappointment" action="" method="post" style="width:100%">
          <table style="margin:10px;width:300px;">
          	<tr>
            	<td class="appointmentlavel"> <label for="room"> <?php _e("Schedule","appointgen_ustsappointment"); ?> </label></td>
              <td class="appointmentinput" id="multi_rooms_select" style="padding:0 0 5px 5px;">
              	<select id="schedule_select" >
                  <?php 
									$sql_schedules = "select * from ".$table_prefix."appointgen_schedules";
									 
									$schedules = $wpdb->get_results($sql_schedules);	
									foreach($schedules as $schedule){
									?>
                  	<option value="<?php echo $schedule->id;?>"><?php printf(__("%s","appointgen_ustsappointment"),$schedule->schedule_name);?></option>
                  <?php } ?>
                </select><span style="color:red;">*</span>
              </td>
            </tr>
            <tr>
            	<td class="appointmentlavel">
              	<?php _e("Date:","appointgen_ustsappointment"); ?>
              </td>
              <td class="appointmentinput">
              	<input type="text" id="dtpdate" name="dtpdate" value="" style="width:230px;" /><span style="color:red;">*</span>
              </td>
            </tr>
            <tr>
            	<td class="appointmentlavel">
              	<?php _e("Start Time:","appointgen_ustsappointment"); ?>
              </td>
              <td class="appointmentinput">
              	<input type="text" id="starttime" name="starttime" value="" style="width:230px;" /><span style="color:red;">*</span><br>
                <span style="font-style:italic;font-size:11px;"><?php _e("Must be in format of: 09:00","appointgen_ustsappointment"); ?></span>
              </td>
            </tr>
            <tr>
            	<td class="appointmentlavel">
              	<?php _e("End Time:","appointgen_ustsappointment"); ?>
              </td>
              <td class="appointmentinput">
              	<input type="text" id="endtime" name="endtime" value="" style="width:230px;" /><span style="color:red;">*</span><br>
                <span style="font-style:italic;font-size:11px;"><?php _e("Must be in format of: 10:00","appointgen_ustsappointment"); ?></span>
              </td>
            </tr>
            <tr>
            	<td class="appointmentlavel">
              	<?php _e("Time Shift:","appointgen_ustsappointment"); ?>
              </td>
              <td class="appointmentinput">
                <select id="timeshift" name="timeshift" style="height:21px;" >
                	<option value="am"><?php _e("AM","appointgen_ustsappointment"); ?></option>
                  <option value="pm"><?php _e("PM","appointgen_ustsappointment"); ?></option>
                </select>
              </td>
            </tr>
            <tr>
            	<td class="appointmentlavel">
              	<?php _e("First Name:","appointgen_ustsappointment"); ?>
              </td>
              <td class="appointmentinput">
              	<input type="text" id="txtFirstName" name="txtFirstName" value="" />
              </td>
            </tr>
            <tr>
            	<td class="appointmentlavel">
              	<?php _e("Last Name:","appointgen_ustsappointment"); ?>
              </td>
              <td class="appointmentinput">
              	<input type="text" id="txtLastName" name="txtLastName" value="" />
              </td>
            </tr>
            <tr>
            	<td class="appointmentlavel">
              	<?php _e("Email:","appointgen_ustsappointment"); ?>
              </td>
              <td class="appointmentinput">
              	<input type="text" id="txtEmail" name="txtEmail" value="" /><!--<span style="color:red;">*</span>-->
              </td>
            </tr>
            <tr>
            	<td class="appointmentlavel">
              	<?php _e("Phone:","appointgen_ustsappointment"); ?>
              </td>
              <td class="appointmentinput">
              	<input type="text" id="txtPhone" name="txtPhone" value="" /><span style="color:red;">*</span>
              </td>
            </tr>
            <tr>
            	<td class="appointmentlavel">
              	<?php _e("Details:","appointgen_ustsappointment"); ?>
              </td>
              <td class="appointmentinput">
              	<textarea cols="35" rows="10" id="details" name="details"></textarea>
              </td>
            </tr>
            <tr>
            	<td class="appointmentlavel">
              	<?php _e("Appointment By:","appointgen_ustsappointment"); ?>
              </td>
              <td class="appointmentinput">
              	<input type="text" readonly="readonly" id="txtappointmentby" name="txtappointmentby" value="<?php printf(__("%s","appointgen_ustsappointment"), $current_user->display_name); ?>" />
              </td>
            </tr>
            <tr>
            	<td class="appointmentlavel">
              	<?php _e("Price:","appointgen_ustsappointment"); ?>
              </td>
              <td class="appointmentinput">
              	<input type="text" id="txtCustomPrice" name="txtCustomPrice" value="" />
              </td>
            </tr>
            <tr>
            	<td class="appointmentlavel">
              	<?php _e("Payment Method:","appointgen_ustsappointment"); ?>
              </td>
              <td class="appointmentinput">
              	<select id="optpaymentmethod" name="optpaymentmethod" >
                	<?php foreach($payment_methods as $pm){?>
                  	<option value="<?php echo $pm->payment_method;?>"><?php printf(__("%s","appointgen_ustsappointment"),$pm->payment_method);?></option>
                  <?php }?>  
                </select>
              </td>
            </tr>
            <tr>
            	<td class="appointmentlavel" colspan="2" style="height:15px;">
              	<input type="hidden" class="hdnappointmentidcls" id="hdnappointmentid" name="hdnappointmentid" value="" style="width:150px;"/>
              </td>
            </tr>
          </table>
        </form>
    	</div>
    </div>
  </div>
 </div>