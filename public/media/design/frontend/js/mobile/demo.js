
/* GTM Functions */

$('[data-gtm]').click(function(){
	var value = $(this).attr('data-gtm');
});

window.addEventListener('load',function(){

	new gtm_listener.viewed(document.querySelector('#gtm-visibility-test'),function(){
	},100);

	new gtm_listener.dragged(document.querySelector('.dragnchange .drag'),function(){
	},100);

},false);
