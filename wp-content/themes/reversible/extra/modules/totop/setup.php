<?php

function extra_custom_template_totop ($template_name) {
	return 'extra/totop/front/totop';
}
add_filter('extra-template-totop', 'extra_custom_template_totop', 10, 1);