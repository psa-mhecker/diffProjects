//================================================================================
// MyMap - LGPL Copyright (c) 2006 Lionel Lask�
//
// This file is part of MyMap.
//
// MyMap is free software; you can redistribute it and/or modify
// it under the terms of the GNU Lesser General Public License as published by
// the Free Software Foundation; either version 2.1 of the License, or
// (at your option) any later version.
//
// MyMap is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU Lesser General Public License for more details.
//
// You should have received a copy of the GNU Lesser General Public License
// along with MyMap; if not, write to the Free Software
// Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
//
//================================================================================

var map = null;

var MYMODE_MAP = YAHOO_MAP_REG;
var MYMODE_SATELLITE = YAHOO_MAP_SAT;
var MYMODE_MIXTE = YAHOO_MAP_HYB;

var MYMARKER_TYPE1 = null;
var MYMARKER_TYPE2 = null;


function MyMapInitialize(mapname, lat, lng, zoom, mode) {
   map = new YMap(document.getElementById(mapname), mode);
   map.drawZoomAndCenter(new YGeoPoint(lat, lng), 18-zoom);
   map.addPanControl(); 
   map.addZoomLong();
   map.addTypeControl();

   MYMARKER_TYPE1 = new YImage();
   MYMARKER_TYPE1.src = "/library/External/mymap/images/marker_red.png"
   MYMARKER_TYPE1.size = new YSize(20,34);
   MYMARKER_TYPE1.offsetSmartWindow = new YCoordPoint(9,34);
   MYMARKER_TYPE2 = new YImage();
   MYMARKER_TYPE2.src = "/library/External/mymap/images/marker_blue.png"
   MYMARKER_TYPE2.size = new YSize(20,34);
   MYMARKER_TYPE2.offsetSmartWindow = new YCoordPoint(9,34);
}   

function MyMapTerminate() {
}


function MyMapAddMarker(lat, lng, markertype, info) {
   var marker = new YMarker(new YGeoPoint(lat, lng), markertype);

   YEvent.Capture(marker, EventsList.MouseClick, function OnClick() {
     marker.openSmartWindow(info);
   });

   map.addOverlay(marker);
   return marker;
}

function MyMapRemoveMarker(marker) {
   map.removeOverlay(marker);
}

function MyMapGoto(lat, lng) {
   map.panToLatLon(new YGeoPoint(lat, lng));
}

function MyMapSetZoom(zoom) {
   map.setZoomLevel(18-zoom);
}

function MyMapPoint() {
   this.lat = 0;
   this.lng = 0;
}

function MyMapGeocode(address, callback) {
   YEvent.Capture(map, EventsList.onEndGeoCode, function (point) {
       if (!point)
           callback(null);
       else {
           var res = new MyMapPoint();
           res.lat = point.Lat;
           res.lng = point.Lon;
           callback(res);
       }
   });
   map.geoCodeAddress(address);
}

