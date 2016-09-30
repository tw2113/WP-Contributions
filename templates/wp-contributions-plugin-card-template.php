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

<div class="wp-contributions-plugin plugin-card card <?php echo esc_attr( $slug ); ?>">
	
	<header class="card-header plugin-card-header">
		
		<div class="card-image plugin-card-image">
			<a href="<?php echo esc_url( $link ); ?>"><img src="<?php echo esc_url( $icon ); ?>" /></a>
		</div>
		
		<h5 class="card-name plugin-card-name"><a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $name ); ?></a></h5>
		
		<div class="card-info plugin-card-info">

			<div class="card-author plugin-card-author">
				<span><?php echo esc_html__( 'Author: ', 'wp-contributions' ); ?></span> <?php echo esc_html( $author ); ?>
			</div>
			
			<div class="card-contributors plugin-card-contributors">
				<span><?php echo esc_html__( 'Contributors: ', 'wp-contributions' ); ?></span> <?php echo esc_html( $contributors ); ?>
			</div>
		
			<div class="card-version plugin-card-version">
				<span><?php echo esc_html__( 'Version: ', 'wp-contributions' ); ?></span> <?php echo esc_html( $version ); ?>
			</div>
		
		</div><!-- .card-info .plugin-card-info -->
		
	</header><!-- .card-header .plugin-card-header -->
	
	<?php if ( $description ) { ?>
		<div class="card-description plugin-card-description">
			<p><?php echo esc_html( $description ); ?></p>
		</div>
	<?php } ?>
	
	<footer class="card-footer plugin-card-footer">

		<div class="card-rating plugin-card-rating">
			<?php echo wp_contributions_star_rating( array( 'rating' => intval( $rating ), 'type' => 'percent', 'number' => $num_ratings ) ); ?>
		</div>
		
		<div class="card-meta plugin-card-meta">
		
			<div class="card-downloads plugin-card-downloads">
				<span><?php echo esc_html__( ' Downloads:', 'wp-contributions' ); ?></span> <?php echo esc_html( $downloaded ); ?>
			</div>

			<div class="card-last-updated plugin-card-last-updated">
				<span><?php echo esc_html__( 'Last Updated: ', 'wp-contributions' ); ?></span> <?php echo esc_html( $last_update ); ?>
			</div>
		
		</div><!-- .card-meta .plugin-card-meta -->
	
	</footer><!-- .card-footer .plugin-card-footer -->
	
</div><!-- .wp-contributions-plugin .plugin-card .card -->