$(document).ready(function() {
	$('.wpcf7').each(function(){
		var $form = $(this),
			submitted = false,
			$button = $(".wpcf7-submit");
		$form.on("submit.wpcf7", function() {
			if(!submitted) {
				submitted = true;
				$form.addClass("extra-overlay");
				extraLoaderPlay($button);
			} else {
				submitted = false;
			}
		}).on("invalid.wpcf7 spam.wpcf7 mailsent.wpcf7 mailfailed.wpcf7", function() {
			$form.removeClass("extra-overlay");
			extraLoaderStop($button);
		});
	});
});