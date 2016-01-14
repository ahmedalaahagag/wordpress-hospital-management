<?php
$obj_package = new Hmgt_packages();
if(isset($_REQUEST['save_package']))
{

	if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
	{
		$result = $obj_package->hmgt_add_package($_REQUEST);
		if($result)
		{
			if($_REQUEST['action'] == 'edit')
			{
				wp_redirect ( home_url().'?dashboard=user&page=packages&tab=packagelist&message=2');
			 }
			else
			{
			wp_redirect ( home_url().'?dashboard=user&page=packages&tab=packagelist&message=1');
			}


		}
	}
}
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
	$result = $obj_session->delete_session($_REQUEST['package_id']);
	if($result)
	{
			wp_redirect ( home_url().'?dashboard=user&page=packages&tab=packagelist&message=3');
	}
}
if(isset($_REQUEST['message']))
{
	$message =$_REQUEST['message'];
	if($message == 1)
	{?>
			<div id="message" class="updated below-h2 ">
			<p>
			<?php
				_e('Record inserted successfully','hospital_mgt');
			?></p></div>
			<?php

	}
	elseif($message == 2)
	{?><div id="message" class="updated below-h2 "><p><?php
				_e("Record updated successfully",'hospital_mgt');
				?></p>
				</div>
			<?php

	}
	elseif($message == 3)
	{?>
	<div id="message" class="updated below-h2"><p>
	<?php
		_e('Record deleted successfully','hospital_mgt');
	?></div></p><?php

	}
}
$active_tab = isset($_GET['tab'])?$_GET['tab']:'packagelist';
?>
<script type="text/javascript">
$(document).ready(function() {
	$('#session_form').validationEngine();

} );

</script>
<style>
 .bootstrap-datetimepicker-widget{
 z-index: 199999;
 }
</style>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<script src="/staging/GMHC-CMS/wp-includes/js/bootstrap-datetimepicker.min.js"></script>
<script src="/staging/GMHC-CMS/wp-includes/css/bootstrap-datetimepicker.min.css"></script>
<div class="panel-body panel-white">
 <ul class="nav nav-tabs panel_tabs" role="tablist">
      <li class="<?php if($active_tab == 'packagelist'){?>active<?php }?>">
          <a href="?dashboard=user&page=packages&tab=packagelist">
             <i class="fa fa-align-justify"></i> <?php _e('Package List', 'hospital_mgt'); ?></a>
          </a>
      </li>
	  <li class="<?php if($active_tab=='addpackage'){?>active<?php }?>">
		  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['package_id']))
			{?>
			<a href="?dashboard=user&page=packages&tab=addpackage&action=edit&package_id=<?php if(isset($_REQUEST['package_id'])) echo $_REQUEST['package_id'];?>"" class="tab <?php echo $active_tab == 'addpackage' ? 'active' : ''; ?>">
             <i class="fa fa"></i> <?php _e('Edit package', 'hospital_mgt'); ?></a>
			 <?php }
			else
			{?>
				<a href="?dashboard=user&page=packages&tab=addpackage" class="tab <?php echo $active_tab == 'addpackage' ? 'active' : ''; ?>">
				<i class="fa fa-plus-circle"></i> <?php _e('Add package', 'hospital_mgt'); ?></a>
	  <?php } ?>

	</li>

