<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi/Factory.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta/Factory.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cache/Services/CacheService.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cache/Services/RedisService.php';

/**
 *
 */
class Cms_Page_Ndp_Pc19Slideshow extends Cms_Page_Ndp
{
    const VISUEL       = 'VISUEL';
    const VIDEO        = 'VIDEO';
    const VISUEL_WEB   = 'VISUEL_WEB';
    const SHOW         = 1;
    const TIMER_SPEED  = 5;
    const WHITE_COLOR  = 'white';
    const GREY_COLOR   = 'darkBlue';
    const RATIO_VISUEL = 'NDP_PF2_DESKTOP';
    const CROP_DESKTOP = 'NDP_PF2_DESKTOP';
    const CROP_MOBILE  = 'NDP_GENERIC_4_3_640';

    /**
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();

        $controller->zoneValues['ZONE_WEB']    = (isset($controller->zoneValues['ZONE_WEB'])) ? $controller->zoneValues['ZONE_WEB'] : self::SHOW;
        $controller->zoneValues['ZONE_MOBILE'] = (isset($controller->zoneValues['ZONE_MOBILE'])) ? $controller->zoneValues['ZONE_MOBILE'] : self::SHOW;

        $controller->zoneValues['ZONE_WEB_EVENT']    = self::addJsContainerCheckBoxAffichage($controller->multi.self::VISUEL.self::VISUEL_WEB);

        $form = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));

        if (empty($controller->zoneValues['ZONE_TIMER_SPEED'])) {
            $controller->zoneValues['ZONE_TIMER_SPEED'] = self::TIMER_SPEED;
        }

        $form .= $controller->oForm->createInput($controller->multi.'ZONE_TIMER_SPEED',
            t('NDP_TIMING_SLIDE'), 255, 'number', true,
            $controller->zoneValues['ZONE_TIMER_SPEED'], $controller->readO, 10);

        $isZoneDynamique = Ndp_Multi::isZoneDynamique($controller->zoneValues['ZONE_TEMPLATE_ID']);

        $multi           = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC, $isZoneDynamique);

        $slides = $multi->setMultiType(self::VISUEL)
            ->hydrate($controller->zoneValues)
            ->getValues();

        $paramTypeAffichage = [
            self::VISUEL => t('NDP_VISUEL'),
            self::VIDEO => t('NDP_VIDEO'),
        ];

        $type = $controller->multi.self::VISUEL;

        $js   = self::addJsContainerRadio($type);

        self::setDefaultValueTo($controller->zoneValues, 'ZONE_PARAMETERS', self::VISUEL);

        $form .= $controller->oForm->createRadioFromList($controller->multi.'ZONE_PARAMETERS', t('TYPE_AFFICHAGE'), $paramTypeAffichage, $controller->zoneValues['ZONE_PARAMETERS'], true, $controller->readO, 'h', false, $js);

        $form .= self::addHeadContainer(self::VISUEL, $controller->zoneValues['ZONE_PARAMETERS'], $type);

        $form .= $controller->oForm->createMultiHmvc($controller->multi.self::VISUEL,
            t('NDP_ADD_SLIDE'),
            array(
                "path" => __FILE__,
                "class" => __CLASS__,
                "method" => "addSlide"
            ),
            $slides,
            $controller->multi.self::VISUEL,
            (Cms_Page_Ndp::isTranslator() || $controller->readO),
            array(1, 6),
            true,
            true,
            $controller->multi.self::VISUEL,
            '',
            '',
            2,
            '',
            '',
            '',
            $options = [
                'noDragNDrop' => Cms_Page_Ndp::isTranslator(),
            ]
        );

        $form .= self::addFootContainer();

        $form .= self::addHeadContainer(self::VIDEO, $controller->zoneValues['ZONE_PARAMETERS'], $type);
        $form .= self::addVIDEO($controller);
        $form .= self::addFootContainer();

        return $form;
    }

    /**
     * @param Ndp_Form $form
     * @param array    $values
     * @param boolean  $readO
     * @param string   $multi
     *
     * @return string
     */
    public static function addSlide(Ndp_Form $form, $values, $readO, $multi)
    {
        $return = $form->createNewImage(
            $multi.'MEDIA_ID',
            t('NDP_IMAGE'),
            true,
            $values['MEDIA_ID'],
            (Cms_Page_Ndp::isTranslator() || $readO),
            false,
            self::RATIO_VISUEL,
            [t('DESKTOP') => self::CROP_DESKTOP, t('MOBILE') => self::CROP_MOBILE]
        );

        $position = array(
            '' => t('POPUP_LABEL_LEFT'),
            'right' => t('POPUP_LABEL_RIGHT'),
        );

        $return .= $form->createRadioFromList(
            $multi.'PAGE_ZONE_MULTI_VALUE',
            t('NDP_POS_TITLE_SUBTITLE_CTA'),
            $position,
            $values['PAGE_ZONE_MULTI_VALUE'],
            true,
            $readO,
            'h',
            false
        );

        $color = array(
            self::WHITE_COLOR => t('NDP_WHITE'),
            self::GREY_COLOR => t('NDP_DARK_BLUE'),
        );

        self::setDefaultValueTo($values, 'PAGE_ZONE_MULTI_VALUE2', self::WHITE_COLOR);

        $return .= $form->createComboFromList(
            $multi.'PAGE_ZONE_MULTI_VALUE2',
            t('NDP_COLOR_TITLE_SUBTITLE'),
            $color,
            $values['PAGE_ZONE_MULTI_VALUE2'],
            true,
            $readO,
            1,
            false,
            false,
            false
        );


        $return .= $form->createInput(
            $multi.'PAGE_ZONE_MULTI_TITRE',
            t('TITLE'),
            50,
            '',
            false,
            $values['PAGE_ZONE_MULTI_TITRE'],
            $readO,
            40,
            false,
            '',
            'text',
            [],
            false,
            '',
            50 .t('NDP_MAX_CAR')
        );

        $return .= $form->createInput(
            $multi.'PAGE_ZONE_MULTI_TITRE2',
            t('NDP_SOUS_TITRE'),
            50,
            '',
            false,
            $values['PAGE_ZONE_MULTI_TITRE2'],
            $readO,
            40,
            false,
            '',
            'text',
            [],
            false,
            '',
            50 .t('NDP_MAX_CAR')
        );

        $return .= self::addCtaMulti($form, $values, $multi, $readO);

        return $return;
    }

