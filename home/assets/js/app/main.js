/*
1-  General Styles
2-  Parallax
3-  Navigation
4-  Flicker
5-  Slider
*/
jQuery(function($) {
    "use strict";
    /*=======================================
	General
	=======================================*/
    var xv_ww = $(window).width();


    /*=======================================
	Parallax
	=======================================*/
    if (xv_ww >= 768) {
        $.stellar({
            horizontalScrolling: false,
            verticalOffset: 0,
            responsive: true,
        });
    }
    /*=======================================
	Navigation
	=======================================*/
    $("body").on("click", ".nav-triger", function(e) {
        e.preventDefault();
        $(".main-menu").slideToggle();
        $(this).toggleClass('active');
        if ($(this).hasClass('active')) {
            $(this).find('span').removeClass("fa-navicon");
            $(this).find('span').addClass("fa-times");
        } else {
            $(this).find('span').removeClass("fa-times");
            $(this).find('span').addClass("fa-navicon");
        }
    });

    $("body").on("click", ".nav a[href^='#']", function(e) {
        e.preventDefault();
        var $this = $(this);
        $("body,html").animate({
            scrollTop: $($this.attr("href")).offset().top
        }, 500);
    });

    $("body").on("click", ".main-menu > li.parent", function(e) {
        if ($(window).width() < 1024) {
            e.preventDefault();
            e.stopImmediatePropagation();
            $(this).children("ul").slideToggle();
        }
    });

    $(window).on("scroll", function(e) {
        if ($(window).scrollTop() > 50) {
            // > 100px from top - show div
            $(".navbar-custom").addClass("navbar_style");
        } else {
            // <= 100px from top - hide div
            $(".navbar-custom").removeClass("navbar_style");
        }
    });




    /*=======================================
	Flicker
	=======================================*/
    if ($('#flicker-feed').length !== 0) {
        $('#flicker-feed').jflickrfeed({
            limit: $('#flicker-feed').data('limit'),
            qstrings: {
                id: $('#flicker-feed').attr('data-userID')
            },
            itemTemplate: '<li><a href="{{image_b}}" data-rel="prettyPhoto"><img alt="{{title}}" src="{{image_s}}" /></a></li>'
        }, function() {
            $("a[data-rel^='prettyPhoto']").prettyPhoto();
        });
    }

    /*=======================================
	Slider
	=======================================*/
    var owl = $(".owl-carousel");
    owl.each(function() {
        var $this = $(this),
            viewDots = $this.data("dots"),
            isLoop = $this.data("loop"),
            isNav = $this.data("nav"),
            viewSlides = +$this.data("slides"),
            viewSlides_md = +$this.data("slides-md"),
            viewSlides_sm = +$this.data("slides-sm"),
            slideMargin = +$this.data("margin"),
            slidesCenter = $this.data("center"),
            slidesHash = $this.data("hash"),
            nextIcon = $this.data("next"),
            prevIcon = $this.data("prev"),
            slideDrag = $this.data("drag"),
            slideFade = $this.data("fade"),
            slideAuto = $this.data("auto");

        $this.owlCarousel({
            loop: isLoop,
            margin: slideMargin,
            nav: isNav,
            dots: viewDots,
            center: slidesCenter,
            URLhashListener: slidesHash,
            mouseDrag: slideDrag,
            animateOut: slideFade,
            autoplay: slideAuto,
            autoHeight: true,
            navText: ["<i class='btn_prev fa " + prevIcon + "'></i>", "<i class='btn_next fa " + nextIcon + "'></i>"],
            responsive: {
                0: {
                    items: viewSlides_sm
                },
                600: {
                    items: viewSlides_md
                },
                1000: {
                    items: viewSlides
                }
            }
        }); /*owl end*/
    }); /*each*/

}); /*end ready*/ /*end require*/