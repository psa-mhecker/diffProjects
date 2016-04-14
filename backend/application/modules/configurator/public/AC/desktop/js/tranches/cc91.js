'use strict';
ISO.moduleCreate('sliceCC91', function($el, param) {


    // orientation tablette
    $(document).on("pagecreate", function(event) {
        $(window).on("orientationchange", function() {
            if (window.orientation == 0) {
                heightPopin = function(popinHeight) {
                    var windowHeight = $(window).height(),
                        popinHeight = popinHeight ? popinHeight : $('.popinopen:not(.isPopinParent)').outerHeight();
                    if (popinHeight !== null) {
                        $('body').css('height', 'auto');
                        $('.popin_overlay').css('height', 'auto');
                        if (popinHeight < windowHeight - 100) {
                            $('body').css('height', 'auto');
                            $('.popinopen').css({
                                'margin-top': -popinHeight / 2,
                                'top': '50%'
                            });
                            if (windowHeight > $('.popin_overlay').outerHeight()) {
                                $('.popin_overlay').css('position', 'fixed');
                                $('.popin_overlay').css({
                                    height: windowHeight
                                });
                            }
                        }
                    } else {
                        if (popinHeight < $('.body').height() - 100 || popinHeight < $('body').height() - 100) {
                            $('body').css('height', 'auto');
                            $('.popin_overlay').css('height', $('.body').height() + 100);
                            $('.popinopen').css({
                                'margin-top': 0,
                                'top': $(window).scrollTop()
                            });
                        } else {
                            $('body').css({
                                height: popinHeight + 100
                            });
                            $('.popin_overlay').css({
                                height: popinHeight + 100
                            });
                            $('.popinopen').css({
                                'margin-top': -(popinHeight) / 2,
                                'top': '50%'
                            });
                        }
                        $('.popin_overlay').css('position', 'absolute');
                    }
                };
            }
        });

    });
});
