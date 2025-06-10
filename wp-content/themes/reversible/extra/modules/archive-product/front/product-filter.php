<?php

$materials = get_terms('extra_product_template_material');
$material_taxonomy = get_taxonomy('extra_product_template_material');

$types = get_terms('extra_product_template_type');
$type_taxonomy = get_taxonomy('extra_product_template_type');

$collections = get_terms('extra_product_collection');
$collection_taxonomy = get_taxonomy('extra_product_collection');

?>
<div class="filters">
	<?php if (!empty($materials)) : ?>
		<h3 class="filter-title"><?php echo $material_taxonomy->label; ?></h3>
		<ul class="extra-product-filters extra-product-filters-materiel">
			<?php foreach($materials as $term) : ?>
				<li>
					<a href="<?php echo esc_url( get_term_link($term, 'extra_product_material')); ?>"
					   class="extra-product-filter extra-product-filter-materiel extra-filter-materiel-<?php echo $term->slug; ?>"
					   id="<?php echo $term->slug; ?>"
					   data-filter-type="materiel"
						>
						<span class="extra-product-filter-checkbox"></span>
						<?php echo $term->name; ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<?php if (!empty($types)) : ?>
		<h3 class="filter-title"><?php echo $type_taxonomy->label; ?></h3>
		<ul class="extra-product-filters extra-product-filters-type">
			<?php foreach($types as $term) : ?>
				<li>
					<a href="<?php echo esc_url( get_term_link($term, 'extra_product_type')); ?>"
					   class="extra-product-filter extra-product-filter-type extra-filter-type-<?php echo $term->slug; ?>"
					   id="<?php echo $term->slug; ?>"
					   data-filter-type="type"
						>
						<span class="extra-product-filter-checkbox"></span>
						<?php echo $term->name; ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<?php if (!empty($collections)) : ?>
		<h3 class="filter-title"><?php echo $collection_taxonomy->label; ?></h3>
		<ul class="extra-product-filters extra-product-filters-collection">
			<?php foreach($collections as $term) : ?>
				<li>
					<a href="<?php echo esc_url( get_term_link($term, 'extra_product_type')); ?>"
					   class="extra-product-filter extra-product-filter-collection extra-filter-collection-<?php echo $term->slug; ?>"
					   id="<?php echo $term->slug; ?>"
					   data-filter-type="collection"
						>
						<span class="extra-product-filter-checkbox"></span>
						<?php echo $term->name; ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>
