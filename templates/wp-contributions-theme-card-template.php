<?php
/**
 * Theme Card Template
 *
 * @package WP Contributions
 * @since 1.1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wp-contributions-theme theme-card card <?php echo esc_attr( $slug ); ?>">
	
	<header class="card-header theme-card-header">
	
		<div class="card-image theme-card-image">
			<a href="<?php echo esc_url( $link ); ?>"><img src="<?php echo esc_url( $icon ); ?>" /></a>
		</div>
		
		<h5 class="card-name theme-card-name"><a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $name ); ?></a></h5>
	
	</header><!-- .card-header .theme-card-header -->
	
	<?php if ( $description ) { ?>
		<div class="card-description theme-card-description">
			<p><?php echo esc_html( $description ); ?></p>
		</div><!-- .card-description .theme-card-description -->
	<?php } ?>
	
	<footer class="card-footer theme-card-footer">
		
		<div class="card-rating theme-card-rating">
			<?php echo wp_contributions_star_rating( array( 'rating' => intval( $rating ), 'type' => 'percent', 'number' => $num_ratings ) ); ?>
		</div>
		
		<div class="card-meta theme-card-meta">

			<div class="card-author theme-card-author">
				<span><?php echo esc_html__( 'Author: ', 'wp-contributions' ); ?></span> <?php echo esc_html( $author ); ?>
			</div>
			
			<div class="card-version theme-card-version">
				<span><?php echo esc_html__( 'Version: ', 'wp-contributions' ); ?></span> <?php echo esc_html( $version ); ?>
			</div>
			
			<div class="card-downloads theme-card-downloads">
				<span><?php echo esc_html__( ' Downloads:', 'wp-contributions' ); ?></span> <?php echo esc_html( $downloaded ); ?>
			</div>

			<div class="card-last-updated theme-card-last-updated">
				<span><?php echo esc_html__( 'Last Updated: ', 'wp-contributions' ); ?></span> <?php echo esc_html( $last_update ); ?>
			</div>
			
		</div><!-- .card-meta .theme-card-meta -->
	
	</footer><!-- .card-footer .theme-card-footer -->
	
</div><!-- .wp-contributions-theme .theme-card .card -->