$(document).ready(function() {
	////////////
	//
	// MATERIALS
	//
	////////////
	var $wrapper = $(".materials-wrapper"),
		$items = $wrapper.find('.material-item');
	$items.each(function(){
		var $item = $(this),
			$content = $item.find('.material-content'),
			$inner = $content.find('.material-content-inner'),
			$link = $item.find('.material-link'),
			isOpen = false;
		function showContent() {
			var height = $inner.outerHeight(true);
			TweenMax.to($content, 0.3, {height: height});
		}
		function hideContent(fast) {
			var speed = fast == true ? 0 : 0.3;
			TweenMax.to($content, speed, {height: 0});
		}
		$link.on("click", function() {
			isOpen = !isOpen;
			if(isOpen) {
				showContent();
			} else {
				hideContent();
			}
		});
		//hideContent(true);
	});
	////////////
	//
	// SCROLLOLLO
	//
	////////////
	var items = [];
		prevScroll = 0;
		scrollTop = 0;
	$items.each(function(index, element){
		var $this = $(this),
			index = $this.index(),
			$image = $this.find('.material-image');

		$this.data('index', $this.index());
		items[index] = {};
		items[index]['elem'] = $this;
		items[index]['image'] = $image;
		items[index]['offsetTop'] = cumulativeOffset(element).top;
		items[index]['offsetLeft'] = $this.offset().left;
		$this.on('complete.extra.responsiveImage', updateImage);
		$window.on("extra.resize", function(){
			items[index]['offsetTop'] = cumulativeOffset($this[0]).top;
			items[index]['offsetLeft'] = $this.offset().left;
			updateImage();
		});
		$this.on('updateImage', updateImage);
		function updateImage(e) {
			$image.css({
				backgroundImage: 'url(' + items[index]['image'].find('img').first().attr('src') + ')',
				backgroundSize: '' + $this.width() + 'px auto',
				backgroundPosition: items[index]['offsetLeft'] + 'px ' + '0px'
			});

		}
	});
	function updateElement(_item) {
		var item = items[$(_item).data('index')],
			margin = 20,
			relativeScroll = (item.offsetTop - scrollTop) / 420 - 0.5;
		TweenMax.set(item.image, {
			backgroundPosition:  item.offsetLeft + 'px ' + (margin * relativeScroll) + 'px'
		});
	}
	function animate(){
		if(small) {
			return;
		}
		requestAnimationFrame(animate);
		scrollTop = $scrollableWrapper.scrollTop();
		if(prevScroll != scrollTop) {
			prevScroll = scrollTop;
			$items.each(function(){
				updateElement(this);
			});
		}
	}
	$window.on("extra.responsive-resize", function(){
		if(!small) {
			requestAnimationFrame(animate);
			$.each(items, function(index, item){
				item.elem.trigger('updateImage');
			});
		}
	});
	requestAnimationFrame(animate);


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

// http://paulirish.com/2011/requestanimationframe-for-smart-animating/
// http://my.opera.com/emoller/blog/2011/12/20/requestanimationframe-for-smart-er-animating

// requestAnimationFrame polyfill by Erik Möller. fixes from Paul Irish and Tino Zijdel

// MIT license

(function() {
	var lastTime = 0;
	var vendors = ['ms', 'moz', 'webkit', 'o'];
	for(var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
		window.requestAnimationFrame = window[vendors[x]+'RequestAnimationFrame'];
		window.cancelAnimationFrame = window[vendors[x]+'CancelAnimationFrame']
		|| window[vendors[x]+'CancelRequestAnimationFrame'];
	}

	if (!window.requestAnimationFrame)
		window.requestAnimationFrame = function(callback, element) {
			var currTime = new Date().getTime();
			var timeToCall = Math.max(0, 16 - (currTime - lastTime));
			var id = window.setTimeout(function() { callback(currTime + timeToCall); },
				timeToCall);
			lastTime = currTime + timeToCall;
			return id;
		};

	if (!window.cancelAnimationFrame)
		window.cancelAnimationFrame = function(id) {
			clearTimeout(id);
		};
}());