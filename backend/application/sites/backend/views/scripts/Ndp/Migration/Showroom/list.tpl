{$form}


{literal}

<script src="/library/External/jquery/blockui/js/jquery.blockUI.js" type="text/javascript"></script>

<script>
    // Personalized spinner loading with unlimited spinner display
    function showLoadingUnlimited(id, state){
        //l'affichage du block 'Traitement en cours...' ne fonctionne pas sous IE10
        var myNav = navigator.userAgent.toLowerCase();
        var isIE = ((myNav.indexOf('msie') != -1) ? parseInt(myNav.split('msie')[1]) : false);
        //

        if(isIE===false || isIE<9)
        {

            if (!id) {
                id = 'body';
            }
            if (!state) {
                $(id).unblock();
            } else {
                $(id).block({ css: {
                    border: 'none',
                    padding: '25px',
                    backgroundColor: '#000',
                    '-webkit-border-radius': '10px',
                    '-moz-border-radius': '10px',
                    width: '20%',
                    opacity: '.7',
                    color: '#fff',
                    cursor:'wait'
                },
                    overlayCSS:  {
                        backgroundColor:'#fff',
                        opacity:        '0'
                    },
                    message: '<img src="/images/ajax-loader.gif" alt=""/><h1>Traitement en cours...</h1>',
                    fadeIn:  200,
                    fadeOut:  200});
            }
        }
    }

    if (typeof(checkShowRoom) == 'function') {
        checkShowRoomO = checkShowRoom;
        checkShowRoom = function(obj) {
            if (checkShowRoomO(obj)) {
                if (showLoadingUnlimited) {
                    showLoadingUnlimited('body',true);
                }
                return true;
            } else {
                return false;
            }
        }
    }

</script>
{/literal}
