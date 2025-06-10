<?php
define('EXTRA_OLD_BROWSER_RESTRICTIONS', true);
define('EXTRA_CUSTOM_SHARE_ENABLED', false);

include_once 'extra/setup/setup.php';
include_once 'extra/setup/admin/setup.php';
include_once 'extra-framework/setup/extra.php';


add_filter('less_force_compile', function () {
	return true;
});