/*********************
 *
 * WEBFONT LOADER
 *
 *********************/
WebFontConfig = {
	typekit: {
		id:
			'nvt0qrc'
	}
};

/*********************
 *
 * REQUEST ANIMATION SHIM
 *
 *********************/
(function() {
	var lastTime = 0;
	var vendors = ['ms', 'moz', 'webkit', 'o'];
	for(var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
		window.requestAnimationFrame = window[vendors[x]+'RequestAnimationFrame'];
		window.cancelAnimationFrame = window[vendors[x]+'CancelAnimationFrame']
		|| window[vendors[x]+'CancelRequestAnimationFrame'];
	}

	if (!window.requestAnimationFrame)
		window.requestAnimationFrame = function(callback, element) {
			var currTime = new Date().getTime();
			var timeToCall = Math.max(0, 16 - (currTime - lastTime));
			var id = window.setTimeout(function() { callback(currTime + timeToCall); },
				timeToCall);
			lastTime = currTime + timeToCall;
			return id;
		};

	if (!window.cancelAnimationFrame)
		window.cancelAnimationFrame = function(id) {
			clearTimeout(id);
		};
}());


/*********************
 *
 * JQUERY START
 *
 *********************/

var extraScrollBarWidth = 0,
	$scrollableWrapper,
	$scrollable,
	$wrapper,
	$header;

// OVERRIDE EXTRA SCRIPTS OPTIONS
extraOptions.zoomIcon = '<span class="zoom-icon-wrapper"><svg class="icon icon-search"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-search"></use></svg></span>';
extraOptions.fancyboxOptions = {
	margin: 50,
	padding: 0,
	type: 'image',
	helpers: {
		title: {
			type: 'over'
		}
	},
	beforeLoad: function() {
		var $captionText = $(this.element).next('.wp-caption-text');
		if ($captionText.size() == 0) {
			$captionText = $(this.element).find('.wp-caption-text');
		}
		if ($captionText.size() > 0) {
			this.title = $captionText.text();
		}
	},
	tpl: {
		prev : '<a title="Précédent" class="fancybox-nav fancybox-prev" href="javascript:;"><span class="extra-button"><svg class="icon arrow-left"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#arrow-left"></use></svg></span></a>',
		next : '<a title="Suivant" class="fancybox-nav fancybox-next" href="javascript:;"><span class="extra-button"><svg class="icon arrow-right"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#arrow-right"></use></svg></span></a>',
		closeBtn: '<a title="Fermer" class="fancybox-item fancybox-close extra-button" href="http://dev.extralagence.com/www.reversible.fr/panier/" title="Voir mon panier"><svg class="icon icon-close"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-close"></use></svg></a>'
	}
};

