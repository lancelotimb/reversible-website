<?php
global $extra_options;
get_template_part("header-main-image");

$next_link = get_next_posts_link(__("Afficher plus d'actualités", 'extra'));
?>

<div class="main-content">
	<?php if(have_posts()): ?>
		<div class="posts-wrapper">
			<?php while ( have_posts() ) : the_post(); ?>
				<article class="post">
					<div class="post-inner"></div>
					<a class="post-link" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
						<?php
						$thumnailID = has_post_thumbnail() ? get_post_thumbnail_id() : $extra_options['default-thumbnail']['id'];
						extra_responsive_image($thumnailID, array(
							'desktop' => array(660, 300),
							'smalldesktop' => array(660, 300),
							'tablet' => array(660, 300),
							'mobile' => array(660, 300)
						));
						?>
						<div class="post-text">
							<p class="post-date"><?php echo get_the_date(get_option("date_format"), $news->ID); ?></p>
							<h3 class="post-title"><?php the_title(); ?></h3>
						</div>
					</a>
				</article>
			<?php endwhile; ?>
		</div>
	<?php endif; ?>

	<?php if ($next_link) : ?>
		<div class="next-link-wrapper">
			<?php echo $next_link; ?>
		</div>
	<?php endif; ?>
	<div class="this-is-the-end">
		<?php _e("Bravo. Vous êtes arrivés à la fin des internets !"); ?>
	</div>
</div>