jQuery(function ($) {
	var $flashMessages = $('#extra-flash-messages-link');

	if ($flashMessages.size() > 0) {
		$flashMessages.fancybox({
			padding: 0,
			margin: 0,
			width: 540,
			height: 360,
			autoSize: false,
			closeBtn: false
		}).trigger('click');

		$('.extra-flash-messages-close-link').on('click', function (event) {
			if ($(this).hasClass('close-button')) {
				event.preventDefault();
			}
			$.fancybox.close();
		});
	}

	// Init Extra notices
	extraShowNotices();

	$window.on('load', function () {
		$.get(extraFlashOptions.ajaxUrl, {action: 'extra_get_notices'})
			.done(function (response) {
				var json = null;
				if (response){
					try{
						json = $.parseJSON(response);
					} catch (e) {
						console.log(e);
					}
				}
				if (json) {
					getNoticesHandler(json);
				}
			})
			.fail(function (error) {
				console.log(error);
			});
	});

	function getNoticesHandler(json) {
		var $extraNoticesWrapper = $('.extra-checkout-notices'),
			show = false;
		// NOTICES
		if (json.hasOwnProperty('notices')) {
			show = true;
		}
		// MESSAGES
		if (json.hasOwnProperty('messages')) {
			var messages = json.messages;
			for (var index in messages) {
				if (messages.hasOwnProperty(index)) {
					$extraNoticesWrapper.append(messages[index]);
				}
			}
			show = true;
		}
		if (show) {
			extraShowNotices();
		}
	}
});

function extraShowNotices () {
	var $notices = $('.extra-checkout-notices');

	if ($notices.find('> *').size() > 0) {

		var $noticesLink = $('#extra-checkout-notices-link'),
			errorMessages = $notices.find('.woocommerce-error');

		if (errorMessages.size() > 0) {
			errorMessages.before('<svg class="icon icon-broken-heart"><use xlink:href="#icon-broken-heart"></use></svg>');
		}

		$noticesLink.fancybox({
			padding: 0,
			margin: 0,
			width: 540,
			height: 360,
			autoSize: false,
			autoHeight: true,
			closeBtn: false
		}).trigger('click');

		$('.extra-flash-messages-close-link').on('click', function (event) {
			if ($(this).hasClass('close-button')) {
				event.preventDefault();
			}
			$.fancybox.close();
		});
	}
}