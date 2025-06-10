<?php

/**********************
 *
 *
 * REGISTER CUSTOM POST TYPE
 *
 *
 *********************/
// Register Custom Post Type
function register_product_template_type() {

	$labels = array(
		'name'                => _x( 'Modèles de produit', 'Post Type General Name', 'extra' ),
		'singular_name'       => _x( 'Modèle de produit', 'Post Type Singular Name', 'extra' ),
		'menu_name'           => __( 'Modèles', 'extra' ),
		'parent_item_colon'   => __( 'Modèle parent', 'extra' ),
		'all_items'           => __( 'Tous les modèles', 'extra' ),
		'view_item'           => __( 'Voir le modèle', 'extra' ),
		'add_new_item'        => __( 'Ajouter un nouveau modèle', 'extra' ),
		'add_new'             => __( 'Nouveau modèle', 'extra' ),
		'edit_item'           => __( 'Modifier le modèle', 'extra' ),
		'update_item'         => __( 'Mettre à jour le modèle', 'extra' ),
		'search_items'        => __( 'Recherche un modèle', 'extra' ),
		'not_found'           => __( 'Aucun modèle trouvé', 'extra' ),
		'not_found_in_trash'  => __( 'Aucun modèle trouvé dans la corbeille', 'extra' ),
	);
	$args = array(
		'label'               => __( 'product_template', 'extra' ),
		'description'         => __( 'Modèle de produit', 'extra' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor'),
		'hierarchical'        => false,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 56,
		'menu_icon'           => 'dashicons-cart',
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	register_post_type( 'product_template', $args );

}

// Hook into the 'init' action
add_action( 'init', 'register_product_template_type', 0 );


/**********************
 *
 *
 * PRODUCT MATERIAL TAXONOMY
 *
 *
 *********************/
// Register Custom Taxonomy
function register_material_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Matériaux', 'Taxonomy General Name', 'extra' ),
		'singular_name'              => _x( 'Matériau', 'Taxonomy Singular Name', 'extra' ),
		'menu_name'                  => __( 'Materiaux', 'extra' ),
		'all_items'                  => __( 'Tous les matériaux', 'extra' ),
		'parent_item'                => __( 'Matériau parent', 'extra' ),
		'parent_item_colon'          => __( 'Matériau parent :', 'extra' ),
		'new_item_name'              => __( 'Nouveau matériau', 'extra' ),
		'add_new_item'               => __( 'Ajouter un matériau', 'extra' ),
		'edit_item'                  => __( 'Modifier le matériau', 'extra' ),
		'update_item'                => __( 'Mettre à jour le matériau', 'extra' ),
		'separate_items_with_commas' => __( 'Noms séparés par des virgules', 'extra' ),
		'search_items'               => __( 'Chercher un matériau', 'extra' ),
		'add_or_remove_items'        => __( 'Ajouter ou retirer un matériau', 'extra' ),
		'choose_from_most_used'      => __( 'Choisir parmi les plus utilisés', 'extra' ),
		'not_found'                  => __( 'Aucun trouvé', 'extra' ),
	);
	$rewrite = array(
		'slug'                       => 'boutique/materiau',
		'with_front'                 => false,
		'hierarchical'               => false,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
	);
	register_taxonomy( 'extra_product_template_material', array( 'product_template' ), $args );

}
// Hook into the 'init' action
add_action( 'init', 'register_material_taxonomy', 0 );


/**********************
 *
 *
 * PRODUCT TYPE TAXONOMY
 *
 *
 *********************/
// Register Custom Taxonomy
function register_product_type_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Type de produits', 'Taxonomy General Name', 'extra' ),
		'singular_name'              => _x( 'Type de produit', 'Taxonomy Singular Name', 'extra' ),
		'menu_name'                  => __( 'Types', 'extra' ),
		'all_items'                  => __( 'Tous les types', 'extra' ),
		'parent_item'                => __( 'Type parent', 'extra' ),
		'parent_item_colon'          => __( 'Type parent :', 'extra' ),
		'new_item_name'              => __( 'Nouveau type', 'extra' ),
		'add_new_item'               => __( 'Ajouter un type', 'extra' ),
		'edit_item'                  => __( 'Modifier le type', 'extra' ),
		'update_item'                => __( 'Mettre à jour le type', 'extra' ),
		'separate_items_with_commas' => __( 'Noms séparés par des virgules', 'extra' ),
		'search_items'               => __( 'Chercher un type', 'extra' ),
		'add_or_remove_items'        => __( 'Ajouter ou retirer un type', 'extra' ),
		'choose_from_most_used'      => __( 'Choisir parmi les plus utilisés', 'extra' ),
		'not_found'                  => __( 'Aucun trouvé', 'extra' ),
	);
	$rewrite = array(
		'slug'                       => 'boutique/type',
		'with_front'                 => false,
		'hierarchical'               => false,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
	);
	register_taxonomy( 'extra_product_template_type', array( 'product_template' ), $args );

}
// Hook into the 'init' action
add_action( 'init', 'register_product_type_taxonomy', 0 );



