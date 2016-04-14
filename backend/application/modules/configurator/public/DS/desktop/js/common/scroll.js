(function($){
  /* Via Ajax */
  'use strict';
  $.fn.scroller = function(options) {
    return this.each(function(e) {

      var defaults = {
        events   : 'click',
        speed    : '1000',
        element  : null,
        callback : null,
        cible    : 'id',
        dy       : 0
      }
      var opts = $.extend(defaults, options);

      // Scroll résultats
      $("body").on(opts.events, "a[href^='#']", function(e){
        var $this = $(this),
            $id   = $this.attr('href');

        e.preventDefault();

        if( $id != '#' ){
          if( opts.cible === 'class' ) $id = $id.replace('#','.');
          var $el   = $($id),
              $elT  = $el.offset().top + opts.dy;

          /* if ($('.popinopen').length) {
            if( $el.length ) $('.popin--wrapper').animate({ scrollTop: $elT + $('.popin--wrapper').scrollTop() }, opts.speed);
          }else { */
            if( $el.length ) $('html, body').stop().animate({ scrollTop:$elT }, opts.speed);
          // }
        }
      });
    });
  };
  $('.ancre').scroller({
    cible: 'class'
  });
})(jQuery);
