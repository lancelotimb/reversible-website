<?php
/*
Template Name: Catalogue
*/
global $catalog_metabox;
the_post();
$catalog_metabox->the_meta();
$catalog = $catalog_metabox->meta['catalog'];
?>
<?php get_template_part("header-main-image"); ?>
<?php get_template_part("header-sub-navigation"); ?>
<!--///////////////////////////////////////////


MAIN CONTENT


///////////////////////////////////////////-->
<div class="content main-content">
	<?php the_content(); ?>
</div>
<!--///////////////////////////////////////////


MATERIAUX


///////////////////////////////////////////-->
<?php if(isset($catalog) && !empty($catalog)): ?>
<div class="catalog-wrapper">
	<ul class="catalog-inner">
		<?php foreach($catalog as $product): ?>
		<li class="catalog-item">
			<?php
			if(isset($product['image']) && !empty($product['image'])) {
				extra_responsive_image( $product['image'], array(
					'desktop' => array( 240, 180 ),
					'tablet'  => array( 240, 180 ),
					'mobile'  => array( 240, 180 )
				), 'catalog-product-image');
			}
			?>
			<div class="catalog-product-content">
				<?php
				/*
				<?php if(isset($product['type']) && !empty($product['type'])): $type = get_term( intval($product['type']), 'extra_product_template_type' ); ?>
					<p class="catalog-product-info catalog-product-type">
					<?php _e("Produit : ", "extra"); ?>
					<a title="<?php printf(__("Voir le type de produit %s", "extra"),  $type->name); ?>" href="<?php echo get_term_link($type); ?>"><?php echo $type->name; ?></a>
				</p><?php endif; ?>

				<?php if(isset($product['type_text']) && !empty($product['type_text'])) : ?>
					<p class="catalog-product-info catalog-product-type">
					<?php _e("Produit : ", "extra"); ?>
					<?php echo $product['type_text']; ?>
					</p>
				<?php endif; ?>
				 */


				/*
				<?php if(isset($product['material']) && !empty($product['material'])): $material = get_term( intval($product['material']), 'extra_product_template_material' ); ?>
					<p class="catalog-product-info catalog-product-material">
					<?php _e("Materiaux : ", "extra"); ?>
					<a title="<?php printf(__("Voir la matière %s", "extra"),  $material->name); ?>" href="<?php echo get_term_link($material); ?>"><?php echo $material->name; ?></a>
				</p><?php endif; ?>

				<?php if(isset($product['material_text']) && !empty($product['material_text'])) : ?>
					<p class="catalog-product-info catalog-product-material">
					<?php _e("Materiaux : ", "extra"); ?>
					<?php echo $product['material_text']; ?>
					</p>
				<?php endif; ?>
 				*/
				?>

				<?php if(isset($product['description']) && !empty($product['description'])) : ?>
					<p class="catalog-product-info catalog-product-type">
						<?php echo nl2br($product['description']); ?>
					</p>
				<?php endif; ?>



				<?php if(
					(isset($product['detail']) && !empty($product['detail'])) ||
					(isset($product['width']) && !empty($product['width'])) ||
					(isset($product['height']) && !empty($product['height'])) ||
					(isset($product['thickness']) && !empty($product['thickness']))): ?>
					<ul class="catalog-product-details">
						<?php if(isset($product['detail']) && !empty($product['detail'])): ?>
							<li><?php _e("Détail : ", "extra"); ?><?php echo $product['detail']; ?></li>
						<?php endif; ?>
						<?php if(isset($product['width']) && !empty($product['width'])): ?>
							<li><?php _e("Largeur : ", "extra"); ?><?php echo $product['width']; ?></li>
						<?php endif; ?>
						<?php if(isset($product['height']) && !empty($product['height'])): ?>
							<li><?php _e("Hauteur : ", "extra"); ?><?php echo $product['height']; ?></li>
						<?php endif; ?>
						<?php if(isset($product['thickness']) && !empty($product['thickness'])): ?>
							<li><?php _e("Épaisseur : ", "extra"); ?><?php echo $product['thickness']; ?></li>
						<?php endif; ?>
					</ul><?php endif; ?>
			</div>
		</li>
		<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>
<!--///////////////////////////////////////////


CONTACT


///////////////////////////////////////////-->
<?php if(isset($catalog_metabox->meta['contact']) && !empty($catalog_metabox->meta['contact'])): ?>
<div class="catalog-contact-wrapper">
	<div class="catalog-contact-inner content">
		<?php echo apply_filters('the_content', $catalog_metabox->meta['contact']); ?>
	</div>
</div>
<?php endif; ?>