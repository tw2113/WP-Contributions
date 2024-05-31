<?php
/**
 * WDS WP Contributions Plugin Widget
 *
 * @version 1.1.0
 * @package WDS Contributions
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WDS WP Contributions Plugin Widget
 */
class WDS_WP_Contributions_Plugin_Widget extends WP_Widget {

	/**
	 * Unique identifier for this widget.
	 *
	 * Will also serve as the widget class.
	 *
	 * @var string
	 */
	protected $widget_slug = 'wp-contributions-plugin-widget';

	/**
	 * Widget name displayed in Widgets dashboard.
	 * Set in __construct since __() shouldn't take a variable.
	 *
	 * @var string
	 */
	protected $widget_name = '';

	/**
	 * Default widget title displayed in Widgets dashboard.
	 * Set in __construct since __() shouldn't take a variable.
	 *
	 * @var string
	 */
	protected $default_widget_title = '';

	/**
	 * Contruct widget.
	 */
	public function __construct() {
		$this->widget_name          = esc_html__( 'WP Contributions Plugin Widget', 'wp-contributions' );
		$this->default_widget_title = esc_html__( 'My Plugin Info', 'wp-contributions' );
		parent::__construct(
			$this->widget_slug,
			$this->widget_name,
			[
				'classname'   => $this->widget_slug,
				'description' => esc_html__( 'Display information about a plugin hosted on WordPress.org.', 'wp-contributions' ),
			]
		);
		add_action( 'save_post',    [ $this, 'flush_widget_cache' ] );
		add_action( 'deleted_post', [ $this, 'flush_widget_cache' ] );
		add_action( 'switch_theme', [ $this, 'flush_widget_cache' ] );
	}

	/**
	 * Delete this widget's cache.
	 *
	 * Note: Could also delete any transients
	 * delete_transient( 'some-transient-generated-by-this-widget' );
	 */
	public function flush_widget_cache() {
		wp_cache_delete( $this->widget_slug, 'widget' );
	}

	/**
	 * Front-end display of widget.
	 *
	 * @param array $args     The widget arguments set up when a sidebar is registered.
	 * @param array $instance The widget settings as set by user.
	 */
	public function widget( $args, $instance ) {
		self::get_widget( [
			'before_widget' => $args['before_widget'],
			'after_widget'  => $args['after_widget'],
			'before_title'  => $args['before_title'],
			'after_title'   => $args['after_title'],
			'title'         => isset( $instance['title'] ) ? $instance['title'] : '',
			'plugin_slug'   => isset( $instance['plugin_slug'] ) ? $instance['plugin_slug'] : '',
		] );
	}

	/**
	 * Return the widget/shortcode output.
	 *
	 * @param array $atts Array of widget/shortcode attributes/args.
	 */
	public static function get_widget( $atts ) {

		global $wp_contributions;

		// Before widget hook.
		echo $atts['before_widget'];

		// Title.
		echo ( $atts['title'] ) ? $atts['before_title'] . esc_html( $atts['title'] ) . $atts['after_title'] : '';

		$plugin_slug = isset( $atts['plugin_slug'] ) ? $atts['plugin_slug'] : '';

		$args = [
			'slug' => $plugin_slug,
			'type' => 'plugin',
		];
		echo $wp_contributions->display_card( $args );

		// After widget hook.
		echo $atts['after_widget'];

	}


	/**
	 * Update form values as they are saved.
	 *
	 * @param array $new_instance New settings for this instance as input by the user.
	 * @param array $old_instance Old settings for this instance.
	 * @return array Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                = $old_instance;
		$instance['title']       = sanitize_text_field( $new_instance['title'] );
		$instance['plugin_slug'] = sanitize_text_field( $new_instance['plugin_slug'] );
		$this->flush_widget_cache();

		return $instance;
	}

	/**
	 * Back-end widget form with defaults.
	 *
	 * @param array $instance Current settings.
	 * @return void
	 */
	public function form( $instance ) {
		// If there are no settings, set up defaults.
		$instance = wp_parse_args( (array) $instance,
			[
				'title'       => $this->default_widget_title,
				'plugin_slug' => '',
			]
		);
		?>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'wp-contributions' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_html( $instance['title'] ); ?>" placeholder="optional" /></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'plugin_slug' ) ); ?>"><?php esc_html_e( 'Plugin Slug:', 'wp-contributions' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'plugin_slug' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'plugin_slug' ) ); ?>" type="text" value="<?php echo esc_html( $instance['plugin_slug'] ); ?>" /></p>

	<?php
	}
}
