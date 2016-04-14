<?php

/**
 * Content - Pc76 Contenu Promotion
 *
 * @package Pelican_BackOffice
 * @subpackage Content
 * @author Pierre Pottié <pierre.pottie@businessdecision.com>
 * @since 13/08/2015
 */
class Cms_Content_Ndp_Pc76Promotion extends \Cms_Content_Module
{

    const CONTENT_TYPE_CAMPAGNE_ID = 9;
    const IMAGE_CODE = 'NDP_IMAGE';
    const HTML_CODE = 'NDP_HTML';
    const FLASH_CODE = 'NDP_FLASH';
    const IMAGE_KEY = 1;
    const HTML_KEY = 2;
    const FLASH_KEY = 3;
    const TYPE_CTA = "FORM_CTA";
    const TYPE_LINK = "FORM_LINK";

    static public  $VISUAL_FIELDS = [
        'SQUARE_VISUAL' => 'CONTENT_ATTRIBUT_INTEGER',
        'BIG_VISUAL' => 'CONTENT_ATTRIBUT_INTEGER',
        'MEGA_BANNER_VISUAL' => 'CONTENT_ATTRIBUT_INTEGER',
        'VISUAL' => 'MEDIA_ID',
        'HTML_CODE' => 'CONTENT_ATTRIBUT_TEXT',
        'SWF_FILE' => 'MEDIA_ID',
        'XML_FILE' => 'MEDIA_ID',
        'ALTERNATIVE_VISUAL' => 'MEDIA_ID',
        'ALTERNATIVE_TEXT' => 'CONTENT_ATTRIBUT_TEXT',
        'ALTERNATIVE_URL' => 'CONTENT_ATTRIBUT_STRING',
        'MENTIONS_LEGALES' => 'CONTENT_ATTRIBUT_STRING',
        'MACAROON_VISUAL' => 'MEDIA_ID',
    ];

    /**
     * 
     * @param Pelican_Controller $controller
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {

        $return = self::getCampagneSelector($controller);
        $return .= $controller->oForm->createInput(
            "CONTENT_URL", t('NDP_PAGE_URL'), 100, "internallink", false, $controller->values["CONTENT_URL"], $controller->read0, 70
        );
        $return .= $controller->oForm->createInput("CONTENT_TITLE2", t('NDP_SOUS_TITRE'), 120, "", false, $controller->values["CONTENT_TITLE2"], $controller->read0, 100, false, '', 'text', [], false, '', '120'.t('NDP_MAX_CAR'));
        $return .= $controller->oForm->createEditor('CONTENT_SUBTITLE', t('NDP_SHORT_MAIN_PRICE_ADVANTAGE'), true, $controller->values['CONTENT_SUBTITLE'], $controller->readO, true, $strSubFolder = "", $width = "", $height = "50");
        $return .= $controller->oForm->createEditor('CONTENT_TEXT', t('NDP_LONG_MAIN_PRICE_ADVANTAGE'), true, $controller->values['CONTENT_TEXT'], $controller->readO, true, $strSubFolder = "", $width = "", $height = "50");
        $return .= $controller->oForm->createEditor('CONTENT_TEXT2', t('NDP_SECOND_PRICE_ADVANTAGE'), true, $controller->values['CONTENT_TEXT2'], $controller->readO, true, $strSubFolder = "", $width = "", $height = "50");
        $return .= $controller->oForm->createTextArea('CONTENT_SHORTTEXT2', t('NDP_OFFER_DESCRIPTION'), true, $controller->values['CONTENT_SHORTTEXT2'], 600, $controller->readO, 2, 100, false, '', true);

        $return .= self::getSquareVisual($controller);
        $return .= self::getBigVisual($controller);
        $return .= self::getMegaBannerVisual($controller);
        $return .= self::getMacaroonVisual($controller);
        $return .= self::getLegalNotice($controller);
        $return .= self::getCtaAffichage($controller);
        $return .= self::getLinkAffichage($controller);

        return $return;
    }

    /**
     * 
     * @param Pelican_Controller $controller
     * @param string $fieldName
     * @param string $type
     * 
     * @return string/null
     */
    function getAttributeValues(Pelican_Controller $controller, $fieldName, $type = '')
    {
        $connection = Pelican_Db::getInstance();

        $bind = [
            ':CONTENT_ID' => $controller->values['CONTENT_ID'],
            ':LANGUE_ID' => $_SESSION[APP]['LANGUE_ID'],
            ':VERSION_ID' => $controller->values['CONTENT_VERSION'],
            ':CONTENT_ATTRIBUT_NAME' => $connection->strToBind((!empty($type) ? $type.'_' : '').$fieldName)
        ];

        $sql = "SELECT  ".self::$VISUAL_FIELDS[$fieldName]." as value
                    FROM #pref#_content_version_attribut pcva
                    WHERE pcva.CONTENT_ID = :CONTENT_ID
                    AND pcva.LANGUE_ID = :LANGUE_ID
                    AND pcva.CONTENT_VERSION= :VERSION_ID
                    AND pcva.CONTENT_ATTRIBUT_NAME = :CONTENT_ATTRIBUT_NAME";

        $result = $connection->getItem($sql, $bind);
        if (empty($result)) {
            $result = null;
        }

        return $result;
    }

