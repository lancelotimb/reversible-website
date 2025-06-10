<?php
/**********************
 *
 *
 * PUBLIC FUNCTION
 *
 *
 *********************/
global $extra_sharer_counter;
$extra_sharer_counter = 0;
function extra_custom_share($id = 0) {

	global  $post,
			$extra_options,
			$extra_sharer_counter;

	if ( $id == 0 && isset($post->ID)) {
		$id = $post->ID;
	}

	if(!isset($id)) {
		return;
	}

	$extra_sharer_counter++;

	$title = get_the_title();
	$blog_title = get_bloginfo('name');
	$link = get_permalink( $id );

	// IF LINK, ECHO SHARE
	if ( ! empty( $link ) ) {
		$return = '<div class="extra-social-wrapper"><div class="extra-social-inner">';
		$return .= '<div class="extra-social-button-wrapper extra-social-email"><a href="#extra-social-share-'.$extra_sharer_counter.'-wrapper" class="extra-social-button extra-social-share"><span class="icon-wrapper"><svg class="icon icon-mail"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-mail"></use></svg></span><span class="text">' . __( 'Partager par email', 'extra' ) . '</span></a></div>';
		$return .= '<div class="extra-social-button-wrapper extra-social-facebook"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=' . $link . '" class="extra-social-button extra-social-facebook" data-url="' . $link . '" data-counter="https://graph.facebook.com/?ids=' . urlencode($link) . '"><span class="icon-wrapper"><svg class="icon icon-facebook"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-facebook"></use></svg></span><span class="text">' . __( 'Partager sur Facebook', 'extra' ) . '</span><span class="counter"></span></a></div>';
		$return .= '<div class="extra-social-button-wrapper extra-social-twitter"><a target="_blank" href="https://twitter.com/home?status=' . utf8_uri_encode(sprintf(__('Lire l\'article intitulé %s sur %s : %s', 'extra'), $title, $blog_title, $link)) . '" class="extra-social-button extra-social-twitter" data-url="' . $link . '" data-counter="http://cdn.api.twitter.com/1/urls/count.json?url=' . urlencode($link) . '&amp;callback=?"><span class="icon-wrapper"><svg class="icon icon-twitter"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-twitter"></use></svg></span><span class="text">' . __( 'Partager sur Twitter', 'extra' ) . '</span><span class="counter"></span></a></div>';
		$return .= '<div class="extra-social-button-wrapper extra-social-gplus"><a target="_blank" href="https://plus.google.com/share?url=' . $link . '" class="extra-social-button extra-social-gplus" data-url="' . $link . '" data-counter="' . THEME_MODULES_URI . '/custom_share/gplus.php?url= ' . urlencode($link) . '"><span class="icon-wrapper"><svg class="icon icon-gplus"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-gplus"></use></svg></span><span class="text">' . __( 'Partager sur Google+', 'extra' ) . '</span><span class="counter"></span></a></div>';
		$return .= '<div class="js-hidden"><div class="extra-social-share-wrapper" id="extra-social-share-'.$extra_sharer_counter.'-wrapper"><h3>'.__('Partager par email', 'extra').'</h3>'. do_shortcode($extra_options['share_shortcode']) .'</div></div>';
		$return .= '</div></div>';
		echo $return;
	}
}
/**********************
 *
 *
 * ENQUEUE ASSETS
 *
 *
 *********************/
function extra_custom_social_enqueue_assets() {
	if (is_singular('post')) {
		wp_enqueue_style('extra-form', THEME_URI.'/assets/css/form.less', array('extra-content'));
		wp_enqueue_style('extra-custom-share', THEME_MODULES_URI.'/custom_share/front/css/custom_share.less', array('extra-form'));
		wp_enqueue_script('extra-custom-share', THEME_MODULES_URI.'/custom_share/front/js/custom_share.js', array('jquery'), false, true);
	}
}
add_action('wp_enqueue_scripts', 'extra_custom_social_enqueue_assets');
/**********************
 *
 *
 *
 * SHARE CONTACT FORM
 *
 *
 *
 *********************/
function extra_share_contact_wpcf7_before_send_mail($cf7){
	$sender = $cf7->posted_data['sender'];
	$sender_array = explode('@', $sender);

	$username = $sender_array[0];
	$username = preg_replace('/[^a-zA-Z0-9]+/', ' ', $username);
	$username_array = explode(' ', $username);
	$username_array = array_map(ucfirst, $username_array);
	$username = implode(' ', $username_array);

	$cf7->posted_data['username'] = $username;

	add_filter('wp_mail_from_name', 'extra_share_contact_filter_wordpress_name');
}
add_action('wpcf7_before_send_mail', 'extra_share_contact_wpcf7_before_send_mail');
/**********************
 *
 *
 *
 * DEFAULT MESSAGE
 *
 *
 *
 *********************/
function extra_share_contact_wpcf7_form_tag($tags) {

	if(is_admin()) {
		return $tags;
	}

	global $post;
	// WARNING TABULATION IN MESSAGE IS KEPT ON FRONT
	if($tags['name'] == 'share_message') {
		$tags['values'] = array(
			__('Bonjour,', 'extra').'

'.
			__('Je vous invite à aller voir ', 'extra').$post->post_title.'
'
			.get_permalink($post->ID).'

'.
			__('Cordialement.', 'extra')
		);
	}
	return $tags;
}
add_action('wpcf7_form_tag', 'extra_share_contact_wpcf7_form_tag');
function extra_share_contact_filter_wordpress_name ($from_name) {
	if ('WordPress' == $from_name) {
		$from_name = 'Reversible';
	}

	return $from_name;
}



function extra_custom_share_global_options($sections) {

	$sections[] = array(
		'icon' => 'el-icon-website',
		'title' => __('Partage', 'extra-admin'),
		'desc' => null,
		'fields' => array(
			array(
				'id' => 'share_shortcode',
				'type' => 'text',
				'title' => __('Shortcode formulaire de partage par email', 'extra-admin'),
			),
		)
	);

	return $sections;
}
add_filter('extra_default_global_options_section', 'extra_custom_share_global_options', 10, 1);
