;(function($) {

	$(document).ready(function() {

		$(window).on( 'resize', calendarImageSize);

		$(window).load( function() {
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
