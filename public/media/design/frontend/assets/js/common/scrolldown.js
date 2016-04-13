(function ($) {
    return $(document).ready(function () {
        var checkArrowDown;
        var stateArrowDown;
        stateArrowDown = false;
        checkArrowDown = function () {
            if ($(window).scrollTop() > $(window).height() / 2 && stateArrowDown === false) {
                TweenLite.to($('.arrowBottom'), 0.5, {
                    opacity: 0,
                    ease: Power1.easeOut,
                    onComplete: function () {
                        return $('.arrowBottom').hide();
                    }
                });
                return stateArrowDown = true;
            }
        };
        $(window).on('scroll', function () {
            checkArrowDown();
        });
        $('.arrowBottom').on('click', function (e) {
            return $('html, body').animate({ scrollTop: $(window).height() }, 800);
        });
        checkArrowDown();
        $('.cookieBarReviewDesktop').on('displayed', function () {
            if ($('body').hasClass('cookiesNotAccepted')) {
                return $('.arrowBottom').css({ bottom: 90 });
            }
        });
        return $('.cookieBarReviewDesktop').on('accepted', function () {
            return $('.arrowBottom').attr('style', '');
        });
    });
}(jQuery));