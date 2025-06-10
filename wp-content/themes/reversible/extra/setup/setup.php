<?php
/**********************
 *
 *
 *
 * THEME SETUP
 *
 *
 *
 *********************/
function extra_setup() {
	// LANGUAGE
	load_theme_textdomain('extra', get_template_directory().'/includes/lang');

	// DEFAULT POST THUMBNAIL SIZE
	add_theme_support('post-thumbnails', array('post', 'page', 'product'));

	// AUTO RSS
	add_theme_support( 'automatic-feed-links' );

	// HTML 5
	add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );

	$default_nav_menus = array(
		'main' => 'Principale',
		'mobile' => 'Mobile',
		'footer' => 'Pied de page'
	);

	// NAVIGATION MENUS
	register_nav_menus(apply_filters('extra_default_nav_menus', $default_nav_menus));

	// CAP
	$editors = apply_filters('extra_editor_roles', array(get_role( 'editor' )));
	foreach($editors as $editor) {
		$editor->add_cap( 'manage_options' );
		$editor->add_cap( 'edit_theme_options' );
	}

	global $content_width;
//	$content_width = apply_filters('extra_content_width', 940);
	$content_width = apply_filters('extra_content_width', 780);

	add_editor_style('extra/assets/css/editor-style.less');

	// THIS IS A WOOCOMMERCE THEME
	add_theme_support( 'woocommerce' );
}
add_action('after_setup_theme', 'extra_setup');
/**********************
 *
 *
 *
 * TYPE KIT IN ADMIN
 *
 *
 *
 *********************/
add_filter("mce_external_plugins", "extra_mce_external_plugins");
function extra_mce_external_plugins($plugin_array){
	$plugin_array['typekit']  =  THEME_URI.'/assets/js/lib/typekit.tinymce.js';
	return $plugin_array;
}
/**********************
 *
 *
 *
 * LESS VARS
 *
 *
 *
 *********************/
function extra_theme_less_vars($vars, $handle) {
	$vars['font1'] = 'proxima-nova, sans-serif';
	$vars['font2'] = 'proxima-nova-condensed, sans-serif';
	$vars['white'] = '#ffffff';
	$vars['black'] = '#58585a';
	$vars['dark'] = '#333333';
	$vars['grey'] = '#f5f5f5';
	$vars['lightgrey'] = '#d5d5d5';
	$vars['highlight'] = '#acc06f';
	$vars['highlight2'] = '#779f00';
	return $vars;
}

add_filter('less_vars', 'extra_theme_less_vars', 10, 2);
/**********************
 *
 *
 *
 * FAVICON
 *
 *
 *
 *********************/
function extra_favicon() {  ?>
	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo home_url("/"); ?>/apple-touch-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="<?php echo home_url("/"); ?>/apple-touch-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo home_url("/"); ?>/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="<?php echo home_url("/"); ?>/apple-touch-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo home_url("/"); ?>/apple-touch-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="<?php echo home_url("/"); ?>/apple-touch-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo home_url("/"); ?>/apple-touch-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="<?php echo home_url("/"); ?>/apple-touch-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo home_url("/"); ?>/apple-touch-icon-180x180.png">
	<link rel="icon" type="image/png" href="<?php echo home_url("/"); ?>/favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="<?php echo home_url("/"); ?>/favicon-194x194.png" sizes="194x194">
	<link rel="icon" type="image/png" href="<?php echo home_url("/"); ?>/favicon-96x96.png" sizes="96x96">
	<link rel="icon" type="image/png" href="<?php echo home_url("/"); ?>/android-chrome-192x192.png" sizes="192x192">
	<link rel="icon" type="image/png" href="<?php echo home_url("/"); ?>/favicon-16x16.png" sizes="16x16">
	<link rel="manifest" href="<?php echo home_url("/"); ?>/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="<?php echo home_url("/"); ?>/mstile-144x144.png">
	<meta name="theme-color" content="#ffffff">
<?php }
add_action('wp_head', 'extra_favicon');
add_action('admin_head', 'extra_favicon');
add_action('login_head', 'extra_favicon');

/**********************
 *
 *
 *
 * REMOVE ADMIN BAR MARGIN
 *
 *
 *
 *********************/
function my_admin_bar_init() {
	remove_action('wp_head', '_admin_bar_bump_cb');
}
add_action('admin_bar_init', 'my_admin_bar_init');



/**********************
 *
 *
 *
 * HOOK MENU TITLES
 *
 *
 *
 *********************/
function reversible_hook_nav_menu_footer ($items, $menu, $args) {
	global $extra_options;

	foreach ($items as $item) {
		if ($item->object_id === $extra_options['page_shop']) {
			$item->classes[] = 'menu-item-primary';
		} else if ($item->object_id === $extra_options['page_professional']) {
			$item->classes[] = 'menu-item-primary menu-item-primary-first';
		}
	}
	return $items;
}
add_filter('wp_get_nav_menu_items','reversible_hook_nav_menu_footer', 10, 3);


/**********************
 *
 *
 *
 * RESPONSIVE SIZES
 *
 *
 *
 *********************/
function extra_custom_responsive_sizes($sizes) {
	$sizes = array(
		'desktop' => 'only screen and (min-width: 1025px)',
		'smalldesktop' => 'only screen and (min-width: 1025px) and (max-width: 1740px)',
		'tablet' => 'only screen and (min-width: 691px) and (max-width: 1024px)',
		'mobile' => 'only screen and (max-width: 690px)'
	);

	return $sizes;
}
add_filter('extra_responsive_sizes', 'extra_custom_responsive_sizes', 10, 1);

/**********************
 *
 *
 *
 * REMOVE EMOJI
 *
 *
 *
 *********************/
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');


function extra_excluded_modules_directories($excluded_modules_directories, $is_framework) {
	if ($is_framework) {
		$excluded_modules_directories[] = 'custom_share';
	}

	return $excluded_modules_directories;
}
add_filter('extra_excluded_modules_directories', 'extra_excluded_modules_directories', 10, 2);