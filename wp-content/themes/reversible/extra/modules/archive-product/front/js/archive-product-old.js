jQuery(function($) {
	var pagesLoaded = [1],
		pagesToLoad = [],
		nbRequest = 0,
		maxNbRequestAuto = 2,
		maxNbRequest = 6,
		moreToLoad = true,
		pagination = {
			currentPage: parseInt(resultCount.currentPage),
			currentPageFirst: parseInt(resultCount.currentPageFirst),
			currentPageLast: parseInt(resultCount.currentPageLast),
			nbPage: parseInt(resultCount.nbPage),
			productPerPage: parseInt(resultCount.productPerPage),
			totalProduct: parseInt(resultCount.totalProduct),
			basePageUrl: resultCount.basePageUrl
		},
		$infiniteScrollMore = $('.extra-infinite-scroll-more'),
		$infiniteScrollEnd = $('.extra-infinite-scroll-end'),
		$html = $('html'),
		$productList = $('.products');

	var viewportHeight,
		productListTop,
		lineHeight,
		productWidth,
		nbElementByLine,
		allElementsByLineNumber;
	function calculateElementsPositions () {
		viewportHeight = $(window).height();
		productListTop = $productList[0].getBoundingClientRect().top - $('body')[0].getBoundingClientRect().top;
		var firstItem = $productList.find('.product:first-child');
		lineHeight = firstItem.outerHeight();
		productWidth = firstItem.outerWidth();
		nbElementByLine = Math.floor($productList.outerWidth() / productWidth);
		allElementsByLineNumber = [];

		$productList.find('.product').each(function (index) {
			var $currentElement = $(this),
				lineNumber = Math.floor(index / nbElementByLine),
				elementsForCurrentLine = allElementsByLineNumber[lineNumber];
			if (elementsForCurrentLine == null) {
				elementsForCurrentLine = [];
			}
			elementsForCurrentLine.push($currentElement);
			allElementsByLineNumber[lineNumber] = elementsForCurrentLine;
		});
	}

	var resizeTimer = null;
	$window.on('resize', function () {
		clearTimeout(resizeTimer);
		resizeTimer = setTimeout(resizeHandler, 300);
	});
	function resizeHandler() {
		calculateElementsPositions();
		scrollHandler();
	}


	var minPageVisible = 1,
		maxPageVisible = 1;

	function calculatePagesToLoad() {
		var pageNumber;
		pagesToLoad = [];

		console.log('minPageVisible : '+minPageVisible);
		console.log('maxPageVisible : '+maxPageVisible);
		for (pageNumber = minPageVisible; pageNumber <= maxPageVisible; pageNumber++) {
			if (!isPageLoaded(pageNumber)) {
				pagesToLoad.push(pageNumber);
			}
		}
		//var nextPages = [];
		for (pageNumber = maxPageVisible + 1; pageNumber <= pagination.nbPage; pageNumber++) {
			//nextPages.push(pageNumber);
			if (!isPageLoaded(pageNumber)) {
				pagesToLoad.push(pageNumber);
			}
		}
		//var previousPages = [];
		for (pageNumber = minPageVisible - 1; pageNumber >= 1; pageNumber--) {
			//previousPages.push(pageNumber);
			if (!isPageLoaded(pageNumber)) {
				pagesToLoad.push(pageNumber);
			}
		}
		// TODO piocher une fois sur dans dans les next et dans les previous
		console.log(pagesToLoad);
	}

	function isPageLoaded(pageNumber) {
		return pagesLoaded.indexOf(pageNumber) != -1;
	}


	function loadNextProductPage() {
		// Remains pages to load
		if (pagesToLoad.length > 0 && nbRequest < maxNbRequest) {
			var pageNumber = pagesToLoad.shift();
			// If not already loaded
			loadProductPage(pageNumber);
		}
	}

	function loadProductPage(pageNumber) {
		//if (nbRequest < maxNbRequest) {
		//	if (isPageLoaded(pageNumber)) {
		//		if (nbRequest < maxNbRequestAuto) {
		//			loadNextProductPage();
		//		}
		//	} else {
		//		console.log('loadProductPage : '+pageNumber);
		//		console.log(pagesToLoad);
		//		pagesLoaded.push(pageNumber);
		//		var nextUrl = pagination.basePageUrl + pageNumber,
		//			$blankProducts = $productList.find('.product.blank-' + pageNumber),
		//			$blankProduct = null;
		//
		//		// Update scroll-more link in a case of...
		//		$('.extra-infinite-scroll-more').attr('href', nextUrl);
		//		// Replace blank products by loading products
		//		$blankProducts.each(function () {
		//			$blankProduct = $(this);
		//			replaceBlankByLoading($blankProduct, pageNumber);
		//		});
		//
		//		// Request next page
		//		nbRequest++;
		//		$.get(nextUrl, function (response) {
		//			var $nextPage = $(response),
		//				$toInsertProducts = $nextPage.find('.products > .product'),
		//				$loadingProducts = $productList.find('.product.loading-' + pageNumber),
		//				loadingProduct = null;
		//
		//			$toInsertProducts.each(function (index) {
		//				loadingProduct = $loadingProducts.get(index);
		//				if (loadingProduct) {
		//					replaceloadingProduct(loadingProduct, this, pageNumber);
		//				} else {
		//					// TODO reflechir a tous les cas de figures
		//					insertProduct($(this));
		//				}
		//			});
		//
		//			nbRequest--;
		//
		//			// TODO To keep or not to keep ???
		//			checkEnd();
		//			if (nbRequest < maxNbRequestAuto) {
		//				loadNextProductPage();
		//			}
		//		});
		//	}
		//}
	}

	/**
	 * Insert blank products
	 */
	function insertBlankProducts() {
		for (var i = (pagination.productPerPage); i < pagination.totalProduct; i++) {
			var productPageNumber = Math.floor(i / pagination.productPerPage) + 1;
			insertBlankProduct(productPageNumber);
		}
	}

	/**
	 * Insert a blank product. To have a fully scroll bar
	 * @param pageNumber
	 */
	function insertBlankProduct(pageNumber) {
		var $loadingProduct = $('<li class="product type-product blank blank-' + pageNumber + ' page-' + pageNumber + '" data-page="'+pageNumber+'"><span class="blank-message">:)</span> </li>');
		$productList.append($loadingProduct);
	}

	/**
	 * Insert a blank product. loading for an ajax request
	 * @param $product blank to replace
	 */
	function replaceBlankByLoading($product, pageNumber) {
		$product.attr('class', 'product type-product loading loading-' + pageNumber + ' page-' + pageNumber).html('<span class="loading-message">Chargement...</span>');
	}

	/**
	 * Replace loading product by the correct loaded product
	 * @param loadingProduct
	 * @param toInsertProduct
	 * @param pageNumber
	 */
	function replaceloadingProduct(loadingProduct, toInsertProduct, pageNumber) {
		if (loadingProduct && toInsertProduct) {
			var $loadingProduct = $(loadingProduct),
				$toInsertProduct = $(toInsertProduct);
			$loadingProduct.attr('class', $toInsertProduct.attr('class'));
			$loadingProduct.html($toInsertProduct.html());
			$loadingProduct.addClass('product-page-' + pageNumber);
		}
		//TODO add data for filtering !!!
	}

	/**
	 * Insert a product in case loading algorythm is broken
	 * @param $toInsertProduct
	 */
	function insertProduct($toInsertProduct) {
		$productList.append($toInsertProduct);
	}

	function checkEnd() {
		if (pagination.currentPage >= pagination.nbPage) {
			moreToLoad = false;
			$html.addClass('no-more-scroll');

			if (nbRequest == 0) {
				$html.addClass('all-product-loaded');
			}
		}
	}

	/**
	* LOADING ON SCROLL
	*/
	var scrollTimer = null;
	$window.scroll(function(){
		clearTimeout(scrollTimer);
		scrollTimer = setTimeout(scrollHandler, 100);
	});

	function scrollHandler() {
		var windowScrollTop = $(window).scrollTop(),
			productListScrollTop = windowScrollTop - productListTop,
			productListScrollBottom = windowScrollTop + viewportHeight - productListTop,
			minVisibleLine = Math.min(Math.max(Math.floor(productListScrollTop / lineHeight), 0), allElementsByLineNumber.length - 1),
			maxVisibleLine = Math.min(Math.max(Math.floor(productListScrollBottom / lineHeight), 0), allElementsByLineNumber.length - 1),
			minLine = allElementsByLineNumber[minVisibleLine],
			maxLine = allElementsByLineNumber[maxVisibleLine],
			$firstElement = minLine[0],
			$lastElement = maxLine[maxLine.length - 1],
			minPage = $firstElement.data('page'),
			maxPage = $lastElement.data('page');

		//console.log('line : '+minVisibleLine+'-'+maxVisibleLine);
		//console.log('page : '+minPage+'-'+maxPage);
		//console.log('');

		// We remove one from min and add one to max
		minPageVisible = minPage;
		maxPageVisible = maxPage;
		//minPageVisible = Math.max(minPage-1, 1);
		//maxPageVisible = Math.min(maxPage+1, pagination.nbPage);

		for (var i = minPageVisible; i <= maxPageVisible; i++) {
			loadProductPage(i);
		}
		calculatePagesToLoad();
	}

	/**
	 * LOADING ON CLICK
	 */
	//$infiniteScrollMore.on('click', function(event) {
	//	event.preventDefault();
	//	loadNextProductPage();
	//});

	// INIT
	insertBlankProducts();
	calculateElementsPositions();

	$window.on('ready', function () {
		scrollHandler();
		// START LOADING PAGES !
		console.log('startLoading Pages');
		for (var i = 0; i < maxNbRequestAuto; i++) {
			loadNextProductPage();
		}
		console.log('init done, roule ma poule');
	});


	// TODO CHANGE URL WHEN FILTERING AND SORTING !!!
});