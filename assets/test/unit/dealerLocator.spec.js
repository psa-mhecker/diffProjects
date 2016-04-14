import DealerLocator from './modules/dealerLocator.js';

var jsonStub = {
	"listDealer": [{
		"id": "0000000790",
		"name": "PEUGEOT PARIS 10 GARE DE L'EST",
		"type": "magasin",
		"vehicle_new": true,
		"vehicle_occasion": true,
		"vehicle_location": false,
		"adress": {
			"street": "180 RUE DU FBG ST MARTIN",
			"city": "75010 PARIS",
			"country": "",
			"lat": "48.8782043",
			"lng": "2.362105",
			"dist": "2.2"
		},
		"contact": {
			"tel": "0140056610",
			"fax": "0140056616",
			"website": "http:\/\/concessions.peugeot.fr\/paris10-garedelest",
			"vcf": "http:\/\/fr.psa-ndp.com\/api\/vcf\/pf11\/fr\/2\/0000000790"
		},
		"services": [{
			"code": "vn",
			"type_name": "A",
			"type": "V\u00e9hicule neuf",
			"name": "V\u00e9hicule neuf",
			"tel": "0140066606",
			"fax": "0140056616",
			"icon": "http:\/\/media.psa-ndp.com\/image\/83\/6\/footer-social-instagram.6836.png",
			"mail": "gerald.ponce@peugeot.com"
		}, {
			"code": "apv",
			"type_name": "A",
			"type": "Apr\u00e8s-vente",
			"name": "Apr\u00e8s vente surcharg\u00e9 bo",
			"tel": "0140056605",
			"fax": "0140056616",
			"mail": "contact.botzaris@peugeot.com"
		}, {
			"code": "pr",
			"type_name": "A",
			"type": "Pi\u00e8ce de rechange",
			"name": "Pi\u00e8ce de rechange",
			"tel": "0140066610",
			"fax": "0140056616",
			"mail": "contact.botzaris@peugeot.com"
		}, {
			"code": "vo",
			"type_name": "A",
			"type": "V\u00e9hicule d'occasion",
			"name": "V\u00e9hicule d'occasion",
			"tel": "0140066694",
			"fax": "0140056616",
			"mail": "contact.botzaris@peugeot.com"
		}],
		"ctaList": [{
			"url": "https:\/\/www.google.fr\/maps\/dir\/48.86,2.35\/48.8782043,2.362105\/",
			"version": "cta-direction",
			"title": "",
			"target": "_blank"
		}, {
			"url": "http:\/\/concessions.peugeot.fr\/paris10-garedelest\/nous-contacter\/info-utiles\/",
			"version": "cta-contact",
			"title": "",
			"target": "_self"
		}],
		"schedules": "Lundi:07:30-19:00<br \/> Mardi:07:30-19:00<br \/> Mercredi:07:30-19:00<br \/> Jeudi:07:30-19:00<br \/> Vendredi:07:30-19:00<br \/> Samedi:08:30-18:30<br \/> Dimanche:Ferm\u00e9<br \/>",
		"dealer": true,
		"agent": false,
		"principal_vn": true
	}, {
		"id": "0000020937",
		"name": "PEUGEOT PARIS 15 GRENELLE",
		"type": "magasin",
		"vehicle_new": true,
		"vehicle_occasion": true,
		"vehicle_location": false,
		"adress": {
			"street": "146, bd de Grenelle",
			"city": "75015 Paris",
			"country": "",
			"lat": "48.848423",
			"lng": "2.299039",
			"dist": "3.9"
		},
		"contact": {
			"tel": "0156105610",
			"fax": "0156105710",
			"website": "http:\/\/concessions.peugeot.fr\/paris15-grenelle",
			"vcf": "http:\/\/fr.psa-ndp.com\/api\/vcf\/pf11\/fr\/2\/0000020937"
		},
		"services": [{
			"code": "vn",
			"type_name": "A",
			"type": "V\u00e9hicule neuf",
			"name": "V\u00e9hicule neuf",
			"tel": "0156105610",
			"fax": "0156105710",
			"icon": "http:\/\/media.psa-ndp.com\/image\/83\/6\/footer-social-instagram.6836.png",
			"mail": "MARC.GIULIOLI@PEUGEOT.COM"
		}, {
			"code": "apv",
			"type_name": "A",
			"type": "Apr\u00e8s-vente",
			"name": "Apr\u00e8s vente surcharg\u00e9 bo",
			"tel": "0156105610",
			"fax": "0156105710",
			"mail": "david.gourbeyre@mpsa.com"
		}, {
			"code": "pr",
			"type_name": "A",
			"type": "Pi\u00e8ce de rechange",
			"name": "Pi\u00e8ce de rechange",
			"tel": "0156105610",
			"fax": "0156105710",
			"mail": "contact-darlmat@peugeot.com"
		}, {
			"code": "vo",
			"type_name": "A",
			"type": "V\u00e9hicule d'occasion",
			"name": "V\u00e9hicule d'occasion",
			"tel": "0156105610",
			"fax": "0156105710",
			"mail": "contact-darlmat@peugeot.com"
		}],
		"ctaList": [{
			"url": "https:\/\/www.google.fr\/maps\/dir\/48.86,2.35\/48.848423,2.299039\/",
			"version": "cta-direction",
			"title": "",
			"target": "_blank"
		}, {
			"url": "http:\/\/concessions.peugeot.fr\/paris15-grenelle\/nous-contacter\/info-utiles\/",
			"version": "cta-contact",
			"title": "",
			"target": "_self"
		}],
		"schedules": "Lundi:07:30-19:00<br \/> Mardi:07:30-19:00<br \/> Mercredi:07:30-19:00<br \/> Jeudi:07:30-19:00<br \/> Vendredi:07:30-19:00<br \/> Samedi:09:00-18:30<br \/> Dimanche: Ferm\u00e9<br \/>",
		"dealer": true,
		"agent": false,
		"principal_vn": true
	}, {
		"id": "0000020946",
		"name": "PEUGEOT PARIS 14 ALESIA",
		"type": "magasin",
		"vehicle_new": true,
		"vehicle_occasion": true,
		"vehicle_location": false,
		"adress": {
			"street": "3, 5 Avenue Jean Moulin",
			"city": "75014 Paris",
			"country": "",
			"lat": "48.82705",
			"lng": "2.326384",
			"dist": "4.1"
		},
		"contact": {
			"tel": "0156105620",
			"fax": "0156105750",
			"website": "http:\/\/concessions.peugeot.fr\/paris14-alesia",
			"vcf": "http:\/\/fr.psa-ndp.com\/api\/vcf\/pf11\/fr\/2\/0000020946"
		},
		"services": [{
			"code": "vn",
			"type_name": "A",
			"type": "V\u00e9hicule neuf",
			"name": "V\u00e9hicule neuf",
			"tel": "0156105620",
			"fax": "0156105750",
			"icon": "http:\/\/media.psa-ndp.com\/image\/83\/6\/footer-social-instagram.6836.png",
			"mail": "marc.giulioli@peugeot.com"
		}, {
			"code": "apv",
			"type_name": "A",
			"type": "Apr\u00e8s-vente",
			"name": "Apr\u00e8s vente surcharg\u00e9 bo",
			"tel": "0156105620",
			"fax": "0156105750",
			"mail": "fabien.guiraud@peugeot.com"
		}, {
			"code": "pr",
			"type_name": "A",
			"type": "Pi\u00e8ce de rechange",
			"name": "Pi\u00e8ce de rechange",
			"tel": "0156105620",
			"fax": "0156105750",
			"mail": "contact-darlmat@peugeot.com"
		}, {
			"code": "vo",
			"type_name": "A",
			"type": "V\u00e9hicule d'occasion",
			"name": "V\u00e9hicule d'occasion",
			"tel": "0156105620",
			"fax": "0156105750",
			"mail": "contact-darlmat@peugeot.com"
		}],
		"ctaList": [{
			"url": "https:\/\/www.google.fr\/maps\/dir\/48.86,2.35\/48.82705,2.326384\/",
			"version": "cta-direction",
			"title": "",
			"target": "_blank"
		}, {
			"url": "http:\/\/concessions.peugeot.fr\/paris14-alesia\/nous-contacter\/info-utiles\/",
			"version": "cta-contact",
			"title": "",
			"target": "_self"
		}],
		"schedules": "Lundi:07:30-19:00<br \/> Mardi:07:30-19:00<br \/> Mercredi:07:30-19:00<br \/> Jeudi:07:30-19:00<br \/> Vendredi:07:30-19:00<br \/> Samedi:09:00-18:30<br \/> Dimanche: Ferm\u00e9<br \/>",
		"dealer": true,
		"agent": false,
		"principal_vn": true
	}, {
		"id": "0000024480",
		"name": "LOCARSON",
		"type": "magasin",
		"vehicle_new": true,
		"vehicle_occasion": true,
		"vehicle_location": false,
		"adress": {
			"street": "166-168 RUE DE CHARONNE",
			"city": "75011 PARIS",
			"country": "",
			"lat": "48.85591",
			"lng": "2.39208078",
			"dist": "3.1"
		},
		"contact": {
			"tel": "0140091010",
			"fax": "0140098710",
			"website": "http:\/\/agents.peugeot.fr\/locarson-paris",
			"vcf": "http:\/\/fr.psa-ndp.com\/api\/vcf\/pf11\/fr\/2\/0000024480"
		},
		"services": [{
			"code": "vn",
			"type_name": "A",
			"type": "V\u00e9hicule neuf",
			"name": "V\u00e9hicule neuf",
			"tel": "",
			"fax": "0140098710",
			"icon": "http:\/\/media.psa-ndp.com\/image\/83\/6\/footer-social-instagram.6836.png",
			"mail": "giamsonlocarson@gmail.com"
		}, {
			"code": "apv",
			"type_name": "A",
			"type": "Apr\u00e8s-vente",
			"name": "Apr\u00e8s vente surcharg\u00e9 bo",
			"tel": "",
			"fax": "0140098710",
			"mail": "giamsonlocarson@gmail.com"
		}, {
			"code": "pr",
			"type_name": "A",
			"type": "Pi\u00e8ce de rechange",
			"name": "Pi\u00e8ce de rechange",
			"tel": "",
			"fax": "0140098710",
			"mail": ""
		}, {
			"code": "vo",
			"type_name": "A",
			"type": "V\u00e9hicule d'occasion",
			"name": "V\u00e9hicule d'occasion",
			"tel": "",
			"fax": "0140098710",
			"mail": ""
		}],
		"ctaList": [{
			"url": "https:\/\/www.google.fr\/maps\/dir\/48.86,2.35\/48.85591,2.39208078\/",
			"version": "cta-direction",
			"title": "",
			"target": "_blank"
		}, {
			"url": "http:\/\/agents.peugeot.fr\/locarson-paris\/nous-contacter\/info-utiles\/",
			"version": "cta-contact",
			"title": "",
			"target": "_self"
		}],
		"schedules": "Du Lundi au Vendredi: 8h-12h 13h30-19h30",
		"dealer": false,
		"agent": true,
		"principal_vn": false
	}, {
		"id": "0000025283",
		"name": "GRAND GARAGE CLIGNANCOURT",
		"type": "magasin",
		"vehicle_new": true,
		"vehicle_occasion": true,
		"vehicle_location": false,
		"adress": {
			"street": "120 RUE DE CLIGNANCOURT",
			"city": "75018 PARIS",
			"country": "",
			"lat": "48.88991",
			"lng": "2.34833241",
			"dist": "3.3"
		},
		"contact": {
			"tel": "0146064872",
			"fax": "0146064280",
			"website": "http:\/\/agents.peugeot.fr\/clignancourt-paris",
			"vcf": "http:\/\/fr.psa-ndp.com\/api\/vcf\/pf11\/fr\/2\/0000025283"
		},
		"services": [{
			"code": "vn",
			"type_name": "A",
			"type": "V\u00e9hicule neuf",
			"name": "V\u00e9hicule neuf",
			"tel": "",
			"fax": "0146064280",
			"icon": "http:\/\/media.psa-ndp.com\/image\/83\/6\/footer-social-instagram.6836.png",
			"mail": "ggc@numericable.fr"
		}, {
			"code": "apv",
			"type_name": "A",
			"type": "Apr\u00e8s-vente",
			"name": "Apr\u00e8s vente surcharg\u00e9 bo",
			"tel": "",
			"fax": "0146064280",
			"mail": "ggc@numericable.fr"
		}, {
			"code": "pr",
			"type_name": "A",
			"type": "Pi\u00e8ce de rechange",
			"name": "Pi\u00e8ce de rechange",
			"tel": "",
			"fax": "0146064280",
			"mail": ""
		}, {
			"code": "vo",
			"type_name": "A",
			"type": "V\u00e9hicule d'occasion",
			"name": "V\u00e9hicule d'occasion",
			"tel": "",
			"fax": "0146064280",
			"mail": ""
		}],
		"ctaList": [{
			"url": "https:\/\/www.google.fr\/maps\/dir\/48.86,2.35\/48.88991,2.34833241\/",
			"version": "cta-direction",
			"title": "",
			"target": "_blank"
		}, {
			"url": "http:\/\/agents.peugeot.fr\/clignancourt-paris\/nous-contacter\/info-utiles\/",
			"version": "cta-contact",
			"title": "",
			"target": "_self"
		}],
		"dealer": false,
		"agent": true,
		"principal_vn": false
	}, {
		"id": "0000024481",
		"name": "MONTMARTRE CLOYS AUTOMOBILES",
		"type": "magasin",
		"vehicle_new": true,
		"vehicle_occasion": true,
		"vehicle_location": false,
		"adress": {
			"street": "45 RUE DES CLOYS",
			"city": "75018 PARIS",
			"country": "",
			"lat": "48.8929",
			"lng": "2.338143",
			"dist": "3.8"
		},
		"contact": {
			"tel": "0142515253",
			"fax": "0142548879",
			"website": "http:\/\/agents.peugeot.fr\/paris",
			"vcf": "http:\/\/fr.psa-ndp.com\/api\/vcf\/pf11\/fr\/2\/0000024481"
		},
		"services": [{
			"code": "vn",
			"type_name": "A",
			"type": "V\u00e9hicule neuf",
			"name": "V\u00e9hicule neuf",
			"tel": "",
			"fax": "0142548879",
			"icon": "http:\/\/media.psa-ndp.com\/image\/83\/6\/footer-social-instagram.6836.png",
			"mail": "M.C.A@WANADOO.FR"
		}, {
			"code": "apv",
			"type_name": "A",
			"type": "Apr\u00e8s-vente",
			"name": "Apr\u00e8s vente surcharg\u00e9 bo",
			"tel": "",
			"fax": "0142548879",
			"mail": "M.C.A@WANADOO.FR"
		}, {
			"code": "pr",
			"type_name": "A",
			"type": "Pi\u00e8ce de rechange",
			"name": "Pi\u00e8ce de rechange",
			"tel": "",
			"fax": "0142548879",
			"mail": ""
		}, {
			"code": "vo",
			"type_name": "A",
			"type": "V\u00e9hicule d'occasion",
			"name": "V\u00e9hicule d'occasion",
			"tel": "",
			"fax": "0142548879",
			"mail": ""
		}],
		"ctaList": [{
			"url": "https:\/\/www.google.fr\/maps\/dir\/48.86,2.35\/48.8929,2.338143\/",
			"version": "cta-direction",
			"title": "",
			"target": "_blank"
		}, {
			"url": "http:\/\/agents.peugeot.fr\/paris\/nous-contacter\/info-utiles\/",
			"version": "cta-contact",
			"title": "",
			"target": "_self"
		}],
		"schedules": "Du lundi au vendredi de 8H \u00e0 12H - 14H \u00e0 18H30 Samedi 9H \u00e0 12H",
		"dealer": false,
		"agent": true,
		"principal_vn": false
	}, {
		"id": "0000024478",
		"name": "AUTOSPORT DAUSMENIL",
		"type": "magasin",
		"vehicle_new": true,
		"vehicle_occasion": true,
		"vehicle_location": false,
		"adress": {
			"street": "13 RUE DUGOMMIER",
			"city": "75012 PARIS",
			"country": "",
			"lat": "48.84003",
			"lng": "2.391748",
			"dist": "3.8"
		},
		"contact": {
			"tel": "0143433053",
			"fax": "0144739360",
			"website": "http:\/\/agents.peugeot.fr\/daumesnil-paris",
			"vcf": "http:\/\/fr.psa-ndp.com\/api\/vcf\/pf11\/fr\/2\/0000024478"
		},
		"services": [{
			"code": "vn",
			"type_name": "A",
			"type": "V\u00e9hicule neuf",
			"name": "V\u00e9hicule neuf",
			"tel": "",
			"fax": "0144739360",
			"icon": "http:\/\/media.psa-ndp.com\/image\/83\/6\/footer-social-instagram.6836.png",
			"mail": "AUTOSPORT.DAUMESNIL@WANADOO.FR"
		}, {
			"code": "apv",
			"type_name": "A",
			"type": "Apr\u00e8s-vente",
			"name": "Apr\u00e8s vente surcharg\u00e9 bo",
			"tel": "",
			"fax": "0144739360",
			"mail": "AUTOSPORT.DAUMESNIL@WANADOO.FR"
		}, {
			"code": "pr",
			"type_name": "A",
			"type": "Pi\u00e8ce de rechange",
			"name": "Pi\u00e8ce de rechange",
			"tel": "",
			"fax": "0144739360",
			"mail": ""
		}, {
			"code": "vo",
			"type_name": "A",
			"type": "V\u00e9hicule d'occasion",
			"name": "V\u00e9hicule d'occasion",
			"tel": "",
			"fax": "0144739360",
			"mail": ""
		}],
		"ctaList": [{
			"url": "https:\/\/www.google.fr\/maps\/dir\/48.86,2.35\/48.84003,2.391748\/",
			"version": "cta-direction",
			"title": "",
			"target": "_blank"
		}, {
			"url": "http:\/\/agents.peugeot.fr\/daumesnil-paris\/nous-contacter\/info-utiles\/",
			"version": "cta-contact",
			"title": "",
			"target": "_self"
		}],
		"schedules": "Du Lundi au Vendredi: 7h45-12h 13h30-18h45",
		"dealer": false,
		"agent": true,
		"principal_vn": false
	}, {
		"id": "0000001035",
		"name": "NEUBAUER SA MONTMARTRE",
		"type": "magasin",
		"vehicle_new": false,
		"vehicle_occasion": false,
		"vehicle_location": false,
		"adress": {
			"street": "162, rue Lamarck",
			"city": "75018 PARIS",
			"country": "",
			"lat": "48.89141",
			"lng": "2.327108",
			"dist": "3.9"
		},
		"contact": {
			"tel": "0146273333",
			"fax": "0146274188",
			"website": "http:\/\/concessions.peugeot.fr\/neubauer18",
			"vcf": "http:\/\/fr.psa-ndp.com\/api\/vcf\/pf11\/fr\/2\/0000001035"
		},
		"services": [{
			"code": "apv",
			"type_name": "A",
			"type": "Apr\u00e8s-vente",
			"name": "Apr\u00e8s vente surcharg\u00e9 bo",
			"tel": "",
			"fax": "0146274188",
			"mail": "fdubosse@neubauer.fr"
		}, {
			"code": "pr",
			"type_name": "A",
			"type": "Pi\u00e8ce de rechange",
			"name": "Pi\u00e8ce de rechange",
			"tel": "",
			"fax": "0146274188",
			"mail": ""
		}],
		"ctaList": [{
			"url": "https:\/\/www.google.fr\/maps\/dir\/48.86,2.35\/48.89141,2.327108\/",
			"version": "cta-direction",
			"title": "",
			"target": "_blank"
		}, {
			"url": "http:\/\/concessions.peugeot.fr\/neubauer18\/nous-contacter\/info-utiles\/",
			"version": "cta-contact",
			"title": "",
			"target": "_self"
		}],
		"schedules": "Lundi:08:00-12:00 13:30-17:30<br \/> Mardi:08:00-12:00 13:30-17:30<br \/> Mercredi:08:00-12:00 13:30-17:30<br \/> Jeudi:08:00-12:00 13:30-17:30<br \/> Vendredi:08:00-12:00 13:30-17:30<br \/> Samedi:Ferm\u00e9<br \/> Dimanche:Ferm\u00e9<br \/>",
		"dealer": false,
		"agent": false,
		"principal_vn": false
	}, {
		"id": "0000043493",
		"name": "GARAGE LAURENCE",
		"type": "magasin",
		"vehicle_new": true,
		"vehicle_occasion": false,
		"vehicle_location": false,
		"adress": {
			"street": "40 RUE JOUFFROY D'ABBANS",
			"city": "75017 PARIS",
			"country": "",
			"lat": "48.8856964",
			"lng": "2.308612",
			"dist": "4.2"
		},
		"contact": {
			"tel": "0140540938",
			"fax": "0142275583",
			"website": "http:\/\/agents.peugeot.fr\/paris17",
			"vcf": "http:\/\/fr.psa-ndp.com\/api\/vcf\/pf11\/fr\/2\/0000043493"
		},
		"services": [{
			"code": "vn",
			"type_name": "A",
			"type": "V\u00e9hicule neuf",
			"name": "V\u00e9hicule neuf",
			"tel": "",
			"fax": "0142275583",
			"icon": "http:\/\/media.psa-ndp.com\/image\/83\/6\/footer-social-instagram.6836.png",
			"mail": "garagelaurence@gmail.com"
		}, {
			"code": "apv",
			"type_name": "A",
			"type": "Apr\u00e8s-vente",
			"name": "Apr\u00e8s vente surcharg\u00e9 bo",
			"tel": "",
			"fax": "0142275583",
			"mail": "garagelaurence@gmail.com"
		}],
		"ctaList": [{
			"url": "https:\/\/www.google.fr\/maps\/dir\/48.86,2.35\/48.8856964,2.308612\/",
			"version": "cta-direction",
			"title": "",
			"target": "_blank"
		}, {
			"url": "http:\/\/agents.peugeot.fr\/paris17\/nous-contacter\/info-utiles\/",
			"version": "cta-contact",
			"title": "",
			"target": "_self"
		}],
		"dealer": false,
		"agent": true,
		"principal_vn": false
	}, {
		"id": "0000024487",
		"name": "PEUGEOT PARIS 13 Italie",
		"type": "magasin",
		"vehicle_new": true,
		"vehicle_occasion": true,
		"vehicle_location": false,
		"adress": {
			"street": "105 BIS AVENUE D ITALIE",
			"city": "75013 PARIS",
			"country": "",
			"lat": "48.8226967",
			"lng": "2.358357",
			"dist": "4.2"
		},
		"contact": {
			"tel": "0156105630",
			"fax": "0156105800",
			"website": "http:\/\/concessions.peugeot.fr\/paris13-italie",
			"vcf": "http:\/\/fr.psa-ndp.com\/api\/vcf\/pf11\/fr\/2\/0000024487"
		},
		"services": [{
			"code": "vn",
			"type_name": "A",
			"type": "V\u00e9hicule neuf",
			"name": "V\u00e9hicule neuf",
			"tel": "0156105630",
			"fax": "0156105800",
			"icon": "http:\/\/media.psa-ndp.com\/image\/83\/6\/footer-social-instagram.6836.png",
			"mail": "contact-darlmat@peugeot.com"
		}, {
			"code": "apv",
			"type_name": "A",
			"type": "Apr\u00e8s-vente",
			"name": "Apr\u00e8s vente surcharg\u00e9 bo",
			"tel": "0156105630",
			"fax": "0156105800",
			"mail": "fabien.guiraud@peugeot.com"
		}, {
			"code": "pr",
			"type_name": "A",
			"type": "Pi\u00e8ce de rechange",
			"name": "Pi\u00e8ce de rechange",
			"tel": "0156105630",
			"fax": "0156105800",
			"mail": "contact-darlmat@peugeot.com"
		}, {
			"code": "vo",
			"type_name": "A",
			"type": "V\u00e9hicule d'occasion",
			"name": "V\u00e9hicule d'occasion",
			"tel": "0156105630",
			"fax": "0156105800",
			"mail": ""
		}],
		"ctaList": [{
			"url": "https:\/\/www.google.fr\/maps\/dir\/48.86,2.35\/48.8226967,2.358357\/",
			"version": "cta-direction",
			"title": "",
			"target": "_blank"
		}, {
			"url": "http:\/\/concessions.peugeot.fr\/paris13-italie\/nous-contacter\/info-utiles\/",
			"version": "cta-contact",
			"title": "",
			"target": "_self"
		}],
		"schedules": "Lundi:08:00-19:00<br \/> Mardi:08:00-19:00<br \/> Mercredi:08:00-19:00<br \/> Jeudi:08:00-19:00<br \/> Vendredi:08:00-19:00<br \/> Samedi:09:00-18:30<br \/> Dimanche: Ferm\u00e9<br \/>",
		"dealer": true,
		"agent": true,
		"principal_vn": false
	}],
	"new_vehicle_dealers": [{
		"id": "0000000790",
		"name": "PEUGEOT PARIS 10 GARE DE L'EST",
		"type": "magasin",
		"vehicle_new": true,
		"vehicle_occasion": true,
		"vehicle_location": false,
		"adress": {
			"street": "180 RUE DU FBG ST MARTIN",
			"city": "75010 PARIS",
			"country": "",
			"lat": "48.8782043",
			"lng": "2.362105",
			"dist": "2.2"
		},
		"contact": {
			"tel": "0140056610",
			"fax": "0140056616",
			"website": "http:\/\/concessions.peugeot.fr\/paris10-garedelest",
			"vcf": "http:\/\/fr.psa-ndp.com\/api\/vcf\/pf11\/fr\/2\/0000000790"
		},
		"services": [{
			"code": "vn",
			"type_name": "A",
			"type": "V\u00e9hicule neuf",
			"name": "V\u00e9hicule neuf",
			"tel": "0140066606",
			"fax": "0140056616",
			"icon": "http:\/\/media.psa-ndp.com\/image\/83\/6\/footer-social-instagram.6836.png",
			"mail": "gerald.ponce@peugeot.com"
		}, {
			"code": "apv",
			"type_name": "A",
			"type": "Apr\u00e8s-vente",
			"name": "Apr\u00e8s vente surcharg\u00e9 bo",
			"tel": "0140056605",
			"fax": "0140056616",
			"mail": "contact.botzaris@peugeot.com"
		}, {
			"code": "pr",
			"type_name": "A",
			"type": "Pi\u00e8ce de rechange",
			"name": "Pi\u00e8ce de rechange",
			"tel": "0140066610",
			"fax": "0140056616",
			"mail": "contact.botzaris@peugeot.com"
		}, {
			"code": "vo",
			"type_name": "A",
			"type": "V\u00e9hicule d'occasion",
			"name": "V\u00e9hicule d'occasion",
			"tel": "0140066694",
			"fax": "0140056616",
			"mail": "contact.botzaris@peugeot.com"
		}],
		"ctaList": [{
			"url": "https:\/\/www.google.fr\/maps\/dir\/48.86,2.35\/48.8782043,2.362105\/",
			"version": "cta-direction",
			"title": "",
			"target": "_blank"
		}, {
			"url": "http:\/\/concessions.peugeot.fr\/paris10-garedelest\/nous-contacter\/info-utiles\/",
			"version": "cta-contact",
			"title": "",
			"target": "_self"
		}],
		"schedules": "Lundi:07:30-19:00<br \/> Mardi:07:30-19:00<br \/> Mercredi:07:30-19:00<br \/> Jeudi:07:30-19:00<br \/> Vendredi:07:30-19:00<br \/> Samedi:08:30-18:30<br \/> Dimanche:Ferm\u00e9<br \/>",
		"dealer": true,
		"agent": false,
		"principal_vn": true
	}, {
		"id": "0000020937",
		"name": "PEUGEOT PARIS 15 GRENELLE",
		"type": "magasin",
		"vehicle_new": true,
		"vehicle_occasion": true,
		"vehicle_location": false,
		"adress": {
			"street": "146, bd de Grenelle",
			"city": "75015 Paris",
			"country": "",
			"lat": "48.848423",
			"lng": "2.299039",
			"dist": "3.9"
		},
		"contact": {
			"tel": "0156105610",
			"fax": "0156105710",
			"website": "http:\/\/concessions.peugeot.fr\/paris15-grenelle",
			"vcf": "http:\/\/fr.psa-ndp.com\/api\/vcf\/pf11\/fr\/2\/0000020937"
		},
		"services": [{
			"code": "vn",
			"type_name": "A",
			"type": "V\u00e9hicule neuf",
			"name": "V\u00e9hicule neuf",
			"tel": "0156105610",
			"fax": "0156105710",
			"icon": "http:\/\/media.psa-ndp.com\/image\/83\/6\/footer-social-instagram.6836.png",
			"mail": "MARC.GIULIOLI@PEUGEOT.COM"
		}, {
			"code": "apv",
			"type_name": "A",
			"type": "Apr\u00e8s-vente",
			"name": "Apr\u00e8s vente surcharg\u00e9 bo",
			"tel": "0156105610",
			"fax": "0156105710",
			"mail": "david.gourbeyre@mpsa.com"
		}, {
			"code": "pr",
			"type_name": "A",
			"type": "Pi\u00e8ce de rechange",
			"name": "Pi\u00e8ce de rechange",
			"tel": "0156105610",
			"fax": "0156105710",
			"mail": "contact-darlmat@peugeot.com"
		}, {
			"code": "vo",
			"type_name": "A",
			"type": "V\u00e9hicule d'occasion",
			"name": "V\u00e9hicule d'occasion",
			"tel": "0156105610",
			"fax": "0156105710",
			"mail": "contact-darlmat@peugeot.com"
		}],
		"ctaList": [{
			"url": "https:\/\/www.google.fr\/maps\/dir\/48.86,2.35\/48.848423,2.299039\/",
			"version": "cta-direction",
			"title": "",
			"target": "_blank"
		}, {
			"url": "http:\/\/concessions.peugeot.fr\/paris15-grenelle\/nous-contacter\/info-utiles\/",
			"version": "cta-contact",
			"title": "",
			"target": "_self"
		}],
		"schedules": "Lundi:07:30-19:00<br \/> Mardi:07:30-19:00<br \/> Mercredi:07:30-19:00<br \/> Jeudi:07:30-19:00<br \/> Vendredi:07:30-19:00<br \/> Samedi:09:00-18:30<br \/> Dimanche: Ferm\u00e9<br \/>",
		"dealer": true,
		"agent": false,
		"principal_vn": true
	}, {
		"id": "0000020946",
		"name": "PEUGEOT PARIS 14 ALESIA",
		"type": "magasin",
		"vehicle_new": true,
		"vehicle_occasion": true,
		"vehicle_location": false,
		"adress": {
			"street": "3, 5 Avenue Jean Moulin",
			"city": "75014 Paris",
			"country": "",
			"lat": "48.82705",
			"lng": "2.326384",
			"dist": "4.1"
		},
		"contact": {
			"tel": "0156105620",
			"fax": "0156105750",
			"website": "http:\/\/concessions.peugeot.fr\/paris14-alesia",
			"vcf": "http:\/\/fr.psa-ndp.com\/api\/vcf\/pf11\/fr\/2\/0000020946"
		},
		"services": [{
			"code": "vn",
			"type_name": "A",
			"type": "V\u00e9hicule neuf",
			"name": "V\u00e9hicule neuf",
			"tel": "0156105620",
			"fax": "0156105750",
			"icon": "http:\/\/media.psa-ndp.com\/image\/83\/6\/footer-social-instagram.6836.png",
			"mail": "marc.giulioli@peugeot.com"
		}, {
			"code": "apv",
			"type_name": "A",
			"type": "Apr\u00e8s-vente",
			"name": "Apr\u00e8s vente surcharg\u00e9 bo",
			"tel": "0156105620",
			"fax": "0156105750",
			"mail": "fabien.guiraud@peugeot.com"
		}, {
			"code": "pr",
			"type_name": "A",
			"type": "Pi\u00e8ce de rechange",
			"name": "Pi\u00e8ce de rechange",
			"tel": "0156105620",
			"fax": "0156105750",
			"mail": "contact-darlmat@peugeot.com"
		}, {
			"code": "vo",
			"type_name": "A",
			"type": "V\u00e9hicule d'occasion",
			"name": "V\u00e9hicule d'occasion",
			"tel": "0156105620",
			"fax": "0156105750",
			"mail": "contact-darlmat@peugeot.com"
		}],
		"ctaList": [{
			"url": "https:\/\/www.google.fr\/maps\/dir\/48.86,2.35\/48.82705,2.326384\/",
			"version": "cta-direction",
			"title": "",
			"target": "_blank"
		}, {
			"url": "http:\/\/concessions.peugeot.fr\/paris14-alesia\/nous-contacter\/info-utiles\/",
			"version": "cta-contact",
			"title": "",
			"target": "_self"
		}],
		"schedules": "Lundi:07:30-19:00<br \/> Mardi:07:30-19:00<br \/> Mercredi:07:30-19:00<br \/> Jeudi:07:30-19:00<br \/> Vendredi:07:30-19:00<br \/> Samedi:09:00-18:30<br \/> Dimanche: Ferm\u00e9<br \/>",
		"dealer": true,
		"agent": false,
		"principal_vn": true
	}]
};
var maxResults = 5;
var dealerLocator;

