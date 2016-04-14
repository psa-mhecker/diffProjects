<?php
/**
 * Tranche PC - Contenu 1 article 1 visuel.
 *
 * @author Kevin Vignon <kevin.vignon@businessdecision.com>
 *
 * @since 03/03/2015
 */
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi/Factory.php';

use PsaNdp\MappingBundle\Transformers\Pc9Contenu1Article1VisuelDataTransformer;

class Cms_Page_Ndp_Pc9Contenu1Article1Visuel extends Cms_Page_Ndp
{
    const MAX_CTA_VISUEL = 4;
    const MAX_VISUEL = 8;
    const MIN_VISUEL = 0;
    const MULTI_TYPE_684 = 'VISUELS_684';
    const RATIO_VISUEL = 'NDP_GENERIC_4_3_640';

    public static function render(Pelican_Controller $controller)
    {
        $maxCharacter = 500;
        $return = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));

        $return .= $controller->oForm->createInput(
            $controller->multi.'ZONE_TITRE', t('TITRE'), 60, '', false,
            $controller->zoneValues['ZONE_TITRE'], $controller->readO, 75
        );

        $return .= $controller->oForm->createInput(
            $controller->multi.'ZONE_TITRE2', t('SOUS_TITRE'), 60, '', false,
            $controller->zoneValues['ZONE_TITRE2'], $controller->read0, 75
        );

        $targetsAffichage = array(
            Pc9Contenu1Article1VisuelDataTransformer::SLIDE_RIGHT => t('A_DROITE_DU_TEXTE'),
            Pc9Contenu1Article1VisuelDataTransformer::SLIDE_LEFT => t('NPD_A_GAUCHE_DU_TEXTE'),
        );
        $valueZoneParameters = (isset($controller->zoneValues['ZONE_PARAMETERS'])) ? $controller->zoneValues['ZONE_PARAMETERS'] :  Pc9Contenu1Article1VisuelDataTransformer::SLIDE_RIGHT;
        $imageAlignementToolTip = ['infoBull' => ['isIcon' => true, 'message' => t('NDP_MSG_IMAGE_ALIGNEMENT_TEXT')]];
        $return .= $controller->oForm->createRadioFromList(
            $controller->multi.'ZONE_PARAMETERS', t('NDP_AFFICHAGE_VISUEL'),
            $targetsAffichage, $valueZoneParameters, true, false, 'h', false, '', null, $imageAlignementToolTip
        );
        $return .= $controller->oForm->createEditor(
            $controller->multi.'ZONE_TEXTE',
            t('NDP_DESCRIPTION'),
            true,
            $controller->zoneValues['ZONE_TEXTE'],
            $controller->readO,
            true,
            '',
            650,
            150,
            null,
            array('message' => t('NDP_DYN_MAX_CAR', null, array('max_characters' => $maxCharacter)), 'maxCharacterNumber' => $maxCharacter)
        );

        $return .= $controller->oForm->createInput(
            sprintf('%s%s', $controller->multi, 'ZONE_TIMER_SPEED'),
            t('NDP_TIMING_SLIDE'),
            6,
            'number',
            false,
            $controller->zoneValues['ZONE_TIMER_SPEED'],
            $controller->readO,
            10
        );
        $return .= self::addMediaMulti($controller, self::MULTI_TYPE_684);
        $return .= $controller->oForm->showSeparator();

        foreach (self::getTypesLevels() as  $levelLabel) {
            $return .= self::getLevelCta(
               $controller,
               $levelLabel,
               t('CTA'),
               4,
               4,
               false,
               [
                   'CTA' => [
                       'forceValues' => ['CTADisable' => false],
                       'maxCta' => '4',
                       'CTA_READONLY' => (Cms_Page_Ndp::isTranslator() || $controller->readO),
                       'noDragNDrop' => Cms_Page_Ndp::isTranslator(),
                   ],
                   'CTA_LD' => ['showNumberLabel' => false, 'noSeparator' => true],
               ]
           );
        }

        return $return;
    }

    public static function addMediaMulti(Pelican_Controller $controller, $type)
    {
        self::$con = Pelican_Db::getInstance();
        $isZoneDynamique = Ndp_Multi::isZoneDynamique($controller->zoneValues['ZONE_TEMPLATE_ID']);
        $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC, $isZoneDynamique);

        $visuels = $multi->setMultiType($type)
            ->hydrate($controller->zoneValues)
            ->getValues();

        $return = $controller->oForm->createMultiHmvc(
            $controller->multi.$type,
            array('multiAddButton' => t('NDP_ADD_VISUEL'), 'multiTitle' => t('NDP_VISUELS')),
            array(
                'path' => __FILE__,
                'class' => __CLASS__,
                'method' => 'addVisuel',
            ),
            $visuels,
            $type,
            (Cms_Page_Ndp::isTranslator() || $controller->readO),
            array(self::MIN_VISUEL, self::MAX_VISUEL),
            true,
            true,
            $controller->multi.$type,
            'values',
            'multi',
            '2',
            '',
            '',
            true,
            ['noDragNDrop' => Cms_Page_Ndp::isTranslator()]
        );

        return $return;
    }

    /**
     * @param Ndp_Form $form
     * @param array    $values
     * @param bool     $readO
     * @param array    $multi
     *
     * @return string
     */
    public static function addVisuel(Ndp_Form $form, $values, $readO, $multi)
    {
        $return = $form->createNewImage(
            $multi.'MEDIA_ID',
            t('FORM_VISUAL'),
            true,
            $values['MEDIA_ID'],
            (Cms_Page_Ndp::isTranslator() || $readO),
            false,
            self::RATIO_VISUEL,
            [t('DESKTOP_AND_MOBILE') => self::RATIO_VISUEL]
        );

        return $return;
    }

    /**
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {
        parent::save();

        $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC);
        $multi->setMultiType(self::MULTI_TYPE_684)
            ->setMulti($controller->multi)
            ->delete();
        $multi->save();

        foreach (self::getTypesLevels() as $type) {
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
    }
}
