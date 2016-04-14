(function($, win, doc, ISO) {

  jQuery.fx.off = false;

  var app = {
    init: function() {
      var app = this;

      app.stickyInit();
    },
    /**
    * stickyInit() fonction qui initilalize les positions pour sticker des
    *              elements en haut de la page
    */
    stickyInit: function() {
      var app = this;

      var moreHeight = 0;
      $('.b-fixed').each(function() {

        if ($(this).prevAll('[data-posTop]')) {

          var $prevAll = $(this).prevAll('[data-posTop]');

          $prevAll.each(function(){
            moreHeight += $(this).outerHeight();
          });

        }

        $(this).attr('data-posTop', $(this).position().top - moreHeight);

      });
      app.stickySpy();

    },
    /**
    * stickySpy() spy du scroll pour stické ou destické des elements en haut
    *             de page
    */
    stickySpy: function() {
      $(window).on('scroll', function() {
        $('.b-fixed').each(function(i) {
          var $that = $('.b-fixed').eq(i);
          if($(window).scrollTop() < $that.attr('data-posTop')) {

            if($that.hasClass('fixed')) {
              $('.body').css({
                paddingTop: parseInt($('.body').css('paddingTop')) - $that.outerHeight(true)
              });
            }

            $that.removeClass('fixed');

          }else {

            if(!$that.hasClass('fixed')) {
              $('.body').css({
                paddingTop: parseInt($('.body').css('paddingTop')) + $that.outerHeight(true)
              });
            }
            $that.addClass('fixed');

          }
        });
      });
    }
  };
  document.addEventListener('DOMContentLoaded', function() {
    app.init();
  });

})(jQuery, window, document, window.ISO = window.ISO || {});