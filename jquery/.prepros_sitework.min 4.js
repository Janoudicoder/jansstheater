//@prepros-prepend jquery.fancybox.min.js
//@prepros-prepend navigation.min.js
//@prepros-prepend outdated-browser-rework.js
//@prepros-prepend wow.min.js
//@prepros-prepend photogrid.js
//@prepros-prepend lazyload.min.js
//@prepros-prepend ../flexslider/jquery.flexslider-min.js
//@prepros-prepend ../slick/slick.min.js
//@prepros-prepend ../cookie/cookie-bar.js

$(document).ready(function() { 
    
    const btnSwitch = document.querySelector('.switch');
    btnSwitch.addEventListener('click', toggle);
    function toggle(e) {
        e.preventDefault();
        btnSwitch.classList.toggle('active');
    }
    if(($(document.body).hasClass( ".dark-mode" ))){
        $(".switch").addClass("active");
    }
    // Check to see if Media-Queries are supported
    if (window.matchMedia) {
        // Check if the dark-mode Media-Query matches
        if(window.matchMedia('(prefers-color-scheme: dark)').matches){
            // Dark
            $(".switch").addClass("active");
            alert("dark");
        } else {
            // Light
            $(".switch").removeClass("active");
            alert("light");
        }
    } else {
        // Default (when Media-Queries are not supported)
    }
    var lazyLoadInstance = new LazyLoad({
        callback_error: (img) => {
            // Use the following line only if your images have the `srcset` attribute
            img.setAttribute("data-srcset", "img/noimg.jpg 1x, img/noimg.jpg 2x");
            img.setAttribute("data-src", "img/noimg.jpg");
        }
    });
    //MENU
    $("#navigation1").navigation({
        mobileBreakpoint: 1000,
        submenuIndicatorTrigger: true,
        overlayColor: "rgb(242 153 226 / 50%)"
    });

    //galerij grid
    $('#galerij-inner').BlocksIt({
        numOfCol: 5,
        offsetX: 8,
        offsetY: 8,
        blockElement: 'a'
    });


    // FANCYBOX INSTELLINGEN IMAGES
    $().fancybox({
        selector: '[data-fancybox="images"]',
        hash: false,
        keyboard: true,
        arrows: true,
        buttons: [
            'slideShow',
            'fullScreen',
            'thumbs',
            'close'
        ],
    });

    // FANCYBOX INSTELLINGEN VIDEO
    $().fancybox({
        selector: '[data-fancybox="video"]',
        youtube: {
            controls: 1,
            showinfo: 0,
            rel: 0,
        }
    });

    // TABLE RESPONSIVE
    $("table").wrap("<div style=\"overflow-x:auto;\"></div>");

    //flexslider parallax
    // $(window).scroll(function() {
    //    if($(window).scrollTop() > 0) {
    //       var parallaxDistance = ($(window).scrollTop()/2),
    //       parallaxCSS = "translate3d(0, "+ parallaxDistance +"px , 0)";
    //       $('.slides').css('transform', parallaxCSS);
    //    } else {
    //       $('.slides').css('transform', 'translate3d(0, 0, 0)');
    //    }
    // });


    //
    $('#slick').slick({
        dots: true,
        infinite: false,
        speed: 300,
        slidesToShow: 4,
        slidesToScroll: 4,
        responsive: [{
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    infinite: true,
                    dots: true
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });

    $('.image-slider').slick({
        dots: false,
        arrows: true,
        infinite: false,
        speed: 300,
        slidesToShow: 1,
        slidesToScroll: 1,
        prevArrow: $('.prev'),
        nextArrow: $('.next')
    });

    $('.image-slider-2').slick({
        dots: false,
        arrows: true,
        infinite: false,
        speed: 300,
        slidesToShow: 1,
        slidesToScroll: 1,
        prevArrow: $('.prev'),
        nextArrow: $('.next')
    });

    $('.galerij-block').slick({
        dots: true,
        appendDots: $('.slick-slider-dots'),
        arrows: true,
        infinite: false,
        speed: 300,
        slidesToShow: 1,
        slidesToScroll: 1,
        prevArrow: $('.prev-galerij'),
        nextArrow: $('.next-galerij')
    });


    $('#categorie-slider').slick({
        dots: true,
        infinite: false,
        speed: 300,
        slidesToShow: 2,
        slidesToScroll: 2,
        responsive: [{
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                    infinite: true,
                    dots: true
                }
            },
            {
                breakpoint: 600,
                settings: {
                    arrows: false,
                    dots: true,
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });

    $('#logo-slider').slick({
        dots: false,
        infinite: false,
        speed: 300,
        slidesToShow: 4,
        slidesToScroll: 1,
        responsive: [{
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                    infinite: true,
                    dots: true
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
                    dots: true,
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });

    //PARALLAX INSTELLINGEN
    $('.img-parallax').each(function() {
        var img = $(this);
        var imgParent = $(this).parent();

        function parallaxImg() {
            var speed = img.data('speed');
            var imgY = imgParent.offset().top;
            var winY = $(this).scrollTop();
            var winH = $(this).height();
            var parentH = imgParent.innerHeight();


            // The next pixel to show on screen
            var winBottom = winY + winH;

            // If block is shown on screen
            if (winBottom > imgY && winY < imgY + parentH) {
                // Number of pixels shown after block appear
                var imgBottom = ((winBottom - imgY) * speed);
                // Max number of pixels until block disappear
                var imgTop = winH + parentH;
                // Porcentage between start showing until disappearing
                var imgPercent = ((imgBottom / imgTop) * 100) + (50 - (speed * 50));
            }
            img.css({
                top: imgPercent + '%',
                transform: 'translate(-50%, -' + imgPercent + '%)'
            });
        }
        $(document).on({
            scroll: function() {
                parallaxImg();
            },
            ready: function() {
                parallaxImg();
            }
        });
    });

    // ANIMATE NA SCROLL
    wow = new WOW({
        boxClass: 'wow', // default
        animateClass: 'animated', // default
        offset: 0, // default
        mobile: false, // default
        live: true // default
    })
    wow.init();

});

//window resize
var currentWidth = 1400;
$(window).resize(function() {
    var winWidth = $(window).width();
    var conWidth;
    if (winWidth < 660) {
        conWidth = 440;
        col = 1
    } else if (winWidth < 880) {
        conWidth = 660;
        col = 2
    } else if (winWidth < 1100) {
        conWidth = 880;
        col = 3;
    } else {
        conWidth = 1400;
        col = 4;
    }

    if (conWidth != currentWidth) {
        currentWidth = conWidth;
        $('#galerij-inner').width(conWidth);
        $('#galerij-inner').BlocksIt({
            numOfCol: col,
            offsetX: 8,
            offsetY: 8
        });
    }
});

// FLEXSLIDER INSTELLINGEN
$(window).on("load", function() {
    $('.flexslider').flexslider({
        slideshow: true,
        animation: "fade",
        controlNav: false,
        directionNav: true,
        slideshowSpeed: 7000,
        after: function(slider) {
            if (!slider.playing) {
                slider.play();
            }
        }
    });

    // ALLOW CAPTION CSS3 ANIMATION:
    var caption = $(".flex-active-slide .flex-caption").detach();
    caption.appendTo(".flex-active-slide");
});

$(window).bind('scroll', function() {
    if ($(window).scrollTop() > 116) {
        $('body').addClass('menufixed', 500);
    } else {
        $('body').removeClass('menufixed', 500);
    }
});

//--SCROLL CLASS TOEVOEGEN BIJ SCROLLEN--/
$(window).bind('scroll', function() {
    if ($(window).scrollTop() > 116) {
        $('body').addClass('onscroll', 500);
    } else {
        $('body').removeClass('onscroll', 500);
    }
});

//--SCROLL TO TOP--/
(function($) {
    "use strict";

    $(document).ready(function() {
        "use strict";

        //Scroll back to top

        var progressPath = document.querySelector('.progress-wrap path');
        var pathLength = progressPath.getTotalLength();
        progressPath.style.transition = progressPath.style.WebkitTransition = 'none';
        progressPath.style.strokeDasharray = pathLength + ' ' + pathLength;
        progressPath.style.strokeDashoffset = pathLength;
        progressPath.getBoundingClientRect();
        progressPath.style.transition = progressPath.style.WebkitTransition = 'stroke-dashoffset 10ms linear';
        var updateProgress = function() {
            var scroll = $(window).scrollTop();
            var height = $(document).height() - $(window).height();
            var progress = pathLength - (scroll * pathLength / height);
            progressPath.style.strokeDashoffset = progress;
        }
        updateProgress();
        $(window).scroll(updateProgress);
        var offset = 50;
        var duration = 550;
        jQuery(window).on('scroll', function() {
            if (jQuery(this).scrollTop() > offset) {
                jQuery('.progress-wrap').addClass('active-progress');
            } else {
                jQuery('.progress-wrap').removeClass('active-progress');
            }
        });
        jQuery('.progress-wrap').on('click', function(event) {
            event.preventDefault();
            jQuery('html, body').animate({
                scrollTop: 0
            }, duration);
            return false;
        })
        jQuery('.scroll-to-top').on('click', function(event) {
            event.preventDefault();
            jQuery('html, body').animate({
                scrollTop: 0
            }, duration);
            return false;
        })

    });

})(jQuery);

//# sourceMappingURL=.prepros_sitework.min.js.map