    /**
     * @param Pelican_Controller $controller
     *
     * @return mixed
     */
    public static function addVIDEO(Pelican_Controller $controller)
    {
        $return = $controller->oForm->showSeparator();

        $return .= $controller->oForm->createMedia(
            $controller->multi.'MEDIA_ID',
            t('NDP_VIDEO'),
            true,
            'streamlike',
            '',
            $controller->zoneValues['MEDIA_ID'],
            (Cms_Page_Ndp::isTranslator() || $controller->readO),
            true,
            false
        );

        $position = array(
            '' => t('POPUP_LABEL_LEFT'),
            'right' => t('POPUP_LABEL_RIGHT'),
        );

        $return .= $controller->oForm->createRadioFromList(
            $controller->multi.'ZONE_TOOL',
            t('NDP_POS_TITLE_SUBTITLE_CTA'),
            $position,
            $controller->zoneValues['ZONE_TOOL'],
            true,
            $controller->readO,
            'h',
            false
        );

        $color = array(
            self::WHITE_COLOR => t('NDP_WHITE'),
            self::GREY_COLOR => t('NDP_DARK_BLUE'),
        );

        self::setDefaultValueTo($controller->zoneValues, 'ZONE_TOOL2', self::WHITE_COLOR);

        $return .= $controller->oForm->createComboFromList(
            $controller->multi.'ZONE_TOOL2',
            t('NDP_COLOR_TITLE_SUBTITLE'),
            $color,
            $controller->zoneValues['ZONE_TOOL2'],
            true,
            $controller->readO,
            1,
            false,
            false,
            false
        );

        $return .= $controller->oForm->createInput(
            $controller->multi.'ZONE_TITRE',
            t('TITLE'),
            50,
            '',
            false,
            $controller->zoneValues['ZONE_TITRE'],
            $controller->readO,
            40,
            false,
            '',
            'text',
            [],
            false,
            '',
            50 .t('NDP_MAX_CAR')
        );

        $return .= $controller->oForm->createInput(
            $controller->multi.'ZONE_TITRE2',
            t('NDP_SOUS_TITRE'),
            50,
            '',
            false,
            $controller->zoneValues['ZONE_TITRE2'],
            $controller->readO,
            40,
            false,
            '',
            'text',
            [],
            false,
            '',
            50 .t('NDP_MAX_CAR')
        );

        $return .= self::addCtaSimple($controller->oForm, $controller->zoneValues, $controller->multi, $controller->readO);

        return $return;
    }

