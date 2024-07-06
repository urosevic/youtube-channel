<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* My YouTube Channel Widget */
class Wpau_My_Youtube_Channel_Widget extends WP_Widget {

	public function __construct() {
		// Initialize Widget
		parent::__construct(
			YTC_PLUGIN_SLUG,
			__( 'My YouTube Channel', 'wpau-yt-channel' ),
			array(
				'description'                 => __(
					'Serve YouTube videos from channel or playlist right to widget area',
					'wpau-yt-channel'
				),
				'customize_selective_refresh' => true,
			)
		);
	} // END function __construct()

	// Outputs the content of the widget
	public function widget( $args, $instance ) {
		global $wpau_my_youtube_channel;

		// Skip rendering attempt if no widget_id is available.
		if ( empty( $args['widget_id'] ) && true !== boolval( $wpau_my_youtube_channel->defaults['block_preview'] ) ) {
			return;
		}

		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'], $instance, YTC_PLUGIN_SLUG ) . $args['after_title'];
		}
		echo $wpau_my_youtube_channel->generate_ytc_block( $instance );
		echo $args['after_widget'];
	} // END public function widget()

	public function form( $instance ) {
		global $wpau_my_youtube_channel;

		$defaults = get_option( 'youtube_channel_defaults' );

		// Outputs the options form for widget settings

		// General Options
		$title    = ! empty( $instance['title'] ) ? sanitize_text_field( $instance['title'] ) : '';
		$class    = ! empty( $instance['class'] ) ? sanitize_html_classes( $instance['class'] ) : '';
		$handle   = ! empty( $instance['handle'] ) ? sanitize_text_field( $instance['handle'] ) : $defaults['handle'];
		$vanity   = ! empty( $instance['vanity'] ) ? sanitize_text_field( $instance['vanity'] ) : $defaults['vanity']; // deprecated
		$channel  = ! empty( $instance['channel'] ) ? ytc_sanitize_api_key( $instance['channel'] ) : $defaults['channel'];
		$username = ! empty( $instance['username'] ) ? sanitize_key( $instance['username'] ) : $defaults['username']; // deprecated
		$playlist = ! empty( $instance['playlist'] ) ? ytc_sanitize_api_key( $instance['playlist'] ) : $defaults['playlist'];
		$resource = isset( $instance['resource'] ) ? intval( $instance['resource'] ) : intval( $defaults['resource'] ); // resource to use: channel, favorites, playlist
		$cache    = isset( $instance['cache'] ) ? intval( $instance['cache'] ) : intval( $defaults['cache'] );
		$fetch    = ! empty( $instance['fetch'] ) ? intval( $instance['fetch'] ) : intval( $defaults['fetch'] ); // items to fetch
		$num      = ! empty( $instance['num'] ) ? intval( $instance['num'] ) : intval( $defaults['num'] ); // number of items to show
		$skip     = isset( $instance['skip'] ) ? intval( $instance['skip'] ) : 0; // number of items to skip
		$privacy  = ! empty( $instance['privacy'] ) ? boolval( $instance['privacy'] ) : false;
		$random   = ! empty( $instance['random'] ) ? boolval( $instance['random'] ) : false;

		// Video Settings
		$ratio          = ! empty( $instance['ratio'] ) ? intval( $instance['ratio'] ) : intval( $defaults['ratio'] );
		$width          = ! empty( $instance['width'] ) ? intval( $instance['width'] ) : intval( $defaults['width'] );
		$responsive     = isset( $instance['responsive'] ) ? boolval( $instance['responsive'] ) : true;
		$display        = ! empty( $instance['display'] ) ? sanitize_key( $instance['display'] ) : sanitize_key( $defaults['display'] );
		$thumb_quality  = ! empty( $instance['thumb_quality'] ) ? sanitize_key( $instance['thumb_quality'] ) : sanitize_key( $defaults['thumb_quality'] );
		$no_thumb_title = ! empty( $instance['no_thumb_title'] ) ? boolval( $instance['no_thumb_title'] ) : false;
		$controls       = ! empty( $instance['controls'] ) ? boolval( $instance['controls'] ) : false;
		$autoplay       = ! empty( $instance['autoplay'] ) ? boolval( $instance['autoplay'] ) : false;
		$autoplay_mute  = ! empty( $instance['autoplay_mute'] ) ? boolval( $instance['autoplay_mute'] ) : false;
		$norel          = ! empty( $instance['norel'] ) ? boolval( $instance['norel'] ) : false;

		// Content Layout
		$showtitle      = ! empty( $instance['showtitle'] ) ? sanitize_key( $instance['showtitle'] ) : 'none';
		$linktitle      = ! empty( $instance['linktitle'] ) ? boolval( $instance['linktitle'] ) : false;
		$titletag       = ! empty( $instance['titletag'] ) ? strtolower( esc_attr( $instance['titletag'] ) ) : 'h3';
		$showdesc       = ! empty( $instance['showdesc'] ) ? boolval( $instance['showdesc'] ) : false;
		$modestbranding = ! empty( $instance['modestbranding'] ) ? boolval( $instance['modestbranding'] ) : false;
		$desclen        = ! empty( $instance['desclen'] ) ? intval( $instance['desclen'] ) : 0;
		$hideanno       = ! empty( $instance['hideanno'] ) ? boolval( $instance['hideanno'] ) : false;

		// Link to Channel
		$link_to    = ! empty( $instance['link_to'] ) ? sanitize_key( $instance['link_to'] ) : 'none';
		$goto_txt   = ! empty( $instance['goto_txt'] ) ? sanitize_text_field( $instance['goto_txt'] ) : '';
		$popup_goto = isset( $instance['popup_goto'] ) ? intval( $instance['popup_goto'] ) : intval( $defaults['popup_goto'] );
		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_html_e( 'Widget Title', 'wpau-yt-channel' ); ?>
				<input type="text"
					class="widefat"
					id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
					value="<?php echo esc_html( $title ); ?>"
					title="<?php esc_html_e( 'Title for widget', 'wpau-yt-channel' ); ?>"
					/>
			</label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'class' ) ); ?>">
				<?php esc_html_e( 'Custom CSS Class', 'wpau-yt-channel' ); ?>
				<input type="text"
					class="widefat"
					id="<?php echo esc_attr( $this->get_field_id( 'class' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'class' ) ); ?>"
					value="<?php echo sanitize_html_classes( $class ); ?>"
					title="<?php esc_html_e( 'Enter custom class for YTC block, if you wish to target block styling', 'wpau-yt-channel' ); ?>"
					/>
			</label>
		</p>

		<div class="halfs">
			<p class="mt-0">
				<label for="<?php echo esc_attr( $this->get_field_id( 'channel' ) ); ?>">
					<?php esc_html_e( 'Channel ID', 'wpau-yt-channel' ); ?>
					<input type="text"
						class="widefat"
						id="<?php echo esc_attr( $this->get_field_id( 'channel' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'channel' ) ); ?>"
						value="<?php echo esc_html( $channel ); ?>"
						title="<?php esc_html_e( 'Channel ID starts with UC', 'wpau-yt-channel' ); ?>"
						/>
					<span class="description">
						<?php echo '<a href="https://www.youtube.com/account_advanced" target="_blank">' . esc_html__( 'Get it from here', 'wpau-yt-channel' ) . '</a>'; ?>
					</span>
				</label>
			</p>
			<p class="mt-0">
				<label for="<?php echo esc_attr( $this->get_field_id( 'playlist' ) ); ?>">
					<?php esc_html_e( 'Playlist ID', 'wpau-yt-channel' ); ?>
					<input type="text" 
						class="widefat"
						id="<?php echo esc_attr( $this->get_field_id( 'playlist' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'playlist' ) ); ?>"
						value="<?php echo esc_html( $playlist ); ?>"
						title="<?php esc_html_e( 'Playlist starts with PL', 'wpau-yt-channel' ); ?>"
						/>
					<span class="description">
						<?php echo '<a href="' . esc_url( 'https://www.youtube.com/channel/' . $channel . '/playlists' ) . '" target="_blank">' . esc_html__( 'Get it from here', 'wpau-yt-channel' ) . '</a>'; ?>
					</span>
				</label>
			</p>
		</div><!-- .halfs -->

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'handle' ) ); ?>">
				<?php esc_html_e( 'YouTube handle', 'wpau-yt-channel' ); ?>
				<input type="text"
					class="widefat"
					id="<?php echo esc_attr( $this->get_field_id( 'handle' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'handle' ) ); ?>"
					value="<?php echo esc_html( $handle ); ?>"
					title="<?php esc_html_e( 'Playlist starts with PL', 'wpau-yt-channel' ); ?>"
					/>
				<span class="description">
					<?php echo '<a href="https://www.youtube.com/handle" target="_blank">' . esc_html__( 'Get it from here', 'wpau-yt-channel' ) . '</a>'; ?>
				</span>
			</label>
		</p>

		<div class="halfs">
			<p class="mt-0 deprecated">
				<label for="<?php echo esc_attr( $this->get_field_id( 'vanity' ) ); ?>">
					<?php esc_html_e( 'Vanity/Custom ID (deprecated)', 'wpau-yt-channel' ); ?>
					<input type="text"
						class="widefat"
						id="<?php echo esc_attr( $this->get_field_id( 'vanity' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'vanity' ) ); ?>"
						value="<?php echo esc_html( $vanity ); ?>"
						title="<?php esc_html_e( 'Legacy YouTube Vanity/Custom ID from URL (part after /c/)', 'wpau-yt-channel' ); ?>" />
				</label>
			</p>
			<p class="mt-0 deprecated">
				<label for="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>">
					<?php esc_html_e( 'Legacy Username (deprecated)', 'wpau-yt-channel' ); ?>
					<input type="text"
						class="widefat"
						id="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'username' ) ); ?>"
						value="<?php echo esc_attr( $username ); ?>"
						title="<?php esc_html_e( 'Legacy YouTube username located behind /user/ part of channel URL (available only on very old YouTube accounts)', 'wpau-yt-channel' ); ?>" />
				</label>
			</p>
		</div><!-- .halfs -->

		<div class="halfs">
			<p class="mt-0">
				<label for="<?php echo esc_attr( $this->get_field_id( 'resource' ) ); ?>">
					<?php esc_html_e( 'Resource to use', 'wpau-yt-channel' ); ?>
					<select
						class="widefat"
						id="<?php echo esc_attr( $this->get_field_id( 'resource' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'resource' ) ); ?>"
						onchange="ytcToggle('resource', '<?php echo esc_js( $this->get_field_id( '' ) ); ?>');"
						>
						<option value="0"<?php selected( $resource, 0 ); ?>><?php esc_html_e( 'Channel (User Uploads)', 'wpau-yt-channel' ); ?></option>
						<option value="1"<?php selected( $resource, 1 ); ?>><?php esc_html_e( 'Favourites', 'wpau-yt-channel' ); ?></option>
						<option value="3"<?php selected( $resource, 3 ); ?>><?php esc_html_e( 'Liked Videos', 'wpau-yt-channel' ); ?></option>
						<option value="2"<?php selected( $resource, 2 ); ?>><?php esc_html_e( 'Playlist', 'wpau-yt-channel' ); ?></option>
					</select>
				</label>
			</p>
			<p class="mt-0">
				<label for="<?php echo esc_attr( $this->get_field_id( 'cache' ) ); ?>">
					<?php esc_html_e( 'Cache feed', 'wpau-yt-channel' ); ?>
					<select
						class="widefat"
						id="<?php echo esc_attr( $this->get_field_id( 'cache' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'cache' ) ); ?>"
						>
						<?php
						foreach ( $wpau_my_youtube_channel->cache_timeouts as $sec => $title ) {
							echo '<option value="' . intval( $sec ) . '" ' . selected( $cache, $sec, 0 ) . '>' . esc_html( $title ) . '</option>';
						}
						?>
					</select>
				</label>
			</p>
		</div><!-- .halfs -->

		<div class="thirds">
			<p class="third left mt-0">
				<label for="<?php echo esc_attr( $this->get_field_id( 'fetch' ) ); ?>">
					<?php esc_html_e( 'Fetch', 'wpau-yt-channel' ); ?>
					<input type="number"
						class="small-text"
						id="<?php echo esc_attr( $this->get_field_id( 'fetch' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'fetch' ) ); ?>"
						value="<?php echo intval( $fetch ); ?>"
						min="2"
						title="<?php esc_html_e( 'Number of videos that will be used for random pick (min 2, max 50, default 25)', 'wpau-yt-channel' ); ?>"
						/>
					<small><?php esc_html_e( 'video(s)', 'wpau-yt-channel' ); ?></small>
				</label>
			</p>
			<p class="third right mt-0">
				<label for="<?php echo esc_attr( $this->get_field_id( 'num' ) ); ?>">
					<?php esc_html_e( 'Show', 'wpau-yt-channel' ); ?>
					<input type="number"
						class="small-text"
						id="<?php echo esc_attr( $this->get_field_id( 'num' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'num' ) ); ?>"
						value="<?php echo ( $num ) ? intval( $num ) : 1; ?>"
						min="1"
						title="<?php esc_html_e( 'Number of videos to display', 'wpau-yt-channel' ); ?>"
						/>
					<small><?php esc_html_e( 'video(s)', 'wpau-yt-channel' ); ?></small>
				</label>
			</p>
			<p class="third left mt-0">
				<label for="<?php echo esc_attr( $this->get_field_id( 'skip' ) ); ?>">
					<?php esc_html_e( 'Skip', 'wpau-yt-channel' ); ?>
					<input type="number"
						class="small-text"
						id="<?php echo esc_attr( $this->get_field_id( 'skip' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'skip' ) ); ?>"
						value="<?php echo ( $skip ) ? intval( $skip ) : 0; ?>"
						min="0"
						max="49"
						title="<?php esc_html_e( 'Number of videos to skip', 'wpau-yt-channel' ); ?>"
						/>
					<small><?php esc_html_e( 'video(s)', 'wpau-yt-channel' ); ?></small>
				</label>
			</p>
		</div><!-- .thirds -->

		<div class="checkboxes">
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'privacy' ) ); ?>">
					<input type="checkbox"
						class="checkbox"
						id="<?php echo esc_attr( $this->get_field_id( 'privacy' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'privacy' ) ); ?>"
						<?php checked( (bool) $privacy, true ); ?>
						title="<?php _e( 'Enable this option to protect your visitors privacy', 'wpau-yt-channel' ); ?>"
						/>
					<?php
						esc_html_e( 'Enable privacy-enhanced mode', 'wpau-yt-channel' );
						echo ' (<a href="https://support.google.com/youtube/bin/answer.py?hl=en-GB&answer=171780" target="_blank">' . esc_html__( 'learn more here', 'wpau-yt-channel' ) . '</a>)';
					?>
				</label>
				<label for="<?php echo esc_attr( $this->get_field_id( 'random' ) ); ?>" <?php echo 2 === $resource ? 'class="hidden"' : ''; ?>>
					<input type="checkbox"
						class="checkbox"
						<?php checked( (bool) $random, true ); ?>
						id="<?php echo esc_attr( $this->get_field_id( 'random' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'random' ) ); ?>"
						title="<?php esc_html_e( 'Get random videos of all fetched from channel or playlist', 'wpau-yt-channel' ); ?>"
						/>
					<?php esc_html_e( 'Show random video from resource', 'wpau-yt-channel' ); ?>
					<small>(<?php esc_html_e( 'Ignored if you set "Embedded Playlist" for "What to display?"', 'wpau-yt-channel' ); ?>)</small>
				</label>
			</p>
		</div><!-- .checkboxes -->

		<h4><?php esc_html_e( 'Video Settings', 'wpau-yt-channel' ); ?></h4>
		<div class="checkboxes">
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'ratio' ) ); ?>">
					<?php esc_html_e( 'Aspect ratio', 'wpau-yt-channel' ); ?>
					<select
						class="widefat"
						id="<?php echo esc_attr( $this->get_field_id( 'ratio' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'ratio' ) ); ?>">
						<option value="3" <?php selected( $ratio, 3 ); ?>>16:9</option>
						<option value="1" <?php selected( $ratio, 1 ); ?>>4:3</option>
					</select>
				</label>
				<label for="<?php echo esc_attr( $this->get_field_id( 'responsive' ) ); ?>">
					<input type="checkbox"
						class="checkbox"
						id="<?php echo esc_attr( $this->get_field_id( 'responsive' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'responsive' ) ); ?>"
						<?php checked( (bool) $responsive, true ); ?>
						onchange="ytcToggle('responsive', '<?php echo esc_js( $this->get_field_id( '' ) ); ?>');"
						/>
					<?php esc_html_e( 'Responsive video (distribute one full width item per row)', 'wpau-yt-channel' ); ?>
				</label>
			</p>
		</div><!-- .checkboxes -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'width' ) ); ?>" <?php echo $responsive ? 'class="hidden"' : ''; ?>>
				<?php esc_html_e( 'Initial width', 'wpau-yt-channel' ); ?>
				<input type="number"
					class="small-text"
					id="<?php echo esc_attr( $this->get_field_id( 'width' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'width' ) ); ?>"
					value="<?php echo intval( $width ); ?>"
					min="32"
					title="<?php esc_html_e( 'Set video width in pixels', 'wpau-yt-channel' ); ?>"
					/>
				<small>px (<?php esc_html_e( 'default', 'wpau-yt-channel' ); ?> 306)</small>
			</label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'display' ) ); ?>">
				<?php esc_html_e( 'What to display?', 'wpau-yt-channel' ); ?>
				<select
					class="widefat"
					id="<?php echo esc_attr( $this->get_field_id( 'display' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'display' ) ); ?>"
					onchange="ytcToggle('display', '<?php echo esc_js( $this->get_field_id( '' ) ); ?>');"
					>
					<option value="thumbnail"<?php selected( $display, 'thumbnail' ); ?>><?php esc_html_e( 'Thumbnail', 'wpau-yt-channel' ); ?></option>
					<option value="iframe"<?php selected( $display, 'iframe' ); ?>><?php esc_html_e( 'HTML5 (iframe)', 'wpau-yt-channel' ); ?></option>
					<option value="iframe2"<?php selected( $display, 'iframe2' ); ?>><?php esc_html_e( 'HTML5 (iframe) Asynchronous', 'wpau-yt-channel' ); ?></option>
					<option value="playlist"<?php selected( $display, 'playlist' ); ?>><?php esc_html_e( 'Embedded Playlist', 'wpau-yt-channel' ); ?></option>
				</select>
			</label>
		</p>
		<div class="checkboxes">
			<p class="mt-0 mb-0">
				<label for="<?php echo esc_attr( $this->get_field_id( 'thumb_quality' ) ); ?>" <?php echo 'thumbnail' !== $display ? 'class="hidden"' : ''; ?>>
					<?php esc_html_e( 'Thumbnail Quality', 'wpau-yt-channel' ); ?>
					<select
						class="widefat"
						id="<?php echo esc_attr( $this->get_field_id( 'thumb_quality' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'thumb_quality' ) ); ?>"
						>
						<option value="default" <?php selected( $thumb_quality, 'default' ); ?>><?php esc_html_e( 'Default Quality (120x90px)', 'wpau-yt-channel' ); ?></option>
						<option value="mqdefault" <?php selected( $thumb_quality, 'mqdefault' ); ?>><?php esc_html_e( 'Medium Quality (320x180px)', 'wpau-yt-channel' ); ?></option>
						<option value="hqdefault" <?php selected( $thumb_quality, 'hqdefault' ); ?>><?php esc_html_e( 'High Quality (480x360px)', 'wpau-yt-channel' ); ?></option>
						<option value="sddefault" <?php selected( $thumb_quality, 'sddefault' ); ?>><?php esc_html_e( 'Standard Definition (640x480px)', 'wpau-yt-channel' ); ?></option>
						<option value="maxresdefault" <?php selected( $thumb_quality, 'maxresdefault' ); ?>><?php esc_html_e( 'Maximum Resolution (1280x720px)', 'wpau-yt-channel' ); ?></option>
					</select>
				</label>
				<label for="<?php echo esc_attr( $this->get_field_id( 'no_thumb_title' ) ); ?>" <?php echo 'thumbnail' !== $display ? 'class="hidden"' : ''; ?>>
					<input type="checkbox"
						class="checkbox"
						id="<?php echo esc_attr( $this->get_field_id( 'no_thumb_title' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'no_thumb_title' ) ); ?>"
						<?php checked( (bool) $no_thumb_title, true ); ?>
						/>
					<?php esc_html_e( 'Disable thumbnail tooltip', 'wpau-yt-channel' ); ?>
				</label>
			</p>
		</div><!-- .checkboxes -->
		<div class="checkboxes">
			<p class="mt-0">
				<label for="<?php echo esc_attr( $this->get_field_id( 'controls' ) ); ?>"
					title="<?php esc_html_e( 'This option indicates whether the video player controls are displayed', 'wpau-yt-channel' ); ?>"
				>
					<input type="checkbox"
						class="checkbox"
						id="<?php echo esc_attr( $this->get_field_id( 'controls' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'controls' ) ); ?>"
						<?php checked( (bool) $controls, true ); ?>
						/>
					<?php esc_html_e( 'Hide player controls', 'wpau-yt-channel' ); ?>
				</label>
				<label for="<?php echo esc_attr( $this->get_field_id( 'modestbranding' ) ); ?>"
					title="<?php esc_html_e( "Hide a YouTube logo from YouTube player control bar. Note that a small YouTube text label will still display in the upper-right corner of a paused video when the user's mouse pointer hovers over the player.", 'wpau-yt-channel' ); ?>"
					>
					<input type="checkbox"
						class="checkbox"
						id="<?php echo esc_attr( $this->get_field_id( 'modestbranding' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'modestbranding' ) ); ?>"
						<?php checked( (bool) $modestbranding, true ); ?>
						/>
					<?php esc_html_e( 'Hide YouTube Logo', 'wpau-yt-channel' ); ?>
				</label>
				<label for="<?php echo esc_attr( $this->get_field_id( 'hideanno' ) ); ?>">
					<input type="checkbox"
						class="checkbox"
						id="<?php echo esc_attr( $this->get_field_id( 'hideanno' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'hideanno' ) ); ?>"
						<?php checked( (bool) $hideanno, true ); ?>
						/>
					<?php esc_html_e( 'Hide annotations from video', 'wpau-yt-channel' ); ?>
				</label>
				<label for="<?php echo esc_attr( $this->get_field_id( 'norel' ) ); ?>"
					title="<?php esc_html_e( 'Enable this option to show after finished playback only related videos that come from the same channel as the video that was just played', 'wpau-yt-channel' ); ?>"
					>
					<input type="checkbox"
						class="checkbox"
						id="<?php echo esc_attr( $this->get_field_id( 'norel' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'norel' ) ); ?>"
						<?php checked( (bool) $norel, true ); ?>
						/>
					<?php esc_html_e( 'Allow only channel related videos', 'wpau-yt-channel' ); ?>
				</label>
				<label for="<?php echo esc_attr( $this->get_field_id( 'autoplay' ) ); ?>"
					title="<?php esc_html_e( 'If you enable Autoplay, playback will occur without any user interaction with the player if video is muted on autoplay and visitors browser allows that', 'wpau-yt-channel' ); ?>"
					>
					<input type="checkbox"
						class="checkbox"
						id="<?php echo esc_attr( $this->get_field_id( 'autoplay' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'autoplay' ) ); ?>"
						<?php checked( (bool) $autoplay, true ); ?>
						/>
					<?php esc_html_e( 'Autoplay video or playlist', 'wpau-yt-channel' ); ?>
				</label>
				<label for="<?php echo esc_attr( $this->get_field_id( 'autoplay_mute' ) ); ?>">
					<input type="checkbox"
						class="checkbox"
						id="<?php echo esc_attr( $this->get_field_id( 'autoplay_mute' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'autoplay_mute' ) ); ?>"
						<?php checked( (bool) $autoplay_mute, true ); ?>
						/>
					<?php esc_html_e( 'Mute video on autoplay', 'wpau-yt-channel' ); ?>
				</label>
			</p>
		</div><!-- .checkboxes -->

		<h4><?php esc_html_e( 'Content Layout', 'wpau-yt-channel' ); ?></h4>
		<div class="checkboxes">
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'showtitle' ) ); ?>">
					<?php esc_html_e( 'Show video title', 'wpau-yt-channel' ); ?>
					<select
						class="widefat"
						id="<?php echo esc_attr( $this->get_field_id( 'showtitle' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'showtitle' ) ); ?>"
						onchange="ytcToggle('showtitle', '<?php echo esc_js( $this->get_field_id( '' ) ); ?>');"
						>
						<option value="none" <?php selected( $showtitle, 'none' ); ?>><?php esc_html_e( 'Hide title', 'wpau-yt-channel' ); ?></option>
						<option value="above" <?php selected( $showtitle, 'above' ); ?>><?php esc_html_e( 'Above video/thumbnail', 'wpau-yt-channel' ); ?></option>
						<option value="below" <?php selected( $showtitle, 'below' ); ?>><?php esc_html_e( 'Below video/thumbnail', 'wpau-yt-channel' ); ?></option>
						<option value="inside" <?php selected( $showtitle, 'inside' ); ?>><?php esc_html_e( 'Inside thumbnail, top aligned', 'wpau-yt-channel' ); ?></option>
						<option value="inside_b" <?php selected( $showtitle, 'inside_b' ); ?>><?php esc_html_e( 'Inside thumbnail, bototm aligned', 'wpau-yt-channel' ); ?></option>
					</select>
				</label>
			</p>
		</div><!-- .checkboxes -->
		<div class="checkboxes">
			<p class="mt-0 mb-0">
				<label for="<?php echo esc_attr( $this->get_field_id( 'titletag' ) ); ?>" <?php echo 'none' === $showtitle ? 'class="hidden"' : ''; ?>>
					<?php esc_html_e( 'Title HTML tag', 'wpau-yt-channel' ); ?>
					<select
						class="widefat"
						id="<?php echo esc_attr( $this->get_field_id( 'titletag' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'titletag' ) ); ?>"
						>
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
				<label for="<?php echo esc_attr( $this->get_field_id( 'linktitle' ) ); ?>" <?php echo 'none' === $showtitle ? 'class="hidden"' : ''; ?>>
					<input type="checkbox"
						class="checkbox"
						id="<?php echo esc_attr( $this->get_field_id( 'linktitle' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'linktitle' ) ); ?>"
						<?php checked( (bool) $linktitle, true ); ?>
						title="<?php esc_html_e( 'Enable this option to link outside title to video', 'wpau-yt-channel' ); ?>"
						/>
					<?php esc_html_e( 'Link outside title to video', 'wpau-yt-channel' ); ?>
				</label>
			</p>
			</div><!-- .checkboxes -->
			<div class="checkboxes">
			<p class="mt-0">
				<label for="<?php echo esc_attr( $this->get_field_id( 'showdesc' ) ); ?>">
					<input class="checkbox"
						type="checkbox"
						id="<?php echo esc_attr( $this->get_field_id( 'showdesc' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'showdesc' ) ); ?>"
						<?php checked( (bool) $showdesc, true ); ?>
						onchange="ytcToggle('showdesc', '<?php echo esc_js( $this->get_field_id( '' ) ); ?>');"
						/>
					<?php esc_html_e( 'Show video description', 'wpau-yt-channel' ); ?>
				</label>
			</p>
		</div><!-- .checkboxes -->
		<p class="mt-0">
			<label for="<?php echo esc_attr( $this->get_field_id( 'desclen' ) ); ?>" <?php echo ! $showdesc ? 'class="hidden"' : ''; ?>>
				<?php esc_html_e( 'Description length', 'wpau-yt-channel' ); ?>
				<input type="number"
					class="small-text"
					id="<?php echo esc_attr( $this->get_field_id( 'desclen' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'desclen' ) ); ?>"
					value="<?php echo intval( $desclen ); ?>"
					title="<?php esc_html_e( 'Set number of characters to cut down video description to (0 means full length)', 'wpau-yt-channel' ); ?>"
					/>
				<small>(0 = full)</small>
			</label>
		</p>

		<h4><?php esc_html_e( 'Link to Channel', 'wpau-yt-channel' ); ?></h4>
		<div class="halfs">
		<p class="mt-0">
			<label for="<?php echo esc_attr( $this->get_field_id( 'link_to' ) ); ?>">
				<?php esc_html_e( 'Link type', 'wpau-yt-channel' ); ?>
				<select
					class="widefat"
					id="<?php echo esc_attr( $this->get_field_id( 'link_to' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'link_to' ) ); ?>"
					onchange="ytcToggle('link_to', '<?php echo esc_js( $this->get_field_id( '' ) ); ?>');"
					>
					<option value="none" <?php selected( $link_to, 'none' ); ?>><?php esc_html_e( 'Hide link', 'wpau-yt-channel' ); ?></option>
					<option value="handle" <?php selected( $link_to, 'handle' ); ?>><?php esc_html_e( 'Link to Handle URL', 'wpau-yt-channel' ); ?></option>
					<option value="channel" <?php selected( $link_to, 'channel' ); ?>><?php esc_html_e( 'Link to Channel URL', 'wpau-yt-channel' ); ?></option>
					<option value="vanity" <?php selected( $link_to, 'vanity' ); ?>><?php esc_html_e( 'Link to Vanity URL (deprecated)', 'wpau-yt-channel' ); ?></option>
					<option value="legacy" <?php selected( $link_to, 'legacy' ); ?>><?php esc_html_e( 'Link to Legacy username URL (deprecated)', 'wpau-yt-channel' ); ?></option>
				</select>
			</label>
		</p>
		<p class="mt-0">
			<label for="<?php echo esc_attr( $this->get_field_id( 'popup_goto' ) ); ?>" <?php echo 'none' === $link_to ? 'class="hidden"' : ''; ?>>
				<?php esc_html_e( 'Link behaviour', 'wpau-yt-channel' ); ?>
				<select
					class="widefat"
					id="<?php echo esc_attr( $this->get_field_id( 'popup_goto' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'popup_goto' ) ); ?>"
					>
					<option value="0" <?php selected( $popup_goto, 0 ); ?>><?php esc_html_e( 'Open link in same window', 'wpau-yt-channel' ); ?></option>
					<?php // Option 2 `Open link in new window (JavaScript)` is deprecated since 3.23.2 ?>
					<option value="2" <?php selected( $popup_goto, 2 ); ?>><?php esc_html_e( 'Open link in new window', 'wpau-yt-channel' ); ?></option>
				</select>
			</label>
		</p>
		</div><!-- .halfs -->
		<p class="mt-0">
			<label for="<?php echo esc_attr( $this->get_field_id( 'goto_txt' ) ); ?>" <?php echo 'none' === $link_to ? 'class="hidden"' : ''; ?>>
				<?php esc_html_e( 'Link text', 'wpau-yt-channel' ); ?>
				<input class="widefat"
				id="<?php echo esc_attr( $this->get_field_id( 'goto_txt' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'goto_txt' ) ); ?>"
				type="text"
				value="<?php echo esc_html( $goto_txt ); ?>"
				placeholder="<?php esc_html_e( 'Visit our YouTube channel', 'wpau-yt-channel' ); ?>"
				/>
			<span class="description">
			<?php
				esc_html_e( 'Default: Visit our YouTube channel. You can use placeholders', 'wpau-yt-channel' );
				echo ' <code>%handle%</code> ' . esc_html__( 'and/or', 'wpau-yt-channel' ) . ' <code>%channel%</code>';
			?>
			</span>
			</label>
		</p>

		<?php if ( $this->get_field_id( '' ) > 0 ) : ?>
		<script>
			window.ytcWidgets = window.ytcWidgets || [];
			ytcWidgets.push('<?php echo esc_js( $this->get_field_id( '' ) ); ?>');
		</script>
		<?php endif; ?>
		<?php
	} // END public function form()

	public function update( $new_instance, $old_instance ) {

		// processes widget options to be saved
		$instance                   = $old_instance;
		$instance['title']          = sanitize_text_field( $new_instance['title'] );
		$instance['class']          = sanitize_html_classes( $new_instance['class'] );
		$instance['channel']        = ytc_sanitize_api_key( $new_instance['channel'] );
		$instance['handle']         = sanitize_user( $new_instance['handle'] );
		$instance['username']       = sanitize_user( $new_instance['username'] );
		$instance['playlist']       = ytc_sanitize_api_key( $new_instance['playlist'] );
		$instance['vanity']         = sanitize_user( $new_instance['vanity'] );
		$instance['num']            = intval( $new_instance['num'] );
		$instance['skip']           = intval( $new_instance['skip'] );
		$instance['resource']       = intval( $new_instance['resource'] );
		$instance['cache']          = intval( $new_instance['cache'] );
		$instance['random']         = isset( $new_instance['random'] ) ? boolval( $new_instance['random'] ) : false;
		$instance['fetch']          = intval( $new_instance['fetch'] );
		$instance['goto_txt']       = sanitize_text_field( $new_instance['goto_txt'] );
		$instance['popup_goto']     = intval( $new_instance['popup_goto'] );
		$instance['link_to']        = isset( $new_instance['link_to'] ) && in_array( $new_instance['link_to'], array( 'none', 'handle', 'vanity', 'channel', 'legacy' ), true ) ? sanitize_key( $new_instance['link_to'] ) : 'none'; // string
		$instance['showtitle']      = isset( $new_instance['showtitle'] ) ? $new_instance['showtitle'] : 'none';
		$instance['linktitle']      = isset( $new_instance['linktitle'] ) ? boolval( $new_instance['linktitle'] ) : false;
		$instance['titletag']       = isset( $new_instance['titletag'] ) ? $new_instance['titletag'] : 'h3';
		$instance['showdesc']       = isset( $new_instance['showdesc'] ) ? boolval( $new_instance['showdesc'] ) : false;
		$instance['desclen']        = intval( $new_instance['desclen'] );
		$instance['width']          = intval( $new_instance['width'] );
		$instance['responsive']     = isset( $new_instance['responsive'] ) ? boolval( $new_instance['responsive'] ) : false;
		$instance['display']        = sanitize_key( $new_instance['display'] );
		$instance['thumb_quality']  = sanitize_key( $new_instance['thumb_quality'] );
		$instance['no_thumb_title'] = isset( $new_instance['no_thumb_title'] ) ? boolval( $new_instance['no_thumb_title'] ) : false;
		$instance['autoplay']       = isset( $new_instance['autoplay'] ) ? boolval( $new_instance['autoplay'] ) : false;
		$instance['autoplay_mute']  = isset( $new_instance['autoplay_mute'] ) ? boolval( $new_instance['autoplay_mute'] ) : false;
		$instance['norel']          = isset( $new_instance['norel'] ) ? boolval( $new_instance['norel'] ) : false;
		$instance['modestbranding'] = isset( $new_instance['modestbranding'] ) ? boolval( $new_instance['modestbranding'] ) : false;
		$instance['ratio']          = intval( $new_instance['ratio'] );
		$instance['controls']       = isset( $new_instance['controls'] ) ? boolval( $new_instance['controls'] ) : false;
		$instance['hideanno']       = isset( $new_instance['hideanno'] ) ? boolval( $new_instance['hideanno'] ) : false;
		$instance['privacy']        = isset( $new_instance['privacy'] ) ? boolval( $new_instance['privacy'] ) : false;

		return $instance;
	} // END public function update()
} // END class Wpau_My_Youtube_Channel_Widget()

// Register Wpau_My_Youtube_Channel_Widget widget
function wpau_register_my_youtube_channel_widget() {
	register_widget( 'Wpau_My_Youtube_Channel_Widget' );
}
add_action( 'widgets_init', 'wpau_register_my_youtube_channel_widget' );
