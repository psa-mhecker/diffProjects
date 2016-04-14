function mapControl( attributeId ) {
	var mapid = attributeId + '_MAP', latid  = attributeId + '_LATITUDE', longid = attributeId + '_LONGITUDE';
	var geocoder = null, addressid = attributeId + '_ADDRESS';

	var showAddress = function()
	{
		var address = document.getElementById( addressid ).value;
		if ( geocoder )
		{
			geocoder.getLatLng( address, function( point )
			{
				if ( !point )
				{
					alert( address + " introuvable" );
				}
				else
				{
					map.setCenter( point, 13 );
					map.clearOverlays();
					map.addOverlay( new GMarker( point ) );
					updateLatLngFields( point );
				}
			});
		}
	};

	var updateLatLngFields = function( point )
	{
		document.getElementById(latid).value = point.lat();
		document.getElementById(longid).value = point.lng();
		document.getElementById( attributeId + '_ADDRESS_BTN_REST').disabled = false;
	};

	var restoretLatLngFields = function()
	{
		document.getElementById( latid ).value     = document.getElementById(attributeId + '_ADDRESS_HIDDEN_LAT').value;
		document.getElementById( longid ).value    = document.getElementById(attributeId + '_ADDRESS_HIDDEN_LONG').value;
		document.getElementById( addressid ).value = document.getElementById(attributeId + '_ADDRESS_HIDDEN').value;
		if ( document.getElementById( latid ).value && document.getElementById( latid ).value != 0 )
		{
			var point = new GLatLng( document.getElementById( latid ).value, document.getElementById( longid ).value );
			map.clearOverlays();
			map.addOverlay( new GMarker(point) );
			map.panTo( point );
		}
		document.getElementById( attributeId + '_ADDRESS_BTN_REST' ).disabled = true;
		return false;
	};

	var getUserPosition = function()
	{
		navigator.geolocation.getCurrentPosition( function( position )
		{
			var location = '', point = new GLatLng(  position.coords.latitude, position.coords.longitude );

			if ( navigator.geolocation.type == 'Gears' && position.gearsAddress )
			location =[position.gearsAddress.city, position.gearsAddress.region, position.gearsAddress.country].join(', ');
			else if ( navigator.geolocation.type == 'ClientLocation' )
			location =[position.address.city, position.address.region, position.address.country].join(', ');

			document.getElementById( addressid ).value = location;
			map.setCenter( point, 13 );
			map.clearOverlays();
			map.addOverlay( new GMarker(point) );
			updateLatLngFields( point );
		},
		function( e )
		{
			alert( 'Could not get your location, error was: ' + e.message );
		},
		{ 'gearsRequestAddress': true });
	};

	if (GBrowserIsCompatible())
	{
		var startPoint = null, zoom = 0, map = new GMap2( document.getElementById( mapid ) );
		if ( document.getElementById( latid ).value && document.getElementById( latid ).value != 0 )
		{
			startPoint = new GLatLng( document.getElementById( latid ).value, document.getElementById( longid ).value );
			zoom = 15;
		}
		else
		{
			startPoint = new GLatLng(0,0);
		}
		map.addControl( new GSmallMapControl() );
		map.addControl( new GMapTypeControl() );
		map.setCenter( startPoint, zoom );
		map.addOverlay( new GMarker( startPoint ) );
		geocoder = new GClientGeocoder();
		GEvent.addListener( map, 'click', function( newmarker, point )
		{
			map.clearOverlays();
			map.addOverlay( new GMarker( point ) );
			map.panTo( point );
			updateLatLngFields( point );
			document.getElementById( addressid ).value = '';
		});

		document.getElementById( attributeId + '_ADDRESS_BTN_FIND').onclick = showAddress;
		document.getElementById( attributeId + '_ADDRESS_BTN_REST').onclick = restoretLatLngFields;

		if ( navigator.geolocation && document.getElementById( attributeId + '_ADDRESS_BTN_ME') )
		{
			document.getElementById( attributeId + '_ADDRESS_BTN_ME').onclick = getUserPosition;
			document.getElementById( attributeId + '_ADDRESS_BTN_ME').className = 'button';
			document.getElementById( attributeId + '_ADDRESS_BTN_ME').disabled = false;
		}
	}
}

