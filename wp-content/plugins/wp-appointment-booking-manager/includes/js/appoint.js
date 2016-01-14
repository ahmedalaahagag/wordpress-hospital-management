	jQuery(document).ready(function(){
			//----save appointment----
			jQuery('#frmaddtimeslot').on('submit',function(e){
	  		 e.preventDefault();
				 appointgen_save_timeslot();
			});
			jQuery('#frmaddservice').on('submit',function(e){
	  		 e.preventDefault();
				 appointgen_save_service();
			})
			jQuery('#frmaddvenue').on('submit',function(e){
	  		 e.preventDefault();
				 appointgen_save_venue();
			});
			jQuery('#frmaddschedule').on('submit',function(e){
	  		 e.preventDefault();
				 appointgen_save_schedule();
			});
			jQuery('#optschedules').on("change",function(){
				appointgen_set_schedule_session();	
			});
			
	});
  function appointgen_validateTime(strTime) {
    var regex = new RegExp("([0-1][0-9]|2[0-3]):([0-5][0-9])");
    if (regex.test(strTime)) {
      return true;
    } else {
      return false;
    }
  }
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
	function appointgen_save_timeslot(){
		var hdntimeslot_id = jQuery('#hdntimeslotid').val();
		var timeslot_name = jQuery('#timeslot_name').val();
		var starttime = jQuery('#starttime').val();
		var endtime = jQuery('#endtime').val();
		var timeinterval = jQuery('#timeinterval').val();
		if(timeslot_name == ""){
			alert('Please input a TimeSlot Name');
			return;
		}
    else if(starttime == ""){
			alert('Please input a Start Time');
			return;
		}
    else if(!appointgen_validateTime(starttime)){
      alert('Please Correct Start Time Format');
      return;
    }
		else if(endtime == ""){
			alert('Please input End Time');
			return;
		}
    else if(!appointgen_validateTime(endtime)){
      alert('Please Correct End Time Format');
      return;
    }
		else if(timeinterval ==""){
			alert('Please input Time Interval in Minute');
			return;
		}
		jQuery.ajax({
				type: "POST",
						url: scAppointAjax.ajaxurl,
						data: {
							action: 'sc_add_timeslot_ajax_request',	
							hdntimeslotid: hdntimeslot_id, 
							timeslot_name: timeslot_name, 
							start_time: starttime, 
							end_time:endtime, 
							time_interval: timeinterval
						},
					success: function (data) {
						if(data.length>0){
							alert('added successfully');
              jQuery('#timeslot_name').val('');
              jQuery('#starttime').val('');
              jQuery('#endtime').val('');
              jQuery('#timeinterval').val('');
						}
				},
				error : function(s , i , error){
						console.log(error);
				}
		});
		
	}
	function appointgen_save_service(){
		var hdnservice_id = jQuery('#hdnserviceid').val();
		var provider_name = jQuery('#provider_name').val();
		var service_name = jQuery('#service_name').val();
		var service_details = jQuery('#service_details').val();
		var price = jQuery('#price').val();
		var days = "";
		var count=0;
		jQuery('select[name=days] option:selected').each(function(){
			if(count==0){
				days = jQuery(this).val();
			}
			else{
				days = days +','+jQuery(this).val();	
			}
			count++;
		});
    if(provider_name == ""){
			alert('Please input a Provider Name');
			return;
		}
		if(service_name == ""){
			alert('Please input a Service Name');
			return;
		}
		else if(price == ""){
			alert('Please input Price');
			return;
		}
		else if(days ==""){
			alert('Please input Service Days.');
			return;	
		}
		jQuery.ajax({
				type: "POST",
						url: scAppointAjax.ajaxurl,
						data: {
							action: 'sc_add_service_ajax_request',	
							hdnserviceid: hdnservice_id, 
							provider_name: provider_name, 
							service_name: service_name, 
							service_details:service_details, 
							price: price,
							days: days
						},
					success: function (data) {
						if(data.length>0){
							alert('added successfully');
              jQuery('#provider_name').val('');
              jQuery('#service_name').val('');
              jQuery('#service_details').val('');
              jQuery('#price').val('');
						}
				},
				error : function(s , i , error){
						console.log(error);
				}
		});

	}
	function appointgen_save_venue(){
		var hdnvenue_id = jQuery('#hdnvenueid').val();
		var venue_name = jQuery('#venue_name').val();
		var venue_address = jQuery('#venue_address').val();
		var description = jQuery('#description').val();
		if(venue_name == ""){
			alert('Please input a Venue Name');
			return;
		}
		else if(venue_address == ""){
			alert('Please input an Address');
			return;
		}
		jQuery.ajax({
				type: "POST",
						url: scAppointAjax.ajaxurl,
						data: {
							action: 'sc_add_venue_ajax_request',	
							hdnvenueid: hdnvenue_id, 
							venue_name: venue_name, 
							venue_address: venue_address, 
							description: description 
						},
					success: function (data) {
						if(data.length>0){
							alert('added successfully');
              jQuery('#venue_name').val('');
              jQuery('#venue_address').val('');
              jQuery('#description').val('');
						}
				},
				error : function(s , i , error){
						console.log(error);
				}
		});
		
	}
	function appointgen_save_schedule(){
		var hdnschedule_id = jQuery('#hdnscheduleid').val();
		var schedule_name = jQuery('#schedule_name').val();
		var opttimeslot = jQuery('#timeslot').val();
		var optservice = jQuery('#optservice').val();
		var optvenue = jQuery('#optvenue').val();
    if(schedule_name==""){
      alert('Please input Schedule Name.');
      return;
    }  
    jQuery.ajax({
				type: "POST",
					url: scAppointAjax.ajaxurl,
					data: {
						action: 'appointgen_add_schedule_ajax_request',	
						hdnscheduleid: hdnschedule_id, 
						schedule_name: schedule_name, 
						optservice: optservice, 
						optvenue: optvenue,
						opttimeslot: opttimeslot
					},
					success: function (data) {
            //alert(data);
						if(data.length>0){
							alert('added successfully');
              jQuery('#schedule_name').val('');
						}
				},
				error : function(s , i , error){
						console.log(error);
				}
		});
	}
	
	function appointgen_set_schedule_session(){
			var scheduleid = jQuery("select[name=optschedules] option:selected").val();
			jQuery.ajax({
					type: "POST",
					url: scAppointAjax.ajaxurl,
					data: {
						action: 'sc_set_ajax_schedule_session',
						scheduleid : scheduleid
						},
					success: function (data) {
						console.log(data);
					},
					error : function(s , i , error){
						console.log(error);
					},
					complete: function(data){
						jQuery('#frmschedules').submit();
					}
			});
	}