<script type="text/javascript">
	jQuery(document).ready(function(){
			var calltype = appointgen_getUrlVars()["calltype"];
			if(calltype){
				if(calltype == 'editservice'){
					<?php
          if(isset($_REQUEST['id'])){
            $id = $_REQUEST['id'];
            global $table_prefix,$wpdb;
            $sql = "select * from ".$table_prefix."appointgen_services where id=".$id;
            $result = $wpdb->get_results($sql);
            ?>
            var service = <?php echo json_encode($result[0]);?>;
            jQuery('#hdnserviceid').val(service['id']);
            jQuery('#provider_name').val(service['provider_name']);
            jQuery('#service_name').val(service['service_name']);
            jQuery('#service_details').val(service['service_details']);
            jQuery('#price').val(service['price']);
            var days = service['days'].split(',');
            jQuery('#days').val(days);
          <?php } ?>  
				}
			}	
	});
</script>
<div class="wrapper">
  <div class="wrap" style="float:left; width:100%;">
    <div id="icon-options-general" class="icon32"><br />
    </div>
    <h2><?php _e("Services","appointgen_ustsappointment"); ?></h2>
    <div class="main_div">
     	<div class="metabox-holder" style="width:69%; float:left;">
        <div id="namediv" class="stuffbox" style="width:99%;">
        <h3 class="top_bar"><?php _e("Add Services","appointgen_ustsappointment"); ?></h3>
        	<form id="frmaddservice" method="post" action="" novalidate="novalidate">
          	<table style="padding:10px;">
            	<tr>
                <td><?php _e("Provider Name","appointgen_ustsappointment"); ?></td>
                <td><input type="text" name="provider_name" id="provider_name" value="" /><span style="color:red;">*</span> </td>
              </tr>
              <tr>
                <td><?php _e("Service Name","appointgen_ustsappointment"); ?></td>
                <td><input type="text" name="service_name" id="service_name" value="" /><span style="color:red;">*</span> </td>
              </tr>
              <tr>
                <td><?php _e("Service Details","appointgen_ustsappointment"); ?></td>
                <td><textarea cols="50" rows="7" name="service_details" id="service_details"></textarea> </td>
              </tr>
              <tr>
                <td><?php _e("Price","appointgen_ustsappointment"); ?></td>
                <td><input type="text" name="price" id="price" value="" /><span style="color:red;">*</span> </td>
              </tr>
              <tr>
                <td><?php _e("Days","appointgen_ustsappointment"); ?></td>
                <td>
                  <select name="days" id="days" multiple>
                    <option value="0"><?php _e("Sun","appointgen_ustsappointment"); ?></option>
                    <option value="1"><?php _e("Mon","appointgen_ustsappointment"); ?></option>
                    <option value="2"><?php _e("Tue","appointgen_ustsappointment"); ?></option>
                    <option value="3"><?php _e("Wed","appointgen_ustsappointment"); ?></option>
                    <option value="4"><?php _e("Thu","appointgen_ustsappointment"); ?></option>
                    <option value="5"><?php _e("Fri","appointgen_ustsappointment"); ?></option>
                    <option value="6"><?php _e("Sat","appointgen_ustsappointment"); ?></option>
                  </select><span style="color:red;">*</span>
                  <br>
                  <p style="font-style:italic;font-size:11px;"><?php _e("[Hold down the Ctrl (windows) / Command (Mac) button to select multiple options.]","appointgen_ustsappointment"); ?></p>
                </td>
              </tr>
              <tr><td colspan='2'></td></tr>
              <tr>
                <td></td>
                <td>
                	<input type="submit" id="btnaddservice" name="btnaddservice" value="<?php _e("Add Service","appointgen_ustsappointment"); ?>" style="width:150px;background-color:#0074A2;"/>
                  <input type="hidden" id="hdnserviceid" name="hdnserviceid" value="" style="width:150px;"/>
                </td>
              </tr>
            </table>
          </form>
        </div>
      </div>
    </div>
   </div>
  </div>