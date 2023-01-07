<?php
/**
 * Plugin Name: YouTube Channel
 * Plugin URI:  https://urosevic.net/wordpress/plugins/youtube-channel/
 * Description: Quick and easy embed latest or random videos from YouTube channel (user uploads, liked or favourited videos) or playlist. Use <a href="widgets.php">widget</a> for sidebar or shortcode for content. Works with <em>YouTube Data API v3</em>.
 * Version:     3.23.0
 * Author:      Aleksandar Urošević
 * Author URI:  https://urosevic.net/
 * License:     GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: youtube-channel
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'YTC_VER', '3.23.0' );
define( 'YTC_VER_DB', 24 );
define( 'YTC_PLUGIN_FILE', __FILE__ );
define( 'YTC_PLUGIN', plugin_basename( __FILE__ ) );
define( 'YTC_DIR', dirname( __FILE__ ) );
define( 'YTC_DIR_INC', YTC_DIR . '/inc' );
define( 'YTC_DIR_CLASSES', YTC_DIR . '/classes' );
define( 'YTC_DIR_TEMPLATES', YTC_DIR . '/templates' );
define( 'YTC_URL', plugin_dir_url( __FILE__ ) );
define( 'YTC_URL_ASSETS', YTC_URL . 'assets' );

require_once YTC_DIR_INC . '/helper.php';
require_once YTC_DIR_CLASSES . '/class-wpau-youtube-channel.php';

global $wpau_youtube_channel;
if ( empty( $wpau_youtube_channel ) ) {
	$wpau_youtube_channel = new WPAU_YOUTUBE_CHANNEL();
}