    /**
     * 
     * @param Pelican_Controller $controller
     * @return string
     */
    public static function getSquareVisual(Pelican_Controller $controller)
    {
        $return = '';

        $squareVisualData = [
            self::IMAGE_KEY => t(self::IMAGE_CODE),
            self::HTML_KEY => t(self::HTML_CODE),
            self::FLASH_KEY => t(self::FLASH_CODE),
        ];

        $squareVisualValue = self::getAttributeValues($controller, 'SQUARE_VISUAL');

        $type = 'square_visual';
        $js = self::addJsContainerCombo($type);
        $options = [
            'infoBull' => [
                'isIcon' => true,
                'message' => t('NDP_MSG_TOOLTIP_SQUARE_VISUAL')
            ]
        ];
        $return .= $controller->oForm->createComboFromList("SQUARE_VISUAL", t('NDP_SQUARE_VISUAL'), $squareVisualData, $squareVisualValue, false, $readO, "1", false, "", true, false, $js, $options);

        $return .= self::addHeadContainer(self::IMAGE_KEY, $squareVisualValue, $type);
        $svVisualValue = self::getAttributeValues($controller, "VISUAL", "SV");
        $return .= $controller->oForm->createMedia(
            'SV[VISUAL]', t("NDP_VISUEL"), true, "image", "", $svVisualValue, (Cms_Page_Ndp::isTranslator() || $controller->readO), true, false, 'NDP_RATIO_IAB_PAVE:300x250'
        );
        $return .= self::addFootContainer();

        $return .= self::addHeadContainer(self::HTML_KEY, $squareVisualValue, $type);
        $svHtmlValue = self::getAttributeValues($controller, 'HTML_CODE', "SV");

        $return .= $controller->oForm->createTextArea(
            'SV[HTML_CODE]', t('NDP_HTML_CODE'), true, $svHtmlValue, '', $controller->readO, 2, 100, false, '', true
        );
        $return .= self::addFootContainer();

        $return .= self::addHeadContainer(self::FLASH_KEY, $squareVisualValue, $type);

        $svSwfValue = self::getAttributeValues($controller, 'SWF_FILE', "SV");
        $return .= $controller->oForm->createMedia("SV[SWF_FILE]", t('NDP_SWF_FILE'), true, "flash", "", $svSwfValue, (Cms_Page_Ndp::isTranslator() || $controller->readO));

        $svXmlValue = self::getAttributeValues($controller, 'XML_FILE', "SV");
        $return .= $controller->oForm->createMedia("SV[XML_FILE]", t('NDP_XML_FILE'), false, "xml", "", $svXmlValue, (Cms_Page_Ndp::isTranslator() || $controller->readO));

        $svAltrenativeVisualValue = self::getAttributeValues($controller, 'ALTERNATIVE_VISUAL', "SV");
        $return .= $controller->oForm->createMedia("SV[ALTERNATIVE_VISUAL]", t('NDP_ALTERNATIVE_VISUAL'), false, "image", "", $svXmlValue, (Cms_Page_Ndp::isTranslator() || $controller->readO), true, false, 'NDP_RATIO_IAB_PAVE:300x250');

        $svAlternativeTextValue = self::getAttributeValues($controller, 'HTML_CODE', "SV");
        $return .= $controller->oForm->createTextArea(
            'SV[ALTERNATIVE_TEXT]', t('NDP_ALTERNATIVE_TEXT'), true, $svAlternativeTextValue, '', $controller->readO, 2, 100, false, '', true
        );

        $svAlternativeUrlValue = self::getAttributeValues($controller, 'ALTERNATIVE_URL', "SV");
        $return .= $controller->oForm->createInput(
            "SV[ALTERNATIVE_URL]", t('NDP_ALTERNATIVE_URL'), 100, "internallink", false, $svAlternativeUrlValue, $controller->read0, 70
        );

        $return .= self::addFootContainer();

        return $return;
    }

