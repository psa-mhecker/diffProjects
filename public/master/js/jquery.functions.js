$(document).ready(function () {

    //pop up connexion prévisu
    if(jQuery.isFunction($("#previewLoginBox").overlay)){
        //Page d'accueil
        $("#previewLoginBox").overlay({
            // custom top position
            top: 260,

            // some mask tweaks suitable for facebox-looking dialogs
            mask: {

            // you might also consider a "transparent" color for the mask
            color: '#fff',

            // load mask a little faster
            loadSpeed: 200,

            // very transparent
            opacity: 0.9
            },

            // disable this for modal dialog-type of overlays
            closeOnClick: false,

            // load it immediately after the construction
            load: true
        });
    }
    //bandeau choix device
    if(jQuery.isFunction($("#previewUserAgent").overlay)){
        //Page d'accueil
        $("#previewUserAgent").overlay({
            // custom top position
            top: 260,

            // some mask tweaks suitable for facebox-looking dialogs
            mask: {

            // you might also consider a "transparent" color for the mask
            color: '#fff',

            // load mask a little faster
            loadSpeed: 200,

            // very transparent
            opacity: 0.9
            },

            top:0,

            // disable this for modal dialog-type of overlays
            closeOnClick: false,

            // load it immediately after the construction
            load: true
        });
    }

    $("#select_device").change(function (){
        var schedule = '';
        if($("#is_schedule").val()){
            schedule = '&schedule=1';
        }        
        document.location.href = location.pathname+"?pid="+$("#pid_preview_hidden").val()+ schedule +"&preview=1&useragent="+encodeURIComponent($(this).val());
    });

	initCarousel();


$(window).resize( function() {
	initCarousel();
});

	/*

	$('#NewsList').masonry({
	  itemSelector: 'article',
	  // set columnWidth a fraction of the container width
	  columnWidth: function( containerWidth ) {
		return containerWidth / 2;
	  }
	});

	*/



});


function initCarousel(){
		if(jQuery('#Promotion .regular').css('display') != 'none') {
	    	jQuery('#Promotion .regular').jcarousel({scroll:1, wrap:'circular'});
		}

		if(jQuery('#Promotion .mobile').css('display') != 'none') {
			jQuery('#Promotion .mobile').jcarousel({scroll:1, wrap:'circular'});
		}


		if(jQuery('#Offres .regular').css('display') != 'none') {
	    	jQuery('#Offres .regular').jcarousel({scroll: 3});
			//{scroll:3, wrap:'circular'}
		}
		if(jQuery('#Offres .mobile').css('display') != 'none') {
	    	jQuery('#Offres .mobile').jcarousel({scroll: 2});
			//{scroll:3, wrap:'circular'}
		}
	}