</ul>
	<div class="tab-content">
	<?php if($active_tab == 'packagelist'){?>

    	 <div class="tab-pane fade active in"  id="eventlist">
         <?php
		 //	$retrieve_class = get_all_data($tablename);
		?>
		<div class="panel-body">
        <div class="table-responsive">
        <table id="hmgt_package" class="display dataTable " cellspacing="0" width="100%">
        	<thead>
            <tr>
			<th><?php _e( 'Package Name', 'hospital_mgt' ) ;?></th>
               <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
             </thead>
		<tfoot>
            <tr>
			<th><?php _e( 'Package Name', 'hospital_mgt' ) ;?></th>
                <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </tfoot>

        <tbody>
         <?php
		$package_data=$obj_package->get_all_packages();
		 if(!empty($package_data))
		 {
		 	foreach ($package_data as $retrieved_data){


		 ?>
            <tr>
				<td class="session_name"><?php echo $retrieved_data->package_name;?></td>
               	<td class="action">
               	<a href="?dashboard=user&page=packages&tab=addpackage&action=edit&package_id=<?php echo $retrieved_data->package_id;?>" class="btn btn-info">
               	<?php _e('Edit', 'hospital_mgt' ) ;?></a>
                <a href="?dashboard=user&page=packages&tab=packagelist&action=delete&package_id=<?php echo $retrieved_data->package_id;?>" class="btn btn-danger"
                onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
                <?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>

                </td>

            </tr>
            <?php }

		}?>

        </tbody>
        </table>
 		</div>
		</div>
		</div>
	<?php }
	 if($active_tab == 'addpackage'){

//Add Package
$edit=0;
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
{
	$edit = 1;
	$package_id = $_REQUEST['package_id'];
	$result = $obj_package->get_single_package($package_id);
}
?>

	<script type="text/javascript">
$(document).ready(function() {
	$('#package_form').validationEngine();

  });
</script>
<style>
body.modal-open .bootstrap-datetimepicker-widget {
    z-index: 10000  !important;
}
</style>
       <div class="panel-body">
       <?php
       $relativeurl = home_url().'?dashboard=user&page=packages&tab=packagelist';
        ?>
        <form name="package_form" action="" method="post" class="form-horizontal" id="package_form">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="package_id" value="<?php if(isset($_REQUEST['package_id'])) echo $_REQUEST['package_id'];?>"  />

		<div class="form-group">
			<label class="col-sm-2 control-label" for="med_category_name"><?php _e('Package Name','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="package_name" class="form-control validate[required,custom[onlyLetterSp]] text-input" type="text"
				value="<?php if($edit){ echo $result[0]->package_name;}elseif(isset($_POST['package_name'])) echo $_POST['package_name'];?>" name="package_name">
			</div>
		</div>

        <div class="form-group">
                <label class="col-sm-2 control-label" for="patient_id"><?php _e('Patient','hospital_mgt');?></label>
                <div class="col-sm-8">
                    <?php
                    $obj_users=new Hmgtuser();
                    $patients  = $obj_users->get_user_by_type('patient');
                    hmgt_render_options($patients,'patient',$result->patient_id);
                    ?>
                </div>
            </div>
            <div class="form-group">
			<label class="col-sm-2 control-label" for="med_category_name"><?php _e('Package Discount','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="package_name" class="form-control validate[required] text-input" type="text"
				value="<?php if($edit){ echo $result[0]->package_discount;}elseif(isset($_POST['package_discount'])) echo $_POST['package_discount'];?>" name="package_discount">
			</div>
		    </div>
            <!-- If there is session -->
            <?php
            if($result)
            {
            foreach($result as $session_entry)
            {
            $session_entry->to_date =  new DateTime("@$session_entry->to_date");
            $session_entry->to_date->format('Y-m-d H:i:s');
            $session_entry->to_date = (array) $session_entry->to_date;
            $session_entry->to_date =  $session_entry->to_date['date'];
            $session_entry->to_date = explode('.',$session_entry->to_date)[0];
            $session_entry->from_date =  new DateTime("@$session_entry->from_date");
            $session_entry->from_date->format('Y-m-d H:i:s');
            $session_entry->from_date = (array) $session_entry->from_date;
            $session_entry->from_date =  $session_entry->from_date['date'];
            $session_entry->from_date = explode('.',$session_entry->from_date)[0];
            ?>
            <div class="session_entry">
            <div class="form-group" id="form-group">
            	<input type="hidden" name="session[]" value="<?php if($edit){ echo $session_entry->session_id;};?>"  />
                <label class="col-sm-2 control-label" for="session_id"><?php _e('Session Type','hospital_mgt');?></label>
                <div class="col-sm-8">
                    <?php
                    $obj_session=new Hmgt_session();
                    $sessions  = $obj_session->get_all_session();
                    hmgt_render_options($sessions,'session',$session_entry->session_id,$session_entry->session_id,1);
                    ?>
                    <script type="text/javascript">
                        jQuery('#session_id<?php echo $session_entry->session_id?>').on('change',function(){
                            var relativeurl = '<?php echo HMS_PLUGIN_URL.'/class/session_duration.php'?>' ;
                            var data = {'action':'get_single_session_durations','session_id': jQuery('#session_id<?php echo $session_entry->session_id?>').val()};
                            $.get(relativeurl, data, function(callbackdata){
                                $("#duration_id<?php echo $session_entry->session_id?>").html(callbackdata);
                            })
                        });
                    </script>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="doctor_id"><?php _e('Doctor','hospital_mgt');?></label>
                <div class="col-sm-8">
                <?php
                $obj_users=new Hmgtuser();
                $doctors = $obj_users->get_user_by_type('doctor');
                hmgt_render_options($doctors,'doctor',$result->doctor_id);
                ?>
                </div>
		    </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="duration_id"><?php _e('Duration','hospital_mgt');?></label>
                <div class="col-sm-8">
                <?php
                    $obj_durations=new Hmgt_session_duration();
                    $durations  = $obj_durations->get_single_session_durations($session_entry->session_id);
                    hmgt_render_options_duration($durations,'duration',$session_entry->duration_id,$session_entry->session_id,1);
                 ?>
                </div>
            </div>

          	<div class="form-group">
				<label class="col-sm-2 control-label" for="from_date"><?php _e('From','hospital_mgt');?><span class="require-field"></span></label>
				<div class="col-sm-3">
                    <div class="form-group">
                        <div class="">
                            <div id="fromdatetimepicker_<?php echo $session_entry->session_id?>" class="input-append date input-group from_time">
                                <input data-format="yyyy-MM-dd hh:mm:ss"  value="<?php echo $session_entry->from_date; ?>" class="form-control" readonly type="text" name="from_date[]">
                                <span class="add-on input-group-addon">
                                  <i data-time-icon="fa fa-clock-o" data-date-icon="fa fa-calendar"></i>
                                </span>
                                <script type="text/javascript">
                                    var from_time;
                                    var to_time;
                                    var date = new Date('<?php echo $session_entry->from_date; ?>');
                                    $(function() {
                                        $('#fromdatetimepicker_<?php echo $session_entry->session_id?>').datetimepicker({
                                            defaultDate: date
                                        });
                                        $("#fromdatetimepicker_<?php echo $session_entry->session_id?>").datetimepicker('update');
                                        $('#fromdatetimepicker_<?php echo $session_entry->session_id?>').datetimepicker().on('changeDate',function(ev) {
                                            from_time= ev.timeStamp;
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
				</div>
                <label class="col-sm-2 control-label" for="from_date"><?php _e('To','hospital_mgt');?><span class="require-field"></span></label>
                <div class="col-sm-3">
                    <div class="form-group">
                        <div class="">
                            <div id="todatetimepicker_<?php echo $session_entry->session_id?>" class="input-append date input-group">
                                <input data-format="yyyy-MM-dd hh:mm:ss" class="form-control to_time" value="<?php echo $session_entry->to_date; ?>"  readonly type="text" name="to_date[]">
                                <span class="add-on input-group-addon">
                                  <i data-time-icon="fa fa-clock-o" data-date-icon="fa fa-calendar"></i>
                                </span>
                                <script type="text/javascript">
                                    $(function() {
                                        var date = new Date('<?php echo $session_entry->to_date; ?>');
                                        $('#todatetimepicker_<?php echo $session_entry->session_id?>').datetimepicker({
                                            defaultDate:date
                                        });
                                        $("#todatetimepicker_<?php echo $session_entry->session_id?>").datetimepicker('update');
                                        $('#todatetimepicker_<?php echo $session_entry->session_id?>').datetimepicker({ dateFormat: 'yyyy-mm-dd hh:ii' }).on('changeDate',function(ev) {
                                            to_time= ev.timeStamp;
                                            var relativeurl = '<?php echo HMS_PLUGIN_URL.'/class/session_duration.php'?>' ;
                                            var data = {'action':'is_over_lapping','from_time':from_time ,'to_time': to_time ,'doctor_id': jQuery('#doctor_id').val() };
                                            $.get(relativeurl, data, function(callbackdata){
                                                if(callbackdata==1)
                                                {
                                                $(".over_lapping_error").html('<font color="red">This session over laps with another session</font>')
                                                }
                                            })
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
                <div class="over_lapping_error"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="paid"><?php _e('Spent','hospital_mgt');?></label>
                    <div class="col-sm-8">
                        <input type="checkbox" name="session_spent[]" class="form-control" <?php if($session_entry->session_spent==1)echo "checked"; ?>>
                    </div>
                </div>
             <div class="form-group">
                    <label class="col-sm-2 control-label" for="paid"><?php _e('Paid','hospital_mgt');?></label>
                        <div class="col-sm-8">
             <input type="checkbox" name="session_paid[]" class="form-control" <?php if($session_entry->session_paid==1)echo "checked"; ?>>
             </div>
             </div>
             <div class="form-group">
                <label class="col-sm-2 control-label" for="session_entry"></label>
                <div class="col-sm-3">
                    <button id="delete_session<?php echo $session_entry->package_id ;?><?php echo $session_entry->session_id ;?><?php echo $session_entry->session_duration_id ;?>" class="btn btn-danger btn-sm btn-icon icon-left" type="button" name="delete_seesion"><?php _e('Delete Session','hospital_mgt'); ?></button>
                    <script type="text/javascript">
                    console.log($("#delete_session<?php echo $session_entry->package_id ;?><?php echo $session_entry->session_id ;?><?php echo $session_entry->session_duration_id ;?>"));
                    $("#delete_session<?php echo $session_entry->package_id ;?><?php echo $session_entry->session_id ;?><?php echo $session_entry->session_duration_id ;?>").click(function(){
                    alert('x');
                     var relativeurl = '<?php echo HMS_PLUGIN_URL.'/class/session_duration.php'?>' ;
                            var data = {'action':'delete_session','session_id':<?php echo $session_entry->session_id ;?>,'package_id':<?php echo $session_entry->package_id ;?>,'duration_id':<?php echo $session_entry->session_duration_id ;?>};
                            $.get(relativeurl, data, function(callbackdata){
                             $(this).closest('.session_entry').remove();
                            })
                            });
                    </script>
                </div>
            </div>
            </div>
			<?php }
			} ?>
            <!-- Add Session -->
            <div class="session_entry modal fade" tabindex="-1" role="dialog" id="sessionmodal">
               <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                         <h4 class="modal-title">Add Session</h4>
                    </div>
                    <div class="modal-body">
            <div class="form-group" id="form-group">
                <label class="col-sm-2 control-label" for="session_id"><?php _e('Session Type','hospital_mgt');?></label>
                <div class="col-sm-8">
                    <?php
                    $obj_session=new Hmgt_session();
                    $sessions  = $obj_session->get_all_session();
                    hmgt_render_options($sessions,'session',$result->session_id,$result->session_id,1);
                    ?>
                    <script type="text/javascript">
                        $('#session_id').on('change',function(){
                           var relativeurl = '<?php echo HMS_PLUGIN_URL.'/class/session_duration.php'?>' ;
                            var data = {'action':'get_single_session_durations','session_id': jQuery('#session_id').val()};
                            $.get(relativeurl, data, function(callbackdata){
                                $("#duration_id"<?php $result->session_id ?>).html(callbackdata);
                            })
                            });
                    </script>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="doctor_id"><?php _e('Doctor','hospital_mgt');?></label>
                <div class="col-sm-8">
                <?php
                $obj_users=new Hmgtuser();
                $doctors = $obj_users->get_user_by_type('doctor');
                hmgt_render_options($doctors,'doctor',$result->doctor_id);
                ?>
                </div>
		    </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="duration_id"><?php _e('Duration','hospital_mgt');?></label>
                <div class="col-sm-8">
                    <select name="duration_id[]" class="form-control validate[required] duration_id" id="duration_id"<?php $result->session_id ?>>
                        <option value="">Select Session First</option>
                    </select>
                </div>
            </div>
          	<div class="form-group">
				<label class="col-sm-2 control-label" for="from_date"><?php _e('From','hospital_mgt');?><span class="require-field"></span></label>
				<div class="col-sm-3">
                    <div class="form-group">
                        <div class="">
                            <div id="datetimepicker_1" class="input-append date input-group from_time">
                                <input data-format="yyyy-MM-dd hh:mm:ss"  class="form-control" readonly type="text" name="from_date[]">
                                <span class="add-on input-group-addon">
                                  <i data-time-icon="fa fa-clock-o" data-date-icon="fa fa-calendar"></i>
                                </span>
                                <script type="text/javascript">
                                    var from_time;
                                    var to_time;
                                    $(function() {
                                        $('#datetimepicker_1').datetimepicker({
                                            language: 'pt-BR'
                                        });
                                        $('#datetimepicker_1').datetimepicker().on('changeDate',function(ev) {
                                            from_time= ev.timeStamp;
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
				</div>
                <label class="col-sm-2 control-label" for="from_date"><?php _e('To','hospital_mgt');?><span class="require-field"></span></label>
                <div class="col-sm-3">
                    <div class="form-group">
                        <div class="">
                            <div id="datetimepicker_2" class="input-append date input-group">
                                <input data-format="yyyy-MM-dd hh:mm:ss" class="form-control to_time" readonly type="text" name="to_date[]">
                                <span class="add-on input-group-addon">
                                  <i data-time-icon="fa fa-clock-o" data-date-icon="fa fa-calendar"></i>
                                </span>
                                <script type="text/javascript">
                                    $(function() {
                                        $('#datetimepicker_2').datetimepicker({
                                            language: 'pt-BR'
                                        });
                                        $('#datetimepicker_2').datetimepicker({ dateFormat: 'yyyy-mm-dd hh:ii' }).on('changeDate',function(ev) {
                                            to_time= ev.timeStamp;
                                            var relativeurl = '<?php echo HMS_PLUGIN_URL.'/class/session_duration.php'?>' ;
                                            var data = {'action':'is_over_lapping','from_time':from_time ,'to_time': to_time ,'doctor_id': jQuery('#doctor_id').val() };
                                            $.get(relativeurl, data, function(callbackdata){
                                                if(callbackdata==1)
                                                {
                                                $(".over_lapping_error").html('<font color="red">This session over laps with another session</font>')
                                                }
                                            })
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
                <div class="over_lapping_error"></div>
                <div class="form-group" style="display: none;">
                    <label class="col-sm-2 control-label" for="paid"><?php _e('Spent','hospital_mgt');?></label>
                    <div class="col-sm-8">
                        <input type="checkbox" name="session_spent[]" class="form-control" <?php if($result->session_spent==1)echo "checked"; ?>>
                    </div>
                </div>
             <div class="form-group" style="display: none;">
                    <label class="col-sm-2 control-label" for="paid"><?php _e('Paid','hospital_mgt');?></label>
                        <div class="col-sm-8">
             <input type="checkbox" name="session_paid[]" class="form-control" <?php if($result->session_paid==1)echo "checked"; ?>>
             </div>
             </div>
            </div>
			</div>
			</div>
			<div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	            <div class="col-sm-offset-2 col-sm-8">
        	    <input onclick="submitform()" type="button" value="<?php if($edit){ _e('Save Package','hospital_mgt'); }else{ _e('Add Package','hospital_mgt');}?>" name="save_package" class="btn btn-success"/>
                 </div>
            <script type="application/javascript">
            function submitform(){
            $("#package_form").submit();
            }
            </script>
			</div>
			</div>

            <div class="newsession"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="session_entry"></label>
                <div class="col-sm-3">
                    <button id="add_new_entry" class="btn btn-default btn-sm btn-icon icon-left" type="button" data-toggle="modal" data-target="#sessionmodal" name="add_new_entry"><?php _e('Add Session Entry','hospital_mgt'); ?>
                    </button>
                </div>
            </div>
            <br>

		<div class="col-sm-offset-2 col-sm-8">
        	<input type="submit" value="<?php if($edit){ _e('Save Package','hospital_mgt'); }else{ _e('Add Package','hospital_mgt');}?>" name="save_package" class="btn btn-success"/>
        </div>
        </form>
         </div>
        </div>
     <?php
	 //}
	 ?>	 <?php }?>
	</div>

</div>
<?php ?>