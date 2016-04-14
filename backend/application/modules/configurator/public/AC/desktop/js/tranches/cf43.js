'use strict';
ISO.moduleCreate('sliceCF43', function($el) {
    var $carroussel = $('.x-bloc_carroussel', $el);

    //Button "Add" into the toggle panel
    var $btnAdd = $('.cta-Upsell.selections.btn.btn-primary.btn-continued', $el);
    var isBtnAddCurrentlyProcessing = false;
    var cssClassBtnAddReverse = "reverseButtonSkin";
    var $expand = $('.expands.anchorFirstExpand .vignettes-toggle .vignette_bloc', $el);

    init();
    events();


    ////////////////////////////////////////////

    function init() {
        //Mobile Exception
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            cssClassBtnAddReverse = "reverseButtonSkinMobile";
            $btnAdd.addClass("buttonNormalSkinMobile");

            $btnAdd
                .bind("touchstart", function() {
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
        $carroussel.on('click', onClickCarroussel);
        $btnAdd.on('click', onClickBtnAdd);
    }

    //////////

    function onClickCarroussel(e) {
        if (isBtnAddCurrentlyProcessing)
            return;

        e.preventDefault();
        e.stopImmediatePropagation();
    }

    function onClickBtnAdd(event) {
        isBtnAddCurrentlyProcessing = true;

        var $element = $(event.target);

        var $checkBoxes = $element.closest("div.lastRowInToggle").find(".x-bloc_carroussel").find("input:checkbox");
        $checkBoxes.each(function() {
            if (!$(this).hasClass("checkByDefault")) {
                var $li = $(this).closest("li");
                if (!$li.hasClass("on"))
                    $li.addClass("on");
                else
                    $li.removeClass("on");
            }
        });

        var $button = $element.closest(".btn");

        if ($element.hasClass("ctaAddOff")) {
            $button.children().eq(0).addClass("ctaRemoved");
            $button.children().eq(1).removeClass("ctaRemoved");
            $button.removeClass(cssClassBtnAddReverse);

            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                $btnAdd
                    .bind("touchstart", function() {
                        $(this).addClass(cssClassBtnAddReverse);
                    });
            }
        } else {
            $button.children().eq(1).addClass("ctaRemoved");
            $button.children().eq(0).removeClass("ctaRemoved");
            $button.addClass(cssClassBtnAddReverse);

            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                $btnAdd
                    .bind("touchstart", function() {
                        $(this).removeClass(cssClassBtnAddReverse);
                    });
            }
        }

        isBtnAddCurrentlyProcessing = false;
    }
});
