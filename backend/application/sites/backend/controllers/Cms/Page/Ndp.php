<?php
/**
 * Classe avec des méthodes commune du BO
 *
 * @package Pelican_BackOffice
 * @subpackage Administration
 */
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Cms/Page/Module.php';

use PsaNdp\MappingBundle\Entity\PsaPageTypesCode;


abstract class Cms_Page_Ndp extends Cms_Page_Module
{

    const TYPE_CTA     = "CTA";
    const TYPE_CTA_LD  = "CTA_LD";
    const fieldModel   = "PAGE_GAMME_VEHICULE";
    const idForComment = "ERROR_NO_SILHOUETTE";
    const TO_DELETE    = 5;
    const TRANSLATOR_PROFILE = 'TRADUCTEUR';



    /**
     * @param string $selected
     * @param string $type
     * @param mixed  $value
     *
     * @return string
     */
    public static function addHeadContainer($selected, $value, $type)
    {
        $display = 'display:none;';
        $class = ' isNotRequired';
        if ($selected == $value || (is_array($selected) && in_array($value, $selected))) {
            $display = '';
            $class = '';
        }
        $isArray = false;
        if (is_array($selected) && count($selected) > 0) {
            $isArray = true;
            $return = '<tbody id="'.$type.'_'.$selected[0].'" style="'.$display.'" class="'.$type;
            foreach($selected as $key => $select) {
                $return .= ' '.$type.'_'.$select;
            }
            $return .= ' '.$class.'"><tr><td colspan="2"><table class="form">';
        }
        if(!$isArray) {
            $return = sprintf('<tbody id="%s_%s" style="%s" class="%s %s_%s %s"><tr><td colspan="2"><table class="form">', $type, $selected, $display, $type, $type, $selected, $class);
        }

        return $return;
    }

    /**
     * @return string

     *
     */
    public static function addFootContainer()
    {
        $footContainer = '</table>';
        $footContainer .= '</td></tr>';
        $footContainer .= '</tbody>';

        return $footContainer;
    }

