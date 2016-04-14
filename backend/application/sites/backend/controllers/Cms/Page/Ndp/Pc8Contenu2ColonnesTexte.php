<?php

/**
 * Tranche PC - Contenu Grand Visuel.
 *
 * @author Kevin Vignon <kevin.vignon@businessdecision.com>
 *
 * @since 24/02/2015
 */
include_once Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'] . '/Ndp/Cta.php';
include_once Pelican::$config['APPLICATION_LIBRARY'] . '/Ndp/Cta/Factory.php';
include_once Pelican::$config['APPLICATION_LIBRARY'] . '/Ndp/Multi.php';
include_once Pelican::$config['APPLICATION_LIBRARY'] . '/Ndp/Multi/Factory.php';

class Cms_Page_Ndp_Pc8Contenu2ColonnesTexte extends Cms_Page_Ndp
{

    const PDF = 'PDF';
    const CONTAINER_LIEN = 'CONTAINER_LIEN';
    const RADIO_PDF = 2;
    const RADIO_CTA = 1;
    const COLONNE = 'COLONNE';
    const NB_COLONNE = 2;
    const RATIO_VISUEL = 'NDP_SMALL_PICTO';

    
    /**
     * 
     * @param Pelican_Controller $controller
     * @return type
     */
    public static function render(Pelican_Controller $controller)
    {
        $maxCharacter = 500;
        $return  = $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('NDP_TITLE_SLICE_DESKTOP'), 30, "", false, $controller->zoneValues["ZONE_TITRE"], $controller->read0, 100);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE2", t('NDP_TITLE_SLICE_MOBILE'), 30, "", true, $controller->zoneValues["ZONE_TITRE2"], $controller->read0, 100);
        $return .= self::createModeAffichage($controller);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE3", t('NDP_TITRE_COLONNE'). " 1", 40, "", true, $controller->zoneValues["ZONE_TITRE3"], $controller->read0, 100);
        $return .= $controller->oForm->createNewImage($controller->multi.'MEDIA_ID', t('NDP_PICTO_DESKTOP'), false, $controller->zoneValues['MEDIA_ID'], (Cms_Page_Ndp::isTranslator() || $controller->readO),false, self::RATIO_VISUEL, [t('DESKTOP') => self::RATIO_VISUEL]);
        $return .= $controller->oForm->createEditor($controller->multi."ZONE_TEXTE", t('TEXTE'), true, $controller->zoneValues["ZONE_TEXTE"], $controller->readO, true, "", 650, 150, "", array('message'=>t('NDP_DYN_MAX_CAR', null, array('max_characters'=>$maxCharacter)), 'maxCharacterNumber' => $maxCharacter));
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE4", t('NDP_TITRE_COLONNE'). " 2", 40, "", true, $controller->zoneValues["ZONE_TITRE4"], $controller->read0, 100);
        $return .= $controller->oForm->createNewImage($controller->multi.'MEDIA_ID2', t('NDP_PICTO_DESKTOP'), false, $controller->zoneValues['MEDIA_ID2'], (Cms_Page_Ndp::isTranslator() || $controller->readO),false, self::RATIO_VISUEL, [t('DESKTOP') => self::RATIO_VISUEL]);
        $return .= $controller->oForm->createEditor($controller->multi."ZONE_TEXTE2", t('TEXTE'), true, $controller->zoneValues["ZONE_TEXTE2"], $controller->readO, true, "", 650, 150, "", array('message'=>t('NDP_DYN_MAX_CAR', null, array('max_characters'=>$maxCharacter)), 'maxCharacterNumber' => $maxCharacter));

        $return .= self::getCta($controller);

        return $return;
    }
    
    /**
     * 
     * @param Pelican_Controller $controller
     * 
     * @return string
     */
    public static function createModeAffichage(Pelican_Controller $controller)
    {
        $fieldValueWeb = (isset($controller->zoneValues['ZONE_WEB'])) ? $controller->zoneValues['ZONE_WEB'] : 1;
        $fieldValueMob = (isset($controller->zoneValues['ZONE_MOBILE'])) ? $controller->zoneValues['ZONE_MOBILE'] : 1;
        $return  = $controller->oForm->createCheckBoxFromList($controller->multi."ZONE_WEB", t('AFFICHAGE_WEB'), array(1 => ""), $fieldValueWeb, false, $controller->readO);
        $return .= "<tr><td class='formlib'>".t('AFFICHAGE_MOB')."</td><td class='formval'>";
        $return .= $controller->oForm->createCheckBoxFromList($controller->multi."ZONE_MOBILE", t('AFFICHAGE_MOB'), array(1 => ""), $fieldValueMob, false, $controller->readO, 'h', true);
        $return .= " ".t('NDP_TOGGLE_OPEN_DEFAULT')." ".$controller->oForm->createCheckBoxFromList($controller->multi."ZONE_ATTRIBUT", t('NDP_TOGGLE_OPEN_DEFAULT'), array(1 => ""), $controller->zoneValues['ZONE_ATTRIBUT'], false, $controller->readO, 'h', true);
        $return .= "</td></tr>";  
       
        return $return;
    }

    /**
     * 
     * @param Pelican_Controller $controller
     * @return type
     */
    public static function getCta(Pelican_Controller $controller)
    {
        $form = '';
        for ($i = 1; $i <= self::NB_COLONNE; $i++) {
            $typeCta = self::COLONNE . $i;
            $isZoneDynamique = Ndp_Cta::isZoneDynamique($controller->zoneValues['ZONE_TEMPLATE_ID']);
            $ctaMulti = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC, $isZoneDynamique);
            $ctaMulti->hydrate($controller->zoneValues)->setCtaType($typeCta);
            $valuesCta = $ctaMulti->getValues();
            $strLib = array(
                'multiTitle' => t('NDP_CTA') . ' ' . t('NDP_COLONNE') . $i,
                'multiAddButton' => t('ADD_FORM_CTA'). ' ' . t('NDP_COLONNE') . $i
            );
            $form .= $controller->oForm->createMultiHmvc(
                $controller->multi.$typeCta, $strLib, array(
                'path' => __FILE__,
                'class' => __CLASS__,
                'method' => 'addCtaMulti'),
                $valuesCta,
                $controller->multi.$typeCta,
                (Cms_Page_Ndp::isTranslator() || $controller->readO),
                array(0, 2),
                true,
                true,
                $controller->multi.$typeCta,
                '',
                '',
                '2',
                '',
                '',
                false,
                ['noDragNDrop' => Cms_Page_Ndp::isTranslator()]
            );
        }

        return $form;
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
        
        $ctaRef = Pelican_Factory::getInstance('CtaRef');
        $ctaRef->hideStyle(true);
        $ctaRef->typeStyle(1)->addTargetAvailable('_popin' , t('NDP_POPIN'));        
        
        $ctaNew = Pelican_Factory::getInstance('CtaNew');
        $ctaNew->hideStyle(true);
        $ctaNew->typeStyle(1)->addTargetAvailable('_popin' , t('NDP_POPIN'));
        
        $ctaComposite->setCta($form, $values, $multi, '', true, (Cms_Page_Ndp::isTranslator() || $readO));
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
        parent::save();
        for ($i = 1; $i <= self::NB_COLONNE; $i++) {
            $typeCta = self::COLONNE . $i;
            $ctaHmvc = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC);
            $ctaHmvc->setCtaType($typeCta)
                ->setMulti($controller->multi)
                ->delete()
                ->save();
        }
    }
}
