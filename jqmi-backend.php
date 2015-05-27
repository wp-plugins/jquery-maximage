<?php

	global $wpdb;
	$jqmi_image_paths = $wpdb->prefix.'jqmi_image_paths';
	$jqmi_options = $wpdb->prefix.'jqmi_options';

	if ( isset( $_GET['action'] ) ) {
		
		$action = $_GET['action'];
			
		if ( $action == 'add-image' ) {

			$wpdb->insert( $jqmi_image_paths, array( 'id' => 'NULL' ) );

		} elseif ( $action == 'save-settings-paths' ) {
			
			foreach ( $_POST as $key => $value ) {

				if ( empty ( $value ) ) {
					continue;
				}

				$path_id = explode( '-', $key );
				$sql = "Update ".$jqmi_image_paths." SET path = '".$value."' WHERE id = '".$path_id[1]."'";
				$wpdb->query( $sql );

			}

			echo '<div id="message" class="updated">';
			echo '<p><strong>'.__( 'The Slideshow has been updated successfully', 'jqmi' ).'.</strong></p>';
			echo '</div>';
			
		} elseif ( $action == 'delete-image' ) {
			
			$wpdb->delete( $jqmi_image_paths, array( 'id' => $_GET['delete'] ) );
			
		} elseif ( $action == 'truncate-db' ) {

			$wpdb->query( "TRUNCATE $jqmi_image_paths" );

		} elseif ( $action == 'save-settings' ) {
			
			foreach ( $_POST as $key => $value ) {
				
				if ( empty( $value ) || $value == ' ' ) {
					continue;
				}
				
				$sql = "Update ".$jqmi_options." SET jqmi_value = '".$value."' WHERE jqmi_option = '".$key."'";
				$wpdb->query( $sql );
					
			}
			
			echo '<div id="message" class="updated">';
			echo '<p><strong>'.__( 'Settings updated successfully', 'jqmi' ).'.</strong></p>';
			echo '</div>';
			
		}

	}

	function jqmi_render_backend() { ?>

	<div class="wrap">

		<h2>
			<?php echo __( 'Background Slideshow', 'jqmi' ).' - '.__( 'jQuery Maximage', 'jqmi' ); ?>
			<a href="?page=jquery-maximage&action=add-image" class="add-new-h2"><?php _e( 'Add new image', 'jqmi' ); ?></a>
		</h2>

		<h2 class="nav-tab-wrapper">
			<a class="nav-tab nav-tab-active" href="#image-paths"><?php _e( 'Images', 'jqmi' ); ?></a>
			<a class="nav-tab" href="#settings"><?php _e( 'Settings', 'jqmi' ); ?></a>
		</h2>

		<div class="jqmi-wrapper">

			<div class="jqmi-sections" id="image-paths">

				<form action="?page=jquery-maximage&action=save-settings-paths" method="post">

					<?php

						global $wpdb;
						$jqmi_image_paths = $wpdb->prefix.'jqmi_image_paths';
						$result = $wpdb->get_results( "SELECT * FROM $jqmi_image_paths" );

						if ( $wpdb->num_rows > 0 ) {

							foreach ( $result as $row ) {

								echo '<div class="input-container">';
								echo '<a class="red" href="?page=jquery-maximage&action=delete-image&delete='.$row->id.'">'.__( 'Delete', 'jqmi' ).'</a>';
								echo '<label>'.__( 'Image', 'jqmi' ).' #'.$row->id.'</label>';
								echo '<input type="text" class="upload-input" placeholder="'.__( 'Image URL', 'jqmi' ).'" name="path-'.$row->id.'" id="i'.$row->id.'" />';
								echo '<button class="upload-button button-secondary" id="'.$row->id.'">'.__( 'Browse', 'jqmi' ).'...</button>';

								if ( $row->path !== '' ) {
									echo '<div class="current-pic"><p>'.__( 'Current Image', 'jqmi' ).'</p><img src="'.$row->path.'" /></div>';
								}

								echo '</div>';

							}

						} else {

							echo '<div id="message" class="error"><p><strong>'.__( "There aren't any entries yet.", 'jqmi' ).'</strong></p></div>';
						}

					?>

					<a class="red" href="?page=jquery-maximage&action=truncate-db"><?php _e( 'Delete all', 'jqmi' ); ?></a>

					<button type="submit" class="button-primary"><?php _e ( 'Save', 'jqmi' ); ?></button>

				</form>

			</div>

			<div class="jqmi-sections" class="jqmi-sites" id="settings">
				
				<form action="?page=jquery-maximage&action=save-settings" method="post" id="jqmi-settings-form">

					<div id="jqmi-appearance">

						<h2><?php _e( 'Appearance', 'jqmi' ); ?></h2>

						<table class="settings-table appearance">

<!--
							<tr>
								<td><label class="setting-label"><?php _e( 'Effect', 'jqmi' ); ?>: </label></td>
								<td>
									<select name="effect" disabled>
										<option value=" " selected><?php _e( 'Please choose', 'jqmi' ); ?></option>
										<option value="fade"><?php _e( 'Fade', 'jqmi' ); ?></option>
									</select>
									<?php
										global $wpdb;
										$jqmi_options = $wpdb->prefix.'jqmi_options';
										$jqmi_effect = $wpdb->get_var( "SELECT jqmi_value FROM $jqmi_options WHERE jqmi_option = 'effect'" );
									?>
									<label class="setting-label"><?php _e( 'Current value', 'jqmi' ); ?>: <?php echo $jqmi_effect; ?></label>
								</td>
							</tr>
-->
							<tr>
								<td><label class="setting-label"><?php _e( 'Speed', 'jqmi' ); ?>: </label></td>
								<td>
									<?php
										global $wpdb;
										$jqmi_options = $wpdb->prefix.'jqmi_options';
										$jqmi_speed = $wpdb->get_var( "SELECT jqmi_value FROM $jqmi_options WHERE jqmi_option = 'speed'" );
									?>
									<input placeholder="<?php echo __( 'Current value', 'jqmi' ).': '.$jqmi_speed; ?>" type="number" name="speed">
									<label class="setting-label"><?php _e( 'miliseconds', 'jqmi' ); ?></label>
								</td>
							</tr>
							<tr>
								<td><label class="setting-label"><?php _e( 'Time between slides', 'jqmi' ); ?>:</label></td>
								<td>
									<?php
										global $wpdb;
										$jqmi_options = $wpdb->prefix.'jqmi_options';
										$jqmi_duration = $wpdb->get_var( "SELECT jqmi_value FROM $jqmi_options WHERE jqmi_option = 'duration'" );
									?>
									<input type="number" placeholder="<?php echo __( 'Current value', 'jqmi' ).': '.$jqmi_duration; ?>" name="duration">
									<label class="setting-label"><?php _e( 'miliseconds', 'jqmi' ); ?></label>
								</td>
							</tr>

						</table>
						
					</div>
					
					<button type="submit" class="button-primary"><?php _e ( 'Save', 'jqmi' ); ?></button>
					
				</form>
					
			</div>

		</div>
		
	</div>
	
<?php } ?>