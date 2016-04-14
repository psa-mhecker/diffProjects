<?php
/**
 *
 * @package Pelican_BackOffice
 * @subpackage Tranche
 * @author Joseph FRANCLIN <joseph.fracnlin@businessdecision.com>
 * @since 31/07/2015
 */
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';

class Cms_Page_Ndp_Pn10HeaderFooterGalactiques extends Cms_Page_Ndp
{

    const DEALER_LOCATOR   = 364;
    const ALL_PEUGEOT      = 380;
    const HOMEPAGE         = 363;
    const FOOTER           = 1;
    const CONT_CARTSTORE   = "CONT_CARSTORE";
    const CONT_ALL_PEUGEOT = "CONT_TOUT_PEUGEOT";
    const CONT_PEUGEOT_VP  = "CONT_PEUGEOT_VP";
    const CONT_PEUGEOT_PRO = "CONT_PEUGEOT_PRO";
    const ENABLE           = 1;
    const DISABLE          = 0;
    const CARSTORE         = 1;
    const PEUGEOT_ALL      = 2;
    const PEUGEOT_COUNTRY  = 3;
    const PEUGEOT_PRO      = 4;
    const IS_FALSE         = -2;
    const CTA_FORM         = 'CTAFORM';
    const BACKEND_PICTO    = "/design/backend/images/silk/sprite_find_carstore.png";

    /**
     *
     * @param Pelican_Controller $controller
     * 
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        $form  = self::getHeader($controller);
        $form .= self::getCarStore($controller);
        $form .= self::getAllPeugeot($controller);
        $form .= self::getPeugeotCountryVp($controller);
        $form .= self::getPeugeotPro($controller);
        $form .= self::getMobileLink($controller);
        $form .= self::getMyPeugeot($controller);
        $form .= self::getFooter($controller);
        $form .= self::getUrlCall($controller);
        $form .= self::getJS($controller);

        return $form;
    }
    
    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function getHeader(Pelican_Controller $controller)
    {
        $form  = $controller->oForm->createComment(t('NDP_MSG_HEADER_GAL_LIGHT'));
        $form .= $controller->oForm->createComment(t('NDP_MSG_HEADER_GAL_VP'));
        $form .= $controller->oForm->createComment(t('NDP_MSG_FOOTER_GAL_VP'));
        $form .= $controller->oForm->createHeader(t('NDP_MSG_HEADER_GAL_WEB_LINKS'));
        $form .= $controller->oForm->createComment(t('NDP_MSG_HEADER_GAL_WEB_UP_TO_LINKS'));
        $form .= $controller->oForm->createComment('', array('noBold' => true));

        return $form;
    }
    
    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function getCarStore(Pelican_Controller $controller)
    {
        $form  = $controller->oForm->createComment(t('NDP_MSG_SEARCH_CARSTORE'));
        $dealerPages = self::getPageTemplateById(self::DEALER_LOCATOR);
        $readO = false;
        $typAffichage = self::getEnabledDisabled();
        self::setDefaultValueTo($controller->zoneValues, 'ZONE_ATTRIBUT', self::ENABLE);
        if (empty($dealerPages)) {
            $form .= $controller->oForm->createComment(t('NDP_MSG_SEARCH_CARSTORE_DEALER_REQUIRED'), array('noBold' => true));
            $readO = true;
            $controller->zoneValues['ZONE_ATTRIBUT'] = self::DISABLE;
        }
        $type  = $controller->multi.self::CONT_CARTSTORE;
        $js    = self::addJsContainerRadio($type);
        $form .= $controller->oForm->createRadioFromList($controller->multi.'ZONE_ATTRIBUT', t('AFFICHAGE'), $typAffichage, $controller->zoneValues['ZONE_ATTRIBUT'], true, $readO, 'h', false, $js);
        $form .= self::addHeadContainer(self::ENABLE, $controller->zoneValues['ZONE_ATTRIBUT'], $type);
        $form .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('NDP_BLOC_NUMBER'), 1, "number", false, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 5);
        $form .= $controller->oForm->createInput($controller->multi."FAKE_LABEL1", t('NDP_LABEL'), 255, "", false, t('NDP_SEARCH_CARSTORE_LABEL'), true, 5);
        $options = ['data-template' => self::DEALER_LOCATOR];
        $form .= $controller->oForm->createInput($controller->multi."ZONE_URL", t('URL'), 255, "internallink", true, $controller->zoneValues['ZONE_URL'], $controller->readO, 50, false, "", "text", array(), false, "", $options);
        self::setDefaultValueTo($controller->zoneValues, 'ZONE_ATTRIBUT2', self::ENABLE);
        $form .= $controller->oForm->createRadioFromList($controller->multi.'ZONE_ATTRIBUT2', t('PICTO'), $typAffichage, $controller->zoneValues['ZONE_ATTRIBUT2'], true, $controller->readO, 'h', false);
        $form .= $controller->oForm->createLabel('', Pelican_Html::img(
                        array(
                            'border' => "0",
                            'src' => Pelican::$config["MEDIA_HTTP"].self::BACKEND_PICTO,
                            'class' => '',
                            'title' => '',
                            'style' => "vertical-align: middle;"
                        ))
                );
        $form .= self::addFootContainer();


        return $form;
    }

    /**
     *
     * @param int $id
     * @return array
     */
    public static function getPageTemplateById($id)
    {
        $connection = Pelican_Db::getInstance();
        $bind = [':TPID' => $id];
        $sql = "SELECT *
                FROM  #pref#_page_version pv, #pref#_page p
                WHERE pv.TEMPLATE_PAGE_ID = :TPID AND p.PAGE_ID = pv.PAGE_ID AND pv.PAGE_VERSION = p.PAGE_CURRENT_VERSION AND pv.STATE_ID = 4";

        return $connection->queryTab($sql, $bind);
    }

