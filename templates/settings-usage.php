<p>
	<?php esc_html_e( 'For all questions, feature request and communication with author and users of this plugin, use our', 'youtube-channel' ); ?>
	<a href="https://wordpress.org/support/plugin/youtube-channel/" target="_blank"><?php esc_html_e( 'community support forum', 'youtube-channel' ); ?></a>.
</p>
<h3><?php esc_html_e( 'How to use', 'youtube-channel' ); ?> <?php echo YTC_PLUGIN_NAME; ?></h3>
<p>
	<?php
	printf(
		// translators: %s is replaced with plugin name
		esc_html__( 'You can insert %s in couple different ways.', 'youtube-channel' ),
		YTC_PLUGIN_NAME
	);
	?>
</p>
<ol>
<li>
	<?php
	printf(
		/* translators: %1$s is replaced with plugin widget name
		 * %2$s is replaced with translated label Widget Area
		 * %3$s is replaced with link to WordPress Widgets page
		 */
		esc_html__(
			'Add %1$s to preferred %2$s on %3$s, and configure it there.',
			'youtube-channel'
		),
		sprintf(
			// translators: % is replaced with plugin name
			esc_html__( '%s Widget', 'youtube-channel' ),
			YTC_PLUGIN_NAME
		),
		esc_html__( 'Widget Area', 'youtube-channel' ),
		sprintf(
			'<a href="widgets.php">%s</a>',
			esc_html__( 'Widgets', 'youtube-channel' )
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
