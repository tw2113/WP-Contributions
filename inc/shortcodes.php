<?php
/**
 * Register WP Contributions shortcodes
 *
 * @package WP Contributions
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Displays the HTML output of a Plugin Card.
 *
 * @param string $atts The slug of the plugin on WordPress.org.
 */
function wp_contributions_plugin_card_shortcode( $atts ) {

	if ( ! $atts ) {
		echo '<p class="wp-contributions-message alert">' . esc_html__( 'Please enter a plugin slug to display this shortcode, e.g. [wp_contributions_plugin_card slug="your-plugin-slug"]', 'wp-contributions' ) . '</p>';
		return;
	}

	$args = shortcode_atts( array(
		'slug' => $atts,
		'type' => 'plugin',
	), $atts ); // $atts['slug'] aka [wp_contributions_plugin_card slug="your-plugin-slug"]

	wp_contributions_plugin_card( $args['slug'] );

}
add_shortcode( 'wp_contributions_plugin_card', 'wp_contributions_plugin_card_shortcode' );

/**
 * Displays the HTML output of a Theme Card.
 *
 * @param string $atts The slug of the theme on WordPress.org.
 */
function wp_contributions_theme_card_shortcode( $atts ) {

	if ( ! $atts ) {
		echo '<p class="wp-contributions-message alert">' . esc_html__( 'Please enter a theme slug to display this shortcode, e.g. [wp_contributions_theme_card slug="your-theme-slug"]', 'wp-contributions' ) . '</p>';
		return;
	}

	$args = shortcode_atts( array(
		'slug' => $atts,
		'type' => 'plugin',
	), $atts ); // $atts['slug'] aka [wp_contributions_theme_card slug="your-theme-slug"]

	wp_contributions_theme_card( $args['slug'] );

}
add_shortcode( 'wp_contributions_theme_card', 'wp_contributions_theme_card_shortcode' );
