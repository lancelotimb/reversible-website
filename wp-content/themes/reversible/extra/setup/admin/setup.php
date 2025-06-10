<?php
/**********************
 *
 *
 *
 * TINY MCE
 *
 *
 *
 *********************/
function _blank_tinymce($init) {
	$style_formats = json_decode($init['style_formats']);
	if(empty($style_formats) || !is_array($style_formats)) {
		$style_formats = array();
	}
	$style_formats = array_merge($style_formats, array(
	   array(
			'title' => 'Lien bouton',
			'selector' => 'a',
			'classes' => 'link-button',
		), array(
			'title' => 'Lien important',
			'selector' => 'a',
			'classes' => 'link-important'
		), array(
			'title' => 'Chapô',
			'block' => 'div',
			'classes' => 'chapo',
			'wrapper' => true
		), array(
			'title' => 'Image sans marge',
			'selector' => 'img',
			'classes' => 'nomargin'
		), array(
			'title' => 'Bandes',
			'block' => 'div',
			'wrapper' => true,
			'classes' => 'strip-gallery'
		)
	));
	$init['style_formats'] = json_encode($style_formats);

	$init['block_formats'] = 'Paragraph=p;Heading 1=h1;Heading 2=h2;Heading 3=h3;Heading 4=h4;';

	return $init;
}

add_filter('tiny_mce_before_init', '_blank_tinymce');


add_filter('extra_add_global_options_section', function ($sections) {
	$sections[] = array(
		'icon' => 'el-icon-file',
		'title' => __('Pages', 'extra-admin'),
		'desc' => null,
		'fields' => array(
			array(
				'id' => 'page_shop',
				'type' => 'select',
				'data' => 'page',
				'title' => __('Page "Boutique"', 'extra-admin'),
			),
			array(
				'id' => 'page_professional',
				'type' => 'select',
				'data' => 'page',
				'title' => __('Page "Professionnels"', 'extra-admin'),
			)
		)
	);
	return $sections;
});



function extra_clear_wp_super_cache_button() {
	if (is_admin()) {
		if (function_exists('wp_cache_clear_cache')) {
			global $wp_admin_bar;
			if ( !current_user_can('clear_shop_cache') || !is_admin_bar_showing() )
				return;

			$request = extra_get_full_url();

			$wp_admin_bar->add_menu( array(
				'id' => 'custom_menu',
				'title' => __( 'Vider le cache "Boutique"'),
				'href' => admin_url('admin-post.php?action=extra_clear_cache&redirect_to='.urlencode($request))
			) );
		}
	}
}
add_action('admin_bar_menu', 'extra_clear_wp_super_cache_button', 999);

function extra_get_full_url() {
	$pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
	if ($_SERVER["SERVER_PORT"] != "80")
	{
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	}
	else
	{
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}


add_action( 'admin_post_extra_clear_cache', 'extra_clear_cache' );
function extra_clear_cache() {
	if (function_exists('wp_cache_clear_cache')) {
		$redirect_to = isset($_GET['redirect_to']) ? $_GET['redirect_to'] : admin_url();
		wp_cache_post_change(wc_get_page_id( 'shop' ));

		update_option('show_extra_clear_cache_notices', true);

		wp_redirect($redirect_to);
	}
}

function extra_clear_cache_notices() {
	if (get_option('show_extra_clear_cache_notices')) {
		delete_option('show_extra_clear_cache_notices');
		?>
		<div class="updated">
			<p><?php _e( '<strong>Attention !</strong><br>Le cache de la boutique a été vidé.', 'extra' ); ?></p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'extra_clear_cache_notices' );