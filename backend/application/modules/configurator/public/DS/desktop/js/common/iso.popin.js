/* Popin */
(function(){

/*

Lien vers popin 'gÃ©nÃ©ral' pour ouverture popin :
<a
    href="#"
    class             ="isoPopinOpenLink"
    data-datatosend   ='donnees Ã  envoyer au back'
    data-urltosend    ='../../sections/popin-confirmation/html_no-nav-defaut.html'
    data-popinclass   ='popin--finition classtest'
    data-idpopin      ='popin_00002'
>
  'titre du lien popin'
</a>

obligatoire :
class            : 'isoPopinOpenLink'
data-urltosend   : url d'envoie d'appel AJAX - pour le DEV FRONT : mettre lien relatif vers le html popin
data-idpopin     : id de la popin crÃ©e

facultatifs :
data-datatosend  : permet d'envoyer des donnÃ©es au back pour la requete AJAX
data-popinclass  : classes mises dans le container de la popin


sur les tranches popin :
sur la premiere div :
- ajouter une data-closeparentwhenclosed='true' pour forcer au clic sur la croix la fermeture de la popin doit se fermer et de la parente
- data-initjsobj='iso-toggle,iso-form' pour forcer l'initialisation des fonctionnalitÃ©s nÃ©cessaires Ã  la tranche non explicitÃ©s ( comme toggle, form etc;)

*/

  $.fn.isoPopin = function(options){
    var isoPopinObj = {
      $body     : $('body'),
      $overlay  : $('body').find('.popin_overlay'),
      $popinContainer : null,
      $top : null,

      // initialisation du body et des Ã©vÃ©nements globaux
      init : function(){
        var app = this ;

        app.$popinContainer = $('<div class="popin"><div class="ico ico--close close isoPopinClose" data-id=""></div></div>');

        app.$body.off('click').on('click','.isoPopinOpenLink:not(.diselected)', function(event){
          event.preventDefault();
          isoPopinObj.$top = $('body').scrollTop();
          app.manageClickToOpen(this);
        });

        app.$body.on('click','.isoPopinClose, span.before',function(event){
          app.manageClose(this);
        });

        app.$overlay.off('click').on('click', function(e) {
          if (!$(e.target).closest('.popin').length) {
            app.$overlay.fadeOut(250, function() {
              app.$overlay.children().remove();
              // $('html').removeClass('hidden');
              $('.popin--wrapper').hide();
              $('body').animate({ scrollTop: isoPopinObj.$top }, 0);
              $('.before').remove();
            });
          }
        });

        var clearResize ;
        $(window).on('resize',function(){
          clearTimeout(clearResize);
          clearResize = setTimeout(function(){
            app.manageSize();
          }, 250, true);
        });

        if( $('html').hasClass('touch') === false){
          $(window).on('resize', function() {
            app.heightPopin();
          });
        }

      },

      manageClose : function(element){
        var app = this ;
        var idPopin = $(element).parents('.popinopen').attr('id');
        var $popin = $('#'+idPopin, app.$overlay) ;
        var dataIdPopinParent = $popin.data('idpopinparent');
        var closeparentwhenclosed = $popin.hasClass('closeparentwhenclosed');

        if (dataIdPopinParent){
            // la popin vient d'une autre popin : il faut rÃ©afficher la popin parent et ne pas fermer le overlay
            app.heightPopin($('#'+dataIdPopinParent).outerHeight());
            
          if (!closeparentwhenclosed){
            $('#'+dataIdPopinParent).addClass('invisiblePopin').removeClass('isPopinParent');
            app.manageSize($('#'+dataIdPopinParent).outerHeight());
            $('#'+dataIdPopinParent).removeClass('invisiblePopin');
            $popin.remove();
          } else {
            $('#'+dataIdPopinParent).find('.isoPopinClose').trigger('click');
            $popin.remove();
          }
        }else{
          // la popin vient directement du body : il faut supprimer la popin et fadeout le overlay
          app.$overlay.fadeOut(250, function() {
            $popin.remove();
            $('.before').remove();
            // $('body').css('height', 'auto');
          });
          //$('html').removeClass('hidden');
          $('.popin--wrapper').hide();
          $('body').animate({ scrollTop: isoPopinObj.$top }, 0);
        }
      },

      manageClickToOpen : function(element){
        var app = this ;
        var $link = $(element);
        var urlToSend   = $link.data('urltosend') ? $link.data('urltosend') : ''  ;
        var popinClass  = $link.data('popinclass') ? $link.data('popinclass') : '' ;
        var idPopin     = $link.data('idpopin') ? $link.data('idpopin') : '' ;
        var idPopinParent = null ;
        if ($link.parents('.popinopen').length !== 0){
          // on envisage de rendre cette popin invisible et on rÃ©cupÃ¨re son ID.
          var $popinParent = $link.parents('.popinopen');
          var idPopinParent = $popinParent.attr('id');
          $popinParent.addClass('isPopinParent');
        }

        var attrPopin = {
          'popinClass'    : popinClass,
          'idPopinParent' : idPopinParent,
          'idPopin'       : idPopin,
        }
        var dataToSend  = $link.data('datatosend') ? $link.data('datatosend') : '' ;
        if (urlToSend){
          app.sendAjax(urlToSend,dataToSend,attrPopin,app.createAndOpen.bind(app));
        } else {
          // erreur ?
        }
      },

      // fonction de retour de l'appel AJAX
      createAndOpen : function(html,attrPopin){
        var app = this;
        app.createPopinDom(html, attrPopin);
        app.openpopin(attrPopin.idPopin);
      },

      manageSize : function(popinHeight){
        var app = this;
        var popinHeight = popinHeight ? popinHeight : 0 ;

        setTimeout(function(){
          app.heightPopin( popinHeight );
        }, 150);
        
      },

      // ouverture de la popin
      openpopin : function(id){
        var app     = this ;
        var $popin   = $('#'+id, app.$overlay) ;

        if (!$popin.data('idpopinparent')){
          app.$overlay.css({
            'opacity': '0'
          }).show();
          $('body').prepend('<span class="before"/>');
          if( $('.popin--wrapper').length < 1 ){
            app.$overlay.wrap('<div class="popin--wrapper" style="height:'+$(window).height()+'px;"/>');
          }
          else{
            $('.popin--wrapper').show();
          }
        }
        $popin.addClass('popinopen invisiblePopin').show();
        $popin.trigger('eventOpenPopin'); // permet de dÃ©clencher des Ã©venement Ã  l'ouverture d'une popin comme des resizes ou initialisations.

        $popin.find('[data-jsobj]').each(function(){
          var $popinContent =  $(this);
          var dataobjModule = $popinContent.data('jsobj');
          var objModule = dataobjModule.split("[ { 'obj':'")[1].split("' } ]")[0];

          if ( typeof(ISO.getmoduleCreate(objModule)) !== 'undefined') {
            ISO.getmoduleCreate(objModule)( $popinContent, {} );
          }
        });

        if ($popin.find('[data-initjsobj]').length) {
          var tabJsobj = $popin.find('[data-initjsobj]').data('initjsobj').split(',');
          for ( var i = 0 , lgth = tabJsobj.length ; i < lgth ; i ++){
            if (typeof(ISO.getmoduleCreate(tabJsobj[i])) !== 'undefined'){
              ISO.getmoduleCreate(tabJsobj[i])( $popin, {} );
            }
          }
        }

        app.heightPopin($popin.outerHeight());

        // Temporary fix
        setTimeout(function(){
          app.heightPopin($popin.outerHeight());
        }, 300);

        $popin.removeClass('invisiblePopin') ;
        $popin.children().trigger('popinOpened');
        app.$overlay.animate({'opacity':'1'},250);

        $('.elemnt_input label').on('click', function() {
          $(this).parent().find('input.form-control').prop("checked");
        });

      },

      // crÃ©ation de la popin dans le DOM
      createPopinDom : function(html,attrPopin){

        var app = this ;
          var $newPopinContainer = app.$popinContainer.clone();
          $newPopinContainer.find('.isoPopinClose').data('id',attrPopin.idPopin);
          $newPopinContainer
              .attr({
                'id' : attrPopin.idPopin
              })
              .addClass(attrPopin.popinClass)
              .append(html)
              .data('idpopinparent',attrPopin.idPopinParent);
          if($newPopinContainer.find('[data-closeparentwhenclosed]') && $newPopinContainer.find('[data-closeparentwhenclosed]').data('closeparentwhenclosed')) {
            $newPopinContainer.data('closeparentwhenclosed','true');
          }

          app.$overlay.append($newPopinContainer);
      },

      // appel AJAX qui va chercher le contenu HTML de la popin ( et envoie les donnÃ©es nÃ©cessaires au BACK pour gÃ©nÃ©rer ce HTML)
      sendAjax : function(urlToSend,dataToSend,attrPopin,afterSend){

        $.ajax({
              url : urlToSend,
              dataType: 'html',
              data : dataToSend
            }).done(function(html){
              if (afterSend){
                afterSend(html,attrPopin);
              }
            }).fail(function(error,status){

            });
      },

      // gestion de la hauteur des popins
      heightPopin : function(popinHeight){
        var app = this,
            windowHeight = $(window).height(),
            popinHeight = popinHeight ? popinHeight : $('.popinopen:not(.isPopinParent)').outerHeight();

        app.$overlay.css({ height: windowHeight });

        if (popinHeight < windowHeight - 100) {

          popinHeight = $('.popinopen:not(.isPopinParent)').outerHeight();

          if (windowHeight > app.$overlay.outerHeight()) {
            app.$overlay.css({
              height: windowHeight
            });
          }

        } else {
          if (popinHeight < $('.body').height() - 100 && popinHeight < $('body').height() - 100) {

            app.$overlay.css('height', $('.body').height() + 100);
          } else {
            app.$overlay.css({
              height: popinHeight + 100
            });
          }
        }

       $('.popinopen').css({
          'margin-top': -(popinHeight) / 2 + $(window).scrollTop(),
          'top': '50%'
        });
      },

    }

    isoPopinObj.init();
    return isoPopinObj ;
  }

    window.isoPopin = $('body').isoPopin({}) ;

  $('.tit-toggle:not(.lames-title)').on('click', function(){
    setTimeout(function(){
      $( window ).trigger('resize');
    }, 10);
  });

})();
