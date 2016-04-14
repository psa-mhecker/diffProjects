<?php

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta/Factory.php';

/**
 *
 */
class Cms_Page_Ndp_Pt2ElementLegaux extends Cms_Page_Ndp
{
    const CTA_FORM = 'CTAFORM';

    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();
        $return = $controller->oForm->createTextArea($controller->multi."ZONE_TEXTE", t('NDP_TERMS_CONDITIONS'), false, $controller->zoneValues['ZONE_TEXTE'], 1500, $controller->readO, 5, 75, false, "", true);
        //Affichage des CTA en mode multi
        $typeForm = self::CTA_FORM;
        $isZoneDynamique = Ndp_Cta::isZoneDynamique($controller->zoneValues['ZONE_TEMPLATE_ID']);
        $ctaMulti = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC, $isZoneDynamique);
        $valuesCta = $ctaMulti->hydrate($controller->zoneValues)->setCtaType($typeForm)->getValues();
        $strLib = array(
            'multiTitle'     => t('NDP_CTA'),
            'multiAddButton' => t('ADD_FORM_CTA')
        );
        $return .= $controller->oForm->createMultiHmvc(
            $controller->multi."CTAFORM",
            $strLib,
            array(
                "path" => __FILE__,
                "class" => __CLASS__,
                "method" => "addNewCtaMulti"
            ),
            $valuesCta,
            $controller->multi."CTAFORM",
            (Cms_Page_Ndp::isTranslator() || $controller->readO),
            array(1,5),
            true,
            true,
            $controller->multi."CTAFORM",
            'values',
            'multi',
            2,
            '',
            '',
            '',
            ['noDragNDrop' => Cms_Page_Ndp::isTranslator()]
        );

        return $return;
    }

    /**
     *
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();
        parent::save();
        $ctaHmvc = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC);
        $ctaHmvc->setCtaType(self::CTA_FORM)
            ->setMulti($controller->multi)
            ->delete()
            ->save();
    }
}
