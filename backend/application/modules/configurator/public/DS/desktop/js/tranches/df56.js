/**
 * Slice name: DF56 - Motorisations
 * Coded by:
 */

'use strict';
ISO.moduleCreate('sliceDF56', function($el) {
    $('.ancre', $el).scroller();

 /* var $minHeight = 0;
      $el        = $('.lame', $el);

  for( var i = 0; i < $('.minHeight', $el).length; i++ ){
    var $this = $('.minHeight:eq('+ i +')', $el),
        $thisHeight = $this.outerHeight(true);

    $this.attr('data-height', $thisHeight);

    if( $thisHeight > $minHeight ){
      $minHeight = $thisHeight;
    }
  }
  $('.minHeight', $el).animate({ minHeight: $minHeight }, 250);

  $('.lame').each(function(){
  	if( $('label.checked', this).length ){
      $(this).toggleClass('checked');
    }
  }); */
  
	var visible = true;

	$('.toggle').on('click', function(e){
		if(visible)
			$(this).parent().find('.notification').addClass('invisible');   
		else
			$(this).parent().find('.notification').removeClass('invisible');   

		visible = !visible;
	});
  
});
