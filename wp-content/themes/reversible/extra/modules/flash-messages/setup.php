<?php
/**********************
 *
 *
 * CUSTOM FLASH MESSAGES - BECAUSE WOOCOMMERCE NOTICES ONLY WORK WITH CONNECTED USERS
 *
 *
 *********************/
function extra_flash_message_register_session(){
	if( !session_id() )
		session_start();
}
add_action('init','extra_flash_message_register_session');

function extra_add_flash_message($message_id, $message) {
	if (session_id()) {
		$messages = [];
		if (isset($_SESSION['extra_messages'])) {
			$messages = $_SESSION['extra_messages'];
		}
		$messages[$message_id] = $message;
		$_SESSION['extra_messages'] = $messages;
	}
}
function extra_read_flash_messages() {
	$messages = array();
	if (session_id()) {
		if ($_SESSION && isset($_SESSION['extra_messages'])) {
			$messages = $_SESSION['extra_messages'];
			unset($_SESSION['extra_messages']);
		}
	}

	return $messages;
}


/**********************
 *
 *
 * AJAX GET NOTICES
 *
 *
 *********************/
function extra_get_notices() {
	$response = array();

	ob_start();
	wc_print_notices();
	$response['notices'] = ob_get_clean();
	ob_end_clean();
	$response['messages'] = extra_read_flash_messages();
	wc_clear_notices();

	echo json_encode($response);
	session_write_close();
	die();
}

add_action( 'wp_ajax_nopriv_extra_get_notices', 'extra_get_notices' );
add_action( 'wp_ajax_extra_get_notices', 'extra_get_notices' );



/**********************
 *
 *
 * ENQUEUE ASSETS
 *
 *
 *********************/
function extra_flash_messages_enqueue_assets() {
	wp_enqueue_style('extra-flash-messages', THEME_MODULES_URI.'/flash-messages/front/css/flash-messages.less', array('fancybox'));
	wp_enqueue_script('extra-flash-messages', THEME_MODULES_URI.'/flash-messages/front/js/flash-messages.js', array('extra-common', 'fancybox'), false, true);

	wp_localize_script('extra-flash-messages', 'extraFlashOptions', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' )
	));
}
add_action('wp_enqueue_scripts', 'extra_flash_messages_enqueue_assets');