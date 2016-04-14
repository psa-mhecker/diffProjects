<?php

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta/Factory.php';

/**
 *
 */
class Cms_Page_Ndp_Pt2BesoinAide extends Cms_Page_Ndp
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
        $return = $controller->oForm->createCheckBoxFromList($controller->multi."ZONE_AFFICHAGE", t('NDP_SHOW_COLONNE'), array(1 => ""), $controller->zoneValues["ZONE_AFFICHAGE"]);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITLE'), 25, "", true, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 50, false, '', 'text', [], false, '', "25 ".t('NDP_MAX_CAR'));
        //Affichage des CTA en mode multi

        $typeForm = self::CTA_FORM;
        $isZoneDynamique = Ndp_Cta::isZoneDynamique($controller->zoneValues['ZONE_TEMPLATE_ID']);
        $ctaMulti = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC, $isZoneDynamique);
        $valuesCta = $ctaMulti->hydrate($controller->zoneValues)
            ->setCtaType($typeForm)
            ->getValues();
        $strLib = array(
            'multiTitle' => t('NDP_CTA'),
            'multiAddButton' => t('ADD_FORM_CTA')
        );
        $return .= $controller->oForm->createMultiHmvc(
            $controller->multi . self::CTA_FORM,
            $strLib,
            array(
                "path" => __FILE__,
                "class" => __CLASS__,
                "method" => "addNewCtaMulti"),
            $valuesCta,
            $controller->multi . self::CTA_FORM,
            (Cms_Page_Ndp::isTranslator() || $controller->readO),
            array(2, 4),
            true,
            true,
            $controller->multi . self::CTA_FORM,
            'values',
            'multi',
            2,
            '',
            '',
            false,
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

    /**
     *
     * @param Ndp_Form $form
     * @param array $values
     * @param boolean $readO
     * @param string $multi
     *
     * @return string
     */
    public function addNewCtaMulti(Ndp_Form $form, $values, $readO, $multi) {
        // Ajout formulaire des CTA
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $ctaNew = Pelican_Factory::getInstance('CtaNew');
        $ctaNew->hideStyle(true);
        $ctaComposite->setCta($form, $values, $multi, '', true, (Cms_Page_Ndp::isTranslator() || $readO));
        $ctaComposite->addInputCta($ctaNew);

        return $ctaComposite->generate();
    }

}
