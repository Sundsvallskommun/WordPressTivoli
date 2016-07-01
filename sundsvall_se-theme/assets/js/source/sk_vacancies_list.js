;(function($) {

	'use strict';

	var $vacancies = null,
		$parentEl = null,
		$orderEl = null,
		$categories = null;

	function init() {
		if ( $vacancies === null ) {
			$vacancies = $('.vacancies .vacancy');
			$parentEl = $vacancies.parent();
		}

		if ( $orderEl === null ) {
			$orderEl = $('select.order');
		}

		if ( $categories === null ) {
			$categories = $('.categories .category');
		}

		// Show elements that are hidden for browsers
		// without JS.
		$orderEl.parents('div.input-group').show();
		$categories.parents('div.input-group').show();

		// Bind our events to them.
		bindEvents();
	}

	function bindEvents() {
		$orderEl.on( 'change', function(e) {
			// Don't go to link.
			e.preventDefault();

			// Reload $vacancies.
			// $vacancies = $('.vacancies .vacancy');

			// First, detach from DOM.
			$vacancies.detach();

			// Switch for orderby.
			// By saving the orderby option on $vacancies we can make sure
			// that the browser isn't doing any calculations or operations unncessary
			// by breaking if the list is already ordered in the way the user wants.
			switch ( $(this).val() ) {
				case 'title':
					if ( $vacancies.data( 'orderby' ) === 'title' ) break;
					$vacancies.sort(function(a, b) {
						return $(a).text().toUpperCase().localeCompare( $(b).text().toUpperCase() );
					});
					$vacancies.data( 'orderby', 'title' );
				break;

				case 'dateend':
				if ( $vacancies.data( 'orderby' ) === 'date_end' ) break;
					$vacancies.sort(function(a, b) {
						var dateA = $(a).find( '.last-application-date' ).text().match(/([\d-].*)$/),
							dateB = $(b).find( '.last-application-date' ).text().match(/([\d-].*)$/),
							dateA = Date.parse( dateA[1] ),
							dateB = Date.parse( dateB[1] );
						return ( dateA - dateB );
					});
					$vacancies.data( 'orderby', 'date_end' );
				break;
			}

			// Attach again after ordering.
			$parentEl.append( $vacancies );
		} );

		$categories.on( 'change', function(e) {
			var category = $(this).val();

			// If 'all' show all and return.
			if ( category === 'all' ) {
				$vacancies.show();
				return;
			}
			
			// Loop through all and show the appropriate vacancies.
			$vacancies.each(function(i, v) {
				if ( $(v).data( 'category' ) !== category ) {
					$(v).hide();
				}

				else {
					$(v).show();
				}
			});
		} );
	}

	$( document ).ready( init );

})(jQuery);