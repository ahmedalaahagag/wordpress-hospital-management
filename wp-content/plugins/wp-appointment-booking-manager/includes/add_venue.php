<script type="text/javascript">
	jQuery(document).ready(function(){
			var calltype = appointgen_getUrlVars()["calltype"];
			if(calltype){
				if(calltype = 'editvenue'){
					<?php
          if(isset($_REQUEST['id'])){
            $id = $_REQUEST['id'];
            global $table_prefix,$wpdb;
            $sql = "select * from ".$table_prefix."appointgen_venues where id=".$id;
            $result = $wpdb->get_results($sql);
            ?>
            var venue = <?php echo json_encode($result[0]);?>;
            jQuery('#hdnvenueid').val(venue['id']);
            jQuery('#venue_name').val(venue['venue_name']);
            jQuery('#venue_address').val(venue['venue_address']);
            jQuery('#description').val(venue['description']);
          <?php } ?>
				}
			}	
	});
</script>
<div class="wrapper">
  <div class="wrap" style="float:left; width:100%;">
    <div id="icon-options-general" class="icon32"><br />
    </div>
    <h2><?php _e("Venue","appointgen_ustsappointment"); ?></h2>
    <div class="main_div">
     	<div class="metabox-holder" style="width:69%; float:left;">
        <div id="namediv" class="stuffbox" style="width:99%;">
        <h3 class="top_bar"><?php _e("Add Venue","appointgen_ustsappointment"); ?></h3>
        	<form id="frmaddvenue" method="post" action="" novalidate="novalidate">
          	<table style="padding:10px;">
              <tr>
                <td><?php _e("Venue Name","appointgen_ustsappointment"); ?></td>
                <td><input type="text" name="venue_name" id="venue_name" value="" /><span style="color:red;">*</span> </td>
              </tr>
              <tr>
                <td><?php _e("Venue Address","appointgen_ustsappointment"); ?></td>
                <td><textarea cols="50" rows="5" name="venue_address" id="venue_address"></textarea><span style="color:red;">*</span> </td>
              </tr>
              <tr>
                <td><?php _e("Venue Description","appointgen_ustsappointment"); ?></td>
                <td><textarea cols="50" rows="7" name="description" id="description"></textarea>  </td>
              </tr>
              <tr><td colspan='2'></td></tr>
              <tr>
                <td></td>
                <td>
                	<input type="submit" id="btnaddvenue" name="btnaddvenue" value="Add Venue" style="width:150px;background-color: #0074A2;"/>
                	<input type="hidden" id="hdnvenueid" name="hdnvenueid" value="" style="width:150px;"/>
                </td>
              </tr>
            </table>
          </form>
        </div>
      </div>
    </div>
   </div>
  </div>