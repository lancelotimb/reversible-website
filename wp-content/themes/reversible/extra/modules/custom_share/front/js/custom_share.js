$(document).ready(function() {
	$(".extra-social-button").not('.extra-social-share').each(function() {
		var $this = $(this),
			url = $this.data("url"),
			counter = $this.data("counter");
		$.getJSON($(this).data('counter'), function(data) {
			if(data['count']) {
				$this.find('.counter').text(data['count']);
			} else {
				$.each(data, function (key, val) {
					if (val['shares']) {
						$this.find('.counter').text(val['shares']);
					}
				});
			}
		});
		$this.on('click', function(e) {
			e.preventDefault();
			window.open($this.attr('href'),"Partage","menubar=no, status=no, scrollbars=no, menubar=no, width=600, height=500");
		});
	});

	$('.extra-social-share').fancybox( {
			tpl: {
				prev : '<a title="Précédent" class="fancybox-nav fancybox-prev" href="javascript:;"><span class="extra-button"><svg class="icon arrow-left"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#arrow-left"></use></svg></span></a>',
				next : '<a title="Suivant" class="fancybox-nav fancybox-next" href="javascript:;"><span class="extra-button"><svg class="icon arrow-right"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#arrow-right"></use></svg></span></a>',
				closeBtn: '<a title="Fermer" class="fancybox-item fancybox-close extra-button" href="http://dev.extralagence.com/www.reversible.fr/panier/" title="Voir mon panier"><svg class="icon icon-close"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-close"></use></svg></a>'
			}
		}
	);
});