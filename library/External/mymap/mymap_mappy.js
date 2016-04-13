var map = null;
var geo = null;

var MYMODE_MAP = G_NORMAL_MAP;
var MYMODE_SATELLITE = G_SATELLITE_MAP;
var MYMODE_MIXTE = G_HYBRID_MAP;

var MYMARKER_TYPE1 = null;
var MYMARKER_TYPE2 = null;

function MyMapInitialize(mapname, lat, lng, zoom, mode) {
	map = new Mappy.api.map.Map({
		container : '#' + mapname
	});
	map.setCenter(new Mappy.api.geo.Coordinates(lng, lat), zoom * 1.5);

	var toolBar = new Mappy.api.map.tools.ToolBar({
		"move" : true,
		"zoom" : true
	}, new Mappy.api.map.tools.ToolPosition("rb", new Mappy.api.types.Point(5,
			15)), "vertical");
	map.addTool(toolBar);

	// geo = new Mappy.api.geolocation.Geocoder();

	var baseIcon = new Mappy.api.ui.Icon();
		baseIcon.shadowSize = new Mappy.api.types.Size(37, 34);
	baseIcon.iconAnchor = new Mappy.api.types.Point(9, 34);
	baseIcon.cssClass = '';
	MYMARKER_TYPE1 = new Mappy.api.ui.Icon(baseIcon);
	MYMARKER_TYPE1.image = "/library/External/mymap/images/marker_red.png"
	alert(MYMARKER_TYPE1);
	MYMARKER_TYPE2 = new Mappy.api.ui.Icon(baseIcon);
	MYMARKER_TYPE2.image = "/library/External/mymap/images/marker_blue.png"

}

function MyMapTerminate() {
	map.destroy();
}

function MyMapAddMarker(lat, lng, markertype, info) {

	var newmarker = new Mappy.api.map.Marker(new Mappy.api.geo.Coordinates(lng,
			lat), markertype);

	newmarker.addListener("click", function() {
		newmarker.openPopUp(info);
	});

	map.addMarker(newmarker);
	return newmarker;

}

function MyMapRemoveMarker(marker) {
	// map.removeOverlay(marker);
}

function MyMapGoto(lat, lng) {
	// map.panTo(new GLatLng(lat, lng));
}

function MyMapSetZoom(zoom) {
	// map.setZoomLevel(zoom);
}

function MyMapPoint() {
	this.lat = 0;
	this.lng = 0;
}

function MyMapGeocode(address, callback) {
	/*
	 * geo.getLatLng(address, function(point) { if (!point) callback(null); else {
	 * res = new MyMapPoint(); res.lat = point.lat(); res.lng = point.lng();
	 * callback(res); } });
	 */
}
