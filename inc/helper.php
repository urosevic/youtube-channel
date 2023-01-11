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
