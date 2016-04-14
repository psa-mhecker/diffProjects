<?php

/**
 * Tranche PF23 - Rangebar
 *
 * @author Joseph FRANCLIN <hamdi.afrit@businessdecision.com>
 *
 * @since 25/02/2016
 */

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi/Factory.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta/Factory.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cache/Services/CacheService.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cache/Services/RedisService.php';

/**
 * Cms_Page_Ndp_Pf23Rangebar
 */
class Cms_Page_Ndp_Pf23Rangebar extends Cms_Page_Ndp
{
    const RANGE_BAR = 'RANGE_BAR';
    const TEXT_LIMIT = '25';

    /**
     * Render.
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();

        $form = $controller->oForm->createInput(
            $controller->multi.'ZONE_TITRE',
            t('NDP_LABEL_FOR_MOBILE_ONLY'),
            self::TEXT_LIMIT,
            'text',
            true,
            $controller->zoneValues['ZONE_TITRE'],
            $controller->readO,
            40,
            false,
            '',
            'text',
            [],
            false,
            ''
        );

        $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC);

        $RangeBarValues = $multi
            ->setMultiType(self::RANGE_BAR)
            ->hydrate($controller->zoneValues)
            ->getValues();

        $form .= $controller->oForm->createMultiHmvc(
            $controller->multi.self::RANGE_BAR,
            t('NDP_ADD_RANGE_BAR'),
            array(
                "path" => __FILE__,
                "class" => __CLASS__,
                "method" => "addRangeBar",
            ),
            $RangeBarValues,
            $controller->multi.self::RANGE_BAR,
            $controller->readO,
            array(1,14),
            !Cms_Page_Ndp::isTranslator(),
            true,
            $controller->multi.self::RANGE_BAR,
            'values',
            'multi',
            2,
            '',
            '',
            false,
            [
                'noDragNDrop' => Cms_Page_Ndp::isTranslator(),
            ]
        );

        return $form;

    }

    /**
     * @param Ndp_Form $form
     * @param $values
     * @param $readO
     * @param $multi
     *
     * @return string
     */
    public static function addRangeBar(Ndp_Form $form, $values, $readO, $multi){

        $return = $form->createInput(
            $multi.'PAGE_ZONE_MULTI_TITRE',
            t('TITLE'),
            self::TEXT_LIMIT,
            'text',
            true,
            $values['PAGE_ZONE_MULTI_TITRE'],
            $readO,
            40,
            false,
            '',
            'text',
            [],
            false,
            ''
        );

        $infoBulleUrl = array(
            'isIcon' =>  true,
            'message' => t('NDP_MSG_URL_1ER_LIEN'),
        );

        $return .= $form->createInput(
            $multi.'PAGE_ZONE_MULTI_URL',
            t('URL'),
            255,
            "internallink",
            true,
            $values['PAGE_ZONE_MULTI_URL'],
            (Cms_Page_Ndp::isTranslator() || $readO),
            75,
            false,
            '',
            'text',
            array(),
            false,
            $infoBulleUrl
        );

        return $return;
    }

     /**
     * 
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {
        $values = Pelican_Db::$values;

        Pelican_Db::$values['ZONE_MOBILE'] = 1;
        Pelican_Db::$values['ZONE_WEB'] = 1;

        parent::save();

        Pelican_Db::$values = $values;

        $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC);
        $multi->setMultiType(self::RANGE_BAR)
            ->setMulti($controller->multi)
            ->delete()
            ->save();

        Pelican_Db::$values = $values;
    }
}
