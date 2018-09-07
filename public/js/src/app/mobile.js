/** MOBILE ANIMATIONS **/

var messages = [
	'A product on cryptocurrency just was posted on Product Hunt',
	'An indie hacker is asking for feedback for their last side project',
	'Look! Theresa Walcot is posting amazing ideas on monetizing products!',
	'@levelsio just posted a tweet on one of his projects',
];

// LOGIC
$(function() {

	handleMessagesTimelineAnimation();

});

// FUNCTIONS
function handleMessagesTimelineAnimation() {

	setInterval(function() {

		renderExternalMessage();
		animateTimeline();

	}, getRandomNumberBetween(2000, 5000));

}

function getRandomNumberBetween(a, b) {

	return Math.floor(Math.random() * b) + a;
}

$.fn.removeClassRegex = function(regex) {
	return $(this).removeClass(function(index, classes) {
		return classes.split(/\s+/).filter(function(c) {
			return regex.test(c);
		}).join(' ');
	});
};

function renderExternalMessage() {

	var message = messages[Math.floor(Math.random()*messages.length)];
	var $messageContainer = $('.message-container');

	var $messageWrapper = '<div class="message">' + message + '</div>';

	var position = getRandomNumberBetween(1, 2);
	$messageContainer.removeClassRegex(/^position-/);
	$messageContainer.addClass('transition').addClass('position-' + position);

	setTimeout(function() {
		$messageContainer.find('.message').remove();
		$messageContainer.prepend($messageWrapper);
		$messageContainer.removeClass('transition');
	}, 300);

}

function animateTimeline() {

	var $posts = $('.post');
	$posts.removeClass('active');
	$posts.last().remove();

	var $timeline = $('.timeline');
	var $newPost = '<div class="post loading active"></div>';
	$timeline.prepend($newPost);
	setTimeout(function() {
		$timeline.find('.loading').removeClass('loading');
	}, 100);
}