    /**
     *
     * @param string $type
     *
     * @return string
     */
    public static function addJsContainerRadio($type)
    {
        $js = 'onclick="
                    $(\'.'.$type.'\').hide();
                    var selectedRadio =   $(this).val();

                    $(\'.'.$type.'_\' + selectedRadio).show();
                    $(\'.'.$type.'\').addClass(\'isNotRequired\');
                    $(\'.'.$type.'_\' + selectedRadio + \'\').removeClass(\'isNotRequired\');
                "';

        return $js;
    }

    /**
     *
     * @param string $type
     *
     * @return string
     */
    public static function addJsContainerComboLD($type)
    {
        $js = 'onchange="
                   
                    $(\'.'.$type.'\').hide();
                    var selectedRadio =   $(this).val();
                    
                    if( ($(\'.'.$type.'.\' + selectedRadio)).length == 0 ){                        
                        $(\'.'.$type.'_\' + selectedRadio).show();
                        $(\'.'.$type.'\').addClass(\'isNotRequired\');
                        $(\'.'.$type.'_\' + selectedRadio + \'\').removeClass(\'isNotRequired\');
                    }else{
                        $(\'.'.$type.'\').addClass(\'isNotRequired\');
                        $(\'.'.$type.'.\' + selectedRadio).show();
                        $(\'.'.$type.'.\' + selectedRadio).removeClass(\'isNotRequired\');
                    }

                "';

        return $js;
    }

    /**
     *
     * @param string $type
     *
     * @return string
     */
    public static function addJsContainerCheckBoxAffichage($type)
    {
        $js = 'onclick="
                    if ( this.checked ) {
                        $(\'.'.$type.'\').removeClass(\'isNotRequired\');
                        $(\'.'.$type.'\').show();
                    }else{
                        $(\'.'.$type.'\').addClass(\'isNotRequired\');
                        $(\'.'.$type.'\').hide();
                     }
                "';

        return $js;
    }

    /**
     *
     * @param Ndp_Form $form
     * @param array $values
     * @param boolean $readO
     * @param string $multi
     *
     * @return string
     */
    public function addNewCtaMulti(Ndp_Form $form, $values, $readO, $multi)
    {
        // Ajout formulaire des CTA
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $ctaDisable = Pelican_Factory::getInstance('CtaDisable');
        $ctaNew = Pelican_Factory::getInstance('CtaNew');
        $ctaNew->typeStyle(1);
        $ctaComposite->setCta($form, $values, $multi, '', true);
        $ctaComposite->addInputCta($ctaDisable);
        $ctaComposite->addInputCta($ctaNew);

        return $ctaComposite->generate();
    }

    /**
     *
     * @param Ndp_Form $form
     * @param array $values
     * @param boolean $readO
     * @param string $multi
     *
     * @return string
     */
    public function addCtaMultiHidden(Ndp_Form $form, $values, $readO, $multi)
    {
        // Ajout formulaire des CTA
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $ctaDisable = Pelican_Factory::getInstance('CtaDisable');
        $ctaRef = Pelican_Factory::getInstance('CtaRef');
        $ctaRef->typeStyle(1);
        $ctaNew = Pelican_Factory::getInstance('CtaNew');
        $ctaNew->typeStyle(1);
        $ctaNew->addTargetAvailable(
            '_popin', t('NDP_POPIN')
        );
        $ctaComposite->setCta($form, $values, $multi, '', true);
        $ctaComposite->addInputCta($ctaDisable);
        $ctaComposite->addInputCta($ctaRef);
        $ctaComposite->addInputCta($ctaNew);

        return $ctaComposite->generate();
    }

    /**
     *
     * @param Ndp_Form $form
     * @param array $values
     * @param boolean $readO
     * @param string $multi
     *
     * @return string
     */
    public function addCtaMulti(Ndp_Form $form, $values, $readO, $multi)
    {
        // Ajout formulaire des CTA
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $ctaDisable = Pelican_Factory::getInstance('CtaDisable');
        $ctaRef = Pelican_Factory::getInstance('CtaRef');
        $ctaRef->typeStyle(0);
        $ctaNew = Pelican_Factory::getInstance('CtaNew');
        $ctaNew->typeStyle(0);
        $ctaComposite->setCta($form, $values, $multi, '', true);
        $ctaComposite->addInputCta($ctaDisable);
        $ctaComposite->addInputCta($ctaRef);
        $ctaComposite->addInputCta($ctaNew);

        return $ctaComposite->generate();
    }

    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return array
     */
    public static function getConfigAffichage($controller)
    {
        $fieldValueWeb = (isset($controller->zoneValues['ZONE_WEB'])) ? $controller->zoneValues['ZONE_WEB'] : 1;
        $fieldValueMob = (isset($controller->zoneValues['ZONE_MOBILE'])) ? $controller->zoneValues['ZONE_MOBILE'] : 1;
        $showWeb = (isset($controller->zoneValues['ZONE_WEB_SHOW'])) ? $controller->zoneValues['ZONE_WEB_SHOW'] : true;
        $showMob = (isset($controller->zoneValues['ZONE_MOBILE_SHOW'])) ? $controller->zoneValues['ZONE_MOBILE_SHOW'] : true;
        $eventMob = (isset($controller->zoneValues['ZONE_MOBILE_EVENT'])) ? $controller->zoneValues['ZONE_MOBILE_EVENT'] : '';
        $eventWeb = (isset($controller->zoneValues['ZONE_WEB_EVENT'])) ? $controller->zoneValues['ZONE_WEB_EVENT'] : '';
        $fieldDisabledWeb = $fieldDisabledMob = '';
        if (isset($controller->zoneValues['ZONE_WEB_READO']) && $controller->zoneValues['ZONE_WEB_READO'] === true) {
            $fieldDisabledWeb = 'disabled=\"disabled\"';
        }
        if (isset($controller->zoneValues['ZONE_MOBILE_READO']) && $controller->zoneValues['ZONE_MOBILE_READO'] === true) {
            $fieldDisabledMob = 'disabled=\"disabled\"';
        }

        $field = array(
            'MOBILE' => array(
                'DISPLAY' => $showMob,
                'NAME' => $controller->multi.'ZONE_MOBILE',
                'VALUE' => $fieldValueMob,
                'READONLY' => (Cms_Page_Ndp::isTranslator() || $controller->readO),
                'FIELDDISABLED' => $fieldDisabledMob.' '.$eventMob
            ),
            'WEB' => array(
                'DISPLAY' => $showWeb,
                'NAME' => $controller->multi.'ZONE_WEB',
                'VALUE' => $fieldValueWeb,
                'READONLY' => (Cms_Page_Ndp::isTranslator() || $controller->readO),
                'FIELDDISABLED' => $fieldDisabledWeb.' '.$eventWeb
            )
        );

        return $field;
    }

    /**
     *
     * @param Pelican_Controller $controller
     * @param array $values
     * @param int $nombre
     *
     * @return array $values
     */
    public function createMultiFields(Pelican_Controller $controller, array $values, $nombre = 1)
    {
        for ($i = 0; $i < $nombre; $i++) {
            $values[$i]['PAGE_ID'] = $controller->zoneValues['PAGE_ID'];
        }

        return $values;
    }

    /**
     * @param array  $values
     * @param string $multiType
     *
     * @return array
     */
    public static function getMultiValues($values, $multiType, $full = true)
    {
        $multiValues = array();
        self::$con = Pelican_Db::getInstance();
        if ($values['PAGE_ID'] != self::IS_BEING_CREATED) {
            $bind[":PAGE_ID"] = $values["PAGE_ID"];
            $bind[":LANGUE_ID"] = $values["LANGUE_ID"];
            $bind[":PAGE_VERSION"] = $values["PAGE_VERSION"];
            $bind[":ZONE_TEMPLATE_ID"] = $values["ZONE_TEMPLATE_ID"];
            $bind[":PAGE_ZONE_MULTI_TYPE"] = $multiType;
            //Changed ORDER BY to PAGE_ZONE_MULTI_ORDER
            $sql = "SELECT *
                FROM #pref#_page_zone_multi pzm ";
            if ($full) {
                $sql .= "LEFT JOIN #pref#_page_zone_multi_cta pzmc ON pzmc.PAGE_ID= pzm.PAGE_ID AND pzm.LANGUE_ID=pzmc.LANGUE_ID AND pzm.PAGE_VERSION=pzmc.PAGE_VERSION AND  pzm.ZONE_TEMPLATE_ID= pzmc.ZONE_TEMPLATE_ID AND pzm.PAGE_ZONE_MULTI_TYPE =  pzmc.PAGE_ZONE_MULTI_TYPE  AND  pzm.PAGE_ZONE_MULTI_ID  =pzmc.PAGE_ZONE_MULTI_ID
                          LEFT JOIN #pref#_cta c ON pzmc.cta_id = c.id
                ";
            }
            $sql .= " WHERE pzm.page_id = :PAGE_ID AND pzm.langue_id = :LANGUE_ID AND pzm.PAGE_VERSION = :PAGE_VERSION AND pzm.ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID AND pzm.PAGE_ZONE_MULTI_TYPE = ':PAGE_ZONE_MULTI_TYPE' ORDER BY pzm.PAGE_ZONE_MULTI_ORDER";

            $tempValues = self::$con->queryTab($sql, $bind);
            foreach ($tempValues as $value) {
                $value['TYPE_CTA'] = 'disabled';
                if (!empty($value['TARGET'])) {
                    $cta = [];
                    $cta['TARGET'] = $value['TARGET'];
                    $cta['STYLE'] = $value['STYLE'];
                    $cta['CTA_ID'] = $value['CTA_ID'];
                    $fieldname = 'SELECT_CTA';
                    $value['TYPE_CTA'] = 'select';
                    if (!$value['IS_REF']) {
                        $value['TYPE_CTA'] = 'new';
                        $fieldname = 'NEW_CTA';
                        $cta['TITLE'] = $value['TITLE'];
                        $cta['ACTION'] = $value['ACTION'];
                    }
                    $value[$fieldname] = $cta;
                }
                $multiValues[] = $value;
            }
        }

        return $multiValues;
    }

    /**
     *
     * @param array $values
     * @param string $multi
     *
     * @return array
     */
    public static function getMediaValues($values, $multi = "")
    {
        self::$con = Pelican_Db::getInstance();
        $multiValues = array();
        if ($values['PAGE_TEMPLATE_ID'] != self::IS_BEING_CREATED) {
            $bind[":PAGE_ID"] = $values["PAGE_TEMPLATE_ID"];
            $bind[":LANGUE_ID"] = $values["LANGUE_ID"];
            $zones = join(',', Pelican::$config['ZONE_MEDIA_SHOWROOM']);
            $sql = "SELECT DISTINCT pz.*
                       FROM #pref#_page_zone".$multi." pz, #pref#_page p, #pref#_page_version pv
                       WHERE
                       p.PAGE_ID = pv.PAGE_ID
                       AND pv.PAGE_VERSION = p.PAGE_CURRENT_VERSION
                       AND pv.STATE_ID = 4
                       AND
                        ( pz.page_id = :PAGE_ID
                          OR (p.PAGE_PARENT_ID = :PAGE_ID AND pz.PAGE_ID = p.PAGE_ID)
                          OR (p.PAGE_PARENT_ID IN (SELECT PAGE_ID FROM #pref#_page WHERE PAGE_PARENT_ID = :PAGE_ID OR PAGE_PARENT_ID IN (SELECT PAGE_ID FROM #pref#_page WHERE PAGE_PARENT_ID = :PAGE_ID ) ) AND pz.PAGE_ID = p.PAGE_ID))
                       AND pz.langue_id = :LANGUE_ID
                       AND pz.PAGE_VERSION = p.PAGE_CURRENT_VERSION
                       AND pz.ZONE_TEMPLATE_ID IN ( SELECT zt.ZONE_TEMPLATE_ID FROM #pref#_zone_template zt where zt.ZONE_ID IN (".$zones." ))
                       ";
            $multiValues = self::$con->queryTab($sql, $bind);
        }

        return $multiValues;
    }

    public static function getLangueCodeByLangueId($langueId)
    {
        $connection = Pelican_Db::getInstance();
        $bind[":LANGUE_ID"] = $langueId;
        $sql = 'SELECT LANGUE_CODE FROM #pref#_language WHERE LANGUE_ID = :LANGUE_ID';
        $langueCode = $connection->queryRow($sql, $bind);

        return $langueCode['LANGUE_CODE'];
    }

    /**
     *
     * @param array $values
     * @param $areaId
     * @param string $multi
     *
     * @return array
     */
    public static function getMultiMediaValues($values, $areaId, $multi = "")
    {
        self::$con = Pelican_Db::getInstance();
        $multiValues = array();
        if ($values['PAGE_TEMPLATE_ID'] != self::IS_BEING_CREATED) {
            $zones = join(',', Pelican::$config['ZONE_MEDIA_SHOWROOM']);
            $tables = '#pref#_page_multi_zone'.$multi.' pz, #pref#_page p, #pref#_page_version pv';
            $and = ' AND pz.AREA_ID = '.$areaId.' AND pz.ZONE_ID IN ('.$zones.')';
            if (!empty($multi)) {
                $and = ' AND pz.AREA_ID = '.$areaId.' '
                    .'AND pz.ZONE_ORDER IN ('
                    .'SELECT pmz.ZONE_ORDER FROM #pref#_page_multi_zone pmz '
                    .'WHERE pmz.AREA_ID = '.$areaId.' '
                    .'AND pmz.ZONE_ID IN ('.$zones.') '
                    .'AND pmz.PAGE_VERSION = p.PAGE_CURRENT_VERSION '
                    .'AND pmz.PAGE_ID = p.PAGE_ID'
                    .')';
            }
            $bind[":PAGE_ID"] = $values["PAGE_TEMPLATE_ID"];
            $bind[":LANGUE_ID"] = $values["LANGUE_ID"];
            $sql = "SELECT DISTINCT pz.*
                       FROM $tables
                       WHERE
                       p.PAGE_ID = pv.PAGE_ID
                       AND pv.PAGE_VERSION = p.PAGE_CURRENT_VERSION
                       AND pv.STATE_ID = 4
                       AND
                        ( pz.page_id = :PAGE_ID
                          OR (p.PAGE_PARENT_ID = :PAGE_ID AND pz.PAGE_ID = p.PAGE_ID)
                          OR (p.PAGE_PARENT_ID IN (SELECT PAGE_ID FROM #pref#_page WHERE PAGE_PARENT_ID = :PAGE_ID OR PAGE_PARENT_ID IN (SELECT PAGE_ID FROM #pref#_page WHERE PAGE_PARENT_ID = :PAGE_ID ) ) AND pz.PAGE_ID = p.PAGE_ID))
                       AND pz.langue_id = :LANGUE_ID
                       AND pz.PAGE_VERSION = p.PAGE_CURRENT_VERSION
                       ".$and;

            $multiValues = self::$con->queryTab($sql, $bind);
        }

        return $multiValues;
    }

    /**
     * 
     * @param string $message
     * 
     * @return array
     */
    public static function getInfoBulle($message = "")
    {
        $infoBulle = array(
            'isIcon' => true,
            'message' => t($message)
        );

        return $infoBulle;
    }

    /**
     * 
     * @param int $pageId
     * @param int $pageVersion
     * 
     * @return array
     */
    public static function getBindListTranche($pageId, $pageVersion)
    {
        $bind = [
            ':PAGE_ID' => $pageId,
            ':PAGE_VERSION' => $pageVersion
        ];

        return $bind;
    }

    /**
     * 
     * @param int $pageId
     * 
     * @return int
     * 
     */
    public static function getPageVersion($pageId)
    {
        self::$con = Pelican_Db::getInstance();
        $pageVersion = ['PAGE_VERSION' => 0];
        if ($pageId != self::IS_BEING_CREATED) {
            $bind = [':PAGE_ID' => $pageId];
            $sql = "SELECT MAX(pv.PAGE_VERSION) as PAGE_VERSION FROM #pref#_page_version pv "
                ."WHERE pv.PAGE_ID = :PAGE_ID";

            $pageVersion = self::$con->queryRow($sql, $bind);
        }

        return $pageVersion['PAGE_VERSION'];
    }

    /**
     *
     * @param int $templatePageid
     *
     * @return int
     *
     */
    public static function getDynamicAreaIdByTemplateId($templatePageid)
    {
        $repo =  Pelican::getContainer()->get('object_manager')->getRepository('PSA\MigrationBundle\Entity\Area\PsaArea');
        /** @var \PSA\MigrationBundle\Entity\Area\PsaArea $area */
        $area = $repo->findOneDynamicAreaByTemplatePageId($templatePageid);

        if (null === $area) {
            throw new RuntimeException(
                sprintf('Failed to create new entity. No Dynamic Area found for Template Page Id : %d.', $templatePageid)
            );
        }

        return $area->getAreaId();
    }

    public function addCtaMultiWithPopinWithoutStyle(Ndp_Form $form, $values, $readO, $multi)
    {
        // Ajout formulaire des CTA

        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');

        if(!isset($values['CTADisable']) || $values['CTADisable']!=false){
            $ctaDisable = Pelican_Factory::getInstance('CtaDisable');
            $ctaComposite->addInputCta($ctaDisable);
        }else{
            $ctaComposite->setValueDefaultTypeCta(Ndp_Cta::SELECT_CTA);
        }

        $ctaRef = Pelican_Factory::getInstance('CtaRef');
        $ctaRef->typeStyle(0);
        $ctaRef->setReadO($readO);
        $ctaRef->hideStyle(true);

        $ctaRef->addTargetAvailable(
            '_popin', t('NDP_POPIN')
        );

        $ctaNew = Pelican_Factory::getInstance('CtaNew');
        $ctaNew->typeStyle(0);
        $ctaNew->hideStyle(true);
        $ctaNew->setReadO($readO);
        $ctaNew->addStyleAvailable('style_niveau4', t('NDP_STYLE_NIVEAU4'));
        $ctaNew->addTargetAvailable(
            '_popin', t('NDP_POPIN')
        );

        $ctaComposite->setCta($form, $values, $multi, '', true, $readO);
        
        $ctaComposite->addInputCta($ctaRef);
        $ctaComposite->addInputCta($ctaNew);

        return $ctaComposite->generate();
    }

    public function addCtaMultiWithoutStyle(Ndp_Form $form, $values, $readO, $multi)
    {
        // Ajout formulaire des CTA
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');

        if(!isset($values['CTADisable']) || $values['CTADisable']!=false){
            $ctaDisable = Pelican_Factory::getInstance('CtaDisable');
            $ctaComposite->addInputCta($ctaDisable);
        }else{
            $ctaComposite->setValueDefaultTypeCta(Ndp_Cta::SELECT_CTA);
        }

        $ctaRef = Pelican_Factory::getInstance('CtaRef');
        $ctaRef->typeStyle(0);
        $ctaRef->setReadO($readO);
        $ctaRef->hideStyle(true);

        $ctaNew = Pelican_Factory::getInstance('CtaNew');
        $ctaNew->typeStyle(0);
        $ctaNew->hideStyle(true);
        $ctaNew->setReadO($readO);
        $ctaNew->addStyleAvailable('style_niveau4', t('NDP_STYLE_NIVEAU4'));

        $ctaComposite->setCta($form, $values, $multi, '', true, $readO);

        $ctaComposite->addInputCta($ctaRef);
        $ctaComposite->addInputCta($ctaNew);

        return $ctaComposite->generate();
    }

    /**
     *
     * @param Ndp_Form $form
     * @param array $values
     * @param boolean $readO
     * @param string $multi
     *
     * @return string
     */
    public function addCtaMultiLDHidden(Ndp_Form $form, $values, $readO, $multi)
    {
        // Ajout formulaire des CTA
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $ctaComposite->setCta($form, $values, $multi, '', true, $readO);

        $ctaListeDeroulante = Pelican_Factory::getInstance('ListeDeroulante');
        $ctaComposite->addInputCta($ctaListeDeroulante);

        return $ctaComposite->generate();
    }

    /**
     * getLevelsType
     *
     * @return array
     */
    public static function getTypesLevels()
    {
        return array('LEVEL1_'=>'NDP_LEVEL');
    }

    /**
     * 
     * @param Pelican_Controller $controller
     * @param string $level
     * @param string $label
     * @param integer | array(min, max) $maxCta
     * @param integer $maxCtaLD
     * @param boolean $isCtaLabel
     * @param array $options
     * @return type
     */
    static function getLevelCta(Pelican_Controller $controller, $level, $label, $maxCta, $maxCtaLD, $isCtaLabel, $options = [])
    {
        $class = isset($options['CLASS']) ? $options['CLASS'] : __CLASS__;
        $path = isset($options['PATH']) ? $options['PATH'] : __FILE__;
        $method = isset($options['METHOD']) ? $options['METHOD'] : "addCtaMultiWithPopinWithoutStyle";

        $label = $controller->oForm->createLabel($label);

        /*         * CTA REF & NEW - BEGIN* */
        $typeForm = $level.self::TYPE_CTA;
        $isZoneDynamique = Ndp_Cta::isZoneDynamique($controller->zoneValues['ZONE_TEMPLATE_ID']);
        $ctaMulti = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC, $isZoneDynamique);
        $valuesCta = $ctaMulti
            ->hydrate($controller->zoneValues)
            ->setCtaType($typeForm)
            ->setReadO($options['CTA']['CTA_READONLY'] || $controller->readO)
            ->getValues();

        $multiHmvcCTA = $controller->oForm->createMultiHmvc(
            $controller->multi.$typeForm,
            t('ADD_FORM_CTA'),
            array(
                "path" => $path,
                "class" => $class,
                "method" => $method
            ),
            $valuesCta,
            $controller->multi.$typeForm,
            ($options['CTA']['CTA_READONLY'] || $controller->readO),
            $maxCta,
            true,
            true,
            $controller->multi.$typeForm,
            "values",
            "multi",
            "2",
            "",
            "",
            $isCtaLabel,
            (isset($options['CTA']) ? $options['CTA'] : $options)
        );

        /** CTA ListeDeroulante - BEGIN * */
        $typeFormCtaLD = $level.self::TYPE_CTA_LD;
        $valuesCtaLd = $ctaMulti->hydrate($controller->zoneValues)
            ->setCtaType($typeFormCta)
            ->setReadO($options['CTA']['CTA_READONLY'] || $controller->readO)
            ->setCtaDropDown(true)
            ->getValues();
        $titleLabel = t('NDP_ADD_FORM_CTA_LD');
        
        if(isset($options['CTA_LD']) && !empty($options['CTA_LD']['addButtonLabel'])){
            $titleLabel = [
                'multiTitle' => t('NDP_ADD_FORM_CTA_LD'),
                'multiAddButton'=> $options['CTA_LD']['addButtonLabel']
            ];
        }
        $multiHmvcCTALD = $controller->oForm->createMultiHmvc(
            $controller->multi.$typeFormCtaLD,
            $titleLabel,
            array(
                "path" => __FILE__,
                "class" => __CLASS__,
                "method" => "addCtaMultiLDHidden"
            ),
            $valuesCta,
            $controller->multi.$typeFormCtaLD,
            ($options['CTA']['CTA_READONLY'] || $controller->readO),
            array(0, $maxCtaLD),
            true,
            true,
            $controller->multi.$typeFormCtaLD,
            "values",
            "multi",
            "2",
            "",
            "",
            $isCtaLabel,
            (isset($options['CTA_LD']) ? $options['CTA_LD'] : $options)
        );

        /*         * Recuperation des styles communs  des CTA New/Ref/ListeDeroulante * */
        /** @var Ndp_Cta_CtaComposite $ctaComposite */
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $stylesAvailable = $ctaComposite->getStyles();

        /** Initialisation de la valeur de style commun des CTA de ce niveau * */
        if (count($valuesCta)) {
            $controller->zoneValues['STYLE'] = $valuesCta[0]['STYLE'];
        }
        if (count($valuesCtaLd)) {
            $controller->zoneValues['STYLE'] = $valuesCtaLd[0]['STYLE'];
        }
        if (empty($controller->zoneValues['STYLE'])) {
            $controller->zoneValues['STYLE'] =\PSA\MigrationBundle\Entity\Cta\PsaCta::STYLE_NIVEAU4;
        }

        /** definition de la liste des styles valides pour l'affichage du bloc CTADisabled/CTARef/CTANew * */
        $ctaListeDeroulante = Pelican_Factory::getInstance('ListeDeroulante');
        $ctaListeDeroulante->setReadO($options['CTA']['CTA_READONLY'] || $controller->readO);
        unset($stylesAvailable[$ctaListeDeroulante::CODE_STYLE]);
        $stylesAvailable = array_keys($stylesAvailable);

        $needed = false;
        if (isset($options['needed'])) {
            $needed = true;
        }

        /** génération du Selecteur de Style commun à tout les CTA de ce niveau * */
        $form = $ctaComposite->generateFormularStyle($controller->oForm, $controller->multi.$typeFormCtaLD, $controller->zoneValues['STYLE'], '_STYLE', $needed, ($options['CTA']['CTA_READONLY'] || $controller->readO));

        /** creation du container des CTA CTADisabled/CTARef/CTANew */
        $containerCTANewAndRef = self::addHeadContainer($stylesAvailable, $controller->zoneValues['STYLE'], $controller->multi.$typeFormCtaLD.'_STYLE');
        $containerCTANewAndRef .= $multiHmvcCTA;
        $containerCTANewAndRef .= self::addFootContainer();

        /** creation du container des CTA ListeDeroulante */
        $containerCTALD = self::addHeadContainer($ctaListeDeroulante::CODE_STYLE, $controller->zoneValues['STYLE'], $controller->multi.$typeFormCtaLD.'_STYLE');
        $containerCTALD .=$multiHmvcCTALD;
        $containerCTALD .= self::addFootContainer();

        return $label.$form.(isset($options['insertAfterStyle']) ? $options['insertAfterStyle'] : '').$containerCTANewAndRef.$containerCTALD;
    }

    /**
     *
     * @param int $contentId
     *
     * @return array
     */
    public static function getContentById($contentId)
    {
        $bind[":SITE_ID"] = $_SESSION[APP]['SITE_ID'];
        $bind[":LANGUE_ID"] = $_SESSION[APP]['LANGUE_ID'];
        $bind[":CONTENT_ID"] = $contentId;
        $sql = "
            SELECT c.CONTENT_ID as ID, cv.CONTENT_TITLE_BO as TITLE
            FROM #pref#_content c, #pref#_content_version cv
            WHERE
            c.CONTENT_ID = :CONTENT_ID
            AND cv.CONTENT_VERSION = c.CONTENT_CURRENT_VERSION
            AND cv.CONTENT_ID = c.CONTENT_ID
            AND c.SITE_ID = :SITE_ID
            AND c.LANGUE_ID = :LANGUE_ID";
        $values = self::$con->queryRow($sql, $bind);

        return $values;
    }
    
    /**
     * 
     * @return string
     */
    public static function getModeles()
    {
        /**
         *  Le substring sert à valider le LCDV6 sur 6 char par rapport au bouchon
         */
        $sql = "SELECT DISTINCT wsg.LCDV4, wsg.MODEL as LABEL
                FROM #pref#_model wsg
                INNER JOIN #pref#_page_version pv ON wsg.LCDV4 = SUBSTRING(pv.PAGE_GAMME_VEHICULE, 1, 4) COLLATE utf8_unicode_ci
                INNER JOIN #pref#_page p ON (p.PAGE_ID=pv.PAGE_ID AND pv.PAGE_VERSION IN ( p.PAGE_CURRENT_VERSION , p.PAGE_DRAFT_VERSION ) AND p.LANGUE_ID=pv.LANGUE_ID )
                INNER JOIN #pref#_template_page tp ON pv.TEMPLATE_PAGE_ID=tp.TEMPLATE_PAGE_ID
                INNER JOIN #pref#_page_type pt ON tp.PAGE_TYPE_ID=pt.PAGE_TYPE_ID
                "
            . " WHERE ".self::getRuleForShowroom()
            . " AND pv.PAGE_GAMME_VEHICULE IS NOT NULL"
            . " AND wsg.GENDER = 'VP'"
            . " ORDER BY wsg.MODEL ASC";

        return $sql;
    }
    
    /**
     *
     * @return string
     */
    public static function getSqlShowroomPage()
    {
        $sql ='SELECT pv.PAGE_ID, pv.PAGE_TITLE_BO
               FROM  #pref#_page p
               INNER JOIN #pref#_page_version pv ON (p.PAGE_ID=pv.PAGE_ID AND pv.PAGE_VERSION IN ( p.PAGE_CURRENT_VERSION , p.PAGE_DRAFT_VERSION ) AND p.LANGUE_ID=pv.LANGUE_ID )
               INNER JOIN #pref#_template_page tp ON pv.TEMPLATE_PAGE_ID=tp.TEMPLATE_PAGE_ID
               INNER JOIN #pref#_page_type pt ON tp.PAGE_TYPE_ID=pt.PAGE_TYPE_ID
               WHERE '.self::getRuleForShowroom().'
               ORDER BY pv.PAGE_TITLE_BO';

        return $sql;
    }

    /**
     * Recherche de toute les page de type showroom dont la page parent n'est pas une master page ou une showroom
     * @return string
     */
    public static function getRuleForShowroom()
    {
        $sql = '
            pv.LANGUE_ID = '.$_SESSION[APP]['LANGUE_ID'].'
            AND pt.PAGE_TYPE_CODE = "'.PsaPageTypesCode::PAGE_TYPE_CODE_G27.'"
            AND pv.STATE_ID != '.self::TO_DELETE.'
            AND p.SITE_ID = '.$_SESSION[APP]['SITE_ID'].'
            AND p.PAGE_PARENT_ID IN
             ('.self::getParentPageSubQuery().')
            ';

        return $sql;
    }

    /**
     * Recherche de toutes page qui ne sont pas des page de typeShowroom et master page showroom
     * @return string
     */
    public static function getParentPageSubQuery() {

        $sql = ' SELECT DISTINCT(tmpv.PAGE_ID)
                 FROM #pref#_page_version tmpv
                 INNER JOIN  #pref#_page tmp  ON (tmp.PAGE_ID=tmpv.PAGE_ID AND tmpv.PAGE_VERSION IN ( tmp.PAGE_CURRENT_VERSION , tmp.PAGE_DRAFT_VERSION ) AND tmp.LANGUE_ID=tmpv.LANGUE_ID )
                 INNER JOIN #pref#_template_page tmtp ON tmpv.TEMPLATE_PAGE_ID=tmtp.TEMPLATE_PAGE_ID
                 INNER JOIN psa_page_type tmpt ON tmtp.PAGE_TYPE_ID=tmpt.PAGE_TYPE_ID
                 WHERE  tmpv.STATE_ID != '.self::TO_DELETE.'
                 AND tmpt.PAGE_TYPE_CODE NOT IN ("'.PsaPageTypesCode::PAGE_TYPE_CODE_G27.'","'.PsaPageTypesCode::PAGE_TYPE_CODE_G02.'")

                 ';

        return $sql;
    }
    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function getJsForCheckingModel(Pelican_Controller $controller)
    {
        $return = "<script type='text/javascript'>
        function getModelFromGamme".$controller->multi."(model)
        {
            var label = $('#".$controller->multi.self::idForComment."');
            if (model.val() != '') {
                var modelName = model.find('option:selected').text();
                label.html('".t('NDP_MSG_SHOW_SILHOUETTE')." '+ modelName.slice(0, -12) +')');
            } else {
                label.html('".t('NDP_MSG_ERROR_NO_SILHOUETTE')."');
            }
        }
        $( document ).ready(function() {
            var model = $('#".self::fieldModel."');
            if (model.val() !='') {
                getModelFromGamme".$controller->multi."(model);
            }
            $('#".self::fieldModel."').click(function() {
                getModelFromGamme".$controller->multi."(model);
            });
        });
        </script>";

        return $return;
    }
    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function getJsForCheckingModelAccessory(Pelican_Controller $controller)
    {
        $values = Pelican::getContainer()->get('range_manager')->getGammesVehicules([], ['withSilhouette' => true]);


        $return = '<script type="text/javascript">
            function getModelFromGamme'.$controller->multi.'(model)
            {
                var label = $("#'.$controller->multi.self::idForComment.'");
                var modelsList = new Array();
       ';
        foreach ($values as $model => $modelName) {
               $return .= ' modelsList["'.$model.'"] = "'.substr($modelName,0 ,-18).'"; '."\n";
        }

        $return .="
            if (model.val() != '') {
                label.html('".t('NDP_MSG_SHOW_SILHOUETTE_ACCESSORY')." '+modelsList[model.val()]);
            } else {
                label.html('".t('NDP_MSG_ERROR_NO_SILHOUETTE')."');
            }
        }
        $( document ).ready(function() {
            var model = $('#".self::fieldModel."');
            if (model.val() !='') {
                getModelFromGamme".$controller->multi."(model);
            }
            $('#".self::fieldModel."').click(function() {
                getModelFromGamme".$controller->multi."(model);
            });
        });
        </script>";

        return $return;
    }

    /**
     * @return boolean
     */
    public static function isTranslator(){

        $readOnly = false;

        if ($_SESSION[APP]['PROFIL_LABEL'] == self::TRANSLATOR_PROFILE){
            $readOnly = true;
        }

        return $readOnly;
    }

}
