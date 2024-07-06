<?php

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'Wpau_My_Youtube_Channel_Settings' ) ) {
	return;
}

class Wpau_My_Youtube_Channel_Settings {

	private $option_name;
	private $defaults;
	private $slug;

	/**
	 * Construct the plugin object
	 */
	public function __construct() {
		// get default values
		$this->slug        = YTC_PLUGIN_SLUG;
		$this->option_name = YTC_PLUGIN_OPTION_KEY;
		$this->defaults    = get_option( $this->option_name );

		// register actions
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
	} // END public function __construct

	/**
	 * hook into WP's register_settings action hook
	 */
	public function register_settings() {
		global $wpau_my_youtube_channel;

		// =========================== General ===========================
		// --- Add settings section General so we can add fields to it ---
		add_settings_section(
			'ytc_general', // Section Name
			esc_html__( 'General', 'wpau-yt-channel' ), // Section Title
			array( &$this, 'settings_general_section_description' ), // Section Callback Function
			esc_attr( $this->slug . '_general' ) // Page Name
		);
		// --- Add Fields to General section ---
		// YouTube Data API Key
		add_settings_field(
			$this->option_name . 'apikey', // Setting Slug
			esc_html__( 'YouTube Data API Key', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_input_text' ), // Callback
			esc_attr( $this->slug . '_general' ), // Page Name
			'ytc_general', // Section Name
			array(
				'field'         => $this->option_name . '[apikey]',
				'desc_required' => true,
				'desc_global'   => true,
				'description'   => __( 'Your YouTube Data API Key', 'wpau-yt-channel' ),
				'desc_link_url' => 'https://console.developers.google.com/project',
				'desc_link_txt' => __( 'Google Developers Console', 'wpau-yt-channel' ),
				'class'         => 'regular-text password blur-on-lose-focus',
				'value'         => isset( $this->defaults['apikey'] ) ? esc_html( $this->defaults['apikey'] ) : '',
			) // args
		);
		// Channel ID
		add_settings_field(
			$this->option_name . 'channel', // Setting Slug
			esc_html__( 'YouTube Channel ID', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_input_text' ), // Callback
			esc_attr( $this->slug . '_general' ), // Page Name
			'ytc_general', // Section Name
			array(
				'field'         => $this->option_name . '[channel]',
				'label_for'     => $this->option_name . '[channel]',
				'desc_required' => true,
				'desc_link_url' => 'https://www.youtube.com/account_advanced',
				'desc_link_txt' => __( 'YouTube Advanced Settings', 'wpau-yt-channel' ),
				'description'   => __(
					'Your YouTube Channel ID you can get from',
					'wpau-yt-channel'
				),
				'class'         => 'regular-text',
				'value'         => isset( $this->defaults['channel'] ) ? $this->defaults['channel'] : '',
			) // args
		);

		// Handle (new in 2022)
		add_settings_field(
			$this->option_name . 'handle', // id
			esc_html__( 'YouTube Handle', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_input_text' ), // Callback
			esc_attr( $this->slug . '_general' ), // Page Name
			'ytc_general', // section
			array(
				'field'         => $this->option_name . '[handle]',
				'label_for'     => $this->option_name . '[handle]',
				'desc_link_url' => 'https://www.youtube.com/handle',
				'desc_link_txt' => __( 'Your handle', 'wpau-yt-channel' ),
				'description'   => __(
					'Your YouTube Handle handle including @ from',
					'wpau-yt-channel'
				),
				'class'         => 'regular-text',
				'value'         => isset( $this->defaults['handle'] ) ? $this->defaults['handle'] : '',
			) // args
		);
		// Vanity (deprecated in 2022)
		add_settings_field(
			$this->option_name . 'vanity', // id
			esc_html__( 'YouTube Vanity Name', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_input_text' ), // Callback
			esc_attr( $this->slug . '_general' ), // Page Name
			'ytc_general', // section
			array(
				'field'           => $this->option_name . '[vanity]',
				'label_for'       => $this->option_name . '[vanity]',
				'desc_deprecated' => true,
				'description'     => sprintf(
					// translators: %s is replaced with www.youtube.com/c/
					__( 'Your YouTube Custom Name used to be part of %s', 'wpau-yt-channel' ),
					'www.youtube.com/c/'
				),
				'class'           => 'regular-text deprecated',
				'value'           => isset( $this->defaults['vanity'] ) ? $this->defaults['vanity'] : '',
			) // args
		);
		// Username
		add_settings_field(
			$this->option_name . 'username', // id
			esc_html__( 'YouTube Username', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_input_text' ), // Callback
			esc_attr( $this->slug . '_general' ), // Page Name
			'ytc_general', // section
			array(
				'field'           => $this->option_name . '[username]',
				'label_for'       => $this->option_name . '[username]',
				'desc_deprecated' => true,
				'description'     => __( 'Your YouTube legacy username', 'wpau-yt-channel' ),
				'class'           => 'regular-text deprecated',
				'value'           => isset( $this->defaults['username'] ) ? $this->defaults['username'] : '',
			) // args
		);
		// Default Playlist
		add_settings_field(
			$this->option_name . 'playlist', // id
			esc_html__( 'Default Playlist ID', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_input_text' ), // Callback
			esc_attr( $this->slug . '_general' ), // Page Name
			'ytc_general', // section
			array(
				'field'         => $this->option_name . '[playlist]',
				'label_for'     => $this->option_name . '[playlist]',
				'desc_optional' => true,
				'description'   => __( 'Enter default playlist ID (not playlist name)', 'wpau-yt-channel' ),
				'class'         => 'regular-text',
				'value'         => isset( $this->defaults['playlist'] ) ? $this->defaults['playlist'] : '',
			) // args
		);
		// Resource
		add_settings_field(
			$this->option_name . 'resource', // id
			esc_html__( 'Resource to use', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_select' ), // Callback
			esc_attr( $this->slug . '_general' ), // Page Name
			'ytc_general', // section
			array(
				'field'       => $this->option_name . '[resource]',
				'label_for'   => $this->option_name . '[resource]',
				'description' => __( 'What to use as resource for feeds', 'wpau-yt-channel' ),
				'class'       => 'regular-text',
				'value'       => isset( $this->defaults['resource'] ) ? $this->defaults['resource'] : '0',
				'items'       => array(
					'0' => __( 'Channel', 'wpau-yt-channel' ),
					// '1' => __( 'Favourites', 'wpau-yt-channel' ), // deprecated since 3.23.0
					// '3' => __( 'Liked Video', 'wpau-yt-channel' ), // deprecated since 3.23.0
					'2' => __( 'Playlist', 'wpau-yt-channel' ),
				),
			) // args
		);
		// Cache
		add_settings_field(
			$this->option_name . 'cache', // id
			esc_html__( 'Cache Timeout', 'wpau-yt-channel' ),
			array( &$this, 'settings_field_select' ),
			esc_attr( $this->slug . '_general' ), // Page Name
			'ytc_general',
			array(
				'field'       => $this->option_name . '[cache]',
				'label_for'   => $this->option_name . '[cache]',
				'description' => __( 'Define caching timeout for YouTube feeds, in seconds', 'wpau-yt-channel' ),
				'class'       => 'wide-text',
				'value'       => isset( $this->defaults['cache'] ) ? $this->defaults['cache'] : '300',
				'items'       => $wpau_my_youtube_channel->cache_timeouts,
			)
		);
		// Fetch
		add_settings_field(
			$this->option_name . 'fetch', // id
			esc_html__( 'Fetch', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_input_number' ), // Callback
			esc_attr( $this->slug . '_general' ), // Page Name
			'ytc_general', // section
			array(
				'field'       => $this->option_name . '[fetch]',
				'label_for'   => $this->option_name . '[fetch]',
				'description' => __( 'Number of videos that will be fetched from YouTube and used for random pick (min 2, max 50, default 25)', 'wpau-yt-channel' ),
				'class'       => 'num',
				'value'       => isset( $this->defaults['fetch'] ) ? $this->defaults['fetch'] : 25,
				'min'         => 1,
				'max'         => 50,
				'std'         => 25,
			) // args
		);
		// Show
		add_settings_field(
			$this->option_name . 'num', // id
			esc_html__( 'Show', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_input_number' ), // Callback
			esc_attr( $this->slug . '_general' ), // Page Name
			'ytc_general', // section
			array(
				'field'       => $this->option_name . '[num]',
				'label_for'   => $this->option_name . '[num]',
				'description' => __( 'Number of videos to display', 'wpau-yt-channel' ),
				'class'       => 'num',
				'value'       => isset( $this->defaults['num'] ) ? $this->defaults['num'] : 1,
				'min'         => 1,
				'max'         => 50,
				'std'         => 1,
			) // args
		);
		// Timeout
		add_settings_field(
			$this->option_name . 'timeout', // id
			esc_html__( 'Timeout', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_input_number' ), // Callback
			esc_attr( $this->slug . '_general' ), // Page Name
			'ytc_general', // section
			array(
				'field'       => $this->option_name . '[timeout]',
				'label_for'   => $this->option_name . '[timeout]',
				'description' => __( 'Time in seconds, before the connection with YouTube DATA Api is dropped and an error is returned', 'wpau-yt-channel' ),
				'class'       => 'timeout',
				'value'       => isset( $this->defaults['timeout'] ) ? $this->defaults['timeout'] : 5,
				'min'         => 5,
				'max'         => 360,
				'std'         => 5,
			) // args
		);

		// SSL Verify
		add_settings_field(
			$this->option_name . 'sslverify', // id
			esc_html__( 'Verify SSL', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_checkbox' ), // Callback
			esc_attr( $this->slug . '_general' ), // Page Name
			'ytc_general', // section
			array(
				'field'       => $this->option_name . '[sslverify]',
				'label_for'   => $this->option_name . '[sslverify]',
				'label'       => __( 'Yes', 'wpau-yt-channel' ),
				'description' => __( 'If your website host fail to verify SSL Certificate for GoogleApis.com server (if you see in YTC Error keywords cURL and SSL), disable this option.', 'wpau-yt-channel' ),
				'class'       => 'checkbox',
				'value'       => isset( $this->defaults['sslverify'] ) ? $this->defaults['sslverify'] : false,
			) // args
		);

		// Enhanced privacy
		add_settings_field(
			$this->option_name . 'privacy', // id
			esc_html__( 'Enhanced Privacy', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_checkbox' ), // Callback
			esc_attr( $this->slug . '_general' ), // Page Name
			'ytc_general', // section
			array(
				'field'         => $this->option_name . '[privacy]',
				'label_for'     => $this->option_name . '[privacy]',
				'label'         => __( 'Yes', 'wpau-yt-channel' ),
				'desc_link_url' => 'https://support.google.com/youtube/answer/171780',
				'desc_link_txt' => __( 'Learn more here', 'wpau-yt-channel' ),
				'description'   => __( 'Enable this option to protect your visitors privacy.', 'wpau-yt-channel' ),
				'class'         => 'checkbox',
				'value'         => isset( $this->defaults['privacy'] ) ? $this->defaults['privacy'] : false,
			) // args
		);

		// Local Images
		add_settings_field(
			$this->option_name . 'local_img', // id
			esc_html__( 'Local Images', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_checkbox' ), // Callback
			esc_attr( $this->slug . '_general' ), // Page Name
			'ytc_general', // section
			array(
				'field'         => $this->option_name . '[local_img]',
				'label_for'     => $this->option_name . '[local_img]',
				'label'         => __( 'Yes', 'wpau-yt-channel' ),
				'desc_link_url' => 'https://developer.chrome.com/docs/lighthouse/performance/uses-long-cache-ttl/',
				'desc_link_txt' => __( 'Learn more here', 'wpau-yt-channel' ),
				'description'   => __( 'Store video thumbnails locally. Can help with Speed Performance (efficient cache policy).', 'wpau-yt-channel' ),
				'class'         => 'checkbox',
				'value'         => isset( $this->defaults['local_img'] ) ? $this->defaults['local_img'] : false,
			) // args
		);

		// Event Listener
		add_settings_field(
			$this->option_name . 'js_ev_listener', // id
			esc_html__( 'Event Listener', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_checkbox' ), // Callback
			esc_attr( $this->slug . '_general' ), // Page Name
			'ytc_general', // section
			array(
				'field'       => $this->option_name . '[js_ev_listener]',
				'label_for'   => $this->option_name . '[js_ev_listener]',
				'label'       => __( 'Yes', 'wpau-yt-channel' ),
				'description' => __( 'If YTC block fail to render on your website because of async/defer loading of JavaScript, try to enable this option to wrap YTC code within DOMContentLoaded event listener', 'wpau-yt-channel' ),
				'class'       => 'checkbox',
				'value'       => isset( $this->defaults['js_ev_listener'] ) ? $this->defaults['js_ev_listener'] : false,
			) // args
		);

		// TinyMCE icon
		add_settings_field(
			$this->option_name . 'tinymce', // id
			esc_html__( 'TinyMCE button', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_checkbox' ), // Callback
			esc_attr( $this->slug . '_general' ), // Page Name
			'ytc_general', // section
			array(
				'field'       => $this->option_name . '[tinymce]',
				'label_for'   => $this->option_name . '[tinymce]',
				'label'       => __( 'Yes', 'wpau-yt-channel' ),
				'description' => sprintf(
					// translators: %s is replaced with plugin name
					__( 'Disable this option to hide %s button from TinyMCE toolbar on post and page editor.', 'wpau-yt-channel' ),
					YTC_PLUGIN_NAME
				),
				'class'       => 'checkbox',
				'value'       => isset( $this->defaults['tinymce'] ) ? $this->defaults['tinymce'] : false,
			) // args
		);
		// Widget Preview
		add_settings_field(
			$this->option_name . 'block_preview', // id
			esc_html__( 'Preview Widget in Block Editor', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_checkbox' ), // Callback
			esc_attr( $this->slug . '_general' ), // Page Name
			'ytc_general', // section
			array(
				'field'       => $this->option_name . '[block_preview]',
				'label_for'   => $this->option_name . '[block_preview]',
				'label'       => __( 'Yes', 'wpau-yt-channel' ),
				'description' => sprintf(
					// translators: %s is replaced with plugin name
					__( 'Disable this option to prevent %s Widget Preview gets rendered on Block Editor.', 'wpau-yt-channel' ),
					YTC_PLUGIN_NAME
				),
				'class'       => 'checkbox',
				'value'       => isset( $this->defaults['block_preview'] ) ? $this->defaults['block_preview'] : true,
			) // args
		);
		// --- Register setting General so $_POST handling is done ---
		register_setting(
			'ytc_general', // Setting group
			$this->option_name, // option name
			array( $this, 'sanitize_options' )
		);

		// =========================== VIDEO ===========================
		// --- Add settings section Video so we can add fields to it ---
		add_settings_section(
			'ytc_video', // Section Name
			esc_html__( 'Video Tweaks', 'wpau-yt-channel' ), // Section Title
			array( &$this, 'settings_video_section_description' ), // Section Callback Function
			esc_attr( $this->slug . '_video' ) // Page Name
		);
		// --- Add Fields to video section ---
		// Width
		add_settings_field(
			$this->option_name . 'width', // id
			esc_html__( 'Initial Width', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_input_number' ), // Callback
			esc_attr( $this->slug . '_video' ), // Page Name
			'ytc_video', // section
			array(
				'field'       => $this->option_name . '[width]',
				'label_for'   => $this->option_name . '[width]',
				'description' => __( 'Set default width for displayed video, in pixels. This value have effect only if Responsive Video option is disabled!', 'wpau-yt-channel' ),
				'class'       => 'num',
				'value'       => isset( $this->defaults['width'] ) ? $this->defaults['width'] : '306',
				'min'         => 120,
				'max'         => 1980,
				'std'         => 306,
			) // args
		);
		// Aspect Ratio
		add_settings_field(
			$this->option_name . 'ratio', // id
			esc_html__( 'Aspect ratio', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_select' ), // Callback
			esc_attr( $this->slug . '_video' ), // Page Name
			'ytc_video', // section
			array(
				'field'       => $this->option_name . '[ratio]',
				'label_for'   => $this->option_name . '[ratio]',
				'description' => __( 'Select aspect ratio for displayed video', 'wpau-yt-channel' ),
				'class'       => 'regular-text',
				'value'       => isset( $this->defaults['ratio'] ) ? $this->defaults['ratio'] : '3',
				'items'       => array(
					'3' => '16:9',
					'1' => '4:3',
				),
			) // args
		);
		// Display
		add_settings_field(
			$this->option_name . 'display', // id
			esc_html__( 'Embed as', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_select' ), // Callback
			esc_attr( $this->slug . '_video' ), // Page Name
			'ytc_video', // section
			array(
				'field'       => $this->option_name . '[display]',
				'label_for'   => $this->option_name . '[display]',
				'description' => __( 'Choose how to embed video block', 'wpau-yt-channel' ),
				'class'       => 'regular-text',
				'value'       => isset( $this->defaults['display'] ) ? $this->defaults['display'] : 'thumbnail',
				'items'       => array(
					'thumbnail' => __( 'Thumbnail', 'wpau-yt-channel' ),
					'iframe'    => __( 'HTML5 (iframe)', 'wpau-yt-channel' ),
					'iframe2'   => __( 'HTML5 (iframe) Asynchronous', 'wpau-yt-channel' ),
					'playlist'  => __( 'Embedded Playlist', 'wpau-yt-channel' ),
				),
			) // args
		);
		// Thumbnail Quality
		add_settings_field(
			$this->option_name . 'thumb_quality', // id
			esc_html__( 'Thumbnail Quality', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_select' ), // Callback
			esc_attr( $this->slug . '_video' ), // Page Name
			'ytc_video', // section
			array(
				'field'       => $this->option_name . '[thumb_quality]',
				'label_for'   => $this->option_name . '[thumb_quality]',
				'description' => __( 'Choose preferred thumbnail quality. Please be aware, if you select Maximum Resolution but video does not have that thumbnail, you will get broken thumbnail on page!', 'wpau-yt-channel' ),
				'class'       => 'regular-text',
				'value'       => isset( $this->defaults['thumb_quality'] ) ? $this->defaults['thumb_quality'] : '0',
				'items'       => array(
					'default'       => __( 'Default Quality (120x90px)', 'wpau-yt-channel' ),
					'mqdefault'     => __( 'Medium Quality (320x180px)', 'wpau-yt-channel' ),
					'hqdefault'     => __( 'High Quality (480x360px)', 'wpau-yt-channel' ),
					'sddefault'     => __( 'Standard Definition (640x480px)', 'wpau-yt-channel' ),
					'maxresdefault' => __( 'Maximum Resolution (1280x720px)', 'wpau-yt-channel' ),
				),
			) // args
		);

		// Responsive
		add_settings_field(
			$this->option_name . 'responsive', // id
			esc_html__( 'Responsive Video', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_checkbox' ), // Callback
			esc_attr( $this->slug . '_video' ), // Page Name
			'ytc_video', // section
			array(
				'field'       => $this->option_name . '[responsive]',
				'label_for'   => $this->option_name . '[responsive]',
				'label'       => __( 'Yes', '<outube-channel' ),
				'description' => __( 'Enable this option to make YTC videos and thumbnails responsive by default. Please note, this option will set videos and thumbnail to full width relative to parent container, and disable more than one video per row.', 'wpau-yt-channel' ),
				'class'       => 'checkbox',
				'value'       => isset( $this->defaults['responsive'] ) ? $this->defaults['responsive'] : false,
			) // args
		);

		// Plays Inline
		add_settings_field(
			$this->option_name . 'playsinline', // id
			esc_html__( 'Play inline on iOS', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_checkbox' ), // Callback
			esc_attr( $this->slug . '_video' ), // Page Name
			'ytc_video', // section
			array(
				'field'         => $this->option_name . '[playsinline]',
				'label_for'     => $this->option_name . '[playsinline]',
				'label'         => __( 'Yes', 'wpau-yt-channel' ),
				'desc_link_url' => 'https://developers.google.com/youtube/player_parameters#playsinline',
				'desc_link_txt' => __( 'Learn more here', 'wpau-yt-channel' ),
				'description'   => __( 'Enable this option to override fullscreen playback on iOS, and force inline playback on page and in lightbox.', 'wpau-yt-channel' ),
				'class'         => 'checkbox',
				'value'         => isset( $this->defaults['playsinline'] ) ? $this->defaults['playsinline'] : false,
			) // args
		);
		// No Lightbox
		add_settings_field(
			$this->option_name . 'nolightbox', // id
			esc_html__( 'Disable Lightbox', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_checkbox' ), // Callback
			esc_attr( $this->slug . '_video' ), // Page Name
			'ytc_video', // section
			array(
				'field'       => $this->option_name . '[nolightbox]',
				'label_for'   => $this->option_name . '[nolightbox]',
				'label'       => __( 'Yes', 'wpau-yt-channel' ),
				'description' => __( 'Enable this option to disable built-in lightbox for thumbnails (in case that you have youtube links lightbox trigger in theme or other plugin).', 'wpau-yt-channel' ),
				'class'       => 'checkbox',
				'value'       => isset( $this->defaults['nolightbox'] ) ? $this->defaults['nolightbox'] : false,
			) // args
		);
		// Full Screen
		add_settings_field(
			$this->option_name . 'fullscreen', // id
			esc_html__( 'Full Screen', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_checkbox' ), // Callback
			esc_attr( $this->slug . '_video' ), // Page Name
			'ytc_video', // section
			array(
				'field'       => $this->option_name . '[fullscreen]',
				'label_for'   => $this->option_name . '[fullscreen]',
				'label'       => __( 'Yes', 'wpau-yt-channel' ),
				'description' => __( 'Enable this option to make available Full Screen button for embedded playlists.', 'wpau-yt-channel' ),
				'class'       => 'checkbox',
				'value'       => isset( $this->defaults['fullscreen'] ) ? $this->defaults['fullscreen'] : false,
			) // args
		);
		// No Player Controls
		add_settings_field(
			$this->option_name . 'controls', // id
			esc_html__( 'Hide Player Controls', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_checkbox' ), // Callback
			esc_attr( $this->slug . '_video' ), // Page Name
			'ytc_video', // section
			array(
				'field'       => $this->option_name . '[controls]',
				'label_for'   => $this->option_name . '[controls]',
				'label'       => __( 'Yes', '<outube-channel' ),
				'description' => __( 'Enable this option to hide playback controls', 'wpau-yt-channel' ),
				'class'       => 'checkbox',
				'value'       => isset( $this->defaults['controls'] ) ? $this->defaults['controls'] : false,
			) // args
		);
		// Autoplay
		add_settings_field(
			$this->option_name . 'autoplay', // id
			esc_html__( 'Autoplay video or playlist', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_checkbox' ), // Callback
			esc_attr( $this->slug . '_video' ), // Page Name
			'ytc_video', // section
			array(
				'field'       => $this->option_name . '[autoplay]',
				'label_for'   => $this->option_name . '[autoplay]',
				'label'       => __( 'Yes', '<outube-channel' ),
				'description' => __( 'Enable this option to start video playback right after block is rendered', 'wpau-yt-channel' ),
				'class'       => 'checkbox',
				'value'       => isset( $this->defaults['autoplay'] ) ? $this->defaults['autoplay'] : false,
			) // args
		);
		// Mute on autoplay
		add_settings_field(
			$this->option_name . 'autoplay_mute', // id
			esc_html__( 'Mute video on autoplay', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_checkbox' ), // Callback
			esc_attr( $this->slug . '_video' ), // Page Name
			'ytc_video', // section
			array(
				'field'       => $this->option_name . '[autoplay_mute]',
				'label_for'   => $this->option_name . '[autoplay_mute]',
				'label'       => __( 'Yes', '<outube-channel' ),
				'description' => __( 'Enable this option to mute video when start autoplay', 'wpau-yt-channel' ),
				'class'       => 'checkbox',
				'value'       => isset( $this->defaults['autoplay_mute'] ) ? $this->defaults['autoplay_mute'] : false,
			) // args
		);
		// Only channel related videos
		add_settings_field(
			$this->option_name . 'norel', // id
			esc_html__( 'Only channel related videos', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_checkbox' ), // Callback
			esc_attr( $this->slug . '_video' ), // Page Name
			'ytc_video', // section
			array(
				'field'       => $this->option_name . '[norel]',
				'label_for'   => $this->option_name . '[norel]',
				'label'       => __( 'Yes', '<outube-channel' ),
				'description' => __( 'Enable this option to show after finished playback only related videos that come from the same channel as the video that was just played', 'wpau-yt-channel' ),
				'class'       => 'checkbox',
				'value'       => isset( $this->defaults['norel'] ) ? $this->defaults['norel'] : false,
			) // args
		);
		// Hide Annotations
		add_settings_field(
			$this->option_name . 'hideanno', // id
			esc_html__( 'Hide video annotations', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_checkbox' ), // Callback
			esc_attr( $this->slug . '_video' ), // Page Name
			'ytc_video', // section
			array(
				'field'       => $this->option_name . '[hideanno]',
				'label_for'   => $this->option_name . '[hideanno]',
				'label'       => __( 'Yes', '<outube-channel' ),
				'description' => __( 'Enable this option to hide video annotations (custom text set by uploader over video during playback)', 'wpau-yt-channel' ),
				'class'       => 'checkbox',
				'value'       => isset( $this->defaults['hideanno'] ) ? $this->defaults['hideanno'] : false,
			) // args
		);
		// Hide YT logo
		add_settings_field(
			$this->option_name . 'modestbranding', // id
			esc_html__( 'Hide YouTube logo', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_checkbox' ), // Callback
			esc_attr( $this->slug . '_video' ), // Page Name
			'ytc_video', // section
			array(
				'field'       => $this->option_name . '[modestbranding]',
				'label_for'   => $this->option_name . '[modestbranding]',
				'label'       => __( 'Yes', '<outube-channel' ),
				'description' => __( 'Enable this option to hide YouTube logo from playback control bar. Does not work for all videos.', 'wpau-yt-channel' ),
				'class'       => 'checkbox',
				'value'       => isset( $this->defaults['modestbranding'] ) ? $this->defaults['modestbranding'] : false,
			) // args
		);

		// --- Register setting Video so $_POST handling is done ---
		register_setting(
			'ytc_video', // Setting group
			$this->option_name, // option name
			array( $this, 'sanitize_options' )
		);

		// =========================== CONTENT ===========================
		// --- Add settings section Content so we can add fields to it ---
		add_settings_section(
			'ytc_content', // Section Name
			esc_html__( 'Content Tweaks', 'wpau-yt-channel' ), // Section Title
			array( &$this, 'settings_content_section_description' ), // Section Callback Function
			esc_attr( $this->slug . '_content' ) // Page Name
		);
		// --- Add Fields to video section ---
		// Video Title
		add_settings_field(
			$this->option_name . 'showtitle', // id
			esc_html__( 'Video title', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_select' ), // Callback
			esc_attr( $this->slug . '_content' ), // Page Name
			'ytc_content', // section
			array(
				'field'       => $this->option_name . '[showtitle]',
				'label_for'   => $this->option_name . '[showtitle]',
				'description' => __( 'Select should we and where to display title of video.', 'wpau-yt-channel' ),
				'class'       => 'regular-text',
				'value'       => isset( $this->defaults['showtitle'] ) ? $this->defaults['showtitle'] : 'none',
				'items'       => array(
					'none'     => __( 'Hide title', 'wpau-yt-channel' ),
					'above'    => __( 'Above video/thumbnail', 'wpau-yt-channel' ),
					'below'    => __( 'Below video/thumbnail', 'wpau-yt-channel' ),
					'inside'   => __( 'Inside thumbnail, top aligned', 'wpau-yt-channel' ),
					'inside_b' => __( 'Inside thumbnail, bottom aligned', 'wpau-yt-channel' ),
				),
			) // args
		);
		// Link Video Title
		add_settings_field(
			$this->option_name . 'linktitle', // id
			esc_html__( 'Link Title to Video', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_checkbox' ), // Callback
			esc_attr( $this->slug . '_content' ), // Page Name
			'ytc_content', // section
			array(
				'field'       => $this->option_name . '[linktitle]',
				'label_for'   => $this->option_name . '[linktitle]',
				'label'       => __( 'Yes', 'wpau-yt-channel' ),
				'description' => __( 'Enable this option to link outside title to video.', 'wpau-yt-channel' ),
				'class'       => 'checkbox',
				'value'       => isset( $this->defaults['linktitle'] ) ? $this->defaults['linktitle'] : false,
			) // args
		);

		// Video Title HTML Tag
		add_settings_field(
			$this->option_name . 'titletag', // id
			esc_html__( 'Title HTML tag', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_select' ), // Callback
			esc_attr( $this->slug . '_content' ), // Page Name
			'ytc_content', // section
			array(
				'field'       => $this->option_name . '[titletag]',
				'label_for'   => $this->option_name . '[titletag]',
				'description' => __( 'Select which HTML tag to use for title wrapper. Fallback if not set in shortcode.', 'wpau-yt-channel' ),
				'class'       => 'regular-text',
				'value'       => isset( $this->defaults['titletag'] ) ? $this->defaults['titletag'] : 'h3',
				'items'       => array(
					'h3'     => 'h3',
					'h4'     => 'h4',
					'h5'     => 'h5',
					'div'    => 'div',
					'span'   => 'span',
					'strong' => 'strong',
				),
			) // args
		);
		// Video Description
		add_settings_field(
			$this->option_name . 'showdesc', // id
			esc_html__( 'Video description', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_checkbox' ), // Callback
			esc_attr( $this->slug . '_content' ), // Page Name
			'ytc_content', // section
			array(
				'field'       => $this->option_name . '[showdesc]',
				'label_for'   => $this->option_name . '[showdesc]',
				'label'       => __( 'Show', 'wpau-yt-channel' ),
				'description' => __( 'Enable this option to show video description.', 'wpau-yt-channel' ),
				'class'       => 'checkbox',
				'value'       => isset( $this->defaults['showdesc'] ) ? $this->defaults['showdesc'] : false,
			) // args
		);
		// Description length
		add_settings_field(
			$this->option_name . 'desclen', // id
			esc_html__( 'Description length', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_input_number' ), // Callback
			esc_attr( $this->slug . '_content' ), // Page Name
			'ytc_content', // section
			array(
				'field'       => $this->option_name . '[desclen]',
				'label_for'   => $this->option_name . '[desclen]',
				'description' => __( 'Enter length for video description in characters (0 for full length).', 'wpau-yt-channel' ),
				'class'       => 'num',
				'value'       => isset( $this->defaults['desclen'] ) ? $this->defaults['desclen'] : 0,
				'min'         => 0,
				'max'         => 2500,
				'std'         => 0,
			) // args
		);

		// --- Register setting Content so $_POST handling is done ---
		register_setting(
			'ytc_content', // Setting group
			$this->option_name, // option name
			array( $this, 'sanitize_options' )
		);

		// =========================== LINK ===========================
		// --- Add settings section Link to Channel so we can add fields to it ---
		add_settings_section(
			'ytc_link', // Section Name
			esc_html__( 'Link to Channel', 'wpau-yt-channel' ), // Section Title
			array( &$this, 'settings_link_section_description' ), // Section Callback Function
			$this->slug . '_link' // Page Name
		);
		// --- Add Fields to video section ---
		// Link to...
		add_settings_field(
			$this->option_name . 'link_to', // id
			esc_html__( 'Link to...', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_select' ), // Callback
			$this->slug . '_link', // Page
			'ytc_link', // section
			array(
				'field'       => $this->option_name . '[link_to]',
				'label_for'   => $this->option_name . '[link_to]',
				'description' => __( 'Set where link will lead visitors', 'wpau-yt-channel' ),
				'class'       => 'regular-text',
				'value'       => isset( $this->defaults['link_to'] ) ? $this->defaults['link_to'] : 'none',
				'items'       => array(
					'none'    => __( 'Hide link', 'wpau-yt-channel' ),
					'handle'  => __( 'YouTube handle URL', 'wpau-yt-channel' ),
					'channel' => __( 'Channel page URL', 'wpau-yt-channel' ),
					'vanity'  => __( 'Vanity custom URL (deprecated)', 'wpau-yt-channel' ),
					'legacy'  => __( 'Legacy username page (deprecated)', 'wpau-yt-channel' ),
				),
			) // args
		);
		// Open in...
		add_settings_field(
			$this->option_name . 'popup_goto', // id
			esc_html__( 'Open link in...', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_select' ), // Callback
			$this->slug . '_link', // Page
			'ytc_link', // section
			array(
				'field'       => $this->option_name . '[popup_goto]',
				'label_for'   => $this->option_name . '[popup_goto]',
				'description' => __( 'Set how link will be opened', 'wpau-yt-channel' ),
				'class'       => 'regular-text',
				'value'       => isset( $this->defaults['popup_goto'] ) ? $this->defaults['popup_goto'] : '0',
				'items'       => array(
					'0' => __( 'same window', 'wpau-yt-channel' ),
					// option 1 - new window (JavaScript) is deprecated since 3.23.2
					'2' => __( 'new window (target="_blank")', 'wpau-yt-channel' ),
				),
			) // args
		);
		// Visit channel text
		add_settings_field(
			$this->option_name . 'goto_txt', // id
			esc_html__( 'Text for Visit channel link', 'wpau-yt-channel' ), // Title
			array( &$this, 'settings_field_input_text' ), // Callback
			$this->slug . '_link', // Page
			'ytc_link', // section
			array(
				'field'       => $this->option_name . '[goto_txt]',
				'label_for'   => $this->option_name . '[goto_txt]',
				'class'       => 'regular-text',
				'description' => __( 'Set default title for link', 'wpau-yt-channel' ),
				'value'       => isset( $this->defaults['goto_txt'] ) ? $this->defaults['goto_txt'] : '',
			) // args
		);

		// --- Register setting Content so $_POST handling is done ---
		register_setting(
			'ytc_link', // Setting group
			$this->option_name, // option name
			array( $this, 'sanitize_options' )
		);
	} // END public function register_settings()

	/**
	 * Add settings menu
	 */
	public function add_menu() {
		// Add a page to manage this plugin's settings
		add_options_page(
			YTC_PLUGIN_NAME,
			YTC_PLUGIN_NAME,
			'manage_options',
			$this->slug,
			array( &$this, 'plugin_settings_page' )
		);
	} // END public function add_menu()

	// ===================== HELPERS ==========================

	public function settings_description_required() {
		return sprintf(
			'<strong>[%s]</strong>',
			esc_html__( 'Required', 'wpau-yt-channel' )
		);
	}
	public function settings_description_global() {
		return sprintf(
			'<strong title="%1$s" style="cursor:help;">[%2$s]</strong>',
			esc_html__( 'This option is global only and can`t be changed per widget/shortcode', 'wpau-yt-channel' ),
			esc_html__( 'Global', 'wpau-yt-channel' )
		);
	}
	public function settings_description_optional() {
		return sprintf(
			'<strong title="%1$s" style="cursor:help;">[%2$s]</strong>',
			esc_html__( 'You can leave this option empty and set it per widget/shortcode.', 'wpau-yt-channel' ),
			esc_html__( 'Optional', 'wpau-yt-channel' )
		);
	}
	public function settings_description_deprecated() {
		return sprintf(
			'<strong title="%1$s" style="cursor:help;">[%2$s]</strong>',
			esc_html__( 'YouTube deprecated this option. You can use it if you know it, but you cannot get it anymore from YouTube.', 'wpau-yt-channel' ),
			esc_html__( 'Deprecated', 'wpau-yt-channel' )
		);
	}

	// --- Section desciptions ---
	public function settings_general_section_description() {
		echo '<p>';
		printf(
			// translators: %s is replaced with plugin name
			esc_html__(
				'Configure general defaults for the %s used as fallback options for shortcodes and initial values for new widget(s).',
				'wpau-yt-channel'
			),
			'<strong>' . esc_html( YTC_PLUGIN_NAME ) . '</strong>'
		);
		echo '<br />';
		echo esc_html__(
			'Only some options are fallback for widget (Channel ID, Handle, Vanity Name, Legacy Username, Default Playlist), while other options are just initial set of settings for new widget.',
			'wpau-yt-channel'
		);
		echo '</p>';
	} // END public function settings_general_section_description()

	public function settings_video_section_description() {
		echo '<p>';
		printf(
			// translators: %s is replaced with plugin name
			esc_html__(
				'Configure video specific defaults for %s used as fallback options for shortcodes and initial set of options for new widget.',
				'wpau-yt-channel'
			),
			'<strong>' . esc_html( YTC_PLUGIN_NAME ) . '</strong>'
		);
		echo '</p>';
	} // END public function settings_video_section_description()

	public function settings_content_section_description() {
		echo '<p>';
		printf(
			// translators: %s is replaced with plugin name
			esc_html__(
				'Configure defaults of content around and over videos for %s used as fallback options for shortcodes and initial set of options for new widget.',
				'wpau-yt-channel'
			),
			'<strong>' . esc_html( YTC_PLUGIN_NAME ) . '</strong>'
		);
		echo '</p>';
	} // END public function settings_content_section_description()

	public function settings_link_section_description() {
		echo '<p>';
		printf(
			// translators: %s is replaced with plugin name
			esc_html__(
				'Configure defaults for link to channel below %s block used as fallback options for shortcodes and initial set of options for new widget.',
				'wpau-yt-channel'
			),
			'<strong>' . esc_html( YTC_PLUGIN_NAME ) . '</strong>'
		);
		echo '</p>';
	} // END public function settings_link_section_description()

	/**
	 * This function provides separator for settings fields
	 */
	public function settings_field_separator() {
		echo '<hr>';
	} // END public function settings_field_input_text()

	/**
	 * Prepare prefix and sufix for field Description
	 *
	 * @param array $args Array of field options
	 *
	 * @return array Returns associated array of `prefix` and `sufix`
	 */
	public function settings_fields_description( $args ) {
		$desc_prefix = '';
		$desc_sufix  = '';
		// desc_required
		if ( ! empty( $args['desc_required'] ) ) {
			$desc_prefix .= '<strong>[' . esc_html__( 'Required', 'wpau-yt-channel' ) . ']</strong>';
		}
		// desc_optional
		if ( ! empty( $args['desc_optional'] ) ) {
			$desc_prefix .= sprintf(
				'<strong title="%1$s" style="cursor:help">[%2$s]</strong>',
				esc_html__( 'You can leave this option empty and set it per widget/shortcode.', 'wpau-yt-channel' ),
				esc_html__( 'Optional', 'wpau-yt-channel' )
			);
		}
		// desc_deprecated
		if ( ! empty( $args['desc_deprecated'] ) ) {
			$desc_prefix .= sprintf(
				'<strong title="%1$s" style="cursor:help">[%2$s]</strong>',
				esc_html__( 'YouTube deprecated this option. You can use it if you know it, but you cannot get it anymore from YouTube.', 'wpau-yt-channel' ),
				esc_html__( 'Deprecated', 'wpau-yt-channel' )
			);
		}
		// desc_global
		if ( ! empty( $args['desc_global'] ) ) {
			$desc_prefix .= sprintf(
				'<strong title="%1$s" style="cursor:help">[%2$s]</strong>',
				esc_html__( 'This option is global only and can`t be changed per widget/shortcode', 'wpau-yt-channel' ),
				esc_html__( 'Global', 'wpau-yt-channel' )
			);
		}
		// desc_link
		if ( ! empty( $args['desc_link_url'] ) ) {
			if ( empty( $args['desc_link_txt'] ) ) {
				$args['desc_link_txt'] = $args['desc_link_url'];
			}
			$desc_sufix = sprintf(
				' <a href="%1$s" target="_blank">%2$s</a>',
				esc_url( $args['desc_link_url'] ),
				esc_html( $args['desc_link_txt'] )
			);
		}

		return array(
			'prefix' => $desc_prefix,
			'sufix'  => $desc_sufix,
		);
	}

	/**
	 * This function provides text inputs for settings fields
	 */
	public function settings_field_input_text( $args ) {
		$desc_ps = $this->settings_fields_description( $args );

		printf(
			'<input type="text" name="%1$s" id="%1$s" value="%2$s" class="%3$s" data-lpignore="true" /><p class="description">%4$s</p>',
			esc_attr( $args['field'] ),
			esc_attr( $args['value'] ),
			esc_attr( $args['class'] ),
			$desc_ps['prefix'] . ' ' . esc_html( $args['description'] ) . $desc_ps['sufix']
		);
	} // END public function settings_field_input_text()

	/**
	 * This function provides number inputs for settings fields
	 */
	public function settings_field_input_number( $args ) {
		printf(
			'<input type="number" name="%1$s" id="%1$s" value="%2$s" min="%3$s" max="%4$s" class="%5$s" /><p class="description">%6$s</p>',
			esc_attr( $args['field'] ),
			esc_attr( $args['value'] ),
			intval( $args['min'] ),
			intval( $args['max'] ),
			esc_attr( $args['class'] ),
			esc_html( $args['description'] )
		);
	} // END public function settings_field_input_text()

	/**
	 * This function provides select for settings fields
	 */
	public function settings_field_select( $args ) {
		$options = '';
		foreach ( $args['items'] as $key => $val ) {
			$options .= sprintf(
				'<option value="%1$s" %3$s>%2$s</option>',
				esc_attr( $key ),
				esc_html( $val ),
				$args['value'] === $key ? 'selected="selected"' : ''
			);
		}

		printf(
			'<select id="%1$s" name="%1$s">%2$s</select><p class="description">%3$s</p>',
			esc_attr( $args['field'] ),
			$options,
			esc_html( $args['description'] )
		);
	} // END public function settings_field_select()

	/**
	 * This function provides checkbox for settings fields
	 */
	public function settings_field_checkbox( $args ) {
		$desc_ps = $this->settings_fields_description( $args );

		printf(
			'<label for="%1$s"><input type="checkbox" name="%1$s" id="%1$s" value="1" class="%2$s" %3$s />%4$s</label><p class="description">%5$s</p>',
			esc_attr( $args['field'] ),
			esc_attr( $args['class'] ),
			true === boolval( $args['value'] ) ? 'checked=checked' : '',
			isset( $args['label'] ) ? esc_html( $args['label'] ) : '',
			$desc_ps['prefix'] . esc_html( $args['description'] ) . $desc_ps['sufix']
		);
	} // END public function settings_field_checkbox()

	/**
	 * Menu Callback
	 */
	public function plugin_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ), esc_html( YTC_PLUGIN_NAME ) );
		}

		// Render the settings template
		require_once YTC_DIR_TEMPLATES . '/settings.php';
	} // END public function plugin_settings_page()

	/**
	 * process options before update
	 *
	 */
	public function sanitize_options( $options ) {
		$sanitized = get_option( $this->option_name );

		// If there is no POST option_page keyword, return initial plugin options
		if ( empty( $_POST['option_page'] ) ) {
			return $sanitized;
		}

		switch ( $_POST['option_page'] ) {

			// --- General ---
			case 'ytc_general':
				$sanitized['apikey']         = ! empty( $options['apikey'] ) ? ytc_sanitize_api_key( $options['apikey'] ) : ''; // string key
				$sanitized['channel']        = ! empty( $options['channel'] ) ? ytc_sanitize_api_key( $options['channel'] ) : ''; // string key
				$sanitized['handle']         = ! empty( $options['handle'] ) ? sanitize_user( $options['handle'], true ) : ''; // string
				$sanitized['vanity']         = ! empty( $options['vanity'] ) ? sanitize_user( $options['vanity'], true ) : ''; // string
				$sanitized['username']       = ! empty( $options['username'] ) ? sanitize_user( $options['username'], true ) : ''; // string
				$sanitized['playlist']       = ! empty( $options['playlist'] ) ? ytc_sanitize_api_key( $options['playlist'] ) : ''; // string key
				$sanitized['resource']       = isset( $options['resource'] ) ? intval( $options['resource'] ) : intval( $this->defaults['resource'] ); // int
				$sanitized['cache']          = isset( $options['cache'] ) ? intval( $options['cache'] ) : intval( $this->defaults['cache'] ); // int
				$sanitized['fetch']          = ! empty( $options['fetch'] ) ? intval( $options['fetch'] ) : intval( $this->defaults['fetch'] ); // int
				$sanitized['num']            = ! empty( $options['num'] ) ? intval( $options['num'] ) : intval( $this->defaults['num'] ); // int
				$sanitized['privacy']        = ! empty( $options['privacy'] ) && $options['privacy'] ? 1 : 0; // bool
				$sanitized['tinymce']        = ! empty( $options['tinymce'] ) && $options['tinymce'] ? 1 : 0; // bool
				$sanitized['sslverify']      = ! empty( $options['sslverify'] ) && $options['sslverify'] ? 1 : 0; // bool
				$sanitized['local_img']      = ! empty( $options['local_img'] ) && $options['local_img'] ? 1 : 0; // bool
				$sanitized['js_ev_listener'] = ! empty( $options['js_ev_listener'] ) && $options['js_ev_listener'] ? 1 : 0; // bool
				$sanitized['timeout']        = ! empty( $options['timeout'] ) ? intval( $options['timeout'] ) : intval( $this->defaults['timeout'] ); // int
				$sanitized['block_preview']  = ! empty( $options['block_preview'] ) && $options['block_preview'] ? 1 : 0; // bool
				break; // General

			// --- Video ---
			case 'ytc_video':
				$sanitized['width']          = ( ! empty( $options['width'] ) ) ? intval( $options['width'] ) : intval( $this->defaults['width'] ); // int
				$sanitized['ratio']          = ( isset( $options['ratio'] ) ) ? intval( $options['ratio'] ) : intval( $this->defaults['ratio'] ); // int
				$sanitized['display']        = ( ! empty( $options['display'] ) ) ? sanitize_key( $options['display'] ) : sanitize_key( $this->defaults['display'] ); // string
				$sanitized['thumb_quality']  = ( ! empty( $options['thumb_quality'] ) ) ? sanitize_key( $options['thumb_quality'] ) : sanitize_key( $this->defaults['thumb_quality'] ); // string
				$sanitized['responsive']     = ( ! empty( $options['responsive'] ) && $options['responsive'] ) ? 1 : 0; // bool
				$sanitized['playsinline']    = ( ! empty( $options['playsinline'] ) && $options['playsinline'] ) ? 1 : 0; // bool
				$sanitized['nolightbox']     = ( ! empty( $options['nolightbox'] ) && $options['nolightbox'] ) ? 1 : 0; // bool
				$sanitized['fullscreen']     = ( ! empty( $options['fullscreen'] ) && $options['fullscreen'] ) ? 1 : 0; // bool
				$sanitized['controls']       = ( ! empty( $options['controls'] ) && $options['controls'] ) ? 1 : 0; // bool
				$sanitized['autoplay']       = ( ! empty( $options['autoplay'] ) && $options['autoplay'] ) ? 1 : 0; // bool
				$sanitized['autoplay_mute']  = ( ! empty( $options['autoplay_mute'] ) && $options['autoplay_mute'] ) ? 1 : 0; // bool
				$sanitized['norel']          = ( ! empty( $options['norel'] ) && $options['norel'] ) ? 1 : 0; // bool
				$sanitized['modestbranding'] = ( ! empty( $options['modestbranding'] ) && $options['modestbranding'] ) ? 1 : 0; // bool
				$sanitized['hideanno']       = ( ! empty( $options['hideanno'] ) && $options['hideanno'] ) ? 1 : 0; // bool
				break; // Video

			// --- Content ---
			case 'ytc_content':
				$sanitized['showtitle'] = ! empty( $options['showtitle'] ) && in_array( $options['showtitle'], array( 'none', 'above', 'below', 'inside', 'inside_b' ), true ) ? sanitize_key( $options['showtitle'] ) : sanitize_key( $this->defaults['showtitle'] ); // string
				$sanitized['linktitle'] = ! empty( $options['linktitle'] ) && $options['linktitle'] ? 1 : 0; // bool
				$sanitized['titletag']  = ! empty( $options['titletag'] ) && in_array( strtolower( $options['titletag'] ), array( 'h3', 'h4', 'h5', 'div', 'span', 'strong' ), true ) ? sanitize_key( $options['titletag'] ) : sanitize_key( $this->defaults['titletag'] ); // string
				$sanitized['showdesc']  = ! empty( $options['showdesc'] ) && $options['showdesc'] ? 1 : 0; // bool
				$sanitized['desclen']   = ! empty( $options['desclen'] ) ? intval( $options['desclen'] ) : intval( $this->defaults['desclen'] ); // integer
				break; // Content

			// --- Link to Channel ---
			case 'ytc_link':
				$sanitized['link_to']    = isset( $options['link_to'] ) && in_array( $options['link_to'], array( 'none', 'handle', 'vanity', 'channel', 'legacy' ), true ) ? $options['link_to'] : $this->defaults['link_to']; // string
				$sanitized['goto_txt']   = ! empty( $options['goto_txt'] ) ? sanitize_text_field( $options['goto_txt'], true ) : sanitize_text_field( $this->defaults['goto_txt'] ); // text
				$sanitized['popup_goto'] = isset( $options['popup_goto'] ) && in_array( intval( $options['popup_goto'] ), array( 0, 1, 2 ), true ) ? intval( $options['popup_goto'] ) : intval( $this->defaults['popup_goto'] ); // integer 0 or 2 (1 is deprecated since 3.23.2)
				break; // Link to Channel

		} // switch

		// --- Update ---
		// now return sanitized options to be written to database
		return $sanitized;
	} // END public function sanitize_options()
} // END class Wpau_My_Youtube_Channel_Settings
