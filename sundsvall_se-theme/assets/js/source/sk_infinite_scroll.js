;(function($) {

	var buttonLabel = 'Fler nyheter';

	var isLoading = true; // Only activate infinite scroll if user has initiated load more.

	var $loadMoreBtn = $('<button class="load-more btn btn-secondary text-center">' + buttonLabel + '</button>')
	var $paginationBtn = $('.infinite-nav .next');

	var hasMorePages = $paginationBtn.length >= 1;

	// Initiate, hide pagination and add ajax-load button
	if ( hasMorePages ) {

		var loadMoreUrl = $paginationBtn.attr('href');

		$loadMoreBtn.on( 'click', ajaxLoadMore);

		$('.infinite-nav').children().hide();
		$('.infinite-nav').append($loadMoreBtn);

	}


	function ajaxLoadMore(e) {

		console.log('load/more');

		e && e.preventDefault();

		$loadMoreBtn.html('Laddar…');

		isLoading = true;

		var req = $.get( loadMoreUrl );
		
		req.success( function( data ) {

			$loadMoreBtn.html(buttonLabel);

			var newPosts = $(data).find('.posts').children('div');
			var nextLink = $(data).find('.infinite-nav .next');

			if(nextLink.length >= 1) {
				loadMoreUrl = $(data).find('.infinite-nav .next').attr('href');
			} else {
				$loadMoreBtn.remove();
				hasMorePages = false;
			}

			$('.posts').append(newPosts);

			isLoading = false;

		});

		req.error( function( jqXHR, textStatus, errorThrown ) {
			$loadMoreBtn.html('Något gick fel, testa igen…');
		});

	}

	$(window).scroll(function() {

		if(isLoading || !hasMorePages) return;

		var $lastPost = $('.posts > div:last');
		var lastPostBottom = $lastPost.offset().top + $lastPost.height();

		if( ($(window).scrollTop() + $(window).height()) > lastPostBottom ) {
			ajaxLoadMore();
		}

	});

})(jQuery);
