(function($){


	$(document).ready(function() {

		var options = '<label class="setting select-setting"><span>Player Color</span><select data-setting="color"><option value="dark">Dark</option><option value="sunset">Sunset</option></select></label>';
		
		$('#tmpl-audio-details, #tmpl-playlist-settings, #tmpl-video-details').each(function() {

			var template = $(this).html().split('</label>');

			template[template.length - 1] = options + template[template.length - 1];

			$(this).html( template.join('</label>') );

		});

	});

}(jQuery));