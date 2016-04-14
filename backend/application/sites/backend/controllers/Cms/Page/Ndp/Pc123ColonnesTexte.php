<?php
use PsaNdp\MappingBundle\Object\Block\Pc123ColonnesTexte;

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi/Factory.php';

/**
 *
 */
class Cms_Page_Ndp_Pc123ColonnesTexte extends Cms_Page_Ndp
{

    const DISPLAY_CARROUSEL = 0;
    const DISPLAY_AFTER = 1;
    const COLUMN_1 = 'NDP_COLONNE1';
    const COLUMN_2 = 'NDP_COLONNE2';
    const COLUMN_3 = 'NDP_COLONNE3';

    /**
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        $form = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));
        $form .= $controller->oForm->createInput(
            $controller->multi.'ZONE_TITRE', t('TITLE'), 60, '', false, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 50, false, '', 'text', [], false, '', 60 .t('NDP_MAX_CAR')
        );

        foreach (self::getTypesColonnes() as $colonne) {
            $form .= self::getColonne($controller, $colonne);
        }

        return $form;
    }
    /*
     *
     * @return array
     */

    public static function getTypesColonnes()
    {
        return array(self::COLUMN_1, self::COLUMN_2, self::COLUMN_3);
    }

    /*
     *
     * @param Pelican_Controller $controller
     * @param string $colonne
     *
     * @return string
     */

    public static function getColonne(Pelican_Controller $controller, $colonne)
    {
        $maxCharacter = 500;
        $isZoneDynamique = Ndp_Multi::isZoneDynamique($controller->zoneValues['ZONE_TEMPLATE_ID']);
        $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::SIMPLE, $isZoneDynamique);
        $values = $multi->setMultiType($colonne)
            ->hydrate($controller->zoneValues)
            ->getValues();


        $form = $controller->oForm->showSeparator();
        $form .= $controller->oForm->createLabel(t($colonne));
        $form .= $controller->oForm->createInput(
            $controller->multi.$colonne.'_PAGE_ZONE_MULTI_TITRE2', t('NDP_TITRE_COLONNE'), 60, '', false, $values['PAGE_ZONE_MULTI_TITRE2'], $controller->readO, 70, false, '', 'text', [], false, '', 60 .t('NDP_MAX_CAR')
        );
        $form .= $controller->oForm->createNewImage($controller->multi.$colonne.'_MEDIA_ID', t('FORM_VISUAL'), false, $values['MEDIA_ID'],(Cms_Page_Ndp::isTranslator() || $controller->readO), false, Pc123ColonnesTexte::MOBILE_FORMAT, [t('DESKTOP') => Pc123ColonnesTexte::DESKTOP_FORMAT, t('MOBILE') => Pc123ColonnesTexte::MOBILE_FORMAT]);
        $form .= $controller->oForm->createEditor(
            $controller->multi.$colonne.'_PAGE_ZONE_MULTI_TEXT',
            t('NDP_TEXTE'),
            false,
            $values['PAGE_ZONE_MULTI_TEXT'],
            $controller->readO,
            true,
            "",
            650,
            150,
            null,
            array('message'=>t('NDP_DYN_MAX_CAR',null,array('max_characters'=>$maxCharacter)), 'maxCharacterNumber' => $maxCharacter)
        );

        if ($colonne === self::COLUMN_3) {
            $form .= $controller->oForm->createLabel(
                '',
                t('NDP_EQUALLY_BALANCED_TEXT'),
                false,
                '',
                array(
                    'class_value'=>'alert alert_info'
                )
            );
        }

        $form .= self::getCta($controller, $colonne);

        return $form;
    }

    /**
     * @param $controller
     * @param $column
     *
     * @return mixed
     */
    public static function getCta($controller, $column)
    {
        //Affichage des CTA en mode multi
        $form = self::getLevelCta(
            $controller,
            $column,
            t('CTA'),
            2,
            2,
            false,
            [
                'CTA' => [
                    'forceValues' => ['CTADisable' => false],
                    'maxCta' => '2',
                    'CTA_READONLY' => (Cms_Page_Ndp::isTranslator() || $controller->readO),
                    'noDragNDrop' => Cms_Page_Ndp::isTranslator(),
                ],
                'CTA_LD' => ['showNumberLabel' => false, 'noSeparator' => true],
                'METHOD' => 'addCtaMultiWithoutStyle',
            ]
        );

        return $form;
    }

    /*
     *
     * @param Pelican_Controller $controller
     *
     */

    public static function save(Pelican_Controller $controller)
    {
        parent::save();
        $multiId = 1;

        foreach (self::getTypesColonnes() as $type) {
            $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::SIMPLE);
            $multi->setMultiType($type)
                ->setMultiId($multiId)
                ->delete()
                ->save();

            $ctaHmvc = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC);
            $ctaHmvc->setCtaType($type.self::TYPE_CTA)
                ->setTypeCtaDropDown($type.self::TYPE_CTA_LD)
                ->delete() //suppression des anciens CTA (liste deroulante compris)
                ->save();
            $ctaLDHmvc = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC);
            $ctaLDHmvc->setCtaType($type.self::TYPE_CTA_LD)
                ->setCtaDropDown(true)
                ->save();

            $multiId++;
        }
    }
}
