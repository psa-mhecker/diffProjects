<?php
/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Controller
 * @author __AUTHOR__
 */
include_once(dirname(__FILE__) . '.php');

/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Controller
 * @author __AUTHOR__
 */
class Pelican_Controller_External_Magento extends Pelican_Controller_External
{

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function magentoAction()
    {
        $url = $this->getParam('url');
    }
}