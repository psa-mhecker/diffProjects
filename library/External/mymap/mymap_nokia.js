var map = null;
var geo = null;

var MYMODE_MAP = 'NORMAL';
var MYMODE_SATELLITE = 'SATELLITE';
var MYMODE_MIXTE = 'TERRAIN';

var MYMARKER_TYPE1 = "marker";
var MYMARKER_TYPE2 = "marker";

function MyMapInitialize(mapname, lat, lng, zoom, mode) {

	// Set up is the credentials to use the API:
	nokia.Settings.set("appId", global_appid_code);
	nokia.Settings.set("authenticationToken", global_key);
	nokia.Settings.set("defaultLanguage", "fr-FR");
	(document.location.protocol == "https:")
			&& nokia.Settings.set("secureConnection", "force");

	map = new nokia.maps.map.Display(document.getElementById(mapname), {
		components : [
		// Behavior collection
		new nokia.maps.map.component.Behavior(),
				new nokia.maps.map.component.ZoomBar(),
				new nokia.maps.map.component.Overview(),
				new nokia.maps.map.component.TypeSelector(),
				new nokia.maps.map.component.ScaleBar() ],
		// Zoom level for the map
		'zoomLevel' : zoom * 2.5,
		// Map center coordinates
		'center' : [ lat, lng ]
	});

	map.set("baseMapType", nokia.maps.map.Display.NORMAL);

}

function MyMapTerminate() {
	GUnload();
}

function MyMapAddMarker(lat, lng, markertype, info) {

	// Create a marker and add it to the map
	var marker = new nokia.maps.map.Marker(new nokia.maps.geo.Coordinate(lat,
			lng), {
		title : markertype,
		visibility : true,
		icon : "/library/External/mymap/images/marker_red.png",
		// Offset the top left icon corner so that it's
		// Centered above the coordinate
		anchor : new nokia.maps.util.Point(lat, lng)
	});

	map.objects.add(marker);

	/*
	 * var newmarker = new GMarker(new GLatLng(lat, lng), markertype);
	 * GEvent.addListener(newmarker, "click", function() {
	 * newmarker.openInfoWindowHtml(info); }); map.addOverlay(newmarker);
	 */

	return marker;
}

function MyMapRemoveMarker(marker) {
	marker.destroy();
}

function MyMapGoto(lat, lng) {
	map.set("center", new nokia.maps.geo.Coordinate(lat, lng));
}

function MyMapSetZoom(zoom) {
	map.set("zoom", zoom);
}

function MyMapPoint() {
	this.lat = 0;
	this.lng = 0;
}

function MyMapGeocode(address, callback) {
	geo.getLatLng(address, function(point) {
		if (!point)
			callback(null);
		else {
			res = new MyMapPoint();
			res.lat = point.lat();
			res.lng = point.lng();
			callback(res);
		}
	});
}
