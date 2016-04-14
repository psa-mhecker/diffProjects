'use strict';
ISO.moduleCreate('sliceCF57', function($el, param) {

    $('.ancre', $el).scroller({
        cible: 'class'
    });

    $('.isoPopinOpenLink').on("click", function(){
      $(document).scrollTop(0);
    });
});
