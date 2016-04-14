var sitecontainer = {
	init: function(){
		sitecontainer.sitecontainerfixed();
	},
	sitecontainerfixed: function(){
		$html = $('html'),
		$sitecontainer = $('.container'),
		$body = $('body'),
		$toplink = $('.toplink');
		var toplinkbottom = parseInt($toplink.css('bottom')), 
			toplinkwidth = $toplink.width();
		
		if($('.nav-right').length == 1){
			$navright = $('.nav-right');
			var sitecontainermaxwidth = parseInt($sitecontainer.css('max-width')),
				navrightwidth = $navright.width();
		}		
		
		var bodywidth = $body.width(),
			sitecontainerwidth = $sitecontainer.width(),
			toplinkmarginleft = sitecontainerwidth-toplinkwidth-toplinkbottom;
			
		if($('.nav-right').length == 1){
			var navrightmarginleft = sitecontainerwidth-290; 
		}
		
		if(bodywidth > sitecontainermaxwidth){
			if($('.nav-right').length == 1){
				$navright.css({'right': 'inherit', 'margin-left': navrightmarginleft});
				// $navright.css({'right': 'inherit', 'margin-left': navrightmarginleft, 'display': 'block'});
			}
			$toplink.css({'right': 'inherit', 'margin-left': toplinkmarginleft});
		}else{
			if($('.nav-right').length == 1){
				$navright.css({'margin-left': 'inherit', 'right': 0});
				// $navright.css({'margin-left': 'inherit', 'right': 0, 'display': 'block'});
			}
			$toplink.css({'right': 'inherit', 'margin-left': toplinkmarginleft});
		}		
	},
	sitecontainerreset: function(){
		$navigation = $('#navigation');
		$('.overlay-submenu').remove();
		$('#navigation #menu li').removeClass('expanded').find('.sub-menu').hide();
		
		if($('.navmenu-button').length){
			var hNavigation=$navigation.height();
			$navigation.css('top', -hNavigation);
		}
	},
	sitecontainernavreset: function(){
		$submenu = $('#navigation #menu li .sub-menu');
		if($('.navmenu-button').length){
			$('#navigation').removeClass('nav-expanded');
			$submenu.removeAttr('style').find('.sub-menu-content').css('left', -656+'px');
		}else{
			$submenu.removeAttr('style').find('.sub-menu-content').css('left', -656+'px');	
		}
	}
}