/** MOBILE ANIMATIONS **/

var messages = [
	'@awesomeuser just posted a new product on Product Hunt',
	'an indie hacker is asking for feedback for their last side project',
	'look! Theresa Walcot is posting amazing ideas on monetizing products!',
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

	}, 2000);

}

function renderExternalMessage() {

	var message = messages[Math.floor(Math.random()*messages.length)];
	var $messagesExternalContainer = $('.messages-external-container');

	var $messageWrapper = '<div class="message">' + message + '</div>';
	$messagesExternalContainer.html($messageWrapper);

}

function animateTimeline() {

	var $posts = $('.post');
	$posts.removeClass('active');

	var $timeline = $('.timeline');
	var $newPost = '<div class="post loading active"></div>';
	$timeline.prepend($newPost);
	setTimeout(function() {
		$timeline.find('.loading').removeClass('loading');
	}, 100);
}