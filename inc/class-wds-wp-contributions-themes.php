<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WDS_WP_Contributions_Themes' ) ) {

	class WDS_WP_Contributions_Themes {

		function __construct() {

			global $wp_contributions;
			$wp_contributions->is_query = true;
			$wp_contributions->query->type = 'theme';

		}

		/**
		 * Use the WP.org API to return plugin information.
		 *
		 * This is a copy of plugins_api contained in core, but broken out so any filters run
		 * during that process don't also run here.
		 *
		 * @param string       $action The requested action. Likely values are 'theme_information',
		 *                             'feature_list', or 'query_themes'.
		 * @param array|object $args   Optional. Arguments to serialize for the Theme Info API.
		 * @return mixed
		 */
		function themes_api( $action, $args = null ) {

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

			$url = $http_url = 'http://api.wordpress.org/themes/info/1.0/';
			if ( $ssl = wp_http_supports( array( 'ssl' ) ) ) {
				$url = set_url_scheme( $url, 'https' );
			}

			$args = array(
				'body' => array(
					'action' => $action,
					'request' => serialize( $args ),
				),
			);
			$request = wp_remote_post( $url, $args );

			if ( $ssl && is_wp_error( $request ) ) {
				trigger_error( __( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="https://wordpress.org/support/">support forums</a>.' ) . ' ' . __( '(WordPress could not establish a secure connection to WordPress.org. Please contact your server administrator.)' ), headers_sent() || WP_DEBUG ? E_USER_WARNING : E_USER_NOTICE );
				$request = wp_remote_post( $http_url, $args );
			}

			if ( is_wp_error( $request ) ) {
				$res = new WP_Error( 'themes_api_failed', __( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="https://wordpress.org/support/">support forums</a>.' ), $request->get_error_message() );
			} else {
				$res = maybe_unserialize( wp_remote_retrieve_body( $request ) );
				if ( ! is_object( $res ) && ! is_array( $res ) ) {
					$res = new WP_Error( 'themes_api_failed', __( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="https://wordpress.org/support/">support forums</a>.' ), wp_remote_retrieve_body( $request ) );
				}
			}

			return $res;
		}

		/**
		 * Get the theme object from the WP.org API
		 *
		 * @param string $theme_slug The slug of the theme hosted on WP.org.
		 * @return object An object of the theme data returned from the WP.org Theme API.
		 */
		public function get_theme( $theme_slug ) {

			global $wp_contributions;
			$wp_contributions->query->method = 'by_slug';
			$wp_contributions->query->theme_slug = esc_attr( $theme_slug );

			if ( false === ( $theme = get_transient( 'wp_contributions_theme_' . $theme_slug ) ) ) {
				$args   = array(
					'slug'   => esc_attr( $theme_slug ),
					'fields' => array(
						'sections'    => false,
						'tags'        => false,
						'description' => true,
					),
					'is_ssl' => is_ssl(),
				);
				$theme = $this->themes_api( 'theme_information', $args );
				set_transient( 'wp_contributions_theme_' . $theme_slug, $theme, 24 * HOUR_IN_SECONDS );
			}

			$wp_contributions->query->results = $theme;
			return $theme;

		}

		/**
		 * Get all themes from WP.org by a certain Author.
		 *
		 * @param string $author_name The username of the author you are querying for themes.
		 * @return object An object of the theme data returned from the WP.org theme API.
		 */
		public function get_author_themes( $author_name ) {

			global $wp_contributions;
			$wp_contributions->query->method = 'by_author';
			$wp_contributions->query->author = $author_name;

			if ( false === ( $author = get_transient( 'wp_contributions_theme_author_' . $author_name ) ) ) {
				$args   = array(
					'author' => esc_attr( $author_name ),
				);
				$author = $this->themes_api( 'query_themes', $args );
				set_transient( 'wp_contributions_themes_' . $author_name, $author, 24 * HOUR_IN_SECONDS );
			}

			$wp_contributions->query->results = $author;
			return $author;

		}

		/**
		 * Output the HTML for displaying a theme card.
		 *
		 * @param object $theme_data The theme data returned from the WP.org API.
		 */
		public function display( $theme_data ) {

			global $wp_contributions;

			$theme_data = apply_filters( 'wp_contributions_display_theme_data', $theme_data );

			$icon = '';
			if ( ! empty( $this->directory_url ) ) {
				$icon = $this->directory_url . '/assets/images/theme-screenshot.png';
			}

			// Set up variables to use.
			if ( ! empty( $theme_data->screenshot_url ) ) {
				$icon = $theme_data->screenshot_url;
			}

			$name        = ( isset( $theme_data->name ) ) ? $theme_data->name : '';
			$slug        = ( isset( $theme_data->slug ) ) ? $theme_data->slug : '';
			$link        = 'https://wordpress.org/themes/' . esc_attr( $slug );
			$description = isset( $theme_data->description ) ? esc_html( strip_tags( $theme_data->description ) ) : '';
			$more        = apply_filters( 'wp_contributions_display_more_text', '&hellip;' );
			$description = wp_trim_words( $description, apply_filters( 'wp_contributions_desc_length', 30 ), $more );
			$version     = ( isset( $theme_data->version ) ) ? $theme_data->version : '';
			$rating      = ( isset( $theme_data->rating ) ) ? $theme_data->rating : '';
			$num_ratings = ( isset( $theme_data->num_ratings ) ) ? $theme_data->num_ratings : '';
			$downloaded  = ( isset( $theme_data->downloaded ) )  ? $theme_data->downloaded : '';
			$author      = ( isset( $theme_data->author ) ) ? strip_tags( $theme_data->author ) : '';
			$last_update = ( isset( $theme_data->last_updated ) ) ? date( 'M j, Y', strtotime( $theme_data->last_updated ) ) : '';

			// Include template - can be overriden by a theme!
			$template_name = 'wp-contributions-theme-card-template.php';
			$path = locate_template( array( $template_name, 'wp-contributions/' . $template_name ) );
			if ( empty( $path ) ) {
				$path = $wp_contributions->directory_path . 'templates/' . $template_name;
			}
			include( $path );

		}
	}
}
