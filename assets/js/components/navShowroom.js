;(function($, NDP){

	//module private data
	var _data = {
		isFirstClick : true,
		navShowroomContainerSelector : '.js-nav-showroom-container',
		navShowroomSelector : '.js-nav-showroom',
		navShowroomMobileSelector : '.js-nav-showroom-mobile',
		navShowroomHeadSelector : '.js-nav-showroom-head',
		navShowroomListSelector : '.js-nav-showroom-list',
		navShowroomSubListSelector : '.js-nav-showroom-sublist',
		navShowroomItemSelector : '.js-nav-showroom-item',
		navShowroomItemActiveSelector : '#js-nav-showroom-item-active',
		iconToggleSelector : '.js-toggle-icon',
		iconToggleExpandClass : 'toggle-icon-expand',
		iconToggleShrinkClass : 'toggle-icon-shrink',
		scrollCallbackData: {
			$el: null,
			topEl: null
		}
	};

	//Module constructor
	NDP.navShowroom = function($el){

		//If headers are not here, prevent from overflowing with main nav
		if (!$('.slice-pf2').length && !$('.slice-pn7').length) {
			$el.addClass('pt-80');
		}

		_initGTM($el);

		//Create mobile nav showroom
		var $navShowroomContainer = $el.find(_data.navShowroomContainerSelector),
			$navShowroom = $navShowroomContainer.find(_data.navShowroomSelector),
			$clone = $navShowroomContainer.clone();
		$navShowroom.addClass('hide-for-small-only');
		$clone.addClass('nav-showroom-mobile'); //Add class for css layout
		$clone.find(_data.navShowroomSelector).addClass('show-for-small-only js-nav-showroom-mobile');
		if ($('.slice-pn15').length) {
			$clone.insertAfter('.slice-pn15');
		}
		if ($('.slice-pn7').length) {
			$clone.insertAfter('.slice-pn7');
		}


		//Init slide logic in mobile display
		var $navShowroomMobile = $(_data.navShowroomMobileSelector);
		initSlideToggle($navShowroomMobile);

		//Init plugin to hide/show the navigation bar on scroll
		initShowHideTopBar($navShowroomContainer, $navShowroom);
		initShowHideTopBar($clone, $navShowroomMobile);

	};

	function _initGTM($el) {
		var levels = Array.prototype.join.call($('.breadcrumb span').map(function() { return this.textContent; }), '::');
		$el.find('[data-gtm]').each(function() {
			var gtm = $(this).data('gtm');
			gtm.dataList.eventLabel = levels + '::' + gtm.dataList.eventLabel;
			$(this).data('gtm', gtm);
		});
	}

	//Function to initialize slide behavior
	function initSlideToggle($el) {

		var $navShowroomHead = $el.find(_data.navShowroomHeadSelector),
			$navShowroomList = $el.find(_data.navShowroomListSelector),
			$navShowroomItem = $el.find(_data.navShowroomItemSelector),
			$navShowroomItemActive = $el.find(_data.navShowroomItemActiveSelector),
			self = this;

		//Clicking on head
		$navShowroomHead.on('click', function(){

			//On first click, we need to show the active item
			if ( _data.isFirstClick && $navShowroomItemActive.hasClass('nav-showroom-subitem')) {
				var $children = $navShowroomItemActive.parent();
				showHideChildren($children.parent(), $children, _data.isFirstClick);
				_data.isFirstClick = false;
			}
			else {
				//Else resetting nav state
				resetNavState($el, false);
			}

			//Show or hide children of header
			showHideChildren($navShowroomHead, $navShowroomList);

			//Disabling showhidetopbar plugin
			undelegateShowHideTopBarPlugin($el);

		});

		//Clicking on item
		$navShowroomItem.on('click', function(e) {
			if ( $(this).find(_data.navShowroomSubListSelector).length !== 0 ) {
				e.preventDefault();
				resetNavState($el, false, $(this));
				showHideChildren($(this), $(this).find(_data.navShowroomSubListSelector));
			}
			/*
				Event is caught twice (for subitem and item).
				We just need the first event
			*/
			e.stopPropagation();
		});

	}

	function undelegateShowHideTopBarPlugin($el){


		disableShowHideTopBar($el);

		var currentScroll = $(window).scrollTop();
		//Do not change position attribute if $el is at its place of origin (in its container and not sticky)
		if (  $el.css('position') === 'fixed' ) {
			$el.css({position: "absolute", top: currentScroll});
		}
		_data.scrollCallbackData.$el = $el;
		_data.scrollCallbackData.topEl = currentScroll;

		//Binding no more scroll callback than necessary on the scroll event...
		$(window).off('scroll', checkShowHideTopbarNeeded);
		$(window).on('scroll', checkShowHideTopbarNeeded);

	}

	//Function to initialize sticky behavior
	function initShowHideTopBar($container, $stickyEl) {
		$container.showHideTopBar({stickyEl: $stickyEl});
		$stickyEl.trigger('enableShowHideTopBar');
	}

	function enableShowHideTopBar($stickyEl){
		$stickyEl.trigger('enableShowHideTopBar');
	}

	//Function to disable the show hide top bar behavior
	function disableShowHideTopBar($stickyEl){
		$stickyEl.trigger('disableShowHideTopBar');
	}

	function checkShowHideTopbarNeeded(){

		var currentScroll = $(window).scrollTop();
		var elOuterHeight = _data.scrollCallbackData.$el.outerHeight();

		if ( currentScroll < _data.scrollCallbackData.topEl || currentScroll > _data.scrollCallbackData.topEl + elOuterHeight) {

			if ( currentScroll < _data.scrollCallbackData.topEl ) {

				//Reseting the style and state so that the plugin works again with a proper beginning state
				var style = {
					"position": "fixed",
					"left": "inherit",
					"z-index": 11,
					"width": "100%",
					"max-width": "1280px",
					"top": 0
				};

				_data.scrollCallbackData.$el.css(style);
			}

			//ShowHideTopbar plugin is needed, so we turn the current listener off
			$(window).off('scroll', checkShowHideTopbarNeeded);

			//Firing the event that notify the plugin to do its job again
			enableShowHideTopBar(_data.scrollCallbackData.$el);


			resetNavState(_data.scrollCallbackData.$el, true);

		}

	}

	//Function to toggle the arrow upside down
	function toggleIcon($el) {
		$el.find(_data.iconToggleSelector).toggleClass(_data.iconToggleExpandClass + ' ' + _data.iconToggleShrinkClass);
	}

	function showChildren($children, isFirstClick){
		if ( isFirstClick ) {
			//If first click, no slide but simple display
			$children.show();
		}
		else {
			$children.slideDown();
		}
	}

	function hideChildren($children){
		$children.slideUp();
	}

	function checkItemState($parent){
		return $parent.find(_data.iconToggleSelector).hasClass(_data.iconToggleExpandClass);
	}

	//Function to show or hide children elements
	function showHideChildren($parent, $children, isFirstClick) {
		var isClosed = checkItemState($parent);
		if ( isClosed ) {
			showChildren($children, isFirstClick);
		}
		else {
			hideChildren($children);
		}
		toggleIcon($parent);
	}


	function resetNavState($el, resetAll, $keepState){

		var $children = $el.find(_data.navShowroomSubListSelector),
			$parents = $children.parent();

		$parents.not($keepState).each(function(){
			var $parent = $(this),
				isClosed = checkItemState($parent);
			if ( !isClosed ) {
				hideChildren($children);
				toggleIcon($parent);
			}
		});

		if ( resetAll ){
			var $navShowroomHead = $el.find(_data.navShowroomHeadSelector),
				$navShowroomList = $el.find(_data.navShowroomListSelector),
				isHeaderClosed = checkItemState($navShowroomHead);
			if ( !isHeaderClosed ){
				hideChildren($navShowroomList);
				toggleIcon($navShowroomHead);
			}
		}
	}

	$.NDP = NDP;

})(jQuery, window.NDP = window.NDP || {});
