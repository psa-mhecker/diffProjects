<?php
/**
 * Tranche PC77 - Dimension du Vehicule.
 *
 * @author Kevin Vignon <kevin.vignon@businessdecision.com>
 *
 * @since 02/03/2015
 */
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi/Factory.php';

class Cms_Page_Ndp_Pc77DimensionVehicule extends Cms_Page_Ndp
{
    const MULTI_TYPE = "VISUELS";
    const RATIO_VISUEL = 'NDP_MEDIA_16_9';
    const RATIO_VISUEL_MOBILE = 'NDP_MURMEDIA_SMALL_16_9';
    const RATIO_VISUEL_MINIATURE = 'NDP_MEDIA_DIMENSION_THUMBNAIL';

    public static function render(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();

        $field = self::getConfigAffichage($controller);
        $return = $controller->oForm->createCheckboxAffichage($field);
        $return .= $controller->oForm->createInput(
            $controller->multi.'ZONE_TITRE',
            t('TITRE'),
            60,
            "text",
            false,
            $controller->zoneValues['ZONE_TITRE'],
            $controller->readO,
            60
        );
        $return .= $controller->oForm->createInput(
            $controller->multi."ZONE_TITRE2", t('SOUS_TITRE'), 60, "text", false,
            $controller->zoneValues["ZONE_TITRE2"], $controller->read0, 60
        );

        $isZoneDynamique = Ndp_Multi::isZoneDynamique($controller->zoneValues['ZONE_TEMPLATE_ID']);
        $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC, $isZoneDynamique);
        $visuels = $multi->setMultiType(self::MULTI_TYPE)
            ->hydrate($controller->zoneValues)
            ->getValues();

        $infobull = [
            'isIcon'  => true,
            'message' => t('NDP_2_TO_4_VISUALS')
        ];
        $return .= $controller->oForm->createComment(t('NDP_VISUELS'), array('infoBulle'=>$infobull));

        $return .= $controller->oForm->createMultiHmvc(
            $controller->multi.self::MULTI_TYPE,
            t('NDP_ADD_VISUEL'),
            array(
                "path" => __FILE__,
                "class" => __CLASS__,
                "method" => "addVisuel",
            ),
            $visuels,
            $controller->multi.self::MULTI_TYPE,
            (Cms_Page_Ndp::isTranslator() || $controller->readO),
            [2,4],
            true,
            true,
            $controller->multi.self::MULTI_TYPE,
            '',
            '',
            '2',
            '',
            '',
            false,
            ['noDragNDrop' => Cms_Page_Ndp::isTranslator()]

        );
        $return .= $controller->oForm->createTextArea(
            $controller->multi.'ZONE_TEXTE',
            t('NDP_TERMS_CONDITIONS'), false, $controller->zoneValues['ZONE_TEXTE'], 500,
            $controller->read0, 10,  70, false);


        return $return;
    }

    public static function addVisuel(Ndp_Form $form, $values, $readO, $multi)
    {
        $return = '';
        $return .= $form->createNewImage(
            $multi.'MEDIA_ID',
            t('VISUEL'),
            true,
            $values['MEDIA_ID'],
            (Cms_Page_Ndp::isTranslator() || $readO),
            false,
            self::RATIO_VISUEL,
            [t('DESKTOP') => self::RATIO_VISUEL, t('MOBILE') => self::RATIO_VISUEL_MOBILE]
        );

        $return .= $form->createNewImage(
            $multi.'MEDIA_ID2',
            t('NDP_MINIATURE'),
            false,
            $values['MEDIA_ID2'],
            (Cms_Page_Ndp::isTranslator() || $readO),
            false,
            self::RATIO_VISUEL_MINIATURE,
            [t('DESKTOP') => self::RATIO_VISUEL_MINIATURE]
        );

        $return .= $form->createInput(
            $multi.'PAGE_ZONE_MULTI_TITRE',
            t('NDP_MINIATURE_TEXT'),
            25,
            "text",
            false,
            $values['PAGE_ZONE_MULTI_TITRE'],
            $readO,
            30
        );
        $return .= $form->createEditor(
            $multi.'PAGE_ZONE_MULTI_TEXT',
            t('NDP_TEXTE'),
            false,
            $values["PAGE_ZONE_MULTI_TEXT"],
            $readO,
            true,
            "",
            650,
            150
        );

        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
        $DB_VALUES = Pelican_Db::$values;
        parent::save();
        $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC);
        $multi->setMultiType(self::MULTI_TYPE)
            ->setMulti($controller->multi)
            ->delete()
            ->save();
        Pelican_Db::$values = $DB_VALUES;
    }
}
