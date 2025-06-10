<?php

/************************
 *
 *
 * UTILS
 *
 *
 ***********************/
function extra_validation_get_required ($args) {
	if ( $args['required'] ) {
		$args['class'][] = 'validate-required';
		$required = ' <abbr class="required" title="' . esc_attr__( 'required', 'woocommerce'  ) . '">*</abbr>';
	} else {
		$required = '';
	}

	return $required;
}
function extra_validation_get_clear ($args) {
	if ( ( ! empty( $args['clear'] ) ) ) {
		$after = '<div class="clear"></div>';
	} else {
		$after = '';
	}

	return $after;
}
function extra_validation_get_custom_attribures ($args) {
	// Custom attribute handling
	$custom_attributes = array();

	if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
		foreach ( $args['custom_attributes'] as $attribute => $attribute_value ) {
			$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
		}
	}

	return $custom_attributes;
}

/************************
 *
 *
 * REQUIRED
 *
 *
 ***********************/
function extra_validation_get_required_message ($args) {
	$message = '';
	if ($args['required']) {
		$message = '<span class="extra-invalid-required">'.sprintf(__("Ce champ est requis.", 'extra'), $args['label']).'</span>';
	}
	return $message;
}


function extra_validation_get_postcode_message($args) {
	$message = '';
	if (in_array('postcode', $args['validate'])) {
		$message = '<span class="extra-invalid-postcode">'.sprintf(__("Le code postal est invalide.", 'extra'), $args['label']).'</span>';
	}
	return $message;
}
function extra_validation_get_email_message($args) {
	$message = '';
	if (in_array('email', $args['validate'])) {
		$message = '<span class="extra-invalid-email">'.sprintf(__("L'email est invalide", 'extra'), $args['label']).'</span>';
	}
	return $message;

}
function extra_validation_get_phone_message($args) {
	$message = '';
	if (in_array('phone', $args['validate'])) {
		$message = '<span class="extra-invalid-phone">'.sprintf(__("Le numéro de téléphone est invalide", 'extra'), $args['label']).'</span>';
	}
	return $message;
}



/************************
 *
 *
 * COUNTRY
 *
 *
 ***********************/
function extra_woocommerce_form_field_country ($field, $key, $args, $value) {
	$after = extra_validation_get_clear($args);
	$required = extra_validation_get_required($args);
	$custom_attributes = extra_validation_get_custom_attribures($args);

	$countries = $key == 'shipping_country' ? WC()->countries->get_shipping_countries() : WC()->countries->get_allowed_countries();

	if ( sizeof( $countries ) == 1 ) {

		$field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $args['id'] ) . '_field">';

		if ( $args['label'] ) {
			$field .= '<label class="' . esc_attr( implode( ' ', $args['label_class'] ) ) .'">' . $args['label']  . '</label>';
		}

		$field .= '<strong>' . current( array_values( $countries ) ) . '</strong>';

		$field .= '<input type="hidden" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="' . current( array_keys($countries ) ) . '" ' . implode( ' ', $custom_attributes ) . ' class="country_to_state" />';

		if ( $args['description'] ) {
			$field .= '<span class="description">' . esc_attr( $args['description'] ) . '</span>';
		}

		$field .= extra_validation_get_required_message($args);

		$field .= '</p>' . $after;

	} else {

		$field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $args['id'] ) . '_field">'
			. '<label for="' . esc_attr( $args['id'] ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) .'">' . $args['label'] . $required  . '</label>'
			. '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="country_to_state country_select ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'" ' . implode( ' ', $custom_attributes ) . '>'
			. '<option value="">'.__( 'Select a country&hellip;', 'woocommerce' ) .'</option>';

		foreach ( $countries as $ckey => $cvalue ) {
			$field .= '<option value="' . esc_attr( $ckey ) . '" '.selected( $value, $ckey, false ) .'>'.__( $cvalue, 'woocommerce' ) .'</option>';
		}

		$field .= '</select>';

		$field .= '<noscript><input type="submit" name="woocommerce_checkout_update_totals" value="' . __( 'Update country', 'woocommerce' ) . '" /></noscript>';

		if ( $args['description'] ) {
			$field .= '<span class="description">' . esc_attr( $args['description'] ) . '</span>';
		}

		$field .= extra_validation_get_required_message($args);

		$field .= '</p>' . $after;

	}

	return $field;
}
add_filter('woocommerce_form_field_country', 'extra_woocommerce_form_field_country', 10, 4);


/************************
 *
 *
 * STATE
 *
 *
 ***********************/
