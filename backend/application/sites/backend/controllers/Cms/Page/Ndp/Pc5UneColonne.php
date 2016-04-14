<?php
/**
 * Tranche PC5 - Une Colonne.
 *
 * @author     Joseph FRANCLIN <joseph.franclin@businessdecision.com>
 *
 * @since      24/02/2015
 */
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi/Factory.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta/Factory.php';
use PsaNdp\MappingBundle\Object\Block\Pc51Colonne;

class Cms_Page_Ndp_Pc5UneColonne extends Cms_Page_Ndp
{
    const COL_1 = '1_COL';
    const COL_2 = '2_COL';
    const CTA_TYPE = '1_COL';

    /**
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        $maxCharacter = 500;
        self::$con = Pelican_Db::getInstance();
        $return = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));
        $return .= $controller->oForm->createInput(
            $controller->multi.'ZONE_TITRE',
            t('TITLE'),
            60,
            '',
            false,
            $controller->zoneValues['ZONE_TITRE'],
            $controller->readO,
            70,
            false,
            '',
            'text',
            [],
            false,
            '',
            '60 '.t('NDP_MAX_CAR')
        );
        $return .= $controller->oForm->createInput(
            $controller->multi.'ZONE_TITRE2',
            t('NDP_SOUS_TITRE'),
            60,
            '',
            false,
            $controller->zoneValues['ZONE_TITRE2'],
            $controller->readO,
            70,
            false,
            '',
            'text',
            [],
            false,
            '',
            '60 '.t('NDP_MAX_CAR')
        );
        $return .= $controller->oForm->createInput(
            $controller->multi.'ZONE_TITRE3',
            t('NDP_TITRE_ZONE_TEXTE'),
            60,
            '',
            false,
            $controller->zoneValues['ZONE_TITRE3'],
            $controller->readO,
            70,
            false,
            '',
            'text',
            [],
            false,
            '',
            '60 '.t('NDP_MAX_CAR')
        );

        $typAffichage = array(
            self::COL_1 => '1 '.t('NDP_COLONNE'),
            self::COL_2 => '2 '.t('NDP_COLONNES'),
        );
        if (empty($controller->zoneValues['ZONE_TOOL'])) {
            $controller->zoneValues['ZONE_TOOL'] = self::COL_1;
        }
        $type = $controller->multi.'container_colonne';
        $js = self::addJsContainerRadio($type);
        $return .= $controller->oForm->createRadioFromList(
            $controller->multi.'ZONE_TOOL',
            t('NDP_TEXTE_SUR'),
            $typAffichage,
            $controller->zoneValues['ZONE_TOOL'],
            false,
            $controller->readO,
            'h',
            false,
            $js
        );
        $return .= $controller->oForm->createEditor(
            $controller->multi.'ZONE_TEXTE',
            t('NDP_TEXTE').' '.t('NDP_COLONNE').' 1',
            false,
            $controller->zoneValues['ZONE_TEXTE'],
            $controller->readO,
            true,
            '',
            650,
            150,
            null,
            array('message' => t('NDP_DYN_MAX_CAR', null, array('max_characters' => $maxCharacter)), 'maxCharacterNumber' => $maxCharacter)
        );

        $return .= self::addHeadContainer(self::COL_2, $controller->zoneValues['ZONE_TOOL'], $type);
        $return .= $controller->oForm->createLabel(
            '',
            t('NDP_EQUALLY_BALANCED_TEXT'),
            false,
            '',
            array(
                'class_value' => 'alert alert_info',
            )
        );
        $return .= $controller->oForm->createEditor(
            $controller->multi.'ZONE_TEXTE2',
            t('NDP_TEXTE').' '.t('NDP_COLONNE').' 2',
            false,
            $controller->zoneValues['ZONE_TEXTE2'],
            $controller->readO,
            true,
            '',
            650,
            150,
            null,
            array('message' => t('NDP_DYN_MAX_CAR', null, array('max_characters' => $maxCharacter)), 'maxCharacterNumber' => $maxCharacter)
        );
        $return .= self::addFootContainer();

        $return .= $controller->oForm->showSeparator();

        $typAffichage = array(
            Pc51Colonne::VISUEL => t(Pc51Colonne::VISUEL),
            Pc51Colonne::VIDEO => t(Pc51Colonne::VIDEO),
            Pc51Colonne::HTML5 => t(Pc51Colonne::HTML5),
            Pc51Colonne::NO_MEDIA => t(Pc51Colonne::NO_MEDIA),
        );

        $type = $controller->multi.'container_slider';
        $js = self::addJsContainerRadio($type);
        $return .= $controller->oForm->createRadioFromList(
            $controller->multi.'ZONE_PARAMETERS',
            t('TYPE'),
            $typAffichage,
            $controller->zoneValues['ZONE_PARAMETERS'],
            false,
            $controller->readO,
            'h',
            false,
            $js
        );

        $return .= self::makeSlide($controller, Pc51Colonne::VISUEL, array(1, 8));
        $return .= self::addSlideVIDEO($controller->oForm, $controller->zoneValues, $controller->readO, $controller->multi);
        $return .= self::makeSlide($controller, Pc51Colonne::HTML5, array(1, 5));

        $return .= $controller->oForm->showSeparator();

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
                        'forceValues' => ['CTADisable' => false],
                        'CTA_READONLY' => (Cms_Page_Ndp::isTranslator() || $controller->readO),
                        'noDragNDrop' => Cms_Page_Ndp::isTranslator(),
                    ],
                    'CTA_LD' => ['showNumberLabel' => false, 'noSeparator' => true],
                    'needed' => true,
                    'METHOD' => 'addCtaMultiWithoutStyle',
                ]
            );
        }

        return $return;
    }

    /**
     * getLevelsType.
     *
     * @return array
     */
    public static function getTypesLevels()
    {
        return array('LEVEL1_' => 'LEVEL');
    }

