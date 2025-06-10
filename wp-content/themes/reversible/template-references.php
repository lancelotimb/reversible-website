<?php
/*
Template Name: Références
*/
global $references_metabox;
the_post();
$references_metabox->the_meta();
$references = $references_metabox->meta['references'];
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
<?php if(isset($references) && !empty($references)): ?>
<div class="references-wrapper">
	<ul class="references-inner">
		<?php foreach($references as $reference): ?>
			<li class="references-item">
				<div class="references-item-inner">
				<?php
				if(isset($reference['image']) && !empty($reference['image'])) {
					extra_responsive_image( $reference['image'], array(
						'desktop' => array( 420, 420 ),
						'tablet'  => array( 420, 420 ),
						'mobile'  => array( 420, 420 )
					), 'reference-image');
				}
				if(isset($reference['logo']) && !empty($reference['logo'])) {
					extra_responsive_image( $reference['logo'], array(
						'desktop' => array( 180, 180 ),
						'tablet'  => array( 180, 180 ),
						'mobile'  => array( 180, 180 )
					), 'reference-logo');
				}
				?>
				<?php if(isset($reference['description']) && !empty($reference['description'])): ?>
				<div class="reference-content-wrapper">
					<div class="reference-content">
						<svg class="icon icon-close"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-close"></use></svg>
						<div class="reference-content-inner">
							<?php echo apply_filters('the_content', $reference['description']); ?>
						</div>
					</div>
				</div>
				<?php endif; ?>
			</div>
		</li>
		<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>