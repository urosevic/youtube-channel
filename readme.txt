=== YouTube Channel ===
Contributors: urkekg, techwebux
Donate link: https://urosevic.net/wordpress/donate/?donate_for=youtube-channel
Tags: youtube, channel, playlist, widget, video
Requires at least: 4.9
Tested up to: 5.6.2
Stable tag: 3.0.12.1
Requires PHP: 5.6
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Show video thumbnails or playable video block of recent YouTube Playlist, Channel (User Uploads), Liked or Favourited videos.

== Description ==

Add **YouTube Channel** widget to the widget area or shortcode to post/page content, set **Channel ID** or **Playlist ID**, chose resource to use and keep defaults for all other options. And voila! You will get the latest video from chosen YouTube channel or playlist.

**IMPORTANT** YouTube Channel does not support **Live Streams** and does not have Gutenberg Block. Use **Shortcode Block** or **Classic Block** to insert shortcode to page/post content.

If you like our plugin and find it useful, please [write a review and rate it](https://wordpress.org/support/view/plugin-reviews/youtube-channel).

For a manually picked set of videos from YouTube, check out [Easy YouTube Gallery](https://wordpress.org/plugins/easy-youtube-gallery/).

= Features =

* Show latest videos from YouTube Channel (ordered in reverse chronological order related to the creation date), or from Favorited Videos, Liked Videos and Playlist
* Option to get a random video from any of 4 resources
* Responsive (one full-width video per row) or non-responsive
* Preferred aspect ratio relative to width (16:9 and 4:3)
* Custom width for video embeded object (default is 306px)
* Three modes to display video: **thumbnail** (`default`), **HTML5** (`iframe`) and **HTML5 Asynchronous** (`iframe2`)
* Thumbnail mode opens the video in lightbox
* Enhanced Privacy - please note that display mode **HTML5 (IFRAME) Asynchronous** (shortcode parameter `iframe2`) does not support Enhanced Privacy due to YouTube API limitations
* Hide or show video title above/below video wrapped to HTML tag by your choice (h3, h4, h5, span or div)
* Custom feed caching timeout
* Optional video autoplay with optional muted audio
* (Optional) *TinyMCE button* on post/page edit (can be disabled on General plugin settings page), which open a shortcode GUI generator to help you build a shortcode
* Show link to channel/vanity/legacy username below videos
* Final look is highly customisable thanks to classes for each element of YTC block!

= Requirements =

For a fully functional plugin, PHP 5.6 or newer has required! If you use older PHP, we highly recommend you request from your developer, server support or hosting company to update PHP to a secure version.

= Styling =

Use Customizer, `style.css` from the child theme or [Head & Footer Code](https://wordpress.org/plugins/head-footer-code/) plugin to custom style and tweak the look and feel of the YouTube Channel blocks. You can utilise the following classes:

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

* Video title and description for embedded playlist mode do not work.
* Removing the YouTube logo from the playback control bar does not work for all videos.
* Async HTML5 video does not work for 2nd same video on the same page (two YTC blocks set to Async HTML5)

If WordFence or other malware scan tool detect YouTube Channel file `youtube-channel.php` as a potential risk because `base64_encode()` and `base64_decode()` functions, remember that we use this two functions to store and restore JSON feeds to transient cache, so potential detection is false positive.

= Credits =

* For playing videos in lightbox we use enhanced [Magnific Popup](https://github.com/dimsemenov/Magnific-Popup).
* Initial textdomain adds done by [dimadin](https://profiles.wordpress.org/dimadin/).
* Federico Bozo reminded me to fix z-index problem

= Shortcode =

Along with Widget, you can add YouTube Channel block inline by using shortcode `[youtube_channel]`. Default plugin parameters will be used for shortcode, but you can customize all parameters per shortcode.

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

Use **Plugins / Add New** in WordPress or install the plugin manually:

1. With your FTP program (eg. FileZilla) upload content of unzipped `youtube-channel` directory to the `wp-content/plugins/` directory on the server.
1. Activate the plugin on the **Plugins / Installed Plugins** page in WordPress
1. Enter **YouTube Data API Key** on **Settings / YouTube Channel** page.
1. Add **YouTube Channel** widget to the sidebar
1. Set **Channel ID** in plugin settings, widget or shortcode

If you have to upgrade manually, repeat the installation steps and re-enable the plugin.

= YouTube Data API Key =
To make YouTube Channel work you have to generate [YouTube Data API Key v3](https://developers.google.com/youtube/v3/getting-started) and insert it to General plugin settings.

Learn more about [Obtaining authorization credentials](https://developers.google.com/youtube/registering_an_application) and for detailed instructions on how to generate your own API Key follow instructions below.

1. Visit [Google Cloud Platform](https://console.cloud.google.com/cloud-resource-manager).
1. If you don't have any project, create a new one (click on **CREATE PROJECT**). Set the **Project name** so you can recognize it later (for example **My WordPress Website**). Then click the **CREATE** button and wait until Google create the project.
1. On the popup click **SELECT PROJECT** and in the left-hand side menu go to **APIs & Services** -> **Dashboard**, then click **ENABLE APIS AND SERVICES**
1. Search for **YouTube Data API v3** and click on it, then click the **ENABLE** button.
1. Click the **Credentials** item from the left-hand side menu.
1. From the **CREATE CREDENTIALS** drop-down menu select **API key**
1. As soon as the API key gets created, click the **RESTRICT KEY** button
1. Define recognizable key **Name** (eg. YouTube API key for my website)
1. Choose **Application restrictions** -> **IP addresses (web servers, cron jobs, etc.)** -> click **ADD AN ITEM** -> enter server IP (make sure you enter proper IP or you'll get **Oops, something went wrong.** error message).
1. Choose **API restrictions** -> **Restrict key** -> click **Select APIs** -> chose option **YouTube Data API v3**
1. When you finish, **SAVE** changes
1. On **API keys** table click **Copy API key** icon
1. In WordPress go to **Settings** -> **YouTube Channel** -> **General** and paster API key in to field **YouTube Data API Key**.

Also, do not forget to check and update **Channel ID** in the plugin's General settings, Widgets and/or shortcodes.
You can get **Channel ID** from the page [Account Advanced](https://www.youtube.com/account_advanced) while you're logged in to your YouTube account.

[youtube http://www.youtube.com/watch?v=wOqkfkNhOUE]

[youtube http://www.youtube.com/watch?v=qaLqWi4-9jI]

[youtube http://www.youtube.com/watch?v=u5TnGBVoG9c]

== Frequently Asked Questions ==

= How to get that YouTube Data API Key? =

Please folllow [Installation](https://wordpress.org/plugins/youtube-channel/#installation) instructions.

= The plugin does not work with a premium theme or clash with another premium plugin =

We cannot afford licenses for various premium themes and plugins to keep the fee plugin YouTube Channel working with each of them.

If YouTube Channel clash with a premium theme or plugin, we can help you only if you help us. Ask theme/plugin author for approval to provide a package to us for debugging on local development domain `youtube-channel.wp`.

Upload the installation package to your cloud drive (Dropbox, One Drive, Google Drive, iCloud, WeTransfer or other) and provide a share link to us by [contact form](https://urosevic.net/wordpress/contact/?subject=YouTube%20Channel).

= I set everything correct but receiving 'Oops, something went wrong' message =

As a logged-in administrator, you will see an error explanation. Known issues:

> **YTC ERROR:** Please check did you set the proper Channel ID. You choose to show videos from the channel, but YouTube does not recognize MyCoolLegacyName as an existing or public channel.

Do what the message says - check and correct Channel ID in default settings/widget/shortcode.

> **YTC ERROR** Check YouTube Data API Key restrictions, empty cache if enabled by appending in the browser address bar parameter ?ytc_force_recache=1

1. Try to remove restrictions by referer or IP in your **YouTube Data API Key** and refresh the page after a couple of minutes.
1. If that does not help, please try to create a new API Key for Server w/o restrictions (not to regenerate the existing one).

= How to use Google APIs Explorer to verify YouTube feeds? =

If there is no `YTC ERROR` code in HTML source, visit [Google API Explorer](https://developers.google.com/apis-explorer/#p/youtube/v3/youtube.playlistItems.list?part=snippet&maxResults=5&playlistId=) and append to the end of URL one of resource ID’s based on your Channel ID:

* for videos from channel replace **UC** with **UU** in Channel ID (so *UCRPqmcpGcJ_gFtTmN_a4aVA* becomes *UURPqmcpGcJ_gFtTmN_a4aVA*)
* for videos from Favourited videos replace **UC** with **FL** (so *UCRPqmcpGcJ_gFtTmN_a4aVA* becomes *FLRPqmcpGcJ_gFtTmN_a4aVA*)
* for videos from Liked Videos replace **UC** with **LL** (so *UCRPqmcpGcJ_gFtTmN_a4aVA* becomes *LLRPqmcpGcJ_gFtTmN_a4aVA*)
* for videos from Playlist simply use Playlist ID (like *PLEC850BE962234400* or *RDMMjUe8uoKdHao*)

Note that all four resources are *playlists* (including uploads to channel), so append mentioned ID to field **playlistId** (not to **id**), and click **Execute** button at the bottom of that page.

1. If you receive some error in results, tune parameters in APIs Explorer.
1. If there is no error while the response is empty, and you are sure that there are public videos in selected resource – contact Google Support.
1. If there are video results but not displayed with YouTube Channel plugin – check topic [Read before you post support question or report a bug](https://wordpress.org/support/topic/ytc3-read-before-you-post-support-question-or-report-bug) and then [start new support topic](https://wordpress.org/support/plugin/youtube-channel/#new-topic-0).

= What this YTC ERROR/HTTP Error means? =

You will be able to reproduce HTTP Error w/o WordPress if you have SSH access to the server hosting your website. Log in to shell and run the following command (or ask your developer to do so):

`curl https://www.googleapis.com/youtube/v3/playlistItems`

You will get an HTTP Error from the curl command or response like the one below.

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

Server tool curl has built with an older version of OpenSSL that does not support certificates signed with sha256-With-RSA-Encryption. It requires at least OpenSSL 0.9.8o for the total management of SHA256.

Please contact your server administrator or hosting provider to help you with this issue.

**Problem with the SSL CA cert (path? access rights?)**

Yet another server issue, not related to YouTube Channel nor WordPress.

Please restart Apache (or Nginx) server. If that does not fix the issue, restart the entire server. Or contact server support to help.

= Where to find correct Channel ID and Vanity custom Name? =

Login to your YouTube account and visit page [Account Advanced](https://www.youtube.com/account_advanced).

**Vanity Name** is "Your custom URL" in the **Channel settings** section on the page above. Use part **after www.youtube.com/c/**, not the full URL for the YouTube Channel plugin.

**Channel ID** is **YouTube Channel ID** value composed of mixed characters starting with **UC**.

= Where I can find an ID for Favourites or Liked Videos? =

These two IDs' are composed of your **Channel ID**. Channel ID start with **UC** (like `UCRPqmcpGcJ_gFtTmN_a4aVA`)

* For Favourites ID replace **UC** with **FL** (so you get `FLRPqmcpGcJ_gFtTmN_a4aVA`)
* For Liked Videos ID replace **UC** with **LL** (so you get `LLRPqmcpGcJ_gFtTmN_a4aVA`)

= What is Vanity custom URL? =

Check out [Channel custom URL](https://support.google.com/youtube/answer/2657968?ref_topic=3024172&hl=en-GB) article.

= Where to find Playlist ID? =

Manually extract Playlist ID from YouTube playlist URL. Find string after `&list=` parameter in URL.
Playlist ID can contain lowercase and uppercase letters, dash and underscore characters.
Regular playlists starts with uppercase letters **PL** (like *PLEC850BE962234400*), while Playlist ID for YouTube mixes start with uppercase **RD** (eg. *RDCfMMlT8Lyns*).

= Video titles missing after plugin update =

If you entered shortcode before version 3.0.8, you could have set the parameter `showtitle=1`.

This parameter now accepts different values: `none`, `above` and `below`, depending do you wish to hide the video title or to show it above/below the video thumbnail.

You can do the following:
1. Remove the `showtitle` parameter from the shortcode and set **Show title** global plugin option on **Content** tab, or
1. Change parameter `showtitle` to `above` or `below`.

= How to force embedding 320p video with better audio quality? =

YouTube provide 320p videos if the height of the embedded video is 320 or more. If you use a small YTC video size, 240p will be loaded instead. It's not possible to force 720p in tiny YTC.

= I enabled option `Hide YT Logo` and YouTube logo is still visible =

The modestbranding option does not work for all videos. A lot of videos still have the YouTube logo in the control bar. We recommend enabling the option `Hide player controls` instead.

The logo appears in the upper right corner on hover or when the video pauses. [Read more here](https://developers.google.com/youtube/player_parameters#modestbranding)

= How I can achieve a 'wall' layout with one featured thumbnail? =

Start with following shortcode combination:

`[youtube_channel num=7 responsive=1 class=ytc_wall_1-6 resource=2 random=1]`

Then add custom CSS code to Customizer, or child theme **style.css** or to [Head & Footer Code](https://wordpress.org/plugins/head-footer-code/) plugin:

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

That will show thumbnails for seven random videos from the default playlist defined in plugin settings: one full-width thumbnail and six remaining thumbnails distributed to 3 columns on wide screens, two columns under 768px and a single thumbnail per row under 480px.

= How I can add pagination (to Dynamic Wall view)? =

YouTube Channel does not support pagination.

= How to reduce the size thumbnail Play button or remove it? =

Resize thumbnail play button by adding following custom CSS to Customizer, or child theme **style.css** or to [Head & Footer Code](https://wordpress.org/plugins/head-footer-code/) plugin:

`.youtube_channel .ytc_thumb>span:before {
  transform: scale(.65);
}`

To remove (hide) thumbnail play button, use following custom CSS:

`.youtube_channel .ytc_thumb>span:before {
  display: none !important;
}`

= Your plugin does not support *THIS* or *THAT* =

Feel free to submit a feature request by creating a new topic on [Community forum](https://wordpress.org/support/plugin/youtube-channel/). Make sure to prefix *Topic Title* with **[Feature Request] ...**
Please note, we cannot guarantee an ETA to implement the requested feature.

If you are in a hurry, please find a developer to do it for you or [request a quote from us](https://urosevic.net/wordpress/contact/?subject=YouTube%20Channel).

== Changelog ==

= 3.0.12.1 (20210227) =
* Tested: WordPress 5.6.2 on PHP 7.4.15 and 8.0.2
* Add: compatibility with PHP 8
* Add: conditionally hide not applicable options in widget settings form
* Change: Lowest supported PHP version increased to 5.6
* Improve: Readme, Installation (Step-by-Step instructions for obtaining YouTube Data API Key v3), FAQ

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
* Improve: Disable LastPass altering settings fields

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