var map = null;
var geo = null;

var MYMODE_MAP = G_NORMAL_MAP;
var MYMODE_SATELLITE = G_SATELLITE_MAP;
var MYMODE_MIXTE = G_HYBRID_MAP;

var MYMARKER_TYPE1 = null;
var MYMARKER_TYPE2 = null;

function MyMapInitialize(mapname, lat, lng, zoom, mode) {

	map = Geoportal.load(
	// div's ID:
	mapname,
	// API's keys:
	[ global_key ], {// map's center :
		// longitude:
		lon : lng,
		// latitude:
		lat : lat
	}, null, {
		language : 'fr',
		viewerClass : 'Geoportal.Viewer.Default'
	},
    //zoom level 
    zoom,
    //options
    {
    	type:'js'
    });

	/*map.setCenter(new google.maps.LatLng(lat, lng), zoom, mode);
	map.addControl(new google.maps.LargeMapControl());
	map.addControl(new google.maps.MapTypeControl());

	// geo = new GClientGeocoder();

	var baseIcon = new google.maps.Icon();
	baseIcon.shadow = "/library/External/mymap/images/shadow.png";
	baseIcon.iconSize = new google.maps.Size(20, 34);
	baseIcon.shadowSize = new google.maps.Size(37, 34);
	baseIcon.iconAnchor = new google.maps.Point(9, 34);
	baseIcon.infoWindowAnchor = new google.maps.Point(9, 2);
	baseIcon.infoShadowAnchor = new google.maps.Point(18, 25);
	MYMARKER_TYPE1 = new google.maps.Icon(baseIcon);
	MYMARKER_TYPE1.image = "/library/External/mymap/images/marker_red.png"
	MYMARKER_TYPE2 = new google.maps.Icon(baseIcon);
	MYMARKER_TYPE2.image = "/library/External/mymap/images/marker_blue.png"
*/
}

function MyMapTerminate() {
	//google.maps.Unload();
}

function MyMapAddMarker(lat, lng, markertype, info) {
	/*var newmarker = new google.maps.Marker(new google.maps.LatLng(lat, lng),
			markertype);
	google.maps.Event.addListener(newmarker, "click", function() {
		newmarker.openInfoWindowHtml(info, {
			maxWidth : 100
		});
	});
	map.addOverlay(newmarker);
	return newmarker;*/
}

function MyMapRemoveMarker(marker) {
	//map.removeOverlay(marker);
}

function MyMapGoto(lat, lng) {
	//map.panTo(new google.maps.LatLng(lat, lng));
}

function MyMapSetZoom(zoom) {
	//map.setZoom(zoom);
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
