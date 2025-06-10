<?php
	global $post;
	if ( is_page() && $post->post_parent != 0 ) {
		$topParent = end( get_ancestors( $post->ID, 'page' ) );
	}

	else if(is_home() || is_singular('post')) {
		$topParent = get_option('page_for_posts');
	}

	else {
		$topParent = $post;
	}
?>
<div class="main-image-wrapper">
	<div class="top-parent-title-wrapper">
		<div class="top-parent-title-inner">
			<h2 class="top-parent-title"><?php echo get_second_title( $topParent ); ?></h2>
			<a href="#" class="to-content-link extra-button"><svg class="icon arrow-bottom"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-arrow-down"></use></svg></a>
		</div>
	</div>
	<?php
		$idToUse = null;
		if(is_home() || is_singular('post')) {
			if(has_post_thumbnail(get_option('page_for_posts'))) {
				$idToUse = get_post_thumbnail_id( get_option('page_for_posts'));
			}
		}
		else if(has_post_thumbnail()) {
			$idToUse = get_post_thumbnail_id();
		}
		else if (($post->post_parent) && $post->post_parent > 0){
			$ancestors = get_post_ancestors($post->ID);
			foreach($ancestors as $ancestor) {
				if(has_post_thumbnail($ancestor)) {
					$idToUse = get_post_thumbnail_id($ancestor);
					break;
				}
			}
		}
		if($idToUse != null) {
			extra_responsive_image($idToUse, array(
				'desktop' => array(1620,420),
				'tablet' => array(1024,350),
				'mobile' => array(690,200)
			), 'main-image');
		}
	?>
</div>