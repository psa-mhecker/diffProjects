<?php

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';

/**
 * Tranche PC - Contenu Accessoires
 *
 * @package Pelican_BackOffice
 * @subpackage Tranche
 * @author Kevin Vignon <kevin.vignon@businessdecision.com>
 * @since 26/05/2015
 */
class Cms_Page_Ndp_Pc83ContenuAccessoires extends Cms_Page_Ndp
{
    const AOA = 6;

    /**
     * Render.
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        $wsState = Ndp_Controller::getWsState(self::AOA);
        $options['message'] = 60 .t('NDP_MAX_CAR');

        if (!$wsState) {
            $controller->zoneValues['ZONE_WEB_READO']    = true;
            $controller->zoneValues['ZONE_MOBILE_READO'] = true;
            $options['attributes'] =  ['disabled'=>'disabled', 'readonly'=>'readonly'];
        }
        $return  = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));
        $return .= self::createMessages($controller, $wsState);

        $return .= $controller->oForm->createInput($controller->multi.'ZONE_TITRE', t('TITLE'), 60, "", true, $controller->zoneValues['ZONE_TITRE'], false, 75, false, '', 'text', [], false, array('isIcon'=> true,'message'=> t('NDP_MSG_PRECO_UPPERCASE_TITLE')), $options);
        $return .= self::getCtaAffichage($controller, !$wsState);
        $js = '';
        if ($wsState) {
            $js = self::getJsForCheckingModelAccessory($controller);
        }

        return $return.$js;
    }

    /**
     *
     * @param Pelican_Controller $controller
     * @param boolean $wsState
     * 
     * @return string
     */
    public static function createMessages(Pelican_Controller $controller, $wsState = false)
    {
        $return = '';
        if (!$wsState) {
            $return .= $controller->oForm->createComment(t("NDP_PRESENTATION_ACCESSORIES_AOA_NEEDED"), array('noBold' => true));
        }
        if ($wsState) {
            $return .= $controller->oForm->createComment(t('NDP_MSG_ERROR_NO_SILHOUETTE'), array('noBold' => true, "idForLabel" => $controller->multi.self::idForComment));
        }
        if ($wsState) {
            $return .= $controller->oForm->createComment(t("NDP_ACCESSORY_PARAMETRE"), array('noBold' => true));
        }

        return $return;
    }
    /**
     * @param Pelican_Controller $controller
     * @param boolean            $disabled
     *
     * @return mixed
     */
    public static function getCtaAffichage(Pelican_Controller $controller, $disabled)
    {
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $ctaComposite->setCta($controller->oForm, $controller->zoneValues, $controller->multi, '', false, (Cms_Page_Ndp::isTranslator() || $controller->readO));
        $ctaComposite->setDisabled($disabled);
        $ctaDisable = Pelican_Factory::getInstance('CtaDisable');
        $ctaRef = Pelican_Factory::getInstance('CtaRef');
        $ctaRef->typeStyle(1);
        $ctaRef->addTargetAvailable(
            '_popin',
            t('NDP_POPIN')
        );
        $ctaNew = Pelican_Factory::getInstance('CtaNew');
        $ctaNew->typeStyle(1);
        $ctaNew->addTargetAvailable(
            '_popin',
            t('NDP_POPIN')
        );
        $ctaComposite->addInputCta($ctaDisable);
        $ctaComposite->addInputCta($ctaRef);
        $ctaComposite->addInputCta($ctaNew);

        return $ctaComposite->generate();
    }

    /**
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {
        parent::save();
        $ctaHmvc = Ndp_Cta_Factory::getInstance(Ndp_Cta::SIMPLE);
        $ctaHmvc->setStyle(Pelican_Db::$values['ZONE_LABEL'])
            ->delete()
            ->save();
    }
}

