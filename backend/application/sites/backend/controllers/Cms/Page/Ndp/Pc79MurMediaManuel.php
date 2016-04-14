<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi/Factory.php';

/**
 *
 */
class Cms_Page_Ndp_Pc79MurMediaManuel extends Cms_Page_Ndp
{
    const DISABLE         = 0;
    const ENABLE          = 1;
    const TYPE_LINK       = "LinkContainer";
    const TYPE_MEDIA      = "MediaContainer";
    const TYPE_OF_MULTI   = 'MUR_MEDIA_MANUEL';
    const TYPE_IMAGE      = "Image";
    const RATIO_VISUEL = 'NDP_MEDIA_MANUAL_WALL_SMALL_VISUAL';
    const BIG_RATIO_VISUEL = 'NDP_MEDIA_MANUAL_WALL_BIG_VISUAL';
    const RATIO_VISUEL_MOBILE = 'NDP_MURMEDIA_SMALL_16_9';

    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        $return = self::getHeaderOfForm($controller);
        $return .= self::getGalleryOfForm($controller);
        $return .= self::getLinkOfForm($controller);

        return $return;
    }

    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function getHeaderOfForm(Pelican_Controller $controller)
    {
        $return = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));

        $infoBulle = [
            'isIcon'  => true,
            'message' => t('NDP_MSG_PRECO_UPPERCASE_TITLE')
        ];

        $return .= $controller->oForm->createInput(
            $controller->multi.'ZONE_TITRE',
            t('TITLE'),
            60,
            "",
            false,
            $controller->zoneValues['ZONE_TITRE'],
            $controller->readO,
            60,
            false,
            "",
            "text",
            array(),
            false,
            $infoBulle,
            array('message'=> '60'.t('NDP_MAX_CAR'))
        );

        return $return;
    }

    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function getGalleryOfForm(Pelican_Controller $controller)
    {
        $isZoneDynamique = Ndp_Multi::isZoneDynamique($controller->zoneValues['ZONE_TEMPLATE_ID']);
        $multi           = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC, $isZoneDynamique);
        $listOfMedias    = $multi->setMultiType(self::TYPE_OF_MULTI)
            ->hydrate($controller->zoneValues)
            ->getValues();

        $return = $controller->oForm->createMultiHmvc(
            $controller->multi.self::TYPE_OF_MULTI,
            "",
            array(
                "path" => __FILE__,
                "class" => __CLASS__,
                "method" => "addMediaIntoMulti"
            ),
            $listOfMedias,
            self::TYPE_OF_MULTI,
            (Cms_Page_Ndp::isTranslator() || $controller->readO),
            array(6, 6),
            false,
            false,
            $controller->multi.self::TYPE_OF_MULTI,
            "values",
            "multi",
            "2",
            "",
            "",
            false,
            ['noDragNDrop' => Cms_Page_Ndp::isTranslator()]
        );

        return $return;
    }

    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function getLinkOfForm(Pelican_Controller $controller)
    {
        $type           = $controller->multi.self::TYPE_LINK;
        $optionsForLink = array(
            self::DISABLE => t('NDP_DESACTIVE'),
            self::ENABLE => t('NDP_ACTIVE'),
        );
        if (empty($controller->zoneValues['ZONE_PARAMETERS'])) {
            $controller->zoneValues['ZONE_PARAMETERS'] = self::DISABLE;
        }
        $js = self::addJsContainerRadio($type);
        $return = $controller->oForm->createRadioFromList($controller->multi.'ZONE_PARAMETERS',
            t('NDP_LINK'), $optionsForLink,
            $controller->zoneValues['ZONE_PARAMETERS'], true,
            (Cms_Page_Ndp::isTranslator() || $controller->readO), 'h', false, $js);
        $return .= self::addHeadContainer(self::ENABLE,
                $controller->zoneValues['ZONE_PARAMETERS'], $type);

        $return .= $controller->oForm->createInput($controller->multi.'ZONE_TITRE2',
            t('NDP_LABEL'), 255, "", true,
            $controller->zoneValues['ZONE_TITRE2'], (Cms_Page_Ndp::isTranslator() || $controller->readO), 60);

        $return .= $controller->oForm->createInput($controller->multi.'ZONE_URL',
            t('URL'), 255, "internallink", true,
            $controller->zoneValues['ZONE_URL'], (Cms_Page_Ndp::isTranslator() || $controller->readO), 60);

        $return .= self::addFootContainer();

        return $return;
    }

    /**
     *
     * @param Ndp_Form $form
     * @param array $values
     * @param bool $readO
     * @param string $multi
     *
     * @return string
     */
    public static function addMediaIntoMulti(Ndp_Form $form, $values, $readO, $multi)
    {
        $ratio = self::RATIO_VISUEL_MOBILE; // le mobile est plus grand que le desktop donc on cherche cette taille de visuel
        $crops = [t('DESKTOP') => self::RATIO_VISUEL, t('MOBILE') => self::RATIO_VISUEL_MOBILE];
        // le premier visuel est plus grand
        if (isset($values['CPT_POS_MULTI']) &&  0 == $values['CPT_POS_MULTI'] ) {
            $ratio= self::BIG_RATIO_VISUEL;
            $crops = [t('DESKTOP') => self::BIG_RATIO_VISUEL, t('MOBILE') => self::RATIO_VISUEL_MOBILE];
        }

        return  $form->createNewImage(
            $multi.'MEDIA_ID',
            t('NDP_VISUEL'),
            true,
            $values['MEDIA_ID'],
            (Cms_Page_Ndp::isTranslator() || $readO),
            false,
            $ratio,
            $crops

        );
    }


    /*
     *
     * @param Pelican_Controller $controller
     *
     */
    public static function save(Pelican_Controller $controller)
    {
        if (self::DISABLE === Pelican_Db::$values['ZONE_PARAMETERS']) {
            unset(Pelican_Db::$values['ZONE_LABEL']);
            unset(Pelican_Db::$values['ZONE_URL']);
        }
        parent::save();
        $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC);
        $multi->setMultiType(self::TYPE_OF_MULTI)
            ->setMulti($controller->multi)
            ->delete()
            ->save();
    }

}
