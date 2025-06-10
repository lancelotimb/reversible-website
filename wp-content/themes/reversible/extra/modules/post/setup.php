<?php
/**********************
 *
 *
 *
 * ASSETS
 *
 *
 *
 *********************/
function extra_post_enqueue_assets() {
	if ( is_home() ) {
		wp_enqueue_style( 'extra-home', THEME_MODULES_URI . '/post/front/css/home.less', array('extra-content', 'extra-layout') );

		wp_enqueue_script('jquery.fracs.js', THEME_URI.'/assets/js/lib/jquery.fracs.js', array('jquery'), false, true);
		wp_enqueue_script('extra-home', THEME_MODULES_URI.'/post/front/js/home.js', array('extra', 'extra-common', 'jquery', 'jquery.fracs.js'), false, true);
	}
	if ( is_singular('post') ) {
		wp_enqueue_style( 'extra-form', THEME_MODULES_URI . '/assets/css/form.less' );
		wp_enqueue_style( 'extra-post', THEME_MODULES_URI . '/post/front/css/single-post.less', array('extra-content', 'extra-layout', 'extra-form') );

		wp_enqueue_script('extra-post', THEME_MODULES_URI.'/post/front/js/single-post.js', array('jquery', 'extra-common'), false, true);
	}
}
add_action( 'wp_enqueue_scripts', 'extra_post_enqueue_assets' );
/**********************
 *
 *
 *
 * CUSTOM COMMENTS
 *
 *
 *
 *********************/
function extra_custom_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
	<?php if($comment->comment_type != "pingback") : ?>
		<div class="gravatar">
			<?php echo get_avatar( $comment->comment_author_email, 85 ); ?>
		</div>
	<?php endif; ?>

	<div class="comment-details">
		<div class="comment-author-details">

			<!-- AUTHOR -->
			<?php if(!empty($comment->comment_author_url)): ?>
				<span class="comment-author"><a href="<?php echo get_comment_author_url(); ?>" target="_blank"><?php echo get_comment_author(); ?></a></span>
			<?php else: ?>
				<span class="comment-author"><?php echo get_comment_author(); ?></span>
			<?php endif; ?>

			<!-- DATE -->
			<span class="comment-date"><?php _e('le', 'extra'); ?> <?php comment_date(); ?></span>

			<!-- HOUR -->
			<span class="comment-hour"><?php comment_date('G\hia'); ?></span>

		</div>

		<!-- CONTENT -->
		<div class="content comment-content">

			<!-- IN VALIDATION -->
			<?php
			if ($comment->comment_approved == '0') {
				echo '<p class="not-approuved">'.__('Your comment is awaiting moderation.').'</p>';
			}
			comment_text(); ?>
		</div>
	</div>
<?php }
/**********************
 *
 *
 *
 * COMMENT FORM FIELDS
 *
 *
 *
 *********************/
function extra_comment_form_default_fields($fields) {
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$commenter = wp_get_current_commenter();
	$fields = array(
		'author' => '<p class=" comment-form-author">' . '<label for="author">' . __( 'Nom / Prénom' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
		            '<input class="comment-form-input" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>',
		'email'  => '<p class="comment-form-email"><label for="email">' . __( 'E-mail', 'extra' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '<small>' . __("Il ne sera pas publié", 'extra') . '</small></label> ' .
		            '<input class="comment-form-input" id="email" name="email" type="email" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>',
		'url'    => '<p class="comment-form-url"><label for="url">' . __( 'Site Internet', 'extra' ) . '</label> ' .
		            '<input class="comment-form-input" id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>',
	);
	return $fields;
}
add_filter( 'comment_form_default_fields', 'extra_comment_form_default_fields' );
/**********************
 *
 *
 *
 * AFTER COMMENT FORM
 *
 *
 *
 *********************/
function extra_comment_form_notes() {
	echo '<p class="comment-notes">'.__('Les champs obligatoires sont indiqués par *', 'extra').'</p>';
}
add_action('comment_form', 'extra_comment_form_notes');


function extra_next_posts_link_attributes ($attr) {
	$attr = 'class="extra-button"';

	return $attr;
}
add_filter('next_posts_link_attributes', 'extra_next_posts_link_attributes');