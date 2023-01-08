<h3><?php _e( 'How to use My YouTube Channel', 'wpau-yt-channel' ); ?></h3>
<p><?php _e( 'You can insert My YouTube Channel in couple different ways.', 'wpau-yt-channel' ); ?></p>
<ol>
<li>
	<?php
	printf(
		/* translators: %1$s is replaced with plugin widget label
		 * %2$s is replaced with translated label Widget Area
		 * %3$s is replaced with link to WordPress Widgets page
		 */
		esc_html__(
			'Add %1$s to preferred %2$s on %3$s, and configure it there.',
			'wpau-yt-channel'
		),
		esc_html__( 'My YouTube Channel Widget', 'wpau-yt-channel' ),
		esc_html__( 'Widget Area', 'wpau-yt-channel' ),
		sprintf(
			'<a href="widgets.php">%s</a>',
			esc_html__( 'Widgets', 'wpau-yt-channel' )
		)
	);
	?>
</li>
<li>
	<?php
	printf(
		// translators: %s is replaced with plugin shortcode
		esc_html__(
			'Insert shortcode %s to your page or text widget, and even modify default settings by shortcode parameters listed in section below.',
			'youtube_channel'
		),
		'<code>[youtube_channel]</code>'
	);
	?>
</li>
</ol>
