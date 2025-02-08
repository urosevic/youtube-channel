<?php
/**
 * Plugin Name: My YouTube Channel
 * Plugin URI: https://urosevic.net/wordpress/plugins/youtube-channel/
 * Description: Quick and easy embed latest or random videos from YouTube channel (user uploads) or playlist.
 * Version: 3.25.2
 * Author: Aleksandar Urošević
 * Author URI: https://urosevic.net/
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: youtube-channel
 * Requires at least: 5.3
 * Requires PHP: 7.4
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'YTC_VER', '3.25.2' );
define( 'YTC_VER_DB', 26 );
define( 'YTC_PLUGIN_FILE', plugin_basename( __FILE__ ) );
define( 'YTC_PLUGIN_NAME', 'My YouTube Channel' );
define( 'YTC_PLUGIN_SLUG', 'youtube-channel' );
define( 'YTC_PLUGIN_OPTION_KEY', 'youtube_channel_defaults' );
define( 'YTC_DIR', __DIR__ );
define( 'YTC_DIR_TEMPLATES', __DIR__ . '/templates' );
define( 'YTC_URL', plugin_dir_url( __FILE__ ) );

require_once __DIR__ . '/inc/helper.php';
require_once __DIR__ . '/classes/class-wpau-my-youtube-channel.php';

global $wpau_my_youtube_channel;
if ( empty( $wpau_my_youtube_channel ) ) {
	$wpau_my_youtube_channel = new Wpau_My_Youtube_Channel();
}
