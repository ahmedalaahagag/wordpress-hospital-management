<?php
	global $table_prefix,$wpdb;
	$sql_paymentmethod = "select * from ".$table_prefix."appointgen_ustsappointments_paymentmethods";
	$payment_methods = $wpdb->get_results( $sql_paymentmethod );
	$current_user = wp_get_current_user();
  $output .="
	<style type='text/css'>
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
   
	<script type='text/javascript'>";
  $output .= "var venue_service_schedule = Array();";
  if(isset($tvs_result[0])){
      $output .=" venue_service_schedule = ".json_encode($tvs_result[0]).";";
  }  
	$output .="
	var dayscond = '';
	if(venue_service_schedule == '' || venue_service_schedule == null ){
		dayscond = '1';
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
		jQuery('#dtpdate').datepicker({
      dateFormat: 'yy-mm-dd',
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
	function appointgen_setappointment_info(appointment_id){
			jQuery.ajax({
					type: 'POST',
          url: '".admin_url( 'admin-ajax.php' )."',  
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

								jQuery('#dtpdate').val(appointment['date']);
								
								jQuery('#txtFirstName').val(appointment['first_name']);
								jQuery('#txtLastName').val(appointment['last_name']);
								jQuery('#txtEmail').val(appointment['email']);
								jQuery('#txtPhone').val(appointment['phone']);
								jQuery('#details').val(appointment['details']);
								jQuery('#txtappointmentby').val(appointment['appointment_by']);
								jQuery('#txtCustomPrice').val(appointment['custom_price']);
								jQuery('#optpaymentmethod').val(appointment['payment_method']);
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
			jQuery('#txtappointmentby').val('".$current_user->display_name."');
			jQuery('#txtCustomPrice').val('');
			jQuery('#optpaymentmethod').val('');
	}
	function appointgen_load_moredeals_data_pagerefresh(page){
			jQuery.ajax
			({
					type: 'POST',
          url: '".admin_url( 'admin-ajax.php' )."',
					data: {
          action:'appointgen_load_manageappointment_data_front',
          page: page
          },
					success: function(msg)
					{
							jQuery('#inner_content').ajaxComplete(function(event, request, settings)
							{
									jQuery('#inner_content').html(msg);
							});
					}
					
			});
	}
	function appointgen_get_serviceprice(){
			var arr_schedules = new Array();
			var schedule = jQuery('#schedule_select').val();
			jQuery.ajax({
					type: 'POST',
          url: '".admin_url( 'admin-ajax.php' )."',   
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
	jQuery(document).ready(function(){
			appointgen_get_serviceprice();
			jQuery('#schedule_select').on('change',function(){
				appointgen_get_serviceprice();
			});
			
			var calltype = appointgen_getUrlVars()['calltype'];
			if(calltype){
				if(calltype = 'editappointment'){";
					if(isset($_REQUEST['id'])){
          $id = $_REQUEST['id'];
					global $table_prefix,$wpdb;
					$sql = "select * from ".$table_prefix."appointgen_ustsappointments where appointment_id=".$id;
					$result = $wpdb->get_results($sql);
					$output .="var appointment = ".json_encode($result[0]).";
					jQuery('#hdnappointmentid').val(appointment['appointment_id']);
					jQuery('#dtpdate').val(appointment['date']);
					
					jQuery('#txtFirstName').val(appointment['first_name']);
					jQuery('#txtLastName').val(appointment['last_name']);
					jQuery('#txtEmail').val(appointment['email']);
					jQuery('#txtPhone').val(appointment['phone']);
					jQuery('#details').val(appointment['details']);
					jQuery('#txtappointmentby').val(appointment['appointment_by']);
					jQuery('#txtCustomPrice').val(appointment['custom_price']);
					jQuery('#optpaymentmethod').val(appointment['payment_method']);";
          }
				$output .="}	
			}
			jQuery('#dtptodate').on('change',function(){
				appointgen_get_serviceprice();
			});
			jQuery('#frmappointment').on('submit',function(e){
	  		 e.preventDefault();
				 appointgen_save_appointment();
			});";
			if(isset($_REQUEST['calendarcell'])){
				$calendarcell = $_REQUEST['calendarcell'];
				$calendarcell_data = explode("|",$calendarcell);
				$cell_month_cat = $calendarcell_data[0];
				$cell_month = $calendarcell_data[1];
				$cell_date =  $calendarcell_data[2];
				$output .="jQuery('#dtptodate').val('".$cell_date."');";
			}
	$output .="});
  function validateTime(strTime) {
    var regex = new RegExp('([0-1][0-9]|2[0-3]):([0-5][0-9])');
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
	
			if(schedule == ''){
				alert('Please choose at Least a Schedule.');
				return false;
			}
			else if(date==''){
				alert('Please choose a date.');
				return false;
			}
			else if(starttime==''){
				alert('Please choose a StartTime.');
				return false;
			}
      else if(!validateTime(starttime)){
        alert('Please Correct Start Time Format');
        return;
      }
			else if(endtime==''){
				alert('Please choose a EndTime.');
				return false;
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
					type: 'POST',
          url: '".admin_url( 'admin-ajax.php' )."',  
					data: {
            action: 'appointgen_check_appointment',
            hdnappointmentid: hdnappointmentid,schedule: schedule,date:date,starttime:starttime,endtime: endtime, timeshift: timeshift
          },
					success: function (data) {
              data = data.trim();
							if(data=='yes'){
								alert('Sorry! Already Booked!');
								return;
							}
							else if(data=='no'){
 								jQuery.ajax({
											type: 'POST',
                      url: '".admin_url( 'admin-ajax.php' )."',
											data: {
                        action: 'appointgen_save_appointment',
                        hdnappointmentid: hdnappointmentid,scheduleid:schedule_id,schedule: schedule,date: date, start_time:starttime,end_time:endtime,time_shift:timeshift, first_name:first_name,last_name:last_name,email:email,phone:phone,details: details,appointmentby: appointmentby, price: price,payment_method: payment_method 
                      },
											success: function (data) {
													if(data.length>0){
														alert('added successfully');
                            jQuery('#frmschedules').submit();
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
			var atpos=email.indexOf('@');
			var dotpos=email.lastIndexOf('.');
			if (atpos < 1 || dotpos < atpos+2 || dotpos+2 >= email.length) {
					return false;
			}
			return true;
	}

	//-----------------------add appointment dialog-------------------------------===
  </script>
  <style type='text/css'>
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
	select, input[type='text']{
		height: 35px;;
	}	
	
  </style>";
  $current_user = wp_get_current_user();
	$output .="
 <div id='addappointment_dialog' title='".__('Add/Edit Appointment','appointgen_ustsappointment')."' class='wrapper' style='display:none;z-index:5000'>
  <div class='wrap' style='float:left; width:100%;'>
    <div class='main_div'>
     	<div class='metabox-holder' style='width:49%; float:left;'>
        <form id='frmappointment' action='' method='post' style='width:100%'>
          <table style='margin:10px;width:300px;'>
          	<tr>
            	<td class='appointmentlavel'> <label for='schedule'>".__('Schedule','appointgen_ustsappointment')." </label></td>
              <td class='appointmentinput' id='multi_rooms_select' style='padding:0 0 5px 5px;'>
              	<select id='schedule_select' >";
								
									 $sql_schedules = "select * from ".$table_prefix."appointgen_schedules";
										
									 $schedules = $wpdb->get_results($sql_schedules);	
									 foreach($schedules as $schedule){
                  		$output .="<option value='".$schedule->id."'>".sprintf(__('%s','appointgen_ustsappointment'),$schedule->schedule_name)."</option>";
                   }
                $output .='</select><span style="color:red;">*</span>
              </td>
            </tr>
            <tr>
            	<td class="appointmentlavel">
              	<label for="from date">'.__("Date:","appointgen_ustsappointment").'</label>
              </td>
              <td class="appointmentinput">
              	<input type="text" id="dtpdate" name="dtpdate" class="rounded" value="" style="width:230px;" /><span style="color:red;">*</span>
              </td>
            </tr>
						<tr>
            	<td class="appointmentlavel">
              	'.__("Start Time:","appointgen_ustsappointment").'
              </td>
              <td class="appointmentinput">
              	<input type="text" id="starttime" name="starttime" value="" style="width:230px;" /><span style="color:red;">*</span><br>
                <span style="font-style:italic;font-size:11px;">'.__("Must be in format of: 09:00","appointgen_ustsappointment").'</span>
              </td>
            </tr>
            <tr>
            	<td class="appointmentlavel">
              	'.__("End Time:","appointgen_ustsappointment").'
              </td>
              <td class="appointmentinput">
              	<input type="text" id="endtime" name="endtime" value="" style="width:230px;" /><span style="color:red;">*</span><br>
                <span style="font-style:italic;font-size:11px;">'.__("Must be in format of: 10:00","appointgen_ustsappointment").'</span>
              </td>
            </tr>
						<tr>
            	<td class="appointmentlavel">
              	'.__("Time Shift:","appointgen_ustsappointment").'
              </td>
              <td class="appointmentinput">
                <select id="timeshift" name="timeshift" style="height:21px;" >
                	<option value="am">'.__("AM","appointgen_ustsappointment").'</option>
                  <option value="pm">'.__("PM","appointgen_ustsappointment").'</option>
                </select>
              </td>
            </tr>
            <tr>
            	<td class="appointmentlavel">
              	<label for="first name">'.__("First Name:","appointgen_ustsappointment").'</label>
              </td>
              <td class="appointmentinput">
              	<input type="text" id="txtFirstName" name="txtFirstName" class="rounded" value="" />
              </td>
            </tr>
            <tr>
            	<td class="appointmentlavel">
              	<label for="last name">'.__("Last Name:","appointgen_ustsappointment").'</label>
              </td>
              <td class="appointmentinput">
              	<input type="text" id="txtLastName" name="txtLastName" class="rounded" value="" />
              </td>
            </tr>
            <tr>
            	<td class="appointmentlavel">
              	<label for="email">'.__("Email:","appointgen_ustsappointment").'</label>
              </td>
              <td class="appointmentinput">
              	<input type="text" id="txtEmail" name="txtEmail"  class="rounded" value="" /><!--<span style="color:red;">*</span>-->
              </td>
            </tr>
            <tr>
            	<td class="appointmentlavel">
              	<label for="phone">'.__("Phone:","appointgen_ustsappointment").'</label>
              </td>
              <td class="appointmentinput">
              	<input type="text" id="txtPhone" name="txtPhone" class="rounded" value="" /><span style="color:red;">*</span>
              </td>
            </tr>
            <tr>
            	<td class="appointmentlavel">
              	<label for="details">'.__("Details:","appointgen_ustsappointment").'</label>
              </td>
              <td class="appointmentinput">
              	<textarea cols="30" rows="10" id="details" class="rounded" name="details"></textarea>
              </td>
            </tr>
            <tr>
            	<td class="appointmentlavel">
              	<label for="appointment By">'.__("appointment By:","appointgen_ustsappointment").'</label>
              </td>
              <td class="appointmentinput">
              	<input type="text" readonly="readonly" id="txtappointmentby" name="txtappointmentby" class="rounded" value="'.sprintf(__('%s','appointgen_ustsappointment'),$current_user->display_name).'" />
              </td>
            </tr>
            <tr>
            	<td class="appointmentlavel">
              	<label for="price">'.__("Price:","appointgen_ustsappointment").'</label>
              </td>
              <td class="appointmentinput">
              	<input type="text" id="txtCustomPrice" name="txtCustomPrice" class="rounded" value="" />
              </td>
            </tr>
             <tr>
            	<td class="appointmentlavel">
              	<label for="payment method">'.__("Payment Method:","appointgen_ustsappointment").'</label>
              </td>
              <td class="appointmentinput">
              	<select id="optpaymentmethod" name="optpaymentmethod" >
                	';
									foreach($payment_methods as $pm){
                  	$output .= '<option value="'.$pm->payment_method.'">'.sprintf(__('%s','appointgen_ustsappointment'),$pm->payment_method).'</option>';
                  } 
                $output .='</select>
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
  ';