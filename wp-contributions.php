<?php
/**
 * Plugin Name: WP Contributions
 * Plugin URI: http://webdevstudios.com
 * Description: Show off your WordPress plugins, themes, and contributions.
 * Author: WebDevStudios
 * Author URI: http://webdevstudios.com
 * Version: 1.0.0
 * License: GPLv2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WDS_WP_Contributions' ) ) {

	class WDS_WP_Contributions {

		/**
		 * Construct function to get things started.
		 */
		public function __construct() {
			// Setup some base variables for the plugin
			$this->basename       = plugin_basename( __FILE__ );
			$this->directory_path = plugin_dir_path( __FILE__ );
			$this->directory_url  = plugins_url( dirname( $this->basename ) );
			$this->is_query       = 'false';
			$this->query          = new stdClass();

			// Include any required files
			$this->includes();

			// Load Textdomain
			load_plugin_textdomain( 'wp-contributions', false, dirname( $this->basename ) . '/languages' );

			// Activation/Deactivation Hooks
			register_activation_hook( __FILE__, array( $this, 'activate' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

			// Add settings to User Profile Pages.
			add_action( 'show_user_profile', array( $this, 'user_profile' ) );
			add_action( 'edit_user_profile', array( $this, 'user_profile' ) );

			// Save User Profile Settings
			add_action( 'personal_options_update', array( $this, 'update_user' ) );
			add_action( 'edit_user_profile_update', array( $this, 'update_user' ) );

			// Register Widgets
			add_action( 'widgets_init', array( $this, 'register_widgets' ) );

		}

		/**
		 * Include our plugin dependencies.
		 */
		public function includes() {

			require_once( $this-> directory_path . 'inc/class-wds-wp-contributions-plugins.php' );
			require_once( $this-> directory_path . 'inc/class-wds-wp-contributions-themes.php' );
			require_once( $this-> directory_path . 'inc/class-wds-wp-contributions-core.php' );
			require_once( $this-> directory_path . 'inc/class-wds-wp-contributions-codex.php' );
			require_once( $this-> directory_path . 'inc/class-wds-wp-contributions-plugin-widget.php' );
			require_once( $this-> directory_path . 'inc/class-wds-wp-contributions-theme-widget.php' );
			require_once( $this-> directory_path . 'inc/class-wds-wp-contributions-core-widget.php' );
			require_once( $this-> directory_path . 'inc/class-wds-wp-contributions-codex-widget.php' );
			require_once( $this-> directory_path . 'inc/helper-functions.php' );

		}

		/**
		 * Activation hook for the plugin.
		 */
		public function activate() {

		}

		/**
		 * Deactivation hook for the plugin.
		 */
		public function deactivate() {

		}

		/**
		 * Register our widgets to display plugins, author, themes, and more.
		 */
		function register_widgets() {

			register_widget( 'WDS_WP_Contributions_Plugin_Widget' );
			register_widget( 'WDS_WP_Contributions_Theme_Widget' );
			register_widget( 'WDS_WP_Contributions_Core_Widget' );
			register_widget( 'WDS_WP_Contributions_Codex_Widget' );

		}

		/**
		 * Outputs the per user settings on user profile pages.
		 *
		 * @param object $user The WP_User object of the user being displayed.
		 */
		function user_profile( $user ) {

			if ( ! current_user_can( 'edit_users' ) ) {
				return;
			}

			wp_nonce_field( 'wp_contributions_user_settings', 'wp_contributions_user_settings' );
			?>
			<h3 id="wp-contributions"><?php esc_html_e( 'WP Contributions Settings', 'wp-contributions'); ?></h3>
			<table class="form-table">
			<tr>
				<th>
					<label for="wp_contributions_wporg_username"><?php esc_html_e( 'WordPress.org Username', 'wp-contributions' ); ?></label>
				</th>
				<td><input class="regular-text" type="text" id="wp_contributions_wporg_username" name="wp_contributions_wporg_username"
				           value="<?php echo esc_attr( get_the_author_meta( 'wp_contributions_wporg_username', $user->ID ) ); ?>" />
				</td>
			</tr>
			<tr>
				<th>
					<label for="wp_contributions_show_plugins"><?php esc_html_e( 'Show Plugins?', 'wp-contributions' ); ?></label>
				</th>
				<td>
					<input class="checkbox double" type="checkbox" id="wp_contributions_show_plugins" name="wp_contributions_show_plugins" value="on" <?php echo ( ( esc_attr( get_the_author_meta( 'wp_contributions_show_plugins', $user->ID ) ) == 'on' ) ? 'checked' : '' ); ?> />
				</td>
			</tr>
			<tr>
				<th>
					<label for="wp_contributions_show_themes"><?php esc_html_e( 'Show Themes?', 'wp-contributions' ); ?></label>
				</th>
				<td>
					<input class="checkbox double" type="checkbox" id="wp_contributions_show_themes" name="wp_contributions_show_themes" value="on" <?php echo ( ( esc_attr( get_the_author_meta( 'wp_contributions_show_themes', $user->ID ) ) == 'on' ) ? 'checked' : '' ); ?> />
				</td>
			</tr>
			</table>
			<?php

		}

		/**
		 * Process the updates of user meta.
		 *
		 * @param int $user_id The ID of the user being saved.
		 */
		function update_user( $user_id ) {

			if ( ! isset( $_POST['wp_contributions_wporg_username'] ) ) {
				return;
			}

			check_admin_referer( 'wp_contributions_user_settings', 'wp_contributions_user_settings' );

			update_user_meta( $user_id, 'wp_contributions_wporg_username', isset( $_POST['wp_contributions_wporg_username'] ) ? sanitize_text_field( $_POST['wp_contributions_wporg_username'] ) : '' );
			update_user_meta( $user_id, 'wp_contributions_show_plugins', isset( $_POST['wp_contributions_show_plugins'] ) ? sanitize_text_field( $_POST['wp_contributions_show_plugins'] ) : '' );
			update_user_meta( $user_id, 'wp_contributions_show_themes', isset( $_POST['wp_contributions_show_themes'] ) ? sanitize_text_field( $_POST['wp_contributions_show_themes'] ) : '' );

		}

		/**
		 * Handles the display of the card view to display contribution data.
		 *
		 * @param string $slug The slug of the plugin or theme or the username for codex or core.
		 * @param string $type What type of card is this? 'theme', 'plugin', 'core', 'codex'.
		 * @param array  $args Arguments to pass to the card display.
		 * @return string The HTML output for the card view.
		 */
		public function display_card( $slug, $type, $args = array() ) {

			if ( ! $slug ) {
				return '<p>' . esc_html__( 'No Slug Entered', 'wp-contributions' );
			}

			global $wp_contributions;
			$card = '';

			if ( 'plugin' === $type ) {
				// Get the plugin using the WP.org API
				$plugin_api  = new WDS_WP_Contributions_Plugins();
				$plugin_data = $plugin_api->get_plugin( $slug );

				if ( ! is_wp_error( $plugin_data ) ) {
					$card .= $plugin_api->display( $plugin_data );
				} else {
					$card .= '<p>' . esc_html__( 'Plugin API failed. The plugin slug could be incorrect or there could be an error with the WP Plugin API.', 'wp-contributions' );
				}
			} elseif ( 'theme' === $type ) {
				// Get the theme using the WP.org API
				$theme_api  = new WDS_WP_Contributions_Themes();
				$theme_data = $theme_api->get_theme( $slug );
				if ( ! is_wp_error( $theme_data ) ) {
					$card .= $theme_api->display( $theme_data );
				} else {
					$card .= '<p>' . esc_html__( 'Theme API failed. The theme slug could be incorrect or there could be an error with the WP Theme API.', 'wp-contributions' );
				}
			} elseif ( 'core' === $type ) {
				$count = isset( $args['count'] ) ? $args['count'] : 5;
				$core = new WDS_WP_Contributions_Core();
				$core->display( $slug, $count );
			} elseif ( 'codex' === $type ) {
				$count = isset( $args['count'] ) ? $args['count'] : 5;
				$core = new WDS_WP_Contributions_Codex();
				$core->display( $slug, $count );
			} else {

			}

			return $card;

		}

	}

	global $wp_contributions;
	$wp_contributions = new WDS_WP_Contributions();

}
