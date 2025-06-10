<?php
/**
 * This file need to be call by a cron.
 * In OVH add cron task like : ./www/wp-content/themes/{your theme name}/cron/auto-update-scripts.php
 */

global $typeKitId;

/**
 * Change this typekit id with yours !
 */
$typeKitId = 'lxo0rnv';

$extraCronFolder = dirname(dirname(dirname(__FILE__))) . '/extra/cron/';

include_once $extraCronFolder . '/auto-update-typekit.php';
include_once $extraCronFolder . '/auto-update-ga.php';