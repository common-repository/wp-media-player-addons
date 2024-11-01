/* To avoid CSS expressions while still supporting IE 7 and IE 6, use this script */
/* The script tag referencing this file must be placed before the ending body tag. */

/* Use conditional comments in order to target IE 7 and older:
	<!--[if lt IE 8]><!-->
	<script src="ie7/ie7.js"></script>
	<!--<![endif]-->
*/

(function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'wmp\'">' + entity + '</span>' + html;
	}
	var icons = {
		'wmp-icon-closed-caption-logo': '&#xe902;',
		'wmp-icon-previous': '&#xe900;',
		'wmp-icon-exit-fullscreen': '&#xf107;',
		'wmp-icon-fullscreen': '&#xf10a;',
		'wmp-icon-play': '&#xf109;',
		'wmp-icon-pause': '&#xf106;',
		'wmp-icon-next': '&#xf101;',
		'wmp-icon-repeat': '&#xf10b;',
		'wmp-icon-vol-full': '&#xf102;',
		'wmp-icon-vol-half': '&#xf105;',
		'wmp-icon-vol-cross': '&#xf103;',
		'wmp-icon-vol-low': '&#xf108;',
		'wmp-icon-shuffle': '&#xf104;',
		'wmp-icon-cc': '&#xf20a;',
		'wmp-icon-closed_caption': '&#xe01c;',
		'wmp-icon-warning': '&#xe901;',
		'wmp-icon-spinner2': '&#xe97b;',
		'wmp-icon-volume-high': '&#xea26;',
		'wmp-icon-volume-medium': '&#xea27;',
		'wmp-icon-volume-low': '&#xea28;',
		'wmp-icon-volume-mute': '&#xea29;',
		'wmp-icon-volume-cross': '&#xea2a;',
		'0': 0
		},
		els = document.getElementsByTagName('*'),
		i, c, el;
	for (i = 0; ; i += 1) {
		el = els[i];
		if(!el) {
			break;
		}
		c = el.className;
		c = c.match(/wmp-icon-[^\s'"]+/);
		if (c && icons[c[0]]) {
			addIcon(el, icons[c[0]]);
		}
	}
}());
