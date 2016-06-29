;(function($) {

	'use strict';

	var $vacancies = null,
		$parentEl = null,
		$orderOption = null;

	function init() {
		if ( $vacancies === null ) {
			$vacancies = $('.vacancies .vacancy');
			$parentEl = $vacancies.parent();
		}

		if ( $orderOption === null ) {
			$orderOption = $('a.order-option');
		}

		bindEvents();
	}

	function bindEvents() {
		$orderOption.on( 'click', function(e) {
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
			switch ( $(this).data( 'orderby' ) ) {
				case 'title':
					if ( $vacancies.data( 'orderby' ) === 'title' ) break;
					$vacancies.sort(function(a, b) {
						return $(a).text().toUpperCase().localeCompare( $(b).text().toUpperCase() );
					});
					$vacancies.data( 'orderby', 'title' );
				break;

				case 'date_end':
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
	}

	$( document ).ready( init );

})(jQuery);