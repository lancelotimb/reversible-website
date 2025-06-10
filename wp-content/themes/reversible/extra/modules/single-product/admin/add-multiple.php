<?php

global $extra_module_uri, $extra_post_type, $extra_post_status, $extra_taxonomy_type, $extra_multiple_suffix, $extra_post_meta_prefix, $extra_meta_id_prefix;

$extra_module_uri = THEME_MODULES_URI.'/single-product';

$extra_post_type = 'product';
$extra_post_status = 'pending';
//$extra_post_status = 'publish';
$extra_taxonomy_type = null;

$extra_multiple_suffix = 'product';
$extra_post_meta_prefix = '';
$extra_meta_id_prefix = '';

function extra_extract_property_from_multiple_add_query ($query_var) {
	$objects = array();

	foreach($query_var as $property_name => $properties) {
		for ($i = 0; $i < count($properties); $i++) {
			$object = null;
			if (isset($objects[$i])) {
				$object = $objects[$i];
			} else {
				$object = array();
			}

			$object[$property_name] = $properties[$i];
			$objects[$i] = $object;
		}
	}

	return $objects;
}

/**
 * Insert products from multiple add product form
 *
 * @return array potentials errors / validation / message
 */
function extra_insert_multiple_add() {
	global $extra_post_type, $extra_post_meta_prefix, $extra_post_status, $extra_taxonomy_type;

	$post_type = ($extra_post_type != null) ? $extra_post_type : 'post';
	$post_status = ($extra_post_status != null) ? $extra_post_status : 'publish';

	if (!(array_key_exists("_wpnonce", $_POST) && wp_verify_nonce($_POST['_wpnonce'], 'extra-multiple-add-nonce'))) {

		return null;
	}

	$error_message = null;
	$success_message = null;
	$empty_message = null;

	if (isset($_POST['add_multiple_meta'])) {
		$objects = extra_extract_property_from_multiple_add_query($_POST['add_multiple_meta']);

		$nb_success = 0;
		$nb_errors = 0;
		$nb_empty = 0;
		foreach ($objects as $object) {
			$post = array(
				'post_type' => $post_type,
				'post_status' => $post_status
			);
			$thumbnail_id = null;

			$meta_fields = array();
			foreach ($object as $property_name => $property_value) {
				if ($property_name == 'title') {
					$post['post_title'] = $property_value;
				} elseif ($property_name == 'image') {
					$thumbnail_id = intval($property_value);
				} elseif ($property_name == '_price') {
					$property_value = str_replace(',', '.', $property_value);
					$meta_fields[$extra_post_meta_prefix.$property_name] = $property_value;
					$meta_fields[$extra_post_meta_prefix.'_regular_price'] = $property_value;
				} elseif ($property_name == 'content') {
					$post['post_content'] = $property_value;
				} elseif ($property_name == 'category') {
					if ($property_value != 0) {
						$post['custom_taxonomy'] = array(intval($property_value));
					}
				} else {
					$meta_fields[$extra_post_meta_prefix.$property_name] = $property_value;
				}
			}

			// APPLY A FILTER BEFORE INSERTION
			$post_and_metas = array(
				'post' => $post,
				'meta_fields' => $meta_fields
			);

			$filtered = apply_filters('extra_before_add_multiple_insertion', $post_and_metas);
			if ($filtered !== null and !empty($filtered)) {
				$post_and_metas = $filtered;
				$post = $post_and_metas['post'];
				$meta_fields = $post_and_metas['meta_fields'];
			}

			$meta_field_keys = array();
			foreach($meta_fields as $key => $value) {
				$meta_field_keys[] = $key;
			}

			if (empty($post['post_title'])) {
				$nb_empty++;
			} else {
				$post_id = wp_insert_post($post, true);
				if($post_id) {
					// IMAGE THUMBNAIL
					if (!empty($thumbnail_id)) {
						set_post_thumbnail( $post_id, $thumbnail_id );
					}

					// CUSTOM TAXONOMY
					wp_set_post_terms($post_id, $post['custom_taxonomy'], $extra_taxonomy_type, true );

					// POST METAS
					if (!empty($extra_post_meta_prefix)) {
						add_post_meta($post_id, $extra_post_meta_prefix.'fields', $meta_field_keys, true);
					}

					foreach ($meta_fields as $meta_key => $meta_value) {
						add_post_meta($post_id, $meta_key, $meta_value, true);
					}
					$nb_success++;
				} else {
					$nb_errors++;
				}
			}

		}

		if ($nb_success == 1) {
			$success_message = __("Merci ! Votre élément a été inséré avec succès", "extra-admin");
		} elseif ($nb_success > 1) {
			$success_message = sprintf(__("Merci ! %d éléments insérés avec succès", "extra-admin"), $nb_success);
		}
		if ($nb_errors == 1) {
			$error_message = __("Oh non ! 1 élément n'a pas été créé...", "extra-admin");
		} elseif ($nb_errors > 1) {
			$error_message = sprintf(__("Oh non ! %d éléments n'ont pas été créés...", "extra-admin"), $nb_success);
		}
		if ($nb_empty == 1) {
			$empty_message = __("1 élément vide a été ignoré.", "extra-admin");
		} elseif ($nb_empty > 1) {
			$empty_message = sprintf(__("%d éléments vide ont été ignoré.", "extra-admin"), $nb_success);
		}

	} else {
		$error_message = __('Impossible de récupérer les informations du formulaire', 'extra-admin');
	}


	return array(
		array(
			'type' => 'error',
			'message' => $error_message,
		),
		array(
			'type' => 'updated',
			'message' => $success_message,
		),
		array(
			'type' => 'error',
			'message' => $empty_message,
		),
	);
}


