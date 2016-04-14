/* PN7 - En tête */
;
(function ($, window, document, undefined) {

    // Create the defaults once
    var pluginName = "header",
        defaults = {};

    // The actual plugin constructor
    function Header(element, options) {
        this.element = element;

        this.options = $.extend({}, defaults, options);

        this._defaults = defaults;
        this._name = pluginName;

        this.init();
    }

    Header.prototype = {

        init: function () {
            var $header = $('header', $(this.element));
            var $tranche = $('.header', $(this.element));
            if($('.media').length != 0){
                if($tranche.hasClass("white")){
                    $header.css({'background-color':'rgba(255, 255, 255, 0.5)'});
                }else{
                    $header.css({'background-color':'rgba(240, 240, 240, 0.5)'});
                }
                if($tranche.hasClass("fixed")){
                    if($('.scrollCta').length != 0){
                        $('.scrollCta').fadeOut(0);
                    }
                    var mediaHeight = $('.media', $(this.element)).outerHeight();
                    $(window).scroll( function(){
                        if($(window).scrollTop() - mediaHeight > 0){
                            if($('.scrollCta').length != 0){
                                $('.scrollCta').fadeIn(0);
                            }
                            if($tranche.hasClass("white")){
                                $header.css({'background-color':'rgba(255, 255, 255, 1)'});
                            }else{
                                $header.css({'background-color':'rgba(240, 240, 240, 1)'});
                            }
                        }else{
                            if($('.scrollCta').length != 0){
                                $('.scrollCta').fadeOut(0);
                            }
                            if($tranche.hasClass("white")){
                                $header.css({'background-color':'rgba(255, 255, 255, 0.5)'});
                            }else{
                                $header.css({'background-color':'rgba(240, 240, 240, 0.5)'});
                            }
                        }
                    });
                }
            }
        },
    };

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName,
                    new Header(this, options));
            }
        });
    };

})(jQuery, window, document);
