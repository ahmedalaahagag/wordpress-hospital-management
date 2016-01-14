/*
*ADMIN SCRIPT 
*/

var wpdevart_elements = {
    checkbox_enable : function(element){
		if (jQuery('#' + element.id).prop('checked')) {
			if (!jQuery('#' + element.id).closest('.wpdevart-item-container').next().next().hasClass("items_open")) {
			  for (i = 0; i < element.enable.length; i++) {
				 jQuery('#wpdevart_wrap_'+element.enable[i]).parent().parent().slideDown();
			  }
			} else  {
				 jQuery('#' + element.id).closest('.wpdevart-item-container').next().next().slideDown();
			}
		}
		else{
		  if (!jQuery('#' + element.id).closest('.wpdevart-item-container').next().next().hasClass("items_open")) {	
			  for (i = 0; i < element.enable.length; i++) { 
				 jQuery('#wpdevart_wrap_'+element.enable[i]).parent().parent().slideUp();
			  }
		  } else  {
				 jQuery('#' + element.id).closest('.wpdevart-item-container').next().next().slideUp();
			}
		}
    },
    radio_enable : function(element){
		var sel = jQuery('input[type=radio][name="' + element.id + '"]:checked').val();
		for (i = 0; i < element.enable.length; i++) {
		  for (j = 0; j < element.enable[i].val.length; j++) {
			jQuery('#wpdevart_wrap_'+element.enable[i].val[j]).parent().parent().slideUp();
		 }
		}
		for (i = 0; i < element.enable.length; i++) {
		  if(element.enable[i].key == sel){
		    for (j = 0; j < element.enable[i].val.length; j++) {
			  jQuery('#wpdevart_wrap_'+element.enable[i].val[j]).parent().parent().slideDown();
			}
		  }
		}

	}
};

function wpdevart_set_value(id,value) {
	jQuery("#"+id).val(value);
}

function wpdevart_form_submit(event, form_id) {
  if (jQuery("#"+form_id)) {
    jQuery("#"+form_id).submit();
  }
  if (event.preventDefault) {
    event.preventDefault();
  }
  else {
    event.returnValue = false;
  }
}


function check_all_checkboxes(el,el_class) {
  if (jQuery(el).context.checked == true) {
	jQuery( "."+el_class ).each(function(){
		jQuery(this).context.checked = true;
	});  
  }
  else {
	jQuery( "."+el_class ).each(function(){
		jQuery(this).context.checked = false;
	});
  }
}

function submit_form(id){
	jQuery("#"+id).trigger("click");
}

/*
*Calendar
*/

