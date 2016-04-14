/**
 * Slice name: DF56 - Motorisations
 * Coded by:
 */

'use strict';
ISO.moduleCreate('sliceDF57', function($el) {
    $('.ancre', $el).scroller();
	
	$('.isoPopinOpenLink').on("click", function(){
      $(document).scrollTop(0);
    });
  
});
