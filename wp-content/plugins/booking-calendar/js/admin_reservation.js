/*
*ADMIN SCRIPT 
*/

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
		if($(".wpdevart_res_month_view").length != 0) {
			var reserv = "true";
			var cal_id = $(this).parent().parent().find("table").data('id');
		} else {
			var reserv = "false";
			var cal_id = $(this).parent().next().data('id');
		}
		$(bc_main_div).find('.wpdevart-load-overlay').show();
        $.post(wpdevart.ajaxUrl, {
            action: 'wpdevart_ajax',
            wpdevart_reserv: reserv,
            wpdevart_selected: start_index,
            wpdevart_selected_date: selected_date,
            wpdevart_link: $(this).find('a').attr('href'),
			wpdevart_id: cal_id,
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
		var reserv_data = {};
		$(this).closest("form").find("input[type=text],button,input[type=hidden],input[type=checkbox],input[type=radio],select,textarea").each(function(index,element){
			reserv_data[jQuery(element).attr("name")] = $(element).val();
		});
		reserv_json = JSON.stringify(reserv_data);
		e.preventDefault();
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
			$(window).scrollTo( reserv_cont, 500,{'offset':{'top':-80}});
        });
		e.stopPropagation();
	});
	
	/*
	*CALENDAR
	*/
	
	var select_ex = false,
		select_ex_single = false,
		count_item = jQuery(".wpdevart-day").length,
		start_index,check_in,check_out,
		item_count = 0,
		extra_price_value = 0;
	$(".wpdevart-day").live("click",function() {var price = 0;
		var price_div = "",
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
					$(el).closest(".booking_calendar_container").find(".error_text_container").html("<span class='error_text'>There are no services available for the period you selected.</span><span class='notice_text_close'>x</span>").fadeIn();
					$(".wpdevart-day").each(function(){
						$(this).removeClass("selected");
					});
					exist = false;
				}
				else {
					$(el).closest(".booking_calendar_container").find(".error_text_container").fadeOut(10).html("");
					$(el).closest(".booking_calendar_container").find(".successfully_text_container").fadeOut(10).html("");
				}
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
				/*Reservation info*/
				$("#wpdevart_form_checkin" + id).val(check_in);
				$("#wpdevart_form_checkout" + id).val(check_out);
				$(this).parent().find(".selected").each(function(index,sel_element) {
					if($(sel_element).find(".new-price").length != 0) {
						price += $(sel_element).find(".new-price").data("price");
						currency = $(sel_element).find(".new-price").data("currency")
					}
				});
				var total_price = price;
				
				if($("#wpdevart_count_item"+id).length != 0) {
					item_count = "<div class='reserv_info_row'><span class='reserv_info_cell'>Item Count</span><span class='reserv_info_cell_value count_item'>1</span></div>";
				}
				if($(el).closest(".booking_calendar_container").next().find(".wpdevart_extras").length != 0) {
					$(el).closest(".booking_calendar_container").next().find(".wpdevart_extras").each(function(sel_index,select){
						var label = $(select).parent().parent().find("label").html(),
							option_label_arr = $(select).find("option:selected").html().split(' '),
							option_label = option_label_arr[0],
							operation = $(select).find("option:selected").data("operation"),
							type = $(select).find("option:selected").data("type"),
							opt_price = parseFloat($(select).find("option:selected").data("price"));
						if(type == "price") {
							if(opt_price != 0 || opt_price != "") {
								var option_info = "<span class='extra_price' data-extraprice='"+(opt_price*selected_count)+"' data-extraop='"+operation+"'><span class='extra_price_value'>"+operation+(opt_price*selected_count)+"</span>"+currency+"</span>";
							} else {
								var option_info = "<span class='extra_price' data-extraprice='"+opt_price+"' data-extraop='"+operation+"'><span class='extra_price_value'></span></span>";
							}
							total_price = (operation == "+")? (total_price + (opt_price*selected_count)) : (total_price - (opt_price*selected_count));
							extra_price_value += operation+(opt_price*selected_count);
						} else {
							if(opt_price != 0 || opt_price != "") {
								var option_info = "<span class='extra_percent'>"+operation+opt_price+"%</span><span class='extra_price' data-extraprice='"+opt_price+"' data-extraop='"+operation+"'><span class='extra_price_value'>"+operation+((price * opt_price)/100)+"</span>"+currency+"</span>";
							} else {
								var option_info = "<span class='extra_percent'></span><span class='extra_price' data-extraprice='"+opt_price+"' data-extraop='"+operation+"'><span class='extra_price_value'></span></span>";
							}
							total_price = (operation == "+")? (total_price + ((price * opt_price)/100)) : (total_price - ((price * opt_price)/100));
							extra_price_value += operation+((price * opt_price)/100);
						}
						extra_div += "<div class='wpdevart-extra-info wpdevart-extra-"+sel_index+" reserv_info_row "+($(select).attr("id"))+"'><span class='reserv_info_cell'>"+label+"</span><span class='reserv_info_cell_value'><span class='option_label'>"+option_label+"</span>"+option_info+"</span></div>";
						
					});
				}
				if(price != 0) {
					price_div = "<div class='reserv_info_row'><span class='reserv_info_cell'>Price</span><span class='reserv_info_cell_value price' data-price='"+price+"'><span>"+price+"</span>"+currency+"</span></div>";
					total_div = "<div class='wpdevart-total-price reserv_info_row'><span class='reserv_info_cell'>Total</span><span class='reserv_info_cell_value total_price'><span>"+total_price+"</span>"+currency+"</span></div>";
				}
				$("#check-info-" + id).html("<div class='reserv_info_row'><span class='reserv_info_cell'>Check In</span><span class='reserv_info_cell_value'>"+check_in+"</span></div><div class='reserv_info_row'><span class='reserv_info_cell'>Check Out</span><span class='reserv_info_cell_value'>"+check_out+"</span></div>"+item_count+price_div+extra_div+total_div+"");
				$("#wpdevart_extra_price_value"+id).val(eval(extra_price_value));
				$("#wpdevart_total_price_value"+id).val(total_price);
				$("#wpdevart_price_value"+id).val(price);
			}
			$(el).closest(".booking_calendar_container").next().find(".wpdevart-submit").show();
		} else if($("#wpdevart_single_day" + id).length != 0) {
			select_ex_single = true;
			$(".wpdevart-day").each(function(){
				$(this).removeClass("selected");
			});
			if(typeof($(el).data("available")) != "undefined") {
				$(el).addClass("selected");
				$("#wpdevart_single_day" + id).val($(el).data("date"));
				$(el).closest(".booking_calendar_container").find(".error_text_container").fadeOut(10).html("");
				$(el).closest(".booking_calendar_container").find(".successfully_text_container").fadeOut(10).html("");
				$("#wpdevart_count_item"+id+" option").remove();
				for(var j = 1; j <= ($(el).data("available")); j++){
					$("#wpdevart_count_item"+id).append("<option value='"+j+"'>"+j+"</option>");
				}
				$(el).closest(".booking_calendar_container").next().find(".wpdevart-submit").show();
			} else {
				$(el).closest(".booking_calendar_container").find(".error_text_container").html("<span class='error_text'>There are no services available for this day.</span><span class='notice_text_close'>x</span>").fadeIn();
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
		
	$(".reserv-info-open").live( "click", function(){
		$(this).closest(".reserv-info").next().slideToggle();
		$(this).toggleClass("active");
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
	for_month_view();
	wpdevart_responsive();
});
jQuery( document ).ajaxComplete(function( event, xhr, settings ) {
  for_month_view();
  wpdevart_responsive();
});

/*For month view*/
function for_month_view(){
	var res_id = new Array();
	var id = 0;
	jQuery(".wpdevart-calendar-container .reservation-month").each(function(i,el){
		id = parseInt(jQuery(el).attr("class").replace("reservation-month reservation-month-", ""));
		if(jQuery.inArray(id, res_id) === -1)
			res_id.push(id);
	});
	jQuery.each(res_id, function( index, value ) {
	    jQuery(".reservation-month-" + value).css("top",((index*19) + 19) + "px");
	});
	jQuery(".wpdevart-calendar-container td").css("height",19*res_id.length + 19);

	jQuery(".wpdevart-calendar-container tr").each(function( index, element ) {
	    if(jQuery(element).find(".reservation-month").length == 0) {
			jQuery(element).find("td").css("height","70px");
		}
	});
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
	    new_total = (extraop == "+") ? (total - Math.abs(extraprice)) : (total + Math.abs(extraprice)),
		selected_count = jQuery(el).closest(".wpdevart-booking-form-container").prev().find(".wpdevart-available.selected").length;
	if(jQuery(el).closest(".wpdevart-booking-form").find("."+id).length != 0) {
		jQuery(el).closest(".wpdevart-booking-form").find("."+id+" .extra_price").data("extraprice", thisprice);
		jQuery(el).closest(".wpdevart-booking-form").find("."+id+" .extra_price").data("extraop", thisop);
		jQuery(el).closest(".wpdevart-booking-form").find("."+id+" .option_label").html(label);
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

function wpdevart_responsive(){
	jQuery(".booking_calendar_container").each(function(index,el){
		if(jQuery(el).width() < 520 || jQuery("body").width() < 560) {
			jQuery(el).addClass("wpdevart-responsive");
			jQuery(el).next().addClass("wpdevart-responsive");
		}
	});
}

