<?php

/**
 * Tranche PC84 - Catalogue Applications
 *
 * @package Pelican_BackOffice
 * @subpackage Tranche
 * @author Pierre Pottié <pierre.pottie@businessdecision.com>
 * @author Yohann Philippe <yohann.philippe@businessdecision.com>
 * @since 16/07/2015
 */
class Cms_Page_Ndp_Pc84CatalogueApplications extends Cms_Page_Ndp
{

    const SEPARATOR = '#';
    const MAX_LIMIT = 12;

    /**
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        $return = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));
        $return .= $controller->oForm->createInput(
            $controller->multi.'ZONE_TITRE', t('TITLE'), 50, "", false, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 50
        );
        $return .= $controller->oForm->createInput(
            $controller->multi.'ZONE_TITRE2', t('NDP_SOUS-TITRE'), 120, "", false, $controller->zoneValues['ZONE_TITRE2'], $controller->readO, 80
        );


        $connectedServices = self::getConnectedServices();
        $valuesSelected = explode(self::SEPARATOR, $controller->zoneValues['ZONE_TEXTE']);

        $return .= $controller->oForm->createAssocFromList(
            null, $controller->multi.'ZONE_TEXTE', t('NDP_CONNECTED_SERVICES'), $connectedServices, $valuesSelected, true, true, false, $iSize = "5", $iWidth = 200, false, "", '', 0, true, [
            'iconInfoBulle' => true,
            'messageInfoBulle' => t('NDP_MSG_CONNECTED_SERVICES_WITH_VISUAL_ONLY')
            ]
        );

        self::createJScheckLimit($controller);
        $return .= self::getCtaClassiqueWithPopin($controller);

        return $return;
    }

    /**
     * @param Pelican_Controller $controller
     *
     */
    public static function createJScheckLimit(Pelican_Controller $controller)
    {
        $controller->oForm->createJS("
       	var selected = $('#".$controller->multi."ZONE_TEXTE option').size();
         if (selected >= ".self::MAX_LIMIT.") {
                alert('".t('NDP_CONNECTED_SERVICES_MAX_LIMIT')."');
                fwFocus(eval(src".$controller->multi."ZONE_TEXTE));
                return false;
            }                
        ");
    }

    /**
     * 
     * @return array
     */
    public static function getConnectedServices()
    {
        $connection = Pelican_Db::getInstance();
        $retour = [];
        $bind = [
            ':SITE_ID' => $_SESSION[APP]['SITE_ID'],
            ':LANGUE_ID' => $_SESSION[APP]['LANGUE_ID']
        ];
        $query = 'SELECT ID,LABEL FROM #pref#_services_connect WHERE SITE_ID=:SITE_ID AND LANGUE_ID=:LANGUE_ID AND VISUEL_APPLICATION IS NOT NULL';
        $res = $connection->queryTab($query, $bind);

        foreach ($res as $connectedService) {
            $retour[$connectedService['ID']] = $connectedService['LABEL'];
        }

        return $retour;
    }

    /**
     *
     * @param Pelican_Controller
     *
     * @return string
     */
    public static function getCtaClassiqueWithPopin(Pelican_Controller $controller)
    {
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $ctaComposite->setCta(
            $controller->oForm, $controller->zoneValues, $controller->multi, 'AFFICHAGE_CLASSIQUE', false, (Cms_Page_Ndp::isTranslator() || $controller->readO)
        );
        $ctaComposite->setLabel(t('NDP_CTA'));
        $ctaDisable = Pelican_Factory::getInstance('CtaDisable');
        $ctaRef = Pelican_Factory::getInstance('CtaRef');
        $ctaRef->addTargetAvailable(
            '_popin', t('NDP_POPIN')
        );
        $ctaNew = Pelican_Factory::getInstance('CtaNew');
        $ctaNew->addTargetAvailable(
            '_popin', t('NDP_POPIN')
        );
        $ctaComposite->addInputCta($ctaDisable);
        $ctaComposite->addInputCta($ctaRef);
        $ctaComposite->addInputCta($ctaNew);

        return $ctaComposite->generate();
    }

    /**
     * 
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {


        $DB_VALUES = Pelican_Db::$values;

        Pelican_Db::$values['ZONE_TEXTE'] = implode(self::SEPARATOR, Pelican_Db::$values['ZONE_TEXTE']);
        parent::save();

        $ctaSimple = Ndp_Cta_Factory::getInstance(Ndp_Cta::SIMPLE);
        $ctaSimple->setCtaType('AFFICHAGE_CLASSIQUE')
            ->setMulti($controller->multi)
            ->delete()
            ->save();

        Pelican_Db::$values = $DB_VALUES;
    }
}
