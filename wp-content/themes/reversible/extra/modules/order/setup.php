<?php

/**********************
 *
 *
 * TRACKING METABOX
 *
 *
 *********************/
global $order_tracking_metabox;
$order_tracking_metabox = new ExtraMetaBox(array(
	'id' => '_order_tracking',
	'lock' => WPALCHEMY_LOCK_BOTTOM,
	'title' => __("Suivi de l'envoi", "extra"),
	'types' => array('shop_order'),
//	'context' => 'side',
	'fields' => array(
		array(
			'type' => 'tabs',
			'name' => 'numbers',
			'title' => __("Numéros de suivi", 'extra-admin'),
			'add_label' => __("Ajouter un numéro", 'extra-admin'),
			'delete_label' => __("Supprimer", 'extra-admin'),
			'bloc_label' => __("Numéro de colis", 'extra-admin'),
			'subfields' => array(
				array(
					'type' => 'text',
					'name' => 'number',
					'label' => __("Numéro", 'extra-admin')
				)
			)
		),
		array(
			'type' => 'bloc',
			'title' => __("Informations", 'extra-admin'),
			'subfields' => array(
				array(
					'type' => 'select',
					'name' => 'carrier',
					'values' => array(
						'Colissimo' => __('Colissimo', 'extra-admin'),
						'Other' => __('Autre', 'extra-admin')
					),
					'label' => __("Transporteur", 'extra-admin')
				),
				array(
					'type' => 'date',
					'name' => 'date',
					'label' => __("Date d'envoi", 'extra-admin')
				)
			)
		),
	)
));

/**********************
 *
 *
 * TRACKING ADMIN COLUMN
 *
 *
 *********************/
function extra_order_tracking_admin_column_header ($columns) {
	$new_columns = array_slice($columns, 0, 6, true) +
		array( 'extra_order_tracking' => __( "Numéro de suivi", 'extra' ) ) +
		array_slice($columns, 6, count($columns) - 1, true) ;
	return $new_columns;
}
add_filter('manage_edit-shop_order_columns', 'extra_order_tracking_admin_column_header', 1000);

function extra_order_tracking_admin_column_content ($column) {
	global $post;

	if ( $column == 'extra_order_tracking' ) {
		$order_tracking = extra_get_order_tracking($post->ID);

		if (!empty($order_tracking['date']) && !empty($order_tracking['carrier']) && !empty($order_tracking['numbers'])) {
			echo sprintf(__("Envoyé le %s <br>via %s", 'extra'), $order_tracking['date'], $order_tracking['carrier']);
			echo '<ul>';
			foreach ( $order_tracking['numbers'] as $number ) {
				echo '<li>' . $number . '</li>';
			}
			echo '</ul>';
		}
	}
}
add_action('manage_shop_order_posts_custom_column', 'extra_order_tracking_admin_column_content', 2);


/**********************
 *
 *
 * GET TRACKING DATA
 *
 *
 *********************/
function extra_get_order_tracking ($order_id) {
	global $order_tracking_metabox;

	$order_tracking_meta = $order_tracking_metabox->the_meta($order_id);

	$order_tracking = array();

	$order_tracking['date'] = null;
	$order_tracking['carrier'] = null;
	$order_tracking['numbers'] = array();
	if (isset($order_tracking_meta['date'])) {
		$order_tracking['date'] = $order_tracking_meta['date'];
	}
	if (isset($order_tracking_meta['carrier'])) {
		$order_tracking['carrier'] = $order_tracking_meta['carrier'];
	}
	if (isset($order_tracking_meta['numbers'])) {
		$numbers = [];
		foreach ($order_tracking_meta['numbers'] as $number) {
			if (isset($number['number'])) {
				$numbers[] = $number['number'];
			}
		}
		$order_tracking['numbers'] = $numbers;
	}

	return $order_tracking;
}

/**********************
 *
 *
 * PRINT TRACKING DATA
 *
 *
 *********************/
function extra_the_order_tracking ($order_id) {
	$order_tracking = extra_get_order_tracking($order_id);
	?>
<?php if (!empty($order_tracking['numbers']) && !empty($order_tracking['date']) && !empty($order_tracking['carrier'])) : ?>
	<p>
		<?php if ($order_tracking['carrier'] == 'Other') : ?>
			<?php echo sprintf(__("Votre commande a été envoyée le %s. ", 'extra'), $order_tracking['date']); ?><br>
		<?php else : ?>
			<?php echo sprintf(__("Votre commande a été envoyée via %s le %s. ", 'extra'), $order_tracking['carrier'], $order_tracking['date']); ?><br>
		<?php endif; ?>
		<?php if (count($order_tracking['numbers']) == 1): ?>
			<?php
			$numbers = $order_tracking['numbers'];
			echo sprintf(__("Votre numéro de colis est le n°%s ", 'extra'), $numbers[0]);
			?>
			<?php if ($order_tracking['carrier'] == 'Colissimo') : ?>
				: <a href="http://www.colissimo.fr/portail_colissimo/suivre.do?colispart=<?php echo $numbers[0]; ?>" target="_blank"><?php _e("voir le suivi", 'extra'); ?></a>
			<?php endif; ?>
		</p>
		<?php elseif (count($order_tracking['numbers']) > 1) :
			_e("Vos numéros de colis :", 'extra');
			?>
			</p>
		<ul>
			<?php foreach($order_tracking['numbers'] as $number) : ?>
				<li>
					<span><?php echo sprintf(__("n°%s ", 'extra'), $number); ?></span>
					: <a href="http://www.colissimo.fr/portail_colissimo/suivre.do?colispart=<?php echo $number; ?>" target="_blank"><?php _e("voir le suivi", 'extra'); ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>

<?php endif;
}


/**********************
 *
 *
 * OVERRIDE INVOICE DOWNLOAD
 *
 *
 *********************/
add_action('init', function () {
	global $wpo_wcpdf;
	$wpo_wcpdf->writepanels;

	//add_filter( 'woocommerce_my_account_my_orders_actions', array( $this, 'my_account_pdf_link' ), 10, 2 );
	remove_filter( 'woocommerce_my_account_my_orders_actions', array($wpo_wcpdf->writepanels, 'my_account_pdf_link'), 10, 2);
	add_filter( 'extra_invoice_actions', array($wpo_wcpdf->writepanels, 'my_account_pdf_link'), 10, 2);
});

function extra_the_invoice_button ($order) {
	$actions = apply_filters('extra_invoice_actions', array(), $order);
	if (isset($actions['invoice'])) {
		$invoice = $actions['invoice'];
		if (isset($invoice['url'], $invoice['name'])) : ?>
			<div class="invoice-wrapper">
				<a class="button link-button big-shop-button" href="<?php echo $invoice['url']; ?>" target="_blank">
					<span class="inner">
						<?php echo $invoice['name']; ?>
					</span>
				</a>
			</div>
		<?php endif;
	}
}