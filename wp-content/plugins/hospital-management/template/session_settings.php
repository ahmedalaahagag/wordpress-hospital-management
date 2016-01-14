<?php
$obj_session = new Hmgt_session();
if(isset($_REQUEST['save_session_setting']))
{

	if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
	{

		$result = $obj_session->hmgt_add_session($_POST);
		if($result)
		{
			if($_REQUEST['action'] == 'edit')
			{
				wp_redirect ( home_url().'?dashboard=user&page=session_settings&tab=seesionsettingslist&message=2');
			 }
			else
			{
			wp_redirect ( home_url().'?dashboard=user&page=session_settings&tab=seesionsettingslist&message=1');
			}
    	}
	}
}
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
	$result = $obj_session->delete_session($_REQUEST['session_settings_id']);
	if($result)
	{
			wp_redirect ( home_url().'?dashboard=user&page=session_settings&tab=seesionsettingslist&message=3');
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
$active_tab = isset($_GET['tab'])?$_GET['tab']:'seesionsettingslist';
?>
<script type="text/javascript">
$(document).ready(function() {
	$('#session_settings_form').validationEngine();

} );
</script>

<div class="panel-body panel-white">
 <ul class="nav nav-tabs panel_tabs" role="tablist">
      <li class="<?php if($active_tab == 'seesionsettingslist'){?>active<?php }?>">
          <a href="?dashboard=user&page=session_settings&tab=seesionsettingslist">
             <i class="fa fa-align-justify"></i> <?php _e('Session Settings List', 'hospital_mgt'); ?></a>
          </a>
      </li>
	  <li class="<?php if($active_tab=='addsessionsettings'){?>active<?php }?>">
		  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['seesion_settings_id']))
			{?>
			<a href="?dashboard=user&page=session_settings&tab=addsessionsettings&action=edit&package_id=<?php if(isset($_REQUEST['seesion_settings_id'])) echo $_REQUEST['seesion_settings_id'];?>"" class="tab <?php echo $active_tab == 'addsessionsettings' ? 'active' : ''; ?>">
             <i class="fa fa"></i> <?php _e('Edit Session', 'hospital_mgt'); ?></a>
			 <?php }
			else
			{?>
				<a href="?dashboard=user&page=session_settings&tab=addsessionsettings" class="tab <?php echo $active_tab == 'addsessionsettings' ? 'active' : ''; ?>">
				<i class="fa fa-plus-circle"></i> <?php _e('Add Session Settings', 'hospital_mgt'); ?></a>
	  <?php } ?>

	</li>

</ul>
	<div class="tab-content">
	<?php if($active_tab == 'seesionsettingslist'){?>
    	 <div class="tab-pane fade active in"  id="eventlist">
         <?php
		 //	$retrieve_class = get_all_data($tablename);
		?>
		<div class="panel-body">
        <div class="table-responsive">
        <table id="hmgt_package" class="display dataTable " cellspacing="0" width="100%">
        	<thead>
            <tr>
			<th><?php _e( 'Session Name', 'hospital_mgt' ) ;?></th>
               <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
             </thead>
		<tfoot>
            <tr>
			<th><?php _e( 'Session Name', 'hospital_mgt' ) ;?></th>
                <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </tfoot>

        <tbody>
         <?php
		$seesion_data=$obj_session->get_all_session();
		 if(!empty($seesion_data))
		 {
		 	foreach ($seesion_data as $retrieved_data){
		 ?>
            <tr>
               <td class="session_name"><?php echo $retrieved_data->session_name;?></td>
               	<td class="action">
               	<a href="?dashboard=user&page=session_settings&tab=addsessionsettings&action=edit&session_settings_id=<?php echo $retrieved_data->session_id;?>" class="btn btn-info">
               	<?php _e('Edit', 'hospital_mgt' ) ;?></a>
                <a href="?dashboard=user&page=session_settings&tab=addsessionsettings&action=delete&session_settings_id=<?php echo $retrieved_data->session_id;?>" class="btn btn-danger"
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
	 if($active_tab == 'addsessionsettings'){
		 $edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
				$edit = 1;
                $session_id = $_REQUEST['session_settings_id'];
                $result = $obj_session->get_single_session($session_id);
		}
?>

	<div class="panel-body">
       <?php
       $relativeurl = admin_url().'?dashboard=user&page=session_settings&tab=addsessionsettings&action=edit';
        ?>
        <?php
//Add Session
$edit=0;
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
{
	$edit = 1;
	$session_id = $_REQUEST['session_settings_id'];
	$result = $obj_session->get_single_session($session_id);
}
?>
<script type="text/javascript">
$(document).ready(function() {
	$('#session_form').validationEngine();

});
</script>
       <div class="panel-body">
        <form name="session_form" action="" method="post" class="form-horizontal" id="session_form">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="session_id" value="<?php if(isset($_REQUEST['session_id'])) echo $_REQUEST['session_id'];?>"  />

		<div class="form-group">
			<label class="col-sm-2 control-label" for="med_category_name"><?php _e('Session Name','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="session_name" class="form-control validate[required,custom[onlyLetterSp]] text-input" type="text"
				value="<?php if($edit){ echo $result->session_name;}elseif(isset($_POST['session_name'])) echo $_POST['session_name'];?>" name="session_name">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="session_price"><?php _e('Session Type','hospital_mgt');?></label>
			<div class="col-sm-8">
				<select class="form-control" name="session_type">
					<?php
					$session_types_item ['id']= '1';
					$session_types_item ['value']= 'Assessment';
					$session_types [] =$session_types_item;
					$session_types_item ['id']= '2';
					$session_types_item ['value']= 'Treatment';
					$session_types [] =$session_types_item;
					foreach($session_types as $seesion_type)
					{
						if($result->session_type == $seesion_type['id']){
						echo"<option value='".$seesion_type['id']."'selected=selected>".$seesion_type['value']."</option>";
						}
						else{
							echo"<option value='".$seesion_type['id']."'>".$seesion_type['value']."</option>";
						}
					}
					?>
				</select>
			</div>
		</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="discount_sessions_number"><?php _e('Discount After ','hospital_mgt');?><span class="require-field"></span></label>
				<div class="col-sm-8">
					<input id="discount_sessions_number" class="form-control validate[custom[integer],min[1]] text-input" type="text" value="<?php if($edit){ echo $result->discount_sessions_number;}elseif(isset($_POST['discount_sessions_number'])) echo $_POST['discount_sessions_number'];?>" name="discount_sessions_number">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="discount_sessions_percentage"><?php _e('Discount Percentage','hospital_mgt');?><span class="require-field"></span></label>
				<div class="col-sm-8">
					<input id="discount_sessions_percentage" class="form-control validate[custom[integer],max[100],min[0]] text-input" type="text" value="<?php if($edit){ echo $result->discount_sessions_percentage;}elseif(isset($_POST['discount_sessions_percentage'])) echo $_POST['discount_sessions_percentage'];?>" name="discount_sessions_percentage">
				</div>
			</div>
		<div class="col-sm-offset-2 col-sm-8">
        	<input type="submit" value="<?php if($edit){ _e('Save Session','hospital_mgt'); }else{ _e('Add Session','hospital_mgt');}?>" name="save_session_setting" class="btn btn-success"/>
        </div>
        </form>
        </div>
     <?php
	 //}
	 ?>
         </div>
        </div>
	 <?php }?>
	</div>

</div>
<?php ?>