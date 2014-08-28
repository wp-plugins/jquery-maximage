<?php
	global $wpdb;
	$table_name = $wpdb->prefix.'jquery_maximage';
	@$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
?>