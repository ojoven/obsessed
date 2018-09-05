// Use following syntax to prepend required libraries/scripts
// Use **/*.js if you don't need to follow a specific order
// You can override the order by requiring the JS file before the global require
//=require vendor/**/jquery.min.js
//=require vendor/**/*.js
//=require modules/**/*.js
(function ($) {
    "use strict";
    
   // smooth scroll
    $(document).ready(function () {
        $('#nav').onePageNav({
            currentClass: 'current-menu-item',
        });
    });

    // video popup
    var html5lightbox_options = {
        watermarklink: ""
    };

    // image settings
    $(".media__content").each(function () {
        var thesrc = $(this).attr('src');
        $(this).parent().css("background-image", "url(" + thesrc + ")");
        $(this).parent().css("background-repeat", "no-repeat");
        $(this).hide();
    });

    // testimonial-2
    $('.testimonial-container .testimonial').slick({
        autoplay: false,
        prevArrow: '<div><button class="prevArrow arrowBtn"><i class="nc-icon nc-tail-left"></i></button></div>',
        nextArrow: '<div><button class="nextArrow arrowBtn"><i class="nc-icon nc-tail-right"></i></button></div>',
        responsive: [{
            breakpoint: 992,
            settings: {
                arrows: false,

            }
        }]
    });

    // on reveal
    window.sr = ScrollReveal();
    sr.reveal('.reveal, .faq-list, .feature__list', {
        duration: 1000,
        delay: 0,
        scale: 1,
        opacity: .2,
        easing: 'ease-in-out',
    });


})(jQuery);