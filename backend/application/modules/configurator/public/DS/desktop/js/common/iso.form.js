/* Toggle */
(function($){
  'use strict';
    var ModuleForm = {

      $body   : $('.body'),
      $html   : $('html'),
      init: function() {
        var app = this;
        app.events();
        app.checkbox();
      },

      /**
      * events() manage event
      */
      events:function(){
        var app = this, $click = false, $colorI;

        app.$html.stop().on('click','input:checkbox, input:radio', function(event){
          app.checkbox();
          $('.expands').find('.color-list li').removeClass('on');

          if( !$click ){
            $colorI = $(this).parents('.vignettes-toggle:eq(0)').find('.cont-toggle.selected .x-bloc_carroussel li.on').data('slick-index');
            if( $colorI === undefined ){
              $colorI = 0;
            }
            $(this).parents('.vignette_bloc:eq(0)').find('.color-list li:eq('+$colorI+')').addClass('on');
            $click = true;
          }
        });

        app.$html.stop().on('click', '.selections', function(){
          var $index  = $(this).parents('.cont-toggle:eq(0)').data('index-toggle'),
              $parent = $(this).parents('.vignettes-toggle:eq(0)').toggleClass('sel'),
              $sel    = $(this).parents('.cont-toggle:eq(0)').find('.x-bloc_carroussel li.on').data('slick-index');

          $('.vignette_bloc.'+$index).find('input:checkbox, input:radio').trigger('click');

          setTimeout(function(){
            $('.vignette_bloc.'+$index).find('input:checkbox, input:radio').trigger('click');
            $parent.toggleClass('sel').find('.vignette_bloc.selected .color-list li:eq('+$sel+')').addClass('on');
            $click = false;
          }, 10);
        });
      },

      /**
      * checkbox() manage checkbox selection
      */
      checkbox: function(){
				var $checkboxArray = $('.lame, .vignette_bloc');

				$checkboxArray.each(function(i, el){

					if ( $('input:checkbox:checked', el).length > 0 || $('input:radio:checked', el).length > 0 ) {
						$(this).addClass('selected');

              var $index = $(this).parent().index(),
                  $par   = $(this).parents('.expands:eq(0) .vignettes-toggle .row');

              $('.expands').find( '.cont-toggle.selected' ).removeClass('selected')

              if ( $('.list-vignette-toggle').length > 0 ) {
                $(this).parents('.expands:eq(0)').find( '.cont-toggle.selected' ).removeClass('selected')
                $( '+ .list-vignette-toggle > div:eq('+$index+') > .cont-toggle', $par ).addClass('selected');
              }
					}else{
						$(this).removeClass('selected');
					}

				});
      }

    };

    $( document ).ready(function() {
      ModuleForm.init();
    });

})(jQuery);
