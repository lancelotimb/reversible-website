<?php the_post(); ?>

<div class="main-content">

	<div class="extra-button-wrapper post-link-home"><a class="extra-button" href="<?php echo get_permalink(get_option('page_for_posts')); ?>">
		<svg class="icon arrow-left"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#arrow-left"></use></svg>
		<span class="inner"><?php _e("Retour aux actualités", "extra"); ?></span>
	</a></div>

	<div class="post-header">

		<?php
		$thumnailID = has_post_thumbnail() ? get_post_thumbnail_id() : $extra_options['default-thumbnail']['id'];
		extra_responsive_image( $thumnailID, array(
			'desktop' => array( 780, 360),
			'tablet'  => array( 780, 360),
			'mobile'  => array( 690, 318 )
		) );
		?><!-- .thumbnail -->

		<div class="post-navigation">
			<?php
			// PREVIOUS & NEXT POST
			$previous = get_previous_post();
			$next = get_next_post();

			// PREVIOUS
			if(!empty($previous)):
				?>
			<div class="extra-button-wrapper post-link post-link-previous"><a class="extra-button" href="<?php echo get_permalink($previous->ID); ?>">
				<svg class="icon arrow-left"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#arrow-left"></use></svg>
				<span class="inner"><?php _e("Actualité précédente", "extra"); ?></span>
			</a></div>
			<?php
			endif;

			// NEXT
			if(!empty($next)):
				?>
			<div class="extra-button-wrapper post-link post-link-next"><a class="extra-button" href="<?php echo get_permalink($next->ID); ?>">
				<svg class="icon arrow-right"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#arrow-right"></use></svg>
				<span class="inner"><?php _e("Actualité suivante", "extra"); ?></span>
			</a></div>
			<?php endif; ?>
		</div><!-- .post-navigation -->

		<!-- SHARE -->
		<?php extra_custom_share(); ?>
	</div>

	<div class="content post-content">
		<p class="post-date"><?php echo get_the_date(get_option("date_format"), $news->ID); ?></p>
		<h1><?php the_title(); ?></h1>
		<?php the_content(); ?>
	</div>

</div>

<?php comments_template(); ?>