<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap" id="youtube_channel_settings">
	<h2>
		<?php echo esc_html( YTC_PLUGIN_NAME . ' ' . __( 'Settings', 'wpau-yt-channel' ) ); ?>
		<span class="ver">v. <?php echo esc_html( YTC_VER ); ?></span>
	</h2>
	<?php
	$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'general';

	// define available tabs
	$tabs = array(
		'general' => __( 'General', 'wpau-yt-channel' ),
		'video'   => __( 'Video', 'wpau-yt-channel' ),
		'content' => __( 'Content', 'wpau-yt-channel' ),
		'link'    => __( 'Link to Channel', 'wpau-yt-channel' ),
		'tools'   => __( 'Tools', 'wpau-yt-channel' ),
		'help'    => __( 'Help', 'wpau-yt-channel' ),
	);
	?>
	<h2 class="nav-tab-wrapper">
	<?php
	foreach ( $tabs as $tab_name => $tab_title ) {
		$tab_url   = '?page=' . YTC_PLUGIN_SLUG . '&tab=' . $tab_name;
		$tab_class = $active_tab === $tab_name ? ' nav-tab-active' : '';
		echo '<a href="' . esc_url( $tab_url ) . '" class="nav-tab ' . esc_attr( $tab_class ) . '">' . esc_html( $tab_title ) . '</a>';
	}
	?>
	</h2>
	<?php
	if ( ! empty( $tabs[ $active_tab ] ) ) {

		if ( ! in_array( $active_tab, array( 'tools', 'help' ), true ) ) {
			// for all tabs except tools and help

			echo '<form method="post" action="options.php">';

			settings_fields( sanitize_key( 'ytc_' . $active_tab ) );
			do_settings_sections( sanitize_key( YTC_PLUGIN_SLUG . '_' . $active_tab ) );

			submit_button();

			echo '</form>';

		} elseif ( 'tools' === $active_tab ) {
			include_once( YTC_DIR_TEMPLATES . '/settings-tools.php' );
		} elseif ( 'help' === $active_tab ) {
			include_once( YTC_DIR_TEMPLATES . '/settings-usage.php' );
			include_once( YTC_DIR_TEMPLATES . '/settings-usage-shortcode.php' );
		} // $active_tab != 'tools|help|support'

	} // ! empty ( $tabs[$active_tab] )
	?>

</div>
