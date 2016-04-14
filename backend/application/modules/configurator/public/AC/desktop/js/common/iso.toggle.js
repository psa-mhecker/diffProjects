(function($){
  'use strict';
  $.fn.isoToggle = function(options) {
    return this.each(function() {
      var defaults = {
            content  : $('body'),
            toggle   : $('.tit-toggle:not(.close)'),
            count    : null,
            index    : 0,
            length   : 0,
            position : false
          }, opts = $.extend(defaults, options);

      function toggleHeight(){
        $('.toggle').each(function(){
          $(' > .cont-toggle', this).css({
            height: $(' > .cont-toggle > div', this).outerHeight(true)
          });
        });
      }

	  var $closing = false;
	  var $lastTop = -1;
	  var $lastDom = "";

      defaults.toggle.on('click', function(e){
    		e.preventDefault();
    		e.stopImmediatePropagation();

		//specific to infobubble
		if($('.infobulle').length)
		{
			$('.infobulle').removeClass('open');
			$('.info').removeClass('selected');
		}
		//end specific
		
		var no_anchor = false;
		if($(this).hasClass("no-anchor"))
		{
			no_anchor = true;
			$(this).removeClass("no-anchor");
		}

        $(this).addClass('click');
        $('.tit-toggle:not(.click).close:not(.expend)').parent().removeClass('close').find('> .cont-toggle').each(function() {
			$(this).css('height', '');
		});
        $('.tit-toggle:not(.click).close:not(.expend)').removeClass('close');

        var $parent   = $(this).toggleClass('close').parent('.toggle'),
            $toggle   = $('+ .cont-toggle', this),
            $parents  = $parent.parents('.box-toggle'),
            $top      = 0,
            $height   = $parent.hasClass('close') ? 0 : $toggle.find('> div').outerHeight(true),
			$targetButton = $(e.target);

			if($lastDom!="" && $lastDom!=$targetButton[0])
			{
				$closing = false;
			}	
			
			if(!$closing)
			{
				$lastTop = $(document).scrollTop();
			}
			
          $toggle.animate({height: $height }, 250, function(){

            if( !$(this).hasClass('close') ){
              $top    = $parent.offset().top;
			  
              if($parent.hasClass('toggle-finition') && $parent.hasClass('close')){
                $top    = $parent.parents('.lame.finition').first().offset().top - 30;
              }
			  
			  // toggleHeight();
              $parent.toggleClass('close');
            }
            else
			{
				$('.tit-toggle.close').removeClass('close');
            }
			
			if(!no_anchor)
			{
				if(!$closing)
				{
					$('html, body').animate({ scrollTop: $top }, 500);
				}
				else
				{
					$('html, body').animate({ scrollTop: $lastTop }, 500);
				}
			}
			$closing = !$closing;
			
			$lastDom = $targetButton[0];	
			
            $('.click').removeClass('click');
			
			no_anchor = false;
          });

        if( $toggle.css('position') === 'absolute' ){
          $parents.animate({ paddingBottom: $height }, 250);
          if( $toggle.parents('.expend:eq(0)').length ){
            if( $height === 0 ){
              $height = $parents.parent().outerHeight(true) - $toggle.find('> div').outerHeight(true) - 34;
              $parents
                .animate({ paddingBottom: 0 }, 250);
            }
            else{
              $height = $height + $parents.parent().outerHeight(true);
            }

            $parents
              .parent().animate({ height: $height + 6 }, 250);

          };


        }
      });
      $('.cta-closeExpand').on('click', function(){
        $(this).parents('.close:eq(0)').find('.tit-toggle').trigger('click');
      });
    });
  };
})(jQuery);
$('.tit-toggle:not(.close)').isoToggle();
