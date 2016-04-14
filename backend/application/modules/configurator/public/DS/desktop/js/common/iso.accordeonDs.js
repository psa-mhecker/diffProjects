'use strict';
(function(){
	/*
	Initialise un toggle :
		< .toggle-container >
			< .toggle-title > Le titre "lien" qui ouvre/ferme le toggle </ .toggle-title >
			< .toggle-content > Le contenu du toggle qui est ouvert/fermé </ .toggle-content >
		</ .toggle-container >

		JS : sur élément parent (tranche par exemple)
			var accordeonDS = $el.accordeonDs(afterOpenToggle,afterCloseToggle);
			où : afterOpenToggle est une function de callback appelée lorsqu'un toggle s'est ouvert' - facultative
			où : afterCloseToggle est une function de callback appelée lorsqu'un toggle s'est fermé' - facultative

		méthodes utilisables ensuite :
		accordeonDS.openAllToggle($el); pour ouvrir toutes les toggles contenu dans un objet JqueryDOM $el
		accordeonDS.closeAllToggle($el); pour fermer toutes les toggles contenu dans un objet JqueryDOM $el

		options = {
			'afterOpenToggle' : null ,
			'afterCloseToggle' : null,
		}
	*/
	$.fn.accordeonDs = function(options){
		var $this 		= $(this).eq(0);

      	var accordeon = {
				$container:        $this.find('.toggle-container'),
				$link:             $this.find('.toggle-title'),
				content:           '.toggle-content',
				title:             '.toggle-title',
				$content:          $this.find('.toggle-content'),
				afterOpenToggle:   null,
				afterCloseToggle:  null,
				beforeOpenToggle:  null, // si return False, stop le fonctionnement et empeche l'ouverture du toggle
				beforeCloseToggle: null,
				speed:             200,
				init:              function(options){
					var app = this ;
					app = $.extend(app,options);
					/*
					vérifier que l'extend fonctionne correctement pour supprimer ces lignes
					if (options.afterOpenToggle) { app.afterOpenToggle = options.afterOpenToggle; }
					if (options.afterCloseToggle) { app.afterCloseToggle = options.afterCloseToggle; }
					if (options.beforeOpenToggle) { app.beforeOpenToggle = options.beforeOpenToggle; }
					if (options.beforeCloseToggle) { app.beforeCloseToggle = options.beforeCloseToggle; }
					if (options.$container) {app.$container = options.$container}
					if (options.$link) {app.$link = options.$link}
					*/


					// app.$content.hide();

					app.$link.click(function(event){
						var link = this ;
						app.openToggle(link,app.afterOpenToggle,app.afterCloseToggle,app.beforeOpenToggle,app.beforeCloseToggle,event);
					});
				},
				openToggle : function(link,afterOpenToggle,afterCloseToggle,beforeOpenToggle,beforeCloseToggle,event){
					// this correspond au lien-titre
					var app = this ;
					var $title = $(link) ;
					// fermeture de toggle
					 if ($title.next(app.content+':visible').length !== 0) {
					 	if (beforeCloseToggle) { beforeCloseToggle(event,$title); }

			            app.forceCloseToggle(
			            	$title,
			            	function(){ if (afterCloseToggle) { afterCloseToggle(event,$title); } },
			            	event
			            );
			        }
			        // ouverture de toggle
			        else {
			        	if (beforeOpenToggle) { var stopOpen = beforeOpenToggle(event,$title); }
						if (stopOpen === false) {return false;}
					 	app.forceOpenToggle(
			            	$title,
			            	function(){ if (afterOpenToggle) { afterOpenToggle(event,$title); } },
			            	event
			            );
					}
			        return false;
				},
				forceOpenToggle : function($link,afterOpenToggle,event){
					var app = this;
					$link.addClass('close').next(app.content).slideDown(app.speed,function(){
						var $toggleTitleIncomp = $link.parents('.cont-toggle'),
								$heightContenToggle = $link.parents('.box-toggle').outerHeight(true);
									$link.parent().addClass('toggle-open');
									if ($link.parents('.box-toggle')){
									$toggleTitleIncomp.animate({height: $heightContenToggle});
								}
			            if (afterOpenToggle) { afterOpenToggle(event,$link); }
		            });
				},
				forceCloseToggle : function($link,afterCloseToggle,event){
					var app = this ;
			        $link.removeClass('close') ;
					$link.next('.toggle-content').slideUp(app.speed,function(){
						var $toggleTitleIncomp = $link.parents('.cont-toggle'),
								$heightContenToggle = $link.parents('.box-toggle').outerHeight(true);
		            	$link.parent().removeClass('toggle-open');
									if ($link.parents('.box-toggle')){
									$toggleTitleIncomp.animate({height: $heightContenToggle});
								}
		            	if (afterCloseToggle) { afterCloseToggle(event,$link); }
			        });
				},
				openAllToggle : function($containerCible){
					var app = this;
					$containerCible.find(app.title).addClass('close');
					$containerCible.find(app.title).next(app.content).slideDown(app.speed,function(){});
					$containerCible.find(app.title).parent().addClass('toggle-open');
				},
				closeAllToggle : function($containerCible){
					var app = this;
					$containerCible.find(app.title).removeClass('close');
					$containerCible.find(app.title).next(app.content).slideUp(app.speed);
					$containerCible.find(app.title).parent().removeClass('toggle-open');
				},

			};
			accordeon.init(options);
		return accordeon ;
	};
})();
