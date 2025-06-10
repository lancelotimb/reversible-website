$(document).ready(function() {
	////////////
	//
	// RESPONSIVE NEWS GOODBYE
	//
	////////////
	var $news = $(".push-news"),
		$pushLeft = $(".push-left"),
		$pushRight = $(".push-right"),
		isBig = wWidth > 1730;
	$window.on("extra.resize", function() {
		if(!isBig && wWidth > 1730) {
			isBig = true;
			update();
		} else if(isBig && wWidth <= 1730) {
			isBig = false;
			update();
		}
		update();
	});
	function update() {
		if(isBig) {
			$news.appendTo($pushRight);
		} else {
			$news.appendTo($pushLeft);
		}
	}
	update();
});
$(document).ready(function() {
	////////////
	//
	// MAIN SLIDER
	//
	////////////
	var $slider = $(".front-page-slider"),
		$loader = $('<span class="loader"></span>').prependTo($slider),
		tweenLoader = TweenMax.to($loader, 6, {
			right: '0%',
			ease: Linear.easeNone,
			delay: 1
		});

	$slider.extraSlider({
		auto: small ? false : 7,
		draggable: true,
		dragWindow: true,
		dragWindowObject: $("#scrollable-wrapper"),
		type: 'slide',
		pagination: $slider.find('.pagination'),
		navigate: false,
		speed: 1,
		onPause: onPause,
		onResume: onResume,
		onMoveStart: onMoveStartMainSlider,
		onDragEnd: onMoveStartMainSlider,
		onDragRepositioned: onMoveEnd,
		onMoveEnd: onMoveEnd
	});

	$window.on('complete.extra.responsiveImage', function () {
		$slider.trigger('update');
	});

	$window.on('extra.responsive-resize', function() {
		$slider.trigger('auto', small ? false : 7);
	});

	function onMoveStartMainSlider($current, totalItems, slider) {
		if(!small) {
			tweenLoader = TweenMax.to($loader, 1, {right: '100%', ease: Quad.easeOut});
		}

		var $realCurrent = $slider.find(".front-page-slider-slide.active");
		TweenMax.to($slider.find(".front-page-slider-content-title"), 1, {
			x: '0%',
			ease: Quad.easeOut
		});
	}
	function onMoveEnd($current, total, slider) {
		if(!small) {
			tweenLoader = TweenMax.to($loader, 6, {right: '0%', ease: Linear.easeNone});
		}
		resetSlides();
	}
	function resetSlides() {
		$slider.find(".front-page-slider-slide").not('.active').each(function() {
			TweenMax.set($(this).find('.front-page-slider-content-title'), {x: '-80%'});
		});
	}
	resetSlides();

	function onPause() {
		if(!small) {
			tweenLoader.pause();
		}
	}
	function onResume() {
		if(!small) {
			tweenLoader.play();
		}
	}


});

$(document).ready(function() {
	////////////
	//
	// MATERIALS
	//
	////////////
	var $slider = $(".front-page-materials"),
		$slides = $slider.find(".front-page-materials-slide");
	$slider.extraSlider({
		activeOnEnd: false,
		forcedDimensions: true,
		type: 'custom',
		navigate: $slider.find('.navigation'),
		speed: 0.3,
		paginate: false,
		onUpdate: onUpdate,
		onInit: onMoveStart,
		onMoveStart: onMoveStart
	});

	$window.on('complete.extra.responsiveImage', function () {
		$slider.trigger('update');
	});

	function onUpdate($current, totalItems, slider) {
		TweenMax.delayedCall(0.1, function() {
			resize(0, $current, totalItems, slider);
		});
	}
	function onMoveStart($current, totalItems, slider) {
		resize(1, $current, totalItems, slider);
	}
	function resize(speed, $current, totalItems, slider) {

		var margin = (small == true) ? 0 : 270;

		var _width = slider.width(),
			_height = slider.height();

		var $next = $current.next();
		if(!$next.length) {
			$next = $slides.first();
		}

		var $prev = $current.prev();
		if(!$prev.length) {
			$prev = $slides.last();
		}
		$prev.css("zIndex", 1);

		//console.log($current);

		var $others = $slides.not($current).not($prev);
		//////////////////////////

		// PREVIOUS  SLIDES

		//////////////////////////
		TweenMax.set($others, {
			clip: 'rect(0px, ' + _width + 'px, ' + _height + 'px, ' + (_width - margin) + 'px)',
			zIndex: 3
		});
		TweenMax.set($others.find('img'), {
			x: 150
		});

		//////////////////////////

		// NEXT SLIDE

		//////////////////////////
		$next.css("zIndex", 4);
		//////////////////////////

		// CURRENT SLIDE

		//////////////////////////
		$current.css("zIndex", 5);
		TweenMax.to($current, speed, {
			clip: 'rect(0px, ' + (_width - margin) + 'px, ' + _height + 'px, 0px)',
			ease: Strong.easeOut,
			onComplete: function($current, $next) {
				$current.css("zIndex", 2);
				$next.css("zIndex", 5);
			},
			onCompleteParams: [$current, $next]
		});
		TweenMax.to($current.find('img'), speed, {
			ease: Strong.easeOut,
			x: 0
		});
	}
});