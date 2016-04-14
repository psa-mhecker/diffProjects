/*ISO.moduleCreate('slicebis', function($el, param) {*/

  var teintesHandler = function($root) {
    this.init($root);
  };
  teintesHandler.prototype = {
    init: function(obj) {
      var oThis = this;
      oThis.root = $(obj);

      oThis.namelame = oThis.root.find('.finitions');
      oThis.numberlame = oThis.namelame.length;
      oThis.itemDefault = oThis.root.attr('data-default'); // variable pour ouvrir la lame au chargement de la page
      oThis.accordion = oThis.root.find('.accordion'); // variable pointant l'accordion de chaque lame
      oThis.labelradio = oThis.accordion.find('label.radio'); // variable pointant la case à cocher de chaque lame
      
      oThis.modtoggle = oThis.root.find('.mod-toggle');
      oThis.modtoggleclick = oThis.root.find('.mod-toggle .mod-toggle-click'); // variable pointant la zone à cliquer pour ouvrir l'accordion de chaque lame

      oThis.labelradio.each(function(){
        var $this = $(this);

        $this.on('click', function(e) { // click sur la case à cocher de chaque lame ouverte
          // e.preventDefault();
          // e.stopImmediatePropagation();
          oThis.labelradioclick($(this));
        });
      });


      oThis.modtoggle.each(function(){
        var $mod = $(this),
            $btn = $mod.find('.mod-toggle-click'), 
            $slickexternalnavimg = $mod.find('ul.slickexternalnav img'); // vriable pointant les images secondaires de chaque palette de couleur de chaque lame;

            if($slickexternalnavimg.length>0){

              $slickexternalnavimg.each(function(){
                var $rControlBtn = $(this);

                $rControlBtn.on('click', function(e) { // click sur les images de la palette de chaque lame ouverte        
                  if (!$mod.hasClass('mod-toggle-open')) {
                    oThis.root[0].toggleCheck.manage($btn);
                  }
                }); 
              });

            }

            $btn.on('click', function() { // click sur la zone permettant d'ouvrir chaque accordion
              oThis.modtoggleclickevent($(this));
            });
      });

      oThis.itemDefaultHl();
    },

    itemDefaultHl: function() { // méthode gérant l'affichage de la lame qui s'ouvrira par défaut au lancement de la page
      var oThis = this;

      if (oThis.itemDefault) { // si il y a une lame sélectionnée par défaut alors traitement
        oThis.accordion.each(function() {
          var obj = this;

          if ($(obj).attr('data-finitionid') == oThis.itemDefault) { // lame sélectionnée par défaut trouvée
            oThis.labelradioclick($(this).find('label.radio')); // click sur la case à cocher de la lame sélectionnée par défaut

            oThis.modtoggleselected = oThis.root.find('.mod-toggle.selected');
            oThis.slickslideshow = oThis.modtoggleselected.find('.slickslideshow');
            oThis.slickexternalnav = oThis.modtoggleselected.find('.slickexternalnav');
            oThis.slickexternalnavDD = oThis.slickexternalnav.attr('data-default');
            setTimeout(function() {
              if (oThis.slickexternalnav.attr('data-default')) { // si la palette de couleur secondaire a une couleur sélectionnée par défaut alors traitement
                oThis.slickexternalnav.find('li#' + oThis.slickexternalnavDD + ' img').addClass('on'); // la couleur secondaire sélectionnée par défaut se met à on
                oThis.slickslideshow.slick('slickGoTo', oThis.slickexternalnav.find('li#' + oThis.slickexternalnavDD).index()); // le slider se positionne sur le visuel de la couleur secondaire sélectionnée par défaut
              } else {
                oThis.slickexternalnav.find('li:eq(0) img').addClass('on'); // si la palette de couleur secondaire n'a pas de couleur sélectionnée par défaut alors la première couleur se met à on
              }
              if (oThis.root.hasClass('slicecf41bis')) {
                $(obj).find('.price-prefix').css('display', 'none');
              }
              if ($(obj).parent().attr('data-pricetype') == 'monthly') {
                if (oThis.slickexternalnav.find('li#' + oThis.slickexternalnavDD + ' img.on').length) {
                  $(obj).find('.price-monthly').html(parseInt($(obj).parent().attr('data-pricemonthly')) + parseInt(oThis.slickexternalnav.find('li#' + oThis.slickexternalnavDD + ' img.on').attr('data-pricemonthlysecond')));
                } else {
                  $(obj).find('.price-monthly').html(parseInt($(obj).parent().attr('data-pricemonthly')));
                }
              } else {
                if (oThis.slickexternalnav.find('li#' + oThis.slickexternalnavDD + ' img.on').length) {
                  $(obj).find('.price-outright').html(parseInt($(obj).parent().attr('data-priceoutright')) + parseInt(oThis.slickexternalnav.find('li#' + oThis.slickexternalnavDD + ' img.on').attr('data-priceoutrightsecond')));
                } else {
                  $(obj).find('.price-outright').html(parseInt($(obj).parent().attr('data-priceoutright')));
                }
              }
            }, 10);
          }
        });
      }
    },

    labelradioclick: function(obj) { // au click sur la case à cocher d'une lame alors traitement
      var oThis = this;
      if (obj.parent().hasClass('finInfos') && !obj.parents('.mod-toggle').hasClass('selected') && !obj.parents('.slickcontext').find('.mod-toggle').hasClass('mod-toggle-open') && obj.parents('.slickcontext').find('.slickexternalnav').length) {
        obj.parents('.mod-toggle').find('.mod-toggle-click').click();
      }

      oThis.accordion.removeClass('selected'); // déselection de toutes les lames
      obj.parents('.mod-toggle').addClass('selected'); // selection de la lame choisie
      oThis.labelradio.removeClass('selected'); // déselection de toutes la case à cocher de chaque lame
      obj.addClass('selected'); // sélection de la case à cocher de la lame choisie

      // $('input:checked', oThis.labelradio).attr( 'checked', false );
      $('input', obj).attr( 'checked', true );
      $.publish('configurator.newdata');
    },

    modtoggleclickevent: function(obj) { // au click sur la zone pour déployer une lame alors traitement
      var oThis = this;

      if (obj.parent().find('ul.slickexternalnav').length) {
        if (obj.parent().find('ul.slickexternalnav li img.on').length) {
          obj.parent().find('ul.slickexternalnav li img.on').click();
        } else {
          obj.parent().find('ul.slickexternalnav li:eq(0) img').click();
        }
      }
      if (oThis.root.hasClass('slicecf41bis')) {
        obj.parent().find('.price-prefix').css('display', 'none');
      }
    }
  };

/*  $el.each(function() {
    this.teintesCheck = new teintesHandler($(this)); // activation de l'objet prototypé
  });
  setTimeout(function() {
    $el[0].teintesCheck.itemDefaultHl(); // activation de la lame par défaut
  }, 100);*/

/*});*/


// $(document).ready(function() {
//   $.subscribe('configurator.stepsLoaded', function () {
//       ISO.control.init();
//     })
// });
