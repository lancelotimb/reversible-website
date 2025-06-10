jQuery(function ($) {
	var $form = $('.account-form'),
		$submit = $('.account-form-submit');
	$form.on( 'blur input change', '.input-text, select', validate_field );
	$('body').on('field_validated', function () {
		if (is_form_validate ()) {
			$submit.removeClass('disabled');
		} else {
			$submit.addClass('disabled');
		}
	});


	function validate_fields() {
		$form.find('.input-text, select').each(function () {
			validate_field();
		});
	}

	function validate_field () {
		var $this     = $(this),
			$parent   = $this.closest( '.form-row' ),
			validated = true;

		if ( $parent.is( '.validate-required' ) ) {
			if ( $this.val() === '' ) {
				$parent.removeClass( 'woocommerce-validated' ).addClass( 'woocommerce-invalid woocommerce-invalid-required-field' );
				validated = false;
			}
		}

		if ( $parent.is( '.validate-email' ) ) {
			if ( $this.val() && validated ) {

				/* http://stackoverflow.com/questions/2855865/jquery-validate-e-mail-address-regex */
				var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);

				if ( ! pattern.test( $this.val()  ) ) {
					$parent.removeClass( 'woocommerce-validated' ).addClass( 'woocommerce-invalid woocommerce-invalid-email' );
					validated = false;
				}
			}
		}

		if ( $parent.is( '.validate-phone' ) ) {
			if ( $this.val() && validated ) {
				var phone = $this.val().trim();
				phone = phone.replace(/\./g, '-');
				phone = phone.replace(/[\s\#0-9_\-\+\(\)]/g, '');
				if (phone.length > 0) {
					$parent.removeClass( 'woocommerce-validated' ).addClass( 'woocommerce-invalid woocommerce-invalid-phone' );
					validated = false;
				}
			}
		}

		if ( $parent.is( '.validate-postcode' ) ) {
			if ( $this.val() && validated ) {
				var postcode = $this.val().trim();

				// Remove whitespace
				postcode = postcode.replace(/\s/g, '');

				if (postcode.replace(/[\s\-A-Za-z0-9]/g, '').length > 0) {
					$parent.removeClass( 'woocommerce-validated' ).addClass( 'woocommerce-invalid woocommerce-invalid-postcode' );
					validated = false;
				}
				//else {
				//	var $countrySelect =  $parent.closest('.woocommerce-billing-fields, .woocommerce-shipping-fields').find('#billing_country, #shipping_country'),
				//		country = $countrySelect.find('option:selected').val();
				//
				//	console.log(country);
				//	switch (country) {
				//		case "GB" :
				//			return self::is_GB_postcode( $postcode );
				//		case "US" :
				//			if ( preg_match( "/^([0-9]{5})(-[0-9]{4})?$/i", $postcode ) )
				//				return true;
				//			else
				//				return false;
				//		case "CH" :
				//			if ( preg_match( "/^([0-9]{4})$/i", $postcode ) )
				//				return true;
				//			else
				//				return false;
				//		case "BR" :
				//			if ( preg_match( "/^([0-9]{5,5})([-])?([0-9]{3,3})$/", $postcode ) )
				//				return true;
				//			else
				//				return false;
				//		default :
				//			return true;
				//	}
				//}
			}
		}

		if ( $parent.is( '.validate-password' ) ) {
			if ( $this.val() && validated ) {
				var $inputs = $form.find('.validate-password .input-password');
				if ($inputs.size() > 0) {
					var newPassword = $inputs.val();
					$inputs.each(function () {
						if (newPassword != $(this).val()) {
							validated = false;
						}
					});
					if (!validated) {
						$inputs.each(function () {
							$(this).closest( '.form-row' ).removeClass( 'woocommerce-validated' ).addClass( 'woocommerce-invalid woocommerce-invalid-password' );
						});
					} else {
						$inputs.each(function () {
							$(this).closest( '.form-row' ).removeClass( 'woocommerce-invalid woocommerce-invalid-password' );
						});
					}
				}
			}
		}

		if ( validated ) {
			$parent.removeClass( 'woocommerce-invalid woocommerce-invalid-required-field woocommerce-invalid-password woocommerce-invalid-phone woocommerce-invalid-postcode woocommerce-invalid-email' ).addClass( 'woocommerce-validated' );
		}
		$('body').trigger('field_validated');
	}

	$form.on('submit', function (event) {
		if (!is_form_validate ()) {
			event.preventDefault();
		}
	});


	function is_form_validate() {
		return $form.find('.woocommerce-invalid-required-field, .woocommerce-invalid-email, .woocommerce-invalid-postcode, .woocommerce-invalid-phone').size() == 0;
	}

	//// CLEAR PASSWORDS
	//$form.find('.input-password').each( function () {
	//	$(this).val('');
	//});

	console.log($('input[type="checkbox"], input[type="radio"]'));
	$('input[type="checkbox"], input[type="radio"]').extraCheckbox();
});