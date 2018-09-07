/** MOBILE ANIMATIONS **/

var messages = [
	'A product on cryptocurrency just was posted on Product Hunt',
	'An indie hacker is asking for feedback for their last side project',
	'Look! Theresa Walcot is posting amazing ideas on monetizing products!',
	'@levelsio just posted a tweet on one of his projects',
];

var previousMessagePosition = 3,
	previousMessageIndex = 0;

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

function getRandomNumberBetweenDontDuplicatePrevious(a, b, previous) {

	var randomNumber = previous;
	while (randomNumber === previous) {
		randomNumber = getRandomNumberBetween(a, b);
	}

	return randomNumber;
}

function getRandomNumberBetween(a, b) {

	var randomNumber = Math.floor(Math.random() * b) + a;
	return randomNumber;
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
	$messageContainer.addClass('transition');

	setTimeout(function() {

		var position = $(window).width() < 768 ? getRandomNumberBetweenDontDuplicatePrevious(1, 2, previousMessagePosition) : getRandomNumberBetweenDontDuplicatePrevious(1, 4, previousMessagePosition);
		previousMessagePosition = position;

		$messageContainer.removeClassRegex(/^position-/);
		$messageContainer.addClass('position-' + position);

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