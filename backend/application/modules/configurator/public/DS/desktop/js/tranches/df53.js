/**
 * Slice name: DF56 - Motorisations
 * Coded by:
 */

'use strict';
ISO.moduleCreate('sliceDF53', function($el) {

    var comparatorItems = [];

    var updatecomparatorViews = function (){
        if(comparatorItems.length >= 3){
          setTimeout(disableAllAddComparator, 100);
        }
        else{
          setTimeout(activeAllAddComparator, 100);
        }
        var $comparatorViews = $el.find('.comparator-views');
        for (var i = 0; i < 3; i++) {
            if(typeof comparatorItems[i] != 'undefined'){
                $comparatorViews.each(function (){
                    $($(this).find('div.col-md-4')[i]).find('.item-comparator').first().html('<img src="'+comparatorItems[i].img+'" alt="">'+comparatorItems[i].name+'<div class="close" data-place="'+i+'" data-remove="'+comparatorItems[i].id+'"></div>');
                });
                comparatorItems[i].referer.data('comparatorPlace', i);
            }
            else{
                $comparatorViews.each(function (){
                    $($(this).find('div.col-md-4')[i]).find('.item-comparator').first().html('<img src="../../../img/comparator/empty.jpg" alt=""><div class="name"></div>');
                });
            }
        }

        $el.find('.comparator-views .close').click(function (e){
            e.preventDefault();
            $('#'+$(e.target).data('remove')).data('comparatorPlace', $(e.target).data('place'));
            $('#'+$(e.target).data('remove')).click();
        });
        $el.find('a.close-comparator').click(function (e){
            e.preventDefault();
            $(this).parents('.expand-content').first().stop().slideToggle();
            $(this).parents('.expand-addComparator').first().removeClass('displayOn');
            $(this).parents('.addComparator-toggle').first().find('.cta-addComparator').first().removeClass('open');
        });
    };
    var clickOnComparatorAdd = function (e){
        if($(this).hasClass('disabled')){
          return;
        }
        if($(this).hasClass('toClose')){
            $(this).removeClass('toClose');
            return;
        }

        var compareValue   = $(this).data('compare');
        var addedToCompare = $(this).data('added');
        var comparePlace   = $(this).data('comparatorPlace');
        var cancelPublish   = $(this).data('cancelPublish');

        if(addedToCompare){
            if(!cancelPublish){
                $.publish('configurator.removeFromComparator', this);
            }
            else {
                $(this).data('cancelPublish', '');
                $.publish('configurator.removedFromComparator', this);
            }
            return;
        }
        else{
            var $readMoreCta = $(this).parents('.expand-btn').first().find('.cta-readmore').first();
            comparatorItems.push({
                img: $(this).data('img-compare'),
                name: $(this).data('name'),
                id: $(this).attr('id'),
                referer: $(this)
            });
            $el.find('.cta-addComparator').each(function (){
                if($(this).hasClass('open')){
                    $(this).addClass('toClose').trigger('click');
                }
            });

            $(this).removeClass('border-bottom');

            if($(this).hasClass('open')){
                $readMoreCta.removeClass('border-bottom');
            }
            else{
                $readMoreCta.addClass('border-bottom');
            }
        }
        if(!cancelPublish){
            $.publish('configurator.addToCompare', this);
        }
        else {
            $(this).data('cancelPublish', '');
            $.publish('configurator.addedToComparator', {elt: this, place: 0});
        }
    };

    var activeAllAddComparator = function (){
      $el.find('.cta-addComparator.disabled').removeClass('disabled');
    };

    var disableAllAddComparator = function (){
      $el.find('.cta-addComparator').each(function (){
        if(!$(this).data('added')){
          $(this).addClass('disabled');
        }
      });
    };

    $('.ancre', $el).scroller();


    $('div.cta-readmore').click(function (e){
        var $addComparatorCta = $(this).parents('.expand-btn').first().find('.cta-addComparator').first();
        var $this = $(this);

        $(this).removeClass('border-bottom');

        if($(this).hasClass('open')){
            $addComparatorCta.removeClass('border-bottom');
        }
        else{
            $el.find('.cta-readmore').each(function (){
                if($(this).hasClass('open')){
                    $(this).trigger('click');
                }
            });
            setTimeout(function() {
                $('html, body').animate({
                    scrollTop: $this.offset().top
                }, 500);
            }, 400);
            $addComparatorCta.addClass('border-bottom');
        }
    });

    $el.find('.addComparator-toggle').each(function () {
      var $current = $(this);
      var $comparatorBtn = $current.find('.cta-addComparator').first();
      var $comparatorBtnText = $comparatorBtn.find('span').first();
      var $comparatorView = $current.find('.comparator-views').first();
      var $closeComparator = $comparatorView.find('.close-comparator-view').first();


      /*var toggleComparator = function (open){
        if(open){
            $('.addComparator-toggle').each(function (){
                var $current = $(this);
                if($current.hasClass('close')){
                    $current.find('.comparator-views').slideToggle(700);
                    if(!$current.parents('.lame.finition').first().find('.toggle-finition').first().hasClass('close')){
                        $current.parents('.lame.finition').first().stop().animate({marginBottom:''}, 300);
                    }
                    $current.removeClass('close');
                }
            });
        }
        else{
            if(!$current.hasClass('close')){
                return;
            }
        }
        $comparatorView.stop().slideToggle(300, function (){
            if(!$current.hasClass('close')){
                $current.addClass('close');
                if(!$current.parents('.lame.finition').first().find('.toggle-finition').first().hasClass('close')){
                    $current.parents('.lame.finition').first().stop().animate({marginBottom:$comparatorView.outerHeight()+30}, 300);
                }

                //fixComparatorEmptyCellHeight();
            }
            else{
                $current.parents('.lame.finition').first().stop().animate({marginBottom:''}, 300);
                $current.removeClass('close');
            }
        });
      };*/

      $comparatorBtn.click(clickOnComparatorAdd);

      $closeComparator.click(function() {
        //toggleComparator();
      });
    });
    var addedToComparator = function (e, element, place) {
        e.stopImmediatePropagation();
        var $element = $(element.elt);
        $element.data('added', true);
        $element.data('comparatorPlace', element.place);

        $element.find('.removed').hide();
        $element.find('.added').show();

        updatecomparatorViews();
    };
    $.subscribe('configurator.addedToComparator', addedToComparator);

    var removeFromComparatorView = function (e, element) {
        e.stopImmediatePropagation();
        var $element = $(element);
        $element.data('added', false);
        comparatorItems.splice($element.data('comparatorPlace'), 1);
        updatecomparatorViews();
        $element.find('.added').hide();
        $element.find('.removed').show();
        //fixComparatorEmptyCellHeight();
    };

    $.subscribe('configurator.removedFromComparator', removeFromComparatorView);

    $el.find('.more-finitions').each(function (){
        var $zone    = $(this);
        var $btn     = $(this).find('.more-title').first();
        var $content = $(this).find('.more-content').first();

        $btn.click(function (e){
          e.preventDefault();
          if($zone.hasClass('ouvert')){
            $zone.removeClass('ouvert');
          }
          else{
            $zone.addClass('ouvert');
          }
          $content.slideToggle();
        });
    });

    $.subscribe('comparator.updated', function (e, comparatorArray, comparatorArray2, comparatorArray3){
        var comparatorTableData = [comparatorArray, comparatorArray2, comparatorArray3];

        for (var i = 0; i < 3; i++) {
            if(typeof comparatorItems[i] == 'undefined' && typeof comparatorTableData[i] != 'undefined'){
                $('#add-comparator-'+comparatorTableData[i]).data('cancelPublish', true).trigger('click');
            }
            if(typeof comparatorItems[i] != 'undefined' && typeof comparatorTableData[i] == 'undefined'){
                $('#'+comparatorItems[i].id).data('cancelPublish', true).trigger('click');
            }
        };
    });

});
