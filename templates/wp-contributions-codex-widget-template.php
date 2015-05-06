<?php
/**
 * Codex Widget Template.
 *
 * @since     1.2
 */
?>

<?php if ( isset( $items ) ) : ?>
	<aside class="wp-contributions-plugin codex-contributions-list">
		<h3 class="widget-title"><?php _e('My Codex Contributions', 'wp-contributions'); ?></h3>
		
		<ul>
		<?php foreach ( (array) $items as $item ) :
			$link = 'http://codex.wordpress.org/index.php?title=' . $item['title'] . '&oldid=' . $item['revision'];
	
			if ( (bool)$item['function_ref'] ) {
		?>
			<li><?php printf( __( 'Function: %1$s', 'wp-contributions' ),
				'<a href="' . esc_url( $link ) . '" title="' . esc_html( $item['description'] ) . '">' . esc_html( $item['title'] ) . '</a>'
			); ?></li>
		<?php } else { ?>
			<li><?php printf( __( 'For %1$s', 'wp-contributions' ),
				'<a href="' . esc_url( $link ) . '" title="' . esc_html( $item['description'] ) . '">' . esc_html( $item['title'] ) . '</a>'
			); ?></li>
		<?php } ?>
		<?php endforeach; ?>
		</ul>
		<p>
			<a href="<?php echo esc_url( 'http://codex.wordpress.org/Special:Contributions/' . ucfirst( $user ) ); ?>">
				<?php
					if ( $total == 2 ) {
						_e( "View both changes in the Codex.", 'wp-contributions' );
					} else {
						printf( _n( "View the change in the Codex.", "View all %d changes in the Codex.", $total, 'wp-contributions' ), $total );
					}
				?>
			</a>
		</p>
	</aside><!-- .wp-contributions-plugin .codex-contributions-list -->
<?php endif; ?>