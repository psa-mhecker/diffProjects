<?php
/**
 * Tranche PC36 - faq
 *
 * @package Pelican_BackOffice
 * @subpackage Tranche
 * @author Laurent Franchomme <laurent.franchomme@businessdecision.com>
 * @since 02/06/2015
 */

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';

class Cms_Page_Ndp_Pc36FAQ extends Cms_Page_Ndp
{

    const FAQ_GLOBAL = 1;
    const FAQ_FOCUS = 2;
    const CTA_TYPE = "FOOTER";
    const CONTENT_CATEGORY_FOCUS_CHECKED = 1;
    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();

        if (!isset($controller->zoneValues['ZONE_WEB'])) {
            $controller->zoneValues['ZONE_WEB'] = 0;
        }
        if (!isset($controller->zoneValues['ZONE_MOBILE'])) {
            $controller->zoneValues['ZONE_MOBILE'] = 0;
        }
        $return = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));

        $zoneToValues = array(
          self::FAQ_GLOBAL => t('NDP_FAQ_GLOBAL')
        );
        if (count(self::getContentCategory()) > 0) {
            $zoneToValues[self::FAQ_FOCUS] = t('NDP_FAQ_FOCUS');
        }

        $return .= $controller->oForm->createRadioFromList($controller->multi.'ZONE_ATTRIBUT', t('NDP_ZONE_USED_TO'), $zoneToValues, $controller->zoneValues['ZONE_ATTRIBUT'], true, $controller->readO);
        if (!array_key_exists(self::FAQ_FOCUS, $zoneToValues)) {
            $return .= $controller->oForm->createLabel('', t('NDP_MSG_FOCUS_NEEDED'));
        }

        $return .= $controller->oForm->createInput($controller->multi.'ZONE_TITRE', t('NDP_SUBTITLE_FAQ'), 60, "", true, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 75, false, '', 'text', [], false, '', "60 ".t('NDP_MAX_CAR'));

        $return .= $controller->oForm->createEditor($controller->multi.'ZONE_TEXTE', t('NDP_FAQ_RESPONSE_SATISFACTION_WHEN_NO_SELECTED'), true, $controller->values['ZONE_TEXTE'], $controller->readO, true, 650, 150);
        $return .= self::getCtaAffichage($controller);

           return $return;
    }

    /**
     * @param Pelican_Controller $controller
     *
     * @return mixed
     */
    public static function getCtaAffichage(Pelican_Controller $controller)
    {
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $ctaComposite->setCta(
            $controller->oForm,
            $controller->zoneValues,
            $controller->multi,
            '',
            false,
            (Cms_Page_Ndp::isTranslator() || $controller->readO)
        )
          ->setLabel(t('NDP_CTA_FOOTER'));
        $ctaDisable = Pelican_Factory::getInstance('CtaDisable');
        $ctaRef = Pelican_Factory::getInstance('CtaRef');
        $ctaRef->addTargetAvailable(
          '_popin',
          t('NDP_POPIN')
        );
        $ctaNew = Pelican_Factory::getInstance('CtaNew');
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
        $ctaHmvc->delete()
          ->save();
    }

    /**
     * Méthode statique récupérant les enregistrements présents dans content_category
     *
     * @param Pelican_Controller $controller Objet controller
     * @param NULL/string        $contentMediaType
     *
     * @return array Tableau des medias
     */
    public static function getContentCategory()
    {
        $return = array();
        $connection = Pelican_Db::getInstance();

        $bind = array(
            ':LANGUE_ID' => $_SESSION[APP]['LANGUE_ID'],
            ':SITE_ID' => $_SESSION[APP]['SITE_ID'],
            ':CONTENT_CATEGORY_CODE' => $connection->strToBind('FAQ_CAT'),
            ':CONTENT_CATEGORY_ATTRIBUT' => self::CONTENT_CATEGORY_FOCUS_CHECKED
        );

        $sql = 'SELECT
                        *
                    FROM
                        #pref#_content_category
                    WHERE
                        LANGUE_ID = :LANGUE_ID
                        AND SITE_ID = :SITE_ID
                        AND CONTENT_CATEGORY_CODE = :CONTENT_CATEGORY_CODE
                        AND CONTENT_CATEGORY_ATTRIBUT = :CONTENT_CATEGORY_ATTRIBUT
                    ';

        $return = $connection->queryTab($sql, $bind);

        return $return;
    }

}
