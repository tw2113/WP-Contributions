<?php
/**
 * WDS WP Contributions Core Widget
 *
 * @version 1.1.0
 * @package WDS Contributions
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WDS_WP_Contributions_Core_Widget' ) ) :

	/**
	 * WDS WP Contributions Core Widget
	 */
	class WDS_WP_Contributions_Core_Widget extends WP_Widget {

		/**
		 * Unique identifier for this widget.
		 *
		 * Will also serve as the widget class.
		 *
		 * @var string
		 */
		protected $widget_slug = 'wp-contributions-core-widget';

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
			$this->widget_name          = esc_html__( 'WP Contributions Core Widget', 'wp-contributions' );
			$this->default_widget_title = esc_html__( 'My Core Contributions', 'wp-contributions' );
			parent::__construct(
				$this->widget_slug,
				$this->widget_name,
				[
					'classname'   => $this->widget_slug,
					'description' => esc_html__( 'Add a list of your accepted contributions to WordPress Core as a sidebar widget.', 'wp-contributions' ),
				]
			);
			add_action( 'save_post',    [ $this, 'flush_widget_cache' ] );
			add_action( 'deleted_post', [ $this, 'flush_widget_cache' ] );
			add_action( 'switch_theme', [ $this, 'flush_widget_cache' ] );
		}

		/**
		 * Back-end widget form with defaults.
		 *
		 * @param  array $instance  Current settings.
		 * @return  void
		 */
		function form( $instance ) {
			// Gracefully upgrade if the display count isn't already set.
			if ( ! isset( $instance['display-count'] ) ) {
				$instance['display-count'] = 5;
			}

			if ( $instance && isset( $instance['title'] ) ) {
				$title = esc_attr( $instance['title'] );
			} else {
				$title = esc_attr__( 'WP Core Contributions', 'wp-contributions' );
			}

			if ( $instance && isset( $instance['trac-user'] ) ) {
				$trac_user = esc_attr( $instance['trac-user'] );
			} else {
				$trac_user = esc_attr__( 'Trac Username', 'wp-contributions' );
			}

			if ( $instance && isset( $instance['display-count'] ) ) {
				$trac_count = absint( $instance['display-count'] );
			} else {
				$trac_count = 5;
			}
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
					<?php esc_html_e( 'Title:', 'wp-contributions' ); ?>
				</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'trac-user' ) ); ?>">
					<?php esc_html_e( 'Trac Username:', 'wp-contributions' ); ?>
				</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'trac-user' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'trac-user' ) ); ?>" type="text" value="<?php echo esc_attr( $trac_user ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'display-count' ) ); ?>">
					<?php esc_html_e( 'Display How Many Tickets?', 'wp-contributions' ); ?>
				</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'display-count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'display-count' ) ); ?>" type="text" value="<?php echo esc_attr( $trac_count ); ?>" />
			</p>
		<?php
		}

		/**
		 * Update form values as they are saved.
		 *
		 * @param  array $new_instance  New settings for this instance as input by the user.
		 * @param  array $old_instance  Old settings for this instance.
		 * @return array  Settings to save or bool false to cancel saving.
		 */
		function update( $new_instance, $old_instance ) {
			$instance                  = $old_instance;
			$instance['title']         = strip_tags( $new_instance['title'] );
			$instance['trac-user']     = strip_tags( $new_instance['trac-user'] );
			$instance['display-count'] = absint( $new_instance['display-count'] );
			$this->flush_widget_cache();
			return $instance;
		}

		/**
		 * Front-end display of widget.
		 *
		 * @param  array $args      The widget arguments set up when a sidebar is registered.
		 * @param  array $instance  The widget settings as set by user.
		 */
		function widget( $args, $instance ) {
			global $wp_contributions;

			$title = apply_filters( 'widget_title', $instance['title'] );
			$user = $instance['trac-user'];

			echo $args['before_widget'];

			if ( $title ) {
				echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
			}

			$card_args = [
				'slug'  => $user,
				'type'  => 'core',
				'count' => isset( $instance['display-count'] ) ? $instance['display-count'] : 5,
			];
			echo $wp_contributions->display_card( $card_args );

			echo $args['after_widget'];
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
	}
endif;
