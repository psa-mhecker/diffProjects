(function ($) {
    $(document).ready(function () {
        var mytimer;
        var stateToolbar;
        stateToolbar = false;
        mytimer = 0;
        if (!$.cookie('toolbarOpened')) {
            $.cookie('toolbarOpened', 'true', {
                expires: 15,
                path: '/'
            });
            $('.enableAnima .sidebarToolsDesktopReview .buttonList li>a').each(function (i, key) {
                mytimer += 220;
                return setTimeout(function () {
                    return $(key).addClass('active');
                }, mytimer);
            });
        }
        $('.sidebarToolsDesktopReview .buttonList li>a').on('mouseover', function () {
            return $('.sidebarToolsDesktopReview .buttonList li>a').removeClass('active');
        });
        return $(window).on('scroll', function () {
            if (!stateToolbar) {
                stateToolbar = true;
                return $('.sidebarToolsDesktopReview .buttonList li>a').each(function (i, key) {
                    setTimeout(function () {
                        return $(key).removeClass('active');
                    }, mytimer);
                    return mytimer += 220;
                });
            }
        });
    });
}(jQuery));