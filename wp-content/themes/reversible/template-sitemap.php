<?php
/*
Template Name: Plan du site
*/

the_post();
get_template_part("header-main-image");
?>
<div class="content main-content">
	<?php the_content(); ?>
	<ul>
		<?php
		wp_list_pages(
			array(
				'title_li' => null,
				'exclude' => wc_get_page_id('cart').','.wc_get_page_id('checkout').','.wc_get_page_id('myaccount')
			)
		);
		?>
	</ul>
</div>