    /**
     * @param Ndp_Form $form
     * @param array    $values
     * @param string   $multi
     *
     * @return string
     */
    public function addCtaMulti(Ndp_Form $form, $values, $multi, $readO)
    {
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $ctaComposite->setCta($form, $values, $multi, self::VISUEL,
            false, (Cms_Page_Ndp::isTranslator() || $readO), Ndp_Cta::SIMPLE_INTO_MULTI_HMVC, 'Ndp_Multi');
        $ctaDisable   = Pelican_Factory::getInstance('CtaDisable');
        $ctaDisable->setCtaLabel('NDP_URL_ON_IMAGE');
        $ctaRef       = Pelican_Factory::getInstance('CtaRef');
        $ctaRef->addStyleAvailable('style_niveau4', t('NDP_STYLE_NIVEAU4'));
        $ctaComposite->addInputCta($ctaDisable);
        $ctaComposite->addInputCta($ctaRef);

        return $ctaComposite->generate();
    }

    /**
     * @param Ndp_Form $form
     * @param array    $values
     * @param string   $multi
     *
     * @return string
     */
    public function addCtaSimple(Ndp_Form $form, $values, $multi, $readO)
    {
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $ctaComposite->setCta($form, $values, $multi, self::VIDEO, false, (Cms_Page_Ndp::isTranslator() || $readO));
        $ctaDisable   = Pelican_Factory::getInstance('CtaDisable');
        $ctaDisable->setCtaLabel('NDP_URL_ON_IMAGE');
        $ctaRef       = Pelican_Factory::getInstance('CtaRef');
        $ctaRef->addStyleAvailable('style_niveau4', t('NDP_STYLE_NIVEAU4'));
        $ctaComposite->addInputCta($ctaDisable);
        $ctaComposite->addInputCta($ctaRef);

        return $ctaComposite->generate();
    }

    public static function save(Pelican_Controller $controller)
    {
        Backoffice_Form_Helper::saveFormAffichage();
        self::$con = Pelican_Db::getInstance();
        $saved     = Pelican_Db::$values;
        if ($saved['ZONE_PARAMETERS'] == self::VISUEL){
            unset(Pelican_Db::$values['MEDIA_ID']);
        }
        parent::save();
        Pelican_Db::$values = $saved;
        if ($saved['ZONE_PARAMETERS'] == self::VISUEL){

            $ctaSlideShow = Ndp_Cta_Factory::getInstance(Ndp_Cta::SIMPLE_INTO_MULTI_HMVC);
            $ctaSlideShow->setCtaType(self::VISUEL);
            $multi        = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC);
            $multi->setMultiType(self::VISUEL)
                ->setMulti($controller->multi)
                ->addChild($ctaSlideShow)
                ->delete()
                ->save();
        }
        else{
            $ctaSimple = Ndp_Cta_Factory::getInstance(Ndp_Cta::SIMPLE);
            $ctaSimple->setCtaType(self::VIDEO)
                ->setMulti($controller->multi)
                ->delete()
                ->save();
        }


        Pelican_Db::$values = $saved;
    }
}
