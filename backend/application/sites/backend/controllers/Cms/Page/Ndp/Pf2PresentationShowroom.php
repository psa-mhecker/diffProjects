<?php
/**
 * Tranche PF2 Presentation showroom
 *
 * @author David Moate <david.moate@businessdecision.com>
 *
 * @since 01/06/2015
 */

include_once Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'] . '/Ndp/Multi.php';
include_once Pelican::$config['APPLICATION_LIBRARY'] . '/Ndp/Multi/Factory.php';

/**
 *
 */
class Cms_Page_Ndp_Pf2PresentationShowroom extends Cms_Page_Ndp
{
    const MULTI_TYPE = 'VISUEL';
    const AFFICHAGE_REVEAL = 1;
    const AFFICHAGE_LAUNCH = 2;
    const AFFICHAGE_MARKETING = 3;
    const VISUEL = 1;
    const VIDEO = 2;
    const TITLE = 1;
    const DESACTIVE = 0;
    const ACTIVE = 1;
    const CONTAINER_TYPE_AFFICHAGE = 'container_type_affichage';
    const CONTAINER_PRESENTATIONS = 'container_presentations';
    const CONTAINER_SHOW_TITLE = 'container_show_title';
    const DESKTOP = 'DESKTOP';
    const NDP_PAGE_PARENT = 1;
    const NDP_PAGE_CHILD = 2;
    const RATIO_VISUEL = 'NDP_PF2_DESKTOP';
    const RATIO_VISUEL_MOBILE = 'NDP_GENERIC_4_3_640';


    public static function render(Pelican_Controller $controller)
    {
        if(self::isChildPage()) {
            $form = self::renderChildPage($controller);
        }  else {
            $form = self::renderParentPage($controller);
        }

        return $form;
    }

