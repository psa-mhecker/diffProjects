/**
 * Created on 16/12/15.
 * COMPARE 2 IMAGES : TwentyTwenty JS
 */
;(function ( $, window, document, undefined ) {

    var pluginName = "dragndrop";
    var defaults = {
        default_offset_pct: 0.5
    };

    function DragnDrop( element, options ) {
        this.element = element;

        this.options = $.extend( {}, defaults, options) ;

        this._defaults = defaults;
        this._name = pluginName;

        this.init();
    }

    DragnDrop.prototype = {
        init: function () {
            var $el = $(this.element),
                verticalOrientation = $el.data('orientation');

            if ( verticalOrientation !== '') this.options.orientation = verticalOrientation;
            $el.twentytwenty(this.options);
        }
    };

    $.fn[pluginName] = function ( options ) {
        return this.each(function () {
            if ( !$.data(this, "plugin_" + pluginName )) {
                $.data( this, "plugin_" + pluginName,
                    new DragnDrop( this, options ));
            }
        });
    }

})( jQuery, window, document );
