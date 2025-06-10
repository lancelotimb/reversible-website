<?php get_header(extra_template_base()); ?>

<section class="main-wrapper">

	<?php get_sidebar(extra_template_base()); ?>

	<article class="full-content">
		<?php include extra_template_path(); ?>
	</article>

</section>

<?php
get_footer(extra_template_base());