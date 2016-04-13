/*
 *  Project: Sneezy
 *  Description: Fluid and multiformat gallery viewer
 *  Author: @Luckypouet
 *  License: None
 *  Require: underscore 1.4.4+, jquery 2.0+, bxSlider 4.1+
 */

// the semi-colon before function invocation is a safety net against concatenated
// scripts and/or other plugins which may not be closed properly.
;(function($) {

	/* Functions */
	var tpl = $('#tplSneezy').html(),
		sneezit = function(e,group){
			e.preventDefault();
			
			var dom = $('#sneezy_'+group);
			if(!dom.length){
				build.call(this,group);
			} else {
				show.call(dom,this._sneezy_index);
			};

		},
		build = function(group){

			/* Gathering informations */
			var items = [];
			$(this._sneezy_group).each(function(){
				var data = this.getAttribute('href');
				items.push(data);
			});

			/* Compile HTML and datas with underscore */
			var compiledTemplate = _.template(tpl,{ id:group, items:items });
			$('body').append(compiledTemplate);

			/* Init slider if multiple */
			var $dom = $('#sneezy_'+group);
			if(1 < items.length){
				$dom.find('.inner').bxSlider({
					startSlide:this._sneezy_index,
					onSlideBefore:function(){
						setToolsPosition();
					},
					onSlideAfter:function(){
						/* force iframe rendering on Chrome */
						window.scrollBy(0,1);
						window.scrollBy(0,-1);
					},
					onSliderLoad:function(){
						/* Once built show it! */
						show.call($dom,this._sneezy_index);
					}
				});
			} else {
				/* Once built show it! */
				show.call($dom,this._sneezy_index);				
			};

			/* Events */
			$dom.find('img').load(setToolsPosition);
			$dom.find('.closer, .popClose').click(function(){
				hide.call($dom);
			});
			$dom.find('.item').click(function(e){
				if(e.target != this) return;
				hide.call($dom);
			});

		},
		show = function(){
			
			var $me = this;
			$me.stop(true,false).animate({ opacity:1 },250);
			setToolsPosition();

		},
		hide = function(){

			var $me = this;
			$me.stop(true,false).fadeOut(250,function(){
				$me.remove();
			});

		},
		/* setToolsPosition */
		setToolsPosition = function(){
			
			$('.sneezies:visible').each(function(){
				var $me = $(this);
				$me.find('img, iframe').each(function(){
					var $closer = $(this).parent().find('.closer'),
						top = this.offsetTop+parseInt($(this).css('paddingTop')),
						bottom = this.offsetTop-parseInt($(this).css('paddingTop'))+this.offsetHeight,
						left = (this.offsetWidth/2)-parseInt($(this).css('paddingRight'))-$closer.width()/2;
					
					$closer.css({ top:top, marginLeft:left });
					$(this).parent().find('.popClose').css({ top:bottom });

				});
			});

		};

	/* Find and store sneezy items by group */
	window._sneezies = {};

	$('[data-sneezy]').each(function(i){
		
		/* One build per DOM */
		if(this._sneezy_group) return;

		/* Check if has group and if group already created */
		var group = this.getAttribute('data-sneezy') || 'sneezy'+i;
		
		/* Exist? */
		if(!window._sneezies[group]){
			window._sneezies[group] = [];
		};
		
		/* Store and associate */
		this._sneezy_index = window._sneezies[group].length;
		window._sneezies[group].push(this);
		this._sneezy_group = window._sneezies[group];

		/* Events */
		$(this).click(function(e){
			sneezit.call(this,e,group);
		});

	});

	/* Overall events */
	$(window).resize(setToolsPosition);

})(jQuery);