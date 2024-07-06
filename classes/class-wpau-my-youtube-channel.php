<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'Wpau_My_Youtube_Channel' ) ) {
	return;
}

class Wpau_My_Youtube_Channel {
	/**
	 * YT.player object
	 *
	 * @var array $ytc_html5 Contains settings for all embedable player items
	 */
	public $ytc_html5 = array();

	/**
	 * Predefine variable for default plugin settings
	 *
	 * @var array $defaults Default plugin settings
	 */
	public $defaults;

	/**
	 * Predefine cache timeouts
	 *
	 * @var array $cache_timeouts Associated array of predefined cache timeouts
	 */
	public $cache_timeouts;

	public $image_handler;

	/**
	 * Construct class
	 */
	public function __construct() {

		$this->cache_timeouts = array(
			'0'       => __( 'Do not cache', 'wpau-yt-channel' ),
			'60'      => __( '1 minute', 'wpau-yt-channel' ),
			'300'     => __( '5 minutes', 'wpau-yt-channel' ),
			'900'     => __( '15 minutes', 'wpau-yt-channel' ),
			'1800'    => __( '30 minutes', 'wpau-yt-channel' ),
			'3600'    => __( '1 hour', 'wpau-yt-channel' ),
			'7200'    => __( '2 hours', 'wpau-yt-channel' ),
			'18000'   => __( '5 hours', 'wpau-yt-channel' ),
			'36000'   => __( '10 hours', 'wpau-yt-channel' ),
			'43200'   => __( '12 hours', 'wpau-yt-channel' ),
			'64800'   => __( '18 hours', 'wpau-yt-channel' ),
			'86400'   => __( '1 day', 'wpau-yt-channel' ),
			'172800'  => __( '2 days', 'wpau-yt-channel' ),
			'259200'  => __( '3 days', 'wpau-yt-channel' ),
			'345600'  => __( '4 days', 'wpau-yt-channel' ),
			'432000'  => __( '5 days', 'wpau-yt-channel' ),
			'518400'  => __( '6 days', 'wpau-yt-channel' ),
			'604800'  => __( '1 week', 'wpau-yt-channel' ),
			'1209600' => __( '2 weeks', 'wpau-yt-channel' ),
			'1814400' => __( '3 weeks', 'wpau-yt-channel' ),
			'2419200' => __( '1 month', 'wpau-yt-channel' ),
		);

		load_plugin_textdomain( YTC_PLUGIN_SLUG, false, YTC_DIR . '/languages' );

		// Clear all YTC cache
		add_action( 'wp_ajax_ytc_clear_all_cache', array( $this, 'clear_all_cache' ) );

		// Activation hook and maybe update trigger
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		add_action( 'plugins_loaded', array( $this, 'maybe_update' ) );

		// Initiate default plugin settings
		self::defaults();

		// TinyMCE AddOn
		if ( ! empty( $this->defaults['tinymce'] ) ) {
			add_filter( 'mce_external_plugins', array( $this, 'mce_external_plugins' ), 998 );
			add_filter( 'mce_buttons', array( $this, 'mce_buttons' ), 999 );
		}

		if ( is_admin() ) {

			// Initialize Plugin Settings Magic
			add_action( 'init', array( $this, 'admin_init' ) );

			// Add various Dashboard notices (if needed)
			add_action( 'admin_notices', array( $this, 'admin_notices' ) );

			// Enqueue scripts and styles for Widgets page
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		} else { // ELSE if ( is_admin() )

			// Enqueue frontend scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'wp_footer', array( $this, 'footer_scripts' ), 900 );

		} // END if ( is_admin() )

		// Load widget
		require_once 'class-wpau-my-youtube-channel-widget.php';
		require_once 'class-wpau-my-youtube-channel-image-handler.php';
		// Create an instance of the child class
		$this->image_handler = new Wpau_My_Youtube_Channel_Image_Handler();

		// Register shortcodes `youtube_channel` and `ytc`
		add_shortcode( 'youtube_channel', array( $this, 'shortcode' ) );
		add_shortcode( 'ytc', array( $this, 'shortcode' ) );
	} // END public function __construct()

	/**
	 * Activate the plugin
	 * Credits: http://solislab.com/blog/plugin-activation-checklist/#update-routines
	 */
	public static function activate() {
		global $wpau_my_youtube_channel;
		$wpau_my_youtube_channel->init_options();
		$wpau_my_youtube_channel->maybe_update();
	} // END public static function activate()

	/**
	 * Return initial options
	 *
	 * @return array Global defaults for current plugin version
	 */
	public function init_options() {
		$init = array(
			'handle'         => '', // [NEW 2023] YouTube Handle https://www.youtube.com/handle
			'vanity'         => '', // $this->vanity_id,
			'channel'        => '', // YouTube Channel ID https://www.youtube.com/account_advanced
			'username'       => '', // YouTube Username ID https://www.youtube.com/account_advanced
			'playlist'       => '', // $this->playlist_id,
			'resource'       => 0, // ex use_res
			'cache'          => 300, // 5 minutes // ex cache_time
			'fetch'          => 25, // ex maxrnd
			'num'            => 1, // ex vidqty
			'skip'           => 0,
			'privacy'        => 0,

			'ratio'          => 3, // 3 - 16:9, 1 - 4:3 (deprecated: 2 - 16:10)
			'width'          => 306,
			'responsive'     => 1,
			'display'        => 'thumbnail', // thumbnail, iframe, iframe2, playlist (deprecated: chromeless, object)
			'thumb_quality'  => 'hqdefault', // default, mqdefault, hqdefault, sddefault, maxresdefault
			'fullscreen'     => 0,
			'controls'       => 0,
			'autoplay'       => 0,
			'autoplay_mute'  => 0,
			'norel'          => 0,
			'playsinline'    => 0, // play video on mobile devices inline instead in native device player
			'showtitle'      => 'none', // above, below, inside, inside_b
			'linktitle'      => 0,
			'titletag'       => 'h3',
			'showdesc'       => 0,
			'desclen'        => 0,
			'modestbranding' => 0,
			'hideanno'       => 0,

			'goto_txt'       => 'Visit our channel',
			'popup_goto'     => 0, // 0 same window, 1 new window JS, 2 new window target
			'link_to'        => 'none', // 0 legacy username, 1 channel, 2 vanity
			'tinymce'        => 1, // show TinyMCE button by default
			'nolightbox'     => false, // do not use lightbox global setting
			'timeout'        => 5, // timeout for wp_remote_get()
			'sslverify'      => true,
			'local_img'      => false, // [NEW 2024] Store video thumbnails locally
			'js_ev_listener' => false,
			'block_preview'  => true, // [NEW 2023] Enable YTC Widget preview in Block editor
		);

		add_option( 'youtube_channel_version', YTC_VER, '', 'no' );
		add_option( 'youtube_channel_db_ver', YTC_VER_DB, '', 'no' );
		add_option( YTC_PLUGIN_OPTION_KEY, $init, '', 'no' );

		return $init;
	} // END public function init_options()

	/**
	 * Check do we need to migrate options
	 */
	public function maybe_update() {
		// bail if this plugin data doesn't need updating
		if ( get_option( 'youtube_channel_db_ver' ) >= YTC_VER_DB ) {
			return;
		}

		require_once YTC_DIR . '/update.php';
		au_youtube_channel_update();
	} // END public function maybe_update()

	/**
	 * Initialize Settings link for Plugins page and create Settings page
	 */
	public function admin_init() {
		add_filter( 'plugin_action_links_' . plugin_basename( YTC_PLUGIN_FILE ), array( $this, 'add_action_links' ) );
		// Add row on Plugins page
		add_filter( 'plugin_row_meta', array( $this, 'add_plugin_meta_links' ), 10, 2 );

		require_once 'class-wpau-my-youtube-channel-settings.php';

		global $wpau_my_youtube_channel_settings;
		if ( empty( $wpau_my_youtube_channel_settings ) ) {
			$wpau_my_youtube_channel_settings = new Wpau_My_Youtube_Channel_Settings();
		}
	} // END public function admin_init()

