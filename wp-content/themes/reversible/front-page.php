<?php
global $front_page_metabox;
the_post();
$front_page_metabox->the_meta();
$fpMetas = $front_page_metabox->meta;
//var_dump($fpMetas);
?>
<!--///////////////////////////////////////////


SLIDER


///////////////////////////////////////////-->
<div class="front-page-slider extra-slider">
	<div class="wrapper">

		<ul>
			<?php if(isset($fpMetas['slider']) && !empty($fpMetas['slider'])): foreach($fpMetas['slider'] as $slide): ?>
			<li class="front-page-slider-slide">
				<?php extra_responsive_image($slide['image'], array(
					'desktop' => array(1620, 660),
					'tablet' => array(1024, 540),
					'mobile' => array(660, 660)
				)); ?>
				<div class="front-page-slider-content">
					<div class="front-page-slider-inner">
						<h2 class="front-page-slider-content-title"><?php echo $slide['title']; ?></h2>
					</div>
				</div>
			</li>
			<?php endforeach; endif; ?>
		</ul>

	</div>
	<div class="pagination"></div>
	<div class="front-page-slider-content-shop-link-wrapper"><div class="front-page-slider-content-shop-link-inner"><a class="front-page-slider-content-shop-link extra-button" href="<?php echo get_permalink(wc_get_page_id( 'shop' )); ?>"><?php _e("Découvrez la boutique", 'extra'); ?></a></div></div>
</div>
	<!--///////////////////////////////////////////


	PUSH 1


	///////////////////////////////////////////-->
<?php $push = $fpMetas['push']; ?>
<div class="push-wrapper">
	<div class="push-left">
		<?php
		$title = $push[0]['link_title'];
		$type = $push[0]['link_type'];
		$content = $push[0]['link_content'];
		$url = Link::get_permalink_from_meta($push[0], 'link', '_');
		$target = Link::get_target_from_meta($push[0], 'link', '_');

//		$url = ($type == 'content') ? get_permalink($content) : (isset($push[0]['link_url']) ? $push[0]['link_url'] : '#');
		$hasText = (isset($push[0]['title']) && !empty($push[0]['title'])) || (isset($push[0]['subtitle']) && !empty($push[0]['subtitle']));
		?>
		<a class="push-link border-button" href="<?php echo $url; ?>" title="<?php echo $title; ?>" target="<?php echo $target; ?>">
		<?php extra_responsive_image($push[0]['image'], array(
			'desktop' => array(660, 660),
			'tablet' => array(660, 660),
			'mobile' => array(660, 660)
		)); ?>
			<span class="push-link-text<?php echo $hasText ? ' has-text' : ''; ?>">
				<span class="push-link-text-wrapper">
					<span class="push-link-text-inner"><?php
						echo (isset($push[0]['title']) && !empty($push[0]['title'])) ? '<span class="push-link-title">' . nl2br($push[0]['title']) . '</span>' : '';
						echo (isset($push[0]['subtitle']) && !empty($push[0]['subtitle'])) ? '<span class="push-link-subtitle">' . nl2br($push[0]['title']) . '</span>' : '';
					?></span>
				</span>
			</span>
		</a>
	</div>
	<div class="push-right">
		<div class="push-right-inner">
			<!--///////////////////////////////////////////


			PUSH 2


			///////////////////////////////////////////-->
			<?php for($i = 1; $i <= 2; $i++):
				$title = $push[$i]['link_title'];
				$type = $push[$i]['link_type'];
				$content = $push[$i]['link_content'];
				$url = Link::get_permalink_from_meta($push[$i], 'link', '_');
				$target = Link::get_target_from_meta($push[$i], 'link', '_');

				if (empty($url)) {
					$url = get_permalink(wc_get_page_id( 'shop' ));
				}

