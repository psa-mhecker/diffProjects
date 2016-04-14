<?php

include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Page/Zone/Cta/Hmvc.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Page/Multi/Zone/Cta.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Page/Multi/Zone/Cta/Hmvc.php';

/**
 * Gestion d'un CTA.
 *
 * @author David Moate <david.moate@businessdecision.com>
 *
 * @since 02/03/2015
 */
class Ndp_Cta
{
    const DISABLE_CTA = '1';
    const SELECT_CTA = '2';
    const NEW_CTA = '3';
    const LISTE_DEROULANTE_CTA = '4';
    const MULTI_IS_DISPLAYED = 1;
    const PAGE_ID_NEW = '-2';
    const SIMPLE = 1;
    const HMVC = 2;
    const CONTENT = 3;
    const SIMPLE_INTO_MULTI_HMVC = 4;
    const HMVC_INTO_CTA = 5;
    const HMVC_INTO_CTA_HMVC = 6;
    const TYPE_FORM_CTA_LD = 'LISTE_DEROULANTE';
    const TYPE_FORM_CTA_FOR_REF = 'CTA_FOR_REF';
    const DELETE = 'state_5';

    protected $connection;
    private $pageId;
    private $langueId;
    private $pageVersion;
    private $ctaType = 'UNIQUE';
    private $ctaName;
    private $ctaId;
    private $ctaCtaId;
    private $areaId;
    private $zoneTemplateId;
    private $form;
    private $values = [];
    private $zoneValue = [];
    private $multi = '';
    private $readO = false;
    private $isMulti = false;
    private $targetByDefault = '_self';
    private $styleByDefault = 'style_niveau4';
    private $style = '';
    private $zoneOrder = null;
    private $parentId;
    private $parentType;
    private $modeCta;
    protected $hideTitle = false;
    protected $hideAction = false;
    protected $hideTarget = false;
    protected $hideStyle = false;
    protected $typeStyle = 0;
    protected $hideListeCta = false;
    private $stylesAvailable = array();
    private $targetsAvailable = array();
    private static $targetsTrad = ['_self' => 'NDP_INFO_SELF','_blank' => 'NDP_INFO_BLANK','_popin' => 'NDP_INFO_POPIN', 'Popin' => 'NDP_INFO_POPIN'];
    protected $typeCtaLD;
    protected $ctaDropDown;
    protected $hidePicto = true;
    protected $disabled = false;

    const TYPE_CTA = 'CTA';
    const TYPE_CTA_LD = 'CTA_LD';

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////// GESTION DES DIFFERENTES TABLES DES CTA /////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function __construct()
    {
        $this->connection = Pelican_Db::getInstance();
        $this->targetsAvailable = array(
            '_self' => t('NDP_SELF'),
            '_blank' => t('NDP_BLANK'),
        );
        $this->stylesAvailable = array(
            'style_niveau1' => t('NDP_STYLE_NIVEAU1'),
            'style_niveau2' => t('NDP_STYLE_NIVEAU2'),
            'style_niveau3' => t('NDP_STYLE_NIVEAU3'),
        );
    }

    /**
     * @param $targets
     *
     * @return string
     */
    public static function getToolTipMessage($targets)
    {
        $mess = [];
        if (is_array($targets) && !empty($targets)) {
            foreach ($targets as $key_target => $target) {
                if (isset(self::$targetsTrad[$key_target]) && !empty(self::$targetsTrad[$key_target])) {
                    $mess[$key_target] = t(self::$targetsTrad[$key_target]);
                }
            }
        }

        return implode("\n", $mess);
    }

    /**
     * @param bool $ctaDropDown
     *
     * @return $this
     */
    public function setCtaDropDown($ctaDropDown)
    {
        $this->ctaDropDown = $ctaDropDown;

        return $this;
    }

    /**
     * @return mixed
     */
    public function isCtaDropDown()
    {
        return $this->ctaDropDown;
    }

