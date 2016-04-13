var screenSurveille;
screenSurveille = null;
(function ($) {
    return $(document).ready(function () {
        $('input, textarea').placeholder();
        window.mymqDetector1024 = new mqDetector(1024);
        window.mymqDetector768 = new mqDetector(768);
        window.touchDetect = new touchDetect();
        if (!touchDetect.checkMe() || !mymqDetector1024.checkMe()) {
            $('a, button, input[type=submit]').addClass('activeRoll');
        }
        $.migrateTrace = false;
        $.migrateMute = false;
        $('.cookieBarReviewDesktop, .cookieBarReviewMobile').CookiesBanner();
        if (typeof tooltip !== 'undefined') {
            $(document.body).on('click', function () {
                return tooltip.close();
            });
        }
    });
}(jQuery));