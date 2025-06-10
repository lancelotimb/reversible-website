jQuery(function ($) {
	var requesting = false,
		toRemoveProductIds = [],
		toInsertProductIds = [], // TODO Same mecanism ?
		data = {
			action: 'extra_get_shopping_cart'
		},
		cart = null,
		$html = $('html'),
		$shoppingMenu = $('.shopping-menu'),
		$cartLink = $shoppingMenu.find('.cart-link-wrapper');

	$shoppingMenu.after(cartOptions.templates.cartDetailContainer);

	var $cartDetailContainer = $('#cart-detail-container'),
		$cartDetailFooter = $cartDetailContainer.find('.footer'),
		$cartDetailOverlayTop = $cartDetailContainer.find('> .overlay-top'),
		$cartDetailContainerScrollable = $cartDetailContainer.find('> .scrollable'),
		$loadingMessage = $cartDetailContainerScrollable.find('.message-loading'),
		$cartDetailOverlay = $('#cart-detail-overlay'),
		$price = $cartDetailFooter.find('.price'),
		$priceInner = $price.find('> .price-inner'),
		cartDetailOpen = false;

	$window.on('extra.resize', checkHasScrollBarWhenOpen);
	checkHasScrollBarWhenOpen();

	if(typeof sessionStorage!='undefined') {
		var sessionCartCount = sessionStorage.cartCount;
		if (sessionCartCount == 'undefined' || sessionCartCount == null || sessionCartCount == 0) {
			$html.addClass('cart-empty');
		} else {
			$html.removeClass('cart-empty');
		}
		$cartLink.find('.cart-count').text(sessionCartCount);
		$html.addClass('cart-count-loaded');
	}

	/*************************
	 *
	 *
	 * FIRST LOAD CART
	 *
	 *
	 ************************/
	function loadCart() {
		startLoadingCart();
		requesting = true;
		$.get(shoppingCart.ajaxUrl, data, function (response) {
			stopLoadingCart();
			ajaxResponseHandler(response);
		});
	}
	function startLoadingCart() {
		$html.addClass('cart-loading').removeClass('cart-loaded');
		extraLoaderPlay($loadingMessage);
	}
	function stopLoadingCart() {
		extraLoaderStop($loadingMessage);
		$html.removeClass('cart-loading').addClass('cart-loaded');
	}

	loadCart();

	function updateCartCount() {
		if (cart.count == 0) {
			$html.addClass('cart-empty');
		} else {
			$html.removeClass('cart-empty');
		}
		$cartLink.find('.cart-count').text(cart.count);
		$html.addClass('cart-count-loaded');
		if(typeof sessionStorage!='undefined') {
			sessionStorage.cartCount = cart.count;
		}
	}

	function updateDomCart() {
		updateCartCount();
		updatePrice();
		mergeProducts(cart.products);
	}

	/*************************
	 *
	 *
	 * INSERT & REMOVE PRODUCT ANIMATIONS
	 *
	 *
	 ************************/
	function productAnimationOut($product) {
		//console.log('Out : '+$product.data('product-id'));
		var timeline = new TimelineMax();
		timeline.to($product, 0.6, {opacity: 0});
		timeline.to($product, 0.3, {height: 0, marginBottom: 0}, '-=0.3');
		timeline.addCallback(function () {
			//console.log('truly removed');
			$product.remove();
		});

		return timeline;
	}
	function productsAnimationIn($products) {
		var timeline = new TimelineMax();
		if ($products.length > 0) {
			//for (var key in $products) {
			//	console.log('In : '+$products[key].data('product-id'));
			//}

			// , x: (small || extraResponsiveSizesTests.smalldesktop) ? 360 : 480
			timeline.set($products, {height: 0, opacity: 0});
			timeline.staggerTo($products, 0.6, {height: 240, opacity: 1, x: 0, clearProps: 'all'}, 0.1);
			timeline.addCallback(function () {
				checkHasScrollBarWhenOpen();
			});
		}

		return timeline;
	}

	/*************************
	 *
	 *
	 * INSERT & REMOVE PRODUCT AJAX REQUESTS
	 *
	 *
	 ************************/
	function removeClickHandler (event) {
		event.preventDefault();
		var $this = $(this),
			productId = $this.data('product-id'),
			productToRemove,
			current,
			key;


		if (productId && cart != null && cart.count > 0) {
			productToRemove = getProductById(productId, cart.products);
			if (productToRemove) {

				$window.trigger('extra.cart.itemRemoved', [productId]);

				//console.log('removeClickHandler : '+productId);
				//console.log('removeIndex : '+cart.products.indexOf(productToRemove));

				if (productToRemove.quantity > 0) {
					productToRemove.quantity = productToRemove.quantity - 1;
				}
				// Remove the product if no more quantity
				if (productToRemove.quantity == 0) {
					cart.products.splice(cart.products.indexOf(productToRemove), 1);
				}

				cart.count = 0;
				for (key in cart.products) {
					current = cart.products[key];
					cart.count += current.quantity;
				}
				updateDomCart();
				priceLoaderStart();

				toRemoveProductIds.push(productId);
				if (!requesting) {
					requestNextRemoveProduct();
				}
			}
		}
	}
	function requestNextRemoveProduct() {
		if (!requesting) {
			requesting = true;
			var productId = toRemoveProductIds.shift();
			//console.log('requestNextDeleteProduct : '+productId);

			//// DEBUG IMITATE SERVER RESPONSE
			//setTimeout(function () {
			//	console.log('fake response');
			//	console.log(cart);
			//	cart.price = '<span class="amount">'+getRandomInt(40, 500)+'&nbsp;&euro;</span>';
			//	var fakeResponse = {cart: cart};
			//	ajaxResponseHandlerJson(fakeResponse);
			//}, getRandomInt(1000, 4000));

			var data = {
				action: 'extra_remove_from_shopping_cart',
				product_id: productId
			};
			$.get(shoppingCart.ajaxUrl, data, ajaxResponseHandler);
		}
	}
	function requestNextInsertProduct() {
		// TODO NOT IMPLEMENTED YET
		toInsertProductIds = [];
	}


	/*************************
	 *
	 *
	 * AJAX RESPONSE HANDLERS
	 *
	 *
	 ************************/
	function ajaxResponseHandler(response) {
		var json = null;
		if (response){
			try{
				json = $.parseJSON(response);
			} catch (e) {
				console.log(e);
			}
		}
		//console.log('New JSON from server');
		//console.log(json);
		ajaxResponseHandlerJson(json);
	}
	function ajaxResponseHandlerJson(json) {
		requesting = false;
		//console.log('request result : '+requesting);
		// IF THERE ARE AWAITING REQUESTS WAIT FOR THE LAST
		if (toRemoveProductIds.length == 0 && toInsertProductIds.length == 0) {
			extraLoaderStop($price);
			if (json != null) {
				cart = json.cart;
				updateDomCart();
				$window.trigger('extra.cart.updated');
			}
		} else if (toRemoveProductIds.length > 0) {
			// Request next removal
			requestNextRemoveProduct();
		} else if (toInsertProductIds.length > 0) {
			// Request next insertion
			requestNextInsertProduct();
		}
	}

	function updateProductCount($product, product) {
		var $count = $product.find('.product-count');
		if ($count.size() == 0) {
			$product.find('.product-link').append('<span class="product-count">'+product.quantity+'</span>');
		} else {
			$count.html(product.quantity);
		}
	}

	function mergeProducts(products) {
		//console.log('mergeProduct');
		var $products = $cartDetailContainer.find('.cart-products'),
			$productArray = $products.find('.product').not('.goodbye').get(),
			existingProductIds = [],
			toAppearProducts = [];

		// Determine product to remove from DOM
		var keyDom;
		for (keyDom in $productArray) {
			if ($productArray[keyDom]) {
				var $productInDom = $($productArray[keyDom]);
				var productId = $productInDom.data('product-id');
				var productForRemove= getProductById(productId, products);

				if (productForRemove == null) {
					$productInDom.addClass('goodbye');
					productAnimationOut($productInDom);
				} else {
					existingProductIds.push(productId);
					updateProductCount($productInDom, productForRemove);
				}
			}
		}

		// Determine product to insert in DOM
		var key;
		for (key in products){
			var product = products[key];
			if (product && (existingProductIds.length == 0 || existingProductIds.indexOf(product.id) == -1)) {
				var $product = $(product.template).prependTo($products);
				extraLoadProductThumbnail($product);
				toAppearProducts.push($product);
				updateProductCount($product, product);
			}
		}

		productsAnimationIn(toAppearProducts);

		//Update triggers
		var $removeLinks = $('.extra-remove-from-cart');
		$removeLinks.off('click');
		$removeLinks.on('click', removeClickHandler);

		checkHasScrollBarWhenOpen();
		updateAddToCartButton();
	}

	/*************************
	 *
	 *
	 * PRICE & PRICE LOADER
	 *
	 *
	 ************************/
	var priceLoading = false;
	function priceLoaderStart() {
		if (!priceLoading) {
			priceLoading = true;
			var startPriceWidth = $priceInner.outerWidth(),
				timeline = $priceInner.data('timeline');

			if (timeline) {
				timeline.kill();
			}
			timeline = new TimelineMax();

			extraLoaderPlay($price);
			timeline.set($priceInner, {width: startPriceWidth});
			timeline.addCallback(function () {
				$priceInner.html('');
			});
			timeline.to($priceInner, 0.3, {width: 0});
			timeline.addCallback(function () {
				$priceInner.css('width', '');
			});

			$priceInner.data('timeline', timeline);
		}
	}
	function updatePrice() {
		priceLoading = false;
		var originalPriceWidth = $priceInner.outerWidth(),
			oldHtml = $priceInner.html(),
			newPriceWidth = 0,
			timeline = $priceInner.data('timeline');

		if (timeline) {
			timeline.kill();
		}
		timeline = new TimelineMax();

		$priceInner.html(cart.price);
		newPriceWidth = $priceInner.outerWidth();
		$priceInner.html(oldHtml);

		timeline.set($priceInner, {width: originalPriceWidth});
		timeline.to($priceInner, 0.3, {width: newPriceWidth});
		timeline.addCallback(function () {
			$priceInner.css('width', '');
			$priceInner.html(cart.price);
		});
		$priceInner.data('timeline', timeline);
	}


	/*************************
	 *
	 *
	 * OPEN & CLOSE CART DETAIL
	 *
	 *
	 ************************/
	function openCartDetail () {
		timelineDetail.kill();
		timelineDetail = new TimelineMax();

		var containerWidth = (small || extraResponsiveSizesTests.smalldesktop) ? 360 : 480,
			$products = $cartDetailContainerScrollable.find('.product').not('.goodbye');


		// Show cart detail
		timelineDetail.addCallback(function () {
			$html.addClass('cart-open');
			checkHasScrollBarWhenOpen();
		});
		timelineDetail.set($products, {opacity: 0, x: (small || extraResponsiveSizesTests.smalldesktop) ? 360 : 480});
		timelineDetail.set($cartDetailOverlay, {display: 'block'});
		timelineDetail.set($cartDetailContainer, {display: 'block'});
		timelineDetail.to($cartDetailOverlay, 0.3, {autoAlpha: 1});
		timelineDetail.to($cartDetailContainer, 0.6, {width: containerWidth});
		timelineDetail.staggerTo($products.get().reverse(), 0.6, {opacity: 1, x: 0, clearProps: 'all'}, 0.1, '-=0.6');

		timelineDetail.set($shoppingMenu, {autoAlpha: 1});

		return timelineDetail;
	}

	function closeCartDetail () {
		timelineDetail.kill();
		timelineDetail = new TimelineMax();

		var $products = $cartDetailContainerScrollable.find('.product').not('.goodbye'),
			nbProducts = $products.size();

		// hide cart detail
		timelineDetail.addCallback(function () {
			$html.removeClass('cart-open');
			$cartDetailContainer.css('width', (small || extraResponsiveSizesTests.smalldesktop) ? 360 : 480);
		});
		if (nbProducts > 0) {
			timelineDetail.staggerTo($products, 0.6, {opacity: 0, x: (small || extraResponsiveSizesTests.smalldesktop) ? 360 : 480}, 0.1);
		}
		timelineDetail.to($cartDetailContainer, 0.6, {width: 0}, (nbProducts > 0) ? (nbProducts == 1) ? 0.2 : 0.4 : 0);
		timelineDetail.to($cartDetailOverlay, 0.3, {autoAlpha: 0});
		timelineDetail.set($cartDetailContainer, {display: 'none'});
		timelineDetail.set($cartDetailOverlay, {display: 'none'});
		timelineDetail.set($products, { clearProps: 'all' });
		timelineDetail.addCallback(checkHasScrollBarWhenOpen);
		timelineDetail.addCallback(updateAddToCartButton);
	}

	TweenMax.set($cartDetailOverlay, {autoAlpha: 0});
	TweenMax.set($cartDetailContainer, {width: 0});
	var timelineDetail = new TimelineMax();

	$cartLink.on('click', function (event) {
		event.preventDefault();

		cartDetailOpen = !cartDetailOpen;
		if (cartDetailOpen) {
			openCartDetail();
		} else {
			closeCartDetail();
		}
	});
	$cartDetailOverlay.on('click', function (event) {
		event.preventDefault();
		if (cartDetailOpen) {
			cartDetailOpen = false;
			closeCartDetail();
		}
	});



	/*************************
	 *
	 *
	 * FOOTER FIXED OR NOT
	 *
	 *
	 ************************/
	function checkHasScrollBarWhenOpen() {
		var hasScrollBarWhenOpen = $cartDetailContainer.find('.scrollable > .inner').outerHeight() > wHeight;

		//console.log('checkHasScrollBarWhenOpen');
		//console.log($cartDetailContainer.find('.scrollable > .inner').outerHeight());
		//console.log(wHeight);

		if (hasScrollBarWhenOpen) {
			//console.log('has scroll : ' + extraScrollBarWidth);
			$cartDetailFooter.css('right', extraScrollBarWidth);
			$cartDetailOverlayTop.css('right', extraScrollBarWidth);
		} else {
			$cartDetailFooter.css('right', '');
			$cartDetailOverlayTop.css('right', '');
		}
	}
	// Always fixed
	$cartDetailContainer.addClass('footer-fixed');

	/*************************
	 *
	 *
	 * UTILS
	 *
	 *
	 ************************/
	function getProductById(productId, products) {
		var goodGuy = null,
			key,
			product;
		if (products.length > 0) {
			for (key in products) {
				product = products[key];
				if (productId == product.id) {
					goodGuy = product;
					break;
				}
			}
		}

		return goodGuy;
	}

	function getRandomInt(min, max) {
		return Math.floor(Math.random() * (max - min + 1)) + min;
	}




	/***************************
	 *
	 *
	 * ADD TO CART
	 *
	 *
	 **************************/
	var $addToCartBloc = $('.add-to-cart-bloc'),
		$addToCartMessages = $('<div class="add-to-cart-messages"></div>').appendTo($addToCartBloc),
		$addToCartButton = $('.extra-add-to-cart-button'),
		$currentProduct = $('.current-single-product'),
		currentProductId = $('.extra-add-to-cart-id').val(),
		addToCartData = {
			'add-to-cart': currentProductId,
			'action': 'extra_add_to_shopping_cart'
		},
		adding = false;

	$addToCartButton.on('click', function(event) {
		event.preventDefault();
		if (!$currentProduct.hasClass('already-in-cart') && !$currentProduct.hasClass('outofstock')) {
			if (!adding) {
				adding = true;
				$addToCartButton.addClass('loading');
				$addToCartButton.addClass('over');
				extraLoaderPlay($addToCartButton);
				startLoadingCart();

				$.post(shoppingCart.ajaxUrl, addToCartData, function (response) {
					var json = null;
					if (response){
						try{
							json = $.parseJSON(response);
						} catch (e) {
							console.log(e);
						}
					}

					$addToCartButton.removeClass('over');
					stopLoadingCart();
					if (json.hasOwnProperty('notices') && json.hasOwnProperty('cart')) {
						var notices = json.notices,
							newCart = json.cart;

						if (notices.hasOwnProperty('error')) {
							addToCartError(notices, newCart);
						} else if (notices.hasOwnProperty('success')) {
							addToCartSuccess(notices, newCart);
						}
					} else {
						// ERROR ?
						console.log(json);
					}

					adding = false;
					$addToCartButton.removeClass('loading');
					extraLoaderStop($addToCartButton);
				});
			}
		} else if ($currentProduct.hasClass('already-in-cart')) {
			if (!cartDetailOpen) {
				cartDetailOpen = true;
				openCartDetail();
			}
		}
	});

	function addToCartError(notices, newCart) {
		$addToCartMessages.html(notices.error);
	}

	function addToCartSuccess(notices, newCart) {
		cart = newCart;

		// Hide empty message
		$html.removeClass('cart-empty');
		if (!cartDetailOpen) {
			cartDetailOpen = true;
			openCartDetail();
			// LAUNCH EVEN IF OPEN ARE CANCELLED
			setTimeout(updateDomCart, 900);
		} else {
			updateDomCart();
		}
	}

	function updateAddToCartButton() {
		if ($currentProduct.size() > 0) {
			var inCart = false;

			if (cart && cart.hasOwnProperty('products')) {
				var cartIndex,
					currentElement;
				for (cartIndex = 0; cartIndex < cart.products.length; cartIndex++) {
					currentElement = cart.products[cartIndex];
					if (currentElement && currentElement.hasOwnProperty('id') && currentElement.id == currentProductId) {
						inCart = true;
						break;
					}
				}
			}
			if (inCart) {
				$currentProduct.addClass('already-in-cart');
			} else {
				$currentProduct.removeClass('already-in-cart');
				if (!$currentProduct.hasClass('outofstock')) {
				}
			}
		}
	}


	/***************************
	 *
	 *
	 * BUY LINK
	 *
	 *
	 **************************/
	$('.buy-link').on('click', function (event) {
		if ($(this).hasClass('disabled')) {
			event.preventDefault();
		}
	});

	$window.on('extra.checkout.itemRemoved', function (event, productId) {
		var $toRemove = $('.cart-products post-'+productId);
		if ($toRemove.size() > 0) {
			$toRemove.remove();
		}

		if (cart.count > 0) {
			cart.count = cart.count - 1;
			updateCartCount();
		}
	});

	$window.on('extra.checkout.updated', function (event, json) {
		cart = json.cart;
		updateDomCart();
	});

	$window.on('extra.cart.startLoading', function () {
		$('.cart-link, .cart-count').hide();
		extraLoaderPlay($('.cart-link-wrapper'));
	});

	$window.on('extra.cart.stopLoading', function () {
		$('.cart-link, .cart-count').show();
		extraLoaderStop($('.cart-link-wrapper'));
	});
});