describe("Test Dealer initialization", function () {
	beforeEach(function (done) {
		// spyOn(DealerLocator.prototype, 'addScript');
		var initialInit = DealerLocator.prototype.init;
		spyOn(DealerLocator.prototype, 'init').and.callFake(function () {
			initialInit.apply(dealerLocator, arguments);
			done();
		});
		dealerLocator = new DealerLocator(false, {
			maxResults: maxResults,
			dealers: jsonStub.listDealer,
			url: '/api/search-pointofsale/fr/2/TN/2/1/10/10/20/10/1'
		});
	});
	// it("has added scripts", function() {
	// 	expect(DealerLocator.prototype.addScript.calls.count()).toBe(2);
	// });
	it("has been init", function () {
		expect(DealerLocator.prototype.init).toHaveBeenCalled();
	});
});

describe("Test Dealer formating", function () {

	// var dealerLocator = new DealerLocator(false, {
	// 	maxResults: maxResults,
	// 	dealers: jsonStub.listDealer
	// });
	it("results don't exceed maximum", function () {
		dealerLocator.formatDealers();
		expect(dealerLocator.options.dealers.length).toBe(maxResults);
	});
	it("cta correctly formatted", function () {
		expect(dealerLocator.options.dealers[0].ctaList.length).toBe(2);
		expect(dealerLocator.options.dealers[0].ctaList[0].version).toBe('cta-call');
		expect(dealerLocator.options.dealers[0].ctaList[1].version).not.toBe('cta-direction');
	});
});


describe("Test dealer locator call ajax ws in getDealers function", function () {

	beforeEach(function () {
		jasmine.Ajax.install();
	});

	afterEach(function () {
		jasmine.Ajax.uninstall();
	});

	it("Test call to ws for de", function () {

		jasmine.Ajax.stubRequest('/api/search-pointofsale/fr/2/TN/2/1/10/10/20/10/1').andReturn({
			"status": 200,
			"contentType": 'text/plain',
			"responseText": 'hello'
		});

		dealerLocator.getDealers('{departure: "48.86,2.35"}');

		expect(jasmine.Ajax.requests.mostRecent().url).toBe('/api/search-pointofsale/fr/2/TN/2/1/10/10/20/10/1?{departure: "48.86,2.35"}');

	});

});
