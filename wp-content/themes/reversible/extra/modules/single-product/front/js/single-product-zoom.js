function ExtraProductZoom($zoomLink, options) {
	var self = this;

	var dw, dh, deltaW, deltaH, mouseX, mouseY, targetW, targetH, containerW, containerH;

	self.options = options;
	self.$target = $zoomLink.find('img');
	self.$container = $zoomLink;
	self.$easyzoom = $zoomLink.closest('.easyzoom');
	self.follow = true;

	self.timeline = new TimelineMax();

	self.$html = $('html');

	mouseX = self.options.mouseX;
	mouseY = self.options.mouseY;
	self.isTouch = self.$html.hasClass('touch');

	self.onMove = function (e) {
		if (self.follow) {
			if (targetW > containerW || targetH > containerH) {
				if (e.type.indexOf('touch') === 0) {
					var touchlist = e.touches || e.originalEvent.touches;
					mouseX = touchlist[0].pageX;
					mouseY = touchlist[0].pageY;
				} else {
					mouseX = e.pageX || mouseX;
					mouseY = e.pageY || mouseY;
				}

				self.move(mouseX, mouseY, 0.3);
			}
		}
	};

	self.move = function (mouseX, mouseY, speed) {
		var top = 0,
			left = 0;
		if (targetW >= containerW ) {
			deltaW = targetW - containerW;
			left = -1 * (mouseX / containerW) * deltaW;

			if (self.isTouch) {
				left = -deltaW - left;
			}
		} else {
			left = (containerW - targetW) / 2;
		}
		if (targetH >= containerH) {
			deltaH = targetH - containerH;
			top = -1 * (mouseY / containerH) * deltaH;

			if (self.isTouch) {
				top = -deltaH - top;
			}
		} else {
			top = (containerH - targetH) / 2;
		}

		if (self.isTouch) {

		}

		//console.log({
		//	top: top,
		//	left: left
		//});

		TweenMax.to(self.$target, speed, {
			top: top,
			left: left
		});
		//self.$target.css({
		//	top: top,
		//	left: left
		//});
	};

	self.onTouchEnd = function (e) {
		//console.log('onTouchEnd');
	};
	self.onTouchStart = function (e) {
		//console.log('onTouchStart');
		var touches = e.originalEvent.touches;
		if (!touches || touches.length == 1) {
			self.onMove(e);
		}
	};

	self.resize = function (first) {
		self.$target.css({
			top: 0,
			left: 0
		});

		targetW = self.$target.width();
		targetH = self.$target.height();
		containerW = self.$container.outerWidth(true);
		containerH = self.$container.outerHeight(true);

		//console.log('targetW targetH = '+targetW + '-' +targetH);
		//console.log('containerW containerH = '+containerW + '-' +containerH);

		if (first) {
			self.move(mouseX, mouseY, 0);
		} else {
			self.move(containerW / 2, containerH / 2, 0);
		}
	};

	self.stopFollowing = function () {
		self.follow = false;
		self.$html.removeClass('zoom-on');
	};

	self.stop = function () {
		self.$target.css({
			top: 0,
			left: 0
		});

		self.$container.off('touchend', self.onTouchEnd);
		self.$container.off('touchstart', self.onTouchStart);
		self.$container.off('mousemove', self.onMove);
		self.$container.off('touchmove', self.onMove);

		//console.log('ExtraProductZoom stopped');
		self.$html.removeClass('zoom-on');
	};

	self.close = function () {
		self.timeline.kill();
		self.timeline = new TimelineMax();
		self.timeline.to(self.$easyzoom, 0.3, {opacity: 0});
		self.timeline.to(self.$target, 0.3, {scale: 0.7}, 0);

		self.timeline.addCallback(function () {
			self.stop();
			$.fancybox.close();
		});
	};

	if (self.$target.size() > 0) {
		// Handler
		self.$container.on('touchend', self.onTouchEnd);
		self.$container.on('touchstart', self.onTouchStart);
		self.$container.on('mousemove', self.onMove);
		self.$container.on('touchmove', self.onMove);
		$window.on('extra.resize', self.resize);
		setTimeout(function () {
			self.resize(true);

			self.timeline.kill();
			self.timeline = new TimelineMax();
			self.timeline.to(self.$easyzoom, 0.45, {opacity: 1});
			self.timeline.from(self.$target, 0.45, {scale: 0.5}, 0);

			self.timeline.addCallback(function () {
				self.$html.addClass('zoom-on');
			});
		}, 0);
	}
	//console.log('ExtraProductZoom started !!');
}