;(function($, window, document, undefined) {

    // Create the defaults once
    var pluginName = "lazysrcset",
        defaults = {},
        windowHeight = window.innerHeight;



    var LazySrcset = function (element, options) {

        this.element = element;
        this.$element = $(this.element);
        this.$media = this.$element.children('source');
        this.$image = this.$element.children('img');
        this.windowHeight = windowHeight;
        this.options = $.extend({}, defaults, options);

        this._defaults = defaults;
        this._name = pluginName;

        this._init();
    };


    LazySrcset.prototype = {
        _init: function() {
            this.throttleScroll = _.throttle(this._scroll.bind(this), 500);
            $(window).on('scroll', this.throttleScroll);
            this._scroll();
        },
        _scroll: function() {
            var elTopPosition = this.$element.offset().top;
            var currentBottomScroll = $(window).scrollTop() + this.windowHeight;
            if (currentBottomScroll > elTopPosition) {
                this.$image.attr('src',this.$image.attr('data-src'));
                this.$media.each(function(){
                    $(this).attr('srcset',$(this).attr('data-srcset'));
                    $(this).removeAttr('data-srcset');
                });
                this.$element.toggleClass('lazy-load lazy-loaded');
                // Stop scroll event
                $(window).off('scroll', this.throttleScroll);
            }
        }
    };

    $.fn[pluginName] = function(options) {
        return this.each(function() {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName,
                    new LazySrcset(this, options));
            }
        });
    };


})(jQuery, window, document);
