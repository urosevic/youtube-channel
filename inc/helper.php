<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sanitizes a API Key, stripping out unsafe characters.
 *
 * Removes tags, octets, entities, spaces, etc
 * and keep only alphanumeric, _, -
 *
 * @param string $key
 *
 * @return string Sanitized api key
 */
function ytc_sanitize_api_key( $key ) {
	$key = wp_strip_all_tags( $key );
	$key = remove_accents( $key );
	// Kill octets.
	$key = preg_replace( '|%([a-fA-F0-9][a-fA-F0-9])|', '', $key );
	// Kill entities.
	$key = preg_replace( '/&.+?;/', '', $key );
	// Reduce to ASCII for max portability.
	$key = preg_replace( '|[^a-zA-Z0-9 _\-]|i', '', $key );
	$key = trim( $key );
	// Consolidate contiguous whitespace.
	$key = preg_replace( '|\s+|', ' ', $key );

	return $key;
}

/**
 * Sanitizes an HTML classnames to ensure it only contains valid characters.
 *
 * Strips the string down to A-Z,a-z,0-9,_,-, . If this results in an empty
 * string then it will return the alternative value supplied.
 *
 * @param string $classes    The classnames to be sanitized (multiple classnames separated by space)
 * @param string $fallback   Optional. The value to return if the sanitization ends up as an empty string.
 *                           Defaults to an empty string.
 *
 * @return string            The sanitized value
 */
if ( ! function_exists( 'sanitize_html_classes' ) ) {
	function sanitize_html_classes( $classes, $fallback = '' ) {
		// Strip out any %-encoded octets.
		$sanitized = preg_replace( '|%[a-fA-F0-9][a-fA-F0-9]|', '', $classes );

		// Limit to A-Z, a-z, 0-9, '_', '-' and ' ' (for multiple classes).
		$sanitized = trim( preg_replace( '/[^A-Za-z0-9\_\ \-]/', '', $sanitized ) );

		if ( '' === $sanitized && $fallback ) {
			return sanitize_html_classes( $fallback );
		}
		/**
		 * Filters a sanitized HTML class string.
		 *
		 * @param string $sanitized The sanitized HTML class.
		 * @param string $classse   HTML class before sanitization.
		 * @param string $fallback  The fallback string.
		 */
		return apply_filters( 'sanitize_html_classes', $sanitized, $classes, $fallback );
	}
}
