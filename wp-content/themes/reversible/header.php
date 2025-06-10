<?php

// GLOBAL OPTIONS
global $extra_options;

?><!DOCTYPE html>
<!--[if lt IE 7 ]><html <?php language_attributes(); ?> class="no-js ie ie6 lte7 lte8 lte9"><![endif]-->
<!--[if IE 7 ]><html <?php language_attributes(); ?> class="no-js ie ie7 lte7 lte8 lte9"><![endif]-->
<!--[if IE 8 ]><html <?php language_attributes(); ?> class="no-js ie ie8 lte8 lte9"><![endif]-->
<!--[if IE 9 ]><html <?php language_attributes(); ?> class="no-js ie ie9 lte9 recent"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html <?php language_attributes(); ?> class="recent noie no-js"><!--<![endif]-->
<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>"/>
	<link rel="profile" href="http://gmpg.org/xfn/11"/>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>"/>

	<!--
	EEEEEEEEEEE               EEEE
	EEEEEEEEEEE EEEE   EEEE EEEEEEEE EEEEEEEEEEEEEEEEE
	EEEE         EEEEEEEEE  EEEEEEEE EEEEEEEE EEEEEEEEEE
	EEEEEEEEEE    EEEEEEE     EEEE    EEEE        EEEEEE
	EEEE           EEEEE      EEEE    EEEE    EEEEEEEEEE
	EEEEEEEEEEE  EEEEEEEEE    EEEEEE  EEEE    EEEE EEEEE
	EEEEEEEEEEE EEEE   EEEE   EEEEEE  EEEE     EEEEE EEEE
	-->

	<!-- TITLE -->
	<title><?php wp_title( '|' ); ?></title>

	<!-- REMOVE NO-JS -->
	<!--noptimize-->
	<script>document.documentElement.className = document.documentElement.className.replace(/\bno-js\b/, '') + ' js';</script>
	<!--/noptimize-->

	<!-- MOBILE FRIENDLY -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- IE9.js -->
	<!--[if (gte IE 6)&(lte IE 8)]>
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<script src="<?php echo EXTRA_URI; ?>/assets/js/lib/selectivizr-min.js"></script>
	<script src="<?php echo EXTRA_URI; ?>/assets/js/lib/html5shiv.js"></script>
	<![endif]-->

	<!-- WORDPRESS HOOK -->
	<?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>

<?php
include(THEME_PATH . '/assets/img/sprite.svg');
/**********************
 *
 * ShoppingMenu
 *
 *********************/
get_template_part("extra/modules/shopping-menu/front/shopping-menu");
?>

<div id="scrollable-wrapper">
	<div id="scrollable">
	<?php get_template_part("extra/modules/shopping-menu/front/account"); ?>
	<div class="table-wrapper">
		<div id="header-wrapper">
			<header id="header">
				<div class="wrapper">
					<!-- SITE TITLE (LOGO) -->
					<?php if(is_front_page()): ?>   <h1 class="site-title">
					<span class="inner">
						<svg class="icon icon-logo"><use xlink:href="#icon-logo"></use></svg>
						<span class="title"><?php _e("reversible", 'extra'); ?></span>
						<span class="subtitle"><?php _e("éco design", 'extra'); ?></span>
					</span>
					</h1>
					<?php else: ?>
						<h2 class="site-title">
							<a class="inner" href="<?php echo site_url('/'); ?>">
								<svg class="icon icon-logo"><use xlink:href="#icon-logo"></use></svg>
								<span class="title"><?php _e("reversible", 'extra'); ?></span>
								<span class="subtitle"><?php _e("éco design", 'extra'); ?></span>
							</a>
						</h2>
					<?php endif; ?>


					<!-- MAIN NAV -->
					<nav id="main-menu-container" class="menu-container">
						<?php $args = array(
							'theme_location' 	=> 'main',
							'container'			=> null,
							'menu_id'			=> 'main-menu'
						);
						wp_nav_menu($args); ?>
					</nav>
					<?php
					do_action('extra_after_main_menu');
					?>
				</div>
			</header>
			<a id="switch-mobile-menu" class="extra-button-menu" href="#">
				<span class="switch-mobile-menu-inner">
					<span class="icon-wrapper">
						<svg class="icon icon-menu-part part part-1"><use xlink:href="#icon-menu-part"></use></svg>
						<svg class="icon icon-menu-part part part-2"><use xlink:href="#icon-menu-part"></use></svg>
						<svg class="icon icon-menu-part part part-3"><use xlink:href="#icon-menu-part"></use></svg>
					</span>
				</span>
				<span class="text">
					<span class="open"><?php _e("Menu", "extra"); ?></span>
					<span class="close"><?php _e("Fermer", "extra"); ?></span>
				</span>
			</a>
		</div>
		<div id="wrapper">
			<?php get_template_part("extra/modules/flash-messages/front/flash-messages"); ?>