function extra_woocommerce_form_field_state ($field, $key, $args, $value) {
	$after = extra_validation_get_clear($args);
	$required = extra_validation_get_required($args);
	$custom_attributes = extra_validation_get_custom_attribures($args);

	/* Get Country */
	$country_key = $key == 'billing_state'? 'billing_country' : 'shipping_country';
	$current_cc  = WC()->checkout->get_value( $country_key );
	$states      = WC()->countries->get_states( $current_cc );

	if ( is_array( $states ) && empty( $states ) ) {

		$field  = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $args['id'] ) . '_field" style="display: none">';

		if ( $args['label'] ) {
			$field .= '<label for="' . esc_attr( $args['id'] ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) .'">' . $args['label'] . $required . '</label>';
		}
		$field .= '<input type="hidden" class="hidden" name="' . esc_attr( $key )  . '" id="' . esc_attr( $args['id'] ) . '" value="" ' . implode( ' ', $custom_attributes ) . ' placeholder="' . esc_attr( $args['placeholder'] ) . '" />';

		if ( $args['description'] ) {
			$field .= '<span class="description">' . esc_attr( $args['description'] ) . '</span>';
		}

		$field .= extra_validation_get_required_message($args);

		$field .= '</p>' . $after;

	} elseif ( is_array( $states ) ) {

		$field  = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $args['id'] ) . '_field">';

		if ( $args['label'] )
			$field .= '<label for="' . esc_attr( $args['id'] ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) .'">' . $args['label']. $required . '</label>';
		$field .= '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="state_select ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'" ' . implode( ' ', $custom_attributes ) . ' placeholder="' . esc_attr( $args['placeholder'] ) . '">
						<option value="">'.__( 'Select a state&hellip;', 'woocommerce' ) .'</option>';

		foreach ( $states as $ckey => $cvalue ) {
			$field .= '<option value="' . esc_attr( $ckey ) . '" '.selected( $value, $ckey, false ) .'>'.__( $cvalue, 'woocommerce' ) .'</option>';
		}

		$field .= '</select>';

		if ( $args['description'] ) {
			$field .= '<span class="description">' . esc_attr( $args['description'] ) . '</span>';
		}

		$field .= extra_validation_get_required_message($args);

		$field .= '</p>' . $after;

	} else {

		$field  = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $args['id'] ) . '_field">';

		if ( $args['label'] ) {
			$field .= '<label for="' . esc_attr( $args['id'] ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) .'">' . $args['label']. $required . '</label>';
		}
		$field .= '<input type="text" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'" value="' . esc_attr( $value ) . '"  placeholder="' . esc_attr( $args['placeholder'] ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" ' . implode( ' ', $custom_attributes ) . ' />';

		if ( $args['description'] ) {
			$field .= '<span class="description">' . esc_attr( $args['description'] ) . '</span>';
		}

		$field .= extra_validation_get_required_message($args);

		$field .= '</p>' . $after;

	}
	return $field;
}
add_filter('woocommerce_form_field_state', 'extra_woocommerce_form_field_state', 10, 4);


/************************
 *
 *
 * TEXT
 *
 *
 ***********************/
function extra_woocommerce_form_field_textarea ($field, $key, $args, $value) {
	$after = extra_validation_get_clear($args);
	$required = extra_validation_get_required($args);
	$custom_attributes = extra_validation_get_custom_attribures($args);

	$field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $args['id'] ) . '_field">';

	if ( $args['label'] ) {
		$field .= '<label for="' . esc_attr( $args['id'] ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) .'">' . $args['label']. $required  . '</label>';
	}

	$field .= '<textarea name="' . esc_attr( $key ) . '" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'" id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '" ' . $args['maxlength'] . ' ' . ( empty( $args['custom_attributes']['rows'] ) ? ' rows="2"' : '' ) . ( empty( $args['custom_attributes']['cols'] ) ? ' cols="5"' : '' ) . implode( ' ', $custom_attributes ) . '>'. esc_textarea( $value  ) .'</textarea>';

	if ( $args['description'] ) {
		$field .= '<span class="description">' . esc_attr( $args['description'] ) . '</span>';
	}

	$field .= extra_validation_get_required_message($args);

	$field .= '</p>' . $after;

	return $field;
}
add_filter('woocommerce_form_field_textarea', 'extra_woocommerce_form_field_textarea', 10, 4);

/************************
 *
 *
 * CHECKBOX
 *
 *
 ***********************/
function extra_woocommerce_form_field_checkbox($field, $key, $args, $value) {
	$after = extra_validation_get_clear($args);
	$required = extra_validation_get_required($args);
	$custom_attributes = extra_validation_get_custom_attribures($args);

	$field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $args['id'] ) . '_field">
						<label class="checkbox ' . implode( ' ', $args['label_class'] ) .'" ' . implode( ' ', $custom_attributes ) . '>
						<input type="' . esc_attr( $args['type'] ) . '" class="input-checkbox ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="1" '.checked( $value, 1, false ) .' /> '
		. $args['label'] . $required . '</label>';

	if ( $args['description'] ) {
		$field .= '<span class="description">' . esc_attr( $args['description'] ) . '</span>';
	}

	$field .= extra_validation_get_required_message($args);

	$field .= '</p>' . $after;

	return $field;
}
add_filter('woocommerce_form_field_checkbox', 'extra_woocommerce_form_field_checkbox', 10, 4);

/************************
 *
 *
 * PASSWORD
 *
 *
 ***********************/
