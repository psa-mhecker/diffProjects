$.fn.topLink = function(vanim) {

  var topLink        = $('.toplink'),
      condition2Fois = ($(window).height() * 2) < $(document).height();

  // Afficher le button onScroll
  $(window).on('scroll',function(){
    var $scroll  = $(this).scrollTop(),
        $timer   = 250;
    if (condition2Fois && $scroll > ($(window).height() * 2)) topLink.fadeIn($timer);
    else topLink.fadeOut($timer);
  });
  // Au clic remonte en haut de la page
  topLink.on('click', function(){
    $("html, body").animate({ scrollTop: 0 }, 1000);
  });
  
   // Version Mobile
  if (window.isMobile) {
	$('.toplinkMobile').on('click', function(){
		$("html, body").animate({ scrollTop: 0 }, 1000);
	});
  }
  
};