//				$url = ($type == 'content') ? get_permalink($content) : (isset($push[$i]['link_url']) ? $push[$i]['link_url'] : '#');
				$hasText = (isset($push[$i]['title']) && !empty($push[$i]['title'])) || (isset($push[$i]['subtitle']) && !empty($push[$i]['subtitle']));
			?>
			<div class="push">
				<a class="push-link border-button<?php echo $hasText ? ' has-text' : ''; ?>" href="<?php echo $url; ?>" title="<?php echo $title; ?>" target="<?php echo $target; ?>">
				<?php extra_responsive_image($push[$i]['image'], array(
					'desktop' => array(300, 300),
					'tablet' => array(300, 300),
					'mobile' => array(660, 660)
				)); ?>
					<span class="push-link-text<?php echo $hasText ? ' has-text' : ''; ?>">
						<span class="push-link-text-wrapper">
							<span class="push-link-text-inner"><?php
								echo (isset($push[$i]['title']) && !empty($push[$i]['title'])) ? '<span class="push-link-title">' . nl2br($push[$i]['title']) . '</span>' : '';
								echo (isset($push[$i]['subtitle']) && !empty($push[$i]['subtitle'])) ? '<span class="push-link-subtitle">' . nl2br($push[$i]['subtitle']) . '</span>' : '';
							?></span>
						</span>
					</span>
				</a>
			</div>
			<?php endfor; ?>
		</div>
		<!--///////////////////////////////////////////


		PUSH NEWS


		///////////////////////////////////////////-->
		<?php
			if($fpMetas['post'] == 0) {
				$news = get_posts(array(
					'posts_per_page' => 1
				))[0];
			} else {
				$news = get_post($fpMetas['post']);
			}
		?>
		<div class="push-news">
			<div class="push-news-inner"></div>
			<a class="push-link" href="<?php echo get_permalink($news->ID); ?>" title="<?php echo get_the_title($news->ID); ?>">
				<?php
				if(has_post_thumbnail($news->ID)):
				extra_responsive_image(get_post_thumbnail_id($news->ID), array(
					'desktop' => array(660, 300),
					'tablet' => array(660, 300),
					'mobile' => array(300, 300)
				));
				endif;
				?>
				<div class="push-news-text">
					<p class="push-news-date"><?php echo get_the_date(get_option("date_format"), $news->ID); ?></p>
					<h3 class="push-news-title"><?php echo get_the_title($news->ID); ?></h3>
				</div>
			</a>
		</div>
	</div>
</div>
<!--///////////////////////////////////////////


MATERIAUX


///////////////////////////////////////////-->
<div class="front-page-materials extra-slider">
	<div class="wrapper">
		<ul>
			<?php

			if(isset($fpMetas['materials_images']) && !empty($fpMetas['materials_images'])):
				$images = explode (',', $fpMetas['materials_images']);
				foreach($images as $image): ?>
				<li class="front-page-materials-slide">
					<?php extra_responsive_image($image, array(
						'desktop' => array(1350, 420),
						'tablet' => array(1024, 420),
						'mobile' => array(550, 420)
					)); ?>
				</li>
			<?php endforeach; endif; ?>
		</ul>
		<div class="front-page-materials-content">
			<div class="front-page-materials-inner">
				<h2 class="front-page-materials-content-title"><?php echo $fpMetas['materials_title']; ?></h2>
				<?php
				$title = $fpMetas['materials_link_title'];

				$url = Link::get_permalink_from_meta($fpMetas, 'materials_link', '_');
				$target = Link::get_target_from_meta($fpMetas, 'materials_link', '_');

//				$url = ($type == $fpMetas['materials_link_type']) ? get_permalink($fpMetas['materials_link_content']) : (isset($fpMetas['materials_link_url']) ? $fpMetas['materials_link_url'] : '#');
				?>
				<div class="front-page-materials-content-link-wrapper"><a class="front-page-materials-content-link extra-button" title="<?php echo $title; ?>" href="<?php echo $url; ?>" target="<?php echo $target; ?>"><?php echo $fpMetas['materials_link_text']; ?></a></div>
			</div>
		</div>
		<div class="navigation"><a href="#" class="next extra-button"><svg class="icon arrow-right"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#arrow-right"></use></svg></a></div>
	</div>
</div>