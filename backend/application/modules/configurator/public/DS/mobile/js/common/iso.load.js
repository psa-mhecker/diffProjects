(function($){
  $.fn.isoLoad = function(options) {
    return this.each(function() {
      var defaults = {
        content : 'body',
        image   : '../../../img/ajaxLoader.gif',
		txt		: '',
        kill    : false
      }
      var opts = $.extend(defaults, options);

      // chargement du loader sur l'ensemble de la page
      if( opts.kill )
        $(opts.content).find('.loader').remove();
      else if( !$('.loader', opts.content).length ){
        $(opts.content).append('<div class="loader" style="position: absolute; left: 0; top: 0; height: 100%; width: 100%; text-align:center;"><div style="height:100%;display:inline-block;vertical-align:middle;">&nbsp;</div><div style="display:inline-block;vertical-align:middle;"><img src="'+ opts.image +'" alt="" /><br />'+ opts.txt +'</div></div>');}

    });
  };
})(jQuery);
