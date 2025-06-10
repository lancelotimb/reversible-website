jQuery(function($) {
	console.log('in archive-product-redo-url')
	var productsLoaded = [],
		$html = $('html'),
		$productList = $('.products'),
		cssSelectorMaterial = '.product',
		cssSelectorType = '.product',
		cssSelectorCollection = '.product';


	/*******************************
	 *
	 *
	 * CALCULATE ELEMENTS POSITIONS
	 *
	 *
	 ******************************/
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



		$productList.find(cssSelectorMaterial).filter(cssSelectorType).filter(cssSelectorCollection).each(function (index) {
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

	/*******************************
	 *
	 *
	 * LOAD PRODUCT
	 *
	 *
	 ******************************/
	function isProductAlreadyLoad($product) {
		return productsLoaded.indexOf($product) != -1;
	}
	function loadProduct($product) {
		if (!isProductAlreadyLoad($product)) {
			productsLoaded.push($product);
			var $img = $product.find('.extra-product-thumbnail');
			var imgSrc = $img.data('thumbnail-src');
			if (imgSrc) {
				$img.load(function () {
					//TODO ANIMATE THE APPARITION
					//$product.css('visibility', 'visible');
				}).attr({
					src: imgSrc
				});
			}
		}
	}


	var resizeTimer = null;
	$window.on('resize', function () {
		clearTimeout(resizeTimer);
		resizeTimer = setTimeout(resizeHandler, 300);
	});
	function resizeHandler() {
		calculateElementsPositions();
		checkProductsToLoad();
	}

	/*******************************
	 *
	 *
	 * LOADING ON SCROLL
	 *
	 *
	 ******************************/
	var scrollTimer = null;
	$window.scroll(function(){
		clearTimeout(scrollTimer);
		scrollTimer = setTimeout(checkProductsToLoad, 100);
	});

	function checkProductsToLoad() {
		var windowScrollTop = $(window).scrollTop(),
			productListScrollTop = windowScrollTop - productListTop,
			productListScrollBottom = windowScrollTop + viewportHeight - productListTop,
			minVisibleLine = Math.min(Math.max(Math.floor(productListScrollTop / lineHeight), 0), allElementsByLineNumber.length - 1),
			maxVisibleLine = Math.min(Math.max(Math.floor(productListScrollBottom / lineHeight), 0), allElementsByLineNumber.length - 1);

		for (var i = minVisibleLine; i <= maxVisibleLine; i++) {
			var currentLine = allElementsByLineNumber[i];
			if (currentLine) {
				for (var j = 0; j < currentLine.length; j++) {
					loadProduct(currentLine[j]);
				}
			}
		}
	}


	/*******************************
	 *
	 *
	 * FILTERING
	 *
	 *
	 ******************************/
	var filtersMaterial = [],
		filtersType = [],
		filtersCollection = [];
	$('.extra-product-filter').on('click', function (event) {
		var $checkbox = $(this),
			checkboxValue = $checkbox.val(),
			dataFilterType = $checkbox.data('filter-type'),
			filtersForCurrentType;

		if (dataFilterType == 'materiel') {
			filtersForCurrentType = filtersMaterial;
		} else if(dataFilterType == 'type') {
			filtersForCurrentType = filtersType;
		} else if(dataFilterType == 'collection') {
			filtersForCurrentType = filtersCollection;
		}

		if (!filtersForCurrentType) {
			filtersForCurrentType = [];
		}

		if ($checkbox.is(':checked')) {
			filtersForCurrentType.push(checkboxValue);
		} else {
			var currentIndex = filtersForCurrentType.indexOf(checkboxValue);
			if (currentIndex != -1) {
				filtersForCurrentType.splice(currentIndex, 1);
			}
		}

		if (dataFilterType == 'materiel') {
			filtersMaterial = filtersForCurrentType;
		} else if(dataFilterType == 'type') {
			filtersType = filtersForCurrentType;
		} else if(dataFilterType == 'collection') {
			filtersCollection = filtersForCurrentType;
		}

		updateHashFilter();
	});

	function updateHashFilter() {
		var hash = '',
			filterIndex,
			filter,
			filtersInHash;

		filtersInHash = null;
		for (filterIndex = 0; filterIndex < filtersMaterial.length; filterIndex++) {
			filter = filtersMaterial[filterIndex];
			if (filter != '') {
				if (filtersInHash == null) {
					filtersInHash = filter;
				} else {
					filtersInHash += '+'+filter;
				}
			}
		}
		if (filtersInHash != null) {
			hash += '/materiel'+'/'+filtersInHash;
		}

		// Type
		filtersInHash = null;
		for (filterIndex = 0; filterIndex < filtersType.length; filterIndex++) {
			filter = filtersType[filterIndex];
			if (filter != '') {
				if (filtersInHash == null) {
					filtersInHash = filter;
				} else {
					filtersInHash += '+'+filter;
				}
			}
		}
		if (filtersInHash != null) {
			hash += '/type'+'/'+filtersInHash;
		}

		// Collection
		filtersInHash = null;
		for (filterIndex = 0; filterIndex < filtersCollection.length; filterIndex++) {
			filter = filtersCollection[filterIndex];
			if (filter != '') {
				if (filtersInHash == null) {
					filtersInHash = filter;
				} else {
					filtersInHash += '+'+filter;
				}
			}
		}
		if (filtersInHash != null) {
			hash += '/collection'+'/'+filtersInHash;
		}

		location.hash = hash;

		extractHash();
	}

	function extractHash() {
		filtersMaterial = [];
		filtersType = [];
		filtersCollection = [];
		var hash = location.hash,
			hashArray = hash.split('/'),
			hashIndex,
			isFilterType = true,
			currentHash,
			previousFilterType,
			filtersArray,
			cleanedArray,
			filter;

		hashArray.shift();

		for (hashIndex in hashArray) {
			currentHash = hashArray[hashIndex];
			if (isFilterType) {
				previousFilterType = currentHash;
			} else {
				cleanedArray = [];
				filtersArray = currentHash.split('+');
				for (var i = 0; i < filtersArray.length; i++) {
					filter = filtersArray[i];
					if (filter && filter != '') {
						cleanedArray.push(filter);
					}
				}

				if (previousFilterType == 'materiel') {
					filtersMaterial = cleanedArray;
				} else if (previousFilterType == 'type') {
					filtersType = cleanedArray;
				} else if (previousFilterType == 'collection') {
					filtersCollection = cleanedArray;
				}
			}
			isFilterType = !isFilterType;
		}
		hashArray.shift();
	}

	window.onhashchange = function (event) {
		extractHash();
		applyFilter();
	};

	function applyFilter() {
		var filterIndex,
			filter;

		// Material
		cssSelectorMaterial = '.product';
		for (filterIndex = 0; filterIndex < filtersMaterial.length; filterIndex++) {
			filter = filtersMaterial[filterIndex];

			if (cssSelectorMaterial == '.product') {
				cssSelectorMaterial = '.extra-filter-materiel-'+filter;
			} else {
				cssSelectorMaterial += ', .extra-filter-materiel-'+filter;
			}
		}

		// Type
		cssSelectorType = '.product';
		for (filterIndex = 0; filterIndex < filtersType.length; filterIndex++) {
			filter = filtersType[filterIndex];

			if (cssSelectorType == '.product') {
				cssSelectorType = '.extra-filter-type-'+filter;
			} else {
				cssSelectorType += ', .extra-filter-type-'+filter;
			}
		}

		// Collection
		cssSelectorCollection = '.product';
		for (filterIndex = 0; filterIndex < filtersCollection.length; filterIndex++) {
			filter = filtersCollection[filterIndex];

			if (cssSelectorCollection == '.product') {
				cssSelectorCollection = '.extra-filter-collection-'+filter;
			} else {
				cssSelectorCollection += ', .extra-filter-collection-'+filter;
			}
		}


		var $visibleElements,
			count = 0;
		if (cssSelectorMaterial == '.product' && cssSelectorType == '.product'  && cssSelectorCollection == '.product') {
			// TODO SHOW ALL ELEMENTS
			$visibleElements = $productList.find('.product');
			$visibleElements.show();
		} else {
			// TODO HIDE ONLY BAD ELEMENTS
			$productList.find('.product').hide();
			// TODO SHOW GOOD ELEMENTS
			$visibleElements = $productList.find(cssSelectorMaterial).filter(cssSelectorType).filter(cssSelectorCollection);
			$visibleElements.show();
		}
		count = $visibleElements.size();

		var $resultCount = $('.extra-result-count');
		console.log(count, 'count', $resultCount, 'redo urml')
		$resultCount.html('');
		if (count == 0) {
			$resultCount.html(resultCountMessages.noResult);
		} else if(count == 1) {
			$resultCount.html(resultCountMessages.oneResult);
		} else {
			$resultCount.html(resultCountMessages.manyResults);
			$resultCount.find('.count').text('3');
		}

		calculateElementsPositions();
		checkProductsToLoad();
	}

	function initFiltersCheckboxes() {
		if (cssSelectorMaterial != '.product') {
			$('.extra-product-filters').find(cssSelectorMaterial).attr('checked', 'checked');
		}
		if (cssSelectorType != '.product') {
			$('.extra-product-filters').find(cssSelectorType).attr('checked', 'checked');
		}
		if (cssSelectorCollection != '.product') {
			$('.extra-product-filters').find(cssSelectorCollection).attr('checked', 'checked');
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
		calculateElementsPositions();
		checkProductsToLoad();
		extractHash();
		applyFilter();
		initFiltersCheckboxes();
	}
	init();
});