/**
 * Echo form for multiple add product
 */
function extra_show_multiple_add_product() {
	global $extra_taxonomy_type, $extra_meta_id_prefix;

	$extra_messages = extra_insert_multiple_add();
	$categories = null;
	if ($extra_taxonomy_type != null) {
		$categories = get_categories(array(
			'taxonomy' => $extra_taxonomy_type
		));
	}
	?>
	<div class="wrap">
		<h2><?php _e('Ajout Multiple', 'extra-admin'); ?></h2>

		<?php if ($extra_messages != null) : ?>
			<?php foreach ($extra_messages as $extra_message) : ?>
				<?php if ($extra_message['message'] != null) : ?>
					<div class="<?php echo $extra_message['type']; ?>">
						<p><?php echo $extra_message['message']; ?></p>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>

		<div id="multiple-wrapper">
			<div class="new-post" id="reference">
				<h3><?php _e('Nouveau produit', 'extra-admin'); ?></h3>
				<div class="fields wpa_group">
					<!-- NAME -->
					<p>
						<label for="add_multiple_meta[title][multiple_ref]">Nom</label><br />
						<input class="title" name="add_multiple_meta[title][multiple_ref]" id="add_multiple_meta[title][multiple_ref]" type="text" value="" placeholder="Nom" />
					</p>
					<!-- PRICE -->
					<p>
						<label for="add_multiple_meta[_price][multiple_ref]"><?php _e("Prix", 'extra-admin'); ?></label><br />
						<input class="price" name="add_multiple_meta[_price][multiple_ref]" id="add_multiple_meta[_price][multiple_ref]" type="text" value="" placeholder="<?php _e("Exemple : 39.95", 'extra-admin'); ?>"/>

					</p>

					<!-- DEFAULT VALUE FOR WOOCOMMERCE PROPERTIES -->
					<input name="add_multiple_meta[_visibility][multiple_ref]" type="hidden" value="visible"/>
					<input name="add_multiple_meta[total_sales][multiple_ref]" type="hidden" value="0"/>

					<input name="add_multiple_meta[_downloadable][multiple_ref]" type="hidden" value="no"/>
					<input name="add_multiple_meta[_virtual][multiple_ref]" type="hidden" value="no"/>
					<input name="add_multiple_meta[_featured][multiple_ref]" type="hidden" value="no"/>
					<input name="add_multiple_meta[_manage_stock][multiple_ref]" type="hidden" value="no"/>
					<input name="add_multiple_meta[_backorders][multiple_ref]" type="hidden" value="no"/>

					<?php /*
					<input name="add_multiple_meta[_sale_price][multiple_ref]" type="hidden" value=""/>
					<input name="add_multiple_meta[_purchase_note][multiple_ref]" type="hidden" value=""/>

					<input name="add_multiple_meta[_weight][multiple_ref]" type="hidden" value=""/>
					<input name="add_multiple_meta[_length][multiple_ref]" type="hidden" value=""/>
					<input name="add_multiple_meta[_width][multiple_ref]" type="hidden" value=""/>
					<input name="add_multiple_meta[_height][multiple_ref]" type="hidden" value=""/>
					<input name="add_multiple_meta[_sku][multiple_ref]" type="hidden" value=""/>

					<input name="add_multiple_meta[_tax_status][multiple_ref]" type="hidden" value="taxable"/>

 					*/?>
				</div>

				<div class="fields wpa_group">
					<!-- IMAGE -->
					<p>
						<div class="extra-custom-image">
							<div class="floater">
								<label for="add_multiple_meta[image][multiple_ref]"><?php _e("Sélectionner une image", "extra-admin"); ?></label>
								<input class="image-input" name="add_multiple_meta[image][multiple_ref]" type="hidden" />
								<input class="choose-button button" type="button" value="<?php _e("Ouvrir le gestionnaire d'images", "extra-admin"); ?>" />
							</div>
							<div class="image empty"><img src="" /></div>
						</div>
					</p>
					<?php ?>
				</div>

			</div>
		</div>

		<form id="extra_multiple_form" name="extra_multiple_form" method="post" enctype="multipart/form-data" action="">
			<div class="extra-tool-box">
				<?php wp_nonce_field( 'extra-multiple-add-nonce' ); ?>
				<a href="#add" class="button button-large" id="add-content">Nouveau contenu</a>
				<button class="button button-primary button-large" type="submit">Valider les contenus</button>
			</div>

		</form>
	</div>
<?php
}

