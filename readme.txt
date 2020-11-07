=== YouTube Channel ===
Contributors: urkekg
Donate link: https://urosevic.net/wordpress/donate/?donate_for=youtube-channel
Tags: youtube, channel, playlist, widget, video
Requires at least: 4.9
Tested up to: 5.5
Stable tag: 3.0.12
Requires PHP: 5.6
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Show video thumbnails or playable blocks of recent videos from YouTube Playlist, Channel (User Uploads), Liked or Favourited videos.

== Description ==

When you need to display sidebar widget with latest or random video from some YouTube channel or playlist, use plugin **YouTube Channel**!

Simply insert widget to sidebar or shortcode to content, set Channel or Playlist ID, select resource to use and if you wish leave all other options on default. You will get latest video from chosen YouTube channel or playlist embedded on location of widget/shortcode, with optional link to channel at the bottom of the YTC block.

**IMPORTANT** YouTube Channel does not have Gutenberg Block, so to insert directly in page/post content use Shortcode Block or Classic Block!

If you like our plugin and you find it useful, please [write review and rate it](https://wordpress.org/support/view/plugin-reviews/youtube-channel).

For manual set of videos from YouTube check out [Easy YouTube Gallery](https://wordpress.org/plugins/easy-youtube-gallery/).

= Features =

* Display latest videos from YouTube Channel (resources are sorted in reverse chronological order based on the date they were created) or videos naturaly sorted from Favorited Videos, Liked Videos and Playlist
* Option to get random video from any of 4 resources
* Responsive (one full width video per row) or non responsive
* Preferred aspect ratio relative to width (16:9 and 4:3)
* Custom width for video embeded object (default is 306px)
* Enhanced Privacy (display mode *HTML5 (IFRAME) Asynchronous* (iframe2) does not support Enhanced Privacy due to YouTube API limitations)
* Choose to display video as thumbnail (default), HTML5 (iframe) or HTML5 Asynchronous (iframe2)
* Thumbnail mode opens video in lightbox
* Hide or show video title above/below video wrapped to HTML tag by your choice (h3, h4, h5, span or div)
* Custom feed caching timeout
* Optional video autoplay with optional muted audio
* (Optional) *TinyMCE button* on post/page edit (can be disabled on General plugin settings page), which open shortcode GUI generator to help you build shortcode
* Show customized link to channel/vanity/legacy username below videos
* Final look is highly customizable thanks to predefined classes for each element of YTC block!

= Requirements =

For fully functional plugin you need to have PHP 5.3 or newer! If you experience issues on older PHP, we can't help you as we don't have access to such old development platform.

If you have an old version of WordPress bundled with jQuery library older than v1.7, some aspects of plugin may not work, as we do not reinclude jQuery library.

= Styling =

You can use `style.css` from theme or other custom location to additionaly style/tweak look of YTC block. You can stick to classes:

* `.widget_youtube-channel` – class of whole widget (parent for widget title and YTC block)
* `.youtube_channel` – YTC block wrapper class. Additional classes are available:
  * `.default` – for non-responsive block
  * `.responsive` – when you have enabled responsive option
* `.ytc_title` – class for video title container above thumbnail/video object
  * `.ytc_title_above` - additional class for video title above video/thumbnail
  * `.ytc_title_below` - additional class for video title below video/thumbnail
  * `.ytc_title_inside` - additional class for video title printed inside of the thumbnail
  * `.ytc_title_inside_bottom` - additional class for bottom aligned video title printed inside of the thumbnail
* `.ytc_video_container` – class of container for single item, plus:
  * `.ytc_video_1`, `.ytc_video_2`, … – class of container for single item with ordering number of item in widget
  * `.ytc_video_first` – class of first container for single item
  * `.ytc_video_mid` – class of all other containers for single item
  * `.ytc_video_last` – class of last container for single item
  * `.ar16_9` – class for Aspect Ratio 16:9
  * `.ar4_3` – class for Aspect Ration 4:3
* `.ytc_thumb` – class of anchor for Thumbnail mode
* `.fluid-width-video-wrapper` – class for parent element of IFRAME for enabled responsive
* `.ytc_description` – class for video description text below thumbnail/video object
* `.ytc_link` – class of container for link to channel

= Known Issues =

* Video title and description for embedded playlist mode does not work.
* Removing YouTube logo from playback control bar does not work for all videos
* Async HTML5 video does not work for 2nd same video on same page (two YTC blocks set to Async HTML5)

If WordFence or other malware scan tool detect YouTube Channel fule youtube-channel.php as potential risk because `base64_encode()` and `base64_decode()` functions, remember that we use this two functions to store and restore JSON feeds to transient cache, so potential detection is false positive.

= Credits =

* For playing videos in lightbox we use enhanced [Magnific Popup](http://dimsemenov.com/plugins/magnific-popup/).
* Initial textdomain adds done by [dimadin](http://wordpress.org/extend/plugins/profile/dimadin).
* [Federico Bozo](http://corchoweb.com/) reminded me to fix z-index problem
* Czech localization by [Ladislav Drábek](http://zholesova.cz)
* Spanish localization by [Diego Riaño](http://Digital03.net)
* Danish localisation by [GSAdev v. Georg Adamsen](http://www.gsadev.dk)

= Shortcode =

Along to Widget, you can add YouTube Channel block inline by using shortcode `[youtube_channel]`. Default plugin parameters will be used for shortcode, but you can customize all parameters per shortcode.

**General Settings**

* `class` (string) Set custom class if you wish to target special styling for specific YTC block
* `channel` (string) ID of preferred YouTube channel. Do not set full URL to channel, but just last part from URL - ID (name)
* `vanity` (string) part after www.youtube.com/c/ from [Custom URL](https://support.google.com/youtube/answer/2657968?hl=en)
* `username` (string) Optional legacy YouTube username.
* `playlist` (string) ID of preferred YouTube playlist.
* `resource` (int) Resource to use for feed:
  * `0` Channel (User uploads)
  * `1` Favorites (for defined channel)
  * `2` Playlist
  * `3` Liked Videos
* `cache` (int) Period in seconds for caching feed. You can disable caching by setting this option to 0, but if you have a lot of visits, consider at least short caching (couple minutes).
* `fetch` (int) Number of videos that will be used as stack for random pick (min 2, max 50)
* `num` (int) Number of videos to display per YTC block.
* `random` (bool) Option to randomize videos on every page load.

**Video Settings**

* `ratio` (int) Set preferred aspect ratio for thumbnail and video. You can use:
  * `3` 16:9 (widescreen)
  * `1` 4:3
* `responsive` (bool) Distribute one full width video per row.
* `width` (int) Width of thumbnail and video in pixels.
* `display` (string) Object that will be used to represent video. We have couple predefined options:
  * `thumbnail` Thumbnail will be used and video will be loaded in lightbox. (default)
  * `iframe` HTML5 (iframe)
  * `iframe2` HTML5 (iframe) with asynchronous loading - recommended
  * `playlist` Embedded playlist (same behaviour as old function `only_pl`)
* `thumb_quality` (string) Define image quality for thumbnail display mode. Default is `hqdefault`, available:
  * `default` Default Quality (120x90px)
  * `mqdefault` Medium Quality (320x180px)
  * `hqdefault` High Quality (480x360px)
  * `sddefault` Standard Definition (640x480px)
  * `maxresdefault` Maximum Resolution (1280x720px)
* `no_thumb_title` (bool) By default YouTube thumbnail will have tooltip with info about video title and date of publishing. By setting this option to 1 or true you can hide tooltip
* `themelight` (bool) By default YouTube have dark play controls theme. By setting this option to 1 or true you can get light theme in player (HTML5 and Flash)
* `controls` (bool) Set this option to 1 or true to hide playback controls.
* `autoplay` (bool) Enable autoplay of first video in YTC video stack by setting this option to 1 or true
* `mute` (bool) Set this option to 1 or true to mute videos set to autoplay on load
* `norel` (bool) Set this option to 1 or true to hide related videos after finished playbak
* `nobrand` (bool) Set this option to 1 or true to hide YouTube logo from playback control bar
* *NEW* `nolightbox` (bool) Set this option to 1 or true to prevent YTC block with thumbnail to open in lightbox. If you have other plugin that trigger youtube links for lightbox, that one will steal links from this YTC block.
* *NEW* `target` (string) If you enable nolightbox for specific YTC block, you can force opening of thumbnail links in new tab/window if you set this shortcode option to `_blank` like `target="_blank"`.

**Content Layout**

* `showtitle` (string):
  * `none` - Hide title
  * `above` - Display title above video/thumbnail
  * `below` - Display title below video/thumbnail
  * `inside` - Display top aligned title inside thumbnail; if `display` is not `thumbnail` then treat as `above`
  * `inside_b` - Display bottom aligned title inside thumbnail; if `display` is not `thumbnail` then treat as `below`
* `titletag` - Video title HTML tag to wrap title (H3, H4, H5, div, span, strong, etc)
* `showdesc` (bool) Set to 1 or true to show video description.
* `desclen` (int) Set number of characters to cut down length of video description. Set to 0 to use full length description.
* `noinfo` (bool) Set to 1 or true to hide overlay video infos (from embedded player)
* `noanno` (bool) Set to 1 or true to hide overlay video annotations (from embedded player)

**Link to Channel**

* `goto_txt` (string)
* `popup` (int) Control where link to channel will be opened:
  * `0` open link in same window
  * `1` open link in new window with JavaScript
  * `2` open link in new window with target="_blank" anchor attribute
* `link_to` (string) URL to link:
  * `none` Hide link (defult)
  * `vanity` Vanity custom URL
  * `channel` Channel page
  * `legacy` Legacy username page

*Please note, to enhance plugin functionality, we can change some shortcode parameters in future.*

== Filter hooks ==

You can modify final output of video block by hooking to filter `ytc_print_video`.

Four parameters are provided:
* `video_content` - HTML of original video block
* `item` - YouTube video object which contains:
  * `snippet->publishedAt` - date of publishing YouTube video
  * `shippet->title` - YouTube video title
  * `shippet->description` - YouTube video description
  * `snippet->resourceId->videoId` -> YouTube video ID
* `instance` - Current YouTUbe Channel Block parameters, including global settings:
  * `vanity`
  * `channel`
  * `username`
  * `playlist`
  * `resource`
  * `cache`
  * `fetch`
  * `num`
  * `skip`
  * `privacy`
  * `ratio`
  * `width`
  * `responsive`
  * `display`
  * `fullscreen`
  * `controls`
  * `autoplay`
  * `autoplay_mute`
  * `norel`
  * `playsinline`
  * `showtitle`
  * `titletag`
  * `showdesc`
  * `desclen`
  * `modestbranding`
  * `hideanno`
  * `goto_txt`
  * `popup_goto`
  * `link_to`
  * `tinymce`
  * `nolightbox`
  * `apikey`
  * `thumb_quality`
  * `timeout`
  * `random`
  * `no_thumb_title`
  * `class`
  * `target`
* `y` - order number of video (`1` for first, `2` for second, etc)

Example:
`add_filter( 'ytc_print_video', 'customized_ytc_print_video', 10, 4 );
function customized_ytc_print_video( $video_block, $item, $instance, $y ) {
  // Do whatever you wish to do
  // ...
  return $video_block;
}`

== Installation ==

You can use the built in installer and upgrader, or you can install the plugin manually.

1. You can either use the automatic plugin installer or your FTP program to upload unziped `youtube-channel` directory to your `wp-content/plugins` directory.
1. Activate the plugin through the `Plugins` menu in WordPress
1. Add YouTube Channel widget to sidebar
1. Set Channel ID and save changes

If you have to upgrade manually simply repeat the installation steps and re-enable the plugin.

= YouTube Data API Key =
Main difference since v2.x branch is that now we use [YouTube Data API v3](https://developers.google.com/youtube/v3/) so to make plugin to work, you'll need to generate YouTube Data API Key and insert it to General plugin settings.

Learn more about [Obtaining authorization credentials](https://developers.google.com/youtube/registering_an_application) and for detailed instructions how to generate your own API Key watch video below.

[youtube http://www.youtube.com/watch?v=8NlXV77QO8U]

1. Visit [Google Developers Console](https://console.developers.google.com/project).
1. If you don't have any project, create new one. Name it so you can recognize it (for example **My WordPress Website**).
1. Select your new project and from LHS sidebar expand group **APIs & auth**, then select item **APIs**.
1. Locate and click **YouTube Data API** under **YouTube API** section.
1. Click **Enable API** button.
1. When you get enabled YouTube Data API in your project, click **Credentials** item from LHS menu **APIs & auth**.
1. Click **Create New Key** button and select **Server Key**.
1. Leave empty or enter IP of your website. If you get message **Oops, something went wrong.** make sure you set proper IP, or do not set any restriction.
1. Click **Create** button.
1. Copy newly created **API Key**.

When you generate your own YouTube Data API Key, from your **Dashboard** go to **Settings** -> **YouTube Channel** -> **General** and paster key in to field **YouTube Data API Key**.

Also, do not forget to check and update **Channel ID** in plugin's General settings, Widgets and/or shortcodes.
You can get **Channel ID** from page [Account Advanced](https://www.youtube.com/account_advanced) while you're loagged in to your YouTube account.

[youtube http://www.youtube.com/watch?v=wOqkfkNhOUE]

[youtube http://www.youtube.com/watch?v=qaLqWi4-9jI]

[youtube http://www.youtube.com/watch?v=u5TnGBVoG9c]

== Frequently Asked Questions ==

Please note, latest FAQ you can find [on our website](http://urosevic.net/wordpress/plugins/youtube-channel/faq/). This section on WordPress.org has been updated only on plugin version release, so questions answered between releases are not visible here.

= How to get that YouTube Data API Key? =

Please folllow [Installation](https://wordpress.org/plugins/youtube-channel/installation/) instructions.

= Plugin does not work with premium theme or clash with other premium plugin =

As a developer of free plugin YouTube Channel, I can not afford to purchase and renew licence for premium themes and plugins.

If you experiencing issue on premium theme or see clash with premium plugin, I can help you only if you help me. Ask theme/plugin developer for approval to provide a package of premium theme/plugin to me, so I can install it on my local development domain youtube-channel.test and debug issue in YouTube Channel.

Upload premium theme or plugin installation package to Dropbox or Google Drive, and provide share link for package by [contact form](https://urosevic.net/wordpress/contact/?subject=YouTube%20Channel).

= I set everything correct but receiveing 'Oops, something went wrong' message =

Login as user with Administrator role and you'll see YTC ERROR message with explanation what's wrong. Examples:

> **YTC ERROR:** Please check did you set proper Channel ID. You set to show videos from channel, but YouTube does not recognize MyCoolLegacyName as existing and public channel.

Do exactly what message says - check and correct Channel ID in default settings/widget/shortcode.

> **YTC ERROR** Check YouTube Data API Key restrictions, empty cache if enabled by appending in browser address bar parameter ?ytc_force_recache=1

1. Try to remove restrictions by referer or IP in your **YouTube Data API Key** and refresh page after couple minutes.
1. If that does not help, please try to create new API Key for Server w/o restrictions (not to regenerate existing one).

= How to use Google APIs Explorer to verify YouTube feeds? =

If there is no `YTC ERROR` code in HTML source, visit [Google API Explorer](https://developers.google.com/apis-explorer/#p/youtube/v3/youtube.playlistItems.list?part=snippet&maxResults=5&playlistId=) and append to the end of URL one of resource ID’s based on your Channel ID:

* for videos from channel replace **UC** with **UU** in Channel ID (so *UCRPqmcpGcJ_gFtTmN_a4aVA* becomes *UURPqmcpGcJ_gFtTmN_a4aVA*)
* for videos from Favourited videos replace **UC** with **FL** (so *UCRPqmcpGcJ_gFtTmN_a4aVA* becomes *FLRPqmcpGcJ_gFtTmN_a4aVA*)
* for videos from Liked Videos replace **UC** with **LL** (so *UCRPqmcpGcJ_gFtTmN_a4aVA* becomes *LLRPqmcpGcJ_gFtTmN_a4aVA*)
* for videos from Playlist simply use Playlist ID (like *PLEC850BE962234400* or *RDMMjUe8uoKdHao*)

Note that all four resources are *playlists* (including uploads to channel), so append mentioned ID to field **playlistId** (not to **id**), and click **Execute** button at the bottom of that page.

1. If you receive some error in results, tune parameters in APIs Explorer.
1. If there is no error but you do not get any video in results, and you are sure that there is public videos in selected resource – contact Google Support.
1. If there are video results but not displayed with YouTube Channel plugin – check topic [Read before you post support question or report bug](https://wordpress.org/support/topic/ytc3-read-before-you-post-support-question-or-report-bug) and then [start your own support topic](https://wordpress.org/support/plugin/youtube-channel#postform).

= What this YTC ERROR/HTTP Error means? =

You will be able to reproduce HTTP Error w/o WordPress if you have SSH access to server where you host your website. Simply login to shell and run following command:

`curl https://www.googleapis.com/youtube/v3/playlistItems`

If you do not receive response like one below, then you'll receive HTTP Error from curl command.

`{
 "error": {
  "errors": [
   {
    "domain": "global",
    "reason": "required",
    "message": "Required parameter: part",
    "locationType": "parameter",
    "location": "part"
   }
  ],
  "code": 400,
  "message": "Required parameter: part"
 }
}`

Known HTTP Errors:

**error:0D0890A1:asn1 encoding routines:ASN1_verify:unknown message digest algorithm**

The remote connection software you are using on your server might be compiled with a very old version of OpenSSL that does not take certificates signed with sha256-With-RSA-Encryption into account. It requires at least OpenSSL 0.9.8o for a total management of SHA256.

Please contact your server admin or hosting provider about this issue.

**Problem with the SSL CA cert (path? access rights?)**

This is a server related issue (not related to YouTube Channel or WordPress).

To resolve the issue, you’ll need to restart Apache (or nginx). If that doesn’t fix the problem, you’ll need to restart your entire server. Or simply contact server support.

= Where to find correct Channel ID and/or Vanity custom Name? =

Login to your YouTube account and visit page [Account Advanced](https://www.youtube.com/account_advanced).

You'll find your **Vanity Name** as "Your custom URL" in **Channel settins** section on that page. For YTC plugin use only part **after www.youtube.com/c/**, not full URL.

**Channel ID** is **YouTube Channel ID** value composed of mixed characters starting with **UC**.

= Where to find ID for Favourites and/or Liked Videos? =

You will not need that two ID's, in general. But, if you really wish to know, these two ID's are produced from your **Channel ID**. Channel ID start with **UC** (like `UCRPqmcpGcJ_gFtTmN_a4aVA`)

* For Favourites ID replace **UC** with **FL** (so you get `FLRPqmcpGcJ_gFtTmN_a4aVA`)
* For Liked Videos ID replace **UC** with **LL** (so you get `LLRPqmcpGcJ_gFtTmN_a4aVA`)

= What is Vanity custom URL? =

Check out [Channel custom URL](https://support.google.com/youtube/answer/2657968?ref_topic=3024172&hl=en-GB) article.

= Where to find Playlist ID? =

Playlist ID can be manually extracted from YouTube playlist URL. Just look for string after `&list=` parameter in URL which can contain lowercase and uppercase letters, dash and underscore characters. Regular playlists starts with uppercase letters **PL** (like *PLEC850BE962234400*), but Playlist ID for YouTube mixes start with uppercase **RD** (like *RDCfMMlT8Lyns*).

= Video titles missing after plugin update =

If you inserted videos by shortcode previous v3.0.8 then you probably have set parameter `showtitle=1`.

Since version v3.0.8 of plugin this parameter has been changed to accept values `none`, `above` and `below`, depending do you wish to hide video title, or to display them above/below video thumbnail.

So, you can:
1. Remove `showtitle` parameter from shortcode and set **Show title** global plugin option on **Content** tab, or
1. Change parameter `showtitle` to `above` or `below`.

= How to force embeding 320p video with better audio quality? =

YouTube provide 320p videos if height of embeded video is set to 320 or more. If you use small YTC video size, 240p will be loaded instead. So, you could not force 720p in tiny YTC.

= I enabled option `Hide YT Logo` but YouTube logo is still visible =

Modestbranding option does not work for all videos, so a lot of videos will still have YouTube logo in control bar. I recommend to enable option `Hide player controls` instead.

Also, even when hidding logo works for your video, on hover or when video is paused in upper right corner will be displayed YouTube link/logo. [Read more here](https://developers.google.com/youtube/player_parameters#modestbranding)

= How I can achieve 'wall' layout with one featured thumbnail? =

You can try with shortcode combination:
`[youtube_channel num=7 responsive=1 class=ytc_wall_1-6 resource=2 random=1]`

and custom CSS code added to theme style.css or similar customization:
`.youtube_channel.ytc_wall_1-6 .ytc_video_container {
    padding: 5px;
    box-sizing: border-box;
    max-width: 33.333%;
}
.youtube_channel.ytc_wall_1-6 .ytc_video_container.ytc_video_1 {
    max-width: 100%;
}
@media screen and (max-width: 768px) {
    .youtube_channel.ytc_wall_1-6 .ytc_video_container:not(.ytc_video_1) {
        max-width: 50%;
    }
}
@media screen and (max-width: 480px) {
    .youtube_channel.ytc_wall_1-6 .ytc_video_container:not(.ytc_video_1) {
        max-width: 100%;
    }
}`

So, we display thumbnails for 7 random videos from default (global) playlist, and distribute small thumbnails to 3 columns on wide screens, 2 columns under 768px and single thumbnail per row under 480px.

= How I can add pagination (for example to Dynamic Wall view)? =

Unfortunately, YouTube Channel does not support pagination, so you’ll get only defined number of YouTube items in block in single view, no matter did you choose thumbnail or HTML5 Embed as mode.

= How to reduce size of/remove thumbnail Play button? =

Since v3.0.8 we changed how thumnail Play button is embedded. If you wish to reduce button size, tune transform CSS property in theme's style.css, like this:
`.youtube_channel .ytc_thumb>span:before {
  transform: scale(.65);
}`

If you wish to remove (hide) play button from thumbnails, simply set display property to none, like this:

`.youtube_channel .ytc_thumb>span:before {
  display: none !important;
}`

= Your plugin does not support *THIS* or *THAT* =

If you really need that missing feature ASAP, feel free to [contact me](urosevic.net/wordpress/contact/). Select *Subject* option "Quote For New Feature in YouTube Channel", provide detailed explanation of feature you need, also provide some example if there is such, and I'll send you price for implementation.

If you don't wish to pay for enhancements (then you don't care would that be implemented in a week, month, year or so), then send new [Support topic](https://wordpress.org/support/plugin/youtube-channel) with *Topic title* in format **[Feature Request] ...**

== Changelog ==

= 3.0.12 (20201107) =
* Add: link video title to YouTube playback page opened in new tab/window
* Cleanup: remove deprecated parameters `theme` (`themelight`), `showinfo` (`noinfo`, `hideinfo`)
* Improve: code for widget
* Add: widget parameter `skip`
* (20201014) Add: shortcode parameter `skip` to skip requested number of items

= 3.0.11.8 (20200810) =
* Tested: WordPress 5.5-RC2-48768 and PHP 7.4.1
* (20190719) Fix: referrer is wrong for protected API keys (thanks to @hmmux)

= 3.0.11.7 (20180906) =
* Add: Global option `sslverify` to disable SSL Verification
* Add: Global option `js_ev_listener` to enable Event Listener DOMContentLoaded
* (20180826) Add: Override video block template by 3rd party theme or plugin with filter `ytc_print_video`
* Add: Customizable timeout for wp_remote_get()
* Improvement: Disable LastPass altering settings fields

= 3.0.11.6 (20180826) =
* Add compatibility with async/defer optimization (thanks to @lordbass)

= 3.0.11.5 (20180721) =
* Add: Missing `titletag` parameter for shortcode, shortcode TinyMCE wizard and widget
* Fix: Missing video title for `thubmbnail` display with `above` or `below` positioning (thanks @nimeldk)

= 3.0.11.4 (20180622) =
* Improvement: add `showtitle` options `inside` and `inside_b`.
* (20180213) Update: section descriptions on plugin settings.

= 3.0.11.3 (20171001) =
* Fix: Default values in dropdown lists does not preselect in TinyMCE shortcode selector
* Add: new option for thumbnail quality for TinyMCE shortcode selector

= 3.0.11.2 (20171001) =
* (20171001) Fix: Undefined index: option_page in youtube-channel/inc/settings.php on line 1006
* Add: Support for custom thumbnail quality
* (20170716) Add: native error message from Google for cases not covered by common errors (like `accessNotConfigured`)

= 3.0.11.1 (20170530) =
* Fix: cut description in the middle of multy-byte characters (reported by @funfrog)
* (20170509) Fix: undefined variable `nolightbox`
* Fix: add `nolightbox` shortcode parameter and fix typos on Help section

= 3.0.11 (20170424) =
* Fix: added all 3 parameters to `widget_title` filter (reported by @squarestar)
* (20170301) Add: New shortcode options `nolightbox` and `target`, to make available opening thumbnail anchors in new tab/window (requested by @bakercreative)

== Upgrade Notice ==

= 3.0.11.7 =
There is new option to disable SSL verification on host which have a problem to verify Google SSL keys

== Screenshots ==

1. YouTube Channel default plugin settings (General tab)
2. YouTube Channel customized widget settings
3. YouTube Channel in WP Customizer and Dynamic Wall layout
4. How to add YouTube Data API Key to YouTube Channel
5. TinyMCE form to easy configure YouTube Channel shortcode for content