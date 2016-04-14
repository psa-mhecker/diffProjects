'use strict';

ISO.moduleCreate('sliceCF56', function($el, param) {
    // à l'ouverture de la popin
    $el.find('.ancre').scroller({
        dy: -8
    });

    setTimeout(function() {
        var toggles = $el.find('.toggle');
        if (toggles.length == 1) {
            toggles.find(".tit-toggle").trigger("click");
        }
    }, 100);


    // initialisation préselection
    if ($el.data('active') !== 'undefined') {
        $el.find('#lame_' + $el.data('active') + ' label').trigger('click');
    }

    // initialisation toggle
    if ($el.data('openinitial') !== 'undefined') {
        $el.find('#lame_' + $el.data('openinitial') + ' .toggle .tit-toggle').trigger('click');
    }
});
