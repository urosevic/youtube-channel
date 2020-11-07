<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* YouTube Channel Widget */
class WPAU_YOUTUBE_CHANNEL_Widget extends WP_Widget {

	public function __construct() {

		global $wpau_youtube_channel;

		// Initialize Widget
		parent::__construct(
			$wpau_youtube_channel->plugin_slug,
			__( 'YouTube Channel', 'youtube-channel' ),
			array(
				'description' => __(
					'Serve YouTube videos from channel or playlist right to widget area',
					'youtube-channel'
				),
				'customize_selective_refresh' => true,
			)
		);

	} // END function __construct()

	// Outputs the content of the widget
	public function widget( $args, $instance ) {

		global $wpau_youtube_channel;

		$output = $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			$output .= $args['before_title'];
			$output .= apply_filters( 'widget_title', $instance['title'], $instance, $wpau_youtube_channel->plugin_slug );
			$output .= $args['after_title'];
		}
		$output .= $wpau_youtube_channel->output( $instance );
		$output .= $args['after_widget'];

		echo $output;

	} // END public function widget()

	public function form( $instance ) {
		global $wpau_youtube_channel;

		$defaults = get_option( 'youtube_channel_defaults' );

		// Outputs the options form for widget settings

		// General Options
		$title          = ! empty( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$class          = ! empty( $instance['class'] ) ? esc_attr( $instance['class'] ) : '';
		$vanity         = ! empty( $instance['vanity'] ) ? esc_attr( $instance['vanity'] ) : '';
		$channel        = ! empty( $instance['channel'] ) ? esc_attr( $instance['channel'] ) : '';
		$username       = ! empty( $instance['username'] ) ? esc_attr( $instance['username'] ) : '';
		$playlist       = ! empty( $instance['playlist'] ) ? esc_attr( $instance['playlist'] ) : '';

		$resource       = isset( $instance['resource'] ) ? intval( $instance['resource'] ) : intval( $defaults['resource'] ); // resource to use: channel, favorites, playlist

		$cache          = isset( $instance['cache'] ) ? intval( $instance['cache'] ) : intval( $defaults['cache'] );

		$fetch          = ! empty( $instance['fetch'] ) ? intval( $instance['fetch'] ) : intval( $defaults['fetch'] ); // items to fetch
		$num            = ! empty( $instance['num'] ) ? intval( $instance['num'] ) : intval( $defaults['num'] ); // number of items to show
		$skip           = isset( $instance['skip'] ) ? intval( $instance['skip'] ) : 0; // number of items to skip

		$privacy        = ! empty( $instance['privacy'] ) ? esc_attr( $instance['privacy'] ) : 0;
		$random         = ! empty( $instance['random'] ) ? esc_attr( $instance['random'] ) : 0;

		// Video Settings
		$ratio          = ! empty( $instance['ratio'] ) ? esc_attr( $instance['ratio'] ) : trim( $defaults['ratio'] );
		$width          = ! empty( $instance['width'] ) ? esc_attr( $instance['width'] ) : trim( $defaults['width'] );
		$responsive     = isset( $instance['responsive'] ) ? esc_attr( $instance['responsive'] ) : 1;

		$display        = ! empty( $instance['display'] ) ? esc_attr( $instance['display'] ) : trim( $defaults['display'] );
		$thumb_quality  = ! empty( $instance['thumb_quality'] ) ? esc_attr( $instance['thumb_quality'] ) : trim( $defaults['thumb_quality'] );
		$no_thumb_title = ! empty( $instance['no_thumb_title'] ) ? esc_attr( $instance['no_thumb_title'] ) : 0;

		$controls       = ! empty( $instance['controls'] ) ? esc_attr( $instance['controls'] ) : '';
		$autoplay       = ! empty( $instance['autoplay'] ) ? esc_attr( $instance['autoplay'] ) : '';
		$autoplay_mute  = ! empty( $instance['autoplay_mute'] ) ? esc_attr( $instance['autoplay_mute'] ) : '';
		$norel          = ! empty( $instance['norel'] ) ? esc_attr( $instance['norel'] ) : '';

		// Content Layout
		$showtitle      = ! empty( $instance['showtitle'] ) ? esc_attr( $instance['showtitle'] ) : 'none';
		$linktitle      = ! empty( $instance['linktitle'] ) ? esc_attr( $instance['linktitle'] ) : 0;
		$titletag       = ! empty( $instance['titletag'] ) ? strtolower( esc_attr( $instance['titletag'] ) ) : 'h3';
		$showdesc       = ! empty( $instance['showdesc'] ) ? esc_attr( $instance['showdesc'] ) : '';
		$modestbranding = ! empty( $instance['modestbranding'] ) ? esc_attr( $instance['modestbranding'] ) : '';
		$desclen        = ! empty( $instance['desclen'] ) ? esc_attr( $instance['desclen'] ) : 0;

		$hideanno       = ! empty( $instance['hideanno'] ) ? esc_attr( $instance['hideanno'] ) : '';

		// Link to Channel
		$link_to        = ! empty( $instance['link_to'] ) ? esc_attr( $instance['link_to'] ) : 'none';
		$goto_txt       = ! empty( $instance['goto_txt'] ) ? esc_attr( $instance['goto_txt'] ) : '';
		$popup_goto     = isset( $instance['popup_goto'] ) ? intval( $instance['popup_goto'] ) : $defaults['popup_goto'];
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Widget Title', 'youtube-channel' ); ?>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>" title="<?php _e( 'Title for widget', 'youtube-channel' ); ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'class' ); ?>"><?php _e( 'Custom CSS Class', 'youtube-channel' ); ?>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'class' ); ?>" name="<?php echo $this->get_field_name( 'class' ); ?>" value="<?php echo $class; ?>" title="<?php _e( 'Enter custom class for YTC block, if you wish to target block styling', 'youtube-channel' ); ?>" />
			</label>
		</p>
		<p>
		<?php
		printf(
			wp_kses(
				__(
					'Get your %1$s and %2$s from <a href="%3$s" target="_blank">here</a>.',
					'youtube-channel'
				),
				array( 'a' => array( 'href' => array(), 'target' => [] ) )
			),
			__( 'Channel ID', 'youtube-channel' ),
			__( 'Custom ID', 'youtube-channel' ),
			'https://www.youtube.com/account_advanced'
		);
		?>
		</p>
		<p class="half left glue-top">
			<label for="<?php echo $this->get_field_id( 'vanity' ); ?>">
				<?php _e( 'Vanity/Custom ID', 'youtube-channel' ); ?>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'vanity' ); ?>" name="<?php echo $this->get_field_name( 'vanity' ); ?>" value="<?php echo $vanity; ?>" title="<?php _e( 'YouTube Vanity/Custom ID from URL (part after /c/)', 'youtube-channel' ); ?>" />
			</label>
		</p>
		<p class="half right glue-top">
			<label for="<?php echo $this->get_field_id( 'channel' ); ?>">
				<?php _e( 'Channel ID', 'youtube-channel' ); ?>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'channel' ); ?>" name="<?php echo $this->get_field_name( 'channel' ); ?>" value="<?php echo $channel; ?>" title="<?php _e( 'Find Channel ID behind My Channel menu item in YouTube (ID have UC at the beginning)', 'youtube-channel' ); ?>" />
			</label>
		</p>
		<p class="half left glue-top">
			<label for="<?php echo $this->get_field_id( 'username' ); ?>">
				<?php _e( 'Legacy Username', 'youtube-channel' ); ?>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" value="<?php echo $username; ?>" title="<?php _e( 'Legacy YouTube username located behind /user/ part of channel URL (available only on old YouTube accounts)', 'youtube-channel' ); ?>" />
			</label>
		</p>
		<p class="half right glue-top">
			<label for="<?php echo $this->get_field_id( 'playlist' ); ?>">
				<?php _e( 'Playlist ID', 'youtube-channel' ); ?>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'playlist' ); ?>" name="<?php echo $this->get_field_name( 'playlist' ); ?>" value="<?php echo $playlist; ?>" title="<?php _e( 'Find Playlist ID in your playlists (ID have PL at the beginning)', 'youtube-channel' ); ?>" />
			</label>
		</p>
		<p class="half left glue-top">
			<label for="<?php echo $this->get_field_id( 'resource' ); ?>">
				<?php _e( 'Resource to use', 'youtube-channel' ); ?>
				<select class="widefat" id="<?php echo $this->get_field_id( 'resource' ); ?>" name="<?php echo $this->get_field_name( 'resource' ); ?>">
					<option value="0"<?php selected( $resource, 0 ); ?>><?php _e( 'Channel (User Uploads)', 'youtube-channel' ); ?></option>
					<option value="1"<?php selected( $resource, 1 ); ?>><?php _e( 'Favourites', 'youtube-channel' ); ?></option>
					<option value="3"<?php selected( $resource, 3 ); ?>><?php _e( 'Liked Videos', 'youtube-channel' ); ?></option>
					<option value="2"<?php selected( $resource, 2 ); ?>><?php _e( 'Playlist', 'youtube-channel' ); ?></option>
				</select>
			</label>
		</p>
		<p class="half right glue-top">
			<label for="<?php echo $this->get_field_id( 'cache' ); ?>">
				<?php _e( 'Cache feed', 'youtube-channel' ); ?>
				<select class="widefat" id="<?php echo $this->get_field_id( 'cache' ); ?>" name="<?php echo $this->get_field_name( 'cache' ); ?>">
					<option value="0"<?php selected( $cache, 0 ); ?>><?php _e( 'Do not cache', 'youtube-channel' ); ?></option>
					<?php echo $wpau_youtube_channel->cache_time( $cache ); ?>
				</select>
			</label>
		</p>
		<p class="half left glue-top">
			<label for="<?php echo $this->get_field_id( 'fetch' ); ?>">
				<?php _e( 'Fetch', 'youtube-channel' ); ?>
				<input class="small-text" id="<?php echo $this->get_field_id( 'fetch' ); ?>" name="<?php echo $this->get_field_name( 'fetch' ); ?>" type="number" min="2" value="<?php echo $fetch; ?>" title="<?php _e( 'Number of videos that will be used for random pick (min 2, max 50, default 25)', 'youtube-channel' ); ?>" /> 
				<small><?php _e( 'video(s)', 'youtube-channel' ); ?></small>
			</label>
		</p>
		<p class="half right glue-top">
			<label for="<?php echo $this->get_field_id( 'num' ); ?>">
				<?php _e( 'Show', 'youtube-channel' ); ?>
				<input class="small-text" id="<?php echo $this->get_field_id( 'num' ); ?>" name="<?php echo $this->get_field_name( 'num' ); ?>" type="number" min="1" value="<?php echo ( $num ) ? $num : '1'; ?>" title="<?php _e( 'Number of videos to display', 'youtube-channel' ); ?>" />
				<small><?php _e( 'video(s)', 'youtube-channel' ); ?></small>
			</label>
		</p>
		<p class="half left glue-top">
			<label for="<?php echo $this->get_field_id( 'skip' ); ?>">
				<?php _e( 'Skip', 'youtube-channel' ); ?>
				<input class="small-text" id="<?php echo $this->get_field_id( 'skip' ); ?>" name="<?php echo $this->get_field_name( 'skip' ); ?>" type="number" min="0" max="49" value="<?php echo ( $skip ) ? $skip : '0'; ?>" title="<?php _e( 'Number of videos to skip', 'youtube-channel' ); ?>" />
				<small><?php _e( 'video(s)', 'youtube-channel' ); ?></small>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'privacy' ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( (bool) $privacy, true ); ?> id="<?php echo $this->get_field_id( 'privacy' ); ?>" name="<?php echo $this->get_field_name( 'privacy' ); ?>" title="<?php _e( 'Enable this option to protect your visitors privacy', 'youtube-channel' ); ?>" />
				<?php printf( __( 'Enable <a href="%s" target="_blank">privacy-enhanced mode</a>', 'youtube-channel' ), 'https://support.google.com/youtube/bin/answer.py?hl=en-GB&answer=171780' ); ?>
			</label>
			<br />
			<label for="<?php echo $this->get_field_id( 'random' ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( (bool) $random, true ); ?> id="<?php echo $this->get_field_id( 'random' ); ?>" name="<?php echo $this->get_field_name( 'random' ); ?>" title="<?php _e( 'Get random videos of all fetched from channel or playlist', 'youtube-channel' ); ?>" />
				<?php _e( 'Show random video from resource', 'youtube-channel' ); ?>
				<br />
				<small>(<?php _e( 'Ignored if you set "Embedded Playlist" for "What to display?"', 'youtube-channel' ); ?>)</small>
			</label>
		</p>

		<h4><?php _e( 'Video Settings', 'youtube-channel' ); ?></h4>
		<p>
			<label for="<?php echo $this->get_field_id( 'ratio' ); ?>">
				<?php _e( 'Aspect ratio', 'youtube-channel' ); ?>
				<select class="widefat" id="<?php echo $this->get_field_id( 'ratio' ); ?>" name="<?php echo $this->get_field_name( 'ratio' ); ?>">
					<option value="3"<?php selected( $ratio, 3 ); ?>>16:9</option>
					<option value="1"<?php selected( $ratio, 1 ); ?>>4:3</option>
				</select>
			</label>
			<br />
			<label for="<?php echo $this->get_field_id( 'responsive' ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( (bool) $responsive, true ); ?> id="<?php echo $this->get_field_id( 'responsive' ); ?>" name="<?php echo $this->get_field_name( 'responsive' ); ?>" />
				<?php _e( 'Responsive video <small>(distribute one full width item per row)</small>', 'youtube-channel' ); ?>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'width' ); ?>">
				<?php _e( 'Initial width', 'youtube-channel' ); ?>
				<input class="small-text" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" type="number" min="32" value="<?php echo $width; ?>" title="<?php _e( 'Set video width in pixels', 'youtube-channel' ); ?>" />
				<small>px (<?php _e( 'default', 'youtube-channel' ); ?> 306)</small>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'display' ); ?>">
				<?php _e( 'What to display?', 'youtube-channel' ); ?>
				<select class="widefat" id="<?php echo $this->get_field_id( 'display' ); ?>" name="<?php echo $this->get_field_name( 'display' ); ?>" onchange="ytcToggle('display', '<?php echo $this->get_field_id(''); ?>');">
					<option value="thumbnail"<?php selected( $display, 'thumbnail' ); ?>><?php _e( 'Thumbnail', 'youtube-channel' ); ?></option>
					<option value="iframe"<?php selected( $display, 'iframe' ); ?>><?php _e( 'HTML5 (iframe)', 'youtube-channel' ); ?></option>
					<option value="iframe2"<?php selected( $display, 'iframe2' ); ?>><?php _e( 'HTML5 (iframe) Asynchronous', 'youtube-channel' ); ?></option>
					<option value="playlist"<?php selected( $display, 'playlist' ); ?>><?php _e( 'Embedded Playlist', 'youtube-channel' ); ?></option>
				</select>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'controls' ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( (bool) $controls, true ); ?> id="<?php echo $this->get_field_id( 'controls' ); ?>" name="<?php echo $this->get_field_name( 'controls' ); ?>" />
				<?php _e( 'Hide player controls', 'youtube-channel' ); ?>
			</label>
			<br />
			<label for="<?php echo $this->get_field_id( 'modestbranding' ); ?>" title="<?php _e( "Hide a YouTube logo from YouTube player control bar. Note that a small YouTube text label will still display in the upper-right corner of a paused video when the user's mouse pointer hovers over the player.", 'youtube-channel' ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( (bool) $modestbranding, true ); ?> id="<?php echo $this->get_field_id( 'modestbranding' ); ?>" name="<?php echo $this->get_field_name( 'modestbranding' ); ?>" />
				<?php _e( 'Hide YouTube Logo (does not work for all videos)', 'youtube-channel' ); ?>
			</label>
			<br />
			<label for="<?php echo $this->get_field_id( 'norel' ); ?>" title="<?php _e( 'Enable this option to show after finished playback only related videos that come from the same channel as the video that was just played', 'youtube-channel' ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( (bool) $norel, true ); ?> id="<?php echo $this->get_field_id( 'norel' ); ?>" name="<?php echo $this->get_field_name( 'norel' ); ?>" />
				<?php _e( 'Allow only channel related videos', 'youtube-channel' ); ?>
			</label>
			<br />
			<label for="<?php echo $this->get_field_id( 'autoplay' ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( (bool) $autoplay, true ); ?> id="<?php echo $this->get_field_id( 'autoplay' ); ?>" name="<?php echo $this->get_field_name( 'autoplay' ); ?>" />
				<?php _e( 'Autoplay video or playlist', 'youtube-channel' ); ?>
			</label>
			<br />
			<label for="<?php echo $this->get_field_id( 'autoplay_mute' ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( (bool) $autoplay_mute, true ); ?> id="<?php echo $this->get_field_id( 'autoplay_mute' ); ?>" name="<?php echo $this->get_field_name( 'autoplay_mute' ); ?>" />
				<?php _e( 'Mute video on autoplay', 'youtube-channel' ); ?>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'thumb_quality' ); ?>">
				<?php _e( 'Thumbnail Quality', 'youtube-channel' ); ?>
				<select class="widefat" id="<?php echo $this->get_field_id( 'thumb_quality' ); ?>" name="<?php echo $this->get_field_name( 'thumb_quality' ); ?>">
					<option value="default"<?php selected( $thumb_quality, 'default' ); ?>><?php _e( 'Default Quality (120x90px)', 'youtube-channel' ); ?></option>
					<option value="mqdefault"<?php selected( $thumb_quality, 'mqdefault' ); ?>><?php _e( 'Medium Quality (320x180px)', 'youtube-channel' ); ?></option>
					<option value="hqdefault"<?php selected( $thumb_quality, 'hqdefault' ); ?>><?php _e( 'High Quality (480x360px)', 'youtube-channel' ); ?></option>
					<option value="sddefault"<?php selected( $thumb_quality, 'sddefault' ); ?>><?php _e( 'Standard Definition (640x480px)', 'youtube-channel' ); ?></option>
					<option value="maxresdefault"<?php selected( $thumb_quality, 'maxresdefault' ); ?>><?php _e( 'Maximum Resolution (1280x720px)', 'youtube-channel' ); ?></option>
				</select>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'no_thumb_title' ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( (bool) $no_thumb_title, true ); ?> id="<?php echo $this->get_field_id( 'no_thumb_title' ); ?>" name="<?php echo $this->get_field_name( 'no_thumb_title' ); ?>" />
				<?php _e( 'Disable thumbnail tooltip', 'youtube-channel' ); ?>
			</label>
		</p>

		<h4><?php _e( 'Content Layout', 'youtube-channel' ); ?></h4>
		<p>
			<label for="<?php echo $this->get_field_id( 'showtitle' ); ?>">
				<?php _e( 'Show video title', 'youtube-channel' ); ?>
				<select class="widefat" id="<?php echo $this->get_field_id( 'showtitle' ); ?>" name="<?php echo $this->get_field_name( 'showtitle' ); ?>" onchange="ytcToggle('title', '<?php echo $this->get_field_id(''); ?>');">
					<option value="none"<?php selected( $showtitle, 'none' ); ?>><?php _e( 'Hide title', 'youtube-channel' ); ?></option>
					<option value="above"<?php selected( $showtitle, 'above' ); ?>><?php _e( 'Above video/thumbnail', 'youtube-channel' ); ?></option>
					<option value="below"<?php selected( $showtitle, 'below' ); ?>><?php _e( 'Below video/thumbnail', 'youtube-channel' ); ?></option>
					<option value="inside"<?php selected( $showtitle, 'inside' ); ?>><?php _e( 'Inside thumbnail, top aligned', 'youtube-channel' ); ?></option>
					<option value="inside_b"<?php selected( $showtitle, 'inside_b' ); ?>><?php _e( 'Inside thumbnail, bototm aligned', 'youtube-channel' ); ?></option>
				</select>
			</label>
			<br />
			<label for="<?php echo $this->get_field_id( 'linktitle' ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( (bool) $linktitle, true ); ?> id="<?php echo $this->get_field_id( 'linktitle' ); ?>" name="<?php echo $this->get_field_name( 'linktitle' ); ?>" title="<?php _e( 'Enable this option to link outside title to video', 'youtube-channel' ); ?>" />
				<?php _e( 'Link outside title to video', 'youtube-channel' ); ?>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'titletag' ); ?>">
				<?php _e( 'Title HTML tag', 'youtube-channel' ); ?>
				<select class="widefat" id="<?php echo $this->get_field_id( 'titletag' ); ?>" name="<?php echo $this->get_field_name( 'titletag' ); ?>">
					<?php
					foreach ( array( 'h3', 'h4', 'h5', 'div', 'span', 'strong' ) as $tag ) {
						printf(
							'<option value="%1$s" %2$s>%1$s</option>',
							$tag,
							selected( $titletag, $tag )
						);
					}
					?>
				</select>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'hideanno' ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( (bool) $hideanno, true ); ?> id="<?php echo $this->get_field_id( 'hideanno' ); ?>" name="<?php echo $this->get_field_name( 'hideanno' ); ?>" />
				<?php _e( 'Hide annotations from video', 'youtube-channel' ); ?>
			</label>
			<br />
			<label for="<?php echo $this->get_field_id( 'showdesc' ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( (bool) $showdesc, true ); ?> id="<?php echo $this->get_field_id( 'showdesc' ); ?>" name="<?php echo $this->get_field_name( 'showdesc' ); ?>" />
				<?php _e( 'Show video description', 'youtube-channel' ); ?>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'desclen' ); ?>">
				<?php _e( 'Description length', 'youtube-channel' ); ?>
				<input class="small-text" id="<?php echo $this->get_field_id( 'desclen' ); ?>" name="<?php echo $this->get_field_name( 'desclen' ); ?>" type="number" value="<?php echo $desclen; ?>" title="<?php _e( 'Set number of characters to cut down video description to (0 means full length)', 'youtube-channel' ); ?>" />
				<small>(0 = full)</small>
			</label>
		</p>

		<h4><?php _e( 'Link to Channel', 'youtube-channel' ); ?></h4>
		<p class="glue-top">
			<label for="<?php echo $this->get_field_id( 'goto_txt' ); ?>">
				<?php _e( 'Link text', 'youtube-channel' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'goto_txt' ); ?>" name="<?php echo $this->get_field_name( 'goto_txt' ); ?>" type="text" value="<?php echo $goto_txt; ?>" title="<?php sprintf( __( 'Default: Visit our YouTube channel. You can use placeholders %1$s, %2$s and %3$s.', 'youtube-channel' ), '%vanity%', '%channel%', '%username%' ); ?>" placeholder="<?php _e( 'Visit our YouTube channel', 'youtube-channel' ); ?>" />
			</label>
		</p>
		<p class="half left glue-top">
			<label for="<?php echo $this->get_field_id( 'link_to' ); ?>">
				<?php _e( 'Link type', 'youtube-channel' ); ?>
				<select class="widefat" id="<?php echo $this->get_field_id( 'link_to' ); ?>" name="<?php echo $this->get_field_name( 'link_to' ); ?>">
					<option value="none"<?php selected( $link_to, 'none' ); ?>><?php _e( 'Hide link', 'youtube-channel' ); ?></option>
					<option value="vanity"<?php selected( $link_to, 'vanity' ); ?>><?php _e( 'Link to Vanity customized URL', 'youtube-channel' ); ?></option>
					<option value="channel"<?php selected( $link_to, 'channel' ); ?>><?php _e( 'Link to Channel page URL', 'youtube-channel' ); ?></option>
					<option value="legacy"<?php selected( $link_to, 'legacy' ); ?>><?php _e( 'Link to Legacy username page', 'youtube-channel' ); ?></option>
				</select>
			</label>
		</p>
		<p class="half right glue-top">
			<label for="<?php echo $this->get_field_id( 'popup_goto' ); ?>">
				<?php _e( 'Link behaviour', 'youtube-channel' ); ?>
				<select class="widefat" id="<?php echo $this->get_field_id( 'popup_goto' ); ?>" name="<?php echo $this->get_field_name( 'popup_goto' ); ?>">
					<option value="0"<?php selected( $popup_goto, 0 ); ?>><?php _e( 'Open link in same window', 'youtube-channel' ); ?></option>
					<option value="1"<?php selected( $popup_goto, 1 ); ?>><?php _e( 'Open link in new window (JavaScript)', 'youtube-channel' ); ?></option>
					<option value="2"<?php selected( $popup_goto, 2 ); ?>><?php _e( 'Open link in new window (target="blank")', 'youtube-channel' ); ?></option>
				</select>
			</label>
		</p>

		<h4><?php _e( 'Does not work?', 'youtube-channel' ); ?></h4>
		<p>
			<small>
			<?php
			printf(
				wp_kses(
					__(
						'Carefully read <a href="%1$s" target="_faq">%2$s</a> before you contact us. If that does not help, <a href="%3$s" target="_blank">get JSON file</a> and send it to <a href="%4$s" target="_support">support forum</a> along with other details noted in <a href="%5$s" target=_blank">this article</a>.',
						'youtube-channel'
					),
					array( 'a' => array( 'href' => array() ) )
				),
				'https://wordpress.org/plugins/youtube-channel/faq/',
				__( 'FAQ', 'youtube-channel' ),
				"?ytc_debug_json_for={$this->number}",
				'https://wordpress.org/support/plugin/youtube-channel',
				'https://wordpress.org/support/topic/ytc3-read-before-you-post-support-question-or-report-bug'
			);
			?>
			</small>
		</p>
		<?php
	} // END public function form()

	public function update( $new_instance, $old_instance ) {

		// processes widget options to be saved
		$instance                   = $old_instance;
		$instance['title']          = strip_tags( $new_instance['title'] );
		$instance['class']          = strip_tags( $new_instance['class'] );
		$instance['channel']        = strip_tags( $new_instance['channel'] );
		$instance['username']       = strip_tags( $new_instance['username'] );
		$instance['playlist']       = strip_tags( $new_instance['playlist'] );
		$instance['vanity']         = strip_tags( $new_instance['vanity'] );
		$instance['num']            = $new_instance['num'];
		$instance['skip']           = $new_instance['skip'];
		$instance['resource']       = $new_instance['resource'];
		$instance['cache']          = $new_instance['cache'];
		$instance['random']         = isset( $new_instance['random'] ) ? $new_instance['random'] : false;
		$instance['fetch']          = $new_instance['fetch'];

		$instance['goto_txt']       = strip_tags( $new_instance['goto_txt'] );
		$instance['popup_goto']     = $new_instance['popup_goto'];
		$instance['link_to']        = $new_instance['link_to'];

		$instance['showtitle']      = isset( $new_instance['showtitle'] ) ? $new_instance['showtitle'] : 'none';
		$instance['linktitle']      = isset( $new_instance['linktitle'] ) ? $new_instance['linktitle'] : false;
		$instance['titletag']       = isset( $new_instance['titletag'] ) ? $new_instance['titletag'] : 'h3';
		$instance['showdesc']       = isset( $new_instance['showdesc'] ) ? $new_instance['showdesc'] : false;
		$instance['desclen']        = strip_tags( $new_instance['desclen'] );
		$instance['width']          = strip_tags( $new_instance['width'] );
		$instance['responsive']     = isset( $new_instance['responsive'] ) ? $new_instance['responsive'] : '';

		$instance['display']        = strip_tags( $new_instance['display'] );
		$instance['thumb_quality']  = strip_tags( $new_instance['thumb_quality'] );
		$instance['no_thumb_title'] = isset( $new_instance['no_thumb_title'] ) ? $new_instance['no_thumb_title'] : false;
		$instance['autoplay']       = isset( $new_instance['autoplay'] ) ? $new_instance['autoplay'] : false;
		$instance['autoplay_mute']  = isset( $new_instance['autoplay_mute'] ) ? $new_instance['autoplay_mute'] : false;
		$instance['norel']          = isset( $new_instance['norel'] ) ? $new_instance['norel'] : false;
		$instance['modestbranding'] = isset( $new_instance['modestbranding'] ) ? $new_instance['modestbranding'] : false;

		$instance['ratio']          = strip_tags( $new_instance['ratio'] );
		$instance['controls']       = isset( $new_instance['controls'] ) ? $new_instance['controls'] : false;
		$instance['hideanno']       = isset( $new_instance['hideanno'] ) ? $new_instance['hideanno'] : '';
		$instance['privacy']        = isset( $new_instance['privacy'] ) ? $new_instance['privacy'] : '';

		return $instance;

	} // END public function update()

} // END class WPAU_YOUTUBE_CHANNEL_Widget()

// Register WPAU_YOUTUBE_CHANNEL_Widget widget
function wpau_register_youtube_channel_widget() {
	register_widget( 'WPAU_YOUTUBE_CHANNEL_Widget' );
}
add_action( 'widgets_init', 'wpau_register_youtube_channel_widget' );