    /**
     *
     * @return array
     */
    private static function getEnabledDisabled()
    {
        return  array(
            self::DISABLE => t('NDP_DESACTIVE'),
            self::ENABLE => t('NDP_ACTIVE'),
        );
    }

    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function getAllPeugeot(Pelican_Controller $controller)
    {
        $form  = $controller->oForm->createComment(t('NDP_ALL_PEUGEOT'));
        $allPeugeotPages = self::getPageTemplateById(self::ALL_PEUGEOT);
        $readO = false;
        $typAffichage = self::getEnabledDisabled();
        self::setDefaultValueTo($controller->zoneValues, 'ZONE_ATTRIBUT3', self::ENABLE);
        if (empty($allPeugeotPages)) {
            $form .= $controller->oForm->createComment(t('NDP_MSG_ALL_PEUGEOT_REQUIRED'), array('noBold' => true));
            $readO = true;
            $controller->zoneValues['ZONE_ATTRIBUT3'] = self::DISABLE;
        }
        $type  = $controller->multi.self::CONT_ALL_PEUGEOT;
        $js    = self::addJsContainerRadio($type);
        $form .= $controller->oForm->createRadioFromList($controller->multi.'ZONE_ATTRIBUT3', t('AFFICHAGE'), $typAffichage, $controller->zoneValues['ZONE_ATTRIBUT3'], true, $readO, 'h', false, $js);
        $form .= self::addHeadContainer(self::ENABLE, $controller->zoneValues['ZONE_ATTRIBUT3'], $type);
        $form .= $controller->oForm->createInput($controller->multi."ZONE_TITRE2", t('NDP_BLOC_NUMBER'), 1, "number", false, $controller->zoneValues["ZONE_TITRE2"], $controller->readO, 5);
        $form .= $controller->oForm->createInput($controller->multi."FAKE_LABEL2", t('NDP_LABEL'), 255, "", false, t('NDP_ALL_PEUGEOT'), true, 5);
        $options = ['data-template' => self::ALL_PEUGEOT];
        $form .= $controller->oForm->createInput($controller->multi."ZONE_URL2", t('URL'), 255, "internallink", true, $controller->zoneValues['ZONE_URL2'], $controller->readO, 50, false, "", "text", array(), false, "", $options);
        $form .= self::addFootContainer();

        return $form;
    }

    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function getPeugeotCountryVp(Pelican_Controller $controller)
    {
        //TODO check si désactivé en site
        $form  = $controller->oForm->createComment(t('NDP_PEUGEOT_COUNTRY_VP'));
        $typAffichage = self::getEnabledDisabled();
        self::setDefaultValueTo($controller->zoneValues, 'ZONE_TOOL', self::ENABLE);
        $type  = $controller->multi.self::CONT_PEUGEOT_VP;
        $js    = self::addJsContainerRadio($type);
        $form .= $controller->oForm->createRadioFromList($controller->multi.'ZONE_TOOL', t('AFFICHAGE'), $typAffichage, $controller->zoneValues['ZONE_TOOL'], true, $controller->readO, 'h', false, $js);
        $form .= self::addHeadContainer(self::ENABLE, $controller->zoneValues['ZONE_TOOL'], $type);
        $form .= $controller->oForm->createInput($controller->multi."ZONE_TITRE3", t('NDP_BLOC_NUMBER'), 1, "number", false, $controller->zoneValues["ZONE_TITRE3"], $controller->readO, 5);
        $form .= $controller->oForm->createInput($controller->multi."FAKE_LABEL3", t('NDP_LABEL'), 255, "", false, t('NDP_PEUGEOT_COUNTRY_VP'), true, 5);
        $field = 'SITE_DOMAIN_NAME';
        $urlHomePage = self::getPageTemplateById(self::HOMEPAGE)[0]['PAGE_CLEAR_URL'];
        $form .= $controller->oForm->createInput($controller->multi."FAKE_LABEL4", t('URL'), 255, "internallink", false, self::getParamPeugeot($field)[$field].$urlHomePage, true, 5);
        $form .= self::addFootContainer();

        return $form;
    }

