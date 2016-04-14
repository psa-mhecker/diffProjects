'use strict';
ISO.moduleCreate('lames', function($el) {
    var $monthly          = $el.find('#monthly'),
        $cash             = $el.find('#cash'),
        $comptantElement  = $el.find('.cash-element'),
        $mensuelElement   = $el.find('.mensuel-element'),
        $addComparatorBtn = $el.find('.addComparator'),
        $moreBtn          = $el.find('.toggle-finition>.tit-toggle'),
        $dataMode         = $el.data('mode'),
        $minHeight;

    if($cash.is(':checked') || $dataMode === 'cash') {
        $mensuelElement.hide();
        $comptantElement.show();
    }
    else{
        $mensuelElement.show();
        $comptantElement.hide();
    }

    $monthly.change(function(){
        if($(this).val() === 'monthly'){

          $mensuelElement.show();
          $comptantElement.hide();
        }
    });

    $cash.change(function(){
        if($(this).val() === 'cash'){
           $mensuelElement.hide();
           $comptantElement.show();
        }
    });



    var $this,
        $thisHeight,
        $top = false,
        manageHeight = function(){
          $minHeight = 300,
          $el        = $('.minHeight', $el).parents('.lame:eq(0)');

          for( var i = 0; i < $('.minHeight', $el).length; i++ ){
            $this = $('.minHeight:eq('+ i +')', $el);
            $thisHeight = $this.outerHeight(true);
            if( Math.abs(parseInt($this.css('top'))) > 0 && $thisHeight > $minHeight ){
              $top = Math.abs(parseInt($this.css('top')));
              $this.addClass('top');
            }

            $this.attr('data-height', $thisHeight);
            if( $thisHeight > $minHeight ) {
              $minHeight = $thisHeight;
            }
          }
          if( $top !== false){
            $('.minHeight:not(.top)', $el).animate({ minHeight: $minHeight-$top }, 250, function(){
              window.isoPopin.manageSize();
            });
            $this.animate({ height: $minHeight }, 250, function(){
              window.isoPopin.manageSize();
            });
          }
          else{
            $('.minHeight', $el).animate({ minHeight: $minHeight }, 250, function(){
              window.isoPopin.manageSize();
            });
          }

        };

    $el.ready(manageHeight);

    var timerResize ;
    $('.tit-toggle:not(.close)').isoToggle();
    $(window).resize(function(){
      clearTimeout(timerResize);
      var timeResize = setTimeout(function(){
        manageHeight();
      },450);
    });
});


/*
  Version dans la tranche cf53 - cf58

ISO.moduleCreate('configLame', function($el, param) {
    var $monthly         = $el.find('#monthly'),
        $cash            = $el.find('#cash'),
        $comptantElement = $el.find('.cash-element'),
        $mensuelElement  = $el.find('.mensuel-element'),
        $dataMode        = $el.data('mode');
    if($cash.is(':checked') || $dataMode === 'cash') {
        $mensuelElement.hide();
        $comptantElement.show();
    }
    else{
        $mensuelElement.show();
        $comptantElement.hide();
    }

    $monthly.change(function(){
        if($(this).val() == 'monthly'){

          $mensuelElement.show();
          $comptantElement.hide();
        }
    });

    $cash.change(function(){
        if($(this).val() == 'cash'){
           $mensuelElement.hide();
           $comptantElement.show();
        }
    });

    var $this,
        $thisHeight,
        $top = false,
        manageHeight = function(){
          $minHeight = 0,
          $el        = $('.minHeight', $el).parents('.configLame');

          for( var i = 0; i < $('.minHeight', $el).length; i++ ){
            $this = $('.minHeight:eq('+ i +')', $el);
            $thisHeight = $this.outerHeight(true);
            if( Math.abs(parseInt($this.css('top'))) > 0 && $thisHeight > $minHeight ){
              $top = Math.abs(parseInt($this.css('top')));
              $this.addClass('top');
            }

            $this.attr('data-height', $thisHeight);
            if( $thisHeight > $minHeight ) {
              $minHeight = $thisHeight;
            }
          }
          if( $top !== false){
            $('.minHeight:not(.top)', $el).animate({ minHeight: $minHeight-$top }, 250, function(){
              window.isoPopin.manageSize();
            });
            $this.animate({ height: $minHeight }, 250, function(){
              window.isoPopin.manageSize();
            });
          }
          else{
            $('.minHeight', $el).animate({ minHeight: $minHeight }, 250, function(){
              window.isoPopin.manageSize();
            });
          }

        };

    $(document).ready(function(){
      manageHeight();

      // PERMET LE CLIC SUR TOUT LE ASIDE
      $el.find('aside > div ').on('click', function(e){
        if ($(e.target).hasClass('isoPopinOpenLink')) { return }
        $(this).find('input').trigger('change');
      });

      // GESTION DE LA PRESELECTION
      if($el.data('ispreselection')){
        $el.find('input').trigger('change',false);
      }

    });

  /*  var timerResize ;
    $(window).resize(function(){
      clearTimeout(timerResize);
      timeResize = setTimeout(function(){
        manageHeight();
      },450);
    });

});*/
