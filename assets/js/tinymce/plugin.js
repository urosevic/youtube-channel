( function() {
	tinymce.PluginManager.add( 'youtube_channel', function( editor, url ) {

		// Add a button that opens a window
		editor.addButton( 'youtube_channel_shortcode', {
			tooltip: 'My YouTube Channel',
			icon: 'ytc',
			onclick: function() {
				// Open window
				editor.windowManager.open( {
					title: 'My YouTube Channel',
					// width: 600,
					// height: 400,
					// autoScroll: true,
					// resizable: true,
					// classes: 'ytc-shortcode-popup',
					bodyType: 'tabpanel',
					buttons: [
						{
							text: 'Insert Shortcode',
							onclick: 'submit',
							classes: 'widget btn primary',
							minWidth: 130
						},
						{
							text: 'Cancel',
							onclick: 'close'
						}
					],
					body: [

						{
							title: 'General Settings',
							type: 'form',
							items: [
								{
									type: 'textbox',
									name: 'class',
									label: 'Custom CSS Class',
									value: '',
									tooltip: 'Enter custom class for YTC block, if you wish to target block styling'
								},
								{
									type: 'textbox',
									name: 'channel',
									label: 'YouTube Channel ID',
									value: '',
									// tooltip: ''
								},
								{
									type: 'textbox',
									name: 'handle',
									label: 'Handle',
									value: '',
									// tooltip: ''
								},
								{
									type: 'textbox',
									name: 'playlist',
									label: 'Playlist ID',
									value: '',
									// tooltip: ''
								},
								{
									type: 'listbox',
									name: 'resource',
									label: 'Resource to use',
									tooltip: '',
									values : [
										{text: 'Channel (User Uploads)', value: '0'},
										{text: 'Playlist', value: '2'},
									],
									value : '0'
								},
								{
									type: 'listbox',
									name: 'cache',
									label: 'Cache feed',
									tooltip: '',
									values : [
										{text: 'Do not cache', value: '0'},
										{text: '1 minute', value: '60'},
										{text: '5 minutes', value: '300'},
										{text: '15 minutes', value: '900'},
										{text: '30 minutes', value: '1800'},
										{text: '1 hour', value: '3600'},
										{text: '2 hours', value: '7200'},
										{text: '5 hours', value: '18000'},
										{text: '10 hours', value: '36000'},
										{text: '12 hours', value: '43200'},
										{text: '18 hours', value: '64800'},
										{text: '1 day', value: '86400'},
										{text: '2 days', value: '172800'},
										{text: '3 days', value: '259200'},
										{text: '4 days', value: '345600'},
										{text: '5 days', value: '432000'},
										{text: '6 days', value: '518400'},
										{text: '1 week', value: '604800'},
										{text: '2 weeks', value: '1209600'},
										{text: '3 weeks', value: '1814400'},
										{text: '1 month', value: '2419200'},
									],
									value : '300'
								},
								{
									type: 'textbox',
									name: 'fetch',
									label: 'Fetch',
									value: '10',
									tooltip: 'Number of videos that will be used for random pick (min 2, max 50, default 25)'
								},
								{
									type: 'textbox',
									name: 'skip',
									label: 'Skip',
									value: '0',
									tooltip: 'Number of videos to skip (not applicable for embedded playlist and random pick (min 0, max 49, default 0)'
								},
								{
									type: 'textbox',
									name: 'num',
									label: 'Show',
									value: '1',
									tooltip: 'Number of videos to display'
								},
								{
									type: 'checkbox',
									name: 'privacy',
									label: 'Use Enhanced Privacy',
									tooltip: 'Enable this option to protect your visitors privacy',
									checked: false
								},
								{
									type: 'checkbox',
									name: 'random',
									label: 'Random video',
									tooltip: 'Show random video from resource (Have no effect if \"Embed as\" has been set to \"Embedded Playlist\")',
									checked: false
								},
							]
						},

						{
							title: 'Video Settings',
							type: 'form',
							items: [
								{
									type: 'listbox',
									name: 'ratio',
									label: 'Aspect Ratio',
									// tooltip: '',
									values : [
										{text: 'Widescreen (16:9)', value: '3'},
										{text: 'Standard TV (4:3)', value: '1'},
									],
									value : '3'
								},
								{
									type: 'checkbox',
									name: 'responsive',
									label: 'Responsive video',
									tooltip: 'Make video responsive (distribute one full width video per row)',
									checked: true
								},
								{
									type: 'textbox',
									name: 'width',
									label: 'Initial width (px)',
									value: '306',
									tooltip: 'Set initial width for video or thumbnail (in pixels)'
								},
								{
									type: 'listbox',
									name: 'display',
									label: 'Embed as',
									tooltip: '',
									values : [
										{text: 'Thumbnail', value: 'thumbnail'},
										{text: 'HTML5 (iframe)', value: 'iframe'},
										{text: 'HTML5 (iframe) Asynchronous', value: 'iframe2'},
										{text: 'Embedded Playlist', value: 'playlist'},
									],
									value : 'thumbnail'
								},
								{
									type: 'listbox',
									name: 'thumb_quality',
									label: 'Thumbnail Quality',
									tooltip: '',
									values : [
										{text: 'Default Quality (120x90px)', value: 'default'},
										{text: 'Medium Quality (320x180px)', value: 'mqdefault'},
										{text: 'High Quality (480x360px)', value: 'hqdefault'},
										{text: 'Standard Definition (640x480px)', value: 'sddefault'},
										{text: 'Maximum Resolution (1280x720px)', value: 'maxresdefault'},
									],
									value : 'hqdefault'
								},
								{
									type: 'checkbox',
									name: 'nolightbox',
									label: 'Disable YTC lightbox',
									tooltip: 'This will work only for Thumbnail and will open linked video in new tab',
									checked: false
								},
								{
									type: 'checkbox',
									name: 'no_thumb_title',
									label: 'Hide thumbnail tooltip',
									checked: false
								},
								{
									type: 'checkbox',
									name: 'controls',
									label: 'Hide player controls',
									checked: false
								},
								{
									type: 'checkbox',
									name: 'nobrand',
									label: 'Hide YT Logo',
									tooltip: 'Does not work for all videos',
									checked: true
								},
								{
									type: 'checkbox',
									name: 'noanno',
									label: 'Hide annotations',
									checked: true
								},
								{
									type: 'checkbox',
									name: 'norel',
									label: 'Hide related videos',
									checked: true
								},
								{
									type: 'checkbox',
									name: 'autoplay',
									label: 'Autoplay video/playlist',
									checked: false
								},
								{
									type: 'checkbox',
									name: 'mute',
									label: 'Mute video on autoplay',
									checked: false
								},
							]
						},

						{
							title: 'Content Layout',
							type: 'form',
							items: [

								{
									type: 'listbox',
									name: 'showtitle',
									label: 'Show video title',
									tooltip: '',
									values : [
										{text: 'Hide title', value: 'none'},
										{text: 'Above video/thumbnail', value: 'above'},
										{text: 'Below video/thumbnail', value: 'below'},
										{text: 'Inside thumbnail, top aligned', value: 'inside'},
										{text: 'Inside thumbnail, bottom aligned', value: 'inside_b'},
									],
									value : 'none'
								},
								{
									type: 'checkbox',
									name: 'linktitle',
									label: 'Link outside title to video',
									checked: false
								},
								{
									type: 'listbox',
									name: 'titletag',
									label: 'Title HTML tag',
									tooltip: 'Select which HTML tag to use for title wrapper.',
									values : [
										{text: 'h3', value: 'h3'},
										{text: 'h4', value: 'h4'},
										{text: 'h5', value: 'h5'},
										{text: 'div', value: 'div'},
										{text: 'span', value: 'span'},
										{text: 'strong', value: 'strong'},
									],
									value : 'none'
								},
								{
									type: 'checkbox',
									name: 'showdesc',
									label: 'Show video description',
									checked: false
								},
								{
									type: 'textbox',
									name: 'desclen',
									label: 'Description length',
									value: '0',
									tooltip: 'Set number of characters to cut down video description to (0 means full length)'
								},
							]
						},

						{
							title: 'Link to Channel',
							type: 'form',
							items: [
								{
									type: 'listbox',
									name: 'link_to',
									label: 'Link to',
									// tooltip: '',
									values : [
										{text: 'Hide link', value: 'none'},
										{text: 'Channel page URL', value: 'channel'}, // ex 1
										{text: 'Handle URL', value: 'handle'}, // new 2023
									],
									value : 'none'
								},
								{
									type: 'textbox',
									name: 'goto_txt',
									label: 'Title for link',
									value: 'Visit our YouTube channel',
								},
							]
						}
					],

					onsubmit: function( e ) {
						// Insert content when the window form is submitted
						// Open shortcode
						var shortcode = '[youtube_channel';

						// General Settings
						if ( e.data.handle ) shortcode += ' handle=' + e.data.handle +'';
						if ( e.data.channel ) shortcode += ' channel=' + e.data.channel +'';
						if ( e.data.playlist ) shortcode += ' playlist=' + e.data.playlist +'';
						if ( e.data.resource ) shortcode += ' resource=' + e.data.resource +'';
						if ( e.data.cache ) shortcode += ' cache=' + e.data.cache +'';
						if ( e.data.privacy ) shortcode += ' privacy=1';
						if ( e.data.random ) shortcode += ' random=1';
						if ( e.data.fetch ) shortcode += ' fetch=' + e.data.fetch.replace(/[^0-9.]/g, '') +'';
						if ( e.data.skip ) shortcode += ' skip=' + e.data.skip.replace(/[^0-9.]/g, '') +'';
						if ( e.data.num ) shortcode += ' num=' + e.data.num.replace(/[^0-9.]/g, '') +'';

						// Video Settings
						if ( e.data.ratio ) shortcode += ' ratio=' + e.data.ratio + '';
						if ( e.data.responsive ) shortcode += ' responsive=1';
						if ( e.data.width ) shortcode += ' width=' + e.data.width.replace(/[^0-9.]/g, '') + '';
						if ( e.data.display ) shortcode += ' display=' + e.data.display + '';
						if ( e.data.thumb_quality ) shortcode += ' thumb_quality=' + e.data.thumb_quality + '';
						if ( e.data.no_thumb_title ) shortcode += ' no_thumb_title=1';
						if ( e.data.controls ) shortcode += ' controls=1';
						if ( e.data.autoplay ) shortcode += ' autoplay=1';
						if ( e.data.mute ) shortcode += ' mute=1';
						if ( e.data.norel ) shortcode += ' norel=1';
						if ( e.data.nobrand ) shortcode += ' nobrand=1';
						if ( e.data.nolightbox ) shortcode += ' nolightbox=1';

						// Content Layout
						if ( e.data.showtitle ) shortcode += ' showtitle=' + e.data.showtitle + '';
						if ( e.data.linktitle ) shortcode += ' linktitle=1';
						if ( e.data.titletag ) shortcode += ' titletag=' + e.data.titletag + '';
						if ( e.data.showdesc ) shortcode += ' showdesc=1';
						if ( e.data.desclen ) shortcode += ' desclen=' + e.data.desclen.replace(/[^0-9.]/g, '') + '';
						if ( e.data.noanno ) shortcode += ' noanno=1';

						// Link to Channel
						if ( e.data.link_to ) shortcode += ' link_to=' + e.data.link_to + '';
						if ( e.data.goto_txt ) shortcode += ' goto_txt=\"' + e.data.goto_txt + '\"';

						// Global
						if ( e.data.class ) shortcode += ' class=' + e.data.class + '';

						// Close shortcode
						shortcode += ']';

						editor.insertContent( shortcode );
					} // onsubmit alert

				} );
			} // onclick alert

		} );

	} );

} )();