<?php
global $extra_options;

?>
<div class="contact column column-four column-last">
	<h4><?php echo $extra_options['contact_title']; ?></h4>
	<div class="contact-content">
		<?php echo nl2br($extra_options['contact_content']); ?>
	</div>
	<div class="contact-email contact-info">
		<svg class="icon icon-mail"><use xlink:href="#icon-mail"></use></svg>
		<a class="inner" href="mailto:<?php echo $extra_options['contact_email']; ?>">
			<?php echo $extra_options['contact_email']; ?>
		</a>
	</div>
	<div class="contact-phone contact-info">
		<svg class="icon icon-phone"><use xlink:href="#icon-phone"></use></svg>
		<span class="inner"><?php echo $extra_options['contact_phone']; ?></span>
	</div>
</div>
