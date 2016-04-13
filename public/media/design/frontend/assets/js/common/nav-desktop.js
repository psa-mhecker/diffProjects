(function ($) {
    $(document).ready(function () {
        var fitLangDorpDown;
        var openState1;
        var openState2;
        var searchBarEngine;
        openState1 = false;
        openState2 = false;
        fitLangDorpDown = function () {
            $('.sliceHeadReviewDesk .headerLvl1 .langWrapper a.lang').attr('style', '');
            $('.sliceHeadReviewDesk .headerLvl1 .langWrapper .tabLang').attr('style', '');
            if ($('.sliceHeadReviewDesk .headerLvl1 .langWrapper a.lang').width() + 40 > $('.sliceHeadReviewDesk .headerLvl1 .langWrapper .tabLang').width()) {
                $('.sliceHeadReviewDesk .headerLvl1 .langWrapper .tabLang>ul').css({ width: Math.ceil($('.sliceHeadReviewDesk .headerLvl1 .langWrapper a.lang').width()) + 40 });
                return $('.sliceHeadReviewDesk .headerLvl1 .langWrapper a.lang').css({ width: Math.ceil($('.sliceHeadReviewDesk .headerLvl1 .langWrapper a.lang').width()) + 40 + 2 });
            } else {
                $('.sliceHeadReviewDesk .headerLvl1 .langWrapper a.lang').css({ width: $('.sliceHeadReviewDesk .headerLvl1 .langWrapper .tabLang').width() + 2 });
                return $('.sliceHeadReviewDesk .headerLvl1 .langWrapper .tabLang>ul').css({ width: $('.sliceHeadReviewDesk .headerLvl1 .langWrapper .tabLang').width() });
            }
        };
        fitLangDorpDown();
        $('.sliceHeadReviewDesk .headerLvl1').easyTab({
            defaultOpen: false,
            tabs: '.tabIn',
            onOpen: function () {
                if (!$('.sliceHeadReviewDesk .headerLvl1 a.lang').hasClass('active')) {
                    openState2 = false;
                    TweenLite.killTweensOf($('.sliceHeadReviewOverlay'));
                    if ($('.sliceHeadReviewDesk .headerWrapperLvl2').data('easyTab')) {
                        openState1 = true;
                        $('.sliceHeadReviewDesk .headerWrapperLvl2').data('easyTab').closeAll(0);
                    }
                    if (!$('.sliceHeadReviewOverlay')[0]) {
                        $('body').append('<div class=\'sliceHeadReviewOverlay\'></div>');
                        $('.sliceHeadReviewOverlay').css({ opacity: 0 });
                        TweenLite.to($('.sliceHeadReviewOverlay'), 0.5, {
                            opacity: 1,
                            ease: Power1.easeOut
                        });
                    } else {
                        TweenLite.to($('.sliceHeadReviewOverlay'), 0.5, {
                            opacity: 1,
                            ease: Power1.easeOut
                        });
                    }
                    return searchBarEngine('close');
                } else {
                    if (!openState2) {
                        openState1 = false;
                        openState2 = false;
                        return TweenLite.to($('.sliceHeadReviewOverlay'), 0.5, {
                            opacity: 0,
                            ease: Power1.easeOut,
                            onComplete: function () {
                                return $('.sliceHeadReviewOverlay').remove();
                            }
                        });
                    }
                }
            },
            onClose: function () {
                if (!openState2) {
                    openState1 = false;
                    openState2 = false;
                    return TweenLite.to($('.sliceHeadReviewOverlay'), 0.5, {
                        opacity: 0,
                        ease: Power1.easeOut,
                        onComplete: function () {
                            return $('.sliceHeadReviewOverlay').remove();
                        }
                    });
                }
            }
        });
        $('.sliceHeadReviewDesk .headerWrapperLvl2').easyTab({
            defaultOpen: false,
            tabs: '.tabIn',
            onOpen: function () {
                openState1 = false;
                TweenLite.killTweensOf($('.sliceHeadReviewOverlay'));
                if ($('.sliceHeadReviewDesk .headerLvl1').data('easyTab')) {
                    openState2 = true;
                    $('.sliceHeadReviewDesk .headerLvl1').data('easyTab').closeAll(0);
                }
                $('body').css('height', $('.tabIn.'+$(this).data('tab')).outerHeight() + 200);
                if (!$('.sliceHeadReviewOverlay')[0]) {
                    $('body').append('<div class=\'sliceHeadReviewOverlay\'></div>');
                    $('.sliceHeadReviewOverlay').css({ opacity: 0 });
                    TweenLite.to($('.sliceHeadReviewOverlay'), 0.5, {
                        opacity: 1,
                        ease: Power1.easeOut
                    });
                } else {
                    TweenLite.to($('.sliceHeadReviewOverlay'), 0.5, {
                        opacity: 1,
                        ease: Power1.easeOut
                    });
                }
                return searchBarEngine('close');
            },
            onClose: function () {
                if (!openState1) {
                    openState1 = false;
                    openState2 = false;
                    $('body').css('height', 'auto');
                    return TweenLite.to($('.sliceHeadReviewOverlay'), 0.5, {
                        opacity: 0,
                        ease: Power1.easeOut,
                        onComplete: function () {
                            return $('.sliceHeadReviewOverlay').remove();
                        }
                    });
                }
            }
        });
        $('.sliceHeadReviewDesk .headerLvl1 .cross').on('click', function (e) {
            e.preventDefault();
            if ($('.sliceHeadReviewDesk .headerLvl1').data('easyTab')) {
                return $('.sliceHeadReviewDesk .headerLvl1').data('easyTab').closeAll(0);
            }
        });
        $('.sliceHeadReviewDesk .headerWrapperLvl2 .cross').on('click', function (e) {
            e.preventDefault();
            if ($('.sliceHeadReviewDesk .headerWrapperLvl2').data('easyTab')) {
                return $('.sliceHeadReviewDesk .headerWrapperLvl2').data('easyTab').closeAll(0);
            }
        });
        $('body').on('click touchstart', function (e) {
            if ($(e.target).hasClass('sliceHeadReviewOverlay') || $(e.target).hasClass('buttonList') || $(e.target).hasClass('headerWrapperLvl1') || $(e.target).hasClass('headerWrapperLvl2') || $(e.target).hasClass('headerLvl1') || $(e.target).hasClass('cookieBarReviewDesktopRow')) {
                if ($('.sliceHeadReviewDesk .headerLvl1').data('easyTab')) {
                    $('.sliceHeadReviewDesk .headerLvl1').data('easyTab').closeAll(0);
                }
                if ($('.sliceHeadReviewDesk .headerWrapperLvl2').data('easyTab')) {
                    $('.sliceHeadReviewDesk .headerWrapperLvl2').data('easyTab').closeAll(0);
                }
            }
            if ($('.sliceHeadReviewDesk .headerLvl1 a.lang').hasClass('active') && !$(e.target).data('tab')) {
                if ($('.sliceHeadReviewDesk .headerLvl1').data('easyTab')) {
                    $('.sliceHeadReviewDesk .headerLvl1').data('easyTab').closeAll(0);
                }
            }
            if (!$(e.target).hasClass('searchBarComponent') && !$(e.target).hasClass('search')) {
                return searchBarEngine('close');
            }
        });
        searchBarEngine = function (state) {
            if (state === 'open') {
                if ($('.sliceHeadReviewDesk .headerLvl1').data('easyTab')) {
                    $('.sliceHeadReviewDesk .headerLvl1').data('easyTab').closeAll(0);
                }
                if ($('.sliceHeadReviewDesk .headerWrapperLvl2').data('easyTab')) {
                    $('.sliceHeadReviewDesk .headerWrapperLvl2').data('easyTab').closeAll(0);
                }
                return TweenLite.to($('.sliceHeadReviewDesk .searchBar'), 0.5, {
                    css: { height: 2 + $('.sliceHeadReviewDesk .searchBar > form').height() + parseInt($('.sliceHeadReviewDesk .searchBar > form').css('padding-bottom'), 10) + parseInt($('.sliceHeadReviewDesk .searchBar > form').css('padding-top'), 10) },
                    ease: Power3.easeOut
                });
            } else {
                return TweenLite.to($('.sliceHeadReviewDesk .searchBar'), 0.5, {
                    css: { height: 0 },
                    ease: Power3.easeOut
                });
            }
        };
        return $('.sliceHeadReviewDesk .search').on('click', function (e) {
            e.preventDefault();
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
                return searchBarEngine('close');
            } else {
                $(this).addClass('active');
                return searchBarEngine('open');
            }
        });
    });
    return $(document).load(function () {
        return fitLangDorpDown();
    });
}(jQuery));