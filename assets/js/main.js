var oldBrowser = !(window.history && history.pushState);
if (!oldBrowser) {
	history.replaceState({pagination: window.location.href}, '');
}

function loadPage(href) {
	var wrapper = $('#news-wrapper');
	wrapper.css('opacity', .5);
	$.ajax({
		dataType: 'json',
		url: href,
		cache: false,
		success: function(res) {
			wrapper.css('opacity', 1);
			if (res.success) {
				$('#news-items').html(res.data['items']);
				$('#news-pagination').html(res.data['pagination']);
			}
			else if (res.data['redirect']) {
				window.location = res.data['redirect'];
			}
			else {
				console.log(res);
			}
		}
	});
}

$('#news-wrapper').on('click', '#news-pagination a', function() {
	var href = $(this).attr('href');

	if (href != '') {
		if (!oldBrowser) {
			window.history.pushState({pagination: href}, '', href);
		}
		loadPage(href);
	}

	return false;
});

$(window).on('popstate', function(e) {
	if (e.originalEvent.state && e.originalEvent.state['pagination']) {
		loadPage(e.originalEvent.state['pagination']);
	}
});