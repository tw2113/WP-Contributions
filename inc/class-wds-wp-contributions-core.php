<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists('WDS_WP_Contributions_Core') ) {

	class WDS_WP_Contributions_Core {

		function __construct() {

			global $wp_contributions;
			$wp_contributions->is_query = true;
			$wp_contributions->query->type = 'core';

		}

		public static function get_items( $username ) {
			if ( null == $username ) {
				return array();
			}

			if ( false === ( $formatted = get_transient( 'wp-contributions-core' . $username ) ) ) {
				$results_url = add_query_arg( array(
					'q'           => 'props+' . $username,
					'noquickjump' => '1',
					'changeset'   => 'on',
				), 'https://core.trac.wordpress.org/search' );
				$results = wp_remote_retrieve_body( wp_remote_get( $results_url, array( 'sslverify' => false ) ) );

				$pattern = '/<dt><a href="(.*?)" class="searchable">\[(.*?)\]: ((?s).*?)<\/a><\/dt>\n\s*(<dd class="searchable">.*\n?.*(?:ixes|ee) #(.*?)\n?<\/dd>)?/';

				preg_match_all( $pattern, $results, $matches, PREG_SET_ORDER );

				$formatted = array();

				foreach ( $matches as $match ) {
					array_shift( $match );
					$new_match = array(
						'link'        => 'https://core.trac.wordpress.org' . $match[0],
						'changeset'   => intval( $match[1] ),
						'description' => $match[2],
						'ticket'      => isset( $match[3] ) ? intval( $match[4] ) : '',
					);
					array_push( $formatted, $new_match );
				}

				set_transient( 'wp-contributions-core' . $username, $formatted, 60 * 60 * 12 );
			}

			return $formatted;
		}

		public static function get_changeset_count( $username ) {
			if ( null == $username ) {
				return array();
			}

			if ( false == ( $count = get_transient( 'wp-contributions-core-count-' . $username ) ) ) {
				$results_url = add_query_arg( array(
					'q'           => 'props+' . $username,
					'noquickjump' => '1',
					'changeset'   => 'on',
				), 'https://core.trac.wordpress.org/search' );
				$results = wp_remote_retrieve_body( wp_remote_get( $results_url, array( 'sslverify' => false ) ) );

				$pattern = '/<meta name="totalResults" content="(\d*)" \/>/';

				preg_match( $pattern, $results, $matches );

				$count = ( isset( $matches[1] ) ) ? intval( $matches[1] ) : '';

				set_transient( 'wp-contributions-core-count-' . $username, $count, 60 * 60 * 12 );
			}

			return $count;
		}

		/**
		 * Output the HTML for displaying a core contributions card.
		 *
		 * @param string $user  The WP.org username.
		 * @param int    $count The number of contributions to show.
		 */
		public function display( $user, $count ) {

			global $wp_contributions;

			// Widget content.
			$items = array_slice( WDS_WP_Contributions_Core::get_items( $user ), 0, $count );
			$total = WDS_WP_Contributions_Core::get_changeset_count( $user );

			// Include template - can be overriden by a theme!
			$template_name = 'wp-contributions-core-widget-template.php';
			$path = locate_template( $template_name );
			if ( empty( $path ) ) {
				$path = $wp_contributions->directory_path . 'templates/' . $template_name;
			}

			include( $path ); // This include will generate the markup for the widget.

		}
	}
}
