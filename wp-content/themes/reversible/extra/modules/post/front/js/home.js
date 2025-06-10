jQuery(function ($) {
	var $nextLink = $('.next-link-wrapper a'),
		loading = false,
		$postsWrapper = $('.posts-wrapper'),
		$endMessage = $('.this-is-the-end'),
		$mainWrapper = $('.main-content');

	$nextLink.on('click', function (event) {
		event.preventDefault();
		loadNextPage();
	});

	function loadNextPage() {
		if (!loading) {
			loading = true;
			var href = $nextLink.attr('href');
			extraLoaderPlay($nextLink.addClass('loading'));
			$.get(href)
				.done(function(response) {
					var $response = $(response),
						$newPosts = $response.find('.posts-wrapper > .post'),
						$newNextLink = $response.find('.next-link-wrapper a');

					// Append new posts
					$postsWrapper.append($newPosts);
					animationIn($newPosts);
					//TODO ENTRANCE ANIMATION

					// Update next posts button
					if ($newNextLink.size() > 0) {
						$nextLink.attr('href', $newNextLink.attr('href'));
					} else {
						thisIsTheEnd();
					}
				})
				.fail(function() {
				})
				.always(function() {
					extraLoaderStop($nextLink.removeClass('loading'));
					loading = false;
				});
		}
	}

	function animationIn($posts) {
		var $postLinks = $posts.find('.post-link');

		$posts.find('.responsiveImagePlaceholder').each(function () {
			$window.trigger('extra.responsiveImage', [$(this)]);
		});

		var timelineMax = new TimelineMax();
		timelineMax.staggerFrom($posts, 0.3, {height: 0}, 0.2);
	}

	function thisIsTheEnd() {
		$nextLink.hide();
		$mainWrapper.addClass('no-more');
	}
});