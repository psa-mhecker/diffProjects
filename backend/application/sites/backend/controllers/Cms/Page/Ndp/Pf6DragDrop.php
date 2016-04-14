<?php
/**
 * Tranche PF - Drag & Drop
 *
 * @package Pelican_BackOffice
 * @subpackage Tranche
 * @author Kevin Vignon <kevin.vignon@businessdecision.com>
 * @since 17/03/2015
 */
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';

class Cms_Page_Ndp_Pf6DragDrop extends Cms_Page_Ndp
{

    const CTA_TYPE = "DRAG_DROP";
    const COL_1 = '1_COL';
    const COL_2 = '2_COL';
    const RATIO_VISUEL = 'NDP_MEDIA_CONTENT_ONE_COLUMN';
    const RATIO_VISUEL_MOBILE = 'NDP_GENERIC_4_3_640';
    
    public static function render(Pelican_Controller $controller)
    {
        $form = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));
        $form .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITRE'), 60, "", false, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 75);
        $form .= $controller->oForm->createInput($controller->multi."ZONE_TITRE2", t('SOUS_TITRE'), 60, "", false, $controller->zoneValues['ZONE_TITRE2'], $controller->readO, 75);
        $form .= $controller->oForm->createInput($controller->multi."ZONE_TITRE3", t('NDP_TITRE_ZONE_TEXTE'), 60, "", false, $controller->zoneValues['ZONE_TITRE3'], $controller->readO, 75);
        $typAffichage = array(
            self::COL_1 => "1 ".t('NDP_COLONNE'),
            self::COL_2 => "2 ".t('NDP_COLONNES')
        );
        self::setDefaultValueTo($controller->zoneValues, 'ZONE_TOOL', self::COL_1);
        $type = $controller->multi.'container_colonne';
        $js = self::addJsContainerRadio($type);
        $form .= $controller->oForm->createRadioFromList($controller->multi.'ZONE_TOOL', t('NDP_TEXTE_SUR'), $typAffichage, $controller->zoneValues['ZONE_TOOL'], true, (Cms_Page_Ndp::isTranslator() || $controller->readO), 'h', false, $js);
        $form .= $controller->oForm->createEditor($controller->multi.'ZONE_TEXTE', t('NDP_TEXTE').' '.t('NDP_COLONNE').' 1', false, $controller->zoneValues['ZONE_TEXTE'], $controller->readO, true, "", 650, 150);
        $form .= self::addHeadContainer(self::COL_2, $controller->zoneValues['ZONE_TOOL'], $type);
        $form .= $controller->oForm->createEditor($controller->multi.'ZONE_TEXTE2', t('NDP_TEXTE').' '.t('NDP_COLONNE').' 2', false, $controller->zoneValues['ZONE_TEXTE2'], $controller->readO, true, "", 650, 150);
        $form .= self::addFootContainer();
        $form .=  $controller->oForm->createNewImage(
            $controller->multi.'MEDIA_ID',
            t('NDP_VISUEL_1_GAUCHE'),
            true,
            $controller->zoneValues['MEDIA_ID'],
            (Cms_Page_Ndp::isTranslator() ||  $controller->readO),
            false,
            self::RATIO_VISUEL,
            [t('DESKTOP') => self::RATIO_VISUEL, t('MOBILE') => self::RATIO_VISUEL_MOBILE]
        );
        $form .=  $controller->oForm->createNewImage(
            $controller->multi.'MEDIA_ID2',
            t('NDP_VISUEL_2_DROITE'),
            true,
            $controller->zoneValues['MEDIA_ID2'],
            (Cms_Page_Ndp::isTranslator() ||  $controller->readO),
            false,
            self::RATIO_VISUEL,
            [t('DESKTOP') => self::RATIO_VISUEL, t('MOBILE') => self::RATIO_VISUEL_MOBILE]
        );

        $ligneSeparatrice = array(
            0 => t('NDP_VERTICALE'),
            1 => t('NDP_HORIZONTALE')
        );
        $form .= $controller->oForm->createRadioFromList($controller->multi.'ZONE_POS', t('NDP_LIGNE_SEPARATRICE'), $ligneSeparatrice, $controller->zoneValues['ZONE_POS'], true, $controller->readO, 'h', false);
        $form .= $controller->oForm->showSeparator();
        foreach (self::getTypesLevels() as $level=>$label) {
            $form .= self::getLevelCta(
                $controller,
                $level,
                t('CTA'),
                4,
                4,
                false,
                [
                    'CTA' => [
                        'forceValues'=> ['CTADisable'=>false],
                        'maxCta' => '4',
                        'CTA_READONLY' =>(Cms_Page_Ndp::isTranslator() || $controller->readO),
                        'noDragNDrop' => Cms_Page_Ndp::isTranslator()
                    ],
                    'CTA_LD' => ['showNumberLabel' => true, 'noSeparator' => true,'addButtonLabel'=>t('NDP_ADD_CTA_LD')]
                ]
            );
        }
        $form .= $controller->oForm->createJS("
            var image = new Image();
            image.src = $('#imgdiv".$controller->multi."MEDIA_ID').attr('href');
            width1 = image.width, height1 = image.height;

            var image2 = new Image();
            image2.src = $('#imgdiv".$controller->multi."MEDIA_ID2').attr('href');
            width2 = image2.width, height2 = image2.height;

            if (width1 != width2 || height1 != height2) {
                alert('".t('NDP_ERROR_SIZE_VISUAL')."');
                fwFocus(eval(imgdiv".$controller->multi."MEDIA_ID));
                    
                return false;
            }
        ");

        return $form;
    }

     /**
     * 
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {

        self::$con = Pelican_Db::getInstance();
        parent::save();
        $type = Pelican_Db::$values['ZONE_PARAMETERS'];
        $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC);
        $multi->setMultiType($type)
            ->setMulti($controller->multi)
            ->delete()
            ->save();
        foreach (self::getTypesLevels() as $type => $label) {
           $ctaHmvc = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC);
            $ctaHmvc->setCtaType($type.self::TYPE_CTA)
                ->setMulti($controller->multi)
                ->setTypeCtaDropDown($type.self::TYPE_CTA_LD)
                ->delete() //suppression des anciens CTA (liste deroulante compris)
                ->save();
            
            $ctaLDHmvc = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC);
            $ctaLDHmvc->setCtaType($type.self::TYPE_CTA_LD)
                ->setMulti($controller->multi)
                ->setCtaDropDown(true)
                ->save();
        }
    }
}