$(document).ready(function(){
	var $overlay = $('<div id="overlay"></div>').prependTo($('body')),
		$menuButton = $('#switch-mobile-menu'),
		$menuPart1 = $menuButton.find('.part-1'),
		$menuPart2 = $menuButton.find('.part-2'),
		$menuPart3 = $menuButton.find('.part-3');
	$scrollableWrapper = $('#scrollable-wrapper');
	$scrollable = $('#scrollable');
	$wrapper = $('#wrapper');
	$header = $('#header');

	$window.on('extra:menu:ShowComplete', forceMenuVisibleOnDesktop);
	$window.on('extra:menu:HideComplete', forceMenuVisibleOnDesktop);
	function forceMenuVisibleOnDesktop() {
		if (!small) {
			TweenMax.set($header, {clearProps: 'transform'});
		}
	}

	$header.extraMenu(
		{
			resizeEvent: 'extra.resize',
			$site: $wrapper,
			$button: $menuButton,
			moveButton: false,
			moveSite: false,
			breakPointWidth: 1024,
			//autoHide: false,
			prependTimeline: function (timeline) {

				timeline.set($overlay, {display: 'block', autoAlpha: 0});
				timeline.to($overlay, 0.3, {autoAlpha: 1});

				return timeline;
			},
			appendTimeline: function (timeline) {
				timeline.to([$menuPart1, $menuPart3], 0.3, {top: 5}, '-=0.6');
				timeline.set($menuPart2, {autoAlpha: 0});
				timeline.to($menuPart1, 0.3, {rotation: 45});
				timeline.to($menuPart3, 0.3, {rotation: 135}, '-=0.3');
				timeline.set($header, {clearProps: 'transform'});

				return timeline;
			}
		}
	);
	$('html').addClass('responsive-menu');

	function toTopForMenu() {
		TweenMax.set($scrollableWrapper, {scrollTo: {y: 0}});
	}
	$window.on('extra:menu:ShowStart', toTopForMenu);
	$window.on('extra:menu:HideStart', toTopForMenu);
	forceMenuVisibleOnDesktop();


	$overlay.on('click', function (event) {
		event.preventDefault();
		$header.trigger('extra:menu:hide');
	});
	$window.on('extra.resize', function () {
		if(!small) {
			$header.trigger('extra:menu:hide', [true]);
		}
	});


	function getScrollbarWidth() {
		var outer = document.createElement("div");
		outer.style.visibility = "hidden";
		outer.style.width = "100px";
		outer.style.msOverflowStyle = "scrollbar"; // needed for WinJS apps

		document.body.appendChild(outer);

		var widthNoScroll = outer.offsetWidth;
		// force scrollbars
		outer.style.overflow = "scroll";

		// add innerdiv
		var inner = document.createElement("div");
		inner.style.width = "100%";
		outer.appendChild(inner);

		var widthWithScroll = inner.offsetWidth;

		// remove divs
		outer.parentNode.removeChild(outer);

		return widthNoScroll - widthWithScroll;
	}
	extraScrollBarWidth = getScrollbarWidth();
	//TweenMax.set($('#overlay'), {right: extraScrollBarWidth});

	/*********************
	 *
	 *
	 * INIT BORDER FILL
	 *
	 *
	 *********************/
	$('.border-fill').each(extraInitBorderFill);
	/*********************
	 *
	 *
	 * LOGO
	 *
	 *
	 *********************/
	var $logoLink = $('.site-title > a'),
		$logoSubtitle = $logoLink.find('.subtitle'),
		timeline = new TimelineMax();
	$logoLink.on('mouseenter', function () {
		var speed = 0.1;
		timeline.kill();
		timeline = new TimelineMax({repeat: -1});
		//timeline.set($logoSubtitle, {color: '#0097a7'});
		timeline.set($logoSubtitle, {color: '#FF0000'});
		timeline.set($logoSubtitle, {color: '#12B2A4'}, '+='+speed);
		//timeline.set($logoSubtitle, {color: '#FFFF00'}, '+='+speed);
		timeline.set($logoSubtitle, {color: '#FF19E6'}, '+='+speed);
		timeline.set($logoSubtitle, {color: '#ff6e40'}, '+='+speed);
		timeline.set($logoSubtitle, {color: '#7812B2'}, '+='+speed);
		timeline.set($logoSubtitle, {color: '#00FFE8'}, '+='+speed);
		timeline.set($logoSubtitle, {color: '#CCA914'}, '+='+speed);
		timeline.set($logoSubtitle, {color: '#00FFFF'}, '+='+speed);
		timeline.set($logoSubtitle, {color: '#FFAF19'}, '+='+speed);
		timeline.set($logoSubtitle, {color: '#B2A982'}, '+='+speed);
		timeline.set($logoSubtitle, {color: '#2979ff'}, '+='+speed);
		timeline.set($logoSubtitle, {color: '#00FF00'}, '+='+speed);
		timeline.set($logoSubtitle, {color: '#12B263'}, '+='+speed);
		timeline.set($logoSubtitle, {color: '#A200FF'}, '+='+speed);
		timeline.set($logoSubtitle, {color: '#C757FF'}, '+='+speed);
		//timeline.set($logoSubtitle, {color: '#b2ff59'}, '+='+speed);
		timeline.set($logoSubtitle, {color: '#0000FF'}, '+='+speed);
		timeline.set($logoSubtitle, {color: '#14CC6B'}, '+='+speed);
		timeline.set($logoSubtitle, {color: '#00FF80'}, '+='+speed);
		timeline.set($logoSubtitle, {color: '#9c27b0'}, '+='+speed);
		timeline.set($logoSubtitle, {color: '#FF00FF'}, '+='+speed);
		timeline.set($logoSubtitle, {color: '#212121'}, '+='+speed);
		timeline.set($logoSubtitle, {color: '#CC8E14'}, '+='+speed);
		timeline.set($logoSubtitle, {color: '#67B28A'}, '+='+speed);
		timeline.set($logoSubtitle, {color: '#B2A282'}, '+='+speed);
		timeline.set($logoSubtitle, {color: '#FF0000'}, '+='+speed);

	});
	$logoLink.on('mouseleave', function () {
		timeline.kill();
		timeline = new TimelineMax();
		timeline.set($logoSubtitle, {color: '#0097a7', clearProps: 'color'});
	});


	/*********************
	 *
	 *
	 * MAIN SCROLLBAR
	 *
	 *
	 *********************/
	extraCheckHasMainScrollBar();

	$window.on('extra.resize', resizeHandler);
	function resizeHandler() {
		extraCheckHasMainScrollBar();
	}

	/*********************
	 *
	 * BACK TO TOP
	 *
	 *********************/
	$(".custom-totop").click(function () {
		TweenMax.to($scrollableWrapper, 0.5, {scrollTo: {y: 0}});
		return false;
	});

	/*********************
	 *
	 * TO CONTENT
	 *
	 *********************/
	$(".to-content-link").on("click", function() {
		TweenMax.to($scrollableWrapper, 0.5, {scrollTo: {y: $(".main-image-wrapper").height() + $(".main-image-wrapper").offset().top}});
		return false;
	});

	/*********************
	 *
	 * SCROLL TO ANCHOR
	 *
	 *********************/
	$('.scroll-to-anchor').on('click', function (event) {
		var urlArray = $(this).attr('href').split('#');
		if (urlArray.length > 1) {
			var targetId = urlArray[1],
				$target = $('#'+targetId);
			if ($target.length > 0) {
				event.preventDefault();
				TweenMax.to($scrollableWrapper, 0.5, {scrollTo: {y: $target[0].offsetTop}});
			}
		}
	});

	/*********************
	 *
	 * BACK BUTTON
	 *
	 *********************/
	//$(".back-button").click(function (event) {
	//	if (history) {
	//		event.preventDefault();
	//		history.back();
	//	}
	//});

	/*********************
	 *
	 * FIXED HEADER
	 *
	 *********************/
	$("#wrapper .main-image-wrapper .main-image").each(function() {
		var $image = $(this);
		$window.on("extra.resize", update);
		$image.on("complete.extra.responsiveImage", update);
		function update(e) {
			if(!Modernizr.touch) {
				if(wWidth < 1920) {
					$image.css({
						backgroundImage: 'url(' + $image.find('img').first().attr('src') + ')',
						backgroundSize: 'auto ' + $image.height() + 'px',
						//backgroundPosition: $image.offset().left + 'px ' + '0px'
						backgroundPosition: 'top right'
					});
				} else {
					$image.css({
						backgroundImage: 'url(' + $image.find('img').first().attr('src') + ')',
						backgroundSize: '100% auto',
						//backgroundPosition: $image.offset().left + 'px ' + '0px'
						backgroundPosition: 'top right'
					});
				}
			}
		}
	});
});


