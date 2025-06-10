console.log('in archive-product-filter before all')

if (redirectToUrl != null) {
	//NEED TO REDIRECT
	console.log('Redirect to : '+redirectToUrl);
	window.location.replace(redirectToUrl);
} else {
	jQuery(function($) {
		console.log('in archive-product-filter')
		var cssSelectorMaterial = '.product',
			cssSelectorType = '.product',
			cssSelectorCollection = '.product',
			utils = new Diacritics();

		/*******************************
		 *
		 *
		 * FILTERING
		 *
		 *
		 ******************************/
		var filtersMaterial = [],
			filtersType = [],
			filtersCollection = [],
			sortBy = null,
			searchText = '';
		$('.extra-product-filter').on('click', function (event) {
			event.preventDefault();

			var $this = $(this),
				dataFilterValue = $this.attr('id'),
				dataFilterType = $this.data('filter-type'),
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

			if (!$this.hasClass('active')) {
				$this.addClass('active');
				filtersForCurrentType.push(dataFilterValue);
			} else {
				$this.removeClass('active');
				var currentIndex = filtersForCurrentType.indexOf(dataFilterValue);
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

		$('.product-sort-link').on('click', function (event) {
			event.preventDefault();
			var $this = $(this);
			if (!$this.hasClass('active')) {
				$('.product-sort-link.active').removeClass('active');
				sortBy = $this.addClass('active').data('sort-type');
				updateHashFilter();
			}
		});

		function updateHashFilter() {
			var hash = '',
				filterIndex,
				filter,
				filtersInHash;

			// SEARCHING
			if (searchText != null && searchText != '') {
				hash += '&recherche='+searchText;
			}

			// SORTING
			if (sortBy != null) {
				hash += '&tri='+sortBy;
			}

			// FILTERING
			filtersInHash = null;
			for (filterIndex = 0; filterIndex < filtersMaterial.length; filterIndex++) {
				filter = filtersMaterial[filterIndex];
				if (filter != '') {
					if (filtersInHash == null) {
						filtersInHash = filter;
					} else {
						filtersInHash += ','+filter;
					}
				}
			}
			if (filtersInHash != null) {
				hash += '&materiel'+'='+filtersInHash;
			}

			// Type
			filtersInHash = null;
			for (filterIndex = 0; filterIndex < filtersType.length; filterIndex++) {
				filter = filtersType[filterIndex];
				if (filter != '') {
					if (filtersInHash == null) {
						filtersInHash = filter;
					} else {
						filtersInHash += ','+filter;
					}
				}
			}
			if (filtersInHash != null) {
				hash += '&type'+'='+filtersInHash;
			}

			// Collection
			filtersInHash = null;
			for (filterIndex = 0; filterIndex < filtersCollection.length; filterIndex++) {
				filter = filtersCollection[filterIndex];
				if (filter != '') {
					if (filtersInHash == null) {
						filtersInHash = filter;
					} else {
						filtersInHash += ','+filter;
					}
				}
			}
			if (filtersInHash != null) {
				hash += '&collection'+'='+filtersInHash;
			}

			// We remove the first &
			hash = hash.substring(1);

			location.hash = '/'+hash;

			extractHash();
		}

		function extractHash() {
			filtersMaterial = [];
			filtersType = [];
			filtersCollection = [];
			var hash = location.hash,
				hashArray,
				hashIndex,
				currentHash,
				filterType,
				filtersArray,
				cleanedArray,
				filter;

			// We remove '#/'
			hash = hash.substring(2);
			hashArray = hash.split('&');

			// Reset sort by
			sortBy = null;
			// Reset search
			searchText = '';

			for (var hashIndex = 0; hashIndex < hashArray.length; hashIndex++) {
				currentHash = hashArray[hashIndex];
				cleanedArray = [];
				filtersArray = currentHash.split('=');
				if (filtersArray.length >= 2) {
					//The first is the type
					filterType = filtersArray[0];
					// The second is the filter list
					filtersArray = filtersArray[1].split(',');
					for (var i = 0; i < filtersArray.length; i++) {
						filter = filtersArray[i];
						if (filter && filter != '') {
							cleanedArray.push(filter);
						}
					}

					if (filterType == 'materiel') {
						filtersMaterial = cleanedArray;
					} else if (filterType == 'type') {
						filtersType = cleanedArray;
					} else if (filterType == 'collection') {
						filtersCollection = cleanedArray;
					} else if (filterType == 'tri') {
						sortBy = cleanedArray.join('');
					} else if (filterType == 'recherche') {
						searchText = cleanedArray.join('');
					}
				}
			}

			// FOR BACK BUTTON
			if(typeof sessionStorage != 'undefined') {
				sessionStorage.productsFilterHash = hash;
			}
		}

		window.onhashchange = function (event) {
			extractHash();
			applyFilter(false);
		};

		var timelineOut = new TimelineMax(),
			timelineIn = new TimelineMax();
		function applyFilter(first) {
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
			checkTheBoxes();
			fillSearchInput();
			activeSortLink();

			var $searchedElements,
				count = 0,
				$toHideElements;
			if (cssSelectorMaterial == '.product' && cssSelectorType == '.product'  && cssSelectorCollection == '.product') {
				$visibleElements = $productList.find('.product');
			} else {
				$visibleElements = $productList.find(cssSelectorMaterial).filter(cssSelectorType).filter(cssSelectorCollection);
			}

			var searching = decodeURIComponent(searchText).trim().toLowerCase();
			if (searching != '') {
				var searchArray = cleanSearchArray(searching.split(' '));
				$searchedElements = $();
				//console.log(searching);
				$visibleElements.each(function () {
					var $this = $(this),
						terms = $this.data('product-search-terms'),
						matched = true,
						s = 0;

					while (s < searchArray.length && matched) {
						var currentSearchTerm = searchArray[s];
						s++;

						var diacriticsTerms = utils.removeDiacritics(terms.toLowerCase()),
							diacriticsSearch = utils.removeDiacritics(currentSearchTerm);

						if (diacriticsTerms.indexOf(diacriticsSearch) == -1) {
							matched = false;
						}
					}
					if (matched) {
						$searchedElements = $searchedElements.add($this);
					}
				});
				//console.log($searchedElements.size());
				//console.log('end');
				$visibleElements = $searchedElements;
			}

			$toHideElements = $productList.find('.product').not($visibleElements);

			count = 0;
			$visibleElements.each(function () {
				count += parseInt($(this).data('product-quantity'));
			});
			count = $visibleElements.size();

			if (!first) {
				var $products = $productList.find('.product'),
					$notInViewportProduct = $products;

				for (var i = 0; i < viewportProducts.length; i++) {
					var current = viewportProducts[i];
					$notInViewportProduct = $notInViewportProduct.not(current);
				}

				timelineOut.kill();
				timelineIn.kill();
				timelineOut = new TimelineMax();

				timelineOut.set($notInViewportProduct, {autoAlpha: 0});
				//timelineOut.set(viewportProducts, {autoAlpha: 1});
				timelineOut.staggerTo(viewportProducts.reverse(), 0.3, {autoAlpha: 0, y: 0, clearProps: 'y'}, 0.05);
				timelineOut.addCallback(function () {
					$toHideElements.hide();
					$visibleElements.show();
					applySort();

					extraCheckHasMainScrollBar();
					extraCalculateElementsPositions();
					extraDefineViewportProducts();
					extraCheckProductsToLoad();
					extraDefineProductByRow();

					$notInViewportProduct = $products;
					for (var i = 0; i < viewportProducts.length; i++) {
						var current = viewportProducts[i];
						$notInViewportProduct = $notInViewportProduct.not(current);
					}

					timelineOut.kill();
					timelineIn.kill();
					timelineIn = new TimelineMax();

					timelineIn.set($notInViewportProduct, {autoAlpha: 1});
					timelineIn.staggerTo(viewportProducts, 0.3, {autoAlpha: 1, y: 0, clearProps: 'y'}, 0.05);
				});

				//timelineFilter.to($products, 0.5, {autoAlpha: 0});
				//timelineFilter.addCallback(function () {
				//	$toHideElements.hide();
				//	$visibleElements.show();
				//});
				//timelineFilter.to($visibleElements, 0.5, {autoAlpha: 1});
			} else {
				$toHideElements.hide();
				$visibleElements.show();
				applySort();

				extraCheckHasMainScrollBar();
				extraCalculateElementsPositions();
				extraDefineViewportProducts();
				extraCheckProductsToLoad();
				extraDefineProductByRow();
			}

			var $resultCount = $('.extra-result-count');
			console.log(count, 'count', $resultCount, 'filter')
			$resultCount.html('');
			if (count == 0) {
				$resultCount.html(resultCountMessages.noResult);
			} else if(count == 1) {
				$resultCount.html(resultCountMessages.oneResult);
			} else {
				$resultCount.html(resultCountMessages.manyResults);
				$resultCount.find('.count').text(count);
			}
		}


		/*******************************
		 *
		 *
		 * SORTING
		 *
		 *
		 ******************************/
		var previousSortBy = null;
		function applySort() {
			if (sortBy != previousSortBy) {
				previousSortBy = sortBy;
				var toSort = $productList.find('.product');
				$('.product-sort-link.active').removeClass('active');
				switch (sortBy) {
					case 'prix-croissant' :
						//console.log('Need to sort by lowest');
						$('.product-sort-link.lowest-first-sort-link').addClass('active');
						toSort.sort(lowestFirstSort);
						$visibleElements.sort(lowestFirstSort);
						break;
					case 'prix-decroissant' :
						//console.log('Need to sort by highest');
						$('.product-sort-link.highest-first-sort-link').addClass('active');
						toSort.sort(highestFirstSort);
						$visibleElements.sort(highestFirstSort);
						break;
					default :
						//console.log('Need to sort by default order');
						$('.product-sort-link.default-sort-link').addClass('active');
						toSort.sort(defaultSort);
						$visibleElements.sort(defaultSort);
						break;
				}
				$productList.append(toSort);
			}
		}
		function activeSortLink() {
			if (sortBy == null) {
				sortBy = 'nouveautes';
			}
			$('.product-sort-link.active').removeClass('active');
			$('.product-sort-link[data-sort-type="'+sortBy+'"]').addClass('active');
		}

		function lowestFirstSort (a, b) {
			// convert to integers from strings
			a = parseFloat(a.getAttribute('data-product-price'));
			b = parseFloat(b.getAttribute('data-product-price'));
			// compare
			if(a > b) {
				return 1;
			} else if(a < b) {
				return -1;
			} else {
				return 0;
			}
		}
		function highestFirstSort (a, b) {
			// convert to integers from strings
			a = parseFloat(a.getAttribute('data-product-price'));
			b = parseFloat(b.getAttribute('data-product-price'));
			// compare
			if(a > b) {
				return -1;
			} else if(a < b) {
				return 1;
			} else {
				return 0;
			}
		}
		function defaultSort (a, b) {
			var aStar = parseFloat(a.getAttribute('data-product-featured')),
				bStar = parseFloat(b.getAttribute('data-product-featured'));
			if(aStar > bStar) {
				return -1;
			} else if(aStar < bStar) {
				return 1;
			} else {
				// convert to integers from strings
				a = parseFloat(a.getAttribute('data-product-order'));
				b = parseFloat(b.getAttribute('data-product-order'));
				// compare
				if(a > b) {
					return 1;
				} else if(a < b) {
					return -1;
				} else {
					return 0;
				}
			}
		}

		function checkTheBoxes() {
			var $filters = $('.extra-product-filters');
			$filters.find('.extra-product-filter').removeClass('active');

			if (cssSelectorMaterial != '.product') {
				$filters.find(cssSelectorMaterial).addClass('active');
			}
			if (cssSelectorType != '.product') {
				$filters.find(cssSelectorType).addClass('active');
			}
			if (cssSelectorCollection != '.product') {
				$filters.find(cssSelectorCollection).addClass('active');
			}
		}


		/*******************************
		 *
		 *
		 * SEARCHING
		 *
		 *
		 ******************************/
		$window.on('extra-productSearch', function (event, value) {
			//console.log('extra-fillProductSearch : '+value);
			value = encodeURIComponent(value);
			if (value != searchText) {
				//console.log(value);
				//var cleanedSearch = cleanSearchArray(decodeURIComponent(value).trim().split(' '));

				//if (cleanedSearch.length > 0) {
				//console.log('search allowed');
				searchText = value;
				updateHashFilter();
				//}
			}
		});


		function cleanSearchArray(searchArray) {
			var i = 0,
				currentTerm,
				cleanedArray = [];
			while (i < searchArray.length) {
				currentTerm = searchArray[i];
				i++;
				if (currentTerm.length > 2) {
					cleanedArray.push(currentTerm);
				}
			}

			return cleanedArray;
		}

		function fillSearchInput() {
			$window.trigger('extra-fillProductSearch', [decodeURIComponent(searchText)]);
		}

		/*******************************
		 *
		 *
		 * INIT
		 *
		 *
		 ******************************/
		extractHash();
		console.log('before aplyfilter')
		applyFilter(true);


		/*******************************
		 *
		 *
		 * CART SHOP LINK ONLY CLOSE THE CART
		 *
		 *
		 ******************************/
		$window.on('extra.cart.appended', function () {
			$('.cart-shop-link').on('click', function (event) {
				event.preventDefault();
				$window.trigger('extra.closeCart');
			});
		});
	});
}