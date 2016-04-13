<?php
/**
 * Fonction de génération de mise en page des blocs
 *
 * @package Pelican
 * @subpackage Layout
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @since 01/12/2008
 * @link http://www.interakting.com
 */

/**
 * Fonction de génération de mise en page des blocs
 *
 * @package Pelican
 * @subpackage Layout
 * @author Gilles LENORMAND <gilles.lenormand@businessdecision.com>
 * @since 01/12/2008
 */
class Pelican_Layout_Portal extends Pelican_Layout_Desktop
{
    /**
     * @access protected
     * @var __TYPE__ __DESC__
     */
    protected $_type = 'desktop';
    const AREA_PREFIX = 'aid_';
    const ZTID_PREFIX = 'ztid_';
    const HANDLE_PREFIX = 'ztid_handle_';
    const DROPPABLE_AREA_CSS_CLASS = 'portal-droppable';
    const NON_DROPPABLE_AREA_CSS_CLASS = 'non_portal-droppable';
    const AREA_ACTIVE_CSS_CLASS = 'portal-droppable-active-container';
    const AREA_HOVER_CSS_CLASS = 'hover_portal-droppable';
    const ZONE_CSS_CLASS = 'portal-moveable-zone  ui-corner-all';
    const FIXED_ZONE_CSS_CLASS = 'non_portal-moveable-zone';
    const ZONE_CONTENT_PREFIX = 'ztid_content_';
    const FIXED_ZONE_CONTENT_CSS_CLASS = 'portal-non-moveable';
    const ZONE_CONTENT_CSS_CLASS = 'portal-moveable-content';
    const EDITABLE_ZONE_CONTENT_CSS_CLASS = 'editable_zone_content';
    const HANDLE_CSS_CLASS = 'portal-moveable-handle portlet-header ui-widget-header ui-corner-all';
    const ZONE_ADD_PREFIX = 'aid_zone_add_';
    const ZONE_EDIT_PREFIX = 'ztid_zone_edit_';
    const ZONE_REDUCE_PREFIX = 'ztid_zone_reduce_';
    const ZONE_DELETE_PREFIX = 'ztid_zone_delete_';
    const ZONE_TITLE_PREFIX = 'ztid_zone_title_';
    const ZONE_ACTIONS_PREFIX = 'ztid_zone_actions_';
    const AREA_ACTIONS_PREFIX = 'aid_area_actions_';
    const ZONE_EDIT_CSS_CLASS = 'portal-moveable-edit';
    const ZONE_ADD_CSS_CLASS = 'portal-droppable-add';
    const ZONE_TITLE_CSS_CLASS = 'portal-moveable-title';
    const ZONE_ACTIONS_CSS_CLASS = 'portal-moveable-actions';
    const AREA_ACTIONS_CSS_CLASS = 'portal-droppable-actions';
    const ZONE_DELETE_CSS_CLASS = 'portal-moveable-delete';
    const ZONE_REDUCE_CSS_CLASS = 'portal-moveable-reduce';

    /**
     * Utilisateur courant (instance de User : lib/Pelican/User/Portal.php)
     *
     * @access protected
     * @var User
     */
    protected $oUser;

    /**
     * Les id des area qui peuvent recevoir des blocs déplaçables
     *
     * @access protected
     * @var array
     */
    protected $_aAllDroppableAreaId;

    /**
     * Les id des blocs qui peuvent être déplacés
     *
     * @access protected
     * @var array
     */
    protected $_aAllMoveableZtid;

    /**
     * Les blocs personnalisables
     *
     * @access protected
     * @var array
     */
    protected $_tabZonesPerso;

