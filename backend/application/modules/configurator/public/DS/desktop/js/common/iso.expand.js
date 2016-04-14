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
    prevArrow: '<span class="arrow arrow--2-big-left"></span>',
    nextArrow: '<span class="arrow arrow--2-big-right"></span>'
  },
  settingsSlickVertival: {
    infinite: true,
    vertical: true,
    slidesToShow: 3,
    slidesToScroll: 1,
    verticalSwiping: true,
    prevArrow: '<span class="arrow arrow--2-big-left"></span>',
    nextArrow: '<span class="arrow arrow--2-big-right"></span>'
  },
  saveTop: undefined,

  init: function() {
    var app = this;
    app.events();
    app.otherSelect();
    app.expandVignette();
  },

  /**
   * events() manage event
   */
  events: function() {
    var app = this;

    app.$html.stop().on('click', '.cta-expand-1', function(event) {
      event.preventDefault();
      var $this = $(this);
      var isAddComparator = $(this).hasClass('cta-addComparator');
      if(isAddComparator && !$this.data('added') && !$this.hasClass('open')){
        return;
      }
      app.expand($(this), $(this));
    });

    $('.list-vignette-toggle > div .cont-toggle').on('click', '.item-slide', function() {
      var $this = $(this);
      var selector = $this.data('for');
      var index = $this.parents('.open').index();
      var $vignetteSrc = $this.parents('.list-vignette-toggle').prev().children().eq(index);

      if($vignetteSrc.find('.color-list').length) {
        $vignetteSrc.find('.color-list li.' + selector).trigger('click');
      }else{
        app.update($this);
      }

    });

    $('.color-list').on('click', 'li', function(e) {
      e.stopPropagation();
      var $index = $(this).index(),
        $par = $(this).parents('.color-list:eq(0)'),
        $parents = $(this).parents('.row:eq(0)'),
        $parent = $(this).parents('.col-md-3:eq(0)'),
        $ind = $parent.index();

      if ($('.cta-details', $parent).hasClass('open') === false) {
        $('.cta-details', $parent).trigger('click');
      }
      if ($(this).parents('.selected:eq(0)').length) {
        $('.color-list li.on').removeClass('on');
        $(this).addClass('on');
      }
      var selector = $(this).data('for');
      var $sliderEl = $('+ .list-vignette-toggle > div:eq(' + $ind + ') .cont-toggle .item-slide.' + selector, $parents);
      if( $('+ .cta-details', $par).hasClass('open') ){
        app.update($sliderEl);
      }
      else{
        setTimeout(function(){
          app.update($sliderEl);
        }, 1000);
      }
    });

  },

  update: function ($el) {
    var $index = $el.data('slick-index'),
    $toggle = $el.parents('.cont-toggle:eq(0)').data('index-toggle');

    $el.parent().find('li.on, li[data-actif]').removeAttr('data-actif').removeClass('on');
    $el.attr('data-actif', 'on');
    $el.addClass('on');
    $el.parents('.cont-toggle').find('.pictures img').attr('src', $('img', $el).attr('data-target-src'));
  },
  /**
   * expand() manage open/close
   * @param {Element} html
   */
  expand: function($html, thatBtn) {
    var app = this;
    var $lame = $html.parent().parent().parent();
    var $content = $html.parents('.expand-btn').next('.expand-content');

    if ($html.hasClass('open')) {

      $content.slideUp(function() {
        $('.cta-readmore, .cta-addComparator', $lame).removeClass('open');
        $('.expand-readmore, .expand-addComparator', $lame).removeClass('displayOn');
      });
      if(!thatBtn.hasClass('noScroll')){
        $('html, body').animate({
          scrollTop: app.saveTop
        }, 500);
      }

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

      var ancre = thatBtn.offset().top;

      if ($('.open', thatBtn.parent()).length) {
        app.saveTop = $(document).scrollTop();
      }

      if(!thatBtn.hasClass('noScroll')){
        $('html, body').animate({
          scrollTop: ancre
        }, 500);
      }
    }
  },

  otherSelect: function() {
    $('.more-choice-head').stop().click(function() {
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

  expandVignette: function() {
    var app = this;
    var saveTop;
    var isScrollDisabled = false;

    $('.vignette_bloc .cta-details').stop().click(function() {

      var thatBtn = $(this),
        $that = thatBtn.parents('.col-md-3').eq(0),
        el = $that.parent().next().find('> div'),
        eleq = $that.index(),
        $sel = thatBtn.parents('.expands:eq(0)');

      isScrollDisabled = (thatBtn.hasClass('noScroll'));
      if (isScrollDisabled) {
        thatBtn.removeClass('noScroll');
      }

      var ancre = null;

      if (thatBtn.hasClass('open')) {

        el.eq(eleq).slideUp(500, function(){
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

          $('.list-vignette-toggle > div.open', $sel).slideUp(500, function(){
            $('.list-vignette-toggle > div.open', $sel).removeClass('open');
            $('.vignette_bloc .cta-details.open', $sel).removeClass('open');
            thatBtn.addClass('open');
            $('.multiple-items.slideOn.slick-initialized', $sel).slick('unslick');

            el.eq(eleq).slideDown(function(){
              el.eq(eleq).addClass('open');
              el.eq(eleq).parents('.vignettes-toggle').addClass('active');

              if ($('.cont-toggle').hasClass('slickVertical')) {
                el.eq(eleq).find('.multiple-items.slideOn').slick(app.settingsSlickVertival);
                el.eq(eleq).find('.multiple-items.slideOn').slick('setPosition');
              }else {
                el.eq(eleq).find('.multiple-items.slideOn').slick(app.settingsSlick);
              }
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

          el.eq(eleq).slideDown(500, function() {
            el.eq(eleq).addClass('open');
            el.eq(eleq).parents('.vignettes-toggle').addClass('active');
          });

          if (el.eq(eleq).find('.multiple-items.slideOn').length && !el.eq(eleq).find('.multiple-items.slideOn').hasClass('slick-initialized')) {

            if ($('.cont-toggle').hasClass('slickVertical')) {
              el.eq(eleq).find('.multiple-items.slideOn').slick(app.settingsSlickVertival);
              el.eq(eleq).find('.multiple-items.slideOn').slick('setPosition');
            }else {
              el.eq(eleq).find('.multiple-items.slideOn').slick(app.settingsSlick);
            }
          }


        }

      }
      $('.act').removeClass('act');

    });

  }
};

$(function() {
  ModuleExpand.init();
  $.subscribe('configurator.stepsLoaded', function () {
      ModuleExpand.init();
    })
});
