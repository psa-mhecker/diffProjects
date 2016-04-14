  'use strict' ;
(function(){
  var $el = $('#comparatorTable');
  var manageSticky = {
    $container    : $el.find('.model-selector-container'),
    $boxSelection : $el.find('.model-selector'),
    $boxSubtitle  : $el.find('.subtitleContainer'),
    $nav          : $('.marie-louise-navigation'),//WTF?

    init : function(){
      var appSticky = this ;
      appSticky.$container.height(appSticky.$boxSelection.height());

      $(window).scroll(function(){
        appSticky.manageSticky();

      });
      var resizeTimer ;
      $(window).resize(function(){
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function(){
          appSticky.manageSticky(true);
        },250);
      });
    },

    manageSticky:function(onResize){
      var appSticky = this ;
      var windowpos = $(window).scrollTop();
      var calc = $(window).scrollTop() - $el.offset().top - $el.height() + 1;
      if (windowpos > appSticky.$container.position().top + 200)
      {
        if (!appSticky.$boxSelection.hasClass('stick-it') || onResize ){
          appSticky.$boxSelection.addClass('stick-it');
          appSticky.manageWidth(true);
        }
        appSticky.$boxSelection.css({'top':appSticky.$nav.outerHeight()+'px'});
        if (calc > 0) { appSticky.$boxSelection.css('margin-top','-'+calc+'px'); }
        else { appSticky.$boxSelection.css('margin-top','inherit'); }
      }
      else {
        if (appSticky.$boxSelection.hasClass('stick-it') || onResize){
          appSticky.$boxSelection.removeClass('stick-it');
          appSticky.manageWidth(false);
        }
        appSticky.$boxSelection.css('margin-top','inherit');
      }
    },
    manageWidth : function(sticker){
      var appSticky = this;
      if (sticker){
        var offset = appSticky.$container.offset();
        appSticky.$boxSelection.width(appSticky.$boxSubtitle.width());
        appSticky.$boxSelection.css({'left':offset.left});
      }
      else {
        appSticky.$boxSelection.width('100%');
        appSticky.$boxSelection.css({'top':0,'left':0});
      }
    },
    // gestion des hautes des zones de sélections des modèles
    manageHeightSelections : function(){
      var hght = $el.find('.item').eq(0).height();
      $el.find('.container-bg  > .grid-col:first-child').height(hght);
    }
  };


  var ComparatorTable = {
    versionTest: $el.data('versiontest') ,  // pour generation AJAX et data renvoyées en test .
    urlJSONTest: null,
    accordeonDS:      null ,
    $selects01: $el.find('.select-01'),
    $selects02: $el.find('.select-02'),
    $selectsMotor:    $el.find('.select-motor'),
    pictureDefault:   $el.find('figure img').data('default'),
    urlJSON:          null,
    tableOpen:        false,
    tableMotorOpen:   false,
    tableauValue:     [0,0,0],
    checkboxDiff : $el.find('input#showDiff'),
    $comparatorTable : $el.find('.table-comparator-equipement'),
    $loader : $el.find('.loader-ds-container'),
    $boxSelections : $el.find('.box-selection'),
    typeComparateur : null,
    tabSelects : [],
    noPublish: false,
    compareItems: [],
    defaultSelect : {
      'select01' : '',
      'select02' : '',
      'select02Options': null,
      'htmlCarModel': null,
      'url' : ''
    },
    selectBox : null,

    init: function(){
      $('select',$el).selectBox({
        mobile:true
      });
      var app = this;
      app.typeComparateur = $el.data('comparateurtype');
      app.urlJSONTest = ['../../../json/comparatorTable_'+app.typeComparateur+'_1.json','../../../json/comparatorTable_'+app.typeComparateur+'_2.json','../../../json/comparatorTable_'+app.typeComparateur+'_3.json']
      app.manageLoader('affiche');
      app.urlJSON = $el.data('html');
      app.initAccordeon();
      app.defaultSelect.htmlCarModel = app.$boxSelections.find('.car-model').html();
      app.defaultSelect.select02Options = app.$boxSelections.eq(0).find('.select-02 select').html();
      app.$selects01.on('change','select.select01',function(e){
        if (e.target.value === '') { app.onSelectSelect01(e.target, true); }
        else { app.onSelectSelect01(e.target); }
      });
      app.$selects01.find('select').each(function(){
        app.initSelect(this);
      });

      app.$selects02.on('change','select',function(e){
         if (e.target.value === '') { app.onSelectSelect02(e.target, true); }
        else { app.onSelectSelect02(e.target); }
      });

      $el.find('.cta-supprimer').on('click',function(){
        var $this = $(this);
        var  $boxSelection =  $this.parents('.box-selection').first(); ;
        var selectOldValue = $boxSelection.find('select.select01').first().val();
        var place = $this.data('place')-1;

        ComparatorTable.compareItems.splice(place, 1);

        ComparatorTable.updateCompareItems();
        $.publish('comparator.updated', ComparatorTable.compareItems);

        //$boxSelection.find('select.select01').val('').selectBox('refresh').trigger('change');

      });

      // trigger d'un click sur le title pour permettre l'ouverture-fermeture du toogle via toute sa zone de contenu
      $el.find('.table-comparator-result').on('click','.toggle-content',function(event){
        if ( $(event.target).hasClass('isoPopinOpenLink')) { return ;}

        var $content = $(this);
        $content.parent().find('.toggle-title').trigger('click');
      });
      app.defaultSelect.select02Options = app.$boxSelections.eq(0).find('.select-02 select').html();
      // ------------------
      app.setTabSelects();
      app.manageDisableSelects();
      app.selectBoxCustomCss();
      $el.find('.container-bg').removeClass('hidden');
      app.manageLoader('masque');

      $.subscribe('configurator.addToCompare', app.addToComparator);
      $.subscribe('configurator.removeFromComparator', app.removeFromComparator);
    },
    removeFromComparator : function (e, element) {
      var app = ComparatorTable;
      var $btnAddToComparator = $(element);
      var value = $btnAddToComparator.data('compare');
      var place = $btnAddToComparator.data('comparatorPlace');

      app.compareItems.splice(place, 1);

      $.publish('configurator.removedFromComparator', element);

      app.updateCompareItems();
    },

    addToComparator : function (e, element) {
      var app = ComparatorTable;
      var $btnAddToComparator = $(element);
      var value = $btnAddToComparator.data('compare');

      app.compareItems.push(value);

      $.publish('configurator.addedToComparator', {elt:element,place:app.compareItems.length});

      app.updateCompareItems();

    },

    updateCompareItems: function (){
      var app = this;
      app.noPublish = true;
      for (var i = 0; i < 3; i++) {
        var $select = $('#compare-select-'+(i+1));
        if(typeof app.compareItems[i] != 'undefined'){
          $select.val(app.compareItems[i]).trigger('change').selectBox('value', app.compareItems[i]);
        }
        else{
          $select.val('').trigger('change').selectBox('refresh');
        }
      };
      app.noPublish = false;
    },

    selectBoxCustomCss : function(){
      //$el.find('.selectBox').css('width','100%');
      $el.find('.selectBox .selectBox-label').css('width','85%');
      $el.find('.selectBox .selectBox-arrow').css('width','15%');
      //$el.find('.selectBox-dropdown').css('display','table-cell');
    },

    sendSelectsToExpands : function(data){
      var app = this ;
      if (data){
         var tabSelectsToSend = data.expands;
      }else {
         var tabSelectsToSend = {datas : [0,0,0]};
      }
      try{
        if(window.localStorage){
           localStorage.setItem('tabSelectedItem',app.tableauValue);
        }
      } catch (e){
       return false;
    }

      try{
        if(!window.localStorage){
            localStorage.setItem('tabSelectedItem',app.tableauValue);
        }
      } catch (e){
        return false;
      }
        $('body').trigger('comparatorChange',['expands',tabSelectsToSend]);
    },

    // fonction de gestion du trigger commun pour le liens expands <-> tableau
    editSelectByExpands : function(action){
      var app = this ;
      // différents états de la variable action :
      // action = {type:'add', value:'02541'}
      // aciton = {type:'delete', indice : 1 }
      if (action.type === 'add'){
        var tabSelectNotEmpty = app.tabSelects.filter(app.filterEmpty),
        nbSelected = tabSelectNotEmpty.length;
        app.$boxSelections.eq(nbSelected).find('.select-02 select').val('');
        app.$boxSelections.eq(nbSelected).find('.select-01 select').val(action.value).trigger('change').selectBox('refresh');
      }
      else { // if action === 'delete'
        app.$boxSelections.eq(action.indice).find('.cta-supprimer').trigger('click');
      }
      // app.editComparator();


    },
    // rectifie le app.tabSelects pour enlever "le trou"
    manageTabSelects : function(){
      var tmpTabSelect = [] ,
          app = this;

      for (var i = 0 ; i < 3 ; i++){
        if ( app.tabSelects[i].select01 !== '' || app.tabSelects[i].select02 !== '' ){
          tmpTabSelect.push(app.tabSelects[i]);
        }
      }
      while (tmpTabSelect.length < 3){
        tmpTabSelect.push(app.defaultSelect);
      }

      var areEqual = ( app.isEqualTab(app.tabSelects,tmpTabSelect) ) ;
      if (!areEqual) { app.tabSelects = tmpTabSelect ; }
      return !areEqual ;
    },


    filterEmpty : function(element){
      return (element.select01 !==  '' || element.select02 !==  '') ;
    },

    // gère la possibilité d'accéder au premier select de chaque boite
    manageDisableSelects : function(){
      var app = this,
      tabSelectNotEmpty = app.tabSelects.filter(app.filterEmpty),
      nbSelected = tabSelectNotEmpty.length;
      for (var i = 0 ; i < 3 ; i++){
        if ( i <= nbSelected ){
          app.$boxSelections.eq(i).find('.select-01 select').removeAttr('disabled').selectBox('enable');
          app.$boxSelections.eq(i).addClass('enabled');
          if (i === nbSelected)   { app.$boxSelections.eq(i).find('.select-02 select').attr('disabled',true).selectBox('disable'); }
          else { app.$boxSelections.eq(i).find('.select-02 select').removeAttr('disabled').selectBox('enable'); }

        } else {
          app.$boxSelections.eq(i).find('.select-01 select').attr('disabled',true).selectBox('disable');
          app.$boxSelections.eq(i).find('.select-02 select').attr('disabled',true).selectBox('disable');
          app.$boxSelections.eq(i).removeClass('enabled');

        }
      }
    },

    // replace les box-selecions (après suppresion d'une selection)
    manageDeleteSelects : function(){
      var app = this,
          data = {};
          app.tableauValue = [];
    },


    // réinitialisation des états d'une box-selection (après suppression d'une selection)
    manageStyleBox : function($boxSelection){
      if ($boxSelection.find('.select-01 select').val() !==  '')
      {
        $boxSelection.addClass('selected_01');
        $boxSelection.find('.select-02 select').removeAttr('disabled');
        $boxSelection.find('figure img').removeClass('hidden');
        if ($boxSelection.find('.select-02 select').val() !== ''){
          $boxSelection.addClass('selected_02');
        }
        else {
          $boxSelection.removeClass('selected_02');
        }
      }
      else{
        $boxSelection.removeClass('selected_01 selected_02');
        $boxSelection.find('.select-02 select').attr('disabled');
        $boxSelection.find('figure img').addClass('hidden');
      }
    },

    // Récupération des contenus des 3 Box-selections
    setTabSelects : function(){
      var app = this ;
      app.tabSelects = [] ;
      app.tableauValue = [];
      var data = {};
      for (var i = 0 , lgth = app.$boxSelections.length ; i < lgth ; i++){
        data = {
          'select01' : app.$selects01.eq(i).find('select').val(),
          'select02Options' : app.$selects02.eq(i).find('select').html(),
          'select02' : app.$selects02.eq(i).find('select').val(),
          'htmlCarModel' : app.$boxSelections.eq(i).find('.car-model').html(),
          'url' : app.$boxSelections.eq(i).find('.config-content a').attr('href')
        };
        app.tabSelects.push(data);
        if (data.select02)
          { app.tableauValue.push(data.select02); }
        else if (data.select01)
          { app.tableauValue.push(data.select01); }
        else
          { app.tableauValue.push(0); }
      }

    },


    // gestion du visuel voiture et prix lors d'une sélection - data est récupéré en appel ajax à la selection
    editDataModel: function($boxContainer,data){
      if (data){
        $boxContainer.find('figure img').removeClass('hidden').attr('src',data.pictureUrl) ;
        $boxContainer.find('.model-price.cash span').html(data.prices.cash);
        $boxContainer.find('.model-price.monthly span:first-child').html(data.prices.monthly);
        $boxContainer.find('.model-price.monthly span.firstMonth').html(data.prices.firstMonth);
        $boxContainer.find('.infobulle .bulleContent').html(data.prices.legals);
        $boxContainer.find('.cta-configurer').attr('href',data.urlConfig);
      }
      else {
        $boxContainer.find('figure img').addClass('hidden') ;
        $boxContainer.find('.model-price.cash span').html();
        $boxContainer.find('.model-price.monthly span:first-child').html();
        $boxContainer.find('.model-price.monthly span.firstMonth').html();
        $boxContainer.find('.infobulle .bulleContent').html();
        $boxContainer.find('.cta-configurer').attr('href','javascript:void(0)');
      }
    },

    // gestion d'une classe globale de tranche permettant l'affichage ou non du tableau comparateur en dessous des box
    manageMainClass: function(action,laClass){
      var classToChange = (laClass) ? laClass : 'selected_01' ;
      var app = this ;
      if (action === 'remove' && $el.find('.box-selection.'+classToChange).length === 0 ){
        $el.removeClass(classToChange);
        if (classToChange === 'selected_01'){
          app.tableOpen = false;
        }
      }
      if (action === 'add'){
        $el.addClass(classToChange);
        app.openFirstToggle();
        if (classToChange === 'selected_01') { app.tableOpen = true; }
      }
    },

    initSelect: function(select){
      var app = this;
      app.onSelectSelect01(select,true);
      app.onSelectSelect02(select,true);
    },



    getSelectOptions : function(select){
      var optString = $(select).find('option:selected').data('options');
      return optString ;
    },


    // gestion du tableau comparateur lors de la sélection - data est récupéré en appel ajax
    manageComparator : function(data){
      var app     = this;
      var $tpl    = $el.find('#comparatorTable_tpl'),
          tpl     = _.template($tpl.html());

      // generation tableau equipements
      app.$comparatorTable.html(tpl(data));
      var options = {
        afterOpenToggle : app.onToggleOpen,
        afterCloseToggle : app.onToggleClose
      };
      app.accordeonDS = $el.accordeonDs(options);

      $el.find('.toggle-container').each(function(){
        var $toggle = $(this);
        if ($toggle.find('.toggle-content .row').length === 0 ) { $toggle.addClass('hide'); }
        else { $toggle.removeClass('hide'); }
      });
      $el.find('.toggle-content .row').each(function(){
        var $row = $(this);
        if ($row.find('.diff').length !==  0) { $row.addClass('hasDiff'); }
          else { $row.addClass('noDiff'); }
      });
      $el.find('.toggle-container').each(function(){
        var $toggle = $(this);
        if ($toggle.find('.row.hasDiff').length !== 0 ) { $toggle.removeClass('noRowDiff'); }
        else  { $toggle.addClass('noRowDiff'); }
      });
      var $openAllToggle = $el.find('.openAllToggle');
      var $closeAllToggle = $el.find('.closeAllToggle');
      if ($openAllToggle.hasClass('active')) {
          $openAllToggle.trigger('click');
        }
      else if (!$closeAllToggle.hasClass('active')){
        $el.find('.toggle-container .toggle-title:visible').eq(0).trigger('click');
      }

    },


    // méthode "utilitaire"
    filterTab :function(element){
      return element !== 0 ;
    },


    // gestion du loader avec 3 états : affiche - masque - error (lors des erreurs appel ajax)
    manageLoader : function(action){
      var app = this;
      switch (action){
        case 'affiche' : // affiche le loader...
          $el.removeClass('errorLoad');
          var hght = $('.box-selection').height() ;
          app.$loader.find('.col-xs-9').css({'height':hght,'line-height':hght+'px'});
          app.$loader.removeClass('hide');
        break;
        case 'error' :
          $el.addClass('errorLoad');
          app.$loader.addClass('hide');
        break;
        case 'masque' :
          $el.removeClass('errorLoad');
          app.$loader.addClass('hide');
        break;
      }
    },

    // appel AJAX utilisé lors de la suppression d'une sélection pour réinitialiser le tableau
    editComparator: function(){
      var app = this ;
      var dataSent =  {
                        'selects' : app.tableauValue ,
                        'model'   : ''
                      };
      // console.log('envoie de données AJAX : '+dataSent.selects);
      var nbSelected = app.tabSelects.filter(function(element){return (element.select01 !== ''); }).length;
      if (!nbSelected) {  return false; }
      var url = '' ;
      if (app.versionTest){
        url = app.urlJSONTest[nbSelected-1] ;
      }
      else { url =  app.urlJSON ; }
        app.manageLoader('affiche');
        $.ajax({
          url : url,
          dataType: 'json',
          data : dataSent
        }).done(function(data){
          app.manageComparator(data);
          app.sendSelectsToExpands(data);
          app.manageLoader('masque');
        }).fail(function(error,status){
          app.sendSelectsToExpands(0);
          app.manageLoader('error');

        });
    },

    // gestion lors de la sélection du "premier select" d'une box-selection
    onSelectSelect01 : function(select,emptySelect){

      var app = this;
      var $boxSelection =  $(select).parents('.box-selection') ;
      var SelectOptionTpl = _.template($boxSelection.find('script.selectOpt').html());
      var $select02 = $boxSelection.find('select.select02') ;
      app.setTabSelects();

      var nbSelected = app.tabSelects.filter(function(element){return (element.select01 !== ''); }).length;
      var url = '';
      if (app.versionTest){
        url = app.urlJSONTest[nbSelected-1] ;
      }
      else  { url =  app.urlJSON ; }
      if (!emptySelect){
        app.tableauValue[$boxSelection.data('idx')-1] = select.value ;
        // console.log('envoie de données AJAX (select01) : '+app.tableauValue);

        var dataSent =  {
                          'selects' : app.tableauValue ,
                          'model'   : select.value
                        };
        if(!ComparatorTable.noPublish){
          ComparatorTable.compareItems.push(select.value);
          $.publish('comparator.updated', ComparatorTable.compareItems);
          //$.publish('configurator.addedFromComparator', {value: select.value, place: $boxSelection.data('idx')-1});
        }
        app.manageLoader('affiche');
        $.ajax({
          url : url,
          dataType: 'json',
          data : dataSent
        }).done(function(data){
          var templateData = {
            options : app.getSelectOptions(select)
          };
          $select02.html(SelectOptionTpl( templateData ));
          $select02.removeAttr('disabled').selectBox('refresh');
          $boxSelection.addClass('selected_01');

          app.editDataModel($boxSelection,data);
          app.manageMainClass('add','selected_01');
          app.manageLoader('masque');
          app.manageDisableSelects();
          app.manageComparator(data);
          app.sendSelectsToExpands(data);

        }).fail(function(error,status){
          var templateData = {
            options : []
          };
          app.editDataModel($boxSelection,null);
          app.manageMainClass('remove','selected_01');
          $boxSelection.removeClass('selected_01');
          $select02.html(SelectOptionTpl( templateData )).selectBox('refresh');
          $select02.trigger('change');
          app.setTabSelects();
          app.manageDisableSelects();
          app.sendSelectsToExpands(0);
          app.manageLoader('error');
        });
      } else {
        var templateData = {
          options : []
        };
        $select02.attr('disabled','disabled');
        $boxSelection.removeClass('selected_01');
        $select02.html(SelectOptionTpl( templateData )).selectBox('refresh');
        $select02.trigger('change');
        app.manageMainClass('remove','selected_01');
        if (nbSelected) {
          var needChangeSelect = app.manageTabSelects();
          if (needChangeSelect)  { app.manageDeleteSelects(); }
          app.editComparator();
        }
        app.manageDisableSelects();
        app.sendSelectsToExpands();
      }
    },



    // gestion lors de la sélection du "2eme select" d'une box-selection
    onSelectSelect02 : function(select,emptySelect){
      var app = this;

      var $boxSelection = $(select).parents('.box-selection') ;
      app.setTabSelects();

      var nbSelected = app.tabSelects.filter(function(element){return (element.select01 !==  ''); }).length;
      var url ='' ;
      if (app.versionTest){
         url = app.urlJSONTest[nbSelected-1] ;
      }
      else { url =  app.urlJSON ; }
      if (!emptySelect){
        app.tableauValue[$boxSelection.data('idx')-1] = select.value ;

        var dataSent =  {
                          'selects' : app.tableauValue ,
                          'model'   : select.value
                        };
      // console.log('envoie de données AJAX : '+dataSent.selects);
        app.manageLoader('affiche');
        $.ajax({
          url : url,
          dataType: 'json',
          data : dataSent
        }).done(function(data){
          $boxSelection.addClass('selected_02');
          app.editDataModel($boxSelection,data);
          app.manageMainClass('add','selected_02');
          app.manageComparator(data);
          app.manageLoader('masque');

        }).fail(function(error,status){

          app.editDataModel($boxSelection,null);
          app.manageMainClass('remove','selected_02');
          $boxSelection.removeClass('selected_02');
          app.manageLoader('error');
        });
      }
      else {
        app.tableauValue[$boxSelection.data('idx')-1] = 0 ;
        app.editDataModel($boxSelection,null);
        $boxSelection.removeClass('selected_02');
        app.manageMainClass('remove','selected_02');
      }


    },


    // initialisation de l'accordéon et des evenements des boutons "ouvrir tout" , "fermer tout", et "seulement les différences"
    initAccordeon : function(){
      var app = this;
      var options = {
        afterOpenToggle : app.onToggleOpen,
        afterCloseToggle : app.onToggleClose
      };
      app.accordeonDS = $el.accordeonDs(options);
      var $openAllToggle = $el.find('.openAllToggle');
      $openAllToggle.off('click').on('click',
        function(e){
          e.preventDefault();
          $el.find('.manageToggle a:not(".openOnlyDiff")').removeClass('active');
          app.accordeonDS.openAllToggle($el);
          $(this).addClass('active');
      });
      $el.find('.closeAllToggle').off('click').click(
        function(e){
          e.preventDefault();
          $el.find('.manageToggle a:not(".openOnlyDiff")').removeClass('active');
          app.accordeonDS.closeAllToggle($el);
          $(this).addClass('active');

      });

      $el.find('.openOnlyDiff').off('click').click(
        function(e){
          e.preventDefault();
          $el.toggleClass('showOnlyDiff');
          $el.find('.toggle-container');
          $(this).toggleClass('active');
      });
    },

    // Gère l'ouverture des 1ers toggles des tables lors de la selection de la premiere finition
    openFirstToggle : function(){
      var app = this ;
      if (!app.tableOpen) {
        var $toggleContainer = $el.find('.table-comparator-result .toggle-container:first-child');
        app.accordeonDS.openToggle($toggleContainer.find('.toggle-title'));
      }
    },
    onToggleOpen:function(){
      $el.find('.closeAllToggle').removeClass('active');

    },
    onToggleClose:function(){
      $el.find('.openAllToggle').removeClass('active');
    },

    // méthode "utilitaire"
    isEqualTab : function(tab1,tab2){
      if(!tab1.join){return false;}
      if(tab2.length !== tab1.length){return false;}
      var long = tab1.length;
      for(var i = 0; i < long; i++){
        if(tab1[i].select01 !== tab2[i].select01 || tab1[i].select02 !== tab2[i].select02){
            return false;
        }
      }
      return true;
    }
  };


    if ($el.length !== 0){

      manageSticky.init();
      ComparatorTable.init();

        var target = 'expands';
        $('body').on('comparatorChange',function(event,target,action){
          if (target === 'comparateur'){
            // console.log('trigger evenement cible COMPARATEUR');
            ComparatorTable.editSelectByExpands(action);
          }

  /*        if (target == "expands"){

          }
          else if (target == "comparateur"){

          }*/
        });
        /* ZONE DE TESTS */
        /*
        var action = {type:'add',value:'1CCXWB1B3'};
          $('body').trigger('comparatorChange',["comparateur",action]);
        setTimeout(function(){
           action = {type:'add',value:'1CB1B3'};
          $('body').trigger('comparatorChange',["comparateur",action]);
        },2500)
        setTimeout(function(){
           action = {type:'add',value:'1CDSQ3'};
          $('body').trigger('comparatorChange',["comparateur",action]);
        },5000)
        setTimeout(function(){
           action = {type:'remove',indice:0};
          $('body').trigger('comparatorChange',["comparateur",action]);
        },7500)
      */
        /* ------------ */
    }

})();
