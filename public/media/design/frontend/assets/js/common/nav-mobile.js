(function ($) {
    return $(document).ready(function () {
        var NavMobileEngine;
        var myTimer;
        var openState;
        var resizer;
        myTimer = null;
        openState = false;
        resizer = function () {
            if (window.innerHeight) {
                return $('.headerReviewMob .navWrapper').css({ height: window.innerHeight });
            } else {
                return $('.headerReviewMob .navWrapper').css({ height: $(window).innerHeight() });
            }
        };
        NavMobileEngine = function (state) {
            if (state === 'open') {
                openState = true;
                resizer();
                $('body.mobile .container').addClass('menuActive');
                TweenLite.to($('.headerReviewMob .navWrapper'), 0.6, {
                    css: { left: -50 },
                    ease: Back.easeOut
                });
                TweenLite.to($('body.mobile .headerReviewMob+.content, .headerReviewMob .navFirsLvl'), 0.6, {
                    css: { left: 250 },
                    ease: Back.easeOut,
                    onComplete: function () {
                        return openState = false;
                    }
                });
                $('.headerReviewMob .homeButton').hide();
                $('.headerReviewMob .menuButton').addClass('active');
                $('body .container').append('<div class=\'sliceHeadReviewOverlay\'></div>');
                return $('.sliceHeadReviewOverlay').on('touchmove', function (e) {
                    return e.preventDefault();
                });
            } else {
                TweenLite.to($('.headerReviewMob .navWrapper'), 0.6, {
                    css: { left: -350 },
                    ease: Power3.easeOut
                });
                TweenLite.to($('body.mobile .headerReviewMob+.content, .headerReviewMob .navFirsLvl'), 0.6, {
                    css: { left: 0 },
                    ease: Power3.easeOut,
                    onComplete: function () {
                        return $('body.mobile .container').removeClass('menuActive');
                    }
                });
                $('.headerReviewMob .homeButton').show();
                $('.headerReviewMob .menuButton').removeClass('active');
                return $('.sliceHeadReviewOverlay').remove();
            }
        };
        $('.headerReviewMob .menuButton').on('click', function (e) {
            e.preventDefault();
            if ($(this).hasClass('active')) {
                return NavMobileEngine('close');
            } else {
                return NavMobileEngine('open');
            }
        });
        $(window).on('resize', function () {
            clearTimeout(myTimer);
            myTimer = setTimeout(function () {
                return resizer();
            }, 50);
        });
        $(window).on('orientationchange', function () {
            clearTimeout(myTimer);
            return myTimer = setTimeout(function () {
                resizer();
                return NavMobileEngine('close');
            }, 50);
        });
        return $('body').on('click touchend', function (e) {
            if ($(e.target).hasClass('sliceHeadReviewOverlay') && openState === false) {
                NavMobileEngine('close');
            }
        });
    });
}(jQuery));