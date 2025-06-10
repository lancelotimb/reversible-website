<?php
global $extra_options;
for ($i = 1; $i <= 4; $i++) {
	$extra_footer_value = array(
		'text' => $extra_options['value_text_'.$i],
		'icon' => $extra_options['value_icon_'.$i]
	);

	include THEME_MODULES_PATH . '/footer/front/value.php' ;
}