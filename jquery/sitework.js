//@prepros-prepend jquery.fancybox.min.js
//@prepros-prepend navigation.min.js
//@prepros-prepend wow.min.js
//@prepros-prepend photogrid.js
//@prepros-prepend lazyload.min.js
//@prepros-prepend grid-gallery.js
//@prepros-prepend jquery.photoset-grid.js
//@prepros-prepend ../slick/slick.min.js
//@prepros-prepend swiper-bundle.min.js

$(document).ready(function() {
    $('.photoset-grid-basic').photosetGrid({
        highresLinks: true,
        rel: 'print-gallery'
    });

    var lazyLoadInstance = new LazyLoad({
        callback_error: (img) => {
            // Use the following line only if your images have the `srcset` attribute
            img.setAttribute("data-srcset", "img/noimg.jpg 1x, img/noimg.jpg 2x");
            img.setAttribute("data-src", "img/noimg.jpg");
        }
    });

    $(".tekst-leesmeer").click(function() {
        $("#tekst-inhoud").toggleClass("volledig");

        if ($("#tekst-inhoud").hasClass("volledig")) {
            $(".tekst-leesmeer").text("Lees minder -");
        } else {
            $(".tekst-leesmeer").text("Lees meer +");
        }
    });

    $(".formulier").submit(function (e) {
        $(".submit-form").attr("disabled", true);
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

    // Woning afbeeldingen
    $().fancybox({
        selector: '.woning-gallery-foto',
        arrows: true,
        autoStart: true,
        protect: true,
        idleTime: false,
        baseClass: 'fancybox-custom-layout',
        margin: 0,
        gutter: 0,
        infobar: false,
        zoom: false,
        thumbs: {
            autoStart: true,
            hideOnClose: false,
            parentEl: '.fancybox-custom-layout',
            axis: 'x'
        },
        mobile: {
            thumbs: {
                autoStart: false
            }
        },
        touch: {
            vertical: false
        },
        buttons: [
            'thumbs',
            'slideShow',
            'close',
        ],
        animationEffect: "fade",
        animationDuration: 300,
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

    // Swiper
    // ======
        // Swiper for #slick
        // var swiperSlick = new Swiper('#slick', {
        //     slidesPerView: 4,
        //     spaceBetween: 10,
        //     pagination: {
        //         el: '.swiper-pagination',
        //         clickable: true,
        //     },
        //     navigation: {
        //         nextEl: '.swiper-button-next',
        //         prevEl: '.swiper-button-prev',
        //     },
        //     breakpoints: {
        //         1024: {
        //             slidesPerView: 3,
        //             spaceBetween: 10,
        //         },
        //         600: {
        //             slidesPerView: 2,
        //             spaceBetween: 10,
        //         },
        //         480: {
        //             slidesPerView: 1,
        //             spaceBetween: 10,
        //         }
        //     }
        // });

        // // Swiper for .basis-slides
        // var swiperBasisSlides = new Swiper('.basis-slides', {
        //     slidesPerView: 1,
        //     spaceBetween: 10,
        //     navigation: {
        //         nextEl: '.swiper-button-next',
        //         prevEl: '.swiper-button-prev',
        //     },
        //     breakpoints: {
        //         1024: {
        //             slidesPerView: 1,
        //         },
        //         600: {
        //             slidesPerView: 1,
        //         },
        //         480: {
        //             slidesPerView: 1,
        //         }
        //     }
        // });

        // // Swiper for .image-slider
        // // Initialize Swiper for each .image-slider container
        // document.querySelectorAll('.image-slider').forEach((slider, index) => {
        //     new Swiper(slider, {
        //         slidesPerView: 1,
        //         spaceBetween: 10,
        //         navigation: {
        //             nextEl: slider.querySelector('.swiper-button-next'),
        //             prevEl: slider.querySelector('.swiper-button-prev'),
        //         },
        //         // Optionally, you can add more configurations here
        //     });
        // });


        // // Swiper for .galerij-block
        // var swiperGalerijBlock = new Swiper('.galerij-block', {
        //     slidesPerView: 1,
        //     spaceBetween: 10,
        //     pagination: {
        //         el: '.swiper-pagination',
        //         clickable: true,
        //     },
        //     navigation: {
        //         nextEl: '.next-galerij',
        //         prevEl: '.prev-galerij',
        //     }
        // });

        // // Swiper for #categorie-slider
        // var swiperCategorieSlider = new Swiper('#categorie-slider', {
        //     slidesPerView: 2,
        //     spaceBetween: 10,
        //     pagination: {
        //         el: '.swiper-pagination',
        //         clickable: true,
        //     },
        //     breakpoints: {
        //         1024: {
        //             slidesPerView: 2,
        //         },
        //         600: {
        //             slidesPerView: 1,
        //             pagination: {
        //                 el: '.swiper-pagination',
        //                 clickable: true,
        //             }
        //         }
        //     }
        // });

        // // Swiper for .logo-slider
        // var swiperLogoSlider = new Swiper('.logo-slider', {
        //     slidesPerView: 4,
        //     spaceBetween: 10,
        //     breakpoints: {
        //         1024: {
        //             slidesPerView: 2,
        //             spaceBetween: 10,
        //         },
        //         600: {
        //             slidesPerView: 1,
        //             spaceBetween: 10,
        //         }
        //     }
        // });



    // Slick
    // =====
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

    $('.basis-slides').slick({
        autoplay: true,       // Enable automatic sliding
        autoplaySpeed: 4000,  // Time between slides (3 seconds)
        arrows: false,        // Hide navigation arrows
        dots: false,          // Disable navigation dots
        fade: false,          // Disable fade transition for sliding effect
        speed: 1000,          // Transition speed (1 second)
        infinite: true,       // Infinite looping
        cssEase: 'ease',      // Smoother sliding transition
        slidesToShow: 1,      // Show one slide at a time
        slidesToScroll: 1,    // Scroll one slide at a time
        responsive: [{
                breakpoint: 1024,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    infinite: true,
                    dots: false
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
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

    $('.image-slider').each(function( index ) {
        $(this).slick({
            dots: false,
            arrows: true,
            infinite: false,
            speed: 300,
            slidesToShow: 1,
            slidesToScroll: 1,
            appendArrows: $(this),
            prevArrow:  '<i class="fas fa-chevron-left arrow-left"></i>',
            nextArrow: '<i class="fas fa-chevron-right arrow-right"></i>'
        });
    });

    $('.programma-slider').each(function(index) {
        $(this).slick({
            dots: false,
            arrows: true,
            infinite: true,   // Loop infinitely
            speed: 300,
            slidesToShow: 2,  // Default to showing 2 slides
            slidesToScroll: 1,
            centerMode: true, // Center the current slide by default
            centerPadding: '20%', // Make adjacent slides partially visible (adjust as needed)
            appendArrows: $(this),
            prevArrow: '<i class="slick-prev"></i>',
            nextArrow: '<i class="slick-next"></i>',
            responsive: [
                {
                    breakpoint: 770, // Small screens (e.g., tablets or mobile)
                    settings: {
                        slidesToShow: 2,  // Show only 1 slide on small screens
                        centerMode: false, // Disable centerMode on small screens
                        centerPadding: '0', 
                        arrows: false,
                    }
                },
                {
                    breakpoint: 637, // Small screens (e.g., tablets or mobile)
                    settings: {
                        slidesToShow: 1,  // Show only 1 slide on small screens
                        centerMode: false, // Disable centerMode on small screens
                        centerPadding: '0', 
                        arrows: false,
                    }
                }
            ]
        });
    });
    

    $('.galerij-slider').each(function(index) {
        $(this).slick({
            dots: false,
            arrows: false,
            infinite: false,  // Loop infinitely
            speed: 300,
            slidesToShow: 1,  // Show 1 slide at a time
            slidesToScroll: 1,  // Scroll 1 slide at a time
            centerMode: false,  // Disable centering of the current slide
            centerPadding: '0%',  // No padding for adjacent slides
            appendArrows: $(this),
            prevArrow: '<i class="slick-prev"></i>',
            nextArrow: '<i class="slick-next"></i>'
        });
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

    $('.logo-slider').slick({
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
                    autoplay: true,
                    autoplaySpeed: 4000,
                    speed: 500,
                    infinite: true,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
                    dots: false,
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

// function noLoadNoimg() {
//     $(this).find("img").attr("src", "/img/noimg.jpg");
// }

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
// $(window).on("load", function() {
//     $('.flexslider').flexslider({
//         slideshow: true,
//         animation: "fade",
//         controlNav: false,
//         directionNav: true,
//         slideshowSpeed: 7000,
//         after: function(slider) {
//             if (!slider.playing) {
//                 slider.play();
//             }
//         }
//     });

//     // ALLOW CAPTION CSS3 ANIMATION:
//     var caption = $(".flex-active-slide .flex-caption").detach();
//     caption.appendTo(".flex-active-slide");
// });

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

//darkmode switch.
//beide classes verwijderen indien aanwezig. de localstorage setten en die toevoegen aan de body
function changeToDarkMode(settings) {
    if ($(document.body).hasClass("dark-mode")) {
        $(document.body).removeClass("dark-mode");
        $(document.body).removeClass("light-mode");
        localStorage.setItem("modes", "light-mode");
        $(document.body).addClass(localStorage.getItem('modes'));
    }else{
        $(document.body).addClass("dark-mode");
        $(document.body).removeClass("dark-mode");
        $(document.body).removeClass("light-mode");
        localStorage.setItem("modes", "dark-mode");
        $(document.body).addClass(localStorage.getItem('modes'));
    }
}
