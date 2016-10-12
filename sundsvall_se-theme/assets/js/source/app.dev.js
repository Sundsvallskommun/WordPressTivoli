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

				var source = $('#searchitem-template-'+type).html();
				var template = Handlebars.compile(source);

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

		var pageResult = new Bloodhound({

			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('title'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,

			remote: {
				url: ajaxdata.ajax_url + '?action=search_suggestions&type=pages&s=%QUERY'+parentParam,
				wildcard: '%QUERY',
				transform: function(response) {
					if(!response || !response.pages) return false;
					return response.pages.posts.slice(0, 3);
				}
			}

		});

		var postResult = new Bloodhound({

			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('title'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,

			remote: {
				url: ajaxdata.ajax_url + '?action=search_suggestions&type=posts&s=%QUERY'+parentParam,
				wildcard: '%QUERY',
				transform: function(response) {
					if(!response || !response.posts) return false;
					return response.posts.posts.slice(0, 3);
				}
			}

		});

		var contactResult = new Bloodhound({

			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('title'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,

			remote: {
				url: ajaxdata.ajax_url + '?action=search_suggestions&type=contacts&s=%QUERY'+parentParam,
				wildcard: '%QUERY',
				transform: function(response) { 
					if(!response || !response.contacts) return false;
					return response.contacts.posts.slice(0, 3);
				}
			}

		});

		var mediaResult = new Bloodhound({

			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('title'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,

			remote: {
				url: ajaxdata.ajax_url + '?action=search_suggestions&type=attachments&s=%QUERY'+parentParam,
				wildcard: '%QUERY',
				transform: function(response) { 
					if(!response || !response.attachments) return false;
					return response.attachments.posts.slice(0, 3);
				}
			}

		});

		/*
		* Typeahead
		*/
		var pageTemplate       = $('#searchitem-template-posts').html();
		var postTemplate       = $('#searchitem-template-pages').html();
		var contactTemplate    = $('#searchitem-template-contacts').html();
		var attachmentTemplate = $('#searchitem-template-attachments').html();

		function initTypeahead() {

			$( 'input[name="s"]' ).typeahead({
			 minLength: 3,
			 highlight: true
		 },{
			 name: 'page-result',
			 display: 'title',
			 source: pageResult,
			 limit: Infinity, //Fix for bug causing only two results to show. See https://github.com/twitter/typeahead.js/issues/1232
			 templates: {
					header: '<h3 class="tt-heading">Sidor</h3>',
					suggestion: Handlebars.compile(pageTemplate)
				}
		 },{
			 name: 'post-result',
			 display: 'title',
			 source: postResult,
			 limit: Infinity, //Fix for bug causing only two results to show. See https://github.com/twitter/typeahead.js/issues/1232
			 templates: {
					header: '<h3 class="tt-heading">Nyheter</h3>',
					suggestion: Handlebars.compile(postTemplate)
				}
		 },{
			 name: 'contacts-result',
			 display: 'title',
			 source: contactResult,
			 limit: Infinity, //Fix for bug causing only two results to show. See https://github.com/twitter/typeahead.js/issues/1232
			 templates: {
					header: '<h3 class="tt-heading">Kontakter</h3>',
					suggestion: Handlebars.compile(contactTemplate)
				}
		 },{
			 name: 'media-result',
			 display: 'title',
			 source: mediaResult,
			 limit: Infinity, //Fix for bug causing only two results to show. See https://github.com/twitter/typeahead.js/issues/1232
			 templates: {
					header: '<h3 class="tt-heading">Bilder och dokument</h3>',
					suggestion: Handlebars.compile(attachmentTemplate),
					empty : [
							'<div class="search-module__footer">',
								'<a href="/?parent='+searchparams.post_parent+'&s=%QUERY" class="tt-loadmore">Visa fler</a>',
							'<div>'
						].join('\n'),
					footer : [
							'<div class="search-module__footer">',
								'<a href="/?parent='+searchparams.post_parent+'&s=%QUERY" class="tt-loadmore">Visa fler</a>',
							'<div>'
						].join('\n')
				}
		 }).on('typeahead:asyncrequest', function() {
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


})(jQuery);
