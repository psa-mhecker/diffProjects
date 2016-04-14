var map = null;
var geo = null;

var MYMODE_MAP = G_NORMAL_MAP;
var MYMODE_SATELLITE = G_SATELLITE_MAP;
var MYMODE_MIXTE = G_HYBRID_MAP;

var MYMARKER_TYPE1 = null;
var MYMARKER_TYPE2 = null;

function MyMapInitialize(mapname, lat, lng, zoom, mode) {
	var cloudmade = new CM.Tiles.CloudMade.Web({
		key : global_key
	});

	map = new CM.Map(document.getElementById(mapname), cloudmade);

	map.setCenter(new CM.LatLng(lat, lng), zoom);
	map.addControl(new CM.LargeMapControl());
	map.addControl(new CM.TileLayerControl());

	var baseIcon = new CM.Icon();
	baseIcon.shadow = "/library/External/mymap/images/shadow.png";
	baseIcon.iconSize = new CM.Size(20, 34);
	baseIcon.shadowSize = new CM.Size(37, 34);
	baseIcon.iconAnchor = new CM.Point(9, 34);
	baseIcon.infoWindowAnchor = new CM.Point(9, 2);
	baseIcon.infoShadowAnchor = new CM.Point(18, 25);
	MYMARKER_TYPE1 = new CM.Icon(baseIcon);
	MYMARKER_TYPE1.image = "/library/External/mymap/images/marker_red.png"
	MYMARKER_TYPE2 = new CM.Icon(baseIcon);
	MYMARKER_TYPE2.image = "/library/External/mymap/images/marker_blue.png"
}

function MyMapTerminate() {
	CM.Unload();
}

function MyMapAddMarker(lat, lng, markertype, info) {
	var newmarker = new CM.Marker(new CM.LatLng(lat, lng), markertype);
	CM.Event.addListener(newmarker, "click", function() {
		newmarker.openInfoWindow(info, {
			maxWidth : 100
		});
	});
	map.addOverlay(newmarker);
	return newmarker;
}

function MyMapRemoveMarker(marker) {
	map.removeOverlay(marker);
}

function MyMapGoto(lat, lng) {
	map.panTo(new CM.LatLng(lat, lng));
}

function MyMapSetZoom(zoom) {
	map.setZoom(zoom);
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
