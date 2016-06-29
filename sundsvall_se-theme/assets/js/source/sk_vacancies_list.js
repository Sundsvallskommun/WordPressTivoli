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
						var dateA = Date.parse( $(a).find('.last-application-date').text() ),
							dateB = Date.parse( $(b).find('.last-application-date').text() );
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