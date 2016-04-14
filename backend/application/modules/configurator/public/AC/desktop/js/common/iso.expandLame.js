/* Toggle */
'use strict';
var config = config || {};
$(function () {
  config.expand = (function () {

    function init() {
      events();
    }

    function initSlick($el) {
      var settings = {
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        prevArrow: '<span class="arrow arrow--gray-big-left"></span>',
        nextArrow: '<span class="arrow arrow--gray-big-right"></span>'
      };
      var $slider = $el.find('.multiple-items.slideOn');
      $slider.slick(settings);
    }

    function destroySlick($el) {
      var $slider = $el.find('.multiple-items.slideOn.slick-initialized');
      $slider.slick('unslick');
    }

    function events() {
      $('.lame .lame_bloc .cta-details').on('click', toggleExpandLame);;
      $('.list-vignette-toggle').on('click', '.item-slide', selectSlide);
    }

    function toggleExpandLame() {
      var $btn = $(this);
      var $expand = $btn.parents('.expands');

      if ($btn.hasClass('open')) {
        closeExpandLame($expand);
      } else {
        openExpandLame($expand);
      }
    }

    function closeSiblingsExpandLame($expand) {
      var $siblings = $expand.parents('.lame').siblings('.lame').find('.cta-details');
      var results = [];
      $siblings.each(function (index, el) {
        var $el = $(el);
        if ($el.hasClass('open')) {
          toggleExpandLame.call($el);
        }
      })
    }

    function openExpandLame($el) {
      var $expandContainner = $el.find('.list-vignette-toggle > div');
      var ancre = null;
      config.saveTop = $(document).scrollTop();
      var isScrollDisabled = false;

      isScrollDisabled = ($el.find('.cta-details').hasClass('noScroll'));

      if (isScrollDisabled) {
        $el.find('.cta-details').removeClass('noScroll');
      }

       $expandContainner.slideDown(250, function(){
         initSlick($el);
        $el.find('.cta-details').addClass('open');
        $expandContainner.addClass('open');
        var height = $expandContainner.outerHeight(true) - 10;
        $expandContainner.find('.side').css({ 'height': height });
        ancre = $el.offset().top;

        if (!isScrollDisabled) {
          $('html, body').animate({
            scrollTop: ancre
          }, 500);
        }
      });

      closeSiblingsExpandLame($el);
    }

    function closeExpandLame($el) {
      var $expandContainner = $el.find('.list-vignette-toggle > div');
      $expandContainner.slideUp(250, function(){
        $el.find('.cta-details').removeClass('open');
        $expandContainner.removeClass('open');
        destroySlick($el);

        $('html, body').animate({
          scrollTop: config.saveTop
        }, 500);
      });
    }

    function selectSlide() {
      var $this = $(this);
      var clonesSelector = $this.data('for');

      $this.siblings().removeClass('on');
      $this.addClass('on').siblings('.' + clonesSelector).addClass('on');
      $this.parents('.cont-toggle').find('.pictures img').attr('src', $('img', $this).attr('data-target-src'));
    }

    return {
      init: init
    }
  })();

  config.expand.init();
});
