<?php
/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Layout
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */

/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Layout
 * @author __AUTHOR__
 */
class Pelican_Layout_Desktop
{
    /**
     * @access protected
     * @var __TYPE__ __DESC__
     */
    protected $_tpl = '';

    /**
     * @access protected
     * @var __TYPE__ __DESC__
     */
    protected $_pid = '';

    /**
     * @access protected
     * @var __TYPE__ __DESC__
     */
    protected $_cid = '';

    /**
     * @access protected
     * @var __TYPE__ __DESC__
     */
    protected $_ajax = '';

    /**
     * @access protected
     * @var __TYPE__ __DESC__
     */
    protected $_iframe = '';

    /**
     * @access protected
     * @var __TYPE__ __DESC__
     */
    protected $_type = 'desktop';

    /**
     * Constructeur
     *
     * @access public
     * @param  __TYPE__ $aPage __DESC__
     * @return __TYPE__
     */
    public function __construct($aPage)
    {
        $this->aPage = $aPage;
        if (!empty($_GET["pid"])) {
            $this->_pid = $_GET["pid"];
        }
        if (!empty($_GET["tpl"])) {
            $this->_tpl = $_GET["tpl"];
        }
        if (!empty($_GET["cid"])) {
            $this->_cid = $_GET["cid"];
        }
        if (!empty($_GET["preview"])) {
            $this->preview = $_GET["preview"];
        }
        if (!empty($_GET["ajax"])) {
            $this->_ajax = $_GET["ajax"];
        }
        if (!empty($_GET["iframe"])) {
            $this->_iframe = $_GET["iframe"];
        }
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
        	
            $return = Pelican_Cache::fetch("Frontend/Page/Zone", array($this->_pid, $_SESSION[APP]['LANGUE_ID'], Pelican::getPreviewVersion(), $this->_type));
		
            $this->tabAreas = $return["areas"];
            $this->tabZones = $return["zones"];
			if($this->_cid !="" && $this->_pid !=""){
				$this->_tpl="";
			}
            /** si un contenu est défini */
            if ($this->_tpl) {
            	
                $template = Pelican_Cache::fetch("Template", $this->_tpl);
             
                $this->aTemplate = $template[0];
                if ($this->aTemplate["TEMPLATE_PATH_FO"]) {
                    // Génère la Pelican_Index_Frontoffice_Zone du contenu
                    $this->getModuleResponse($this->aTemplate["TEMPLATE_PATH_FO"], $this->_type);
                } else {
                   Pelican_Request::getInstance()->sendError(404);
                   // $this->sendError(404);
                }
            } else {
            	
                $this->getModuleResponse('');
            }
        }
        
    
        if (is_array($this->response)) {
            return implode("\n", $this->response);
        }
    }

    /**
     * DESC
     *
     * @access public
     * @param  string $tpl (option) __DESC__
     * @return void
     */
    public function getModuleResponse($tpl = "")
    {
        /** cas d'un template imposé par un contenu ou par le paramètre tpl*/
        if ($tpl) {
            $tabAreas = $this->tabAreas;
            $tabZones = $this->tabZones;
            $template_contenu = Pelican_Cache::fetch("Template/Content", $_SESSION[APP]['SITE_ID']);
            $return = Pelican_Cache::fetch("Frontend/Template_Page", array($template_contenu["TEMPLATE_PAGE_ID"], $this->_type));
            $this->tabAreas = $return["areas"];
            $this->tabZones = $return["zones"];
            foreach ($this->tabAreas as $area => $tmp) {
                if ($this->tabZones[$this->tabAreas[$area]["AREA_ID"]]) {
                    foreach ($this->tabZones[$this->tabAreas[$area]["AREA_ID"]] as $data => $values) {
                        if ($values["ZONE_CONTENT"]) {
                            $this->tabZones[$this->tabAreas[$area]["AREA_ID"]][$data]["ZONE_FO_PATH"] = $tpl;
                        }
                    }
                }
            }
        }
        if ($this->tabAreas && $this->tabZones) {
            $i = 0;
            foreach ($this->tabAreas as $area) {
                ++$i;
                $this->response[] = $area["AREA_HEAD"] . "\n";
                if ($this->tabZones[$area["AREA_ID"]]) {
                    foreach ($this->tabZones[$area["AREA_ID"]] as $data) {
                        // temporaire
                        $data["ZONE_FO_PATH"] = str_replace('pageLayout', 'Layout', $data["ZONE_FO_PATH"]);

                        /** zones héritables */
                        if ($data["ZONE_TYPE_ID"] == 3) {
                            $savePath = $data["ZONE_FO_PATH"];
                            if (!$data["PAGE_ID"]) {
                                $data["PAGE_ID"] = $this->_pid;
                                $data['LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
                            }
                            $data = Pelican_Cache::fetch("Frontend/Page/Heritable", array("heritable", $data["PAGE_ID"], $data["ZONE_TEMPLATE_ID"], Pelican::getPreviewVersion(), $_SESSION[APP]['LANGUE_ID']));
                            $data["ZONE_FO_PATH"] = $savePath;
                            $savePath = "";
                        }
                        if (Pelican::$config["SHOW_DEBUG"]) {
                            $this->response[] = Pelican_Html::comment("Bloc " . $data["ZONE_TEMPLATE_ID"] . " : " . $data["ZONE_FO_PATH"]) . "\n";
                        } else {
                            $this->response[] = Pelican_Html::comment("Bloc " . $data["ZONE_TEMPLATE_ID"]) . "\n";
                        }
                        if (!empty($data["ZONE_FO_PATH"])) {
                            // plugin
                            $data = Pelican_Layout::identifyPlugins($data);
                            if (empty($data["ZONE_CACHE_TIME"]) || !empty($this->preview)) {
                                $cache = false;
                            } else {
                                $cache = Pelican::$config["ENABLE_CACHE_SMARTY"];
                            }
                            if (valueExists($this->aPage, "PAGE_TITLE")) {
                                $data["PAGE_TITLE"] = $this->aPage["PAGE_TITLE"];
                            }
                            if (valueExists($this->aPage, "PAGE_SUBTITLE")) {
                                $data["PAGE_SUBTITLE"] = $this->aPage["PAGE_SUBTITLE"];
                            }

                            /** temporaire */
                            if ($this->_ajax) {
                                $data["ZONE_AJAX"] = true;
                            }
                            if ($this->_iframe) {
                                $data["ZONE_IFRAME"] = true;
                            }

                            /** output */
                            //if ($data["ZONE_FO_PATH"] != "Layout") {
                            $this->recapZone[] = $data["ZONE_FO_PATH"];
                            $time0 = microtime(TRUE);
                            if ($data["ZONE_IFRAME"]) {
                                $this->getOutputZone($data, $cache, 'iframe');
                            } elseif ($data["ZONE_AJAX"]) {
                                $this->getOutputZone($data, $cache, 'ajax');
                            } else {
                                $this->getOutputZone($data, $cache);
                            }
                            $time = microtime(TRUE);
                            Pelican_Log::control(sprintf(PROFILE_FORMAT_TIME, ($time - $time0)) . ' : ' . $data["ZONE_FO_PATH"], 'generation');
                        }
                    }
                }
                $this->response[] = $area["AREA_FOOT"] . "\n";
            }
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @param  __TYPE__ $data  __DESC__
     * @param  bool     $cache (option) __DESC__
     * @param  string   $type  (option) __DESC__
     * @return __TYPE__
     */
    public function getOutputZone($data, $cache = false, $type = "")
    {
        switch ($type) {
            case "ajax": {
                        $this->response[] = $this->getAjaxZone($data, $cache);
                    break;
                }
            case "iframe": {
                    $this->response[] = $this->getIframeZone($data, $cache);
                    break;
                }
            default: {
                    $this->response[] = $this->getDirectZone($data, $cache);
                    break;
                }
            }
        }

        /**
         * __DESC__
         *
         * @access public
         * @param __TYPE__ $data __DESC__
         * @param bool $cache (option) __DESC__
         * @return __TYPE__
         */
        public Function getDirectZone($data, $cache = false) {
            return Pelican_Request::cachedCall(trim($data["ZONE_FO_PATH"]), $data, $cache, $data["ZONE_CACHE_TIME"]);
        }

        /**
         * __DESC__
         *
         * @access public
         * @param __TYPE__ $data __DESC__
         * @param bool $cache (option) __DESC__
         * @return __TYPE__
         */
        public function getIframeZone($data, $cache = false)
        {
            $_SESSION[APP]['ajax_' . $data["ZONE_TEMPLATE_ID"]] = $data;
            $_SESSION[APP]['ajax_get'] = $_GET;
            $this->response[] = Pelican_Html::iframe(array(src => '/_/Index/iframeZone/?zone=' . $data["ZONE_TEMPLATE_ID"], name => "iframe_" . $data["ZONE_TEMPLATE_ID"], frameborder => "0", scrolling => "no", width => "100%", height => "100%"));
        }

        /**
         * __DESC__
         *
         * @access public
         * @param __TYPE__ $data __DESC__
         * @param bool $cache (option) __DESC__
         * @return __TYPE__
         */
        public function getAjaxZone($data, $cache = false)
        {
            $_SESSION[APP]['ajax_' . $data["ZONE_TEMPLATE_ID"]] = $data;
            $_SESSION[APP]['ajax_get'] = $_GET;
            $_SESSION[APP]['ajax_post'] = $_POST;
            $ajaxJsLoading = Pelican_Factory::staticCall(Pelican::getAjaxEngine(), 'getJsLoading');
            $this->response[] = Pelican_Html::span(array(id => "ajax_" . $data["ZONE_TEMPLATE_ID"]), Pelican_Html::script($ajaxJsLoading . "('ajax_" . $data["ZONE_TEMPLATE_ID"] . "', '&nbsp;');
                callAjax('/_/Index/ajaxZone','" . $data["ZONE_TEMPLATE_ID"] . "','" . $cache . "');"));
        }

        /**
         * Récupération de l'objet Vue
         *
         * @access public
         * @return Pelican_View
         */
        public function getView()
        {
            if ($this->view == null) {
                $this->view = & Pelican_Factory::getInstance('View');
            }

            return $this->view;
        }
    }
