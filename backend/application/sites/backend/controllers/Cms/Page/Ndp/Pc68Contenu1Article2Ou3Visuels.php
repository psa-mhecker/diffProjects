<?php
/**
 * Tranche PC5 - Une Colonne
 *
 * @package Pelican_BackOffice
 * @subpackage Tranche
 * @author Joseph FRANCLIN <joseph.franclin@businessdecision.com>
 * @since 24/02/2015
 */
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi/Factory.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta/Factory.php';

class Cms_Page_Ndp_Pc68Contenu1Article2Ou3Visuels extends Cms_Page_Ndp
{

    const COL_1 = '1_COL';
    const COL_2 = '2_COL';
    const VISUELS_2 = '2_VISUELS';
    const VISUELS_3 = '3_VISUELS';
    const VISUELS = 'NDP_VISUELS';
    const POS1 = 1;
    const CTA_TYPE = "ARTICLE_VISUEL";
    const MAX_CHAR = 500;
    const RATIO_VISUEL = 'NDP_MEDIA_ONE_ARTICLE_THREE_VISUAL';
    const RATIO_VISUEL_MOBILE = 'NDP_GENERIC_4_3_640';
  
    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();
        $return = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITLE'), 60, "", false, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 70, false, '', 'text', [], false, '', 60 .t('NDP_MAX_CAR'));
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE2", t('NDP_SOUS_TITRE'), 60, "", false, $controller->zoneValues["ZONE_TITRE2"], $controller->readO, 70, false, '', 'text', [], false, '', 60 .t('NDP_MAX_CAR'));
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE3", t('NDP_TITRE_ZONE_TEXTE'), 60, "", false, $controller->zoneValues["ZONE_TITRE3"], $controller->readO, 70, false, '', 'text', [], false, '', 60 .t('NDP_MAX_CAR'));
        
        $typAffichage = array(
            self::COL_1 => "1 ".t('NDP_COLONNE'),
            self::COL_2 => "2 ".t('NDP_COLONNES')
        );
        if (empty($controller->zoneValues['ZONE_PARAMETERS'])) {
            $controller->zoneValues['ZONE_PARAMETERS'] = self::COL_1;
        }
        $type = $controller->multi.'container_colonne';
        $js = self::addJsContainerRadio($type);
        $return .= $controller->oForm->createRadioFromList($controller->multi.'ZONE_PARAMETERS', t('NDP_TYPE_AFFICHAGE'), $typAffichage, $controller->zoneValues['ZONE_PARAMETERS'], true, (Cms_Page_Ndp::isTranslator() || $controller->readO), 'h', false, $js);
        $return .= $controller->oForm->createEditor(
            $controller->multi."ZONE_TEXTE",
            t('NDP_TEXTE')." 1",
            false,
            $controller->zoneValues['ZONE_TEXTE'],
            $controller->readO,
            true,
            "",
            650,
            150,
            null,
            array('message'=>t('NDP_DYN_MAX_CAR', null, array('max_characters'=> self::MAX_CHAR)), 'maxCharacterNumber' => self::MAX_CHAR)
        );

        $return .= self::addHeadContainer(self::COL_2, $controller->zoneValues['ZONE_PARAMETERS'], $type);

        $return.=$controller->oForm->createLabel(
            '',
            t('NDP_EQUALLY_BALANCED_TEXT'),
            false,
            '',
            array(
                'class_value'=>'alert alert_info'
            )
        );
        $return .= $controller->oForm->createEditor(
            $controller->multi."ZONE_TEXTE2",
            t('NDP_TEXTE')." 2",
            false,
            $controller->zoneValues['ZONE_TEXTE2'],
            $controller->readO,
            true,
            "",
            650,
            150,
            null,
            array('message'=>t('NDP_DYN_MAX_CAR', null, array('max_characters'=> self::MAX_CHAR)), 'maxCharacterNumber' => self::MAX_CHAR)
        );
        $return .= self::addFootContainer();
        $return .= $controller->oForm->showSeparator();
        $return .= self::setMedias($controller);
        $return .= $controller->oForm->showSeparator();

