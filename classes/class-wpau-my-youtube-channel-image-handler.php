<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'Wpau_My_Youtube_Channel_Image_Handler' ) ) {
	return;
}

class Wpau_My_Youtube_Channel_Image_Handler extends Wpau_My_Youtube_Channel {
	private $upload_dir;
	public $defaults;

	public function __construct() {
		$this->defaults   = get_option( YTC_PLUGIN_OPTION_KEY );
		$upload_dir_info  = wp_upload_dir();
		$this->upload_dir = $upload_dir_info['basedir'] . '/my-youtube-channel/';
		// Ensure the directory exists
		if ( ! file_exists( $this->upload_dir ) ) {
			wp_mkdir_p( $this->upload_dir );
		}
	}

	public function get_youtube_image_url( $video_id, $video_quality ) {
		// Build remote image URL
		$remote_url = 'https://img.youtube.com/vi/' . $video_id . '/' . $video_quality . '.jpg';

		// If we don't need to use locally stored images, return remote URL
		if ( true !== (bool) $this->defaults['local_img'] ) {
			return $remote_url;
		}

		// Prepare parameters for local storage
		$video_file_name = "yt-$video_id-$video_quality.jpg";
		$local_file_path = $this->upload_dir . $video_file_name;

		// Check if the file exists locally
		if ( ! file_exists( $local_file_path ) ) {
			// Download the image from YouTube
			$this->download_image( $remote_url, $local_file_path );
		}

		// Return the URL of the locally stored image
		$upload_dir_info = wp_upload_dir();
		return $upload_dir_info['baseurl'] . '/my-youtube-channel/' . $video_file_name;
	}

	private function download_image( $remote_url, $local_file_path ) {
		$image_data = file_get_contents( $remote_url );
		if ( false === $image_data ) {
			throw new Exception( "Failed to download image from $remote_url" );
		}
		file_put_contents( $local_file_path, $image_data );
	}
}

// Usage example
// $wpau_my_youtube_channel_image_handler = new Wpau_My_Youtube_Image_Handler();
// $image_url = $wpau_my_youtube_channel_image_handler->get_youtube_image_url( 'VIDEO_ID', 'maxresdefault');
