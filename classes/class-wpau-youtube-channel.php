<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPAU_YOUTUBE_CHANNEL' ) ) {
	class WPAU_YOUTUBE_CHANNEL {

		public $plugin_option = 'youtube_channel_defaults';
		public $ytc_html5_js  = '';
		public $defaults;

		/**
		 * Construct class
		 */
		function __construct() {

			load_plugin_textdomain( YTC_PLUGIN_SLUG, false, YTC_DIR . '/languages' );

			// Clear all YTC cache
			add_action( 'wp_ajax_ytc_clear_all_cache', array( $this, 'clear_all_cache' ) );

			// Activation hook and maybe update trigger
			register_activation_hook( __FILE__, array( $this, 'activate' ) );
			add_action( 'plugins_loaded', array( $this, 'maybe_update' ) );

			$this->defaults = self::defaults();

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
			require_once( 'class-wpau-youtube-channel-widget.php' );

			// Generate debug JSON
			add_action( 'init', array( $this, 'generate_debug_json' ), 10 );

			// Register shortcodes `youtube_channel` and `ytc`
			add_shortcode( 'youtube_channel', array( $this, 'shortcode' ) );
			add_shortcode( 'ytc', array( $this, 'shortcode' ) );

		} // END function __construct()

		/**
		 * Activate the plugin
		 * Credits: http://solislab.com/blog/plugin-activation-checklist/#update-routines
		 */
		public static function activate() {

			global $wpau_youtube_channel;
			$wpau_youtube_channel->init_options();
			$wpau_youtube_channel->maybe_update();

		} // END public static function activate()

		/**
		 * Return initial options
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
				'nolightbox'     => 0, // do not use lightbox global setting
				'timeout'        => 5, // timeout for wp_remote_get()
				'sslverify'      => true,
				'js_ev_listener' => false,
				'block_preview'  => true, // [NEW 2023] Enable YTC Widget preview in Block editor
			);

			add_option( 'youtube_channel_version', YTC_VER, '', 'no' );
			add_option( 'youtube_channel_db_ver', YTC_VER_DB, '', 'no' );
			add_option( $this->plugin_option, $init, '', 'no' );

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

			require_once( YTC_DIR . '/update.php' );
			au_youtube_channel_update();

		} // END public function maybe_update()

		/**
		 * Initialize Settings link for Plugins page and create Settings page
		 */
		function admin_init() {

			add_filter( 'plugin_action_links_' . plugin_basename( YTC_PLUGIN_FILE ), array( $this, 'add_action_links' ) );
			// Add row on Plugins page
			add_filter( 'plugin_row_meta', array( $this, 'add_plugin_meta_links' ), 10, 2 );

			require_once( 'class-wpau-youtube-channel-settings.php' );

			global $wpau_youtube_channel_settings;
			if ( empty( $wpau_youtube_channel_settings ) ) {
				$wpau_youtube_channel_settings = new WPAU_YOUTUBE_CHANNEL_SETTINGS();
			}

		} // END function admin_init_settings()

		/**
		 * Append Settings link for Plugins page
		 * @param array $links array of links on plugins page
		 */
		function add_action_links( $links ) {

			$links[] = '<a href="' . esc_url( admin_url( 'options-general.php?page=' . YTC_PLUGIN_SLUG ) ) . '">' . esc_html__( 'Settings' ) . '</a>';

			// Return updated array of links
			return $links;

		} // END function add_action_links()

		/**
		 * Add link to plugin community support
		 */
		function add_plugin_meta_links( $links, $file ) {

			if ( plugin_basename( YTC_PLUGIN_FILE ) === $file ) {
				$links[] = '<a href="' . esc_url( YTC_PLUGIN_URI ) . '" target="_blank">' . esc_html__( 'Support' ) . '</a>';
			}

			// Return updated array of links
			return $links;

		} // END function add_plugin_meta_links()

		/**
		 * Enqueue admin scripts and styles for widget customization
		 */
		function admin_scripts() {

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
				esc_url( YTC_URL . '/assets/css/admin.min.css' ),
				array(),
				YTC_VER
			);

			// Enqueue script for widget in admin
			if ( in_array( $pagenow, array( 'widgets.php', 'customize.php' ), true ) ) {
				wp_enqueue_script(
					esc_attr( YTC_PLUGIN_SLUG . '-admin' ),
					esc_url( YTC_URL . '/assets/js/admin.min.js' ),
					array( 'jquery' ),
					YTC_VER,
					true
				);
			}
		} // END function admin_scripts()

		/**
		 * Print dashboard notice
		 * @return string Formatted notice with usefull explanation
		 */
		function admin_notices() {

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

		} // END function admin_notices()

		/**
		 * Get default options from DB
		 * @return array Latest global defaults
		 */
		public function defaults() {

			$defaults = get_option( $this->plugin_option );
			if ( empty( $defaults ) ) {
				$defaults = $this->init_options();
			}

			return $defaults;

		} // END public function defaults()

		/**
		 * Enqueue frontend scripts and styles
		 */
		function enqueue_scripts() {

			// Check do we need our own lightbox?
			if ( empty( $this->defaults['nolightbox'] ) ) {
				wp_enqueue_style(
					'magnific-popup-au',
					esc_url( YTC_URL . '/assets/lib/magnific-popup/css/magnific-popup.min.css' ),
					array(),
					YTC_VER
				);
				wp_enqueue_script(
					'magnific-popup-au',
					esc_url( YTC_URL . '/assets/lib/magnific-popup/jquery.magnific-popup.min.js' ),
					array( 'jquery' ),
					YTC_VER,
					true
				);
			}

			wp_enqueue_style(
				'youtube-channel',
				esc_url( YTC_URL . '/assets/css/youtube-channel.min.css' ),
				array(),
				YTC_VER
			);

		} // END function enqueue_scripts()

		/**
		 * Generate comlete inline JavaScript code that conains
		 * Async video load and lightbox init for thumbnails
		 * @return string Compressed JavaScript code
		 */
		function footer_scripts() {

			$js = '';

			// Print YT API only if we have set ytc_html5_js in $_SESSION
			// if ( ! empty( $_SESSION['ytc_html5_js'] ) ) {
			if ( ! empty( $this->ytc_html5_js ) ) {
				$js .= "
					if (!window['YT']) {
						var tag=document.createElement('script');
						tag.src=\"//www.youtube.com/iframe_api\";
						var firstScriptTag=document.getElementsByTagName('script')[0];
						firstScriptTag.parentNode.insertBefore(tag,firstScriptTag);
					}
					function ytc_create_ytplayers(){
						{$this->ytc_html5_js}
					}
					try {
						ytc_create_ytplayers();
					} catch (e) {}
					function onYouTubeIframeAPIReady(){
						ytc_create_ytplayers();
					}
					function ytc_mute(event){event.target.mute();}
				";
			} // END if ( ! empty($_SESSION['ytc_html5_js']) )

			// Print Magnific Popup if not disabled
			if ( empty( $this->defaults['nolightbox'] ) ) {
				$js .= "
					function ytc_init_MPAU() {
						jQuery('.ytc-lightbox').magnificPopupAU({
							disableOn:320,
							type:'iframe',
							mainClass:'ytc-mfp-lightbox',
							removalDelay:160,
							preloader:false,
							fixedContentPos:false
						});
					}
					jQuery(window).on('load',function(){
						ytc_init_MPAU();
					});
					jQuery(document).ajaxComplete(function(){
						ytc_init_MPAU();
					});
				";
			} // END if ( empty($this->defaults['nolightbox']) )

			if ( ! empty( $js ) ) {
				$js = sprintf(
					'
					<!-- My YouTube Channel -->
					<script type="text/javascript">%2$s%1$s%3$s</script>
					',
					$js,
					$this->defaults['js_ev_listener'] ? "window.addEventListener('DOMContentLoaded', function() {console.log('test');" : '',
					$this->defaults['js_ev_listener'] ? '});' : ''
				);
				echo $js;
				/*
				if ( WP_DEBUG ) {
					// Uncompressed code if WP debug is enabled
					$js = str_replace( ';var', ";\nvar", $js );
					$js = str_replace( "\t", '', $js );
					// $js = str_replace(',', ",\n\t", $js);
					echo $js;
				} else {
					echo trim( preg_replace( '/[\t\r\n]+/', '', $js ) );
				}
				*/
			}

		} // END function footer_scripts()

		public function shortcode( $atts ) {

			// Get general default settings
			$instance = $this->defaults();

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

					'nolightbox'     => ! empty( $instance['nolightbox'] ) ? $instance['nolightbox'] : '0',
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
			$instance['class']      = sanitize_html_class( $atts['class'] ); // custom additional class for container
			$instance['nolightbox'] = $atts['nolightbox']; // custom usage of lightbox
			$instance['target']     = $atts['target'];     // custom target for thumbnails w/o lightbox (empty, _blank or custom)
			$instance['skip']       = (int) $atts['skip'];

			// return implode( array_values( $this->output( $instance ) ) );
			return $this->output( $instance );
		} // END public function shortcode()

		// Print out YTC block
		public function output( $instance ) {

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
			$class = ( ! empty( $instance['class'] ) ) ? $instance['class'] : 'default';
			if ( ! empty( $instance['responsive'] ) ) {
				$class .= ' responsive';
			}
			if ( ! empty( $instance['display'] ) ) {
				$class .= " ytc_display_{$instance['display']}";
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

			$output .= '<div class="youtube_channel ' . esc_attr( $class ) . '">';

			if ( empty( $instance['display'] ) ) {
				$instance['display'] = $this->defaults['display'];
			}
			if ( 'playlist' === $instance['display'] ) { // Insert as Embedded playlist

				$output .= self::embed_playlist( $resource_id, $instance );

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
				$resource_nice_name = $this->resource_nice_name( $resource );

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
						$video_block = $this->ytc_print_video( $item, $instance, $y );
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
			$output .= $this->ytc_footer( $instance );

			$output .= '</div><!-- .youtube_channel -->';

			// fix overflow on crappy themes
			$output .= '<div class="clearfix"></div>';
			return $output; // AUDBG

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
						'href'   => array(),
						'target' => array(),
						'title'  => array(),
						'class'  => array(),
					),
					'span'   => array(
						'id'    => array(),
						'title' => array(),
						'style' => array(),
					),
					'iframe' => array(
						'id'     => array(),
						'src'    => array(),
						'title'  => array(),
						'width'  => array(),
						'height' => array(),
						'style'  => array(),
					),
				),
			);

		} // END public function output($instance)

		// --- HELPER FUNCTIONS ---

		/**
		 * Download YouTube video feed through API 3.0
		 * @param  string $id       ID of resource
		 * @param  integer $items   Number of items to fetch (min 2, max 50)
		 * @return string           JSON with videos
		 */
		function fetch_youtube_feed( $resource_id, $items ) {

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

		} // END function fetch_youtube_feed($resource_id, $items)

		/**
		 * Print explanation of error for administrators (users with capability manage_options)
		 * and hidden message for lower users and visitors
		 * @param  string $message Error message
		 * @return string          Formatted message for error
		 */
		function front_debug( $message ) {

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

		} // END function debug( $message )

		/**
		 * Calculate height by provided width and aspect ratio
		 * @param  integer $width Width in pixels
		 * @param  integer $ratio Selected aspect ratio (1 for 4:3, other for 16:9)
		 * @return integer        Calculated height in pixels
		 */
		function height_ratio( $width = 306, $ratio = 2 ) {

			switch ( $ratio ) {
				case 1:
					$height = round( ( $width / 4 ) * 3 );
					break;
				default:
					$height = round( ( $width / 16 ) * 9 );
			}
			return intval( $height );
		} // END function height_ratio($width=306, $ratio)

		/**
		 * Generate link to YouTube channel/user
		 * @param  array $instance widget or shortcode settings
		 * @return array           components prepared for output
		 */
		function ytc_footer( $instance ) {

			// Initialize output string
			$output = '';

			// Get link to channel part
			$output .= $this->ytc_channel_link( $instance );

			// Wrap content, if we have it
			if ( ! empty( $output ) ) {
				$output = $this->clearfix() . '<div class="ytc_link"><p>' . $output . '</p></div>';
			}

			return $output;
		} // end function ytc_footer

		function clearfix() {
			return '<div class="clearfix"></div>';
		}

		/**
		 * Generate link to YouTube channel/user
		 *
		 * @param  array $instance Widget or shortcode settings
		 *
		 * @return string          Prepared link to channel HTML code
		 */
		function ytc_channel_link( $instance ) {

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
					$output .= '<a href="' . esc_url( $goto_url ) . '" target="_blank" title="' . esc_html( "$goto_txt $newtab" ) . '">' . esc_html( $goto_txt ) . '</a>';
					break;
				default:
					$output .= '<a href="' . esc_url( $goto_url ) . '" title="' . esc_html( $goto_txt ) . '">' . esc_html( $goto_txt ) . '</a>';
			} // switch popup_goto

			return $output;
		} // END function ytc_channel_link

		/**
		 * Generate output for single video block
		 *
		 * @param  object $item     Video object from JSON
		 * @param  array  $instance Settings from widget or shortcode
		 * @param  int    $y        Order number of video
		 *
		 * @return string           Prepared single video block as HTML
		 */
		function ytc_print_video( $item, $instance, $y ) {

			// Start output string
			$output = '';

			// Calculate width and height
			if ( empty( $instance['ratio'] ) ) {
				$instance['ratio'] = $this->defaults['ratio'];
			}
			$width  = ! empty( $instance['width'] ) ? intval( $instance['width'] ) : intval( $this->defaults['width'] );
			$height = $this->height_ratio( $width, $instance['ratio'] );

			// How to display videos?
			if ( empty( $instance['display'] ) ) {
				$instance['display'] = 'thumbnail';
			}

			// Extract details about video from Resource
			$yt_id    = ytc_sanitize_api_key( $item->snippet->resourceId->videoId );
			$yt_title = $item->snippet->title;
			// $yt_date  = $item->snippet->publishedAt;

			// Enhanced privacy?
			$youtube_domain = $this->youtube_domain( $instance );

			switch ( $y ) {
				case 1:
					$vnumclass = 'first';
					break;
				case $instance['num']:
					$autoplay  = false;
					$vnumclass = 'last';
					break;
				default:
					$vnumclass = 'mid';
					$autoplay  = false;
					break;
			}

			// Set proper class for responsive thumbs per selected aspect ratio
			$arclass = $this->arclass( $instance );

			// Prepare title_tag (H3, etc)
			$title_tag = isset( $instance['titletag'] ) ? sanitize_key( $instance['titletag'] ) : sanitize_key( $this->defaults['titletag'] );

			$output .= '<div class="ytc_video_container ytc_video_' . intval( $y ) . ' ytc_video_' . $vnumclass . ' ' . $arclass . '" style="width:' . intval( $instance['width'] ) . 'px">';

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
							'<%1$s class="ytc_title ytc_title_above"><a href="https://%3$s/watch/?v=%4$s" target="youtube">%2$s</a></%1$s>',
							$title_tag,
							esc_html( $yt_title ),
							$youtube_domain,
							$yt_id
						);
					} else {
						$output .= sprintf(
							'<%1$s class="ytc_title ytc_title_above">%2$s</%1$s>',
							$title_tag,
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

				// youtube API async - prefix
				$js_vars = array();
				if ( ! empty( $instance['norel'] ) ) {
					$js_vars[] = 'rel:0';
				}
				if ( ! empty( $instance['autoplay'] ) && 1 === $y ) {
					$js_vars[] = 'autoplay:1';
				}
				if ( ! empty( $instance['controls'] ) ) {
					$js_vars[] = 'controls:0';
				}
				if ( ! empty( $instance['modestbranding'] ) ) {
					$js_vars[] = 'modestbranding:1';
				}
				if ( ! empty( $instance['playsinline'] ) ) {
					$js_vars[] = 'playsinline:1';
				}
				$js_vars[] = "wmmode:'opaque'";
				$js_vars   = implode( ',', $js_vars );

				// youtube API async - sufix
				$js_end = array();
				if ( ! empty( $instance['hideanno'] ) ) {
					$js_end[] = 'iv_load_policy:3';
				}
				if ( ! empty( $instance['autoplay'] ) && ( 1 === $y ) && ! empty( $instance['autoplay_mute'] ) ) {
					$js_end[] = "events:{'onReady':ytc_mute}";
				}
				$js_end = implode( ',', $js_end );

				$js_player_id = str_replace( '-', '_', $yt_id );

				// Start wrapper for responsive item
				if ( $instance['responsive'] ) {
					$output .= '<div class="fluid-width-video-wrapper">';
				}

				$output .= '<div id="ytc_player_' . $js_player_id . '"></div>';

				// Close wrapper for responsive item
				if ( $instance['responsive'] ) {
					$output .= '</div>';
				}

				$site_domain = sanitize_url( $_SERVER['HTTP_HOST'] );

				$ytc_html5_js = sprintf(
					'var ytc_player_%1$s;ytc_player_%2$s=new YT.Player(\'ytc_player_%1$s\',{height:\'%2$s\',width:\'%3$s\',videoId:\'%4$s\',enablejsapi:1,playerVars:{%5$s},origin:\'%6$s\',%7$s});',
					$js_player_id, // 1
					$height, // 2
					$width, // 3
					$yt_id, // 4
					$js_vars, // 5
					esc_url( $site_domain ), // 6
					$js_end // 7
				);
				// prepare JS for footer
				if ( empty( $this->ytc_html5_js ) ) {
					$this->ytc_html5_js = $ytc_html5_js;
				} else {
					$this->ytc_html5_js .= $ytc_html5_js;
				}
			} else { // default is thumbnail

				$params = '';
				$target = '';
				if ( empty( $instance['nolightbox'] ) ) {
					if ( ! empty( $instance['norel'] ) ) {
						$params .= '&amp;rel=0';
					}
					if ( ! empty( $instance['modestbranding'] ) ) {
						$params .= '&amp;modestbranding=1';
					}
					if ( ! empty( $instance['controls'] ) ) {
						$params .= '&amp;controls=0';
					}
					if ( ! empty( $instance['playsinline'] ) ) {
						$params .= '&amp;playsinline=1';
					}
					if ( ! empty( $instance['privacy'] ) ) {
						$params .= '&amp;enhanceprivacy=1';
					}
					$lightbox_class = 'ytc-lightbox';
				} else {
					$lightbox_class = 'ytc-nolightbox';
					if ( ! empty( $instance['target'] ) ) {
						$target = 'target="' . sanitize_key( $instance['target'] ) . '"';
					}
				}

				// Do we need thumbnail w/ or w/o tooltip
				$tag_title = empty( $instance['no_thumb_title'] ) ? $tag_title = 'title="' . esc_html( $yt_title ) . '"' : '';

				// Define video thumbnail
				if ( empty( $instance['thumb_quality'] ) || ! in_array( $instance['thumb_quality'], array( 'default', 'mqdefault', 'hqdefault', 'sddefault', 'maxresdefault' ), true ) ) {
					$instance['thumb_quality'] = 'hqdefault';
				}
				$yt_thumb = 'https://img.youtube.com/vi/' . $yt_id . '/' . esc_attr( $instance['thumb_quality'] ) . '.jpg';

				// Show video title inside video?
				$title_inside = '';
				if ( ! empty( $instance['showtitle'] ) && in_array( $instance['showtitle'], array( 'inside', 'inside_b' ), true ) ) {
					$title_inside = sprintf(
						'<%1$s class="ytc_title ytc_title_inside %3$s">%2$s</%1$s>',
						$title_tag,
						esc_html( $yt_title ),
						'inside_b' === $instance['showtitle'] ? 'ytc_title_inside_bottom' : ''
					);
				}
				$output .= sprintf(
					'<a href="https://www.youtube.com/watch?v=%1$s%2$s" %3$s class="ytc_thumb %4$s" %5$s><span style="background-image: url(%6$s);" %3$s id="ytc_%1$s">%7$s</span></a>',
					$yt_id, // 1
					$params, //2
					$tag_title, // 3
					"$lightbox_class $arclass", // 4
					$target, // 5
					$yt_thumb, // 6
					esc_html( $title_inside ) // 7
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
							$title_tag,
							esc_html( $yt_title ),
							$yt_id
						);
					} else {
						$output .= sprintf(
							'<%1$s class="ytc_title ytc_title_below">%2$s</%1$s>',
							$title_tag,
							esc_html( $yt_title )
						);
					}
				}
			}

			// do we need to show video description?
			if ( ! empty( $instance['showdesc'] ) ) {

				$video_description = $item->snippet->description;
				$etcetera          = '';
				##TODO: If description should not be shortened, print HTML formatted desc
				if ( $instance['desclen'] > 0 ) {
					if ( function_exists( 'mb_strlen' ) && function_exists( 'mb_substr' ) ) {
						if ( mb_strlen( $video_description ) > $instance['desclen'] ) {
							$video_description = mb_substr( $video_description, 0, $instance['desclen'] );
							$etcetera          = '&hellip;';
						}
					} else {
						if ( strlen( $video_description ) > $instance['desclen'] ) {
							$video_description = substr( $video_description, 0, $instance['desclen'] );
							$etcetera          = '&hellip;';
						}
					}
				}

				if ( ! empty( $video_description ) ) {
					$output .= '<p class="ytc_description">' . esc_html( $video_description . $etcetera ) . '</p>';
				}
			}

			$output .= '</div><!-- .ytc_video_container -->';

			return $output;
		} // end function ytc_print_video

		/**
		 * Function to print standard playlist embed code
		 *
		 * @param string $resource_id YouTube Channel or PLaylist ID
		 * @param object $instance    Settings from widget or shortcode
		 *
		 * @return string             Prepared HTML embed code
		 */
		function embed_playlist( $resource_id, $instance ) {

			$width          = empty( $instance['width'] ) ? 306 : $instance['width'];
			$height         = self::height_ratio( $width, $instance['ratio'] );
			$autoplay       = empty( $instance['autoplay'] ) ? '' : '&autoplay=1';
			$modestbranding = empty( $instance['modestbranding'] ) ? '' : '&modestbranding=1';
			$rel            = empty( $instance['norel'] ) ? '' : '&rel=0';
			$playsinline    = empty( $instance['playsinline'] ) ? '' : '&playsinline=1';

			// enhanced privacy
			$youtube_domain = $this->youtube_domain( $instance );
			$arclass        = $this->arclass( $instance );

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

		} // END function embed_playlist($resource_id, $instance)

		// Helper function cache_time()
		function cache_time( $cache_time ) {

			$out   = '';
			$times = self::cache_times_arr();
			foreach ( $times as $sec => $title ) {
				$out .= '<option value="' . intval( $sec ) . '" ' . selected( $cache_time, $sec, 0 ) . '>' . esc_html( $title ) . '</option>';
			}

			return $out;

		} // END function cache_time()

		/**
		 * Define cache times array
		 */
		public static function cache_times_arr() {

			return array(
				'0'       => esc_html__( 'Do not cache', 'wpau-yt-channel' ),
				'60'      => esc_html__( '1 minute', 'wpau-yt-channel' ),
				'300'     => esc_html__( '5 minutes', 'wpau-yt-channel' ),
				'900'     => esc_html__( '15 minutes', 'wpau-yt-channel' ),
				'1800'    => esc_html__( '30 minutes', 'wpau-yt-channel' ),
				'3600'    => esc_html__( '1 hour', 'wpau-yt-channel' ),
				'7200'    => esc_html__( '2 hours', 'wpau-yt-channel' ),
				'18000'   => esc_html__( '5 hours', 'wpau-yt-channel' ),
				'36000'   => esc_html__( '10 hours', 'wpau-yt-channel' ),
				'43200'   => esc_html__( '12 hours', 'wpau-yt-channel' ),
				'64800'   => esc_html__( '18 hours', 'wpau-yt-channel' ),
				'86400'   => esc_html__( '1 day', 'wpau-yt-channel' ),
				'172800'  => esc_html__( '2 days', 'wpau-yt-channel' ),
				'259200'  => esc_html__( '3 days', 'wpau-yt-channel' ),
				'345600'  => esc_html__( '4 days', 'wpau-yt-channel' ),
				'432000'  => esc_html__( '5 days', 'wpau-yt-channel' ),
				'518400'  => esc_html__( '6 days', 'wpau-yt-channel' ),
				'604800'  => esc_html__( '1 week', 'wpau-yt-channel' ),
				'1209600' => esc_html__( '2 weeks', 'wpau-yt-channel' ),
				'1814400' => esc_html__( '3 weeks', 'wpau-yt-channel' ),
				'2419200' => esc_html__( '1 month', 'wpau-yt-channel' ),
			);

		} // END public static function cache_times_arr()

		/**
		 * Method to delete all YTC transient caches
		 * @return string Report message about success or failed purge cache
		 */
		function clear_all_cache() {

			if ( ! current_user_can( 'activate_plugins' ) ) {
				echo 'Oops, insufficient permissions to clear My YouTube Channel cache.';
				exit();
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
			exit();

		} // END function clear_all_cache()

		/**
		 * Return nice name for resource by provided resource ID
		 *
		 * @param  integer $resource_id Resource ID
		 *
		 * @return string               Resource nice name
		 */
		function resource_nice_name( $resource_id ) {
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
		} // END function resource_nice_name( $resource_id )

		function youtube_domain( $instance ) {
			return empty( $instance['privacy'] ) ? 'www.youtube.com' : 'www.youtube-nocookie.com';
		} // END function youtube_domain

		function arclass( $instance ) {
			return ! empty( $instance['ratio'] ) && 1 === (int) $instance['ratio'] ? 'ar4_3' : 'ar16_9';
		} // END function arclass()

		/**
		 * Register TinyMCE button for YTC
		 *
		 * @param  array $plugins Unmodified set of plugins
		 *
		 * @return array          Set of TinyMCE plugins with YTC addition
		 */
		function mce_external_plugins( $plugins ) {

			$plugins['youtube_channel'] = esc_url( YTC_URL . '/assets/js/tinymce/plugin.min.js' );

			return $plugins;

		} // END function mce_external_plugins()

		/**
		 * Append TinyMCE button for YTC at the end of row 1
		 *
		 * @param  array $buttons Unmodified set of buttons
		 *
		 * @return array          Set of TinyMCE buttons with YTC addition
		 */
		function mce_buttons( $buttons ) {

			$buttons[] = 'youtube_channel_shortcode';
			return $buttons;

		} // END function mce_buttons()

		public function generate_debug_json() {

			// Proceed only if global is requested (widget is abandoned)
			if ( empty( $_GET['ytc_debug_json_for'] ) || 'global' !== $_GET['ytc_debug_json_for'] ) {
				return;
			}

			if ( empty( $_REQUEST['_ytc_dbg_nonce'] ) || ! wp_verify_nonce( $_REQUEST['_ytc_dbg_nonce'], 'ytc_debug_json_for' ) ) {
				wp_die( __( 'Oops, insufficient permissions to access My YouTube Channel debug info.' ), __( 'My YouTube Channel' ) );
			}

			// global settings
			$options = get_option( 'youtube_channel_defaults' );

			if ( ! is_array( $options ) ) {
				return;
			}

			// Remove YouTube Data API Key from config JSON
			$options['apikey'] = '*** REDACTED ***';

			// Prepare data with current plugin settings
			$data = array_merge(
				array(
					'date'   => gmdate( 'r' ),
					'server' => sanitize_text_field( $_SERVER['SERVER_SOFTWARE'] ),
					'php'    => PHP_VERSION,
					'wp'     => get_bloginfo( 'version', 'display' ),
					'ytc'    => YTC_VER,
					'url'    => get_site_url(),
				),
				$options
			);

			// Return JSON file
			header( 'Content-disposition: attachment; filename=' . sanitize_file_name( 'ytc3_' . $_SERVER['HTTP_HOST'] . '_' . gmdate( 'ymdHis' ) . '.json' ) );
			header( 'Content-Type: application/json' );
			echo json_encode( $data );

			// Exit now, because we need only debug data in JSON file, not settings or any other page
			exit;
		} // End function generate_debug_json()

	} // End class
} // End class check
