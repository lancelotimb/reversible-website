jQuery(function ($) {
	/***********************
	 *
	 *
	 * ON SCROLL SHARE FOLLOW
	 *
	 *
	 **********************/
	var scrollTimer = null,
		$mainWrapper = $('.main-wrapper'),
		$socialWrapper = $('.extra-social-wrapper'),
		$postContent = $('.post-content'),
		$postHeader = $('.post-header'),
		mainWrapperHeight,
		socialWrapperHeight,
		postHeaderPosition,
		widthMin = 1351,
		tween = null,
		min = 235,
		max,
		deltaTop = 80;

	$scrollableWrapper.on('scroll', function () {
		clearTimeout(scrollTimer);
		scrollTimer = setTimeout(positionSocialWrapper, 0);
	});

	$window.on('extra.resize', function () {
		initSocial();
		positionSocialWrapper();
	});

	function initSocial () {
		mainWrapperHeight = $mainWrapper.outerHeight();
		socialWrapperHeight = $socialWrapper.outerHeight();
		postHeaderPosition = $postHeader.position();

		max = mainWrapperHeight - socialWrapperHeight - postHeaderPosition.top - deltaTop;
	}

	function positionSocialWrapper () {
		if (window.matchMedia('(min-width: ' + widthMin + 'px)').matches) {
			var scrollTop = $scrollableWrapper.scrollTop(),
				scrollTopMin = postHeaderPosition.top + min - deltaTop;

			if (scrollTop > scrollTopMin) {
				var dest = Math.min(max, Math.max(min, scrollTop - postHeaderPosition.top + deltaTop));

				if (tween) {
					tween.kill();
				}
				tween = TweenMax.set($socialWrapper, {top: dest});

			} else {
				tween = TweenMax.set($socialWrapper, {top: min, clearProps: 'all'});
			}
		} else {
			$socialWrapper.css('top', '');
		}
	}


	/***********************
	 *
	 *
	 * RESPOND COMMENTS
	 *
	 *
	 **********************/
	var $addCommentLink = $('.add-comment-link'),
		$respondWrapper = $('.respond-wrapper'),
		commentScrollTop = $('.main-content').outerHeight() + $('.commentlist').outerHeight(),
		opened = false;

	var timeline = new TimelineMax({paused: true});
	timeline.from($respondWrapper, 0.6, {height: 0});
	timeline.to($scrollableWrapper, 0.5, {scrollTo: {y: commentScrollTop}});
	timeline.addCallback(function () {
		initSocial();
		positionSocialWrapper();
	});

	$addCommentLink.on('click', function (event) {
		event.preventDefault();
		if (!opened) {
			opened = true;
			timeline.resume();
		} else {
			commentScrollTop = $('.main-content').outerHeight() + $('.commentlist').outerHeight();
			TweenMax.to($scrollableWrapper, 0.5, {scrollTo: {y: commentScrollTop}});
		}
	});

	setTimeout(function () {
		initSocial();
	}, 100);
});