jQuery( document ).ready(function() {
    var $ = jQuery;
	var ajax_next = "";
	$(".wpda-booking-calendar-head .wpda-previous,.wpda-booking-calendar-head .wpda-next").live( "click", function(e){
		if(typeof(start_index) == "undefined") {
			start_index = "";
			selected_date = "";
		}
		e.preventDefault();
		var bc_main_div = $(this).closest('.booking_calendar_container');
		$(bc_main_div).find('.wpdevart-load-overlay').show();
        $.post(wpdevart.ajaxUrl, {
            action: 'wpdevart_ajax',
            wpdevart_selected: start_index,
            wpdevart_selected_date: selected_date,
            wpdevart_link: $(this).find('a').attr('href'),
			wpdevart_id: $(this).parent().next().data('id'),
            wpdevart_nonce: wpdevart.ajaxNonce
        }, function (data) {
            $(bc_main_div).find('div.booking_calendar_main').replaceWith(data);
            $(bc_main_div).find('.wpdevart-load-overlay').hide();
        });
		e.stopPropagation();
	});
	$(".wpda-booking-calendar-head .wpda-next").live( "click", function(e){
		 ajax_next = "next";
	});
	$(".wpda-booking-calendar-head .wpda-previous").live( "click", function(e){
		 ajax_next = "prev";
	});
	
	/*
	*CALENDAR
	*/
	
	var select_ex = false;
	var select_ex_single = false;
	var count_item = jQuery(".wpdevart-day").length;
	var start_index;
	$(".wpdevart-day").live("click",function(){
		var el = this;
		if($("#single_day").length == 0) {
			if(select_ex == true){
				$(".wpdevart-day").each(function(){
					$(this).removeClass("selected");
				});
				select_ex = false;
			}
			if($(".wpdevart-calendar-container .selected").length != 0 ){
				select_ex = true;
			} 
			else {
				ajax_next = "";
				$(el).addClass("selected");
				start_index =$(".wpdevart-day").index(el);
				selected_date = $(".wpdevart-day").eq(start_index).data("date");
			}
			if(select_ex == true){
				$(".wpdevart-item-section.form-section").fadeIn(100);
				if(ajax_next == "") {
					if(start_index>=$(".wpdevart-day").index(el)){
						$("#start_date").val($(el).data("date"));
						$("#end_date").val(selected_date);
					}
					else {
						$("#start_date").val(selected_date);
						$("#end_date").val($(el).data("date"));
					}
				} else if(ajax_next == "next"){
					$("#start_date").val(selected_date);
					$("#end_date").val($(el).data("date"));
				} else if(ajax_next == "prev"){
					$("#start_date").val($(el).data("date"));
					$("#end_date").val(selected_date);
				}
			}
		} else {
			select_ex_single = true;
			$(".wpdevart-item-section.form-section").fadeIn(100);
			$(".wpdevart-day").each(function(){
				$(this).removeClass("selected");
			});
			$(el).addClass("selected");
			$("#single_day").val($(el).data("date"));
		}
	});
	
	$(".wpdevart-day").live("hover",function(){
		if(($(".wpdevart-calendar-container .selected").length != 0 || typeof(start_index) != "undefined") && select_ex == false && select_ex_single == false && start_index != ""){
			end_index = $(".wpdevart-day").index(this);			
			if(ajax_next == "") { 
				if(start_index <= end_index) {
					for(var j = 0; j < start_index; j++) {
						$(".wpdevart-day").eq(j).removeClass("selected");
					}
					for(var n = end_index; n < count_item; n++) {
						$(".wpdevart-day").eq(n).removeClass("selected");
					}
					for (var i = start_index; i < end_index; i++) {
						$(".wpdevart-day").eq(i).addClass("selected");
					}
				}
				else if(start_index >= end_index){
					for(var k = start_index+1; k < count_item; k++) {
						$(".wpdevart-day").eq(k).removeClass("selected");
					}
					for(var p = 0; p < end_index; p++) {
						$(".wpdevart-day").eq(p).removeClass("selected");
					}
					for (var m = end_index; m < start_index; m++) {
						$(".wpdevart-day").eq(m).addClass("selected");
					}
				}
			} else if(ajax_next == "next") {
				for(var j = 0; j < start_index; j++) {
					$(".wpdevart-day").eq(j).removeClass("selected");
				}
				for(var n = end_index; n < count_item; n++) {
					$(".wpdevart-day").eq(n).removeClass("selected");
				}
				for (var i = 0; i < end_index; i++) {
					$(".wpdevart-day").eq(i).addClass("selected");
				}
			} else if(ajax_next == "prev") {
				for(var k = start_index+1; k < count_item; k++) {
					$(".wpdevart-day").eq(k).removeClass("selected");
				}
				for(var p = 0; p < end_index; p++) {
					$(".wpdevart-day").eq(p).removeClass("selected");
				}
				for (var m = end_index; m < count_item; m++) {
					$(".wpdevart-day").eq(m).addClass("selected");
				}
			}
			$(this).addClass("selected");
		}
	});
	
	
	/*
	*EXTRA
	*/
	var extra_count = 0;
	$("#add_extra_field").live( "click", function(e){
		e.preventDefault();
		$(this).addClass("wait");
        $.post(wpdevart.ajaxUrl, {
            action: 'wpdevart_add_extra_field',
            wpdevart_extra_field_max: $(this).data('max'),
            wpdevart_extra_field_count: extra_count,
            wpdevart_form_nonce: wpdevart.ajaxNonce
        }, function (data) {
            $('#new_extra_fields').append(data);
			$('#add_extra_field').removeClass("wait");
        });
		e.stopPropagation();
		extra_count += 1;
	});
	
	/*Extra field items*/
	var extra_field_count = 0;
	$(".add_extra_field_item").live( "click", function(e){
		e.preventDefault();
		$(this).addClass("wait");
		var this_add = $(this);
		console.log(extra_count);
		var field_item = $(this).parent().next().find(".wpdevart-extra-item-container");
        $.post(wpdevart.ajaxUrl, {
            action: 'wpdevart_add_extra_field_item',
            wpdevart_extra_field_item_max: $(this).data('max'),
            wpdevart_extra_field_item_count: extra_field_count,
            wpdevart_extra_field: $(this).data('field'),
            wpdevart_form_nonce: wpdevart.ajaxNonce
        }, function (data) {
            field_item.append(data);
			this_add.removeClass("wait");
        });
		e.stopPropagation();
		extra_field_count += 1;
	});
	$(".delete-extra-fild").live( "click",function(){
		$(this).closest(".wpdevart-extra-item").remove();
	});
	
	
	
	/*
	*FORM
	*/
	var count = 0;
	$("#form_field_type span").live( "click", function(e){
		e.preventDefault();		
		$(this).parent().prev().addClass("wait");
        $.post(wpdevart.ajaxUrl, {
            action: 'wpdevart_add_field',
            wpdevart_field_count: count,
            wpdevart_field_type: $(this).attr('id'),
            wpdevart_field_max: $(this).parent().data('max'),
            wpdevart_form_nonce: wpdevart.ajaxNonce
        }, function (data) {
            $('#new_fieds').append(data);
			$('#add_field').removeClass("wait");
        });
		e.stopPropagation();
		$(this).parent().slideUp();
		count += 1;
	});
	
	$("#wpdevart_forms .wpdevart-item-parent-container .wpdevart-fild-item-container,#wpdevart_extras .wpdevart-item-parent-container .wpdevart-fild-item-container").live( "click", function(){
		$(this).closest(".wpdevart-item-container").find(".form-fild-options").slideToggle();
		$(this).find(".open-form-fild-options").toggleClass("active");
	});
	
	$(".reserv-info-open").live( "click", function(){
		$(this).closest(".reserv-info").next().slideToggle();
		$(this).toggleClass("active");
	});
	
	$("#add_field").live( "click",function(){
		$("#form_field_type").slideToggle();
	});

	$(".delete-form-fild").live( "click",function(){
		$(this).closest(".wpdevart-item-container").remove();
	});
	
	$(".form_label").live( "keyup",function(){
		jQuery(this).closest(".wpdevart-item-parent-container").find(".section-title-txt").html(jQuery(this).val());
	});
	$(".form_req").live( "change",function(el){
		if(jQuery(this).is(":checked")){
			jQuery(this).closest(".wpdevart-item-parent-container").find(".wpdevart-required").html('*');
		}
		else {
			jQuery(this).closest(".wpdevart-item-parent-container").find(".wpdevart-required").html('');
		}
	});
	
	
	/*
	*Reservations
	*/
	/*form tab*/
	if(typeof(localStorage.currentTab) !== "undefined") {
		var current_item_tab = localStorage.currentTab;
		$("#resrv_action_filters .wpdevart_tab").removeClass("show");
		$("#resrv_action_filters .wpdevart_container").removeClass("show");
		$('#resrv_action_filters #' + current_item_tab).addClass("show");
		$('#resrv_action_filters #' + current_item_tab + '_container').show();
	}	   
	$("#resrv_action_filters .wpdevart_tab").click(function(){
		if(typeof(Storage) !== "undefined") {
			localStorage.currentTab = $(this).attr("id");
		}
		$("#resrv_action_filters .wpdevart_tab").removeClass("show");
		$("#resrv_action_filters .wpdevart_container").removeClass("show").hide();
		$("#resrv_action_filters #" + $(this).attr("id") + "_container").show();
		$(this).addClass("show");
	});
	/*Theme tab*/
	if(typeof(localStorage.currentThemeTab) !== "undefined") {
		var current_item_tab = localStorage.currentThemeTab;
		$("#wpdevart_themes .wpdevart_tab").removeClass("show");
		$("#wpdevart_themes .wpdevart_container").removeClass("show");
		$('#wpdevart_themes #' + current_item_tab).addClass("show");
		$('#wpdevart_themes #' + current_item_tab + '_container').addClass("show");
	}	   
	$("#wpdevart_themes .wpdevart_tab").click(function(){
		if(typeof(Storage) !== "undefined") {
			localStorage.currentThemeTab = $(this).attr("id");
		}
		$("#wpdevart_themes .wpdevart_tab").removeClass("show");
		$("#wpdevart_themes .wpdevart_container").removeClass("show").hide();
		$("#wpdevart_themes #" + $(this).attr("id") + "_container").show();
		$(this).addClass("show");
	});
	
	$(".check_for_action").click(function(){
	  if (jQuery(this).context.checked == true) {
		jQuery(this).parent().parent().addClass("checked");  
	  }
	  else {
		jQuery(this).parent().parent().removeClass("checked");  
	  }
	});

	$(function() {
		$( ".admin_datepicker" ).datepicker({
		  dateFormat: "yy-mm-dd"
		});
	});
	$('body').on("click", ".pro-field", function(){
		alert("If you want to use this feature upgrade to Booking calendar Pro");
		$(this).blur(); 
		return false;
	});
	$('.pro-field').closest(".wp-picker-container").click(function(){
		alert("If you want to use this feature upgrade to Booking calendar Pro");
		$(this).blur();
		return false;
	});
})


