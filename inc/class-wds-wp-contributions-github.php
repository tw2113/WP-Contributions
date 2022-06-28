<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WDS_WP_Contributions_Plugins' ) ) {

	class WDS_WP_Contributions_Github {

		function __construct() {

			global $wp_contributions;
			$wp_contributions->is_query = true;
			$wp_contributions->query->type = 'github';

		}

		/**
		 * Github public contributions for a user
		 *
		 * @param string       $action Action the API should perform for it's query.
		 * @param array $args   Optional. Arguments to serialize for the Plugin Info API.
		 * @return array|object $res response object on success, WP_Error on failure.
		 *
		 * https://docs.github.com/en/rest/activity/events#list-public-events-for-a-user
		 *
		 */
		public function github_api( $username = null ) {

			if ( $username ) {
				$url = 'https://api.github.com/users/' . $username . '/events/public';
				if ( ! wp_http_supports( array( 'ssl' ) ) ) {
					$url = str_replace( 'https://%', 'http://', $url );
				}

				$request = wp_remote_get( $url );

				if ( is_wp_error( $request ) ) {
					$res = new WP_Error( 'github_api_failed', __( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="https://wordpress.org/support/">support forums</a>.' ), $request->get_error_message() );
				} else {
					$res = maybe_unserialize( wp_remote_retrieve_body( $request ) );
					if ( ! is_object( $res ) && ! is_array( $res ) ) {
						$res = new WP_Error( 'github_api_failed', __( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="https://wordpress.org/support/">support forums</a>.' ), wp_remote_retrieve_body( $request ) );
					}
				}

				return $res;
			}
		}

		/**
		 * Get the plugin object from the WP.org API
		 *
		 * @param string $plugin_slug The slug of the plugin hosted on WP.org.
		 * @return object An object of the plugin data returned from the WP.org Plugin API.
		 */
		public function get_github_info( $username ) {

			global $wp_contributions;
			$wp_contributions->query->method = 'by_username';
			$wp_contributions->query->username = esc_attr( $username );

			if ( false === ( $data = get_transient( 'wp_contributions_github_' . $username ) ) ) {
				$data = $this->plugins_api( 'plugin_information' );
				set_transient( 'wp_contributions_username_' . $plugin_slug, $plugin, 24 * HOUR_IN_SECONDS );
			}

			$wp_contributions->query->results = $data;
			return $data;

		}

		/**
		 * Output the HTML for displaying a card.
		 *
		 * @param object $data The data returned from the Github API.
		 */
		public function display( $data ) {

			/** In Construction */
			return [];

		}
	}
}
