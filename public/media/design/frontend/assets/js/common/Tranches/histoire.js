(function ($) {
    return $(document).ready(function () {
        var allDate;
        var checkScroll;
        $('.stickyDateNav li a').on('click', function (e) {
            var toGo;
            e.preventDefault();
            toGo = $($(this).attr('href')).offset().top;
            $('html, body').scrollTop(toGo + 1);
            return checkScroll();
        });
        allDate = $('.stickyDateNav .dateColumn a');
        checkScroll = function () {
            var overed;
            if ($(window).scrollTop() > $('.stickyDateNav').offset().top) {
                $('.stickyDateNav').addClass('stickyActive');
            } else {
                $('.stickyDateNav').removeClass('stickyActive');
            }
            overed = false;
            allDate.each(function (key, el) {
                if ($(window).scrollTop() > $($(this).attr('href')).offset().top) {
                    overed = true;
                    allDate.removeClass('active');
                    return $(this).addClass('active');
                }
            });
            return overed = false;
        };
        $('.wrapperDate .arrowTop').on('click', function () {
            return $('html, body').animate({ scrollTop: 0 }, 'slow');
        });
        return $(window).on('scroll', function () {
            return checkScroll();
        });
    });
}(jQuery));