function extra_woocommerce_form_field_password($field, $key, $args, $value) {
	$after = extra_validation_get_clear($args);
	$required = extra_validation_get_required($args);
	$custom_attributes = extra_validation_get_custom_attribures($args);

	$field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $args['id'] ) . '_field">';

	if ( $args['label'] ) {
		$field .= '<label for="' . esc_attr( $args['id'] ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) .'">' . $args['label']. $required . '</label>';
	}

	$field .= '<input type="password" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '" value="' . esc_attr( $value ) . '" ' . implode( ' ', $custom_attributes ) . ' />';

	if ( $args['description'] ) {
		$field .= '<span class="description">' . esc_attr( $args['description'] ) . '</span>';
	}

	$field .= extra_validation_get_required_message($args);

	$field .= '</p>' . $after;

	return $field;
}
add_filter('woocommerce_form_field_password', 'extra_woocommerce_form_field_password', 10, 4);

/************************
 *
 *
 * TEXT
 *
 *
 ***********************/
function extra_woocommerce_form_field_text ($field, $key, $args, $value) {
	$after = extra_validation_get_clear($args);
	$required = extra_validation_get_required($args);
	$custom_attributes = extra_validation_get_custom_attribures($args);

	$field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $args['id'] ) . '_field">';

	if ( $args['label'] ) {
		$field .= '<label for="' . esc_attr( $args['id'] ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) .'">' . $args['label'] . $required . '</label>';
	}

	$field .= '<input type="text" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '" '.$args['maxlength'].' value="' . esc_attr( $value ) . '" ' . implode( ' ', $custom_attributes ) . ' />';

	if ( $args['description'] ) {
		$field .= '<span class="description">' . esc_attr( $args['description'] ) . '</span>';
	}

	$field .= extra_validation_get_required_message($args);
	$field .= extra_validation_get_postcode_message($args);
	$field .= extra_validation_get_email_message($args);
	$field .= extra_validation_get_phone_message($args);

	$field .= '</p>' . $after;
	return $field;
}
add_filter('woocommerce_form_field_text', 'extra_woocommerce_form_field_text', 10, 4);

/************************
 *
 *
 * SELECT
 *
 *
 ***********************/
function extra_woocommerce_form_field_select ($field, $key, $args, $value) {
	$after = extra_validation_get_clear($args);
	$required = extra_validation_get_required($args);
	$custom_attributes = extra_validation_get_custom_attribures($args);

	$options = $field = '';

	if ( ! empty( $args['options'] ) ) {
		foreach ( $args['options'] as $option_key => $option_text ) {
			if ( "" === $option_key ) {
				// If we have a blank option, select2 needs a placeholder
				if ( empty( $args['placeholder'] ) ) {
					$args['placeholder'] = $option_text ? $option_text : __( 'Choose an option', 'woocommerce' );
				}
				$custom_attributes[] = 'data-allow_clear="true"';
			}
			$options .= '<option value="' . esc_attr( $option_key ) . '" '. selected( $value, $option_key, false ) . '>' . esc_attr( $option_text ) .'</option>';
		}

		$field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $args['id'] ) . '_field">';

		if ( $args['label'] ) {
			$field .= '<label for="' . esc_attr( $args['id'] ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) .'">' . $args['label']. $required . '</label>';
		}

		$field .= '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="select '.esc_attr( implode( ' ', $args['input_class'] ) ) .'" ' . implode( ' ', $custom_attributes ) . ' placeholder="' . esc_attr( $args['placeholder'] ) . '">
							' . $options . '
						</select>';

		if ( $args['description'] ) {
			$field .= '<span class="description">' . esc_attr( $args['description'] ) . '</span>';
		}

		$field .= extra_validation_get_required_message($args);

		$field .= '</p>' . $after;
	}

	return $field;
}
add_filter('woocommerce_form_field_select', 'extra_woocommerce_form_field_select', 10, 4);


/************************
 *
 *
 * RADIO
 *
 *
 ***********************/
function extra_woocommerce_form_field_radio ($field, $key, $args, $value) {
	$after = extra_validation_get_clear($args);
	$required = extra_validation_get_required($args);
	$custom_attributes = extra_validation_get_custom_attribures($args);

	$field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $args['id'] ) . '_field">';

	if ( $args['label'] ) {
		$field .= '<label for="' . esc_attr( current( array_keys( $args['options'] ) ) ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) .'">' . $args['label']. $required  . '</label>';
	}

	if ( ! empty( $args['options'] ) ) {
		foreach ( $args['options'] as $option_key => $option_text ) {
			$field .= '<input type="radio" class="input-radio ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'" value="' . esc_attr( $option_key ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '"' . checked( $value, $option_key, false ) . ' />';
			$field .= '<label for="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '" class="radio ' . implode( ' ', $args['label_class'] ) .'">' . $option_text . '</label>';
		}
	}

	$field .= extra_validation_get_required_message($args);

	$field .= '</p>' . $after;

	return $field;
}
add_filter('woocommerce_form_field_radio', 'extra_woocommerce_form_field_radio', 10, 4);