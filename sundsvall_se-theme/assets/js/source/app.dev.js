require('./bootstrap/umd/collapse.js');
require('./bootstrap/umd/dropdown.js');
require('./bootstrap/umd/modal.js');
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

		$('[data-toggle="search"]').on('click', function (e) {
			e.preventDefault();
			var target = $(this).attr('href');
			console.log(target);
			$(target).toggleClass('active');
			$('body').toggleClass('search-active');
		});

		/**
		 * Toggle off canvas navigation
		 */
		$('[data-toggle="offcanvas-left"]').on('click', function (e) {
			e.preventDefault();
			var target = $(this).attr('href');
			offcanvas(target, 'left');
		});

		function offcanvas(target, dir) {

			$(target).toggleClass('active');
			$('body').toggleClass('offcanvas-active-' + dir);

			/**
			 * Add class to body to indicate that offcanvas is animating in or out.
			 */
			$('body').addClass('offcanvas-animating-' + dir);
			$(target).on('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(e) {
				$('body').removeClass('offcanvas-animating-' + dir);
			});

		}

		/**
		 * Load more results with ajax
		 */
		$('[data-append-button]').on('click', 'a', function(e) {
			e.preventDefault();

			var anchorParent = $(this).parents('[data-append-button]');
			var appendContainerID = anchorParent.data('append-button');
			var anchor = $(this);
			var link = anchor.attr('href');
			var count = parseInt($('.post-count__count').html());

			anchor.html('Laddar …');
			$.get(link, function(data){ 
				anchor.remove();
				$(data).find(appendContainerID + ' li').hide().appendTo(appendContainerID).fadeIn(1000);
				$(data).find('[data-append-button] a').appendTo(anchorParent);

				var newCount = parseInt($(data).find('.post-count__count').html());
				$('.post-count__count').html(count + newCount);
			}).fail(function() {
				anchor.html('Något gick fel, prova igen …');
			});

		});

	});


})(jQuery);
