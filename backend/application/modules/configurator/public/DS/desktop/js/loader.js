(function($) {

    $.subscribe("iso.loadSteps", loadContent);

    function loadContent(e, el) {
        var $content = $('#dynamic-content');
        var url = $(el).find('a').data('load');
        if (url && url !== "#") {
            $.ajax(url)
                .done(function(data) {
                    $content.fadeOut(function() {
                        $content.html(data).fadeIn()
                        $.publish('configurator.stepsLoaded')
                    });
                })
                .fail(function(data) {
                    $content.html('<h1>Error</h1>')
                })
        } else {
            $content.fadeOut(function() {
                $content.fadeIn()
            });
        };
    }
})(jQuery)
