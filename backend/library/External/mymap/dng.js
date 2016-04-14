
var guys = [ 
   { "lat": 48.786290, "lng": 2.057517, "pref": ".NET", "name":"Lionel Lask\351", "company":"C2S" },
   { "lat": 43.578394, "lng": 1.495859, "pref": "J2EE", "name":"Sami Jaber", "company":"Valtech" },
   { "lat": 48.892756, "lng": 2.247776, "pref": "J2EE", "name":"Thomas Gil", "company":"Valtech" },
   { "lat": 48.880620, "lng": 2.325784, "pref": "J2EE", "name":"Bruno Guedes", "company":"Atos Origin" },
   { "lat": 48.867804, "lng": 2.359656, "pref": ".NET", "name":"Jean-Louis Besnard", "company":"Brainsonic" },
   { "lat": 48.835729, "lng": 2.238051, "pref": ".NET", "name":"Eric Groise", "company":"MobiPocket" },
   { "lat": 47.755057, "lng": 7.320988, "pref": ".NET", "name":"S\351bastien Ros", "company":"Evaluant" },
   { "lat": 48.885187, "lng": 2.244792, "pref": "J2EE", "name":"Didier Girard", "company":"Improve" },
   { "lat": 48.692796, "lng": 2.221195, "pref": ".NET", "name":"Marc Gardette", "company":"Microsoft" },
   { "lat": 47.639557, "lng": -122.128336, "pref": ".NET", "name":"Bill Gates", "company":"Microsoft" }
];

function load() {
   MyMapInitialize("map", 48.786290, 2.057517, 6, MYMODE_MAP);
   for (i=0; i<guys.length; i++) {
       guys[i].marker = null;
       print(i);
       var newopt = document.createElement('option');
       newopt.text = guys[i].name;
       newopt.value = i;
       try {
         document.getElementById("list").add(newopt);
       }
       catch(e) {
         document.getElementById("list").add(newopt, null);
       }
   }
}

function unload() {
   MyMapTerminate();
}

function print(index) {
   var person = guys[index];
   if (person.marker != null)
       return;
   var markertype = MYMARKER_TYPE1;
   if (person.pref == "J2EE")
       markertype = MYMARKER_TYPE2;
   guys[i].marker = MyMapAddMarker(person.lat, person.lng, markertype, "<B>"+person.name+"</B><BR/>"+person.company);
}

function hide(index) {
   var person = guys[index];
   if (person.marker != null) {
       MyMapRemoveMarker(person.marker);
       person.marker = null;
   }
}

function goto(index) {
   var person = guys[index];
   MyMapSetZoom(12);
   MyMapGoto(person.lat, person.lng);
}

function gotoselected() {
   goto(document.getElementById("list").value);
}

function filter(pref) {
   for (i=0; i<guys.length; i++) {
       if (guys[i].pref == pref)
           hide(i);
       else
           print(i);
   }
}
