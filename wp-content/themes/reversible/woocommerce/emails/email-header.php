<?php
/**
 * Email Header
 *
 * @author 	WooThemes
 * @package WooCommerce/Templates/Emails
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php echo get_bloginfo( 'name', 'display' ); ?></title>
</head>
<body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
<div id="wrapper">
	<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
		<tr>
			<td align="center" valign="top">
				<div id="template_header_image">
					<?php
					if ( $img = get_option( 'woocommerce_email_header_image' ) ) {
						echo '<p style="margin-top:0;"><img src="' . esc_url( $img ) . '" alt="' . get_bloginfo( 'name', 'display' ) . '" /></p>';
					}
					?>
				</div>
				<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container">
					<tr>
						<td align="center" valign="top">
							<!-- Header -->
							<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_header">
								<tr>
									<td>
										<a href="<?php echo home_url('/'); ?>" title="Reversible">
											<img src="<?php echo THEME_URI . '/assets/img/email/header.png'; ?>" width="600" height="210" alt="Reversible" />
										</a>
										<hr id="template_header_separator" />
										<h1><?php echo $email_heading; ?></h1>
									</td>
								</tr>
							</table>
							<!-- End Header -->
						</td>
					</tr>
					<tr>
						<td align="center" valign="top">
							<!-- Body -->
							<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body">
								<tr>
									<td valign="top" id="body_content">
										<!-- Content -->
										<table border="0" cellpadding="20" cellspacing="0" width="100%">
											<tr>
												<td valign="top">
													<div id="body_content_inner">
