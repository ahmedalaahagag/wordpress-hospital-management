<?php
	global $table_prefix,$wpdb;
	$schedule = 0;
	if($_POST){
    $schedule = $_REQUEST['id'];
	}
	$sql_paymentmethod = "select * from ".$table_prefix."appointgen_ustsappointments_paymentmethods";
	$payment_methods = $wpdb->get_results( $sql_paymentmethod );
	$sql_schedule = "select scd.id as scheduleid,srv.id as serviceid,tmsl.id as timeslotid,vn.id as venueid, scd.* ,srv.*,tmsl.*,vn.* from ".$table_prefix."appointgen_schedules scd inner join ".$table_prefix."appointgen_services srv on scd.service = srv.id 
inner join ".$table_prefix."appointgen_timeslot tmsl on tmsl.id = scd.timeslot
inner join ".$table_prefix."appointgen_venues vn on vn.id = scd.venue 
where scd.id=".$schedule;
	$tvs_result = $wpdb->get_results($sql_schedule);
	?>
  <style type="text/css">
		.multiselect {
			text-align: left;
		}
		.multiselect-container li.active .checkbox{
			background-color:#3A83C2;
		}
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
	<script>
	//--------------form validation------------------
  var venue_service_schedule = Array();
  <?php if(isset($tvs_result[0])){?>
      venue_service_schedule = <?php echo json_encode($tvs_result[0]);?>;
  <?php } ?>
	var dayscond = "";
	if(venue_service_schedule == "" || venue_service_schedule == null ){
		dayscond = "1";
	}
	else{
		var days = venue_service_schedule['days'];
		var daysarr = days.split(',');
		
		for(var i=0; i<daysarr.length; i++){
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
	function appointgen_get_rooms_for_appointmentcell(roomid){
		  var term_id = jQuery('#roomtype').val();
			jQuery.ajax({
					type: "POST",
          url: '<?php echo admin_url( 'admin-ajax.php' );?>',
					dataType:'json', 
					data: {
            action: 'appointgen_get_room_bycat',  
            term_id:term_id
          },
					success: function (data) {
							var count = data.length;
							jQuery('#optroom').empty();
							if(data.length > 0 ){
								for(var i=0;i<data.length;i++){
										if(i==0){
											jQuery('#optroom').append('<option value="'+data[i]['ID']+'" selected="selected">'+data[i]['post_title']+'</option>');
										}
										else{
											jQuery('#optroom').append('<option value="'+data[i]['ID']+'">'+data[i]['post_title']+'</option>');
										}
								}
								appointgen_get_roomprice();
							}
							else{
								jQuery('#optroom').empty();
							}
					},
					error : function(s , i , error){
							console.log(error);
					}
			}).done(function(msg){
					jQuery('#optroom').val(roomid);
			});
	}
	function appointgen_get_rooms_for_editappointment(roomid){
		  var term_id = jQuery('#roomtype').val();
			jQuery.ajax({
					type: "POST",
          url: '<?php echo admin_url( 'admin-ajax.php' );?>',
					dataType:'json', 
					data: {
            action: 'appointgen_get_room_bycat',
            term_id:term_id
          },
					success: function (data) {
							var count = data.length;
							jQuery('#optroom').empty();
							if(data.length > 0 ){
								for(var i=0;i<data.length;i++){
										if(i==0){
											jQuery('#optroom').append('<option value="'+data[i]['ID']+'" selected="selected">'+data[i]['post_title']+'</option>');
										}
										else{
											jQuery('#optroom').append('<option value="'+data[i]['ID']+'">'+data[i]['post_title']+'</option>');
										}
								}
								appointgen_get_roomprice();
							}
							else{
								jQuery('#optroom').empty();
							}
					},
					error : function(s , i , error){
							console.log(error);
					}
			}).done(function(msg){
					jQuery('#optroom').val(roomid);
			});
	}
	function appointgen_get_serviceprice(){
			var arr_schedules = new Array();
			var schedule = jQuery('#schedules_select').val();
			
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
              $sql = "select * from ".$table_prefix."appointgen_ustsappointments where appointment_id=".$id;
              $result = $wpdb->get_results($sql);
              ?>
              var appointment = <?php echo json_encode($result[0]);?>;
              jQuery('#hdnappointmentid').val(appointment['appointment_id']);
              jQuery("#schedules_select option[value="+appointment['schedule_id'] +"]").attr("selected","selected");

              jQuery('#dtpdate').val(appointment['date']);
              jQuery('#starttime').val(appointment['start_time']);
              jQuery('#endtime').val(appointment['end_time']);

              jQuery('#timeshift').val(appointment['timeshift']);
              jQuery('#txtFirstName').val(appointment['first_name']);
              jQuery('#txtLastName').val(appointment['last_name']);
              jQuery('#txtEmail').val(appointment['email']);

              jQuery('#txtPhone').val(appointment['phone']);
              jQuery('#details').val(appointment['details']);
              jQuery('#txtappointmentby').val(appointment['appointment_by']);
              jQuery('#txtCustomPrice').val(appointment['custom_price']);
              jQuery("#optpaymentmethod option:selected").text(appointment['payment_method']);
          <?php } ?>
				}	
			}
			
      //---------------------------------	
			jQuery('#schedules_select').on("change",function(){
				appointgen_get_serviceprice();
			});
			//----save appointment----
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
					jQuery("#schedules_select").multiselect("select",<?php echo $cell_month;?>);
					appointgen_get_roomprice();
					jQuery('#roomtype').val(<?php echo $cell_month_cat;?>);
					appointgen_get_rooms_for_appointmentcell(<?php echo $cell_month;?>);
					jQuery('#dtpfromdate').val('<?php echo $cell_date;?>');
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
			var hdnappointmentid = jQuery('#hdnappointmentid').val();
			var schedule = jQuery('#schedules_select :selected').text();
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
				alert('Please choose at Least a Schedule .');
				return;
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
				return;
			}
			jQuery.ajax({
					type: "POST",
          url: '<?php echo admin_url( 'admin-ajax.php' );?>',
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
											type: "POST",
                      url: '<?php echo admin_url( 'admin-ajax.php' );?>',
											data: {
                        action: 'appointgen_save_appointment',
                        hdnappointmentid: hdnappointmentid,scheduleid:1,schedule: schedule,date: date, start_time:starttime,end_time:endtime,time_shift:timeshift, first_name:first_name,last_name:last_name,email:email,phone:phone,details: details,appointmentby: appointmentby, price: price,payment_method: payment_method 
                      },
											success: function (data) {
                          if(data.length>0){
														alert('added successfully');
                            jQuery('#dtpdate').val('');
                            jQuery('#starttime').val('');
                            jQuery('#endtime').val('');
                            jQuery('#txtFirstName').val('');
                            jQuery('#txtLastName').val('');
                            jQuery('#txtEmail').val('');
                            jQuery('#txtPhone').val('');
                            jQuery('#details').val('');
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
	function appointgen_calculate_due(){
		var price = jQuery('#txtCustomPrice').val();
		var paid = jQuery('#txtPaid').val();
		var due = (price - paid);
		jQuery('#txtDue').val(due); 
	}
  </script>
  
  <style type="text/css">
		.appointmentlavel{
			width:16%;
		}
		.appointmentinput{
			width:75%;
		}
	</style>
  <?php $current_user = wp_get_current_user();
	?>	  
  <div class="wrapper">
  <div class="wrap" style="float:left; width:100%;">
    <div id="icon-options-general" class="icon32"><br />
    </div>
    <h2><?php _e("Appointment Scheduling","appointgen_ustsappointment"); ?></h2>
    <div class="main_div">
     	<div class="metabox-holder" style="width:69%; float:left;">
        <div id="namediv" class="stuffbox" style="width:99%;">
        <h3 class="top_bar"><?php _e("Add Appointment","appointgen_ustsappointment"); ?></h3>
        <form id="frmappointment" action="" method="post" novalidate="novalidate">
          <table style="margin:10px;width:100%;">
          	<tr>
            	<td class="appointmentlavel"><?php _e("Schedule","appointgen_ustsappointment"); ?></td>
              <td class="appointmentinput" id="multi_schedules_select">
                <select id="schedules_select"  >
                  <?php 
									 $sql_schedules = "select * from ".$table_prefix."appointgen_schedules";
									 
									 $schedules = $wpdb->get_results($sql_schedules);	
									 foreach($schedules as $schedule){
									?>
                  	<option value="<?php echo $schedule->id;?>"><?php printf(__("%s","appointgen_ustsappointment"),$schedule->schedule_name) ;?></option>
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
                <span style="font-style:italic;clear:both;font-size:11px;"><?php _e("Example: 09:00","appointgen_ustsappointment"); ?></span>
              </td>
            </tr>
            <tr>
            	<td class="appointmentlavel">
              	<?php _e("End Time:","appointgen_ustsappointment"); ?>
              </td>
              <td class="appointmentinput">
              	<input type="text" id="endtime" name="endtime" value="" style="width:230px;" /><span style="color:red;">*</span><br>
                <span style="font-style:italic;clear:both;font-size:11px;"><?php _e("Example: 10:30","appointgen_ustsappointment"); ?></span>
              </td>
            </tr>
            <tr>
            	<td class="appointmentlavel">
              	<?php _e("Time Shift:","appointgen_ustsappointment"); ?>
              </td>
              <td class="appointmentinput">
                <!--<input type="text" id="txtampm" name="txtampm" value="" />-->
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
              	<textarea cols="57" rows="15" id="details" name="details"></textarea>
              </td>
            </tr>
            <tr>
            	<td class="appointmentlavel">
              	<?php _e("Appointment By:","appointgen_ustsappointment"); ?>
              </td>
              <td class="appointmentinput">
              	<input type="text" readonly="readonly" id="txtappointmentby" name="txtappointmentby" value="<?php printf(__("%s","appointgen_ustsappointment"),$current_user->display_name); ?>" />
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
                  	<option value="<?php echo $pm->payment_method;?>"><?php printf(__("%s","appointgen_ustsappointment"),$pm->payment_method) ;?></option>
                  <?php }?>  
                </select>
              </td>
            </tr>
            <tr>
            	<td class="appointmentlavel" colspan="2" style="height:15px;">
              </td>
            </tr>
            <tr>
            	<td></td>
              <td>
              <input type="submit" id="btnaddappointment" name="btnaddappointment" value="<?php _e("Add Appointment","appointgen_ustsappointment"); ?>" style="width:150px;background-color: #0074A2;"/>
              <input type="hidden" id="hdnappointmentid" name="hdnappointmentid" value="" style="width:150px;"/>
              </td>
            </tr>
          </table>
          </form>
          
    		</div>
      </div>
    </div>
   </div>
  </div>