    /**
     *
     * @param string $field
     * @return type
     */
    public static function getParamPeugeot($field)
    {
        $connection = Pelican_Db::getInstance();
        $bind = [':SITE_ID' => $_SESSION[APP]['SITE_ID']];
        $sql = "SELECT $field
                FROM  #pref#_sites_et_webservices_psa
                WHERE SITE_ID = :SITE_ID";

        return $connection->queryRow($sql, $bind);
    }

    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function getPeugeotPro(Pelican_Controller $controller)
    {
        $form  = $controller->oForm->createComment(t('NDP_PEUGEOT_PRO'));
        $field = 'ZONE_URL_PEUGEOT_PRO';
        $urlPeugeotPro = self::getParamPeugeot($field)[$field];
        $readO = false;
        if (empty($urlPeugeotPro)) {
            $readO = true;
            $controller->zoneValues['ZONE_TOOL2'] = self::DISABLE;
            $form .= $controller->oForm->createComment(t('NDP_MSG_PEUGEOT_PRO_DISABLE'), array('noBold' => true));
        }
        $typAffichage = self::getEnabledDisabled();
        self::setDefaultValueTo($controller->zoneValues, 'ZONE_TOOL2', self::ENABLE);
        $type  = $controller->multi.self::CONT_PEUGEOT_PRO;
        $js    = self::addJsContainerRadio($type);
        $form .= $controller->oForm->createRadioFromList($controller->multi.'ZONE_TOOL2', t('AFFICHAGE'), $typAffichage, $controller->zoneValues['ZONE_TOOL2'], true, $readO, 'h', false, $js);
        $form .= self::addHeadContainer(self::ENABLE, $controller->zoneValues['ZONE_TOOL2'], $type);
        $form .= $controller->oForm->createInput($controller->multi."ZONE_TITRE4", t('NDP_BLOC_NUMBER'), 1, "number", false, $controller->zoneValues["ZONE_TITRE4"], $controller->readO, 5);
        $form .= $controller->oForm->createInput($controller->multi."FAKE_LABEL5", t('NDP_LABEL'), 255, "", false, t('NDP_PEUGEOT_PRO_LABEL'), true, 5);
        $form .= $controller->oForm->createInput($controller->multi."FAKE_LABEL6", t('URL'), 255, "internallink", false, $urlPeugeotPro, true, 5);
        $form .= self::addFootContainer();

        return $form;
    }

