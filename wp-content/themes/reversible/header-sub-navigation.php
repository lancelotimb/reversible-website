<?php
if(is_checkout()) {
	return;
}
if(is_account_page()) {
	return;
}

global $post;
if ( ! $post->post_parent) {
	return;
}
?>
<nav class="sub-navigation">
	<ul class="sub-navigation-inner">
	<?php
	global $post,
			$extra_options;
	$current_post = $post;
	if ( $post->post_parent ) {
		$topParent = end( get_ancestors( $post->ID, 'page' ) );
		$children  = get_posts( array(
			'post_parent'    => $topParent,
			'post_type'      => 'page',
			'posts_per_page' => - 1
		) );
	} else {
		$children = array( $post );
	}
	foreach($children as $child) {
		$class = ($child->ID == $current_post->ID) ? ' current-menu-item' : '';
		echo '<li class="sub-navigation-item'.$class.'"><a href="'.get_permalink($child->ID).'">'.get_second_title($child->ID).'</a></li>';
	}
	?>
	</ul>
</nav>