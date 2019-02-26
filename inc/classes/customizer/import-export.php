<?php
/**
 * Creates the admin panel for the customizer
 *
 * @package		Zero
 * @subpackage	Customizer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		1.0.0
 */

// Only Needed in the admin
if ( ! is_admin() ) {
	return;
}

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add sub menu page to the Appearance menu.
 *
 * @link http://codex.wordpress.org/Function_Reference/add_theme_page
 */
if ( ! function_exists( 'wpex_customizer_admin' ) ) {
	function wpex_customizer_admin() {
		add_submenu_page(
			'themes.php',
			__( 'Customizer Manager', 'wpex-zero' ), 
			__( 'Customizer Manager', 'wpex-zero' ),
			'manage_options',
			'wpex-customizer-manager',
			'wpex_customizer_options_page'
		);
	}
}
add_action( 'admin_menu', 'wpex_customizer_admin' );

/**
 * Register a setting and its sanitization callback.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_setting
 */
if ( ! function_exists( 'wpex_customizer_register_settings' ) ) {
	function wpex_customizer_register_settings() {
		register_setting( 'wpex_customizer_options', 'wpex_customizer_options', 'wpex_customizer_options_sanitize');
	}
}
add_action( 'admin_init', 'wpex_customizer_register_settings' );

/**
 * Displays all messages registered to 'wpex-customizer-notices'
 *
 * @link http://codex.wordpress.org/Function_Reference/settings_errors
 */
if ( ! function_exists( 'wpex_customizer_admin_notices_action' ) ) {
	function wpex_customizer_admin_notices_action() {
		settings_errors( 'wpex-customizer-notices' );
	}
}
add_action( 'admin_notices', 'wpex_customizer_admin_notices_action' );

/**
 * Sanitization callback
 */
if ( ! function_exists( 'wpex_customizer_options_sanitize' ) ) {
	function wpex_customizer_options_sanitize( $options ) {
		// Import the imported options
		if ( $options ) {
			// Delete options if import set to -1
			if ( '-1' == $options['reset'] ) {
				// Get menu locations
				$locations = get_theme_mod( 'nav_menu_locations' );
				$save_menus = array();
				foreach( $locations as $key => $val ) {
					$save_menus[$key] = $val;
				}
				// Remove all mods
				remove_theme_mods();
				// Re-add the menus
				set_theme_mod( 'nav_menu_locations', array_map( 'absint', $save_menus ) );
				// Error messages
				$error_msg = __( 'All theme customizer settings have been reset.', 'wpex-zero' );
				$error_type = 'updated';
			}
			// Set theme mods based on json data
			elseif( ! empty( $options['import'] ) ) {
				// Decode input data
				$theme_mods = json_decode( $options['import'], true );
				// Validate json file then set new theme options
				if ( '0' == json_last_error() ) {
					foreach ( $theme_mods as $theme_mod => $value ) {
						set_theme_mod( $theme_mod, $value );
					}
					$error_msg  = __( 'Theme customizer settings imported successfully.', 'wpex-zero' );
					$error_type = 'updated';
				}
				// Display invalid json data error
				else {
					$error_msg  = __( 'Invalid Import Data.', 'wpex-zero' );
					$error_type = 'error';
				}
			}
			// No json data entered
			else {
				$error_msg = __( 'No import data found.', 'wpex-zero' );
				$error_type = 'error';
			}
			// Make sure the settings data is reset! 
			$options = array(
				'import'	=> '',
				'reset'		=> '',
			);
		}
		// Display message
		add_settings_error(
			'wpex-customizer-notices',
			esc_attr( 'settings_updated' ),
			$error_msg,
			$error_type
		);

		// Return options
		return $options;
	}
}

/**
 * Settings page output
 */
if ( ! function_exists( 'wpex_customizer_options_page' ) ) {
	function wpex_customizer_options_page() { ?>
		<div class="wrap">
		<h2><?php _e( 'Import, Export or Reset Customizer Settings', 'wpex-zero' ); ?></h2>
		<?php
		// Default options
		$options = array(
				'import'	=> '',
				'reset'		=> '',
		); ?>
		<form method="post" action="options.php">
			<?php
			/**
			 * Output nonce, action, and option_page fields for a settings page
			 *
			 * @link http://codex.wordpress.org/Function_Reference/settings_fields
			 */
			$options = get_option( 'wpex_customizer_options', $options );
			settings_fields( 'wpex_customizer_options' ); ?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><?php _e( 'Export Customizer Options', 'wpex-zero' ); ?></th>
					<td>
						<?php
						// Get an array of all the theme mods
						if ( $theme_mods = get_theme_mods() ) {
							$mods = array();
							foreach ( $theme_mods as $theme_mod => $value ) {
								$mods[$theme_mod] = maybe_unserialize( $value );
							}
							$json = json_encode( $mods );
							$disabled = '';
						} else {
							$json = __( 'No Options Found', 'wpex-zero' );
							$disabled = 'disabled';
						}
						echo '<textarea rows="10" cols="50" disabled id="wpex-customizer-export" style="width:100%;">' . $json . '</textarea>'; ?>
						<p class="submit">
							<a href="#" class="button-primary wpex-highlight-options <?php echo $disabled; ?>"><?php _e( 'Highlight Options', 'wpex-zero' ); ?></a>
						</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'Import Customizer Options', 'wpex-zero' ); ?></th>
					<td>
						<?php
						// Get import data
						$import = isset( $options['import'] ) ? $options['import'] : ''; ?>
						<textarea name="wpex_customizer_options[import]" rows="10" cols="50" style="width:100%;"><?php echo stripslashes( $import ); ?></textarea>
						<input id="wpex-reset-hidden" name="wpex_customizer_options[reset]" type="hidden" value=""></input>
						<p class="submit">
							<input type="submit" class="button-primary wpex-submit-form" value="<?php _e( 'Import Options', 'wpex-zero' ) ?>" />
							<a href="#" class="button-secondary wpex-delete-options"><?php _e( 'Reset Options', 'wpex-zero' ); ?></a>
							<a href="#" class="button-secondary wpex-cancel-delete-options" style="display:none;"><?php _e( 'Cancel Reset', 'wpex-zero' ); ?></a>
						</p>
						<div class="wpex-delete-options-warning error inline" style="display:none;">
							<p style="margin:.5em 0;"><?php _e( 'Always make sure you have a backup of your settings before resetting, just incase!', 'wpex-zero' ); ?></p>
						</div>
					</td>
				</tr>
			</table>
		</form>
		<script>
			(function($) {
				"use strict";
					$('.wpex-highlight-options').click( function() {
						$('#wpex-customizer-export').focus().select();
						return false;
					});
					$('.wpex-delete-options').click( function() {
						$(this).hide();
						$('.wpex-delete-options-warning, .wpex-cancel-delete-options').show();
						$('.wpex-submit-form').val( "<?php _e( 'Confirm Reset', 'wpex-zero' ); ?>" );
						$('#wpex-reset-hidden').val('-1');
						return false;
					});
					$('.wpex-cancel-delete-options').click( function() {
						$(this).hide();
						$('.wpex-delete-options-warning').hide();
						$('.wpex-delete-options').show();
						$('.wpex-submit-form').val( "<?php _e( 'Import Options', 'wpex-zero' ); ?>" );
						$('#wpex-reset-hidden').val('');
						return false;
					});
			})(jQuery);
		</script>
		</div>
	<?php }
}