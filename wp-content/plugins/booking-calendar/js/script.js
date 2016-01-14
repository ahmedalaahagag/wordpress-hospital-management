
  
var wpdevartScript;
var wpdevartScriptOb;

jQuery( document ).ready(function() {
	wpdevartScript = function () {	
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
		
		
		$(".wpdevart-submit").live( "click", function(e){
			var wpdevart_required_field = wpdevart_required($(this));
			e.preventDefault();
			if(wpdevart_required_field === true) {
				var reserv_data = {};
				$(this).closest("form").find("input[type=text],button,input[type=hidden],input[type=checkbox],input[type=radio],select,textarea").each(function(index,element){
					reserv_data[jQuery(element).attr("name")] = $(element).val();
				});
				reserv_json = JSON.stringify(reserv_data);
				$(this).addClass("load");
				var reserv_form = $(this).closest("form");
				var reserv_cont = $(this).closest(".wpdevart-booking-form-container").prev();
				$.post(wpdevart.ajaxUrl, {
					action: 'wpdevart_form_ajax',
					wpdevart_data: reserv_json,
					wpdevart_id: $(this).closest(".wpdevart-booking-form-container").prev().find(".wpdevart-calendar-container").data('id'),
					wpdevart_nonce: wpdevart.ajaxNonce
				}, function (data) {
					$(reserv_cont).find('div.booking_calendar_main').replaceWith(data);
					$(reserv_cont).find('div.selected').removeClass("selected");
					$(reserv_form).find("input[type=text],input[type=hidden],textarea").each(function(index,element){
						jQuery(element).val("");
					});
					$(reserv_form).find("select").each(function(index,element){
						jQuery(element).find("option:selected").removeAttr("selected");
					});
					$(reserv_form).find("input[type=checkbox],input[type=radio]").each(function(index,element){
						jQuery(element).find(":checked").removeAttr("checked");
					});
					$(reserv_form).find(".wpdevart-submit").removeClass("load").hide();
					$(window).scrollTo( reserv_cont, 400,{'offset':{'top':-80}});
				});
				e.stopPropagation();
			}
		});
		
		/*
		*CALENDAR
		*/
		var select_ex = false,
			select_ex_single = false,
			count_item = jQuery(".wpdevart-day").length,
			start_index,check_in,check_out,
			item_count = "",
			extra_price_value = 0;
		$(".wpdevart-day").live("click",function() {
			var price = 0,
				price_div = "",
				total_div = "",
				extra_div = "",
				currency = "",
				selected_count = 0,
				el = this,
				id = $(this).parent().data("id");
			if(!$(el).hasClass("wpdevart-available") && $(".wpdevart-calendar-container .selected").length == 0){
				return false;
			}	
			if($("#wpdevart_form_checkin" + id).length != 0) {
				selected_count = $(".wpdevart-calendar-container .wpdevart-available.selected").length;
				if(select_ex == true) {
					$(".wpdevart-day").each(function() {
						$(this).removeClass("selected");
					});
					$("#wpdevart_form_checkin" + id).val($(el).data("date"));
					$("#wpdevart_form_checkout" + id).val($(el).data("date"));
					select_ex = false;
				}
				if(selected_count != 0) {
					select_ex = true;
				} 
				else {
					ajax_next = "";
					$(el).addClass("selected");
					start_index = $(".wpdevart-day").index(el);
					selected_date = $(".wpdevart-day").eq(start_index).data("date");
				}
				if(select_ex == true){
					var exist = false;
					$(".wpdevart-calendar-container .selected").each(function(ind, element) {
						if(typeof($(element).data("available")) == "undefined") {
							exist = true;
						}
						
					});
					if(exist == true) {
						$(el).closest(".booking_calendar_container").find(".error_text_container").fadeIn();
						$(el).closest(".booking_calendar_container").find(".successfully_text_container").fadeOut();
						$(window).scrollTo( $(el).closest(".booking_calendar_container").find(".error_text_container"), 400,{'offset':{'top':-50}});
						$(".wpdevart-day").each(function(){
							$(this).removeClass("selected");
						});
						exist = false;
					}
					else {
						$(el).closest(".booking_calendar_container").find(".error_text_container,.successfully_text_container").fadeOut(10);
						var av_min = $(".wpdevart-calendar-container .selected").eq(0).data("available");
						for(var i = 1; i < selected_count; i++) {
							if($(".wpdevart-calendar-container .selected").eq(i).data("available") < av_min) {
								av_min = $(".wpdevart-calendar-container .selected").eq(i).data("available");
							}
						}
						$("#wpdevart_count_item"+id+" option").remove();
						for(var j = 1; j <= av_min; j++){
							$("#wpdevart_count_item"+id).append("<option value='"+j+"'>"+j+"</option>");
						}
						if(ajax_next == "") {
							if(start_index >= $(".wpdevart-day").index(el)){
								check_in = $(el).data("date");
								check_out = selected_date
							}
							else {
								check_in = selected_date;
								check_out = $(el).data("date");
							}
						} else if(ajax_next == "next"){
							check_in = selected_date;
							check_out = $(el).data("date");
						} else if(ajax_next == "prev"){
							check_in = $(el).data("date");
							check_out = selected_date;
						}
						$("#wpdevart_form_checkin" + id).val(check_in);
						$("#wpdevart_form_checkout" + id).val(check_out);
						$(el).closest(".booking_calendar_container").next().find(".wpdevart-submit").show();
						reservation_info(el,price,price_div,total_div,extra_div,currency,id,extra_price_value,check_in,check_out,item_count,false,selected_count);
						$(window).scrollTo( "#wpdevart_booking_form_" + id, 400,{'offset':{'top':-50}});
					}
				}
			} else if($("#wpdevart_single_day" + id).length != 0) {
				select_ex_single = true;
				$(".wpdevart-day").each(function(){
					$(this).removeClass("selected");
				});
				if(typeof($(el).data("available")) != "undefined") {
					$(el).addClass("selected");
					$("#wpdevart_single_day" + id).val($(el).data("date"));
					$(el).closest(".booking_calendar_container").find(".error_text_container,.successfully_text_container").fadeOut(10);
					$("#wpdevart_count_item"+id+" option").remove();
					for(var j = 1; j <= ($(el).data("available")); j++){
						$("#wpdevart_count_item"+id).append("<option value='"+j+"'>"+j+"</option>");
					}
					$(el).closest(".booking_calendar_container").next().find(".wpdevart-submit").show();
					reservation_info(el,price,price_div,total_div,extra_div,currency,id,extra_price_value,check_in,check_out,item_count,$(el).data("date"),1);
					$(window).scrollTo( "#wpdevart_booking_form_" + id, 400,{'offset':{'top':-50}});
				} else {
					$(el).closest(".booking_calendar_container").find(".error_text_container").fadeIn();
					$(window).scrollTo( $(el).closest(".booking_calendar_container").find(".error_text_container"), 400,{'offset':{'top':-50}});
					$("#wpdevart_single_day" + id).val("");
				}
			}
		});
		
		$(".wpdevart-day").live("hover",function(){
			if(($(".wpdevart-calendar-container .selected").length != 0 || typeof(start_index) != "undefined") && select_ex == false && select_ex_single == false && start_index != "") {
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
		
		$(".notice_text_close").live("click",function(){
			$(this).parent().fadeOut(10);
		});
		$(function() {
			$( ".datepicker" ).datepicker({
			  dateFormat: "yy-mm-dd"
			});
		});

		wpdevart_responsive();
	}
	wpdevartScriptOb = new wpdevartScript();	
});

function wpdevart_set_value(id,value) {
	jQuery("#"+id).val(value);
}

function change_count(el) {
	var price = 0,
		old_price = 0,
		total_price = 0,
		extra_price_value = 0,
		extraprice = 0,
		old_total = 0;
	if(jQuery(el).closest(".wpdevart-booking-form").find(".price").length != 0) {
		old_price = parseFloat(jQuery(el).closest(".wpdevart-booking-form").find(".price").data("price"));
		price = parseFloat(jQuery(el).closest(".wpdevart-booking-form").find(".price span").html());
		total_price = old_price*(jQuery(el).val());
		old_total = parseFloat(jQuery(el).closest(".wpdevart-booking-form").find(".total_price span").html());
		if(jQuery(el).closest(".wpdevart-booking-form").find(".wpdevart-extra-info").length != 0) {
			jQuery(el).closest(".wpdevart-booking-form").find(".wpdevart-extra-info").each(function(){
				if(jQuery(this).find("span:first-child").html() != "") {
					if(jQuery(this).find(".extra_price_value").html() != "") {
						operation = jQuery(this).find(".extra_price").data("extraop");
						extraprice = jQuery(this).find(".extra_price").data("extraprice");
						if( jQuery(this).find(".extra_percent").length != 0 && jQuery(this).find(".extra_percent").is(":visible")) {
							jQuery(this).find(".extra_price_value").html(operation+(extraprice*(old_price*(jQuery(el).val()))/100));
							extra_price_value += operation + (extraprice*(old_price*(jQuery(el).val()))/100);
							total_price = (operation == "+")? (total_price + (extraprice*(old_price*(jQuery(el).val()))/100)) : (total_price - (extraprice*(old_price*(jQuery(el).val()))/100));
						} else {
							total_price = (operation == "+")? (total_price + extraprice) : (total_price - extraprice);
							extra_price_value += operation + (extraprice);
						}
					}
				}
			});
		} else {
			total_price = (old_total-price)+(old_price*(jQuery(el).val()));
		}
		jQuery(el).closest(".wpdevart-booking-form").find(".total_price span").html(total_price);
		jQuery(el).closest(".wpdevart-booking-form").find(".price span").html(old_price*(jQuery(el).val()));
		jQuery(el).closest(".wpdevart-booking-form").find(".wpdevart_extra_price_value").val(eval(extra_price_value));
		jQuery(el).closest(".wpdevart-booking-form").find(".wpdevart_total_price_value").val(total_price);
		jQuery(el).closest(".wpdevart-booking-form").find(".wpdevart_price_value").val(old_price*(jQuery(el).val()));
	}
	if(jQuery(el).closest(".wpdevart-booking-form").find(".count_item").length != 0) {
		jQuery(el).closest(".wpdevart-booking-form").find(".count_item").html(jQuery(el).val());
	}
}
function change_extra(el) {
	var id = jQuery(el).attr("id"),
	    thisprice =  jQuery(el).find("option:selected").data("price"),
	    thisop =  jQuery(el).find("option:selected").data("operation"),
	    label =  jQuery(el).find("option:selected").data("label"),
	    thistype =  jQuery(el).find("option:selected").data("type"),
	    extraprice =  ((jQuery("."+id+" .extra_price_value").html())? parseFloat(jQuery("."+id+" .extra_price_value").html()) : 0),
	    extraop =  jQuery("."+id+" .extra_price").data("extraop"),
	    total_price =  0,
	    extra_price_value =  0,
		total = parseFloat(jQuery(el).closest(".wpdevart-booking-form").find(".total_price span").html()),
		price = parseFloat(jQuery(el).closest(".wpdevart-booking-form").find(".price span").html()),
	 	new_total = (extraop == "+") ? (total - Math.abs(extraprice)) : (total + Math.abs(extraprice));
	 	selected_count = jQuery(el).closest(".wpdevart-booking-form-container").prev().find(".wpdevart-available.selected").length;
	if(jQuery(el).closest(".wpdevart-booking-form").find("."+id).length != 0) {
		jQuery(el).closest(".wpdevart-booking-form").find("."+id+" .extra_price").data("extraprice", thisprice);
		jQuery(el).closest(".wpdevart-booking-form").find("."+id+" .extra_price").data("extraop", thisop);
		jQuery(el).closest(".wpdevart-booking-form").find("."+id+" .option_label").html(label);
		jQuery(el).closest(".wpdevart-booking-form").find("."+id+" .reserv_info_cell").html(jQuery(el).closest(".wpdevart-fild-item-container").find("label").html());
		if(thisprice) {
			if(thistype == "price") {
				jQuery(el).closest(".wpdevart-booking-form").find("."+id+" .extra_price_value").html(thisop+(thisprice*selected_count));
				jQuery(el).closest(".wpdevart-booking-form").find("."+id+" .extra_percent").hide();
				jQuery(el).closest(".wpdevart-booking-form").find("."+id+" .extra_price").show();
				total_price = (thisop == "+")? (new_total + (thisprice*selected_count)) : (new_total - (thisprice*selected_count));				
			} else {
				jQuery(el).closest(".wpdevart-booking-form").find("."+id+" .extra_price_value").html(thisop+(price*thisprice)/100);
				jQuery(el).closest(".wpdevart-booking-form").find("."+id+" .extra_percent").html(thisprice+"%").show();
				jQuery(el).closest(".wpdevart-booking-form").find("."+id+" .extra_price").show();
				total_price = (thisop == "+")? (new_total + ((price * thisprice)/100)) : (new_total - ((price * thisprice)/100));
			}
		} else {
			jQuery(el).closest(".wpdevart-booking-form").find("."+id+" .extra_price_value").html("");
			jQuery(el).closest(".wpdevart-booking-form").find("."+id+" .extra_percent,."+id+" .extra_price").hide();
			total_price = new_total;
		}
		jQuery(el).closest(".wpdevart-booking-form").find(".extra_price_value").each(function(){
			extra_price_value += jQuery(this).html();
		});
		jQuery(el).closest(".wpdevart-booking-form").find(".total_price span").html(total_price);
		jQuery(el).closest(".wpdevart-booking-form").find(".wpdevart_total_price_value").val(total_price);	
		jQuery(el).closest(".wpdevart-booking-form").find(".wpdevart_extra_price_value").val(eval(extra_price_value));
	}
	
}

function reservation_info(el,price,price_div,total_div,extra_div,currency,id,extra_price_value,check_in,check_out,item_count,single_date,selected_count) {
	/*Reservation info*/
	jQuery(el).parent().find(".selected").each(function(index,sel_element) {
		if(jQuery(sel_element).find(".new-price").length != 0) {
			price += jQuery(sel_element).find(".new-price").data("price");
			currency = jQuery(sel_element).find(".new-price").data("currency")
		}
	});
	var total_price = price;
	
	if(jQuery("#wpdevart_count_item"+id).length != 0) {
		item_count = "<div class='reserv_info_row'><span class='reserv_info_cell'>" +  (jQuery("#wpdevart_count_item"+id).closest(".wpdevart-fild-item-container").find("label").html()) + "</span><span class='reserv_info_cell_value count_item'>1</span></div>";
	}
	if(jQuery(el).closest(".booking_calendar_container").next().find(".wpdevart_extras").length != 0) {
		jQuery(el).closest(".booking_calendar_container").next().find(".wpdevart_extras").each(function(sel_index,select){
			var label = jQuery(select).parent().parent().find("label").html(),
				option_label_arr = jQuery(select).find("option:selected").html().split(' '),
				option_label = option_label_arr[0],
				operation = jQuery(select).find("option:selected").data("operation"),
				type = jQuery(select).find("option:selected").data("type"),
				opt_price = parseFloat(jQuery(select).find("option:selected").data("price"));
			if(type == "price") {
				if(opt_price != 0 || opt_price != "") {
					var option_info = "<span class='extra_percent' style='display:none;'></span><span class='extra_price' data-extraprice='"+(opt_price*selected_count)+"' data-extraop='"+operation+"' style='display:inline-block;'><span class='extra_price_value'>"+operation+(opt_price*selected_count)+"</span>"+currency+"</span>";
				} else {
					var option_info = "<span class='extra_percent' style='display:none;'></span><span class='extra_price' data-extraprice='"+opt_price+"' data-extraop='"+operation+"'  style='display:none;'><span class='extra_price_value'></span>"+currency+"</span>";
				}
				total_price = (operation == "+")? (total_price + (opt_price*selected_count)) : (total_price - (opt_price*selected_count));
				extra_price_value += operation+(opt_price*selected_count);
			} else {
				if(opt_price != 0 || opt_price != "") {
					var option_info = "<span class='extra_percent'>"+operation+opt_price+"%</span><span class='extra_price' data-extraprice='"+opt_price+"' data-extraop='"+operation+"'  style='display:inline-block;'><span class='extra_price_value'>"+operation+((price * opt_price)/100)+"</span>"+currency+"</span>";
				} else {
					var option_info = "<span class='extra_percent'></span><span class='extra_price' data-extraprice='"+opt_price+"' data-extraop='"+operation+"'  style='display:none;'><span class='extra_price_value'></span>"+currency+"</span>";
				}
				total_price = (operation == "+")? (total_price + ((price * opt_price)/100)) : (total_price - ((price * opt_price)/100));
				extra_price_value += operation+((price * opt_price)/100);
			}
			extra_div += "<div class='wpdevart-extra-info wpdevart-extra-"+sel_index+" reserv_info_row "+(jQuery(select).attr("id"))+"'><span class='reserv_info_cell'>"+label+"</span><span class='reserv_info_cell_value'><span class='option_label'>"+option_label+"</span>"+option_info+"</span></div>";
			
		});
	}
	if(price != 0) {
		price_div = "<div class='reserv_info_row'><span class='reserv_info_cell'>"+(jQuery("#booking_calendar_container_" + id).data("price"))+"</span><span class='reserv_info_cell_value price' data-price='"+price+"'><span>"+price+"</span>"+currency+"</span></div>";
		total_div = "<div class='wpdevart-total-price reserv_info_row'><span class='reserv_info_cell'>"+(jQuery("#booking_calendar_container_" + id).data("total"))+"</span><span class='reserv_info_cell_value total_price'><span>"+total_price+"</span>"+currency+"</span></div>";
	}
	if(single_date === false) {
		jQuery("#check-info-" + id).html("<div class='reserv_info_row'><span class='reserv_info_cell'>" + jQuery("label[for=wpdevart_form_checkin" + id + "]").html() + "</span><span class='reserv_info_cell_value'>"+check_in+"</span></div><div class='reserv_info_row'><span class='reserv_info_cell'>" + jQuery("label[for=wpdevart_form_checkin" + id + "]").html() + "</span><span class='reserv_info_cell_value'>"+check_out+"</span></div>"+item_count+price_div+extra_div+total_div+"");
	} else {
		jQuery("#check-info-" + id).html("<div class='reserv_info_row'><span class='reserv_info_cell'>" + wpdevart.date + "</span><span class='reserv_info_cell_value'>"+single_date+"</span></div>"+item_count+price_div+extra_div+total_div+"");
	}
	
	jQuery("#wpdevart_extra_price_value"+id).val(eval(extra_price_value));
	jQuery("#wpdevart_total_price_value"+id).val(total_price);
	jQuery("#wpdevart_price_value"+id).val(price);
}

function wpdevart_responsive(){
	jQuery(".booking_calendar_container").each(function(index,el){
		if(jQuery(el).width() < 520 || jQuery("body").width() < 560) {
			jQuery(el).addClass("wpdevart-responsive");
			jQuery(el).next().addClass("wpdevart-responsive");
		}
	});
}

function wpdevart_required(submit) {
	var label = "",
		tag_name = "",
		type = "",
		error = false,
		error_email = false;
	if(jQuery(submit).closest("form").find(".wpdevart-required:not(span)").length != 0) {
		jQuery(submit).closest("form").find(".wpdevart-required:not(span)").each(function(index,el){
			label = jQuery(el).closest(".wpdevart-fild-item-container").find("label").text();
			tag_name = jQuery(el).prop("tagName");
			type = jQuery(el).attr("type");
			if(tag_name == "INPUT") {
				if(type == "text") {
					if(jQuery(el).val().trim() == "") {
						error = true;
					}
					if(jQuery(el).hasClass("wpdevart-email") && validate_email(jQuery(el).val())) {
						error_email = true;
					}
				} else if(type == "checkbox" || type == "radio") {
					if(typeof jQuery(el).attr("checked") == "undefined") {
						error = true;
					} 
				}
			} else if(tag_name == "SELECT") {
				if(jQuery(el).find("option:selected").val() == "") {
					error = true;
				}
			} else if(tag_name == "TEXTAREA") {
				if(jQuery(el).val().trim() == "") {
					error = true;
				}
			}
			if(error === true) {
				alert(label + ": " + wpdevart.required);
				jQuery(el).focus();
				jQuery(window).scrollTo( jQuery(el), 400,{'offset':{'top':-5}});
				return false;
			} else if(error_email === true) {
				alert(wpdevart.emailValid);
				jQuery(el).focus();
				jQuery(window).scrollTo( jQuery(el), 400,{'offset':{'top':-50}});
				return false;
			} 
		});
	}
	if(error === true || error_email === true) {
		return false;
	} else {
		return true;
	}	
}

function validate_email(email) {
    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!filter.test(email)) {
		return true;
	} else {
		return false;
	}	
}



