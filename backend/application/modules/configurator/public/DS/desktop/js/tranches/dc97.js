/* DC97 */
ISO.moduleCreate('slicedc97', function($el) {
  'use strict';

  var slicedc97 = {
    settingsSlick: {
      infinite: true,
      slidesToShow: 1,
      dots: true,
      autoplay: false,
      autoplaySpeed: 3000,
      slidesToScroll: 1,
      prevArrow: '<div class="arrow arrow--2-big-left"><span></span></div>',
      nextArrow: '<div class="arrow arrow--2-big-right"><span></span></div>'
    },
    autoplay: false,
    slider: null,

    init: function(){
      var app = this;

      if ($('.multiple-items', $el).length) {
        app.initSwiper();
      }
    },

    initSwiper: function(){
      var app = this;
          app.slider = $('.multiple-items', $el);

      if (app.slider.hasClass('auto')) {

        /**
        * @todo FINIR L'AUTO play
        * Il faut debuger l'autoslide avec la video :
        * - Stopper autoslide quand video en lecture (ligne 69)
        * - Renclancher autoplay quand video event === ended (ligne 79)
        * ---------------------------------------------------------------------
        * - Gerer l'avancement de la video au touch sur tablette
        */

        // app.autoplay = true;

        app.settingsSlick.autoplay = app.autoplay;

        app.slider.slick(app.settingsSlick);

        // app.slick = app.slider.slick('getSlick');

        // app.slider.slick('slickPause');
        app.slider.on('swipe', function(){
          app.autoplay = false;

          // app.slicks.slick('slickPause');
        });

        app.slider.slick('getSlick').$dots.on('click', 'li', function(){
          app.autoplay = false;
          app.slider.slick('slickPause');
        });

        app.slider.slick('getSlick').$slider.on('click', '.arrow', function(){
          app.autoplay = false;
          app.slider.slick('slickPause');
        });

      }else{
        app.slider.slick(app.settingsSlick);
      }

      $('video').each(function(i, el){
        var vjs = videojs($(el)[0], {}, function(){

          this.on('play', function() {
            app.slider.slick('slickPause');
            this.posterImage.hide();
          });

          this.on('pause', function() {
            this.posterImage.hide();
          });

          this.on('ended', function() {

            if (app.autoplay === true) {
              app.slider.slick('slickPlay');
            }

            this.controlBar.show();

          });
        });

        app.slider.on('afterChange', function(event, slick, currentSlide){
          var $current = $('.item-slide', app.slider).eq(currentSlide);
          if ($current.find('video').length) {
            var $video = $('video', $current);
            $video[0].pause();
          }
        });

      });

    }

  };

  slicedc97.init();
});
