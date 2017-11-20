<?php

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPAU_YOUTUBE_CHANNEL_SETTINGS' ) ) {

	class WPAU_YOUTUBE_CHANNEL_SETTINGS {

		private $option_name;
		private $defaults;
		private $slug;

		/**
		 * Construct the plugin object
		 */
		public function __construct() {

			global $wpau_youtube_channel;

			// get default values
			$this->slug = $wpau_youtube_channel->plugin_slug;
			$this->option_name = $wpau_youtube_channel->plugin_option;
			$this->defaults = get_option( $this->option_name );

			// register actions
			add_action( 'admin_init', array( &$this, 'register_settings' ) );
			add_action( 'admin_menu', array( &$this, 'add_menu' ) );

		} // END public function __construct

		/**
		 * hook into WP's register_settings action hook
		 */
		public function register_settings() {
			global $wpau_youtube_channel;

			// =========================== General ===========================
			// --- Add settings section General so we can add fields to it ---
			add_settings_section(
				'ytc_general', // Section Name
				__( 'General', 'youtube-channel' ), // Section Title
				array( &$this, 'settings_general_section_description' ), // Section Callback Function
				$this->slug . '_general' // Page Name
			);
			// --- Add Fields to General section ---
			// YouTube Data API Key
			add_settings_field(
				$this->option_name . 'apikey', // Setting Slug
				__( 'YouTube Data API Key', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_input_text' ), // Callback
				$this->slug . '_general', // Page Name
				'ytc_general', // Section Name
				array(
					'field'       => $this->option_name . '[apikey]',
					'description' => sprintf(
						'%1$s %2$s %3$s',
						$this->settings_description_required(),
						$this->settings_description_global(),
						sprintf(
							wp_kses(
								__(
									'Your YouTube Data API Key (get it from <a href="%1$s" target="_blank">%2$s</a>)',
									'youtube-channel'
								),
								array(
									'a' => array(
										'href'   => array(),
										'target' => array( '_blank' ),
									),
								)
							),
							esc_url( 'https://console.developers.google.com/project' ),
							__( 'Google Developers Console', 'youtube-channel' )
						)
					),
					'class'       => 'regular-text password',
					'value'       => isset( $this->defaults['apikey'] ) ? $this->defaults['apikey'] : '',
				) // args
			);
			// Channel ID
			add_settings_field(
				$this->option_name . 'channel', // Setting Slug
				__( 'YouTube Channel ID', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_input_text' ), // Callback
				$this->slug . '_general', // Page Name
				'ytc_general', // Section Name
				array(
					'field'       => $this->option_name . '[channel]',
					'description' => sprintf(
						'%1$s %2$s',
						$this->settings_description_required(),
						sprintf(
							wp_kses(
								__(
									'Your YouTube Channel ID (get it from <a href="%1$s" target="_blank">%2$s</a>)',
									'youtube-channel'
								),
								array(
									'a' => array(
										'href'   => array(),
										'target' => array( '_blank' ),
									),
								)
							),
							esc_url( 'https://www.youtube.com/account_advanced' ),
							__( 'YouTube Account Overview', 'youtube-channel' )
						)
					),
					'class'       => 'regular-text',
					'value'       => isset( $this->defaults['channel'] ) ? $this->defaults['channel'] : '',
				) // args
			);
			// Vanity
			add_settings_field(
				$this->option_name . 'vanity', // id
				__( 'YouTube Vanity Name', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_input_text' ), // Callback
				$this->slug . '_general', // Page
				'ytc_general', // section
				array(
					'field'       => $this->option_name . '[vanity]',
					'description' => sprintf(
						'%s %s',
						$this->settings_description_optional(),
						sprintf(
							wp_kses(
								__(
									'Your YouTube Custom Name (get only part after %1$s instead whole URL from <a href="%2$s" target="_blank">%3$s</a>)',
									'youtube-channel'
								),
								array(
									'a' => array(
										'href'   => array(),
										'target' => array( '_blank' ),
									),
								)
							),
							'www.youtube.com/c/',
							esc_url( 'https://www.youtube.com/account_advanced' ),
							__( 'YouTube Account Overview', 'youtube-channel' )
						)
					),
					'class'       => 'regular-text',
					'value'       => isset( $this->defaults['vanity'] ) ? $this->defaults['vanity'] : '',
				) // args
			);
			// Username
			add_settings_field(
				$this->option_name . 'username', // id
				__( 'Legacy YouTube Username', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_input_text' ), // Callback
				$this->slug . '_general', // Page
				'ytc_general', // section
				array(
					'field'       => $this->option_name . '[username]',
					'description' => sprintf(
						'%s %s',
						$this->settings_description_optional(),
						__( 'Your YouTube legacy username', 'youtube-channel' )
					),
					'class'       => 'regular-text',
					'value'       => isset( $this->defaults['username'] ) ? $this->defaults['username'] : '',
				) // args
			);
			// Default Playlist
			add_settings_field(
				$this->option_name . 'playlist', // id
				__( 'Default Playlist ID', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_input_text' ), // Callback
				$this->slug . '_general', // Page
				'ytc_general', // section
				array(
					'field'       => $this->option_name . '[playlist]',
					'description' => sprintf(
						'%s %s',
						$this->settings_description_optional(),
						__( 'Enter default playlist ID (not playlist name)', 'youtube-channel' )
					),
					'class'       => 'regular-text',
					'value'       => isset( $this->defaults['playlist'] ) ? $this->defaults['playlist'] : '',
				) // args
			);
			// Resource
			add_settings_field(
				$this->option_name . 'resource', // id
				__( 'Resource to use', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_select' ), // Callback
				$this->slug . '_general', // Page
				'ytc_general', // section
				array(
					'field'       => $this->option_name . '[resource]',
					'label' => __( 'Resource:', 'youtube-channel' ),
					'description' => __( 'What to use as resource for feeds', 'youtube-channel' ),
					'class'       => 'regular-text',
					'value'       => isset( $this->defaults['resource'] ) ? $this->defaults['resource'] : '0',
					'items'       => array(
						'0' => __( 'Channel', 'youtube-channel' ),
						'1' => __( 'Favourites', 'youtube-channel' ),
						'3' => __( 'Liked Video', 'youtube-channel' ),
						'2' => __( 'Playlist', 'youtube-channel' ),
					),
				) // args
			);
			// Cache
			add_settings_field(
				$this->option_name . 'cache', // id
				__( 'Cache Timeout','youtube-channel' ),
				array( &$this, 'settings_field_select' ),
				$this->slug . '_general',
				'ytc_general',
				array(
					'field'       => $this->option_name . '[cache]',
					'description' => __( 'Define caching timeout for YouTube feeds, in seconds', 'youtube-channel' ),
					'class'       => 'wide-text',
					'value'       => isset( $this->defaults['cache'] ) ? $this->defaults['cache'] : '300',
					'items'       => $wpau_youtube_channel->cache_times_arr(),
				)
			);
			// Fetch
			add_settings_field(
				$this->option_name . 'fetch', // id
				__( 'Fetch', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_input_number' ), // Callback
				$this->slug . '_general', // Page
				'ytc_general', // section
				array(
					'field'       => $this->option_name . '[fetch]',
					'description' => __( 'Number of videos that will be fetched from YouTube and used for random pick (min 2, max 50, default 25)', 'youtube-channel' ),
					'class'       => 'num',
					'value'       => isset( $this->defaults['fetch'] ) ? $this->defaults['fetch'] : '25',
					'min'         => 1,
					'max'         => 50,
					'std'         => 25,
				) // args
			);
			// Show
			add_settings_field(
				$this->option_name . 'num', // id
				__( 'Show', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_input_number' ), // Callback
				$this->slug . '_general', // Page
				'ytc_general', // section
				array(
					'field'       => $this->option_name . '[num]',
					'description' => __( 'Number of videos to display', 'youtube-channel' ),
					'class'       => 'num',
					'value'       => isset( $this->defaults['num'] ) ? $this->defaults['num'] : '1',
					'min'         => 1,
					'max'         => 50,
					'std'         => 1,
				) // args
			);
			// Enhanced privacy
			add_settings_field(
				$this->option_name . 'privacy', // id
				__( 'Enhanced Privacy', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_checkbox' ), // Callback
				$this->slug . '_general', // Page
				'ytc_general', // section
				array(
					'field'       => $this->option_name . '[privacy]',
					'label'       => __( 'Enable', 'youtube-channel' ),
					'description' => sprintf(
						wp_kses(
							__(
								'Enable this option to protect your visitors privacy. <a href="%1$s" target="_blank">%2$s</a>',
								'youtube-channel'
							),
							array(
								'a' => array(
									'href'   => array(),
									'target' => array( '_blank' ),
								),
							)
						),
						esc_url( 'https://support.google.com/youtube/answer/171780' ),
						__( 'Learn more here', 'youtube-channel' )
					),
					'class'       => 'checkbox',
					'value'       => isset( $this->defaults['privacy'] ) ? $this->defaults['privacy'] : '0',
				) // args
			);
			// TinyMCE icon
			add_settings_field(
				$this->option_name . 'tinymce', // id
				__( 'TinyMCE button', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_checkbox' ), // Callback
				$this->slug . '_general', // Page
				'ytc_general', // section
				array(
					'field'       => $this->option_name . '[tinymce]',
					'label'       => __( 'Enable', 'youtube-channel' ),
					'description' => sprintf(
						__( 'Disable this option to hide %s button from TinyMCE toolbar on post and page editor.', 'youtube-channel' ),
						__( 'YouTube Channel', 'youtube-channel' )
					),
					'class'       => 'checkbox',
					'value'       => isset( $this->defaults['tinymce'] ) ? $this->defaults['tinymce'] : '0',
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
				__( 'Video Tweaks', 'youtube-channel' ), // Section Title
				array( &$this, 'settings_video_section_description' ), // Section Callback Function
				$this->slug . '_video' // Page Name
			);
			// --- Add Fields to video section ---
			// Width
			add_settings_field(
				$this->option_name . 'width', // id
				__( 'Initial Width', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_input_number' ), // Callback
				$this->slug . '_video', // Page
				'ytc_video', // section
				array(
					'field'       => $this->option_name . '[width]',
					'description' => __( 'Set default width for displayed video, in pixels. This value have effect only if Responsive Video option is disabled!', 'youtube-channel' ),
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
				__( 'Aspect ratio', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_select' ), // Callback
				$this->slug . '_video', // Page
				'ytc_video', // section
				array(
					'field'       => $this->option_name . '[ratio]',
					'description' => __( 'Select aspect ratio for displayed video', 'youtube-channel' ),
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
				__( 'Embed as', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_select' ), // Callback
				$this->slug . '_video', // Page
				'ytc_video', // section
				array(
					'field'       => $this->option_name . '[display]',
					'description' => __( 'Choose how to embed video block', 'youtube-channel' ),
					'class'       => 'regular-text',
					'value'       => isset( $this->defaults['display'] ) ? $this->defaults['display'] : 'thumbnail',
					'items'       => array(
						'thumbnail' => __( 'Thumbnail', 'youtube-channel' ),
						'iframe'    => __( 'HTML5 (iframe)', 'youtube-channel' ),
						'iframe2'   => __( 'HTML5 (iframe) Asynchronous', 'youtube-channel' ),
						'playlist'  => __( 'Embedded Playlist', 'youtube-channel' ),
						// 'gallery'   => 'Gallery'
					),
				) // args
			);
			// Thumbnail Quality
			add_settings_field(
				$this->option_name . 'thumb_quality', // id
				__( 'Thumbnail Quality', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_select' ), // Callback
				$this->slug . '_video', // Page
				'ytc_video', // section
				array(
					'field'       => $this->option_name . '[thumb_quality]',
					'description' => __( 'Choose preferred thumbnail quality. Please be aware, if you select Maximum Resolution but video does not have that thumbnail, you will get broken thumbnail on page!', 'youtube-channel' ),
					'class'       => 'regular-text',
					'value'       => isset( $this->defaults['thumb_quality'] ) ? $this->defaults['thumb_quality'] : '0',
					'items'       => array(
						'default'       => __( 'Default Quality (120x90px)', 'youtube-channel' ),
						'mqdefault'     => __( 'Medium Quality (320x180px)', 'youtube-channel' ),
						'hqdefault'     => __( 'High Quality (480x360px)', 'youtube-channel' ),
						'sddefault'     => __( 'Standard Definition (640x480px)', 'youtube-channel' ),
						'maxresdefault' => __( 'Maximum Resolution (1280x720px)', 'youtube-channel' ),
					),
				) // args
			);

			// Responsive
			add_settings_field(
				$this->option_name . 'responsive', // id
				__( 'Responsive Video', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_checkbox' ), // Callback
				$this->slug . '_video', // Page
				'ytc_video', // section
				array(
					'field'       => $this->option_name . '[responsive]',
					'label'       => __( 'Enable', '<outube-channel' ),
					'description' => __( 'Enable this option to make YTC videos and thumbnails responsive by default. Please note, this option will set videos and thumbnail to full width relative to parent container, and disable more than one video per row.', 'youtube-channel' ),
					'class'       => 'checkbox',
					'value'       => isset( $this->defaults['responsive'] ) ? $this->defaults['responsive'] : '0',
				) // args
			);

			// Plays Inline
			add_settings_field(
				$this->option_name . 'playsinline', // id
				__( 'Play inline on iOS', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_checkbox' ), // Callback
				$this->slug . '_video', // Page
				'ytc_video', // section
				array(
					'field'       => $this->option_name . '[playsinline]',
					'description' => sprintf(
						wp_kses(
							__(
								'Enable this option to override fullscreen playback on iOS, and force inline playback on page and in lightbox. <a href="%1$s" target="_blank">%2$s</a>',
								'youtube-channel'
							),
							array(
								'a' => array(
									'href'   => array(),
									'target' => array( '_blank' ),
								),
							)
						),
						esc_url( 'https://developers.google.com/youtube/player_parameters#playsinline' ),
						__( 'Learn more here', 'youtube-channel' )
					),
					'class'       => 'checkbox',
					'value'       => isset( $this->defaults['playsinline'] ) ? $this->defaults['playsinline'] : '0',
				) // args
			);
			// No Lightbox
			add_settings_field(
				$this->option_name . 'nolightbox', // id
				__( 'Disable Lightbox', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_checkbox' ), // Callback
				$this->slug . '_video', // Page
				'ytc_video', // section
				array(
					'field'       => $this->option_name . '[nolightbox]',
					'description' => __( 'Enable this option to disable built-in lightbox for thumbnails (in case that you have youtube links lightbox trigger in theme or other plugin).', 'youtube-channel' ),
					'class'       => 'checkbox',
					'value'       => isset( $this->defaults['nolightbox'] ) ? $this->defaults['nolightbox'] : '0',
				) // args
			);
			// Full Screen
			add_settings_field(
				$this->option_name . 'fullscreen', // id
				__( 'Full Screen', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_checkbox' ), // Callback
				$this->slug . '_video', // Page
				'ytc_video', // section
				array(
					'field'       => $this->option_name . '[fullscreen]',
					'label'       => __( 'Enable', 'youtube-channel' ),
					'description' => __( 'Enable this option to make available Full Screen button for embedded playlists.', 'youtube-channel' ),
					'class'       => 'checkbox',
					'value'       => isset( $this->defaults['fullscreen'] ) ? $this->defaults['fullscreen'] : '0',
				) // args
			);

			// Light Theme
			add_settings_field(
				$this->option_name . 'themelight', // id
				__( 'Light Theme', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_checkbox' ), // Callback
				$this->slug . '_video', // Page
				'ytc_video', // section
				array(
					'field'       => $this->option_name . '[themelight]',
					'description' => __( 'Enable this option to use light theme for playback controls instead dark.', 'youtube-channel' ),
					'class'       => 'checkbox',
					'value'       => isset( $this->defaults['themelight'] ) ? $this->defaults['themelight'] : '0',
				) // args
			);
			// No Player Controls
			add_settings_field(
				$this->option_name . 'controls', // id
				__( 'Hide Player Controls', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_checkbox' ), // Callback
				$this->slug . '_video', // Page
				'ytc_video', // section
				array(
					'field'       => $this->option_name . '[controls]',
					'description' => __( 'Enable this option to hide playback controls', 'youtube-channel' ),
					'class'       => 'checkbox',
					'value'       => isset( $this->defaults['controls'] ) ? $this->defaults['controls'] : '0',
				) // args
			);
			// Fix Height (deprecated?)
			// Autoplay
			add_settings_field(
				$this->option_name . 'autoplay', // id
				__( 'Autoplay video or playlist', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_checkbox' ), // Callback
				$this->slug . '_video', // Page
				'ytc_video', // section
				array(
					'field'       => $this->option_name . '[autoplay]',
					'description' => __( 'Enable this option to start video playback right after block is rendered', 'youtube-channel' ),
					'class'       => 'checkbox',
					'value'       => isset( $this->defaults['autoplay'] ) ? $this->defaults['autoplay'] : '0',
				) // args
			);
			// Mute on autoplay
			add_settings_field(
				$this->option_name . 'autoplay_mute', // id
				__( 'Mute video on autoplay', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_checkbox' ), // Callback
				$this->slug . '_video', // Page
				'ytc_video', // section
				array(
					'field'       => $this->option_name . '[autoplay_mute]',
					'description' => __( 'Enable this option to mute video when start autoplay', 'youtube-channel' ),
					'class'       => 'checkbox',
					'value'       => isset( $this->defaults['autoplay_mute'] ) ? $this->defaults['autoplay_mute'] : '0',
				) // args
			);
			// No related videos
			add_settings_field(
				$this->option_name . 'norel', // id
				__( 'No related videos', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_checkbox' ), // Callback
				$this->slug . '_video', // Page
				'ytc_video', // section
				array(
					'field'       => $this->option_name . '[norel]',
					'description' => __( 'Enable this option to hide related videos after finished playback', 'youtube-channel' ),
					'class'       => 'checkbox',
					'value'       => isset( $this->defaults['norel'] ) ? $this->defaults['norel'] : '0',
				) // args
			);
			// Hide Annotations
			add_settings_field(
				$this->option_name . 'hideanno', // id
				__( 'No video annotations', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_checkbox' ), // Callback
				$this->slug . '_video', // Page
				'ytc_video', // section
				array(
					'field'       => $this->option_name . '[hideanno]',
					'description' => __( 'Enable this option to hide video annotations (custom text set by uploader over video during playback)', 'youtube-channel' ),
					'class'       => 'checkbox',
					'value'       => isset( $this->defaults['hideanno'] ) ? $this->defaults['hideanno'] : '0',
				) // args
			);
			// Hide Video Info
			add_settings_field(
				$this->option_name . 'hideinfo', // id
				__( 'No video info', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_checkbox' ), // Callback
				$this->slug . '_video', // Page
				'ytc_video', // section
				array(
					'field'       => $this->option_name . '[hideinfo]',
					'description' => __( 'Enable this option to hide informations about video before play start (video title and uploader in overlay)', 'youtube-channel' ),
					'class'       => 'checkbox',
					'value'       => isset( $this->defaults['hideinfo'] ) ? $this->defaults['hideinfo'] : '0',
				) // args
			);
			// Hide YT logo
			add_settings_field(
				$this->option_name . 'modestbranding', // id
				__( 'No YouTube logo', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_checkbox' ), // Callback
				$this->slug . '_video', // Page
				'ytc_video', // section
				array(
					'field'       => $this->option_name . '[modestbranding]',
					'description' => __( 'Enable this option to hide YouTube logo from playback control bar. Does not work for all videos.', 'youtube-channel' ),
					'class'       => 'checkbox',
					'value'       => isset( $this->defaults['modestbranding'] ) ? $this->defaults['modestbranding'] : '0',
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
				__( 'Content Tweaks', 'youtube-channel' ), // Section Title
				array( &$this, 'settings_content_section_description' ), // Section Callback Function
				$this->slug . '_content' // Page Name
			);
			// --- Add Fields to video section ---
			// Video Title
			add_settings_field(
				$this->option_name . 'showtitle', // id
				__( 'Video title', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_select' ), // Callback
				$this->slug . '_content', // Page
				'ytc_content', // section
				array(
					'field'       => $this->option_name . '[showtitle]',
					'description' => __( 'Select should we and where to display title of video.', 'youtube-channel' ),
					'class'       => 'regular-text',
					'value'       => isset( $this->defaults['showtitle'] ) ? $this->defaults['showtitle'] : 'none',
					'items'       => array(
						'none'  => __( 'Hide title', 'youtube-channel' ),
						'above' => __( 'Above video/thumbnail', 'youtube-channel' ),
						'below' => __( 'Below video/thumbnail', 'youtube-channel' ),
					),
				) // args
			);
			// Video Title HTML Tag
			add_settings_field(
				$this->option_name . 'titletag', // id
				__( 'Title HTML tag', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_select' ), // Callback
				$this->slug . '_content', // Page
				'ytc_content', // section
				array(
					'field'       => $this->option_name . '[titletag]',
					'description' => sprintf(
						'%1$s %2$s',
						$this->settings_description_global(),
						__( 'Select which HTML tag to use for title wrapper.', 'youtube-channel' )
					),
					'class'       => 'regular-text',
					'value'       => isset( $this->defaults['titletag'] ) ? $this->defaults['titletag'] : 'h3',
					'items'       => array(
						'h3'   => 'H3',
						'h4'   => 'H4',
						'h5'   => 'H5',
						'span' => 'span',
						'div'  => 'div',
					),
				) // args
			);
			// Video Description
			add_settings_field(
				$this->option_name . 'showdesc', // id
				__( 'Video description', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_checkbox' ), // Callback
				$this->slug . '_content', // Page
				'ytc_content', // section
				array(
					'field'       => $this->option_name . '[showdesc]',
					'description' => __( 'Enable this option to show video description.', 'youtube-channel' ),
					'class'       => 'checkbox',
					'value'       => isset( $this->defaults['showdesc'] ) ? $this->defaults['showdesc'] : '0',
				) // args
			);
			// Description length
			add_settings_field(
				$this->option_name . 'desclen', // id
				__( 'Description length', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_input_number' ), // Callback
				$this->slug . '_content', // Page
				'ytc_content', // section
				array(
					'field'       => $this->option_name . '[desclen]',
					'description' => __( 'Enter length for video description in characters (0 for full length).', 'youtube-channel' ),
					'class'       => 'num',
					'value'       => isset( $this->defaults['desclen'] ) ? $this->defaults['desclen'] : '0',
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
				__( 'Link to Channel', 'youtube-channel' ), // Section Title
				array( &$this, 'settings_link_section_description' ), // Section Callback Function
				$this->slug . '_link' // Page Name
			);
			// --- Add Fields to video section ---
			// Link to...
			add_settings_field(
				$this->option_name . 'link_to', // id
				__( 'Link to...', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_select' ), // Callback
				$this->slug . '_link', // Page
				'ytc_link', // section
				array(
					'field'       => $this->option_name . '[link_to]',
					'description' => __( 'Set where link will lead visitors', 'youtube-channel' ),
					'class'       => 'regular-text',
					'value'       => isset( $this->defaults['link_to'] ) ? $this->defaults['link_to'] : 'none',
					'items'       => array(
						'none'    => __( 'Hide link', 'youtube-channel' ),
						'vanity'  => __( 'Vanity custom URL', 'youtube-channel' ),
						'channel' => __( 'Channel page URL', 'youtube-channel' ),
						'legacy'  => __( 'Legacy username page', 'youtube-channel' ),
					),
				) // args
			);
			// Open in...
			add_settings_field(
				$this->option_name . 'popup_goto', // id
				__( 'Open link in...', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_select' ), // Callback
				$this->slug . '_link', // Page
				'ytc_link', // section
				array(
					'field'       => $this->option_name . '[popup_goto]',
					'description' => __( 'Set how link will be opened', 'youtube-channel' ),
					'class'       => 'regular-text',
					'value'       => isset( $this->defaults['popup_goto'] ) ? $this->defaults['popup_goto'] : '0',
					'items'       => array(
						'0' => __( 'same window', 'youtube-channel' ),
						'1' => __( 'new window (JavaScript)', 'youtube-channel' ),
						'2' => __( 'new window (target="_blank")', 'youtube-channel' ),
					),
				) // args
			);
			// Visit channel text
			add_settings_field(
				$this->option_name . 'goto_txt', // id
				__( 'Text for Visit channel link', 'youtube-channel' ), // Title
				array( &$this, 'settings_field_input_text' ), // Callback
				$this->slug . '_link', // Page
				'ytc_link', // section
				array(
					'field'       => $this->option_name . '[goto_txt]',
					'class'       => 'regular-text',
					'description' => __( 'Set default title for link', 'youtube-channel' ),
					'value'       => isset( $this->defaults['goto_txt'] ) ? $this->defaults['goto_txt'] : '',
				) // args
			);

			// --- Register setting Content so $_POST handling is done ---
			register_setting(
				'ytc_link', // Setting group
				$this->option_name, // option name
				array( $this, 'sanitize_options' )
			);

		} // eom register_settings()

		/**
		 * Add settings menu
		 */
		public function add_menu() {

			// Add a page to manage this plugin's settings
			add_options_page(
				__( 'YouTube Channel', 'youtube-channel' ),
				__( 'YouTube Channel','youtube-channel' ),
				'manage_options',
				$this->slug,
				array( &$this, 'plugin_settings_page' )
			);

		} // eom add_menu()

		// ===================== HELPERS ==========================

		public function settings_description_required() {
			return sprintf(
				'<strong>[%s]</strong>',
				__( 'Required', 'youtube-channel' )
			);
		}
		public function settings_description_global() {
			return sprintf(
				'<strong title="%1$s" style="cursor:help;">[%2$s]</strong>',
				__( 'This option is global only and can`t be changed per widget/shortcode', 'youtube-channel' ),
				__( 'Global', 'youtube-channel' )
			);
		}
		public function settings_description_optional() {
			return sprintf(
				'<strong title="%1$s" style="cursor:help;">[%2$s]</strong>',
				__( 'You can leave this option empty and set it per widget/shortcode.', 'youtube-channel' ),
				__( 'Optional', 'youtube-channel' )
			);
		}

		// --- Section desciptions ---
		public function settings_general_section_description() {

			echo '<p>' .
			sprintf(
				wp_kses(
					__(
						'Configure general defaults for %1$s used as fallback options in widget or shortcodes. To get %2$s and %3$s visit <a href="%4$s" target="_blank">%5$s</a>.',
						'youtube-channel'
					),
					array(
						'a' => array(
							'href'   => array(),
							'target' => array( '_blank' ),
						),
					)
				),
				__( 'YouTube Channel', 'youtube-channel' ),
				__( 'Channel ID', 'youtube-channel' ),
				__( 'Vanity URL', 'youtube-channel' ),
				esc_url( 'https://www.youtube.com/account_advanced' ),
				__( 'YouTube Account Overview', 'youtube-channel' )
			) .
			'<p>';

		} // END public function settings_general_section_description()

		public function settings_video_section_description() {
			echo '<p>' .
			sprintf(
				__( 'Configure video specific defaults for %s used as fallback options in widget or shortcodes.', 'youtube-channel' ),
				__( 'YouTube Channel', 'youtube-channel' )
			) .
			'</p>';
		} // END public function  settings_video_section_description()

		public function settings_content_section_description() {
			echo '<p>' .
			sprintf(
				__( 'Configure defaults of content around and over videos for %s used as fallback options in widget or shortcodes.', 'youtube-channel' ),
				__( 'YouTube Channel', 'youtube-channel' )
			) .
			'</p>';
		} // END public function settings_content_section_description()

		public function settings_link_section_description() {
			echo '<p>' .
			sprintf(
				__( 'Configure defaults for link to channel below %s block used as fallback options in widget or shortcodes.', 'youtube-channel' ),
				__( 'YouTube Channel', 'youtube-channel' )
			) .
			'</p>';
		} // END public function settings_link_section_description()

		/**
		 * This function provides separator for settings fields
		 */
		public function settings_field_separator( $args = null ) {
			echo '<hr>';
		} // END public function settings_field_input_text()

		/**
		 * This function provides text inputs for settings fields
		 */
		public function settings_field_input_text( $args ) {

			printf(
				'<input type="text" name="%1$s" id="%1$s" value="%2$s" class="%3$s" /><p class="description">%4$s</p>',
				$args['field'],
				$args['value'],
				$args['class'],
				$args['description']
			);

		} // END public function settings_field_input_text()

		/**
		 * This function provides password inputs for settings fields
		 */
		public function settings_field_input_password( $args ) {

			printf(
				'<input type="password" name="%1$s" id="%1$s" value="%2$s" class="%3$s" /><p class="description">%4$s</p>',
				$args['field'],
				$args['value'],
				$args['class'],
				$args['description']
			);

		} // END public function settings_field_input_text()

		/**
		 * This function provides number inputs for settings fields
		 */
		public function settings_field_input_number( $args ) {

			printf(
				'<input type="number" name="%1$s" id="%1$s" value="%2$s" min="%3$s" max="%4$s" class="%5$s" /><p class="description">%6$s</p>',
				$args['field'],
				$args['value'],
				$args['min'],
				$args['max'],
				$args['class'],
				$args['description']
			);

		} // END public function settings_field_input_text()

		/**
		 * This function provides select for settings fields
		 */
		public function settings_field_select( $args ) {

			$html = sprintf( '<select id="%1$s" name="%1$s">', $args['field'] );
			foreach ( $args['items'] as $key => $val ) {
				$selected = ( $args['value'] == $key ) ? 'selected="selected"' : '';
				$html .= sprintf( '<option %1$s value="%2$s">%3$s</option>', $selected, $key, $val );
			}
			$html .= sprintf( '</select><p class="description">%s</p>', $args['description'] );

			echo $html;

		} // END public function settings_field_select()

		/**
		 * This function provides checkbox v2 for settings fields
		 */
		public function settings_field_checkbox( $args ) {

			$checked = ! empty( $args['value'] ) ? 'checked="checked"' : '';
			$html = sprintf(
				'<label for="%1$s"><input type="checkbox" name="%1$s" id="%1$s" value="1" class="%2$s" %3$s />%4$s</label>',
				$args['field'],
				$args['class'],
				$checked,
				isset( $args['label'] ) ? $args['label'] : ''
			);
			$html .= sprintf( '<p class="description">%s</p>', $args['description'] );
			echo $html;

		} // END public function settings_field_checkbox()

		/**
		 * This function provides checkbox groupfor settings fields
		 */
		/*
		public function settings_field_checkbox_group( $args ) {

			// items
			$out = '<fieldset>';

			foreach ( $args['items'] as $key => $label ) {

				$checked = '';
				if ( ! empty( $args['value'] ) ) {
					$checked = in_array( $key, $args['value'] ) ? 'checked="checked"' : '';
				}

				$out .= sprintf(
					'<label for="%1$s_%2$s"><input type="checkbox" name="%1$s[]" id="%1$s_%2$s" value="%2$s" class="%3$s" %4$s />%5$s</label><br>',
					$args['field'],
					$key,
					$args['class'],
					$checked,
					$label
				);
			}

			$out .= '</fieldset>';
			$out .= sprintf( '<p class="description">%s</p>' , $args['description'] );

			echo $out;

		} // END public function settings_field_checkbox_group()
		/**/

		/**
		 * This function provides radio buttons for settings fields
		 */
		/*
		public function settings_field_radio( $args ) {

			$html = '';

			if ( ! empty( $args['prescription'] ) ) {
				$html .= sprintf( '<p class="prescription">%s</p>', $args['prescription'] );
			}

			foreach ( $args['items'] as $key => $val ) {

				$checked = $args['value'] == $key ? 'checked="checked"' : '';
				$html .= sprintf(
					'<label for="%1$s_%2$s"><input type="radio" name="%1$s" id="%1$s_%2$s" value="%2$s" %3$s>%4$s</label><br />',
					$args['field'],
					$key,
					$checked,
					$val
				);

			} // END foreach $args['items']

			$html .= sprintf( '<p class="description">%s</p>', $args['description'] );

			echo $html;

		} // END public function settings_field_radio()
		/**/

		/**
		 * Menu Callback
		 */
		public function plugin_settings_page() {

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}

			// Render the settings template
			require_once( 'settings-template.php' );

		} // eom plugin_settings_page()

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
					$apikey = ( defined( 'YOUTUBE_DATA_API_KEY' ) ) ? YOUTUBE_DATA_API_KEY : '';
					$sanitized['apikey']   = ( ! empty( $options['apikey'] ) ) ? trim( $options['apikey'] ) : $apikey;
					$sanitized['channel']  = ( ! empty( $options['channel'] ) ) ? trim( $options['channel'] ) : '';
					$sanitized['vanity']   = ( ! empty( $options['vanity'] ) ) ? trim( $options['vanity'] ) : '';
					$sanitized['username'] = ( ! empty( $options['username'] ) ) ? trim( $options['username'] ) : '';
					$sanitized['playlist'] = ( ! empty( $options['playlist'] ) ) ? trim( $options['playlist'] ) : '';
					$sanitized['resource'] = ( isset( $options['resource'] ) ) ? intval( $options['resource'] ) : $this->defaults['resource'];
					$sanitized['cache']    = ( isset( $options['cache'] ) ) ? intval( $options['cache'] ) : $this->defaults['cache'];
					$sanitized['fetch']    = ( ! empty( $options['fetch'] ) ) ? intval( $options['fetch'] ) : $this->defaults['fetch'];
					$sanitized['num']      = ( ! empty( $options['num'] ) ) ? intval( $options['num'] ) : $this->defaults['num'];
					$sanitized['privacy']  = ( ! empty( $options['privacy'] ) && $options['privacy'] ) ? 1 : 0;
					$sanitized['tinymce']  = ( ! empty( $options['tinymce'] ) && $options['tinymce'] ) ? 1 : 0;
				break; // General

				// --- Video ---
				case 'ytc_video':
					$sanitized['width']          = ( ! empty( $options['width'] ) ) ? intval( $options['width'] ) : $this->defaults['width'];
					$sanitized['ratio']          = ( isset( $options['ratio'] ) ) ? intval( $options['ratio'] ) : $this->defaults['ratio'];
					$sanitized['display']        = ( ! empty( $options['display'] ) ) ? trim( $options['display'] ) : $this->defaults['display'];
					$sanitized['thumb_quality']  = ( ! empty( $options['thumb_quality'] ) ) ? trim( $options['thumb_quality'] ) : $this->defaults['thumb_quality'];
					$sanitized['responsive']     = ( ! empty( $options['responsive'] ) && $options['responsive'] ) ? 1 : 0;
					$sanitized['playsinline']    = ( ! empty( $options['playsinline'] ) && $options['playsinline'] ) ? 1 : 0;
					$sanitized['nolightbox']     = ( ! empty( $options['nolightbox'] ) && $options['nolightbox'] ) ? 1 : 0;
					$sanitized['fullscreen']     = ( ! empty( $options['fullscreen'] ) && $options['fullscreen'] ) ? 1 : 0;
					$sanitized['themelight']     = ( ! empty( $options['themelight'] ) && $options['themelight'] ) ? 1 : 0;
					$sanitized['controls']       = ( ! empty( $options['controls'] ) && $options['controls'] ) ? 1 : 0;
					$sanitized['autoplay']       = ( ! empty( $options['autoplay'] ) && $options['autoplay'] ) ? 1 : 0;
					$sanitized['autoplay_mute']  = ( ! empty( $options['autoplay_mute'] ) && $options['autoplay_mute'] ) ? 1 : 0;
					$sanitized['norel']          = ( ! empty( $options['norel'] ) && $options['norel'] ) ? 1 : 0;
					$sanitized['modestbranding'] = ( ! empty( $options['modestbranding'] ) && $options['modestbranding'] ) ? 1 : 0;
					$sanitized['hideanno']       = ( ! empty( $options['hideanno'] ) && $options['hideanno'] ) ? 1 : 0;
					$sanitized['hideinfo']       = ( ! empty( $options['hideinfo'] ) && $options['hideinfo'] ) ? 1 : 0;
				break; // Video

				// --- Content ---
				case 'ytc_content':
					$sanitized['showtitle']  = ( ! empty( $options['showtitle'] ) ) ? $options['showtitle'] : $this->defaults['showtitle'];
					$sanitized['titletag']   = ( ! empty( $options['titletag'] ) ) ? $options['titletag'] : $this->defaults['titletag'];
					$sanitized['showdesc']   = ( ! empty( $options['showdesc'] ) && $options['showdesc'] ) ? 1 : 0;
					$sanitized['desclen']    = ( ! empty( $options['desclen'] ) ) ? intval( $options['desclen'] ) : $this->defaults['desclen'];
				break; // Content

				// --- Link to Channel ---
				case 'ytc_link':
					$sanitized['link_to']    = ( isset( $options['link_to'] ) ) ? $options['link_to'] : $this->defaults['link_to'];
					$sanitized['goto_txt']   = ( ! empty( $options['goto_txt'] ) ) ? $options['goto_txt'] : $this->defaults['goto_txt'];
					$sanitized['popup_goto'] = ( isset( $options['popup_goto'] ) ) ? intval( $options['popup_goto'] ) : $this->defaults['popup_goto'];
				break; // Link to Channel

			} // switch

			// --- Update ---
			// now return sanitized options to be written to database
			return $sanitized;

		} // END public function sanitize_options()

	} // END class WPAU_YOUTUBE_CHANNEL_SETTINGS

} // END class_exists WPAU_YOUTUBE_CHANNEL_SETTINGS
