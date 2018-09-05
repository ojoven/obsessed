/** VARS **/
var stickyAfter,
    isSticky,
    init = true;

var $navigation = $('.navigation');

/** LOGIC **/
$(function() {

    handleResponsiveMenu();
    handleMenuMobile();
    handleStickyHeader();
});

$(window).resize(function() {
    handleResponsiveMenu();
});

/** FUNCTIONS **/
function handleResponsiveMenu() {

    if ($(window).width() <= 992) {
        $navigation.addClass('navigation__portrait');
        $navigation.removeClass('navigation__landscape');
    } else {
        if (!init) {
            $('html').removeClass('navigation__portrait');
        }
    }
    if ($(window).width() > 992) {
        $navigation.addClass('navigation__landscape');
        $navigation.removeClass('navigation__portrait');
        $navigation.removeClass('offcanvas__overlay');
        $('body').removeClass('scroll-prevent');
        $('.navigation-wrapper').removeClass('offcanvas__is-open');
    } else {
        if (!init) {
            $navigation.removeClass('navigation__landscape');
        }
    }

    init = false;
}

function handleMenuMobile() {

    handleOpenMenuMobile();
    handleCloseMenuMobile();
}

function handleOpenMenuMobile() {

    var $navigationToggler = $('.navigation__toggler');
    $navigationToggler.on('click', function(e) {

        $('.navigation-wrapper').addClass('offcanvas__is-open');
        $('.navigation').addClass('offcanvas__overlay');
        $('body').toggleClass('scroll-prevent');

        e.stopPropagation();
    });
}

function handleCloseMenuMobile() {

    $('body, .offcanvas__close, .navigation-menu__item').on('click', function() {
        $('.navigation-wrapper').removeClass('offcanvas__is-open');
        $navigation.removeClass('offcanvas__overlay');
        $('body').removeClass('scroll-prevent');
    });

    // PREVENT CLOSING
    var $body = $('body');
    $body.on('click', '.navigation-wrapper', function(e) {
        e.stopPropagation();
    });
}

function handleStickyHeader() {

    stickyAfter = 100;
    isSticky = $(window).scrollTop() > stickyAfter;
    if (isSticky) {
        $navigation.addClass('sticky-nav');
    }

    $(window).on('scroll', function() {
        handleStickyHeaderOnScroll();
    });
}

function handleStickyHeaderOnScroll() {

    if ($(window).scrollTop() > stickyAfter) {
        if (!isSticky) {
            $navigation.addClass('sticky-nav');
            isSticky = !isSticky;
        }
    } else {
        if (isSticky) {
            $navigation.removeClass('sticky-nav');
            isSticky = !isSticky;
        }
    }
}
