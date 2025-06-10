<?php
/*
Template Name: Contact
*/
global $contact_metabox;
the_post();
$contact_metabox->the_meta();
?>
<?php get_template_part("header-main-image"); ?>
<!--///////////////////////////////////////////


MAIN CONTENT


///////////////////////////////////////////-->
<div class="main-content">
	<?php echo (isset($contact_metabox->meta['contact_form_id'])) ? do_shortcode('[contact-form-7 id="'.$contact_metabox->meta['contact_form_id'].'" title="Formulaire de contact"]') : ''; ?>
	<div class="content right-content"><?php the_content(); ?></div>
</div>
<!--///////////////////////////////////////////


MATERIAUX


///////////////////////////////////////////-->
<?php if(isset($contact) && !empty($contact)): ?>
<div class="contact-wrapper">
	<ul class="contact-inner">
		<?php foreach($contact as $contact): ?>
		<li class="contact-item">
			<?php
			if(isset($contact['image']) && !empty($contact['image'])) {
				extra_responsive_image( $contact['image'], array(
					'desktop' => array( 420, 420 ),
					'tablet'  => array( 420, 420 ),
					'mobile'  => array( 420, 420 )
				), 'contact-image');
			}
			?>
			<?php if(isset($contact['description']) && !empty($contact['description'])): ?>
			<div class="contact-content-wrapper">
				<div class="contact-content">
					<svg class="icon icon-close"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-close"></use></svg>
					<div class="contact-content-inner">
						<?php echo apply_filters('the_content', $contact['description']); ?>
					</div>
				</div>
			</div>
			<?php endif; ?>
		</li>
		<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>