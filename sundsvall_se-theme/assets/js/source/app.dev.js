require('./bootstrap/umd/collapse.js');
require('./bootstrap/umd/dropdown.js');
require('./acf-map.js');

(function($) {
	"use strict";

	/**
	 * Replace .no-js with .js modernizr-style
	 */
	$('html').removeClass('no-js').addClass('js');

	$(document).ready(function() {

		/**
		 *
		 * Don't close bootstrap dropdown for translation when clicking inside,
		 * then it would be impossible to select a language.
		 *
		 */
		$('.translation-dropdown .dropdown-toggle').on('click', function(){
			$(this).parent().toggleClass('open');
			$(this).attr('aria-expanded', function (i, attr) {
				return attr == 'true' ? 'false' : 'true';
			});
		});

		$('body').on('click', function (e) {
			if (!$('.translation-dropdown').is(e.target) 
					&& $('.translation-dropdown').has(e.target).length === 0 
				&& $('.open').has(e.target).length === 0
				 ) {
					 $('.translation-dropdown').removeClass('open');
					 $('.translation-dropdown .dropdown-toggle').attr('aria-expanded', 'false');
				 }
		});

		/**
		 * Toggle off canvas navigation
		 */
		$('[data-toggle="offcanvas"]').on('click', function () {
			$('body').toggleClass('offcanvas-active');
			$('body').addClass('offcanvas-animating');
			$('.offcanvas-fixed-sm').toggleClass('active')

			$('.offcanvas-fixed-sm').one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(e) {
				$('body').removeClass('offcanvas-animating');
			});

		});

	});


})(jQuery);