	/**
	 * Append Settings link for Plugins page
	 *
	 * @param array $links Array of default plugin links
	 *
	 * @return array       Array of plugin links with appended link for Settings page
	 */
	public function add_action_links( $links ) {
		$settings_link = '<a href="' . esc_url( admin_url( 'options-general.php?page=' . YTC_PLUGIN_SLUG ) ) . '">' . esc_html__( 'Settings' ) . '</a>';
		array_unshift( $links, $settings_link );
		return $links; // Return updated array of links
	} // END public function add_action_links( $links )

	/**
	 * Add link to plugin community support
	 *
	 * @param array $links Array of default plugin meta links
	 * @param string $file Current hook file path
	 *
	 * @return array       Array of default plugin meta links with appended link for Support community forum
	 */
	public function add_plugin_meta_links( $links, $file ) {
		if ( plugin_basename( YTC_PLUGIN_FILE ) === $file ) {
			$links[] = '<a href="https://wordpress.org/support/plugin/youtube-channel/" target="_blank">' . esc_html__( 'Support' ) . '</a>';
		}

		// Return updated array of links
		return $links;
	} // END public function add_plugin_meta_links( $links, $file )

	/**
	 * Enqueue admin scripts and styles for widget customization
	 */
	public function admin_scripts() {
		global $pagenow;

		// Enqueue only on widget or post pages
		if ( ! in_array( $pagenow, array( 'widgets.php', 'customize.php', 'options-general.php', 'post.php', 'post-new.php' ), true ) ) {
			return;
		}

		// Enqueue on post page only if tinymce is enabled
		if ( in_array( $pagenow, array( 'post.php', 'post-new.php' ), true ) && empty( $this->defaults['tinymce'] ) ) {
			return;
		}

		wp_enqueue_style(
			esc_attr( YTC_PLUGIN_SLUG . '-admin' ),
			esc_url( YTC_URL . 'assets/css/admin.min.css' ),
			array(),
			YTC_VER
		);

		// Enqueue script for widget in admin
		if ( in_array( $pagenow, array( 'widgets.php', 'customize.php' ), true ) ) {
			wp_enqueue_script(
				esc_attr( YTC_PLUGIN_SLUG . '-admin' ),
				esc_url( YTC_URL . 'assets/js/admin.min.js' ),
				array( 'jquery' ),
				YTC_VER,
				true
			);
		}
	} // END public function admin_scripts()

	/**
	 * Print dashboard notice
	 *
	 * @return string Formatted notice with usefull explanation
	 */
	public function admin_notices() {
		// Prepare vars for notices
		$notice = array( 'error' => '' );

		// Inform if PHP version is lower than 7.4
		if ( version_compare( PHP_VERSION, '7.4', '<' ) ) {
			$notice['error'] .= '<p>'
				. sprintf(
					// translators: %1$s stand for PHP version on server, %2$s is plugin name
					esc_html__( 'Your website running on web server with PHP version %1$s. Please note that %2$s requires PHP 7.4 or newer to work properly.', 'wpau-yt-channel' ),
					PHP_VERSION, // 1
					'<strong>' . esc_html( YTC_PLUGIN_NAME ) . '</strong>' // 2
				)
				. '</p>';
		}

		// No YouTube DATA Api Key?
		if ( empty( $this->defaults['apikey'] ) ) {

			$notice['error'] .= '<p>'
				. sprintf(
					/* translators: %1$s is replaced with plugin name,
						* %2$s with translated label YouTube Data API Key,
						* %3$s with link to plugin General Settings page
						* %4$s with link to Google Developers Console,
						* %5$s with link to Data API Key guide,
						*/
					__(
						'%1$s require %2$s to be set on plugin %3$s page. You can generate your own key on %4$s by following %5$s.',
						'wpau-yt-channel'
					),
					'<strong>' . esc_html( YTC_PLUGIN_NAME ) . '</strong>', // 1
					'<strong>' . esc_html__( 'YouTube Data API Key', 'wpau-yt-channel' ) . '</strong>', // 2
					'<a href="' . admin_url( 'options-general.php?page=' . YTC_PLUGIN_SLUG . '&tab=general' ) . '">' . esc_html__( 'General Settings', 'wpau-yt-channel' ) . '</a>', // 3
					'<a href="https://console.developers.google.com/project" target="_blank">' . esc_html__( 'Google Developers Console', 'wpau-yt-channel' ) . '</a>', // 4
					'<a href="https://urosevic.net/wordpress/plugins/youtube-channel/#youtube_data_api_key" target="_blank">' . esc_html__( 'this tutorial', 'wpau-yt-channel' ) . '</a>' // 5
				)
				. '</p>';

		}

		// Now output all prepared notices
		foreach ( $notice as $type => $message ) {
			if ( ! empty( $message ) ) {
				echo '<div class="notice notice-' . esc_attr( $type ) . '">' . wp_kses(
					$message,
					array(
						'p'      => array(),
						'strong' => array(),
						'br'     => array(),
						'a'      => array(
							'href'   => array(),
							'target' => array(),
						),
					)
				) . '</div>';
			}
		}
	} // END public function admin_notices()

	/**
	 * Get default options from DB and store it to variable $this->defaults
	 *
	 * @return void
	 */
	public function defaults() {
		$defaults = get_option( YTC_PLUGIN_OPTION_KEY );
		if ( empty( $defaults ) ) {
			$defaults = $this->init_options();
		}
		$this->defaults = $defaults;
	} // END public function defaults()

