jQuery(function ($) {
	/*******************************
	 *
	 *
	 * TOTOP FOLLOW SCROLL
	 *
	 *
	 ******************************/
	var $totopWrapper = $('.custom-totop-wrapper').addClass('follow'),
		totopBottom = 60,
		totopHeight = 50,
		following = true;

	var scrollTotopTimer = null;
	$scrollableWrapper.scroll(function() {
		clearTimeout(scrollTotopTimer);
		scrollTotopTimer = setTimeout(scrollTotopHandler, 0);
	});

	function scrollTotopHandler () {
		//totopBottom = 120;
		//if (extraResponsiveSizesTests.tablet || extraResponsiveSizesTests.mobile) {
		//	totopBottom = 60;
		//}

		var position = wHeight - $totopWrapper.offset().top - totopBottom - totopHeight;
		if (position > 0) {
			if (following) {
				following = false;
				$totopWrapper.removeClass('follow');
			}
		} else {
			if (!following) {
				following = true;
				$totopWrapper.addClass('follow');
			}
		}
	}
});