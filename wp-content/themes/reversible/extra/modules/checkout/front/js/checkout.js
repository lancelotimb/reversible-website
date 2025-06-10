jQuery(function ($) {
	var $body = $('body'),
		$extraRemoveInput = $('#extra_remove_from_cart_id');

	/*********************
	 *
	 *
	 * SUMMARY
	 *
	 *
	 ********************/
	$body.on('updated_checkout', function () {
		var $removeButtons = $('.extra-checkout-remove-from-cart');
		$removeButtons.each(extraInitBorderFill);
		$removeButtons.off('click', removeClickHandler);
		$removeButtons.on('click', removeClickHandler);

		$extraRemoveInput.val('');

		if ($('.checkout-cart-item').size() == 0 ) {
			$('#order_review').hide();
			$('#order_empty').show();
		}

		initScripts();
	});
	function initScripts () {
		/*********************
		 *
		 *
		 * CHECKBOXES AND RADIOS
		 *
		 *
		 ********************/
			//extraListenPaymentChange = false;
		$('input[type="checkbox"], input[type="radio"]').extraCheckbox();
		//extraListenPaymentChange = true;
	}
	initScripts();

	$body.on( 'checkout_error', extraShowNotices);
	$body.on( 'show_notices', extraShowNotices);

	function removeClickHandler (event) {
		event.preventDefault();

		// TODO passer par cart.js

		$( '.woocommerce-error, .woocommerce-message' ).remove();

		var $row = $(this).closest('.checkout-cart-item'),
			timeline = new TimelineMax(),
			productId = $(this).data('product-id');

		timeline.to($row.find('.cell-wrapper'), 0.6, {height: 0});

		//$window.trigger('extra.cart.update');
		$extraRemoveInput.val(productId);
		$body.trigger('update_checkout');
		$window.trigger('extra.checkout.itemRemoved', [productId]);
	}
	$('.extra-checkout-remove-from-cart').on('click', removeClickHandler);


	$window.on('extra.cart.itemRemoved', function (event, productId) {
		if (productId) {
			var $row = $('.checkout-cart-item-'+productId),
				timeline = new TimelineMax();

			timeline.to($row.find('.cell-wrapper'), 0.6, {height: 0});

			$window.on('extra.cart.updated', onCartUpdated);
		}
	});

	function onCartUpdated () {
		$window.off('extra.cart.updated', onCartUpdated);
		$( '.woocommerce-error, .woocommerce-message' ).remove();
		$body.trigger('update_checkout');
	}


	/*********************
	 *
	 *
	 * COUPON
	 *
	 *
	 ********************/
	var $proxyCouponButton = $('#extra_coupon_code_proxy_link'),
		$couponProxyInput = $('#extra_coupon_code_proxy'),
		$couponButton = $('#coupon_code_button'),
		$couponInput = $('#coupon_code');
	$proxyCouponButton.on('click', function (event) {
		event.preventDefault();

		$couponInput.val($couponProxyInput.val());
		// Lose focus for all fields
		$couponInput.blur();
		$couponButton.trigger('click');
	});


	/*********************
	 *
	 *
	 * Cumulative Offset
	 *
	 *
	 ********************/
	function cumulativeOffset (element) {
		var top = 0, left = 0;
		if (element) {
			do {
				top += element.offsetTop  || 0;
				left += element.offsetLeft || 0;
				element = element.offsetParent;
			} while(element);
		}

		return {
			top: top,
			left: left
		};
	}


	/*********************
	 *
	 *
	 * ACCORDION
	 *
	 *
	 ********************/
	var $lines = $('.form-line'),
		$contents = $('.form-line-content');

	$contents.each(function () {
		var $content = $(this),
			$inner = $content.find('.form-line-content-inner'),
			isOpen = false;

		function showContent(fast) {
			var speed = fast == true ? 0 : 0.5,
				height = $inner.outerHeight(true);
			TweenMax.to($content, speed, {height: height, clearProps: 'all'});
		}
		function hideContent(fast) {
			var speed = fast == true ? 0 : 0.5;
			TweenMax.to($content, speed, {height: 0});
		}
		$content.on('extra.accordion.open', function (event, fast) {
			if(!isOpen) {
				isOpen = true;
				showContent(fast);
			}
		});
		$content.on('extra.accordion.close', function (event, fast) {
			if(isOpen) {
				isOpen = false;
				hideContent(fast);
			}
		});

		hideContent(true);
		//var $line = $content.closest('.form-line');
		//if (
		//	(extra_checkout_options.step == 0 && !$line.is('.form-line-identity')) ||
		//	(extra_checkout_options.step == 1 && !$line.is('.form-line-address')) ||
		//	(extra_checkout_options.step == 2 && !$line.is('.form-line-order-review'))
		//) {
		//	hideContent(true);
		//} else {
		//	$line.addClass('form-line-current');
		//	isOpen = true;
		//}
	});
	$('html').addClass('enable-accordion');

	// LOGIN NEXT BUTTON
	$('.form-line-identity .form-link-next').on('click', function (event) {
		event.preventDefault();
		gotoAccordionLine(1, false);
	});

	// SHIPPING & BILLING NEXT BUTTON
	$('body').on('click', '#extra-validate-billing-shipping', function (event) {
		event.preventDefault();
		$('body').trigger('validate_fields');
	});
	$('body').on('fields_validated', function () {
		if (is_billing_and_shipping_validate()) {
			gotoAccordionLine(2, false);
		}
	});

	$('body').on('field_validated', function () {
		var $billingShipping = $('#extra-validate-billing-shipping');
		if (is_billing_and_shipping_validate()) {
			$billingShipping.removeClass('disabled');
		} else {
			$billingShipping.addClass('disabled');
		}
	});

	function is_billing_and_shipping_validate() {
		var $form;
		if ( $( '#ship-to-different-address input' ).is( ':checked' ) ) {
			$form = $('.woocommerce-billing-fields, .woocommerce-shipping-fields');
		} else {
			$form = $('.woocommerce-billing-fields');
		}

		var $invalides = $form.find('.woocommerce-invalid-required-field, .woocommerce-invalid-email, .woocommerce-invalid-postcode, .woocommerce-invalid-phone');
		return $invalides.size() == 0;
	}


	function gotoAccordionLine(lineIndex, fast) {
		var $identity = $('.form-line-identity'),
			$address = $('.form-line-address'),
			$orderReview = $('.form-line-order-review'),
			$current,
			offset,
			scrollToY = 0;

		if (lineIndex == 2) {
			closePreviousAccordion($identity, fast);
			closePreviousAccordion($address, fast);
			$current = $orderReview;

			offset = cumulativeOffset($address[0]);
			scrollToY = offset.top;
		} else if (lineIndex == 1) {
			closePreviousAccordion($identity, fast);
			$current = $address;
			closeNextAccordion($orderReview, fast);

			offset = cumulativeOffset($identity[0]);
			scrollToY = offset.top;
		} else {
			// Default 0
			$current = $identity;
			closeNextAccordion($address, fast);
			closeNextAccordion($orderReview, fast);

			offset = cumulativeOffset($identity[0]);
			scrollToY = offset.top - 50;
		}

		openAccordion($current, fast);
		if (!fast) {
			TweenMax.to($scrollableWrapper, 0.5, {scrollTo: {y: scrollToY}});
		}
	}
	function closePreviousAccordion($line, fast) {
		$line.removeClass('form-line-current').addClass('form-line-previous').find('.form-line-content').trigger('extra.accordion.close', [fast]);
	}
	function closeNextAccordion($line, fast) {
		$line.removeClass('form-line-previous form-line-current').find('.form-line-content').trigger('extra.accordion.close', [fast]);
	}
	function openAccordion($line, fast) {
		$line.removeClass('form-line-previous').addClass('form-line-current').find('.form-line-content').trigger('extra.accordion.open', [fast]);
	}

	// PREVIOUS CLICK
	$('.form-line .form-line-title').on('click', function () {
		var $title = $(this),
			$line = $title.closest('.form-line');

		if ($line.is('.form-line-previous')) {
			if ($line.is('.form-line-identity')) {
				gotoAccordionLine(0, false);
			} else if ($line.is('.form-line-address')) {
				gotoAccordionLine(1, false);
			} else if ($line.is('.form-line-order-review')) {
				gotoAccordionLine(2, false);
			}
		}
	});

	// INIT
	gotoAccordionLine(extra_checkout_options.step, true);
});