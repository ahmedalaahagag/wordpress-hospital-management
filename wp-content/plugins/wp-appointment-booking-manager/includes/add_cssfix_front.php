<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('#frmCssFix').on('submit',function(e){
		e.preventDefault();
		appointgen_save_CssFixFront();
	});
	
});	

function appointgen_save_CssFixFront(){
	var css = jQuery('#cssfix').val();
	if(css == ""){
		alert('Field is Empty');
		return;
	}
	jQuery.ajax({
			type: "POST",
      url: '<?php echo admin_url( 'admin-ajax.php' );?>?cssfix=front',
			data: {
        action: 'appointgen_save_cssfixfront',  
        css : css 
      },
			success: function (data) {
					console.log(data);
					if(data.length>0){
						alert('added successfully');
					}
			},
			error : function(s , i , error){
					console.log(error);
			}
	});
}	
</script>

<div class="wrapper">
  <div class="wrap" style="float:left; width:100%;">
    <div id="icon-options-general" class="icon32"></div>
    <div style="width:70%;float:left;"><h2><?php _e("Appointment Scheduling","appointgen_ustsappointment"); ?></h2></div>
       <div class="main_div">
     	<div class="metabox-holder" style="width:98%; float:left;">
          <div id="namediv" class="stuffbox" style="width:60%;">
          	<h3 class="top_bar"><?php _e("FrontEnd Css Fix","appointgen_ustsappointment"); ?></h3>
         	<form id="frmCssFix" action="" method="post" style="width:100%">
                <table style="margin:10px;width:300px;">
                    <tr>
                        <td><?php _e("CSS:","appointgen_ustsappointment"); ?></td>
                        <td><textarea cols="50" rows="10" id="cssfix" class="rounded" name="details"><?php echo get_option('cssfix_front');?></textarea> </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td>
                      	<input type="submit" id="btnaddcssfix" name="btnaddcssfix" value="Add Css" style="width:150px;background-color: #0074A2;"/>
                      </td>
                    </tr>
                </table>	
             </form>	 
          </div>
        </div>
       </div>  
  </div>
</div>