<?php
	global $table_prefix,$wpdb;
	$sql = "select * from ".$table_prefix."appointgen_ustsappointments";
	$appointments = $wpdb->get_results($sql);
	?>
  <script type="text/javascript">
		jQuery(document).ready(function(){
				jQuery('#btnsearchappointment').on('click',function(){
						var searchtext = jQuery('#txtsearchappointment').val();
						jQuery.ajax
						({
								type: "POST",
                url: '<?php echo admin_url( 'admin-ajax.php' );?>',
								data: {
                  action: 'appointgen_search_appointment',
                  searchtext: searchtext
                },
								success: function(data)
								{
								},
								error : function(s , i , error){
										console.log(error);
								}
						}).done(function(data){
              data = data.trim();
              appointgen_loading_hide();
              jQuery("#inner_content").html(data);
            });
				});
				appointgen_load_moredeals_data(1);
				function appointgen_load_moredeals_data(page){
						appointgen_loading_show();                    
						jQuery.ajax
						({
								type: "POST",
                url: '<?php echo admin_url( 'admin-ajax.php' );?>',
								data: {
                  action: 'appointgen_load_manageappointment_data',  
                  page: page
                },
								success: function(msg)
								{
								}
						}).done(function(msg){
                appointgen_loading_hide();
                jQuery("#inner_content").html(msg);
            });
				
				}
				function appointgen_loading_show(){
						jQuery('#loading').html("<img src='<?php echo USTSAPPOINTMENT_PLUGIN_URL; ?>/images/loading.gif'/>").fadeIn('fast');
				}
				function appointgen_loading_hide(){
						jQuery('#loading').fadeOut('fast');
				}                
				jQuery('#inner_content').delegate('.pagination li.active','click',function(){
						var page = jQuery(this).attr('p');
						appointgen_load_moredeals_data(page);
						jQuery('html, body').animate({
								scrollTop: jQuery("#content_top").offset().top
						}, 1950);
				});           
				jQuery('#inner_content').delegate('#go_btn','click',function(){
						var page = parseInt(jQuery('.goto').val());
						var no_of_pages = parseInt(jQuery('.total').attr('a'));
						if(page != 0 && page <= no_of_pages){
								appointgen_load_moredeals_data(page);
								jQuery('html, body').animate({
										scrollTop: jQuery("#content_top").offset().top
								}, 2050);
						}else{
								alert('Enter a PAGE between 1 and '+no_of_pages);
								jQuery('.goto').val("").focus();
								return false;
						}
				});
				jQuery('#inner_content').delegate('#lnkapprove','click',function(e){
					e.preventDefault();
					var appointmentid = jQuery(this).parent().children('#hdnappointmentid').val();
					jQuery.ajax({
							type: "POST",
              url: '<?php echo admin_url( 'admin-ajax.php' );?>',
							data: {
                action: 'appointgen_activate_appointment',
                appointment_id:appointmentid
              },
							success: function (data) {
									var count = data.length;
									if(count>0){
										alert('Appointment Activated');
									}
							},
							error : function(s , i , error){
									console.log(error);
							}
					});
				});	
				jQuery('#inner_content').delegate('#delete_appointment','click',function(e){
					e.preventDefault();
          if(!confirm('Are you sure want to delete')){
            return false;
          }
					var appointmentid = jQuery(this).parent().children('#hdnappointmentid').val();
					jQuery.ajax({
							type: "POST",
              url: '<?php echo admin_url( 'admin-ajax.php' );?>',
							data: {
                action: 'appointgen_delete_appointment',
                appointment_id:appointmentid
              },
							success: function (data) {
									var count = data.length;
									if(count>0){
										alert('appointment Deleted');
									}
							},
							error : function(s , i , error){
									console.log(error);
							}
					});
					console.log(jQuery(this).parent().parent().remove());
				});
					
		});
	</script>
  <style type="text/css">
		#btnsearchappointment{
			background:url('<?php echo USTSAPPOINTMENT_PLUGIN_URL ?>/images/search.png') no-repeat;
			width: 30px; 
			height: 30px; 
			cursor:pointer;
		}
	</style>
	<div class="wrapper">
  <div class="wrap" style="float:left; width:100%;">
    <div id="icon-options-general" class="icon32"><br />
    </div>
    
    <div style="width:50%;float:left;"><h2><?php _e("Appointment Scheduling","appointgen_ustsappointment"); ?></h2></div>
    <div style="width:29%;float:left;margin-top:15px;">
    	<form id="frmsearchb" method="post" action="">
      	<input type="text" name="txtsearchappointment" id="txtsearchappointment" value="" style="width:250px;height:40px;" />
      	<input type="button" id="btnsearchappointment" name="btnsearchappointment" value="" />
      </form>
    </div>
    
    <div class="main_div">
     	<div class="metabox-holder" style="width:80%; float:left;">
        <div id="namediv" class="stuffbox" style="width:99%;">
        <h3 class="top_bar"><?php _e("Manage Appointment","appointgen_ustsappointment"); ?></h3>
				<div id="inner_content">		
        	<div class="data"></div>
			  	<div class="pagination"></div>			
				 <table class="wp-list-table widefat fixed bookmarks" cellspacing="0">
          <thead>
            <tr>
              <th><?php _e("Schedule","appointgen_ustsappointment"); ?></th>
              <th><?php _e("Date","appointgen_ustsappointment"); ?></th>
              <th><?php _e("Start Time","appointgen_ustsappointment"); ?></th>
              <th><?php _e("End Time","appointgen_ustsappointment"); ?></th>
              <th><?php _e("Email","appointgen_ustsappointment"); ?></th>
              <th><?php _e("Phone","appointgen_ustsappointment"); ?></th>
              <th></th>
            </tr>
          </thead>
					<?php
          foreach($appointments as $appointment){
          ?>
            <tr class="alternate">
                <td><?php printf(__("%s","appointgen_ustsappointment"), $appointment->schedule);?></td>
                <td><?php printf(__("%s","appointgen_ustsappointment"), $appointment->date);?></td>
                <td><?php printf(__("%s","appointgen_ustsappointment"), $appointment->start_time);?></td>
                <td><?php printf(__("%s","appointgen_ustsappointment"), $appointment->end_time);?></td>
                <td><?php printf(__("%s","appointgen_ustsappointment"), $appointment->email);?></td>
                <td><?php printf(__("%s","appointgen_ustsappointment"), $appointment->phone);?></td>
                
                <td>
                  <a href="<?php echo get_option('siteurl');?>/wp-admin/admin.php?page=add-appointment-menu&calltype=editappointment&id=<?php echo $appointment->appointment_id;?>"><?php _e("edit","appointgen_ustsappointment"); ?></a>
                  &nbsp;&nbsp;&nbsp;<a style="cursor:pointer;" id="delete_appointment"><?php _e("delete","appointgen_ustsappointment"); ?></a>
                  <input type="hidden" id="hdnappointmentid"  name="hdnappointmentid" value="<?php echo $appointment->appointment_id;?>" />
                </td>
            </tr>
            <?php
            }
            ?>
          <tfoot>
            <tr>
              <th><?php _e("Schedule","appointgen_ustsappointment"); ?></th>
              <th><?php _e("Date","appointgen_ustsappointment"); ?></th>
              <th><?php _e("Start Time","appointgen_ustsappointment"); ?></th>
              <th><?php _e("End Time","appointgen_ustsappointment"); ?></th>
              <th><?php _e("Email","appointgen_ustsappointment"); ?></th>
              <th><?php _e("Phone","appointgen_ustsappointment"); ?></th>
              <th></th>
            </tr>
          </tfoot>
        </table>
				</div>
				</div>
		  </div>
	  </div>
	 </div>
  </div>
  
  <div id='loading'></div>