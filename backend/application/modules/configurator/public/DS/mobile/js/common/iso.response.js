(function($){
  $.fn.response = function(options) {
    return this.each(function(){

      var defaults = {
        content  : 'body',
        status   : 'done',
        text     : 'La recherche ne retourne aucun résultat.',
        timer    : 5000,
        animTime : 1000
      }
      var opts        = $.extend(defaults, options),
          resp        = $('.resp', respContent),
          $index      = parseInt(resp.length) + 1,
          respInd,
          rmv,
          respContent;

      // Création du bloc qui va accueillir les messages
      if( !$('.responses').length ) $( opts.content ).append('<div class="responses" />');

      // Ajout du message au début du conteneur RESPONSES
      respContent = $('.responses');
      respContent.prepend('\
        <p class="resp resp_'+ $index +' '+opts.status+'">'+opts.text+'</p>\
      ');

      // Recupération de l'index pour le faire disparaître
      respInd = $('.resp_'+ $index );
      rmv = window.setTimeout(function(){
        respInd.animate({ opacity: 0 }, opts.animTime, function(){
          respInd.remove();
        });
      }, opts.timer);

    });
  }
})(jQuery);