	/**
	 * Enqueue frontend scripts and styles
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		// Check do we need our own lightbox?
		if ( empty( $this->defaults['nolightbox'] ) ) {
			wp_enqueue_style(
				'bigger-picture',
				esc_url( YTC_URL . 'assets/lib/bigger-picture/css/bigger-picture.min.css' ),
				array(),
				YTC_VER
			);
			wp_register_script(
				'bigger-picture',
				esc_url( YTC_URL . 'assets/lib/bigger-picture/bigger-picture.min.js' ),
				array(),
				YTC_VER,
				true
			);
			wp_enqueue_script( 'bigger-picture' );
			wp_register_script(
				'youtube-channel',
				esc_url( YTC_URL . 'assets/js/youtube-channel.min.js' ),
				array( 'bigger-picture' ),
				YTC_VER,
				true
			);
			wp_enqueue_script( 'youtube-channel' );
		}

		wp_enqueue_style(
			'youtube-channel',
			esc_url( YTC_URL . 'assets/css/youtube-channel.min.css' ),
			array(),
			YTC_VER
		);
	} // END public function enqueue_scripts()

	/**
	 * Generate complete inline JavaScript code that conains
	 * Async video load and lightbox init for thumbnails
	 *
	 * @return void
	 */
	public function footer_scripts() {
		if ( ( ! empty( $this->ytc_html5 ) || empty( $this->defaults['nolightbox'] ) ) ) {
			echo '<!-- My YouTube Channel --><script type="text/javascript">';
			if ( $this->defaults['js_ev_listener'] ) {
				echo "window.addEventListener('DOMContentLoaded', function() {";
			}
		}

		// Print YT API only if we have set ytc_html5
		if ( ! empty( $this->ytc_html5 ) ) {

			// Inject YT.player API
			echo '
				if (!window[\'YT\']){
					var tag=document.createElement(\'script\');
					tag.src="https://www.youtube.com/iframe_api";
					var firstScriptTag=document.getElementsByTagName(\'script\')[0];
					firstScriptTag.parentNode.insertBefore(tag,firstScriptTag);
				}
				function ytc_create_ytplayers(){';
			// Print YT.player object for each video
			foreach ( $this->ytc_html5 as $player_id => $data ) {
				echo "\n";
				echo 'var ytc_player_' . esc_html( $player_id ) . '=new YT.Player(';
				echo '\'ytc_player_' . esc_html( $player_id ) . '\',';
				echo wp_json_encode( $data );
				echo ');';
			}
			// Close YT.player object
			echo '
				}
				function onYouTubeIframeAPIReady(){
					ytc_create_ytplayers();
				}
				function ytc_play(event){event.target.playVideo();}
				function ytc_playmute(event){event.target.mute();event.target.playVideo();}
			';
		} // END if ( ! empty( $this->ytc_html5 ) )

		if ( ( ! empty( $this->ytc_html5 ) || empty( $this->defaults['nolightbox'] ) ) ) {
			if ( $this->defaults['js_ev_listener'] ) {
				echo '});';
			}
			echo '</script>';
		}
	} // END public function footer_scripts()

	/**
	 * Method to render YTC shortcode
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public function shortcode( $atts ) {
		// Get general default settings
		$instance = $this->defaults;

		// Extract shortcode parameters
		$atts = shortcode_atts(
			array(
				'handle'         => $instance['handle'],
				'vanity'         => $instance['vanity'],
				'channel'        => $instance['channel'],
				'username'       => $instance['username'],
				'playlist'       => $instance['playlist'],
				'res'            => '', // (deprecated, but leave for back compatibility) ex res
				'use_res'        => '', // (deprecated, but leave for back compatibility) ex use_res
				'resource'       => $instance['resource'], // ex use_res
				'only_pl'        => 0, // disabled by default (was: $instance['only_pl'],)
				'cache'          => $instance['cache'], // ex cache_time
				'privacy'        => $instance['privacy'], // ex showvidesc
				'fetch'          => $instance['fetch'], // ex maxrnd
				'num'            => $instance['num'], // ex vidqty

				'random'         => 0, // ex getrnd

				'ratio'          => $instance['ratio'],
				'width'          => $instance['width'],
				'responsive'     => ! empty( $instance['responsive'] ) ? $instance['responsive'] : '0',

				'show'           => $instance['display'], // (deprecated, but keep for back compatibility) ex to_show
				'display'        => $instance['display'],
				'thumb_quality'  => $instance['thumb_quality'],
				'no_thumb_title' => 0,
				'controls'       => $instance['controls'],
				'autoplay'       => $instance['autoplay'],
				'mute'           => $instance['autoplay_mute'],
				'norel'          => $instance['norel'],
				'playsinline'    => $instance['playsinline'], // play video on mobile devices inline instead in native device player

				'showtitle'      => $instance['showtitle'], // none, above, below, inside, inside_b
				'linktitle'      => ! empty( $instance['linktitle'] ) ? $instance['linktitle'] : '0',
				'titletag'       => $instance['titletag'], // h3, h4, h5, div, span, strong
				'showdesc'       => $instance['showdesc'], // ex showvidesc
				'nobrand'        => ! empty( $instance['modestbranding'] ) ? $instance['modestbranding'] : '0',
				'desclen'        => $instance['desclen'], // ex videsclen
				'noanno'         => $instance['hideanno'],

				'goto_txt'       => $instance['goto_txt'],
				'popup'          => $instance['popup_goto'],
				'link_to'        => $instance['link_to'], // none, vanity, channel, legacy

				'class'          => ! empty( $instance['class'] ) ? $instance['class'] : '',

				'target'         => '',
				'skip'           => 0, // how many items to skip
			),
			$atts
		);

		// backward compatibility for show -> display shortcode parameter
		if ( ! empty( $atts['show'] ) && $atts['show'] !== $atts['display'] && $atts['show'] !== $instance['display'] ) {
			$atts['display'] = $atts['show'];
		}
		// backward compatibility for use_res -> resource shortcode parameter
		if ( ! empty( $atts['use_res'] ) ) {
			$atts['resource'] = $atts['use_res'];
		} elseif ( ! empty( $atts['res'] ) ) {
			$atts['resource'] = $atts['res'];
		}

		// prepare instance for output
		$instance['handle']   = sanitize_user( $atts['handle'], true );
		$instance['vanity']   = sanitize_user( $atts['vanity'], true );
		$instance['channel']  = $atts['channel'];
		$instance['username'] = sanitize_user( $atts['username'], true );
		$instance['playlist'] = $atts['playlist'];
		$instance['resource'] = $atts['resource']; // resource: 0 channel, 2 playlist (1 favorites and 3 liked are deprecated in 2023)
		$instance['cache']    = $atts['cache']; // in seconds, def 5min - settings?
		$instance['privacy']  = $atts['privacy']; // enhanced privacy
		$instance['fetch']    = (int) $atts['fetch'];
		$instance['num']      = (int) $atts['num']; // num: 1
		$instance['random']   = $atts['random']; // use embedded playlist - false by default

		// Video Settings
		$instance['ratio']          = $atts['ratio']; // aspect ratio: 3 - 16:9, 2 - 16:10, 1 - 4:3
		$instance['width']          = (int) $atts['width']; // 306
		$instance['responsive']     = $atts['responsive']; // enable responsivenes?
		$instance['display']        = $atts['display']; // thumbnail, iframe, iframe2, playlist
		$instance['thumb_quality']  = $atts['thumb_quality']; // default, mqdefault, hqdefault, sddefault, maxresdefault
		$instance['no_thumb_title'] = $atts['no_thumb_title']; // hide tooltip for thumbnails
		$instance['controls']       = $atts['controls']; // hide controls, false by default
		$instance['autoplay']       = $atts['autoplay']; // autoplay disabled by default
		$instance['autoplay_mute']  = $atts['mute']; // mute sound on autoplay - disabled by default
		$instance['norel']          = $atts['norel']; // hide related videos
		$instance['playsinline']    = $atts['playsinline']; // inline plaer for iOS

		// Content Layout
		$instance['showtitle']      = $atts['showtitle']; // show video title, disabled by default
		$instance['linktitle']      = $atts['linktitle']; // link title to video, disabled by default
		$instance['titletag']       = $atts['titletag']; // title HTML tag wrapper, h3 by default
		$instance['showdesc']       = $atts['showdesc']; // show video description, disabled by default
		$instance['modestbranding'] = $atts['nobrand']; // hide YT logo
		$instance['desclen']        = (int) $atts['desclen']; // cut video description, number of characters
		$instance['hideanno']       = $atts['noanno']; // hide annotations, false by default

		// Link to Channel
		$instance['goto_txt']   = $atts['goto_txt']; // text for goto link - use settings
		$instance['popup_goto'] = $atts['popup']; // open channel in: 0 same window, 1 javascript new, 2 target new
		$instance['link_to']    = $atts['link_to']; // link to: none, vanity, legacy, channel

		// Customization
		$instance['class']  = sanitize_html_classes( $atts['class'] ); // custom additional class for container
		$instance['target'] = $atts['target']; // should use target for thumbnails w/o lightbox (`0` || `1`|`2`)
		$instance['skip']   = (int) $atts['skip'];

		return $this->generate_ytc_block( $instance );
	} // END public function shortcode()

	/**
	 * Generate HTML of YTC block
	 *
	 * @param array $instance Widget or Shortcode settings
	 *
	 * @return string         HTML content of the YTC block
	 */
	public function generate_ytc_block( $instance ) {
		// Error message if no YouTube Data API Key
		if ( empty( $this->defaults['apikey'] ) ) {

			$error_msg = sprintf(
				// translators: %1$s is replaced with plugin name, %2$s with link to DATA API Key instructions
				__( '<strong>%1$s v3</strong> requires <strong>YouTube DATA API Key</strong> to work. <a href="%2$s" target="_blank">Learn more here</a>.', 'wpau-yt-channel' ),
				YTC_PLUGIN_NAME,
				'https://urosevic.net/wordpress/plugins/youtube-channel/#youtube_data_api_key'
			);

			return $this->front_debug( $error_msg );

		}

		// 1) Get resource from widget/shortcode
		// 2) If not set, get global default
		// 3) if no global, get plugin's default
		if ( ! isset( $instance['resource'] ) ) {
			$instance['resource'] = $this->defaults['resource'];
		}
		$resource = intval( $instance['resource'] );
		if ( empty( $resource ) && 0 !== $resource ) {
			$resource = intval( $this->defaults['resource'] );
			if ( empty( $resource ) ) {
				$resource = 0;
			}
		}

		// Get Channel or Playlist ID based on requested resource
		switch ( $resource ) {

			// Playlist
			case '2':
				// 1) Get Playlist from shortcode/widget
				// 2) If not set, use global default
				// 3) If no global, throw error
				if ( ! empty( $instance['playlist'] ) ) {
					$playlist = ytc_sanitize_api_key( $instance['playlist'] );
				} else {
					$playlist = ytc_sanitize_api_key( $this->defaults['playlist'] );
				}
				// Now check has Playlist ID set or throw error
				if ( '' === $playlist ) {
					return $this->front_debug( 'Playlist selected as resource but no Playlist ID provided!' );
				}
				break;

			// Channel, Favourites, Liked
			default:
				/* Channel */
				// 1) Get channel from shortcode/widget
				// 2) If not set, use global default
				// 3) If no global, throw error
				if ( ! empty( $instance['channel'] ) ) {
					$channel = ytc_sanitize_api_key( $instance['channel'] );
				} else {
					$channel = ytc_sanitize_api_key( $this->defaults['channel'] );
				}

				// Now check is Channel ID set or throw error
				if ( '' === $channel ) {
					if ( 1 === (int) $resource ) {
						$resource_name = 'deprecated Favourited videos';
					} elseif ( 3 === (int) $resource ) {
						$resource_name = 'deprecated Liked videos';
					} else {
						$resource_name = 'Channel (User uploads)';
					}
					$error_msg = sprintf( '%s selected as resource but no Channel ID provided!', $resource_name );
					return $this->front_debug( $error_msg );
				}
		} // END switch ($resource)

		/* OK, we have required resource (Playlist or Channel ID), so we can proceed to real job */

		// Set custom class and responsive if needed
		$class = ( ! empty( $instance['class'] ) ) ? sanitize_html_classes( $instance['class'] ) : 'default';
		if ( ! empty( $instance['responsive'] ) ) {
			$class .= ' responsive';
		}
		if ( ! empty( $instance['display'] ) ) {
			$class .= ' ytc_display_' . esc_attr( $instance['display'] );
		}

		switch ( $resource ) {
			case 2: // Playlist
				$resource_name = 'playlist';
				$resource_id   = $playlist;
				break;
			default: // Channel (#1 Favourites (FL) and #3 Liked (LL) are deprecated)
				$resource_name = 'channel';
				$resource_id   = preg_replace( '/^UC/', 'UU', $channel );
		}

		// Start output string
		$output = '';

		$output .= '<div class="youtube_channel ' . $class . '">';

		if ( empty( $instance['display'] ) ) {
			$instance['display'] = $this->defaults['display'];
		}
		if ( 'playlist' === $instance['display'] ) { // Insert as Embedded playlist

			$output .= $this->generate_playlist_embed( $resource_id, $instance );

		} else { // Individual videos from channel or playlist (favourites and liked are deprecated)

			// Get max items for random video
			$fetch = empty( $instance['fetch'] ) ? $this->defaults['fetch'] : $instance['fetch'];
			if ( $fetch < 1 ) {
				$fetch = 10;
			} elseif ( $fetch > 50 ) {
				$fetch = 50;
			}

			// How many items to skip?
			$skip = 0;
			if ( ! empty( $instance['skip'] ) ) {
				$skip = intval( $instance['skip'] ) > 49 ? 49 : intval( $instance['skip'] );
			}
			// If we have to skip more items than we have in fetch, set skip to $fetch-1
			if ( $skip >= $fetch ) {
				$skip = $fetch - 1;
			}

			$resource_key = $resource_id . '_' . $fetch;

			// Do we need cache? Let we define cache fallback key
			$cache_key_fallback = sanitize_key( 'ytc_' . md5( $resource_key ) . '_fallback' );

			// Do cache magic
			if ( ! empty( $instance['cache'] ) && $instance['cache'] > 0 ) {

				// generate feed cache key for caching time
				$cache_key = sanitize_key( 'ytc_' . md5( $resource_key ) . '_' . $instance['cache'] );

				if ( ! empty( $_GET['ytc_force_recache'] ) ) {
					delete_transient( $cache_key );
				}

				// get/set transient cache
				$json = get_transient( $cache_key );
				if ( false === $json || empty( $json ) ) {

					// no cached JSON, get new
					$json = $this->fetch_youtube_feed( $resource_id, $fetch );

					// set decoded JSON to transient cache_key
					set_transient( $cache_key, base64_encode( $json ), $instance['cache'] );

				} else {

					// we already have cached feed JSON, get it encoded
					$json = base64_decode( $json );

				}
			} else {

				// just get fresh feed if cache disabled
				$json = $this->fetch_youtube_feed( $resource_id, $fetch );

			}

			// free some memory
			unset( $response );

			// decode JSON data
			$json_output = json_decode( $json );

			// YTC 3.0.7: Do we need this, still?
			// if current feed is messed up, try to get it from fallback cache
			if ( is_wp_error( $json_output ) && ! is_object( $json_output ) && empty( $json_output->items ) ) {
				// do we have fallback cache?!
				$json_fallback = get_transient( $cache_key_fallback );
				if ( true === $json_fallback && ! empty( $json_fallback ) ) {
					$json_output = json_decode( base64_decode( $json_fallback ) );
					// and free memory
					unset( $json_fallback );
				}
			}

			// Get resource nice name based on selected resource
			$resource_nice_name = $this->get_resource_nice_name( $resource );

			// Prevent further checks if we have WP Error or empty record even after fallback
			if ( is_wp_error( $json_output ) ) {
				$output .= $this->front_debug( $json_output->get_error_message() );
				return $output;
			} elseif ( isset( $json_output->items ) && 0 === sizeof( $json_output->items ) ) {
				// translators: %1$s is replaced with resource nice name, %2$s is replaced with Resource ID
				$output .= $this->front_debug( sprintf( __( 'You have set to display videos from %1$s [resource list ID: %2$s], but there have no public videos in that resouce.' ), $resource_nice_name, $resource_id ) );
				return $output;
			} elseif ( empty( $json_output ) ) {
				// translators: %1$s is replaced with URL to plugin FAQ page, %2$s with link to official plugin page
				$output .= $this->front_debug( sprintf( __( 'We have empty record for this feed. Please read <a href="%1$s" target="_blank">FAQ</a> and if that does not help, contact <a href="%2$s" target="_blank">support</a>.' ), 'https://wordpress.org/plugins/youtube-channel#faq', 'https://wordpress.org/support/plugin/youtube-channel/' ) );
				return $output;
			}

			// Predefine `max_items` to prevent undefined notices
			$max_items = 0;
			if ( is_object( $json_output ) && ! empty( $json_output->items ) ) {

				// Sort by date uploaded
				$json_entry = $json_output->items;

				$num = ( empty( $instance['num'] ) ) ? $this->defaults['num'] : $instance['num'];
				if ( $num > $fetch ) {
					$fetch = $num;
				}
				$max_items = ( $fetch > sizeof( $json_entry ) ) ? sizeof( $json_entry ) : $fetch;

				if ( ! empty( $instance['random'] ) ) {
					$items = array_slice( $json_entry, 0, $max_items );
				} else {
					if ( ! $num ) {
						$num = 1;
					}
					$items = array_slice( $json_entry, $skip, $num );
				}
			}

			if ( 0 === $max_items ) {

				// Set default error message
				$error_msg = 'Unrecognized error experienced.';

				// Append YouTube DATA API error reason as comment
				if ( ! empty( $json_output ) && is_object( $json_output ) && ! empty( $json_output->error->errors ) ) {

					// Error went in fetch_youtube_feed()
					if ( 'wpError' === $json_output->error->errors[0]->reason ) {
						$error_msg = $json_output->error->errors[0]->message;
					} elseif ( 'playlistNotFound' === $json_output->error->errors[0]->reason ) {
						// Playlist error from Google API
						if ( 'playlist' === $resource_name ) {
							$error_msg = "Please check did you set existing <em>Playlist ID</em>. You set to show videos from {$resource_nice_name}, but YouTube does not recognize <strong>{$resource_id}</strong> as existing and public playlist.";
						} else {
							$error_msg = "Please check did you set the proper <em>Channel ID</em>. You set to show videos from {$resource_nice_name}, but YouTube does not recognize <strong>{$channel}</strong> as an existing or public channel.";
						}
					} elseif ( 'keyInvalid' === $json_output->error->errors[0]->reason ) {
						// Invalid YouTube Data API Key
						// translators: %s is replaced with link to official plugin Installation page
						$error_msg = sprintf( __( "Double check <em>YouTube Data API Key</em> on <em>General</em> plugin tab and make sure it's correct. Read <a href=\"%s\" target=\"_blank\">Installation</a> document." ), 'https://wordpress.org/plugins/youtube-channel/installation/' );
					} elseif ( 'ipRefererBlocked' === $json_output->error->errors[0]->reason ) {
						// Restricted access YouTube Data API Key
						$error_msg = 'Check <em>YouTube Data API Key</em> restrictions, empty cache if enabled by appending in the browser address bar parameter <em>?ytc_force_recache=1</em>';
					} elseif ( 'invalidChannelId' === $json_output->error->errors[0]->reason ) {
						// (deprecated?) Non existing Channel ID set
						// translators: %s is replaced with link to official plugin FAQ page
						$error_msg = sprintf( __( 'You have set wrong Channel ID. Fix that in General plugin settings, Widget and/or shortcode. Read <a href="%s" target="_blank">FAQ</a> document.' ), 'https://wordpress.org/plugins/youtube-channel/faq/' );
					} elseif ( 'playlistItemsNotAccessible' === $json_output->error->errors[0]->reason ) {
						// Forbidden access to resource
						// translators: %s is replaced with Resource ID
						$error_msg = sprintf( __( "You do not have permission to access ressource <strong>%s</strong> (it's maybe set to private or even does not exists!)" ), $resource_id );
					} else {
						$error_msg = sprintf(
							'Reason: %1$s; Domain: %2$s; Message: %3$s',
							$json_output->error->errors[0]->reason,
							$json_output->error->errors[0]->domain,
							$json_output->error->errors[0]->message
						);
					}
				} // END ! empty($json_output->error->errors)

				$output .= $this->front_debug( $error_msg );

			} else { // ELSE if ($max_items == 0)

				// looks that feed is OK, let we update fallback that never expire
				set_transient( $cache_key_fallback, base64_encode( $json ), 0 );

				// and now free some memory
				unset( $json, $json_output, $json_entry );

				// set array for unique random item
				if ( ! empty( $instance['random'] ) ) {
					$random_used = array();
				}

				/* AU:20141230 reduce number of videos if requested > available */
				if ( $num > sizeof( $items ) ) {
					$num = sizeof( $items );
				}
				$rand_fn = function_exists( 'random_int' ) ? 'random_int' : 'mt_rand';

				for ( $y = 1; $y <= $num; ++$y ) {
					if ( ! empty( $instance['random'] ) ) {

						$random_item = $rand_fn( 0, ( count( $items ) - 1 ) );
						while ( $y > 1 && in_array( $random_item, $random_used, true ) ) {
							$random_item = $rand_fn( 0, ( count( $items ) - 1 ) );
						}
						$random_used[] = $random_item;
						$item          = $items[ $random_item ];
					} else {
						$item = $items[ $y - 1 ];
					}

					// Generate single video block
					$video_block = $this->generate_video_block( $item, $instance, $y );
					// Allow plugins/themes to override the default video block template.
					$video_block = apply_filters( 'ytc_print_video', $video_block, $item, $instance, $y );
					// Append video block to final output
					$output .= $video_block;
				}
				// Free some memory
				unset( $random_used, $random_item, $json );

			} // END if ($max_items == 0)
		} // single playlist or ytc way

		// Append link to channel on bootom of the widget
		$output .= $this->get_footer( $instance );

		$output .= '</div><!-- .youtube_channel -->';

		// fix overflow on crappy themes
		$output .= '<div class="clearfix"></div>';

		return wp_kses(
			$output,
			array(
				'p'      => array(
					'class' => array(),
				),
				'div'    => array(
					'id'    => array(),
					'class' => array(),
					'style' => array(),
				),
				'a'      => array(
					'href'        => array(),
					'target'      => array(),
					'title'       => array(),
					'class'       => array(),
					'data-iframe' => array(),
					'data-title'  => array(),
				),
				'h3'     => array(
					'id'    => array(),
					'class' => array(),
					'title' => array(),
					'style' => array(),
				),
				'h4'     => array(
					'id'    => array(),
					'class' => array(),
					'title' => array(),
					'style' => array(),
				),
				'h5'     => array(
					'id'    => array(),
					'class' => array(),
					'title' => array(),
					'style' => array(),
				),
				'strong' => array(
					'class' => array(),
				),
				'span'   => array(
					'id'    => array(),
					'class' => array(),
					'title' => array(),
					'style' => array(),
				),
				'iframe' => array(
					'id'              => array(),
					'src'             => array(),
					'title'           => array(),
					'width'           => array(),
					'height'          => array(),
					'style'           => array(),
					'allowfullscreen' => array(),
				),
			),
		);
	} // END public function generate_ytc_block( $instance )

	// --- HELPER FUNCTIONS ---

	/**
	 * Download YouTube video feed through API 3.0
	 * @param  string $id       ID of resource
	 * @param  integer $items   Number of items to fetch (min 2, max 50)
	 * @return string           JSON with videos
	 */
	private function fetch_youtube_feed( $resource_id, $items ) {
		$feed_url  = 'https://www.googleapis.com/youtube/v3/playlistItems?';
		$feed_url .= 'part=snippet';
		$feed_url .= '&playlistId=' . ytc_sanitize_api_key( $resource_id );
		$feed_url .= '&fields=items(snippet(title%2Cdescription%2CpublishedAt%2CresourceId(videoId)))';
		$feed_url .= '&maxResults=' . intval( $items );
		$feed_url .= '&key=' . ytc_sanitize_api_key( $this->defaults['apikey'] );

		$wparg = array(
			'timeout'   => intval( $this->defaults['timeout'] ),
			'sslverify' => $this->defaults['sslverify'] ? true : false,
			'headers'   => array( 'referer' => site_url() ),
		);

		$response = wp_remote_get( $feed_url, $wparg );

		// If we have WP error, make JSON with error
		if ( is_wp_error( $response ) ) {

			$json = sprintf( '{"error":{"errors":[{"reason":"wpError","message":"%s","domain":"wpRemoteGet"}]}}', $response->get_error_message() );

		} else {

			$json = wp_remote_retrieve_body( $response );

		}

		// Free some memory
		unset( $response );

		return $json;
	} // END private function fetch_youtube_feed($resource_id, $items)

	/**
	 * Print explanation of error for administrators (users with capability manage_options)
	 * and hidden message for lower users and visitors
	 * @param  string $message Error message
	 * @return string          Formatted message for error
	 */
	private function front_debug( $message ) {
		// Show visible error to admin, Oops message to visitors and lower members
		if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {

			$output = "<p class=\"ytc_error\"><strong>YTC ERROR:</strong> $message</p>";

		} else {

			$output = __( 'Oops, something went wrong.', 'wpau-yt-channel' );

		}

		return wp_kses(
			$output,
			array(
				'strong' => array(),
				'em'     => array(),
				'p'      => array(
					'class' => array(),
				),
				'a'      => array(
					'href'   => array(),
					'target' => array(),
				),

			),
		);
	} // END private function debug( $message )

	/**
	 * Calculate height by provided width and aspect ratio
	 *
	 * @param  integer $width Width in pixels
	 * @param  integer $ratio Selected aspect ratio (1 for 4:3, other for 16:9)
	 *
	 * @return integer        Calculated height in pixels
	 */
	private function calculate_height( $width = 306, $ratio = 2 ) {
		switch ( $ratio ) {
			case 1:
				$height = round( ( intval( $width ) / 4 ) * 3 );
				break;
			default:
				$height = round( ( intval( $width ) / 16 ) * 9 );
		}
		return intval( $height );
	} // END function calculate_height( $width = 306, $ratio )

	/**
	 * Generate link to YouTube channel/user
	 *
	 * @param  array $instance Widget or shortcode settings
	 *
	 * @return string          Generated HTML for block footer
	 */
	private function get_footer( $instance ) {
		// Get link to channel
		$link_to_channel = $this->get_link_to_channel( $instance );

		// Wrap content, if we have it
		if ( ! empty( $link_to_channel ) ) {
			return '<div class="clearfix"></div><div class="ytc_link"><p>' . $link_to_channel . '</p></div>';
		}

		return;
	} // END function get_footer( $instance )

	/**
	 * Generate link to YouTube channel/user
	 *
	 * @param  array $instance Widget or shortcode settings
	 *
	 * @return string          Prepared link to channel HTML code
	 */
	private function get_link_to_channel( $instance ) {
		// Do we need to show goto link?
		if ( empty( $instance['link_to'] ) || 'none' === $instance['link_to'] ) {
			return;
		}

		// Initialize output string
		$output = '';

		$goto_url = 'https://www.youtube.com/';

		$handle   = ! empty( $instance['handle'] ) ? $instance['handle'] : $this->defaults['handle'];
		$vanity   = ! empty( $instance['vanity'] ) ? $instance['vanity'] : $this->defaults['vanity'];
		$username = ! empty( $instance['username'] ) ? $instance['username'] : $this->defaults['username'];
		$channel  = ! empty( $instance['channel'] ) ? $instance['channel'] : $this->defaults['channel'];

		switch ( $instance['link_to'] ) {
			case 'handle':
				if ( empty( $handle ) ) {
					return '<!-- YTC ERROR: Selected handle custom URL to be linked but no Handle provided! -->';
				}
				// sanity handle content (strip all in front of last slash to cleanup handle ID only)
				if ( false !== strpos( $handle, 'youtube.com' ) ) {
					$handle = preg_replace( '/^.*\//', '', $handle );
				}
				$goto_url .= $handle;
				break;

			case 'channel':
				if ( empty( $channel ) ) {
					return '<!-- YTC ERROR: Selected Channel page to be linked but no Channel ID provided! -->';
				}
				$goto_url .= "channel/$channel";
				break;

			case 'vanity': // deprecated
				if ( empty( $vanity ) ) {
					return '<!-- YTC ERROR: Selected Vanity custom URL to be linked but no Vanity Name provided! -->';
				}
				// sanity vanity content (strip all in front of last slash to cleanup vanity ID only)
				if ( false !== strpos( $vanity, 'youtube.com' ) ) {
					$vanity = preg_replace( '/^.*\//', '', $vanity );
				}
				$goto_url .= "c/$vanity";
				break;

			case 'legacy': // deprecated
				if ( empty( $username ) ) {
					return '<!-- YTC ERROR: Selected Legacy username to be linked but no Legacy username provided! -->';
				}
				$goto_url .= "user/$username";
				break;

		}

		$goto_txt = trim( $instance['goto_txt'] );
		if ( empty( $goto_txt ) ) {
			$goto_txt = __( 'Visit our YouTube channel', 'wpau-yt-channel' );
		}
		// replace placeholders
		$goto_txt = str_replace( '%handle%', $handle, $goto_txt );
		$goto_txt = str_replace( '%channel%', $channel, $goto_txt );
		$goto_txt = str_replace( '%vanity%', $vanity, $goto_txt ); // deprecated
		$goto_txt = str_replace( '%user%', $username, $goto_txt ); // deprecated

		$newtab = __( 'in new window/tab', 'wpau-yt-channel' );

		switch ( $instance['popup_goto'] ) {
			case 1: // JavaScript is deprecated in 3.24.0
			case 2:
				$output .= '<a href="' . esc_url( $goto_url ) . '" target="youtube" title="' . esc_html( "$goto_txt $newtab" ) . '">' . esc_html( $goto_txt ) . '</a>';
				break;
			default:
				$output .= '<a href="' . esc_url( $goto_url ) . '" title="' . esc_html( $goto_txt ) . '">' . esc_html( $goto_txt ) . '</a>';
		} // switch popup_goto

		return $output;
	} // END private function get_link_to_channel( $instance )

	/**
	 * Generate HTML output for single video block
	 *
	 * @param  object $item     Video object from JSON
	 * @param  array  $instance Settings from widget or shortcode
	 * @param  int    $y        Order number of video
	 *
	 * @return string           Prepared single video block as HTML
	 */
	private function generate_video_block( $item, $instance, $y ) {
		// Start output string
		$output = '';

		// Calculate width and height
		if ( empty( $instance['ratio'] ) ) {
			$instance['ratio'] = $this->defaults['ratio'];
		}

		/**
		 * @var integer $width  Sanitized width of YouTube video
		 */
		$width = ! empty( $instance['width'] ) ? intval( $instance['width'] ) : intval( $this->defaults['width'] );

		/**
		 * @var integer $height Sanitized calculated height of YouTube video
		 */
		$height = $this->calculate_height( $width, $instance['ratio'] );

		// How to display videos?
		if ( empty( $instance['display'] ) ) {
			$instance['display'] = 'thumbnail';
		}

		// Extract details about video from Resource
		/**
		 * @var string $yt_id   Sanitized YouTube Video ID
		 */
		$yt_id = ytc_sanitize_api_key( $item->snippet->resourceId->videoId );

		/**
		* @var string $yt_title Sanitized Title of YouTube video
		*/
		$yt_title = sanitize_text_field( $item->snippet->title );
		// $yt_date  = $item->snippet->publishedAt;

		// Enhanced privacy?
		/**
		 * @var string $youtube_domain YouTube domain without protocol and trailing slash
		 */
		$youtube_domain = $this->get_youtube_domain( $instance );

		/**
		 * @var string $vnumclass HTML class `first` | `mid` | `last`
		 */
		$vnumclass = 'mid';
		switch ( $y ) {
			case 1:
				$vnumclass = 'first';
				break;
			case intval( $instance['num'] ):
				// $autoplay  = false;
				$vnumclass = 'last';
				break;
			default:
				$vnumclass = 'mid';
				// $autoplay  = false;
				break;
		}

		/**
		 * Aspect ratio class
		 *
		 * @todo deprecate and always use ar16_9
		 *
		 * @var string $arclass HTML class for aspect ratio ar4_3 | ar16_9
		 */
		$arclass = $this->get_ar_class( $instance );

		/**
		 * @var string $title_html_tag HTML tag for title (eg. `h3`, `div`, `span`, `strong`, etc)
		 */
		$title_html_tag = isset( $instance['titletag'] ) ? sanitize_key( $instance['titletag'] ) : sanitize_key( $this->defaults['titletag'] );

		$output .= '<div class="ytc_video_container ytc_video_' . intval( $y ) . ' ytc_video_' . $vnumclass . ' ' . $arclass . '" style="width:' . $width . 'px">';

		// Show video title above video?
		if ( ! empty( $instance['showtitle'] ) ) {
			if (
				// for non-thumbnail for `above` and `inside`
				( 'thumbnail' !== $instance['display'] && in_array( $instance['showtitle'], array( 'above', 'inside' ), true ) ) ||
				// for thubmanil only if it's `below`
				( 'thumbnail' === $instance['display'] && 'above' === $instance['showtitle'] )
			) {
				if ( ! empty( $instance['linktitle'] ) ) {
					$output .= sprintf(
						'<%1$s class="ytc_title ytc_title_above"><a href="https://www.youtube.com/watch?v=%3$s" target="youtube">%2$s</a></%1$s>',
						$title_html_tag,
						esc_html( $yt_title ),
						$yt_id
					);
				} else {
					$output .= sprintf(
						'<%1$s class="ytc_title ytc_title_above">%2$s</%1$s>',
						$title_html_tag,
						esc_html( $yt_title ),
					);
				}
			}
		}

		// Print out video
		if ( 'iframe' === $instance['display'] ) {

			// Start wrapper for responsive item
			if ( $instance['responsive'] ) {
				$output .= '<div class="fluid-width-video-wrapper">';
			}

			$output .= '<iframe title="YouTube Video Player" width="' . $width . '" height="' . $height . '" src="' . esc_url( 'https://' . $youtube_domain . '/embed/' . $yt_id . '?wmode=opaque' );

			// disable related vides
			if ( ! empty( $instance['norel'] ) ) {
				$output .= '&amp;rel=0';
			}
			if ( ! empty( $instance['controls'] ) ) {
				$output .= '&amp;controls=0';
			}
			if ( ! empty( $instance['autoplay'] ) && 1 === $y ) {
				$output .= '&amp;autoplay=1';
			}
			if ( ! empty( $instance['hideanno'] ) ) {
				$output .= '&amp;iv_load_policy=3';
			}
			if ( ! empty( $instance['modestbranding'] ) ) {
				$output .= '&amp;modestbranding=1';
			}
			if ( ! empty( $instance['playsinline'] ) ) {
				$output .= '&amp;playsinline=1';
			}

			$output .= '" style="border:0" allowfullscreen id="ytc_' . $yt_id . '"></iframe>';

			// Close wrapper for responsive item
			if ( $instance['responsive'] ) {
				$output .= '</div>';
			}
		} elseif ( 'iframe2' === $instance['display'] ) {

			// Start wrapper for responsive item
			if ( $instance['responsive'] ) {
				$output .= '<div class="fluid-width-video-wrapper">';
			}

			/**
			 * @var string $js_player_id Unique YTC item ID
			 */
			$js_player_id = count( $this->ytc_html5 ) . '_' . str_replace( '-', '_', $yt_id );

			// Inject YT.player placeholder
			$output .= '<div id="ytc_player_' . $js_player_id . '"></div>';

			// Close wrapper for responsive item
			if ( $instance['responsive'] ) {
				$output .= '</div>';
			}

			/**
			 * Prepare YT.player settings object for single video item
			 */
			$ytc_player = array(
				'height'  => $height,
				'width'   => $width,
				'videoId' => $yt_id,
			);
			if ( ! empty( $instance['norel'] ) ) {
				$ytc_player['playerVars']['rel'] = 0;
			}
			if ( ! empty( $instance['controls'] ) ) {
				$ytc_player['playerVars']['controls'] = 0;
			}
			if ( ! empty( $instance['modestbranding'] ) ) {
				$ytc_player['playerVars']['modestbranding'] = 1;
			}
			if ( ! empty( $instance['playsinline'] ) ) {
				$ytc_player['playerVars']['playsinline'] = 1;
			}
			$ytc_player['playerVars']['wmmode'] = 'opaque';

			if ( ! empty( $instance['hideanno'] ) ) {
				$ytc_player['iv_load_policy'] = 3;
			}
			// Autoplay may not work https://developer.chrome.com/blog/autoplay/
			if ( ! empty( $instance['autoplay'] ) && 1 === $y && empty( $this->ytc_html5 ) ) {
				$ytc_player['playerVars']['autoplay'] = 1;
				if ( ! empty( $instance['autoplay_mute'] ) ) {
					$ytc_player['events'] = array(
						'onReady' => 'ytc_playmute',
					);
				} else {
					$ytc_player['events'] = array(
						'onReady' => 'ytc_play',
					);
				}
			}
			$ytc_player['origin'] = sanitize_url( $_SERVER['HTTP_HOST'] );

			// Append YT.player object to site-wide ytc_html5 players
			$this->ytc_html5[ $js_player_id ] = $ytc_player;

		} else { // default is thumbnail

			/**
			 * Target atribute for thumbnail anchor
			 *
			 * @var string $target Empty if lightbox is used or `target="youtube"` tag attribute
			 */
			$target = '';

			/**
			 * URL query with YouTube Video parameters
			 *
			 * @var string $http_query Empty or safe URL parameters
			 */
			$http_query = '';
			if ( empty( $this->defaults['nolightbox'] ) ) {
				$params = array();
				if ( ! empty( $instance['norel'] ) ) {
					$params['rel'] = 0;
				}
				if ( ! empty( $instance['modestbranding'] ) ) {
					$params['modestbranding'] = 1;
				}
				if ( ! empty( $instance['controls'] ) ) {
					$params['controls'] = 0;
				}
				if ( ! empty( $instance['playsinline'] ) ) {
					$params['playsinline'] = 1;
				}
				if ( ! empty( $instance['privacy'] ) ) {
					$params['enhanceprivacy'] = 1;
				}
				if ( ! empty( $instance['autoplay'] ) ) {
					$params['autoplay'] = 1;
				}
				$http_query     = http_build_query( $params );
				$lightbox_class = 'ytc-lightbox';
			} else {
				$lightbox_class = 'ytc-nolightbox';
				if ( ! empty( $instance['popup_goto'] ) ) {
					$target = 'target="youtube"';
				}
			}

			/**
			 * Title parameter for anchor tag
			 *
			 * @var string $tag_title Empty or `title="YouTube Video Sanitized Title"` tag attribute
			 */
			$tag_title = empty( $instance['no_thumb_title'] ) ? 'title="' . esc_html( $yt_title ) . '"' : '';

			// Define video thumbnail
			if ( empty( $instance['thumb_quality'] ) || ! in_array( $instance['thumb_quality'], array( 'default', 'mqdefault', 'hqdefault', 'sddefault', 'maxresdefault' ), true ) ) {
				$instance['thumb_quality'] = 'hqdefault';
			}
			/**
			 * @var string $yt_thumb Sanitized URL to video thumbnail
			 */
			$yt_thumb = $this->image_handler->get_youtube_image_url( $yt_id, esc_attr( $instance['thumb_quality'] ) );
			// $yt_thumb = 'https://img.youtube.com/vi/' . $yt_id . '/' . esc_attr( $instance['thumb_quality'] ) . '.jpg';

			// Show video title inside video?
			/**
			 * HTML code of video title inside the thumbnail
			 *
			 * @var string $title_inside_html HTML element for video title inside thumbnail
			 */
			$title_inside_html = '';
			if ( ! empty( $instance['showtitle'] ) && in_array( $instance['showtitle'], array( 'inside', 'inside_b' ), true ) ) {
				$title_inside_html = sprintf(
					'<%1$s class="ytc_title ytc_title_inside %3$s">%2$s</%1$s>',
					$title_html_tag,
					esc_html( $yt_title ),
					'inside_b' === $instance['showtitle'] ? 'ytc_title_inside_bottom' : ''
				);
			}
			$output .= sprintf(
				'<a href="https://www.youtube.com/watch?v=%1$s&%2$s" class="ytc_thumb %4$s" %5$s %3$s
					data-iframe="https://%8$s/embed/%1$s?%2$s"
					data-title="%9$s"
				><span style="background-image: url(%6$s);" %3$s id="ytc_%1$s">%7$s</span></a>',
				$yt_id, // 1
				$http_query, //2
				$tag_title, // 3
				$lightbox_class . ' ' . $arclass, // 4
				$target, // 5
				$yt_thumb, // 6
				$title_inside_html, // 7
				$youtube_domain, // 8
				esc_html( $yt_title )
			);
		} // what to show conditions

		// Show video title below video?
		if ( ! empty( $instance['showtitle'] ) ) {
			if (
				// for non-thumbnail for `below` and `inside_b`
				( 'thumbnail' !== $instance['display'] && in_array( $instance['showtitle'], array( 'below', 'inside_b' ), true ) ) ||
				// for thubmanil only if it's `below`
				( 'thumbnail' === $instance['display'] && 'below' === $instance['showtitle'] )
			) {
				if ( ! empty( $instance['linktitle'] ) ) {
					$output .= sprintf(
						'<%1$s class="ytc_title ytc_title_below"><a href="https://www.youtube.com/watch/?v=%3$s" target="youtube">%2$s</a></%1$s>',
						$title_html_tag,
						esc_html( $yt_title ),
						$yt_id
					);
				} else {
					$output .= sprintf(
						'<%1$s class="ytc_title ytc_title_below">%2$s</%1$s>',
						$title_html_tag,
						esc_html( $yt_title )
					);
				}
			}
		}

		// do we need to show video description?
		if ( ! empty( $instance['showdesc'] ) ) {

			/**
			 * YouTube Video description
			 *
			 * @todo If description should not be shortened, print HTML formatted desc
			 *
			 * @var string $video_description Raw HTML of YouTube video description
			 */
			$video_description = $item->snippet->description;

			if ( $instance['desclen'] > 0 ) {
				if ( function_exists( 'mb_strlen' ) && function_exists( 'mb_substr' ) ) {
					if ( mb_strlen( $video_description ) > $instance['desclen'] ) {
						$video_description = mb_substr( $video_description, 0, $instance['desclen'] ) . '&hellip;';
					}
				} else {
					if ( strlen( $video_description ) > $instance['desclen'] ) {
						$video_description = substr( $video_description, 0, $instance['desclen'] ) . '&hellip;';
					}
				}
			}

			if ( ! empty( $video_description ) ) {
				$output .= '<p class="ytc_description">' . esc_html( $video_description ) . '</p>';
			}
		}

		$output .= '</div><!-- .ytc_video_container -->';

		return $output;
	} // END private function generate_video_block( $item, $instance, $y )

	/**
	 * Function to print standard playlist embed code
	 *
	 * @param string $resource_id YouTube Channel or Playlist ID
	 * @param object $instance    Settings from widget or shortcode
	 *
	 * @return string             Prepared HTML embed code
	 */
	private function generate_playlist_embed( $resource_id, $instance ) {
		$width          = empty( $instance['width'] ) ? 306 : intval( $instance['width'] );
		$height         = $this->calculate_height( $width, $instance['ratio'] );
		$autoplay       = empty( $instance['autoplay'] ) ? '' : '&autoplay=1';
		$modestbranding = empty( $instance['modestbranding'] ) ? '' : '&modestbranding=1';
		$rel            = empty( $instance['norel'] ) ? '' : '&rel=0';
		$playsinline    = empty( $instance['playsinline'] ) ? '' : '&playsinline=1';

		// enhanced privacy
		$youtube_domain = $this->get_youtube_domain( $instance );
		$arclass        = $this->get_ar_class( $instance );

		// Start output string
		$output = '';

		$output .= '<div class="ytc_video_container ytc_video_1 ytc_video_single ytc_playlist_only ' . esc_attr( $arclass ) . '">';
		$output .= '<div class="fluid-width-video-wrapper">';
		$output .= '<iframe src="' . esc_url( 'https://' . $youtube_domain . '/embed/videoseries?list=' . $resource_id . $autoplay . $modestbranding . $playsinline . $rel ) . '"';
		if ( ! empty( $instance['fullscreen'] ) ) {
			$output .= ' allowfullscreen';
		}
		$output .= ' width="' . intval( $width ) . '" height="' . intval( $height ) . '" frameborder="0"></iframe>';
		$output .= '</div><!-- .fluid-width-video-wrapper -->';
		$output .= '</div><!-- .ytc_video_container -->';

		return $output;
	} // END private function generate_playlist_embed( $resource_id, $instance )

	/**
	 * Method to delete all YTC transient caches
	 * @return string Report message about success or failed purge cache
	 */
	public function clear_all_cache() {
		if (
			! current_user_can( 'activate_plugins' ) ||
			(
				! isset( $_POST['nonce'] ) ||
				! wp_verify_nonce( $_POST['nonce'], 'action-ytc_clear_all_cache' )
			)
		) {
			echo 'Oops, insufficient permissions to clear My YouTube Channel cache.';
			wp_die();
		}

		global $wpdb;

		$ret = $wpdb->query(
			$wpdb->prepare(
				"DELETE FROM $wpdb->options
					WHERE option_name LIKE %s
					OR option_name LIKE %s
				",
				'_transient_timeout_ytc_%',
				'_transient_ytc_%'
			)
		);

		if ( false === $ret ) {
			echo 'Oops, we did not cleared any My YouTube Channel cache because some error occured';
		} else {
			if ( 0 === $ret ) {
				echo 'Congratulations! You can chill, there is no My YouTube Channel caches.';
			} else {
				echo "Success! We cleared $ret row/s with My YouTube Channel caches.";
			}
		}
		wp_die();
	} // END public function clear_all_cache()

	/**
	 * Return nice name for resource by provided resource ID
	 *
	 * @param  integer $resource_id Resource ID
	 *
	 * @return string               Resource nice name
	 */
	private function get_resource_nice_name( $resource_id ) {
		if ( 0 === (int) $resource_id ) {
			$resource_nice_name = 'Channel (User uploads)';
		} elseif ( 1 === (int) $resource_id ) {
			$resource_nice_name = 'Favourited videos';
		} elseif ( 2 === (int) $resource_id ) {
			$resource_nice_name = 'Liked videos';
		} elseif ( 3 === (int) $resource_id ) {
			$resource_nice_name = 'Liked videos';
		} else {
			$resource_nice_name = 'Unknown resource';
		}
		return $resource_nice_name;
	} // END private function get_resource_nice_name( $resource_id )

	/**
	 * Define YouTube domain with or without nocookie sufix
	 *
	 * @param array $instance YTC object
	 *
	 * @return string         YouTube domain without protocol and trailing slash
	 */
	private function get_youtube_domain( $instance ) {
		return empty( $instance['privacy'] ) ? 'www.youtube.com' : 'www.youtube-nocookie.com';
	} // END private function get_youtube_domain

	/**
	 * Define HTML class for video aspect ratio
	 *
	 * @param array $instance Settings from widget or shortcode
	 *
	 * @return string         HTML class for aspect ratio ar4_3 | ar16_9
	 */
	private function get_ar_class( $instance ) {
		return ! empty( $instance['ratio'] ) && 1 === (int) $instance['ratio'] ? 'ar4_3' : 'ar16_9';
	} // END private function arclass()

	/**
	 * Register TinyMCE button for YTC
	 *
	 * @param  array $plugins Unmodified set of plugins
	 *
	 * @return array          Set of TinyMCE plugins with YTC addition
	 */
	public function mce_external_plugins( $plugins ) {
		$plugins['youtube_channel'] = esc_url( YTC_URL . 'assets/js/tinymce/plugin.min.js' );

		return $plugins;
	} // END public function mce_external_plugins()

	/**
	 * Append TinyMCE button for YTC at the end of row 1
	 *
	 * @param  array $buttons Unmodified set of buttons
	 *
	 * @return array          Set of TinyMCE buttons with YTC addition
	 */
	public function mce_buttons( $buttons ) {
		$buttons[] = 'youtube_channel_shortcode';
		return $buttons;
	} // END public function mce_buttons()
} // End class Wpau_My_Youtube_Channel
