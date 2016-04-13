<?php

/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 *
 * Fonction de génération de l'attribut data-gtm-js, utilisé pour marquer des éléments spécifiques comme les toggle et les slides
 * Paramètres :
 *  - type (string)
 *  - labels (array) : liste des valeurs à passer dans la clé labels
 *  - data (array) : données du bloc, obtenu via $this->getParams()
 *  - autolabels (string) : liste de variables à pré-remplir automatiquement dans labels (cela permet de récupérer certaines informations communes comme le profil ou le nom de la tranche directement dans la fonction plutôt que dans les tpl smarty). Ex: profiles, id interne
 */
function smarty_function_gtmjs($params, &$view) {


    $eventGTM ='';

    // Mode debug ({gtmjs debug=1...)
    if (isset($params['debug'])) {
        echo "\n\n\n\n<!--\n" . __FUNCTION__ . " debug " . $params['debug'];
        echo "\n\n" . '$params : ' . print_r($params, true);
        echo "\n\n" . '$aGTM : ' . print_r($aGTM, true);
        echo "\n\n" . 'profil : ' . print_r($_SESSION[APP]['PROFILES_USER'], true);
        echo "\n-->\n\n\n\n";
    }

   

    if (isset($params['action']) ) {
        $action= $params['action'];
        if(empty($action)){
            $action = Pelican::$config['DEFAULT_TOOLBAR_GTM_ACTION'][$params['data']['TEMPLATE_PAGE_ID']];
            if(empty($action)){
                $action = Pelican::$config['DEFAULT_TOOLBAR_GTM_ACTION']['default'];
            }else{
                if(isset($action[$params['data']["ZONE_ID"]])){
                    $action = $action[$params['data']["ZONE_ID"]];
                }else{
                    $action = $action['default'];
                }
            }
        }
  
       
       //Récupere l'eventCategory selon le TEMPLATE_PAGE_ID et le ZONE_ID 
       $category = Pelican::$config['GTM_CATEGORY'] [$params['data']['TEMPLATE_PAGE_ID']];
       if(empty($category)){
            $category = Pelican::$config['GTM_CATEGORY']['default'];
       }
       $eventCategory = $category[$params['data']["ZONE_ID"]];
       if(empty($eventCategory)){
            $eventCategory = $category['default'];
       }
       if(!isset($params['_perso']) && class_exists('Frontoffice_Zone_Helper')){
        $usingPersoData = Frontoffice_Zone_Helper::usingPersoData($params['data'],$params['idMulti']);
      }

        if($usingPersoData ||  $params['_perso']){
           //si la perso est activé
                
                    $perso = "[Perso]";
                    $CD1 = "Perso";    
          
        }else{
            $perso='';
            $CD1 = "No Perso";  
        }
    


        $attr = array(
                'GTMEventName'      => 'eventGTM',
                'eventCategory'   =>  $eventCategory,
                'eventAction'     =>   $action,
                'eventLabel'      =>  '',
                'perso'           => $perso,
                'value'           => ''
            );

      // Ajout des valeurs spécifiques envoyées depuis les tpl (facultatif)
        if (!empty($params['datasup'])) {
            $attr = array_merge($attr, $params['datasup']);
        }
        if($params['action'] == 'Display::ToolTip'){
            $attr['value'] = Pelican::$config['ZONE_CODE'] [$params['data']["ZONE_ID"]] .':'.$attr['value'];
        }

        $attrArray=array(
                'type' => $params['type'],             
                implode('|',$attr)
                );
         if( isset($params['eventGTM']) && !empty($params['eventGTM']) ){
               $attrArray ['eventGTM']=$params['eventGTM'];
            }
        $attr = htmlspecialchars(raw_json_encode($attrArray));

}else{

    // Collecte paramètres
    if (empty($params['type'])) {
        trigger_error("assign: missing 'type' parameter");
        return;
    }
    if (!isset($params['labels']) || !is_array($params['labels'])) {
        $params['labels'] = array();
    }

    // evol 2944 test si perso ou non perso ou rien
    $CD1 = "";
    if ($params['data']['ZONE_PERSO']) {   //si la perso est activé
        if (isset($params['idMulti']) && !empty($params['idMulti'])) {
            if ($params['idMulti'] == '-2') {
                $CD1 = "Perso";
            } else {
                $CD1 = "No Perso";
            }
        } else {
            if (in_array($aZonePerso[0]->PROFILE_ID, $_SESSION[APP]['PROFILES_USER'])) {
                $CD1 = "Perso";
            } else {
                $CD1 = "No Perso";
            }
        }
    }
    //fin evol 2944
    // Construction labels (autolabels + labels)
    $labels = array();
    if (!empty($params['autolabels']) && !empty($params['data'])) {
        $autolabels = preg_split('#\s*,\s*#', $params['autolabels']);
        // echo "\n\n".'vince autolabels : '.print_r($autolabels, true)."\n\n";
        foreach ($autolabels as $val) {
            switch ($val) {
                case 'profiles': $labels[$val] = \Citroen\GTM::$dataLayer['profiles'];
                    break;
                case 'idInterne': $labels[$val] = $params['data']['ZONE_ID'];
                    break;
                case 'typeTranche': $labels[$val] = $params['data']['ZONE_TYPE_ID'];
                    break;
                case 'titreTranche': $labels[$val] = isset($params['data']['ZONE_TEMPLATE_LABEL']) ? $params['data']['ZONE_TEMPLATE_LABEL'] : $params['data']['ZONE_LABEL'];
                    break;
            }
        }
    }
    $labels = array_merge($labels, $params['labels']);

    // Construction de l'attribut data-gtm-js
    $attrArray = array(
        'type' => $params['type'],
        'labels' => $labels,
        'customDimension1' => $CD1
    );

    // Sérialisation de l'attribut data-gtm-js
    $attr = htmlspecialchars(raw_json_encode($attrArray));

    // Mise à jour dataLayer : customDimension1 vaut "Perso" si au moins une tranche utilise les données perso
    if (\Citroen\GTM::$dataLayer['customDimension1'] != "Perso" && $CD1 == "Perso") {
        \Citroen\GTM::$dataLayer['customDimension1'] = $CD1;
    }
}
    return ' data-gtm-js="' . $attr . '" '.$eventGTM;
}

function raw_json_encode($input, $flags = 0) {
    $fails = implode('|', array_filter(array(
        '\\\\',
        $flags & JSON_HEX_TAG ? 'u003[CE]' : '',
        $flags & JSON_HEX_AMP ? 'u0026' : '',
        $flags & JSON_HEX_APOS ? 'u0027' : '',
        $flags & JSON_HEX_QUOT ? 'u0022' : '',
    )));
    $pattern = "/\\\\(?:(?:$fails)(*SKIP)(*FAIL)|u([0-9a-fA-F]{4}))/";
    $callback = function ($m) {
        return html_entity_decode("&#x$m[1];", ENT_QUOTES, 'UTF-8');
    };
    return preg_replace_callback($pattern, $callback, json_encode($input, $flags));
}