    /**
     * @param $type
     *
     * @return $this
     */
    public function setTypeCtaDropDown($type)
    {
        $this->typeCtaLD = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTypeCtaDropDown()
    {
        return $this->typeCtaLD;
    }

    /**
     * @return Pelican_Db
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param $connection
     *
     * @return $this
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPageId()
    {
        return $this->pageId;
    }

    /**
     * @param $pageId
     *
     * @return $this
     */
    public function setPageId($pageId)
    {
        $this->pageId = $pageId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLangueId()
    {
        return $this->langueId;
    }

    /**
     * @param $langueId
     *
     * @return $this
     */
    public function setLangueId($langueId)
    {
        $this->langueId = $langueId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPageVersion()
    {
        return $this->pageVersion;
    }

    /**
     * @param $pageVersion
     *
     * @return $this
     */
    public function setPageVersion($pageVersion)
    {
        $this->pageVersion = $pageVersion;

        return $this;
    }

    /**
     * @return string
     */
    public function getCtaType()
    {
        if (empty($this->ctaType)) {
            $this->ctaType = 'UNIQUE';
        }

        return $this->ctaType;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->getCtaType();
    }

    /**
     * @param string $ctaType
     *
     * @return $this
     */
    public function setCtaType($ctaType = 'UNIQUE')
    {
        $this->ctaType = $ctaType;

        return $this;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type = 'UNIQUE')
    {
        $this->ctaType = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCtaName()
    {
        return $this->ctaName;
    }

    /**
     * @param $ctaName
     *
     * @return $this
     */
    public function setCtaName($ctaName)
    {
        $this->ctaName = $ctaName;

        return $this;
    }

    /**
     * @return int
     */
    public function getCtaId()
    {
        if (empty($this->ctaId)) {
            $this->ctaId = 1;
        }

        return $this->ctaId;
    }

    /**
     * @param int
     *
     * @return $this
     */
    public function setCtaId($ctaId)
    {
        $this->ctaId = $ctaId;

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->getCtaId();
    }

    /**
     * @param int
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->ctaId = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCtaCtaId()
    {
        return $this->ctaCtaId;
    }

    /**
     * @param int
     *
     * @return $this
     */
    public function setCtaCtaId($ctaCtaId)
    {
        $this->ctaCtaId = $ctaCtaId;

        return $this;
    }

    /**
     * @return int
     */
    public function getAreaId()
    {
        return $this->areaId;
    }

    /**
     * @param int
     *
     * @return $this
     */
    public function setAreaId($areaId)
    {
        $this->areaId = $areaId;

        return $this;
    }

    /**
     * @return int
     */
    public function getZoneTemplateId()
    {
        return $this->zoneTemplateId;
    }

    /**
     * @param int
     *
     * @return $this
     */
    public function setZoneTemplateId($zoneTemplateId)
    {
        $this->zoneTemplateId = $zoneTemplateId;

        return $this;
    }

    /**
     * @return string
     */
    public function getTargetByDefault()
    {
        return $this->targetByDefault;
    }

    /**
     * @param string
     *
     * @return \Ndp_Cta
     */
    public function setTargetByDefault($targetByDefault)
    {
        $this->targetByDefault = $targetByDefault;

        return $this;
    }

    /**
     * @return int
     */
    public function getZoneOrder()
    {
        return $this->zoneOrder;
    }

    /**
     * @param int $zoneOrder
     *
     * @return $this
     */
    public function setZoneOrder($zoneOrder)
    {
        $this->zoneOrder = $zoneOrder;

        return $this;
    }

    public function getParentId()
    {
        return $this->parentId;
    }

    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    public function getParentType()
    {
        return $this->parentType;
    }

    public function setParentType($parentType)
    {
        $this->parentType = $parentType;

        return $this;
    }

    /**
     * @return string
     */
    public function getStyleByDefault()
    {
        return $this->styleByDefault;
    }

    /**
     * @param string
     *
     * @return $this
     */
    public function setStyleByDefault($styleByDefault)
    {
        $this->styleByDefault = $styleByDefault;

        return $this;
    }

    /**
     * @param int
     *
     * @return bool
     */
    public static function isZoneDynamique($templateId)
    {
        return (empty($templateId)) ? true : false;
    }

    /**
     * @return bool
     */
    public function isDisabled()
    {
        return $this->disabled;
    }

    /**
     * @param bool $disabled
     *
     * @return Ndp_Cta
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * @param array $zoneValues
     *
     * @return $this
     */
    public function hydrate(array $zoneValues)
    {
        if (!empty($zoneValues['PAGE_ID'])) {
            $this->setPageId($zoneValues['PAGE_ID']);
        }
        if (!empty($zoneValues['LANGUE_ID'])) {
            $this->setLangueId($zoneValues['LANGUE_ID']);
        }
        if (!empty($zoneValues['PAGE_VERSION'])) {
            $this->setPageVersion($zoneValues['PAGE_VERSION']);
        }
        if (!empty($zoneValues['ZONE_TEMPLATE_ID'])) {
            $this->setZoneTemplateId($zoneValues['ZONE_TEMPLATE_ID']);
        }
        if (!empty($zoneValues['AREA_ID'])) {
            $this->setAreaId($zoneValues['AREA_ID']);
        }
        if (!empty($zoneValues['PAGE_ZONE_CTA_TYPE'])) {
            $this->setCtaType($zoneValues['PAGE_ZONE_CTA_TYPE']);
        }
        if (!empty($zoneValues['PAGE_ZONE_CTA_ID'])) {
            $this->setCtaId($zoneValues['PAGE_ZONE_CTA_ID']);
        }
        if (!empty($zoneValues['ZONE_ORDER'])) {
            $this->setZoneOrder($zoneValues['ZONE_ORDER']);
        }

        return $this;
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////// GESTION DES INPUT DES CTA /////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @param bool $hideTitle
     *
     * @return $this
     */
    public function hideTitle($hideTitle = false)
    {
        $this->hideTitle = $hideTitle;

        return $this;
    }

    /**
     * @param bool $hideAction
     *
     * @return $this
     */
    public function hideAction($hideAction = false)
    {
        $this->hideAction = $hideAction;

        return $this;
    }

    /**
     * @param bool $hideTarget
     *
     * @return $this
     */
    public function hideTarget($hideTarget = false)
    {
        $this->hideTarget = $hideTarget;

        return $this;
    }

    /**
     * @param bool
     *
     * @return $this
     */
    public function hideStyle($hideStyle = false)
    {
        $this->hideStyle = $hideStyle;

        return $this;
    }

    /**
     * @param int $typeStyle
     *
     * @return $this
     */
    public function typeStyle($typeStyle = 0)
    {
        $this->typeStyle = $typeStyle;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsMulti()
    {
        return $this->isMulti;
    }

    /**
     * @param bool $isMulti
     *
     * @return $this
     */
    public function setIsMulti($isMulti)
    {
        $this->isMulti = $isMulti;

        return $this;
    }

    /** .
     * @param Ndp_Form $form
     *
     * @return $this
     */
    public function setForm(Ndp_Form $form)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * @return Ndp_Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param string $multi
     *
     * @return $this
     */
    public function setMulti($multi)
    {
        $this->multi = $multi.$this->getCtaType();
        if (true === $this->getIsMulti()) {
            $this->multi = $multi;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getMulti()
    {
        return $this->multi;
    }

    /**
     * @param $values
     *
     * @return $this
     */
    public function setValues($values)
    {
        $this->values = $values;

        return $this;
    }

    /**
     * @param $values
     *
     * @return mixed
     */
    public function getValues($values)
    {
        if (true === $this->getIsMulti()) {
            return $values;
        }

        return $values[0];
    }

    /**
     * @return array
     */
    public function getZoneValue()
    {
        return $this->zoneValue;
    }

    /**
     * @param array $zoneValue
     *
     * @return $this
     */
    public function setZoneValue(array $zoneValue = [])
    {
        $this->zoneValue = $zoneValue;

        return $this;
    }

    /**
     * @return bool
     */
    public function getReadO()
    {
        return $this->readO;
    }

    /**
     * @param $readO
     *
     * @return $this
     */
    public function setReadO($readO)
    {
        $this->readO = $readO;

        return $this;
    }

    /**
     * @param string $style
     *
     * @return $this
     */
    public function setStyle($style)
    {
        if (!empty($style)) {
            $this->style = $style;
        }

        return $this;
    }

    /**
     *  @return string $style
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * @return string
     */
    public function getModeCta()
    {
        return $this->modeCta;
    }

    /**
     * @param string $modeCta
     * 
     * @return $this
     */
    public function setModeCta($modeCta)
    {
        $this->modeCta = $modeCta;

        return $this;
    }

    /**
     *  @param string
     *  @param string
     *
     *  @return $this
     */
    public function addStyleAvailable($key, $label)
    {
        $this->stylesAvailable[$key] = $label;

        return $this;
    }

    /**
     *  @param array $styles
     *
     *  @return $this
     */
    public function setStylesAvailable($styles)
    {
        $this->stylesAvailable = $styles;

        return $this;
    }

    /**
     *  @param string
     *
     *  @return $this
     */
    public function removeStyleAvailable($key)
    {
        unset($this->stylesAvailable[$key]);

        return $this;
    }

    /**
     *  @return array
     */
    public function getStylesAvailable()
    {
        return $this->stylesAvailable;
    }

    /**
     * @return string targetByDefault
     */
    public function getTargetDefault()
    {
        return $this->targetByDefault;
    }

    /**
     * @param string $targetByDefault
     *
     *  @return $this
     */
    public function setTargetDefault($targetByDefault)
    {
        $this->targetByDefault = $targetByDefault;

        return $this;
    }

    /**
     *  @param string
     *  @param string
     *
     *  @return $this
     */
    public function addTargetAvailable($key, $label)
    {
        $this->targetsAvailable[$key] = $label;

        return $this;
    }

    /**
     * @param $key
     * @return $this
     */
    public function removeTargetAvailable($key)
    {
        unset($this->targetsAvailable[$key]);

        return $this;
    }

    /**
     *  @return array
     */
    public function getTargetsAvailable()
    {
        return $this->targetsAvailable;
    }

    /**
     * @param bool $isMulti
     *
     * @return $this
     */
    public function isMulti($isMulti = false)
    {
        $this->isMulti = $isMulti;

        return $this;
    }

    /**
     * @return bool
     */
    public function getHidePicto()
    {
        return $this->hidePicto;
    }

    /**
     * @param bool $hidePicto
     *
     * @return $this
     */
    public function setHidePicto($hidePicto)
    {
        $this->hidePicto = $hidePicto;

        return $this;
    }

    /**
     * @param $multi
     * @param $ctaStatus
     * @param $type
     *
     * @return string
     */
    public function addTbodyCta($multi, $ctaStatus, $type)
    {
        $display = 'display:none;';
        $class = ' isNotRequired';
        if ($type === $ctaStatus) {
            $display = '';
            $class = '';
        }

        return sprintf(
            '<tbody id="%scontainer_%s_cta" style="%s" class="%scontainer_cta%s">', $multi, $type, $display, $multi, $class
        );
    }

    /**
     * @return string
     */
    public function addFootTbodyCta()
    {
        return '</tbody>';
    }

    /**
     * @param $cta
     *
     * @return string
     */
    public function getJsContainer($cta)
    {
        $js = '
            onclick="
                $(\'.'.$cta->multi.'container_cta\').hide();
                var val=$(this).val();
                $(\'#'.$cta->multi.'container_\'+val+\'_cta\').show();
                $(\'.'.$cta->multi.'container_cta\').addClass(\'isNotRequired\');
                $(\'#'.$cta->multi.'container_\'+val+\'_cta\').removeClass(\'isNotRequired\');
            "';

        return $js;
    }

    /**
     * @param $values
     * @param $tableName
     *
     * @return $this
     */
    public function saveCta($values, $tableName)
    {
        $values['PAGE_ZONE_CTA_TYPE'] = $this->getCtaType();
        $typeCta = $values['PAGE_ZONE_CTA_STATUS'];
        if (isset($typeCta)) {
            switch ($typeCta) {
                case self::DISABLE_CTA:
                    $this->addDisableMultiCta($values, $tableName);
                    break;
                case self::SELECT_CTA:
                    $this->addSelectedMultiCta($values, $tableName);
                    break;
                case self::NEW_CTA:
                    $this->addNewMultiCta($values, $tableName);
                    break;
                case self::LISTE_DEROULANTE_CTA:
                    $this->addNewListeDeroulante($values, $tableName);
                    break;
                default:
                    break;
            }
        }

        return $this;
    }

    /**
     *  Create as DropDown CTA and then save relation with multi cta.
     *
     * @param array  $values
     * @param string $tableName
     *
     * @return $this
     */
    public function addNewListeDeroulante($values, $tableName)
    {
        $isZoneDynamique = Ndp_Multi::isZoneDynamique($values['ZONE_TEMPLATE_ID']);
        $saved = Pelican_Db::$values;

        if (!$this->getIsMulti()) {
            $values = $this->readCtaSimple($values);
        }

        $values['LD']['TITLE'] = $values['LD']['PAGE_ZONE_CTA_LABEL'];
        $type_saved = $this->getType();
        $this->setType(str_replace(self::TYPE_CTA_LD, self::TYPE_CTA, $type_saved));
        $this->addMultiCta($values, $tableName, $values['LD']);

        if ($this->getIsMulti()) {
            $listCta = $this->readCtaMulti(self::TYPE_FORM_CTA_LD.'_Niveau1', '_');
        } else {
            $listCta = $this->readCtaMulti(self::TYPE_FORM_CTA_LD);
        }

        $parentId = Pelican_Db::$values['PAGE_ZONE_CTA_ID'];
        $id = 1;
        foreach ($listCta as $valueCta) {
            /** @var Ndp_Page_Zone_Cta_Cta|Ndp_Page_Multi_Zone_Cta_Cta $ctaMulti */
            $ctaMulti = Ndp_Cta_Factory::getInstance(self::HMVC_INTO_CTA, $isZoneDynamique);
            Pelican_Db::$values = $valueCta;
            $ctaMulti->hydrate($values)
                ->setParentId($parentId)
                ->setParentType($values['TYPE'])
                ->setType($this->getType())
                ->setId($id)
                ->delete()
                ->save();
            ++$id;
        }
        $this->setType($type_saved);
        Pelican_Db::$values = $saved;

        return $this;
    }

    /**
     *  Create as CTA and then save relation with multi cta.
     *
     * @param $values
     * @param $tableName
     */
    public function addNewMultiCta($values, $tableName)
    {
        $valuesCta['IS_REF'] = $values['IS_REF'];

        $this->addMultiCta($values, $tableName, $values['NEW_CTA']);
    }

    /**
     * save the the relation between a multi and a cta.
     *
     * @param $values
     * @param $tableName
     */
    public function addDisableMultiCta($values, $tableName)
    {
        $this->addMultiCta($values, $tableName, $values['DISABLE_CTA']);
    }

    /**
     * save the the relation between a multi and a cta.
     *
     * @param $values
     * @param $tableName
     */
    public function addSelectedMultiCta($values, $tableName)
    {
        $this->addMultiCta($values, $tableName, $values['SELECT_CTA'], false);
    }

    /**
     * @param array  $values
     * @param string $tableName
     * @param array  $ctaValues
     * @param bool   $saveTableCta
     */
    public function addMultiCta($values, $tableName, $ctaValues = [], $saveTableCta = true)
    {
        $saved = Pelican_Db::$values;

        Pelican_Db::$values = array_merge($values, $ctaValues);
        $style = $this->getStyle();

        if (!empty($style)) {
            Pelican_Db::$values['STYLE'] = $style;
        }
        $type = $this->getType();
        if (!empty($type)) {
            Pelican_Db::$values['PAGE_ZONE_CTA_TYPE'] = $type;
        }

        if (true === $saveTableCta) {
            $id = $this->saveTableCta(Pelican_Db::$values);
            Pelican_Db::$values['CTA_ID'] = $id;
        }
        if ($_SESSION[APP]['form_button'] != self::DELETE) {
            $this->getConnection()->insertQuery(sprintf('#pref#_%s', $tableName));
        }

        Pelican_Db::$values = $saved;
    }

    /**
     *  Save data to CTA table.
     *
     * @param array $cta
     *
     * @return int
     */
    public function saveTableCta($cta)
    {
        $saved = Pelican_Db::$values;
        $where = null;
        $id = null;
        Pelican_Db::$values = array_merge(Pelican_Db::$values, $cta);

        Pelican_Db::$values['SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        Pelican_Db::$values['USED_COUNT'] = 0;
        Pelican_Db::$values['IS_REF'] = 0;
        //Si on a pas de CTA_ID ou si on a un CTA_ID mais qui fait parti du Référentiel alors on crée un nouveau CTA
        if (!isset($cta['CTA_ID']) || empty($cta['CTA_ID']) || (isset($cta['CTA_ID']) && !empty($cta['CTA_ID']) && true === $this->isRefByCtaId($cta['CTA_ID']))) {
            $where = ' ID = 0 ';
        }
        //Si on a un CTA mais qui ne fait pas parti du référentiel alors on peut le modifier
        if (isset($cta['CTA_ID']) && !empty($cta['CTA_ID']) && true !== $this->isRefByCtaId($cta['CTA_ID'])) {
            $where = ' ID = '.intval($cta['CTA_ID']);
            Pelican_Db::$values['ID'] = $cta['CTA_ID'];
            $id = $cta['CTA_ID'];
        }
        $this->getConnection()->replaceQuery('#pref#_cta', $where);

        if (!isset($cta['CTA_ID']) || empty($cta['CTA_ID']) || (isset($cta['CTA_ID']) && !empty($cta['CTA_ID']) && true === $this->isRefByCtaId($cta['CTA_ID']))) {
            $id = $this->getConnection()->getLastOid();
        }
        Pelican_Db::$values = $saved;

        return $id;
    }

    /**
     * @param $ctaId
     *
     * @return bool
     */
    public function isRefByCtaId($ctaId)
    {
        $result = null;
        if (!empty($ctaId)) {
            $bind[':CTA_ID'] = $ctaId;
            $sqlCta = 'SELECT IS_REF FROM #pref#_cta WHERE ID =:CTA_ID';
            $result = $this->getConnection()->queryRow($sqlCta, $bind);
        }
        if ($result['IS_REF'] == '1') {
            return true;
        }

        return false;
    }

    /**
     * Permet de récupérer tous les champs d'un cta
     * Et de pouvoir convertir un champs de type tableau en string (ex utilisation d'une liste associative).
     *
     * @param array
     *
     * @return array
     */
    public function addFieldCta(array $cta)
    {
        foreach ($cta as $key => $value) {
            if (is_string($key) && !empty($key)) {
                $cta[$key] = $value;
            }
        }

        return $cta;
    }

    /**
     * Permet de réorganiser les Ids des cta.
     *
     * @param array $ctaValues
     *
     * @return array
     */
    public function setAllCtaId(array $ctaValues)
    {
        $pageZoneCtaIds = [];
        foreach ($ctaValues as $key => $values) {
            if (isset($values['PAGE_ZONE_CTA_ID']) && is_numeric($values['PAGE_ZONE_CTA_ID'])) {
                $pageZoneCtaIds[] = intval($values['PAGE_ZONE_CTA_ID']);
            }
        }
        $nbIdCta = !empty($pageZoneCtaIds) ? max($pageZoneCtaIds) : 0;
        foreach ($ctaValues as $key => $values) {
            if (!isset($values['PAGE_ZONE_CTA_ID'])) {
                $ctaValues[$key]['PAGE_ZONE_CTA_ID'] = ++$nbIdCta;
            }
        }

        return $ctaValues;
    }

    /**
     * @param array $values
     *
     * @return array
     */
    public function readCtaSimple($values)
    {
        $cleanData = [];
        $type = $this->getType();
        foreach ($values as $key => $value) {
            if (strpos($key, $type) !== false) {
                $keyClean = str_replace($type, '', $key);
                $cleanData[$keyClean] = $value;
            } else {
                if (!isset($cleanData[$key])) {
                    $cleanData[$key] = $value;
                }
            }
        }

        return $cleanData;
    }

    /**
     * @param string
     * @param string
     *
     * @return array
     */
    public function readCtaMulti($type, $suffix = '')
    {
        $cleanData = [];
        $multiValues = Pelican_Db::$values;
        foreach ($multiValues as $key => $value) {
            if (strpos($key, $type) !== false) {
                $keyClean = str_replace($type.$suffix, '', $key);
                $index = explode('_', $keyClean);
                $firstIndex = array_shift($index);
                if (preg_match('/[0-9]+/', $firstIndex)) {
                    $firstIndex = intval($firstIndex);
                    if (!isset($cleanData[$firstIndex])) {
                        $cleanData[$firstIndex] = [];
                    }
                    $cleanData[$firstIndex] = array_merge($cleanData[$firstIndex], $value);
                    if (!empty($index[0])) {
                        $cleanData[$firstIndex][implode('_', $index)] = $value;
                    }
                }
            }
        }

        return $cleanData;
    }
}
