<?php
/**
* Smarty plugin
* @package Smarty
* @subpackage plugins
*
* Fonction de génération de l'attribut data-gtm
* Paramètres :
*  - data : données du bloc, obtenu via $this->getParams()
*  - datasup : données supplémentaires, à ajouter dans la valeur de l'attribut data-gtm.
*    Sert par exemple à définir le paramètre value.
*  - labelvars : variables dynamiques injectées dans le champ label.
*    Ces variables sont envoyées séparément car elles ne doivent pas être injectées directement
*    dans la valeur de l'attribut data-gtm ($aItems), elles servent juste à générer le champ label.
*/

function smarty_function_gtm($params, &$view)
{
    

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
    


        $gtmVars=array(
                'GTMEventName'      => 'eventGTM',
                'eventCategory'   =>  $eventCategory,
                'eventAction'     =>   $action,
                'eventLabel'      =>  '',
                'perso'           => $perso,
                'value'           => ''
            );
      // Ajout des valeurs spécifiques envoyées depuis les tpl (facultatif)
        if (!empty($params['datasup'])) {
            $gtmVars = array_merge($gtmVars, $params['datasup']);
        }
         if($params['action'] == 'Display::ToolTip'){
            $attr['value'] = Pelican::$config['ZONE_CODE'] [$params['data']["ZONE_ID"]] .':'.$attr['value'];
        }
        
        $return = ' data-gtm="'.implode('|', $gtmVars).'" ';
    
  
        
    }else{



        // Collecte paramètres
        if (empty($params['name'])) {
            trigger_error("assign: missing 'name' parameter");
            return;
        }

        // Récupération des champs category, action et label dans la table psa_gtm
        $aGTMGlobal = Pelican_Cache::fetch("Citroen/GTM");
        if (isset($aGTMGlobal[$params['data']["ZONE_ID"]]) && isset($aGTMGlobal[$params['data']["ZONE_ID"]][$params['name']])) {
            $aGTM = $aGTMGlobal[$params['data']["ZONE_ID"]][$params['name']];
        } elseif (isset($aGTMGlobal[0]) && isset($aGTMGlobal[0][$params['name']])) {
            $aGTM = $aGTMGlobal[0][$params['name']];
        }
        
        // Récupération mapping type zone / nom type zone
        $result = Pelican_Cache::fetch("Backend/ZoneType");
        $zoneTypes = array();
        foreach ($result as $key => $val) {
            $zoneTypes[$val['ZONE_TYPE_ID']] = $val['ZONE_TYPE_LABEL'];
        }
        unset($result);

        // Mode debug ({gtm debug=1...)
        if( isset($params['debug']) ){
            echo "\n\n\n\n<!--\nsmarty_function_gtm debug ".$params['debug'];
            echo "\n\n".'$params : '.print_r($params, true);
            echo "\n\n".'$aGTM : '.print_r($aGTM, true);
            echo "\n\n".'profil : '.print_r($_SESSION[APP]['PROFILES_USER'], true);
            echo "\n-->\n\n\n\n";
        }

        // Génération du label (remplacement des variables dynamiques par leur valeur)
        // label : variables communes
        $labelTypeTranche = isset($zoneTypes[$params['data']['ZONE_TYPE_ID']]) ? $zoneTypes[$params['data']['ZONE_TYPE_ID']] : '';
        $labelVars = array(
            '%titre de tranche%'   => $params['data']['ZONE_TEMPLATE_LABEL'],
            '%id tranche%'         => $params['data']['ZONE_TYPE_ID'],
            '%id interne%'         => $params['data']['ZONE_ID'],
            '%type de tranche%'    => $labelTypeTranche,
            '%type tranche%'       => $labelTypeTranche,
            '%type de la tranche%' => $labelTypeTranche,
            '%profil%'             => \Citroen\GTM::$dataLayer['profiles'],
        );
        // label : variables spécifiques
        if (!empty($params['labelvars'])) {
            $labelVars = array_merge($labelVars, $params['labelvars']);
        }
        $label = strtr($aGTM['label'], $labelVars);

        // evol 2944 test si perso ou non perso ou rien
        $CD1 = "";
        
        if($params['data']['ZONE_PERSO'])
        {   //si la perso est activé

            $aZonePerso = json_decode($params['data']['ZONE_PERSO']);
            
            if(isset($params['idMulti']) && !empty($params['idMulti'])){
                if($params['idMulti'] == '-2'){
                    $CD1 = "Perso";
                }else{
                    $CD1 = "No Perso";            
                }
            }else{
                if(isset($aZonePerso->PROFIL_1->PROFILE_ID)){
                    if(in_array($aZonePerso->PROFIL_1->PROFILE_ID, $_SESSION[APP]['PROFILES_USER'])){
                      $CD1 = "Perso";
                    }
                    else{
                      $CD1 = "No Perso";
                    }
                }else{
                    $CD1 = "No Perso";
                }
            }
        }
    // Synchronisation de la perso dans les push expand
    if ($params['data']['PAGE_PERSO']) {
        $CD1 = $params['idMulti'] == '-2' ? "Perso" : "No perso";
    }
    //fin evol 2944

        // Init valeurs communes
        $aItems = array(
            'GTMEventName'            => 'eventGTM',
            'category'                => $aGTM['category'],
            'action'                  => $aGTM['action'],
            'label'                   => $label,
            'value'                   => '10',
            'interaction'             => '0',
            'GTMBackupEvent'          => '',
            'customDimensionsMetrics' => '',
            'customDimension1' => $CD1
        );

        // Ajout des valeurs spécifiques envoyées depuis les tpl (facultatif)
        if (!empty($params['datasup'])) {
            $aItems = array_merge($aItems, $params['datasup']);
        }
        
        $return = ' data-gtm="'.implode('|', $aItems).'" ';
    }        
    // Mise à jour dataLayer : customDimension1 vaut "Perso" si au moins une tranche utilise les données perso
    if (\Citroen\GTM::$dataLayer['customDimension1'] != "Perso" && $CD1 == "Perso") {
        \Citroen\GTM::$dataLayer['customDimension1'] = $CD1;
    }
    
    // Debug info
    if ((Pelican::$config["SHOW_DEBUG"] && isset($_GET['debug'])) || isset($_COOKIE['debuggtm'])) {
        $debuginfo = array(
            'zone_id' => isset($params['data']['ZONE_ID']) ? $params['data']['ZONE_ID'] : null,
            'name' => $params['name'],
            'label_template' => $aGTM['label'],
        );
        $return .= ' data-gtm-debug="'.htmlspecialchars(json_encode($debuginfo)).'"';
    }
    
    return $return;
}