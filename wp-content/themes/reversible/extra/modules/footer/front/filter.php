<div class="column column-six">
	<h4 class="column-title"><?php echo $extra_footer_taxonomy->label; ?></h4>

	<ul>
		<?php foreach($extra_footer_terms as $term) : ?>
			<li>
				<a href="<?php echo esc_url( get_term_link($term, 'extra_product_material')); ?>" class="footer-filter">
					<?php echo $term->name; ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>