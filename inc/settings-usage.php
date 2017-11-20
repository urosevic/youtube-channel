<h3><?php _e( 'How to use YouTube Channel', 'youtube-channel' ); ?></h3>
<p><?php _e( 'You can insert YouTube Channel in couple different ways.', 'youtube-channel' ); ?></p>
<ol>
<li><?php
printf(
	__(
		'Add %1$s to preferred %2$s, and configure it there.',
		'youtube-channel'
	),
	__( 'YouTube Channel Widget', 'youtube-channel' ),
	sprintf(
		'<a href="widgets.php">%s</a>',
		__( 'Widget Area', 'youtube-channel' )
	)
); ?></li>
<li><?php
printf(
	__(
		'Insert shortcode %s to your page or text widget, and even modify default settings by shortcode parameters listed in section below.',
		'youtube_channel'
	),
	'<code>[youtube_channel]</code>'
); ?></li>
</ol>
