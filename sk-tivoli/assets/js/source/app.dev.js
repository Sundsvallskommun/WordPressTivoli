require('./bootstrap/umd/button.js');
require('./bootstrap/umd/collapse.js');
require('./bootstrap/umd/dropdown.js');
require('./bootstrap/umd/modal.js');
require('./acf-map.js');
require('./sk_vacancies_list.js');
require('./sk_infinite_scroll.js');
require('./sk_calendar.js');

(function($) {
	"use strict";

	/**
	 * Replace .no-js with .js modernizr-style
	 */
	$('html').removeClass('no-js').addClass('js');


	$(document).ready(function() {

		/**
		 * Some flex styling is not working properly in Internet Explorer 10
		 * and the only way to target IE10 specifically via CSS media queries
		 * also targets IE11 (in which these things works) so we have to resort
		 * to UE sniffing in order to fix these style related problems.
		 *
		 * 
		 */
		var doc = document.documentElement;
  		doc.setAttribute('data-useragent', navigator.userAgent);

		/**
		* Wrap tables in wrapper to make them responsive with bootstraps
		* .table-responsive-class
		*/
		$('table').wrap('<div class="table-wrapper">');

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
			$('#s').focus();
		});

		/**
		 * Toggle off canvas navigation
		 */
		$('[data-toggle="offcanvas-bottom"]').on('click', function (e) {
			e.preventDefault();
			var target = $(this).attr('href');
			offcanvas(target, 'bottom');

			// Fix scrolling issues on iOS
			var showMenu = $(target).hasClass('active');
			if(showMenu) {
				disableBodyScroll();
			} else {
				enableBodyScroll();
			}

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

		/**
		* Load more search results via ajax
		*/
		var search = function(type, term, page, callback) {

			$.ajax({

				url: searchparams.ajax_url,
				type: 'GET',
				dataType: 'json',
				data: {
					action: 'sk_search',
					type: type,
					s: term,
					page: page,
					parent: searchparams.post_parent
				}

			}).done( function(data) {

				data = data[type];

				var items = '';

				var source = $('#searchitem-template-' + type);

				if (!source.length) {
					source = $('#searchitem-template-generic');
				}

				var template = Handlebars.compile(source.html());

				data.posts.forEach(function(item) {
					console.log(item);
					items += template(item);
				});

				callback(items, data);

			}).error(function(jqHXR, textStatus, errorThrown) {
				console.log('Error searching', textStatus);
			});

		}

		var currentPage = {};

		$('[data-load-more]').on('click', function(e) {

			var $button = $(this);

			var searchType = $button.data('load-more');

			var $searchContainer = $(this).closest('.search-module');

			var $itemsContainer = $searchContainer.find('ol');
			var $currentCountCountainer = $searchContainer.find('.post-count__count');

			e.preventDefault();

			currentPage[searchType] = parseInt(currentPage[searchType] || searchparams.currentPage);
			var page = currentPage[searchType] += 1;

			search(searchType, searchparams.search_string, page, function(items, result) {

				// Update post count info "Visar x av x"
				var displayedPosts = parseInt($currentCountCountainer.html());
				$currentCountCountainer.html(displayedPosts += result.posts.length);

				// Fade in the new items
				var $items = $(items).hide();
				$itemsContainer.append($items);
				$items.fadeIn();

				// Hide button if we are on last page
				if( result.max_num_pages <= currentPage[searchType]) {
					$button.hide();
				}

			});

		});

		var parentParam = searchparams.post_parent ? '&parent='+searchparams.post_parent : '';

		var bh_params = {
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('title'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,

			remote: {
				url: ajaxdata.ajax_url + '?action=search_suggestions&type=pages&s=%QUERY'+parentParam,
				wildcard: '%QUERY',
				transform: function(response) {
					if(!response) return false;

					var key = Object.keys(response)[0];

					if(!response[key] || !response[key].posts) return false;

					return response[key].posts.slice(0, 3);
				}
			}

		};


	function initTypeahead() {
		// ---------------------------------------------------------

		/*
		* Typeahead
		*/
		var postTemplate       = $('#searchitem-template-posts').html();
		var attachmentTemplate = $('#searchitem-template-attachments').html();
		var contactTemplate    = $('#searchitem-template-contacts').html();

		var typeaheadParams = [{
			 minLength: 3,
			 highlight: true
		}];

		searchparams.post_types.forEach(function(postType) {

			var source_params = bh_params;
			source_params.remote.url = ajaxdata.ajax_url + '?action=search_suggestions&type='+postType.slug+'&s=%QUERY'+parentParam;
			var source = new Bloodhound(source_params);
			var suggestionTemplate = (postType.slug == 'attachment') ? Handlebars.compile(attachmentTemplate) : Handlebars.compile(postTemplate);

			typeaheadParams.push({
			 name: postType.slug + '-result',
			 display: 'title',
			 source: source,
			 limit: Infinity, //Fix for bug causing only two results to show. See https://github.com/twitter/typeahead.js/issues/1232
			 templates: {
					header: '<h3 class="tt-heading">'+postType.label+'</h3>',
					suggestion: suggestionTemplate
				}
		 });

		});

		// Add "Show more" button at the last dataset.
		typeaheadParams[typeaheadParams.length-1].templates.empty = [
				'<div class="search-module__footer">',
				'<a href="/?parent='+searchparams.post_parent+'&s=%QUERY" class="tt-loadmore">Visa fler</a>',
				'<div>'
			].join('\n');

		typeaheadParams[typeaheadParams.length-1].templates.footer = [
				'<div class="search-module__footer">',
				'<a href="/?parent='+searchparams.post_parent+'&s=%QUERY" class="tt-loadmore">Visa fler</a>',
				'<div>'
			].join('\n');

			var $searchInput = $('input[name="s"]');

			$searchInput.typeahead.apply($searchInput, typeaheadParams)

			// Show loading indicator while loading results
			.on('typeahead:asyncrequest', function() {
				$(this).parents('.input-group').find('.input-group-btn').addClass('loading');
			})
			.on('typeahead:asynccancel typeahead:asyncreceive', function() {
				$(this).parents('.input-group').find('.input-group-btn').removeClass('loading');
			});

			$(document).on('click', '.tt-loadmore', function() {
				var $this = $(this),
						query = $('#s').val();
				$this.attr('href', $this.attr('href').replace('%QUERY', query));
			});

		}

		if( $(window).width() > 720 ) {
			initTypeahead();
		}

		function addTypeaheadInDesktop() {
			if( $(this).width() > 720 ) {
				initTypeahead();
				$(this).off('resize', addTypeaheadInDesktop);
				$(this).on('resize', removeTypeaheadInMobile);
			}
		}

		function removeTypeaheadInMobile() {
			if( $(this).width() <= 720 ) {
				$( 'input[name="s"]' ).typeahead('destroy');
				$(this).off('resize', removeTypeaheadInMobile);
				$(this).on('resize', addTypeaheadInDesktop);
			}
		}

		$(window).on('resize', removeTypeaheadInMobile);

		function initNewsSlider() {
			$('#news .widget-latest-news').slick({
				speed: 750,
				adaptiveHeight: true,
				prevArrow: $('#prevslide'),
				nextArrow: $('#nextslide')
			});
		}

		if( $(window).width() <= 720 ) {
			initNewsSlider();
		}

		function removeSliderInDesktop() {
			if( $(this).width() > 720 ) {
				$( '#news .widget-latest-news' ).slick( 'unslick' );
				$(this).on('resize', addSliderInMobile);
				$(this).off('resize', removeSliderInDesktop);
			}
		}

		function addSliderInMobile() {
			if( $(this).width() <= 720 ) {
				initNewsSlider();
				$(this).on('resize', removeSliderInDesktop);
				$(this).off('resize', addSliderInMobile);
			}
		}

		$(window).on( 'resize', addSliderInMobile);



	});


	/**
	 * Fix scrolling issues between fixed navigation and body in iOS.
	 *
	 * Solution from http://stackoverflow.com/a/22041340
	 */
	function document_touchmove(e) {
		e.preventDefault();
	}

	function body_touchstart(e) {
		// If the element is scrollable (content overflows), then...
		if (this.scrollHeight !== this.clientHeight) {
			// If we're at the top, scroll down one pixel to allow scrolling up
			if (this.scrollTop === 0) {
				this.scrollTop = 1;
			}
			// If we're at the bottom, scroll up one pixel to allow scrolling down
			if (this.scrollTop === this.scrollHeight - this.clientHeight) {
				this.scrollTop = this.scrollHeight - this.clientHeight - 1;
			}
		}
		// Check if we can scroll
		this.allowUp = this.scrollTop > 0;
		this.allowDown = this.scrollTop < (this.scrollHeight - this.clientHeight);
		this.lastY = e.originalEvent.pageY;
	}

	function body_touchmove(e) {
		var event = e.originalEvent;
		var up = event.pageY > this.lastY;
		var down = !up;
		this.lastY = event.pageY;

		if ((up && this.allowUp) || (down && this.allowDown)) {
			event.stopPropagation();
		} else {
			event.preventDefault();
		}
	}

	function disableBodyScroll() {
		// Disable scroll for the document, we'll handle it ourselves
		$(document).on('touchmove', document_touchmove);

		// Check if we should allow scrolling up or down
		$(document.body).on("touchstart", ".offcanvas", body_touchstart);

		$(document.body).on('touchmove', ".offcanvas", body_touchmove);
	}

	function enableBodyScroll() {
		$(document).off('touchmove', document_touchmove);
		$(document.body).off("touchstart", ".offcanvas", allowScroll);
		$(document.body).off('touchmove', ".offcanvas", foo);

	}

})(jQuery);
