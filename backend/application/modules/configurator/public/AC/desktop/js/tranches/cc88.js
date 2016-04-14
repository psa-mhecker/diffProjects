ISO.moduleCreate('sliceCC88', function($el, param) {
    var $toggles = $('.toggle', $el);


    init();

    function init() {
        setTimeout(function() {
            initCaroussel($toggles.find('.multiple-items'));
        }, 250);
    }

    function initCaroussel($el) {
        var settingsSlick = {
            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 1,
            prevArrow: '<span class="arrow arrow--gray-big-left marg-picto-left"></span>',
            nextArrow: '<span class="arrow arrow--gray-big-right marg-picto-right"></span>'
        };


        var $this = $(this);
        try {
            if ($el.slick('getSlick')) {
                return;
            }
        } catch (err) {
            $el.slick(settingsSlick);
        }


    }
});
