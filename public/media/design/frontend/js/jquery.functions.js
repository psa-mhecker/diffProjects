$(document).ready(function () {

    //pop up connexion prévisu
    if(jQuery.isFunction($("#previewLoginBox").overlay)){
        //Page d'accueil
        $("#previewLoginBox").overlay({
            // custom top position
            top: 100,
            left : 15,
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
        document.location.href = location.pathname+"?pid="+$("#pid_preview_hidden").val()+"&preview=1&useragent="+encodeURIComponent($(this).val());
    });

});