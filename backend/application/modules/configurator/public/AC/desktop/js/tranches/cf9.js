'use strict';
ISO.moduleCreate('sliceCF9', function($el, param) {
    $('.ancre').scroller({
        cible: 'class'
    });


    $('aside.minHeight ').on('click', function(e) {
        if (e.target.className.indexOf('isoPopinOpenLink') != -1) {
            // don't activate aside if we are only clicking on info picto
            return;
        }
        $(this).find('.form-control').prop('checked', true);
        $('.minHeight').removeClass('checked');
        $(this).addClass('checked');
    });
});