function mapControlZoom( attributeId ) {
	var mapid = attributeId + '_MAP', latid  = attributeId + '_LATITUDE', longid = attributeId + '_LONGITUDE', zoomId = attributeId + '_ZOOM';
	var geocoder = null, addressid = attributeId + '_ADDRESS';

	var showAddress = function()
	{
		var address = document.getElementById( addressid ).value;
		if ( geocoder )
		{
			geocoder.getLatLng( address, function( point )
			{
				if ( !point )
				{
					alert( address + " introuvable" );
				}
				else
				{
					map.setCenter( point, 13 );
					map.clearOverlays();
					map.addOverlay( new GMarker( point ) );
					updateLatLngFields( point );
				}
			});
		}
	};

	var updateLatLngFields = function( point )
	{
		document.getElementById(latid).value = point.lat();
		document.getElementById(longid).value = point.lng();
		document.getElementById( attributeId + '_ADDRESS_BTN_REST').disabled = false;
	};

	var restoretLatLngFields = function()
	{
		document.getElementById( latid ).value     = document.getElementById(attributeId + '_ADDRESS_HIDDEN_LAT').value;
		document.getElementById( longid ).value    = document.getElementById(attributeId + '_ADDRESS_HIDDEN_LONG').value;
		document.getElementById( addressid ).value = document.getElementById(attributeId + '_ADDRESS_HIDDEN').value;
		if ( document.getElementById( latid ).value && document.getElementById( latid ).value != 0 )
		{
			var point = new GLatLng( document.getElementById( latid ).value, document.getElementById( longid ).value );
			map.clearOverlays();
			map.addOverlay( new GMarker(point) );
			map.panTo( point );
		}
		document.getElementById( attributeId + '_ADDRESS_BTN_REST' ).disabled = true;
		return false;
	};

	var getUserPosition = function()
	{
		navigator.geolocation.getCurrentPosition( function( position )
		{
			var location = '', point = new GLatLng(  position.coords.latitude, position.coords.longitude );

			if ( navigator.geolocation.type == 'Gears' && position.gearsAddress )
			location =[position.gearsAddress.city, position.gearsAddress.region, position.gearsAddress.country].join(', ');
			else if ( navigator.geolocation.type == 'ClientLocation' )
			location =[position.address.city, position.address.region, position.address.country].join(', ');

			document.getElementById( addressid ).value = location;
			map.setCenter( point, 13 );
			map.clearOverlays();
			map.addOverlay( new GMarker(point) );
			updateLatLngFields( point );
		},
		function( e )
		{
			alert( 'Could not get your location, error was: ' + e.message );
		},
		{ 'gearsRequestAddress': true });
	};

	if (GBrowserIsCompatible())
	{
		var startPoint = null, zoom = 0, map = new GMap2( document.getElementById( mapid ) );
		if ( document.getElementById( latid ).value && document.getElementById( latid ).value != 0 )
		{
			startPoint = new GLatLng( document.getElementById( latid ).value, document.getElementById( longid ).value );
			if (document.getElementById(zoomId)) {
			    zoom = parseInt(document.getElementById(zoomId).value);
			} else {
                zoom = 15;
			}
		}
		else
		{
			startPoint = new GLatLng(0,0);
		}
		map.addControl( new GSmallMapControl() );
		map.addControl( new GMapTypeControl() );
		map.setCenter( startPoint, zoom );
		map.addOverlay( new GMarker( startPoint ) );
		geocoder = new GClientGeocoder();
		GEvent.addListener( map, 'click', function( newmarker, point )
		{
			map.clearOverlays();
			map.addOverlay( new GMarker( point ) );
			map.panTo( point );
			updateLatLngFields( point );
			document.getElementById( addressid ).value = '';
		});
		GEvent.addListener( map, 'zoomend', function( oldLevel, newLevel ) {
			document.getElementById (zoomId).value = newLevel;
		});

		document.getElementById( attributeId + '_ADDRESS_BTN_FIND').onclick = showAddress;
		document.getElementById( attributeId + '_ADDRESS_BTN_REST').onclick = restoretLatLngFields;

		if ( navigator.geolocation && document.getElementById( attributeId + '_ADDRESS_BTN_ME') )
		{
			document.getElementById( attributeId + '_ADDRESS_BTN_ME').onclick = getUserPosition;
			document.getElementById( attributeId + '_ADDRESS_BTN_ME').className = 'button';
			document.getElementById( attributeId + '_ADDRESS_BTN_ME').disabled = false;
		}
	}
}
