;(function($, window, document, undefined) {

    // Create the defaults once
    var pluginName = "lazyInterchange",
        defaults = {},
        windowHeight = window.innerHeight;



    var LazyInterchange = function (element, options) {

        this.element = element;
        this.windowHeight = windowHeight;
        this.options = $.extend({}, defaults, options);

        this._defaults = defaults;
        this._name = pluginName;

        this._init();
    };


    LazyInterchange.prototype = {
        _init: function() {
            this.throttleScroll = _.throttle(this._scroll.bind(this), 500);
            $(window).on('scroll', this.throttleScroll);
            this._scroll();
        },
        _scroll: function() {
            var $element = $(this.element);
            var $parentSlide = $element.parents('.js-slideshow');
            if($parentSlide.length){
                var elTopPosition = $parentSlide.offset().top;
                var currentBottomScroll = $(window).scrollTop() + this.windowHeight;
                if ($element.hasClass('js-slide') && currentBottomScroll > elTopPosition) {
                    $element.attr('data-interchange',$element.attr('data-lazy'));
                    // reboot interchange
                    $element.foundation('interchange', 'reflow');
                    $element.toggleClass('lazy-load lazy-loaded');
                    $element.removeAttr('data-lazy');
                    // Stop scroll event
                    $(window).off('scroll', this.throttleScroll);
                }
            }else{
                $(window).off('scroll', this.throttleScroll);
            }

        }
    };

    $.fn[pluginName] = function(options) {
        return this.each(function() {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName,
                    new LazyInterchange(this, options));
            }
        });
    };


})(jQuery, window, document);
