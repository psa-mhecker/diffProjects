/**
 * Slice name: CF53 - Config AC Desktop FINITION
 * Coded by:
 */
 'use strict';
ISO.moduleCreate('sliceDF58', function($el, param) {

	$('.anchor2', $el).scroller({
		cible: 'class'
	});
	
	//toggle code
	(function () {
		var _opened;
		var _toggle_button;
		var _toggle_content;
		var _lastTop;
		
		init($(".parent-toggle"), $("#target-toggle"));
		function init(toggle_button, toggle_content)
		{
			_opened = true;
			_toggle_button = toggle_button;
			_toggle_content = toggle_content;
			bindEvents();
		}
		
		function bindEvents()
		{
			_toggle_button.on("click", onToggle);
		}
		
		function onToggle()
		{
			 _toggle_content.slideToggle( 400, onToggleComplete);
		}
		
		function onToggleComplete()
		{
			if(_opened)
			{
				_toggle_button.removeClass("opened");
				
				$('html, body').animate({ scrollTop: _lastTop }, 500);
			}
			else
			{
				_toggle_button.addClass("opened");
				
				var _top    = _toggle_button.offset().top;
				_lastTop = _top;
				
				$('html, body').animate({ scrollTop: _top }, 500);
			}
			
			_opened = !_opened;	
		}
	})();
	//end toggle code
	
	//filter code:
	(function () {
		//map structure: <jquery_object, filter_infos>
		var lamesMap;
		
		var lamesArray;
		
		//we keep track of the active filters
		var activeFilters;
		
		//lists of jquery_object
		var checked_energies;
		var checked_boites;
		
		//maps used by isBoite() function
		var energy_types;
		var boites_types;
		
		function init()
		{
			lamesMap = {};
			lamesArray = [];
			activeFilters = [];
			checked_energies = 0;
			checked_boites = 0;
			energy_types = {};
			boites_types = {};
			
			$('.lame').each(indexLame);
			
			bindEvents();
			applyMobileFix();
		}
		
		//maps initialization code
		function indexLame()
		{
			var lame = $(this);
			var compatible = lame.attr("compatible");
			
			if(compatible=="true")
			{
				var _energyType = lame.attr("energie");
				var _boiteType = lame.attr("boite");
				
				lamesArray.push(lame);
				
				lamesMap[lamesArray.length-1] = {
					energyType:_energyType,
					boiteType:_boiteType
				};
				
				if(!energy_types[_energyType])
					energy_types[_energyType] = true;
				
				if(!boites_types[_boiteType])
					boites_types[_boiteType] = true;
			}
		}

		function applyMobileFix()
		{
			if(isMobile())
			{
				 $(".filtre").addClass("isMobile");
			}
		}
		
		function bindEvents()
		{
			$(".filtre").on("mouseup", onCheckBoxTriggered);
			$(".showAllFiltre").on("click", resetFilters);
		}
		
		function updateLamesView()
		{
			for(var i in lamesMap)
			{
				var lame = lamesArray[i];
				var lame_infos = lamesMap[i];
				
				if(
					(activeFilters.indexOf(lame_infos.energyType)>=0 || checked_energies==0) &&
					(activeFilters.indexOf(lame_infos.boiteType)>=0 || checked_boites==0)
				)
				{
					lame.css("display", "block");
				}
				else
				{
					lame.css("display", "none");
				}
			}
		}
		
		function onCheckBoxTriggered()
		{
			var check_box = $(this).find("input"),
				type = check_box.attr("value"),
				checked = $(this).hasClass("checked");
			
			if(!checked)
			{
				activeFilters.push(type);
				
				if(isBoite(type))
					checked_boites++;
				else
					checked_energies++;

				$(this).addClass("checked");
			}
			else
			{
				var index = activeFilters.indexOf(type);
				activeFilters.splice(index, 1);
				
				if(isBoite(type))
					checked_boites--;
				else
					checked_energies--;

				$(this).removeClass("checked");
				
			}

			
			updateLamesView();
			
		}
		
		function resetFilters()
		{
			$(".filtre").each(resetCheckBox);
		}
		
		function resetCheckBox()
		{
			var check_box = $(this).find("input"),
				type = check_box.attr("value"),
				checked = $(this).hasClass("checked");
				
			if(checked)
			{
				$(this).trigger("click");
				
				var index = activeFilters.indexOf(type);
				activeFilters.splice(index, 1);
				
				if(isBoite(type))
					checked_boites--;
				else
					checked_energies--;
				
				updateLamesView();

				$(this).removeClass("checked");
			}
			else
			{
				$(this).addClass("checked");
			}
		}

		function isMobile()
		{
			if( navigator.userAgent.match(/Android/i)
			|| navigator.userAgent.match(/webOS/i)
			|| navigator.userAgent.match(/iPhone/i)
			|| navigator.userAgent.match(/iPad/i)
			|| navigator.userAgent.match(/iPod/i)
			|| navigator.userAgent.match(/BlackBerry/i)
			|| navigator.userAgent.match(/Windows Phone/i)
			){
				return true;
		  }
		  else
		  {
		  	return false;
		  }
		}
		
		function isBoite(type)
		{
			if(boites_types[type])
				return true;
			else
				return false;
		}
		
		init();
	})();
	//end filter code

});
