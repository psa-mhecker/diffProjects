var viewManager = function ($root){ this.init($root); };

viewManager.prototype = {
	init:function($root){
		var oThis = this; // OBJECT context
		oThis.$root = $root; // DOM element attached to Object instance
		oThis.$rootParent = $root.parent(); // DOM element attached to Object instance
		
		// DEBUG MODE : SET TO 0 TO DESACTIVATE IT
		oThis.debugMode = 0;

		// DOM elements selectors
		oThis.$controlContainer = oThis.$root.find('.control-btns');
		oThis.$controlCollection = oThis.$controlContainer.find('.btn');
		oThis.$controlSimulation = oThis.$controlContainer.find('.simulation-tog');
		oThis.$switchCollection = oThis.$controlContainer.find('.switch');
		oThis.$viewsContainer = oThis.$root.find('.views');
		oThis.$viewsCollection = oThis.$viewsContainer.find('.view');
		oThis.$viewsCollection.each(function(){
			var $view = $(this);
			if($view.hasClass('outside')){
				oThis.$viewOutside = $view;
			} else if($view.hasClass('inside')){
				oThis.$viewInside = $view;
			}
		});
		oThis.$subviewsInside = oThis.$viewInside.find('.subview');
		oThis.$subviewsInside.each(function(){
			var $view = $(this);
			if($view.hasClass('classic')){
				oThis.$subviewInsideClassic = $view;
			} else if($view.hasClass('simulation')){
				oThis.$subviewInsideSimulation = $view;
			}
		});
		oThis.$subviewsOutside = oThis.$viewOutside.find('.subview');
		oThis.$subviewsOutside.each(function(){
			var $view = $(this);
			if($view.hasClass('classic')){
				oThis.$subviewOutsideClassic = $view;
			} else if($view.hasClass('simulation')){
				oThis.$subviewOutsideSimulation = $view;
			}
		});
		oThis.$canvasModel = $('<canvas id="canvas" width="100%" height="372px"></canvas>');
		oThis.$viewSlickModel = $('<div class="slick"></div>');
		oThis.$viewSlickImage = $('<div class="item"><figure><img class="lazy" src="" data-original="" /></figure></div>');

		// INIT variables
		oThis.activeView = 'outside';
		oThis.loadCount = 0;
		oThis.totalCount = 0;

		// HANDLING SWITCH BUTTONS
		oThis.$switchCollection.each(function(){
			var $btn = $(this);

			$btn.on('click', function(e){
				e.preventDefault();
				if($btn.hasClass('menu-tog')){
					oThis.menuToggle($btn);
				} else if($btn.hasClass('fullscreen-tog')){
					oThis.fullscreenToggle($btn);
				}
			});
		});

		// HANDLING VIEWS BUTTONS
		oThis.$controlCollection.each(function(){
			var $btn = $(this);

			$btn.on('click', function(e){
				e.preventDefault();
				oThis.$controlCollection.removeClass('on');
				$btn.addClass('on');
				if(oThis.activeView != $btn.attr('data-toggle')){
					oThis.$root.removeClass(oThis.activeView);
					oThis.$root.addClass($btn.attr('data-toggle'));
					oThis.activeView = $btn.attr('data-toggle');
					oThis.$root.trigger('togView');
				}
			});
		});

		// HANDLING VIEWS BUTTONS

		oThis.$controlSimulation.each(function(){
			var $btn = $(this);

			$btn.on('click', function(e){
				e.preventDefault();
				if($btn.hasClass('on')){
					$btn.removeClass('on');
					oThis.$activeSubview = 'classic';
					oThis.$subviewInsideClassic.show();
					oThis.$subviewInsideSimulation.hide();
				} else {
					$btn.addClass('on');
					oThis.$activeSubview = 'simulation';
					oThis.$subviewInsideClassic.hide();
					oThis.$subviewInsideSimulation.show();
				}
			});
		});

		// ADD EVENT LISTENER ON TOGGLE VIEW
		oThis.$root.on('togView', function(event){
			oThis.$viewsCollection.each(function(){
				$view = $(this);
				if($view.hasClass(oThis.activeView)){
					$view.show();
				} else {
					$view.hide();
				}
			})
		});

		// ADD EVENT LISTENER ON LOADING CONTENT
		oThis.$root.on('loading', function(event){
oThis.debug(oThis.loadCount + " VS " + oThis.totalCount);
			if(oThis.loadCount==oThis.totalCount){
				oThis.$root.removeClass('loading');
				oThis.$root.addClass('loaded');
				oThis.loadCount = 0;
			}
		});

		// ADD EVENT LISTENER ON VIEW SWITCH
		oThis.$root.on('switchView', function(event, view){
			oThis.$controlCollection.each(function(){
				if($(this).attr('data-toggle')==view){
					$(this).trigger('click');
				}
			});
		});

		// ADD EVENT LISTENER ON VIEW UPDATE
		oThis.$root.on('updateView', function(e, args){
			var ajaxCollection = args.ajaxCollection,
				view = args.view;

oThis.debug('ajaxCollection :');
oThis.debug(ajaxCollection);
oThis.debug('view :');
oThis.debug(view);

			oThis.$controlCollection.each(function(){
				if($(this).attr('data-toggle')==view){
					$(this).trigger('click');
					oThis.update(view, ajaxCollection);
				}
			});
		});

		// SETTING EXISTING VIEWS
		oThis.totalCount = oThis.$viewsCollection.length;
		oThis.$viewsCollection.each(function(){
			oThis.initView(this);
			oThis.$root.trigger('togView');
		});
	}, 
	menuToggle: function($btn){
		var oThis = this;

		if($btn.hasClass('on')){
			$btn.removeClass('on');
		} else {
			$btn.addClass('on');
		}
	}, 
	fullscreenToggle: function($btn){
		var oThis = this;

		if($btn.hasClass('on')){
			$btn.removeClass('on');
		} else {
			$btn.addClass('on');
		}
		oThis.fullscreenMode();
	},
	initView: function(view){
		var oThis = this,
			$view = $(view);

oThis.debug('initializing View :');
oThis.debug(view);

		// CONTENT IS ALREADY HERE
		oThis.$root.addClass('loading');
		if($view.hasClass('inside')){
			oThis.loadingContent($view, 0);
			oThis.createSimulation($.parseJSON(oThis.$subviewInsideSimulation.attr('data-sources')));
		}else {
			oThis.loadingContent($view, 0);
		}

	},
	update: function(view, ajaxCollection){
		var oThis = this,
			ajaxCollection = $.parseJSON(ajaxCollection),
			ajaxCollection1 = ajaxCollection.classic;
oThis.debug('updating ' + view + ' with this JSON');
oThis.debug(ajaxCollection1);
		oThis.totalCount = 1;
		oThis.$root.removeClass('loaded');
		oThis.$root.trigger('switchView',oThis.$controlCollection.attr('[data-view='+view));
		if(view=='inside'){
			var ajaxCollection2 = ajaxCollection.simulation;
oThis.debug(ajaxCollection2);
			oThis.createSimulation(ajaxCollection2);
		}
oThis.debug(ajaxCollection1);
		oThis.createView(ajaxCollection1);
	}, 
	createSimulation: function(ajaxCollection){
		var oThis = this,
			imagesColl = ajaxCollection,
			imagesArr = [],
			$canvas = oThis.$subviewInsideSimulation.find('canvas');
oThis.debug('imagesColl :');
oThis.debug(imagesColl);

		for(var x in imagesColl){
			imagesArr.push(imagesColl[x].src);
		}
oThis.debug(imagesArr.length);
		
		if($canvas.length>0){
			$canvas.remove();
			oThis.inside = '';
		}		

		$canvas = oThis.$canvasModel.clone().appendTo(oThis.$subviewInsideSimulation);

		$canvas.width(oThis.$viewInside.width());
		$canvas.height(oThis.$viewInside.height());
		oThis.loadCount = oThis.loadCount+1;  
		oThis.$root.trigger('loading'); 


	    (function($, Inside, PointOfInterest) {
	        "use strict";
	        oThis.inside = new Inside($canvas[0], oThis.$subviewInsideSimulation);
	        oThis.inside.cubeSize = 100;
	        oThis.inside.init(imagesArr);
	        oThis.inside.start();
	    }(window.jQuery, NameSpace('inside.Inside'), NameSpace('inside.object3D.PointOfInterest')));
oThis.debug(oThis.$subviewInsideSimulation); 

		// ADDING CLICK EVENT ON SIMULATION
		$canvas.on('click touchend', function(){
	        if(!oThis.$subviewInsideSimulation.hasClass('touched')){
	            oThis.$subviewInsideSimulation.addClass('touched');
	        }
		});

		if(oThis.$activeSubview != 'simulation'){
			oThis.$subviewInsideSimulation.hide();
		}
		
	},
	createView: function(ajaxCollection){
		var oThis = this,
			$targetedView,
			viewSlickIndex,
			$slickContainer, 
			$slickSlide,
			$slickImage, 
			lazySrc,
			ajaxCollection = ajaxCollection;

		oThis.$root.addClass('loading');

		// GET DOM Element of the view
		oThis.$viewsCollection.each(function(){
			$view = $(this);
			if($view.hasClass(oThis.activeView)){
				$targetedView = $view.find('.classic');
			} 
		})

		lazySrc = $targetedView.attr('data-lazy'); 

		viewSlickIndex = $targetedView.find('.slick').slick('slickCurrentSlide');
oThis.debug(viewSlickIndex);
oThis.debug('current slick pager ' + viewSlickIndex);

		// EMPTY DOM Element of the view
		$targetedView.empty();

oThis.debug('creating View :');
oThis.debug($targetedView);
oThis.debug('with :');
oThis.debug(ajaxCollection);
oThis.debug(ajaxCollection.length);

		// CLONING SLICK ELEMENT AND APPEND IT IN THE VIEW
		$slickContainer = oThis.$viewSlickModel.clone();
		$slickContainer.appendTo($targetedView);
		for(i=0; i<ajaxCollection.length; i++){
			$slickSlide = oThis.$viewSlickImage.clone();
			$slickImage = $slickSlide.find('img');
oThis.debug($slickImage);
			
			$slickImage.attr('src',lazySrc);
			$slickImage.attr('data-original',ajaxCollection[i].src);
			$slickSlide.appendTo($slickContainer);
		}


		// HERE WE GET SOMETHING FROM THE ajaxCollection parameter
oThis.debug('New Collection is ready !!!');
		oThis.loadingContent($targetedView, viewSlickIndex);

	},
	createSlick: function($viewSlick, viewSlickIndex){
		var oThis = this;

oThis.debug('creating slick : ');
oThis.debug($viewSlick);

			$viewSlick.on('init', function(event, slick){ 
oThis.debug('Slick initialized ');
				oThis.loadCount = oThis.loadCount+1; 
				oThis.$root.trigger('loading'); 
			});


		$viewSlick.slick({
			slide :'.item',
			initialSlide : viewSlickIndex,
			arrows: false,
			dots: true,
			infinite: true,
			speed: 500,
			fade: true,
			cssEase: 'linear'
		});
	}, 
	loadingContent: function($view, viewSlickIndex){
		var oThis = this,
			$viewSlick = $view.find('.slick'), 
			viewSlickIndex = viewSlickIndex, 
			$imgs = $view.find('img.lazy'),
			loadedElements = 0;

		oThis.loadCount = 0;

oThis.debug('loading Content (view) : ');
oThis.debug($view);


		if($imgs.length>0){
oThis.debug('Images : ' + $imgs.length);
oThis.debug($imgs);

			// LAZY IMAGE LOADER OPTION
			var lazyOptions = {
		        'chainable': false,
		        'bind': 'event',
		        'appendScroll': null,
				'attribute':'data-original',
				'removeAttribute':true,
				'effect':'fadeIn',
				'effectTime':250,
	        	beforeLoad: function(element){
oThis.debug('image '+ loadedElements +' is about to be loaded');
	        	},
	        	afterLoad: function(element){
loadedElements++;
oThis.debug('image '+ loadedElements +' was loaded successfully');
	        	},
				onFinishedAll: function(){
oThis.debug('finished loading ' + loadedElements + ' elements');
oThis.debug('all images loaded');
			    	oThis.createSlick($viewSlick, viewSlickIndex);
				}
			}; 
			var lazyImgs = $imgs.lazy(lazyOptions);

oThis.debug('initializing Lazy');		
			lazyImgs.loadAll()


		}

	},
	fullscreenMode: function(){
		var oThis = this,
			$fsContainer = $('.fs-f02-modal');
oThis.debug($fsContainer);
		if($fsContainer.length>0){
			oThis.$root.prependTo(oThis.$rootParent);

			$fsContainer.remove();
		} else {
			$fsContainer= $('<div class="fs-f02-modal"></div>').prependTo('body');
			$fsContainer.css({
				width:$(window).width(),
				height:$(window).height()
			})
			oThis.$root.appendTo($fsContainer);
		}
	},
	debug:function(log){
		var oThis = this;

		if(oThis.debugMode == 1){
			console.log(log);
		}
	}
};



$(document).ready(function($) {
	var viewVehicle = $("section.f02 .viewManager");
	if (viewVehicle.length != 0) {
		viewVehicle.each(function(){
			this.viewManager = new viewManager($(this));
		});
	}
	
	$('.switch-view').each(function(){
		var $btn = $(this);

		$btn.on('click',function(){
			viewVehicle.eq(0).trigger('switchView',$btn.attr('data-view'));
		})

	});

	$('.add-image').each(function(){
		var $btn = $(this),
			args = {
				'view' : $btn.attr('data-view'),
				'ajaxCollection' : $btn.attr('data-collection')
			}

		$btn.on('click',function(){
			viewVehicle.eq(0).trigger('updateView', args);
		})

	});
	
});