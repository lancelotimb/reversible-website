jQuery(function ($) {

	/***************************
	 *
	 *
	 * QUANTITY SELECTOR
	 *
	 *
	 **************************/
	var $quantity = $('.quantity'),
		$quantityInput = $quantity.find('input.qty');
	if ($quantityInput.size() > 0) {

		var min = 0,
			max = -1;

		if ($quantityInput.data('min')) {
			min = $quantityInput.data('min');
		}
		if ($quantityInput.data('max')) {
			max = $quantityInput.data('max');
		}

		if (min < max) {
			$quantityInput.before('<button type="button" class="quantity-button quantity-less">-</button>');
			//$quantity.append('<span class="quantity-value">'+$quantityInput.val()+'</span>');
			$quantityInput.after('<button type="button" class="quantity-button quantity-more">+</button>');

			var $quantityLessButton = $quantity.find('.quantity-less'),
				$quantityMoreButton = $quantity.find('.quantity-more'),
				$quantityValue = $quantity.find('.quantity-value');

			$quantityLessButton.on('click', function (event) {
				event.preventDefault();
				var newVal = parseInt($quantityInput.val()) - 1;
				if (newVal >= min) {
					$quantityInput.val(newVal);
				}
				$quantityValue.html($quantityInput.val());
			});
			$quantityMoreButton.on('click', function (event) {
				event.preventDefault();
				var newVal = parseInt($quantityInput.val()) + 1;

				if (max && newVal <= max) {
					$quantityInput.val(newVal);
				}
				$quantityValue.html($quantityInput.val());
			});

			$quantityInput.on('keypress', function (event) {
				if(event.which != 13) {
					var regex = new RegExp("^[0-9]+$");
					var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
					if (!regex.test(key)) {
						event.preventDefault();
						return false;
					}
				}
			});

			$quantity.addClass('initialized');
		} else {
			$quantity.addClass('disabled');
		}


	}

	/***************************
	 *
	 *
	 * ANCHORED SUMMARY
	 *
	 *
	 **************************/
	var $product = $('.product'),
		$anchor = $product.find('.summary-anchor'),
		$lastAnchor = $product.find('.small-images .small-image-wrapper:last-child .summary-anchor'),
		$previousMax = null,
		$summary = $product.find('.summary'),
		timeline = null,
		follow = true;

	if ($lastAnchor.size() == 0) {
		$lastAnchor = $product.find('.big-images .easyzoom:last-child .summary-anchor');
	}

	function checkSummaryAnchor() {
		if (follow) {
			var $max = null,
				max = 0;

			$anchor.each(function () {
				var $this = $(this),
					fracs = $this.fracs();

				if (fracs.visible > max) {
					max = fracs.visible;
					$max = $this;
				}
			});

			if ($max !== null && ($previousMax === null || $max[0] != $previousMax[0])) {
				$previousMax = $max;

				moveToAnchor($previousMax);
			}
		}
	}

	function getAnchorY($anchorTarget) {
		//console.log($anchorTarget);
		var anchorY = null;
		if ($anchorTarget.size() > 0) {
			anchorY = $anchorTarget[0].offsetTop;
			if ($anchorTarget.hasClass('big-image')) {
				anchorY += $anchorTarget[0].offsetParent.offsetTop;
				anchorY += $anchorTarget[0].offsetParent.offsetParent.offsetTop;
			} else if ($anchorTarget.hasClass('small-image')) {
				anchorY += $anchorTarget[0].offsetParent.offsetTop;
				anchorY += $anchorTarget[0].offsetParent.offsetParent.offsetTop;
			}
		} else {
			console.log($anchorTarget);
		}

		return anchorY;
	}

	function moveToAnchor($anchorTarget) {
		if ($lastAnchor.size() > 0) {
			var lastAnchorY = getAnchorY($lastAnchor),
				maxY = lastAnchorY + $lastAnchor.outerHeight(true) - $summary.outerHeight(true),
				anchorY = Math.min(maxY, getAnchorY($anchorTarget));

			if (timeline == null) {
				// First behavior
				timeline = new TimelineMax();
				timeline.set($summary, {top: 0, y: anchorY});
			} else {
				// Default behavior
				timeline.kill();
				timeline = new TimelineMax();
				timeline.to($summary, 0.5, {y: anchorY});
			}
		}
	}

	$scrollableWrapper.on('scroll', checkSummaryAnchor);
	function preCheckSummaryAnchor () {
		var minHeight = $summary.outerHeight(true) + ($summary.outerHeight(true) / 3);
		if (window.matchMedia('(max-height: '+minHeight+'px)').matches || window.matchMedia('(max-width: 1280px)').matches) {
			follow = false;
			// remove summary move
			$summary.attr('style', '');
			timeline = null;
		} else {
			follow = true;
			checkSummaryAnchor();
		}
	}
	$window.on('extra.resize', preCheckSummaryAnchor);
	preCheckSummaryAnchor();
	/***************************
	 *
	 *
	 * SMALL IMAGE DETAIL
	 *
	 *
	 **************************/
	//var $smallImages = $('.small-images'),
	//	$smallImageLinks = $('.small-image-link'),
	//	$smallImageCloseLink = $('.small-image-close'),
	//	smallImageTimeline = new TimelineMax();
	//
	//$smallImageLinks.on('click', function (event) {
	//	event.preventDefault();
	//	console.log('small image click');
	//
	//	var $this = $(this),
	//		$smallImageWrapper = $this.closest('.small-image-wrapper'),
	//		$smallImage = $smallImageWrapper.find('.small-image'),
	//		$smallImageLink = $smallImageWrapper.find('.small-image-link');
	//
	//	smallImageTimeline.progress(0);
	//	smallImageTimeline.kill();
	//	smallImageTimeline = new TimelineMax({
	//		onReverseComplete: function () {
	//			$smallImages.removeClass('show-detail');
	//		}
	//	});
	//
	//	smallImageTimeline.set($smallImageWrapper, {zIndex: 1});
	//	smallImageTimeline.to($smallImageLink, 0.3, {autoAlpha: 0});
	//	smallImageTimeline.addCallback(function () {
	//		$smallImages.addClass('show-detail');
	//	});
	//	smallImageTimeline.to($smallImage, 0.5, {width: 600, height: 600}, '-=0.2');
	//	smallImageTimeline.to($smallImageCloseLink, 0.3, {x: -60, y: 60});
	//});
	//
	//$smallImageCloseLink.on('click', function (event) {
	//	event.preventDefault();
	//	console.log('close click');
	//	smallImageTimeline.reverse();
	//});

	/***************************
	 *
	 *
	 * RELATED PRODUCTS
	 *
	 *
	 **************************/
	extraDefineProductByRow();

	/***************************
	 *
	 *
	 * IMAGE ZOOMS
	 *
	 *
	 **************************/
	var $zoomLinks = $('.zoom-link'),
		mouseX,
		mouseY;
	$zoomLinks.on('click', function (e) {
		e.preventDefault();

		if (e.type.indexOf('touch') === 0) {
			var touchlist = e.touches || e.originalEvent.touches;
			mouseX = touchlist[0].pageX;
			mouseY = touchlist[0].pageY;
		} else {
			mouseX = e.pageX || mouseX;
			mouseY = e.pageY || mouseY;
		}
	});

	if (extraResponsiveSizesTests.mobile) {
		$zoomLinks.removeClass('enabled');
	}

	var productZoom = null;
	$zoomLinks.filter('.enabled').fancybox
	({
		padding    : 0,
		margin     : 0,
		nextEffect : 'fade',
		prevEffect : 'fade',
		width   : '100%',
		height  : '100%',
		maxWidth   : '100%',
		maxHeight  : '100%',
		autoCenter : false,
		openEffect: 'none',
		closeEffect: 'none',
		openSpeed: 0,
		closeSpeed: 0,
		modal: true,
		closeBtn : false,
		//scrolling: 'no',
		//closeClick: true,
		beforeLoad : function () {
			$('html').addClass('zoom');
		},
		afterLoad  : function () {
			var elementW = this.element.data('zoom-width'),
				elementH = this.element.data('zoom-height'),
				style = 'width: '+elementW+'px; height: '+elementH+'px;';

			$.extend(this, {
				aspectRatio : false,
				type    : 'html',
				width   : '100%',
				height  : '100%',
				content : '<div class="fancybox-image easyzoom"><a class="easyzoom-link" href="#zoom"><img width="'+elementW+'px" height="'+elementH+'px" style="'+style+'" class="easyzoom-link-image" src="'+this.href+'" /></a> <a class="close-button" href="#/close"><svg class="icon icon-close"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-close"></use></svg></a></div>'
			});
		},
		beforeShow  : function () {
			productZoom = new ExtraProductZoom($('.easyzoom-link'), {
				mouseX: mouseX,
				mouseY: mouseY
			});
		},
		afterClose : function () {
			setTimeout(function () {
				$('html').removeClass('zoom');
			}, 300);
		}
	});
	$('body').on('click', '.easyzoom .close-button', function (event) {
		event.preventDefault();
		productZoom.stopFollowing();
		productZoom.close();
	});
	$('body').on('click', '.easyzoom-link', function (event) {
		event.preventDefault();
		if ($('html').hasClass('no-touch')) {
			productZoom.stopFollowing();
			productZoom.close();
		}
	});


	/***************************
	 *
	 *
	 * BACK BUTTON HREF
	 *
	 *
	 **************************/
	if(typeof sessionStorage!='undefined') {
		var sessionProductsFilterHash = sessionStorage.productsFilterHash,
			$backButton = $('.back-button'),
			shopUrl = $backButton.attr('href');
		if (!(sessionProductsFilterHash == 'undefined' || sessionProductsFilterHash == null || sessionProductsFilterHash == 0)) {
			shopUrl += '#/'+sessionProductsFilterHash;
			$backButton.attr('href', shopUrl);
		}
	}
});