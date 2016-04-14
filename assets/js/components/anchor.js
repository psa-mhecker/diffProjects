;(function($, NDP){

    NDP.anchorScroll = function($el){
        $el.on('click', function(e) {
            e.preventDefault();
            var theId = this.getAttribute('href');
            $('html, body').animate({
                scrollTop: $(theId).offset().top,
            },{
                duration : 2000,
                complete : function (animation) {
                    $('html, body').animate({
                        scrollTop: $(theId).offset().top,
                    }, 1000);
                }
            }, 'easeOutCubic');
        });
    };

    $.NDP = NDP;

})(jQuery, window.NDP = window.NDP || {});
