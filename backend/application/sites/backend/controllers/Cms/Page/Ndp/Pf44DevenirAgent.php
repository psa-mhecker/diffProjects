<?php
/**
 * Tranche PF44 - Devenir Agent.
 *
 * @author Pierre Pottié <pierre.pottie@businessdecision.com>
 *
 * @since 18/03/2015
 */
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta/Factory.php';

/**
 * Cms_Page_Ndp_Pf44DevenirAgent.
 */
class Cms_Page_Ndp_Pf44DevenirAgent extends Cms_Page_Ndp
{

    const BUSINESS_FOR_SALE    = 1;
    const AVAILABLE_LOCATION   = 2;
    const YES                  = 1;
    const NO                   = 0;
    const DEFAULT_MAX_BUSINESS = 300;
    const DEFAULT_RADIUS       = 20;
    const ENABLED              = 1;
    const DISABLED             = 0;
    const STYLE_LIEN           = 'style_niveau4';

    /**
     * Render.
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {

        $field = self::getConfigAffichage($controller);
        $return = $controller->oForm->createCheckboxAffichage($field);

        $valueAttribut = (isset($controller->zoneValues['ZONE_PARAMETERS'])) ? $controller->zoneValues['ZONE_PARAMETERS'] : 0;
        $valueAttribut = explode('#', $valueAttribut);
        $return .= $controller->oForm->createCheckBoxFromList(
            $controller->multi.'ZONE_PARAMETERS', t('NDP_SEARCH_FILTER'), array(
            self::BUSINESS_FOR_SALE => t('NDP_BUSINESS_FOR_SALE'),
            self::AVAILABLE_LOCATION => t('NDP_AVAILABLE_LOCATION'),
            ), $valueAttribut, false, $controller->readO
        );

        $targetsYesNo = array(
            self::YES => t('NDP_YES'),
            self::NO => t('NDP_NO')
        );
        self::setDefaultValueTo($controller->zoneValues, 'ZONE_ATTRIBUT', self::YES);
        $return .= $controller->oForm->createRadioFromList(
            $controller->multi.'ZONE_ATTRIBUT', t('NDP_GROUPING'), $targetsYesNo, $controller->zoneValues['ZONE_ATTRIBUT'], true, $controller->readO, 'h'
        );

        self::setDefaultValueTo($controller->zoneValues, 'ZONE_ATTRIBUT2', self::YES);
        $return .= $controller->oForm->createRadioFromList(
            $controller->multi.'ZONE_ATTRIBUT2', t('NDP_AUTOCOMPLETE'), $targetsYesNo, $controller->zoneValues['ZONE_ATTRIBUT2'], true, $controller->readO, 'h'
        );

        self::setDefaultValueTo($controller->zoneValues, 'ZONE_ATTRIBUT3', self::DEFAULT_MAX_BUSINESS);
        $return .= $controller->oForm->createInput(
            $controller->multi.'ZONE_ATTRIBUT3', t('NDP_NB_MAX_BUSINESS'), 3, 'number', true, $controller->zoneValues['ZONE_ATTRIBUT3'], $controller->readO, 4
        );

        self::setDefaultValueTo($controller->zoneValues, 'ZONE_LABEL2', self::DEFAULT_RADIUS);
        $return .= $controller->oForm->createInput(
            $controller->multi.'ZONE_LABEL2', t('NDP_RADIUS'), 3, 'number', true, $controller->zoneValues['ZONE_LABEL2'], $controller->readO, 4
        );

        $return .= $controller->oForm->createEditor(
            $controller->multi."ZONE_TEXTE", t('NDP_HOME_TEXT'), false, $controller->zoneValues["ZONE_TEXTE"], $controller->readO, true, "", 650, 150
        );


        // Affichage du lien 'En Savoir Plus" (Obligatoire)
        $aParamShowLink = array(
            self::DISABLED => t('DISABLED'),
            self::ENABLED => t('ENABLED'),
        );
        self::setDefaultValueTo($controller->zoneValues, 'ZONE_TITRE2', self::DISABLED);
        $type = 'container_enSavoirPlus';
        $jsContainerAffichage = self::addJsContainerRadio($type);
        $return .= $controller->oForm->createRadioFromList($controller->multi.'ZONE_TITRE2', t('NDP_LINK_LEARN_MORE'), $aParamShowLink, $controller->zoneValues['ZONE_TITRE2'], true, $controller->readO, 'h', false, $jsContainerAffichage);

        // CONTAINER POUR L'AFFICHAGE CLASSIQUE
        $return .= self::addHeadContainer(self::ENABLED, $controller->zoneValues['ZONE_TITRE2'], $type);
        $return .= self::getCtaAffichageClassique($controller);
        $return .= self::addFootContainer();

        return $return;
    }

    /**
     * getCtaAffichageClassique.
     *
     * @return string $return
     */
    public static function getCtaAffichageClassique(Pelican_Controller $controller)
    {
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $ctaComposite->setCta($controller->oForm, $controller->zoneValues, $controller->multi, 'AFFICHAGE_CLASSIQUE', false, (Cms_Page_Ndp::isTranslator() || $controller->readO));

        $ctaNew = Pelican_Factory::getInstance('CtaNew');
        $ctaNew->addStyleAvailable('style_niveau4' , t('NDP_STYLE_NIVEAU4'))
            ->hideStyle(true)
            ->addTargetAvailable( '_popin', t('NDP_POPIN'));
        $ctaComposite->addInputCta($ctaNew);
       
        return $ctaComposite->generate();
    }

    /**
     * 
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {
        if (!empty(Pelican_Db::$values['ZONE_PARAMETERS'])) {
            Pelican_Db::$values['ZONE_PARAMETERS'] = implode('#', Pelican_Db::$values['ZONE_PARAMETERS']);
        }
        parent::save();

        $ctaSimple = Ndp_Cta_Factory::getInstance(Ndp_Cta::SIMPLE);
        $ctaSimple->setCtaType('AFFICHAGE_CLASSIQUE')
            ->setMulti($controller->multi)
            ->setStyle(self::STYLE_LIEN)
            ->delete()
            ->save();
    }
}
