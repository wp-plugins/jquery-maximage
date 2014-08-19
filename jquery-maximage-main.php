<?php

    /*
        Plugin Name: jQuery Maximage
		Description: This Plugin brings the jQuery Maximage 2 Plugin from Aaron Vanderzwan to Wordpress.
        Plugin URI: http://wordpress.jonasspaller.de/plugins/jquery-maximage
        Version: 2.0.2
        Author: Jonas Spaller
        Author URI: http://www.jonasspaller.de
		License: GPLv3
    */

	/* activation hook */

	function jqmi_activate() {
		
		global $wpdb;
		$table_name = $wpdb->prefix.'jquery_maximage';
		
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		id int(11) NULL AUTO_INCREMENT,
		path text NOT NULL,
		UNIQUE KEY id (id)
		);";

		$wpdb->query( $sql);
			
	}

	register_activation_hook( __FILE__, 'jqmi_activate' );

	// make plugin translatable

	function jqmi_textdomain() {
		load_plugin_textdomain ( 'jquery-maximage', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	add_action ( 'plugins_loaded', 'jqmi_textdomain' );

	// load scripts and styles in Frontend

    function jqmi_load_scripts_frontend() {
        
        wp_register_script ( 'jquery.maximage', plugins_url().'/jquery-maximage/js/jquery.maximage.js', false, null, false );
        wp_register_script ( 'jquery.cycle', plugins_url().'/jquery-maximage/js/jquery.cycle.js', false, null, false );
        wp_register_script ( 'jquery.maximage.initiate', plugins_url().'/jquery-maximage/js/jquery.maximage.initiate.js', false, null, false );
        wp_register_style ( 'jquery.maximage', plugins_url().'/jquery-maximage/css/jquery.maximage.css', false, null, false );
        
        wp_enqueue_script ( 'jquery' );
        wp_enqueue_script ( 'jquery.maximage' );
        wp_enqueue_script ( 'jquery.cycle' );
        wp_enqueue_script ( 'jquery.maximage.initiate' );
        wp_enqueue_style ( 'jquery.maximage' );
        
    }

    add_action ( 'wp_enqueue_scripts', 'jqmi_load_scripts_frontend' );

    // add markup on footer

    function jqmi_add_markup_footer() {
        
        echo '<div id="maximage">';
        
		global $wpdb;
		$table_name = $wpdb->prefix.'jquery_maximage';
        $result = $wpdb->get_results ( "SELECT * FROM $table_name" );
        
        foreach ( $result as $row ) {
            echo '<img src="'.$row->path.'" />';
        }
        
        echo '</div>';
        
    }

    add_action ( 'wp_footer', 'jqmi_add_markup_footer' );

    // load scripts and styles in backend

    function jqmi_load_scripts_backend() {
        
        if ( isset ( $_GET['page'] ) && $_GET['page'] == 'jquery-maximage' ) {
            
            wp_register_script ( 'jquery.maximage.upload', plugins_url().'/jquery-maximage/js/jquery.maximage.upload.js', false, null, false );
            wp_register_style ( 'jquery.maximage.adminstyle', plugins_url().'/jquery-maximage/css/jquery.maximage.adminstyle.css', false, null, false );

            wp_enqueue_media();
            wp_enqueue_script ( 'jquery' );
            wp_enqueue_script ( 'jquery.maximage.upload' );
            wp_enqueue_style ( 'jquery.maximage.adminstyle' );
            
        }
        
    }

    add_action ( 'admin_enqueue_scripts', 'jqmi_load_scripts_backend' );

    // add menu point

    function jqmi_add_menu_point() {
        
        add_theme_page ( 'jQuery Maximage', __( 'Background Slideshow', 'jquery-maximage' ), 'manage_options', 'jquery-maximage', 'jqmi_render_site' );
        
    }

    add_action ( 'admin_menu', 'jqmi_add_menu_point' );

    // render plugin site

    function jqmi_render_site() {
        
        if ( isset ( $_GET['action'] ) ) {
			
			if ( $_GET['action'] == 'save-settings' ) {
			
				global $wpdb;
				$table_name = $wpdb->prefix.'jquery_maximage';

				foreach ( $_POST as $key => $value ) {

					if ( empty ( $value ) ) {
						continue;
					}

					$path_id = explode( '-', $key );
					$sql = "Update ".$table_name." SET path = '".$value."' WHERE id = '".$path_id[1]."'";
					$wpdb->query( $sql );

				}

				echo '<div id="message" class="updated">';
				echo '<p><strong>'.__( 'The Slideshow has been updated successfully', 'jquery-maximage' ).'.</strong></p>';
				echo '</div>';
				
			} elseif ( $_GET['action'] == 'delete-pic' ) {
				
				global $wpdb;
				$table_name = $wpdb->prefix.'jquery_maximage';
				$wpdb->delete( $table_name, array( 'id' => $_GET['delete'] ) );
				
			}
            
        }
        
        include_once ( 'jquery-maximage-site.php' );
        
    }

?>