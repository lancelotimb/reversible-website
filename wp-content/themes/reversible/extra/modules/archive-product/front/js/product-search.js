jQuery(function ($) {
	/*******************************
	 *
	 *
	 * SEARCHING
	 *
	 *
	 ******************************/
	var $searchBox = $('.product-search-bloc'),
		$searchLabel = $searchBox.find('.product-search-label'),
		$searchInput = $searchBox.find('#product-search-input'),
		searchInputTimer = null;
	$searchInput.on('focus', function () {
		$searchBox.addClass('focus');
	});
	$searchInput.on('blur', function () {
		if ($searchInput.val() == '') {
			$searchBox.addClass('empty');
		} else {
			$searchBox.removeClass('empty');
		}
		$searchBox.removeClass('focus');
	});
	$searchInput.on('keyup', function (event) {
		if ($searchInput.val() == '') {
			$searchBox.addClass('empty');
		} else {
			$searchBox.removeClass('empty');
		}
		if (event.keyCode == 13) { // ENTER
			searchInputHandler();
		} else {
			clearTimeout(searchInputTimer);
			searchInputTimer = setTimeout(searchInputHandler, 500);
		}
	});
	$searchLabel.on('click', function (event) {
		if (!$searchBox.hasClass('empty')) {
			event.preventDefault();
			$searchInput.val('');
			$searchBox.addClass('empty');
			searchInputHandler();
		}
	});

	function searchInputHandler() {
		$window.trigger('extra-productSearch', [$searchInput.val()]);
	}
	$window.on('extra-fillProductSearch', function (event, value) {
		$searchInput.val(value);
		if ($searchInput.val() == '') {
			$searchBox.addClass('empty');
		} else {
			$searchBox.removeClass('empty');
		}
	});
});