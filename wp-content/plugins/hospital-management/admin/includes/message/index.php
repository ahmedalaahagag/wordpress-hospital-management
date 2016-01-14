<?php 
	// This is Class at admin side!!!!!!!!! 
	$obj_message = new Hmgt_message();

$active_tab = isset($_GET['tab'])?$_GET['tab']:'inbox';
	?>
<div class="page-inner" style="min-height:1631px !important">
<div class="page-title">
		<h3><img src="<?php echo get_option( 'hmgt_hospital_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'hmgt_hospital_name' );?></h3>
	</div>
	<?php 
	if(isset($_POST['save_message']))
	{
		$result = $obj_message->hmgt_add_message($_POST);
	}
	
	if(isset($result))
	{
		wp_redirect ( admin_url() . 'admin.php?page=hmgt_message&tab=inbox&message=1');
	}
	if(isset($_REQUEST['message']))
	{
		$message =$_REQUEST['message'];
		if($message == 1)
		{?>
				<div id="message" class="updated below-h2 ">
				<p>
				<?php 
					_e('Message sent successfully','hospital_mgt');
				?></p></div>
				<?php 
			
		}
		elseif($message == 2)
		{?><div id="message" class="updated below-h2 "><p><?php
					_e("Message deleted successfully",'hospital_mgt');
					?></p>
					</div>
				<?php 
			
		}
	}	
	?>
<div id="main-wrapper">
<div class="row mailbox-header">
                                <div class="col-md-2">
                                    <a class="btn btn-success btn-block" href="?page=hmgt_message&tab=compose"><?php _e('Compose','hospital_mgt');?></a>
                                </div>
                                <div class="col-md-6">
                                    <h2>
                                    <?php
									if(!isset($_REQUEST['tab']) || ($_REQUEST['tab'] == 'inbox'))
                                    echo esc_html( __( 'Inbox', 'hospital_mgt' ) );
									else if(isset($_REQUEST['page']) && $_REQUEST['tab'] == 'sentbox')
									echo esc_html( __( 'Sent Item', 'hospital_mgt' ) );
									else if(isset($_REQUEST['page']) && $_REQUEST['tab'] == 'compose')
										echo esc_html( __( 'Compose', 'hospital_mgt' ) );
									?>
								</h2>
                                </div>
                               
                            </div>
 <div class="col-md-2">
                            <ul class="list-unstyled mailbox-nav">
                                <li <?php if(!isset($_REQUEST['tab']) || ($_REQUEST['tab'] == 'inbox')){?>class="active"<?php }?>>
                                <a href="?page=hmgt_message&tab=inbox"><i class="fa fa-inbox"></i> <?php _e('Inbox','hospital_mgt');?><span class="badge badge-success pull-right"><?php echo count($obj_message->hmgt_count_inbox_item(get_current_user_id()));?></span></a></li>
                                <li <?php if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'sentbox'){?>class="active"<?php }?>><a href="?page=hmgt_message&tab=sentbox"><i class="fa fa-sign-out"></i><?php _e('Sent','hospital_mgt');?></a></li>                                
                            </ul>
                        </div>
 <div class="col-md-10">
 <?php  
 	if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'sentbox')
 		require_once HMS_PLUGIN_DIR. '/admin/includes/message/sendbox.php';
 	if(!isset($_REQUEST['tab']) || ($_REQUEST['tab'] == 'inbox'))
 		require_once HMS_PLUGIN_DIR. '/admin/includes/message/inbox.php';
 	if(isset($_REQUEST['tab']) && ($_REQUEST['tab'] == 'compose'))
 		require_once HMS_PLUGIN_DIR. '/admin/includes/message/composemail.php';
 	if(isset($_REQUEST['tab']) && ($_REQUEST['tab'] == 'view_message'))
 		require_once HMS_PLUGIN_DIR. '/admin/includes/message/view_message.php';
 	
 	?>
 </div>
</div><!-- Main-wrapper -->
</div><!-- Page-inner -->
<?php ?>