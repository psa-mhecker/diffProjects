<?php
include_once (Pelican::$config['APPLICATION_CONTROLLERS'] . '/Cms/Page/Module.php');

/**
 *
 *
 * Module Backend de param�trage du plugin iGoogle
 * 
 * @author rcarles
 *        
 */
class Igoogle_Cms_Page_Bloc extends Cms_Page_Module
{

    /**
     *
     *
     * Tableau des services iGoogle disponibles
     * 
     * @var array
     */
    public static $aGadget = array(
        'http://www.google.com/ig/modules/docs.xml' => 'Google docs',
        'http://www.programme.tv/webmaster/google/tv.xml' => 'Programme TV',
        'http://www.google.com/ig/modules/calendar3.xml' => 'Google Calendar',
        'http://www.netvibes.com/api/uwa/compile/google.php?moduleUrl=http%3A%2F%2Fwidget.pagesjaunes.fr%2Fuwa%2Findex.html' => "Pages jaunes ",
        'http://andyast.googlepages.com/MSOutlookWidget.xml' => 'Outlook',
        'http://www.linternaute.com/dictionnaire/fr/widget/dictionnaire.xml' => 'Dictionnaire',
        'http://www.google.com/uds/modules/elements/localsearch/localsearch.xml' => "Google maps",
        'http://www.google.com/ig/modules/driving_directions.xml' => "Itinéraires",
        'http://www.labpixies.com/campaigns/todo/todo.xml' => "Todo List",
        'http://nvmodules.netvibes.com/widget/gspec?uwaUrl=http%3A%2F%2Fimetro.nanika.net%2Fnvimetro.html' => "Métro",
        'http://www.gstatic.com/ig/modules/youtube/v3/youtube.xml' => 'YouTube',
        'http://www.google.com/ig/modules/facebook.xml' => 'Facebook',
        'http://google.spontex.org/gadget_le_saviez_vous.xml' => 'Le saviez-vous ',
        'http://www.widget-radio.com/radio/google/radio.xml' => 'Radio Web FM',
        'http://www.google.com/ig/modules/todo.xml' => 'Liste à faire',
        'http://www.efattal.fr/google/saints/module.html' => 'Saints et fête du jour',
        'http://www.labpixies.com/campaigns/weather/weather.xml' => 'Météo LabPixies',
        'http://www.google.com/ig/modules/calculator.xml' => 'Calculatrice Google',
        'http://www.ebabylone.com/webservice/googlewidget/tv/ebabTV1.xml' => 'Live TV',
        'http://igwidgets.com/lig/gw/f/islk/89/slkm/ik/s/9878767676530/87/charles447/mapquest.xml' => 'Mapquest',
        'http://www.widget-horoscope.fr/horoscope/google/horoscope.xml' => 'Horoscope',
        'http://www.lemonde.fr/widget/igoogle/infos-generales.xml' => 'Actualités : Le Monde',
        'http://www.cammap.net/tvlive/microtv.xml' => 'Micro TV',
        'http://www.efattal.fr/google/circulation/module.html' => 'Circulation',
        'http://fly3.net/gadgets/hotel-c-fr.xml' => 'Hotels',
        'http://hosting.gmodules.com/ig/gadgets/file/100257629120419735870/esa.xml' => 'Eumetsat',
        "http://hosting.gmodules.com/ig/gadgets/file/102373417984284974242/TF1.xml" => 'TF1'
    );

    /**
     *
     *
     * Enregistrement des param�tres du bloc
     * 
     * @param Pelican_Controller $controller            
     */
    public static function save (Pelican_Controller $controller)
    {
        $oConnection = Pelican_Db::getInstance();
        
        foreach (Pelican_Db::$values as $key => $value) {
            if (substr($key, 0, 10) == 'parameter_') {
                $parameter[substr($key, 10, strlen($key))] = $value;
            }
        }
        Pelican_Db::$values["ZONE_TEXTE"] = base64_encode(serialize($parameter));
        
        parent::save($controller);
    }

