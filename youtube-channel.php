<?php
/**
 * Plugin Name:       My YouTube Channel
 * Plugin URI:        https://urosevic.net/wordpress/plugins/youtube-channel/
 * Description:       Quick and easy embed latest or random videos from YouTube channel (user uploads) or playlist.
 * Version:           3.24.7
 * Requires at least: 5.3
 * Requires PHP:      7.4
 * Author:            Aleksandar Urošević
 * Author URI:        https://urosevic.net/
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       wpau-yt-channel
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'get_plugin_data' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}
$plugin_data = get_plugin_data( __FILE__ );

define( 'YTC_VER', $plugin_data['Version'] );
define( 'YTC_VER_DB', 26 );
define( 'YTC_PLUGIN_FILE', plugin_basename( __FILE__ ) );
define( 'YTC_PLUGIN_NAME', $plugin_data['Name'] );
define( 'YTC_PLUGIN_URI', $plugin_data['PluginURI'] );
define( 'YTC_PLUGIN_SLUG', 'youtube-channel' );
define( 'YTC_PLUGIN_OPTION_KEY', 'youtube_channel_defaults' );
define( 'YTC_DIR', __DIR__ );
define( 'YTC_DIR_INC', YTC_DIR . '/inc' );
define( 'YTC_DIR_CLASSES', YTC_DIR . '/classes' );
define( 'YTC_DIR_TEMPLATES', YTC_DIR . '/templates' );
define( 'YTC_URL', plugin_dir_url( __FILE__ ) );

require_once YTC_DIR_INC . '/helper.php';
require_once YTC_DIR_CLASSES . '/class-wpau-my-youtube-channel.php';

global $wpau_my_youtube_channel;
if ( empty( $wpau_my_youtube_channel ) ) {
	$wpau_my_youtube_channel = new Wpau_My_Youtube_Channel();
}
