<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Echos the HTML output of a Plugin Card.
 *
 * @param string $plugin_slug The slug of the plugin on WordPress.org.
 */
function wp_contributions_plugin_card( $plugin_slug ) {

	global $wp_contributions;

	$args = array(
		'slug' => $plugin_slug,
		'type' => 'plugin',
	);
	$wp_contributions->display_card( $args );

}

/**
 * Echos the HTML output of a Theme Card.
 *
 * @param string $theme_slug The slug of the plugin on WordPress.org.
 */
function wp_contributions_theme_card( $theme_slug ) {

	global $wp_contributions;

	$args = array(
		'slug' => $theme_slug,
		'type' => 'theme',
	);
	$wp_contributions->display_card( $args );

}

/**
 * Displays a loop of all of a given author's plugins in card format.
 *
 * @param string $username The author's WordPress.org username.
 */
function wp_contributions_author_plugin_cards( $username ) {

	if ( ! $username ) {
		echo __( 'Please enter a WordPress.org Username.', 'wp-contributions' );
		return;
	}
	global $wp_contributions;
	$plugins = new WDS_WP_Contributions_Plugins();
	$author = $plugins->get_author_plugins( $username );

	$author = apply_filters( 'wp_contributions_author_plugins', $author, $username );

	foreach( $author->plugins as $plugin ) {
		$args = array(
			'slug' => $plugin->slug,
			'type' => 'plugin',
		);
		$wp_contributions->display_card( $args );
	}

}

/**
 * Displays a loop of all of a given author's themes in card format.
 *
 * @param string $username The author's WordPress.org username.
 */
function wp_contributions_author_theme_cards( $username ) {

	if ( ! $username ) {
		echo __( 'Please enter a WordPress.org Username.', 'wp-contributions' );
		return;
	}
	global $wp_contributions;
	$themes = new WDS_WP_Contributions_Themes();
	$author = $themes->get_author_themes( $username );

	$author = apply_filters( 'wp_contributions_author_themes', $author, $username );

	foreach( $author->themes as $theme ) {
		$args = array(
			'slug' => $theme->slug,
			'type' => 'theme',
		);
		$wp_contributions->display_card( $args );
	}

}

/**
 * Displays a list of a WP.org user's core contributions.
 *
 * @param string $username The WP.org username.
 * @param int    $count    The number of contributions to display.
 */
function wp_contributions_core_contributions_card( $username, $count = 5 ) {

	if ( ! $username ) {
		echo __( 'Please enter a WordPress.org Username.', 'wp-contributions' );
		return;
	}

	global $wp_contributions;


	$args = array(
		'slug'  => $username,
		'type'  => 'core',
		'count' => intval( $count ),
	);
	$wp_contributions->display_card( $args );

}

/**
 * Displays a list of a WP.org user's codex contributions.
 *
 * @param string $username The WP.org username.
 * @param int    $count    The number of contributions to display.
 */
function wp_contributions_codex_contributions_card( $username, $count = 5 ) {

	if ( ! $username ) {
		echo __( 'Please enter a WordPress.org Username.', 'wp-contributions' );
		return;
	}

	global $wp_contributions;

	$args = array(
		'slug'  => $username,
		'type'  => 'codex',
		'count' => intval( $count ),
	);
	$wp_contributions->display_card( $args );

}

/**
 * Returns a HTML element with a star rating for a given rating.
 *
 * Returns a HTML element with the star rating exposed on a 0..5 scale in
 * half star increments (ie. 1, 1.5, 2 stars). Optionally, if specified, the
 * number of ratings may also be displayed by passing the $number parameter.
 * Copied from WP Core and modified to return instead of echo.
 *
 * @param array $args {
 *     Optional. Array of star ratings arguments.
 *
 *     @type int    $rating The rating to display, expressed in either a 0.5 rating increment,
 *                          or percentage. Default 0.
 *     @type string $type   Format that the $rating is in. Valid values are 'rating' (default),
 *                          or, 'percent'. Default 'rating'.
 *     @type int    $number The number of ratings that makes up this rating. Default 0.
 * }
 * @return string   The formatted HTML of star ratings.
 */
function wp_contributions_star_rating( $args = array() ) {
	$defaults = array(
		'rating' => 0,
		'type' => 'rating',
		'number' => 0,
	);
	$r = wp_parse_args( $args, $defaults );

	// Non-english decimal places when the $rating is coming from a string
	$rating = str_replace( ',', '.', $r['rating'] );

	// Convert Percentage to star rating, 0..5 in .5 increments
	if ( 'percent' == $r['type'] ) {
		$rating = round( $rating / 10, 0 ) / 2;
	}

	// Calculate the number of each type of star needed
	$full_stars = floor( $rating );
	$half_stars = ceil( $rating - $full_stars );
	$empty_stars = 5 - $full_stars - $half_stars;

	if ( $r['number'] ) {
		/* translators: 1: The rating, 2: The number of ratings */
		$format = _n( '%1$s rating based on %2$s rating', '%1$s rating based on %2$s ratings', $r['number'] );
		$title = sprintf( $format, number_format_i18n( $rating, 1 ), number_format_i18n( $r['number'] ) );
	} else {
		/* translators: 1: The rating */
		$title = sprintf( __( '%s rating' ), number_format_i18n( $rating, 1 ) );
	}

	$html = '<div class="star-rating" title="' . esc_attr( $title ) . '">';
	$html .= '<span class="screen-reader-text">' . esc_html( $title ) . '</span>';
	$html .= str_repeat( '<div class="star star-full"></div>', $full_stars );
	$html .= str_repeat( '<div class="star star-half"></div>', $half_stars );
	$html .= str_repeat( '<div class="star star-empty"></div>', $empty_stars);
	$html .= '</div>';

	return $html;
}