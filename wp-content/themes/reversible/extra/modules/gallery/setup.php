<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 19/05/15
 * Time: 09:35
 */

add_filter('extra_gallery_width', 'extra_reversible_gallery_width', 10, 3);
add_filter('extra_gallery_height', 'extra_reversible_gallery_height', 10, 3);

function extra_reversible_gallery_width ($width, $type, $size) {
	if ($type == 'gallery') {
		global $content_width;
		$width = ($content_width - 30) / 5;
	}
	return $width;
}
function extra_reversible_gallery_height ($height, $type, $size) {
	if ($type == 'gallery') {
		global $content_width;
		$height = ($content_width - 30) / 5;
	}
	return $height;
}
