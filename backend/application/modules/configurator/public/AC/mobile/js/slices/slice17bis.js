ISO.moduleCreate('slice17', function($el, param) {
    var cf17b = (function() {
        var $radioButtonElement;

        function init() {
            $radioButtonElement = $('.js-cf17b-radio');


            events();
        };

        function events() {
            $radioButtonElement.on("click", setRadioClass);
        };

        function setRadioClass(e) {
        	$radioButtonElement.removeClass('selected');
            $(this).addClass('selected');
        };

        return {
            init: init
        };
    })();

    cf17b.init();
});
