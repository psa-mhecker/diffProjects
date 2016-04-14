ISO.moduleCreate('slicecc97', function($el) {
    'use strict';

    var slicecc97 = {
        settingsSlick: {
            infinite: true,
            slidesToShow: 1,
            dots: true,
            autoplay: false,
            autoplaySpeed: 3000,
            slidesToScroll: 1,
            prevArrow: '<span class="icon icon-picto-back-retina-AC"></span>',
            nextArrow: '<span class="icon icon-picto-next-retina-AC"></span>'
        },
        autoplay: false,
        slider: null,

        init: function() {
            var app = this;

            if ($('.multiple-items', $el).length) {
                app.initSwiper();
            }
        },

        initSwiper: function() {
            var app = this;
            app.slider = $('.multiple-items', $el);

            if (app.slider.hasClass('auto')) {
                // app.autoplay = true;
                app.settingsSlick.autoplay = app.autoplay;

                app.slider.slick(app.settingsSlick);

                app.slick = app.slider.slick('getSlick');

                app.slider.on('swipe', function() {
                    app.autoplay = false;
                    app.slider.slick('slickPause');
                });

                app.slider.slick('getSlick').$dots.on('click', 'li', function() {
                    app.autoplay = false;
                    app.slider.slick('slickPause');
                });

                app.slider.slick('getSlick').$slider.on('click', '.slick-arrow', function() {
                    app.autoplay = false;
                    app.slider.slick('slickPause');
                });

            } else {
                app.slider.slick(app.settingsSlick);
            }

            $('video').each(function(i, el) {
                var vjs = videojs($(el)[0], {}, function() {

                    //Resize video player to match aspect ratio
                    var myPlayer = this,
                        id = myPlayer.id();
                    var aspectRatio = 787 / 1050;

                    function resizeVideoJS() {
                        var width = document.getElementById(id).parentElement.offsetWidth;
                        myPlayer.width(width).height(width * aspectRatio);
                    }

                    resizeVideoJS();
                    window.addEventListener("resize", resizeVideoJS);


                    this.on('play', function() {
                        app.slider.slick('slickPause');
                        this.posterImage.hide();
                    });

                    this.on('pause', function() {
                        this.posterImage.hide();
                        this.controlBar.show();
                    });

                    this.on('ended', function() {

                        if (app.autoplay === true) {
                            app.slider.slick('slickPlay');
                        }

                        this.controlBar.show();

                    });
                });

                app.slider.on('afterChange', function(event, slick, currentSlide) {
                    var $current = $('.item-slide', app.slider).eq(currentSlide);
                    if ($current.find('video').length) {
                        var $video = $('video', $current);
                        $video[0].pause();
                    }
                });

            });

            //Put Slider dots after slide title
            /*function sliderDotsPosition(){
              var sliderDotsTop = $('.item-slide').first().find(">:first-child").height() + 44;
              $('.slick-dots').first().css('top', sliderDotsTop+'px');
            }*/
            /*

                  /*sliderDotsPosition();
                  window.addEventListener("resize", sliderDotsPosition);*/


        }

    };

    slicecc97.init();
});
