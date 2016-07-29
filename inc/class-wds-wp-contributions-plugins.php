<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WDS_WP_Contributions_Plugins' ) ) {

	class WDS_WP_Contributions_Plugins {

		function __construct() {

			global $wp_contributions;
			$wp_contributions->is_query = true;
			$wp_contributions->query->type = 'plugin';

		}

		/**
		 * Use the WP.org API to return plugin information.
		 *
		 * This is a copy of plugins_api contained in core, but broken out so any filters run
		 * during that process don't also run here.
		 *
		 * @param string       $action Action the API should perform for it's query.
		 * @param array|object $args   Optional. Arguments to serialize for the Plugin Info API.
		 * @return object $res response object on success, WP_Error on failure.
		 */
		public function plugins_api( $action, $args = null ) {

			global $wp_contributions;
			$wp_contributions->query->action = esc_attr( $action );
			$wp_contributions->query->args   = $args;

			if ( is_array( $args ) ) {
				$args = (object) $args;
			}

			if ( ! isset( $args->per_page ) ) {
				$args->per_page = 24;
			}

			if ( ! isset( $args->locale ) ) {
				$args->locale = get_locale();
			}

			$url = $http_url = 'http://api.wordpress.org/plugins/info/1.0/';
			if ( $ssl = wp_http_supports( array( 'ssl' ) ) ) {
				$url = set_url_scheme( $url, 'https' );
			}

			$args = array(
				'timeout' => 15,
				'body' => array(
					'action'  => $action,
					'request' => serialize( $args ),
				),
			);
			$request = wp_remote_post( $url, $args );

			if ( $ssl && is_wp_error( $request ) ) {
				trigger_error( __( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="https://wordpress.org/support/">support forums</a>.' ) . ' ' . __( '(WordPress could not establish a secure connection to WordPress.org. Please contact your server administrator.)' ), headers_sent() || WP_DEBUG ? E_USER_WARNING : E_USER_NOTICE );
				$request = wp_remote_post( $http_url, $args );
			}

			if ( is_wp_error( $request ) ) {
				$res = new WP_Error( 'plugins_api_failed', __( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="https://wordpress.org/support/">support forums</a>.' ), $request->get_error_message() );
			} else {
				$res = maybe_unserialize( wp_remote_retrieve_body( $request ) );
				if ( ! is_object( $res ) && ! is_array( $res ) ) {
					$res = new WP_Error( 'plugins_api_failed', __( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="https://wordpress.org/support/">support forums</a>.' ), wp_remote_retrieve_body( $request ) );
				}
			}

			return $res;
		}

		/**
		 * Get the plugin object from the WP.org API
		 *
		 * @param string $plugin_slug The slug of the plugin hosted on WP.org.
		 * @return object An object of the plugin data returned from the WP.org Plugin API.
		 */
		public function get_plugin( $plugin_slug ) {

			global $wp_contributions;
			$wp_contributions->query->method = 'by_slug';
			$wp_contributions->query->plugin_slug = esc_attr( $plugin_slug );

			if ( false === ( $plugin = get_transient( 'wp_contributions_plugin_' . $plugin_slug ) ) ) {
				$args   = array(
					'slug'   => esc_attr( $plugin_slug ),
					'fields' => array(
						'sections'          => false,
						'tags'              => false,
						'icons'             => true,
						'banners'           => true,
						'short_description' => true,
					),
					'is_ssl' => is_ssl(),
				);
				$plugin = $this->plugins_api( 'plugin_information', $args );
				set_transient( 'wp_contributions_plugin_' . $plugin_slug, $plugin, 24 * HOUR_IN_SECONDS );
			}

			$wp_contributions->query->results = $plugin;
			return $plugin;

		}

		/**
		 * Get all plugins from WP.org by a certain Author.
		 *
		 * @param string $author_name The username of the author you are querying for plugins.
		 * @return object An object of the plugin data returned from the WP.org Plugin API.
		 */
		public function get_author_plugins( $author_name ) {

			global $wp_contributions;
			$wp_contributions->query->method = 'by_author';
			$wp_contributions->query->author = $author_name;

			if ( false === ( $author = get_transient( 'wp_contributions_plugin_author_' . $author_name ) ) ) {
				$args   = array(
					'author' => esc_attr( $author_name ),
				);
				$author = $this->plugins_api( 'query_plugins', $args );
				set_transient( 'wp_contributions_plugin_' . $author_name, $author, 24 * HOUR_IN_SECONDS );
			}

			$wp_contributions->query->results = $author;
			return $author;

		}

		/**
		 * Output the HTML for displaying a plugin card.
		 *
		 * @param object $plugin_data The plugin data returned from the WP.org API.
		 */
		public function display( $plugin_data ) {

			global $wp_contributions;

			$plugin_data = apply_filters( 'wp_contributions_display_plugin_data', $plugin_data );

			$icon = $wp_contributions->directory_url . '/assets/images/plugin-icon.png';

			// Set up variables to use.
			if ( ! empty( $plugin_data->icons['svg'] ) ) {
				$icon = $plugin_data->icons['svg'];
			} elseif ( ! empty( $plugin_data->icons['2x'] ) ) {
				$icon = $plugin_data->icons['2x'];
			} elseif ( ! empty( $plugin_data->icons['1x'] ) ) {
				$icon = $plugin_data->icons['1x'];
			}

			$name         = $plugin_data->name;
			$slug         = $plugin_data->slug;
			$link         = 'https://wordpress.org/plugins/' . esc_attr( $slug );
			$description  = isset( $plugin_data->short_description ) ? esc_html( strip_tags( $plugin_data->short_description ) ) : '';
			$more         = apply_filters( 'wp_contributions_display_more_text', '&hellip;' );
			$description  = wp_trim_words( $description, apply_Filters( 'wp_contributions_desc_length', 30 ), $more );
			$version      = $plugin_data->version;
			$rating       = $plugin_data->rating;
			$num_ratings  = $plugin_data->num_ratings;
			$downloaded   = $plugin_data->downloaded;
			$author       = strip_tags( $plugin_data->author );
			$contributors = implode( ', ', array_keys( $plugin_data->contributors ) );
			$last_update = date( 'M j, Y', strtotime( $plugin_data->last_updated ) );

			// Include template - can be overriden by a theme!
			$template_name = 'wp-contributions-plugin-card-template.php';
			$path = locate_template( array( $template_name, 'wp-contributions/' . $template_name ) );
			if ( empty( $path ) ) {
				$path = $wp_contributions->directory_path . 'templates/' . $template_name;
			}
			include( $path );

		}
	}
}
