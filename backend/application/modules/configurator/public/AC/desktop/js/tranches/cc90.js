ISO.moduleCreate('slicecc90', function($el) {
    'use strict';
    var slicecc90 = {
        init: function() {
          var $expand = $('.expands .vignettes-toggle .vignette_bloc', $el);
          if ($expand.length == 1) {
                      console.log($expand.length)
            var $button = $expand.find('.cta-details');
            $button.addClass('noScroll');
            $button.trigger("click");
          }
            $('.ancre').scroller({
                cible: 'class',
                dy: 0
            });
            $('.btn-continued', $el).on('click', function() {
                $(this).parents('.cont-toggle').find('.oneEquipment').toggleClass('onSelected');
                if ($(this).hasClass('add')) {
                    $(this).toggleClass('add');
                } else {
                    $(this).parents('.expands').find('.btn-continued').removeClass('add');
                    $(this).addClass('add');
                }
            });
        }
    };
    slicecc90.init();
});