    /**
     * 
     * @param Pelican_Controller $controller
     * @return string
     */
    public static function getBigVisual(Pelican_Controller $controller)
    {
        $return = '';

        $bigVisualData = [
            self::IMAGE_KEY => t(self::IMAGE_CODE),
            self::HTML_KEY => t(self::HTML_CODE),
        ];

        $bigVisualValue = self::getAttributeValues($controller, "BIG_VISUAL");

        $type = 'big_visual';
        $js = self::addJsContainerCombo($type);
        $options = [
            'infoBull' => [
                'isIcon' => true,
                'message' => t('NDP_MSG_TOOLTIP_BIG_VISUAL')
            ]
        ];
        $return .= $controller->oForm->createComboFromList("BIG_VISUAL", t('NDP_BIG_VISUAL'), $bigVisualData, $bigVisualValue, false, $readO, "1", false, "", true, false, $js, $options);

        $return .= self::addHeadContainer(self::IMAGE_KEY, $bigVisualValue, $type);
        $bgVisualValue = self::getAttributeValues($controller, "VISUAL", 'BV');
        $return .= $controller->oForm->createMedia(
            'BV[VISUAL]', t("NDP_VISUEL"), true, "image", "", $bgVisualValue, (Cms_Page_Ndp::isTranslator() || $controller->readO), true, false, 'NDP_RATIO_IAB_BILLBOARD:970x250'
        );
        $return .= self::addFootContainer();

        $return .= self::addHeadContainer(self::HTML_KEY, $bigVisualValue, $type);
        $bgHtmlValue = self::getAttributeValues($controller, 'HTML_CODE', 'BV');

        $return .= $controller->oForm->createTextArea(
            'BV[HTML_CODE]', t('NDP_HTML_CODE'), true, $bgHtmlValue, '', $controller->readO, 2, 100, false, '', true
        );
        $return .= self::addFootContainer();

        return $return;
    }

