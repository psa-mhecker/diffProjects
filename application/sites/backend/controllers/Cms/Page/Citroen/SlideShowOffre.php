<?php
include_once(Pelican::$config['CONTROLLERS_ROOT']."/Cms/Page/Module.php");
include_once(Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php');

use Citroen\Perso\Synchronizer;

class Cms_Page_Citroen_SlideShowOffre extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        $return .= Backoffice_Form_Helper::getFormAffichage($controller);
        // Détection du contexte (onglet existant ou nouvel onglet perso)
        $context = $controller->getContext();
        
        // Récupération du préfixe multi (qui change en fonction du contexte)
        $multiPrefix = $context == 'newprofile' ? $controller->getParam('multiId') : $controller->getParam('multi');
        
        // Récupération des données génériques
        $result = $controller->getMultisGenericData();
        $resultPrefix = $context == 'newprofile' ? '' : $multiPrefix;
        $result = isset($result[$resultPrefix.'SLIDEOFFREADDFORM']) ? $result[$resultPrefix.'SLIDEOFFREADDFORM'] : null;
        $isPerso = empty($result) ? false : true;
        
        // Nettoyage données génériques
        // (effacement des éléments supprimés du multi générique, mais dont on récupère des données résiduelles dans la popin)
        $genericData = null;
        if (is_array($result)) {
            $genericData = array();
            foreach ($result as $key => $val) {
                unset($val[''], $val['PAGE_ZONE_MULTI_ID'], $val['MEDIA_ID2']);
                if (empty($val)) {
                    continue;
                }
                $genericData[] = $result[$key];
            }
        }
        
        #debug #test
        $same = json_encode($result) == json_encode($genericData);
        if (!$same) {
            ob_start();
            echo '<div style="background:#faa; padding:10px;"><pre>$genericData != $result</pre>';
            Synchronizer::debugUI($result);
            Synchronizer::debugUI($genericData);
            echo '</div>';
            $return .= ob_get_clean();
        }
        
        // Lecture des métadonnées multi
        $multiMetadataForm = $controller->getParam('multiMetadata');
        parse_str($multiMetadataForm, $multiMetadata);
        $addedMultiIndex = isset($multiMetadata['added_multi_index'][$multiPrefix.'SLIDEOFFREADDFORM']) ? $multiMetadata['added_multi_index'][$multiPrefix.'SLIDEOFFREADDFORM'] : null;
        
        $return .= $controller->oForm->createMultiHmvc(
            $controller->multi."SLIDEOFFREADDFORM", // $strName
            t('SLIDE_OFFRE_ADD_FORM'),              // $strLib
            array("path" => __FILE__, "class" => __CLASS__, "method" => "slideOffreAddForm"),
            Backoffice_Form_Helper::getPageZoneMultiValues($controller, 'SLIDEOFFREADDFORM'), // $tabValues
            $controller->multi."SLIDEOFFREADDFORM", // $incrementField
            $controller->readO,                     // $bReadOnly = false
            "12",                                   // $intMinMaxIterations = ""
            true,                                   // $bAllowDeletion = true
            true,                                   // $bAllowAdd = true
            $controller->multi."SLIDEOFFREADDFORM", // $strPrefixe = "multi"
            "values",                               // $line = "values"
            "multi",                                // $strCss = "multi"
            "2",                                    // $sColspan = "2"
            "",                                     // $sButtonAddMulti = ""
            "",                                     // $complement = ""
            $isPerso,                               // $perso = false
            array(
                'generic_data' => $genericData,
                'added_multi_index' => $addedMultiIndex,
            )
        );

        return $return;
    }

    /**
     * Formulaire d'un élément multi
     *
     * @param bool $perso Contient une collection des multi génériques de la tranche
     * @param array $extendedArgs Contient les données géénriques ainsi que les collection MultiMetadataManager
     */
    public static function slideOffreAddForm($oForm, $values, $readO, $multi, $perso, $extendedArgs)
    {
        $offre = '';
        
        // Debug
        $debugMode = isset($_COOKIE['debug']) && preg_match('#perso_sync#', $_COOKIE['debug']) ? true : false;
        if ($debugMode) {
            ob_start();
            echo '<div>$multi : '; var_dump($multi); echo '</div>';
            echo '<div>$perso : '; var_dump($perso); echo '</div>';
            Synchronizer::debugUI($extendedArgs, array('title' => '$extendedArgs'));
            $offre .= '<tr><td colspan="50"><div style="padding:10px; background:rgba(0,0,0,.15); margin:10px;">'.ob_get_clean().'</div></td></tr>';
        }

        // Synchronisation (perso)
        $genericData = isset($extendedArgs['generic_data']) ? $extendedArgs['generic_data'] : null;
        if (isset($genericData) && is_array($genericData)) {
            // Liste des hash des multi ajoutés
            $addedMulti = isset($extendedArgs['added_multi_index']) ? $extendedArgs['added_multi_index'] : array();
            
            // Liste synchronisation
            $synchroValues = array('-2' => t('ACTIVER_LA_PERSO_POUR_CE_SLIDE'));
            $numerotation = 1;
            foreach ($genericData as $key => $val) {
                if (empty($val['MULTI_HASH'])) {
                    continue;
                }
                
                // Exclusion des nouveaux éléments multi génériques (pour ne pas créer de double ajout avec la synchro add/del)
                if (in_array($val['MULTI_HASH'], $addedMulti)) {
                    continue;
                }
                
                $titre = !empty($val['PAGE_ZONE_MULTI_TITRE']) ? $val['PAGE_ZONE_MULTI_TITRE'] : t('PAS_DE_TITRE_POUR_CE_SLIDE');
                $label = t('SYNCHRONISATION_AVEC_LE_SLIDE')
                    .' '.substr($val['MULTI_HASH'], 0, 7)
                    .', '.t('OFFRE').' n°'.$numerotation
                    .' "'.$titre.'"';
                $numerotation++;
                $synchroValues[$val['MULTI_HASH']] = htmlspecialchars($label);
            }
            
            // Définition de la valeur du champ synchronisation : valeur enregistrée | multi générique | activer la perso
            $synchroValue = -2;
            if (isset($values['_sync'])) {
                $synchroValue = $values['_sync'];
            } elseif (!empty($values['MULTI_HASH'])) {
                $synchroValue = $values['MULTI_HASH'];
            }
            
            $offre .= $oForm->createComboFromList($multi."_sync", t('SLIDE_PERSO_ACTIVE'), $synchroValues, $synchroValue, true, $readO);
        } else {
            // Affichage de l'identifiant de l'élément mutli
            if ($debugMode) {
                $offre .= $oForm->createLabel(t('MULTI_SLIDE_ID').' (old)',$values["PAGE_ZONE_MULTI_ID"]);
            }
            $offre .= sprintf(
                '<tr><td class="formlib">%s</td><td class="formval"><a class="multi-hash-display" title="%s">%s</a></td></tr>',
                htmlspecialchars(t('MULTI_SLIDE_ID')),
                $values["MULTI_HASH"],
                substr($values["MULTI_HASH"], 0, 7)
            );

            if (!empty($values["PAGE_ZONE_MULTI_ID"])) {
                $offre .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_ID", $values["PAGE_ZONE_MULTI_ID"]);
            }
        }

        $offre .= $oForm->createInput($multi."PAGE_ZONE_MULTI_TITRE", t('TITRE'), 255, "", false, $values["PAGE_ZONE_MULTI_TITRE"], $readO, 50);
        $offre .= $oForm->createMedia($multi."MEDIA_ID", t('IMAGE_WEB'), false,  "image", "", $values['MEDIA_ID'] , $readO, true, false, 'offre', null, $perso, $values['MEDIA_ID_GENERIQUE']);
        $offre .= $oForm->createMedia($multi."MEDIA_ID2", t('IMAGE_MOBILE'), false,  "image", "", $values['MEDIA_ID2'] , $readO, true, false, 'offre', null, $perso, $values['MEDIA_ID2_GENERIQUE']);
        $offre .= $oForm->createInput($multi."PAGE_ZONE_MULTI_URL", t('URL_WEB'), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL"], $readO, 50);
        $offre .= $oForm->createInput($multi."PAGE_ZONE_MULTI_URL2", t('URL_MOBILE'), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL2"], $readO, 50);
        $offre .= $oForm->createRadioFromList($multi.'PAGE_ZONE_MULTI_ATTRIBUT', t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $values['PAGE_ZONE_MULTI_ATTRIBUT'], false, $readO);
        $offre .= $oForm->createHidden($multi."MULTI_HASH", $values["MULTI_HASH"]);
        return $offre;
    }

    public static function save(Pelican_Controller $controller)
    {
        // Synchronisation (perso)
        try {
            $verbose = isset($_COOKIE['debug']) && preg_match('#perso_sync#', $_COOKIE['debug']) ? true : false;
            $persoData = Synchronizer::unserialize(Pelican_Db::$values['ZONE_PERSO']);
            $sync = new Synchronizer($persoData, Pelican_Db::$values, $_POST, $verbose);
            $sync->sync('SLIDEOFFREADDFORM', 12);
            Pelican_Db::$values['ZONE_PERSO'] = Synchronizer::serialize($sync->persoData);
        } catch (Exception $ex) {
            switch ($ex->getCode()) {
                case Synchronizer::EX_UNREADABLE_PERSODATA:
                    break;
                default:
                    trigger_error($ex->getMessage(), E_USER_WARNING);
                    break;
            }
        }
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
        Backoffice_Form_Helper::savePageZoneMultiValues('SLIDEOFFREADDFORM','SLIDEOFFREADDFORM');
        Pelican_Cache::clean("Frontend/Citroen/ZoneMulti");
    }
}