    private static function renderChildPage(Pelican_Controller $controller)
    {
        $form = $controller->oForm->createDescription(
            t('NDP_MSG_CTA_SHOWROOM_DISPLAY_CONDITION_CHILD_SUITE')
        );

        // permet d'enregistrer la zone dans la tranche même si aucun champs affiché
        $form.= $controller->oForm->createHidden($controller->multi . 'ZONE_PARAMETERS', self::NDP_PAGE_CHILD);

        return $form;
    }
    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function renderParentPage(Pelican_Controller $controller)
    {               
        $form = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));
        $presentations = [
            self::AFFICHAGE_REVEAL => t('NDP_LABEL_ANNOUNCEMENT_REVEAL'),
            self::AFFICHAGE_LAUNCH => t('NDP_LABEL_ANNOUNCEMENT_LAUNCH'),
            self::AFFICHAGE_MARKETING => t('NDP_LABEL_MARKETING')
        ];

        $multivalue = [
            self::AFFICHAGE_LAUNCH,
            self::AFFICHAGE_REVEAL
        ];
        self::setDefaultValueTo($controller->zoneValues, "ZONE_PARAMETERS", self::AFFICHAGE_MARKETING);
        $type = $controller->multi. 'container_presentations';
        $jsContainerPresentation = self::addJsContainerRadio($type, $multivalue);
        $form .= $controller->oForm->createComboFromList($controller->multi."ZONE_PARAMETERS", t('NDP_PRESENTATION'), $presentations, $controller->zoneValues["ZONE_PARAMETERS"], true, $controller->readO, 1, false,'',false,false,$jsContainerPresentation);

        $datePublication = self::getDatePublication($controller);
        $labelFinPublication = t('NDP_NON_RENSEIGNE');        
        if (!empty($datePublication['PAGE_END_DATE']))
        {
            $labelFinPublication = $datePublication['PAGE_END_DATE'];
        }
        $labelStartPublication = t('NDP_NON_RENSEIGNE');
        if (!empty($datePublication['PAGE_START_DATE']))
        {
            $labelStartPublication = $datePublication['PAGE_START_DATE'];
        }
        
        $labelFinPublicationReveal = t('NDP_ANNOUNCEMENT_REVEAL_DATE') . ': ' . $labelFinPublication;
        $labelFinPublicationAnnouncement = t('NDP_ANNOUNCEMENT_LAUNCH_DATE') . ': ' . $labelFinPublication;
        $labelStartPublicationMarketing = t('NDP_MARKETING_DATE'). ' : ' . $labelStartPublication;
        
        $form .= self::addHeadContainer(self::AFFICHAGE_REVEAL, $controller->zoneValues['ZONE_PARAMETERS'], $type);
        $form .= $controller->oForm->createLabel('', t('NDP_MSG_ANNOUNCEMENT_REVEAL'));
        $form .= $controller->oForm->createLabel('', $labelFinPublicationReveal) ;
        $form .= self::addFootContainer();
        
        $form .= self::addHeadContainer(self::AFFICHAGE_LAUNCH, $controller->zoneValues['ZONE_PARAMETERS'], $type);
        $form .= $controller->oForm->createLabel('', t('NDP_MSG_ANNOUNCEMENT_LAUNCH'));        
        $form .= $controller->oForm->createLabel('', $labelFinPublicationAnnouncement);
        $form .= self::addFootContainer();
        
        $form .= self::addHeadContainer(self::AFFICHAGE_MARKETING, $controller->zoneValues['ZONE_PARAMETERS'], $type);
        $form .= $controller->oForm->createLabel('', t('NDP_MSG_MARKETING'));        
        $form .= $controller->oForm->createLabel('', $labelStartPublicationMarketing);
        $form .= self::addFootContainer();
        
        if (empty($controller->zoneValues['ZONE_ATTRIBUT'])) 
        {
            $controller->zoneValues['ZONE_ATTRIBUT'] = 1;
        }

        $paramTypeAffichage = [
            1 => t('NDP_VISUAL_MORE'),
            2 => t('NDP_VIDEO'),
        ];
        
        $type = $controller->multi. self::CONTAINER_TYPE_AFFICHAGE;
        $jsType = self::addJsContainerRadio($type);
        $form .= $controller->oForm->createRadioFromList($controller->multi.'ZONE_ATTRIBUT', t('TYPE'), $paramTypeAffichage, $controller->zoneValues['ZONE_ATTRIBUT'], true, $controller->readO, 'h', false, $jsType);        
        
        $form .= self::addHeadContainer(self::VISUEL, $controller->zoneValues['ZONE_ATTRIBUT'], $type);
        
        $isZoneDynamique = Ndp_Multi::isZoneDynamique($controller->zoneValues['ZONE_TEMPLATE_ID']);
        $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC,
                $isZoneDynamique);
        $visuelValues = $multi->setMultiType(self::MULTI_TYPE)
            ->hydrate($controller->zoneValues)
            ->getValues();
        
        $strLib = [
            'multiTitle'     => t('NDP_VISUEL'),
            'multiAddButton' => t('NDP_ADD_VISUEL'),
            'oneStrongLine'  => true
        ];
        $options = [
            'noDragNDrop' => Cms_Page_Ndp::isTranslator(),
            'showNumberLabel'=>false
        ];
        $form .= $controller->oForm->createMultiHmvc(
            $controller->multi . self::MULTI_TYPE,
            $strLib,
            array(
                "path" => __FILE__,
                "class" => __CLASS__,
                "method" => "addVisuel"
            ),
            $visuelValues,
            $controller->multi . self::MULTI_TYPE,
            (Cms_Page_Ndp::isTranslator() || $controller->readO),
            array(1, 6),
            true,
            true,
            $controller->multi . self::MULTI_TYPE,
            null,
            null,
            2,
            null,
            null,
            null,
            $options
        );

        $form .= $controller->oForm->createInput(
            $controller->multi.'ZONE_TIMER_SPEED',
            t('NDP_TIMING_SLIDE'),
            6,
            'number',
            false,
            $controller->zoneValues['ZONE_TIMER_SPEED'],
            $controller->readO,
            10
        );
        $form .= self::addFootContainer();
        
        $form .= self::addHeadContainer(self::VIDEO, $controller->zoneValues['ZONE_ATTRIBUT'], $type);
        $form .= $controller->oForm->createMedia($controller->multi.'MEDIA_ID', t('NDP_VIDEO'), true, "streamlike", "", $controller->zoneValues['MEDIA_ID'], (Cms_Page_Ndp::isTranslator() || $controller->readO), true, false);
        $form .= self::addFootContainer();

        $readOnlyDecompte = false;
        if (empty($datePublication['PAGE_END_DATE']))
        {
            $controller->zoneValues['ZONE_ATTRIBUT2'] = 0;
            $readOnlyDecompte = true;
        }
        
        $type = $controller->multi. self::CONTAINER_PRESENTATIONS; 'container_presentations';
        $form .= self::addHeadContainer(array(self::AFFICHAGE_LAUNCH,self::AFFICHAGE_REVEAL), $controller->zoneValues['ZONE_PARAMETERS'], $type);
        $form .= self::getAffichageDecompte($controller, $readOnlyDecompte, $datePublication);
        $form .= self::addFootContainer();
                 
        $paramPosition = [
            'left' => t('NDP_TO_LEFT'),
            'right' => t('NDP_TO_RIGHT'),
        ];

        $form .= $controller->oForm->createRadioFromList($controller->multi.'ZONE_LABEL2', t('NDP_AFFICHAGE_DE_LA_ZONE_ANNONCE_REVEAL'), $paramPosition, $controller->zoneValues['ZONE_LABEL2'], true, $controller->readO, 'h', false);

        // start show title into info zone

        /** connect info title with marketing option only */
        $form .= self::addHeadContainer(self::AFFICHAGE_MARKETING, $controller->zoneValues['ZONE_PARAMETERS'], $type);

        $paramShowTitle = [
            0 => t('NDP_LIST_MODELE'),
            1 => t('NDP_TITLE_MANUAL'),
            2 => t('NONE')
        ];

        $infoBulle = [
            'isIcon'  => true,
            'message' => t('NDP_MSG_PRECO_UPPERCASE_TITLE')
        ];

        $typeTitle = $controller->multi. self::CONTAINER_SHOW_TITLE;
        $jsType = self::addJsContainerRadio($typeTitle);
        $form .= $controller->oForm->createRadioFromList($controller->multi.'ZONE_ATTRIBUT3', t('NDP_SHOW_TITLE'), $paramShowTitle, $controller->zoneValues['ZONE_ATTRIBUT3'], false, (Cms_Page_Ndp::isTranslator() || $controller->readO), 'h', false, $jsType);
        $form .= self::addHeadContainer(self::TITLE, $controller->zoneValues['ZONE_ATTRIBUT3'], $typeTitle); //show if NDP_TITLE_MANUAL
        $form .= $controller->oForm->createInput(
            $controller->multi.'ZONE_TITRE4',
            t('TITLE'),
            40,
            '',
            true,
            $controller->zoneValues['ZONE_TITRE4'],
            $controller->readO,
            50,
            false,
            '',
            'text',
            [],
            false,
            $infoBulle,
            40 .t('NDP_MAX_CAR')
        );
        $form .= self::addFootContainer(); // close if no NDP_TITLE_MANUAL
        $form .= self::addFootContainer(); /** close connection of info title with marketing option only */
        // end show title into info zone

        $form .= self::addHeadContainer(self::AFFICHAGE_MARKETING, $controller->zoneValues['ZONE_PARAMETERS'], $type);
        $form .= self::getCta($controller);
        $form .= self::addFootContainer();
 
        self::addJsControl($controller);

        return $form;
    }

    private static function isChildPage() {
        $bind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        $bind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $parentPages = explode('/',$_SESSION[APP]["CURRENT_PAGE_PATH"]);
        if($_SESSION[APP]['PAGE_ID'] != self::IS_BEING_CREATED) {
            array_pop($parentPages);
        }
        $item = [];
        if(!empty($parentPages)) {
            $templatesShowRoom = implode(',', Pelican::$config['TEMPLATE_PAGE_SHOWROOM']);
            $sql = 'SELECT
                  p.PAGE_ID
                FROM #pref#_page p
                INNER JOIN #pref#_page_version pv ON p.PAGE_ID = pv.PAGE_ID AND p.LANGUE_ID=pv.LANGUE_ID AND p.PAGE_CURRENT_VERSION =pv.PAGE_VERSION
                WHERE
                   pv.TEMPLATE_PAGE_ID  IN (' . $templatesShowRoom . ')
                   AND p.PAGE_ID IN (' . implode(',', $parentPages) . ')
                   AND p.LANGUE_ID=:LANGUE_ID
                   AND p.SITE_ID=:SITE_ID
                LIMIT 0,1';
            $con = Pelican_Db::getInstance();
            $item = $con->queryItem($sql, $bind);
        }

        return !empty($item);
    }

    public static function getAffichageDecompte($controller, $readO, $dateFinPublication){
        $paramTypeAffichage = [
            0 => t('NDP_DESACTIVE'),
            1 => t('NDP_ACTIVE'),
        ];
        $options['disabled'] = $readO;
        $typeDecompte = $controller->multi. 'container_decompte';
        $jsType = self::addJsContainerRadio($typeDecompte);
        $form  = $controller->oForm->createRadioFromList($controller->multi.'ZONE_ATTRIBUT2', t('NDP_DECOMPTE'), $paramTypeAffichage, $controller->zoneValues['ZONE_ATTRIBUT2'], true, false, 'h', false, $jsType, null, $options );
        $form .= self::addHeadContainer(self::DESACTIVE, $controller->zoneValues['ZONE_ATTRIBUT2'], $typeDecompte);
        if (empty($dateFinPublication['PAGE_END_DATE'])) {
            $form .= $controller->oForm->createLabel('', t('NDP_MSG_ERROR_PUBLICATION_DATE'));
        }
        $form .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITRE'), 50, "", false, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 70, false, '', 'text', [], false, '', array('message'=> '50'.t('NDP_MAX_CAR')));
        $form .= self::addFootContainer();
        $form .= self::addHeadContainer(self::ACTIVE, $controller->zoneValues['ZONE_ATTRIBUT2'], $typeDecompte);
        $date = new DateTime($controller->zoneValues["ZONE_DATE"]);
        $dateDeb = $date->format('d/m/Y');
        $form .= $controller->oForm->createInput($controller->multi."ZONE_DATE", t('Display date begin'), 10, "date", true, $dateDeb, $controller->readO, 10, false);
        $form .= $controller->oForm->createInput($controller->multi."ZONE_TITRE3", t('NDP_DISPLAY_HEURE_BEGIN'), 10, "heure", true, $controller->zoneValues["ZONE_TITRE3"], $controller->readO, 10, false);
        $form .= $controller->oForm->createInput($controller->multi."ZONE_TITRE2", t('NDP_SOUS_TITRE'), 50, "", false, $controller->zoneValues["ZONE_TITRE2"], $controller->readO, 50);
        $form .= self::addFootContainer();                   
        
        return $form;
    }
    /**
     *
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {
        if (Pelican_Db::$values['ZONE_ATTRIBUT'] == self::VISUEL) {
            unset(Pelican_Db::$values['MEDIA_ID']);
            unset(Pelican_Db::$values['MEDIA_ID2']);
        }
        parent::save();
        $saved = Pelican_Db::$values;
        $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC);
        $multi->setMultiType(self::MULTI_TYPE)
            ->setMulti($controller->multi)
            ->delete()
            ->save();
        Pelican_Db::$values = $saved;

        $cta = Ndp_Cta_Factory::getInstance(Ndp_Cta::SIMPLE);
        $cta->setCtaType(self::DESKTOP)
            ->setMulti($controller->multi)
            ->delete()
            ->save();       
    }

    /**
     *
     * @param Ndp_Form $form
     * @param array    $values
     * @param boolean  $readO
     * @param string   $multi
     *
     * @return string
     */
    public static function addVisuel(Ndp_Form $form, $values, $readO, $multi)
    {
        $options = [
            'isIcon'  => true,
            'message' => t('NDP_1_TO_6_VISUALS')
        ];

        return $form->createNewImage(
            $multi.'MEDIA_ID',
            t('NDP_VISUEL').' '.(isset($values['CPT_POS_MULTI'])?$values['CPT_POS_MULTI']+1:'__CPT1__'),
            true,
            $values['MEDIA_ID'],
            (Cms_Page_Ndp::isTranslator() || $readO),
            false,
            self::RATIO_VISUEL,
            [t('DESKTOP') => self::RATIO_VISUEL, t('MOBILE') => self::RATIO_VISUEL_MOBILE],
            $options
        );
    }

    public static function getCta($controller)
    {
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $ctaComposite->setCta($controller->oForm, $controller->zoneValues, $controller->multi, 'DESKTOP', false, (Cms_Page_Ndp::isTranslator() || $controller->readO));
        $ctaDisable = Pelican_Factory::getInstance('CtaDisable');
        
        $ctaRef = Pelican_Factory::getInstance('CtaRef');
        $ctaRef->typeStyle(1)->addTargetAvailable('_popin', t('NDP_POPIN'));
        
        $ctaNew = Pelican_Factory::getInstance('CtaNew');
        $ctaNew->typeStyle(1)->addTargetAvailable('_popin', t('NDP_POPIN'));



        $ctaComposite->addInputCta($ctaDisable);
        $ctaComposite->addInputCta($ctaRef);
        $ctaComposite->addInputCta($ctaNew);


        return $ctaComposite->generate();
    }
    
    /**
     *
     * @param string $type
     * @param string $multiValue
     *
     * @return string
     */
    public static function addJsContainerRadio($type, $multiValue = '') {
        if (is_array($multiValue))
        {
            $values = implode('_', $multiValue);
        }

        $js = 'onclick="
                    var selectedRadio =   $(this).val();
                    $(\'.'.$type.'\').hide();
                    $(\'.'.$type.'_\' + selectedRadio).show();
                    $(\'.'.$type.'\').addClass(\'isNotRequired\');
                    $(\'.'.$type.'_\' + selectedRadio + \'\').removeClass(\'isNotRequired\');
                    
                    if (selectedRadio == 1 || selectedRadio == 2){
                        $(\'.'.$type.'_\').show();
                        $(\'.'.$type . '_' . $values .'\').show();
                        $(\'.'.$type . '_' . $values . '\').removeClass(\'isNotRequired\');
                        $(\'.'.$type . '_' . $values . '\').addClass(\'isNotRequired\');
                    }else{
                        $(\'.'.$type . '_' . $values . '\').hide();
                    }
                "';

        return $js;
    }
    
    public static function getDatePublication($controller){
        $connection = Pelican_Db::getInstance();
        $bind = [
            ':PAGE_ID' => $controller->zoneValues['PAGE_ID'],
            ':LANGUE_ID' => $controller->zoneValues['LANGUE_ID'],
            ':SITE_ID' => $_SESSION[APP]['SITE_ID']
        ];        
        $sql ="SELECT ".$connection->dateSqlToString("PAGE_END_DATE ", true)." as PAGE_END_DATE,
                      ".$connection->dateSqlToString("PAGE_START_DATE ", true)." as PAGE_START_DATE
                FROM #pref#_page p
                INNER JOIN #pref#_page_version pv on (p.PAGE_ID = pv.PAGE_ID AND pv.PAGE_VERSION = PAGE_DRAFT_VERSION AND p.LANGUE_ID = pv.LANGUE_ID)
                WHERE pv.LANGUE_ID = :LANGUE_ID
                AND p.PAGE_ID= :PAGE_ID
                AND p.SITE_ID = :SITE_ID";       
        
        return $connection->queryRow($sql, $bind);        
    }
    
    public static function addJsControl($controller)
    {

        $controller->oForm->_sJS .='
            var dateDebut    =   $(\'#'.$controller->multi.'ZONE_DATE\').val().split("/");
            var heureDebut = $("#'.$controller->multi.'ZONE_TITRE3").val().split(":");
            var dateFin = $("#PAGE_END_DATE").val();
            var heureFin = $("#PAGE_END_DATE_HEURE").val();


            var new_dateDebut = new Date(dateDebut[2], (dateDebut[1] - 1), dateDebut[0], heureDebut[0], heureDebut[1]);
            var new_dateDuJour = new Date();
            
            if(dateFin != ""){
                dateFin = dateFin.split("/");
                heureFin = heureFin.split(":");
                var new_dateFin = new Date(dateFin[2], (dateFin[1] - 1), dateFin[0], heureFin[0], heureFin[1]);
            }
            var isNotRequired  = $("#'.$controller->multi.'container_decompte_1").hasClass("isNotRequired");
            if (!isNotRequired) {
                if (new_dateDebut < new_dateDuJour) {
                    var res = confirm("'.t("NDP_LA_DATE_SELECTIONNEE_DOIT_ETRE_SUPERIEURE_OU_EGALE_A_LA_DATE_DU_JOUR").'");

                    if(!res) {
                     return res
                    }
                }

                if (dateFin != "" && (new_dateDebut > new_dateFin)) {
                    var res = confirm("'.t("NDP_LA_DATE_SELECTIONNEE_DOIT_ETRE_INFERIEURE_OU_EGALE_A_LA_DATE_DE_FIN").'");

                    if(!res) {
                     return res
                    }
                }
            }
        ';
    }
}