    /**
     *
     *
     * Affichage des contr�les de saisie du bloc
     * 
     * @param Pelican_Controller $controller            
     */
    public static function render (Pelican_Controller $controller)
    {
        $prefix = $controller->multi . 'parameter_';
        $userPrefPrefixe = 'up_';
        
        $parameters = unserialize(base64_decode($controller->zoneValues["ZONE_TEXTE"]));
        
        if (! $parameters && $controller->zoneValues['parameter_url']) {
            foreach ($controller->zoneValues as $key => $value) {
                if (substr($key, 0, 10) == 'parameter_') {
                    $parameters[substr($key, 10, strlen($key))] = $value;
                }
            }
        }
        
        $parameters["w"] = ($parameters['w'] ? $parameters['w'] : "320");
        // $parameters["h"] = ($parameters['h']?$parameters['h']:"300");
        
        asort(self::$aGadget);
        $return = $controller->oForm->createComboFromList($prefix . "url", "Gadget Igoogle", self::$aGadget, $parameters["url"], false, $controller->readO, "1", false, "", true, false, "onchange=\"callAjax('/_/module/igoogle/Igoogle/get',this.value, '" . $prefix . $userPrefPrefixe . "', '" . $controller->zoneValues["ZONE_TEMPLATE_ID"] . "')\"");
        
        /*
         * <select id=locale name=locale style="width:95%;"><option value="all ALL">langue par défaut</option><option value="ar ALL">???????</option><option value="bg ALL">?????????</option><option value="bn ALL">?????</option><option value="ca ALL">català</option><option value="cs ALL">¿esky</option><option value="da ALL">Dansk</option><option value="de ALL">Deutsch</option><option value="el ALL">????????</option><option value="en-GB ALL"></option><option value="en ALL">English</option><option value="es ALL">español</option><option value="fi ALL">suomi</option><option value="fil ALL"></option><option value="fr ALL" selected>français</option><option value="gu ALL">???????</option><option value="he ALL"></option><option value="hi ALL">??????</option><option value="hr ALL">hrvatski</option><option value="hu ALL">magyar</option><option value="id ALL">Indonesia</option><option value="in ALL"></option><option value="it ALL">Italiano</option><option value="iw ALL">?????</option><option value="ja ALL">???</option><option value="kn ALL">?????</option><option value="ko ALL">???</option><option value="lt ALL">lietuviu</option><option value="lv ALL">latviešu</option><option value="ml ALL">??????</option><option value="mr ALL">?????</option><option value="nl ALL">Nederlands</option><option value="no ALL">norsk (bokmål)</option><option value="or ALL">????</option><option value="pl ALL">polski</option><option value="pt-BR ALL">português (Brasil)</option><option value="pt-PT ALL">Português (Portugal)</option><option value="ro ALL">Româna</option><option value="ru ALL">???????</option><option value="sk ALL">sloven¿ina</option><option value="sl ALL">slovenš¿ina</option><option value="sr ALL">??????</option><option value="sv ALL">svenska</option><option value="ta ALL">?????</option><option value="te ALL">??????</option><option value="th ALL">???????</option><option value="tr ALL">Türkçe</option><option value="uk ALL">??????????</option><option value="vi ALL">Ti?ng Vi?t</option><option value="zh-CN ALL">??(??)</option><option value="zh-TW ALL">??(??)</option></select> $aLang; $return .= $controller->oForm->createComboFromList($controller->multi."ZONE_TEXT2", "Langue", $aLang, $controller->zoneValues["ZONE_TEXT2"], true, $controller->readO, "1", false, "", true, false);
         */
        
        $return .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE", "Titre", 150, "", false, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 50);
        
        $return .= $controller->oForm->createInput($prefix . "w", "Largeur", 10, "real", true, $parameters["w"], $controller->readO, 10);
        $return .= $controller->oForm->createInput($prefix . "h", "Hauteur", 10, "real", false, $parameters["h"], $controller->readO, 10);
        
        $return .= $controller->oForm->createLabel('Paramètres spécifiques', Pelican_Html::div(array(
            id => $controller->zoneValues["ZONE_TEMPLATE_ID"] . 'Igoogle'
        )));
        
        if ($parameters['url']) {
            $_SESSION[APP]['plugin']['Igoogle'][$controller->zoneValues["ZONE_TEMPLATE_ID"]] = $parameters;
            $return .= $controller->oForm->createFreeHtml(Pelican_Html::script(array(), "callAjax('/_/module/igoogle/Igoogle/get', '" . $parameters['url'] . "', '" . $prefix . $userPrefPrefixe . "', '" . $controller->zoneValues["ZONE_TEMPLATE_ID"] . "')"));
        }
        
        return $return;
    }
}
