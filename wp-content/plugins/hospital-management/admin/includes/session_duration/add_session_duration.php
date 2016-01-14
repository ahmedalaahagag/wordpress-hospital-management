<?php
//Add Session
$edit=0;
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
{
	$edit = 1;
	$session_id = $_REQUEST['session_id'];
	$durations = $obj_session_duration->get_single_session_durations($session_id);
}
$sessions = $obj_session->get_all_session();
?>

	<script type="text/javascript">
$(document).ready(function() {
	$('#duration_form').validationEngine();

});
</script>
       <div class="panel-body">
        <form name="duration_form" action="" method="post" class="form-horizontal" id="duration_form">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="session_id" value="<?php if(isset($_REQUEST['session_id'])) echo $_REQUEST['session_id'];?>"  />
		<div class="form-group">
			<label class="col-sm-2 control-label" for="med_category_name"><?php _e('Session ','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<?php
				if($edit)
				{$selected=$durations[0]->session_id;}
				else
				{$selected=0;}
				hmgt_render_options($sessions,'session',$selected);
				?>
			</div>
		</div>
		<?php
			if(!empty($durations))
			{
			foreach($durations as $duration){
			?>
			<div id="duration_entry">
				<div class="form-group">
					<label class="col-sm-2 control-label" for="duraiton_entry"><?php _e('Duration Entry','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-2">
						<input id="duration_time" class="form-control validate[required] text-input" type="text" value="<?php echo $duration->duration_time;?>" name="duration_time[]" >
					</div>
					<div class="col-sm-4">
						<input id="duraiton_entry" class="form-control validate[required] text-input" type="text" value="<?php echo $duration->duration_price;?>" name="duration_price[]">
					</div>

					<div class="col-sm-2">
						<button type="button" class="btn btn-default" onclick="deleteParentElement(this)">
							<i class="entypo-trash"><?php _e('Delete','hospital_mgt');?></i>
						</button>
					</div>
				</div>
			</div>
			<?php }
			}
			else
			{?>
				<div id="duration_entry">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="income_entry"><?php _e('Duration Entry','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-2">
							<input id="duration_time" class="form-control validate[required] text-input" type="text" value="" name="duration_time[]" placeholder="Duration time (minutes)">
						</div>
						<div class="col-sm-4">
							<input id="duration_price" class="form-control validate[required] text-input" type="text" value="" name="duration_price[]" placeholder="Duration price (LE)">
						</div>

						<div class="col-sm-2">
							<button type="button" class="btn btn-default" onclick="deleteParentElement(this)">
								<i class="entypo-trash"><?php _e('Delete','hospital_mgt');?></i>
							</button>
						</div>
					</div>
				</div>
			<?php }?>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="duration_entry"></label>
				<div class="col-sm-3">
					<button id="add_new_entry" class="btn btn-default btn-sm btn-icon icon-left" type="button"   name="add_new_entry" onclick="add_entry()"><?php _e('Add Duration Entry','hospital_mgt'); ?>
					</button>
				</div>
			</div>
			<div class="col-sm-offset-2 col-sm-8">
        	<input type="submit" value="<?php if($edit){ _e('Save Durations','hospital_mgt'); }else{ _e('Add Durations','hospital_mgt');}?>" name="save_durations" class="btn btn-success"/>
        </div>
        </form>
        </div>
	<script>

		function add_entry()
		{
            var blank_duration_entry = $('#duration_entry').last().clone();
            blank_duration_entry.find('input').val('').html();
            $("#duration_entry").last().after(blank_duration_entry);
			//alert("hellooo");
		}

		function deleteParentElement(n){
			n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
		}
	</script>
<?php
	 //}
	 ?>