    /**
     * 
     * @param Pelican_Controller $controller
     * @return string
     */
    public static function getMegaBannerVisual(Pelican_Controller $controller)
    {
        $return = '';

        $megaBannerVisualData = [
            self::IMAGE_KEY => t(self::IMAGE_CODE),
            self::HTML_KEY => t(self::HTML_CODE),
            self::FLASH_KEY => t(self::FLASH_CODE),
        ];

        $megaBannerVisualValue = self::getAttributeValues($controller, "MEGA_BANNER_VISUAL");

        $type = 'megaBanner_visual';
        $js = self::addJsContainerCombo($type);
        $options = [
            'infoBull' => [
                'isIcon' => true,
                'message' => t('NDP_MSG_TOOLTIP_MEGA_BANNER_VISUAL')
            ]
        ];
        $return .= $controller->oForm->createComboFromList("MEGA_BANNER_VISUAL", t('NDP_MEGA_BANNER_VISUAL'), $megaBannerVisualData, $megaBannerVisualValue, false, $readO, "1", false, "", true, false, $js, $options);

        $return .= self::addHeadContainer(self::IMAGE_KEY, $megaBannerVisualValue, $type);
        $svVisualValue = self::getAttributeValues($controller, "VISUAL", 'MBV');
        $return .= $controller->oForm->createMedia(
            'MBV[VISUAL]', t("NDP_VISUEL"), true, "image", "", $svVisualValue, (Cms_Page_Ndp::isTranslator() || $controller->readO), true, false, 'NDP_RATIO_IAB_HORIZONTAL:728x90'
        );
        $return .= self::addFootContainer();

        $return .= self::addHeadContainer(self::HTML_KEY, $megaBannerVisualValue, $type);
        $svHtmlValue = self::getAttributeValues($controller, "HTML_CODE", 'MBV');

        $return .= $controller->oForm->createTextArea(
            'MBV[HTML_CODE]', t('NDP_HTML_CODE'), true, $svHtmlValue, '', $controller->readO, 2, 100, false, '', true
        );
        $return .= self::addFootContainer();

        $return .= self::addHeadContainer(self::FLASH_KEY, $megaBannerVisualValue, $type);

        $svSwfValue = self::getAttributeValues($controller, "SWF_FILE", 'MBV');
        $return .= $controller->oForm->createMedia("MBV[SWF_FILE]", t('NDP_SWF_FILE'), true, "flash", "", $svSwfValue, (Cms_Page_Ndp::isTranslator() || $controller->readO));

        $svXmlValue = self::getAttributeValues($controller, "XML_FILE", 'MBV');
        $return .= $controller->oForm->createMedia("MBV[XML_FILE]", t('NDP_XML_FILE'), false, "xml", "", $svXmlValue, (Cms_Page_Ndp::isTranslator() || $controller->readO));

        $svAltrenativeVisualValue = self::getAttributeValues($controller, "ALTERNATIVE_VISUAL", 'MBV');
        $return .= $controller->oForm->createMedia("MBV[ALTERNATIVE_VISUAL]", t('NDP_ALTERNATIVE_VISUAL'), false, "image", "", $svXmlValue, (Cms_Page_Ndp::isTranslator() || $controller->readO), true, false, 'NDP_RATIO_IAB_HORIZONTAL:728x90');

        $svAlternativeTextValue = self::getAttributeValues($controller, "HTML_CODE", 'MBV');
        $return .= $controller->oForm->createTextArea(
            'MBV[ALTERNATIVE_TEXT]', t('NDP_ALTERNATIVE_TEXT'), true, $svAlternativeTextValue, '', $controller->readO, 2, 100, false, '', true
        );

        $svAlternativeUrlValue = self::getAttributeValues($controller, "ALTERNATIVE_URL", 'MBV');
        $return .= $controller->oForm->createInput(
            "MBV[ALTERNATIVE_URL]", t('NDP_ALTERNATIVE_URL'), 100, "internallink", false, $svAlternativeUrlValue, $controller->read0, 70
        );

        $return .= self::addFootContainer();

        return $return;
    }

    /**
     * 
     * @param Pelican_Controller $controller
     * @return string
     */
    public static function getLegalNotice(Pelican_Controller $controller)
    {
        $return = '';
        $mentionsLegalesValue = self::getAttributeValues($controller, "MENTIONS_LEGALES");
        $return .= $controller->oForm->createEditor('MENTIONS_LEGALES', t('NDP_TEXT_DESC_MENTIONS_LEGALES'), true, $mentionsLegalesValue, $controller->readO, true, $strSubFolder = "", $width = "", $height = "50");

        return $return;
    }

    /**
     * 
     * @param Pelican_Controller $controller
     * @return string
     */
    public static function getMacaroonVisual(Pelican_Controller $controller)
    {
        $return = '';
        $mvVisualValue = self::getAttributeValues($controller, "MACAROON_VISUAL");

        $return .= $controller->oForm->createMedia("MACAROON_VISUAL", t('NDP_MACAROON_VISUAL'), false, "media", "", $mvVisualValue, (Cms_Page_Ndp::isTranslator() || $controller->readO));

        return $return;
    }

    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function getCtaAffichage(Pelican_Controller $controller)
    {
        Ndp_Cta_Factory::setContext('Content');
        $ctaMulti = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC);
        $ctaMulti->hydrate($controller->values);
        $ctaMulti->setIsMulti(true);
        $ctaMulti->setCtaType(self::TYPE_CTA);

        $strLib = array(
            'multiTitle' => t('NDP_CTA'),
            'multiAddButton' => t('NDP_ADD_FORM_CTA')
        );

        $return .= $controller->oForm->createMultiHmvc(
            self::TYPE_CTA,
            $strLib,
            array(
                "path" => __FILE__,
                "class" => __CLASS__,
                "method" => 'addCtaMulti',
            ),
            $ctaMulti->getValues(),
            self::TYPE_CTA,
            (Cms_Page_Ndp::isTranslator() || $controller->readO),
            array(0, 2),
            true,
            true,
            self::TYPE_CTA,
            'values',
            'multi',
            '2',
            '',
            '',
            false,
            ['noDragNDrop' => Cms_Page_Ndp::isTranslator()]
        );

