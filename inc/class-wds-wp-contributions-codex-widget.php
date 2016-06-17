<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists('WDS_WP_Contributions_Codex_Widget') ) :

	class WDS_WP_Contributions_Codex_Widget extends WP_Widget {

		/**
		 * Unique identifier for this widget.
		 *
		 * Will also serve as the widget class.
		 *
		 * @var string
		 */
		protected $widget_slug = 'wp-contributions-codex-widget';

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
			$this->widget_name          = __( 'WP Contributions Codex Widget', 'wp-contributions' );
			$this->default_widget_title = __( 'My Codex Contributions', 'wp-contributions' );
			parent::__construct(
				$this->widget_slug,
				$this->widget_name,
				array(
					'classname'   => $this->widget_slug,
					'description' => __( 'Add a list of your contributions to the WordPress Codex as a sidebar widget.', 'wp-contributions' ),
				)
			);
			add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
			add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
			add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
		}

		function form( $instance ) {
			// Gracefully upgrade if the display count isn't already set.
			if ( ! isset( $instance['display-count'] ) ) {
				$instance['display-count'] = 5;
			}

			if ( $instance && isset( $instance['title'] ) ) {
				$title = esc_attr( $instance['title'] );
			} else {
				$title = esc_attr__( 'WP Codex Contributions', 'wp-contributions' );
			}

			if ( $instance && isset( $instance['codex-user'] ) ) {
				$codex_user = esc_attr( $instance['codex-user'] );
			} else {
				$codex_user = esc_attr__( 'Codex Username', 'wp-contributions' );
			}

			if ( $instance && isset( $instance['display-count'] ) ) {
				$codex_count = absint( $instance['display-count'] );
			} else {
				$codex_count = 5;
			}
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'wp-contributions' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_html( $title ); ?>" />

				<label for="<?php echo esc_attr( $this->get_field_id( 'codex-user' ) ); ?>"><?php esc_html_e( 'Codex Username:', 'wp-contributions' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'codex-user' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'codex-user' ) ); ?>" type="text" value="<?php echo esc_attr( $codex_user ); ?>" />

				<label for="<?php echo esc_attr( $this->get_field_id( 'display-count' ) ); ?>"><?php esc_html_e( 'Display How Many Changes?', 'wp-contributions' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'display-count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'display-count' ) ); ?>" type="text" value="<?php echo esc_attr( $codex_count ); ?>" />
			</p>
		<?php
		}

		function update( $new_instance, $old_instance ) {
			$instance                  = $old_instance;
			$instance['title']         = strip_tags( $new_instance['title'] );
			$instance['codex-user']    = strip_tags( $new_instance['codex-user'] );
			$instance['display-count'] = absint( $new_instance['display-count'] );
			$this->flush_widget_cache();
			return $instance;
		}

		function widget( $args, $instance ){
			global $wp_contributions;

			$title = apply_filters( 'widget_title', $instance['title'] );
			// Mediawiki usernames uppercase on 1st letter & case-specific.
			$user = $instance['codex-user'];

			echo $args['before_widget'];

			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			$card_args = array(
				'slug'  => $user,
				'type'  => 'codex',
				'count' => isset( $instance['display-count'] ) ? $instance['display-count'] : 5,
			);
			$wp_contributions->display_card( $card_args );

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
