function mapControl( attributeId ) {
    /* Déclaration des variables  */
    var geocoder;
    var map;
    var markers = new Array();
    var i = 0;
    var mapid = attributeId + '_MAP', latid  = attributeId + '_LATITUDE', longid = attributeId + '_LONGITUDE';
	var addressid = attributeId + '_ADDRESS', zoomId = attributeId + '_ZOOM';

    /* Initialisation de la carte  */
    function initialize() {
        /* Instanciation du geocoder  */
        geocoder = new google.maps.Geocoder();
        zoom = 10;
        if ( document.getElementById( latid ).value && document.getElementById( latid ).value != 0 )
		{
            var current = new google.maps.LatLng(document.getElementById( latid ).value, document.getElementById( longid ).value);
            if (document.getElementById(zoomId)) {
			    zoom = parseInt(document.getElementById(zoomId).value);
			}
		}
		else
		{
			var current = new google.maps.LatLng(0, 0);
		}
        
        
        var mapOptions  = {
            zoom: zoom,
            center: current,
            mapTypeId: google.maps.MapTypeId.ROADMAP 
        }
        /* Chargement de la carte  */
        map = new google.maps.Map(document.getElementById(mapid), mapOptions );
        
    }

    /* Fonction de géocodage déclenchée en cliquant surle bouton "Geocoder"  */
    function codeAddress() {
        /* Récupération de la valeur de l'adresse saisie */
        google.maps.event.trigger(document.getElementById(mapid), 'resize');
        var address = document.getElementById(addressid).value;
        /* Appel au service de geocodage avec l'adresse en paramètre */
        geocoder.geocode( { 'address': address}, function(results, status) {
            /* Si l'adresse a pu être géolocalisée */
            if (status == google.maps.GeocoderStatus.OK) {
                /* Récupération de sa latitude et de sa longitude */
                document.getElementById(latid).value = results[0].geometry.location.lat();
                document.getElementById(longid).value = results[0].geometry.location.lng();
                map.setCenter(results[0].geometry.location);
                /* Affichage du marker */
                var marker = new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location
                });
                /* Permet de supprimer le marker précédemment affiché */
                markers.push(marker);
                if(markers.length > 1)
                    markers[(i-1)].setMap(null);
                i++;
                document.getElementById( attributeId + '_ADDRESS_BTN_REST').disabled = false;
            } else {
                alert("Le geocodage n\'a pu etre effectue pour la raison suivante: " + status);
            }
        });
    }
    
    function restoretLatLngFields() {
        document.getElementById( latid ).value     = document.getElementById(attributeId + '_ADDRESS_HIDDEN_LAT').value;
		document.getElementById( longid ).value    = document.getElementById(attributeId + '_ADDRESS_HIDDEN_LONG').value;
		document.getElementById( addressid ).value = document.getElementById(attributeId + '_ADDRESS_HIDDEN').value;
		if ( document.getElementById( latid ).value && document.getElementById( latid ).value != 0 )
		{
            var myLatlng = new google.maps.LatLng(document.getElementById( latid ).value,document.getElementById( longid ).value);
            map.setCenter(myLatlng);
            var marker = new google.maps.Marker({
                map: map,
                position: myLatlng
            });
            /* Permet de supprimer le marker précédemment affiché */
            markers.push(marker);
            if(markers.length > 1)
                markers[(i-1)].setMap(null);
            i++;
        }
		
		document.getElementById( attributeId + '_ADDRESS_BTN_REST' ).disabled = true;
		return false;
    }
  
    document.getElementById( attributeId + '_ADDRESS_BTN_FIND').onclick = codeAddress;
    document.getElementById( attributeId + '_ADDRESS_BTN_REST').onclick = restoretLatLngFields;
    
    initialize();
    
}
