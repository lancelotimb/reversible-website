jQuery(function ($) {

	var $form = $('.newsletter-form'),
		$button = $form.find('.newsletter-button'),
		$input = $form.find('.newsletter-email'),
		loading = false;
	$form.on('submit', function (event) {
		event.preventDefault();

		if (!loading) {
			var email = $input.val(),
				data = {
					'action': 'extra_sarbacane_subscription',
					'email': email
				};

			if (isValid(email)) {
				loading = true;
				$form.addClass('loading');
				$input.prop('disabled', true);
				extraLoaderPlay($button);
				$.post(extraSarbacaneOptions.ajaxUrl, data)
					.done(function (response) {
						if (response == 'success') {
							showSuccess();
						} else {
							showError();
						}
					})
					.fail(function (error) {
						showError();
					})
					.always(function () {
						$form.removeClass('loading');
						loading = false;
						$input.prop('disabled', false);
						extraLoaderStop($button);
					});
			} else {
				showNotValid();
			}
		}
	});

	function isValid (email) {
		var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
		return  pattern.test(email);
	}

	function showError () {
		var $noticesContainer = $('.extra-checkout-notices'),
			$errorMessages = $('<ul class="woocommerce-error"></ul>');

		$errorMessages.append('<li>'+extraSarbacaneOptions.messages.error+'</li>');
		$noticesContainer.html($errorMessages);
		extraShowNotices();
	}

	function showNotValid () {
		var $noticesContainer = $('.extra-checkout-notices'),
			$errorMessages = $('<ul class="woocommerce-error"></ul>');

		$errorMessages.append('<li>'+extraSarbacaneOptions.messages.notValid+'</li>');
		$noticesContainer.html($errorMessages);
		extraShowNotices();
	}

	function showSuccess() {
		$('.extra-checkout-notices').html('<p>'+extraSarbacaneOptions.messages.success+'</p>');
		extraShowNotices();
	}
});