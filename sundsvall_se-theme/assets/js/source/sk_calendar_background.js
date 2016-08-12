;(function($) {

	if ( $('#citybreak_event_calendar_widget').length < 1 ) return; // Only run this script if we are on page with calendar

	$(document).ready(function() {

		$(window).on( 'resize', calendarImageSize);

		// Update size when calendar has changed (loaded)
		$('#citybreak_event_calendar_widget').on( 'DOMSubtreeModified', calendarImageSize);

		$(window).load( function() {
			// We use this as a fallback if DOMSubtreeModified is unsupported.
			setTimeout(calendarImageSize, 1000);
		});

		var calImg = $('.calendar-image');
		var calInfo = $('.calendar-left');
		var calContainer = $('.front-page-section__calendar');

		function calendarImageSize() {

			var offset = calInfo.offset();

			calImg.css('top', '0');
			calImg.css('left', '0');
			calImg.width(offset.left + calInfo.width());
			calImg.height(calContainer.height());

		}

	});

})(jQuery);
