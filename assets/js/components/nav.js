/*
 * PSA NDP Navigation
 */
(function($, window, document, undefined) {

	// Create the defaults once
	var pluginName = "navigation",
		defaults = {
			vanim: 500,
			activateClass: 'on',
			menu: '#menu',                      // Selecteur principal du menu
			boxIconMenu: '.display-burger', 	// Conteneur de l'icône qui affiche le menu en mobile
			iconMenu: '.burger',   				// Conteneur de l'icône qui affiche le menu en mobile
			menuItem: '.js-menuItem',           // Class css des liens de premier niveau du menu
			subMenu: '.sub-menu',               // Class css des sous-menus
			subMenuContent: '.sub-menu-content',
			subMenuItem: 'sub-menu-item',
			overlayMenu: 'overlay-menu', 		// Selecteur du masque qui se positionne juste sous le menu
			closeNavigation: '#close-navigation'
		};

	// The actual plugin constructor

	function Navigation(element, options) {
		this.element = element;

		this.options = $.extend({}, defaults, options);

		this._defaults 		= defaults;
		this._name 			= pluginName;
		this.isMobile 		= false;
		this.$navigation 	= $(this.element);
		this.$menu 			= $(this.options.menu); 					// Selecteur principal du menu
		this.$menuItem 		= $(this.options.menuItem); 				// Selecteur liens de premier niveau
		this.$subMenu 		= $(this.options.subMenu); 					// Class css des sous-menus
		this.$secondLevel 	= this.$subMenu.find('.sub-menu-item > a');
		this.$boxIconMenu 	= $(this.options.boxIconMenu);				// Conteneur de l'icône qui affiche le menu en mobile
		this.$buttonClose 	= $('.close-layer');
		this.$overlayMenu 	= $(document.getElementById(this.options.overlayMenu));
		this.activateClass 	= this.options.activateClass;
		this.panelOpened 	= false;

		this._create();
		this.init();
	}

	Navigation.prototype = {

		_create: function () {
			if (!this.$overlayMenu.length) {
				$('body').append('<div id="overlay-menu" class="overlay-menu"></div>');
			}
		},
		/**
		 * Fonction d'initialisation
		 * @type {Navigation}
		 */
		init: function() {

			this.isMobile = this._detectMobile();

			$(window).on('resize', function () {
				this.isMobile = this._detectMobile();
				this._unEqualizer('.sub-menu-item');
			}.bind(this));

			// Click sur un element de premier niveau
			this.$menuItem.on('click', function(e) {
				var $el =$(e.currentTarget),
					activPannel = this.$menu.find('.expanded').length,
					$currentPanelContainer = $el.next('ul');

				if ($currentPanelContainer.length > 0) {
					this._preventDefault(e);
					if (!this.isMobile) { // Desktop
						this._togglePanel($el, $currentPanelContainer, activPannel);
					} else {
						var $obj = $el.parent().find(this.options.subMenu);
						this._toggleMenu($el, $obj, 2);
					}
				}

			}.bind(this));

			// Click sur un element de second niveau
			this.$secondLevel.on('click', function(e) {
				if (this.isMobile) { // Mobile
					var $el =$(e.currentTarget),
						$currentPanel = $el.next('ul');
					if ($currentPanel.length > 0) {
						this._preventDefault(e);
						this._toggleMenu($el, $currentPanel, 3);
					}

				}
			}.bind(this));

			// Click sur le bouton burger
			this.$boxIconMenu.on('click', function(e) {
				var $el = $(e.currentTarget);
				this._preventDefault(e);
				if (!this.isMobile) { // Desktop
					// cas nav showroom
					if (this.panelOpened) {
						this._closePanel();
					}
					if ($el.closest('aside').hasClass('nav-light')) {
						this._toggleMenu($el, this.$menu, 1, 'light');
					}
				}
				else {
					this._toggleMenu($el, this.$menu, 1);
				}
			}.bind(this));

			// au click hors submenu
			$('html').on('click touchstart', function(e) {
				if (!this.isMobile) { // Desktop
					var $target = $(e.target);
					if (!$target.parents('.sub-menu-content').length && $target.hasClass('on') || $target.hasClass('overlay-menu')) {
						this._closePanel();
					}
				}
			}.bind(this));

			// Fermeture du panneau des sous-menu
			this.$buttonClose.on('click', function () {
				this._closePanel();
			}.bind(this));

			// Gestion du scroll en desktop
			$(window).on('scroll', _.throttle(this._closeNavOnScroll.bind(this), 100));

		},
		_closeNavOnScroll: function () {
			if ($('.nav-light').length > 0 && !this.isMobile && window.pageYOffset >= 100) {
				if (this.panelOpened) {
					this._closePanel();
				}
				$('#menu').removeClass('expanded').removeAttr('style');
				$('.display-burger').removeClass(this.activateClass);
			}
		},
		/**
		 * Méthode d'ouverture et de fermeture du menu quand il est en mobile mode accordeon
		 * @param elTarget Objet DOM déclencheur
		 * @param obj Objet DOM qui réceptionne l'action
		 * @param type String permetant d'identifier si on est en mode light
		 * @private
		 */
		_toggleMenu: function (elTarget, obj, level, type) {

			if (elTarget.hasClass(this.activateClass)) {

				if (typeof type !== 'undefined' && type === 'light') {
					obj.css('height', 'auto');
				}

				obj.removeClass('expanded');
				elTarget.removeClass(this.activateClass);

			} else {

				if (level === 1 || level === 2) {
					this.$menu.find('.on').removeClass('on');
					this.$menu.find('.expanded').removeClass('expanded');
				} else if (level === 3) {
					var $root = elTarget.closest('ul');
					$root.find('.on').removeClass('on');
					$root.find('.expanded').removeClass('expanded');
				}

				obj.addClass('expanded');

				if (typeof type !== 'undefined' && type === 'light') {
					obj.css('height', $(document).height());
				}

				elTarget.addClass(this.activateClass);
			}
		},
		/**
		 * Fermeture de tous les sous menus
		 * @param obj
		 * @private
		 */
		_closeAllMenu: function (obj) {
			obj.closest('ul')
				.find('li a.'+this.activateClass)
				.removeClass(this.activateClass)
				.next('ul.expanded')
				.removeClass('expanded');
		},
		/**
		 * Fonction qui prend en charge l'ouverture du panneau en mode desktop
		 * @param elTarget Element declencheur
		 * @param obj Element DOM qui represente le panneau
		 * @private
		 */
		_togglePanel: function (obj, panel, isActivPanel) {
			if (!this.isMobile) {
				if ((!this.panelOpened && isActivPanel === 0 && !panel.hasClass('expanded')) || (this.panelOpened && isActivPanel === 1 && !panel.hasClass('expanded'))) {
					this._closePanel();
					this._openPanel(obj);
				} else if (panel.hasClass('expanded') || (this.panelOpened && isActivPanel === 1 && panel.hasClass('expanded'))) {
					this._closePanel();
				}
			}
		},
		/**
		 * Ouverture du panneau des sous menu
		 * @param obj
		 * @private
		 */
		_openPanel: function (obj) {
			var menuPosLeft = this.$navigation.position().left + this.$navigation.outerWidth(),
				$panel = obj.next();

			$(document.getElementById(this.options.overlayMenu)).addClass('show');
			obj.addClass('on').next('.js-subMenu').addClass('expanded');
			$panel.show();
			$panel.css('left', menuPosLeft);
			$panel.width(this._computePanelWidth());
			this.$buttonClose.show();

			this._equalizer($panel, '.sub-menu-item');

			this.panelOpened = true;
		},
		/**
		 * Fermeture des panneaux de sous menu
		 * @private
		 */
		_closePanel: function () {
			this.$menu.find('> li a.on').removeClass('on').next('ul.expanded').removeClass('expanded');
			this.$buttonClose.hide();
			$(document.getElementById(this.options.overlayMenu)).removeClass('show');
			this.panelOpened = false;
		},
		_computePanelWidth: function () {
			var lCol = 235;
			var gutter = 15;
			var wBtnClose = window.parseFloat($('.close-layer').width(),10);

			if (!this.isMobile) { // Desktop

				var _1Col = window.parseFloat(lCol+wBtnClose, 10),
					_2Col = window.parseFloat((lCol*2)+ 2*gutter+wBtnClose,10),
					_3Col = window.parseFloat((lCol*3)+ 3*gutter+wBtnClose,10);

				if (window.matchMedia("(min-width: 641px) and (max-width: 761px)").matches) {
					return _1Col;
				} else if (window.matchMedia("(min-width: 762px) and (max-width: 989px)").matches) {
					return _2Col;
				} else if (window.matchMedia("(min-width: 990px)").matches) {
					return _3Col;
				}

			}
		},
		/**
		 * Permet fixer une même taille sur tout les éléments frère
		 * d'un même block
		 * @param parentEl JQUERYElement Represente le bloc
		 * @param elSelector String Selecteur permetant de recuperer les elements
		 * sur lesquels on doit appliquer une hauteur
		 * @private
		 */
		_equalizer: function (parentEl, elSelector) {
			if (!this.isMobile) {
				var j = 0;
				parentEl.find(elSelector)
					.each(function (i, element) {
						var $el = $(element);
						if ($el.height() > j) {
							j = $el.height();
						}
					}
				);
				parentEl.find(elSelector).height(j);
			}
			else {
				parentEl.find(elSelector).removeAttr('style');
			}
		},
		_unEqualizer: function (elSelector) {
			this.$menu.find(elSelector).removeAttr('style');
		},
		/**
		 * Function de detection des points de ruptures css
		 * basé sur l'affichage ou non d'un élément déclaré
		 * dans les mediaQuery
		 * @returns {boolean}
		 */
		_detectMobile: function () {
			return !window.matchMedia("(min-width: 641px)").matches;
		},
		/**
		 * Rendre compatible la methode preventDefault de l'objet event
		 * de jQuery avec les vieux navigateurs
		 * @param e
		 */
		_preventDefault: function (e) {
			e = e || window.event;
			if (e.preventDefault) {
				e.preventDefault();
			}
			e.returnValue = false;
		}

 };

	$.fn[pluginName] = function(options) {
		return this.each(function() {
			if (!$.data(this, "plugin_" + pluginName)) {
				$.data(this, "plugin_" + pluginName,
					new Navigation(this, options));
			}
		});
	};

})(jQuery, window, document);