function extra_add_multiple_styles() {
	global $extra_multiple_suffix, $extra_module_uri;
	// COMMON
	wp_enqueue_style('extra-multiple-' . $extra_multiple_suffix, $extra_module_uri . '/admin/css/multiple.less', array(), false, 'all');
}

function extra_add_multiple_scripts() {
	global $extra_multiple_suffix, $extra_module_uri;
	// COMMON
	wp_enqueue_media();

	wp_enqueue_script('extra-multiple-'.$extra_multiple_suffix, $extra_module_uri . '/admin/js/multiple.js', array('jquery'), false, true);
	wp_enqueue_script('extra-image', get_template_directory_uri() . '/includes/extra-metabox/js/extra-image.js', array('jquery'), null, true);

	$medias = array();
	$all_media = get_posts(array(
		'post_type' => 'attachment',
		'posts_per_page' => -1
	));
	foreach ($all_media as $media) {
		if ($media->post_content == 'generator') {
			$medias[] = array(
				'id' => $media->ID,
				'src' => wp_get_attachment_image_src($media->ID)
			);
		}
	}
	wp_localize_script('extra-multiple-'.$extra_multiple_suffix, 'medias', $medias);
}

function extra_add_multiple_submenu_page() {
	global $extra_multiple_suffix;

	$menu = add_submenu_page('edit.php?post_type=' . $extra_multiple_suffix, 'Ajout multiple', 'Ajout multiple', 'manage_options', 'multiple', 'extra_show_multiple_add_product');
	add_action('admin_print_styles-' . $menu, 'extra_add_multiple_styles');
	add_action('admin_print_scripts-' . $menu, 'extra_add_multiple_scripts');
}
add_action('admin_menu', 'extra_add_multiple_submenu_page');

function extra_validate_title($post_and_metas) {
	global $extra_meta_id_prefix;
	$post = $post_and_metas['post'];
	$meta_fields = $post_and_metas['meta_fields'];
	if (empty($post['post_title'])) {
		$post['post_title'] = $meta_fields['_location_meta_'.$extra_meta_id_prefix.'address'];
	}

	$post_and_metas = array(
		'post' => $post,
		'meta_fields' => $meta_fields
	);

	return $post_and_metas;
}
add_action('extra_before_add_multiple_insertion', 'extra_validate_title', 10, 3);