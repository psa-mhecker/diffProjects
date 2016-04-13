<?php
/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Controller
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */
pelican_import('Controller');

/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Controller
 * @author __AUTHOR__
 */
class Pelican_Controller_Front extends Pelican_Controller {
    
    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function ajaxZoneAction() {
        pelican_import('Layout.Desktop');
        $zoneTemplateId = $this->getParam(0);
        $cache = $this->getParam(1);
        $_GET = $_SESSION[APP]['ajax_get'];
        $_POST = $_SESSION[APP]['ajax_post'];
        $zone = $_SESSION[APP]['ajax_' . $zoneTemplateId];
        
        /**
         * nettoyage de la session
         */
        unset($_SESSION[APP]['ajax_' . $zoneTemplateId]);
        $output = Pelican_Layout_Desktop::getDirectZone($zone, $cache);
        $this->getRequest()->addResponseCommand('assign', array('id' => "ajax_" . $zoneTemplateId, 'attr' => 'innerHTML', 'value' => $output));
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function iframeZoneAction() {
        pelican_import('Layout.Desktop');
        $zone = $_SESSION[APP]['ajax_' . $_GET["zone"]];
        $_GET = $_SESSION[APP]['ajax_get'];
        if (!empty($_GET["preview"])) {
            $this->preview = $_GET["preview"];
        }
        if (empty($zone["ZONE_CACHE_TIME"]) || !empty($this->preview)) {
            $cache = false;
        } else {
            $cache = Pelican::$config["ENABLE_CACHE_SMARTY"];
        }
        unset($_SESSION[APP]['ajax_' . $zoneTemplateId]);
        $output = Pelican_Layout_Desktop::getDirectZone($zone, $cache);
        $this->setResponse($output);
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function beforeFetch() {
        $this->assignData();
    }
    
    /**
     * __DESC__
     *
     * @access protected
     * @return __TYPE__
     */
    protected function model() {
        $this->assign("template", $this->getTemplate());
        $this->setTemplate(Pelican::$config['APPLICATION_VIEWS'] . '/Layout/Model/bloc.tpl');
    }
    
    /**
     * __DESC__
     *
     * @access protected
     * @return __TYPE__
     */
    protected function assignData() {
        $data = $this->getParams();
        $this->assign("zone_title", (!empty($data['ZONE_TITRE']) ? $data['ZONE_TITRE'] : ''));
        $this->assign("data", $data);
    }
    
    /**
     * __DESC__
     *
     * @access protected
     * @param __TYPE__ $list __TYPE__ __DESC__
     * @return __TYPE__
     */
    protected function listModel($list) {
        $this->model();
        $this->setTemplate(Pelican::$config['APPLICATION_VIEWS'] . '/Layout/Model/list.tpl');
        $this->assign("list", $list);
    }
}
