<?php
// COLLECTION
$extra_footer_terms = get_terms('extra_product_collection', array());
$extra_footer_taxonomy = get_taxonomy('extra_product_collection');
include THEME_MODULES_PATH . '/footer/front/filter.php';

// TYPE
$extra_footer_terms = get_terms('extra_product_template_type', array());
$extra_footer_taxonomy = get_taxonomy('extra_product_template_type');
include THEME_MODULES_PATH . '/footer/front/filter.php';

// MATERIAL
$extra_footer_terms = get_terms('extra_product_template_material', array());
$extra_footer_taxonomy = get_taxonomy('extra_product_template_material');
include THEME_MODULES_PATH . '/footer/front/filter.php';