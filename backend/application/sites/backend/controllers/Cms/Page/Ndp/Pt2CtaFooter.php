<?php

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta/Factory.php';

/**
 *
 */
class Cms_Page_Ndp_Pt2CtaFooter extends Cms_Page_Ndp
{
    const TYPE_AFFICHAGE = 'CTA';
    const NB_CTA = 2;

    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();
        $return = self::makeCTAs($controller, self::NB_CTA);

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
        self::saveCTAs($controller, self::NB_CTA);
    }

    /**
     *
     * @param Pelican_Controller $controller
     * @param int $nombre
     */
    public static function saveCTAs(Pelican_Controller $controller, $nombre = 1)
    {
        for ($i = 1; $i <= $nombre; $i++) {
            $ctaSimple = Ndp_Cta_Factory::getInstance(Ndp_Cta::SIMPLE);
            $ctaSimple->setCtaType(self::TYPE_AFFICHAGE.$i)
                ->setMulti($controller->multi)
                ->delete()
                ->save();
        }
    }

    /**
     *
     * @param Pelican_Controller $controller
     * @param int $nombre
     */
    public static function makeCTAs(Pelican_Controller $controller, $nombre = 1)
    {
        $return = '';
        for ($i = 1; $i <= $nombre; $i++) {
            $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
            $ctaComposite->setLabel('CTA '.$i);
            $ctaComposite->setCta($controller->oForm, $controller->zoneValues, $controller->multi, self::TYPE_AFFICHAGE.$i, false, (Cms_Page_Ndp::isTranslator() || $controller->readO));
            $ctaRef = Pelican_Factory::getInstance('CtaRef');
            $ctaNew = Pelican_Factory::getInstance('CtaNew');
            $ctaRef->typeStyle(1);
            $ctaNew->typeStyle(1);
            $ctaComposite->addInputCta($ctaRef);
            $ctaComposite->addInputCta($ctaNew);
            $return .= $ctaComposite->generate();
        }

        return $return;
    }
}