/*********************
 *
 *
 * LOADER
 *
 *
 *********************/
function extraLoaderPlay($element) {
	var $loader = $element.find('> .extra-loader');
	if ($loader.size() == 0) {
		$loader = $('<span class="extra-loader"><span class="inner"><span class="part part-1"></span><span class="part part-2"></span><span class="part part-3"></span></span></span>').appendTo($element);
		TweenMax.set($loader, {autoAlpha: 0});
	}

	var timelines = $loader.data('loader-timelines'),
		tween = $loader.data('loader-tween'),
		$part1 = $loader.find('.part-1'),
		$part2 = $loader.find('.part-2'),
		$part3 = $loader.find('.part-3');
	if (timelines && timelines.length > 0) {
		for (key in timelines) {
			var timeline = timelines[key];
			timeline.kill();
		}
	}
	if (tween) {
		tween.kill();
	}

	timelines = [];

	// @private
	function blink($part, delay) {
		var timelinePart = new TimelineMax({repeat: -1, delay: delay});
		timelinePart.to($part, 0.5, {opacity: 0});
		timelinePart.to($part, 0.5, {opacity: 0.7});

		return timelinePart;
	}
	timelines.push(blink($part1, 0));
	timelines.push(blink($part2, 0.25));
	timelines.push(blink($part3, 0.5));
	tween = TweenMax.to($loader, 0.3, {autoAlpha: 1});

	$loader.data('loader-timelines', timelines);
	$loader.data('loader-tween', tween);
}

