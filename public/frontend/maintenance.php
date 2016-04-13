<?php
include_once ('config.php');
/**
* Maintenance page (HTTP 503)
* !!! Make sure to use UTF-8 without BOM to edit this file
*
* How to use
* ==========
* 1. Set language data for each country/culture ($config['languages'])
* 2. Bind host and url prefix to language data (switch ($host))
*/

// BEGIN configuration
$config['default_language'] = 'fr-FR';
$config['logo'] = array('url'=>'http://media.citroen.fr/design/frontend/images/logo.png', 'alt'=>'Citroën');
$config['languages'] = array(
	'es-AR' => array(
        "brand"       => "Citroën",
        "logo_url"    => "http://media.citroen.com.ar/design/frontend/images/logo.png",
        "page_title"  => " Error: Sitio en mantenimiento ",
        //"image_url"   => "http://citroen-prensa.com.ar/imagenes/mantenimiento_cpp.jpg",
        "image_url"   => "/design/frontend/images/maintenance/503AR.jpg",
        "subtitle"    => "Mantenimiento",
        "parttitle"   => " El sitio de Citroën Argentina esta actualmente bajo tareas de mantenimiento.\nWe Lo invitamos a regresar más tarde.",
        "text_top"    => " ¿Estás buscando información de Citroën? Podés visitar nuestras Redes Sociales Oficiales.",
        "links_title" => "Otros Sitios Citroën y DS",
        "links" => array(
            array("Facebook CITROEN", "https://es-la.facebook.com/Citroen.Argentina"),
            array("Twitter CITROEN", "https://twitter.com/citroen_arg"),
            array("Youtube CITROEN", "https://www.youtube.com/user/CITROENARGENTINA"),
			array("Facebook DS", "https://www.facebook.com/DS.ArgOficial"),
            array("Twitter DS", "https://twitter.com/ds_argentina"),

        ),
        "text_bottom" => " Pedimos disculpas por las molestias",
    ),
	'de-AT' => array(
        "brand"       => "Citroën",
        "logo_url"    => "http://media.citroen.fr/design/frontend/images/logo.png",
        "page_title"  => "Fehler: Die Seite wird derzeit gewartet ",
         "image_url"   => "/design/frontend/images/maintenance/503PL.jpg",
        "subtitle"    => "Wartung",
        "parttitle"   => "Die Website von Citroën wird derzeit gewartet.\nBitte besuchen Sie uns zu einem späteren Zeitpunkt nochmals.",
        "text_top"    => "Suchen Sie eine spezielle Information? Bitte besuchen Sie dafür eine unserer anderen Websites.",
        "links_title" => "ANDERE WEB-ANGEBOTE",
        "links" => array(
            array("CarStore", "http://www.carstore.citroen.at/startseite"),
            array("Citroën Select", "http://www.citroenselect.at/"),
            array("Citroën-Partner", "http://www.citroen-partner.at/"),
            array("Konfigurator", "http://konfigurator.citroen.at/#content"),
        ),
        "text_bottom" => "Wir bitten Sie, die entstandenen Unannehmlichkeiten zu entschuldigen.",
    ),
	'cs-CZ' => array(
        "brand"       => "Citroën",
        "logo_url"    => "http://data.citroen.cz/web/components/error/logo.png",
        "page_title"  => " Chyba: Na stránkách probíhá údržba ",
        "image_url"   => "/design/frontend/images/maintenance/503CZ.png",
        "subtitle"    => "Údržba",
        "parttitle"   => " Náš web Citroën aktuálně prochází údržbou, kterou není možné provést za provozu.\n Prosíme, zkuste se vrátit později.",
        "text_top"    => " Hledáte konkrétní informaci či vůz? Zkuste v mezičase navštívit naše další webové aplikace a stránky.",
        "links_title" => "Další weby",
        "links" => array(
            array("Citroën Car Store - nové vozy ihned k odběru", "http://www.carstore.citroen.cz/uvodni-stranka"),
            array("Citroën Select - certifikované ojeté vozy", "http://www.citroenselect.cz/cs/home"),
            array("MyCITROËN - Váš osobní prostor Citroën", "http://www.mycitroen.cz/MyCITROEN/"),
            array("Citroën Česká republika na Facebooku", "http://www.facebook.com/citroen.ceska.republika"),
            array("YouTube kanál Citroën Česká republika", "http://www.youtube.com/citroencesko"),
        ),
        "text_bottom" => " Omlouváme se za způsobené komplikace",
    ),
	'de-DE' => array(
        "brand"       => "CITROËN",
        "logo_url"    => "http://media.citroen.de/design/frontend/images/logo.png",
        "page_title"  => "Error 503: Website aktuell nicht verfügbar",
        "image_url"   => "/design/frontend/images/maintenance/503DE.jpg",
        "subtitle"    => "Wartungsarbeiten",
        "parttitle"   => "Wir möchten unseren Service für Sie weiter verbessern.\nBitte versuchen es doch später wieder.",
        "text_top"    => " Finden Sie die gesuchte Information auf unseren weiteren Seiten.",
        "links_title" => "WEITERE WEBSITES",
        "links" => array(
            array("DriveDS, die Website der Marke DS", "http://www.driveds.de"),
            array("CITROËN Rent, die händlereigene Autovermietung", "http://www.citroenrent.de"),
            array("CITROËN Select, unsere geprüften Gebrauchtwagen", "http://www.citroenselect-gebrauchtwagen.de"),
            array("Motorsport", "http://www.citroen-motorsport.de"),
        ),
        "text_bottom" => "Bitte entschuldigen Sie die Unannehmlichkeiten.",
    ),
	'de-CH' => array(
        "brand"       => "Citroën",
        "logo_url"    => "http://media.citroen.fr/design/frontend/images/logo.png",
        "page_title"  => "Erreur : Die Seite wird gerade überarbeitet.",
         "image_url"   => "/design/frontend/images/maintenance/503PL.jpg",
        "subtitle"    => " Wartung ",
        "parttitle"   => " Die Seite wird gerade überarbeitet. / Bitte besuchen Sie uns zu einem späteren Zeitpunkt wieder.",
        "text_top"    => " Sie suchen etwas bestimmtes ? Besuchen Sie unsere anderen Seiten.",
        "links_title" => "Andere Seite",
        "links" => array(
            array("CarStore", "http://www.carstore.citroen.ch/de/"),
            array("Citroën Select", "http://www.eurocasion.ch/d.htm"),
            array("Konfigurator", "http://modelle.citroen.ch/"),
            array("Händler Website", "http://www.dealer.citroen.ch/de"),
        ),
        "text_bottom" => " Bitte entschuldigen Sie die Unannehmlichkeit.",
    ),
	'da-DK' => array(
        "brand"       => "Citroën",
        "logo_url"    => "http://media.citroen.fr/design/frontend/images/logo.png",
        "page_title"  => " Fejl: Siden er under vedligeholdelse ",
        "image_url"   => "/design/frontend/images/maintenance/503DK.jpg",
        "subtitle"    => "Vedligeholdelse",
        "parttitle"   => " Citroëns hjemmeside er i øjeblikket under vedligeholdelse.\nPrøv venligst igen senere.",
        "text_top"    => " Leder du efter noget bestemt? Besøg en af vores andre hjemmesider.",
        "links_title" => "Andre hjemmesider",
        "links" => array(
            array("Forhandlerhjemmesider", "http://www.citroenforhandler.dk/"),
            array("Book værkstedstid", "http://service.citroen.dk/"),
            array("Konfigurator", "http://konfigurator.citroen.dk/"),
            array("Køb tilbehør", "http://shop.citroen.dk/da"),
        ),
        "text_bottom" => " Vi beklager ulejligheden.",
    ),
	'es-ES' => array(
        "brand"       => "Citroën",
        "logo_url"    => "http://media.citroen.fr/design/frontend/images/logo.png",
        "page_title"  => "Site en mantenimiento",
         "image_url"   => "/design/frontend/images/maintenance/503PL.jpg",
        "subtitle"    => "Mantenimiento",
        "parttitle"   => "El site citroën está en mantenimiento.\nTe invitamos a volver más tarde.",
        "text_top"    => "Quizá encuentres lo que buscas en otro de nuestros sites.",
        "links_title" => "OTROS SITES CITROËN",
        "links" => array(
            array("Configurador", "http://configurador.citroen.es/#content"),
            array("CarStore", "http://www.carstore.citroen.es"),
            array("Citroën Select", "http://www.citroenselect.es"),
            ),
        "text_bottom" => "Lamentamos las molestias ocasionadas.",
    ),
	'fr-FR' => array(
        "brand"       => "Citroën",
        "logo_url"    => "http://media.citroen.fr/design/frontend/images/logo.png",
        "page_title"  => "Erreur : site en cours de maintenance",
        "image_url"   => "/design/frontend/images/maintenance/503PL.jpg",
        "subtitle"    => "Maintenance",
        "parttitle"   => "Le site Citroën est actuellement en maintenance.\nNous vous invitons à revenir ultérieurement.",
        "text_top"    => "Vous cherchez une information en particulier ? Rendez-vous sur nos autres sites.",
        "links" => array(
            array("Consultez les véhicules en stock près de chez vous", "http://www.carstore.citroen.fr/accueil"),
            array("Configurez votre véhicule", "http://www.configurateur.citroen.fr"),
            array("Découvrez l’Univers de DS", "http://www.driveds.fr/fr"),
            array("Citroën Select, nos véhicules d’occasion", "http://www.citroenselect.fr/fr/Voiture-Occasion"),
            array("Véhicules collaborateurs", "http://www.collcit.com/Collcit/HomePage.aspx"),
        ),
        "text_bottom" => "Veuillez nous excuser pour la gêne occasionnée.",
    ),
	'fr-BE' => array(
        "brand"       => "Citroën",
        "logo_url"    => "http://media.citroen.fr/design/frontend/images/logo.png", 
        "page_title"  => "Erreur : site en cours de maintenance",
        "image_url"   => "/design/frontend/images/maintenance/503PL.jpg",
        "subtitle"    => "Maintenance",
        "parttitle"   => "Le site citroën est actuellement en maintenance.\nNous vous invitons à revenir ultérieurement.",
        "text_top"    => "Vous cherchez une information en particulier ? Rendez-vous sur nos autres sites.",
        "links_title" => "AUTRES SITES",
        "links" => array(
            array("CarStore", " http://www.carstore.citroen.be/fr/Accueil"),
            array("Citroën Select", " http://www.citroenselect.be/fr/accueil"),
            array("Configurateur", " http://configurateur-vehicules.citroen.be/#content"),
            array("eDealer", " http://www.dealer.citroen.be/pointsdevente?_ga=1.147956599.1509545542.1415779241"),
        ),
        "text_bottom" => "Veuillez nous excuser pour la gêne occasionnée.",
    ),
	'fr-CH' => array(
        "brand"       => "Citroën",
        "logo_url"    => "http://media.citroen.fr/design/frontend/images/logo.png",
        "page_title"  => "Erreur : site en cours de maintenance",
        "image_url"   => "/design/frontend/images/maintenance/503PL.jpg",
        "subtitle"    => "Maintenance",
        "parttitle"   => "Le site citroën est actuellement en maintenance.\nNous vous invitons à revenir ultérieurement.",
        "text_top"    => "Vous cherchez une information en particulier ? Rendez-vous sur nos autres sites.",
        "links_title" => "AUTRES SITES",
        "links" => array(
            array("CarStore", "http://www.carstore.citroen.ch/fr/Accueil"),
            array("Citroën Select", "http://www.eurocasion.ch/f.htm"),
            array("Configurateur", "http://modeles.citroen.ch/"),
            array("Sites point de Vente", "http://www.dealer.citroen.ch/fr"),
        ),
        "text_bottom" => "Veuillez nous excuser pour la gêne occasionnée.",
    ),
	'hu-HU' => array(
        "brand"       => "Citroën",
        "logo_url"    => "http://media.citroen.fr/design/frontend/images/logo.png",
        "page_title"  => "Karbantartás",
        "image_url"   => "/design/frontend/images/maintenance/503PL.jpg",
        "subtitle"    => "Tisztelt látogatónk!",
        "parttitle"   => "Honlapunkon rövid ideig tartó karbantartási munkálatokat végzünk.\nKérjük, nézzen vissza később.",
        "text_top"    => "Ha speciális információkra van szüksége, látogassa meg tematikus oldalainkat.",
        "links_title" => "Other Website",
        "links" => array(
            array("Új Citroën készletről", " http://citroenakciok.hu/"),
            array("A Citroën Magyar Facebook-oldala", " https://www.facebook.com/citroen.magyarorszag"),
            array("Magyarországi Citroën márkakereskedők", " http://www.kereskedok.citroen.hu"),
        ),
        "text_bottom" => "A kellemetlenségért elnézését kérjük.",
    ),
	'en-IE' => array(
        "brand"       => "Citroën",
        "logo_url"    => "http://media.citroen.fr/design/frontend/images/logo.png",
        "page_title"  => " Error: Site under maintenance ",
        "image_url"   => "/design/frontend/images/maintenance/503PL.jpg",
        "subtitle"    => "Maintenance",
        "parttitle"   => " The Citroën website is currently under maintenance.\nWe invite you to come back later.",
        "text_top"    => " Looking for a particular information ? Visit our other websites.",
        "links_title" => "Other Website",
        "links" => array(
            array("Citroën Select", "http://usedcars.citroen.ie/usedcars/"),
            array("Citroën Offers", "http://www.citroenoffers.ie/"),
        ),
        "text_bottom" => " We apologize for the inconvenience.",
    ),
	'it-IT' => array(
        "brand"       => "Citroën",
        "logo_url"    => "http://media.citroen.fr/design/frontend/images/logo.png",
        "page_title"  => " Il sito non è disponibile",
        "image_url"   => "/design/frontend/images/maintenance/503PL.jpg",
        "subtitle"    => "Indisponibile",
        "parttitle"   => "Il sito Citroën non è disponibile.\Ti invitiamo a tornare più tardi.",
        "text_top"    => "Cerchi un’informazione particolare ? Rendez-vous sur nos autres sites.",
        "links_title" => "ALTRI SITI",
        "links" => array(
            array("CarStore", " http://www.carstore.citroen.it"),
            array("Citroën Select", " http://www.citroenselect.it"),
            array("Configuratore", " http://configuratore.citroen.it"),
            
        ),
        "text_bottom" => "Ci scusiamo per il disagio.",
    ),
	'it-CH' => array(
        "brand"       => "Citroën",
        "logo_url"    => "http://media.citroen.fr/design/frontend/images/logo.png",
        "page_title"  => "Erreur : sito internet è in fase di manutenzione",
        "image_url"   => "/design/frontend/images/maintenance/503PL.jpg",
        "subtitle"    => "Manutenzione",
        "parttitle"   => "Il nostro sito internet è in fase di manutenzione./ invitiamo a riprovare più tardi.",
        "text_top"    => "In cerca di qualcosa di specifico? Abbiamo altri siti internet da visitare.",
        "links_title" => "Andere Seite",
        "links" => array(
            array("CarStore", "http://www.carstore.citroen.ch/it/home"),
            array("Citroën Select", "http://www.eurocasion.ch/i.htm"),
            array("Configuratore", "http://modelli.citroen.ch/"),
            array("Concessionari", "http://www.dealer.citroen.ch/it"),
        ),
        "text_bottom" => "Ci scusiamo per il disagio.",
	),
	'fr-LU' => array(
        "brand"       => "Citroën",
        "logo_url"    => "http://media.citroen.fr/design/frontend/images/logo.png", 
        "page_title"  => "Erreur : site en cours de maintenance",
        "image_url"   => "/design/frontend/images/maintenance/503PL.jpg",
        "subtitle"    => "Maintenance",
        "parttitle"   => "Le site citroën est actuellement en maintenance.\nNous vous invitons à revenir ultérieurement.",
        "text_top"    => "Vous cherchez une information en particulier ? Rendez-vous sur nos autres sites.",
        "links_title" => "AUTRES SITES",
        "links" => array(
            array("CarStore", "http://www.carstore.citroen.lu/Accueil"),
            array("Citroën Select", "http://www.citroenselect.be/fr/accueil"),
            array("Configurateur", "http://configurateur-vehicules.citroen.lu/#content"),
            array("eDealer", "http://www.dealer.citroen.lu/"),
        ),
        "text_bottom" => "Veuillez nous excuser pour la gêne occasionnée.",
    ),
	'nl-NL' => array(
   	"brand"   	=> "Citroën Nederland",
   	"logo_url"	=> "http://acties.citroen.nl/wp-content/uploads/2014/11/logo.png",
   	"page_title"  => "Error: site in onderhoud",
   	"image_url"   => "/design/frontend/images/maintenance/503NL.jpg",
   	"subtitle"	=> "Onderhoud",
   	"parttitle"   => "Citroën.nl is tijdelijk niet beschikbaar in verband met onderhoudswerkzaamheden.",
   	"text_top"	=> "Wellicht kunt u de benodigde informatie vinden op een van onze andere sites.",
   	"links_title" => "ANDERE SITES",
   	"links" => array(
       	array("Citroën-dealers", "http://dealer.citroen.nl/"),
       	array("Car Store", "http://www.carstore.citroen.nl/Accueil"),
       	array("Occasions", "http://www.citroenselect.nl/nl/home"),
       	array("Car Configurator", "http://carconfigurator.citroen.nl/#content"),
		array("Drive DS", "http://www.driveds.nl/nl"),
   	),
		"text_bottom" => "Onze excuses voor het ongemak.",
	),
	'nl-BE' => array(
        "brand"       => "Citroën",
        "logo_url"    => "http://media.citroen.fr/design/frontend/images/logo.png", 
        "page_title"  => "Foutmelding : de website is in onderhoud",
       "image_url"   => "/design/frontend/images/maintenance/503PL.jpg",
        "subtitle"    => "Onderhoud",
        "parttitle"   => "De Citroën-website is momenteel in onderhoud.\nGelieve het later opnieuw te proberen.",
        "text_top"    => "Bent u op zoek naar een speciale informatie ? Surf dan naar onze andere websites",
        "links_title" => "ANDERE WEBSITES",
        "links" => array(
            array("CarStore", " http://www.carstore.citroen.be/nl/home"),
            array("Citroën Select", " http://www.citroenselect.be/nl/home"),
            array("Configurateur", " http://configurator-voertuigen.citroen.be/#content"),
            array("eDealer", "http://www.dealer.citroen.be/verkooppunten"),
        ),
        "text_bottom" => "Gelieve ons te verontschuldigen voor het ongemak.",
    ),
	'nb-NO' => array(
        "brand"       => "Citroën",
        "logo_url"    => "http://media.citroen.fr/design/frontend/images/logo.png",
        "page_title"  => " Feil: Siden er under vedlikehold.",
        "image_url"   => "/design/frontend/images/maintenance/503NO.jpg",
        "subtitle"    => "Vedlikehold",
        "parttitle"   => " Citroëns hjemmeside er for øyeblikket under vedlikehold.\nVennligst prøv igjen senere.",
        "text_top"    => " Ser du etter noe spesielt ? Besøk en av våre andre nettsider.",
        "links_title" => "Andre nettsider",
        "links" => array(
            array("Forhandlerhjemmesider", "http://www.forhandler.citroen.no/"),
            array("Bestill verkstedstime", "http://verkstedstime.citroen.no/"),
			array("Konfigurator", "http://konfigurator.citroen.no"),
            array("Kjøp tilbehør", "http://shop.citroen.no/no"),
            
        ),
        "text_bottom" => "Vi beklager ulempen",
    ),
	'pl-PL' => array(
        "brand"       => "Citroën",
        "logo_url"    => "http://media.citroen.fr/design/frontend/images/logo.png",
        "page_title"  => "Przerwa konserwacyjna",
        "image_url"   => "/design/frontend/images/maintenance/503PL.jpg",
        "subtitle"    => "Przerwa konserwacyjna",
        "parttitle"   => "Strona jest w trakcie prac konserwacyjnych. Zapraszamy wkrótce.",
        "text_top"    => "Potrzebujesz dodatkowych informacji? Zapraszamy na inne strony Citroën Polska:",
        "links_title" => "",
        "links" => array(
            array("Konfigurator aut osobowych Citroën", "http://konfigurator.citroen.pl/"),
            array("Konfigurator aut dostawczych Citroën", "http://dostawcze.citroen.pl/dostawcze/"),
            array("Citroën Dealer", "http://www.dealer.citroen.pl/"),
            array("Citroën Select", "http://www.citroenselect.pl/pl/Samochody-uzywane-z-gwarancja"),
            array("MyCitroën", "http://www.mycitroen.pl/MyCITROEN/"),
        ),
        "text_bottom" => "Przepraszamy za utrudnienia.",
    ),
	'pt-PT' => array(
        "brand"       => "Citroën",
        "logo_url"    => "http://media.citroen.pt/design/frontend/images/logo.png",
        "page_title"  => "Erro : site em  manutenção",
        "image_url"   => "/design/frontend/images/maintenance/503PL.jpg",
        "subtitle"    => "Manutenção",
        "parttitle"   => "O site citroën encontra-se actualmente em manutenção.\nAgradecemos que tente mais tarde.",
        "text_top"    => "Procura alguma informação em particular ? Visite-nos nos nossos sites alternativos",
        "links_title" => "OUTROS SITES",
        "links" => array(
            array("CarStore", "http://www.carstore.citroen.pt/entrada"),
            array("Citroën Select", "http://www.citroenselect.pt/entrada"),
            
        ),
        "text_bottom" => "Queira desculpar-nos pelo inconveniente causado.",
	),
	'sv-SE' => array(
        "brand"       => "Citroën",
        "logo_url"    => "http://media.citroen.fr/design/frontend/images/logo.png",
        "page_title"  => " Stängd pga underhåll ",
        "image_url"   => "/design/frontend/images/maintenance/503PL.jpg",
        "subtitle"    => "Underhåll",
        "parttitle"   => " Citroëns website är stängd pga av underhåll.\nVi ber dig att besöka oss lite senare.",
        "text_top"    => " Letar du efter någon specifk information ? Besök någon av våra andra websiter.",
        "links_title" => "Andra websiter",
        "links" => array(
            array("Citroën CarStore", "http://www.carstore.citroen.se/Hem"),
            array("Citroën Select", "http://access.bytbil.com/citroensverige/Access/Home/SokSelected?s3path=citroen-selectbilar"),
			array("Citroën Tillbehörs-webbshop", "http://shop.citroen.se/sv"),
            array("Boka verkstadsbesök", "http://service.citroen.se/"),
        ),
        "text_bottom" => " Vi ber om ursäkt för olägenheten.",
    ),
	'sl-SI' => array(
        "brand"       => "Citroën",
        "logo_url"    => "http://media.citroen.fr/design/frontend/images/logo.png",
        "page_title"  => "Napaka: Stran je v prenovi.",
        "image_url"   => "/design/frontend/images/maintenance/503PL.jpg",
        "subtitle"    => "Prenova",
        "parttitle"   => "Spletna stran Citroën je trenutno v prenovi.\Obiščite nas ponovno v kratkem.",
        "text_top"    => "Iščete točno določene informacije? Obiščite ostale naše spletne strani.",
        "links_title" => "Ostale spletne strani",
        "links" => array(
            array("Citroën Select", "http://rabljena.citroen.si/"), 
        ),
        "text_bottom" => "Opravičujemo se vam zaradi nevščenosti. Hvala za razumevanje.",
    ),
	'sk-SK' => array(
        "brand"       => "Citroën",
        "logo_url"    => "http://media.citroen.fr/design/frontend/images/logo.png",
        "page_title"  => "Chyba : na stránke sa momentálne pracuje",
        "image_url"   => "/design/frontend/images/maintenance/503PL.jpg",
        "subtitle"    => "Údržba",
        "parttitle"   => "Na stránke citroën sa momentálne pracuje.\nProsím, navštívte túto stánku neskôr.",
        "text_top"    => "Zatiaľ môžete navštíviť nasledovné stránky.",
        "links_title" => "ĎALŠIE STRÁNKY",
        "links" => array(
            array("Citroën Select", "http://www.citroen-bazar.sk/Main.aspx?doinit=1"),
            array("Konfigurátor", "http://konfigurator.citroen.sk/"),
            array("Konfigurátor servisných kontraktov", "http://promo.citroen.sk/servis-kalkulator/"),
        ),

        "text_bottom" => " Ospravedlňujeme sa za spôsobené nepríjemnosti.",
    ),
	'br-BR' => array(
        "brand"       => "Citroën",
        "logo_url"    => "http://media.citroen.fr/design/frontend/images/logo.png",
        "page_title"  => " Erro: Site em manutenção ",
         "image_url"   => "/design/frontend/images/maintenance/503PL.jpg",
        "subtitle"    => "Manutenção",
        "parttitle"   => "O site da Citroën do Brasil está em manutenção. Convidamos você a voltar mais tarde",
        "text_top"    => " Visite nossos outros sites",
        "links_title" => "Acesse",
        "links" => array(
            array("Fidélité", "http://www.citroenfidelite.com.br/"),
            array("Blog Créative Technologie", " http://blog.citroen.com.br/"),
            array("Facebook", "http://www.facebook.com/CitroenBrasil"),
        ),
        "text_bottom" => "Pedimos  desculpas pela inconveniência",
    ),
	
	'hr-HR' => array(
        "brand"       => "Citroën",
        "logo_url"    => "http://media.citroen.fr/design/frontend/images/logo.png",
        "page_title"  => " Stranica trenutno nedostupna zbog održavanja",
         "image_url"   => "/design/frontend/images/maintenance/503PL.jpg",
        "subtitle"    => "Redovito održavanja stranice",
        "parttitle"   => " Citroën stranica trenutno je nedostupna zbog redovitog održavanja.\nPozivamo Vas da se vratite malo kasnije.",
        "text_top"    => " Tražite određenu informaciju ? Posjetite naše ostale stranice.",
        "links_title" => "Ostale stranice",
        "links" => array(
            array("Konfigurator", "http://konfigurator.citroen.hr/"),
            array("Citroën mreža", "http://www.citroenmreza.hr/"),
            array("Citroën Facebook", "https://www.facebook.com/Citroen.Hrvatska"),
            array("MyCitroën", " http://www.mycitroen.hr/MyCitroen/"),
		),
        "text_bottom" => " Ispričavamo se na neugodnosti.",
	),
	'ru-RU' => array(
			"brand"       => "Citroën",
			"logo_url"    => "http://info.citroen.ru/images/logo.png",
			"page_title"  => "Техническое обслуживание",
			"image_url"   => "/design/frontend/images/maintenance/503RU.jpg",
			"subtitle"    => "Техническое обслуживание",
			"parttitle"   => "На нашем сайте проводятся технические работы. \nПожалуйтса, повторите попытку чуть позже.",
			"text_top"    => "Но самые необходимые страницы всегда доступны!",
			"links_title" => "Вы можете",
			"links" => array(
				array("Записаться на тест-драйв", "http://info.citroen.ru/request/test-drive/"),
				array("Заказать звонок", "http://info.citroen.ru/request/callback/"),
				array("Сконфигурировать автомобиль Citroen", "http://configurator.citroen.ru/"),
				array("Посмотреть прайс-лист", "http://info.citroen.ru/price/"),
			),
			"text_bottom" => " Приносим свои извинения за доставленные неудобства.",
	),

	'ru-UA' => array(
		 "brand" => "Citroën",
		 "logo_url" => "http://media.citroen.fr/design/frontend/images/logo.png",
		  "page_title" => "Ошибка: сайт временно недоступен",
		 "subtitle" => "Сайт временно недоступен",
		 "image_url"   => "/design/frontend/images/maintenance/503PL.jpg",
		 "parttitle" => "Сайт Citroën временно недоступен. Проводятся технические работы.\nПожалуйста, попробуйте зайти позже.",
		 "text_top" => "Также вы можете перейти на другие сайты Citroën Украина.",
		 "links_title" => "Другие сайты",
		 "links" => array(
		 array("Конфигуратор", "http://configurator.citroen.ua/"),
		 array("Записаться на тест-драйв", "http://info.citroen.ua/testDrives/"),
		 array("Запросить спецпредложение", "http://www.info.citroen.ua/offerRequest/"),
		  ),
		 "text_bottom" => "Приносим извинения за доставленные неудобства.",
	 ),
	 'gb-GB' => array(
        "brand"       => "Citroën",
         "logo_url" => "http://media.citroen.fr/design/frontend/images/logo.png",
        "page_title"  => " Site under maintenance ",
         "image_url"   => "/design/frontend/images/maintenance/503UK.jpg",
        "subtitle"    => "Maintenance",
        "parttitle"   => "The Citroën website is currently under maintenance.\n Please try again later.",
        "text_top"    => " Alternatively, select from the links below to explore:",
        "links" => array(
            array("Offers ", "http://info.citroen.co.uk/offers/"),
            array("Request a Test Drive ", "http://info.citroen.co.uk/contact-us/retail-test-drive"),
            array("Request a Brochure ", "http://info.citroen.co.uk/contact-us/request-a-brochure"),
            array("Configure your vehicle ", "http://www.car-configurator.citroen.co.uk"),
            array("Pre-owned Citroën ", "http://www.usedcars.citroen.co.uk/car-model-search.aspx"),
        ),
        "text_bottom" => "We apologise for any inconvenience caused and thank you for your interest in Citroën.",
    ),
	 'en-ZA' => array(
        "brand"       => "Citroën",
        "logo_url"    => "http://media.citroen.fr/design/frontend/images/logo.png",
        "page_title"  => " Error: Site under maintenance ",
        "image_url"   => "/design/frontend/images/maintenance/503ZA.jpg",
        "subtitle"    => "Maintenance",
        "parttitle"   => " The Citroën website is currently under maintenance.\n Please come back later.",
        "text_top"    => "Looking for a particular information ? Visit our other websites.",
        "links_title" => "Other Website",
        "links" => array(
           array("Facebook", " https://www.facebook.com/Citroen.South.Africa"),
        ),
        "text_bottom" => " We apologize for the inconvenience.",
    )
				
);
// END configuration



