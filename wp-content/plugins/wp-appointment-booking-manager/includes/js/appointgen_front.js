	jQuery(document).ready(function(){
			//----save appointment----
			//alert('on load....');
			jQuery('#optschedules').on("change",function(){
				//alert('called....');
				appointgen_set_schedule_session_front();	
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
 function appointgen_set_schedule_session_front(){
			var scheduleid = jQuery("select[name=optschedules] option:selected").val();
			//alert(' set cookie scheduleid: '+scheduleid);
			jQuery.ajax({
					type: "POST",
					url: scAppointAjax_front.ajaxurl,
					data: {
						action: 'sc_set_ajax_schedule_session_front',
						scheduleid : scheduleid
						},
					success: function (data) {
						//alert('set data: '+data)
						console.log(data);
					},
					error : function(s , i , error){
						//alert('set cookie error: '+error);
						console.log(error);
					},
					complete: function(data){
						jQuery('#frmschedules').submit();
					}
			});
	}