<?php
/**
 * Plugin Name: WP Contributions
 * Plugin URI: https://michaelbox.net
 * Description: Provides an easy way to display your WordPress.org Themes, Plugins, Core tickets, and Codex contributions with handy widgets and template tags.
 * Author: Michael Beckwith
 * Author URI: https://michaelbox.net
 * Version: 1.3.1
 * License: GPLv2
 * Requires PHP: 7.4
 * Text Domain: wp-contributions
 *
 * @package WP Contributions
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WDS_WP_Contributions' ) ) {

	class WDS_WP_Contributions {

		public string $basename;

		public string $directory_path;

		public string $directory_url;

		public string $is_query;

		public object $query;

		/**
		 * Construct function to get things started.
		 */
		public function __construct() {
			// Setup some base variables for the plugin.
			$this->basename       = plugin_basename( __FILE__ );
			$this->directory_path = plugin_dir_path( __FILE__ );
			$this->directory_url  = plugins_url( dirname( $this->basename ) );
			$this->is_query       = 'false';
			$this->query          = new stdClass();
		}

		/**
		 * Creates or returns an instance of this class.
		 *
		 * @since 1.1.0
		 *
		 * @return WDS_WP_Contributions A single instance of this class.
		 */
		public function init(): WDS_WP_Contributions {
			static $instance = null;
			if ( null === $instance ) {
				$instance = new self();
			}
			$instance->includes();
			$instance->hooks();

			return $instance;
		}

		/**
		 * Add hooks and filters.
		 *
		 * @since 1.1.0
		 */
		public function hooks() {

			// Activation/Deactivation Hooks.
			register_activation_hook( __FILE__, [ $this, 'activate' ] );
			register_deactivation_hook( __FILE__, [ $this, 'deactivate' ] );

			// Register Widgets.
			add_action( 'widgets_init', [ $this, 'register_widgets' ] );

			// Enqueue necessary styles.
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );

			// Block init
			add_action( 'init', [ $this, 'wp_contributions_block_init' ] );
		}


		/**
		 * Include our plugin dependencies.
		 */
		public function includes() {

			require_once( $this->directory_path . 'inc/class-wds-wp-contributions-plugins.php' );
			require_once( $this->directory_path . 'inc/class-wds-wp-contributions-themes.php' );
			require_once( $this->directory_path . 'inc/class-wds-wp-contributions-core.php' );
			require_once( $this->directory_path . 'inc/class-wds-wp-contributions-codex.php' );
			require_once( $this->directory_path . 'inc/class-wds-wp-contributions-plugin-widget.php' );
			require_once( $this->directory_path . 'inc/class-wds-wp-contributions-theme-widget.php' );
			require_once( $this->directory_path . 'inc/class-wds-wp-contributions-core-widget.php' );
			require_once( $this->directory_path . 'inc/class-wds-wp-contributions-codex-widget.php' );
			require_once( $this->directory_path . 'inc/helper-functions.php' );
			require_once( $this->directory_path . 'inc/shortcodes.php' );

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
		 * Enqueue script.
		 *
		 * @since 1.1.0
		 */
		public function enqueue() {
			$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_style( 'wds-wp-contributions', $this-> directory_url . "/assets/css/style$min.css", [ 'dashicons' ], '150505' );
		}

		/**
		 * Handles the display of the card view to display contribution data.
		 *
		 * @param array $args Arguments to pass to the card display.
		 * @return string The HTML output for the card view.
		 */
		public function display_card( $args = [] ) {

			if ( ! isset( $args['slug'] ) ) {
				return '<p>' . esc_html__( 'No Slug Entered', 'wp-contributions' );
			} else {
				$args['slug'] = strtolower( $args['slug'] );
			}

			$args = apply_filters( 'wp_contributions_display_card_args', $args );

			$card = '';

			if ( 'plugin' === $args['type'] ) {
				$plugin_api  = new WDS_WP_Contributions_Plugins();
				$plugin_data = $plugin_api->get_plugin( $args['slug'] );

				if ( ! is_wp_error( $plugin_data ) ) {
					$card .= $plugin_api->display( $plugin_data );
				} else {
					$card .= '<p>' . esc_html__( 'Plugin API failed. The plugin slug could be incorrect or there could be an error with the WP Plugin API.', 'wp-contributions' );
				}
			} elseif ( 'theme' === $args['type'] ) {
				$theme_api  = new WDS_WP_Contributions_Themes();
				$theme_data = $theme_api->get_theme( $args['slug'] );
				if ( ! is_wp_error( $theme_data ) ) {
					$card .= $theme_api->display( $theme_data );
				} else {
					$card .= '<p>' . esc_html__( 'Theme API failed. The theme slug could be incorrect or there could be an error with the WP Theme API.', 'wp-contributions' );
				}
			} elseif ( 'core' === $args['type'] ) {
				$count = isset( $args['count'] ) ? $args['count'] : 5;
				$core  = new WDS_WP_Contributions_Core();
					$card .= $core->display( $args['slug'], $count );
			} elseif ( 'codex' === $args['type'] ) {
				$count = isset( $args['count'] ) ? $args['count'] : 5;
				$codex = new WDS_WP_Contributions_Codex();
					$card .= $codex->display( $args['slug'], $count );
			}

			return apply_filters( 'wp_contributions_display_card', $card, $args );

		}

		/**
		 * Block Initializer.
		 */
		public function wp_contributions_block_init() {
			register_block_type(
				plugin_dir_path( __FILE__ ) . 'assets/block/build',
				[
					'render_callback' => 'wp_contributions_block_callback',
					'attributes'      => [
						'slug' => [
							'type' => 'string',
						],
						'preferred_username' => [
							'type'    => 'string',
							'default' => 'My Preferred_handle',
						],
						'theme'  => [
							'type'    => 'boolean',
							'default' => false,
						],
						'contribution_type' => [
							'type'    => 'string',
							'default' => 'plugin',
						],
					],
				]
			);
		}
	}

	/**
	 * Block Output.
	 *
	 * @param string $attr Block attributes
	 * @return string The HTML output for the card view.
	 */
	function wp_contributions_block_callback( $attr ) {
		if ( isset( $attr['slug'] ) && isset( $attr['contribution_type'] ) ) {
			try {
				ob_start();
				global $wp_contributions;
				echo $wp_contributions->display_card( [ 'type' => $attr['contribution_type'], 'slug' => $attr['slug'] ] );
				return ob_get_clean();
			} catch ( Exception $e ) {
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG === true ) {
					error_log( $e );
				}
			}
		}

		/* Default output */
		ob_start();
		echo '<p>' . esc_html__( 'Still missing must-have paramaters for the render.', 'wp-contributions' );
		return ob_get_clean();
	}

	/**
	 * Grab the WDS_WP_Contributions object and return it.
	 * Wrapper for WDS_WP_Contributions::get_instance()
	 */
	function load_wp_contributions() {
		global $wp_contributions;
		$wp_contributions = new WDS_WP_Contributions();
		$wp_contributions->init();
		return $wp_contributions;
	}
	add_action( 'plugins_loaded', 'load_wp_contributions' );

}

add_action( 'init', function() {
	load_plugin_textdomain( 'wp-contributions' );
} );
