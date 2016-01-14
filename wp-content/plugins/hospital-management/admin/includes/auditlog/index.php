<?php 
if(isset($_REQUEST['clear_log']))
{
	$path = $_REQUEST['path'];
	//open file to write
	$fp = fopen($path, "r+");
	// clear content to 0 bits
	ftruncate($fp, 0);
	//close file
	fclose($fp);
}
?>
<div class="page-inner" style="min-height:1631px !important">
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'hmgt_hospital_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'hmgt_hospital_name' );?></h3>
	</div>
	<div  id="main-wrapper" class="marks_list">
	<div class="panel panel-white">
		<div class="panel-body">  

			<h2 class="nav-tab-wrapper">
		    	<a href="?page=hmgt_audit_log" class="nav-tab  nav-tab-active">
				<?php echo '<span class="dashicons dashicons-welcome-view-site"></span> '.__('Audit Log/Activity', 'hospital_mgt'); ?></a>
		        
		    </h2>
		    <div class="panel-body">
		     <form name="bed_form" action="" method="post" class="form-horizontal" id="bed_form">
		     <div class="audit_button">
        	<?php echo '<a href="'.HMS_PLUGIN_URL.'/download_log.php?mime=hmgt_log.txt&title=audit_log.txt&token='.HMS_LOG_file.'" class="btn btn-success">Download Log</a>'; 
        //	echo '<a href="'.HMS_PLUGIN_URL.'/clear_log.php?token='.HMS_LOG_file.'" class="btn btn-success"><i class="fa fa-download"></i> Clear Log</a>';
        	?>
        	<input type="hidden" name="path" value = "<?php echo HMS_LOG_file;?>" >
        	<input type="submit" value="<?php _e('Clear Log','hospital_mgt');?>" name="clear_log" class="btn btn-success"/>
        	</div>
		    <div class="aduit_log_file">
				<?php 
				//$current = file_get_contents(HMS_LOG_file);
				//echo $current;
				//$lines = explode("\n", file_get_contents(HMS_LOG_file));
				//var_dump($lines);
				//echo HMS_LOG_file;
				if (file_exists(HMS_LOG_file)) 
				{
					foreach(file(HMS_LOG_file) as $line) {
						echo "<P>".$line. "<P>";
					}
				}
				else 	
					echo "No any Log found";
				?>
			</div>
			 <div class="audit_button">
        	<?php echo '<a href="'.HMS_PLUGIN_URL.'/download_log.php?mime=hmgt_log.txt&title=audit_log.txt&token='.HMS_LOG_file.'" class="btn btn-success">Download Log</a>'; 
        //	echo '<a href="'.HMS_PLUGIN_URL.'/clear_log.php?token='.HMS_LOG_file.'" class="btn btn-success"><i class="fa fa-download"></i> Clear Log</a>';
        	?>
        	<input type="hidden" name="path" value = "<?php echo HMS_LOG_file;?>" >
        	<input type="submit" value="<?php _e('Clear Log','hospital_mgt');?>" name="clear_log" class="btn btn-success"/>
        	</div>
			</form>
			
		</div>
		</div>
		
	</div>
	
</div>
</div>

