(function($) {
    'use strict';

    /* -----------------------------------
       1 Mobile menu
    ----------------------------------- */
    $('.mobile-menu').meanmenu();

    /* ----------------------------------
       2. Portfolio activation
    ----------------------------------- */
    $('.portfolio-section').imagesLoaded(function() {
        var $grid = $('.portfolio-grid').isotope({
            itemSelector: '.portfolio-item',
            percentPosition: true,
        })

        // Portfolio filtering activation
        $('.portfolio-filter li a').on('click', function() {
            var filterValue = $(this).attr('data-filter');
            $grid.isotope({ filter: filterValue });
        });

        // Filter menu active class addition  
        $('.portfolio-filter li').on('click', function(event) {
            $(this).siblings('.active').removeClass('active');
            $(this).addClass('active');
            event.preventDefault();
        });
    });
    /* ----------------------------------
       3. Magnificpopup image gallery
    ----------------------------------- */
    var projectZoom = $('.project-zoom');
    projectZoom.magnificPopup({
        type: 'image',
        removalDelay: 300,
        mainClass: 'mfp-no-margins mfp-with-zoom',
        gallery: {
            enabled: true
        },
        image: {
            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
            titleSrc: function(item) {
                return item.el.attr('data-title');
            }
        }
    });

    /*-------------------------------------
      4. Magnificpopup video gallery
    --------------------------------------- */
    var videoPlayModal = $('.video-play-icon');
    videoPlayModal.magnificPopup({
        disableOn: 700,
        type: "iframe",
        mainClass: "mfp-fade",
        removalDelay: 160,
        preloader: false,
        fixedContentPos: false
    });

    /*---------------------------
     5. Fact counter
    -----------------------------*/
    var factCounter = $('.fact-number');
    factCounter.counterUp({
        delay: 20,
        time: 3000
    });

    /*---------------------------
     6. WOW Js
    -----------------------------*/
    new WOW().init();

    /*---------------------------
     7. Testimonial slider
    -----------------------------*/
    // 
    var testimonialSlider = $('.testimonial-wrapper');
    testimonialSlider.slick({
        dots: false,
        arrows: true,
        infinite: true,
        slidesToShow: 2,
        slidesToScroll: 1,
        responsive: [
            { breakpoint: 991, settings: { slidesToShow: 1 } },
            { breakpoint: 767, settings: { slidesToShow: 1 } },
            {
                breakpoint: 479,
                settings: {
                    slidesToShow: 1,
                    autoplay: true,
                    autoplaySpeed: 500,
                }
            },
        ]
    });

    /*---------------------------
     8. Brand logo slider
    -----------------------------*/
    var brandLogoSlider = $('.brand-logo-slider');
    brandLogoSlider.slick({
        dots: false,
        arrows: false,
        slidesToShow: 5,
        infinite: true,
        speed: 300,
        adaptiveHeight: false,
        responsive: [
            { breakpoint: 991, settings: { slidesToShow: 4 } },
            { breakpoint: 767, settings: { slidesToShow: 3 } },
            { breakpoint: 481, settings: { slidesToShow: 2 } },
            {
                breakpoint: 321,
                settings: {
                    slidesToShow: 1,
                    autoplay: true,
                    autoplaySpeed: 1500,
                }
            },
        ]
    });

    /*---------------------------
     9. Main Slider
    -----------------------------*/
    function mainSlider() {
        var expertSlider = $('#expert-slider');
        expertSlider.on('init', function(e, slick) {
            var $firstAnimatingElements = $('.expert-single-slide:first-child').find('[data-animation]');
            doAnimations($firstAnimatingElements);
        });
        expertSlider.on('beforeChange', function(e, slick, currentSlide, nextSlide) {
            var $animatingElements = $('.expert-single-slide[data-slick-index="' + nextSlide + '"]').find('[data-animation]');
            doAnimations($animatingElements);
        });
        expertSlider.slick({
            autoplay: true,
            autoplaySpeed: 10000,
            dots: false,
            fade: true,
            responsive: [
                { breakpoint: 767, settings: { dots: false, arrows: false } }
            ]
        });

        function doAnimations(elements) {
            var animationEndEvents = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
            elements.each(function() {
                var $this = $(this);
                var $animationDelay = $this.data('delay');
                var $animationType = 'animated ' + $this.data('animation');
                $this.css({
                    'animation-delay': $animationDelay,
                    '-webkit-animation-delay': $animationDelay
                });
                $this.addClass($animationType).one(animationEndEvents, function() {
                    $this.removeClass($animationType);
                });
            });
        }
    }
    mainSlider();

    /*-------------------------------------------
      10. Scroll to top button
    ---------------------------------------------*/
    $('body').append('<a id="back-to-top" class="to-top-btn" href="#"><i class="fa fa-angle-up"></i></a>');
    if ($('#back-to-top').length != 0) {
        var scrollTrigger = 100, // px
            backToTop = function() {
                var scrollTop = $(window).scrollTop();
                if (scrollTop > scrollTrigger) {
                    $('#back-to-top').addClass('to-top-show');
                } else {
                    $('#back-to-top').removeClass('to-top-show');
                }
            };
        backToTop();
        $(window).on('scroll', function() {
            backToTop();
        });
        $('#back-to-top').on('click', function(e) {
            e.preventDefault();
            $('html,body').animate({
                scrollTop: 0
            }, 500);
        });
    };

    /* -------------------- ---
     11. Sticky header activation
    ------------------ --------*/
    if ($('.sticky-header').length != 0) {
        $('.sticky-header').sticky({
            zIndex: 999
        });
    }

    /* -------------------- ---
     12. Rellax For Parallax
    ------------------ --------*/
    if ($('.rellax').length != 0) {
        var rellax = new Rellax('.rellax');
    }

})(jQuery)