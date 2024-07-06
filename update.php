<?php
/**
 * Run the incremental updates one by one.
 *
 * For example, if the current DB version is 3, and the target DB version is 6,
 * this function will execute update routines if they exist:
 *  - au_youtube_channel_update_routine_4()
 *  - au_youtube_channel_update_routine_5()
 *  - au_youtube_channel_update_routine_6()
 */

function au_youtube_channel_update() {
	// no PHP timeout for running updates
	set_time_limit( 0 );

	// this is the current database schema version number
	$current_db_ver = get_option( 'youtube_channel_db_ver', 0 );

	// this is the target version that we need to reach
	$target_db_ver = YTC_VER_DB;

	// run update routines one by one until the current version number
	// reaches the target version number
	while ( $current_db_ver < $target_db_ver ) {
		// increment the current db_ver by one
		++$current_db_ver;

		// each db version will require a separate update function
		// for example, for db_ver 3, the function name should be solis_update_routine_3
		$func = "au_youtube_channel_update_routine_{$current_db_ver}";
		if ( function_exists( $func ) ) {
			call_user_func( $func );
		}

		// update the option in the database, so that this process can always
		// pick up where it left off
		update_option( 'youtube_channel_db_ver', $current_db_ver );
	}

	// Update plugin version number
	update_option( 'youtube_channel_version', YTC_VER );
} // END function au_youtube_channel_update()

/**
 * Add default value for new global options sslverify and js_ev_listener
 */
function au_youtube_channel_update_routine_22() {
	// get options from DB
	$defaults = get_option( 'youtube_channel_defaults' );

	if ( ! isset( $defaults['sslverify'] ) ) {
		$defaults['sslverify'] = true;
	}
	if ( ! isset( $defaults['js_ev_listener'] ) ) {
		$defaults['js_ev_listener'] = false;
	}

	if ( isset( $defaults ) ) {
		update_option( 'youtube_channel_defaults', $defaults );
		unset( $defaults );
	}
} // END function au_youtube_channel_update_routine_22()

/**
 * Add default value for new option skip and remove deprecatged options themelight and showinfo
 */
function au_youtube_channel_update_routine_23() {
	// get options from DB
	$defaults = get_option( 'youtube_channel_defaults' );

	if ( ! isset( $defaults['skip'] ) ) {
		$defaults['skip'] = 0;
	}
	if ( isset( $defaults['themelight'] ) ) {
		unset( $defaults['themelight'] );
	}
	if ( isset( $defaults['noinfo'] ) ) {
		unset( $defaults['noinfo'] );
	}

	if ( isset( $defaults ) ) {
		update_option( 'youtube_channel_defaults', $defaults );
		unset( $defaults );
	}
} // END function au_youtube_channel_update_routine_23()


/**
 * Add default value for new option handle and block_preview
 */
function au_youtube_channel_update_routine_24() {
	// get options from DB
	$defaults = get_option( 'youtube_channel_defaults' );

	if ( ! isset( $defaults['handle'] ) ) {
		$defaults['handle'] = '';
	}
	if ( ! isset( $defaults['blockpreview'] ) ) {
		$defaults['block_preview'] = true;
	}
	if ( isset( $defaults ) ) {
		update_option( 'youtube_channel_defaults', $defaults );
		unset( $defaults );
	}

	// Five years after changes with notices, we don't need dissmissable notices anymore
	delete_option( 'youtube_channel_dismissed_notices' );
} // END function au_youtube_channel_update_routine_24()

/**
 * Add default value for new option handle and block_preview
 */
function au_youtube_channel_update_routine_25() {
	// get options from DB
	$defaults = get_option( 'youtube_channel_defaults' );

	if ( ! isset( $defaults['resource'] ) ) {
		$defaults['resource'] = 0;
	} elseif ( in_array( intval( $defaults['resource'] ), array( 1, 3 ), true ) ) {
		$defaults['resource'] = 0;
	}
	if ( isset( $defaults ) ) {
		update_option( 'youtube_channel_defaults', $defaults );
	}
} // END function au_youtube_channel_update_routine_25()

/**
 * Add default value for new option local_img
 */
function au_youtube_channel_update_routine_26() {
	// get options from DB
	$defaults = get_option( 'youtube_channel_defaults' );

	if ( ! isset( $defaults['local_img'] ) ) {
		$defaults['local_img'] = 0;
	}
	if ( isset( $defaults ) ) {
		update_option( 'youtube_channel_defaults', $defaults );
	}
} // END function au_youtube_channel_update_routine_26()
