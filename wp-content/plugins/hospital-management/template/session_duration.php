<?php
$obj_session_duration = new Hmgt_session_duration();
$obj_session = new Hmgt_session();
if(isset($_REQUEST['save_durations']))
{
	if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
	{
		$result = $obj_session_duration->hmgt_add_session_duration($_POST);
		if($result)
		{
			if($_REQUEST['action'] == 'edit')
			{
				wp_redirect ( home_url().'?dashboard=user&page=session_duration&tab=sessiondurationlist&message=2');
			 }
			else
			{
			wp_redirect ( home_url().'?dashboard=user&page=session_duration&tab=sessiondurationlist&message=1');
			}


		}
	}
}
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
	$result = $obj_session->delete_session($_REQUEST['duration_id']);
	if($result)
	{
			wp_redirect ( home_url().'?dashboard=user&page=session_duration&tab=sessiondurationlist&message=3');
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
$active_tab = isset($_GET['tab'])?$_GET['tab']:'sessiondurationlist';
?>
<script type="text/javascript">
$(document).ready(function() {
	$('#session_form').validationEngine();

} );
</script>
<div class="panel-body panel-white">
 <ul class="nav nav-tabs panel_tabs" role="tablist">
      <li class="<?php if($active_tab == 'sessiondurationlist'){?>active<?php }?>">
          <a href="?dashboard=user&page=session_duration&tab=sessiondurationlist">
             <i class="fa fa-align-justify"></i> <?php _e('Duration List', 'hospital_mgt'); ?></a>
          </a>
      </li>
	  <li class="<?php if($active_tab=='addduratrion'){?>active<?php }?>">
		  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['invoice_id']))
			{?>
			<a href="?dashboard=user&page=session_duration&tab=addduratrion&action=edit&duration_id=<?php if(isset($_REQUEST['duration_id'])) echo $_REQUEST['duration_id'];?>"" class="tab <?php echo $active_tab == 'addduratrion' ? 'active' : ''; ?>">
             <i class="fa fa"></i> <?php _e('Edit session duration', 'hospital_mgt'); ?></a>
			 <?php }
			else
			{?>
				<a href="?dashboard=user&page=session_duration&tab=addduratrion" class="tab <?php echo $active_tab == 'addduratrion' ? 'active' : ''; ?>">
				<i class="fa fa-plus-circle"></i> <?php _e('Add session duration', 'hospital_mgt'); ?></a>
	  <?php } ?>

	</li>

</ul>
	<div class="tab-content">
	<?php if($active_tab == 'sessiondurationlist'){?>

    	 <div class="tab-pane fade active in"  id="eventlist">
         <?php
		 //	$retrieve_class = get_all_data($tablename);
		?>
		<div class="panel-body">
        <div class="table-responsive">
        <table id="hmgt_session" class="display dataTable " cellspacing="0" width="100%">
        	<thead>
            <tr>
			<th><?php _e( 'Session Name', 'hospital_mgt' ) ;?></th>
			<th><?php _e( 'Duration Price', 'hospital_mgt' ) ;?></th>
               <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
             </thead>
		<tfoot>
            <tr>
			<th><?php _e( 'Session Name', 'hospital_mgt' ) ;?></th>
		    <th><?php _e( 'Duration Price', 'hospital_mgt' ) ;?></th>
            <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </tfoot>

        <tbody>
         <?php
		$duration_data=$obj_session_duration->get_all_session_duration();
		 if(!empty($duration_data))
		 {
		 	foreach ($duration_data as $retrieved_data){


		 ?>
            <tr>
				<td class="session_name"><?php echo $retrieved_data->session_name;?></td>
                <td class="duration_price"><?php echo $retrieved_data->duration_price;?></td>
               	<td class="action">
               	<a href="?dashboard=user&page=session_duration&tab=addduratrion&action=edit&duration_id=<?php echo $retrieved_data->duration_id;?>" class="btn btn-info">
               	<?php _e('Edit', 'hospital_mgt' ) ;?></a>
                <a href="?dashboard=user&page=session_duration&tab=sessiondurationlist&action=delete&duration_id=<?php echo $retrieved_data->duration_id;?>" class="btn btn-danger"
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
	 if($active_tab == 'addduratrion'){

		 $edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit = 1;
			$session_id = $_REQUEST['session_id'];
			$result = $obj_session_duration->get_single_session_durations($session_id);
		}
		$sessions =  $obj_session->get_all_session();
?>

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

</div>
<?php } ?>