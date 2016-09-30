<?php
/**
 * Codex Widget Template.
 *
 * @package WP Contributions
 * @since 1.1.0
 */

?>

<?php if ( isset( $items ) ) : ?>
	<div class="wp-contributions-codex codex-card card <?php echo esc_attr( $user ); ?>">
		<ul>
		<?php foreach ( (array) $items as $item ) :
			$link = 'http://codex.wordpress.org/index.php?title=' . $item['title'] . '&oldid=' . $item['revision'];

			if ( ( bool ) $item['function_ref'] ) {
		?>
			<li><?php printf( esc_html( 'Function: %1$s', 'wp-contributions' ), '<a href="' . esc_url( $link ) . '" title="' . esc_html( $item['description'] ) . '">' . esc_html( $item['title'] ) . '</a>' ); ?></li>
		<?php } else { ?>
			<li><?php printf( esc_html( 'For %1$s', 'wp-contributions' ), '<a href="' . esc_url( $link ) . '" title="' . esc_html( $item['description'] ) . '">' . esc_html( $item['title'] ) . '</a>' ); ?></li>
		<?php } ?>
		<?php endforeach; ?>
		</ul>
		<p>
			<a href="<?php echo esc_url( 'http://codex.wordpress.org/Special:Contributions/' . ucfirst( $user ) ); ?>">
				<?php
				if ( 2 == $total ) {
					esc_html_e( 'View both changes in the Codex.', 'wp-contributions' );
				} else {
					printf( esc_html_e( 'View the change in the Codex.', 'View all %d changes in the Codex.', $total, 'wp-contributions' ), esc_html( $total ) );
				}
				?>
			</a>
		</p>
	</div><!-- .wp-contributions-codex .codex-card .card -->
<?php endif; ?>