/**********************
 *
 *
 * ADMIN CSS
 *
 *
 *********************/
function product_template_admin_style() {
	wp_enqueue_style( 'extra-product-template-admin', THEME_MODULES_URI . '/product-template/admin/css/product-template.less' );
}
add_action('admin_print_styles', 'product_template_admin_style');


/**********************
 *
 *
 * METABOX
 *
 *
 *********************/
global $product_template_metabox;
$product_template_metabox = new ExtraMetaBox(array(
	'id' => '_product_template',
	'lock' => WPALCHEMY_LOCK_AFTER_POST_TITLE,
	'title' => __("Images du modèle", "extra"),
	'types' => array('product_template'),
	'hide_editor' => FALSE,
	'hide_ui' => TRUE,
	'fields' => array(
		array(
			'type' => 'bloc',
			'title' => 'Description du modèle',
			'subfields' => array(
				array(
					'type' => 'text',
					'name' => 'detail',
					'label' => __("Détail", 'extra')
				),
				array(
					'type' => 'number',
					'name' => 'width',
					'label' => __("Largeur (en cm)", 'extra')
				),
				array(
					'type' => 'number',
					'name' => 'height',
					'label' => __("Hauteur (en cm)", 'extra')
				),
				array(
					'type' => 'number',
					'name' => 'length',
					'label' => __("Epaisseur (en cm)", 'extra')
				)
			)
		),
		array(
			'type' => 'bloc',
			'title' => 'Images du modèle',
			'subfields' => array(
				array(
					'type' => 'image',
					'name' => 'wearing_image',
					'label' => __("Modèle porté", 'extra')
				),
				array(
					'type' => 'image',
					'name' => 'detail_image_1',
					'label' => __("Detail n°1", 'extra')
				),
				array(
					'type' => 'image',
					'name' => 'detail_image_2',
					'label' => __("Detail n°2", 'extra')
				),
				array(
					'type' => 'image',
					'name' => 'detail_image_3',
					'label' => __("Detail n°3", 'extra')
				)
			)
		),
	),
));



/**********************
 *
 *
 * REDIRECT ARCHIVES URLS
 *
 *
 *********************/
function extra_product_template_redirect () {
	if (is_tax('extra_product_template_type') || is_tax('extra_product_template_material') || is_tax('extra_product_collection')) {
		$shop_url = get_permalink( wc_get_page_id( 'shop' ) );
		$term = get_queried_object();
		if (is_tax('extra_product_template_type')) {
			$shop_url .= '#/type='.$term->slug;
		} else if (is_tax('extra_product_template_material')) {
			$shop_url .= '#/materiel='.$term->slug;
		} else if (is_tax('extra_product_collection')) {
			$shop_url .= '#/collection='.$term->slug;
		}

		wp_redirect($shop_url);
		exit;
	}
}
add_action('template_redirect', 'extra_product_template_redirect');



/**********************
 *
 *
 * REPLACE PRODUCT TITLE WITH PRODUCT TEMPLATE TITLE
 *
 *
 *********************/
global $extra_cached_product_template;
$extra_cached_product_template = array();
function extra_woocommerce_product_title ($title, $product) {
	if ($product && $product->post && !is_admin()) {
		global $extra_cached_product_template;
		$post = $product->post;
		$product_template = null;

		if (array_key_exists($post->ID, $extra_cached_product_template)) {
			$product_template = $extra_cached_product_template[$post->ID];
		} else {
			$product_template = extra_get_product_template($post);
			if ($product_template) {
				$extra_cached_product_template[$post->ID] = $product_template;
			}
		}
		if ($product_template) {
			$title = $product_template->post_title;
		}
	}

	return $title;
}
add_action ('woocommerce_product_title', 'extra_woocommerce_product_title', 10, 2);