    /**
     *
     * @param Pelican_Controller $controller
     * 
     * @return string
     */
    public static function getMobileLink(Pelican_Controller $controller)
    {
        $form  = $controller->oForm->createHeader(t('NDP_MOBILE_HEADER_GALATIC'));
        $form .= $controller->oForm->createComboFromList($controller->multi.'ZONE_CRITERIA_ID', t('NDP_MOBILE_LINK'), self::getLinks(), $controller->zoneValues['ZONE_CRITERIA_ID'], true, false, 1, false);

        return $form;
    }

    /**
     *
     * @return array
     */
    public static function getLinks()
    {
        return [
            self::CARSTORE => t('NDP_MSG_SEARCH_CARSTORE'),
            self::PEUGEOT_ALL => t('NDP_ALL_PEUGEOT'),
            self::PEUGEOT_COUNTRY => t('NDP_PEUGEOT_COUNTRY_VP'),
            self::PEUGEOT_PRO => t('NDP_PEUGEOT_PRO')
        ];
    }

    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function getMyPeugeot(Pelican_Controller $controller)
    {
        $form  = $controller->oForm->createHeader(t('NDP_MY_PEUGEOT_HEADER_GALATIC'));
        $field = 'ZONE_MY_PEUGEOT';
        $stateMyPeugeot = self::getParamPeugeot($field)[$field];
        $tmpForm = $controller->oForm->createComment(t('NDP_MSG_MY_PEUGEOT_REQUIRED'), array('noBold' => true));
        if ($stateMyPeugeot != self::IS_FALSE) {
            $field = 'ZONE_URL_WEB_ACCUEIL';
            $urlWeb = self::getParamPeugeot($field)[$field];
            $field = 'ZONE_URL_MOB_ACCUEIL';
            $urlMob = self::getParamPeugeot($field)[$field];
            $tmpForm  = $controller->oForm->createInput($controller->multi."FAKE_LABEL7", t('NDP_URL_WEB_LINK'), 255, '', false, t('NDP_MY_PEUGEOT_WEB_LABEL'), true, 5);
            $tmpForm .= $controller->oForm->createInput($controller->multi."FAKE_LABEL8", t('URL_WEB'), 255, "", false, $urlWeb, true, 5);
            $tmpForm .= $controller->oForm->createInput($controller->multi."FAKE_LABEL9", t('NDP_URL_MOB_LINK'), 255, '', false, t('NDP_MY_PEUGEOT_MOB_LABEL'), true, 5);
            $tmpForm .= $controller->oForm->createInput($controller->multi."FAKE_LABEL10", t('URL_MOB'), 255, "", false, $urlMob, true, 5);
        }
        $form .= $tmpForm;

        return $form;
    }

    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function getFooter(Pelican_Controller $controller)
    {
        $form  = $controller->oForm->createHeader(t('NDP_FOOTER_GALACTIC'));
        $form .= $controller->oForm->createComment(t('NDP_FOOTER_GALACTIC_ONLY_VP'));
        $valuesForPt2 = $controller->zoneValues;
        $valuesForPt2['ZONE_TEMPLATE_ID'] = self::FOOTER;
        $_SESSION[APP]['PROTOCOL_URL'] = $controller->getBaseUrl();
        $strLib = array(
            'multiTitle' => t('NDP_LINK').' : '
        );
        $typeForm = self::CTA_FORM;
        $isZoneDynamique = Ndp_Cta::isZoneDynamique($valuesForPt2['ZONE_TEMPLATE_ID']);
        $ctaMulti = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC, $isZoneDynamique);
        
