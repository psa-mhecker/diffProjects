/**
 * Slice name: CF53 - Config AC Desktop FINITION
 * Coded by:
 */
 'use strict';
 ISO.moduleCreate('sliceCF53', function($el, param) {
  var slice53 = {
    $addComparatorBtn: $el.find('.addComparator'),
    comparatorView: $el.find('.addComparator'),
    $moreBtn: $el.find('.toggle-finition>.tit-toggle'),
    addedToComparator: [],
    currentApp: this,
    init: function (){
      var app = this;

      $el.find('.more-finitions').each(app.manageMoreFinition);

      $('.addComparator').click(function (e){
        if($(this).hasClass('disabled')){
          return;
        };
        var isAdded = $(this).data('added');
        if(!isAdded){
          app.addedToComparator.push({
                img: $(this).data('img-compare'),
                name: $(this).data('name'),
                id: $(this).attr('id'),
                referer: $(this)
            });
        }
        else{
          //app.addedToComparator.splice($(this).data('comparatorPlace')-1, 1);
        }

      });

      $el.find('.fin-radio').change(function (){
        var id = $(this).attr('id');
        var finId = id.replace('radio', '');
        $.publish('monteeGamme.changeSelectedItem', finId);
      });

      $('.monteeGamme .ancre-lames').click(function (e){
        var href = $(this).attr('href');
        if($(href).parents('.more-finitions').length > 0 && !$(href).parents('.more-finitions').hasClass('open')){
          $('.more-finitions .more-title').first().trigger('click');
        }
      });

      $('.toggle-finition.unique').find('.tit-toggle').addClass('no-anchor').trigger('click');

      app.fixComparatorContentWidth();
      app.fixComparatorEmptyCellHeight();
      $(window).resize(function(){
        app.fixComparatorContentWidth();
        app.fixComparatorEmptyCellHeight();
      });

      $el.find('.addComparator-toggle').each(function () {
        var $current = $(this);
        var $comparatorBtn = $current.find('.addComparator').first();
        var $comparatorBtnText = $comparatorBtn.find('span').first();
        var $comparatorView = $current.find('.comparator-views').first();
        var $closeComparator = $comparatorView.find('.close-comparator-view').first();


        $comparatorBtn.click(app.clickAddComparator);

        $closeComparator.click(function() {
          app.toogleComparator($current);
        });
      });
      var addedToComparator = function (e, element, place) {
        var $element = $(element.elt);
        $element.data('added', true);
        $element.data('comparatorPlace', element.place);

        $element.find('.removed').hide();
        $element.find('.added').show();

        slice53.updatecomparatorViews();

      };

      $.subscribe('configurator.addedToComparator', addedToComparator);

      var removeFromComparatorView = function (e, element) {
        var $element = $(element);
        var comparatorViewPlace = $element.data('comparatorPlace');
        $element.data('added', false);
        slice53.addedToComparator.splice(comparatorViewPlace, 1);
        slice53.updatecomparatorViews();
        $element.find('.added').hide();
        $element.find('.removed').show();
        app.fixComparatorEmptyCellHeight();
      };

      $.subscribe('configurator.removedFromComparator', removeFromComparatorView);

      $('.toggle-finition .tit-toggle').mouseup(function (e){
        app.fixComparatorContentWidth();
        app.fixComparatorEmptyCellHeight();
        var container = $(this);
        var comparatorZone = container.parents('.lame.finition').first().find(".addComparator-toggle").first();

        if (container.is(e.target) || container.has(e.target)){
          comparatorZone.find('.close-comparator-view').first().trigger('click');
        }
      });



      $.subscribe('comparator.updated', function (e, comparatorArray, comparatorArray2, comparatorArray3){
        var comparatorTableData = [comparatorArray, comparatorArray2, comparatorArray3];
          for (var i = 0; i < 3; i++) {
              if(typeof app.addedToComparator[i] == 'undefined' && typeof comparatorTableData[i] != 'undefined'){
                  $('#add-comparator-'+comparatorTableData[i]).data('cancelPublish', true).trigger('click');
              }
              if(typeof app.addedToComparator[i] != 'undefined' && typeof comparatorTableData[i] == 'undefined'){
                  $('#'+app.addedToComparator[i].id).data('cancelPublish', true).trigger('click');
              }
          };
      });
    },

    updatecomparatorViews: function (){
      var app = this;
      if(app.addedToComparator.length >= 3){
        setTimeout(app.disableAllAddComparator, 100);
      }
      else{
        setTimeout(app.activeAllAddComparator, 100);
      }



      var $comparatorViews = $el.find('.comparator-views');
      for (var i = 0; i < 3; i++) {
        if(app.addedToComparator[i] != undefined){
          $comparatorViews.each(function (){
            $($(this).find('li.col-md-4')[i]).html('<img src="'+app.addedToComparator[i].img+'" alt=""><div class="close" data-place="'+i+'" data-remove="'+app.addedToComparator[i].id+'"></div>');
          });
          app.addedToComparator[i].referer.data('comparatorPlace', i);
        }
        else{
          $comparatorViews.each(function (){
            var $elt = $($(this).find('li.col-md-4')[i]);
            $elt.html('<div class="empty"></div>');
          });
        }
      }

      $el.find('.comparator-views .close').click(function (e){
        e.preventDefault();
        var $btn = $('#'+$(e.target).data('remove'));
        $btn.data('comparatorPlace', $(e.target).data('place'));
        $btn.click();
      });
    },

    toogleComparator: function (elt, open){
      var app = this;
      var $current = elt;
      var $comparatorView = elt.find('.comparator-views').first();

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
          else{
            $current.parents('.lame.finition').first().find('.toggle-finition').first().find('.tit-toggle').addClass('noScroll').trigger('click').removeClass('noScroll');
            $current.parents('.lame.finition').first().stop().animate({marginBottom:$comparatorView.outerHeight()+30}, 300);
          }

          app.fixComparatorEmptyCellHeight();
        }
        else{
          $current.parents('.lame.finition').first().stop().animate({marginBottom:''}, 300);
          $current.removeClass('close');
        }
      });
    },

    clickAddComparator: function(e) {
      if($(this).hasClass('disabled')){
        return;
      }
      var $current = $(this).parent('.addComparator-toggle').first();

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
        slice53.toogleComparator($current,false);

        return;
      }
      if(!cancelPublish){
          $.publish('configurator.addToCompare', this);
      }
      else {
          $(this).data('cancelPublish', '');
          $.publish('configurator.addedToComparator', {elt: this, place: 0});
      }
      slice53.toogleComparator($current, true);
      slice53.fixComparatorContentWidth();
    },

    manageMoreFinition: function (e){
      var $zone    = $(this);
      var $btn     = $(this).find('.more-title').first();
      var $content = $(this).find('.more-content').first();

      $btn.click(function (e){
        e.preventDefault();
        if($zone.hasClass('open')){
          $zone.removeClass('open');
        }
        else{
          $zone.addClass('open');
        }
        $content.slideToggle();
      });
    },

    activeAllAddComparator: function (){
      $el.find('.addComparator.disabled').removeClass('disabled');
    },

    disableAllAddComparator: function (){
      $el.find('.addComparator').each(function (){
        if(!$(this).data('added')){
          $(this).addClass('disabled');
        }
      });
    },

    fixComparatorEmptyCellHeight: function() {
      $('.comparator-views .empty').each(function (){
        $(this).outerHeight($(this).outerWidth());
      });
    },

    fixComparatorContentWidth: function() {
      var app = this;
      app.$addComparatorBtn.each(function() {
        var $parent        = $(this).parents('.lame.finition').first();
        var $toggleContent = $parent.find('.comparator-views').first();

        var parentWidth = $parent.outerWidth();
        if($parent.hasClass('lame-finition')){
          parentWidth = parentWidth-21;
        }

        $toggleContent.outerWidth(parentWidth);
      });

      app.$moreBtn.each(function() {
        var $parent        = $(this).parents('.lame.finition').first();
        var $toggleContent = $parent.find('.cont-toggle').first();

        var parentWidth = $parent.outerWidth()-32;

        $toggleContent.outerWidth(parentWidth);
      });
    },

    removedFromComparatorTable: function (e, selectValue) {
      var $lameRemoved = $('#lame'+selectValue).first();
      slice53.updatecomparatorViews();
    },
    addedFromComparatorTable: function (e, element) {
      var $element = $('#lame'+element.value).first().find('.addComparator').first();

        //$lameRemoved.find('.addComparator').first().trigger('click');



        $element.data('added', true);
        $element.data('comparatorPlace', element.place);
        $element.find('.removed').fadeOut(0);
        $element.find('.added').fadeIn(0);


        slice53.updatecomparatorViews();
      }
    };


    slice53.init();
    $.subscribe('configurator.addedFromComparator', slice53.addedFromComparatorTable);
    $.subscribe('configurator.deleteFromComparator', slice53.removedFromComparatorTable);
  });
