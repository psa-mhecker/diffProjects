<?php
/**
 * Tranche PC40 - CTA.
 *
 * @author David Moate <david.moate@businessdecision.com>
 *
 * @since 16/03/2015
 */
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta/Factory.php';

class Cms_Page_Ndp_Pc40Cta extends Cms_Page_Ndp
{
    const VISUELS_CTA       = 1;
    const GRAND_VISUEL      = 2;
    const NO_VISUEL         = 3;
    const VISUELS_CTA_LIEN  = 'NDP_VISUELS_CTA_LIEN';
    const GRAND_VISUEL_LIEN = 'GRAND_VISUEL_LIEN';
    const NO_VISUEL_LIEN    = 'NDP_SANS_VISUEL_LIEN';
    const MAX_VISUELS_CTA   = 2;
    const MAX_DEFAULT_CTA   = 3;
    const STYLE_LIEN        = 'style_niveau4';
    const CTA_COMMUN        = 'CTA_COMMUN';
    const CENTER            = 1;

    /**
     * 
     * @param Pelican_Controller $controller
     * 
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        // Affichage WEB / MOBILE
        $form = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));

        // Affichage du titre de la page (Obligatoire)
        $form .= $controller->oForm->createInput($controller->multi.'ZONE_TITRE',
            t('TITRE'), 60, "", false, $controller->zoneValues['ZONE_TITRE'],
            $controller->readO, 60);

        // Affichage du Style pour les CTA ci-dessous
        $form .= $controller->oForm->createComboFromList($controller->multi.'ZONE_TOOL',
            t('NDP_STYLE_CTA'), self::getStyle(),
            $controller->zoneValues['ZONE_TOOL'], true, false, 1, false);

        // Par défaut on set le mode d'affichage 3 VISUELS
        if (empty($controller->zoneValues['ZONE_AFFICHAGE'])) {
            $controller->zoneValues['ZONE_AFFICHAGE'] = 1;
        }
        
        $form .= $controller->oForm->createHR();        
        $form .= self::getCtaSimple($controller);
        $form .= $controller->oForm->createHR(); 
        
        $type                 = $controller->multi.'CONTAINER_CTA';
        $jsContainerAffichage = self::addJsContainerRadio($type);
        $form .= $controller->oForm->createRadioFromList($controller->multi.'ZONE_AFFICHAGE',
            t('NDP_FORMAT_WEB'), self::getTypeFormForCta(true),
            $controller->zoneValues['ZONE_AFFICHAGE'], true, $controller->readO,
            'h', false, $jsContainerAffichage);      
        
        // CONTAINER POUR L'AFFICHAGE 3 VISUELS
        $form .= self::addHeadContainer(self::VISUELS_CTA,
                $controller->zoneValues['ZONE_AFFICHAGE'], $type);
        $form .= self::getContainer3Visuels($controller);
        $form .= self::addFootContainer();
        //FIN CONTAINER POUR L'AFFICHAGE 3 VISUELS
        // CONTAINER POUR L'AFFICHAGE GRAND VISUEL
        $form .= self::addHeadContainer(self::GRAND_VISUEL,
                $controller->zoneValues['ZONE_AFFICHAGE'], $type);
        $form .= self::getContainerGrandVisuel($controller);
        $form .= self::addFootContainer();


        // CONTAINER POUR L'AFFICHAGE SANS VISUEL
        $form .= self::addHeadContainer(self::NO_VISUEL,
                $controller->zoneValues['ZONE_AFFICHAGE'], $type);
        $form .= self::getContainerSansVisuels($controller);
        $form .= self::addFootContainer();

        return $form;
    }

    /**
     * 
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {
        if (Pelican_Db::$values['ZONE_AFFICHAGE'] == self::VISUELS_CTA || Pelican_Db::$values['ZONE_AFFICHAGE']
            == self::NO_VISUEL) {
            unset(Pelican_Db::$values['MEDIA_ID']);
        }
        parent::save();
        self::saveCta($controller);
    }

    /**
     * 
     * @param Pelican_Controller $controller
     */
    public static function saveCta(Pelican_Controller $controller)
    {
        $ctaHmvc = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC);
        $ctaHmvc->setCtaType(self::getSingleFormForCta(Pelican_Db::$values['ZONE_AFFICHAGE']))
            ->setMulti($controller->multi)
            ->setStyle(Pelican_Db::$values['ZONE_TOOL'])
            ->delete()
            ->save();
        $ctaHmvcLien = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC);
        $ctaHmvcLien->setCtaType(self::getTypeFormForCtaLien(Pelican_Db::$values['ZONE_AFFICHAGE']))
            ->setMulti($controller->multi)
            ->setStyle(self::STYLE_LIEN)
            ->delete()
            ->save();
        $ctaSimple = Ndp_Cta_Factory::getInstance(Ndp_Cta::SIMPLE);
        $ctaSimple->setCtaType(self::CTA_COMMUN)
            ->setMulti($controller->multi)
            ->setStyle(Pelican_Db::$values['ZONE_TOOL'])
            ->delete()
            ->save();
    }

    /**
     * 
     * @param Ndp_Form $form
     * @param array $values
     * @param bool $readO
     * @param array $multi
     * 
     * @return string
     */
    public function addCtaMulti(Ndp_Form $form, $values, $readO, $multi)
    {
        // Ajout formulaire des CTA
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $ctaRef       = Pelican_Factory::getInstance('CtaRef');
        $ctaRef->hideStyle(true);
        $ctaComposite->setCta($form, $values, $multi, '', true, (Cms_Page_Ndp::isTranslator() || $readO));
        $ctaComposite->addInputCta($ctaRef);

        return $ctaComposite->generate();
    }

    /**
     *
     * @param Ndp_Form $form
     * @param array $values
     * @param bool $readO
     * @param array $multi
     *
     * @return string
     */
    public function addCtaMultiLien(Ndp_Form $form, $values, $readO, $multi)
    {
        // Ajout formulaire des CTA Lien lvl4
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $ctaNew       = Pelican_Factory::getInstance('CtaNew');
        $ctaNew->hideStyle(true);
        $ctaNew->hideTarget(true);
        $ctaNew->hideTitle(true);
        $ctaComposite->setCta($form, $values, $multi, '', true, (Cms_Page_Ndp::isTranslator() || $readO));
        $ctaComposite->addInputCta($ctaNew);

        return $ctaComposite->generate();
    }

    /**
     * 
     * @param Pelican_Controller $controller
     * @param string $typeCta
     * @param int $nbMaxVisuel
     * 
     * @return string
     */
    public static function getCtaMulti(Pelican_Controller $controller, $typeCta, $nbMaxVisuel)
    {
        $isZoneDynamique = Ndp_Cta::isZoneDynamique($controller->zoneValues['ZONE_TEMPLATE_ID']);
        $ctaMulti        = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC, $isZoneDynamique);
        $ctaMulti->hydrate($controller->zoneValues);
        $ctaMulti->setIsMulti(true);
        $ctaMulti->setCtaType($typeCta);
        $nom = t('NDP_CTA').' 2 - 4';
        $methode = 'addCtaMulti';
        $button = t('ADD_FORM_CTA');
        if (self::MAX_VISUELS_CTA == $nbMaxVisuel) {
            $nom = t('NDP_CTA').' 2 - 3';
        }        
        if (preg_match("/LIEN/i", $typeCta)) {
            $button = t('ADD_FORM_CTA_LIEN');
            $methode = 'addCtaMultiLien';
        }
        $valuesCta       = $ctaMulti->getValues();
        $strLib          = array(
            'multiTitle' => $nom,
            'multiAddButton' => $button
        );
        $form = $controller->oForm->createMultiHmvc($controller->multi.$typeCta,
            $strLib,
            array(
            "path" => __FILE__,
            "class" => __CLASS__,
            "method" => $methode,),
            $valuesCta,
            $controller->multi.$typeCta,
            (Cms_Page_Ndp::isTranslator() || $controller->readO),
            array(0, $nbMaxVisuel),
            true,
            true,
            $controller->multi.$typeCta,
            "values",
            "multi",
            "2",
            "",
            "",
            true,
            ['noDragNDrop' => Cms_Page_Ndp::isTranslator()]
        );

        return $form;
    }

    /**
     * 
     * @param Pelican_Controller $controller
     * 
     * @return type
     */
    public static function getContainerGrandVisuel(Pelican_Controller $controller)
    {
        // Affichage du positionnement du CTA (Obligatoire)
        $positionnementCta = array(
            1 => t('CENTRE'),
            2 => t('NDP_DROITE'),
        );
        // Affichage du média pour la version Web
        $form = $controller->oForm->createMedia($controller->multi.'MEDIA_ID',
            t('VISUEL_WEB'), false, 'image', '',
            $controller->zoneValues['MEDIA_ID'], (Cms_Page_Ndp::isTranslator() || $controller->readO), true, false);
   
        $nbMaxVisuel = self::MAX_DEFAULT_CTA;
        $form .= self::getCtaMulti($controller, self::getSingleFormForCta(self::GRAND_VISUEL), $nbMaxVisuel);

        return $form;
    }

    /**
     * 
     * @param Pelican_Controller $controller
     * 
     * @return string
     */
    public static function getContainer3Visuels(Pelican_Controller $controller)
    {
        $nbMaxVisuel = self::MAX_VISUELS_CTA;
        $form        = $controller->oForm->createLabel('',
            t('NDP_MSG_VISUEL_CTA_IMP'));
        $form .= $controller->oForm->createLabel('', t('NDP_MSG_CTA_ABS'));
        $form .= self::getCtaMulti($controller, self::getSingleFormForCta(self::VISUELS_CTA), $nbMaxVisuel);

        return $form;
    }

    /**
     * 
     * @param Pelican_Controller $controller
     * 
     * @return string
     */
    public static function getContainerSansVisuels(Pelican_Controller $controller)
    {
        $nbMaxSansVisuel = self::MAX_DEFAULT_CTA;
        $form = self::getCtaMulti($controller, self::getSingleFormForCta(self::NO_VISUEL), $nbMaxSansVisuel);

        return $form;
    }

    /**
     * 
     * @return array
     */
    public static function getStyle()
    {
        $styles = array(
            'style_niveau1' => t('NDP_STYLE_NIVEAU1'),
            'style_niveau2' => t('NDP_STYLE_NIVEAU2'),
            'style_niveau3' => t('NDP_STYLE_NIVEAU3'),
        );

        return $styles;
    }

    /**
     * 
     * @param int $typeAffichage
     * 
     * @return string
     */
    public static function getSingleFormForCta($typeAffichage)
    {
        $typesForm = self::getTypeFormForCta(false);

        return $typesForm[$typeAffichage];
    }

    /**
     *
     * @param  int $id
     * @return string
     */
    public static function getTypeFormForCtaLien($id)
    {
        $typesForm = '';
        switch ($id) {
            case self::VISUELS_CTA :
                $typesForm = self::VISUELS_CTA_LIEN;
                break;
            case self::GRAND_VISUEL:
                $typesForm = self::GRAND_VISUEL_LIEN;
                break;
            case self::NO_VISUEL :
                $typesForm = self::NO_VISUEL_LIEN;
                break;
            default:
                //nothing
                break;
        }

        return $typesForm;
    }

    /**
     * 
     * @param boolean $translate
     * 
     * @return string
     */
    public static function getTypeFormForCta($translate = false)
    {
        $typesForm = array(
            self::VISUELS_CTA => 'NDP_VISUELS_CTA',
            self::GRAND_VISUEL => 'GRAND_VISUEL',
            self::NO_VISUEL => 'NDP_SANS_VISUEL'
        );
        if ($translate) {
            $key  = array_keys($typesForm);
            $size = sizeOf($key);
            for ($i = 0; $i < $size; $i++) {
                $typesForm[$key[$i]] = t($typesForm[$key[$i]]);
            }
        }

        return $typesForm;
    }
    
    public static function getCtaSimple(Pelican_Controller $controller)
    {
        $form = $controller->oForm->createLabel(t('NDP_CTA').' 1', '');
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $ctaComposite->setCta($controller->oForm, $controller->zoneValues, $controller->multi, self::CTA_COMMUN, false, (Cms_Page_Ndp::isTranslator() || $controller->readO));
        $ctaRef = Pelican_Factory::getInstance('CtaRef');
        $ctaRef->hideStyle(true);
        $ctaComposite->addInputCta($ctaRef);
        $form .= $ctaComposite->generate();
        
        // Affichage du positionnement du CTA (Obligatoire)
        $positionnementCta = array(
            1 => t('CENTRE'),
            2 => t('NDP_TO_RIGHT'),
        );

        return $form;
    }    
}
