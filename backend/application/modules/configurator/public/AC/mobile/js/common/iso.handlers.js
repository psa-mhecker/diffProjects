(function($){
  /* Via handlers */
  $.fn.handlers = function(options) {
    return this.each(function(e) {
      var defaults = {
        url      : null,
        src      : '.result',
        mess     : false,
        callback : null
      },
      opts = $.extend(defaults, options),
      source = $( opts.src ).html(),
      template = Handlebars.compile(source),
      filtreResult = $.getJSON(opts.url, function( data ) {

        $( opts.src ).html(template(data));
      
      }).done(function(data) {
        
        if( opts.callback ) opts.callback();
        if( opts.mess ) $(this).response({ text : "Mise à jour effectuée." });
      
      }).fail(function(data) {
        if( opts.mess ) $(this).response({ text : "Une erreur s'est produite. Veuillez recommencer votre recherche.", status : 'error' });
      }).error(function(data) {

        if( opts.mess ) $(this).response({ text : "Une erreur s'est produite. Veuillez recommencer votre recherche.", status : 'error' });

      });
    });
  };
})(jQuery);
