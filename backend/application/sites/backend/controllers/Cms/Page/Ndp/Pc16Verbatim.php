<?php
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi/Factory.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta/Factory.php';
/**
 * Tranche PC - Verbatim
 *
 * @package Pelican_BackOffice
 * @subpackage Tranche
 * @author Kevin Vignon <kevin.vignon@businessdecision.com>
 * @since 11/03/2015
 */
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi/Factory.php';

class Cms_Page_Ndp_Pc16Verbatim extends Cms_Page_Ndp
{

    const VERBATIM = 'NDP_VERBATIM';
    const TYPE_VERBATIM = 'SLIDESHOW';
    const DISABLED = 0;
    const ENABLED = 1;

    public static function render(Pelican_Controller $controller)
    {
        $form = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));
        $typeMulti = self::VERBATIM;
        $isZoneDynamique = Ndp_Multi::isZoneDynamique($controller->zoneValues['ZONE_TEMPLATE_ID']);
        $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC, $isZoneDynamique);
        $verbatimValues = $multi->setMultiType($typeMulti)
            ->hydrate($controller->zoneValues)
            ->getValues();
        $strLib = array(
            'multiTitle' => t('NDP_VERBATIM'),
            'multiAddButton' => t('NDP_ADD_VERBATIM')
        );
        $form .= $controller->oForm->createMultiHmvc(
            $controller->multi.$typeMulti, $strLib,
            array(
                "path" => __FILE__,
                "class" => __CLASS__,
                "method" => "addVerbatim"
            ),
            $verbatimValues,
            $typeMulti,
            (Cms_Page_Ndp::isTranslator() || $controller->readO),
            array(1, 5),
            true,
            true,
            $controller->multi.$typeMulti,
            'values',
            'multi',
            '2',
            '',
            '',
            false,
            ['noDragNDrop' => Cms_Page_Ndp::isTranslator()]
        );

        return $form;
    }

    public static function addVerbatim(Ndp_Form $form, $values, $readO, $multi)
    {
        $return = $form->createMedia(
            $multi.'MEDIA_ID', t('FORM_VISUAL'), true, 'image', '', $values['MEDIA_ID'], (Cms_Page_Ndp::isTranslator() || $readO), true, false, 'NDP_RATIO_SQUARE_1_1:494x494'
        );
        $return .= $form->createInput(
             $multi.'PAGE_ZONE_MULTI_TITRE', t('NDP_IDENTITE_DU_CLIENT'), 100, "", true, $values['PAGE_ZONE_MULTI_TITRE'], $readO, 110, false, '', 'text', [], false, '', "100 ".t('NDP_MAX_CAR')
        );
        $return .= $form->createEditor($multi.'PAGE_ZONE_MULTI_TEXT', t('AVIS_CLIENT'), true, $values['PAGE_ZONE_MULTI_TEXT'], $readO, true, "", 650, 150);
        $return .= $form->createInput(
            $multi.'PAGE_ZONE_MULTI_LABEL', t('NDP_LABEL_ORIGINE'), 30, "", false, $values['PAGE_ZONE_MULTI_LABEL'], $readO, 40, false, '', 'text', [], false, '', "30 ".t('NDP_MAX_CAR')
        );
        $targetsAffichagePublication = array(
            self::DISABLED => t('NDP_DESACTIVE'),
            self::ENABLED => t('NDP_ACTIVE'),
        );

        $type = $multi.'configSite';
        $jsActivation = self::addJsContainerRadio($type);
        self::setDefaultValueTo($values, 'PAGE_ZONE_MULTI_ATTRIBUT', self::DISABLED);

        $return .= $form->createRadioFromList(
            $multi."PAGE_ZONE_MULTI_ATTRIBUT", t('NDP_SITE_PUBLICATION'), $targetsAffichagePublication, $values['PAGE_ZONE_MULTI_ATTRIBUT'], false, $readO, 'h', false, $jsActivation
        );


        $return .= self::addHeadContainer(self::ENABLED, $values['PAGE_ZONE_MULTI_ATTRIBUT'], $type);
        $return .= self::addCtaSimple($form, $values, $multi, $readO);
        $return .= self::addFootContainer();

        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
        $DB_VALUES = Pelican_Db::$values;
        parent::save();
        $typeMulti = self::VERBATIM;

        $ctaSlideShow = Ndp_Cta_Factory::getInstance(Ndp_Cta::SIMPLE_INTO_MULTI_HMVC);
        $ctaSlideShow->setCtaType(self::TYPE_VERBATIM)
            ->setStyle(\PSA\MigrationBundle\Entity\Cta\PsaCta::STYLE_NIVEAU4);

        $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC);
        $multi->setMultiType($typeMulti)
            ->setMulti($controller->multi)
            ->addChild($ctaSlideShow)
            ->delete()
            ->save();
        Pelican_Db::$values = $DB_VALUES;
    }

    /**
     * @param Ndp_Form $form
     * @param array    $values
     * @param string   $multi
     *
     * @return string
     */
    public function addCtaSimple(Ndp_Form $form, $values, $multi, $readO)
    {
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $ctaComposite->setCta($form, $values, $multi, self::TYPE_VERBATIM, false, (Cms_Page_Ndp::isTranslator() || $readO), Ndp_Cta::SIMPLE_INTO_MULTI_HMVC, 'Ndp_Multi');
        $ctaDisable = Pelican_Factory::getInstance('CtaDisable');
        $ctaNew = Pelican_Factory::getInstance('CtaNew');
        $ctaNew->typeStyle(1)->addTargetAvailable(
            '_popin', t('NDP_POPIN')
        );
        $ctaComposite->addInputCta($ctaDisable);
        $ctaComposite->addInputCta($ctaNew);

        return $ctaComposite->generate();
    }
}
