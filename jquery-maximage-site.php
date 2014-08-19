<div class="wrap">
	
	<h2><?php echo __( 'Background Slideshow', 'jquery-maximage' ).' '.__( ' Options', 'jquery-maximage' ); ?> - jQuery Maximage
	<a href="?page=jquery-maximage&action=add-pic" class="add-new-h2"><?php _e( 'Add new picture', 'jquery-maximage' ); ?></a></h2>
	
	<?php

		if ( isset ( $_GET['action'] ) ) {
			
			if ( $_GET['action'] == 'add-pic' ) {
			
				global $wpdb;
				$table_name = $wpdb->prefix.'jquery_maximage';
				$wpdb->insert( $table_name, array( 'id' => 'NULL' ) );
				
			} elseif ( $_GET['action'] == 'truncate-db' ) {
				
				global $wpdb;
				$table_name = $wpdb->prefix.'jquery_maximage';
				$wpdb->query( "TRUNCATE $table_name" );
				
			}
			
		}

	?>
	
	<form action="?page=jquery-maximage&action=save-settings" method="post">
	
		<?php

			global $wpdb;
			$table_name = $wpdb->prefix.'jquery_maximage';
			$result = $wpdb->get_results( "SELECT * FROM $table_name" );

			if ( $wpdb->num_rows > 0 ) {

				foreach ( $result as $row ) {
					
					echo '<div class="input-container">';
					echo '<a class="red" href="?page=jquery-maximage&action=delete-pic&delete='.$row->id.'">'.__( 'Delete', 'jquery-maximage' ).'</a>';
					echo '<label>'.__( 'Picture', 'jquery-maximage' ).' #'.$row->id.'</label>';
					echo '<input type="text" class="upload-input" placeholder="'.__( 'Image URL', 'jquery-maximage' ).'" name="path-'.$row->id.'" id="i'.$row->id.'" />';
					echo '<button class="upload-button button-secondary" id="'.$row->id.'">'.__( 'Browse', 'jquery-maximage' ).'...</button>';
					
					if ( $row->path !== '' ) {
						echo '<div class="current-pic"><p>'.__( 'Current picture', 'jquery-maximage' ).'</p><img src="'.$row->path.'" /></div>';
					}
					
					echo '</div>';
					
				}

			} else {
				
				echo '<div id="message" class="error"><p><strong>'.__( "There aren't any entries yet.", 'jquery-maximage' ).'</strong></p></div>';
			}

		?>
		
		<a class="red" href="?page=jquery-maximage&action=truncate-db"><?php _e( 'Reset database', 'jquery-maximage' ); ?></a>
		<button type="submit" class="button-primary"><?php _e ( 'Save', 'jquery-maximage' ); ?></button>
		
	</form>

</div>
