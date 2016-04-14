'use strict';
ISO.moduleCreate('sliceDF90', function ($el) {
  //Button "Add" into the toggle panel
  var $btnAdd = $('.cta.btn.selections.cta-lg.cta-Upsell.col-md-7', $el);
  var isBtnAddCurrentlyProcessing = false;
  var cssClassBtnAddReverse = "reverseButtonSkin";
  var $expand = $('.expands .vignettes-toggle .vignette_bloc', $el);

  function init() {
    managePack();
    manageExpand();
    events();
    //Mobile Exception
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
      cssClassBtnAddReverse = "reverseButtonSkinMobile";
      $btnAdd.addClass("buttonNormalSkinMobile");

      $btnAdd
        .bind("touchstart", function () {
          $(this).addClass(cssClassBtnAddReverse);
        });
    }
    if ($expand.length == 1) {
      var $button = $expand.find('.cta-details');

      $button.addClass('noScroll');
      $button.trigger("click");

    }
  }

  function manageExpand() {
    var $checkboxArray = $('.lame, .vignette_bloc');
    $checkboxArray.each(function (i, el) {
      if ($('input:checkbox:checked', el).length > 0 || $('input:radio:checked', el).length > 0) {
        var index = $(this).parents('.col-md-3').index();
        var thisToggle = $(this).parents('.vignettes-toggle').find('.list-vignette-toggle > div:eq(' + index + ') > .cont-toggle');
        thisToggle.addClass('checked');
      }

    });
    $('.selections').on('click', function () {
      var index = $(this).parents('.col-md-3').index();
      var thisToggle = $(this).parents('.vignettes-toggle').find('.list-vignette-toggle > div:eq(' + index + ') > .cont-toggle');
      thisToggle.toggleClass('checked');
      thisToggle.removeClass('selected');
    });
  }

  function events() {
    $btnAdd.on('click', onClickBtnAdd);
  }

  function onClickBtnAdd(event) {
    isBtnAddCurrentlyProcessing = true;

    var $element = $(event.target);
    var $button = $element.closest(".btn");

    if ($element.hasClass("ctaAddOff")) {
      $button.children().eq(0).addClass("ctaRemoved");
      $button.children().eq(1).removeClass("ctaRemoved");
      $button.removeClass("reverseButtonSkin");

      if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        $btnAdd
          .bind("touchstart", function () {
            $(this).addClass(cssClassBtnAddReverse);
          });
      }
    } else {
      $button.children().eq(1).addClass("ctaRemoved");
      $button.children().eq(0).removeClass("ctaRemoved");
      $button.addClass("reverseButtonSkin");

      if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        $btnAdd
          .bind("touchstart", function () {
            $(this).removeClass(cssClassBtnAddReverse);
          });
      }
    }

    isBtnAddCurrentlyProcessing = false;
  }

  function managePack() {
    $('.ctaAdd').on('click', function () {
      $(this).parents('.cont-toggle').find('.oneEquipment').addClass('on');
    });
    $('.ctaAddOff').on('click', function () {
      $(this).parents('.cont-toggle').find('.oneEquipment').removeClass('on');
    });
  }

  init();
});
