<h3>Clear YTC Cache</h3>

<p>To avoid page load slowdown, we provide internal YouTube Channel caching functionality. You can enable caching per widget or shortcode, and set how long cached feed for particular widget or shortcode will live.</p>
<p>If you wish to force clear cache before it expires (for example, you have set cache timeout to 2 days, but wish to force loading of fresh channel or playlist feed), you can force Clear All YTC Cache to remove transients from database on demand.</p>

<button id="ytc_clear_all_cache" class="button">Clear All YTC Cache</button>
<?php $nonce = wp_create_nonce( 'action-ytc_clear_all_cache' ); ?>
<script type="text/javascript">
jQuery(document).ready(function($){
	$('#ytc_clear_all_cache').on('click', function(ev){
		ev.preventDefault();

		$.post(
			ajaxurl,
			{
				'action': 'ytc_clear_all_cache',
				'nonce': '<?php echo esc_attr( $nonce ); ?>'
			},
			function(response){
				alert(response);
			}
		);

	});
});
</script>