        $return .= $controller->oForm->createDescription(t('CTA'));
        foreach (self::getTypesLevels() as $level => $levelLabel) {
            $return .= self::getLevelCta(
                $controller,
                $level,
                '',
                4,
                4,
                false,
                [
                    'CTA' => [
                        'forceValues'=> ['CTADisable'=>false],
                        'maxCta' => '4',
                        'CTA_READONLY' =>(Cms_Page_Ndp::isTranslator() || $controller->readO),
                        'noDragNDrop' => Cms_Page_Ndp::isTranslator()
                    ],
                    'CTA_LD'=> ['showNumberLabel' => false, 'noSeparator' => true]
                ]
            );
        }
        return $return;
    }

    /**
     * getLevelsType
     *
     * @return array
     */
    public static function getTypesLevels()
    {
        return array('LEVEL1_' => 'LEVEL');
    }



    /**
     *
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();
        $save = Pelican_Db::$values;

        if (!self::checkOrder(Pelican_Db::$values['ZONE_ATTRIBUT'], Pelican_Db::$values['ZONE_ATTRIBUT2'])) {
            Pelican_Db::$values['ZONE_ATTRIBUT2'] = Pelican_Db::$values['ZONE_ATTRIBUT2'] + 1;
        }

        if (isset(Pelican_Db::$values['ZONE_ATTRIBUT3'])) {
            if (!self::checkOrder(Pelican_Db::$values['ZONE_ATTRIBUT'], Pelican_Db::$values['ZONE_ATTRIBUT3'])) {
                Pelican_Db::$values['ZONE_ATTRIBUT3'] = Pelican_Db::$values['ZONE_ATTRIBUT3'] + 1;
            }
            if (!self::checkOrder(Pelican_Db::$values['ZONE_ATTRIBUT2'], Pelican_Db::$values['ZONE_ATTRIBUT3'])) {
                Pelican_Db::$values['ZONE_ATTRIBUT3'] = Pelican_Db::$values['ZONE_ATTRIBUT3'] + 1;
            }
        }

        Pelican_Db::$values = array_merge(Pelican_Db::$values,Pelican_Db::$values[Pelican_Db::$values['ZONE_TOOL']]);
        parent::save();

        foreach (self::getTypesLevels() as $type => $label) {
            $ctaHmvc = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC);
            $ctaHmvc->setCtaType($type.self::TYPE_CTA)
                ->setMulti($controller->multi)
                ->setTypeCtaDropDown($type.self::TYPE_CTA_LD)
                ->delete() //suppression des anciens CTA (liste deroulante compris)
                ->save();
            
            $ctaLDHmvc = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC);
            $ctaLDHmvc->setCtaType($type.self::TYPE_CTA_LD)
                ->setMulti($controller->multi)
                ->setCtaDropDown(true)
                ->save();
        }
        Pelican_Db::$values = $save;
    }

    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function setMedias(Pelican_Controller $controller)
    {
        $typAffichage = array(
            self::VISUELS_2 => "2 ".t(self::VISUELS),
            self::VISUELS_3 => "3 ".t(self::VISUELS)
        );
        self::setDefaultValueTo($controller->zoneValues,'ZONE_TOOL', self::VISUELS_2);
        $ratio= self::RATIO_VISUEL_MOBILE;
        $crops =  [t('DESKTOP_AND_MOBILE') => self::RATIO_VISUEL_MOBILE];
        $type = $controller->multi.'container_visuels';
        $js = self::addJsContainerRadio($type);
        $return  = $controller->oForm->createDescription(t('VISUELS'));
        $return .= $controller->oForm->createRadioFromList($controller->multi.'ZONE_TOOL', t('VISUELS'), $typAffichage, $controller->zoneValues['ZONE_TOOL'], true, $controller->readO, 'h', false, $js);
        // ZONE 2 VISUELS
        $return .= self::addHeadContainer(self::VISUELS_2, $controller->zoneValues['ZONE_TOOL'], $type);
        $return .= $controller->oForm->createNewImage($controller->multi.'['.self::VISUELS_2.'][MEDIA_ID]',t('FORM_VISUAL')." 1", true, $controller->zoneValues['MEDIA_ID'], (Cms_Page_Ndp::isTranslator() ||  $controller->readO), false, $ratio, $crops);
        self::setDefaultValueTo($controller->zoneValues,'ZONE_ATTRIBUT', self::POS1);
        $return .= $controller->oForm->createInput($controller->multi.'['.self::VISUELS_2.'][ZONE_ATTRIBUT]', t('NDP_POSITION'), 1, "number", true, $controller->zoneValues["ZONE_ATTRIBUT"], $controller->readO, 5);
        $return .= $controller->oForm->createNewImage($controller->multi.'['.self::VISUELS_2.'][MEDIA_ID2]',t('FORM_VISUAL')." 2", true, $controller->zoneValues['MEDIA_ID2'], (Cms_Page_Ndp::isTranslator() ||  $controller->readO), false, $ratio, $crops);
        self::setDefaultValueTo($controller->zoneValues,'ZONE_ATTRIBUT2', 2);
        $return .= $controller->oForm->createInput($controller->multi.'['.self::VISUELS_2.'][ZONE_ATTRIBUT2]', t('NDP_POSITION'), 1, "number", true, $controller->zoneValues["ZONE_ATTRIBUT2"], $controller->readO, 5);
        $return .= self::addFootContainer();
        //ZONE 3 VISUELS
        $ratio= self::RATIO_VISUEL_MOBILE;

        $crops =  [t('DESKTOP') => self::RATIO_VISUEL, t('MOBILE') => self::RATIO_VISUEL_MOBILE];
        $return .= self::addHeadContainer(self::VISUELS_3, $controller->zoneValues['ZONE_TOOL'], $type);

        $return .= $controller->oForm->createNewImage($controller->multi.'['.self::VISUELS_3.'][MEDIA_ID]',t('FORM_VISUAL')." 1", true, $controller->zoneValues['MEDIA_ID'], (Cms_Page_Ndp::isTranslator() ||  $controller->readO), false, $ratio, $crops);
        self::setDefaultValueTo($controller->zoneValues,'ZONE_ATTRIBUT', self::POS1);
        $return .= $controller->oForm->createInput($controller->multi.'['.self::VISUELS_3.'][ZONE_ATTRIBUT]', t('NDP_POSITION'), 1, "number", true, $controller->zoneValues["ZONE_ATTRIBUT"], $controller->readO, 5);

        $return .= $controller->oForm->createNewImage($controller->multi.'['.self::VISUELS_3.'][MEDIA_ID2]',t('FORM_VISUAL')." 2", true, $controller->zoneValues['MEDIA_ID2'], (Cms_Page_Ndp::isTranslator() ||  $controller->readO), false, $ratio, $crops);
        self::setDefaultValueTo($controller->zoneValues,'ZONE_ATTRIBUT2', 2);
        $return .= $controller->oForm->createInput($controller->multi.'['.self::VISUELS_3.'][ZONE_ATTRIBUT2]', t('NDP_POSITION'), 1, "number", true, $controller->zoneValues["ZONE_ATTRIBUT2"], $controller->readO, 5);

        $return .= $controller->oForm->createNewImage($controller->multi.'['.self::VISUELS_3.'][MEDIA_ID3]',t('FORM_VISUAL')." 3", true, $controller->zoneValues['MEDIA_ID3'], (Cms_Page_Ndp::isTranslator() ||  $controller->readO), false, $ratio, $crops);
        self::setDefaultValueTo($controller->zoneValues,'ZONE_ATTRIBUT3', 3);
        $return .= $controller->oForm->createInput($controller->multi.'['.self::VISUELS_3.'][ZONE_ATTRIBUT3]', t('NDP_POSITION'), 1, "number", true, $controller->zoneValues["ZONE_ATTRIBUT3"], $controller->readO, 5);
        $return .= self::addFootContainer();

        return $return;
    }

    /**
     * @param $firstAttribute
     * @param $secondAttribute
     *
     * @return bool
     */
    public function checkOrder($firstAttribute, $secondAttribute)
    {
        if (($firstAttribute - $secondAttribute) === 0) {
            return false;
        }

        return true;
    }
}
