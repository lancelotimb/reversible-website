var extraMultipleCounter = 0;
jQuery(document).ready(function ($) {

	console.log(medias);

	var $reference = $("#reference"),
		$wrapper = $("#extra_multiple_form");
	$("#add-content").on('click',function (event) {
		event.preventDefault();

		var $new = $reference.clone().appendTo($wrapper);
		$new.removeAttr('id');
		$new.addClass('new-post-'+extraMultipleCounter);
		adjustProps($new);

		var previousSelector = '.new-post-'+(extraMultipleCounter-1),
			$previous = $(previousSelector);
		copyHelper($new, $previous);

		$(window).scrollTop($new.offset().top).trigger('extra_wpa_copy', [$new]);
		extraMultipleCounter++;
	}).trigger("click");

	$wrapper.on('submit', function () {
		var r = confirm("Voulez-vous vraiment créer ces contenus ?");
		if (r == true) {
			return;
		}
		else {
			return false;
		}
	});

	function adjustProps($parent) {
		var theProps = ['name', 'id', 'for'];
		$parent.find('[name], [for]').each(function () {
			var $element = jQuery(this);
			jQuery.each(theProps, function (index, value) {
				if ($element.is('[' + value + ']')) {
					var oldValue = $element.prop(value),
						newValue = oldValue.replace('[multiple_ref]', '[' + extraMultipleCounter + ']');
					$element.prop(value, newValue);

				}
			});
		});
	}

	function copyHelper($new, $previous) {
		if ($previous.length > 0 && $new.length > 0) {
			var title = $previous.find('.title').val(),
				arrayTitle = title.split(' '),
				lastPartTitle = arrayTitle[arrayTitle.length - 1];

			if (isNormalInteger(lastPartTitle)) {
				//console.log(lastPartTitle);
				//console.log('Number is detected => apply generator');

				var lastNumber = parseInt(lastPartTitle);
				lastNumber++;
				arrayTitle[arrayTitle.length - 1] = lastNumber;
				var newTitle = arrayTitle.join(' ');
				// TITLE
				$new.find('.title').val(newTitle);
				// PRICE
				$new.find('.price').val(Math.round(getRandomArbitrary(20, 200)*10)/10);
				// IMAGE

				var $imageInputBox = $new.find('.extra-custom-image '),
					selectedMedia = medias[ Math.floor(getRandomArbitrary(0, medias.length))],
					imageSrc = selectedMedia.src[0],
					imageWidth = selectedMedia.src[1],
					imageHeight = selectedMedia.src[2],
					$img = $('<img src="'+imageSrc+'" width="'+imageWidth+'" height="'+imageHeight+'"/>');

				$imageInputBox.find('.image-input').val(selectedMedia.id);
				$imageInputBox.find(".image:first").removeClass("empty").html("").append($img).append('<a class="close" href="#close"><span class="dashicons dashicons-no"></span></a>');
			}
		}
	}

	function isNormalInteger(str) {
		var n = ~~Number(str);
		return String(n) === str && n >= 0;
	}

	function getRandomArbitrary(min, max) {
		return Math.random() * (max - min) + min;
	}
});