function extraLoaderStop($element) {
	var $loader = $element.find('> .extra-loader');
	if ($loader.size() > 0) {
		var timelines = $loader.data('loader-timelines'),
			tween = $loader.data('loader-tween');
		if (timelines && timelines.length > 0) {
			for (key in timelines) {
				var timeline = timelines[key];
				timeline.kill();
			}
		}
		if (tween) {
			tween.kill();
		}
		TweenMax.set($loader, {autoAlpha: 0});
	}
}

function extraCheckHasMainScrollBar () {
	if ($wrapper.outerHeight() > wHeight || $header.outerHeight() > wHeight) {
		$scrollable.css('margin-right', -extraScrollBarWidth);
	} else {
		$scrollable.css('margin-right', '');
	}
}

/*********************
 *
 *
 * BORDER FILL
 *
 *
 *********************/
function extraInitBorderFill () {
	var $wrapper = $(this),
		$square = $('<span class="square over"></span>').prependTo($wrapper),
		$default = $('<span class="square default"></span>').prependTo($wrapper),
		width = $square.outerWidth(),
		hight= $square.outerWidth(),
		timeline = new TimelineMax({paused: true});

	timeline.set($square, {
		clip: "rect(100px 0px 100px 0px)"
	});

	timeline.to($square, 0.3, {
		opacity: 0
	});
	timeline.set($square, {
		// rect(top, right, bottom, left)
		clip: $square.data('border-fill-leave'),
		opacity: 1
	});

	$wrapper.on('mouseenter', function() {

	});

	function initBorderFill($square) {
		var borderWidth = $square.outerWidth(),
			borderHeight = $square.outerHeight(),
			max = Math.ceil(Math.sqrt(Math.pow(borderWidth, 2) + Math.pow(borderHeight, 2))),
			min = Math.ceil((max - borderWidth) / 2),
		// rect(top, right, bottom, left)
			leaveRect = 'rect(-'+min+'px -'+min+'px '+max+'px -'+min+'px)',
		// rect(top, right, bottom, left)
			overRect = 'rect(-'+min+'px '+max+'px '+max+'px -'+min+'px)';

		$square.data('border-fill-leave', leaveRect);
		$square.data('border-fill-over', overRect);
	}

	$(document).on('mouseleave', '.border-fill', function () {
		var $border = $(this);
		initBorderFill($border);
		var $square = $border.find('.square.over'),
			timeline = $square.data('square-timeline');
		if (timeline) {
			timeline.kill();
		} else {
			// First
			initBorderFill($square);
		}
		timeline = new TimelineMax();
		timeline.to($square, 0.3, {
			opacity: 0
		});
		timeline.set($square, {
			// rect(top, right, bottom, left)
			clip: $square.data('border-fill-leave'),
			opacity: 1
		});
		$square.data('square-timeline', timeline);
	});

	$(document).on('mouseenter', '.border-fill', function () {
		var $border = $(this);
		initBorderFill($border);
		var $square = $border.find('.square.over'),
			timeline = $square.data('square-timeline');
		if (timeline) {
			timeline.kill();
			timeline = new TimelineMax();
		} else {
			// First
			initBorderFill($square);
			timeline = new TimelineMax();
		}
		timeline.set($square, {
			// rect(top, right, bottom, left)
			clip: $square.data('border-fill-leave'),
			opacity: 1
		});
		timeline.to($square, ($border.data('border-fill-speed')) ? $border.data('border-fill-speed') : 0.5, {
			// rect(top, right, bottom, left)
			clip: $square.data('border-fill-over')
		});
		$square.data('square-timeline', timeline);
	});
}