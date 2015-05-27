<?php

    /*
        Plugin Name: jQuery Maximage
	Description: This Plugin brings the jQuery Maximage 2 Plugin from Aaron Vanderzwan to Wordpress.
        Plugin URI: http://jonasspaller.de/jquery-maximage
        Version: 2.1.0
        Author: Jonas Spaller
        Author URI: http://www.jonasspaller.de
		License: GPLv3
    */

	/* create tables for jQuery Maximage */

	function jqmi_create_tables() {

		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		require_once( ABSPATH.'wp-admin/includes/upgrade.php' );

		$jqmi_image_paths = $wpdb->prefix.'jqmi_image_paths';
		$jqmi_options = $wpdb->prefix.'jqmi_options';

		// wp_jqmi_image_paths

		$jqmi_sql_one = "CREATE TABLE $jqmi_image_paths (
			id int(11) NULL AUTO_INCREMENT,
			path text NOT NULL,
			UNIQUE KEY id (id)
		) $charset_collate";

		dbDelta( $jqmi_sql_one );

		// wp_jqmi_options

		$jqmi_sql_two = "CREATE TABLE $jqmi_options (
			jqmi_option varchar(55) DEFAULT '' NOT NULL,
			jqmi_value varchar(55) DEFAULT '' NOT NULL,
			UNIQUE KEY jqmi_option (jqmi_option)
		) $charset_collate";

		dbDelta( $jqmi_sql_two );
		
		/* initial information for wp_jqmi_options */

		$wpdb->query("INSERT INTO $jqmi_options
            (jqmi_option, jqmi_value)
            VALUES
            ('effect', 'fade'),
            ('speed', '3000'),
            ('duration', '2000')");
		
		/* check for old wp_jquery_maximage_table */
		
		global $wpdb;
		$jqmi_old_table = $wpdb->prefix.'jquery_maximage';
		
		if ( !($wpdb->get_var("SHOW TABLES LIKE '$jqmi_old_table'") != $jqmi_old_table) ) {
			$wpdb->query( "INSERT INTO $jqmi_image_paths SELECT * FROM $jqmi_old_table" );
			$wpdb->query( "DROP TABLE IF EXISTS $jqmi_old_table" );
		}

	}
	
	register_activation_hook( __FILE__, 'jqmi_create_tables' );

	// make plugin translatable

	function jqmi_textdomain() {
		load_plugin_textdomain ( 'jqmi', false, dirname( plugin_basename( __FILE__ ) ).'/languages/' );
	}

	add_action ( 'init', 'jqmi_textdomain' );

    // add menu point

    function jqmi_add_menu_point() {
        
        add_theme_page ( 'jQuery Maximage', __( 'Background Slideshow', 'jqmi' ), 'manage_options', 'jquery-maximage', 'jqmi_render_backend' );
        
    }

    add_action ( 'admin_menu', 'jqmi_add_menu_point' );

	// load scripts and styles in Frontend

    function jqmi_load_scripts_frontend() {
        
        wp_register_script ( 'jquery.maximage', plugins_url().'/jquery-maximage/js/jquery.maximage.js', false, null, false );
        wp_register_script ( 'jquery.cycle', plugins_url().'/jquery-maximage/js/jquery.cycle.js', false, null, false );
        wp_register_script ( 'jqmi.initiate', plugins_url().'/jquery-maximage/js/jqmi.initiate.php', false, null, false );
        wp_register_style ( 'jquery.maximage', plugins_url().'/jquery-maximage/css/jquery-maximage.css', false, null, false );
        
        wp_enqueue_script ( 'jquery' );
        wp_enqueue_script ( 'jquery.maximage' );
        wp_enqueue_script ( 'jquery.cycle' );
        wp_enqueue_script ( 'jqmi.initiate' );
        wp_enqueue_style ( 'jquery.maximage' );
        
    }

	add_action ( 'wp_enqueue_scripts', 'jqmi_load_scripts_frontend' );

    // load scripts and styles in backend

    function jqmi_load_scripts_backend() {
        
        if ( isset ( $_GET['page'] ) && $_GET['page'] == 'jquery-maximage' ) {
            
            wp_register_script ( 'jqmi.jquery.main', plugins_url().'/jquery-maximage/js/jqmi.jquery.main.js', false, null, false );
            wp_register_style ( 'jqmi.adminstyle', plugins_url().'/jquery-maximage/css/jqmi-adminstyle.css', false, null, false );

            wp_enqueue_media();
            wp_enqueue_script ( 'jquery' );
            wp_enqueue_script ( 'jqmi.jquery.main' );
            wp_enqueue_style ( 'jqmi.adminstyle' );
            
        }
        
    }

	add_action ( 'admin_enqueue_scripts', 'jqmi_load_scripts_backend' );

    // load main plugin site

    include_once( 'jqmi-backend.php');

	// add markup in frontend

	function jqmi_add_markup_footer() {

		global $wpdb;
		$jqmi_options = $wpdb->prefix.'jqmi_options';
		$jqmi_effect = $wpdb->get_var( "SELECT jqmi_value FROM $jqmi_options WHERE jqmi_option = 'effect'" );
		$jqmi_speed = $wpdb->get_var( "SELECT jqmi_value FROM $jqmi_options WHERE jqmi_option = 'speed'" );
		$jqmi_duration = $wpdb->get_var( "SELECT jqmi_value FROM $jqmi_options WHERE jqmi_option = 'duration'" );

?>
	
		<script type="text/javascript">	
			jQuery(document).ready(function ($) {
				$('div#maximage').maximage({
					cycleOptions: {
						fx: '<?php echo $jqmi_effect; ?>',
						speed: '<?php echo $jqmi_speed; ?>',
						timeout: '<?php echo $jqmi_duration; ?>',
					}
				});
			});
		</script>

		<div id="maximage">
			<?php
        			global $wpdb;
				$jqmi_image_paths = $wpdb->prefix.'jqmi_image_paths';
        			$result = $wpdb->get_results ( "SELECT * FROM $jqmi_image_paths" );
        
        			foreach ( $result as $row ) {
            				echo '<img src="'.$row->path.'" />';
        			}
			?>
		</div>

<?php

	}

	add_action ( 'wp_footer', 'jqmi_add_markup_footer' );
    
?>