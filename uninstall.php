<?php
	global $wpdb;
	$jqmi_image_paths = $wpdb->prefix.'jqmi_image_paths';
	$jqmi_options = $wpdb->prefix.'jqmi_options';
	@$wpdb->query( "DROP TABLE IF EXISTS $jqmi_image_paths" );
	@$wpdb->query( "DROP TABLE IF EXISTS $jqmi_options" );
?>