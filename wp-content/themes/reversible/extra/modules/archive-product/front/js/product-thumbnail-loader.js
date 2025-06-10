/*******************************
 *
 *
 * LOAD PRODUCT
 *
 *
 ******************************/

function extraLoadProductThumbnail($product) {
	if (!$product.data('tumbnail-loaded')) {
		//console.log('thumbnail load : ' + $product.data('product-id'));

		$product.data('tumbnail-loaded', true);
		extraLoaderPlay($product);

		var $img = $product.find('.extra-product-thumbnail');
		var imgSrc = $img.data('thumbnail-src');
		if (imgSrc) {
			$img.load(function () {
				//console.log('thumbnail loaded : ' + $product.data('product-id'));
				var timeline = new TimelineMax();
				timeline.addCallback(function() {
					extraLoaderStop($product);
				});
				//timeline.set($img, {opacity: 1});
				timeline.to($img, 0.6, {opacity: 1, lazy: true});
				//timeline.set($img, {opacity: 1});
				//timeline.addCallback(function () {
				//	console.log('thumbnail appeared : ' + $product.data('product-id'));
				//});
			}).attr({
				src: imgSrc
			});
		}
	}
}