var $html = $('html'),
	viewportProducts = [],
	$productList,
	$visibleElements,
	archiveProduct = {},
	nbColumnsByMaxWidth = [
		{maxWidth: 1620, nbColumns: 3},
		{maxWidth: 1330, nbColumns: 2},
		{maxWidth: 1024, nbColumns: 3},
		{maxWidth: 960, nbColumns: 2},
		{maxWidth: 620, nbColumns: 1}
	],
	defaultNbColumns = 4;

jQuery(function($) {
	$productList = $('.products');
	$visibleElements = $productList.find('.product');

	$window.on('extra.resize', resizeHandler);
	function resizeHandler() {
		extraCheckHasMainScrollBar();
		extraCalculateElementsPositions();
		extraDefineViewportProducts();
		extraCheckProductsToLoad();

		extraDefineProductByRow();
	}

	/*******************************
	 *
	 *
	 * LOADING ON SCROLL
	 *
	 *
	 ******************************/
	var scrollTimer = null;
	$scrollableWrapper.scroll(function() {
		clearTimeout(scrollTimer);
		scrollTimer = setTimeout(scrollHandler, 200);
	});

	archiveProduct.lastScrollTop = 0;
	function scrollHandler () {
		window.requestAnimationFrame( onNextAnimationFrame );
	}

	function onNextAnimationFrame() {
		var newScrollTop = $scrollableWrapper.scrollTop();
		if (newScrollTop > archiveProduct.lastScrollTop + 50 || newScrollTop < archiveProduct.lastScrollTop - 50 ) {
			archiveProduct.lastScrollTop = newScrollTop;
			extraDefineViewportProducts();
			extraCheckProductsToLoad();
		}
	}

	/*******************************
	 *
	 *
	 * INIT
	 *
	 *
	 ******************************/
	function init() {
		extraCheckHasMainScrollBar();
		extraCalculateElementsPositions();
		extraDefineViewportProducts();
		extraCheckProductsToLoad();

		extraDefineProductByRow();

		$html.addClass('products-initialized');
	}

	$window.on('load', function () {
		init();
	});
});

/*******************************
 *
 *
 * CALCULATE NB COLUMN, DEFINE FIRST AND LAST FOR EACH ROWS
 *
 *
 ******************************/
function extraDefineProductByRow () {
	//var index,
	//	current,
	//	maxNbColumn = defaultNbColumns;
	//for (index in nbColumnsByMaxWidth) {
	//	current = nbColumnsByMaxWidth[index];
	//	if (window.matchMedia('(max-width: '+current.maxWidth+'px)').matches) {
	//		maxNbColumn = current.nbColumns;
	//	}
	//}
	//$productList.removeClass('by-row-4').removeClass('by-row-3').removeClass('by-row-2').removeClass('by-row-1').addClass('by-row-'+maxNbColumn);
	//
	//console.log($visibleElements.length);
	//$visibleElements.removeClass('last-of-row').removeClass('first-of-row');
	//
	//console.log(':eq('+maxNbColumn+'n)');
	//console.log($visibleElements.filter(':eq('+maxNbColumn+'n)'));
	//$visibleElements.filter(':eq('+maxNbColumn+'n)').addClass('last-of-row');
	//$visibleElements.filter(':eq('+maxNbColumn+'n+1)').addClass('first-of-row');
	//var visibleElementsIndex = 0;
	//$visibleElements.each(function () {
	//	console.log('');
	//	visibleElementsIndex++;
	//});
}

/*******************************
 *
 *
 * CALCULATE ELEMENTS POSITIONS
 *
 *
 ******************************/
function extraCalculateElementsPositions () {
	var productWidth,
		nbElementByLine;

	archiveProduct.allElementsByLineNumber = [];

	if ($productList.length > 0) {
		archiveProduct.viewportHeight = $window.outerHeight();
		archiveProduct.productListTop = $productList[0].getBoundingClientRect().top - $('body')[0].getBoundingClientRect().top;

		var firstItem = $productList.find('.product:first-child');
		archiveProduct.lineHeight = firstItem.outerHeight(true);
		productWidth = firstItem.outerWidth(true);
		nbElementByLine = Math.floor($productList.innerWidth() / productWidth);

		$visibleElements.each(function (index) {
			var $currentElement = $(this),
				lineNumber = Math.floor(index / nbElementByLine),
				elementsForCurrentLine = archiveProduct.allElementsByLineNumber[lineNumber];
			if (elementsForCurrentLine == null) {
				elementsForCurrentLine = [];
			}
			elementsForCurrentLine.push($currentElement);
			archiveProduct.allElementsByLineNumber[lineNumber] = elementsForCurrentLine;
		});
	}
}
/*******************************
 *
 *
 * DEFINE PRODUCT IN VIEWPORT
 *
 *
 ******************************/
function extraDefineViewportProducts () {
	viewportProducts = [];
	var windowScrollTop = archiveProduct.lastScrollTop,
		productListScrollTop = windowScrollTop - archiveProduct.productListTop,
		productListScrollBottom = windowScrollTop + archiveProduct.viewportHeight - archiveProduct.productListTop,
		minVisibleLine = Math.min(Math.max(Math.floor(productListScrollTop / archiveProduct.lineHeight), 0), archiveProduct.allElementsByLineNumber.length - 1),
		maxVisibleLine = Math.min(Math.max(Math.floor(productListScrollBottom / archiveProduct.lineHeight), 0), archiveProduct.allElementsByLineNumber.length - 1);

	for (var i = minVisibleLine; i <= maxVisibleLine; i++) {
		var currentLine = archiveProduct.allElementsByLineNumber[i];
		if (currentLine) {
			for (var j = 0; j < currentLine.length; j++) {
				viewportProducts.push(currentLine[j]);
			}
		}
	}
}
/*******************************
 *
 *
 * LOAD IMAGE FOR PRODUCT IN VIEWPORT
 *
 *
 ******************************/
function extraCheckProductsToLoad() {
	for (var i = 0; i < viewportProducts.length; i++) {
		extraLoadProductThumbnail(viewportProducts[i]);
	}
}
