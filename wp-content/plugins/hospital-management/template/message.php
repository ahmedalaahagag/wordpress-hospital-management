<?php 

	$obj_message = new Hmgt_message();
if(isset($_POST['save_message']))
{
	$result = $obj_message->hmgt_add_message($_POST);	
}

						if(isset($result))
						{?>
							<div id="message" class="updated below-h2">
								<p><?php _e('Message Sent Successfully','hospital_mgt');?></p>
							</div>
					<?php 
						}		
	?>
	<?php
$active_tab = isset($_GET['tab'])?$_GET['tab']:'inbox';
	?>


<!--<div id="main-wrapper">-->
<div class="row mailbox-header">
                                <div class="col-md-2">
                                    <a class="btn btn-success btn-block" href="?dashboard=user&page=message&tab=compose"><?php _e('Compose','school-mgt');?></a>
                                </div>
                                <div class="col-md-6">
                                    <h2>
                                    <?php
									if(!isset($_REQUEST['tab']) || ($_REQUEST['tab'] == 'inbox'))
                                    echo esc_html( __( 'Inbox', 'school-mgt' ) );
									else if(isset($_REQUEST['page']) && $_REQUEST['tab'] == 'sentbox')
									echo esc_html( __( 'Sent Item', 'school-mgt' ) );
									else if(isset($_REQUEST['page']) && $_REQUEST['tab'] == 'compose')
										echo esc_html( __( 'Compose', 'school-mgt' ) );
									?>
								
                                    
                                    </h2>
                                </div>
                               
                            </div>
 <div class="col-md-2">
                            <ul class="list-unstyled mailbox-nav">
                                <li <?php if(!isset($_REQUEST['tab']) || ($_REQUEST['tab'] == 'inbox')){?>class="active"<?php }?>>
                                <a href="?dashboard=user&page=message&tab=inbox"><i class="fa fa-inbox"></i> <?php _e('Inbox','school-mgt');?><span class="badge badge-success pull-right"><?php echo count($obj_message->hmgt_count_inbox_item(get_current_user_id()));?></span></a></li>
                                <li <?php if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'sentbox'){?>class="active"<?php }?>><a href="?dashboard=user&page=message&tab=sentbox"><i class="fa fa-sign-out"></i><?php _e('Sent','school-mgt');?></a></li>                                
                            </ul>
                        </div>
 <div class="col-md-10">
 <?php  
 	if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'sentbox')
 		require_once HMS_PLUGIN_DIR. '/template/message/sendbox.php';
 	if(!isset($_REQUEST['tab']) || ($_REQUEST['tab'] == 'inbox'))
 		require_once HMS_PLUGIN_DIR. '/template/message/inbox.php';
 	if(isset($_REQUEST['tab']) && ($_REQUEST['tab'] == 'compose'))
 		require_once HMS_PLUGIN_DIR. '/template//message/composemail.php';
 	if(isset($_REQUEST['tab']) && ($_REQUEST['tab'] == 'view_message'))
 		require_once HMS_PLUGIN_DIR. '/template/message/view_message.php';
 	
 	?>
 </div>
<!--</div> Main-wrapper -->

<?php ?>
 