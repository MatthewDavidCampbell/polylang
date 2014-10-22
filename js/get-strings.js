jQuery(document).ready(function($) {

	$(document).on("click", "#pll_scan_theme_and_plugins", function() {
		var data = {
			action: 'get-strings'
		}

		$.post(ajaxurl, data, function(r, stat) {
			if ( 0 == r || 'success' != stat )
				r = wpAjax.broken;

			console.log (r);
			console.log (stat);
		});
	});
});