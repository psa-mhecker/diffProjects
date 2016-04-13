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

var MYMODE_MAP = 'r';
var MYMODE_SATELLITE = 'a';
var MYMODE_MIXTE = 'h';

var MYMARKER_TYPE1 = '/library/External/mymap/images/marker_red.png';
var MYMARKER_TYPE2 = '/library/External/mymap/images/marker_blue.png';

var myPINid = 1;


function MyMapInitialize(mapname, lat, lng, zoom, mode) {
   map = new VEMap(mapname);
   map.LoadMap(new VELatLong(lat, lng), zoom, mode, false);
}   

function MyMapTerminate() {
}

function MyMapAddMarker(lat, lng, markertype, info) {
   var pinID;
   var pin;

   pinID = myPINid;
   myPINid = myPINid + 1;
   pin = new VEPushpin(
     pinID, 
     new VELatLong(lat, lng),
     markertype,
     '',
     info
     );
   map.AddPushpin(pin);
   return pinID;
}

function MyMapRemoveMarker(marker) {
   map.DeletePushpin(marker);
}

function MyMapGoto(lat, lng) {
   map.SetCenter(new VELatLong(lat, lng));
}

function MyMapSetZoom(zoom) {
   map.SetZoomLevel(zoom);
}

function MyMapPoint() {
   this.lat = 0;
   this.lng = 0;
}

function MyMapGeocode(address, callback) {
   map.Find(null, address, 1, function(results) {
       if (results.length == 0)
           callback(null);
       else {
           var res = new MyMapPoint();
           res.lat = results[0].LatLong.Latitude;
           res.lng = results[0].LatLong.Longitude;
           callback(res);
       }
   });
}

