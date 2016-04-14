'use strict';
ISO.moduleCreate('sliceDF43', function($el) {
  var $carroussel = $('.x-bloc_carroussel', $el);

  //Button "Add" into the toggle panel
  var $btnAdd = $('.cta.btn.selections.cta-lg.cta-Upsell.col-md-7', $el);
  var isBtnAddCurrentlyProcessing = false;
  var cssClassBtnAddReverse = "reverseButtonSkin";
  var $expand = $('.expands.anchorFirstExpand .vignettes-toggle .vignette_bloc', $el);


  init();
  events();


  ////////////////////////////////////////////

  function init() {
    //Mobile Exception
    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
      cssClassBtnAddReverse = "reverseButtonSkinMobile";
      $btnAdd.addClass("buttonNormalSkinMobile");

      $btnAdd
        .bind("touchstart", function () {
          $(this).addClass(cssClassBtnAddReverse);
        });
    }
    //One thumnail
    if ($expand.length == 1) {
      var $button = $expand.find('.cta-details');
      var $notification = $('.notification1', $el);
      var $otherExistingRim = $('.more-choice', $el);

      $button.addClass('noScroll');
      $button.trigger("click");
      $notification.css('display', 'none');
      $otherExistingRim.css('display', 'none');
    }
  }
  function events() {
    $carroussel.find(".oneEquipment").on('click', onClickCarroussel);
    $btnAdd.on('click', onClickBtnAdd);
  }

  //////////

  function onClickCarroussel(e){
    if (isBtnAddCurrentlyProcessing)
      return;

    e.preventDefault();
  }

  function onClickBtnAdd(event) {
    isBtnAddCurrentlyProcessing = true;

    var $element = $(event.target);



    var $button = $element.closest(".btn");

    if ($element.hasClass("ctaAddOff")) {
      $button.children().eq(0).addClass("ctaRemoved");
      $button.children().eq(1).removeClass("ctaRemoved");
      $button.removeClass("reverseButtonSkin");

      if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
        $btnAdd
          .bind("touchstart", function () {
            $(this).addClass(cssClassBtnAddReverse);
          });
      }
    } else {
      $button.children().eq(1).addClass("ctaRemoved");
      $button.children().eq(0).removeClass("ctaRemoved");
      $button.addClass("reverseButtonSkin");

      if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
        $btnAdd
          .bind("touchstart", function () {
            $(this).removeClass(cssClassBtnAddReverse);
          });
      }
    }

    var $checkBoxes = $element.parents().children(".x-bloc_carroussel").find("input:checkbox");
    $checkBoxes.each(function(){
      if (!$(this).hasClass("checkByDefault"))
        $(this).trigger("click");
    });

    isBtnAddCurrentlyProcessing = false;
  }
});
