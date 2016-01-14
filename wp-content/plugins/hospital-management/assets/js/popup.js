jQuery(document).ready(function($) {		
	//Category Add and Remove
  $("body").on("click", "#addremove", function(event){
	 
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = $(document).height(); //grab the height of the page
	  var scrollTop = $(window).scrollTop();
	  var model  = $(this).attr('model') ;
		/*alert(model);
		return false;*/
	   var curr_data = {
	 					action: 'hmgt_add_remove_category',
	 					model : model,
	 					dataType: 'json'
	 					};	 				
	 					$.post(hmgt.ajax, curr_data, function(response) { 						
	 						$('.popup-bg').show().css({'height' : docHeight});
							$('.category_list').html(response);	
							return true; 					
	 					});	
	
  });
  
  $("body").on("click", ".close-btn-cat", function(){		
		
		$( ".category_list" ).empty();
		
		$('.popup-bg').hide(); // hide the overlay
		});  
  
  $("body").on("click", ".btn-delete-cat", function(){		
		var cat_id  = $(this).attr('id') ;	
		 var model  = $(this).attr('model') ;
		if(confirm("Are you sure want to delete this record?"))
		{
			var curr_data = {
					action: 'hmgt_remove_category',
					model : model,
					cat_id:cat_id,			
					dataType: 'json'
					};
					
					$.post(hmgt.ajax, curr_data, function(response) {						
						$('#cat-'+cat_id).hide();
						if(model=='specialization'){
							$("#specialization").find('option[value='+cat_id+']').remove();
						}
						else{
							$("#category_data").find('option[value='+cat_id+']').remove();
						}
						
						return true;				
					});			
		}
	});
  
  $("body").on("click", "#btn-add-cat", function(){		
		var medicine_cat_name  = $('#medicine_name').val() ;
		var model  = $(this).attr('model');	
			
		if(medicine_cat_name != "")
		{
			var curr_data = {
					action: 'hmgt_add_category',
					model : model,
					medicine_cat_name: medicine_cat_name,			
					dataType: 'json'
					};
					
					$.post(hmgt.ajax, curr_data, function(response) {						
						 var json_obj = $.parseJSON(response);//parse JSON						
						$('.table').append(json_obj[0]);
						$('#medicine_name').val("");
						if(model=="specialization"){
							$("#specialization").append(json_obj[1]);
						}
						else{
							$("#category_data").append(json_obj[1]);
						}
						
						
						return false;					
					});	
		
		}
		else
		{
			alert("Please enter Category Name.");
		}
	});
 
  //End category Add Remove 
  
//start load subject for managemarks
  $("#bed_type_id").change(function(){
		$('#bednumber').html('');	
	//alert(curr_data);
	 var bed_type_id = $("#bed_type_id").val();
	//alert(selection);
	var optionval = $(this);
		var curr_data = {
				action: 'hmgt_get_bednumber',
				bed_type_id: bed_type_id,			
				dataType: 'json'
				};
				//alert(curr_data);
				
				$.post(hmgt.ajax, curr_data, function(response) {
					//alert(response);
					//var option = $("<option>" + response+ "</option>");
				
				$('#bednumber').append(response);
				});
					
					//alert(response);
	
});
  //----------view patient status from list----------------------
  
  $("body").on("click", ".show-popup", function(event){
	
	
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = $(document).height(); //grab the height of the page
	  var scrollTop = $(window).scrollTop();
	  var idtest  = $(this).attr('idtest');
		//alert(idtest);
		//return false;
	   var curr_data = {
	 					action: 'hmgt_patient_status_view',
	 					idtest: idtest,
	 					dataType: 'json'
	 					};	 	
							//alert('hello');					
	 					$.post(hmgt.ajax, curr_data, function(response) { 	
	 						//alert(response);	 
	 					$('.popup-bg').show().css({'height' : docHeight});							
						$('.patient_data').html(response);	
						return true; 					
	 					});	
	
  });
  //----------show charges inpopup---------------
  $("body").on("click", ".show-charges-popup", function(event){
	
	
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = $(document).height(); //grab the height of the page
	  var scrollTop = $(window).scrollTop();
	  var idtest  = $(this).attr('idtest');
		//alert(idtest);
		//return false;
	   var curr_data = {
	 					action: 'hmgt_patient_charges_view',
	 					idtest: idtest,
	 					dataType: 'json'
	 					};	 	
							//alert('hello');					
	 					$.post(hmgt.ajax, curr_data, function(response) { 	
	 						//alert(response);	 
	 					$('.popup-bg').show().css({'height' : docHeight});							
						$('.patient_data').html(response);	
						return true; 					
	 					});	
	
  });
  
  //-----------Add nurse notes-------------------
  $("body").on("click", "#btn-add-note", function(){		
		var note_by=$(this).attr('note_by');
		var doctor_note  = $('#doctor_note_text').val();
		var nurse_note  = $('#nurse_note_text').val();
		var patient_id  = $('#patient_id').val();
		if(doctor_note != "" || nurse_note !="")
		{
			var curr_data = {
					action: 'hmgt_add_nurse_notes',
					note_by: note_by,
					doctor_note: doctor_note,
					nurse_note: nurse_note,
					patient_id:patient_id,
					dataType: 'json'
					};
					
					$.post(hmgt.ajax, curr_data, function(response) {	
						var json_obj = $.parseJSON(response);//parse JSON						
						$('.nurse_notes').append(json_obj[0]);
						$('#doctor_note_text').val("");
						$('#nurse_note_text').val("");
						
						return false;					
					});	
		
		}
		else
		{
			alert("Please enter Category Name.");
		}
	});
  
  $("body").on("click", "#btn-add-doctor-note", function(){		
		var note_by=$(this).attr('note_by');
		var doctor_note  = $('#doctor_note_text').val();
		
		var patient_id  = $('#patient_id').val();
		if(doctor_note != "")
		{
			var curr_data = {
					action: 'hmgt_add_doctor_notes',
					note_by: note_by,
					doctor_note: doctor_note,
					
					patient_id:patient_id,
					dataType: 'json'
					};
					
					$.post(hmgt.ajax, curr_data, function(response) {	
						var json_obj = $.parseJSON(response);//parse JSON						
						$('.doctor_notes').append(json_obj[0]);
						$('#doctor_note_text').val("");
						
						
						return false;					
					});	
		
		}
		else
		{
			alert("Please enter note.");
		}
	});
	//----------view Invoice popup--------------------
	 $("body").on("click", ".show-invoice-popup", function(event){
	

	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = $(document).height(); //grab the height of the page
	  var scrollTop = $(window).scrollTop();
	  var idtest  = $(this).attr('idtest');
	  var invoice_type  = $(this).attr('invoice_type');
	  
		//alert(idtest);
		//return false;
	   var curr_data = {
	 					action: 'hmgt_patient_invoice_view',
	 					idtest: idtest,
	 					invoice_type: invoice_type,
	 					dataType: 'json'
	 					};	 	
							//alert('hello');					
	 					$.post(hmgt.ajax, curr_data, function(response) { 	
	 						//alert(response);	 
	 					$('.popup-bg').show().css({'height' : docHeight});							
						$('.invoice_data').html(response);	
						return true; 					
	 					});	
	
  });
	//----------remove nurse note---------------
	$("body").on("click", ".btn-delete-note", function(){		
		var note_id  = $(this).attr('noteid') ;	
		if(confirm("Are you sure want to delete this record?"))
		{
			var curr_data = {
					action: 'hmgt_remove_nurse_note',
					note_id:note_id,			
					dataType: 'json'
					};
					
					
					$.post(hmgt.ajax, curr_data, function(response) {						
						
						$('#note-'+note_id).hide();
						$('#notex-'+note_id).hide();
						
						
						
						return true;				
					});			
		}
	});
	
	
	  $("body").on("click", ".view-profile", function(event){
		  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
		  var docHeight = $(document).height(); //grab the height of the page
		  var scrollTop = $(window).scrollTop();
		 var user_id  = $(this).attr('idtest') ;
		 //alert(user_id);
		// return false;
		   var curr_data = {
		 					action: 'hmgt_user_profile',
		 					user_id : user_id,
		 					dataType: 'json'
		 					};	 				
		 					$.post(hmgt.ajax, curr_data, function(response) {
		 						$('.popup-bg').show().css({'height' : docHeight});
								$('.profile_data').html(response);	
								return true; 					
		 					});	
		
	  });
	  
	  // view report
	  $("body").on("click", ".view-report", function(event){
		   //alert("hello");
	  var evnet_id = $(this).attr('id');
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = $(document).height(); //grab the height of the page
	  var scrollTop = $(window).scrollTop();
	   //alert(evnet_id);
	   var curr_data = {
	 					action: 'hmgt_view_report',
	 					evnet_id: evnet_id,			
	 					dataType: 'json'
	 					};
	 					//alert('hello');
	 					$.post(hmgt.ajax, curr_data, function(response) {
	 						
	 						//alert(response);
	 						//return false;
	 						$('.popup-bg').show().css({'height' : docHeight});
							$('.notice_content').html(response);	
	 						return true;
	 						
	 					
	 					
	 					});	
	 		});
	  //---------view events----------
	   $("body").on("click", ".view-notice", function(event){
		   //alert("hello");
	  var evnet_id = $(this).attr('id');
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = $(document).height(); //grab the height of the page
	  var scrollTop = $(window).scrollTop();
	   //alert(evnet_id);
	   var curr_data = {
	 					action: 'hmgt_view_event',
	 					evnet_id: evnet_id,			
	 					dataType: 'json'
	 					};
	 					//alert('hello');
	 					$.post(hmgt.ajax, curr_data, function(response) {
	 						
	 						//alert('hello');
	 						$('.popup-bg').show().css({'height' : docHeight});
							$('.notice_content').html(response);	
	 						return true;
	 						
	 					
	 					
	 					});	
	 		});
	   
	   $("body").on("click", ".view-prescription", function(event){
		   //alert("hello");
	  var prescription_id = $(this).attr('id');
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = $(document).height(); //grab the height of the page
	  var scrollTop = $(window).scrollTop();
	   //alert(evnet_id);
	   var curr_data = {
	 					action: 'hmgt_view_priscription',
	 					prescription_id: prescription_id,			
	 					dataType: 'json'
	 					};
	 					//alert('hello');
	 					$.post(hmgt.ajax, curr_data, function(response) {
	 						
	 						//alert('hello');
	 						$('.popup-bg').show().css({'height' : docHeight});
							$('.prescription_content').html(response);	
	 						return true;
	 						
	 					
	 					
	 					});	
	 		});
			
			
			//---------------convert patient into inpatient-------
		$("#patient_id").change(function(){
			$('.convert_patient').html('');	
		
		
		
		var optionval = $(this);
			var curr_data = {
					action: 'hmgt_load_convert_patient',
					patient_id: $("#patient_id").val(),			
					dataType: 'json'
					};
					
					$.post(hmgt.ajax, curr_data, function(response) {
					
					$('.convert_patient').append(response);
					});
						
						
		
	});

		//SMS Message
		 $("input[name=select_serveice]:radio").change(function(){
			
			 var curr_data = {
						action: 'hmgt_sms_service_setting',
						select_serveice: $(this).val(),			
						dataType: 'json'
						};					
						
						$.post(hmgt.ajax, curr_data, function(response) {	
							
							
						$('#sms_setting_block').html(response);
						});
		 });
		 $("#chk_sms_sent").change(function(){
				
			 if($(this).is(":checked"))
			{
				 //alert("chekked");
				 $('#hmsg_message_sent').addClass('hmsg_message_block');
				 
			}
			 else
			{
				 $('#hmsg_message_sent').addClass('hmsg_message_none');
				 $('#hmsg_message_sent').removeClass('hmsg_message_block');
			}
		 });
  
});
  
  

