=== YouTube Channel ===
Contributors: urkekg
Donate link: https://urosevic.net/wordpress/donate/?donate_for=youtube-channel
Tags: youtube, channel, playlist, single, widget, widgets, youtube player, feed, video, thumbnail, embed, sidebar, iframe, html5, responsive
Requires at least: 4.0
Tested up to: 4.9
Stable tag: 3.0.11.3
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Show video thumbnails or playable blocks of recent videos from YouTube Playlist, Channel (User Uploads), Liked or Favourited videos.

== Description ==

When you need to display sidebar widget with latest or random video from some YouTube channel or playlist, use plugin **YouTube Channel**!

Simply insert widget to sidebar or shortcode to content, set Channel or Playlist ID, select resource to use and if you wish leave all other options on default. You will get latest video from chosen YouTube channel or playlist embedded on location of widget/shortcode, with optional link to channel at the bottom of the YTC block.

If you like our plugin and you find it useful, please [write review and rate it](https://wordpress.org/support/view/plugin-reviews/youtube-channel).

For manual set of videos from YouTube check out [Easy YouTube Gallery](https://wordpress.org/plugins/easy-youtube-gallery/).

= Features =
* Display latest videos from YouTube Channel (resources are sorted in reverse chronological order based on the date they were created) or videos naturaly sorted from Favorited Videos, Liked Videos and Playlist
* Option to get random video from any of 4 resources
* Responsive (one full width video per row) or non responsive
* Preferred aspect ratio relative to width (16:9 and 4:3)
* Custom width for video embeded object (default is 306px)
* Enhanced Privacy
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

= 3.0.10.5 (20170225) =
* Remove: FMVD opt-in because of general plugin guideline violation rule #9

= 3.0.10.4 (20170123) =
* Fix: once enabled FMVD notice has not auto dismissed.
* Change: input type for YouTube Data API Key from password to text so key is visible by webmaster and prevent messing with autofill browser extensions

= 3.0.10.3 (20170114) =
* Add opt-in option Freemage Video Downloader
* Code cleanup: delete unused admin.js file
* UI cleanup: enhance checkbox control in settings
* UI cleanup: update option names like 'What to show' is now 'Embed as', 'Hide related videos' to 'No related videos' , etc

= 3.0.10.2 (20170110) =
* Add option to select Title HTML tag (default to H3)

= 3.0.10.1 (20170110) =
* Fix Parse error: syntax error, unexpected T_PAAMAYIM_NEKUDOTAYIM, expecting ')' in /plugins/youtube-channel/inc/settings.php on line 218

= 3.0.10 (20161231) =
* (20161225) Optimize: Remove `extract()` from shortcode parser
* Code cleanup and fixing to be compliant with WordPress Core coding standards
* Test compatibility with WordPreee 4.7 and PHP 7.0.14
* (20161224) Change: Update notification for missing YouTube Data API Key
* Change: Do not display shortcode options changes for fresh installation
* (20160824) Change: HTTP to HTTPS links and open remote links on settings page in new tab/window
* Change: Disable deprecated notification related to old v2.4 of plugin
* (20160419) Fix: resource from globals used if in widget set to use User uploads as resource.
* Enhance: Implemented Selective Refresh Support for Widget (WP 4.5)
* (20160131) Fix: prevent PHP Notices for Settings page.
* Fix: `Cache feed` option in widget can't be disabled (always revert to default).
* Fix: `Open link in` option in widget always revert to default.
* Security: prevent direct loading of settings and widget files.
* (20160120) Optimize: replace yt_play.png with optimized image.

= 3.0.9 =
* Fix: broken autoplay when start to play all videos instead only first in YTC block set
* Fix: remove $_SESSION variable and replace with class variable to avoid duplicate sets of JS code

= 3.0.8.9 =
* Fix: Set `Hide link` for `Link to` option for `Link to Channel` in global setting does not work (reported by drweby2).
* Change: Set minumal required WordPress version 4.0
* Update: Supported WordPress Version to 4.4

= 3.0.8.8 =
* Fix: Link to channel enabled in Widget not shown if related link ID has not set in Widget (ignored general settings).
* Fix: Undefined notices

= 3.0.8.7 =
* Enhance: Merge two improvements for MagnificPopupAU fork from core library (commit 60d5aa4 and 1d0f697)
* Fix: TinyMCE button does not have icon when new post/page is created
* Fix: Strip non-number characters entered in shortcode TinyMCE creator for Fetch, Show, Initial Width and Description length attributes.
* Fix: make sure that Initial Width is always in pixels (if user enter width in percentage, strip percent sign and threat value as pixel value)

= 3.0.8.6 =
* Fix: Broken Enhanced Privacy on small screens in forked MagnificPopupAU library
* Enhanced: Finished making code compliant to WordPress Core coding standards

= 3.0.8.5 =
* Enhanced: Settings page made compliant to WordPress Core Coding Standard
* Fix: Wrong links to external resources on Settings page
* Fix: Opening external resources links on Settings page in new tab
* Change: Replace PayPal donation links to prevent account limitations if plugin is used on website that violates PayPal's Acceptable Use Policy

= 3.0.8.4 (2015-06-10/16/17/18/19-07/10) =
* Fix: (6/19) Undefined notice for apikey
* Fix: (6/18) Wrong name of widgets page on Help tab
* Fix: (6/17) Do not load empty JS asset to prevent clash with VisualComposer and invisible rows wit enabled strtching
* Fix: (6/16) Initiate .MagnificPopupAU() on window load event, not on DOM ready event
* Fix: Lost some settings during igration from old to new options in settings and widgets
* Add: (7/13) New global option **Disable TinyMCE** added to **General** tab. Enabled by default, disable to remove TinyMCE icon from post/page Visual Editor
* Add: (7/10) New global option **Enable Full Screen** added to **Video** tab. Disabled by default, enable fullscreen option for embedded playlist
* Add: (6/18) Support to initiate .MagnificPopupAU() on .ajaxComplete() and support dynamically loaded YTC within AJAX
* Add: New global option **Play inline on iOS** added to **Video** tab. Disabled by default, provide support for playsinline parameter.
* Add: Support for (playsinline)[https://developers.google.com/youtube/player_parameters#playsinline] player option in MagnificPopup library to play video on mobile devices in page instead in device player (disabled by default)
* Add: Default option settings for nolightbox and
* Cleanup: Removed unused modules from MagnificPopup library
* Change: Help tab now have shortcode parameters distributed to subtabs
* Change: Lightbox classes by prepending `ytc-` in front of all MagnificPopup classes
* Change: Reduce minimal screen width from 700px to 320px when lightbox will not be used and will open video directly on YouTube website.
* Improve: Updated strings for localization support
* Improve: Updated FAQ with new question about play button on thumbnails
* Updated Serbian localization

= 3.0.8.3 (2015-06-09) =
* Add: Support for enhanced privacy videos in lightbox (MagnificPopupAU tweak)

= 3.0.8.2 (2015-06-08) =
* Fix: Async HTML5 has broken when debug is disabled because single comments before JS code compression

= 3.0.8.1 (2015-06-07) =
* Fix: Migrate deprecated widget options
* Add: Notice about changed shortcode parameters
* Add: Message if access to resource is forbidden (private Liked or Favourited videos)

= 3.0.8 (2015-06-07) =
* Fix: Undefined and deprecated global options
* Add: Global option to disable builtin Lightbox (Video tab)
* Add: Link to Support forums on Plugins page
* Add: Visible error in YTC block for Administrator, Oops for visitors and lower members
* Add: Filename of debug JSON
* Add: Video title classes `ytc_title_above`/`ytc_title_below`
* Add: Button `Clear All YTC Cache` on `Tools` tab to quick purge all cached YTC feeds
* Improve: Do not include YT `iframe_api` if already included by other plugin and make YTC iframe2 to work even if other plugin uses `iframe_api`
* Improve: Remove `Playlist Only` checkbox (`only_pl=1` shortcode parameter) and integrate as new Display:Embedded Playlist option (`display=playlist` shortcode parameter)
* Improve: Remove `Show title below` checkbox (titlebelow) and integrate to `Show video title` (showtitle) as dropdown
* Improve: Remove `Show link to channel` checkbox (showgoto) and integrate option `None` to `What to link` (link_to)
* Improve: Move JS code to initiate Magnific popup to inline print on wp_footer
* Improve: LESS stylesheet for easier maintenance
* Remove: `Et cetera` (descappend) option and always use `...` for shortened description
* Remove: Macro templates for Link to channel title
* Change: Values for `showtitle` and `link_to` are changed from integer to string (check Shortcodes section)
* Change: Move `Hide annotations` and `Hide video info` from `Content` to `Video` tab on settings page
* Change: Play icon to be like original YT play shape
* Change: Make responsive enabled by default in new widgets
* Optimize: DRY of visible errors for Administrator and visitors (Oops message)
* Optimize: Faster empty defaults for channel, vanity, legacy and playlist in global settings
* Optimize: Minify inline JS code
* Optimize: Remove call to fitVids() function
* Cleanup: Remove $yt_url and $yt_video variables
* Cleanup: Remove unused function to clean playlist ID

= 3.0.7.3 (2015-05-29) =
* Add: TinyMCE button to easy configure and insert shortcode to post/page content
* Add: Report about zero videos in resource
* Add: Helper method to generate resource nice name (DRY)

= 3.0.7.2 (2015-05-24) =
* Add: Error report if we have broken feed on record
* Add: Report about failed HTTP connections and other problems ocurred when we try to fetch feed
* Add: DRY of visible errors for Administrator and visitors (Oops message)

= 3.0.7.1 (2015-05-17/18) =
* Fix: Plugin version number not updated in DB
* Fix: Magnific Popup appear under header on Twenty Eleven theme
* Fix: .clearfix break layout if used as class on content division

= 3.0.7 (2015-05-17) =
* Fix: Uncaught TypeError: e(...).fitVids is not a function
* Change: Remove plugin default Channel ID, Vanity custom name, Legacy username and Playlist ID; leave them empty by default and allow them to be empty parameters; throw error if required value not provided. All this to prevent questions like *Why I see your videos on my website* or *Why my website link to your channel*
* Cleanup: Deprecated widget toggler for Playlist Only depending on selected Resource
* Cleanup: Deprecated 16:10 styles
* Optimize: Minimize admin style for widget layout

= 3.0.6.2 (2015-05-15) =
* Fix: Fatal error: Cannot unset string offsets in update.php on line 229 (introduced in 3.0.6.1)
* Add: Helpfull links to plugin settings page

= 3.0.6.1 (2015-05-14) =
* Fix: Undefined index: random
* Fix: Unremoved only_pl from global settings
* Add: Box sizing of .youtube_channel element for crappy themes

= 3.0.6 (2015-05-13/14) =

* Fix: Prevent Fatal error on PHP<5.3 because required __DIR__ for updater replaced with dirname(__FILE__)
* Fix: No retrieved or missing videos from some channels so switch `search` to `playlistItems` API call (kudos to @[mmirus](https://wordpress.org/support/profile/mmirus))
* Add: Embed As Playlist for all resources
* Add: Clearfix for crappy themes where clearfix does not exists
* Add: Option to move video title below video (boolean shortcode parameter `titlebelow`)
* Add: PayPal donate button to settings page
* Improved: Move YouTube Data API Key to plugin settings and add notification to remove YOUTUBE_DATA_API_KEY from wp-config.php (optional)
* Improved: Updated shortcode explanation in README and Help tab in plugin settings.
* Improved: Better tips for 'Oops, something went wrong' message.
* Change: Wording `Ups` to `Oops`
* Remove: Options `Embed standard playlist` and `Show random video` from global settings as this should be off by default
* Remove: Loading of fitVids JS library for test before final removing.

= 3.0.5 (2015-05-11/12) =

* Fix: Setting back dropdown options with `0` ID does not work on Settings page (Channel as resource to use, Cache timeout, Aspect ratio, What to show, Open link to, Link to)
* Add: Option to export global settings to JSON and add to Tools tab in settings button to download global settings JSON
* Change: Update plugin features
* Improved: Retrieve only fields which will be used for output w/o unused items to reduce
* Improved: More micro optimizations

= 3.0.4 (2015-05-11) =

* Add: Tip what to do if error ocurred with YouTube Data API Key printed inside YTC ERROR comment
* Change: Where to ask for support links in widget
* Change: Timeout for getting feed increased from 2 to 5 seconds
* Change: Update FAQ sections in readme file
* Remove: Check for Redux Framework in debug JSON generator

= 3.0.3 (2015-05-10) =

* Fix: "Oops, something went wrong." when Playlist selected as resource because wrong switch
* Fix: Jumping thumbnails in responsive wall on hover in Twenty Fifteen theme because border-bottom for anchors
* Fix: Another deprecated shortcode attribute backward compatibility (`use_res`)
* Add: Example of dynamic responsive wall (1 large + 6 small thumbnails) (to [Description](https://wordpress.org/plugins/youtube-channel/))

= 3.0.2 (2015-05-10) =

* Fix: (typo - experiencing on frontend when no API Key set) PHP Fatal error:  Call to undefined function __sprintf() in youtube-channel.php on line 445
* Fix: shortcode deprecated params `res` and `show` not backward compatibile

= 3.0.1 (2015-05-10) =

* Fix: Fatal error: Using $this when not in object context in youtube-channel.php on line 89
* Fix: Link to channel not visible on Twenty Fifteen theme

= 3.0.0 (2015-05-07/08/09/10) =

* Fix: Migraton of global and widget settings to v3.0.0
* Add: New Global Settings page as replacement of Redux Framework solution
* Add: Non-Dismissable Dashboard notice if YouTube Data API Key missing with link to explanation page
* Change: Option key `ytc_version` to `youtube_channel_version`
* Change: Shortcode parameters: `res` to `resource`, `show` to `display`; but leave old parameter names for backward compatibility
* Enhance: Various plugin core micro optimizations
* Enhance: Dashboard notices
* Enhance: Proper options migration on plugin update
* Remove: Redux Framework mentioning from core plugin
* Remove: Redux Framework config.php
* Remove: chromeless.swf asset
* Remove: option `Fix height taken by controls` as now YouTube displays control bar only when video is hovered

= 3.0.0alpha2 (2015-03-07/22/24) =

* Add: Rewrite plugin to work with YouTube Data API v3
* Add: Vanity link as option to Link to channel (now supports Legacy username, Channel and Vanity URL) with cleanup Vanity ID routine
* Add: Liked videos as resource (now support channel, playlists, favourites and liked videos)
* Add: Admin notification in widget output on front-end if no YouTube Data API Key is defined to prevent errors
* Add: Dismissable Dashboard notice if PHP version is lower than 5.3 as YTC maybe will not work with older versions.
* Change: Global and widget option names: `use_res` to `resource`, `cache_time` to `cache`, `maxrnd` to `fetch`, `vidqty` to `num`, `getrnd` to `random`, `to_show` to `display`, `showvidesc` to `showdesc`, `enhprivacy` to `privacy`, `videsclen` to `desclen`,
* Change: Widget settings functionality, two column options, better toggle for playlist and GoTo section
* Enhance: Caching routine (reduce possibility of failed feed fetch)
* Remove: Chromeless and Flash player - leave only Thumbnail and HTML5
* Remove: Aspect Ration 16:10 (so support only 16:9 and 4:3, same as modern YouTube)
* Remove: "Fix No Item" option - not required for YouTube API 3.0

== Upgrade Notice ==

= 3.0.8 =
Bugfixes, optimizations and improvements.

= 3.0.7.3 =
User experience improved with Shortcode generator for TinyMCE

= 3.0.7.2 =
Added report for user if any HTTP error occured

= 3.0.7,1 =
Quick fix for clearfix class

= 3.0.7 =
Cleanup and optimization release

== Screenshots ==

1. YouTube Channel default plugin settings (General tab)
2. YouTube Channel customized widget settings
3. YouTube Channel in WP Customizer and Dynamic Wall layout
4. How to add YouTube Data API Key to YouTube Channel
5. TinyMCE form to easy configure YouTube Channel shortcode for content