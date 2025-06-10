jQuery(function ($) {
	var $items = $('.extra-about-gallery > li');

	$items.on('click', function (event){
		event.preventDefault();

		$items.removeClass('active');
		$(this).addClass('active');
	});
});