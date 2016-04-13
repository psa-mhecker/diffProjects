<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Module.php');
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_ContractServices extends Cms_Page_Citroen
{

    public static function render(Pelican_Controller $controller)
    {
         $return .= '<script type="text/javascript">
             var cpt = -2;



        </script>';

        $return .= $controller->oForm->createJS('

            for(var i=0 ; i <= cpt ; i++)
            {

                if($("#'.$controller->multi.'ADD_CONTRACT_SERVICE"+ i +"_multi_display").val())
                {

                    var Libelle = document.getElementsByName("'.$controller->multi.'ADD_CONTRACT_SERVICE"+ i +"_PAGE_ZONE_MULTI_LABEL")[0].value;
                    var URLw =  document.getElementsByName("'.$controller->multi.'ADD_CONTRACT_SERVICE"+ i +"_PAGE_ZONE_MULTI_URL")[0].value;
                    var URLm =  document.getElementsByName("'.$controller->multi.'ADD_CONTRACT_SERVICE"+ i +"_PAGE_ZONE_MULTI_URL2")[0].value;

                    if(!Libelle || !URLw || !URLm)
                    {

                      if(!document.getElementsByName("'.$controller->multi.'ADD_CONTRACT_SERVICE"+ i +"_PAGE_ZONE_MULTI_VALUE")[0].value)
                          {
                           document.getElementsByName("'.$controller->multi.'ADD_CONTRACT_SERVICE"+ i +"_PAGE_ZONE_MULTI_VALUE")[0].value = "SELF"
                          }


                    }

                   var Libelle2 = document.getElementsByName("'.$controller->multi.'ADD_CONTRACT_SERVICE"+ i +"_PAGE_ZONE_MULTI_LABEL2")[0].value;
                    var URLw2 =  document.getElementsByName("'.$controller->multi.'ADD_CONTRACT_SERVICE"+ i +"_PAGE_ZONE_MULTI_URL3")[0].value;

                    if(!Libelle2 || !URLw2)
                    {

                         if(!document.getElementsByName("'.$controller->multi.'ADD_CONTRACT_SERVICE"+ i +"_PAGE_ZONE_MULTI_VALUE2")[0].value)
                          {
                           document.getElementsByName("'.$controller->multi.'ADD_CONTRACT_SERVICE"+ i +"_PAGE_ZONE_MULTI_VALUE2")[0].value = "SELF"
                          }
                    }


                }

            }



        ');
        $return .= Backoffice_Form_Helper::getFormAffichage($controller);
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);
        $return .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE", t ( 'TITRE' ), 255, "", false, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 50);
        $return .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE2", t ( 'SOUS_TITRE' ), 255, "", false, $controller->zoneValues["ZONE_TITRE2"], $controller->readO, 50);

        $sMultiName = $controller->multi .'ADD_CONTRACT_SERVICE';
        $return .= $controller->oForm->createMultiHmvc(
                $sMultiName,
                t('CONTRAT_SERVICE_FORM'),
                array(
                    'path' => __FILE__,
                    'class' => __CLASS__,
                    'method' => 'addContractSevices'
                 ),
                Backoffice_Form_Helper::getPageZoneMultiValues($controller, 'CONTRACT_SERVICE'),
                $sMultiName, $controller->readO, '', true, true, $sMultiName
            );

        return $return;
    }

    public static function addContractSevices($oForm, $values, $readO, $multi)
    {
        $aModeOUVERTURE = Pelican::$config['TRANCHE_COL']["BLANK_SELF"];
        $aTypePicto = Pelican::$config['TYPE_PICTO_CDS'];

        $return .= '
            <script type="text/javascript">
				cpt = cpt + 1;

            </script>
			';

        //$return .= Backoffice_Form_Helper::getFormModeAffichageMultiHmvc($oForm, $values,$readO,$multi,'PAGE_ZONE_MULTI_TITRE4');
        $aModeAffichage = array(
            'C' => t('LIGNE_C'),
            'NEUTRE' => t('Neutre'),
            'NOUVEAU_SHOWROOM'=>t('NOUVEAU_SHOWROOM')
        );
        $return .= $oForm->createComboFromList($multi . "PAGE_ZONE_MULTI_TITRE4", t("MODE_AFFICHAGE"), $aModeAffichage, $values['PAGE_ZONE_MULTI_TITRE4'], true, $readO);
        $return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_TITRE", t ( 'TITRE' ), 255, "", true, $values["PAGE_ZONE_MULTI_TITRE"], $readO, 50);
        $return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_TITRE2", t ( 'SOUS_TITRE' ), 255, "", true, $values["PAGE_ZONE_MULTI_TITRE2"], $readO, 50);
        $return .= $oForm->createMedia($multi."MEDIA_ID", t ('VISUEL'), true, "image", "", $values['MEDIA_ID'], $readO, true, false);
        $return .= $oForm->createComboFromList($multi . "PAGE_ZONE_MULTI_VALUE3", t("PICTOGRAMME"), $aTypePicto, $values["PAGE_ZONE_MULTI_VALUE3"], true, $readO);
        $return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_TITRE3", t ( 'TITRE_CONTENT' ), 255, "", true, $values["PAGE_ZONE_MULTI_TITRE3"], $readO, 50);
        $return .= $oForm->createEditor($multi . "PAGE_ZONE_MULTI_TEXT", t('TEXTE_OUVERT'), true, $values["PAGE_ZONE_MULTI_TEXT"], $readO, true, "", 500, 150);
        $return .= $oForm->createEditor($multi . "PAGE_ZONE_MULTI_TEXT2", t('TEXTE_FERMER'), true, $values["PAGE_ZONE_MULTI_TEXT2"], $readO, true, "", 500, 150);
        $return .= $oForm->createLabel(t('LINK'),'');
        $return .= $oForm->createInput ($multi . "PAGE_ZONE_MULTI_LABEL", t ( 'LIBELLE' ), 255, "", false, $values["PAGE_ZONE_MULTI_LABEL"], $readO, 100);
        $return .= $oForm->createInput ($multi . "PAGE_ZONE_MULTI_URL", t ( 'URL_WEB' ), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL"], $readO, 100);
        $return .= $oForm->createInput ($multi . "PAGE_ZONE_MULTI_URL2", t ( 'URL_MOB' ), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL2"], $readO, 100);
        $return .= $oForm->createComboFromList($multi . "PAGE_ZONE_MULTI_VALUE", t("MODE_OUVERTURE"), $aModeOUVERTURE, $values["PAGE_ZONE_MULTI_VALUE"], true, $readO);
        $return .= $oForm->createLabel(t('CTA'),'');
        $return .= $oForm->createInput ($multi . "PAGE_ZONE_MULTI_LABEL2", t ( 'LIBELLE' ), 255, "", false, $values["PAGE_ZONE_MULTI_LABEL2"], $readO, 100);
        $return .= $oForm->createInput ($multi . "PAGE_ZONE_MULTI_URL3", t ( 'URL_WEB' ), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL3"], $readO, 100);
        $return .= $oForm->createComboFromList($multi . "PAGE_ZONE_MULTI_VALUE2", t("MODE_OUVERTURE"), $aModeOUVERTURE, $values["PAGE_ZONE_MULTI_VALUE2"], true, $readO);

        return $return;
    }

    public static function save()
    {
        Backoffice_Form_Helper::saveFormAffichage();
		parent::save();
        Backoffice_Form_Helper::savePageZoneMultiValues('ADD_CONTRACT_SERVICE', 'CONTRACT_SERVICE');
        Pelican_Cache::clean("Frontend/Citroen/ZoneMulti");
    }
}
?>