    /**
     * __DESC__
     *
     * @access public
     * @param  __TYPE__ $aPage __DESC__
     * @return __TYPE__
     */
    public function __construct($aPage)
    {
        require_once 'portal.ini.php';
        parent::__construct($aPage);
        $this->getView()->getHead()->setCss(Pelican::$config['LIB_PATH'] . Pelican::$config['LIB_FRONT'] . "/portal/css/style.css");
        $this->getView()->getHead()->setJquery("ui.sortable");
        $this->getView()->getHead()->setJquery("ui.effects.drop");
        $this->getView()->getHead()->setJs(Pelican::$config['LIB_PATH'] . Pelican::$config['LIB_FRONT'] . "/portal/js/portal.js");
        if ($_POST['name'] || $_POST['pass']) {
            $_POST['userid'] = $_POST['name'];
            $_POST['userpassword'] = $_POST['pass'];
        }
        if ($_POST['userid'] || $_POST['userpassword']) {
            $oAuthResult = $this->getUser()->login($_POST['userid'], $_POST['userpassword']);
        }
        $this->_aAllMoveableZtid = array();
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function getModules()
    {
        if ($this->aPage) {
            //récupération du squelette de page : zones (areas)
            $return = Pelican_Cache::fetch("Portal/Page/Zone", array($_GET["pid"], $_SESSION[APP]['LANGUE_ID'], Pelican::getPreviewVersion()));
            $this->tabAreas = $return["areas"];
            $this->tabZones = $return["zones"];
            //récupération des blocs persos : blocs (zones)
            $return2 = Pelican_Cache::fetch("Portal/Page/User", array($_GET["pid"], $this->getUser()->get('id'), $_SESSION[APP]['LANGUE_ID'], $return["areas"][0]["PAGE_VERSION"], $return["areas"][0]["TEMPLATE_PAGE_ID"]));
            $this->_tabZonesPerso = $return2;
            $this->generateZone();
        }
        $data["FORCED_ROOT"] = Pelican::$config['LIB_ROOT'] . Pelican::$config['LIB_FRONT'] . '/portal';

        return Pelican_Request::cachedCall("/module/portal/Portal/header", $data, false);
        return Pelican_Request::cachedCall("/module/portal/Portal/layout", $data, false);
    }

    /**
     * __DESC__
     *
     * @access private
     * @param  __TYPE__ $data __DESC__
     * @return __TYPE__
     */
    private function getMoveableZone($data)
    {
        $this->_aAllMoveableZtid[] = $data['ZONE_TEMPLATE_ID'];
        $content = self::buildNonMoveableZone($data);
        if ($this->getUser()->isLoggedIn()) {
            $return = self::buildMoveableZone($data, $content);
        } else {
            $return = $content;
        }

        return $return;
    }

    /**
     * __DESC__
     *
     * @access private
     * @param  __TYPE__ $data __DESC__
     * @return __TYPE__
     */
    private function buildNonMoveableZone($data)
    {
        $return = $this->getDirectZone($data, false);

        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param  __TYPE__ $data    __DESC__
     * @param  __TYPE__ $content __DESC__
     * @return __TYPE__
     */
    public function buildMoveableZone($data, $content)
    {
        $return = self::getMoveableZoneStart($data);
        $return.= $content;
        $return.= self::getMoveableZoneEnd($data);

        return $return;
    }

    /**
     * __DESC__
     *
     * @static
     * @access public
     * @param  __TYPE__ $data __DESC__
     * @return __TYPE__
     */
    public static function getMoveableZoneStart($data)
    {
        $sCode = '';
        //chaque bloc draggable est précédé d'un bloc invisible de réception
        //$sCode .= '<div class="receiver" id="before_'.$data["ZONE_TEMPLATE_ID"].'"></div>';
        $sCode.= '<div id="' . self::ZTID_PREFIX . $data['ZONE_TEMPLATE_ID'] . '" class="' . self::ZONE_CSS_CLASS . '">';
        //barre de titre (+ handler déplacement)
        if ($this->getUser()->isLoggedIn()) {
            $aAction[] = Pelican_Html::span(array(id => self::ZONE_DELETE_PREFIX . $data['ZONE_TEMPLATE_ID'], "class" => self::ZONE_DELETE_CSS_CLASS . " ui-icon ui-icon-close", onclick => self::getJsDeleteFunction($data), title => "delete"));
        }
        if ($this->getUser()->isLoggedIn() && $data['ZONE_BO_PATH']) {
            $aAction[] = Pelican_Html::span(array(id => self::ZONE_EDIT_PREFIX . $data['ZONE_TEMPLATE_ID'], "class" => self::ZONE_EDIT_CSS_CLASS . " ui-icon ui-icon-pencil", onclick => self::getJsEditFunction($data), title => "edit"));
        }
        $action = Pelican_Html::div(array(id => self::ZONE_ACTIONS_PREFIX . $data['ZONE_TEMPLATE_ID'], "class" => self::ZONE_ACTIONS_CSS_CLASS), implode('', $aAction));
        $toggle = Pelican_Html::span(array(id => self::ZONE_REDUCE_PREFIX . $data['ZONE_TEMPLATE_ID'], "class" => self::ZONE_REDUCE_CSS_CLASS . " ui-icon ui-icon-triangle-1-s", onclick => self::getJsReduceFunction($data), title => "min-max"));
        $title = ($data['ZONE_TITRE'] ? $data['ZONE_TITRE'] : ($data['ZONE_TEMPLATE_LABEL'] ? $data['ZONE_TEMPLATE_LABEL'] : $data['ZONE_LABEL']));
        $title = Pelican_Html::div(array(id => self::ZONE_TITLE_PREFIX . $data['ZONE_TEMPLATE_ID'], "class" => self::ZONE_TITLE_CSS_CLASS), $title);
        $handle = Pelican_Html::div(array(id => self::HANDLE_PREFIX . $data['ZONE_TEMPLATE_ID'], "class" => self::HANDLE_CSS_CLASS, "ondblclick" => self::getJsReduceFunction($data)), $action . $toggle . $title);
        $sCode.= $handle . '<div id="' . self::ZONE_CONTENT_PREFIX . $data['ZONE_TEMPLATE_ID'] . '" class="' . self::ZONE_CONTENT_CSS_CLASS . '">';

        return $sCode;
    }

    /**
     * __DESC__
     *
     * @static
     * @access public
     * @param  __TYPE__ $data __DESC__
     * @return __TYPE__
     */
    public static function getEditableZoneStart($data)
    {
        $sCode = '';
        $sCode.= '<div id="editable" style="height:100%; position:relative;">';
        $title = Pelican_Html::div(array(id => self::ZONE_TITLE_PREFIX . $data['ZONE_TEMPLATE_ID'], "class" => self::ZONE_TITLE_CSS_CLASS), ($data['USER_ZONE_TITRE'] ? $data['USER_ZONE_TITRE'] : $data['ZONE_TITRE']));
        $action = '';
        if ($this->getUser()->isLoggedIn()) {
            $action = Pelican_Html::span(array(id => editable_close, "class" => self::ZONE_DELETE_CSS_CLASS . " ui-icon ui-icon-close", onclick => self::getJsCloseFunction($data), title => "close"));
        }
        $editable_handle = Pelican_Html::div(array(id => self::ZONE_ACTIONS_PREFIX . $data['ZONE_TEMPLATE_ID'], "class" => self::ZONE_ACTIONS_CSS_CLASS), $action);
        $sCode.= Pelican_Html::div(array(id => "editable_handle", "class" => self::HANDLE_CSS_CLASS), $editable_handle . $title);
        $sCode.= '<div id="editable_content" class="' . self::EDITABLE_ZONE_CONTENT_CSS_CLASS . '">';

        return $sCode;
    }

    /**
     * __DESC__
     *
     * @static
     * @access public
     * @param  __TYPE__ $data __DESC__
     * @return __TYPE__
     */
    public static function getNonMoveableZoneStart($data)
    {
        $sCode = '';
        $sCode.= '<div id="' . self::ZTID_PREFIX . $data['ZONE_TEMPLATE_ID'] . '" class="' . self::FIXED_ZONE_CSS_CLASS . '">';
        $sCode.= '<div id="' . self::ZONE_CONTENT_PREFIX . $data['ZONE_TEMPLATE_ID'] . '" class="' . self::FIXED_ZONE_CONTENT_CSS_CLASS . '">';

        return $sCode;
    }

    /**
     * __DESC__
     *
     * @static
     * @access public
     * @param  __TYPE__ $data __DESC__
     * @return __TYPE__
     */
    public static function getJsDeleteFunction($data)
    {
        $sJs = '';
        $sJs.= 'deleteBlock(\'' . self::ZTID_PREFIX . $data['ZONE_TEMPLATE_ID'] . '\',\'' . self::ZONE_CONTENT_PREFIX . $data['ZONE_TEMPLATE_ID'] . '\',\'' . $data["ZONE_TEMPLATE_ID"] . '\',\'' . $data["PAGE_ID"] . '\');';

        return $sJs;
    }

    /**
     * __DESC__
     *
     * @static
     * @access public
     * @param  __TYPE__ $data __DESC__
     * @return __TYPE__
     */
    public static function getJsReduceFunction($data)
    {
        $sJs = '';
        $sJs.= 'reduceBlock(\'' . self::ZONE_CONTENT_PREFIX . $data['ZONE_TEMPLATE_ID'] . '\',\'' . $data["ZONE_TEMPLATE_ID"] . '\',\'' . $data["PAGE_ID"] . '\');';

        return $sJs;
    }

    /**
     * __DESC__
     *
     * @static
     * @access public
     * @param  __TYPE__ $data __DESC__
     * @return __TYPE__
     */
    public static function getJsEditFunction($data)
    {
        $sJs = '';
        $sJs.= 'editBlock(\'' . self::ZONE_CONTENT_PREFIX . $data['ZONE_TEMPLATE_ID'] . '\',\'' . $data["ZONE_TEMPLATE_ID"] . '\',\'' . $data["PAGE_ID"] . '\',\'' . $data["PAGE_VERSION"] . '\',\'' . $data['LANGUE_ID'] . '\');';

        return $sJs;
    }

    /**
     * __DESC__
     *
     * @static
     * @access public
     * @param  __TYPE__ $data __DESC__
     * @return __TYPE__
     */
    public static function getJsCloseFunction($data)
    {
        $sJs = '';
        $sJs.= 'closeTopLayerSmarty(true);';

        return $sJs;
    }

    /**
     * __DESC__
     *
     * @access private
     * @param  __TYPE__ $area __DESC__
     * @return __TYPE__
     */
    private function getJsAddFunction($area)
    {
        $sJs = '';
        $sJs.= 'addZone(\'' . $this->aPage["PAGE_ID"] . '\',\'' . $this->aPage["TEMPLATE_PAGE_ID"] . '\',\'' . $area["AREA_ID"] . '\');';

        return $sJs;
    }

    /**
     * __DESC__
     *
     * @static
     * @access public
     * @param  __TYPE__ $data __DESC__
     * @return __TYPE__
     */
    public static function getMoveableZoneEnd($data)
    {
        $sCode = '';
        $sCode.= '</div>';
        $sCode.= '</div>';
        //$sCode.='<!-- end ztid '.$data['ZONE_TEMPLATE_ID'].' -->';
        return $sCode;
    }

    /**
     * __DESC__
     *
     * @static
     * @access public
     * @param  __TYPE__ $data __DESC__
     * @return __TYPE__
     */
    public static function getEditableZoneEnd($data)
    {
        $sCode = '';
        $sCode.= '</div>';
        $sCode.= '</div>';
        //$sCode.='<!-- end ztid '.$data['ZONE_TEMPLATE_ID'].' -->';
        return $sCode;
    }

    /**
     * __DESC__
     *
     * @static
     * @access public
     * @param  __TYPE__ $data __DESC__
     * @return __TYPE__
     */
    public static function getNonMoveableZoneEnd($data)
    {
        $sCode = '';
        $sCode.= '</div>';
        $sCode.= '</div>';
        //$sCode.=  '<!-- end ztid '.$data['ZONE_TEMPLATE_ID'].' -->';
        return $sCode;
    }

    /**
     * __DESC__
     *
     * @access private
     * @return __TYPE__
     */
    private function getMoveableJsCode()
    {
        $sJs = "";
        $sJs.= "<script type=\"text/javascript\" defer=\"defer\">\r\n";
        $sJs.= "<!-- \r\n";
        $sJs.= "makeMoveable('" . $this->aPage["PAGE_ID"] . "');";
        $sJs.= "\n-->\n";
        $sJs.= "</script>\r\n";

        return $sJs;
    }

    /**
     * __DESC__
     *
     * @access private
     * @param  __TYPE__ $area __DESC__
     * @return __TYPE__
     */
    private function getDroppableAreaStart($area)
    {
        $sHtml = '';
        $sHtml.= Pelican_Html::div(array(id => self::AREA_ACTIONS_PREFIX . $area['AREA_ID'], "class" => self::AREA_ACTIONS_CSS_CLASS), Pelican_Html::span(array(id => self::ZONE_ADD_PREFIX . $area['AREA_ID'], "class" => self::ZONE_ADD_CSS_CLASS . " ui-state-default portlet-header ui-widget-header ui-corner-all ui-icon ui-icon-plus", onclick => $this->getJsAddFunction($area), title => "add")));
        $sHtml.= '<div id="' . self::AREA_PREFIX . $area['AREA_ID'] . '" class="' . self::DROPPABLE_AREA_CSS_CLASS . '">';

        return $sHtml;
    }

    /**
     * __DESC__
     *
     * @access private
     * @param  __TYPE__ $area __DESC__
     * @return __TYPE__
     */
    private function getDroppableAreaEnd($area)
    {
        $sHtml = '';
        $sHtml.= '</div>';

        return $sHtml;
    }

    /**
     * DESC
     *
     * @access public
     * @param  string $tpl (option) __DESC__
     * @return void
     */
    public function generateZone($tpl = "")
    {
        $return = '';
        $oConnection = Pelican_Db::getInstance();
        if (!empty($this->tabAreas)) {
            foreach ($this->tabAreas as $area) {
                $return.= $area["AREA_HEAD"] . "\n";
                if ($area["IS_DROPPABLE"] == 1) {
                    $this->_aAllDroppableAreaId[] = $area['AREA_ID'];
                    if ($this->getUser()->isLoggedIn()) {
                        $return.= $this->getDroppableAreaStart($area);
                    }
                    if ($this->_tabZonesPerso[$area["AREA_ID"]]) {
                        foreach ($this->_tabZonesPerso[$area["AREA_ID"]] as $data) {
                            // temporaire
                            $data["ZONE_FO_PATH"] = str_replace('pageLayout', 'Layout', $data["ZONE_FO_PATH"]);
                            if (valueExists($data, "ZONE_FO_PATH")) {

                                /** output */
                                if ($data["ZONE_FO_PATH"] != "/layout") {
                                    switch ($data["ZONE_TYPE_ID"]) {
                                        case 3:
                                            /* Pelican_Index_Frontoffice_Zone héritable !!!!!!!!!!!!!!!!!! */
                                            $zoneTemplateId = $oConnection->queryItem("select zone_template_id
                                                from " . Pelican::$config['FW_PREFIXE_TABLE'] . "zone_template
                                                where template_page_id=(select template_page_id from " . Pelican::$config['FW_PREFIXE_TABLE'] . "page_version where page_id=:HOME_PAGE_ID and LANGUE_ID=:HOME_LANGUE_ID and PAGE_VERSION=:HOME_PAGE_VERSION)
                                                and zone_template_label=:ZONE_TEMPLATE_LABEL", array(":HOME_PAGE_ID" => $_SESSION[APP]["HOME_PAGE_ID"], ":HOME_PAGE_VERSION" => $_SESSION[APP]["HOME_PAGE_VERSION"], ":HOME_LANGUE_ID" => $_SESSION[APP]['LANGUE_ID'], ":ZONE_TEMPLATE_LABEL" => $oConnection->strToBind($data["ZONE_TEMPLATE_LABEL"])));
                                            $data = Pelican_Cache::fetch("Portal/Bloc", array($_SESSION[APP]["HOME_PAGE_ID"], $zoneTemplateId, $this->getUser()->get('id'), $_SESSION[APP]['LANGUE_ID'], $_SESSION[APP]["HOME_PAGE_VERSION"]));
                                        break;
                                        default:
                                            /* autres */
                                    }
                                    $return.= $this->getMoveableZone($data);
                            }
                        }
                    }
                }
                if ($this->getUser()->isLoggedIn()) {
                    $return.= $this->getDroppableAreaEnd($area);
                }
            } else {
                $return.= '<div id="' . self::AREA_PREFIX . $area['AREA_ID'] . '" class="' . self::NON_DROPPABLE_AREA_CSS_CLASS . '">';
                if ($this->tabZones[$area["AREA_ID"]]) {
                    foreach ($this->tabZones[$area["AREA_ID"]] as $data) {
                        // temporaire
                        $data["ZONE_FO_PATH"] = str_replace('pageLayout', 'Layout', $data["ZONE_FO_PATH"]);
                        if (valueExists($data, "ZONE_FO_PATH")) {

                            /** output */
                            if ($data["ZONE_FO_PATH"] != "/layout") {
                                switch ($data["ZONE_TYPE_ID"]) {
                                    case 3:
                                        /* Pelican_Index_Frontoffice_Zone h�ritable */
                                        $zoneTemplateId = $oConnection->queryItem("select zone_template_id
                                                from " . Pelican::$config['FW_PREFIXE_TABLE'] . "zone_template
                                                where template_page_id=(select template_page_id from " . Pelican::$config['FW_PREFIXE_TABLE'] . "page_version where page_id=:HOME_PAGE_ID and LANGUE_ID=:HOME_LANGUE_ID and PAGE_VERSION=:HOME_PAGE_VERSION)
                                                and zone_template_label=:ZONE_TEMPLATE_LABEL", array(":HOME_PAGE_ID" => $_SESSION[APP]["HOME_PAGE_ID"], ":HOME_PAGE_VERSION" => $_SESSION[APP]["HOME_PAGE_VERSION"], ":HOME_LANGUE_ID" => $_SESSION[APP]['LANGUE_ID'], ":ZONE_TEMPLATE_LABEL" => $oConnection->strToBind($data["ZONE_TEMPLATE_LABEL"])));
                                        $data = Pelican_Cache::fetch("Portal/Bloc", array($_SESSION[APP]["HOME_PAGE_ID"], $zoneTemplateId, "", $_SESSION[APP]['LANGUE_ID'], $_SESSION[APP]["HOME_PAGE_VERSION"]));
                                    break;
                                    default:
                                        /* autres */
                                }
                                $return.= $this->buildNonMoveableZone($data);
                        }
                    }
                }
            }
            $return.= '</div>';
        }
        $return.= $area["AREA_FOOT"] . "\n";
    }
}
$return.= $this->getMoveableJsCode();
return $return;
}

/**
 * __DESC__
 *
 * @static
 * @access public
 * @param __TYPE__ $data __DESC__
 * @return __TYPE__
 */
public static function templateEdit($data)
{
    $host = Pelican::$config["PORTAL_ADMIN_HTTP"] . "/index_front.php";
    $host = Pelican::$config["LIB_PATH"] . Pelican::$config['LIB_FRONT'] . "/portal/index.php";
    $data = "<iframe id=\"portal-layer-iframe-edit\" name=\"portal-layer-iframe-edit\" class=\"portal-layer-iframe-edit\" src=\"" . $host . "?block=" . base64_encode("tid=256&pid=" . $data["PAGE_ID"] . "&lid=" . $data['LANGUE_ID'] . "&pver=" . $data["PAGE_VERSION"] . "&ztid=" . $data["ZONE_TEMPLATE_ID"]) . "\" />";

    return $data;
}

/**
 * __DESC__
 *
 * @static
 * @access public
 * @param __TYPE__ $login __DESC__
 * @param __TYPE__ $ztid __DESC__
 * @param __TYPE__ $pageId __DESC__
 * @return __TYPE__
 */
public static function deleteUserBlock($login, $ztid, $pageId)
{
    $oConnection = Pelican_Db::getInstance();
    $aBind[":ZONE_TEMPLATE_ID"] = $ztid;
    $aBind[":PAGE_ID"] = $pageId;
    $aBind[":PORTAL_USER_ID"] = $oConnection->strToBind($login);
    $aZtInfo = $oConnection->queryRow("select area_id,zone_template_order from " . Pelican::$config['FW_PREFIXE_TABLE'] . "portal_user_zone_template where PORTAL_USER_ID=:PORTAL_USER_ID AND PAGE_ID=:PAGE_ID and zone_template_id=:ZONE_TEMPLATE_ID", $aBind);
    $aBind[":AREA_ID"] = $aZtInfo["AREA_ID"];
    $aBind[":ZONE_TEMPLATE_ORDER"] = $aZtInfo["ZONE_TEMPLATE_ORDER"];
    $oConnection->query("delete from " . Pelican::$config['FW_PREFIXE_TABLE'] . "portal_user_page_zone where PORTAL_USER_ID=:PORTAL_USER_ID AND PAGE_ID=:PAGE_ID AND ZONE_TEMPLATE_ID=:ZONE_TEMPLATE_ID", $aBind);
    $oConnection->query("delete from " . Pelican::$config['FW_PREFIXE_TABLE'] . "portal_user_zone_template where PORTAL_USER_ID=:PORTAL_USER_ID AND PAGE_ID=:PAGE_ID AND ZONE_TEMPLATE_ID=:ZONE_TEMPLATE_ID", $aBind);
    //décalage vers le bas de tous les autres blocs de la zone
    $oConnection->query("update " . Pelican::$config['FW_PREFIXE_TABLE'] . "portal_user_zone_template SET ZONE_TEMPLATE_ORDER=ZONE_TEMPLATE_ORDER-1 WHERE PORTAL_USER_ID=:PORTAL_USER_ID AND AREA_ID=:AREA_ID AND ZONE_TEMPLATE_ORDER>=:ZONE_TEMPLATE_ORDER", $aBind);
}

/**
 * __DESC__
 *
 * @static
 * @access public
 * @param __TYPE__ $ztid __DESC__
 * @param __TYPE__ $pageId __DESC__
 * @param __TYPE__ $data __DESC__
 * @return __TYPE__
 */
public static function saveUserBlock($ztid, $pageId, $data)
{
    $aBind = array();
    $oConnection = Pelican_Db::getInstance();
    $aBind[":PORTAL_USER_ID"] = $oConnection->strToBind($this->getUser()->get('id'));
    $aBind[":PAGE_ID"] = $pageId;
    $aBind[":ZONE_TEMPLATE_ID"] = $ztid;
    //_work/test d'existence
    $exists = $oConnection->queryItem("select PORTAL_USER_ID from " . Pelican::$config['FW_PREFIXE_TABLE'] . "portal_user_page_zone where PORTAL_USER_ID=:PORTAL_USER_ID and PAGE_ID=:PAGE_ID and ZONE_TEMPLATE_ID=:ZONE_TEMPLATE_ID", $aBind);
    $DBVALUES_SAVE = Pelican_Db::$values;
    Pelican_Db::$values = $data;
    Pelican_Db::$values["PORTAL_USER_ID"] = $this->getUser()->get('id');
    Pelican_Db::$values["PAGE_ID"] = $pageId;
    Pelican_Db::$values["ZONE_TEMPLATE_ID"] = $ztid;
    Pelican_Db::$values["PAGE_VERSION"] = 1;
    Pelican_Db::$values['LANGUE_ID'] = 1;
    $base = array("PORTAL_USER_ID", "PAGE_ID", "ZONE_TEMPLATE_ID", "PAGE_VERSION", 'LANGUE_ID');
    $aZoneData = array();
    foreach (Pelican_Db::$values as $formElementName => $formElement) {
        if ($formElementName == "ZONE_TEXTE") {
            $aZoneData[$formElementName] = str_replace(array(Pelican::$config["MEDIA_HTTP"], "\\'"), array(Pelican::$config["MEDIA_VAR"], "'"), $formElement);
        } elseif ($formElementName == "MEDIA_ID") {
            $aZoneData[$formElementName] = $formElement;
            $aZoneData["MEDIA_PATH"] = Pelican_Media::getMediaPath($formElement);
        } elseif (!in_array($formElementName, $base) && $formElement && $formElement != ":DATE_COURANTE") { //elseif(substr($formElementName, 0, 5) == "ZONE_") {
            $aZoneData[$formElementName] = $formElement;
        }
    }
    Pelican_Db::$values["ZONE_DATA"] = http_build_query($aZoneData);
    if ($exists) {
        $oConnection->updateTable(Pelican_Db::DATABASE_UPDATE, Pelican::$config['FW_PREFIXE_TABLE'] . "portal_user_page_zone");
    } else {
        $oConnection->updateTable(Pelican_Db::DATABASE_INSERT, Pelican::$config['FW_PREFIXE_TABLE'] . "portal_user_page_zone");
    }
    Pelican_Db::$values = $DBVALUES_SAVE;
}

/**
 * __DESC__
 *
 * @static
 * @access public
 * @param __TYPE__ $ztid __DESC__
 * @param __TYPE__ $pageId __DESC__
 * @return __TYPE__
 */
public static function decacheUserBlock($ztid, $pageId)
{
    if ($this->getUser()->isLoggedIn()) {
        Pelican_Cache::clean('Portal/Page/User', array($pageId, $this->getUser()->get("id")));
        Pelican_Cache::clean('Portal/Bloc', array($pageId, $ztid, $this->getUser()->get("id")));
    }
}

/**
 * __DESC__
 *
 * @static
 * @access public
 * @param __TYPE__ $template_page_id __DESC__
 * @return __TYPE__
 */
public static function getAvailableZoneTemplatesForPageTemplate($template_page_id)
{
    $oConnection = Pelican_Db::getInstance();
    $aBind[":TEMPLATE_PAGE_ID"] = $template_page_id;
    $oConnection->query("select zt.ZONE_TEMPLATE_ID as \"id\",
                " . $oConnection->getNvlClause("zt.ZONE_TEMPLATE_LABEL", "z.ZONE_LABEL", "") . " as \"lib\"
                from " . Pelican::$config['FW_PREFIXE_TABLE'] . "zone_template zt
                INNER JOIN " . Pelican::$config['FW_PREFIXE_TABLE'] . "template_page_area tpa on (tpa.TEMPLATE_PAGE_ID = zt.TEMPLATE_PAGE_ID AND tpa.AREA_ID=zt.AREA_ID AND IS_DROPPABLE=1)
                inner join " . Pelican::$config['FW_PREFIXE_TABLE'] . "zone z on (z.ZONE_ID=zt.ZONE_ID)
                where zt.TEMPLATE_PAGE_ID=:TEMPLATE_PAGE_ID", $aBind);
    if (!empty($oConnection->data["id"]) && !empty($oConnection->data["lib"])) {
        $aZones = array_combine($oConnection->data["id"], $oConnection->data["lib"]);
    } else {
        $aZones = array();
    }

    return $aZones;
}

/**
 * __DESC__
 *
 * @static
 * @access public
 * @param __TYPE__ $template_page_id __DESC__
 * @return __TYPE__
 */
public static function getAvailableZonesForPageTemplate($template_page_id)
{
    $oConnection = Pelican_Db::getInstance();
    $aBind[":TEMPLATE_PAGE_ID"] = $template_page_id + 0;
    $oConnection->query("select z.ZONE_ID as \"id\",
                " . $oConnection->getNvlClause("zt.ZONE_TEMPLATE_LABEL", "z.ZONE_LABEL", "") . " as \"lib\"
                from " . Pelican::$config['FW_PREFIXE_TABLE'] . "zone_template zt
                INNER JOIN " . Pelican::$config['FW_PREFIXE_TABLE'] . "template_page_area tpa on (tpa.TEMPLATE_PAGE_ID = zt.TEMPLATE_PAGE_ID AND tpa.AREA_ID=zt.AREA_ID AND IS_DROPPABLE=1)
                inner join " . Pelican::$config['FW_PREFIXE_TABLE'] . "zone z on (z.ZONE_ID=zt.ZONE_ID)
                where zt.TEMPLATE_PAGE_ID=:TEMPLATE_PAGE_ID", $aBind);
    if (!empty($oConnection->data["id"]) && !empty($oConnection->data["lib"])) {
        $aZones = array_combine($oConnection->data["id"], $oConnection->data["lib"]);
    } else {
        $aZones = array();
    }

    return $aZones;
}

/**
 * __DESC__
 *
 * @access public
 * @return __TYPE__
 */
public function getUser()
{
    if (!$this->oUser) {
        $this->oUser = Pelican_Factory::getInstance('User.Portal');
    }

    return $this->oUser;
}
}
