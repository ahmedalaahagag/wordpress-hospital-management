<?php
	$obj_invoice= new Hmgtinvoice();

$active_tab = isset($_GET['tab'])?$_GET['tab']:'invoicelist';

	?>

<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
    <div class="modal-content">
    <div class="invoice_data">
     </div>

    </div>
    </div>

</div>
<!-- End POP-UP Code -->
<div class="page-inner" style="min-height:1631px !important">
<div class="page-title">
		<h3><img src="<?php echo get_option( 'hmgt_hospital_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'hmgt_hospital_name' );?></h3>
	</div>
	<?php
	if(isset($_POST['save_invoice']))
	{
		if($_REQUEST['action']=='edit')
		{

			$result=$obj_invoice->hmgt_add_invoice($_POST);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_invoice&tab=invoicelist&message=2');
			}


		}
		else
		{
			$result=$obj_invoice->hmgt_add_invoice($_POST);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_invoice&tab=invoicelist&message=1');
			}
		}
	}


	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
		if(isset($_REQUEST['invoice_id'])){
			$result=$obj_invoice->delete_invoice($_REQUEST['invoice_id']);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_invoice&tab=invoicelist&message=3');
			}
		}
		if(isset($_REQUEST['income_id'])){
			$result=$obj_invoice->delete_income($_REQUEST['income_id']);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_invoice&tab=incomelist&message=3');
			}
		}
		if(isset($_REQUEST['expense_id'])){
			$result=$obj_invoice->delete_expense($_REQUEST['expense_id']);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_invoice&tab=expenselist&message=3');
			}
		}

	}
	//--------save income-------------
	if(isset($_POST['save_income']))
	{

		if($_REQUEST['action']=='edit')
		{

			$result=$obj_invoice->add_income($_POST);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_invoice&tab=incomelist&message=2');
			}
		}
		else
		{
			$result=$obj_invoice->add_income($_POST);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_invoice&tab=incomelist&message=1');
			}
		}

	}

	//--------save Expense-------------
	if(isset($_POST['save_expense']))
	{

		if($_REQUEST['action']=='edit')
		{

			$result=$obj_invoice->add_expense($_POST);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_invoice&tab=expenselist&message=2');
			}
		}
		else
		{
			$result=$obj_invoice->add_expense($_POST);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_invoice&tab=expenselist&message=1');
			}
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
	?>
	<div id="main-wrapper">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-white">
					<div class="panel-body">
	<h2 class="nav-tab-wrapper">
			<a href="?page=hmgt_invoice&tab=addinvoice" class="nav-tab active <?php echo $active_tab == 'addinvoice' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Create Invoice', 'hospital_mgt'); ?></a>
    </h2>
     <?php
	//Report 1
	if($active_tab == 'invoicelist')
	{

	?>
     <script type="text/javascript">
$(document).ready(function() {
	jQuery('#tblinvoice').DataTable({

		 "aoColumns":[
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": false}
	               ]
		});


} );
</script>
    <form name="wcwm_report" action="" method="post">

        <div class="panel-body">
        	<div class="table-responsive">
        <table id="tblinvoice" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>
			<th><?php _e( 'Title', 'hospital_mgt' ) ;?></th>
			  <th> <?php _e( 'Patient', 'hospital_mgt' ) ;?></th>
				<th> <?php _e( 'Vat Percentage', 'hospital_mgt' ) ;?></th>
				<th> <?php _e( 'Discount Amount', 'hospital_mgt' ) ;?></th>
				<th> <?php _e( 'Status', 'hospital_mgt' ) ;?></th>
                <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </thead>
		<tfoot>
            <tr>
			<th><?php _e( 'Title', 'hospital_mgt' ) ;?></th>
			  <th> <?php _e( 'Patient', 'hospital_mgt' ) ;?></th>
				<th> <?php _e( 'Vat Percentage', 'hospital_mgt' ) ;?></th>
				<th> <?php _e( 'Discount Amount', 'hospital_mgt' ) ;?></th>
				<th> <?php _e( 'Status', 'hospital_mgt' ) ;?></th>
                <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
            </tr>
        </tfoot>

        <tbody>
         <?php

		 	foreach ($obj_invoice->get_all_invoice_data() as $retrieved_data){  ?>
            <tr>
			<td class="title"><a href="?page=hmgt_invoice&tab=addinvoice&action=edit&invoice_id=<?php echo $retrieved_data->invoice_id;?>"><?php echo $retrieved_data->invoice_title; ?></a></td>
                <td class="patient"><?php echo $patient_id=get_user_meta($retrieved_data->patient_id, 'patient_id', true);?></td>
				<td class="vat_percentage"><?php echo $retrieved_data->vat_percentage;?></td>
				<td class="discount"><?php echo $retrieved_data->discount;?></td>
                <td class="email"><?php echo $retrieved_data->status;?></td>

               	<td class="action">
				<a  href="#" class="show-invoice-popup btn btn-default" idtest="<?php echo $retrieved_data->invoice_id; ?>" invoice_type="invoice">
				<i class="fa fa-eye"></i> <?php _e('View Invoice', 'hospital_mgt');?></a>
				<a href="?page=hmgt_invoice&tab=addinvoice&action=edit&invoice_id=<?php echo $retrieved_data->invoice_id;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
                <a href="?page=hmgt_invoice&tab=invoicelist&action=delete&invoice_id=<?php echo $retrieved_data->invoice_id;?>" class="btn btn-danger"
                onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
                <?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>

                </td>

            </tr>
            <?php }

		?>

        </tbody>

        </table>
        </div>
        </div>

</form>
     <?php
	 }

	if($active_tab == 'addinvoice')
	 {
	require_once HMS_PLUGIN_DIR. '/admin/includes/invoice/add_invoice.php';
	 }
	 if($active_tab == 'incomelist')
	 {
	require_once HMS_PLUGIN_DIR. '/admin/includes/invoice/income-list.php';
	 }
	 if($active_tab == 'addincome')
	 {
	require_once HMS_PLUGIN_DIR. '/admin/includes/invoice/add_income.php';
	 }
	 if($active_tab == 'expenselist')
	 {
	require_once HMS_PLUGIN_DIR. '/admin/includes/invoice/expense-list.php';
	 }
	 if($active_tab == 'addexpense')
	 {
	require_once HMS_PLUGIN_DIR. '/admin/includes/invoice/add_expense.php';
	 }
	 ?>
</div>

	</div>
	</div>
</div>

<?php ?>
