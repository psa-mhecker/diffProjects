(function($){
  /* Via Ajax */
  $.fn.ajax = function(options) {
    return this.each(function(e) {
      var defaults = {
        type        : 'POST',
        url         : null,
        format      : null,
        data        : null,
        results     : false,
        mess        : false,
        callback    : null,
        errorCallback: null,
        contentLoad : 'body'
      }
      var opts = $.extend(defaults, options);

      $.ajax({
        type: opts.type,
        url: opts.url,
        format: opts.format,
        data: opts.data,
        beforeSend: function() {

          $(this).isoLoad({
            content: opts.contentLoad
          });
        },
        statusCode: {

          404: function() {
            $(this).response({ text : "Erreur 404 : la page demandée est introuvable.", status : 'not-found' });
          }

        }
      }).done(function(data) {
        if( opts.callback ) opts.callback(data);
        //$(this).update();
        if( opts.mess ) $(this).response({ text : "Mise à jour effectuée." });

      }).fail(function(req, status, err) {
        if (opts.errorCallback) opts.errorCallback(req, status, err) ;
        if( opts.mess ) $(this).response({ text : "Une erreur s'est produite. Veuillez recommencer votre recherche.", status : 'error' });

      }).error(function(req, status, err) {
        if (opts.errorCallback) opts.errorCallback(req, status, err) ;
        if( opts.mess ) $(this).response({ text : "Une erreur s'est produite. Veuillez recommencer votre recherche.", status : 'error' });

      }).complete(function(data){

        $(this).isoLoad({
          kill : true
        });

      });
    });
  };
})(jQuery);
