<?php
//Add Package
$edit=0;
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
{
    $edit = 1;
    $sesssion_id = $_REQUEST['session_id'];
    $result = $obj_package->get_session($session_id);
}
?>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#package_session_form').validationEngine();

        });
    </script>
    <div class="panel-body">
        <form name="package_form" action="" method="post" class="form-horizontal" id="package_form">
            <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
            <input type="hidden" name="action" value="<?php echo $action;?>">
            <input type="hidden" name="session_id" value="<?php if(isset($_REQUEST['sesssion_id'])) echo $_REQUEST['sesssion_id'];?>"  />
            <div class="session_entry">
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="session_id"><?php _e('Session Type','hospital_mgt');?></label>
                    <div class="col-sm-8">
                        <?php
                        $obj_session=new Hmgt_session();
                        $sessions  = $obj_session->get_all_session();
                        hmgt_render_options($sessions,'session',$result->session_id);
                        ?>
                        <script type="text/javascript">
                            jQuery('#session_id').on('change',function(){
                                var relativeurl = '<?php echo HMS_PLUGIN_URL.'/class/session_duration.php'?>' ;
                                var data = {'action':'get_single_session_durations','session_id': jQuery('#session_id').val()};
                                $.get(relativeurl, data, function(callbackdata){
                                    $("#duration_id").html(callbackdata);
                                })
                            });
                        </script>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="duration_id"><?php _e('Duration','hospital_mgt');?></label>
                    <div class="col-sm-8">
                        <select name="duration_id[]" class="form-control validate[required]" id="duration_id">
                            <option value="">Select Session First</option>
                        </select>
                    </div>
                </div>
                <?php if($action=='edit')
                {

                }
                else
                {
                    ?>
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
                    <?php
                }
                ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="paid"><?php _e('Spent','hospital_mgt');?></label>
                    <div class="col-sm-8">
                        <input type="checkbox" name="session_spent[]" class="form-control" <?php if($result->session_spent==1)echo "checked"; ?>>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="paid"><?php _e('Paid','hospital_mgt');?></label>
                    <div class="col-sm-8">
                        <input type="checkbox" name="session_paid[]" class="form-control" <?php if($result->session_paid==1)echo "checked"; ?>>
                    </div>
                </div>
            </div>
            <div class="newsession"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="session_entry"></label>
                <div class="col-sm-3">
                    <button id="add_new_entry" class="btn btn-default btn-sm btn-icon icon-left" type="button"   name="add_new_entry" onclick="add_entry()"><?php _e('Add Session Entry','hospital_mgt'); ?>
                    </button>
                </div>
            </div>
            <div class="col-sm-offset-2 col-sm-8">
                <input type="submit" value="<?php if($edit){ _e('Save Package','hospital_mgt'); }else{ _e('Add Package','hospital_mgt');}?>" name="save_Package" class="btn btn-success"/>
            </div>
        </form>
    </div>
    <script>
        function add_entry()
        {
            var blank_session_entry = $('.session_entry').clone();
            var datepickers = blank_session_entry.find('.datetimepicker');
            $(".newsession").html(blank_session_entry);

            //alert("hellooo");
        }

        function deleteParentElement(n){
            n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
        }

    </script>
<?php
//}
?>