/* Toggle */
'use strict';
var ModuleExpand = {
  $body: $('.body'),
  $html: $('html'),
  open: false,
  swiperToggle: null,
  settingsSlick: {
    infinite: true,
    slidesToShow: 3,
    slidesToScroll: 1,
    prevArrow: '<span class="arrow arrow--gray-big-left"></span>',
    nextArrow: '<span class="arrow arrow--gray-big-right"></span>'
  },

  init: function () {
    var app = this;
    app.events();
    app.otherSelect();
    app.expandVignette();
  },

  /**
   * events() manage event
   */
  events: function () {
    var app = this;
    var $sliderEl;
    var $window = $(window);

    app.$html.stop().on('click', '.cta-expand-1', function (event) {
      event.preventDefault();
      app.expand($(this), $(this));
    });

    $('.list-vignette-toggle > div .cont-toggle').on('click', '.item-slide', function () {
      var $this = $(this);
      var selector = $this.data('for');
      var index = $this.parents('.open').index();
      var $vignetteSrc = $this.parents('.list-vignette-toggle').prev().children().eq(index);

      if (!$vignetteSrc.hasClass('monoton')) {
        $('.vignette_bloc').removeClass('selected');
        $vignetteSrc.find('.input-radio .selections').trigger('click');
        $vignetteSrc.find('.vignette_bloc').addClass('selected');
      }

      if ($vignetteSrc.find('.color-list').length) {
        $vignetteSrc.find('.color-list li.' + selector).trigger('click');
      } else {
        app.update($this);
      }
    });

    $('.color-list').on('click', 'li', function (e) {
      e.stopPropagation();
      var $index = $(this).index(),
        $par = $(this).parents('.color-list:eq(0)'),
        $parents = $(this).parents('.row:eq(0)'),
        $parent = $(this).parents('.vignette:eq(0)'),
        $ind = $parent.index();

      var selector = $(this).data('for');

      if ($('.cta-details', $parent).hasClass('open') === false) {
        $('.cta-details', $parent).trigger('click');
      }

      if ($(this).parents('.selected:eq(0)').length) {
        $('.color-list li.on').removeClass('on');
        $(this).addClass('on');
      }

      var $sliderEl = $('+ .list-vignette-toggle > div:eq(' + $ind + ') .cont-toggle .item-slide.' + selector, $parents);
      if ($('+ .cta-details', $par).hasClass('open')) {
        app.update($sliderEl);
      }
      else {
        setTimeout(function () {
          app.update($sliderEl);
        }, 1000);
      }
    });

    function setHeightSide() {
      var height = null;

      height = $('.list-vignette-toggle .open .cont-toggle .row .col-md-8').outerHeight(true);
      $('.list-vignette-toggle .open .cont-toggle .row').find('.side').css({'height': height});
    }

    $window.on('resize', setHeightSide);
    $window.on('orientationchange', setHeightSide);
  },

  update: function ($el) {
    var $index = $el.data('for'),
      $toggle = $el.parents('.cont-toggle:eq(0)').data('index-toggle');
    var selector = $el.data('for');

    $el.parent().find('li.on, li[data-actif]').removeAttr('data-actif').removeClass('on');
    $el.attr('data-actif', 'on');
    $el.addClass('on');

    $el.siblings('.' + selector).addClass('on');
    $el.parents('.cont-toggle').find('.pictures img').attr('src', $('img', $el).attr('data-target-src'));
  },
  /**
   * expand() manage open/close
   * @param {Element} html
   */
  expand: function ($html, thatBtn) {
    var app = this;
    var $lame = $html.parent().parent().parent();
    var $content = $html.parents('.expand-btn').next('.expand-content');
    var saveTop;

    if ($html.hasClass('open')) {

      $content.slideUp(function () {
        $('.cta-readmore, .cta-addComparator', $lame).removeClass('open');
        $('.expand-readmore, .expand-addComparator', $lame).removeClass('displayOn');
      });


      $('html, body').animate({
        scrollTop: saveTop
      }, 500);


      app.open = false;

    } else {
      if ($html.hasClass('cta-readmore')) {
        $('.cta-addComparator', $lame).removeClass('open');
        $('.expand-addComparator', $lame).removeClass('displayOn');

        $('.cta-readmore', $lame).addClass('open');
        $('.expand-readmore', $lame).addClass('displayOn');
      } else {
        $('.cta-readmore', $lame).removeClass('open');
        $('.expand-readmore', $lame).removeClass('displayOn');

        $('.cta-addComparator', $lame).addClass('open');
        $('.expand-addComparator', $lame).addClass('displayOn');
      }

      $content.slideDown();

      // START - Fixing de la lame sur le top au moment de l'ouverture
      var ancre = thatBtn.offset().top;

      if ($('.open', thatBtn.parent()).length) {

        saveTop = $(document).scrollTop();

      }

      $('html, body').animate({
        scrollTop: ancre
      }, 500);

// END - Fixing de la lame sur le top au moment de l'ouverture

    }
  },

  otherSelect: function () {
    $('.more-choice-head').stop().click(function () {
      var ancre = null,
        $that = $(this);

      $(this).toggleClass('open');
      $(this).parent().find('.more-choice-content').slideToggle();

      ancre = $that.offset().top;

      $('html, body').animate({
        scrollTop: ancre
      }, 500);

    });
  },

  expandVignette: function () {
    var app = this;
    var saveTop;
    var isScrollDisabled = false;

    $('.vignette_bloc .cta-details').stop().click(function () {

      var thatBtn = $(this),
        $that = thatBtn.parents('.vignette').eq(0),
        el = $that.parent().next().find('> div'),
        eleq = $that.index(),
        $sel = thatBtn.parents('.expands:eq(0)'),
        height = null;

      isScrollDisabled = (thatBtn.hasClass('noScroll'));
      if (isScrollDisabled) {
        thatBtn.removeClass('noScroll');
      }

      var ancre = null;

      if (thatBtn.hasClass('open')) {

        el.eq(eleq).slideUp(250, function () {
          if (el.eq(eleq).parents('.vignettes-toggle').hasClass('.active')) {
            $('.multiple-items.slideOn.slick-initialized').slick('unslick');
          }
          thatBtn.removeClass('open');
          el.eq(eleq).removeClass('open');
        });

        $('html, body').animate({
          scrollTop: saveTop
        }, 500);


      } else {
        if ($('.vignette_bloc .cta-details.open', $sel).length) {

          $('.list-vignette-toggle > div.open', $sel).slideUp(250, function () {
            $('.list-vignette-toggle > div.open', $sel).removeClass('open');
            $('.vignette_bloc .cta-details.open', $sel).removeClass('open');
            thatBtn.addClass('open');
            $('.multiple-items.slideOn.slick-initialized', $sel).slick('unslick');

            el.eq(eleq).slideDown(function () {
              el.eq(eleq).addClass('open');
              el.eq(eleq).parents('.vignettes-toggle').addClass('active');
              el.eq(eleq).find('.multiple-items.slideOn').slick(app.settingsSlick);
              height = $('.list-vignette-toggle .open .cont-toggle .row').outerHeight(true);
              $('.list-vignette-toggle .open .cont-toggle .row').find('.side').css({'height': height});
            });

            ancre = thatBtn.offset().top;

            $('html, body').animate({
                scrollTop: ancre
              }, 500);

          });

        } else {

          thatBtn.addClass('open');

          saveTop = $(document).scrollTop();

          ancre = thatBtn.offset().top;

          if (!isScrollDisabled) {
            $('html, body').animate({
              scrollTop: ancre
            }, 500);
          }

          $('.multiple-items.slideOn.slick-initialized', $sel).slick('unslick');

          el.eq(eleq).slideDown(250, function () {
            el.eq(eleq).addClass('open');
            el.eq(eleq).parents('.vignettes-toggle').addClass('active');
            height = $('.list-vignette-toggle .open .cont-toggle .row').outerHeight(true);
            $('.list-vignette-toggle .open .cont-toggle .row').find('.side').css({'height': height});
          });

          if (el.eq(eleq).find('.multiple-items.slideOn').length && !el.eq(eleq).find('.multiple-items.slideOn').hasClass('slick-initialized')) {
            el.eq(eleq).find('.multiple-items.slideOn').slick(app.settingsSlick);
          }

        }

      }
      $('.act').removeClass('act');

    });
  }
};

$(document).ready(function () {
  ModuleExpand.init();
  $.subscribe("configurator.stepsLoaded", function () {
    ModuleExpand.init();
  });
});
