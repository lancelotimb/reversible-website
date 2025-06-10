<?php
/*
Template Name: Matières
*/
global $materials_metabox;
the_post();
$materials_metabox->the_meta();
$materials = $materials_metabox->meta['materials'];;
?>
<?php //get_template_part("header-sub-navigation"); ?>
<!--///////////////////////////////////////////


MATERIAUX


///////////////////////////////////////////-->
<?php if(isset($materials) && !empty($materials)): ?>
<div class="materials-wrapper">
	<ul class="material-inner">
		<?php foreach($materials as $material): ?>
		<li class="material-item">
			<div class="material-header">
				<?php
				if(isset($material['image']) && !empty($material['image'])) {
					extra_responsive_image( $material['image'], array(
						'desktop' => array( 1380, 1020 ),
						'tablet'  => array( 1024, 420 ),
						'mobile'  => array( 690, 420 )
					), 'material-image');
				}
				?>
				<div class="material-header-inner">
					<h2 class="material-title"><?php echo $material['title']; ?></h2>
					<!--<a class="extra-button material-link" href="#"><svg class="icon plus"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#plus"></use></svg></a>-->
				</div>
			</div>
			<div class="material-content">
				<div class="material-content-inner content"><?php echo apply_filters('the_content', $material['content']); ?></div>
			</div>
		</li>
		<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>