// HTTP headers
header('HTTP/1.1 503 Service Temporarily Unavailable');
header('Content-Type: text/html; charset=utf-8');

// Init
$host = isset($_SERVER['HTTP_CLIENT_HOST']) ? $_SERVER['HTTP_CLIENT_HOST'] : $_SERVER['HTTP_HOST'];
$uri  = $_SERVER['REQUEST_URI'];

$aCurrent_language = array();
// Default language
if (isset($config['languages'][$config['default_language']])) {
    $current_language = $config['languages'][$config['default_language']];
    $aCurrent_language[] = $config['languages'][$config['default_language']];
} else {
    $current_language = array_shift($config['languages']);
	$aCurrent_language[] = array_shift($config['languages']);
    trigger_error("Default language not defined", E_USER_WARNING);
}


$dataInUrl = false;
if(isset($_GET["country"]) && $_GET["country"] != "" && isset($_GET["culture"]) && $_GET["culture"] != ""){
	$culturePrefix = array(
		$_GET["country"] => $config['languages'][$_GET["culture"]]
	);	
	$dataInUrl = true;
}else{
	// Binding languages with hosts and url prefix (url prefix => language data)
	
	switch ($host) {
		//CT
		case 'ct.cppv2.dev.inetpsa.com':
		case 'ct.rec.cppv2.dev.inetpsa.com':
		case 'ct.cppv2.citroen.preprod.inetpsa.com':
		case 'ct.cppv2.citroen.rec.inetpsa.com':
		case 'ct.cppv2.citroen.recprj.inetpsa.com':
			$pays = 'fr';
			$culturePrefix = array(
				'fr' => $config['languages']['fr-FR']
			);
			break; 
		//AR
		case 'ar.cppv2.citroen.preprod.inetpsa.com':
		case 'ar.cppv2.citroen.rec.inetpsa.com':
		case 'www.citroen.ar':
			$pays = 'ar';
			$culturePrefix = array(
				'ar' => $config['languages']['es-AR']
			);
			break; 
			
		//AT
		case 'at.cppv2.citroen.preprod.inetpsa.com':	
		case 'at.cppv2.citroen.rec.inetpsa.com':	
		case 'www.citroen.at':
			$pays = 'at';
			$culturePrefix = array(
				'at' => $config['languages']['de-AT']
			);
			break; 
		//BE
		case 'be.cppv2.dev.inetpsa.com':
		case 'be.rec.cppv2.dev.inetpsa.com':
		case 'be.cppv2.citroen.preprod.inetpsa.com':
		case 'be.cppv2.citroen.rec.inetpsa.com':
		case 'be.cppv2.citroen.recprj.inetpsa.com':  
		case 'www.citroen.be':		
			$pays = 'be';
			$culturePrefix = array(
				'nl' => $config['languages']['nl-BE'],
				'fr' => $config['languages']['fr-BE']
			);
			break;	
		//CZ
		case 'cz.cppv2.citroen.preprod.inetpsa.com':
		case 'cz.cppv2.citroen.rec.inetpsa.com':
		case 'www.citroen.cz':
			$pays = 'cz';
			$culturePrefix = array(
				'cz' => $config['languages']['cs-CZ']
			);
			break; 
		//CH
		case 'ch.cppv2.citroen.preprod.inetpsa.com':
		case 'ch.cppv2.citroen.rec.inetpsa.com':
		case 'www.citroen.ch':
			$pays = 'ch';
			$culturePrefix = array(
				'ch' => $config['languages']['fr-CH'],
				'ch' => $config['languages']['it-CH'],
				'ch' => $config['languages']['it-CH']
			);
			break; 
			
		//DE
		case 'de.cppv2.citroen.preprod.inetpsa.com':
		case 'de.cppv2.citroen.rec.inetpsa.com':
		case 'www.citroen.de':
			$pays = 'de';
			$culturePrefix = array(
				'de' => $config['languages']['de-DE']
			);
			break; 
			
		//DK
		case 'dk.cppv2.citroen.preprod.inetpsa.com':
		case 'dk.cppv2.citroen.rec.inetpsa.com':
		case 'www.citroen.dk':
			$pays = 'dk';
			$culturePrefix = array(
				'dk' => $config['languages']['da-DK']
			);
			break; 
			
		//ES		
		case 'es.cppv2.citroen.preprod.inetpsa.com':
		case 'es.cppv2.citroen.rec.inetpsa.com':
		case 'www.citroen.es':
			$pays = 'es';
			$culturePrefix = array(
				'es' => $config['languages']['es-ES']
			);
			break; 
		
		//FR
		case 'fr.cppv2.dev.inetpsa.com':
		case 'fr.rec.cppv2.dev.inetpsa.com':
		case 'fr.cppv2.citroen.preprod.inetpsa.com':
		case 'fr.cppv2.citroen.rec.inetpsa.com':
		case 'fr.cppv2.citroen.recprj.inetpsa.com':
		case 'www.citroen.fr':
			$pays = 'fr';
			$culturePrefix = array(
				'fr' => $config['languages']['fr-FR']
			);
			break; 
			
		//HU
		case 'hu.cppv2.citroen.preprod.inetpsa.com':
		case 'hu.cppv2.citroen.rec.inetpsa.com':
		case 'www.citroen.hu':
			$pays = 'hu';
			$culturePrefix = array(
				'hu' => $config['languages']['hu-HU']
			);
			break; 
			
		//IE
		case 'ie.cppv2.citroen.preprod.inetpsa.com':
		case 'ie.cppv2.citroen.rec.inetpsa.com':
		case 'www.citroen.ie':
			$pays = 'ie';
			$culturePrefix = array(
				'ie' => $config['languages']['ie-IE']
			);
			break; 
			
		//IT
		case 'it.cppv2.citroen.preprod.inetpsa.com':
		case 'it.cppv2.citroen.rec.inetpsa.com':
		case 'www.citroen.it':
			$pays = 'it';
			$culturePrefix = array(
				'it' => $config['languages']['it-IT']
			);
			break; 
			
		//LU
		case 'lu.cppv2.citroen.preprod.inetpsa.com':
		case 'lu.cppv2.citroen.rec.inetpsa.com':
		case 'www.citroen.lu':
			$pays = 'lu';
			$culturePrefix = array(
				'lu' => $config['languages']['fr-LU']
			);
			break; 
		
		// NL
		case 'nl.cppv2.citroen.preprod.inetpsa.com':
		case 'nl.cppv2.citroen.rec.inetpsa.com':
		case 'www.citroen.nl':
			$pays = 'nl';
			$culturePrefix = array(
				'nl' => $config['languages']['nl-NL']
			);
			break; 
		
		//NO
		case 'no.cppv2.citroen.preprod.inetpsa.com':
		case 'no.cppv2.citroen.rec.inetpsa.com':
		case 'www.citroen.no':
			$pays = 'no';
			$culturePrefix = array(
				'no' => $config['languages']['nb-NO']
			);
			break; 
			
		//PL
		case 'pl.cppv2.citroen.preprod.inetpsa.com':
		case 'pl.cppv2.citroen.rec.inetpsa.com':
		case 'www.citroen.pl':
			$pays = 'pl';
			$culturePrefix = array(
				'pl' => $config['languages']['pl-PL']
			);
			break; 
		
		//PL
		case 'pl.cppv2.citroen.preprod.inetpsa.com':
		case 'pl.cppv2.citroen.rec.inetpsa.com':
		case 'www.citroen.pt':
			$pays = 'pt';
			$culturePrefix = array(
				'pt' => $config['languages']['pt-PT']
			);
			break; 
			
		// SE
		case 'se.cppv2.citroen.preprod.inetpsa.com':
		case 'se.cppv2.citroen.rec.inetpsa.com':
		case 'www.citroen.se':
			$pays = 'se';
			$culturePrefix = array(
				'se' => $config['languages']['sv-SE']
			);
			break; 
		//SI
		case 'si.cppv2.citroen.preprod.inetpsa.com':
		case 'si.cppv2.citroen.rec.inetpsa.com':
		case 'www.citroen.si':
			$pays = 'si';
			$culturePrefix = array(
				'si' => $config['languages']['sl-SI']
			);
			break;    
			
		//SK
		case 'sk.cppv2.citroen.preprod.inetpsa.com':
		case 'sk.cppv2.citroen.rec.inetpsa.com':
		case 'www.citroen.sk':
			$pays = 'sk';
			$culturePrefix = array(
				'nl' => $config['languages']['sk-sk']
			);
			break;
			
			
		//RU
		case 'ru.cppv2.citroen.preprod.inetpsa.com':
		case 'ru.cppv2.citroen.rec.inetpsa.com':
		case 'www.citroen.ru':
			$pays = 'ru';
			$culturePrefix = array(
				'ru' => $config['languages']['ru-RU']
			);
			break;
		//BR
		case 'br.cppv2.citroen.rec.inetpsa.com':
		case 'br.cppv2.citroen.preprod.inetpsa.com':
		case 'www.citroen.com.br':
			$pays = 'br';
			$culturePrefix = array(
				'br' => $config['languages']['br-BR']
			);
			break;
		//hr
		case 'hr.cppv2.citroen.rec.inetpsa.com':
		case 'hr.cppv2.citroen.preprod.inetpsa.com':
		case 'www.citroen.hr':
			$pays = 'hr';
			$culturePrefix = array(
				'hr' => $config['languages']['hr-HR']
			);
			break;
			
		//ru-UA
		case 'ua.cppv2.citroen.rec.inetpsa.com':
		case 'ua.cppv2.citroen.preprod.inetpsa.com':
		case 'www.citroen.ua':
			$pays = 'ua';
			$culturePrefix = array(
				'ua' => $config['languages']['ru-UA']
			);
			break;
			
		//gb-GB
		case 'gb.cppv2.citroen.recprj.inetpsa.com':
		case 'gb.cppv2.citroen.preprod.inetpsa.com':
		case 'gb.cppv2.citroen.rec.inetpsa.com':
		case 'www.citroen.co.uk':
			$pays = 'gb';
			$culturePrefix = array(
				'gb' => $config['languages']['gb-GB']
			);
			break;	

		//en-ZA
		case 'za.cppv2.citroen.rec.inetpsa.com':
		case 'za.cppv2.citroen.preprod.inetpsa.com':
		case 'za.cppv2.citroen.inetpsa.com':
		case 'www.citroen.co.za':
			$pays = 'za';
			$culturePrefix = array(
				'za' => $config['languages']['en-ZA']
			);
			break;
	}
}
$langSession = '';
// Select culture from URL prefix
if (isset($culturePrefix)) {
    foreach ($culturePrefix as $prefix => $val) {
        // Use this language if its prefix matches the current URI        
        //vérification si langue en session
		if($_SESSION[$host]['LANGUE_CODE'] != NULL){
			if(strtolower($_SESSION[$host]['LANGUE_CODE']) == strtolower($prefix)){
				$langSession = strtolower($_SESSION[$host]['LANGUE_CODE']);
			}			
		}
		$aCurrent_language_country[] = $val;
    }
}
if($dataInUrl == true && isset($_GET["country"]) && $_GET["country"] != "" && isset($_GET["culture"]) && $_GET["culture"] != ""){
	$aCurrent_language = array();
	$aCurrent_language[] = $config['languages'][$_GET["culture"]];
}elseif($langSession != '' && $pays != ''){
	$aCurrent_language = array();
	//si on a la langue pour le pays
	$aCurrent_language[] = $config['languages'][$langSession."-".strtoupper($pays)];		
}elseif($pays != ''){
	//toutes les langues du pays
	$aCurrent_language = $aCurrent_language_country;
}
?>
<!doctype html>
<!--[if lt IE 9]><html class="ie ie8"><![endif]-->
<!--[if IE 9]><html class="ie ie9"><![endif]-->
<!--[if gt IE 9]><!--><html><!--<![endif]-->
<head>
    <link rel="icon" type="image/x-icon" href="favicon.ico" />
    <link rel="alternate" hreflang="fr" href="help.htm" /> 
    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />

    <meta name="robots" content="noindex,nofollow" />

    <title><?php echo htmlspecialchars($aCurrent_language[0]['page_title']); ?></title>

    <!--[if lt IE 9]>
    <script>
        var html5 = ["header","footer","aside","article","section","nav","summary","time"];
        for(var i=0; i<html5.length; i++){ document.createElement(html5[i]); }
    </script>
    <![endif]-->

    <link rel="stylesheet" href="/css/maintenance/main.css" media="screen" />