    /**
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {
        $type = Pelican_Db::$values['ZONE_PARAMETERS'];
        Pelican_Db::$values['ZONE_TIMER_SPEED'] = Pelican_Db::$values['ZONE_TIMER_SPEED'][$type];

        parent::save();

        $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC);
        $multi->setMultiType($type)
            ->setMulti($controller->multi)
            ->delete()
            ->save();

        foreach (self::getTypesLevels() as $type => $label) {
            $ctaHmvc = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC);
            $ctaHmvc->setCtaType($type.self::TYPE_CTA)
                ->setMulti($controller->multi)
                ->setTypeCtaDropDown($type.self::TYPE_CTA_LD)
                ->delete()//suppression des anciens CTA (liste deroulante compris)
                ->save();

            $ctaLDHmvc = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC);
            $ctaLDHmvc->setCtaType($type.self::TYPE_CTA_LD)
                ->setMulti($controller->multi)
                ->setCtaDropDown(true)
                ->save();
        }
    }

    /**
     * @return array
     */
    public static function getTypes()
    {
        return array(Pc51Colonne::VISUEL, Pc51Colonne::VIDEO, Pc51Colonne::HTML5);
    }

    /**
     * @param Ndp_Form $form
     * @param array    $values
     * @param bool     $readO
     * @param string   $multi
     *
     * @return string
     */
    public static function addSlideVIDEO(Ndp_Form $form, $values, $readO, $multi)
    {
        $return = $return = self::addHeadContainer(
            Pc51Colonne::VIDEO,
            $values['ZONE_PARAMETERS'],
            $multi.'container_slider'
        );

        $return .= $form->createMedia(
            $multi.'MEDIA_ID',
            t(Pc51Colonne::VIDEO),
            true,
            'streamlike',
            '',
            $values['MEDIA_ID'],
            (Cms_Page_Ndp::isTranslator() || $readO),
            true,
            false
        );

        $return .= self::addFootContainer();

        return $return;
    }

    /**
     * @param Ndp_Form $form
     * @param array    $values
     * @param bool     $readO
     * @param string   $multi
     *
     * @return string
     */
    public static function addSlideVISUEL(Ndp_Form $form, $values, $readO, $multi)
    {
        return $form->createNewImage(
            $multi.'MEDIA_ID',
            t('FORM_VISUAL'),
            true,
            $values['MEDIA_ID'],
            (Cms_Page_Ndp::isTranslator() || $readO),
            false,
            Pc51Colonne::DESKTOP_FORMAT,
            [t('DESKTOP') => Pc51Colonne::DESKTOP_FORMAT, t('MOBILE') => Pc51Colonne::MOBILE_FORMAT]
        );
    }

    /**
     * @param Ndp_Form $form
     * @param array    $values
     * @param bool     $readO
     * @param string   $multi
     *
     * @return string
     */
    public static function addSlideHTML5(Ndp_Form $form, $values, $readO, $multi)
    {
        return $form->createTextArea(
            $multi.'PAGE_ZONE_MULTI_TEXT',
            t(Pc51Colonne::HTML5),
            true,
            $values['PAGE_ZONE_MULTI_TEXT'],
            '',
            (Cms_Page_Ndp::isTranslator() || $readO),
            5,
            75,
            false,
            '',
            false
        );
    }

    /**
     * @param Pelican_Controller $controller
     * @param string             $type
     * @param array              $minMax
     *
     * @return string
     */
    public static function makeSlide(Pelican_Controller $controller, $type, $minMax)
    {
        $isZoneDynamique = Ndp_Multi::isZoneDynamique($controller->zoneValues['ZONE_TEMPLATE_ID']);
        $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC, $isZoneDynamique);
        $slides = $multi->setMultiType($type)
            ->hydrate($controller->zoneValues)
            ->getValues();
        $return = self::addHeadContainer(
            $type,
            $controller->zoneValues['ZONE_PARAMETERS'],
            $controller->multi.'container_slider'
        );

        $return .= $controller->oForm->createInput(
            sprintf('%s%s[%s]', $controller->multi, 'ZONE_TIMER_SPEED', $type),
            t('NDP_TIMING_SLIDE'),
            6,
            'number',
            false,
            $controller->zoneValues['ZONE_TIMER_SPEED'],
            $controller->readO,
            10
        );

        $return .= $controller->oForm->createMultiHmvc(
            $controller->multi.$type,
            t('ADD_SLIDE_'.$type),
            array(
                'path' => __FILE__,
                'class' => __CLASS__,
                'method' => 'addSlide'.$type,
            ),
            $slides,
            $type,
            (Cms_Page_Ndp::isTranslator() || $controller->readO),
            $minMax,
            true,
            true,
            $controller->multi.$type,
            'values',
            'multi',
            2,
            '',
            '',
            '',
            ['noDragNDrop' => Cms_Page_Ndp::isTranslator()]
        );

        $return .= self::addFootContainer();

        return $return;
    }
}
