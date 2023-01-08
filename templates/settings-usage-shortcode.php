<h3><?php _e( 'How to use shortcode', 'wpau-yt-channel' ); ?></h3>
<p>
	<?php
	printf(
		// translators: %s is replaced with plugin shortcode
		__(
			'You can use shortcode %s with options listed below (all options are optional if you have set defaults in global plugin settings).',
			'wpau-yt-channel'
		),
		'<code>[youtube_channel]</code>'
	);
	?>
</p>

<h3 class="nav-tab-wrapper subnav-tab-wrapper">
	<a href="#ytc-general-settings" class="nav-tab nav-tab-active"><?php _e( 'General Settings', 'wpau-yt-channel' ); ?></a>
	<a href="#ytc-video-settings" class="nav-tab"><?php _e( 'Video Settings', 'wpau-yt-channel' ); ?></a>
	<a href="#ytc-content-settings" class="nav-tab"><?php _e( 'Content Layout', 'wpau-yt-channel' ); ?></a>
	<a href="#ytc-link-settings" class="nav-tab"><?php _e( 'Link to Channel', 'wpau-yt-channel' ); ?></a>
</h3>

<div class="tab-content-ytc tab-content-active" id="ytc-general-settings">
<ul>
	<li><code>channel</code> <em>(string)</em> ID of preferred YouTube channel. Do not set full URL to channel, but just last part from URL - ID (name)</li>
	<li><code>vanity</code> <em>(string)</em> Vanity name.</li>
	<li><code>username</code> <em>(string)</em> Legacy YouTube username.</li>
	<li><code>playlist</code> <em>(string)</em> ID of preferred YouTube playlist.</li>
	<li><code>resource</code> <em>(int)</em> Resource to use for feed:
	<ul>
		<li>&bullet; <code>0</code> Channel (User Uploads)</li>
		<li>&bullet; <code>1</code> Favorites (for defined channel)</li>
		<li>&bullet; <code>2</code> Playlist</li>
		<li>&bullet; <code>3</code> Liked Videos (for defined channel)</li>
	</ul></li>
	<li><code>cache</code> <em>(int)</em> Period in seconds for caching feed. You can disable caching by setting this option to <code>0</code>, but if you have a lot of visits, consider at least short caching (couple minutes).</li>

	<li><code>fetch</code> <em>(int)</em> Number of videos that will be used as stack for random pick (min 2, max 50)</li>
	<li><code>skip</code> <em>(int)</em> Number of videos to skip (not applicable for embedable playlist and random pick (min 0, max 49, default 0) [<strong>Individual option, does not exists in global plugin settings!</strong>]</li>
	<li><code>num</code> <em>(int)</em> Number of videos to display per YTC block.</li>

	<li><code>random</code> <em>(bool)</em> Option to randomize videos on every page load. [<strong>Individual option, does not exists in global plugin settings!</strong>]</li>
</ul>
</div>

<div class="tab-content-ytc" id="ytc-video-settings">
<ul>
	<li><code>ratio</code> <em>(int)</em> Set preferred aspect ratio for thumbnail and video. You can use:
	<ul>
		<li>&bullet; <code>3</code> 16:9 widescreen (default)</li>
		<li>&bullet; <code>1</code> 4:3</li>
	</ul></li>
	<li><code>width</code> <em>(int)</em> Width of thumbnail and video in pixels.</li>
	<li><code>responsive</code> <em>(bool)</em> Distribute one full width video per row.</li>
	<li><code>display</code> <em>(string)</em> Object that will be used to represent video. We have couple predefined options:
	<ul>
		<li>&bullet; <code>thumbnail</code> Thumbnail will be used and video will be loaded in lightbox.</li>
		<li>&bullet; <code>iframe</code> HTML5 (iframe)</li>
		<li>&bullet; <code>iframe2</code> HTML5 (iframe) with asynchronous loading - recommended</li>
		<li>&bullet; <code>playlist</code> Embedded playlist</li>
	</ul></li>
	<li><code>thumb_quality</code> <em>(string)</em> Define image quality for thumbnail display mode. Default is <code>hqdefault</code>, available:
	<ul>
		<li>&bullet; <code>default</code> Default Quality (120x90px)</li>
		<li>&bullet; <code>mqdefault</code> Medium Quality (320x180px)</li>
		<li>&bullet; <code>hqdefault</code> High Quality (480x360px)</li>
		<li>&bullet; <code>sddefault</code> Standard Definition (640x480px)</li>
		<li>&bullet; <code>maxresdefault</code> Maximum Resolution (1280x720px)</li>
	</ul></li>

	<li><code>no_thumb_title</code> <em>(bool)</em> By default YouTube thumbnail will have tooltip with info about video title and date of publishing. By setting this option to <code>1</code> or <code>true</code> you can hide tooltip</li>
	<li><code>controls</code> <em>(bool)</em> Set this option to <code>1</code> or <code>true</code> to hide playback controls. To display controls set this option to <code>0</code> or <code>false</code>.</li>
	<li><code>autoplay</code> <em>(bool)</em> Enable autoplay of first video in YTC video stack by setting this option to <code>1</code> or <code>true</code></li>
	<li><code>mute</code> <em>(bool)</em> Set this option to <code>1</code> or <code>true</code> to mute videos set to autoplay on load</li>
	<li><code>norel</code> <em>(bool)</em> Set this option to <code>1</code> or <code>true</code> to hide related videos after finished playbak</li>
	<li><code>nobrand</code> <em>(bool)</em> Set this option to <code>1</code> or <code>true</code> to hide YouTube logo from playback control bar</li>
	<li><code>nolightbox</code> <em>(bool)</em> Set this option to <code>1</code> or <code>true</code> to disable lightbox and open thumbnail link in new tab/windows instead in lightbox</li>
</ul>
</div>

<div class="tab-content-ytc" id="ytc-content-settings">
<ul>
	<li><code>showtitle</code> <em>(string)</em> Set to:
		<ul>
			<li>&bullet; <code>none</code> to hide title</li>
			<li>&bullet; <code>above</code> to show video title above video/thumbnail</li>
			<li>&bullet; <code>below</code> to show video title below video/thumbnail</li>
			<li>&bullet; <code>inside</code> to show top aligned video title inside thumbnail; if <code>display</code> is not <code>thumbnail</code> then treat as <code>above</code><li>
			<li>&bullet; <code>inside_b</code> to show bottom aligned title inside thumbnail; if <code>display</code> is not <code>thumbnail</code> then treat as <code>below</code></li>
		</ul>
	</li>
	<li><code>linktitle</code> <em>(bool)</em> Set to <code>1</code> or <code>true</code> to link title to vlideo.</li>
	<li><code>showdesc</code> <em>(bool)</em> Set to <code>1</code> or <code>true</code> to show video description.</li>
	<li><code>desclen</code> <em>(int)</em> Set number of characters to cut down length of video description. Set to <code>0</code> to use full length description.</li>
	<li><code>noanno</code> <em>(bool)</em> Set to <code>1</code> or <code>true</code> to hide overlay video annotations (from embedded player)</li>
</ul>
</div>

<div class="tab-content-ytc" id="ytc-link-settings">
<ul>
	<li><code>goto_txt</code> <em>(string)</em></li>
	<li><code>popup</code> <em>(int)</em> Control where link to channel will be opened:
	<ul>
		<li>&bullet; <code>0</code> open link in same window</li>
		<li>&bullet; <code>1</code> open link in new window with JavaScript</li>
		<li>&bullet; <code>2</code> open link in new window with <code>target="_blank"</code> anchor attribute</li>
	</ul>
	</li>
	<li><code>link_to</code> <em>(string)</em> URL to link:
	<ul>
		<li>&bullet; <code>none</code> hide link (default)</li>
		<li>&bullet; <code>channel</code> Channel page</li>
		<li>&bullet; <code>handle</code> YouTube Handle URL</li>
		<li>&bullet; <code>vanity</code> <strong>DEPRECATED</strong> Vanity custom URL</li>
		<li>&bullet; <code>legacy</code> <strong>DEPRECATED</strong> Legacy username page</li>
	</ul>
	</li>
</ul>
</div>

<p>
	<?php
	printf(
		// translators: %1$s is replaced with translated word for General, %2$s for Video, %3$s for Content and %4$s for Link to Channel
		__(
			'Please note, you can omit all options listed above, and then will be used plugin defaults customized on tabs %1$s, %2$s, %3$s and %4$s.',
			'youtube-chanel'
		),
		__( 'General', 'wpau-yt-channel' ),
		__( 'Video', 'wpau-yt-channel' ),
		__( 'Content', 'wpau-yt-channel' ),
		__( 'Link to Channel', 'wpau-yt-channel' )
	);
	?>
</p>
<p>
	<?php
	printf(
		// translators: %s is replaced with plugin name
		__(
			'Important note: %s blocks inserted through widget have their own settings.',
			'wpau-yt-channel'
		),
		__( 'YouTube Channel', 'wpau-yt-channel' )
	);
	?>
</p>

<script>
jQuery(document).ready(function($){
	$('.subnav-tab-wrapper .nav-tab').on('click',function(ev){
		ev.preventDefault();
		var target = $(this).attr('href');
		$('.subnav-tab-wrapper .nav-tab').removeClass('nav-tab-active');
		$(this).addClass('nav-tab-active');
		$('.tab-content-ytc').removeClass('tab-content-active');
		$(target).addClass('tab-content-active');
	});
});
</script>
