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

		/**
		 * Vote function for pages
		 */
		$('.vote-widget').on('click', '[data-vote]', function(e) {
			var $buttons = $('.vote-widget [data-vote]');
			$buttons.prop("disabled", true);
			var action = $(this).data('vote');
			pageVote(action);
			$('#vote-form').collapse('show');
		});

		function pageVote(voteType) {

			var $buttons = $('.vote-widget [data-vote]');

			if(voteType !== 'up' && voteType !== 'down') {
				return false;
			}

			$.ajax({
				url: ajaxdata.ajax_url,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'pagevote',
					vote_type: voteType,
					post_id: ajaxdata.post_id,
					_ajax_nonce: ajaxdata.ajax_nonce,
				}
			}).done(function(data) {
				if(data.status == 'success') {
					$buttons.hide();
					$('.vote-percent').html(data.new_percent_text);
				}
			}).error(function(jqHXR, textStatus, errorThrown) {
			});

		}


		/**
		* Load Gravity Form with ajax
		*/
		var loadedForms = [];
		$('.gform-async').on('show.bs.modal show.bs.collapse', function() {

			var $gformContainer = $(this).find('[data-gform]');
			var formID          = $gformContainer.data('gform');
			var displayDescription = $gformContainer.data('gform-display_description');
			var displayTitle       = $gformContainer.data('gform-display_title');

			if($.inArray(formID, loadedForms) > -1) return;

			$gformContainer.html('Laddar formulär…');

			$.ajax({
				url: ajaxdata.ajax_url,
				type: 'GET',
				dataType: 'text',
				data: {
					action: 'sk_load_gform',
					form_id: formID,
					_ajax_nonce: ajaxdata.ajax_nonce,
					display_description: displayDescription,
					display_title: displayTitle
				}
			}).done(function(data) {
				$gformContainer.html(data);
				loadedForms.push(formID);
			}).error(function(jqHXR, textStatus, errorThrown) {
				$gformContainer.html('Något gick fel, det gick inte att ladda synpunktsformuläret.');
			});

		});

		// Search
		var search = function(term, page, callback) {

			console.log(page);

			$.ajax({

				url: searchparams.ajax_url,
				type: 'GET',
				dataType: 'json',
				data: {
					action: 'sk_search_main',
					s: term,
					page: page
				}

			}).done( function(data) {

				var items = '';

				var source = $('#searchitem-template').html();
				var template = Handlebars.compile(source);

				data.items.forEach(function(item) {
					items += template(item);
				});

				callback(items, data.query);

			}).error(function(jqHXR, textStatus, errorThrown) {
				console.log('Error searching', textStatus);
			});

		}

		$('[data-load-more]').on('click', function(e) {

			var $button = $(this);

			var $searchContainer = $(this).closest('.search-module');

			var $itemsContainer = $searchContainer.find('ol');
			var $currentCountCountainer = $searchContainer.find('.post-count__count');

			console.log($itemsContainer);

			e.preventDefault();

			searchparams.currentPage_main = parseInt(searchparams.currentPage_main);
			var page = searchparams.currentPage_main += 1;

			search(searchparams.search_string, page, function(items, query) {

				// Update post count info "Visar x av x"
				var displayedPosts = parseInt($currentCountCountainer.html());
				$currentCountCountainer.html(displayedPosts += query.post_count);

				// Fade in the new items
				var $items = $(items).hide();
				$itemsContainer.append($items);
				$items.fadeIn();

				// Hide button if we are on last page
				if( query.max_num_pages <= searchparams.currentPage_main) {
					$button.hide();
				}

			});

		});

	});


})(jQuery);
