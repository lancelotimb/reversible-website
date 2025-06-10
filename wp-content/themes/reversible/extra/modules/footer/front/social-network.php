<?php
global $extra_options;
?>
<div class="column column-three column-last social-network">
	<h4><?php echo $extra_options['social_network_title'] ?></h4>
	<ul>
		<?php if (isset($extra_options['facebook_url']) && !empty($extra_options['facebook_url'])) : ?>
		<li>
			<a class="social-link facebook" href="<?php echo $extra_options['facebook_url']; ?>" target="_blank">
				<svg class="icon icon-facebook"><use xlink:href="#icon-facebook"></use></svg>
			</a>
		</li>
		<?php endif; ?>
		<?php if (isset($extra_options['twitter_url']) && !empty($extra_options['twitter_url'])) : ?>
		<li>
			<a class="social-link twitter" href="<?php echo $extra_options['twitter_url']; ?>" target="_blank">
				<svg class="icon icon-facebook"><use xlink:href="#icon-twitter"></use></svg>
			</a>
		</li>
		<?php endif; ?>
		<?php if (isset($extra_options['pinterest_url']) && !empty($extra_options['pinterest_url'])) : ?>
		<li>
			<a class="social-link pinterest" href="<?php echo $extra_options['pinterest_url']; ?>" target="_blank">
				<svg class="icon icon-pinterest"><use xlink:href="#icon-pinterest"></use></svg>
			</a>
		</li>
		<?php endif; ?>
		<?php if (isset($extra_options['youtube_url']) && !empty($extra_options['youtube_url'])) : ?>
		<li>
			<a class="social-link youtube" href="<?php echo $extra_options['youtube_url']; ?>" target="_blank">
				<svg class="icon icon-youtube"><use xlink:href="#icon-youtube"></use></svg>
			</a>
		</li>
		<?php endif; ?>
		<?php if (isset($extra_options['instagram_url']) && !empty($extra_options['instagram_url'])) : ?>
		<li>
			<a class="social-link instagram" href="<?php echo $extra_options['instagram_url']; ?>" target="_blank">
				<svg class="icon icon-instagram"><use xlink:href="#icon-instagram"></use></svg>
			</a>
		</li>
		<?php endif; ?>
	</ul>
</div>