</head>
<body>
    <div class="container">
        <div class="body">
            <section class="headlite">
                <img src="<?php echo htmlspecialchars($config['logo']["url"]); ?>" width="78" height="55" alt="<?php echo htmlspecialchars($config['logo']["alt"]); ?>" />
            </section>
            <!-- /. row -->
            <?php
            foreach ($aCurrent_language as $language) {
                ?>
                <div class="sliceNew sliceOfflineDesk">
                    <section class="offline">
                        <h1 class="subtitle"><p><?php echo htmlspecialchars($language["subtitle"], null, "UTF-8"); ?></p></h1>
                        <h2 class="parttitle"><?php echo htmlspecialchars($language["parttitle"]); ?></h2>
                        <div class="row of2">
                            <div class="col">

                                <p><?php echo htmlspecialchars($language["text_top"]); ?></p>

                                <?php if (!empty($language['links'])) : ?>
                                    <p><?php echo htmlspecialchars($language["links_title"]); ?></p>
                                    <ul class="actions">
                                        <?php
                                        foreach ($language['links'] as $val) {
                                            echo '<li><a href="' . htmlspecialchars($val[1]) . '" class="buttonLink">' . htmlspecialchars($val[0]) . '</a></li>';
                                        }
                                        ?>
                                    </ul>
                                <?php endif; ?>

                                <p><?php echo htmlspecialchars($language["text_bottom"]); ?></p>
                            </div>

                            <!-- /.col -->

                            <figure class="col">
                                <img src="<?php echo Pelican::$config["MEDIA_HTTP"] . htmlspecialchars($language["image_url"]); ?>" width="580" height="323" alt="<?php echo htmlspecialchars($language["subtitle"]); ?>" />
                            </figure>
                            <!-- /.col -->
                        </div>
                    </section>
                </div>
                <?php
            }
            ?>
            <!-- /. row -->
        </div>
        <!-- /.body -->
    </div>
    <!-- /.container -->
</body>
</html>
