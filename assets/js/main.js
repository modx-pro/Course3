$('#news-wrapper').on('click', '#news-pagination a', function() {
	var href = $(this).attr('href');

	if (href != '') {
		var wrapper = $('#news-wrapper');
		wrapper.css('opacity', .5);
		$.get(href, function(res) {
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
		}, 'json');
	}

	return false;
});