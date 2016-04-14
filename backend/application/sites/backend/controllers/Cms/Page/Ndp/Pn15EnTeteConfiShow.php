<?php

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta/Factory.php';

/**
 * Tranche PC - en tete ConfiShow.
 *
 * @author Kevin Vignon <kevin.vignon@businessdecision.com>
 *
 * @since 22/05/2015
 */
class Cms_Page_Ndp_Pn15EnTeteConfiShow extends Cms_Page_Ndp
{
    const FORM_WEB = 'FORM_WEB';

    /**
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        if (self::isChildPage()) {
            $return = $controller->oForm->createDescription(t('NDP_MSG_CHILD_CONFIGURATION'));
        } else {
            $return = self::getCtaAffichage($controller);
        }

        return $return;
    }

    // Ajout formulaire des CTA

    public static function getCtaAffichage(Pelican_Controller $controller)
    {
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $ctaComposite->setCta($controller->oForm, $controller->zoneValues, $controller->multi, self::TYPE_CTA, false, true);
        $ctaDisable = Pelican_Factory::getInstance('CtaDisable');
        $ctaRef = Pelican_Factory::getInstance('CtaRef');
        $ctaRef->typeStyle(1);
        $ctaRef->addTargetAvailable(
            '_popin', t('NDP_POPIN')
        );
        $ctaNew = Pelican_Factory::getInstance('CtaNew');
        $ctaNew->typeStyle(1);
        $ctaNew->addTargetAvailable(
            '_popin', t('NDP_POPIN')
        );

        $ctaComposite->addInputCta($ctaDisable);
        $ctaComposite->addInputCta($ctaRef);
        $ctaComposite->addInputCta($ctaNew);

        return $ctaComposite->generate();
    }

    public static function save(Pelican_Controller $controller)
    {
        Pelican_Db::$values['ZONE_WEB'] = true;
        Pelican_Db::$values['ZONE_MOBILE'] = true;
        parent::save();

        $ctaSimple = Ndp_Cta_Factory::getInstance(Ndp_Cta::SIMPLE);
        $ctaSimple->setCtaType(self::TYPE_CTA)
            ->setMulti($controller->multi)
            ->setStyle(1)
            ->delete()
            ->save();
    }

    private static function isChildPage()
    {
        $bind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        $bind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $parentPages = explode('/', $_SESSION[APP]['CURRENT_PAGE_PATH']);
        if ($_SESSION[APP]['PAGE_ID'] != self::IS_BEING_CREATED) {
            array_pop($parentPages);
        }
        $item = [];
        if (!empty($parentPages)) {
            $templatesShowRoom = implode(',', Pelican::$config['TEMPLATE_PAGE_SHOWROOM']);
            $sql = 'SELECT
                  p.PAGE_ID
                FROM #pref#_page p
                INNER JOIN #pref#_page_version pv ON p.PAGE_ID = pv.PAGE_ID AND p.LANGUE_ID=pv.LANGUE_ID AND p.PAGE_CURRENT_VERSION =pv.PAGE_VERSION
                WHERE
                   pv.TEMPLATE_PAGE_ID  IN ('.$templatesShowRoom.')
                   AND p.PAGE_ID IN ('.implode(',', $parentPages).')
                   AND p.LANGUE_ID=:LANGUE_ID
                   AND p.SITE_ID=:SITE_ID
                LIMIT 0,1';
            $con = Pelican_Db::getInstance();
            $item = $con->queryItem($sql, $bind);
        }

        return !empty($item);
    }
}