        $values = $ctaMulti->hydrate($valuesForPt2)
            ->setCtaType($typeForm)
            ->getValues();
        $options = ['noSeparator' => true, 'noDragNDrop' => true, 'showNumberLabel' => false];
        $form .= $controller->oForm->createMultiHmvc(
            $controller->multi."fakeMulti", $strLib,
            array(
                "path" => __FILE__,
                "class" => __CLASS__,
                "method" => "addLink"
            ),
            $values,
            "fakeMulti",
            true,
            '',
            false,
            false,
            $controller->multi."fakeMulti",
            "values",
            "multi",
            "2",
            "",
            "",
            true,
            $options
        );

        return $form;

    }
    /**
     *
     * @param Ndp_Form $form
     * @param array $values
     * @param boolean $readO
     * @param string $multi
     *
     * @return string
     */
    public static function addLink(Ndp_Form $form, $values, $readO, $multi)
    {
        $url = $values['ACTION'];
        if (substr($values['ACTION'], 0, 1) === "/") {
            $url = $_SESSION[APP]['PROTOCOL_URL'].$url;
        }
        $return = $form->createLabel('- <a href="'.$url.'" target="_blank">'.$values['TITLE'].'</a>', '');
        
        return $return;
    }

    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function getUrlCall(Pelican_Controller $controller)
    {
        $form  = $controller->oForm->createHeader(t('NDP_CALLED_URL'));
        //TODO : Récupérer la génération des liens
        $form .= $controller->oForm->createLabel('- '.t('NDP_HEADER_GALACTIC_JSON'), self::createUrlForUrlCall());
        $form .= $controller->oForm->createLabel('- '.t('NDP_HEADER_GALACTIC_XML'), self::createUrlForUrlCall());
        $form .= $controller->oForm->createLabel('- '.t('NDP_HEADER_GALACTIC_VP_JSON'), self::createUrlForUrlCall());
        $form .= $controller->oForm->createLabel('- '.t('NDP_HEADER_GALACTIC_VP_XML'), self::createUrlForUrlCall());

        return $form;
    }

    /**
     *
     * @return string
     */
    public static function createUrlForUrlCall()
    {
        $url = '';
        
        return $url;
    }

    /**
     *
     * @return string
     */
    public static function getJS(Pelican_Controller $controller)
    {
        $form = $controller->oForm->createJS("
            var activedLinks = 0;
            var multi = '".$controller->multi."';
            var input = '';
            if($('input[name=\"'+multi+'ZONE_ATTRIBUT\"]:checked').val() == ".self::ENABLE.") {
                activedLinks++;
            }
            if($('input[name=\"'+multi+'ZONE_ATTRIBUT3\"]:checked').val() == ".self::ENABLE.") {
                activedLinks++;
            }
            if($('input[name=\"'+multi+'ZONE_TOOL\"]:checked').val() == ".self::ENABLE.") {
                activedLinks++;
            }
            if($('input[name=\"'+multi+'ZONE_TOOL2\"]:checked').val() == ".self::ENABLE.") {
                activedLinks++;
            }

            if(activedLinks > 3) {
                alert('".t('NDP_MSG_ERROR_UP_TO_3_LINKS_WEB')."');

                return false;
            }
            ");

        return $form;
    }
    
     /**
     *
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();
        if (Pelican_Db::$values['ZONE_ATTRIBUT'] == self::DISABLE) {
            unset(Pelican_Db::$values['ZONE_TITRE']);
            unset(Pelican_Db::$values['ZONE_URL']);
        }
        if (Pelican_Db::$values['ZONE_ATTRIBUT3'] == self::DISABLE) {
            unset(Pelican_Db::$values['ZONE_TITRE2']);
            unset(Pelican_Db::$values['ZONE_URL2']);
        }
        if (Pelican_Db::$values['ZONE_TOOL'] == self::DISABLE) {
            unset(Pelican_Db::$values['ZONE_TITRE3']);
        }
        if (Pelican_Db::$values['ZONE_TOOL2'] == self::DISABLE) {
            unset(Pelican_Db::$values['ZONE_TITRE4']);
        }
        
        parent::save();        
    }
}
