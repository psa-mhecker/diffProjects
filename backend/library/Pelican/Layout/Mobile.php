<?php
/**
 * __DESC__.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */
include_once pelican_path('Layout.Desktop');

/**
 * __DESC__.
 *
 * @author __AUTHOR__
 */
class Pelican_Layout_Mobile extends Pelican_Layout_Desktop
{
    /**
     * @access protected
     *
     * @var __TYPE__ __DESC__
     */
    protected $_type = 'mobile';

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $data  __DESC__
     * @param bool     $cache (option) __DESC__
     * @param string   $type  (option) __DESC__
     *
     * @return __TYPE__
     */
    public function getOutputZone($data, $cache = false, $type = "")
    {
        // pas de Pelican_Cache ou iframe ou ajax pour le mobile
        $this->response[] = $this->getDirectZone($data, false);
    }
}
