<?php
/**
 * WDS WP Contributions Codex
 *
 * @version 1.1.0
 * @package WDS Contributions
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WDS_WP_Contributions_Codex' ) ) {

	/**
	 * WDS WP Contributions Codex
	 */
	class WDS_WP_Contributions_Codex {

		/**
		 * Constructor
		 */
		function __construct() {

			global $wp_contributions;
			$wp_contributions->is_query    = true;
			$wp_contributions->query->type = 'codex';

		}

		public static function get_codex_items( $username, $limit = 10 ) {
			if ( null === $username ) {
				return [];
			}

			if ( true || false === ( $formatted = get_transient( 'wp-contributions-codex-' . $username ) ) ) {

				$results_url = add_query_arg( [
					'action'  => 'query',
					'list'    => 'usercontribs',
					'ucuser'  => $username,
					'uclimit' => $limit,
					'ucdir'   => 'older',
					'format'  => 'php',
				], 'https://codex.wordpress.org/api.php' );
				$results = wp_remote_retrieve_body( wp_remote_get( $results_url, [ 'sslverify' => false ] ) );

				$raw = maybe_unserialize( $results );

				/*
				 * Expected array format is as follows:
				 * Array
				 * (
				 *     [query] => Array
				 *         (
				 *             [usercontribs] => Array
				 *                 (
				 *                     [0] => Array
				 *                         (
				 *                             [user] => Mbijon
				 *                             [pageid] => 23000
				 *                             [revid] => 112024
				 *                             [ns] => 0
				 *                             [title] => Function Reference/add help tab
				 *                             [timestamp] => 2011-12-13T23:49:38Z
				 *                             [minor] =>
				 *                             [comment] => Functions typo fix
				 *                         )
				 **/

				$formatted = [];
				if ( is_array( $raw ) && array_key_exists( 'query', $raw ) ) {
					foreach ( $raw['query']['usercontribs'] as $item ) {
						$count       = 0;
						$clean_title = preg_replace( '/^Function Reference\//', '', (string) $item['title'], 1, $count );

						$new_item = [
							'title'        => $clean_title,
							'description'  => (string) $item['comment'],
							'revision'     => (int) $item['revid'],
							'function_ref' => (bool) $count,
						];
						array_push( $formatted, $new_item );
					}
					set_transient( 'wp-contributions-codex-' . $username, $formatted, 60 * 60 * 12 );
				}
			}

			return $formatted;
		}

		public static function get_codex_count( $username ) {
			if ( null === $username ) {
				return [];
			}

			if ( false === ( $count = get_transient( 'wp-contributions-codex-count-' . $username ) ) ) {

				$results_url = add_query_arg( [
					'action'  => 'query',
					'list'    => 'users',
					'ususers' => $username,
					'usprop'  => 'editcount',
					'format'  => 'json',
				], 'https://codex.wordpress.org/api.php' );
				$results = wp_remote_retrieve_body( wp_remote_get( $results_url, [ 'sslverify' => false ] ) );

				if ( ! empty( $results ) ) {
					$raw   = json_decode( $results, true );
					$count = ( array_key_exists( 'query', $raw ) ) ?
						(int) $raw['query']['users'][0]['editcount'] :
						0;

					set_transient( 'wp-contributions-codex-count-' . $username, $count, 60 * 60 * 12 );
				}
			}

			return $count;
		}

		/**
		 * Output the HTML for displaying a codex contributions card.
		 *
		 * @param string $user  The WP.org username.
		 * @param int    $count The number of contributions to show.
		 */
		public function display( $user, $count ) {

			ob_start();

			global $wp_contributions;

			$items = array_slice( WDS_WP_Contributions_Codex::get_codex_items( $user, $count ), 0, $count );
			$total = WDS_WP_Contributions_Codex::get_codex_count( $user );

			// Include template - can be overriden by a theme!
			$template_name = 'wp-contributions-codex-widget-template.php';
			$path          = locate_template( $template_name );
			if ( empty( $path ) ) {
				$path = $wp_contributions->directory_path . 'templates/' . $template_name;
			}

			include( $path );

			return ob_get_clean();
		}
	}
}
