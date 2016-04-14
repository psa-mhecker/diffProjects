/* infobulle */
ISO.moduleCreate('iso-infobulle', function($el, param) {


  var isTablet = false;
  var isOpen = false;
  var ignoreNextEvent = false;
   
  var registredClass;

  //SCROLL ADD TO INFOBULLE
  //$('.scroller', $el).mCustomScrollbar({
  //  theme: "minimal",
  //  scrollInertia : 0
  //});

  //ON SCROLL AND RESIZE EVENT
  /*$( window ).on('resize scroll',function() {
    var hW      = $(window).height(),
        wW      = $(window).width(),
        halfWw  = parseInt(wW/2),
        halfHw  = parseInt(hW/2);
    if(isOpen) {
      var $infobulle = $('[data-infobulleo="'+ $('.infoClassB.selected').data('infobulle') +'"]');//infobulle selected and open
      var pLeft  = $('.infoClassB.selected').offset().left - $(window).scrollLeft(),
          pTop   = $('.infoClassB.selected').offset().top - $(window).scrollTop();

      //on a 4 cas de la postion de LeftTop - RightTop - LeftBottom - RightBottom
      var classPosition = (pTop >= halfHw) ? "bottom" : "top";
          classPosition += (pLeft >= halfWw) ? "-right" : "-left";


      //Infobulle
      $infobulle.removeClass(registredClass);
      $infobulle.addClass(classPosition);
      if(classPosition === 'top-left') $infobulle.css({ 'left' : pLeft - 200  , 'top' : pTop + 25 + 16 });
        if(classPosition === 'top-right') $infobulle.css({ 'left' : pLeft - $infobulle.outerWidth() + 180  , 'top' : pTop + 25 + 16 });
        if(classPosition === 'bottom-right') $infobulle.css({ 'left' : pLeft - $infobulle.outerWidth() + 180  , 'top' : pTop - $infobulle.outerHeight() - 16 });
        if(classPosition === 'bottom-left') $infobulle.css({ 'left' : pLeft - 200  , 'top' : pTop - $infobulle.outerHeight() - 16 });
        registredClass = classPosition;

    }
  });*/
	
	//is this mobile...
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
		//ON CLICK EVENT
		$('[data-infobulle]').on('click', onOpen);

		//FERMETURE INFOBULLE
		$('body, [data-infobulle]').on('click', onClose);
		
		isTablet = true;
	}
	else
	{
		//ON CLICK EVENT
		$('[data-infobulle]').on('mouseover', onOpen);
		
		//FERMETURE INFOBULLE
		$('[data-infobulle]').on('mouseleave', onClose);
		$('body').on('click', onClose);
	}
	
	function onOpen(e){
		
		e.stopPropagation();

		if(isOpen || (ignoreNextEvent && isTablet))
		{
			return;
		}

		$win = $(window);

		var hW      = $win.height(),
			wW      = $win.width(),
			halfWw  = parseInt(wW/4),
			halfHw  = parseInt(hW/4);

		var $infobulle = $('[data-infobulleo="'+ $(this).data('infobulle') +'"]');

		var pLeft  = $(this).offset().left - $win.scrollLeft(),
			pTop   = $(this).offset().top - $win.scrollTop();

		$('.infoClassB').removeClass('selected');
		$(this).addClass('selected');
		$('.infobulle').removeClass('open');
		$('.infobulle').removeClass(registredClass);

		//on a 4 cas de la postion de LeftTop - RightTop - LeftBottom - RightBottom
		var classPosition = (pTop >= halfHw) ? "bottom" : "top";
			classPosition += (pLeft >= halfWw) ? "-right" : "-left";

		//Infobulle
		isOpen = true;
		$infobulle.addClass('open');
		/*$infobulle.addClass(classPosition);
		if(classPosition === 'top-left') $infobulle.css({ 'left' : pLeft - 200  , 'top' : pTop + 25 + 16 });
		  if(classPosition === 'top-right') $infobulle.css({ 'left' : pLeft - $infobulle.outerWidth() + 180  , 'top' : pTop + 25 + 16 });
		  if(classPosition === 'bottom-right') $infobulle.css({ 'left' : pLeft - $infobulle.outerWidth() - 250  , 'top' : pTop - $infobulle.outerHeight() - 115 });
		  if(classPosition === 'bottom-left') $infobulle.css({ 'left' : pLeft - 200  , 'top' : pTop - $infobulle.outerHeight() - 16 });
		  registredClass = classPosition;*/
		  
		ignoreNextEvent = true;
  }
  
	function onClose(e){
		
		if(!isOpen || (ignoreNextEvent && isTablet))
		{
			ignoreNextEvent = false;
			return;
		}
		
		$('.infobulle').removeClass('open');
		$('.infobulle').removeClass(registredClass);
		$('.info').removeClass('selected');
		isOpen = false;
	}
	
});
