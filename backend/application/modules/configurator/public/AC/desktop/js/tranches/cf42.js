ISO.moduleCreate('sliceCF42', function($el) {
    'use strict';
    var sliceCF42 = {
        init: function() {
          var $expand = $('.expands .vignettes-toggle .vignette_bloc', $el);
          var $expandLame = $('.expands .lames-toggle .lame_bloc', $el);

          if ($expand.length === 1) {
            var $button = $expand.find('.cta-details');
            $button.addClass('noScroll');
            $button.trigger('click');
          }else if ($expandLame.length === 1) {
            var $buttonLame = $expandLame.find('.cta-details');
            $buttonLame.addClass('noScroll');            
            $buttonLame.trigger('click');
          }

          $('.ancre').scroller({
              cible: 'class',
              dy: 0
          });
        }
    };
    sliceCF42.init();
});
