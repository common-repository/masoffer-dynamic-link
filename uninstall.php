<?php
	//if uninstall not called from WordPress exit
	if (!defined('WP_UNINSTALL_PLUGIN'))
		exit();
	
	// delete option from options table
	delete_option('masoffer_dl_publisher_id');
?>