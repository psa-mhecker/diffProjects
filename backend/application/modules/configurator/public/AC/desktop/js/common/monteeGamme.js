'use strict';
ISO.moduleCreate('monteeGamme', function($el, param) {
	var monteeGamme = {
		$container: $('.itemContainer',$el) ,
		wWidth:     0 ,
		resize:     null,
		$contentsJauge : $('.itemContainer .itemContent',$el),
		$contentBusiness : $('.itemContainer .itemsContent .itemBusiness',$el),
		$marginContent : $('.itemContainer .marginContent div',$el),
		init:       function(){
			var app = this;
			app.manageTableWidth();

			var resizeTimer ;
			$(window).resize(function(){
	        clearTimeout(resizeTimer);
	        resizeTimer =	setTimeout(function(){
				  				app.manageTableWidth();
				  			},250);
			});
			if($('html').hasClass('touch')) {
				app.$container.find('.itemsLink td').click(function(){
					var index = $(this).index();
					app.$container.find('.itemsContent td').eq(index).has('.itemContent').addClass('mouseenter');
				});
				app.$container.find('.itemsLink td').click(function(){
					var index = $(this).index();
					app.$container.find('.itemsContent td').eq(index).has('.itemContent').removeClass('mouseenter');
				});
			}
			else {
				app.$container.find('.itemsLink td').mouseenter(function(){
					var index = $(this).index();
					app.$container.find('.itemsContent td').eq(index).has('.itemContent').addClass('mouseenter');
				});
				app.$container.find('.itemsLink td').mouseleave(function(){
					var index = $(this).index();
					app.$container.find('.itemsContent td').eq(index).has('.itemContent').removeClass('mouseenter');
				});
			}
			$.subscribe('monteeGamme.changeSelectedItem', this.changeSelectedItem);
		},
		changeSelectedItem: function (e, finId){
			$el.find('.ancre-lames').removeClass('active');
			$el.find('#ancre-lame-'+finId).addClass('active');
		},
		manageTableWidth : function(){
			var app = this;
			app.wWidth = $el.width();
			/*
			// POUR UNE GESTION DES GRANDS MOTS
			app.$contentsJauge.css('width','inherit');
			app.$contentsJauge.each(function(){
				var $content = $(this);
				// $content.css('width','inherit');
				var index = $content.parent().index();
				var w = app.$container.find('.itemsLink td').eq(index).width();
				var newWidth = ( w < app.wWidth/10 ) ? app.wWidth/10 : w ;

				$content.width(newWidth);
			}); */

			/*
			POUR UNE TAILLE FIXE DE 10% DES COLONNES
			*/
			app.$contentsJauge.width( app.wWidth/10 );
			app.$container.find('.itemsLink div').width(app.wWidth/10);
			app.$contentBusiness.width(app.wWidth/10);
			app.$marginContent.width(app.wWidth/100);
		}
	};
	monteeGamme.init();
});
