$(document).ready(function() {
	////////////
	//
	// MATERIALS
	//
	////////////
	var $items = $('.references-item'),
		isSmall = false;
	$items.each(function(){
		var $item = $(this),
			$content = $item.find(".reference-content-wrapper"),
			$inner = $item.find(".reference-content-inner"),
			_height = 0,
			open = false;

		$item

		// SHOW
		.on("show", function() {
			open = true;
			$item.addClass('active');
			_height = $inner.outerHeight(true);
			TweenMax.to($content, 0.3, {height: _height});
		})

		// HIDE
		.on("hide", function(event, fast){
			var speed = fast ? 0 : 0.3;
			open = false;
			$item.removeClass("active");
			TweenMax.to($content, speed, {height: 0});
		})

		// CLICK
		.on("click", function(e) {
			e.preventDefault();
			if(open == true) {
				$item.trigger("hide");
			} else {
				$items.not($item).trigger("hide");
				$item.trigger("show");
				if(!isSmall) {
					TweenMax.to($scrollableWrapper, 1, {scrollTo: {y: cumulativeOffset($item.find('.reference-content')[0]).top - 60}});
				}
			}
		})

		// START HIDDEN
		.trigger("hide", [true]);
	});

	$window.on('extra.resize', function() {
		updateSizes();
	});

	function updateSizes() {
		if($items.first().width() <= 410 && !isSmall) {
			isSmall = true;
			$items.addClass("small-item");
		} else {
			if(isSmall) {
				isSmall = false;
				$items.removeClass("small-item");
			}
		}
	}
	updateSizes();


	function cumulativeOffset (element) {
		var top = 0, left = 0;
		if (element) {
			do {
				top += element.offsetTop || 0;
				left += element.offsetLeft || 0;
				element = element.offsetParent;
			} while(element);
		}

		return {
			top: top,
			left: left
		};
	}
});