        return $return;
    }

    /**
     *
     * @param Ndp_Form $form
     * @param array $values
     * @param bool $readO
     * @param array $multi
     *
     * @return string
     */
    public static function addCtaMulti(Ndp_Form $form, $values, $readO, $multi)
    {
        // Ajout formulaire des CTA
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $ctaRef = Pelican_Factory::getInstance('CtaRef');
        $ctaRef->addTargetAvailable('_popin', t('NDP_POPIN'));

        $ctaNew = Pelican_Factory::getInstance('CtaNew');
        $ctaNew->addTargetAvailable('_popin', t('NDP_POPIN'));


        $ctaComposite->setCta($form, $values, $multi, '', true, (Cms_Page_Ndp::isTranslator() || $readO));
        $ctaComposite->addInputCta($ctaRef);
        $ctaComposite->addInputCta($ctaNew);

        return $ctaComposite->generate();
    }

    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function getLinkAffichage(Pelican_Controller $controller)
    {
        Ndp_Cta_Factory::setContext('Content');
        $ctaMulti = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC);
        $ctaMulti->hydrate($controller->values);
        $ctaMulti->setIsMulti(true);
        $ctaMulti->setCtaType(self::TYPE_LINK);

        $strLib = array(
            'multiTitle' => t('NDP_LINK'),
            'multiAddButton' => t('NDP_ADD_FORM_LINK')
        );

        $return .= $controller->oForm->createMultiHmvc(
            self::TYPE_LINK,
            $strLib,
            array(
                "path" => __FILE__,
                "class" => __CLASS__,
                "method" => 'addLinkMulti',
            ),
            $ctaMulti->getValues(),
            self::TYPE_LINK,
            (Cms_Page_Ndp::isTranslator() || $controller->readO),
            array(0, 2),
            true,
            true,
            self::TYPE_LINK,
            'values',
            'multi',
            "2",
            '',
            '',
            false,
            ['noDragNDrop' => Cms_Page_Ndp::isTranslator()]
        );

        return $return;
    }

    /**
     *
     * @param Ndp_Form $form
     * @param array $values
     * @param bool $readO
     * @param array $multi
     *
     * @return string
     */
    public static function addLinkMulti(Ndp_Form $form, $values, $readO, $multi)
    {
        // Ajout formulaire des CTA
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $ctaRef = Pelican_Factory::getInstance('CtaRef');
        $ctaRef->addTargetAvailable('_popin', t('NDP_POPIN'));
        $ctaRef->hideStyle(true);
        $ctaRef->setStyle('style_niveau4');
        $ctaNew = Pelican_Factory::getInstance('CtaNew');
        $ctaNew->addTargetAvailable('_popin', t('NDP_POPIN'));
        $ctaNew->hideStyle(true);
        $ctaNew->setStyle('style_niveau4');
        $ctaComposite->setCta($form, $values, $multi, '', true, (Cms_Page_Ndp::isTranslator() || $readO));

        $ctaComposite->addInputCta($ctaRef);
        $ctaComposite->addInputCta($ctaNew);

        return $ctaComposite->generate();
    }

    /**
     * get sql request for list campagne
     *
     * @return string sql
     *
     */
    public static function getSqlListCampagne()
    {
        $sql = "SELECT  pc.CONTENT_ID  AS id, pcv.CONTENT_TITLE AS lib
                    FROM #pref#_content as pc LEFT JOIN #pref#_content_version pcv
                    ON (pc.CONTENT_ID = pcv.CONTENT_ID 
                        AND pc.LANGUE_ID = pcv.LANGUE_ID
                        AND pc.CONTENT_CURRENT_VERSION = pcv.CONTENT_VERSION
                    )
                    WHERE pc.SITE_ID =:SITE_ID
                    AND pc.LANGUE_ID =:LANGUE_ID
                    AND CONTENT_STATUS = 1
                    AND STATE_ID = 4
                    AND pc.CONTENT_TYPE_ID = :CONTENT_TYPE_ID
                    ORDER BY pcv.CONTENT_TITLE";

        return $sql;
    }

    /**
     * get bind array for list campagne request
     *
     * @return array bind
     */
    public static function getBindListCampagne($connection)
    {
        $bind = array(':SITE_ID' => $_SESSION[APP]['SITE_ID'],
            ':LANGUE_ID' => $_SESSION[APP]['LANGUE_ID'],
            ':CONTENT_TYPE_ID' => self::CONTENT_TYPE_CAMPAGNE_ID
        );

        return $bind;
    }

    /**
     * 
     * @param Pelican_Controller $controller
     * 
     * @return string
     */
    public static function getCampagneSelector(Pelican_Controller $controller)
    {
        /* Connexion à la bdd */
        $connection = Pelican_Db::getInstance();
        $return = $controller->oForm->createComboFromSql($connection, 'CONTENT_CODE', t('NDP_CAMPAGNE'), self::getSqlListCampagne(), $controller->values["CONTENT_CODE"], false, $controller->read0, "1", false, "", true, false, "", "", self::getBindListCampagne($connection)
        );

        return $return;
    }

    /**
     * 
     * @param string $typeVisual
     */
    public static function saveVisualParameters($typeVisual)
    {
        $saved = Pelican_Db::$values;
        $visualParams = Pelican_Db::$values[$typeVisual];
        if (isset($visualParams['VISUAL'])) {
            self::saveParameter(self::$VISUAL_FIELDS, 'VISUAL', $typeVisual);
        }

        if (isset($visualParams['HTML_CODE'])) {
            self::saveParameter(self::$VISUAL_FIELDS, 'HTML_CODE', $typeVisual);
        }
        if (isset($visualParams['ALTERNATIVE_TEXT'])) {
            self::saveParameter(self::$VISUAL_FIELDS, 'ALTERNATIVE_TEXT', $typeVisual);
        }
        if (isset($visualParams['ALTERNATIVE_URL'])) {
            self::saveParameter(self::$VISUAL_FIELDS, 'ALTERNATIVE_URL', $typeVisual);
        }
        if (isset($visualParams['SWF_FILE'])) {
            self::saveParameter(self::$VISUAL_FIELDS, 'SWF_FILE', $typeVisual);
        }
        if (isset($visualParams['XML_FILE'])) {
            self::saveParameter(self::$VISUAL_FIELDS, 'XML_FILE', $typeVisual);
        }
        if (isset($visualParams['ALTERNATIVE_VISUAL'])) {
            self::saveParameter(self::$VISUAL_FIELDS, 'ALTERNATIVE_VISUAL', $typeVisual);
        }
        Pelican_Db::$values = $saved;
    }

    /**
     * 
     */
    public static function saveAllParameters()
    {
        if (isset(Pelican_Db::$values['SV'])) {
            self::saveParameter(self::$VISUAL_FIELDS, 'SQUARE_VISUAL');
            self::saveVisualParameters('SV');
        }

        if (isset(Pelican_Db::$values['BV'])) {
            self::saveParameter(self::$VISUAL_FIELDS, 'BIG_VISUAL');
            self::saveVisualParameters('BV');
        }
        if (isset(Pelican_Db::$values['MBV'])) {
            self::saveParameter(self::$VISUAL_FIELDS, 'MEGA_BANNER_VISUAL');
            self::saveVisualParameters('MBV');
        }

        self::saveParameter(self::$VISUAL_FIELDS, 'MACAROON_VISUAL');
        self::saveParameter(self::$VISUAL_FIELDS, 'MENTIONS_LEGALES');
    }

    
    public static function addJSValidator(Pelican_Controller $controller){
        $controller->oForm->createJS(''
            . 'var bv_value = $("#BIG_VISUAL").val();'
            . 'var sv_value = $("#SQUARE_VISUAL").val();'
            . 'if( sv_value == "" && bv_value == ""){    alert("'.t('NDP_NEED_SQUARE_VISUAL_OR_BIG_VISUAL').'"); return false;}');
    }
    /**
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {
        Ndp_Cta_Factory::setContext('Content');

        $saved = Pelican_Db::$values;
        self::cleanAllParameters();
        parent::save($controller);
        if (Pelican_Db::$values["form_action"] != Pelican_Db::DATABASE_DELETE) {
            self::saveAllParameters();
        }
        Pelican_Db::$values = $saved;
        $saved = Pelican_Db::$values;
        self::saveCtaHMVC($controller->multi, self::TYPE_CTA);

        self::saveCtaHMVC($controller->multi, self::TYPE_LINK);

        Ndp_Cta_Factory::setDefaultContext();
        Pelican_Db::$values = $saved;
    }
    
    
}
