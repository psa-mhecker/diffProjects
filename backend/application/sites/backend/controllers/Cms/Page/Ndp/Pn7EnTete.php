<?php

/**
 * Tranche PN7 - En tete.
 *
 * @author David Moate <david.moate@businessdecision.com>
 *
 * @since 26/02/2015
 */
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta/Factory.php';

class Cms_Page_Ndp_Pn7EnTete extends Cms_Page_Ndp
{
    const AFFICHAGE_CLASSIQUE = '1';
    const AFFICHAGE_VISUEL = '2';
    const NDP_VISUEL_16_9 = 1;
    const NDP_VIDEO = 2;
    const RATIO_VISUEL = 'NDP_PF2_DESKTOP';
    const RATIO_VISUEL_MOBILE = 'NDP_GENERIC_4_3_640';


    public static function render(Pelican_Controller $controller)
    {
        // Affichage WEB / MOBILE
        // vérifier si c'est la page showroom alors l'affichage web sera décocher par défaut
        if (
            (isset($_GET['gid']) && ($_GET['gid'] == Pelican::$config['TEMPLATE_PAGE_SHOWROOM'][0]))
            || (isset($_GET['tpl']) && ($_GET['tpl'] == Pelican::$config['TEMPLATE_PAGE_SHOWROOM'][0]))
            )
        {
            self::setDefaultValueTo($controller->zoneValues, 'ZONE_WEB', 0);
        }

        $return  = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));
        // Affichage du titre de la page (Obligatoire)
        $return .= $controller->oForm->createInput($controller->multi.'ZONE_TITRE', t('TITLE'), 60, "", true, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 75, false, '', 'text', [], false, '', 60 .t('NDP_MAX_CAR'));

        // Permet de savoir si on sticke ou pas le titre de la page
        $return .= $controller->oForm->createCheckBoxFromList($controller->multi.'ZONE_TITRE2', t('NDP_MSG_STICKER_TITRE_PAGE'), array(1 => ""), $controller->zoneValues['ZONE_TITRE2'], false, (Cms_Page_Ndp::isTranslator() || $controller->readO));

        // Par défaut on set le mode d'affichage a classique
        if (empty($controller->zoneValues['ZONE_TITRE3'])) {
            $controller->zoneValues['ZONE_TITRE3'] = 1;
        }
        // Format d'affichage de l'entête (Obligatoire)
        $aParamFormatAffichage = array(
            1 => t('CLASSIQUE'),
            2 => t('VISUEL_+_TEXTE'),
        );
        $type = $controller->multi. 'container_affichage';
        $jsContainerAffichage = self::addJsContainerRadio($type);
        $options = [ 'infoBull' => ['isIcon'=>true, 'message'=>t('NDP_INFO_CLASSIQUE') . "\n" . t('NDP_INFO_VISUEL_TEXTE')] ];
        $return .= $controller->oForm->createRadioFromList($controller->multi.'ZONE_TITRE3', t('NDP_FORMAT_AFFICHAGE'), $aParamFormatAffichage, $controller->zoneValues['ZONE_TITRE3'], true, (Cms_Page_Ndp::isTranslator() || $controller->readO), 'h', false, $jsContainerAffichage, NULL, $options);
        // CONTAINER POUR L'AFFICHAGE CLASSIQUE
        $return .= self::addHeadContainer(self::AFFICHAGE_CLASSIQUE, $controller->zoneValues['ZONE_TITRE3'], $type);
        $return .= self::getCtaAffichageClassique($controller);
        $return .= self::addFootContainer();
        //FIN CONTAINER POUR L'AFFICHAGE CLASSIQUE
        // CONTAINER POUR L'AFFICHAGE VISUEL + TEXTE
        $return .= self::addHeadContainer(self::AFFICHAGE_VISUEL, $controller->zoneValues['ZONE_TITRE3'], $type);
        $return .= self::getContainerVisuelText($controller);
        $return .= self::addFootContainer();
        //FIN CONTAINER POUR L'AFFICHAGE VISUEL + TEXTE

        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {    
        if (self::NDP_VIDEO == Pelican_Db::$values['ZONE_TITRE5']) {
            Pelican_Db::$values['MEDIA_ID'] = Pelican_Db::$values['MEDIA_ID2'];
        }
        unset(Pelican_Db::$values['MEDIA_ID2']);
        if(Pelican_Db::$values['ZONE_TITRE3'] == self::AFFICHAGE_CLASSIQUE){
            unset(Pelican_Db::$values['MEDIA_ID2']);
            unset(Pelican_Db::$values['MEDIA_ID']);
        }
        parent::save();

        if(Pelican_Db::$values['ZONE_TITRE3'] == self::AFFICHAGE_CLASSIQUE) {
            $ctaSimple = Ndp_Cta_Factory::getInstance(Ndp_Cta::SIMPLE);
            $ctaSimple->setCtaType('AFFICHAGE_CLASSIQUE')
                ->setMulti($controller->multi)
                ->delete()
                ->save();
        } elseif(Pelican_Db::$values['ZONE_TITRE3'] == self::AFFICHAGE_VISUEL){
            $ctaHmvc = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC);
            $ctaHmvc->setCtaType('AFFICHAGE_VISUEL')
                ->setMulti($controller->multi)
                ->delete()
                ->save();
        }
    }

    public function addCtaMulti(Ndp_Form $form, $values, $readO, $multi)
    {
        // Ajout formulaire des CTA
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $ctaComposite->setValueDefaultTypeCta(Ndp_Cta::SELECT_CTA);
        $ctaRef = Pelican_Factory::getInstance('CtaRef');
        $ctaRef->hideStyle(true);

        $ctaNew = Pelican_Factory::getInstance('CtaNew');
        $ctaNew->hideStyle(true);
        $ctaComposite->setCta($form, $values, $multi, '', true, (Cms_Page_Ndp::isTranslator() || $readO));

        $ctaComposite->addInputCta($ctaRef);
        $ctaComposite->addInputCta($ctaNew);

        return $ctaComposite->generate();
    }

    public static function getCtaAffichageClassique($controller)
    {
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $ctaComposite->setCta($controller->oForm, $controller->zoneValues, $controller->multi, 'AFFICHAGE_CLASSIQUE', false, (Cms_Page_Ndp::isTranslator() || $controller->readO));
        $ctaDisable = Pelican_Factory::getInstance('CtaDisable');
        $ctaRef = Pelican_Factory::getInstance('CtaRef');
        $ctaRef->hideStyle(true);
        $ctaNew = Pelican_Factory::getInstance('CtaNew');
        $ctaNew->hideStyle(true);

        $ctaComposite->addInputCta($ctaDisable);
        $ctaComposite->addInputCta($ctaRef);
        $ctaComposite->addInputCta($ctaNew);

        return $ctaComposite->generate();
    }

    public static function getContainerVisuelText($controller)
    {
        //Affichage couleur de fond et descriptif
        $return = self::getContainerChoixMedia($controller);
        $return .= self::getContainerDescriptionEntete($controller);

        return $return;
    }

    public static function getContainerChoixMedia($controller)
    {
        // Par défaut on set l'affichage visuel a image
        if (empty($controller->zoneValues['ZONE_TITRE5'])) {
            $controller->zoneValues['ZONE_TITRE5'] = 1;
        }
        // Type de média visuel 16/9 ou video (Obligatoire)
        $aParamTypeMedia = array(
            self::NDP_VISUEL_16_9 => t('NDP_VISUEL'),
            self::NDP_VIDEO => t('NDP_VIDEO'),
        );
        $media = $controller->zoneValues["MEDIA_ID"];
        $mediaVideo = "";
        if (self::NDP_VIDEO == $controller->zoneValues['ZONE_TITRE5']) {
            $mediaVideo = $controller->zoneValues["MEDIA_ID"];
            $media = "";
        }
        $type = $controller->multi. 'container_video';
        $jsContainerVideo = self::addJsContainerRadio($type);
        $return = $controller->oForm->createRadioFromList($controller->multi.'ZONE_TITRE5', t('NDP_TYPE_AFFICHAGE'), $aParamTypeMedia, $controller->zoneValues['ZONE_TITRE5'], true, (Cms_Page_Ndp::isTranslator() || $controller->readO), 'h', false, $jsContainerVideo);
        // CONTAINER POUR L'AFFICHAGE VISUEL
        $return .= self::addHeadContainer('1', $controller->zoneValues['ZONE_TITRE5'], $type);
        $return .= $controller->oForm->createNewImage($controller->multi.'MEDIA_ID',t('NDP_VISUEL'), true, $media, (Cms_Page_Ndp::isTranslator() ||  $controller->readO), false, self::RATIO_VISUEL, [t('DESKTOP') => self::RATIO_VISUEL]);
        $return .= self::addFootContainer();
        //FIN CONTAINER POUR L'AFFICHAGE VISUEL
        // CONTAINER POUR L'AFFICHAGE VIDEO
        $return .= self::addHeadContainer('2', $controller->zoneValues['ZONE_TITRE5'], $type);
        $return .= $controller->oForm->createMedia($controller->multi.'MEDIA_ID2', t('NDP_VIDEO'), true, "video", "", $mediaVideo, (Cms_Page_Ndp::isTranslator() || $controller->readO), true, false);
        $return .= self::addFootContainer();

        return $return;
        //FIN CONTAINER POUR L'AFFICHAGE VISUEL
    }

    public static function getContainerDescriptionEntete($controller)
    {
        //Affichage Zone description + CTA (Obligatoire)
        $aParamTypeAffichage = array(
            '1' => t('ENABLED'),
            '2' => t('DISABLED'),
        );
        // Par défaut on désactive la description
        if (empty($controller->zoneValues['ZONE_TITRE6'])) {
            $controller->zoneValues['ZONE_TITRE6'] = 2;
        }
        $type = $controller->multi. 'zone_description_cta';
        $jsZoneDescriptionCta = self::addJsContainerRadio($type);

        $options = [ 'infoBull' => ['isIcon'=>true, 'message'=>t('NDP_INFO_ZONE_DESCR_CTA')] ];
        $return  = $controller->oForm->createRadioFromList($controller->multi.'ZONE_TITRE6', t('NDP_ZONE_DESCRIPTION_CTA'), $aParamTypeAffichage, $controller->zoneValues['ZONE_TITRE6'], true, (Cms_Page_Ndp::isTranslator() || $controller->readO), 'h', false, $jsZoneDescriptionCta, NULL, $options);
        // CONTAINER POUR LA ZONE DESCRIPTION
        $return .= self::addHeadContainer('1', $controller->zoneValues['ZONE_TITRE6'], $type);
        $return .= $controller->oForm->createTextArea($controller->multi."ZONE_TEXTE", t('NDP_DESCRIPTION'), true, $controller->zoneValues['ZONE_TEXTE'], 250, (Cms_Page_Ndp::isTranslator() || $controller->readO), 5, 75, false, "", true);

        $aCouleurs = array(
            1 => t('NDP_WHITE'),
            2 => t('NDP_GREY'),
        );
        // Par défaut on set la couleur a blanc
        if (empty($controller->zoneValues['ZONE_TITRE4'])) {
            $controller->zoneValues['ZONE_TITRE4'] = 1;
        }

        $options = [ 'infoBull' => ['isIcon'=>true, 'message'=>t('NDP_INFO_ZONE_TITRE_APPLAT')] ];
        $return  .= $controller->oForm->createComboFromList($controller->multi.'ZONE_TITRE4', t('NDP_FOND_ET_DESCRIPTIF'), $aCouleurs, $controller->zoneValues['ZONE_TITRE4'], true, (Cms_Page_Ndp::isTranslator() || $controller->readO), NULL, NULL, NULL, NULL, NULL, NULL, $options);
        //Affichage des CTA en mode multi
        $typeForm = 'AFFICHAGE_VISUEL';
        $isZoneDynamique = Ndp_Cta::isZoneDynamique($controller->zoneValues['ZONE_TEMPLATE_ID']);
        $ctaMulti = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC, $isZoneDynamique);
        $valuesCta = $ctaMulti->hydrate($controller->zoneValues)
            ->setCtaType($typeForm)
            ->getValues();
        $strLib = array(
            'multiTitle'     => t('NDP_CTA'),
            'multiAddButton' => t('ADD_FORM_CTA')
        );
        $return .= $controller->oForm->createMultiHmvc($controller->multi.$typeForm, $strLib, array(
            "path" => __FILE__,
            "class" => __CLASS__,
            "method" => "addCtaMulti", ),
            $valuesCta,
            $controller->multi.$typeForm,
            (Cms_Page_Ndp::isTranslator() || $controller->readO),
            array(1,2),
            true,
            true,
            $controller->multi.$typeForm,
            'values',
            'multi',
            2,
            '',
            '',
            false,
            ['noDragNDrop' => Cms_Page_Ndp::isTranslator()]
        );
        $return .= self::addFootContainer();
        // FIN CONTAINER POUR LA ZONE DESCRIPTION

        return $return;
    }
}
