<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpau_youtube_channel;
?>
<div class="wrap" id="youtube_channel_settings">
	<h2>
		<?php echo $wpau_youtube_channel->plugin_name . __( ' Settings', 'youtube-channel' ); ?>
		<span class="ver">v. <?php echo YTC_VER; ?></span>
	</h2>
	<?php
	$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general';

	// define available tabs
	$tabs = array(
		'general' => __( 'General', 'youtube-channel' ),
		'video'   => __( 'Video', 'youtube-channel' ),
		'content' => __( 'Content', 'youtube-channel' ),
		'link'    => __( 'Link to Channel', 'youtube-channel' ),
		'tools'   => __( 'Tools', 'youtube-channel' ),
		'help'    => __( 'Help', 'youtube-channel' ),
		'support' => __( 'Support', 'youtube-channel' ),
	);
	?>
	<h2 class="nav-tab-wrapper">
	<?php
	foreach ( $tabs as $tab_name => $tab_title ) {
		echo '<a href="?page=' . $wpau_youtube_channel->plugin_slug . '&tab=' . $tab_name . '" class="nav-tab' . ( ( $active_tab === $tab_name ) ? ' nav-tab-active' : '' ) . '">' . $tab_title . '</a>';
	}
	?>
	</h2>
	<?php
	if ( ! empty( $tabs[ $active_tab ] ) ) {

		if ( ! in_array( $active_tab, array( 'tools', 'help', 'support' ), true ) ) {
			// for all tabs except tools and help

			echo '<form method="post" action="options.php">';

			settings_fields( 'ytc_' . $active_tab );
			do_settings_sections( $wpau_youtube_channel->plugin_slug . '_' . $active_tab );

			submit_button();

			echo '</form>';

		} elseif ( 'tools' === $active_tab ) {
			include_once( YTC_DIR_TEMPLATES . '/settings-tools.php' );
		} elseif ( 'help' === $active_tab ) {
			include_once( YTC_DIR_TEMPLATES . '/settings-usage.php' );
			include_once( YTC_DIR_TEMPLATES . '/settings-usage-shortcode.php' );
		} elseif ( 'support' === $active_tab ) {
			include_once( YTC_DIR_TEMPLATES . '/settings-support.php' );
		} // $active_tab != 'tools|help|support'

	} // ! empty ( $tabs[$active_tab] )
	?>

</div>
