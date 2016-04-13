<?php

/**
 * @author Ayoub Hidri <ayoub.hidri@businessdecision.com>
 */
class Cms_Page_Citroen_CitroenSocial extends Cms_Page_Citroen {

    public static $aSocialNetworksFieldsMap = array(
            'FACEBOOK' => 'ZONE_TEXTE',
            'TWITTER' => 'ZONE_TEXTE2',
            'YOUTUBE' => 'ZONE_TEXTE3',
            'PINTEREST' => 'ZONE_TEXTE4',
            'INSTAGRAM' => 'ZONE_TEXTE5'
        );
    public static $sCountrySocialNetworksField='ZONE_TEXTE6';
    public static $sCorportateSocialNetworksField='ZONE_TEXTE7';
    public static function render(Pelican_Controller $oController) {

        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = (int) $_SESSION[APP]['SITE_ID'];
        $aBind[':LANGUE_ID'] = (int) $_SESSION[APP]['LANGUE_ID'];
        $sSqlSocialNetworks = "SELECT
						*
					FROM
						#pref#_reseau_social
					WHERE
						SITE_ID = :SITE_ID
					AND LANGUE_ID = :LANGUE_ID
					";


        $aSocialNetworks = $oConnection->queryTab($sSqlSocialNetworks, $aBind);

        $aSocialNetworkTypes = array_flip(Pelican::$config['TYPE_RESEAUX_SOCIAUX']);
        $aSocialNetworksSorted = array();
        foreach ($aSocialNetworks as $sn) {
            $aSocialNetworksSorted[$aSocialNetworkTypes[$sn['RESEAU_SOCIAL_TYPE']]][$sn['RESEAU_SOCIAL_ID']] = $sn['RESEAU_SOCIAL_LABEL'];
        }
        $aSocialNetworksNotSorted = array();

        foreach ($aSocialNetworks as $sn) {
            $aSocialNetworksNotSorted[$sn['RESEAU_SOCIAL_ID']] = $sn['RESEAU_SOCIAL_LABEL'];
        }
        $oControllerForm = Backoffice_Form_Helper::getFormAffichage($oController, true, true);
        $oControllerForm .= Backoffice_Form_Helper::getFormModeAffichage($oController);

        foreach ($aSocialNetworksSorted as $k => $v) {
            if (self::$aSocialNetworksFieldsMap[$k]) {
                $oControllerForm .= $oController->oForm->createAssocFromList(
                        $oConnection, $oController->multi . self::$aSocialNetworksFieldsMap[$k], t($k), $v, explode(';',$oController->zoneValues[self::$aSocialNetworksFieldsMap[$k]]), false, true, $oController->readO, 8, 200, false, "", ""
                );
            }
        }

        $oControllerForm .= $oController->oForm->createInput(
                $oController->multi . "ZONE_TITRE2", t('TITRE_RESEAU_SOCIAUX_PAYS'), 255, "", true, $oController->zoneValues["ZONE_TITRE2"], $oController->readO, 100
        );

        $oControllerForm .= $oController->oForm->createAssocFromList(
                $oConnection, $oController->multi . 'ZONE_TEXTE6', t('RESEAU_SOCIAUX_PAYS'), $aSocialNetworksNotSorted, explode(';',$oController->zoneValues['ZONE_TEXTE6']), false, true, $oController->readO, 8, 200, false, "", ""
        );
        $oControllerForm .= $oController->oForm->createInput(
                $oController->multi . "ZONE_TITRE3", t('TITRE_RESEAU_SOCIAUX_CORPORATE'), 255, "", true, $oController->zoneValues["ZONE_TITRE3"], $oController->readO, 100
        );

        $oControllerForm .= $oController->oForm->createAssocFromList(
                $oConnection, $oController->multi . 'ZONE_TEXTE7', t('RESEAU_SOCIAUX_CORPORATE'), $aSocialNetworksNotSorted, explode(';',$oController->zoneValues['ZONE_TEXTE7']), false, true, $oController->readO, 8, 200, false, "", ""
        );

        return $oControllerForm;
    }

    public static function save(Pelican_Controller $controller) {
        Backoffice_Form_Helper::saveFormAffichage(); 
       
        foreach(Pelican_Db::$values as $k=>$v){
            if(
                    in_array($k, self::$aSocialNetworksFieldsMap)||
                    $k == 'ZONE_TEXTE7'||
                    $k == 'ZONE_TEXTE6'
                    
                    ){
                $tmp = implode(';',$v);
                Pelican_Db::$values[$k]=$tmp;
            }
            
        }
        
        parent::save();
    }

}
